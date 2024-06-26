 <?php
/*call the FPDF library*/
require('fpdf/fpdf.php');
require 'dbHandler.php';
    
/*A4 width : 219mm*/
date_default_timezone_set('asia/taipei');
$currentDateTime = new DateTime('now');
$pdf = new FPDF('P','mm','A4');

$pdf->AddPage();
/*output the result*/

//set font to arial, bold, 14pt
$pdf->SetFont('Arial','B',14);

//Cell(width , height , text , border , end line , [align] )

$pdf->Cell(130 ,5,'J&R FOOD SERVICES',0,0);

//set font to arial, regular, 12pt
$pdf->SetFont('Arial','',12);

$pdf->Cell(59 ,5,'',0,1);//end of line

$pdf->Cell(130 ,5,'Lapu-Lapu City',0,0);
$pdf->Cell(35 ,5,'Statement Date:',0,0);
$pdf->Cell(34 ,5,$currentDateTime->format('Y-m-d'),0,1);//end of line

$pdf->Cell(130 ,5,'6015, Cebu',0,0);
$pdf->Cell(35 ,5,'Due Date:',0,0);
$pdf->Cell(34 ,5,$_GET['due_date'],0,1);//end of line

$pdf->Cell(130 ,5,'09310366901',0,0);

//make a dummy empty cell as a vertical spacer
$pdf->Cell(189 ,10,'',0,1);//end of line
$pdf->Cell(189 ,10,'',0,1);//end of line

//billing address
$pdf->SetFont('Arial','B',14);
$pdf->Cell(65 ,10,'',0,0);
$pdf->Cell(90 ,5,'STATEMENT OF ACCOUNT',0,1);//end of line
$pdf->SetFont('Arial','',12);
//add dummy cell at beginning of each line for indentation
$pdf->Cell(75 ,10,'',0,0);
$pdf->Cell(90 ,5,$_GET['comp_name'],0,1);


//make a dummy empty cell as a vertical spacer
$pdf->Cell(189 ,10,'',0,1);//end of line

$pdf->SetFont('Arial','',12);

$pdf->GetY();
$pdf->GetX();
$pdf->SetFillColor(255, 255, 255);
$pdf->SetDrawColor(0, 0, 0);
//Numbers are right-aligned so we give 'R' after new line parameter
$db = new DbHandler();

