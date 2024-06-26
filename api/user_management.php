<?php

	//********************login functionality*****************//
    $app->post('/login', function() use ($app) {
        $r = json_decode($app->request->getBody());
        verifyRequiredParams(array('username', 'password'),$r->customer);
        $response = array();
        $arr = array();
        $db = new DbHandler();
        $password = $r->customer->password;
        $username = $r->customer->username;
	    $user = $db->getOneRecord(" SELECT usd.role_id,usd.username,usd.password,usd.fname,usd.lname,usr.role 
	    from userdetail usd left join userrole usr on usr.role_id = usd.role_id
	    WHERE usd.username = '".$username."' and usd.status = 1");
        if ($user != NULL) {
            if($user['password']==$password){
                $response['status'] = "success";
                $response['message'] = 'Logged in successfully.';
                $response['role'] = $user['role'];
                $response['uid'] = $user['role_id'];
                $response['name'] = $user['fname'];

                array_push($arr, (object)[
				        'key1' => $user['role'],
				        'key2' => $user['role_id'],
				        'key3' => $user['fname'],
				]);
                if (!isset($_SESSION)) {
                    session_start();
                }
                $_SESSION['uid'] = $user['role_id'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['name'] = $user['fname'];
            } else {
                $response['status'] = "error";
                $response['message'] = 'Login failed. Incorrect credentials';
            }
        }else {
                $response['status'] = "error";
                $response['message'] = 'No such user is registered';
            }
        echoResponse(200, $response);
    });

    //********************Get Users functionality*****************//
	$app->get('/getRole', function() use ($app){
		$db = new DbHandler();
		if (!isset($_SESSION)) {
	        session_start();
	    }
	    $uid =  $_SESSION['uid'];
	    $data = $db->getData(" SELECT * FROM userrole ");
    	echo json_encode($data);
	});

    //********************Get Users functionality*****************//
	$app->get('/getUsers', function() use ($app){
		$db = new DbHandler();
	    $data = $db->getData(" SELECT usd.id,usd.username,usd.password,usd.fname,usd.mname,usd.lname,usr.role from userdetail usd left join userrole usr on usr.role_id = usd.role_id where usd.status = 1 ");
    	echo json_encode($data);
	});

    //********************Add User functionality*****************//
	$app->post('/addUser', function() use ($app){
		$postdata = file_get_contents("php://input");
	    $r = json_decode($postdata);
	    verifyRequiredParams(array('fname','mname','lname','username','password','role_id'),$r->customer);
	    $response = array();
	    $db = new DbHandler();
	    date_default_timezone_set('asia/taipei');
	    $currentDateTime = new DateTime('now');
        $currentDate = $currentDateTime->format('Y-m-d H:i:s');
	    $fname = $r->customer->fname;
	    $mname = $r->customer->mname;
	    $lname = $r->customer->lname;
	    $username = $r->customer->username;
	    $password = $r->customer->password;
	    $role_id = $r->customer->role_id;
	    if (!isset($_SESSION)) {
	        session_start();
	    }
	    $name =  $_SESSION['name'];
	    $isUserExists = $db->getOneRecord("SELECT * from userdetail where username='$username'");
        if(!$isUserExists){
            $result = $db->insertRecord(" INSERT INTO userdetail(fname,lname,mname,username,password,role_id,added_date,added_by) 
            VALUES ('".$fname."','".$lname."','".$mname."','".$username."','".$password."','".$role_id."','".$currentDate."','".$name."') ");
            $response["status"] = "success";
            $response["message"] = $fname. "User account created successfully";
            echoResponse(200, $response);           
        }else{
            $response["status"] = "error";
            $response["message"] = "An users with the provided username exists!";
            echoResponse(201, $response);
        }
	});

    //********************Get User by ID functionality*****************//        
	$app->get('/getUser/:id', function($id) use ($app){
	    $db = new DbHandler();
	    $user = $db->getData("SELECT usd.id,usd.username,usd.password,usd.fname,usd.mname,usd.lname,usr.role,usr.role_id from userdetail usd left join userrole usr on usr.role_id = usd.role_id where usd.id = '$id' ");
        echo json_encode($user);
	});

	//********************Delete User functionality*****************//
	$app->get('/deleteUser/:id', function($id) use ($app){
	    $db = new DbHandler();
	    $result = $db->deleteRecord("UPDATE userdetail SET status = 0 where id = '$id' ");
	    if($result != NULL) { 
	        $response["status"] = "success";
	        $response["message"] = "User deleted successfully";
	        echoResponse(200, $response);
	    } else {
	        $response["status"] = "error";
	        $response["message"] = "Failed to delete user. Please try again";
	        echoResponse(201, $response);
	    }
	});
	
	//********************Update User functionality*****************//
	$app->put('/updateUser/:id', function($id) use ($app){
	    $response = array();
	    $r = json_decode($app->request->getBody());
	    verifyRequiredParams(array('fname','mname','lname','username','password','role_id'),$r->customer);
	    $db = new DbHandler();
	    $fname = $r->customer->fname;
	    $mname = $r->customer->mname;
	    $lname = $r->customer->lname;
	    $username = $r->customer->username;
	    $password = $r->customer->password;
	    $role_id = $r->customer->role_id;

	    $result = $db->updateRecord(" UPDATE userdetail SET 
    	fname = '".$fname."', lname = '".$lname."', mname = '".$mname."', username = '".$username."',
    	password = '".$password."', role_id = '".$role_id."' WHERE id = '$id' ");

	    if($result != NULL)
	    { 
	        $response["status"] = "success";
	        $response["message"] = "User information updated successfully";
	        echoResponse(200, $response);
	    }
	    else
	    {
	        $response["status"] = "error";
	        $response["message"] = "Failed to update charge. Please try again";
	        echoResponse(201, $response);
	    }
	});

?>