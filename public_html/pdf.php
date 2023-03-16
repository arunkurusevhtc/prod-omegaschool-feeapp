<?php
require('fpdf/fpdf.php');
include_once('config.php');   
//     if(isset($_SESSION['success_msg']) && $_SESSION['success_msg'] !='') {
//         echo $_SESSION['success_msg'];
//         unset($_SESSION['success_msg']);
//     } elseif (isset($_SESSION['error_msg']) && $_SESSION['error_msg'] !='') {
//         echo $_SESSION['error_msg'];
//         unset($_SESSION['error_msg']);
//     }
if(isset($_GET["sid"]) && isset($_GET["cid"]))
{
// print_r($_GET["sid"]);
    $studId = $_GET["sid"];
    $chlno = $_GET["cid"];
}
// print_r($studId);
// print_r($chlno);exit;
$pdf=new FPDF('P', 'mm', 'A4' );
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);
$pdf->Image('C:\xampp\htdocs\omega\fpdf\logo.png', 60, 7, -100);
$pdf->Ln();
$pdf->Ln();
$pdf->SetFont('times','B',10);
// $pdf->SetFillColor('150,150,150');
// $pdf->SetDrawColor(0, 50, 0, 0);
$pdf->SetFillColor(151, 203, 228, 0.26);
// $pdf->SetTextColor(0, 100, 0, 0);
$pdf->Ln();
// $pdf->SetFont('Arial','B',16);
// Move to 8 cm to the right
$pdf->Cell(1);
$pdf -> SetY(35);
// Centered text in a framed 20*10 mm cell and line break
$pdf->Cell(195,18,'PAYMENT RECEIPT',1,1,'C',true);
$sql = 'SELECT * FROM getstudentdata where "studentId" = \''.$studId.'\' AND "challanNo" = \''.$chlno.'\'';
$result = sqlgetresult($sql);
$stream     = $result['stream'];
$studentId = $result['studentId'];
$name = $result['studentName'];
// $amount = $result['amount'];
$feetype = $result['feeTypes'];
$total = 0;
// $feetype = (explode(",",$ftype));
$class = $result['classList'];
$Semester = $result['term'];
$challanno = $result['challanNo'];
// $duedate = $result['dueDate'];
$className = getClassbyNameId($class);
$streamName = getStreambyId($stream);
$X=95;
$Y=108;
$feeTypes= sqlgetresult("SELECT * FROM getfeetypedata WHERE class = '".$class."' AND stream = '".$stream."' AND semester = '".$Semester."' ");       
        if( strpos($result['feeTypes'], ',') !== false ) {
            $feeData = explode(',',$result['feeTypes']);
            foreach ($feeData as $k=>$v) {
                foreach($feeTypes as $val){
                    if(in_array(trim($v), $val)) {
                        $result['fee'][$val['feename']] = $val['amount'];
                        $result['dueDate'] = $val['dueDate'];
                    }
                }
            }
        } else { 
          foreach($result['feeTypes'] as $val){
                if(in_array($result['feeTypes'], $val)) {
                   $result['feeTypes'][$val['feename']] = $val['amount'];
                   $result['dueDate'] = $val['dueDate'];
                }
            }
        }    
$pdf -> SetY(38);
$pdf->SetFillColor(0, 0, 255, 0);
// $pdf->SetFillColor(153, 204, 255, 0);
$pdf->Cell(25,40,"School Name:    LMOIS - $streamName");
$pdf -> SetX(41);
$pdf -> SetLineWidth(.3);
$pdf->line(10,63,205,63);
$pdf->SetFillColor(0, 0, 255, 0);
$pdf -> SetY(41);
$pdf->Cell(25,55,"Name:     $name");
$pdf -> SetX(150);
$pdf->Cell(100,55,"Semester:     $Semester");
$pdf->line(10,74,205,74);
$pdf->SetFillColor(153, 204, 255, 0);
$pdf -> SetY(47);
$pdf->Cell(55,65,"ID:     $studentId");
$pdf -> SetX(150);
$pdf->Cell(65,65,"Class:     $className");
$pdf->line(10,85,205,85);
$pdf->SetFillColor(153, 204, 255, 0);
$pdf -> SetY(53);
$pdf->Cell(75,75,"Challan Number:     $challanno");
$pdf->line(10,95,205,95);
$pdf->line(10,35,10,148);
$pdf->line(205,35,205,148);
/*creating Table */
$pdf -> SetY(54);
$pdf -> Cell(90,90,"FEES DETAILS :");
$i = 1;
foreach ($result['fee'] as $key => $value) {
    $pdf -> SetX(10);
    $pdf->Cell($X,$Y,$i);
    $pdf -> SetX(30);
    $pdf->Cell($X,$Y,trim($key));
    $pdf -> SetX(150);
    $pdf->Cell($X,$Y,trim($value));
    $total  += $value;
    $pdf -> SetX(10);
    $Y = $Y+10;
    $i++;
}

$pdf->line(10,$Y,205,$Y);
$pdf->line(10,$Y+10,205,$Y+10);
$pdf -> SetY($Y-40);    
$pdf->Cell(5,90,'Grand Total:');
$pdf -> SetX(175);                                                                         
$pdf->Cell(55,90,$total);
$pdf -> SetY($Y);    
$pdf->Cell(50,50,'This receipt is electronically generated.');
$pdf->Ln();
ob_start();    
$pdf->Output();
ob_end_flush();
?>