$Purchases = $db->getOneRecord(" SELECT sum(amount) as amount FROM transactiondetailcredit
WHERE comp_id = '".$_GET['comp_id']."' AND DATE_FORMAT(due_date, '%Y-%m-%d') = '".$_GET['due_date']."' ");
$pdf->Cell(20 ,5,'',0,0);
$pdf->Cell(100 ,5,'Current Purchases',0,0);
$pdf->Cell(34 ,5,number_format($Purchases['amount'],2),0,1,'R');//end of line


$Payments = $db->getOneRecord(" SELECT sum(amount) as curr_payment FROM payment
WHERE comp_id = '".$_GET['comp_id']."' AND DATE_FORMAT(due_date, '%Y-%m-%d') = '".$_GET['due_date']."' ");
$pdf->Cell(20 ,5,'',0,0);
$pdf->Cell(100 ,5,'Current Payments',0,0);
$pdf->Cell(34 ,5,number_format($Payments['curr_payment'],2),0,1,'R');//end of line


$CreditAdjustment = $db->getOneRecord(" SELECT SUM(amount) as curr_cred_adj FROM adjustment 
WHERE adjtype = 'Credit' and comp_id = '".$_GET['comp_id']."' AND DATE_FORMAT(due_date, '%Y-%m-%d') = '".$_GET['due_date']."' ");
$pdf->Cell(20 ,5,'',0,0);
$pdf->Cell(100,5,'Current Credit Adjustment',0,0);
$pdf->Cell(34 ,5,number_format($CreditAdjustment['curr_cred_adj'],2),0,1,'R');


$DebitAdjustment = $db->getOneRecord(" SELECT SUM(amount) as curr_deb_adj FROM adjustment 
WHERE adjtype = 'Debit' and comp_id = '".$_GET['comp_id']."' AND DATE_FORMAT(due_date, '%Y-%m-%d') = '".$_GET['due_date']."' ");
$pdf->Cell(20 ,5,'',0,0);
$pdf->Cell(100 ,5,'Current Debit Adjustment',0,0);
$pdf->Cell(34 ,5,number_format($DebitAdjustment['curr_deb_adj'],2),0,1,'R');
//end of line

$pdf->Cell(188 ,0, "", 1, 1, 'C');

$Subtotal =  (round((float)$Payments['curr_payment'],2) + 
	round((float)$CreditAdjustment['curr_cred_adj'],2) + 
	round((float)$Purchases['amount'],2) + 
	round((float)$DebitAdjustment['curr_deb_adj'],2));
//summary
$pdf->Cell(80 ,5,'',0,0);
$pdf->Cell(40 ,5,'Subtotal',0,0);
$pdf->Cell(34 ,5,number_format($Subtotal,2),0,1,'R');//end of line

$pdf->Cell(189 ,10,'',0,1);//end of line

$PrevPurchases = $db->getOneRecord(" SELECT sum(amount) as prev_purchase FROM transactiondetailcredit
WHERE comp_id = '".$_GET['comp_id']."' AND DATE_FORMAT(due_date, '%Y-%m-%d') < '".$_GET['due_date']."' ");
$pdf->Cell(20 ,5,'',0,0);
$pdf->Cell(100 ,5,'Total Previous Purchases',0,0);
$pdf->Cell(34 ,5,number_format($PrevPurchases['prev_purchase'],2),0,1,'R');//end of line

$PrevPayments = $db->getOneRecord(" SELECT sum(amount) as prev_payment FROM payment
WHERE comp_id = '".$_GET['comp_id']."' AND DATE_FORMAT(due_date, '%Y-%m-%d') < '".$_GET['due_date']."' ");
$pdf->Cell(20 ,5,'',0,0);
$pdf->Cell(100 ,5,'Total Previous Payments',0,0);
$pdf->Cell(34 ,5,number_format($PrevPayments['prev_payment'],2),0,1,'R');//end of line

$PrevCreditAdjustment = $db->getOneRecord(" SELECT SUM(amount) as prev_cred_adj FROM adjustment 
WHERE adjtype = 'Credit' and comp_id = '".$_GET['comp_id']."' 
AND DATE_FORMAT(due_date, '%Y-%m-%d') < '".$_GET['due_date']."' ");
$pdf->Cell(20 ,5,'',0,0);
$pdf->Cell(100 ,5,'Total Previous Credit Adjustment',0,0);
$pdf->Cell(34 ,5,number_format($PrevCreditAdjustment['prev_cred_adj'],2),0,1,'R');//end of line

$PrevDebitAdjustment = $db->getOneRecord(" SELECT SUM(amount) as prev_deb_adj FROM adjustment 
WHERE adjtype = 'Debit' and comp_id = '".$_GET['comp_id']."' 
AND DATE_FORMAT(due_date, '%Y-%m-%d') < '".$_GET['due_date']."' ");
$pdf->Cell(20 ,5,'',0,0);
$pdf->Cell(100 ,5,'Total Previous Debit Adjustment',0,0);
$pdf->Cell(34 ,5,number_format($PrevDebitAdjustment['prev_deb_adj'],2),0,1,'R');//end of line

$pdf->Cell(188 ,0, "", 1, 1, 'C');
$PrevTotal =  (round((float)$PrevPurchases['prev_purchase'],2) + 
	round((float)$PrevPayments['prev_payment'],2) + 
	round((float)$PrevCreditAdjustment['prev_cred_adj'],2) + 
	round((float)$PrevDebitAdjustment['prev_deb_adj'],2));
$pdf->Cell(80 ,5,'',0,0);
$pdf->Cell(40 ,5,'Subtotal',0,0);
$pdf->Cell(34 ,5,number_format($PrevTotal,2),0,1,'R');//end of line

$pdf->Cell(189 ,10,'',0,1);//end of line

$TotalDue =  (round((float)$PrevPurchases['prev_purchase'],2) + 
	round((float)$PrevPayments['prev_payment'],2) + 
	round((float)$PrevCreditAdjustment['prev_cred_adj'],2) + 
	round((float)$PrevDebitAdjustment['prev_deb_adj'],2)) + $Subtotal;
$pdf->Cell(80 ,5,'',0,0);
$pdf->Cell(40 ,5,'Total Due',0,0);
$pdf->Cell(34 ,5,number_format($TotalDue,2),0,1,'R');//end of line


$pdf->Output();

?>