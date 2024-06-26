<?php
   $app->get('/getCntUsers', function() use ($app){
      $db = new DbHandler();
      $user = $db->getData("select count(*) as CNT from userdetail order by id");
      echo json_encode($user);
   });

   $app->get('/getCntCompany', function() use ($app){
      $db = new DbHandler();
      $user = $db->getData("select count(*) as CNT from companydetail order by id");
      echo json_encode($user);
   });

   $app->get('/getCntCustomer', function() use ($app){
      $db = new DbHandler();
      $user = $db->getData("select count(*) as CNT from customerdetail order by id");
      echo json_encode($user);
   });

   $app->get('/getCntCashPayment', function() use ($app){
      $db = new DbHandler();
      $user = $db->getData("select count(*) as CNT from transactiondetailcash order by id");
      echo json_encode($user);
   });

   $app->get('/getCntCreditPayment', function() use ($app){
      $db = new DbHandler();
      $user = $db->getData("select count(*) as CNT from transactiondetailcredit order by id");
      echo json_encode($user);
   });
	
?>