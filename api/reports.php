<?php
	
 	//*******************Get Order Cash functionality*****************//
	$app->post('/getOrderCash', function() use ($app){
		$db = new DbHandler();
		$postdata = file_get_contents("php://input");
		$r = json_decode($postdata);
		$db = new DbHandler();
		$startdate = $r->startdate;
		$enddate = $r->enddate;

	    $data = $db->getData(" SELECT * FROM transactiondetailcash 
	    where DATE_FORMAT(added_date, '%Y-%m-%d') BETWEEN '".$startdate."' AND '".$enddate."' ");
    	echo json_encode($data);
	});

	//*******************Get Order Credit functionality*****************//
 	$app->post('/CreditTrxnReport', function() use ($app){
		 $postdata = file_get_contents("php://input");
		 $r = json_decode($postdata);
		 $db = new DbHandler();
		 $startdate = $r->startdate;
		 $enddate = $r->enddate;
		 $comp_id = $r->comp_id;

		 $IsCompanyExist = $db->getOneRecord(" SELECT * from transactiondetailcredit WHERE comp_id ='$comp_id' ");

		if($IsCompanyExist){
			 $data = $db->getData(" SELECT tdc.id,cust.cust_barcode_num, tdc.cust_id_no, concat(cust.lname, ', ', cust.fname, ', ', cust.mname) as name, tdc.amount, DATE_FORMAT(tdc.added_date, '%Y-%m-%d') as added_date, tdc.due_date, comp_name from transactiondetailcredit tdc left join customerdetail cust on tdc.cust_id_no = cust.cust_id and tdc.comp_id = cust.comp_id left join companydetail comp on tdc.comp_id = comp.comp_id where DATE_FORMAT(tdc.added_date, '%Y-%m-%d') BETWEEN '".$startdate."' AND '".$enddate."' AND tdc.comp_id = '".$comp_id."' ");
			 echo json_encode($data);
		 }else{
		 	if (empty($comp_id)) {
			 	$data = $db->getData(" SELECT tdc.id,cust.cust_barcode_num, tdc.cust_id_no, concat(cust.lname, ', ', cust.fname, ', ', cust.mname) as name, tdc.amount, DATE_FORMAT(tdc.added_date, '%Y-%m-%d') as added_date, tdc.due_date, comp_name from transactiondetailcredit tdc left join customerdetail cust on tdc.cust_id_no = cust.cust_id and tdc.comp_id = cust.comp_id left join companydetail comp on tdc.comp_id = comp.comp_id where DATE_FORMAT(tdc.added_date, '%Y-%m-%d') BETWEEN '".$startdate."' AND '".$enddate."'");
			 	echo json_encode($data);
		 	}else{
			 	$response["status"] = "error";
	            $response["message"] = "No transaction. Please try again.";
	            echoResponse(201, $response);
			 }

		 }
 	});

	//*******************Get Summary Report functionality*****************//
	$app->post('/CreditSummaryTrxnReport', function() use ($app){
		$postdata = file_get_contents("php://input");
		$r = json_decode($postdata);
		$db = new DbHandler();
		$summary_startdate = $r->summary_startdate;
		$summary_enddate = $r->summary_enddate;
		$summary_comp_id = $r->summary_comp_id;

		$IsCompanyExist = $db->getOneRecord(" SELECT * from transactiondetailcredit WHERE comp_id ='$summary_comp_id' ");
       	
       	if($IsCompanyExist){
		    $data = $db->getData(" SELECT cust_barcode_num,cust_id_no, name, sum(amount) as amount, due_date, comp_name,added_date from (
			 SELECT tdc.id,cust.cust_barcode_num, tdc.cust_id_no, concat(cust.lname, ', ', cust.fname, ', ', cust.mname) as name, 
			 tdc.amount, DATE_FORMAT(tdc.added_date, '%Y-%m-%d') 
			 as added_date, tdc.due_date, comp_name 
			 from transactiondetailcredit tdc 
			 left join customerdetail cust on tdc.cust_id_no = cust.cust_id and tdc.comp_id = cust.comp_id 
			 left join companydetail comp on tdc.comp_id = comp.comp_id 
			 where DATE_FORMAT(tdc.added_date, '%Y-%m-%d') 
			 BETWEEN '".$summary_startdate."' AND '".$summary_enddate."' AND tdc.comp_id = '".$summary_comp_id."' 
			) summary_trxn group by cust_id_no,due_date,comp_name order by comp_name, due_date, comp_name
				 ");
	    	echo json_encode($data);
	    }else{
	    	$data = $db->getData(" SELECT cust_barcode_num,cust_id_no, name, sum(amount) as amount, due_date, comp_name,added_date from (
			 SELECT tdc.id,cust.cust_barcode_num, tdc.cust_id_no, concat(cust.lname, ', ', cust.fname, ', ', cust.mname) as name, 
			 tdc.amount, DATE_FORMAT(tdc.added_date, '%Y-%m-%d') 
			 as added_date, tdc.due_date, comp_name 
			 from transactiondetailcredit tdc 
			 left join customerdetail cust on tdc.cust_id_no = cust.cust_id and tdc.comp_id = cust.comp_id 
			 left join companydetail comp on tdc.comp_id = comp.comp_id 
			 where DATE_FORMAT(tdc.added_date, '%Y-%m-%d') 
			 BETWEEN '".$summary_startdate."' AND '".$summary_enddate."' 
			) summary_trxn group by cust_id_no,due_date,comp_name order by comp_name, due_date, comp_name
	    		");
	    	echo json_encode($data);
	    }
	});

	//*******************Get Adjustment Report functionality*****************//
 	$app->post('/AdjustmentReport', function() use ($app){
		 $postdata = file_get_contents("php://input");
		 $r = json_decode($postdata);
		 $db = new DbHandler();
		 $startdate = $r->startdate;
		 $enddate = $r->enddate;
		 $comp_id = $r->adjustment_comp_id;

		$IsCompanyExist = $db->getCountRecord(" SELECT count(*) as cnt from companydetail WHERE comp_id ='$comp_id' ");
       	
       	if($IsCompanyExist['cnt'] == 1){
			$data = $db->getData(" SELECT adj.id,cust.cust_barcode_num, adj.adjtype, adj.cust_id_no, 
			concat(cust.lname, ', ', cust.fname, ', ', cust.mname) as name, 
			adj.amount, DATE_FORMAT(adj.added_date, '%Y-%m-%d') 
			as added_date, adj.due_date, comp_name,adj.reason
			from adjustment adj 
			left join customerdetail cust on adj.cust_id_no = cust.cust_id and adj.comp_id = cust.comp_id 
			left join companydetail comp on adj.comp_id = comp.comp_id 
			where DATE_FORMAT(adj.added_date, '%Y-%m-%d') 
			BETWEEN '".$startdate."' AND '".$enddate."' AND adj.comp_id = '".$comp_id."' ");
			echo json_encode($data);
		 }else{
		 	if ($IsCompanyExist['cnt'] < 1) {
			 	$data = $db->getData(" SELECT adj.id,cust.cust_barcode_num, adj.adjtype, adj.cust_id_no, 
				concat(cust.lname, ', ', cust.fname, ', ', cust.mname) as name, 
				adj.amount, DATE_FORMAT(adj.added_date, '%Y-%m-%d') 
				as added_date, adj.due_date, comp_name,adj.reason
				from adjustment adj 
				left join customerdetail cust on adj.cust_id_no = cust.cust_id and adj.comp_id = cust.comp_id 
				left join companydetail comp on adj.comp_id = comp.comp_id 
				where DATE_FORMAT(adj.added_date, '%Y-%m-%d') 
				BETWEEN '".$startdate."' AND '".$enddate."' ");
			 	echo json_encode($data);
		 	}else{
			 	$response["status"] = "error";
	            $response["message"] = "No transaction. Please try again.";
	            echoResponse(201, $response);
		 	}

		 }
 	});

 	//*******************Get Payment Report functionality*****************//
 	$app->post('/PaymentReport', function() use ($app){
		 $postdata = file_get_contents("php://input");
		 $r = json_decode($postdata);
		 $db = new DbHandler();
		 $startdate = $r->startdate;
		 $enddate = $r->enddate;
		 $comp_id = $r->comp_id;

		$IsCompanyExist = $db->getOneRecord(" SELECT * from companydetail WHERE comp_id ='$comp_id' ");

		if($IsCompanyExist){
			$data = $db->getData(" SELECT pay.id, pay.amount, DATE_FORMAT(pay.added_date, '%Y-%m-%d') 
			as added_date, pay.due_date, comp_name 
			from payment pay 
			left join companydetail comp on pay.comp_id = comp.comp_id 
			where DATE_FORMAT(pay.added_date, '%Y-%m-%d') 
			BETWEEN '".$startdate."' AND '".$enddate."' AND pay.comp_id = '".$comp_id."' ");
			echo json_encode($data);
		 }else{
		 	if (empty($comp_id)) {
			 	$data = $db->getData(" SELECT pay.id, pay.amount, DATE_FORMAT(pay.added_date, '%Y-%m-%d') 
				as added_date, pay.due_date, comp_name 
				from payment pay 
				left join companydetail comp on pay.comp_id = comp.comp_id 
				where DATE_FORMAT(pay.added_date, '%Y-%m-%d') 
				BETWEEN '".$startdate."' AND '".$enddate."' ");
			 	echo json_encode($data);
		 	}else{
			 	$response["status"] = "error";
	            $response["message"] = "No transaction. Please try again.";
	            echoResponse(201, $response);
		 	}

		 }
 	});

 	//*******************Get Payment Report functionality*****************//
 	$app->post('/SOAReport', function() use ($app){
		 $postdata = file_get_contents("php://input");
		 $r = json_decode($postdata);
		 $db = new DbHandler();
		 $comp_id = $r->comp_id;

		$IsCompanyExist = $db->getOneRecord(" SELECT * from companydetail WHERE comp_id ='$comp_id' ");

		if($IsCompanyExist){
			$data = $db->getData(" SELECT * from companydetail WHERE comp_id ='$comp_id' ");
			echo json_encode($data);
		}else{
			$response["status"] = "error";
            $response["message"] = "No transaction. Please try again.";
            echoResponse(201, $response);
		}
 	});

?>