<?php
	//********************getCompanyPayments functionality*****************//
    $app->post('/getCompanyPayments', function() use ($app){
		$db = new DbHandler();
		$postdata = file_get_contents("php://input");
		$r = json_decode($postdata);
		$db = new DbHandler();
		$comp_id = $r->comp_id;
		$due_date = $r->due_date;

		date_default_timezone_set('asia/taipei');
		$currentDateTime = new DateTime('now');
		$currentDate = $currentDateTime->format('Y-m-d');
		$d = new DateTime('first day of this month');
		$monthly_15 = date('Y-m-d',strtotime('+14 days',strtotime(date('Y-m-01'))));

		//sample checking here
		if($currentDate >= (new DateTime('first day of this month'))->format('Y-m-d') && $currentDate <= $monthly_15){
			$max_duedate = $monthly_15;
		}
		$currentDateTime->modify('last day of this month');
		if($currentDate > $monthly_15 && $currentDate <= $currentDateTime->format('Y-m-d')){
			$max_duedate = $currentDateTime->format('Y-m-d');
		}

		if($due_date > $max_duedate){
			$response["status"] = "error";
	        $response["message"] = "Transaction Still Ongoing On The Selected Duedate. Please try again";
	        echoResponse(201, $response);
		}else{
			$Purchases = $db->getData(" SELECT sum(amount) as curr_purchase FROM transactiondetailcredit
			WHERE comp_id = '".$comp_id."' AND DATE_FORMAT(due_date, '%Y-%m-%d') = '".$due_date."' ");

			$Payments = $db->getData(" SELECT sum(amount) as curr_payment FROM payment
			WHERE comp_id = '".$comp_id."' AND DATE_FORMAT(due_date, '%Y-%m-%d') = '".$due_date."' ");

			$CreditAdjustment = $db->getData(" SELECT SUM(amount) as curr_cred_adj FROM adjustment 
			WHERE adjtype = 'Credit' AND comp_id = '".$comp_id."' AND DATE_FORMAT(due_date, '%Y-%m-%d') = '".$due_date."' ");

			$DebitAdjustment = $db->getData(" SELECT SUM(amount) as curr_deb_adj FROM adjustment 
			WHERE adjtype = 'Debit' AND comp_id = '".$comp_id."' AND DATE_FORMAT(due_date, '%Y-%m-%d') = '".$due_date."' ");

			$PrevPurchases = $db->getData(" SELECT sum(amount) as prev_purchase FROM transactiondetailcredit
			WHERE comp_id = '".$comp_id."' AND DATE_FORMAT(due_date, '%Y-%m-%d') < '".$due_date."'  ");

			$PrevPayments = $db->getData(" SELECT sum(amount) as prev_payment FROM payment
			WHERE comp_id = '".$comp_id."' AND DATE_FORMAT(due_date, '%Y-%m-%d') < '".$due_date."' ");

			$PrevCreditAdjustment = $db->getData(" SELECT SUM(amount) as prev_cred_adj FROM adjustment 
			WHERE adjtype = 'Credit' AND comp_id = '".$comp_id."' AND DATE_FORMAT(due_date, '%Y-%m-%d') < '".$due_date."' ");

			$PrevDebitAdjustment = $db->getData(" SELECT SUM(amount) as prev_deb_adj FROM adjustment 
			WHERE adjtype = 'Debit' AND comp_id = '".$comp_id."' AND DATE_FORMAT(due_date, '%Y-%m-%d') < '".$due_date."' ");

	   		$result = array('Purchases'=>$Purchases,'Payments'=>$Payments,'CreditAdjustment'=>$CreditAdjustment,'DebitAdjustment'=>$DebitAdjustment, 'PrevPurchases'=>$PrevPurchases,'PrevPayments'=>$PrevPayments,'PrevCreditAdjustment'=>$PrevCreditAdjustment,'PrevDebitAdjustment'=>$PrevDebitAdjustment);
			echo json_encode($result);
		}
		
	});

    //********************getCompanyPayments functionality*****************//
	$app->post('/CompanyPaymentDue', function() use ($app){
		$db = new DbHandler();
		$postdata = file_get_contents("php://input");
		$r = json_decode($postdata);
		$db = new DbHandler();
		$comp_id = $r->comp_id;
		$due_date = $r->due_date;
		$amoutDue = $r->amoutDue;
		$paymentDue = $r->paymentDue;
		$paymentDues = floatval($r->paymentDue*-1);
		date_default_timezone_set('asia/taipei');
		$currentDateTime = new DateTime('now');
		$currentDate = $currentDateTime->format('Y-m-d H:i:s');
		if (!isset($_SESSION)) {
		   session_start();
		}
		$name =  $_SESSION['name'];
		if(round((float)$paymentDue,2) < round((float)$amoutDue,2) && round((float)$paymentDue,2) != round((float)$amoutDue,2)){
			$response["status"] = "error";
         	$response["message"] = "Payment must be lesser than or equal to Total Due ONLY. No advance Payment. Please try again";
         	echoResponse(201, $response);
		}else{
			if(round((float)$paymentDue,2) < 0){
				$response["status"] = "error";
	         	$response["message"] = "Amount due must be positive number. Please try again";
	         	echoResponse(201, $response);
			}else if(round((float)$paymentDue,2) != round((float)$amoutDue,2)){
				$response["status"] = "error";
	         	$response["message"] = "Amount due must be equal to number. Please try again";
	         	echoResponse(201, $response);
			}else{

				$result = $db->insertRecord(" INSERT INTO payment(comp_id, amount, due_date, added_date, added_by) 
				      VALUES ('".$comp_id."','".$paymentDues."','".$due_date."','".$currentDate."','".$name."') ");

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
?>