<?php


    //********************Get Items functionality*****************//
	$app->get('/getCompany', function() use ($app){
		$db = new DbHandler();
	    $data = $db->getData(" SELECT * FROM companydetail");
    	echo json_encode($data);
	});

    //********************Add Items functionality*****************//
	$app->post('/addCompany', function() use ($app){
	 	$r = json_decode($app->request->getBody());
        verifyRequiredParams(array('comp_name', 'comp_id', 'amount_limit'),$r->customer);
        $response = array();
        $db = new DbHandler();
        date_default_timezone_set('asia/taipei');
        $currentDateTime = new DateTime('now');
        $currentDate = $currentDateTime->format('Y-m-d H:i:s');
        $comp_name = $r->customer->comp_name;
        $comp_id = $r->customer->comp_id;
        $amount_limit = $r->customer->amount_limit;
	    $date = date('Y-m-d');
	     if (!isset($_SESSION)) {
            session_start();
        }
        $name =  $_SESSION['name'];
	    $isUserExists = $db->getOneRecord("SELECT * from companydetail where comp_name='$comp_name'");
        if(!$isUserExists){
            $db->insertRecord(" INSERT INTO companydetail(comp_name, comp_id, amount_limit, added_date, added_by) 
            VALUES ('".$comp_name."','".$comp_id."','".$amount_limit."','".$currentDate."','".$name."') ");

            $response["status"] = "success";
            $response["message"] = "Successfully created";
            echoResponse(200, $response);       
        }else{
            $response["status"] = "error";
            $response["message"] = "An item with the provided brand exists!";
            echoResponse(201, $response);
        }
	    
	});

    //********************Get Company by Id functionality*****************//
    $app->get('/getCompany/:id', function($id) use ($app){
        $db = new DbHandler();
        $data = $db->getData(" SELECT * FROM companydetail where id = '".$id."' ");
        echo json_encode($data);
    });

    //********************Get Update Company functionality*****************//
    $app->put('/updateCompany/:id', function($id) use ($app){
        $response = array();
        $r = json_decode($app->request->getBody());
        verifyRequiredParams(array('comp_name','comp_id','amount_limit'),$r->customer);
        $db = new DbHandler();
        $comp_name = $r->customer->comp_name;
        $comp_id = $r->customer->comp_id;
        $amount_limit = $r->customer->amount_limit;

        $stmt = $db->conn->prepare("UPDATE companydetail SET 
        comp_name='".$comp_name."',comp_id='".$comp_id."',amount_limit='".$amount_limit."'
        WHERE id = '$id' ");
        $result = $stmt->execute();

        if($result != NULL)
        { 
            $response["status"] = "success";
            $response["message"] = "Company information updated successfully";
            echoResponse(200, $response);
        }
        else
        {
            $response["status"] = "error";
            $response["message"] = "Failed to update charge. Please try again";
            echoResponse(201, $response);
        }
    });

    //********************Delete Company functionality*****************//
    $app->get('/deleteCompany/:id', function($id) use ($app){
        $db = new DbHandler();
        $result = $db->deleteRecord("DELETE FROM companydetail where id = '".$id."' ");
        if($result != NULL)
        { 
            $response["status"] = "success";
            $response["message"] = "User deleted successfully";
            echoResponse(200, $response);
        }
        else
        {
            $response["status"] = "error";
            $response["message"] = "Failed to delete user. Please try again";
            echoResponse(201, $response);
        }
    });

?>