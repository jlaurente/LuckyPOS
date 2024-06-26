<?php

	//*******************Enter Company ID functionality*****************//
	$app->post('/search_compId', function() use ($app){
	    $r = json_decode($app->request->getBody());
        verifyRequiredParams(array('comp_id'),$r->company);
        $response = array();
        $db = new DbHandler();
        $comp_id = $r->company->comp_id;

        $IsCompanyExist = $db->getOneRecord(" SELECT * from companydetail WHERE comp_id ='$comp_id'");
       	
       	if($IsCompanyExist){
       		$result = $db->getData(" SELECT pay.comp_id, pay.due_date,
       		comp.comp_name FROM payment pay 
       		LEFT JOIN companydetail comp on pay.comp_id = comp.comp_id 
       		WHERE pay.comp_id ='$comp_id' group by pay.comp_id");
			echo json_encode($result);
       	}else{
   			$response["status"] = "error";
         	$response["message"] = "Company ID NOT FOUND. Please try again";
         	echoResponse(201, $response);
       	}
	});


	//********************Add Adjustment Payment functionality*****************//
	$app->post('/addAdjustmentPayment', function() use ($app){
		$postdata = file_get_contents("php://input");
		$r = json_decode($postdata);
		$db = new DbHandler();
		$trxn_id = $r->trxn_id;
		$cust_id = $r->cust_id;
		$comp_id = $r->comp_id;
		$type = $r->type; 
		$credit_amount = $r->amount;
		$reason = $r->reason;
		$debit_amount = $r->amount;
		$debit_amounts = $debit_amount*-1;
		date_default_timezone_set('asia/taipei');
		$currentDateTime = new DateTime('now');
		$added_date = $currentDateTime->format('Y-m-d H:i:s');
		if (!isset($_SESSION)) {
		   session_start();
		}
		$name =  $_SESSION['name'];

		$istransactionExists = $db->getOneRecord("SELECT id,cust_id_no,added_date,due_date from transactiondetailcredit 
		where id = '".$trxn_id."' and cust_id_no = '".$cust_id."' and comp_id = '".$comp_id."'");
    	if($istransactionExists){
    		if($type == "Credit"){
		    	if(floatval($credit_amount) < 0){
					$response["status"] = "error";
		         	$response["message"] = "Amount adjustment must be positive number. Please try again";
		         	echoResponse(201, $response);
				}else{
		    		$result = $db->insertRecord(" INSERT INTO adjustment(cust_id_no, comp_id, adjtype, amount,trxn_date, due_date, reason, added_date, added_by,type,ref_id) VALUES ('".$cust_id."','".$comp_id."','".$type."','".$credit_amount."',
		    			'".$istransactionExists['added_date']."',
		    			'".$istransactionExists['due_date']."','".$reason."','".$added_date."','".$name."','Credit Order','".$trxn_id."') ");
		 		
			 		if($result != NULL) { 
			            $response["status"] = "success";
				        $response["message"] = "Successfully encoded adjustment.";
				        echoResponse(200, $response);  
		        	} else {
			            $response["status"] = "error";
			            $response["message"] = "Failed to encoded adjustment. Please try again";
			            echoResponse(201, $response);
		        	}
		        }
		    }else{
		    	if(floatval($debit_amount) < 0){
					$response["status"] = "error";
		         	$response["message"] = "Amount adjustment must be positive number. Please try again";
		         	echoResponse(201, $response);
				}else{
					$result = $db->insertRecord(" INSERT INTO adjustment(cust_id_no, comp_id, adjtype, amount,trxn_date, due_date, reason, added_date, added_by,type,ref_id) VALUES ('".$cust_id."','".$comp_id."','".$type."','".$debit_amounts."',
		    			'".$istransactionExists['added_date']."',
		    			'".$istransactionExists['due_date']."','".$reason."','".$added_date."','".$name."','Credit Order','".$trxn_id."') ");

			 		if($result != NULL) { 
			            $response["status"] = "success";
				        $response["message"] = "Successfully encoded adjustment.";
				        echoResponse(200, $response);  
		        	} else {
			            $response["status"] = "error";
			            $response["message"] = "Failed to encoded adjustment. Please try again";
			            echoResponse(201, $response);
		        	}
		        }
		    }
	    }else{
            $response["status"] = "error";
            $response["message"] = "Transaction ID NOT FOUND. Please try again";
            echoResponse(201, $response);
        }

	});

	//********************Add Adjustment Payment functionality*****************//
	$app->post('/CompanyPaymentAdjustment', function() use ($app){
		$postdata = file_get_contents("php://input");
		$r = json_decode($postdata);
		$db = new DbHandler();
		$trxn_id = $r->trxn_id;
		$comp_id = $r->comp_id;
		$type = $r->type; 
		$credit_amount = $r->amount;
		$reason = $r->reason;
		$debit_amount = $r->amount;
		$debit_amounts = $debit_amount*-1;
		date_default_timezone_set('asia/taipei');
		$currentDateTime = new DateTime('now');
		$added_date = $currentDateTime->format('Y-m-d H:i:s');
		if (!isset($_SESSION)) {
		   session_start();
		}
		$name =  $_SESSION['name'];

		$istransactionExists = $db->getOneRecord(" SELECT id, comp_id, due_date,added_date 
		from payment where id = '".$trxn_id."'  and comp_id = '".$comp_id."' ");
    	if($istransactionExists){
    		if($type == "Credit"){
		    	if(floatval($credit_amount) < 0){
					$response["status"] = "error";
		         	$response["message"] = "Amount adjustment must be positive number. Please try again";
		         	echoResponse(201, $response);
				}else{
		    		$result = $db->insertRecord(" INSERT INTO adjustment(cust_id_no, comp_id, adjtype, amount,trxn_date, due_date, reason, added_date, added_by,type,ref_id) VALUES ('','".$comp_id."','".$type."','".$credit_amount."',
		    			'".$istransactionExists['added_date']."',
		    			'".$istransactionExists['due_date']."','".$reason."','".$added_date."','".$name."','Payment','".$trxn_id."') ");
		 		
			 		if($result != NULL) { 
			            $response["status"] = "success";
				        $response["message"] = "Successfully encoded adjustment.";
				        echoResponse(200, $response);  
		        	} else {
			            $response["status"] = "error";
			            $response["message"] = "Failed to encoded adjustment. Please try again";
			            echoResponse(201, $response);
		        	}
		        }
		    }else{
		    	if(floatval($debit_amount) < 0){
					$response["status"] = "error";
		         	$response["message"] = "Amount adjustment must be positive number. Please try again";
		         	echoResponse(201, $response);
				}else{
					$result = $db->insertRecord(" INSERT INTO adjustment(cust_id_no, comp_id, adjtype, amount,trxn_date, due_date, reason, added_date, added_by,type,ref_id) VALUES ('','".$comp_id."','".$type."','".$debit_amounts."',
		    			'".$istransactionExists['added_date']."',
		    			'".$istransactionExists['due_date']."','".$reason."','".$added_date."','".$name."','Payment','".$trxn_id."') ");

			 		if($result != NULL) { 
			            $response["status"] = "success";
				        $response["message"] = "Successfully encoded adjustment.";
				        echoResponse(200, $response);  
		        	} else {
			            $response["status"] = "error";
			            $response["message"] = "Failed to encoded adjustment. Please try again";
			            echoResponse(201, $response);
		        	}
		        }
		    }
	    }else{
            $response["status"] = "error";
            $response["message"] = "Payment ID NOT FOUND. Please try again";
            echoResponse(201, $response);
        }

	});					
?>