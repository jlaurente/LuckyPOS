<?php
	
 	//*******************Scan Barcode functionality*****************//
	$app->post('/search_account', function() use ($app){
	    $r = json_decode($app->request->getBody());
        verifyRequiredParams(array('barcode'),$r->customer);
        $response = array();
        $db = new DbHandler();
        $barcode = $r->customer->barcode;
        // $checking = $db->getCountRecord(" SELECT count(*) from customerdetail 
        // where cust_barcode_num = '".$barcode."' ");

        $checking = $db->getCountRecord(" SELECT count(*) as cnt from customerdetail 
        	WHERE cust_barcode_num ='$barcode' ");
       	
       	if($checking['cnt'] == 1){
       		$result = $db->getData(" SELECT cust.cust_barcode_num, cust.cust_id, cust.lname, cust.fname, 
			cust.mname, cust.comp_id, comp.comp_name FROM customerdetail cust
			LEFT JOIN companydetail comp on comp.comp_id = cust.comp_id 
			where cust.cust_barcode_num = '".$barcode."' ");
			echo json_encode($result);
       	}else if($checking['cnt'] > 1){
       		$data = $db->getData(" SELECT cust.id, cust.cust_barcode_num, cust.cust_id, cust.lname, cust.fname, 
			cust.mname, cust.comp_id, comp.comp_name FROM customerdetail cust
			LEFT JOIN companydetail comp on comp.comp_id = cust.comp_id 
			where cust.cust_barcode_num = '".$barcode."' ");

       		$result = array('cust'=>$data,'barcodecnt'=>$checking['cnt']);
			echo json_encode($result);
       	}else{
   			$response["status"] = "error";
         	$response["message"] = "Error! No barcode found. Please try again";
         	echoResponse(201, $response);
       	}
	});

	//*******************Scan Barcode functionality*****************//
	$app->post('/search_barcode', function() use ($app){
	    $postdata = file_get_contents("php://input");
	    $r = json_decode($postdata);
        $db = new DbHandler();
	    $id = $r->id;
        $barcode = $r->barcode;

   		$result = $db->getData(" select cust.cust_barcode_num, cust.cust_id, cust.lname, cust.fname, 
		cust.mname, cust.comp_id, comp.comp_name FROM customerdetail cust
		LEFT JOIN companydetail comp on comp.comp_id = cust.comp_id 
		where cust.id = '".$id."' and cust.cust_barcode_num = '".$barcode."'");
		echo json_encode($result);     	
	});

	//********************Add Cash Payment functionality*****************//
	$app->post('/addCashPayment', function() use ($app){
		$postdata = file_get_contents("php://input");
		$r = json_decode($postdata);
		$db = new DbHandler();
		$amount_pay = $r->amount_pay;
		$cash_amount = $r->cash_amount;
		date_default_timezone_set('asia/taipei');
		$currentDateTime = new DateTime('now');
		$currentDate = $currentDateTime->format('Y-m-d H:i:s');
		if (!isset($_SESSION)) {
		   session_start();
		}
		$name =  $_SESSION['name'];
		if(floatval($cash_amount) <= floatval($amount_pay)){
			$response["status"] = "error";
         	$response["message"] = "Cash Amount is LESS than Amount to Pay. Please try again";
         	echoResponse(201, $response);
		}else{
		    if(floatval($amount_pay) <= 0){
				$response["status"] = "error";
	         	$response["message"] = "Amount is less than ZERO. Please try again";
	         	echoResponse(201, $response);
			}else{
				$result = $db->insertRecord(" INSERT INTO transactiondetailcash(amount, added_date, added_by) 
		      VALUES ('".$amount_pay."','".$currentDate."','".$name."') ");

		      if($result != NULL) { 
		        $response["status"] = "success";
		        $response["message"] = "Successfully encoded payment";
		        echoResponse(200, $response);  
		      } else {
		         $response["status"] = "error";
		         $response["message"] = "Failed to encoded payment. Please try again";
		         echoResponse(201, $response);
		     }   
			}
		}
	});

    //********************Add Credit Payment functionality*****************//
	$app->post('/addCreditPayment', function() use ($app){
		$postdata = file_get_contents("php://input");
		$r = json_decode($postdata);
		$db = new DbHandler();
		$cust_id = $r->cust_id;
		$comp_id = $r->comp_id;
		$amount_pay = $r->amount_pay;
		date_default_timezone_set('asia/taipei');
		$currentDateTime = new DateTime('now');
		$added_date = $currentDateTime->format('Y-m-d H:i:s');
		$trxn = $currentDateTime->format('Y-m-d');
		// $trxn = "2023-09-16";
		 $monthly_15 = date('Y-m-d',strtotime('+14 days',strtotime(date('Y-m-01'))));
		if (!isset($_SESSION)) {
		   session_start();
		}
		$name =  $_SESSION['name'];
	    
	    if(floatval($amount_pay) <= 0 ){
			$response["status"] = "error";
         $response["message"] = "Amount is less than ZERO. Please try again";
         echoResponse(201, $response);

		}else{

	    	if($trxn <= $monthly_15){
	    		$currentDateTime->modify('last day of this month');
	    		$result = $db->insertRecord(" INSERT INTO transactiondetailcredit( cust_id_no, comp_id, amount, added_date, added_by, due_date) 
	        VALUES ('$cust_id','$comp_id',".$amount_pay.",
	        	'".$added_date."','".$name."','".$currentDateTime->format('Y-m-d')."') ");
	 		
		 		if($result != NULL) { 
	           $response["status"] = "success";
		        $response["message"] = "Successfully encoded payment.";
		        echoResponse(200, $response);  
	        	} else {
	            $response["status"] = "error";
	            $response["message"] = "Failed to encoded payment. Please try again";
	            echoResponse(201, $response);
	        	}
	    	}else if($trxn > $monthly_15 && $trxn <= $currentDateTime->format('Y-m-d')){ 
	    		$result = $db->insertRecord(" INSERT INTO transactiondetailcredit( cust_id_no, comp_id, amount, added_date, added_by, due_date) 
	        VALUES ('$cust_id','$comp_id',".$amount_pay.", '".$added_date."','".$name."',
	        	'".date("Y-m-d", strtotime(date("Y-m-15")." +1 month"))."') ");
	 		
		 		if($result != NULL) { 
	           $response["status"] = "success";
		        $response["message"] = "Successfully encoded payment.";
		        echoResponse(200, $response);  
	        	} else {
	            $response["status"] = "error";
	            $response["message"] = "Failed to encoded payment. Please try again";
	            echoResponse(201, $response);
	        	}
	    	}
	    }
	});
?>