<?php
include_once('config.php'); 

if(isset($_GET["sid"]) && isset($_GET["cid"]))
{
    $studId = $_GET["sid"];
    $chlno = $_GET["cid"];
}
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

if (!is_dir(BASEPATH."receipts\\".date('dmY'))) {
	mkdir(BASEPATH."receipts\\".date('dmY'));
}	

$documentPath = BASEPATH."receipts\\".date('dmY')."\\";	

// echo $documentPath;

$contract=' <style> .noBorder td{ border:none} </style>
			<table width="750" border="0" >
			  	<tr> <td align="center" valign="top"><img src="images/logo_pdf.jpg" width="350" height="76" /> </td> </tr>			  	
			  </table>';

$contract.='<table width="750" border="1" cellspacing="5" cellpadding="30">
				<tr>
					<td colspan="3" align="center" height="30"><strong>PAYMENT RECEIPT</strong></td>					
				</tr>
				<tr>
					<td colspan="3" align="left" height="25"><strong>SCHOOL NAME</strong> : LMOIS - '.$streamName.' </td>
				</tr>
				<tr>
					<td align="left" colspan="2" height="25"><strong>NAME</strong> : '.$name.' </td>
					<td align="left" ><strong>SEMESTER</strong> : '.$Semester.' </td>
				</tr>
				<tr>
					<td align="left" colspan="2" height="25"><strong>ID</strong> : '.$studentId.' </td>
					<td align="left" ><strong>CLASS</strong> : '.$className.' </td>
				</tr>
				<tr>
					<td colspan="3" align="left" height="25"><strong>CHALLAN NUMBER</strong> : '.$challanno.' </td>
				</tr>
				<tr>
					<td colspan="3" align="left" height="25"><strong>FEE DETAILS</strong> </td>
				</tr>';
				$tot = 0;
				$i = 1;
				foreach ($result['fee'] as $key => $value) {
						$contract .= '<tr>
							<td align="center" height="25">'.$i.'</td>
							<td align="left">'.$key.'</td>
							<td align="left" >'.$value.'</td>
						</tr>';
						$i++;
						$tot += $value;
					}
				
$contract.='<tr>
				<td colspan="2" align="right" height="25"><strong>GRAND TOTAL</strong></td>
				<td align="left"> '.$tot.' </td>
			</tr>
			</table>';


// echo $contract;exit;
include_once("plugins\\pdf.class.php");
$pdf= new pdf() ; 

$challanID = str_replace('/', '', trim($challanno));
$pdf->convert($contract);
// $pdf->convert($contract,$documentPath.$challanID.".pdf",1);

// header("Location:studetscr.php");
?>