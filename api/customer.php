<?php

    //********************Get Customer functionality*****************//
    $app->get('/getCustomers', function() use ($app){
        $db = new DbHandler();
        $data = $db->getData(" SELECT cust.id,cust.cust_barcode_num,cust.cust_id,cust.fname,
        cust.mname,cust.lname, comp.comp_name FROM customerdetail cust
        left join companydetail comp on comp.comp_id = cust.comp_id where cust.status = 1");
        echo json_encode($data);
    });

    //********************Add Customer functionality*****************//
    $app->post('/addCustomer', function() use ($app){
        $postdata = file_get_contents("php://input");
        $r = json_decode($postdata);
        verifyRequiredParams(array('barcode','cust_id','fname','mname','lname','comp_id'),$r->customer);
        $response = array();
        $db = new DbHandler();
        $barcode = $r->customer->barcode;
        $cust_id = $r->customer->cust_id;
        $fname = $r->customer->fname;
        $mname = $r->customer->mname;
        $lname = $r->customer->lname;
        $comp_id = $r->customer->comp_id;
        date_default_timezone_set('asia/taipei');
        $currentDateTime = new DateTime('now');
        $currentDate = $currentDateTime->format('Y-m-d H:i:s');

        $isUserExists = $db->getOneRecord("SELECT * from customerdetail where cust_barcode_num='".$barcode."'");
        if(!$isUserExists){
            $db->insertRecord(" INSERT INTO customerdetail(`cust_barcode_num`, `cust_id`, `lname`, `fname`, `mname`, `comp_id`, `added_date`, `added_by`) 
            VALUES ('".$barcode."','".$cust_id."','".$lname."','".$fname."','".$mname."','".$comp_id."','".$currentDate."','".$currentDate."') ");
          
            $response["status"] = "success";
            $response["message"] = "Successfully created";
            echoResponse(200, $response);
                  
        }else{
            $response["status"] = "error";
            $response["message"] = "An barcode with the provided series exists!";
            echoResponse(201, $response);
        }
    });

    //********************Get Customer by Id functionality*****************//
    $app->get('/getCustomer/:id', function($id) use ($app){
        $db = new DbHandler();
        $data = $db->getData(" SELECT * FROM customerdetail where id = '".$id."' ");
        echo json_encode($data);
    });

    //********************Update User functionality*****************//
    $app->put('/updateCustomer/:id', function($id) use ($app){
        $response = array();
        $r = json_decode($app->request->getBody());
        verifyRequiredParams(array('cust_barcode_num','cust_id','fname','mname','lname','comp_id'),$r->customer);
        $db = new DbHandler();
        $cust_barcode_num = $r->customer->cust_barcode_num;
        $cust_id = $r->customer->cust_id;
        $fname = $r->customer->fname;
        $mname = $r->customer->mname;
        $lname = $r->customer->lname;
        $comp_id = $r->customer->comp_id;

        $result = $db->updateRecord(" UPDATE customerdetail 
            set `cust_barcode_num` = '".$cust_barcode_num."', `cust_id` = '".$cust_id."', 
            `lname` = '".$lname."', `fname` = '".$fname."', 
            `mname` = '".$mname."', `comp_id` = ".$comp_id." WHERE id = '".$id."' "); 

        if($result != NULL)
        { 
            $response["status"] = "success";
            $response["message"] = "Customer information updated successfully";
            echoResponse(200, $response);
        }
        else
        {
            $response["status"] = "error";
            $response["message"] = "Failed to update charge. Please try again";
            echoResponse(201, $response);
        }
    });

    //********************Delete User functionality*****************//
    $app->get('/deleteCustomer/:id', function($id) use ($app){
        $db = new DbHandler();
        $result = $db->deleteRecord("UPDATE customerdetail SET status = 0 where id = '$id' ");
        if($result != NULL) { 
            $response["status"] = "success";
            $response["message"] = "Customer remove successfully";
            echoResponse(200, $response);
        } else {
            $response["status"] = "error";
            $response["message"] = "Failed to remove customer. Please try again";
            echoResponse(201, $response);
        }
    });
    
?>