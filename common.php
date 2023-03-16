<?php

function getMailTemplate($fromemail = "")
{

	$html = '<style type="text/css">

	body {
		margin: 20px;
		padding: 0px;
	}

	a {
		color: #bb1b21;
		text-decoration:none;
	}
	a:hover {
		color: #000;
		text-decoration:underline;
	}
	h1 {
		font-family: Arial, Helvetica, sans-serif;
		font-size:24px;
		color:#bb1b21;
		padding-top:5px;
		margin:0;
	}
	h2 {
		font:bold 15px Tahoma, Arial, Helvetica, sans-serif;
		color:#f00;
		padding-top:5px;
	}
	p {
		font-family:Tahoma, Arial, Helvetica, sans-serif;
		font-size:11px;
		color:#656565;
		line-height:20px;
	}
	.click
	{
		color: #cb1717;
	}

	</style>

	<table width="640" border="0" align="center" cellpadding="0" cellspacing="0" style="width:614px;" >
		<tr>
			<td valign="top" style="background:#fff; padding:15px; width:614px; ">
				<table  border="0" cellpadding="0" cellspacing="0"  style=" background-color:#f6f6f6; padding:0px; margin:0px; width:612px;border: 10px solid #2a0192;">
					<tr>
						<td style="text-align:center;"><img height="100" src="'.BASEURL.'images/logo.png" alt="'.SITENAME.'"/></td>
					</tr>

					<tr>
						<td style=" width:577px">
							<table cellspacing="0"  width="100%"  cellpadding="0" border="0" style="border-top:1px solid #040404;">
								<tr>
									<td style="color:#818181; padding:15px; background:#f6f6f6"> 
										<p>{BODY}</p>
									</td>
								</tr>';

					if ($fromemail != '') {
						$html .= '<tr>
							<td style="color:#818181; padding:15px; background:#f6f6f6"> 
							<p><b>For any queries please contact us at feeapp.support@omegaschools.org  or 044-66241110.</a></b></p>
							</td>
							</tr>';
					}

					$html .= '</table>
						</td>
					</tr>

					<tr>
						<td style="width:577px; border-top:1px solid #040404;"> 
							<table cellspacing="0"  width="100%"  cellpadding="0" border="0" style="border-top:1px solid #040404;">
								<tr>
									<td align="center" style="color:#818181; padding:15px; background:#f6f6f6; font-size:12px; font-family:Tahoma, Arial, Helvetica, sans-serif; ">
										&copy; Copyright '.date('Y').' Omega International School
									</td>
								</tr>
							</table>
						</td>
					</tr>

				</table>
			</td>
		</tr>
	</table> ';
	return $html;
}

function SendMailId($to, $subject, $data, $fromname = "", $fromemail = "", $attachment = "", $type = "", $cc = '') {	

	// if( BASEURL == 'http://111.93.105.51/feeapp/' || BASEURL == 'http://172.16.0.25/feeapp/' || BASEURL == 'https://qa.omegaschools.org/feeapp/') {
	// 	require_once('/var/www/html/feeapp/plugins/PHPMailer/class.phpmailer.php');
	// } elseif ( BASEURL == 'http://www.omegaschools.org/newfeeapp/' ) {
	// 	require_once('/srv/feeapp/plugins/PHPMailer/class.phpmailer.php');
	// } else {
	// 	require_once(BASEPATH.'plugins\PHPMailer\class.PHPMailer.php');
	// }

	if( BASEURL == 'http://111.93.105.51/feeapp/' || BASEURL == 'http://172.16.0.25/feeapp/' || BASEURL == 'https://qa.omegaschools.org/feeapp/') {
		require_once(BASEPATH.'plugins/PHPMailer/class.phpmailer.php');
	} elseif ( BASEURL == 'https://www.omegaschools.org/feeapp/' ) {
		require_once(BASEPATH.'plugins/PHPMailer/class.phpmailer.php');
	} else {
		require_once(BASEPATH.'plugins\PHPMailer\class.PHPMailer.php');
	}
	
	$mail  = new PHPMailer();
	
	$mail->IsSMTP();
	$mail->SMTPDebug  = 0;  
	$mail->Host     = SMTPHOST; //"mail.autobulls.com"; 
	$mail->Username = SMTPUSERNAME;  
	$mail->Password = SMTPPASSWORD;  
	$fromemail = SMTPFROM;
	$fromname = SMTPFROMNAME;
	
	$mail->SMTPSecure = 'tls'; // secure transfer enabled REQUIRED for GMail	 	
	$mail->Port = SMTPPORT; 
	$mail->SMTPAuth  = true;                  // enable SMTP authentication
	$mail->SMTPKeepAlive = true; 	
	
	$mail->SetFrom($fromemail,$fromname);
	
 	$address = $to;

 // 		if($to != 'manojkumarp@vishwak.com') {
	// 	$address = "manojkumarp@vishwak.com";
	// } else {
	// 	$address = "manojkumarp@vishwak.com";
	// }
 // 	if( $type = "challan") {
 // 		$address = SMTPUSERNAME ;
	// } else {
	// 	$address = $to;
	// }
	// $address = 'notification@omegaschools.org';
	/*if (strpos(APP_ROOT_URL, 'localhost') !== false || strpos(APP_ROOT_URL, 'flrvrent.com') !== false || strpos(APP_ROOT_URL, '10.10.100.27') !== false) {
		if ($address != 'renukad@vishwak.com' && $address != 'anandj@vishwak.com' && $address != 'anandhct14@gmail.com') {
			$address = "flrvstaging@gmail.com";
		}
	}*/
	$body=str_replace("{BODY}",$data, getMailTemplate($fromemail));

	$mail->AddAddress($address, "");
	$mail->Subject=$subject;
	$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
	$mail->MsgHTML($body);

	if($cc != ''){
		$mail->AddCC('feeapp@omegaschools.org');


	}
	if(stristr($body, 'transport challan')){
		$mail->AddBCC('transportfees@omegaschools.org');
	}

	if( $attachment != '') {
		$mail->AddAttachment($attachment); 
	}
	return $mail->Send();
}

function sqlgetresult($sql,$array=false)
{
	$lastQuery= $sql;
	$host        = "host = ".DB_HOST;
	$port        = "port = ".DB_PORT;
	$dbname      = "dbname = ".DB_NAME;
	$credentials = "user = ".DB_USERNAME." password=".DB_PASSWORD;

	$dbconn = pg_connect( "$host $port $dbname $credentials"  );

	$query=pg_query($dbconn,$sql);

	$mysqlrows=pg_num_rows($query);

	if($mysqlrows==0)	{
		return NULL;		
	}
	 
	if($mysqlrows == 1)	{
		$result=pg_fetch_assoc($query);
	
		if($array == true) {
			return array(stripslashes_deep($result));
		} else {
			return stripslashes_deep($result);
		}		
	} else {
		$array=array();
		while($result=pg_fetch_assoc($query)) {		
			array_push($array,stripslashes_deep($result));
		}		 
		return $array;
	}
}

function stripslashes_deep($value) {

	// if(is_array($value)) {
	// 	$val = "";
	// 	foreach($value as $key => $keyvalue)
	// 	{
	// 		$val[$key] =stripslashes($keyvalue);
	// 	}
	// 	$value=$val;
	// } else {		
	// 	$value =  stripslashes($value);

	// }
	return $value;
}

function generateVerifyCode($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function generateRandom($length = 6) {
    $characters = '0123456789';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function checksession() {
	// print_r($_SESSION);
	if (count($_SESSION) == 0 || !isset($_SESSION['uid']) ) {
		header("Location:login.php");
	}
}

function checkadmsession() {
	// print_r($_SESSION);
	if (count($_SESSION) == 0 || !isset($_SESSION['myadmin']) ) {
		header("Location:login.php");
	}
}

function exportData($data,$filename,$columns) {
	ob_start();
    $html = '<html xmlns:x="urn:schemas-microsoft-com:office:excel">
            <head>
                <meta http-equiv="Content-Type" content="text/html;charset=windows-1252">
                <!--[if gte mso 9]>
                <xml>
                    <x:ExcelWorkbook>
                    <x:ExcelWorksheets>
                    <x:ExcelWorksheet>
                    <x:Name>'.$filename.'</x:Name>
                    <x:WorksheetOptions>
                    <x:Panes>
                </x:Panes>
            </x:WorksheetOptions>
        </x:ExcelWorksheet>
    </x:ExcelWorksheets>
    </x:ExcelWorkbook>
    </xml>
    <![endif]-->
    </head>
    <body>
        <table >
            <tr>
            <td></td>
            <td colspan="'.count($columns).'" style="text-align:center;"><b>'.$filename.'</b></td>
            </tr>
            <tr>';
            foreach($columns as $heading) {
            	$html .= '<th align="center">'.$heading.'</th>';
            }
            
            $html .= '</tr>';

       		foreach($data as $data)
            {
            	$html .= '<tr>';
            	foreach($columns as $heading) {                
	                $html .= '<td>'.$data[$heading].'</td>';	                
            	}
            	$html .= '</tr>';
            }
    $html .= '</table></body></html>';

    echo $html;

    header("Content-type: application/octet-stream");
    header("Content-Disposition: attachment; filename=".$filename."-".date("m-d-Y-his").".xls");
    header("Pragma: no-cache");
    header("Expires: 0");
    ob_end_flush();
    exit;    
}

function currentAcademicyr() {
	$data  = sqlgetresult("SELECT year FROM tbl_academic_year");
	return $data['year'];
}

function getStreambyName($stream) {
	$data  = sqlgetresult("SELECT id FROM tbl_stream WHERE stream = '$stream' ");
	return $data['id'];	
}

function getStreambyId($streamid) {
	$data  = sqlgetresult("SELECT stream FROM tbl_stream WHERE id = '$streamid' ");
	return $data['stream'];	
}


function getTaxbyId($taxid) {
	$data  = sqlgetresult('SELECT "taxType" FROM tbl_tax WHERE "id"  =  \''.$taxid.'\'');
	return $data['taxType'];	
}
function getFeeGroupbyId($feegrpid) {
	if($feegrpid == 0){
		$data = array();
 		$data['feeGroup'] = 'LATE FEE';
	}
	else{
		$data  = sqlgetresult('SELECT "feeGroup" FROM tbl_fee_group WHERE "id"  =  \''.$feegrpid.'\'');
	}
	return $data['feeGroup'];	
}
function getFeeGroupbyName($feegrpname) {
	if($feegrpname == 'LATE FEE'){
		$data = array();
		$data['id'] = 0; 
	}
	else{
	$data  = sqlgetresult('SELECT "id" FROM tbl_fee_group WHERE "feeGroup"  =  \''.$feegrpname.'\'');
    }
	return $data['id'];	
}
function getParentNamebyId($parentId) {
	$data  = sqlgetresult('SELECT "userName" FROM tbl_parents WHERE "id"  =  \''.$parentId.'\'');
	return $data['userName'];	
}

function getClassbyName($class) {
	$data  = sqlgetresult("SELECT id FROM tbl_class WHERE class_list = '$class' ");
	return $data['id'];	
}

function getClassbyNameId($classid) {
	$classArr = explode(',', $classid);
	foreach ($classArr as $v) {
		$data[]  = sqlgetresult("SELECT class_list FROM tbl_class WHERE id = '$v' ");
	}
	foreach ($data as $k=>$v) {
		$data1[] = trim($v['class_list']);
	}
	// print_r($data1);
	return implode(', ', $data1);	
	
}
	

function getStudentNameById($studentid) {
	$data  = sqlgetresult('SELECT "studentName" FROM tbl_student WHERE "studentId" = \''.$studentid.'\'');
	return $data['studentName'];	
}

function getStudentIdByChallan($chlno) {
	$data  = sqlgetresult('SELECT "studentId" FROM tbl_challans WHERE "challanNo" = \''.$chlno.'\' LIMIT 1');
	return $data['studentId'];	
}
function getAcademicyrByStudentId($studentid) {
	$data  = sqlgetresult('SELECT "academic_yr" FROM tbl_student WHERE "studentId" = \''.$studentid.'\'');
	return $data['academic_yr'];	
}

function getAcademicyrById($yrid) {
	$data  = sqlgetresult('SELECT "year" FROM tbl_academic_year WHERE "id" = \''.$yrid.'\' ');
	return $data['year'];	
}

function getAcademicyrIdByName($yr) {
	$data  = sqlgetresult('SELECT "id" FROM tbl_academic_year WHERE "year" = \''.$yr.'\' ');
	return $data['id'];	
}

function getDisplayOrderById($class_id) {
	$data  = sqlgetresult('SELECT "displayOrder" FROM tbl_class WHERE "id" = \''.$class_id.'\' ');
	return $data['displayOrder'];
}

function getFeeTypebyId($feeid) {
	if($feeid == 0){
		$data = array();
		$data['feeType'] = 'Late Fee';
	}
	else{
		$data  = sqlgetresult('SELECT "feeType" FROM tbl_fee_type WHERE "id"  =  \''.$feeid.'\'');
	}
	return $data['feeType'];	
}
function getFeeTypebyGrpId($feeid) {
                $data  = sqlgetresult('SELECT "feeGroup" FROM tbl_fee_type WHERE "id"  =  \''.$feeid.'\'');
                return $data['feeGroup'];            
}
function getFeeTypeAmountbyFeetypeId($classid,$streamid,$termid,$feetypeid) {
                $data  = sqlgetresult('SELECT "amount" FROM tbl_fee_configuration WHERE class='.$classid.' AND semester=\''.$termid.'\' AND stream = \''.$streamid.'\' AND "feeType" = \''.$feetypeid.'\'');
                return $data['amount'];            
}
function getFeeGrpByFeeId($feeid) {
                $data  = sqlgetresult('SELECT "feeGroup" FROM tbl_fee_type WHERE "id"  =  \''.$feeid.'\'');
                return $data['feeGroup'];            
}
function getreceiptamount($studentid){
	$data = sqlgetresult('SELECT SUM("amount") AS total FROM tbl_payments WHERE "studentId" = \''.$studentid.'\' AND "transStatus" = \'Ok\'');
	return $data['total'];
}

function getsfsdata($challanno, $feetype){
	$data = sqlgetresult('SELECT "amount", "quantity", "totalAmount" FROM tbl_sfs_qty WHERE "challanNo" = \''.$challanno.'\' AND "feeTypes" = \''.$feetype.'\'');
	// print_r($data);
	// exit;
	return $data;
}

function getsfsdatabychn($challanno){
	$data = sqlgetresult('SELECT "amount", "quantity", "totalAmount" FROM tbl_sfs_qty WHERE "challanNo" = \''.$challanno.'\'',true);
	// print_r($data);
	// exit;
	return $data;
}

function createErrorlog($data,$msg = '',$page = 0)
{
	if (!is_dir(BASEPATH."logs/".date('dmY'))) {
		mkdir(BASEPATH."logs/".date('dmY'));
	}
	if( $page == 2) {
		$error_log = fopen(BASEPATH."/logs/".date('dmY')."/demand_log".time().".txt", "a+");
	} elseif ( $page == 3 ) {
		$error_log = fopen(BASEPATH."/logs/".date('dmY')."/payment_log".time().".txt", "a+");
	}
	else {
		$error_log = fopen(BASEPATH."/logs/".date('dmY')."/error_log".time().".txt", "a+");
	}
	
	fwrite($error_log,$data);
	fclose($error_log);

	if($page == 1) {
		$_SESSION['errorpage_content'] = $msg;
		header("location:errorpage.php");
	}
}

function createPDF($studId,$chlno,$type='') {

    $getparentmailid = sqlgetresult('SELECT "email" AS parentMailId FROM tbl_student WHERE "studentId"=\''.$studId . '\'');
    $challanData = sqlgetresult('SELECT * FROM challandata WHERE "challanNo" = \'' . $chlno . '\' AND "challanStatus" = 1 ',true);
    // print_r($challanData);
    $feeTypes = sqlgetresult("SELECT * FROM getFeeTypes");    
    $mailid = $getparentmailid['parentmailid'];
    $to = $mailid;

    $order = array('SCHOOL FEE', 'SCHOOL UTILITY FEE', 'SFS UTILITIES FEE', 'REFUNDABLE DEPOSIT' , 'LATE FEE');

    if($type != '') {
        $datefolder = date("dmY", strtotime($type));
    } else {
        $datefolder = date('dmY');
    }

    if (!is_dir(BASEPATH."receipts/".$datefolder)) {
        mkdir(BASEPATH."receipts/".$datefolder);
    }   
    $documentPath = BASEPATH."receipts/".$datefolder."/";

    $total = 0;
    $feeData = array();  
    $chlncnt = count($challanData);  
    $groupdata = array();

    foreach ($challanData as $k =>$value) {
        $challanno = $value['challanNo'];
        $Semester = $value['term'];
        $challanData1['clid'] = $value['clid'];
        $name = $value['studentName'];
        $studentId = $value['studentId'];
        $className = $value['class_list'];
        $challanData1['duedate'] = $value['duedate'];
        $challanData1['stream'] = $value['stream'];
        $streamName = $value['steamname'];
        $challanData1['org_total'] = $value['org_total'];
        $challanData1['section'] = $value['section'];
        $cheque = $value['cheque_dd_no'];
        $pay_type = $value['pay_type'];
        $pdate = $value['paid_date'];
        $challanData1['academicYear'] = $value['academicYear'];
        $feegroup = $value['feeGroup'];
        if($value['remarks'] != ''){
        $remarks = $value['remarks'];
        }
        else{
        $remarks = 'Nil';

        }
        $feetypearray[getFeeGroupbyId($value['feeGroup'])][$value['feeType']][] = $value['org_total'];
        $feetypearray[getFeeGroupbyId($value['feeGroup'])][$value['feeType']][] = getFeeTypebyId($value['feeType']);

        $chequebankarray[getFeeGroupbyId($value['feeGroup'])]= $value['bank'];

        $chequenoarray[getFeeGroupbyId($value['feeGroup'])]= $value['cheque_dd_no'];

        $feetypearray[getFeeGroupbyId($value['feeGroup'])]['waived'] = getwaiveddata($value['challanNo'], $value['feeGroup']);

        $cnt = $k+1;
        if($cnt == $chlncnt) {
            $groupdata = $feetypearray;

        }
        uksort($groupdata, function ($key1, $key2) use($order)
	    {
	        return (array_search(trim($key1) , $order) > array_search(trim($key2) , $order));
	    });

        // if($feegroup != "LATE FEE" && $value['feeType'] != ""){
        foreach ($groupdata as $key => $feegroup) {
            if(trim($Semester) == 'I'){
                $provisional = "(Provisional)";
            }
            else{
                $provisional = "";
            }
            
            $contract[$key] =' <style> .noBorder td{ border:none} table{border-collapse: collapse} </style>
            <table width="750" border="0" >
                <tr> <td  align="center" valign="top"><img src="images/logo_pdf.jpg"  height="80" /> </td> 
                <td align="right">'.$key.'</td>
                </tr>               
              </table>';
            $contract[$key].='<table width="750" border="1" cellspacing="5" cellpadding="15">
                    <tr>
                    <td colspan="2" align="left" height="25" width="300"><strong>Challan No.</strong>: '.$challanno.' </td>
                    <td colspan="" align="left" height="25" width="250"><strong>Academic Year</strong>: '.getAcademicyrById($challanData1['academicYear']).' </td>
                    <td colspan="" align="left" height="25" width="200"><strong>Date</strong>: '.date("d-m-Y").' </td>
                </tr>               
                <tr>
                    <td align="left" colspan="3" height="25"><strong>Name</strong>: '.$name.' </td>
                    <td align="left" ><strong>Semester</strong>: '.$Semester.' </td>
                </tr>
                <tr>
                    <td align="left" colspan="2" height="25"><strong>Student ID</strong>: '.$studentId.' </td>
                    <td align="left" ><strong>Class</strong>: '.$className.'-'. $challanData1['section'] . $provisional .' </td>
                    <td align="left" ><strong>Due Date</strong>: '.date("d-m-Y", strtotime($challanData1['duedate'])).' </td>
                </tr>               
                <tr>
                    <td align="center" height="25"><strong>S.No</strong> </td>
                    <td align="center" colspan="2" height="25"><strong>Particluars</strong> </td>
                    <td align="center" height="25" ><strong>Amount</strong> </td>
                </tr>';

                $tot = 0;
                $i = 1;
                $contractt = '';
                $wtot = 0;
                if(trim($key) == '10') {
                    $findSFS = sqlgetresult('SELECT * FROM tbl_sfs_qty WHERE "challanNo" = \''.$challanno.'\' ', true);
                    foreach ($findSFS as $v) {
                        $contractt .= '<tr><td colspan="" align="center" height="25">'.$i.'</td>';
                        $contractt .= '<td colspan="2" align="center" height="25">'.getFeeTypebyId($v['feeTypes']).'('.$v['quantity'].')'.'</td>';
                        $contractt .= '<td colspan="" align="right" height="25">'.$v['amount'].'</td>';                    
                        $contractt .= '</tr>';
                        $tot += $v['amount'];
                        $i++;
                    }
                } else {
                    $last_key = end(array_keys($feegroup));
                    $waiveddata = array();

                    foreach ($feegroup as $k => $val) {                       
                        if(trim($k) != 'waived' && $val != 0) {
                            if(trim($val[0]) != 0) {
                                $contractt .= '<tr><td colspan="" align="center" height="25">'.$i.'</td>';
                                $contractt .= '<td colspan="2" align="center" height="25">'.$val[1].'</td>';
                                $contractt .= '<td colspan="" align="right" height="25">'.$val[0].'</td>';
                                $tot += $val[0];
                            }
                            $i++;
                        } 

                        $contractt .= '</tr>';
                                                                               
                        if(trim($k) == 'waived' && $val != 0) {
                            $waiveddata[] =  $val[0]['waiver_type'];
                            $waiveddata[] =  $val[0]['waiver_total']; 
                            $wtot = $val[0]['waiver_total'];
                        }
                        if( $k == $last_key && sizeof($waiveddata) > 0)  {

                                $contractt .= '<tr><td colspan="3" align="right" height="25">Waiver - '.$waiveddata[0].'</td>';
                                $contractt .= '<td colspan="" align="right" height="25">'.$waiveddata[1].'</td>';
                                $contractt .= '</tr>';
                            // }

                        }  
                                       
                    }

                    
                    $amount = $tot - $wtot;                     
                }

                $contract[$key] .= $contractt;
                $contract[$key].='<tr>
                    <td colspan="3" align="right" height="25"><strong>Total</strong></td>
                    <td align="right"> '.$amount.' </td>
                </tr>';
                $contract[$key].='<tr>
                    <td colspan="4" align="left" height="25"><strong>Rupees in words : </strong>'.getCurrencyInWords($amount).' </td>
                </tr>
                <tr>
                    <td align="left" colspan="4" height="25"><strong>Remarks</strong>: '.$remarks.' </td>
                </tr>';
                if( $pay_type == '' ) {
                    $pay_type = 'Online';
                }
                $contract[$key].='<tr>
                    <td align="left" height="25"><strong>Mode of Payment </strong></td>
                    <td colspan="3" align="left" height="25">'.$pay_type.'</td>
                </tr>';

                if( $pay_type != 'Online') {
                    $bank = $value['bank'];
                    $contract[$key].='<tr>
                        <td  align="left" height="25"><strong>Cheque / DD No.</strong></td>
                        <td colspan="" align="left" height="25">'.$chequenoarray[$key].'</td>
                        <td  align="left" height="25"><strong>Date</strong></td>
                        <td colspan="" align="left" height="25">'.$pdate.'</td>
                    </tr>';
                    $contract[$key].='<tr>                
                        <td  align="left" height="25"><strong>Bank</strong></td>
                        <td colspan="" align="left" height="25">'.$chequebankarray[$key].'</td>
                        <td  align="left" height="25"><strong>Branch</strong></td>
                        <td colspan="" align="left" height="25"></td>
                    </tr>';
                    $contract[$key].='<tr>
                        <td  align="left" height="25"><strong>Cashier / Manager</strong></td>
                        <td colspan="" align="left" height="25"></td>
                        <td  align="left" height="25"><strong>Signature of Remitter</strong></td>
                        <td colspan="" align="left" height="25"></td>
                    </tr>';
                }
                
                // $contract[$key].='</table>';  
                $contract[$key].='</table><p>*This is a computer generated receipt and does not require authorization.</p><pagebreak>';         
        }
    // }
        // else{
        //     $contract[$key] =' <style> .noBorder td{ border:none} </style>
        //     <table width="750" border="0" >
        //         <tr> <td  align="center" valign="top"><img src="images/logo_pdf.jpg"  height="80" /> </td> 
        //         <td align="right">'.$feegroup.'</td>
        //         </tr>               
        //       </table>';

        //     $contract[$key].='<table width="750" border="1" cellspacing="5" cellpadding="15">
        //             <tr>
        //             <td colspan="2" align="left" height="25"><strong>Challan No.</strong>: '.$challanno.' </td>
        //             <td colspan="" align="left" height="25"><strong>Academic Year</strong>: '.getAcademicyrById($challanData1['academicYear']).' </td>
        //             <td colspan="" align="left" height="25"><strong>Date</strong>: '.date("d-m-Y").' </td>
        //         </tr>               
        //         <tr>
        //             <td align="left" colspan="3" height="25"><strong>NAME</strong>: '.$name.' </td>
        //             <td align="left" ><strong>SEMESTER</strong>: '.$Semester.' </td>
        //         </tr>
        //         <tr>
        //             <td align="left" colspan="2" height="25"><strong>Student ID</strong>: '.$studentId.' </td>
        //             <td align="left" ><strong>Class</strong>: '.$className.'-'. $challanData1['section'] .' </td>
        //             <td align="left" ><strong>Due Date</strong>: '.date("d-m-Y", strtotime($challanData1['duedate'])).' </td>
        //         </tr>               
        //         <tr>
        //             <td align="center" height="25"><strong>S.No</strong> </td>
        //             <td align="center" colspan="2" height="25"><strong>Particluars</strong> </td>
        //             <td align="center" height="25" ><strong>Amount</strong> </td>
        //         </tr>';
        //         $contracttt = '';
        //         $contracttt .= '<tr><td colspan="" align="center" height="25">'. 1 .'</td>';
        //             $contracttt .= '<td colspan="2" align="center" height="25">'.$feegroup.'</td>';
        //             $contracttt .= '<td colspan="" align="right" height="25">'. $challanData1['org_total'] .'</td>';                       
        //             $contracttt .= '</tr>';
        //         $contract[$key] .= $contracttt;
        //         $contract[$key].='<tr>
        //             <td colspan="3" align="right" height="25"><strong>TOTAL</strong></td>
        //             <td align="right"> '. $challanData1['org_total'].' </td>
        //         </tr>';
        //         $contract[$key].='<tr>
        //             <td colspan="4" align="left" height="25"><strong>Rupees in words : </strong>'.getCurrencyInWords($challanData1['org_total']).' </td>
        //         </tr>
        //         <tr>
        //             <td align="left" colspan="4" height="25"><strong>REMARKS</strong>: '.$remarks.' </td>
        //         </tr>';
        //         if( $pay_type == '' ) {
        //             $pay_type = 'Online';
        //         }
        //         $contract[$key].='<tr>
        //             <td align="left" height="25"><strong>Mode of Payment </strong></td>
        //             <td colspan="3" align="left" height="25">'.$pay_type.'</td>
        //         </tr>';

        //         if( $pay_type != 'Online') {
        //             $bank = $value['bank'];
        //             $contract[$key].='<tr>
        //                 <td  align="left" height="25"><strong>Cheque / DD No.</strong></td>
        //                 <td colspan="" align="left" height="25">'.$cheque.'</td>
        //                 <td  align="left" height="25"><strong>Date</strong></td>
        //                 <td colspan="" align="left" height="25">'.$pdate.'</td>
        //             </tr>';
        //             $contract[$key].='<tr>                
        //                 <td  align="left" height="25"><strong>Bank</strong></td>
        //                 <td colspan="" align="left" height="25">'.$bank.'</td>
        //                 <td  align="left" height="25"><strong>Branch</strong></td>
        //                 <td colspan="" align="left" height="25"></td>
        //             </tr>';
        //             $contract[$key].='<tr>
        //                 <td  align="left" height="25"><strong>Cashier / Manager</strong></td>
        //                 <td colspan="" align="left" height="25"></td>
        //                 <td  align="left" height="25"><strong>Signature of Remitter</strong></td>
        //                 <td colspan="" align="left" height="25"></td>
        //             </tr>';
        //         }
        //         $contract[$k].='</table><p>*This is a computer generated receipt and does not require authorization.</p><pagebreak>';          

        // }   
        $groupdata = array();
        $feeData = array();
    }
    // print_r($contract);
    $data = implode('<br/>',$contract);

    // print_r($data);exit;

	require_once 'vendor/autoload.php';

	$mpdf = new \Mpdf\Mpdf();
	$mpdf->SetWatermarkText('PAID', 0.08);
	$mpdf->showWatermarkText = true; 

	$challanID = str_replace('/', '', trim($challanno));
	// $pdf->convert($data,$documentPath.$challanID.".pdf",1);

	$mpdf->WriteHTML($data);
	$mpdf->Output($documentPath.$challanID.".pdf",'F');

	$mail_content = 'Please find the attached receipt.';

	$sms_content = 'Dear Parent, You have the paid the amount for the challan '.trim($challanno).'/-. Email with receipt will be sent to your registered Email ID.';

	$receiptpath = $documentPath.$challanID.".pdf";
	$subject = 'Paid Challan Receipt';

	sendNotificationToParents($studId, $mail_content, $sms_content,'1', $receiptpath, $subject);

	if($type != '') {
		$_SESSION['success'] = "<p class='success-msg'>Receipt Generated Successfully.</p>";
		header("Location:createreceipt.php");
	} else {
		header("Location:studetscr.php");
	}
 }

 function createNFWPDF($studId,$chlno,$type='') {

	$getparentmailid = sqlgetresult('SELECT "email" AS parentMailId FROM tbl_student WHERE "studentId"=\''.$studId . '\'');
    $challanData = sqlgetresult('SELECT * FROM nonfeechallandata WHERE "studentId" =\''. $studId . '\' AND  "challanNo" = \'' . $chlno . '\' AND "challanStatus" = 1 AND "visible" = \'0\' ',true);
    // print_r('SELECT * FROM nonfeechallandata WHERE "studentId" =\''. $studId . '\' AND  "challanNo" = \'' . $chlno . '\' AND "challanStatus" = 1 AND "visible" = \'0\' ');
	$feeTypes = sqlgetresult("SELECT * FROM tbl_nonfee_type");    
    $mailid = $getparentmailid['parentmailid'];
    $to = $mailid;

    if($type != '') {
    	$datefolder = date("dmY", strtotime($type));
    } else {
    	$datefolder = date('dmY');
    }

    if (!is_dir(BASEPATH."receipts/".$datefolder)) {
		mkdir(BASEPATH."receipts/".$datefolder);
	}	
	$documentPath = BASEPATH."receipts/".$datefolder."/";

    $total = 0;
    $feeData = array();   
    foreach ($challanData as $k=>$value) {
        $challanno = $value['challanNo'];
        $Semester = $value['term'];
        $challanData1['clid'] = $value['clid'];
        $name = $value['studentName'];
        $studentId = $value['studentId'];
        $className = $value['class_list'];
        $challanData1['duedate'] = $value['duedate'];
        $challanData1['stream'] = $value['stream'];
        $streamName = $value['steamname'];
        $challanData1['org_total'] = $value['total'];
        $challanData1['section'] = $value['section'];
        $cheque = $value['cheque_dd_no'];
        $pay_type = $value['pay_type'];
        $pdate = $value['paid_date'];
        $challanData1['academicYear'] = getAcademicyrById($value['academic_yr']);
        $feegroup = $value['feeGroup'];
        if($value['remarks'] != ''){
        $remarks = $value['remarks'];
        }
        else{
        $remarks = 'Nil';

        }

        $feetypedata = sqlgetresult('SELECT * FROM getnonfeetypedata WHERE class=\''.$value['clid'].'\' AND semester=\''.$Semester.'\' AND stream = \''.$value['stream'].'\' AND "academicYear" = \''.$value['academic_yr'].'\' AND "feeType" = \''.$value['feeType'].'\' ');


        $contract[$k] =' <style>.noBorder td{ border:none} table{border-collapse: collapse} .datatbl tr td{border:1px solid #9a9a9a;}</style>
		<table width="750" border="0" >
		  	<tr> <td  align="center" valign="top"><img src="images/logo_pdf.jpg"  height="80" /> </td> 
		  	<td align="right">NON-FEE</td>
		  	</tr>			  	
		  </table>';
		$contract[$k].='<table width="750" border="1" cellspacing="5" cellpadding="15" class="datatbl">
				<tr>
				<td colspan="2" align="left" height="25" width="300"><strong>Challan No.</strong>: '.$challanno.' </td>
				<td colspan="" align="left" height="25" width="250"><strong>Academic Year</strong>: '.$challanData1['academicYear'].' </td>
				<td colspan="" align="left" height="25" width="200"><strong>Date</strong>: '.date("d-m-Y").' </td>
			</tr>				
			<tr>
				<td align="left" colspan="3" height="25"><strong>Name</strong>: '.$name.' </td>
				<td align="left" ><strong>Semester</strong>: '.$Semester.' </td>
			</tr>
			<tr>
				<td align="left" colspan="2" height="25"><strong>Student ID</strong>: '.$studentId.' </td>
				<td align="left" ><strong>Class</strong>: '.$className.'-'. $challanData1['section'] .' </td>
				<td align="left" ><strong>Due Date</strong>: '.date("d-m-Y", strtotime($challanData1['duedate'])).' </td>
			</tr>				
			<tr>
				<td align="center" height="25"><strong>S.No</strong> </td>
				<td align="center" colspan="2" height="25"><strong>Particluars</strong> </td>
				<td align="center" height="25" ><strong>Amount</strong> </td>
			</tr>';

			$contracttt = '';
			$contracttt .= '<tr><td colspan="" align="center" height="25">'. 1 .'</td>';
		    	$contracttt .= '<td colspan="2" align="center" height="25">'.$feetypedata['feename'].'</td>';
		    	$contracttt .= '<td colspan="" align="right" height="25">'. $challanData1['org_total'] .'</td>';		    	       
		    	$contracttt .= '</tr>';
			$contract[$k] .= $contracttt;
			$contract[$k].='<tr>
				<td colspan="3" align="right" height="25"><strong>Total</strong></td>
				<td align="right"> '. $challanData1['org_total'].' </td>
			</tr>';
			$contract[$k].='<tr>
				<td colspan="4" align="left" height="25"><strong>Rupees in words : </strong>'.getCurrencyInWords($challanData1['org_total']).' </td>
			</tr>
			<tr>
				<td align="left" colspan="4" height="25"><strong>Remarks</strong>: '.$remarks.' </td>
			</tr>';
			if( $pay_type == '' ) {
				$pay_type = 'Online';
			}
			$contract[$k].='<tr>
				<td align="left" height="25"><strong>Mode of Payment </strong></td>
				<td colspan="3" align="left" height="25">'.$pay_type.'</td>
			</tr>';

			if( $pay_type != 'Online') {
				$bank = $value['bank'];
				$contract[$k].='<tr>
					<td  align="left" height="25"><strong>Cheque / DD No.</strong></td>
					<td colspan="" align="left" height="25">'.$cheque.'</td>
					<td  align="left" height="25"><strong>Date</strong></td>
					<td colspan="" align="left" height="25">'.$pdate.'</td>
				</tr>';
				$contract[$k].='<tr>				
					<td  align="left" height="25"><strong>Bank</strong></td>
					<td colspan="" align="left" height="25">'.$bank.'</td>
					<td  align="left" height="25"><strong>Branch</strong></td>
					<td colspan="" align="left" height="25"></td>
				</tr>';
				$contract[$k].='<tr>
					<td  align="left" height="25"><strong>Cashier / Manager</strong></td>
					<td colspan="" align="left" height="25"></td>
					<td  align="left" height="25"><strong>Signature of Remitter</strong></td>
					<td colspan="" align="left" height="25"></td>
				</tr>';
			}
			
			$contract[$k].='</table><p>*This is a computer generated receipt and does not require authorization.</p>'; 			
	}
    // print_r($contract);
    $data = implode('<br/>',$contract);

    // print_r($data);exit;

	require_once 'vendor/autoload.php';

	$mpdf = new \Mpdf\Mpdf();
	$mpdf->SetWatermarkText('PAID', 0.08);
	$mpdf->showWatermarkText = true; 

	$challanID = str_replace('/', '', trim($challanno));
	// $pdf->convert($data,$documentPath.$challanID.".pdf",1);

	$mpdf->WriteHTML($data);
	$mpdf->Output($documentPath.$challanID.".pdf",'F');	

	$mail_content = 'Please find the attached receipt.';

	$sms_content = 'Dear Parent, You have the paid the amount for the challan '.trim($challanno).'/-. Email with receipt will be sent to your registered Email ID.';

	$receiptpath = $documentPath.$challanID.".pdf";
	$subject = 'Paid Challan Receipt';
	$type = 1;

	sendNotificationToParents($studId, $mail_content, $sms_content,$type, $receiptpath, $subject);

	if($type != '') {
		$_SESSION['success'] = "<p class='success-msg'>Receipt Generated Successfully.</p>";
		header("Location:createreceipt.php");
	} else {
		header("Location:studetscr.php");
	}
}

// function createPDF($studId,$chlno,$type='') {

// 	$getparentmailid = sqlgetresult('SELECT "email" AS parentMailId FROM tbl_student WHERE "studentId"=\''.$studId . '\'');
//     $challanData = sqlgetresult('SELECT * FROM challandatanew WHERE "studentId" =\''. $studId . '\' AND  "challanNo" = \'' . $chlno . '\' AND "challanStatus" = 1 ',true);
//     // print_r($challanData);
// 	$feeTypes = sqlgetresult("SELECT * FROM getFeeTypes");    
//     $mailid = $getparentmailid['parentmailid'];
//     $to = $mailid;

//     if($type != '') {
//     	$datefolder = date("dmY", strtotime($type));
//     } else {
//     	$datefolder = date('dmY');
//     }

//     if (!is_dir(BASEPATH."receipts/".$datefolder)) {
// 		mkdir(BASEPATH."receipts/".$datefolder);
// 	}	
// 	$documentPath = BASEPATH."receipts/".$datefolder."/";

//     $total = 0;
//     $feeData = array();   
//     foreach ($challanData as $k=>$value) {
//         $challanno = $value['challanNo'];
//         $Semester = $value['term'];
//         $challanData1['clid'] = $value['clid'];
//         $name = $value['studentName'];
//         $studentId = $value['studentId'];
//         $className = $value['class_list'];
//         $challanData1['duedate'] = $value['duedate'];
//         $challanData1['stream'] = $value['stream'];
//         $streamName = $value['steamname'];
//         $challanData1['org_total'] = $value['org_total'];
//         $challanData1['section'] = $value['section'];
//         $cheque = $value['cheque_dd_no'];
//         $pay_type = $value['pay_type'];
//         $pdate = $value['paid_date'];
//         $challanData1['academicYear'] = $value['academic_yr'];
//         $challanData1['waivedTotal'] = $value['waivedTotal'];
//         $feegroup = $value['feeGroup'];
//         if($value['remarks'] != ''){
//         $remarks = $value['remarks'];
//         }
//         else{
//         $remarks = 'Nil';

//         }

//         if($feegroup != "LATE FEE" && $value['feeTypes'] != ""){

//         $feetype = explode(',',$value['feeTypes']);
//         foreach ($feetype as $v) {
//             $feeData[trim($v)][] = $value['feeGroup'];
//             $feeData[trim($v)][] = $value['org_total'];
//         }

//         $feetypedata = sqlgetresult('SELECT * FROM getfeetypedata WHERE class=\''.$challanData1['clid'].'\' AND semester=\''.$Semester.'\' AND stream = \''.$challanData1['stream'].'\' AND "academicYear" = \''.$challanData1['academicYear'].'\' ',true);
//         // print_r($feetypedata);
// 	    foreach ($feeData as $id=>$fee) {
// 	        foreach($feetypedata as $val){
// 	        	if ( trim($id) == trim($val['feeType']) && $val['amount'] != '0' ) {	           
// 	                $total  = $val['amount'];
// 	                $groupdata[$val['feeGroup']][$val['id']][] = $val['amount'];
// 	                $groupdata[$val['feeGroup']][$val['id']][] = $val['feename'];
// 	            } 
// 	        }
// 	    }
            
// 	    foreach ($groupdata as $key => $feegroup) {
	    	
//             $contract[$k] =' <style>.noBorder td{ border:none} table{border-collapse: collapse} .datatbl tr td{border:1px solid #9a9a9a;}</style>
// 			<table width="750" border="0" >
// 			  	<tr> <td  align="center" valign="top"><img src="images/logo_pdf.jpg"  height="80" /> </td> 
// 			  	<td align="right">'.getFeeGroupbyId($key).'</td>
// 			  	</tr>			  	
// 			  </table>';
// 			$contract[$k].='<table width="750" border="1" cellspacing="5" cellpadding="15" class="datatbl">
// 					<tr>
// 					<td colspan="2" align="left" height="25" width="300"><strong>Challan No.</strong>: '.$challanno.' </td>
// 					<td colspan="" align="left" height="25" width="250"><strong>Academic Year</strong>: '.$challanData1['academicYear'].' </td>
// 					<td colspan="" align="left" height="25" width="200"><strong>Date</strong>: '.date("d-m-Y").' </td>
// 				</tr>				
// 				<tr>
// 					<td align="left" colspan="3" height="25"><strong>Name</strong>: '.$name.' </td>
// 					<td align="left" ><strong>Semester</strong>: '.$Semester.' </td>
// 				</tr>
// 				<tr>
// 					<td align="left" colspan="2" height="25"><strong>Student ID</strong>: '.$studentId.' </td>
// 					<td align="left" ><strong>Class</strong>: '.$className.'-'. $challanData1['section'] .' </td>
// 					<td align="left" ><strong>Due Date</strong>: '.date("d-m-Y", strtotime($challanData1['duedate'])).' </td>
// 				</tr>				
// 				<tr>
// 					<td align="center" height="25"><strong>S.No</strong> </td>
// 					<td align="center" colspan="2" height="25"><strong>Particluars</strong> </td>
// 					<td align="center" height="25" ><strong>Amount</strong> </td>
// 				</tr>';

// 				$tot = 0;
// 			    $i = 1;
// 			    // print_r($groupdata);
// 			    $contractt = '';
// 			    if(trim($key) == '10') {			    	
// 			    	foreach ($feegroup as $k=>$v) {
// 			    		$findSFS = sqlgetresult('SELECT * FROM tbl_sfs_qty WHERE "challanNo" = \''.$challanno.'\' AND  "feeTypes" = \''.$k.'\'  ');
// 			    		$contractt .= '<tr><td colspan="" align="center" height="25">'.$i.'</td>';
// 				    	$contractt .= '<td colspan="2" align="center" height="25">'.getFeeTypebyId($findSFS['feeTypes']).'('.$findSFS['quantity'].')'.'</td>';
// 				    	$contractt .= '<td colspan="" align="right" height="25">'.$findSFS['totalAmount'].'</td>';		    	       
// 				    	$contractt .= '</tr>';
// 				    	$tot += $findSFS['totalAmount'];
// 				    	$i++;
// 			    	}
// 			    } else {
// 				   	foreach ($feegroup as $v) {
// 			    		$contractt .= '<tr><td colspan="" align="center" height="25">'.$i.'</td>';
// 				    	$contractt .= '<td colspan="2" align="center" height="25">'.$v[1].'</td>';
// 				    	$contractt .= '<td colspan="" align="right" height="25">'.$v[0].'</td>';		    	       
// 				    	$contractt .= '</tr>';
// 				    	$tot += $v[0];
// 				    	$i++;
// 			    	}
// 			    }

// 				$contract[$k] .= $contractt;
// 				$contract[$k].='<tr>
// 					<td colspan="3" align="right" height="25"><strong>Total</strong></td>
// 					<td align="right"> '.$tot.' </td>
// 				</tr>';
// 				$originaltotal = $tot - $challanData1['waivedTotal'];
// 				if($challanData1['waivedTotal'] != "" && $challanData1['waivedTotal'] != 0 ){
// 					$contract[$k].='<tr>
// 					<td colspan="3" align="right" height="25"><strong>Waived</strong></td>
// 					<td align="right"> '.$challanData1['waivedTotal'].' </td>
// 				</tr>';
// 				$contract[$k].='<tr>
// 					<td colspan="3" align="right" height="25"><strong>Original Amount</strong></td>
// 					<td align="right"> '.$originaltotal.' </td>
// 				</tr>';
// 				}
// 				$contract[$k].='<tr>
// 					<td colspan="4" align="left" height="25"><strong>Rupees in words : </strong>'.getCurrencyInWords($originaltotal).' </td>
// 				</tr>
// 				<tr>
// 					<td align="left" colspan="4" height="25"><strong>Remarks</strong>: '.$remarks.' </td>
// 				</tr>';
// 				if( $pay_type == '' ) {
// 					$pay_type = 'Online';
// 				}
// 				$contract[$k].='<tr>
// 					<td align="left" height="25"><strong>Mode of Payment </strong></td>
// 					<td colspan="3" align="left" height="25">'.$pay_type.'</td>
// 				</tr>';

// 				if( $pay_type != 'Online') {
// 					$bank = $value['bank'];
// 					$contract[$k].='<tr>
// 						<td  align="left" height="25"><strong>Cheque / DD No.</strong></td>
// 						<td colspan="" align="left" height="25">'.$cheque.'</td>
// 						<td  align="left" height="25"><strong>Date</strong></td>
// 						<td colspan="" align="left" height="25">'.$pdate.'</td>
// 					</tr>';
// 					$contract[$k].='<tr>				
// 						<td  align="left" height="25"><strong>Bank</strong></td>
// 						<td colspan="" align="left" height="25">'.$bank.'</td>
// 						<td  align="left" height="25"><strong>Branch</strong></td>
// 						<td colspan="" align="left" height="25"></td>
// 					</tr>';
// 					$contract[$k].='<tr>
// 						<td  align="left" height="25"><strong>Cashier / Manager</strong></td>
// 						<td colspan="" align="left" height="25"></td>
// 						<td  align="left" height="25"><strong>Signature of Remitter</strong></td>
// 						<td colspan="" align="left" height="25"></td>
// 					</tr>';
// 				}
				
// 				$contract[$k].='</table><p>*This is a computer generated challan and does not require authorization.</p><pagebreak>'; 			
// 	    }
// 	}
// 	    else{
// 	    					// print_r($feegroup);
// 		    $contract[$k] =' <style> .noBorder td{ border:none} table{border-collapse: collapse} .datatbl tr td{border:1px solid #9a9a9a;}</style>
// 			<table width="750" border="0" >
// 			  	<tr> <td  align="center" valign="top"><img src="images/logo_pdf.jpg"  height="80" /> </td> 
// 			  	<td align="right">'.$feegroup.'</td>
// 			  	</tr>			  	
// 			  </table>';

// 			$contract[$k].='<table width="750" border="1" cellspacing="5" cellpadding="15" class="datatbl">
// 					<tr>
// 					<td colspan="2" align="left" height="25"><strong>Challan No.</strong>: '.$challanno.' </td>
// 					<td colspan="" align="left" height="25"><strong>Academic Year</strong>: '.$challanData1['academicYear'].' </td>
// 					<td colspan="" align="left" height="25"><strong>Date</strong>: '.date("d-m-Y").' </td>
// 				</tr>				
// 				<tr>
// 					<td align="left" colspan="3" height="25"><strong>Name</strong>: '.$name.' </td>
// 					<td align="left" ><strong>Semester</strong>: '.$Semester.' </td>
// 				</tr>
// 				<tr>
// 					<td align="left" colspan="2" height="25"><strong>Student ID</strong>: '.$studentId.' </td>
// 					<td align="left" ><strong>Class</strong>: '.$className.'-'. $challanData1['section'] .' </td>
// 					<td align="left" ><strong>Due Date</strong>: '.date("d-m-Y", strtotime($challanData1['duedate'])).' </td>
// 				</tr>				
// 				<tr>
// 					<td align="center" height="25"><strong>S.No</strong> </td>
// 					<td align="center" colspan="2" height="25"><strong>Particluars</strong> </td>
// 					<td align="center" height="25" ><strong>Amount</strong> </td>
// 				</tr>';
// 			    $contracttt = '';
// 				$contracttt .= '<tr><td colspan="" align="center" height="25">'. 1 .'</td>';
// 			    	$contracttt .= '<td colspan="2" align="center" height="25">'.$feegroup.'</td>';
// 			    	$contracttt .= '<td colspan="" align="right" height="25">'. $challanData1['org_total'] .'</td>';		    	       
// 			    	$contracttt .= '</tr>';
// 				$contract[$k] .= $contracttt;
// 				$contract[$k].='<tr>
// 					<td colspan="3" align="right" height="25"><strong>Total</strong></td>
// 					<td align="right"> '. $challanData1['org_total'].' </td>
// 				</tr>';
// 				$originaltotal = $challanData1['org_total'] - $challanData1['waivedTotal'];
// 				if($$challanData1['waivedTotal'] != ""){
// 					$contract[$k].='<tr>
// 					<td colspan="3" align="right" height="25"><strong>Waived</strong></td>
// 					<td align="right"> '.$challanData1['waivedTotal'].' </td>
// 				</tr>';
// 				$contract[$k].='<tr>
// 					<td colspan="3" align="right" height="25"><strong>Original Amount</strong></td>
// 					<td align="right"> '.$originaltotal.' </td>
// 				</tr>';
// 				}
// 				$contract[$k].='<tr>
// 					<td colspan="4" align="left" height="25"><strong>Rupees in words : </strong>'.getCurrencyInWords($originaltotal).' </td>
// 				</tr>
// 				<tr>
// 					<td align="left" colspan="4" height="25"><strong>Remarks</strong>: '.$remarks.' </td>
// 				</tr>';
// 			// }
// 		// }
// 				if( $pay_type == '' ) {
// 					$pay_type = 'Online';
// 				}
// 				$contract[$k].='<tr>
// 					<td align="left" height="25"><strong>Mode of Payment </strong></td>
// 					<td colspan="3" align="left" height="25">'.$pay_type.'</td>
// 				</tr>';

// 				if( $pay_type != 'Online') {
// 					$bank = $value['bank'];
// 					$contract[$k].='<tr>
// 						<td  align="left" height="25"><strong>Cheque / DD No.</strong></td>
// 						<td colspan="" align="left" height="25">'.$cheque.'</td>
// 						<td  align="left" height="25"><strong>Date</strong></td>
// 						<td colspan="" align="left" height="25">'.$pdate.'</td>
// 					</tr>';
// 					$contract[$k].='<tr>				
// 						<td  align="left" height="25"><strong>Bank</strong></td>
// 						<td colspan="" align="left" height="25">'.$bank.'</td>
// 						<td  align="left" height="25"><strong>Branch</strong></td>
// 						<td colspan="" align="left" height="25"></td>
// 					</tr>';
// 					$contract[$k].='<tr>
// 						<td  align="left" height="25"><strong>Cashier / Manager</strong></td>
// 						<td colspan="" align="left" height="25"></td>
// 						<td  align="left" height="25"><strong>Signature of Remitter</strong></td>
// 						<td colspan="" align="left" height="25"></td>
// 					</tr>';
// 				}
// 				$contract[$k].='</table><p>*This is a computer generated challan and does not require authorization.</p><pagebreak>'; 			

// 	    }
// 	    $groupdata = array();
// 	    $feeData = array();	
//     }
//     // print_r($contract);
//     $data = implode('<br/>',$contract);

//     // print_r($data);exit;

// 	require_once 'vendor/autoload.php';

// 	$mpdf = new \Mpdf\Mpdf();
// 	$mpdf->SetWatermarkText('PAID', 0.08);
// 	$mpdf->showWatermarkText = true; 

// 	$challanID = str_replace('/', '', trim($challanno));
// 	// $pdf->convert($data,$documentPath.$challanID.".pdf",1);

// 	$mpdf->WriteHTML($data);
// 	$mpdf->Output($documentPath.$challanID.".pdf",'F');

// 	$mail_content = 'Please find the attached receipt.';

// 	$sms_content = 'Dear Parent, You have the paid the amount for the challan '.trim($challanno).'/-. Email with receipt will be sent to your registered Email ID.';

// 	$receiptpath = $documentPath.$challanID.".pdf";
// 	$subject = 'Paid Challan Receipt';
// 	$type = 1;

// 	sendNotificationToParents($studId, $mail_content, $sms_content,$type, $receiptpath, $subject);

// 	if($type != '') {
// 		$_SESSION['success'] = "<p class='success-msg'>Receipt Generated Successfully.</p>";
// 		header("Location:createreceipt.php");
// 	} else {
// 		header("Location:studetscr.php");
// 	}
//  }

  function getCurrencyInWords($number,$status = 0)
 {
    $decimal = round($number - ($no = floor($number)), 2) * 100;
    $hundred = null;
    $digits_length = strlen($no);
    $i = 0;
    $str = array();
    $words = array(0 => '', 1 => 'one', 2 => 'two',
        3 => 'three', 4 => 'four', 5 => 'five', 6 => 'six',
        7 => 'seven', 8 => 'eight', 9 => 'nine',
        10 => 'ten', 11 => 'eleven', 12 => 'twelve',
        13 => 'thirteen', 14 => 'fourteen', 15 => 'fifteen',
        16 => 'sixteen', 17 => 'seventeen', 18 => 'eighteen',
        19 => 'nineteen', 20 => 'twenty', 30 => 'thirty',
        40 => 'forty', 50 => 'fifty', 60 => 'sixty',
        70 => 'seventy', 80 => 'eighty', 90 => 'ninety');
    $digits = array('', 'hundred','thousand','lakh', 'crore');
    while( $i < $digits_length ) {
        $divider = ($i == 2) ? 10 : 100;
        $number = floor($no % $divider);
        $no = floor($no / $divider);
        $i += $divider == 10 ? 1 : 2;
        if ($number) {
            $plural = (($counter = count($str)) && $number > 9) ? '' : null;
            $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
            $str [] = ($number < 21) ? $words[$number].' '. $digits[$counter]. $plural.' '.$hundred:$words[floor($number / 10) * 10].' '.$words[$number % 10]. ' '.$digits[$counter].$plural.' '.$hundred;
        } else $str[] = null;
    }
    $Rupees = implode('', array_reverse($str));
    $paise = ($decimal) ? "." . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . ' Paise' : '';
    if($status = 0) {
    	return ($Rupees ? strtoupper($Rupees) . 'ONLY ' : '') . $paise ;
	} elseif($status = 1) {
    	return ($Rupees ? ucwords($Rupees) . 'Only' : '') . $paise ;
	}
}

function contains($str, array $arr)
{
    foreach($arr as $a) {
        if (stripos($str,$a) !== false) return true;
    }
    return false;
}
function removeFromString($str, $item) {
    $parts = explode(',', $str);

    while(($i = array_search($item, $parts)) !== false) {
        unset($parts[$i]);
    }

    return implode(',', $parts);
}

function createNFPDF($studId,$chlno,$type='') {

	$getparentmailid = sqlgetresult('SELECT "email" AS parentMailId FROM tbl_student WHERE "studentId"=\''.$studId . '\'');
    $challanData = sqlgetresult('SELECT * FROM nonfeechallandata WHERE "studentId" =\''. $studId . '\' AND  "challanNo" = \'' . $chlno . '\' AND "challanStatus" = 1 AND "visible" = \'1\' ',true);
    // print_r($challanData);
	$feeTypes = sqlgetresult("SELECT * FROM tbl_nonfee_type");    
    $mailid = $getparentmailid['parentmailid'];
    $to = $mailid;

    if($type != '') {
    	$datefolder = date("dmY", strtotime($type));
    } else {
    	$datefolder = date('dmY');
    }

    if (!is_dir(BASEPATH."receipts/".$datefolder)) {
		mkdir(BASEPATH."receipts/".$datefolder);
	}	
	$documentPath = BASEPATH."receipts/".$datefolder."/";

    $total = 0;
    $feeData = array();   
    foreach ($challanData as $k=>$value) {
        $challanno = $value['challanNo'];
        $Semester = $value['term'];
        $challanData1['clid'] = $value['clid'];
        $name = $value['studentName'];
        $studentId = $value['studentId'];
        $className = $value['class_list'];
        $challanData1['duedate'] = $value['duedate'];
        $challanData1['stream'] = $value['stream'];
        $streamName = $value['steamname'];
        $challanData1['org_total'] = $value['total'];
        $challanData1['section'] = $value['section'];
        $cheque = $value['cheque_dd_no'];
        $pay_type = $value['pay_type'];
        $pdate = $value['paid_date'];
        $challanData1['academicYear'] = getAcademicyrById($value['academic_yr']);
        $feegroup = $value['feeGroup'];
        if($value['remarks'] != ''){
        $remarks = $value['remarks'];
        }
        else{
        $remarks = 'Nil';

        }

        $feetypedata = sqlgetresult('SELECT * FROM getnonfeetypedata WHERE class=\''.$value['clid'].'\' AND semester=\''.$Semester.'\' AND stream = \''.$value['stream'].'\' AND "academicYear" = \''.$value['academic_yr'].'\' AND "feeType" = \''.$value['feeType'].'\' ');


        $contract[$k] =' <style>.noBorder td{ border:none} table{border-collapse: collapse} .datatbl tr td{border:1px solid #9a9a9a;}</style>
		<table width="750" border="0" >
		  	<tr> <td  align="center" valign="top"><img src="images/logo_pdf.jpg"  height="80" /> </td> 
		  	<td align="right">NON-FEE</td>
		  	</tr>			  	
		  </table>';
		$contract[$k].='<table width="750" border="1" cellspacing="5" cellpadding="15" class="datatbl">
				<tr>
				<td colspan="2" align="left" height="25" width="300"><strong>Challan No.</strong>: '.$challanno.' </td>
				<td colspan="" align="left" height="25" width="250"><strong>Academic Year</strong>: '.$challanData1['academicYear'].' </td>
				<td colspan="" align="left" height="25" width="200"><strong>Date</strong>: '.date("d-m-Y").' </td>
			</tr>				
			<tr>
				<td align="left" colspan="3" height="25"><strong>Name</strong>: '.$name.' </td>
				<td align="left" ><strong>Semester</strong>: '.$Semester.' </td>
			</tr>
			<tr>
				<td align="left" colspan="2" height="25"><strong>Student ID</strong>: '.$studentId.' </td>
				<td align="left" ><strong>Class</strong>: '.$className.'-'. $challanData1['section'] .' </td>
				<td align="left" ><strong>Due Date</strong>: '.date("d-m-Y", strtotime($challanData1['duedate'])).' </td>
			</tr>				
			<tr>
				<td align="center" height="25"><strong>S.No</strong> </td>
				<td align="center" colspan="2" height="25"><strong>Particluars</strong> </td>
				<td align="center" height="25" ><strong>Amount</strong> </td>
			</tr>';

			$contracttt = '';
			$contracttt .= '<tr><td colspan="" align="center" height="25">'. 1 .'</td>';
		    	$contracttt .= '<td colspan="2" align="center" height="25">'.$feetypedata['feename'].'</td>';
		    	$contracttt .= '<td colspan="" align="right" height="25">'. $challanData1['org_total'] .'</td>';		    	       
		    	$contracttt .= '</tr>';
			$contract[$k] .= $contracttt;
			$contract[$k].='<tr>
				<td colspan="3" align="right" height="25"><strong>Total</strong></td>
				<td align="right"> '. $challanData1['org_total'].' </td>
			</tr>';
			$contract[$k].='<tr>
				<td colspan="4" align="left" height="25"><strong>Rupees in words : </strong>'.getCurrencyInWords($challanData1['org_total']).' </td>
			</tr>
			<tr>
				<td align="left" colspan="4" height="25"><strong>Remarks</strong>: '.$remarks.' </td>
			</tr>';
			if( $pay_type == '' ) {
				$pay_type = 'Online';
			}
			$contract[$k].='<tr>
				<td align="left" height="25"><strong>Mode of Payment </strong></td>
				<td colspan="3" align="left" height="25">'.$pay_type.'</td>
			</tr>';

			if( $pay_type != 'Online') {
				$bank = $value['bank'];
				$contract[$k].='<tr>
					<td  align="left" height="25"><strong>Cheque / DD No.</strong></td>
					<td colspan="" align="left" height="25">'.$cheque.'</td>
					<td  align="left" height="25"><strong>Date</strong></td>
					<td colspan="" align="left" height="25">'.$pdate.'</td>
				</tr>';
				$contract[$k].='<tr>				
					<td  align="left" height="25"><strong>Bank</strong></td>
					<td colspan="" align="left" height="25">'.$bank.'</td>
					<td  align="left" height="25"><strong>Branch</strong></td>
					<td colspan="" align="left" height="25"></td>
				</tr>';
				$contract[$k].='<tr>
					<td  align="left" height="25"><strong>Cashier / Manager</strong></td>
					<td colspan="" align="left" height="25"></td>
					<td  align="left" height="25"><strong>Signature of Remitter</strong></td>
					<td colspan="" align="left" height="25"></td>
				</tr>';
			}
			
			$contract[$k].='</table><p>*This is a computer generated receipt and does not require authorization.</p>'; 			
	}
    // print_r($contract);
    $data = implode('<br/>',$contract);

    // print_r($data);exit;

	require_once 'vendor/autoload.php';

	$mpdf = new \Mpdf\Mpdf();
	$mpdf->SetWatermarkText('PAID', 0.08);
	$mpdf->showWatermarkText = true; 

	$challanID = str_replace('/', '', trim($challanno));
	// $pdf->convert($data,$documentPath.$challanID.".pdf",1);

	$mpdf->WriteHTML($data);
	$mpdf->Output($documentPath.$challanID.".pdf",'F');

	// if($type != '') {
	// 	$_SESSION['success'] = "<p class='success-msg'>Receipt Generated Successfully.</p>";
	// 	header("Location:createreceipt.php");
	// } else {
	// 	header("Location:nonfeepayments.php");
	// } 
}

// function createNFWPDF($studId,$chlno,$type='') {

// 	$getparentmailid = sqlgetresult('SELECT "email" AS parentMailId FROM tbl_student WHERE "studentId"=\''.$studId . '\'');
//     $challanData = sqlgetresult('SELECT * FROM nonfeechallandata WHERE "studentId" =\''. $studId . '\' AND  "challanNo" = \'' . $chlno . '\' AND "challanStatus" = 1 AND "visible" = \'0\' ',true);
//     // print_r('SELECT * FROM nonfeechallandata WHERE "studentId" =\''. $studId . '\' AND  "challanNo" = \'' . $chlno . '\' AND "challanStatus" = 1 AND "visible" = \'0\' ');
// 	$feeTypes = sqlgetresult("SELECT * FROM tbl_nonfee_type");    
//     $mailid = $getparentmailid['parentmailid'];
//     $to = $mailid;

//     if($type != '') {
//     	$datefolder = date("dmY", strtotime($type));
//     } else {
//     	$datefolder = date('dmY');
//     }

//     if (!is_dir(BASEPATH."receipts/".$datefolder)) {
// 		mkdir(BASEPATH."receipts/".$datefolder);
// 	}	
// 	$documentPath = BASEPATH."receipts/".$datefolder."/";

//     $total = 0;
//     $feeData = array();   
//     foreach ($challanData as $k=>$value) {
//         $challanno = $value['challanNo'];
//         $Semester = $value['term'];
//         $challanData1['clid'] = $value['clid'];
//         $name = $value['studentName'];
//         $studentId = $value['studentId'];
//         $className = $value['class_list'];
//         $challanData1['duedate'] = $value['duedate'];
//         $challanData1['stream'] = $value['stream'];
//         $streamName = $value['steamname'];
//         $challanData1['org_total'] = $value['total'];
//         $challanData1['section'] = $value['section'];
//         $cheque = $value['cheque_dd_no'];
//         $pay_type = $value['pay_type'];
//         $pdate = $value['paid_date'];
//         $challanData1['academicYear'] = $value['academic_yr'];
//         $feegroup = $value['feeGroup'];
//         if($value['remarks'] != ''){
//         $remarks = $value['remarks'];
//         }
//         else{
//         $remarks = 'Nil';

//         }

//         $feetypedata = sqlgetresult('SELECT * FROM getnonfeetypedata WHERE class=\''.$value['clid'].'\' AND semester=\''.$Semester.'\' AND stream = \''.$value['stream'].'\' AND "academicYear" = \''.getAcademicyrIdByName($value['academic_yr']).'\' AND "feeType" = \''.$value['feeType'].'\' ');


//         $contract[$k] =' <style>.noBorder td{ border:none} table{border-collapse: collapse} .datatbl tr td{border:1px solid #9a9a9a;}</style>
// 		<table width="750" border="0" >
// 		  	<tr> <td  align="center" valign="top"><img src="images/logo_pdf.jpg"  height="80" /> </td> 
// 		  	<td align="right">NON-FEE</td>
// 		  	</tr>			  	
// 		  </table>';
// 		$contract[$k].='<table width="750" border="1" cellspacing="5" cellpadding="15" class="datatbl">
// 				<tr>
// 				<td colspan="2" align="left" height="25" width="300"><strong>Challan No.</strong>: '.$challanno.' </td>
// 				<td colspan="" align="left" height="25" width="250"><strong>Academic Year</strong>: '.$challanData1['academicYear'].' </td>
// 				<td colspan="" align="left" height="25" width="200"><strong>Date</strong>: '.date("d-m-Y").' </td>
// 			</tr>				
// 			<tr>
// 				<td align="left" colspan="3" height="25"><strong>Name</strong>: '.$name.' </td>
// 				<td align="left" ><strong>Semester</strong>: '.$Semester.' </td>
// 			</tr>
// 			<tr>
// 				<td align="left" colspan="2" height="25"><strong>Student ID</strong>: '.$studentId.' </td>
// 				<td align="left" ><strong>Class</strong>: '.$className.'-'. $challanData1['section'] .' </td>
// 				<td align="left" ><strong>Due Date</strong>: '.date("d-m-Y", strtotime($challanData1['duedate'])).' </td>
// 			</tr>				
// 			<tr>
// 				<td align="center" height="25"><strong>S.No</strong> </td>
// 				<td align="center" colspan="2" height="25"><strong>Particluars</strong> </td>
// 				<td align="center" height="25" ><strong>Amount</strong> </td>
// 			</tr>';

// 			$contracttt = '';
// 			$contracttt .= '<tr><td colspan="" align="center" height="25">'. 1 .'</td>';
// 		    	$contracttt .= '<td colspan="2" align="center" height="25">'.$feetypedata['feename'].'</td>';
// 		    	$contracttt .= '<td colspan="" align="right" height="25">'. $challanData1['org_total'] .'</td>';		    	       
// 		    	$contracttt .= '</tr>';
// 			$contract[$k] .= $contracttt;
// 			$contract[$k].='<tr>
// 				<td colspan="3" align="right" height="25"><strong>Total</strong></td>
// 				<td align="right"> '. $challanData1['org_total'].' </td>
// 			</tr>';
// 			$contract[$k].='<tr>
// 				<td colspan="4" align="left" height="25"><strong>Rupees in words : </strong>'.getCurrencyInWords($challanData1['org_total']).' </td>
// 			</tr>
// 			<tr>
// 				<td align="left" colspan="4" height="25"><strong>Remarks</strong>: '.$remarks.' </td>
// 			</tr>';
// 			if( $pay_type == '' ) {
// 				$pay_type = 'Online';
// 			}
// 			$contract[$k].='<tr>
// 				<td align="left" height="25"><strong>Mode of Payment </strong></td>
// 				<td colspan="3" align="left" height="25">'.$pay_type.'</td>
// 			</tr>';

// 			if( $pay_type != 'Online') {
// 				$bank = $value['bank'];
// 				$contract[$k].='<tr>
// 					<td  align="left" height="25"><strong>Cheque / DD No.</strong></td>
// 					<td colspan="" align="left" height="25">'.$cheque.'</td>
// 					<td  align="left" height="25"><strong>Date</strong></td>
// 					<td colspan="" align="left" height="25">'.$pdate.'</td>
// 				</tr>';
// 				$contract[$k].='<tr>				
// 					<td  align="left" height="25"><strong>Bank</strong></td>
// 					<td colspan="" align="left" height="25">'.$bank.'</td>
// 					<td  align="left" height="25"><strong>Branch</strong></td>
// 					<td colspan="" align="left" height="25"></td>
// 				</tr>';
// 				$contract[$k].='<tr>
// 					<td  align="left" height="25"><strong>Cashier / Manager</strong></td>
// 					<td colspan="" align="left" height="25"></td>
// 					<td  align="left" height="25"><strong>Signature of Remitter</strong></td>
// 					<td colspan="" align="left" height="25"></td>
// 				</tr>';
// 			}
			
// 			$contract[$k].='</table><p>*This is a computer generated challan and does not require authorization.</p>';
// 			$mailcontent = 'You have paid the amount of '.$challanData1['org_total'].' for the '.$feetypedata['feename'].' successfully.';
// 			$smscontent = 'Dear Parent, You have paid the amount of '.$challanData1['org_total'].' for the '.$feetypedata['feename'].'';

// 			sendNotificationToParents($studentId, $mailcontent, $smscontent,'nonfeechallan'); 			
// 	}
//     // print_r($contract);
//     $data = implode('<br/>',$contract);

//     // print_r($data);exit;

// 	require_once 'vendor/autoload.php';

// 	$mpdf = new \Mpdf\Mpdf();
// 	$mpdf->SetWatermarkText('PAID', 0.08);
// 	$mpdf->showWatermarkText = true; 

// 	$challanID = str_replace('/', '', trim($challanno));
// 	// $pdf->convert($data,$documentPath.$challanID.".pdf",1);

// 	$mpdf->WriteHTML($data);
// 	$mpdf->Output($documentPath.$challanID.".pdf",'F');	


// }

function createCFPDF($id, $type='') {

	if($type == ''){
		$data = sqlgetresult('SELECT * FROM topupdata WHERE tpid = \''.$id.'\' ');	 
		$className = $data['class_list']; 
		$streamName = $data['steamname'];  
		$displayName = "Card Top-up"; 
	}
	else{
		$data = sqlgetresult('SELECT p."amount",p."challanNo",p."createdOn",p."classList" AS class,p."stream",p."term",s."studentName",s."studentId",p."section",p."academicYear" AS "academic_yr"  FROM tbl_nonfee_payments p LEFT JOIN tbl_student s ON s."studentId" = p."studentId" WHERE p.id = \''.$id.'\' AND "challanNo" ILIKE \'%EVENT%\' ');
		$className = getClassbyNameId($data['class']); 
		$eventid = explode('-', $data['challanNo']);
		$displayName = getEventNamebyid($eventid[1]);
		$streamName = getStreambyId($data['stream']);
	}
    

    $datefolder = date("dmY", strtotime($data['createdOn']));

    if (!is_dir(BASEPATH."receipts/".$datefolder)) {
		mkdir(BASEPATH."receipts/".$datefolder);
	}	
	$documentPath = BASEPATH."receipts/".$datefolder."/";

    $Semester = $data['term'];
    $name = $data['studentName'];
    $studentId = $data['studentId'];
    $challanData1['org_total'] = $data['amount'];
    $challanData1['section'] = $data['section'];    
    $challanData1['academicYear'] = getAcademicyrById($data['academic_yr']);  


    $contract =' <style>.noBorder td{ border:none} table{border-collapse: collapse} .datatbl tr td{border:1px solid #9a9a9a;}</style>
    <table width="750" border="0" >
        <tr> <td  align="center" valign="top"><img src="images/logo_pdf.jpg"  height="80" /> </td> 
        <td align="right">NON-FEE</td>
        </tr>               
      </table>';
    $contract.='<table width="750" border="1" cellspacing="5" cellpadding="15" class="datatbl">
            <tr>
            <td colspan="3" align="left" height="25" width="250"><strong>Academic Year</strong>: '.$challanData1['academicYear'].' </td>
            <td colspan="" align="left" height="25" width="200"><strong>Date</strong>: '.date("d-m-Y").' </td>
        </tr>               
        <tr>
            <td align="left" colspan="3" height="25"><strong>Name</strong>: '.$name.' </td>
            <td align="left" ><strong>Semester</strong>: '.$Semester.' </td>
        </tr>
        <tr>
            <td align="left" colspan="3" height="25"><strong>Student ID</strong>: '.$studentId.' </td>
            <td align="left" ><strong>Class</strong>: '.$className.'-'. $challanData1['section'] .' </td>
        </tr>               
        <tr>
            <td align="center" height="25"><strong>S.No</strong> </td>
            <td align="center" colspan="2" height="25"><strong>Particluars</strong> </td>
            <td align="center" height="25" ><strong>Amount</strong> </td>
        </tr>';

    $contracttt = '';
    $contracttt .= '<tr><td colspan="" align="center" height="25">'. 1 .'</td>';
        $contracttt .= '<td colspan="2" align="center" height="25">'.$displayName.'</td>';
        $contracttt .= '<td colspan="" align="right" height="25">'. $challanData1['org_total'] .'</td>';                       
        $contracttt .= '</tr>';
    $contract .= $contracttt;
    $contract.='<tr>
        <td colspan="3" align="right" height="25"><strong>Total</strong></td>
        <td align="right"> '. $challanData1['org_total'].' </td>
    </tr>';
    $contract.='<tr>
        <td colspan="4" align="left" height="25"><strong>Rupees in words : </strong>'.getCurrencyInWords($challanData1['org_total']).' </td>
    </tr>';    
    
    $contract.='</table><p>*This is a computer generated receipt and does not require authorization.</p>';   
    $data = $contract;


	require_once 'vendor/autoload.php';

	$mpdf = new \Mpdf\Mpdf();
	$mpdf->SetWatermarkText('PAID', 0.08);
	$mpdf->showWatermarkText = true; 

	$challanno = $studentId.'EVENT-'.trim($eventid[1]);

	$challanID = str_replace('/', '', trim($challanno));
	$mpdf->WriteHTML($data);
	$mpdf->Output($documentPath.$challanID.".pdf",'F');
}

function downloadFile($data, $page, $filename) {	
	$filename = $filename.time();
	$handle = fopen(BASEPATH."/notificationlogs/".$filename.".txt", "w+");
    fwrite($handle, $data);
    fclose($handle);

    $file = BASEPATH."/notificationlogs/".$filename.".txt";

    if (file_exists($file)) {
	    header('Content-Description: File Transfer');
	    header('Content-Type: application/octet-stream');
	    header('Content-Disposition: attachment; filename="'.basename($file).'"');
	    header('Expires: 0');
	    header('Cache-Control: must-revalidate');
	    header('Pragma: public');
	    header('Content-Length: ' . filesize($file));
	    readfile($file);
	    exit;
	}
}
function updatereceipt($challanNo, $studentId, $feegroup = ''){
    $challan = array();
    $where = '';

	$challanData = sqlgetresult('SELECT id,"feeType" FROM tbl_challans WHERE "challanStatus" = 1 AND "challanNo" = \''.$challanNo.'\' AND deleted=0',true);
	$challanfeetype = array();

	    foreach ($challanData as $k => $v) {
	    	$challanfeetype[$v['id']]=$v['feeType'];
	    }

    $receiptdata= sqlgetresult('SELECT "feeType" FROM tbl_receipt WHERE "challanNo" = \''.$challanNo.'\'',true);

    $receiptfeetype = array();

	    foreach ($receiptdata as $k => $v) {
	        array_push($receiptfeetype, $v['feeType']);
	    }

    $resultarray=array_diff($challanfeetype,$receiptfeetype);

    $resultarray1=array_unique($resultarray);

	    foreach ($resultarray1 as $k => $row)
	    {
	        $id = trim($k);
	        $datas = sqlgetresult("SELECT * FROM createReceiptRows('".$id."')");
	    }
    return 1;
}

function getreceiptreport($studentid, $year='', $semester=''){
	/*************RECEIPT REPORT - Start****************/
	
    $studId = $studentid;
    $year = $year;
    $semester = $semester;
    $whereClauses = array(); 
    
    if (!empty($year)) 
     	$whereClauses[] =" \"academic_yr\" = ".$year;
    $where='';

    if (!empty($studId)) 
        $whereClauses[] ='"studentId" = \''.$studId.'\' ';
    $where='';

    if (! empty($semester)) 
      $whereClauses[] ='term=\''.$semester.'\' ' ;
    $where = '';

    if (count($whereClauses) > 0) 
    { 
      $where = ' WHERE '.implode(' AND ',$whereClauses); 
    }  

	$challanData = ('SELECT * FROM  getreceiptdatanew'. $where);
    $res = sqlgetresult($challanData, true);

    $challanData1 = array();
    $feedata = array();
    $waivedarray = array();

    foreach ($res as $key => $data) {
	    if($data['feeGroup'] == ''){
	    	$feegroup = 'Late Fee';
	    }
	    else{
	    	$feegroup = $data['feeGroup'];
	    }
        $challanData1[$data['year']][$data['term']][$feegroup]['total'][] = $data['total'];    
        $challanData1[$data['year']][$data['term']][$feegroup]['updatedOn'][] = $data['updatedOn'];   
        $challanData1[$data['year']][$data['term']][$feegroup]['paid_date'][] = $data['paid_date'];    
        $challanData1[$data['year']][$data['term']][$feegroup]['pay_type'][] = $data['pay_type']; 
        $challanData1[$data['year']][$data['term']][$feegroup]['waivedarray'] = getwaiveddata($data['challanNo'],$data['feegid']);    
    }
	/*************RECEIPT REPORT - End****************/

	/*************DEMAND REPORT - Start****************/
	$studId = $studentid;
    $year = $year;
    $semester = $semester;

    $whereClauses = array(); 
    
    if (!empty($year)) 
        $whereClauses[] =" \"academic_yr\" = ".$year;
    $where='';

    if (!empty($studId)) 
        $whereClauses[] ='"studentId" = \''.$studId.'\' ';
    $where='';

    if (! empty($semester)) 
      $whereClauses[] ='term=\''.$semester.'\' ' ;
    $where = '';

    if (count($whereClauses) > 0) 
    { 
      $where = ' WHERE '.implode(' AND ',$whereClauses); 
    }  

	$challanData = ('SELECT * FROM  getdemanddatanew'. $where);
    $res = sqlgetresult($challanData, true);
    // print_r($res);
    // exit;
    $challanData2 = array();
    $feedata = array();
    $waivedarray = array();

    foreach ($res as $data) {
    $challanData2[$data['year']][$data['term']]['studentId'] = $data['studentId'];
    $challanData2[$data['year']][$data['term']]['studentName'] = $data['studentName'];
    $challanData2[$data['year']][$data['term']]['challanNo'] = $data['challanNo'];
    $challanData2[$data['year']][$data['term']]['streamName'] = $data['streamName'];
    $challanData2[$data['year']][$data['term']]['createdOn'] = date("d-m-Y", strtotime($data['createdOn']));
    $challanData2[$data['year']][$data['term']]['remarks'] = $data['remarks'];
    $challanData2[$data['year']][$data['term']]['duedate'] = date("d-m-Y", strtotime($data['duedate']));
    if($data['feeType'] == ''){
    	$feetype = 'Late Fee';
    }
    else{
    	$feetype = $data['feeType'];
    }
    $challanData2[$data['year']][$data['term']]['feeType'][] = $feetype;
    if($data['feeGroup'] == ''){
    	$feegroup = 'Late Fee';
    }
    else{
    	$feegroup = $data['feeGroup'];
    }
    $challanData2[$data['year']][$data['term']]['feeGroup'][] = $feegroup;
    $challanData2[$data['year']][$data['term']]['total'][] = $data['total'];

    }

    /************Common Data - Start****************/
    $studId = $studentid;
    $commondata = ('SELECT * FROM  tbl_student WHERE "studentId" = \''.$studId.'\'');

    $res = sqlgetresult($commondata, true);
       
    $challanData3 = array();
    $feedata = array();
    $waivedarray = array();

    foreach ($res as $data) {
    	$challanData3['studentId'] = $data['studentId'];
    	$challanData3['studentName'] = $data['studentName'];
    	$challanData3['stream'] = getStreambyId($data['stream']);
    	$challanData3['gender'] = $data['gender'];
    }
    /************Common Data - End****************/
    
 

	/*************DEMAND REPORT - End****************/
	$fulldata['receipt'] = $challanData1;
	$fulldata['demand'] = $challanData2;
	$fulldata['common'] = $challanData3;

// print_r($fulldata);
// exit;
    return $fulldata;
}

function sendNotificationToParents($studentId, $mailbody, $smsbody, $type='',$attach='', $subject = '')
{
	$parentData = sqlgetresult('SELECT p."userName", p."email" AS mail1 , p."mobileNumber" AS mbl1 , p."phoneNumber" AS mbl2 , p."secondaryEmail" AS mail2 FROM tbl_student s LEFT JOIN tbl_parents p ON s."parentId" = p."id" WHERE s."studentId" =\'' . $studentId . '\'');
    if($parentData['userName'] != '' && $parentData['mail1'] != ''){
		$getmailData = array_values($parentData);
	    foreach ($getmailData as $key => $value) {
	       if (stristr($value,"@") || stristr($value,".")) {
	       		$data = 'Dear '.$parentData['userName'].',<br/> '.$mailbody;
	       		if($subject == ''){
		       		$subject = 'Notification From Omega';
	       		}
	       		if($type == 1) {
	       			$cc = 1;
	       		} else {
	       			$cc = '';
	       		}
	            SendMailId($value, $subject, $data, '', '', $attach,'',$cc);
	        }/* elseif(is_numeric($value)) {
	        	$smsLoginId = "transport@omegaschools.org";
				$smsLoginPass = "SmsLm01s@2019";
				$smsBaseurl ="http://www.businesssms.co.in";
				$smstxt = urlencode($smsbody);
	            $smsURL = "$smsBaseurl/sms.aspx?Id=$smsLoginId&Pwd=$smsLoginPass&PhNo=$value&text=$smstxt";
	            $ret = file($smsURL);                        
	        }*/
	    }
	}
	else{
		$subject = 'Challan - Not mapped with parents';
		$data = 'Dear Admin,<br/> '.$mailbody;
		SendMailId('feeapp@omegaschools.org', $subject, $data, '', '', $attach,'');
	}
}

function getwaiveddata($challanno, $feegroup = '', $challanstatus = ''){
	if($feegroup != ''){

		// $checkchallantable = sqlgetresult('SELECT * FROM tbl_challans WHERE "challanNo" = \''.$challanno.'\' AND "feeGroup" = \''.$feegroup.'\'',true);

		//  if (is_array($checkchallantable) && sizeof($checkchallantable) > 0){
		// 	$totalamount = 0;
		// 	$waivedgrps = array();
		// 	foreach($checkchallantable AS $challantable){
		// 		$totalamount += $challantable['waivedTotal'];
		// 		$waivedgrps[] = getFeeGroupbyId($challantable['feeGroup']);
		// 	}
		// }

		// if($checkchallantable[0]['waivedTotal'] != 0){

	 //     	$data = sqlgetresult('SELECT id, "studentId", "challanNo", "total","feeGroup","waivedTotal" AS "waiver_total", "waivedPercentage" AS "waiver_perccentage", "waivedAmount" AS "waiver_amount", "waivedType" AS "waiver_type" FROM tbl_challans WHERE "challanNo" = \''.$challanno.'\' AND "feeGroup" = \''.$feegroup.'\' LIMIT 1', true);

	 //     	$data[0]['waiver_total_sum'] = $totalamount;
	 //     	$data[0]['waivedgroups'] = array_unique($waivedgrps);
	 //     	$data[0]['oldwaiver'] = 1;
		// }
		// else{
			$data = sqlgetresult('SELECT id, "studentId", "challanNo", "waiver_total", "total","feeGroup", "waiver_percentage", "waiver_amount", "waiver_type" FROM tbl_waiver WHERE "challanNo" = \''.$challanno.'\' AND "feeGroup" = \''.$feegroup.'\' AND deleted=0', true);
		// }
	}
	else{
		
		if($challanstatus != ''){
			$data = sqlgetresult('SELECT id, "studentId", "challanNo", "waiver_total", "total","feeGroup", "waiver_percentage", "waiver_amount", "waiver_type" FROM tbl_waiver WHERE "challanNo" = \''.$challanno.'\' AND "challanStatus" = \''. 1 .'\' AND deleted=0', true);
		}
		else{
			$data = sqlgetresult('SELECT id, "studentId", "challanNo", "waiver_total", "total","feeGroup", "waiver_percentage", "waiver_amount", "waiver_type" FROM tbl_waiver WHERE "challanNo" = \''.$challanno.'\' AND deleted=0', true);
		}
		// $checkchallantable = sqlgetresult('SELECT * FROM tbl_challans WHERE "challanNo" = \''.$challanno.'\' AND "waivedTotal" != \'0\'',true);
  //       $waivedgrps = array();
  //       $totalamount = array();
  //       if (is_array($checkchallantable) && sizeof($checkchallantable) > 0){
		// 	foreach($checkchallantable AS $challantable){
		// 		$totalamount[] = $challantable['waivedTotal'];
		// 		$waivedgrps[] = getFeeGroupbyId($challantable['feeGroup']);
		// 	}
		// }
		// if($checkchallantable[0]['waivedTotal'] != 0){
		// 	$data = sqlgetresult('SELECT id, "studentId", "challanNo", "total","feeGroup","waivedTotal" AS "waiver_total", "waivedPercentage" AS "waiver_percentage", "waivedAmount" AS "waiver_amount", "waivedType" AS "waiver_type" FROM tbl_challans WHERE "challanNo" = \''.$challanno.'\' LIMIT 1',true);

		// 	$data[0]['waiver_total_sum'] = array_unique($totalamount);
		// 	$data[0]['waivedgroups'] = array_unique($waivedgrps);
		// 	$data[0]['oldwaiver'] = 1;
		// }
		// else{
			$data = sqlgetresult('SELECT id, "studentId", "challanNo", "waiver_total", "total","feeGroup", "waiver_percentage", "waiver_amount", "waiver_type" FROM tbl_waiver WHERE "challanNo" = \''.$challanno.'\' AND deleted=0', true);
		// }
	}
	// print_r($data);
	if($data != ''){
		$resultdata = $data;
	}
	else{
		$resultdata = 0 ;
	}
	return $resultdata;
}

function getProduct($proid) {
	$data  = sqlgetresult('SELECT "product_name" FROM tbl_products WHERE "id" = \''.$proid.'\' ');
	return $data['product_name'];	
}

function getProductByFeeGroup($fgname) {
	$data  = sqlgetresult('SELECT "product_name" FROM tbl_products p LEFT JOIN tbl_fee_group fg ON p.id = fg.product WHERE "feeGroup" = \''.$fgname.'\' ');
	return $data['product_name'];	
}

function getTransportStage($stgid) {
	$feetype = 'Transport Stage'.$stgid;
	$data = sqlgetresult('SELECT id FROM tbl_fee_type WHERE "feeType" ILIKE \'%'.$feetype.'%\' ');
	return $data['id'];
}

function getSFSandSchoolFeeByFeeId($feeType, $class, $academicyear, $term) {
	$data = sqlgetresult('SELECT amount FROM tbl_fee_configuration WHERE "feeType" = \''.$feeType.'\' AND class = \''.$class.'\' AND "academicYear" = \''.$academicyear.'\' AND semester = \''.$term.'\' AND deleted = 0 ');
	// echo 'SELECT amount FROM tbl_fee_configuration WHERE "feeType" = \''.$feeType.'\' AND class = \''.$class.'\' AND "academicYear" = \''.$academicyear.'\' AND semester = \''.$term.'\' AND deleted = 0 ';
	return $data['amount'];
}

function getCurrentAcademicYear(){
	$data = sqlgetresult('SELECT id FROM tbl_academic_year WHERE active= 1 AND deleted = 0');
	return trim($data['id']);
}

function getCurrentTerm(){
	$data = sqlgetresult("SELECT semester FROM tbl_semester WHERE status = '1'  ");
	return trim($data['semester']);
}

function getEventNamebyid($eventid){
	$data = sqlgetresult('SELECT "feeType" FROM tbl_nonfee_type WHERE status = \'1\' AND "id" =  \''.trim($eventid).'\'');
	return trim($data['feeType']);
}

function generate_tax_exemption($parentname,$studentname,$student_id,$class,$section,$amount,$amountinWords,$year_val,$year){
	
	$date = time();
	$start_date = date('j<\s\up>S</\s\up> F, Y', $date);
	$exist_query= array();
	$exist_query = sqlgetresult('SELECT * FROM gettaxexemapplied WHERE student_id = \''.$student_id.'\' AND deleted = \'0\' AND academic_year =\''.$year.'\'');
	if(sizeof($exist_query) == 0){
		
		$datefolder = date('dmY',$date);
		

	}
	else{

		$datefolder = date('dmY',strtotime($exist_query['created_on'])); 

	}
	if (!is_dir(BASEPATH."taxexemption/".$datefolder)) {
			mkdir(BASEPATH."taxexemption/".$datefolder);
		}
	if(trim($section)!= ''){
		$section = str_replace(" ", "", $section);
		$section = "\"".$section."\"";

	}
	else{
		$section = '';

	}

	$studentappl = str_replace('/', '', trim($student_id));
	$studentappl = str_replace(' ','',trim($studentappl));
	$data = "<html>
				<style> 
					
					.main_div{padding-top:150px;font-family: 'Lucida Sans Unicode', 'Lucida Grande',Sans-Serif;line-height: 300%}
					.first_head{padding-right:0px;font-weight:bold}
					.inner_content{  margin-top: 30px;  padding-right: 30px;  padding-bottom: 30px; padding-left: 30px;word-spacing: 8px; font-size: 15px;}
					pre{font-family: 'Lucida Sans Unicode', 'Lucida Grande',Sans-Serif;} 
				</style>
				<body>
					<div class='main_div'>
						<div class='first_head' style='text-align:right;font-size:15px;'>Date: ".$start_date."</div>
						<br>
						<div class='center' style='text-align:center'><strong>TUITION FEE CERTIFICATE</strong></div>
						<div class='inner_content'>
							<pre>This  is  to  certify  that,  <b>".trim(ucwords(strtoupper($parentname)))."</b>  parent  of  <b>".trim(ucwords(strtoupper($studentname)))."</b> (ID ".trim($student_id).")  of  Class  ".trim($class)."  Section  ".trim($section)."  has  paid  Rs.".trim(number_format($amount))."/-  (Rupees  ".trim($amountinWords).")  towards  tuition  fees  for  the  Academic  Year  ".trim($year_val).".</pre>
						</div>
						<br>

						<div style='text-align:left;font-size:14px;padding-left: 30px;'>
							<b>
							For LALAJI MEMORIAL OMEGA INTERNATIONAL SCHOOL
 							</b>
 							<br>
 							<div style = 'line-height:100%!important'>
	 							V. HEMA SOWTHIRI<br>
								Head Of Finance
							</div>

						</div>
						<div style='text-align:center;'>
							<img src='../images/rubber_stamp.jpg' width='22%'>
						</div>
					</div>
				</body>
			</html>";
			

	$documentPath = 'taxexemption/'.trim($datefolder).'/'.trim($studentappl).'-'.trim($year_val).'.pdf';

	require_once 'vendor/autoload.php';
		$header = "<img src='../images/tax_logo.jpg'>";
		$footer = "<footer>
						<hr/>
                        <div style='text-align: center;font-family: Verdana,Geneva,sans-serif;  padding: 5px;width:78%;float:left;'>                           
                            <span style='font-size: 12px;color: #1b0042;font-family: 'Lucida Sans Unicode', 'Lucida Grande',Sans-Serif;'> No. 79, Omega School Road, Kolapakkam, Kovur Post, Chennai – 600128, Tamil Nadu, India.
                            </span>
                            <div>
	                            <span style=''>
	                                <img src='../images/call-icon.jpg' height='20' style='padding-top:10px;'><span style='font-size: 12px;color: #1b0042;display: inline-block;vertical-align: super;padding-left:5px;'> +91 44 6624 1117 / 30</span>
	                            </span>
	                            <span style=''>
	                                <img src='../images/facebook-icon.jpg' height='20'><span style='font-size: 12px;color: #1b0042;display: inline-block;vertical-align: super;padding-left:5px;'>  facebook.com/omegaintlschool</span>
	                            </span>
	                            <span style=''>
	                            <img src='../images/mail-icon.jpg' height='20'><span style='font-size: 12px;color: #1b0042;display: inline-block;vertical-align: super;padding-left:5px;'> info@omegaschools.org </span>
	                            </span> 
                            </div>                         
                        </div>
                        <div style='width:20%;float:right; background-color: #ffffff; '>
                        	<span>
                        		<img src='../images/pdfimg.jpg' width='50' height='58' 	/>
                        	</span>
                        	<span>
                        		<img src='../images/award.jpg' width='50' height='58' />
                        	</span>                        	
                        </div>         
                	</footer>
                	<div style='clear:both;'></div>
                	<div style='margin-left: 17px;'>
                		<i style='font-size: 10px;'>
                            ** This is a computer generated document and does not require any signature 
                    	</i>
                	</div>";
                	
        // print_r($header);
        // print_r($data);
        // print_r($footer);
        // exit;
		$mpdf = new \Mpdf\Mpdf();
		// $mpdf->showImageErrors = true;

		$mpdf->SetHTMLHeader($header);
		// $pdf->convert($data,$documentPath.$challanID.".pdf",1);
		$mpdf->WriteHTML($data);

		$mpdf->SetHTMLFooter($footer);
		$mpdf->Output(BASEPATH.$documentPath,'F');
	return $documentPath;
}


function preview_tax_exemption_content($parentname,$studentname,$student_id,$class,$section,$amount,$amountinWords,$year_val){
	
	$studentappl = str_replace('/', '', trim($student_id));
	$date = time();

	$start_date = date('j<\s\up>S</\s\up> F, Y', $date);
	if(trim($section)!= ''){
		$section = str_replace(" ", "", $section);
		$section = "\"".$section."\"";

	}
	else{
		$section = '';

	}

	$data = "
			<img src='../images/Header.svg' width = '100%'  />
			
			<div class='main_div'>
				<div class='first_head' style='text-align:right;font-size:15px;'>
					Date: ".$start_date."
				</div>
				<br>
				<div class='center' style='text-align:center'>
					<strong>TUITION FEE CERTIFICATE</strong>
				</div>
				<div class='inner_content'>
					This is to certify that, <b>".trim(ucwords(strtolower($parentname)))."</b> parent of <b>".trim(ucwords(strtolower($studentname)))."</b> (ID ".trim($student_id).") of Class ".trim($class)." Section ".trim($section)." has paid Rs.".trim(number_format($amount))."/- (Rupees ".trim($amountinWords).") towards tuition fees for the Academic Year ".trim($year_val).".
				</div>
				<br>
				<div style='text-align:left;font-size:14px;padding-left: 30px;'>
					<b>
							For LALAJI MEMORIAL OMEGA INTERNATIONAL SCHOOL
					</b> 	
					<br>
 					<div style = 'line-height:200%!important'>
	 					V. HEMA SOWTHIRI<br>
						Head Of Finance
					</div>
				</div>
			</div>";
	return $data;
}


function uploadSettlement($data) {
	// print_r($data);	
	$arr_file = explode('.', $data['file']['name']);
    $extension = end($arr_file);
 
    if('csv' == $extension) {
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
    } else {
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
    }
    $targetPath = BASEPATH.'settlementreports/'.$data['file']['name'];
    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($targetPath);    
     
    $sheetData = $spreadsheet->getActiveSheet()->toArray();

    $columns = $sheetData[0];

    array_shift($sheetData);
    $data = $sheetData;

    // print_r($columns); echo "<hr/>";
    // print_r($data);

    foreach ($data as $val) {    	
    	$keys = '"'.implode('","', $columns).'"';
		$values = "'".implode("','", $val)."'";
		$insert = 	sqlgetresult('INSERT INTO tbl_settlement_report ('.$keys.') VALUES ('.$values.')') ;
    }

    header('location:settlementreport.php');
}

function getNonfeeTypeById($nonFeeId) {
	$data  = sqlgetresult('SELECT "feeType" FROM tbl_nonfee_type WHERE "id"  =  \''.$nonFeeId.'\'');
	return $data['feeType'];
}

function flattableentry($challanno, $studentid, $fromwhere = ''){
$challantype = 'CHALLAN';
$demandtype = "DEMAND";
$receipttype = "RECEIPT";
$waivertype = "WAIVER";
	$totalchallan = sqlgetresult('SELECT DISTINCT("challanNo") FROM tbl_challans WHERE "challanNo" = \''. $challanno .'\' AND deleted=0',true);
	// print_r($totalchallan);
	// exit;

	foreach($totalchallan AS $key => $challan){
		// Challan Details from Challan Table
		$challandatas = sqlgetresult('SELECT c."studentId", c."challanNo", s."studentName", a."year" AS "academicYear", cl."class_list", st."stream", c."term", g."feeGroup", f."feeType", c."createdOn", c."updatedOn", c."total", c."challanStatus"  FROM tbl_challans c 
		LEFT JOIN tbl_student s ON (c."studentId" = s."studentId" OR c."studentId" = s."application_no" OR ((c."studentId")::bpchar)::TEXT = ANY (string_to_array ((s."old_studentId")::TEXT,\',\'::TEXT)))
		LEFT JOIN tbl_academic_year a ON c."academicYear" = a.id
		LEFT JOIN tbl_class cl ON c."classList" = cl."id"
		LEFT JOIN tbl_stream st ON c."stream" = st.id
		LEFT JOIN tbl_fee_group g ON c."feeGroup" = g.id
		LEFT JOIN tbl_fee_type f ON c."feeType" = f.id
		WHERE c."challanNo" = \''. $challan['challanNo'] .'\' AND c.deleted=0',true);

		$challandata = array();
		$challanstatusunique = array();

	    foreach ($challandatas as $key => $data) {
	        if($data['feeGroup'] == ''){
	            $data['feeGroup'] = 'LATE FEE';
	        }
	        else{
	            $data['feeGroup'] = $data['feeGroup'];
	        }
	        if($data['feeType'] == ''){
	            $data['feeType'] = 'LATE FEE';
	        }
	        else{
	            $data['feeType'] = $data['feeType'];
	        }

	        
	            $challandata[$data['challanNo']][$data['feeGroup']]['studentId']= $data['studentId']; 
	            $challandata[$data['challanNo']][$data['feeGroup']]['studentName']= $data['studentName'];   
	            $challandata[$data['challanNo']][$data['feeGroup']]['academicYear']= $data['academicYear'];  
	            $challandata[$data['challanNo']][$data['feeGroup']]['class'] = $data['class_list'];   
	            $challandata[$data['challanNo']][$data['feeGroup']]['stream'] = $data['stream'];    
	            $challandata[$data['challanNo']][$data['feeGroup']]['term'] = $data['term'];
	            $challandata[$data['challanNo']][$data['feeGroup']]['feeType'][] = $data['feeType'];
	            $challandata[$data['challanNo']][$data['feeGroup']]['demandtotal'][] = $data['total'];
	            $challandata[$data['challanNo']][$data['feeGroup']][$demandtype]['demanddate'] = date('Y-m-d',strtotime($data['createdOn'])); 
	            $challandata[$data['challanNo']][$data['feeGroup']][$demandtype]['demandtotal'][] = $data['total']; 
	            
	            $challanstatus[]=$data['challanStatus'];    
	    }
	    
		// Challan Details from Waiver Table
		if($fromwhere == 'Waiver'){
	    $waivedatas = sqlgetresult('SELECT * FROM tbl_waiver WHERE "challanNo" = \''. $data['challanNo'].'\' AND deleted=0',true);
		    if($waivedatas != ''){
		        foreach ($waivedatas as $key => $data3){
		            $challandata[$data['challanNo']][getFeeGroupbyId($data3['feeGroup'])][$waivertype]['waiverdate'] = date('Y-m-d',strtotime($data3['createdOn'])); 

		            $challandata[$data['challanNo']][getFeeGroupbyId($data3['feeGroup'])][$waivertype]['waivertotal'][] = $data3['waiver_total'];
		            
		            $challandata[$data['challanNo']][getFeeGroupbyId($data3['feeGroup'])][$waivertype]['remarks'] = trim($data3['waiver_remarks']);
		            $challandata[$data['challanNo']][getFeeGroupbyId($data3['feeGroup'])][$waivertype]['waiver_type'] = trim($data3['waiver_type']);
		        } 
		    }
		}

	    if($fromwhere == 'Receipt'){
	        $receiptdatas = sqlgetresult('SELECT g."feeGroup", c."total", c."updatedOn", c."pay_type", c,"cheque_dd_no", c."bank", c."paid_date", c."chequeRemarks" 
	        FROM tbl_receipt c 
	        LEFT JOIN tbl_fee_group g ON c."feeGroup" = g.id
	        WHERE c."challanNo" = \''. $data['challanNo'].'\' ',true);

	        foreach ($receiptdatas as $key => $data2) {
	            if($data2['feeGroup'] == ''){
	                $data2['feeGroup'] = 'LATE FEE';
	            }
	            else{ 
	                $data2['feeGroup'] = $data2['feeGroup'];
	            }


	            if($data2['updatedOn'] == ''){
	                $challanupdate = sqlgetresult('SELECT "updatedOn" FROM tbl_challans WHERE "challanNo" = \''. $data['challanNo'].'\' AND "feeGroup" = \''. getFeeGroupbyName($data2['feeGroup']).'\' AND deleted=0',true);

	                $updatedonarray = reset(array_unique($challanupdate));

	                $updatedon = date('Y-m-d',strtotime($updatedonarray['updatedOn']));
	            }
	            else{
	                $updatedon = date('Y-m-d',strtotime($data2['updatedOn']));
	            }
	            $challandata[$data['challanNo']][$data2['feeGroup']][$receipttype]['receiptdate'] = $updatedon; 
	            $challandata[$data['challanNo']][$data2['feeGroup']][$receipttype]['receipttotal'][] = $data2['total'];
	            $challandata[$data['challanNo']][$data2['feeGroup']][$receipttype]['pay_type'] = $data2['pay_type'];
	            $challandata[$data['challanNo']][$data2['feeGroup']][$receipttype]['cheque_dd_no'] = $data2['cheque_dd_no'];
	            $challandata[$data['challanNo']][$data2['feeGroup']][$receipttype]['bank'] = $data2['bank'];
	            $challandata[$data['challanNo']][$data2['feeGroup']][$receipttype]['paid_date'] = $data2['paid_date'];
	            $challandata[$data['challanNo']][$data2['feeGroup']][$receipttype]['chequeRemarks'] = $data2['chequeRemarks'];
	            $feegroupname = getFeeGroupbyName($data2['feeGroup']);
	            $waiveramount = getwaiveramount($data['challanNo'], $feegroupname);
	             $challandata[$data['challanNo']][$data2['feeGroup']][$receipttype]['waivedTotal'] = $waiveramount;
	        }  
	    }

	    foreach($challandata AS $key => $challan){
	    	 		$chnNo = trim($key);
	        foreach($challan AS $key2 => $chn){
	                $studentId = trim($chn['studentId']);
	                $studentName = trim($chn['studentName']);
	                $academicYear = trim($chn['academicYear']);
	                $class = trim($chn['class']);
	                $stream = trim($chn['stream']);
	                $term = $chn['term'];
	                $feegroup = trim($key2);

	            foreach($chn AS $key3 => $value){
	                if($key3 == 'WAIVER'){
	                    $date = trim($value['waiverdate']);
	                    $amount = array_sum($value['waivertotal']);
	                    $entrytype = $waivertype;
	                    //$remarks = $value['remarks'];
	                    $remarks = $value['waiver_type']."-".$value['remarks']; 
		                $remarks=substr($remarks, 0, 80); 
	                    $feeType = '';      
	                }
	                else if($key3 == 'RECEIPT'){
	                    $date = trim($value['receiptdate']);
	                    $amount = array_sum($value['receipttotal']);
	                    $entrytype = $receipttype;
	                    if($value['pay_type'] != ''){
	                        $paymentremarks['transNum'] = trim($value['bank']) .'/'. trim($value['cheque_dd_no']) .'/'. trim($value['paid_date']) . '/' . trim($value['chequeRemarks']);
	                    }
	                    else{
	                        $paymentremarks = sqlgetresult('SELECT "transNum" FROM tbl_payments WHERE "challanNo" = \''. $data['challanNo'].'\' AND "transNum" IS NOT NULL ');
	                        $paymentremarks['transNum'] = trim($paymentremarks['transNum']) . '/' . 'Online';
	                    }   
	                    $remarks = $paymentremarks['transNum'];
	                    $feeType = '';
	                    if($value['waivedTotal'] != 0){
	                        $amount = array_sum($value['receipttotal']) - $value['waivedTotal']['waiver_total'];
	                    }  
	                    else{
	                        $amount = array_sum($value['receipttotal']);
	                    }

	                }else{
	                    $date = trim($value['demanddate']);
	                    $entrytype = $demandtype;    
	                    $remarks = '';      
	                }

		// For Inseritng into the Flat Table
	                if($key3 == "RECEIPT" || $key3 == "WAIVER"){
	                    $insertstudentledger = sqlgetresult("SELECT * FROM studentledgeradddatanew('$studentId','$chnNo', '$studentName', '$academicYear', '$class', '$stream', '$term', '$date', '$feegroup', '$amount', '$remarks', '$entrytype')");
	                    // print_r($insertstudentledger);
	                    // echo('<hr/>');
	                }
	                
	                if($key3 == "DEMAND"){
	                    if(is_array($chn['feeType'])){ 
	                        foreach($chn['feeType'] AS $key => $feetype1){
	                            $amount = $chn['demandtotal'][$key];
	                            $feetype = trim($feetype1);
	                                $insertstudentledger = sqlgetresult("SELECT * FROM studentledgeradddatademand('$studentId','$chnNo', '$studentName', '$academicYear', '$class', '$stream', '$term', '$date', '$feegroup', '$feetype', '$amount', '$remarks', '$entrytype')",true);
	                                // print_r($insertstudentledger);
	                                // echo('<hr/>');
	                        }
	                    }
	                }
	            }
	        }
	    }
	}
}

function getwaiveramount($challanno, $feegroup){
	$data = sqlgetresult('SELECT "waiver_total" FROM tbl_waiver WHERE "challanNo" = \''.$challanno.'\' AND "feeGroup" = \''.$feegroup.'\' AND deleted=0');

	return $data;
}


function getwaiveramountbychallan($challanno){
	$data = sqlgetresult('SELECT SUM(waiver_total) AS waiver_total FROM tbl_waiver WHERE "challanNo" = \''.$challanno.'\' AND deleted=0');

	return $data;
}

function getTotalbychallan($challanno){
	$data = sqlgetresult('SELECT SUM(org_total) AS org_total FROM tbl_challans WHERE "challanNo" = \''.$challanno.'\' AND deleted=0');
	return $data;
}

function getfeetypeamountfromledger($feetype,$challan){
	$feetypeamount = sqlgetresult('SELECT "amount" FROM tbl_student_ledger WHERE "feeType" = \''. $feetype .'\' AND "challanNo" = \''. $challan .'\'');
	return $feetypeamount['amount'];
}

function getfeegroupamountfromledger($feegroup,$challan,$entrytype){

	$feegroupamount = sqlgetresult('SELECT "amount" FROM tbl_student_ledger WHERE "feeGroup" = \''. $feegroup .'\' AND "challanNo" = \''. $challan .'\' AND "entryType" = \''. $entrytype .'\'');

	return $feegroupamount['amount'];
}


function createPDFtemp($studId,$chlno,$type='') {

    $getparentmailid = sqlgetresult('SELECT "email" AS parentMailId FROM tbl_student WHERE "studentId"=\''.$studId . '\'');
    $challanData = sqlgetresult('SELECT * FROM challandata WHERE "studentId" =\''. $studId . '\' AND  "challanNo" = \'' . $chlno . '\' AND "challanStatus" = 1 ',true);
    // print_r($challanData);
    $feeTypes = sqlgetresult("SELECT * FROM getFeeTypes");    
    $mailid = $getparentmailid['parentmailid'];
    $to = $mailid;

    $order = array('SCHOOL FEE', 'SCHOOL UTILITY FEE', 'SFS UTILITIES FEE', 'REFUNDABLE DEPOSIT' , 'LATE FEE');

    if($type != '') {
        $datefolder = date("dmY", strtotime($type));
    } else {
        $datefolder = date('dmY');
    }

    if (!is_dir(BASEPATH."receipts/".$datefolder)) {
        mkdir(BASEPATH."receipts/".$datefolder);
    }   
    $documentPath = BASEPATH."receipts/".$datefolder."/";

    $total = 0;
    $feeData = array();  
    $chlncnt = count($challanData);  
    $groupdata = array();

    foreach ($challanData as $k =>$value) {
        $challanno = $value['challanNo'];
        $Semester = $value['term'];
        $challanData1['clid'] = $value['clid'];
        $name = $value['studentName'];
        $studentId = $value['studentId'];
        $className = $value['class_list'];
        $challanData1['duedate'] = $value['duedate'];
        $challanData1['stream'] = $value['stream'];
        $streamName = $value['steamname'];
        $challanData1['org_total'] = $value['org_total'];
        $challanData1['section'] = $value['section'];
        $cheque = $value['cheque_dd_no'];
        $pay_type = $value['pay_type'];
        $pdate = $value['paid_date'];
        $challanData1['academicYear'] = $value['academicYear'];
        $feegroup = $value['feeGroup'];
        if($value['remarks'] != ''){
        $remarks = $value['remarks'];
        }
        else{
        $remarks = 'Nil';

        }
        $feetypearray[getFeeGroupbyId($value['feeGroup'])][$value['feeType']][] = $value['org_total'];
        $feetypearray[getFeeGroupbyId($value['feeGroup'])][$value['feeType']][] = getFeeTypebyId($value['feeType']);

        $chequebankarray[getFeeGroupbyId($value['feeGroup'])]= $value['bank'];

        $chequenoarray[getFeeGroupbyId($value['feeGroup'])]= $value['cheque_dd_no'];

        $feetypearray[getFeeGroupbyId($value['feeGroup'])]['waived'] = getwaiveddata($value['challanNo'], $value['feeGroup']);

        $cnt = $k+1;
        if($cnt == $chlncnt) {
            $groupdata = $feetypearray;

        }
        uksort($groupdata, function ($key1, $key2) use($order)
	    {
	        return (array_search(trim($key1) , $order) > array_search(trim($key2) , $order));
	    });

        // if($feegroup != "LATE FEE" && $value['feeType'] != ""){
        foreach ($groupdata as $key => $feegroup) {
            if(trim($Semester) == 'I'){
                $provisional = "(Provisional)";
            }
            else{
                $provisional = "";
            }
            
            $contract[$key] =' <style> .noBorder td{ border:none} table{border-collapse: collapse} </style>
            <table width="750" border="0" >
                <tr> <td  align="center" valign="top"><img src="images/logo_pdf.jpg"  height="80" /> </td> 
                <td align="right">'.$key.'</td>
                </tr>               
              </table>';
            $contract[$key].='<table width="750" border="1" cellspacing="5" cellpadding="15">
                    <tr>
                    <td colspan="2" align="left" height="25" width="300"><strong>Challan No.</strong>: '.$challanno.' </td>
                    <td colspan="" align="left" height="25" width="250"><strong>Academic Year</strong>: '.getAcademicyrById($challanData1['academicYear']).' </td>
                    <td colspan="" align="left" height="25" width="200"><strong>Date</strong>: '.date("d-m-Y").' </td>
                </tr>               
                <tr>
                    <td align="left" colspan="3" height="25"><strong>Name</strong>: '.$name.' </td>
                    <td align="left" ><strong>Semester</strong>: '.$Semester.' </td>
                </tr>
                <tr>
                    <td align="left" colspan="2" height="25"><strong>Student ID</strong>: '.$studentId.' </td>
                    <td align="left" ><strong>Class</strong>: '.$className.'-'. $challanData1['section'] . $provisional .' </td>
                    <td align="left" ><strong>Due Date</strong>: '.date("d-m-Y", strtotime($challanData1['duedate'])).' </td>
                </tr>               
                <tr>
                    <td align="center" height="25"><strong>S.No</strong> </td>
                    <td align="center" colspan="2" height="25"><strong>Particluars</strong> </td>
                    <td align="center" height="25" ><strong>Amount</strong> </td>
                </tr>';

                $tot = 0;
                $i = 1;
                $contractt = '';
                $wtot = 0;
                if(trim($key) == '10') {
                    $findSFS = sqlgetresult('SELECT * FROM tbl_sfs_qty WHERE "challanNo" = \''.$challanno.'\' ', true);
                    foreach ($findSFS as $v) {
                        $contractt .= '<tr><td colspan="" align="center" height="25">'.$i.'</td>';
                        $contractt .= '<td colspan="2" align="center" height="25">'.getFeeTypebyId($v['feeTypes']).'('.$v['quantity'].')'.'</td>';
                        $contractt .= '<td colspan="" align="right" height="25">'.$v['amount'].'</td>';                    
                        $contractt .= '</tr>';
                        $tot += $v['amount'];
                        $i++;
                    }
                } else {
                    $last_key = end(array_keys($feegroup));
                    $waiveddata = array();

                    foreach ($feegroup as $k => $val) {                       
                        if(trim($k) != 'waived' && $val != 0) {
                            if(trim($val[0]) != 0) {
                                $contractt .= '<tr><td colspan="" align="center" height="25">'.$i.'</td>';
                                $contractt .= '<td colspan="2" align="center" height="25">'.$val[1].'</td>';
                                $contractt .= '<td colspan="" align="right" height="25">'.$val[0].'</td>';
                                $tot += $val[0];
                            }
                            $i++;
                        } 

                        $contractt .= '</tr>';
                                                                               
                        if(trim($k) == 'waived' && $val != 0) {
                            $waiveddata[] =  $val[0]['waiver_type'];
                            $waiveddata[] =  $val[0]['waiver_total']; 
                            $wtot = $val[0]['waiver_total'];
                        }
                        if( $k == $last_key && sizeof($waiveddata) > 0)  {

                                $contractt .= '<tr><td colspan="3" align="right" height="25">Waiver - '.$waiveddata[0].'</td>';
                                $contractt .= '<td colspan="" align="right" height="25">'.$waiveddata[1].'</td>';
                                $contractt .= '</tr>';
                            // }

                        }  
                                       
                    }

                    
                    $amount = $tot - $wtot;                     
                }

                $contract[$key] .= $contractt;
                $contract[$key].='<tr>
                    <td colspan="3" align="right" height="25"><strong>Total</strong></td>
                    <td align="right"> '.$amount.' </td>
                </tr>';
                $contract[$key].='<tr>
                    <td colspan="4" align="left" height="25"><strong>Rupees in words : </strong>'.getCurrencyInWords($amount).' </td>
                </tr>
                <tr>
                    <td align="left" colspan="4" height="25"><strong>Remarks</strong>: '.$remarks.' </td>
                </tr>';
                if( $pay_type == '' ) {
                    $pay_type = 'Online';
                }
                $contract[$key].='<tr>
                    <td align="left" height="25"><strong>Mode of Payment </strong></td>
                    <td colspan="3" align="left" height="25">'.$pay_type.'</td>
                </tr>';

                if( $pay_type != 'Online') {
                    $bank = $value['bank'];
                    $contract[$key].='<tr>
                        <td  align="left" height="25"><strong>Cheque / DD No.</strong></td>
                        <td colspan="" align="left" height="25">'.$chequenoarray[$key].'</td>
                        <td  align="left" height="25"><strong>Date</strong></td>
                        <td colspan="" align="left" height="25">'.$pdate.'</td>
                    </tr>';
                    $contract[$key].='<tr>                
                        <td  align="left" height="25"><strong>Bank</strong></td>
                        <td colspan="" align="left" height="25">'.$chequebankarray[$key].'</td>
                        <td  align="left" height="25"><strong>Branch</strong></td>
                        <td colspan="" align="left" height="25"></td>
                    </tr>';
                    $contract[$key].='<tr>
                        <td  align="left" height="25"><strong>Cashier / Manager</strong></td>
                        <td colspan="" align="left" height="25"></td>
                        <td  align="left" height="25"><strong>Signature of Remitter</strong></td>
                        <td colspan="" align="left" height="25"></td>
                    </tr>';
                }
                
                // $contract[$key].='</table>';  
                $contract[$key].='</table><p>*This is a computer generated receipt and does not require authorization.</p><pagebreak>';         
        }
    // }
        // else{
        //     $contract[$key] =' <style> .noBorder td{ border:none} </style>
        //     <table width="750" border="0" >
        //         <tr> <td  align="center" valign="top"><img src="images/logo_pdf.jpg"  height="80" /> </td> 
        //         <td align="right">'.$feegroup.'</td>
        //         </tr>               
        //       </table>';

        //     $contract[$key].='<table width="750" border="1" cellspacing="5" cellpadding="15">
        //             <tr>
        //             <td colspan="2" align="left" height="25"><strong>Challan No.</strong>: '.$challanno.' </td>
        //             <td colspan="" align="left" height="25"><strong>Academic Year</strong>: '.getAcademicyrById($challanData1['academicYear']).' </td>
        //             <td colspan="" align="left" height="25"><strong>Date</strong>: '.date("d-m-Y").' </td>
        //         </tr>               
        //         <tr>
        //             <td align="left" colspan="3" height="25"><strong>NAME</strong>: '.$name.' </td>
        //             <td align="left" ><strong>SEMESTER</strong>: '.$Semester.' </td>
        //         </tr>
        //         <tr>
        //             <td align="left" colspan="2" height="25"><strong>Student ID</strong>: '.$studentId.' </td>
        //             <td align="left" ><strong>Class</strong>: '.$className.'-'. $challanData1['section'] .' </td>
        //             <td align="left" ><strong>Due Date</strong>: '.date("d-m-Y", strtotime($challanData1['duedate'])).' </td>
        //         </tr>               
        //         <tr>
        //             <td align="center" height="25"><strong>S.No</strong> </td>
        //             <td align="center" colspan="2" height="25"><strong>Particluars</strong> </td>
        //             <td align="center" height="25" ><strong>Amount</strong> </td>
        //         </tr>';
        //         $contracttt = '';
        //         $contracttt .= '<tr><td colspan="" align="center" height="25">'. 1 .'</td>';
        //             $contracttt .= '<td colspan="2" align="center" height="25">'.$feegroup.'</td>';
        //             $contracttt .= '<td colspan="" align="right" height="25">'. $challanData1['org_total'] .'</td>';                       
        //             $contracttt .= '</tr>';
        //         $contract[$key] .= $contracttt;
        //         $contract[$key].='<tr>
        //             <td colspan="3" align="right" height="25"><strong>TOTAL</strong></td>
        //             <td align="right"> '. $challanData1['org_total'].' </td>
        //         </tr>';
        //         $contract[$key].='<tr>
        //             <td colspan="4" align="left" height="25"><strong>Rupees in words : </strong>'.getCurrencyInWords($challanData1['org_total']).' </td>
        //         </tr>
        //         <tr>
        //             <td align="left" colspan="4" height="25"><strong>REMARKS</strong>: '.$remarks.' </td>
        //         </tr>';
        //         if( $pay_type == '' ) {
        //             $pay_type = 'Online';
        //         }
        //         $contract[$key].='<tr>
        //             <td align="left" height="25"><strong>Mode of Payment </strong></td>
        //             <td colspan="3" align="left" height="25">'.$pay_type.'</td>
        //         </tr>';

        //         if( $pay_type != 'Online') {
        //             $bank = $value['bank'];
        //             $contract[$key].='<tr>
        //                 <td  align="left" height="25"><strong>Cheque / DD No.</strong></td>
        //                 <td colspan="" align="left" height="25">'.$cheque.'</td>
        //                 <td  align="left" height="25"><strong>Date</strong></td>
        //                 <td colspan="" align="left" height="25">'.$pdate.'</td>
        //             </tr>';
        //             $contract[$key].='<tr>                
        //                 <td  align="left" height="25"><strong>Bank</strong></td>
        //                 <td colspan="" align="left" height="25">'.$bank.'</td>
        //                 <td  align="left" height="25"><strong>Branch</strong></td>
        //                 <td colspan="" align="left" height="25"></td>
        //             </tr>';
        //             $contract[$key].='<tr>
        //                 <td  align="left" height="25"><strong>Cashier / Manager</strong></td>
        //                 <td colspan="" align="left" height="25"></td>
        //                 <td  align="left" height="25"><strong>Signature of Remitter</strong></td>
        //                 <td colspan="" align="left" height="25"></td>
        //             </tr>';
        //         }
        //         $contract[$k].='</table><p>*This is a computer generated receipt and does not require authorization.</p><pagebreak>';          

        // }   
        $groupdata = array();
        $feeData = array();
    }
    // print_r($contract);
    $data = implode('<br/>',$contract);

    // print_r($data);exit;

	require_once 'vendor/autoload.php';

	$mpdf = new \Mpdf\Mpdf();
	$mpdf->SetWatermarkText('PAID', 0.08);
	$mpdf->showWatermarkText = true; 

	$challanID = str_replace('/', '', trim($challanno));
	// $pdf->convert($data,$documentPath.$challanID.".pdf",1);

	$mpdf->WriteHTML($data);
	$mpdf->Output($documentPath.$challanID.".pdf",'F');

	$mail_content = 'Please find the attached receipt.';

	$sms_content = 'Dear Parent, You have the paid the amount for the challan '.trim($challanno).'/-. Email with receipt will be sent to your registered Email ID.';

	$receiptpath = $documentPath.$challanID.".pdf";
	$subject = 'Paid Challan Receipt';

	// sendNotificationToParents($studId, $mail_content, $sms_content,'1', $receiptpath, $subject);

	if($type != '') {
		$_SESSION['success'] = "<p class='success-msg'>Receipt Generated Successfully.</p>";
		header("Location:createreceipt.php");
	} else {
		header("Location:studetscr.php");
	}
 }

function updatereceipt_by_feetype($challanNo, $studentId, $feegroup='', $feeType){
 
    $challan = array();
    $where = '';

	$challanData = sqlgetresult('SELECT id,"feeType" FROM tbl_challans WHERE "challanStatus" = 1 AND "challanNo" = \''.$challanNo.'\' AND "feeType"= \''.$feeType.'\' AND deleted=0',true);
	$challanfeetype = array();

	    foreach ($challanData as $k => $v) {
	    	$challanfeetype[$v['id']]=$v['feeType'];
	    }

    $receiptdata= sqlgetresult('SELECT "feeType" FROM tbl_receipt WHERE "challanNo" = \''.$challanNo.'\' AND "feeType"= \''.$feeType.'\'',true);

    $receiptfeetype = array();

	    foreach ($receiptdata as $k => $v) {
	        array_push($receiptfeetype, $v['feeType']);
	    }

	 // print_r($challanfeetype);
	 // echo('<hr/>');
	 // print_r($receiptfeetype);
	 // echo('<hr/>');

    $resultarray=array_diff($challanfeetype,$receiptfeetype);

    // print_r($resultarray);
    // exit;

	    foreach ($resultarray as $k => $row)
	    {
	        $id = trim($k);
	        $datas = sqlgetresult("SELECT * FROM createReceiptRows('".$id."')");
	    }
    return 1;
}

function createPDF_by_feetype($studId,$chlno,$type='',$type_ids) {

    $getparentmailid = sqlgetresult('SELECT "email" AS parentMailId FROM tbl_student WHERE "studentId"=\''.$studId . '\'');

    $challanData = sqlgetresult('SELECT * FROM challandatanew WHERE "challanNo" = \'' . $chlno . '\' AND "challanStatus" = 1 ',true);

    $findSFS = sqlgetresult('SELECT "feeTypes",amount,quantity,"totalAmount" FROM tbl_sfs_qty WHERE "challanNo" = \''.$chlno.'\' ', true);
    $sfs=[];
    if(count($findSFS) > 0){
		foreach ($findSFS as $key => $value) {
			$tpe=trim($value['feeTypes']);
			$sfs[$tpe]=$value;
		}
    }
    //exit;
    // print_r($challanData);
    //$feeTypes = sqlgetresult("SELECT * FROM getFeeTypes");    
    $mailid = $getparentmailid['parentmailid'];
    $to = $mailid;

    /*if($type != '') {
        $datefolder = date("dmY", strtotime($type));
    } else {
        $datefolder = date('dmY');
    }*/
    $datefolder = date('dmY');

    if (!is_dir(BASEPATH."receipts/".$datefolder)) {
        mkdir(BASEPATH."receipts/".$datefolder);
    }   
    $documentPath = BASEPATH."receipts/".$datefolder."/";

    $total = 0;
    $feeData = array();  
    $chlncnt = count($challanData);  
    $groupdata = array();
    $feeDatas = [];
    $types=[]; 
    foreach ($challanData as $k =>$value) {
        $challanno = $value['challanNo'];
        $academicYear = $value['academicYear'];
        $date=date("d-m-Y");
        $name = $value['studentName'];
        $Semester = $value['term'];
        //$challanData1['clid'] = $value['clid'];
        
        $studentId = $value['studentId'];
        $className = $value['class_list'];
        $duedate = $value['duedate'];
        //$challanData1['duedateuniquearray'] = array_unique(array_filter($challanData1['duedate']));
        //$challanData1['stream'] = $value['stream'];
        //$streamName = $value['steamname'];
        $org_total = $value['org_total'];
        $section = $value['section'];
        $cheque = $value['cheque_dd_no'];
        $bank = $value['bank'];
        $pay_type = $value['pay_type'];
        $pdate = $value['paid_date'];
        
        //getFeeGroupbyId($value['feeGroup'];
        //$updatedon = date('Y-m-d',strtotime($pdate));
        if($value['remarks'] != ''){
        	$remarks = $value['remarks'];
        }
        else{
        	$remarks = 'Nil';
        }
        $feegroup = $value['feeGroup'];
        $ftypeId=trim($value['feeType']);
        $ftype=getFeeTypebyId($ftypeId); 
        
        $types['name']=trim($ftype);
        $types['total']=$value['total'];
        $types['pdate']=$pdate;
        $types['mode']=$pay_type." - ".$cheque;
        $types['bank']=$bank;
        $types['sfsQty']=isset($sfs[$ftypeId]['quantity'])?$sfs[$ftypeId]['quantity']:"";


        $feeDatas[$feegroup]['types'][] = $types;
        $feeDatas[$feegroup]['waived'] = getwaiveddata($challanno, $feegroup);
      
    }

   $content=[];
    foreach ($feeDatas as $key => $feeData) {
    	# code...
    	$feegroup=getFeeGroupbyId($key);
		$content[$key].=' <style> .noBorder td{ border:none} </style>
		<table width="750" border="0" >
		<tr> <td  align="center" valign="top"><img src="images/logo_pdf.jpg"  height="80" /> </td> 
		<td align="right">'.trim($feegroup).'</td>
		</tr>               
		</table>';

		$content[$key].='<table width="750" border="1" cellspacing="0" cellpadding="15">
		    <tr>
		    <td colspan="4" align="left" height="25"><strong>Challan No.</strong>: '.$challanno.' </td>
		    <td colspan="" align="left" height="25"><strong>Academic Year</strong>: '.getAcademicyrById($academicYear).' </td>
		    <td colspan="" align="left" height="25"><strong>Date</strong>: '.$date.' </td>
		</tr>               
		<tr>
		    <td align="left" colspan="5" height="25"><strong>NAME</strong>: '.$name.' </td>
		    <td align="left" ><strong>SEMESTER</strong>: '.$Semester.' </td>
		</tr>
		<tr>
		    <td align="left" colspan="4" height="25"><strong>Student ID</strong>: '.$studentId.' </td>
		    <td align="left" ><strong>Class</strong>: '.$className.'-'. $section .' </td>
		    <td align="left" ><strong>Due Date</strong>: '.date("d-m-Y", strtotime($duedate)).' </td>
		</tr>               
		<tr>
		    <td align="center" height="25"><strong>S.No</strong></td>
		    <td align="center" height="25"><strong>Paid Date</strong></td>
		    <td align="center" height="25"><strong>Mode</strong></td>
		    <td align="center" height="25"><strong>Bank</strong></td>
		    <td align="center" height="25"><strong>Particluars</strong></td>
		    <td align="center" height="25" ><strong>Amount</strong></td>
		</tr>';
		$tot=0;
		foreach ($feeData['types'] as $key1 => $type) {
			$i=$key1+1;
			$tot+=$type['total'];
			if(!empty($type['sfsQty'])){
               $fname=$type['name']." (".$type['sfsQty'].")";
			}else{
               $fname=$type['name'];
			} 
			$content[$key].='<tr>
				<td align="center" height="25">'.$i.'</td>
				<td align="center" height="25">'.$type['pdate'].'</td>
				<td align="center" height="25">'.$type['mode'].'</td>
				<td align="center" height="25">'.$type['bank'].'</td>
				<td align="left" height="25">'.$fname.'</td>
				<td align="right" height="25" style="padding-right:10px">'.$type['total'].'</td>
			</tr>';
		}
		$waived=$feeData['waived'];
		if(count($waived) > 0){
           foreach ($waived as $key1 => $type) {
			    //$i=$key1+1;
				$tot-=$type['waiver_total'];
				$content[$key].='<tr>
					<td colspan="5" align="right" height="25" style="padding-right:10px">Waiver - '.$type['waiver_type'].'</td>
					<td align="right" height="25" style="padding-right:10px">'.$type['waiver_total'].'</td>
				</tr>';
		   }
		}
		$content[$key].='<tr>
                    <td colspan="5" align="right" height="25" style="padding-right:10px"><strong>Total</strong></td>
                    <td align="right" style="padding-right:10px"> '.$tot.' </td>
                </tr>';
        $words="";        
        if($tot >= 0){
          $words=getCurrencyInWords($tot);
        }
        $content[$key].='<tr>
		    <td colspan="6" align="left" height="25"><strong>Rupees in words : </strong>'.$words.' </td>
		  </tr>';        
		$content[$key].='</table>';
    }

   $data=implode("<br/>", $content);
   //exit;

    require_once 'vendor/autoload.php';

	$mpdf = new \Mpdf\Mpdf();
	$mpdf->SetWatermarkText('PAID', 0.08);
	$mpdf->showWatermarkText = true; 

	$challanID = str_replace('/', '', trim($challanno));
	// $pdf->convert($data,$documentPath.$challanID.".pdf",1);

	$mpdf->WriteHTML($data);
	$mpdf->Output($documentPath.$challanID.".pdf",'F');

	$mail_content = 'Please find the attached receipt.';

	$sms_content = 'Dear Parent, You have the paid the amount for the challan '.trim($challanno).'/-. Email with receipt will be sent to your registered Email ID.';

	$receiptpath = $documentPath.$challanID.".pdf";
	$subject = 'Paid Challan Receipt';

	//sendNotificationToParents($studId, $mail_content, $sms_content,'1', $receiptpath, $subject);
}

/* To get WaiverType by Id*/
function getWaiverTypebyId($id) {
    $id=trim($id);
	$data  = sqlgetresult('SELECT waivertypes FROM tbl_waivertypes WHERE "id"  =  \''.$id.'\'');
	return $data['waivertypes'];	
}

function getBankList() {
    //$id=trim($id);
    $banknames=[];
	$data  = sqlgetresult('SELECT bank FROM adminbankcheck WHERE "status"  =  \'ACTIVE\'');
	$num=count($data);
	if($num > 0){
		foreach($data as $name){
           $banknames[]=$name['bank'];
		}
	}
	return $banknames;	
}

$banks=getBankList();

/* Fee Type wise */
function flattableentry_feetype($challanno, $studentid, $fromwhere = ''){
	//echo $fromwhere;
	$challantype = 'CHALLAN';
	// $challanNo = 'IGCSE2018/002123';
	// $studentid = 'IG 370';
	// $semester = 'II';
	// $academicyear = '3';
	// $challanStatus = '0';
	$demandtype = "DEMAND";
	$receipttype = "RECEIPT";
	$waivertype = "WAIVER";
    $receipts_ary=[];
	if($fromwhere == 'Receipt'){
		$receiptdatas = sqlgetresult('SELECT g."feeGroup", c."total", c."updatedOn", c."pay_type", c,"cheque_dd_no", c."bank", c."paid_date", c."chequeRemarks", c."feeGroup"  as fgroupid,c."feeType"  as ftypeid
	        FROM tbl_receipt c 
	        LEFT JOIN tbl_fee_group g ON c."feeGroup" = g.id
	        WHERE c."challanNo" = \''.$challanno.'\'',true);
		
	    if(count($receiptdatas) > 0){
			foreach ($receiptdatas as $key => $value) {
				$ftypeid=trim($value['ftypeid']);
				$ftypname=getFeeTypebyId($ftypeid);
				if($ftypname == ''){
				    $ftypname = 'LATE FEE';
				}
				$ftypname=trim($ftypname);
				$receipts_ary[$ftypname]=$value;
				if($value['fgroupid']==0)
				{
					$receipts_ary[$ftypname]['feeGroup']='LATE FEE';
				}
				
			}
	    }
	}

	$challandatas = sqlgetresult('SELECT c."studentId", c."challanNo", s."studentName", a."year" AS "academicYear", cl."class_list", st."stream", c."term", g."feeGroup", f."feeType", c."createdOn", c."updatedOn", c."total", c."challanStatus", c."feeGroup" as feegroupid  FROM tbl_challans c 
	LEFT JOIN tbl_student s ON (c."studentId" = s."studentId" OR c."studentId" = s."application_no" OR ((c."studentId")::bpchar)::TEXT = ANY (string_to_array ((s."old_studentId")::TEXT,\',\'::TEXT)))
	LEFT JOIN tbl_academic_year a ON c."academicYear" = a.id
	LEFT JOIN tbl_class cl ON c."classList" = cl."id"
	LEFT JOIN tbl_stream st ON c."stream" = st.id
	LEFT JOIN tbl_fee_group g ON c."feeGroup" = g.id
	LEFT JOIN tbl_fee_type f ON c."feeType" = f.id
	WHERE c."challanNo" = \''. $challanno .'\' AND c.deleted=0',true);

	$challandata = array();
	$challanstatusunique = array();
    $feeDatas = [];
    $types=[];
    $receipts=[];
    $demand=[];
    foreach ($challandatas as $key => $data) {

        $fgroup=trim($data['feeGroup']);
        $ftype=trim($data['feeType']);
        $feegroupid=trim($data['feegroupid']);

        if($fgroup == ''){
            $fgroup = 'LATE FEE';
        }
        if($ftype == ''){
            $ftype = 'Late Fee';
        }

        $receipttotal = isset($receipts_ary[$ftype]['total'])?$receipts_ary[$ftype]['total']:"";
        $cheque = isset($receipts_ary[$ftype]['cheque_dd_no'])?$receipts_ary[$ftype]['cheque_dd_no']:"";
        $bank = isset($receipts_ary[$ftype]['bank'])?$receipts_ary[$ftype]['bank']:"";
        $pay_type = isset($receipts_ary[$ftype]['pay_type'])?$receipts_ary[$ftype]['pay_type']:"";
        $pdate = isset($receipts_ary[$ftype]['paid_date'])?$receipts_ary[$ftype]['paid_date']:"";
        $cremarks = isset($receipts_ary[$ftype]['chequeRemarks'])?$receipts_ary[$ftype]['chequeRemarks']:"";
        $cremarks = isset($receipts_ary[$ftype]['chequeRemarks'])?$receipts_ary[$ftype]['chequeRemarks']:"";
        $updatedOn = isset($receipts_ary[$ftype]['updatedOn'])?$receipts_ary[$ftype]['updatedOn']:"";

        $studentId = trim($data['studentId']); 
        $studentName = trim($data['studentName']);   
        $academicYear = trim($data['academicYear']);  
        $class = trim($data['class_list']);   
        $stream = trim($data['stream']);    
        $term = trim($data['term']);
        $ftype=trim($ftype);
        //$types['name']=trim($ftype);
        $demand['type']=$ftype;
        $demand['demandtotal']=$data['total'];
        $demand['demanddate']=date('Y-m-d',strtotime($data['createdOn']));

        $receipts['type']=$ftype;
        $receipts['receipttotal']=$receipttotal;
        $receipts['pdate']=$pdate;
        $receipts['mode']=$pay_type;
        $receipts['cheque_dd_no']=$cheque;
        $receipts['bank']=$bank;
        $receipts['cremarks']=$cremarks;
        $receipts['updatedOn']=$updatedOn;
        
        $feeDatas[$fgroup][$demandtype][] = $demand;
        $feeDatas[$fgroup][$receipttype][] = $receipts;
        $feeDatas[$fgroup]['waived'] = getwaiveddata($challanno, $feegroupid); 
  
    }

	foreach($feeDatas AS $key => $value){
	  $feegroup=$key;
	  $demand_data=$value[$demandtype];
	  if(count($demand_data) > 0){
		foreach($demand_data AS $key1 => $value1 ){
			$feetype = $value1['type'];
			$amount = $value1['demandtotal'];
			$date = $value1['demanddate'];
			$remarks='';
			$insertstudentledger = sqlgetresult("SELECT * FROM studentledgeradddatademand('$studentId','$challanno', '$studentName', '$academicYear', '$class', '$stream', '$term', '$date', '$feegroup', '$feetype', '$amount', '$remarks', '$demandtype')",true);
		}
	  }
	  $receipt_data=$value[$receipttype];
	  if(count($receipt_data) > 0){
	  	foreach($receipt_data AS $key2 => $value2 ){
	  		$feetype = $value2['type'];
	  		$amount = $value2['receipttotal'];
	  		$date = $value2['pdate'];
	  		$type = $value2['mode'];
	  		$cheque_dd_no = $value2['cheque_dd_no'];
	  		$bank = $value2['bank'];
	  		$cremarks = $value2['cremarks'];
	  		$updatedOn = $value2['updatedOn'];
	        $paymentremarks=""; 
	  		if(!empty($amount) && !empty($type)){
	          $paymentremarks= trim($bank).'/'.trim($cheque_dd_no).'/'.trim($date).'/'.trim($cremarks);
	          $remarks = substr($paymentremarks,0,80);
	          $insertstudentledger = sqlgetresult("SELECT * FROM studentledgeradddatabyfeetype('$studentId','$challanno', '$studentName', '$academicYear', '$class', '$stream', '$term', '$date', '$feegroup', '$amount', '$remarks', '$receipttype', '$feetype')",true);
	  		}	
	    }
	  } 
	  
	}
}


/* To get Available Balance*/
function toGetAvailableBalance($sid) {
    /* Student Id (Primary Key) */
    $sid=trim($sid);
	$data  = sqlgetresult('SELECT amount FROM advancepayment WHERE "sid"  =  \''.$sid.'\'');
	return isset($data['amount'])?$data['amount']:0;	
}

/* To get tbl_partial_payment Balance*/
function toGetPartialAmount($sid) {
    /* Student Id (Primary Key) */
    $sid=trim($sid);
	$data  = sqlgetresult('SELECT amount FROM tbl_partial_payment WHERE "sid"  =  \''.$sid.'\' AND deleted=0 LIMIT 1');
	return isset($data['amount'])?$data['amount']:0;	
}


function createPDF_advance($paymentid) {

 $paymentid=trim($paymentid);
 //echo 'SELECT a.*,p."challanNo",p."transStatus" FROM advancePaymentLogDetails a JOIN tbl_payments p ON (a.id=p.advanceid) WHERE p.id = \''.$paymentid.'\'';
 //exit;
 //$getparentmailid = sqlgetresult('SELECT a.*,p."challanNo",p."transStatus",p."transDate" FROM advancePaymentLogDetails a JOIN tbl_payments p ON (a.id=p.advanceid) WHERE p.id = \''.$paymentid.'\'', true);

 $getparentmailid = sqlgetresult('SELECT * FROM advancePaymentLogDetails WHERE id = \''.$paymentid.'\'', true);

 $num=count($getparentmailid);
 if($num > 0){
    $date=date("Y-m-d");
 	$datefolder = 'advance';

    if (!is_dir(BASEPATH."receipts/".$datefolder)) {
        mkdir(BASEPATH."receipts/".$datefolder);
    }   
    $documentPath = BASEPATH."receipts/".$datefolder."/";

 	$studentName=trim($getparentmailid[0]['studentName']);
 	$streamname=trim($getparentmailid[0]['streamname']);
 	$studentId=trim($getparentmailid[0]['studentId']);
 	$mailid=trim($getparentmailid[0]['email']);
 	$academic_yr=trim($getparentmailid[0]['academic_yr']);
 	$className=trim($getparentmailid[0]['class_list']);
 	$section=trim($getparentmailid[0]['section']);
 	$semester=trim($getparentmailid[0]['term']);
 	$challanNo=trim($getparentmailid[0]['transNum']);
 	$transDate=trim($getparentmailid[0]['transDate']);
 	$amount=trim($getparentmailid[0]['amount']);

	$words="";        
	if($amount >= 0){
	  $words=getCurrencyInWords($amount);
	}


	$data=' <style> .noBorder td{ border:none} </style>
	<table width="750" border="0" >
	<tr> <td  align="center" valign="top"><img src="images/logo_pdf.jpg"  height="80" /> </td> 
	<td align="right">Advance Payment</td>
	</tr>               
	</table><table width="750" border="1" cellspacing="0" cellpadding="15">
	    <tr>
	    <td align="left" height="25"><strong>Ref No.</strong>: '.$challanNo.' </td>
	    <td align="left" height="25"><strong>Academic Year</strong>: '.getAcademicyrById($academic_yr).' </td>
	    <td align="left" height="25"><strong>Date</strong>: '.$date.' </td>
	</tr>               
	<tr>
	    <td align="left" colspan="2" height="25"><strong>NAME</strong>: '.$studentName.' </td>
	    <td align="left" ><strong>SEMESTER</strong>: '.$semester.' </td>
	</tr>
	<tr>
	    <td align="left" height="25"><strong>Student ID</strong>: '.$studentId.' </td>
	    <td align="left" ><strong>Class</strong>: '.$className.'-'. $section .' </td>
	    <td align="left" ><strong>Paid Date</strong>: '.date("Y-m-d", strtotime($transDate)).' </td>
	</tr>               
	<tr>
	    <td align="center" height="25"><strong>S.No</strong></td>
	    <td align="center" height="25"><strong>Particluars</strong></td>
	    <td align="center" height="25" ><strong>Amount</strong></td>
	</tr><tr>
			<td align="center" height="50">1</td>
			<td align="left" height="25">Advance</td>
			<td align="right" height="25" style="padding-right:10px">'.$amount.'</td>
		</tr><tr>
	            <td colspan="2" align="right" height="25" style="padding-right:10px"><strong>Total</strong></td>
	            <td align="right" style="padding-right:10px"> '.$amount.' </td>
	        </tr><tr>
	    <td colspan="3" align="left" height="25"><strong>Rupees in words : </strong>'.$words.' </td>
	  </tr></table>';

    require_once 'vendor/autoload.php';

	$mpdf = new \Mpdf\Mpdf();
	$mpdf->SetWatermarkText('PAID', 0.08);
	$mpdf->showWatermarkText = true; 

	$challanID = str_replace('/', '', trim($challanNo));
	// $pdf->convert($data,$documentPath.$challanID.".pdf",1);

	$mpdf->WriteHTML($data);
	$mpdf->Output($documentPath.$challanID.".pdf",'F');

	$mail_content = 'Please find the attached receipt.';

	$sms_content = 'Dear Parent, You have the paid the amount for the challan '.trim($challanno).'/-. Email with receipt will be sent to your registered Email ID.';

	$receiptpath = $documentPath.$challanID.".pdf";
	$subject = 'Paid Advance Receipt';
	//sendNotificationToParents($studId, $mail_content, $sms_content,'1', $receiptpath, $subject);	
 }

}


function completeTransactionById($payment_id){
	date_default_timezone_set("Asia/Kolkata");    
	$cur_data = time();
	$date = date('Y-m-d h:i:s');
	$receiptupd=0;
	//echo 'SELECT t.challanids,t.smsfdata,t.schoolutilsdata,s.term,s.academic_yr,s."studentId",s."parentId"  FROM tbl_transaction t JOIN tbl_student s ON (t.sid=s.id) WHERE t.id= \''.$payment_id.'\' ';

	//$entry = sqlgetresult('SELECT t.challanids,t.smsfdata,t.schoolutilsdata,s.term,s.academic_yr,s."studentId",s."parentId"  FROM tbl_transaction t JOIN tbl_student s ON (t.sid=s.id) WHERE t.id= \''.$payment_id.'\'  AND "transStatus"=\''Ok'\'', true);
	$entry = sqlgetresult('SELECT t.challanids,t.smsfdata,t.schoolutilsdata,s.term,s.academic_yr,s."studentId",s."parentId",t."transStatus", t."transNum", t."returnCode", t."remarks", t."transDate", t."createdBy", t."createdOn"  FROM tbl_transaction t JOIN tbl_student s ON (t.sid=s.id) WHERE t.id= \''.$payment_id.'\'', true);
	//print_r($entry);
	if(count($entry) > 0){
		 /* Challan Data */
	     $challans= trim($entry[0]['challanids']);
	     /* SMSF Data */
	     $smsfdata= trim($entry[0]['smsfdata']);
	     /* School Utils Data */
	     $schoolutilsdata= trim($entry[0]['schoolutilsdata']);
	     /* Student Data */
	     $parent_id= trim($entry[0]['parentId']);
	     $student_id= trim($entry[0]['studentId']);
	     $term= trim($entry[0]['term']);
	     $acad_year= trim($entry[0]['academic_yr']);


	     $returnCode= trim($entry[0]['returnCode']);
	     $remarks= trim($entry[0]['remarks']);
	     $createdby= trim($entry[0]['createdBy']);
	     $createdOn= trim($entry[0]['createdOn']);

	     $transStatus=trim($entry[0]['transStatus']);
		 $transNum=trim($entry[0]['transNum']);
		 $transDate=trim($entry[0]['transDate']);

         
	     
	     $challansArr=explode(",", $challans);
	     foreach ($challansArr as $challanNo) {
	     	$challanNo=trim($challanNo);

	     	$payment_remarks=$challanNo."/Online";

	     	$payment = sqlgetresult('INSERT INTO tbl_payments ("parentId", "studentId", "challanNo","amount","transStatus", "transNum", "returnCode", "remarks", "transDate", "createdby", "createdOn") VALUES (\''.$parent_id.'\',\''.$student_id.'\',\''.$challanNo.'\',\''.$amount.'\',\''.$transStatus.'\',\''.$transNum.'\',\''.$returnCode.'\',\''.$payment_remarks.'\',\''.$transDate.'\',\''.$createdby.'\',\''.$createdOn.'\') RETURNING id');


	            $updateChallan = sqlgetresult('UPDATE tbl_challans SET "challanStatus" = 1, "updatedBy" = \''.$parent_id.'\', "updatedOn" = \''.$date.'\' WHERE "challanNo" = \''.$challanNo.'\' AND deleted=0');
	            $demandtblupd =sqlgetresult('UPDATE tbl_demand SET "challanStatus" = 1, "updatedOn" = CURRENT_TIMESTAMP, "updatedBy" = \''. $parent_id .'\' WHERE "challanNo" =\'' .$challanNo . '\' AND "studentId" = \''.$student_id.'\' AND "term" = \''.$term.'\' AND "academicYear" = \''.$acad_year.'\' AND deleted=0');

	            $waivertblupd = sqlgetresult('UPDATE tbl_waiver SET "challanStatus" = 1, "modified_on" = CURRENT_TIMESTAMP, "modified_by" = \''. $parent_id.'\' WHERE "challanNo" =\'' .$challanNo . '\' AND "studentId" = \''.$student_id.'\' AND deleted=0');

	            createPDF($student_id,$challanNo);
	            $receiptupd = updatereceipt(trim($challanNo), trim($student_id));  

	            $fromwhere = 'Receipt';
	            flattableentry(trim($challanNo), trim($student_id), $fromwhere);
	     }

         if(isset($smsfdata) && !empty($smsfdata)){

         	$smsfarr=json_decode($smsfdata);
         	foreach ($smsfarr as $key => $value) {
         		$arr=explode("-", $value);
         		$name=trim($arr[0]);
         		$sfsfeeId=trim($arr[1]);
         		$confid=trim($arr[2]);
         		$studentId=trim($arr[3]);
         		$singleqtyamount=trim($arr[4]);
         		$grp=trim($arr[6]);
         		$qty=trim($arr[7]);

                $sfsfeeName=getFeeTypebyId($sfsfeeId);
                $amount=($singleqtyamount*$qty);

         		//$challan_suffix=$arr[1]."-".$arr[2]."-".$studentId."-".$sfsextraqty;
         		//$eventname = "UNIFORM-".$challan_suffix;
         		$eventname = $name."-".$sfsfeeId."-".$confid."-".$studentId."-".$qty;

				$paymenttablecheck = sqlgetresult('SELECT COUNT(*) as total FROM tbl_payments WHERE "challanNo" = \''.$eventname.'\' AND "transStatus" = \'Ok\'',true);
				$num=$paymenttablecheck[0]['total'];
				$serial=$num+1;
				$cusChallanNo=$eventname."-".$serial;

				//echo 'INSERT INTO tbl_payments ("parentId", "studentId", "challanNo","amount","transStatus", "transNum", "returnCode", "remarks", "transDate", "createdby", "createdOn") VALUES (\''.$parent_id.'\',\''.$student_id.'\',\''.$cusChallanNo.'\',\''.$amount.'\',\''.$transStatus.'\',\''.$transNum.'\',\''.$returnCode.'\',\''.$remarks.'\',\''.$transDate.'\',\''.$createdby.'\',\''.$createdOn.'\') RETURNING id'."<br>";

				//echo "SELECT * FROM sfstableentry('".$cusChallanNo."','". trim($sfsfeeName) ."','".$singleqtyamount."', '". $qty ."', '". $amount ."','".$parent_id."','". $student_id ."')";
				
               $payment_id = sqlgetresult('INSERT INTO tbl_payments ("parentId", "studentId", "challanNo","amount","transStatus", "transNum", "returnCode", "remarks", "transDate", "createdby", "createdOn") VALUES (\''.$parent_id.'\',\''.$student_id.'\',\''.$cusChallanNo.'\',\''.$amount.'\',\''.$transStatus.'\',\''.$transNum.'\',\''.$returnCode.'\',\''.$remarks.'\',\''.$transDate.'\',\''.$createdby.'\',\''.$createdOn.'\') RETURNING id');

                if(isset($payment_id['id'])){
            	  $sfsqty = sqlgetresult("SELECT * FROM sfstableentry('".$cusChallanNo."','". trim($sfsfeeName) ."','".$singleqtyamount."', '". $qty ."', '". $amount ."','".$parent_id."','". $student_id ."')");
                  createUFPDF($payment_id['id'],$student_id);
                }

         	}
         }

          if(isset($schoolutilsdata) && !empty($schoolutilsdata)){

         	$scUtilityArr=json_decode($schoolutilsdata);
         	foreach ($scUtilityArr as $key => $value) {

         		$arr=explode("-", $value);
         		$name=trim($arr[0]);
         		$sfsfeeId=trim($arr[1]);
         		$confid=trim($arr[2]);
         		$studentId=trim($arr[3]);
         		$singleqtyamount=trim($arr[4]);
         		$grp=trim($arr[6]);

         		$cusChallanNo = $name."-".$sfsfeeId."-".$confid."-".$studentId;
         		//echo 'INSERT INTO tbl_payments ("parentId", "studentId", "challanNo","amount","transStatus", "transNum", "returnCode", "remarks", "transDate", "createdby", "createdOn") VALUES (\''.$parent_id.'\',\''.$student_id.'\',\''.$cusChallanNo.'\',\''.$singleqtyamount.'\',\''.$transStatus.'\',\''.$transNum.'\',\''.$returnCode.'\',\''.$remarks.'\',\''.$transDate.'\',\''.$createdby.'\',\''.$createdOn.'\') RETURNING id';
         		$payment_id = sqlgetresult('INSERT INTO tbl_payments ("parentId", "studentId", "challanNo","amount","transStatus", "transNum", "returnCode", "remarks", "transDate", "createdby", "createdOn") VALUES (\''.$parent_id.'\',\''.$student_id.'\',\''.$cusChallanNo.'\',\''.$singleqtyamount.'\',\''.$transStatus.'\',\''.$transNum.'\',\''.$returnCode.'\',\''.$remarks.'\',\''.$transDate.'\',\''.$createdby.'\',\''.$createdOn.'\') RETURNING id');

                if(isset($payment_id['id'])){
                  createLFPDF($payment_id['id'],$student_id);
                }
         	}
         }

	}
	return $receiptupd;

}


function completePartialTransactionById($payment_id){
	date_default_timezone_set("Asia/Kolkata");    
	$cur_data = time();
	$date = date('Y-m-d h:i:s');
	$receiptupd=0;
	$entry = sqlgetresult('SELECT t.sid,t.balance AS ewallBal, t.challanids,t.smsfdata,t.schoolutilsdata,s.term,s.academic_yr,s."studentId",s."parentId",t."transStatus", t."transNum", t."returnCode", t."remarks", t."transDate", t."createdBy", t."createdOn"  FROM tbl_partial_payment_log t JOIN tbl_student s ON (t.sid=s.id) WHERE t.id= \''.$payment_id.'\'', true);
	//print_r($entry);
	if(count($entry) > 0){
		 $sid= trim($entry[0]['sid']);
		 $ewallBal= trim($entry[0]['ewallBal']);
		 /* Challan Data */
	     $challans= trim($entry[0]['challanids']);
	     /* SMSF Data */
	     $smsfdata= trim($entry[0]['smsfdata']);
	     /* School Utils Data */
	     $schoolutilsdata= trim($entry[0]['schoolutilsdata']);
	     /* Student Data */
	     $parent_id= trim($entry[0]['parentId']);
	     $student_id= trim($entry[0]['studentId']);
	     $term= trim($entry[0]['term']);
	     $acad_year= trim($entry[0]['academic_yr']);


	     $returnCode= trim($entry[0]['returnCode']);
	     $remarks= trim($entry[0]['remarks']);
	     $createdby= trim($entry[0]['createdBy']);
	     $createdOn= trim($entry[0]['createdOn']);

	     $transStatus=trim($entry[0]['transStatus']);
		 $transNum=trim($entry[0]['transNum']);
		 $transDate=trim($entry[0]['transDate']);

	     $challansArr=explode(",", $challans);
	     foreach ($challansArr as $challanNo) {
	     	$challanNo=trim($challanNo);
	     	$payment_remarks=$transNum."/Online";

		    $waivedAmt=0;
			$chalAmt=0;
			$chdata=getTotalbychallan($challanNo);

			if(isset($chdata['org_total']) && !empty($chdata['org_total'])){
			    $chalAmt=$chdata['org_total'];
			}
			$wdata=getwaiveramountbychallan($challanNo);
			if(isset($wdata['waiver_total']) && !empty($wdata['waiver_total'])){
			    $waivedAmt=$wdata['waiver_total'];
			}
             
            $cAmt= $chalAmt-$waivedAmt;
            paidpartialchallans($challanNo, $cAmt, $transNum, $parent_id);

	     	$payment = sqlgetresult('INSERT INTO tbl_payments ("parentId", "studentId", "challanNo","amount","transStatus", "transNum", "returnCode", "remarks", "transDate", "createdby", "createdOn") VALUES (\''.$parent_id.'\',\''.$student_id.'\',\''.$challanNo.'\',\''.$cAmt.'\',\''.$transStatus.'\',\''.$transNum.'\',\''.$returnCode.'\',\''.$payment_remarks.'\',\''.$transDate.'\',\''.$createdby.'\',\''.$createdOn.'\') RETURNING id');
	     	
	            $updateChallan = sqlgetresult('UPDATE tbl_challans SET "challanStatus" = 1, "updatedBy" = \''.$parent_id.'\', "updatedOn" = \''.$date.'\' WHERE "challanNo" = \''.$challanNo.'\' AND deleted=0');
	            $demandtblupd =sqlgetresult('UPDATE tbl_demand SET "challanStatus" = 1, "updatedOn" = CURRENT_TIMESTAMP, "updatedBy" = \''. $parent_id .'\' WHERE "challanNo" =\'' .$challanNo . '\' AND deleted=0');

	            $waivertblupd = sqlgetresult('UPDATE tbl_waiver SET "challanStatus" = 1, "modified_on" = CURRENT_TIMESTAMP, "modified_by" = \''. $parent_id.'\' WHERE "challanNo" =\'' .$challanNo . '\' AND deleted=0');

	            createPDF($student_id,$challanNo);
	            $receiptupd = updatereceipt(trim($challanNo), trim($student_id));  

	            $fromwhere = 'Receipt';
	            flattableentry(trim($challanNo), trim($student_id), $fromwhere);
	     }

         if(isset($smsfdata) && !empty($smsfdata)){

         	$smsfarr=json_decode($smsfdata);
         	foreach ($smsfarr as $key => $value) {
         		$arr=explode("-", $value);
         		$name=trim($arr[0]);
         		$sfsfeeId=trim($arr[1]);
         		$confid=trim($arr[2]);
         		$studentId=trim($arr[3]);
         		$singleqtyamount=trim($arr[4]);
         		$grp=trim($arr[6]);
         		$qty=trim($arr[7]);

                $sfsfeeName=getFeeTypebyId($sfsfeeId);
                $amount=($singleqtyamount*$qty);

         		//$challan_suffix=$arr[1]."-".$arr[2]."-".$studentId."-".$sfsextraqty;
         		//$eventname = "UNIFORM-".$challan_suffix;
         		$eventname = $name."-".$sfsfeeId."-".$confid."-".$studentId."-".$qty;

				$paymenttablecheck = sqlgetresult('SELECT COUNT(*) as total FROM tbl_payments WHERE "challanNo" = \''.$eventname.'\' AND "transStatus" = \'Ok\'',true);
				$num=$paymenttablecheck[0]['total'];
				$serial=$num+1;
				$cusChallanNo=$eventname."-".$serial;

               $payment_id = sqlgetresult('INSERT INTO tbl_payments ("parentId", "studentId", "challanNo","amount","transStatus", "transNum", "returnCode", "remarks", "transDate", "createdby", "createdOn") VALUES (\''.$parent_id.'\',\''.$student_id.'\',\''.$cusChallanNo.'\',\''.$amount.'\',\''.$transStatus.'\',\''.$transNum.'\',\''.$returnCode.'\',\''.$remarks.'\',\''.$transDate.'\',\''.$createdby.'\',\''.$createdOn.'\') RETURNING id');

                if(isset($payment_id['id'])){
            	  $sfsqty = sqlgetresult("SELECT * FROM sfstableentry('".$cusChallanNo."','". trim($sfsfeeName) ."','".$singleqtyamount."', '". $qty ."', '". $amount ."','".$parent_id."','". $student_id ."')");
                  createUFPDF($payment_id['id'],$student_id);
                }

         	}
         }

          if(isset($schoolutilsdata) && !empty($schoolutilsdata)){

         	$scUtilityArr=json_decode($schoolutilsdata);
         	foreach ($scUtilityArr as $key => $value) {

         		$arr=explode("-", $value);
         		$name=trim($arr[0]);
         		$sfsfeeId=trim($arr[1]);
         		$confid=trim($arr[2]);
         		$studentId=trim($arr[3]);
         		$singleqtyamount=trim($arr[4]);
         		$grp=trim($arr[6]);

         		$cusChallanNo = $name."-".$sfsfeeId."-".$confid."-".$studentId;
         		//echo 'INSERT INTO tbl_payments ("parentId", "studentId", "challanNo","amount","transStatus", "transNum", "returnCode", "remarks", "transDate", "createdby", "createdOn") VALUES (\''.$parent_id.'\',\''.$student_id.'\',\''.$cusChallanNo.'\',\''.$singleqtyamount.'\',\''.$transStatus.'\',\''.$transNum.'\',\''.$returnCode.'\',\''.$remarks.'\',\''.$transDate.'\',\''.$createdby.'\',\''.$createdOn.'\') RETURNING id';
         		$payment_id = sqlgetresult('INSERT INTO tbl_payments ("parentId", "studentId", "challanNo","amount","transStatus", "transNum", "returnCode", "remarks", "transDate", "createdby", "createdOn") VALUES (\''.$parent_id.'\',\''.$student_id.'\',\''.$cusChallanNo.'\',\''.$singleqtyamount.'\',\''.$transStatus.'\',\''.$transNum.'\',\''.$returnCode.'\',\''.$remarks.'\',\''.$transDate.'\',\''.$createdby.'\',\''.$createdOn.'\') RETURNING id');

                if(isset($payment_id['id'])){
                  createLFPDF($payment_id['id'],$student_id);
                }
         	}
         }

	}
	return $receiptupd;

}



function updateChallanStatus($challanNo, $date, $parent_id, $student_id, $term, $acad_year, $status){
	$challanNo=trim($challanNo);


	$payment = sqlgetresult('INSERT INTO tbl_payments ("parentId", "studentId", "challanNo","amount","transStatus", "transNum", "returnCode", "remarks", "transDate", "createdby", "createdOn") VALUES (\''.$parent_id.'\',\''.$student_id.'\',\''.$challanNo.'\',\''.$amount.'\',\''.$transStatus.'\',\''.$transNum.'\',\''.$returnCode.'\',\''.$payment_remarks.'\',\''.$transDate.'\',\''.$createdby.'\',\''.$createdOn.'\') RETURNING id');

	//echo 'UPDATE tbl_challans SET "challanStatus" = \''.$status.'\', "updatedBy" = \''.$parent_id.'\', "updatedOn" = \''.$date.'\' WHERE "challanNo" = \''.$challanNo.'\' ';
	//exit;
	$updateChallan = sqlgetresult('UPDATE tbl_challans SET "challanStatus" = \''.$status.'\', "updatedBy" = \''.$parent_id.'\', "updatedOn" = \''.$date.'\' WHERE "challanNo" = \''.$challanNo.'\' AND deleted=0');
	//$demandtblupd =sqlgetresult('UPDATE tbl_demand SET "challanStatus" = "'.$status.'", "updatedOn" = CURRENT_TIMESTAMP, "updatedBy" = \''. $parent_id .'\' WHERE "challanNo" =\'' .$challanNo . '\' AND "studentId" = \''.$student_id.'\' AND "term" = \''.$term.'\' AND "academicYear" = \''.$acad_year.'\'');

	$demandtblupd =sqlgetresult('UPDATE tbl_demand SET "challanStatus" = \''.$status.'\', "updatedOn" = CURRENT_TIMESTAMP, "updatedBy" = \''. $parent_id .'\' WHERE "challanNo" =\'' .$challanNo . '\' AND "studentId" = \''.$student_id.'\' AND deleted=0');

	$waivertblupd = sqlgetresult('UPDATE tbl_waiver SET "challanStatus" = \''.$status.'\', "modified_on" = CURRENT_TIMESTAMP, "modified_by" = \''. $parent_id.'\' WHERE "challanNo" =\'' .$challanNo . '\' AND deleted=0');
	if($status==1){
		createPDF($student_id,$challanNo);
		$receiptupd = updatereceipt(trim($challanNo), trim($student_id));  

		$fromwhere = 'Receipt';
		flattableentry(trim($challanNo), trim($student_id), $fromwhere);
	}
    return 1;	
}


function paidpartialchallansnew($challan, $amt, $plogid, $parentid, $balance){

	$paymenttablecheck = sqlgetresult('SELECT COUNT(*) as total FROM tbl_partialpaid_challan WHERE "challanNo" = \''.$challan.'\' AND deleted=0',true);
	$num=$paymenttablecheck[0]['total'];
	$serial=$num+1;
	$cusChallanNo=$challan."-PP-".$serial;
	$uid=$parentid;

	$plogid=str_replace("REF","",$plogid);

	sqlgetresult('INSERT INTO tbl_partialpaid_challan (plogid,"challanNo","refchallanNo",paidamt,"createdBy","createdOn","balanceamt") VALUES (\''.$plogid.'\',\''.$challan.'\',\''.$cusChallanNo.'\',\''.$amt.'\',\''.$uid.'\',now(),\''.$balance.'\') RETURNING id');
}

function paidpartialchallans($challan, $amt, $plogid, $parentid){

	$paymenttablecheck = sqlgetresult('SELECT COUNT(*) as total FROM tbl_partialpaid_challan WHERE "challanNo" = \''.$challan.'\' AND deleted=0',true);
	$num=$paymenttablecheck[0]['total'];
	$serial=$num+1;
	$cusChallanNo=$challan."-PP-".$serial;
	$uid=$parentid;

	$plogid=str_replace("REF","",$plogid);

	sqlgetresult('INSERT INTO tbl_partialpaid_challan (plogid,"challanNo","refchallanNo",paidamt,"createdBy","createdOn") VALUES (\''.$plogid.'\',\''.$challan.'\',\''.$cusChallanNo.'\',\''.$amt.'\',\''.$uid.'\',now()) RETURNING id');
}


function getAmtPaidbychallan($challanno){
   $data = sqlgetresult('SELECT paidamt,balanceamt FROM tbl_partialpaid_challan WHERE "challanNo" = \''.$challanno.'\' AND deleted=0',true);
    $amount=0;
	foreach ($data as $k =>$value) {
		$paid_total=isset($value['paidamt'])?$value['paidamt']:0;
		$adv_total=isset($value['balanceamt'])?$value['balanceamt']:0;
		$amount+=$paid_total+$adv_total;
	}
	return $amount;

}


function getReceiptChallanPartial($challanno){
	$partialpaid=0;
    $data1 = sqlgetresult('SELECT "challanStatus" FROM tbl_challans WHERE "challanNo" = \''.$challanno.'\' AND "challanStatus" = \'2\' AND (deleted = 0) AND (status = \'1\'::status) ',true);
    if(count($data1) > 0){
    	$partialpaid=getAmtPaidbychallan($challanno);
    }
	return $partialpaid;
}


function getReceiptChallan($challanno){
	//$data = sqlgetresult('SELECT SUM(org_total) AS org_total FROM tbl_challans WHERE "challanNo" = \''.$challanno.'\' AND ("challanStatus" = \'1\' OR "challanStatus" = \'2\') AND (deleted = 0) AND (status = \'1\'::status) ');
	$data = sqlgetresult('SELECT SUM(org_total) AS org_total FROM tbl_challans WHERE "challanNo" = \''.$challanno.'\' AND "challanStatus" = \'1\' AND (deleted = 0) AND (status = \'1\'::status) ');
    $tot=0;
	if(isset($data['org_total']) && !empty($data['org_total'])){
        $tot=$data['org_total'];
    }
	return $tot;
}


function getTotalPaidbychallan($challanno){
	$data = sqlgetresult('SELECT SUM(paidamt) AS paid_total FROM tbl_partialpaid_challan WHERE "challanNo" = \''.$challanno.'\' AND deleted=0');
	return $data;
}

function getPaidbyAdvancechallan($challanno){

	$data = sqlgetresult('SELECT DISTINCT pc.plogid,al.amount FROM tbl_partialpaid_challan pc JOIN tbl_advance_payment_log al ON(pc.plogid=al."transId"::INT) WHERE pc."challanNo" = \''.$challanno.'\' AND al."transStatus"=\'Ok\' AND al.type=\'2\' AND pc.deleted=0',true);
    $amount=0;
	foreach ($data as $k =>$value) {
		$amount+=$value['amount'];
	}
	return $amount;
	
}


function combinePartialRefnumber($challanno){
	$out="";
	$data = sqlgetresult('SELECT plogid,"challanNo","refchallanNo",paidamt FROM tbl_partialpaid_challan WHERE "challanNo" = \''.$challanno.'\' AND deleted=0',true);
    $refnumber=[];
	foreach ($data as $k =>$value) {
		$refnumber[]=$value['refchallanNo'];
	}
	if(count($refnumber) > 0){
	 $out=implode(",", $refnumber);
	}
	return $out;
}

/*function getTotalPaidbychallan($challanno){

	$data = sqlgetresult('SELECT SUM("amount") AS paid_total FROM tbl_payments WHERE "challanNo" = \''.$challanno.'\' AND "transStatus" = \'Ok\'');
	return $data;
}*/

function partialEwalletPayProcess($challanids, $sid, $parent_id, $student_id, $term, $acad_year, $amount, $payment_id, $createdOn, $balance){
	//$date = date('Y-m-d h:i:s');
	$challanids=explode(",",$challanids);
	$empty='';
	//$cur_pabal=toGetPartialAmount($sid);
	if($amount==0){
	  $receivedAmt=$balance;	
	}else{
	   $receivedAmt=$amount;
	}
	
	foreach ($challanids as $chalnum) {
		$waivedAmt=0;
		$chalAmt=0;
		$advpaid=0;
		$chdata=getTotalbychallan($chalnum);

		//$advpaid=getPaidbyAdvancechallan($chalnum);

		if(isset($chdata['org_total']) && !empty($chdata['org_total'])){
		    $chalAmt=$chdata['org_total'];
		}
		$wdata=getwaiveramountbychallan($chalnum);
		if(isset($wdata['waiver_total']) && !empty($wdata['waiver_total'])){
		    $waivedAmt=$wdata['waiver_total'];
		}
		$needpay=$chalAmt-$waivedAmt;

		$alreadyPaidAmt=getAmtPaidbychallan($chalnum);

		//$receivedAmt=$amount+$balance;
		

		$remaining = $needpay-$alreadyPaidAmt;

		if($receivedAmt >  $remaining){
          //$clAmt=$receivedAmt-$remaining;
		  $clAmt=$remaining;
          $sts=1;
          $receivedAmt=$receivedAmt-$remaining;
          paidpartialchallansnew($chalnum, 0, $payment_id, $parent_id, $clAmt);
			updateChallanStatusNew($chalnum, $createdOn, $parent_id, $student_id, $term, $acad_year, $sts, $payment_id, $needpay);
		}else if($receivedAmt==$remaining){
          $clAmt=$receivedAmt;
          $sts=1;
          $receivedAmt=$receivedAmt-$remaining;
			paidpartialchallansnew($chalnum, 0, $payment_id, $parent_id, $clAmt);
			updateChallanStatusNew($chalnum, $createdOn, $parent_id, $student_id, $term, $acad_year, $sts, $payment_id, $needpay);
			break;
		}else{
		  //$clAmt=$remaining-$receivedAmt;
			$clAmt=$receivedAmt;
		  $sts=2;
		  $receivedAmt=0;

		  paidpartialchallansnew($chalnum, 0, $payment_id, $parent_id, $clAmt);
		updateChallanStatusNew($chalnum, $createdOn, $parent_id, $student_id, $term, $acad_year, $sts, $payment_id, $needpay);
		break;
		}

		
	}
}


function partialPayProcess($challanids, $sid, $parent_id, $student_id, $term, $acad_year, $amount, $payment_id, $createdOn, $balance){
	//$date = date('Y-m-d h:i:s');
	$challanids=explode(",",$challanids);
	$empty='';
	//$cur_pabal=toGetPartialAmount($sid);
	$receivedAmt=$amount;
	foreach ($challanids as $chalnum) {
		$waivedAmt=0;
		$chalAmt=0;
		$advpaid=0;
		$chdata=getTotalbychallan($chalnum);

		//$advpaid=getPaidbyAdvancechallan($chalnum);

		if(isset($chdata['org_total']) && !empty($chdata['org_total'])){
		    $chalAmt=$chdata['org_total'];
		}
		$wdata=getwaiveramountbychallan($chalnum);
		if(isset($wdata['waiver_total']) && !empty($wdata['waiver_total'])){
		    $waivedAmt=$wdata['waiver_total'];
		}
		$needpay=$chalAmt-$waivedAmt;

		$alreadyPaidAmt=getAmtPaidbychallan($chalnum);

		//$receivedAmt=$amount+$balance;
		

		$remaining = $needpay-$alreadyPaidAmt;

		if($receivedAmt >  $remaining){
          //$clAmt=$receivedAmt-$remaining;
		  $clAmt=$remaining;
          $sts=1;
          $receivedAmt=$receivedAmt-$remaining;
          paidpartialchallansnew($chalnum, $clAmt, $payment_id, $parent_id, $balance);
			updateChallanStatusNew($chalnum, $createdOn, $parent_id, $student_id, $term, $acad_year, $sts, $payment_id, $needpay);
			$balance=0;
		}else if($receivedAmt==$remaining){
          $clAmt=$receivedAmt;
          $sts=1;
          $receivedAmt=$receivedAmt-$remaining;
			paidpartialchallansnew($chalnum, $clAmt, $payment_id, $parent_id, $balance);
			updateChallanStatusNew($chalnum, $createdOn, $parent_id, $student_id, $term, $acad_year, $sts, $payment_id, $needpay);
			$balance=0;
			break;
		}else{
		  //$clAmt=$remaining-$receivedAmt;
			$clAmt=$receivedAmt;
		  $sts=2;
		  $receivedAmt=0;

		  paidpartialchallansnew($chalnum, $clAmt, $payment_id, $parent_id, $balance);
		updateChallanStatusNew($chalnum, $createdOn, $parent_id, $student_id, $term, $acad_year, $sts, $payment_id, $needpay);
		$balance=0;
		break;
		}

		
	}
}

function updateChallanStatusNew($challanNo, $date, $parent_id, $student_id, $term, $acad_year, $status,$cuschal, $amount){
	$challanNo=trim($challanNo);

	$updateChallan = sqlgetresult('UPDATE tbl_challans SET "challanStatus" = \''.$status.'\', "updatedBy" = \''.$parent_id.'\', "updatedOn" = \''.$date.'\' WHERE "challanNo" = \''.$challanNo.'\' AND deleted=0');

	toUpdatePaidDateOnAppl($student_id,$date);

	$demandtblupd =sqlgetresult('UPDATE tbl_demand SET "challanStatus" = \''.$status.'\', "updatedOn" = CURRENT_TIMESTAMP, "updatedBy" = \''. $parent_id .'\' WHERE "challanNo" =\'' .$challanNo . '\' AND deleted=0');

	$waivertblupd = sqlgetresult('UPDATE tbl_waiver SET "challanStatus" = \''.$status.'\', "modified_on" = CURRENT_TIMESTAMP, "modified_by" = \''. $parent_id.'\' WHERE "challanNo" =\'' .$challanNo . '\' AND deleted=0');
	if($status==1){

		//$transNum="REF".$cuschal;
		//$payment_remarks=$transNum."/Online";
		$transStatus="Ok";
		$createdby = $_SESSION['uid'];
		$createdOn=$date;
		$returnCode=combinePartialRefnumber($challanNo);
		$numberattempt=0;
		if(!empty($returnCode)){
			$paratemp=explode(",", $returnCode);
			$numberattempt=count($paratemp);
		}

		if($numberattempt > 1){
			$transNum="partialpaid";
			//$retn=$returnCode;
			$payment_remarks=$transNum."/Online";
		}else{
			$transNum="REF".$cuschal;
			//$retn=$returnCode;
			$payment_remarks=$transNum."/Online";
		}
		

		$payment = sqlgetresult('INSERT INTO tbl_payments ("parentId", "studentId", "challanNo","amount","transStatus", "transNum", "returnCode", "remarks", "transDate", "createdby", "createdOn") VALUES (\''.$parent_id.'\',\''.$student_id.'\',\''.$challanNo.'\',\''.$amount.'\',\''.$transStatus.'\',\''.$transNum.'\',\''.$returnCode.'\',\''.$payment_remarks.'\',\''.$createdOn.'\',\''.$createdby.'\',\''.$createdOn.'\') RETURNING id');
		
		createPDF($student_id,$challanNo);
		$receiptupd = updatereceipt(trim($challanNo), trim($student_id));  

		$fromwhere = 'Receipt';
		flattableentry(trim($challanNo), trim($student_id), $fromwhere);
	}
    return 1;
}

/* Lunch PDF*/
function createLFPDF($id, $student_id) {

	
	$data = sqlgetresult('SELECT *  FROM otherfeesreport WHERE pyid = \''.$id.'\' AND typeid=\'2\' ');
	$className = trim($data['class_list']); 
	$challanno=trim($data['challanNo']);
	$eventid = explode('-', $data['challanNo']);
	//$displayName = getEventNamebyid($eventid[1]);

	$displayName = trim($data['feetypename']);
	$streamName = trim($data['streamname']);
	
    //$datefolder = date("dmY", strtotime($data['transDate']));
    $datefolder = "lunch";
   
    if (!is_dir(BASEPATH."receipts/".$datefolder)) {
		mkdir(BASEPATH."receipts/".$datefolder);
	}	
	$documentPath = BASEPATH."receipts/".$datefolder."/";

    $Semester = $data['term'];
    $name = trim($data['studentName']);
    $studentId = trim($data['studentId']);
    $challanData1['org_total'] = $data['amount'];
    $challanData1['section'] = trim($data['section']);    
    $challanData1['academicYear'] = trim($data['academic_yr']);
    $refnum=trim($data['transNum']); 
    //$challanData1['transNum'] = trim($data['transNum']); 


    $contract =' <style>.noBorder td{ border:none} table{border-collapse: collapse} .datatbl tr td{border:1px solid #9a9a9a;}</style>
    <table width="750" border="0" >
        <tr> <td  align="center" valign="top"><img src="images/logo_pdf.jpg"  height="80" /> </td> 
        <td align="right">LUNCH-FEE</td>
        </tr>               
      </table>';
    $contract.='<table width="750" border="1" cellspacing="5" cellpadding="15" class="datatbl">
            <tr>
            <td align="left" height="25" width="250"><strong>Academic Year</strong>: '.$challanData1['academicYear'].' </td>
            <td colspan="2" align="left" height="25" width="200"><strong>Ref NO</strong>: '.$refnum.' </td>
            <td align="left" height="25" width="200"><strong>Date</strong>: '.date("d-m-Y").' </td>
        </tr>               
        <tr>
            <td align="left" colspan="3" height="25"><strong>Name</strong>: '.$name.' </td>
            <td align="left" ><strong>Semester</strong>: '.$Semester.' </td>
        </tr>
        <tr>
            <td align="left" colspan="3" height="25"><strong>Student ID</strong>: '.$studentId.' </td>
            <td align="left" ><strong>Class</strong>: '.$className.'-'. $challanData1['section'] .' </td>
        </tr>               
        <tr>
            <td align="center" height="25"><strong>S.No</strong> </td>
            <td align="center" colspan="2" height="25"><strong>Particluars</strong> </td>
            <td align="center" height="25" ><strong>Amount</strong> </td>
        </tr>';

    $contracttt = '';
    $contracttt .= '<tr><td colspan="" align="center" height="25">'. 1 .'</td>';
        $contracttt .= '<td colspan="2" align="center" height="25">'.$displayName.'</td>';
        $contracttt .= '<td colspan="" align="right" height="25">'. $challanData1['org_total'] .'</td>';                       
        $contracttt .= '</tr>';
    $contract .= $contracttt;
    $contract.='<tr>
        <td colspan="3" align="right" height="25"><strong>Total</strong></td>
        <td align="right"> '. $challanData1['org_total'].' </td>
    </tr>';
    $contract.='<tr>
        <td colspan="4" align="left" height="25"><strong>Rupees in words : </strong>'.getCurrencyInWords($challanData1['org_total']).' </td>
    </tr>';    
    
    $contract.='</table><p>*This is a computer generated receipt and does not require authorization.</p>';   
    $data = $contract;


	require_once 'vendor/autoload.php';

	$mpdf = new \Mpdf\Mpdf();
	$mpdf->SetWatermarkText('PAID', 0.08);
	$mpdf->showWatermarkText = true; 

	//$challanno = $studentId.'EVENT-'.trim($eventid[1]);
	$challanno = $challanno;

	$challanID = str_replace('/', '', $refnum);
	$mpdf->WriteHTML($data);
	$filename=$documentPath.$challanID.".pdf";
	$mpdf->Output($filename,'F');
}

/* Uniform PDF*/

/* Uniform PDF*/
function createUFPDF($id, $student_id) {

	
	//$data = sqlgetresult('SELECT p."amount",p."challanNo",p."createdOn",s."class",s."stream",s."term",s."studentName",s."studentId",s."section",s."academic_yr"  FROM tbl_payments p LEFT JOIN tbl_student s ON s."studentId" = p."studentId" WHERE p.id = \''.$id.'\' AND "challanNo" ILIKE \'%UNIFORM%\' ');
	$data = sqlgetresult('SELECT *  FROM otherfeesreport WHERE pyid = \''.$id.'\' AND typeid=\'1\' ');
	$className = $data['class_list'];
	 
	$challanno=trim($data['challanNo']);
	$eventid = explode('-', $data['challanNo']);
	//$displayName = getEventNamebyid($eventid[1]);

	$displayName = trim($data['feetypename']);
	$streamName = trim($data['streamname']);
    //$datefolder = date("dmY", strtotime($data['transDate']));

    $datefolder = "uniform";

    if (!is_dir(BASEPATH."receipts/".$datefolder)) {
		mkdir(BASEPATH."receipts/".$datefolder);
	}	
	$documentPath = BASEPATH."receipts/".$datefolder."/";

    $Semester = $data['term'];
    $name = trim($data['studentName']);
    $studentId = trim($data['studentId']);
    $challanData1['org_total'] = $data['amount'];
    $challanData1['section'] = trim($data['section']);    
    $challanData1['academicYear'] = trim($data['academic_yr']); 
    $refnum=trim($data['transNum']);
    //$challanData1['transNum'] = $refnum;
    $challanData1['quantity'] = trim($data['quantity']);    


    $contract =' <style>.noBorder td{ border:none} table{border-collapse: collapse} .datatbl tr td{border:1px solid #9a9a9a;}</style>
    <table width="750" border="0" >
        <tr> <td  align="center" valign="top"><img src="images/logo_pdf.jpg"  height="80" /> </td> 
        <td align="right">UNIFORM-FEE</td>
        </tr>               
      </table>';
    $contract.='<table width="750" border="1" cellspacing="5" cellpadding="15" class="datatbl">
            <tr>
            <td align="left" height="25" width="250"><strong>Academic Year</strong>: '.$challanData1['academicYear'].' </td>
            <td colspan="2" align="left" height="25" width="200"><strong>Ref NO</strong>: '.$refnum.' </td>
            <td align="left" height="25" width="200"><strong>Date</strong>: '.date("d-m-Y").' </td>
        </tr>               
        <tr>
            <td align="left" colspan="3" height="25"><strong>Name</strong>: '.$name.' </td>
            <td align="left" ><strong>Semester</strong>: '.$Semester.' </td>
        </tr>
        <tr>
            <td align="left" colspan="3" height="25"><strong>Student ID</strong>: '.$studentId.' </td>
            <td align="left" ><strong>Class</strong>: '.$className.'-'. $challanData1['section'] .' </td>
        </tr>               
        <tr>
            <td align="center" height="25"><strong>S.No</strong> </td>
            <td align="center" colspan="2" height="25"><strong>Particluars</strong> </td>
            <td align="center" height="25" ><strong>Amount</strong> </td>
        </tr>';

    $contracttt = '';
    $contracttt .= '<tr><td colspan="" align="center" height="25">'. 1 .'</td>';
        $contracttt .= '<td colspan="2" align="center" height="25">'.$displayName.' - '.$challanData1['quantity'].'</td>';
        $contracttt .= '<td colspan="" align="right" height="25">'. $challanData1['org_total'] .'</td>';                       
        $contracttt .= '</tr>';
    $contract .= $contracttt;
    $contract.='<tr>
        <td colspan="3" align="right" height="25"><strong>Total</strong></td>
        <td align="right"> '. $challanData1['org_total'].' </td>
    </tr>';
    $contract.='<tr>
        <td colspan="4" align="left" height="25"><strong>Rupees in words : </strong>'.getCurrencyInWords($challanData1['org_total']).' </td>
    </tr>';    
    
    $contract.='</table><p>*This is a computer generated receipt and does not require authorization.</p>';   
    $data = $contract;


	require_once 'vendor/autoload.php';

	$mpdf = new \Mpdf\Mpdf();
	$mpdf->SetWatermarkText('PAID', 0.08);
	$mpdf->showWatermarkText = true; 

	//$challanno = $studentId.'EVENT-'.trim($eventid[1]);
	//$challanno = $data['challanNo'];

	$challanID = str_replace('/', '', $refnum);
	$mpdf->WriteHTML($data);
	$filename=$documentPath.$challanID.".pdf";
	$mpdf->Output($filename,'F');
}

function getChallanAmtbyFeeGroup($challanno, $feegroup){

	$data = sqlgetresult('SELECT org_total FROM tbl_challans WHERE "challanNo" = \''.$challanno.'\' AND "feeGroup" = \''.$feegroup.'\' AND (deleted = 0)',true);
    $amount=0;
	foreach ($data as $k =>$value) {
		$amount+=$value['org_total'];
	}
	return $amount;
	
}

function getWaiverAmtbyFeeGroup($challanno, $feegroup){

	$data = sqlgetresult('SELECT "waiver_total" FROM tbl_waiver WHERE "challanNo" = \''.$challanno.'\' AND "feeGroup" = \''.$feegroup.'\' AND deleted = 0',true);
    $amount=0;
	foreach ($data as $k =>$value) {
		$amount+=$value['waiver_total'];
	}
	return $amount;
	
}

function partialEwalletPayProcessOffline($challanids, $sid, $parent_id, $student_id, $term, $acad_year, $amount, $payment_id, $createdOn, $balance, $ptypechk, $cbank, $auth_code, $transactiondate, $remarks){
	//$date = date('Y-m-d h:i:s');
	$challanids=explode(",",$challanids);
	$empty='';
	//$cur_pabal=toGetPartialAmount($sid);
	if($amount==0){
	  $receivedAmt=$balance;	
	}else{
	   $receivedAmt=$amount;
	}
	
	foreach ($challanids as $chalnum) {
		$waivedAmt=0;
		$chalAmt=0;
		$advpaid=0;
		$chdata=getTotalbychallan($chalnum);

		//$advpaid=getPaidbyAdvancechallan($chalnum);

		if(isset($chdata['org_total']) && !empty($chdata['org_total'])){
		    $chalAmt=$chdata['org_total'];
		}
		$wdata=getwaiveramountbychallan($chalnum);
		if(isset($wdata['waiver_total']) && !empty($wdata['waiver_total'])){
		    $waivedAmt=$wdata['waiver_total'];
		}
		$needpay=$chalAmt-$waivedAmt;

		$alreadyPaidAmt=getAmtPaidbychallan($chalnum);

		//$receivedAmt=$amount+$balance;
		

		$remaining = $needpay-$alreadyPaidAmt;

		if($receivedAmt >  $remaining){
          //$clAmt=$receivedAmt-$remaining;
		  $clAmt=$remaining;
          $sts=1;
          $receivedAmt=$receivedAmt-$remaining;
          paidpartialchallansnew($chalnum, 0, $payment_id, $parent_id, $clAmt);
		  updateChallanStatusOffline($chalnum, $createdOn, $parent_id, $student_id, $term, $acad_year, $sts, $payment_id, $needpay, $ptypechk, $cbank, $auth_code, $transactiondate, $remarks);
		}else if($receivedAmt==$remaining){
          $clAmt=$receivedAmt;
          $sts=1;
          $receivedAmt=$receivedAmt-$remaining;
			paidpartialchallansnew($chalnum, 0, $payment_id, $parent_id, $clAmt);
			updateChallanStatusOffline($chalnum, $createdOn, $parent_id, $student_id, $term, $acad_year, $sts, $payment_id, $needpay, $ptypechk, $cbank, $auth_code, $transactiondate, $remarks);
			break;
		}else{
		  //$clAmt=$remaining-$receivedAmt;
			$clAmt=$receivedAmt;
		  $sts=2;
		  $receivedAmt=0;

		  paidpartialchallansnew($chalnum, 0, $payment_id, $parent_id, $clAmt);
		  updateChallanStatusOffline($chalnum, $createdOn, $parent_id, $student_id, $term, $acad_year, $sts, $payment_id, $needpay, $ptypechk, $cbank, $auth_code, $transactiondate, $remarks);
		  break;
		}

		
	}
}

function updateChallanStatusOffline($challanNo, $date, $parent_id, $student_id, $term, $acad_year, $status,$cuschal, $amount, $ptype, $bank, $checknumber, $paiddate, $remarks){
	$challanNo=trim($challanNo);

	$entry = sqlgetresult("SELECT *  FROM new_cheque_fee_entry_by_challan('" .$challanNo. "','" .$ptype. "','" . $bank . "','" . $checknumber . "','" . $paiddate . "','" . $parent_id . "','" . $remarks . "','" . $date . "','" . $status . "') ");
	if(count($entry) > 0){
	    toUpdatePaidDateOnAppl($student_id,$paiddate);
	}
	if($status==1){
		//$transNum="REF".$cuschal;
		//$payment_remarks=$transNum."/Online";
		$transStatus="Ok";
		$createdby = $parent_id;
		$createdOn=$date;
		$returnCode=combinePartialRefnumber($challanNo);
		$numberattempt=0;
		if(!empty($returnCode)){
			$paratemp=explode(",", $returnCode);
			$numberattempt=count($paratemp);
		}

		if($numberattempt > 1){
			$transNum="partialpaid";
			//$retn=$returnCode;
			$payment_remarks=$transNum."/".$ptype."/".$bank."/".$checknumber;
		}else{
			$transNum="REF".$cuschal;
			//$retn=$returnCode;
			$payment_remarks=$transNum."/".$ptype."/".$bank."/".$checknumber;
		}

		$returnCode=$returnCode."-".$payment_remarks;
        $payment_remarks=substr($payment_remarks,0,30);
		

		$payment = sqlgetresult('INSERT INTO tbl_payments ("parentId", "studentId", "challanNo","amount","transStatus", "transNum", "returnCode", "remarks", "transDate", "createdby", "createdOn") VALUES (\''.$parent_id.'\',\''.$student_id.'\',\''.$challanNo.'\',\''.$amount.'\',\''.$transStatus.'\',\''.$transNum.'\',\''.$returnCode.'\',\''.$payment_remarks.'\',\''.$createdOn.'\',\''.$createdby.'\',\''.$createdOn.'\') RETURNING id');
		
		createPDFAdmin($student_id,$challanNo);
		$receiptupd = updatereceipt(trim($challanNo), trim($student_id));  

		$fromwhere = 'Receipt';
		flattableentry(trim($challanNo), trim($student_id), $fromwhere);
	}
    return 1;
}

function partialPayProcessOffline($challanids, $sid, $parent_id, $student_id, $term, $acad_year, $amount, $payment_id, $createdOn, $balance, $ptypechk, $cbank, $auth_code, $transactiondate, $remarks){
	//$date = date('Y-m-d h:i:s');
	$challanids=explode(",",$challanids);
	$empty='';
	//$cur_pabal=toGetPartialAmount($sid);
	$receivedAmt=$amount;
	$bal=0;
	foreach ($challanids as $chalnum) {
		$waivedAmt=0;
		$chalAmt=0;
		$advpaid=0;
		$chdata=getTotalbychallan($chalnum);

		//$advpaid=getPaidbyAdvancechallan($chalnum);

		if(isset($chdata['org_total']) && !empty($chdata['org_total'])){
		    $chalAmt=$chdata['org_total'];
		}
		$wdata=getwaiveramountbychallan($chalnum);
		if(isset($wdata['waiver_total']) && !empty($wdata['waiver_total'])){
		    $waivedAmt=$wdata['waiver_total'];
		}
		$needpay=$chalAmt-$waivedAmt;

		$alreadyPaidAmt=getAmtPaidbychallan($chalnum);

		//$receivedAmt=$amount+$balance;
		

		$remaining = $needpay-$alreadyPaidAmt;

		if($receivedAmt >  $remaining){
          //$clAmt=$receivedAmt-$remaining;
		  $clAmt=$remaining;
          $sts=1;
          $receivedAmt=$receivedAmt-$remaining;
          paidpartialchallansnew($chalnum, $clAmt, $payment_id, $parent_id, $bal);
		  updateChallanStatusOffline($chalnum, $createdOn, $parent_id, $student_id, $term, $acad_year, $sts, $payment_id, $needpay, $ptypechk, $cbank, $auth_code, $transactiondate, $remarks);
			$balance=0;
		}else if($receivedAmt==$remaining){
          $clAmt=$receivedAmt;
          $sts=1;
          $receivedAmt=$receivedAmt-$remaining;
			paidpartialchallansnew($chalnum, $clAmt, $payment_id, $parent_id, $bal);
			updateChallanStatusOffline($chalnum, $createdOn, $parent_id, $student_id, $term, $acad_year, $sts, $payment_id, $needpay, $ptypechk, $cbank, $auth_code, $transactiondate, $remarks);
			$balance=0;
			break;
		}else{
		  //$clAmt=$remaining-$receivedAmt;
			$clAmt=$receivedAmt;
		  $sts=2;
		  $receivedAmt=0;

		  paidpartialchallansnew($chalnum, $clAmt, $payment_id, $parent_id, $bal);
		  updateChallanStatusOffline($chalnum, $createdOn, $parent_id, $student_id, $term, $acad_year, $sts, $payment_id, $needpay, $ptypechk, $cbank, $auth_code, $transactiondate, $remarks);
		  $balance=0;
		  break;
		}

		
	}
}


function revokePartialPaymentChecque($cno){
	$uid = $_SESSION['myadmin']['adminid'];
	$updatedOn = date("Y-m-d h:m:s");
	$data = sqlgetresult('SELECT pl.id AS plid,pc.id AS pcid FROM  tbl_partial_payment_log pl JOIN tbl_partialpaid_challan pc ON(pl.id=pc.plogid) WHERE pl.paymentmode=\'Cheque\' AND pc."challanNo" = \''.$cno.'\' AND pc.deleted=0',true);
	if(count($data) > 0){
	  

	  sqlgetresult('UPDATE tbl_payments SET "transStatus"=\'Revoked\', deleted = 1, "updatedBy" = \''.$uid.'\', "updatedOn" = \''.$updatedOn.'\' WHERE "challanNo" = \''.$cno.'\' ');
      foreach ($data as $k =>$value) {
      	$pcid=$value['pcid'];
      	$logid=$value['plid'];
      	$ref="REF".$logid;

      	$updateChallan = sqlgetresult('UPDATE tbl_partialpaid_challan SET deleted = 1, "updatedBy" = \''.$uid.'\', "updatedOn" = \''.$updatedOn.'\' WHERE id= \''.$pcid.'\' AND  "challanNo" = \''.$cno.'\'');

      	sqlgetresult('UPDATE tbl_partial_payment_log SET deleted = 1,"transStatus"=\'Revoked\', "updatedBy" = \''.$uid.'\', "updatedOn" = \''.$updatedOn.'\' WHERE id = \''.$logid.'\' AND paymentmode=\'Cheque\' ');

	  }
	}
}

function createPDFAdmin($studId,$chlno,$type='') {

    $getparentmailid = sqlgetresult('SELECT "email" AS parentMailId FROM tbl_student WHERE "studentId"=\''.$studId . '\'');
    $challanData = sqlgetresult('SELECT * FROM challandata WHERE "challanNo" = \'' . $chlno . '\' AND "challanStatus" = 1 ',true);
    // print_r($challanData);
    $feeTypes = sqlgetresult("SELECT * FROM getFeeTypes");    
    $mailid = $getparentmailid['parentmailid'];
    $to = $mailid;

    $order = array('SCHOOL FEE', 'SCHOOL UTILITY FEE', 'SFS UTILITIES FEE', 'REFUNDABLE DEPOSIT' , 'LATE FEE');

    if($type != '') {
        $datefolder = date("dmY", strtotime($type));
    } else {
        $datefolder = date('dmY');
    }

    if (!is_dir(BASEPATH."receipts/".$datefolder)) {
        mkdir(BASEPATH."receipts/".$datefolder);
    }   
    $documentPath = BASEPATH."receipts/".$datefolder."/";

    $total = 0;
    $feeData = array();  
    $chlncnt = count($challanData);  
    $groupdata = array();

    foreach ($challanData as $k =>$value) {
        $challanno = $value['challanNo'];
        $Semester = $value['term'];
        $challanData1['clid'] = $value['clid'];
        $name = $value['studentName'];
        $studentId = $value['studentId'];
        $className = $value['class_list'];
        $challanData1['duedate'] = $value['duedate'];
        $challanData1['stream'] = $value['stream'];
        $streamName = $value['steamname'];
        $challanData1['org_total'] = $value['org_total'];
        $challanData1['section'] = $value['section'];
        $cheque = $value['cheque_dd_no'];
        $pay_type = $value['pay_type'];
        $pdate = $value['paid_date'];
        $challanData1['academicYear'] = $value['academicYear'];
        $feegroup = $value['feeGroup'];
        if($value['remarks'] != ''){
        $remarks = $value['remarks'];
        }
        else{
        $remarks = 'Nil';

        }
        $feetypearray[getFeeGroupbyId($value['feeGroup'])][$value['feeType']][] = $value['org_total'];
        $feetypearray[getFeeGroupbyId($value['feeGroup'])][$value['feeType']][] = getFeeTypebyId($value['feeType']);

        $chequebankarray[getFeeGroupbyId($value['feeGroup'])]= $value['bank'];

        $chequenoarray[getFeeGroupbyId($value['feeGroup'])]= $value['cheque_dd_no'];

        $feetypearray[getFeeGroupbyId($value['feeGroup'])]['waived'] = getwaiveddata($value['challanNo'], $value['feeGroup']);

        $cnt = $k+1;
        if($cnt == $chlncnt) {
            $groupdata = $feetypearray;

        }
        uksort($groupdata, function ($key1, $key2) use($order)
	    {
	        return (array_search(trim($key1) , $order) > array_search(trim($key2) , $order));
	    });

        // if($feegroup != "LATE FEE" && $value['feeType'] != ""){
        foreach ($groupdata as $key => $feegroup) {
            if(trim($Semester) == 'I'){
                $provisional = "(Provisional)";
            }
            else{
                $provisional = "";
            }
            
            $contract[$key] =' <style> .noBorder td{ border:none} table{border-collapse: collapse} </style>
            <table width="750" border="0" >
                <tr> <td  align="center" valign="top"><img src="images/logo_pdf.jpg"  height="80" /> </td> 
                <td align="right">'.$key.'</td>
                </tr>               
              </table>';
            $contract[$key].='<table width="750" border="1" cellspacing="5" cellpadding="15">
                    <tr>
                    <td colspan="2" align="left" height="25" width="300"><strong>Challan No.</strong>: '.$challanno.' </td>
                    <td colspan="" align="left" height="25" width="250"><strong>Academic Year</strong>: '.getAcademicyrById($challanData1['academicYear']).' </td>
                    <td colspan="" align="left" height="25" width="200"><strong>Date</strong>: '.date("d-m-Y").' </td>
                </tr>               
                <tr>
                    <td align="left" colspan="3" height="25"><strong>Name</strong>: '.$name.' </td>
                    <td align="left" ><strong>Semester</strong>: '.$Semester.' </td>
                </tr>
                <tr>
                    <td align="left" colspan="2" height="25"><strong>Student ID</strong>: '.$studentId.' </td>
                    <td align="left" ><strong>Class</strong>: '.$className.'-'. $challanData1['section'] . $provisional .' </td>
                    <td align="left" ><strong>Due Date</strong>: '.date("d-m-Y", strtotime($challanData1['duedate'])).' </td>
                </tr>               
                <tr>
                    <td align="center" height="25"><strong>S.No</strong> </td>
                    <td align="center" colspan="2" height="25"><strong>Particluars</strong> </td>
                    <td align="center" height="25" ><strong>Amount</strong> </td>
                </tr>';

                $tot = 0;
                $i = 1;
                $contractt = '';
                $wtot = 0;
                if(trim($key) == '10') {
                    $findSFS = sqlgetresult('SELECT * FROM tbl_sfs_qty WHERE "challanNo" = \''.$challanno.'\' ', true);
                    foreach ($findSFS as $v) {
                        $contractt .= '<tr><td colspan="" align="center" height="25">'.$i.'</td>';
                        $contractt .= '<td colspan="2" align="center" height="25">'.getFeeTypebyId($v['feeTypes']).'('.$v['quantity'].')'.'</td>';
                        $contractt .= '<td colspan="" align="right" height="25">'.$v['amount'].'</td>';                    
                        $contractt .= '</tr>';
                        $tot += $v['amount'];
                        $i++;
                    }
                } else {
                    $last_key = end(array_keys($feegroup));
                    $waiveddata = array();

                    foreach ($feegroup as $k => $val) {                       
                        if(trim($k) != 'waived' && $val != 0) {
                            if(trim($val[0]) != 0) {
                                $contractt .= '<tr><td colspan="" align="center" height="25">'.$i.'</td>';
                                $contractt .= '<td colspan="2" align="center" height="25">'.$val[1].'</td>';
                                $contractt .= '<td colspan="" align="right" height="25">'.$val[0].'</td>';
                                $tot += $val[0];
                            }
                            $i++;
                        } 

                        $contractt .= '</tr>';
                                                                               
                        if(trim($k) == 'waived' && $val != 0) {
                            $waiveddata[] =  $val[0]['waiver_type'];
                            $waiveddata[] =  $val[0]['waiver_total']; 
                            $wtot = $val[0]['waiver_total'];
                        }
                        if( $k == $last_key && sizeof($waiveddata) > 0)  {

                                $contractt .= '<tr><td colspan="3" align="right" height="25">Waiver - '.$waiveddata[0].'</td>';
                                $contractt .= '<td colspan="" align="right" height="25">'.$waiveddata[1].'</td>';
                                $contractt .= '</tr>';
                            // }

                        }  
                                       
                    }

                    
                    $amount = $tot - $wtot;                     
                }

                $contract[$key] .= $contractt;
                $contract[$key].='<tr>
                    <td colspan="3" align="right" height="25"><strong>Total</strong></td>
                    <td align="right"> '.$amount.' </td>
                </tr>';
                $contract[$key].='<tr>
                    <td colspan="4" align="left" height="25"><strong>Rupees in words : </strong>'.getCurrencyInWords($amount).' </td>
                </tr>
                <tr>
                    <td align="left" colspan="4" height="25"><strong>Remarks</strong>: '.$remarks.' </td>
                </tr>';
                if( $pay_type == '' ) {
                    $pay_type = 'Online';
                }
                $contract[$key].='<tr>
                    <td align="left" height="25"><strong>Mode of Payment </strong></td>
                    <td colspan="3" align="left" height="25">'.$pay_type.'</td>
                </tr>';

                if( $pay_type != 'Online') {
                    $bank = $value['bank'];
                    $contract[$key].='<tr>
                        <td  align="left" height="25"><strong>Cheque / DD No.</strong></td>
                        <td colspan="" align="left" height="25">'.$chequenoarray[$key].'</td>
                        <td  align="left" height="25"><strong>Date</strong></td>
                        <td colspan="" align="left" height="25">'.$pdate.'</td>
                    </tr>';
                    $contract[$key].='<tr>                
                        <td  align="left" height="25"><strong>Bank</strong></td>
                        <td colspan="" align="left" height="25">'.$chequebankarray[$key].'</td>
                        <td  align="left" height="25"><strong>Branch</strong></td>
                        <td colspan="" align="left" height="25"></td>
                    </tr>';
                    $contract[$key].='<tr>
                        <td  align="left" height="25"><strong>Cashier / Manager</strong></td>
                        <td colspan="" align="left" height="25"></td>
                        <td  align="left" height="25"><strong>Signature of Remitter</strong></td>
                        <td colspan="" align="left" height="25"></td>
                    </tr>';
                }
                
                // $contract[$key].='</table>';  
                $contract[$key].='</table><p>*This is a computer generated receipt and does not require authorization.</p><pagebreak>';         
        }
     
        $groupdata = array();
        $feeData = array();
    }
    // print_r($contract);
    $data = implode('<br/>',$contract);

    // print_r($data);exit;

	require_once 'vendor/autoload.php';

	$mpdf = new \Mpdf\Mpdf();
	$mpdf->SetWatermarkText('PAID', 0.08);
	$mpdf->showWatermarkText = true; 

	$challanID = str_replace('/', '', trim($challanno));
	// $pdf->convert($data,$documentPath.$challanID.".pdf",1);

	$mpdf->WriteHTML($data);
	$mpdf->Output($documentPath.$challanID.".pdf",'F');

	$mail_content = 'Please find the attached receipt.';

	$sms_content = 'Dear Parent, You have the paid the amount for the challan '.trim($challanno).'/-. Email with receipt will be sent to your registered Email ID.';

	$receiptpath = $documentPath.$challanID.".pdf";
	$subject = 'Paid Challan Receipt';

	sendNotificationToParents($studId, $mail_content, $sms_content,'1', $receiptpath, $subject);

	/*if($type != '') {
		$_SESSION['success'] = "<p class='success-msg'>Receipt Generated Successfully.</p>";
		header("Location:createreceipt.php");
	} else {
		$_SESSION['success_msg'] = "<p class='success-msg'>Payment Completed Successfully.</p>";
		header("Location:addpartialpayment.php");
	}*/
 }



function createTFPDF($studId,$chlno,$type='') {


    $getparentmailid = sqlgetresult('SELECT "email" AS parentMailId FROM tbl_student WHERE "studentId"=\''.$studId . '\'');
    $challanData = sqlgetresult('SELECT * FROM challandatanew WHERE "challanNo" = \'' . $chlno . '\' AND "challanStatus" = 1   AND "studStatus"=\'Transport.Fee\' ',true);

    if(count($challanData) > 0){
	        // print_r($challanData);
	    $feeTypes = sqlgetresult("SELECT * FROM getFeeTypes");    
	    $mailid = $getparentmailid['parentmailid'];
	    $to = $mailid;

	    /*if($type != '') {
	        $datefolder = date("dmY", strtotime($type));
	    } else {
	        $datefolder = date('dmY');
	    }*/
	    $datefolder="transport";

	    if (!is_dir(BASEPATH."receipts/".$datefolder)) {
	        mkdir(BASEPATH."receipts/".$datefolder);
	    }   
	    $documentPath = BASEPATH."receipts/".$datefolder."/";

	    $total = 0;
	    $feeData = array();  
	    $chlncnt = count($challanData);  
	    $groupdata = array();
	    foreach ($challanData as $k =>$value) {
	        $challanno = $value['challanNo'];
	        $Semester = $value['term'];
	        $challanData1['clid'] = $value['clid'];
	        $name = $value['studentName'];
	        $studentId = $value['studentId'];
	        $className = $value['class_list'];
	        $challanData1['duedate'][] = $value['duedate'];
	        $challanData1['duedateuniquearray'] = array_unique(array_filter($challanData1['duedate']));
	        $challanData1['stream'] = $value['stream'];
	        $streamName = $value['steamname'];
	        $challanData1['org_total'] = $value['org_total'];
	        $challanData1['section'] = $value['section'];
	        $cheque = $value['cheque_dd_no'];
	        $pay_type = $value['pay_type'];
	        $pdate = $value['paid_date'];
	        $challanData1['academicYear'] = $value['academicYear'];
	        $feegroup = $value['feeGroup'];
	        if($value['remarks'] != ''){
	        	$remarks = $value['remarks'];
	        }
	        else{
	        	$remarks = 'Nil';
	        }
	        $feetypearray[getFeeGroupbyId($value['feeGroup'])][$value['feeType']][] = $value['total'];
	        $feetypearray[getFeeGroupbyId($value['feeGroup'])][$value['feeType']][] = getFeeTypebyId($value['feeType']);
	        
	        $chequebankarray[getFeeGroupbyId($value['feeGroup'])]= $value['bank'];

	        $chequenoarray[getFeeGroupbyId($value['feeGroup'])]= $value['cheque_dd_no'];

	        $feetypearray[getFeeGroupbyId($value['feeGroup'])]['waived'] = getwaiveddata($value['challanNo'], $value['feeGroup']);

	        $cnt = $k+1;
	        if($cnt == $chlncnt) {
	            $groupdata = $feetypearray;

	        }
	        if($feegroup != "LATE FEE" && $value['feeType'] != ""){
	        foreach ($groupdata as $key => $feegroup) {
	            
	            
	            $contract[$key] =' <style> .noBorder td{ border:none} table{border-collapse: collapse} </style>
	            <table width="750" border="0" >
	                <tr> <td  align="center" valign="top"><img src="images/logo_pdf.jpg"  height="80" /> </td> 
	                <td align="right">'.$key.'</td>
	                </tr>               
	              </table>';
	            $contract[$key].='<table width="750" border="1" cellspacing="5" cellpadding="15">
	                    <tr>
	                    <td colspan="2" align="left" height="25" width="300"><strong>Challan No.</strong>: '.$challanno.' </td>
	                    <td colspan="" align="left" height="25" width="250"><strong>Academic Year</strong>: '.getAcademicyrById($challanData1['academicYear']).' </td>
	                    <td colspan="" align="left" height="25" width="200"><strong>Date</strong>: '.date("d-m-Y").' </td>
	                </tr>               
	                <tr>
	                    <td align="left" colspan="3" height="25"><strong>Name</strong>: '.$name.' </td>
	                    <td align="left" ><strong>Semester</strong>: '.$Semester.' </td>
	                </tr>
	                <tr>
	                    <td align="left" colspan="2" height="25"><strong>Student ID</strong>: '.$studentId.' </td>
	                    <td align="left" ><strong>Class</strong>: '.$className.'-'. $challanData1['section'] .' </td>
	                    <td align="left" ><strong>Due Date</strong>: '.date("d-m-Y", strtotime(reset($challanData1['duedateuniquearray']))).' </td>
	                </tr>               
	                <tr>
	                    <td align="center" height="25"><strong>S.No</strong> </td>
	                    <td align="center" colspan="2" height="25"><strong>Particluars</strong> </td>
	                    <td align="center" height="25" ><strong>Amount</strong> </td>
	                </tr>';

	                $tot = 0;
	                $i = 1;
	                $contractt = '';
	                $wtot = 0;
	                if(trim($key) == '10') {
	                    $findSFS = sqlgetresult('SELECT * FROM tbl_sfs_qty WHERE "challanNo" = \''.$challanno.'\' ', true);
	                    foreach ($findSFS as $v) {
	                        $contractt .= '<tr><td colspan="" align="center" height="25">'.$i.'</td>';
	                        $contractt .= '<td colspan="2" align="center" height="25">'.getFeeTypebyId($v['feeTypes']).'('.$v['quantity'].')'.'</td>';
	                        $contractt .= '<td colspan="" align="right" height="25">'.$v['amount'].'</td>';                    
	                        $contractt .= '</tr>';
	                        $tot += $v['amount'];
	                        $i++;
	                    }
	                } else {
	                    $last_key = end(array_keys($feegroup));
	                    $waiveddata = array();

	                    foreach ($feegroup as $k => $val) {                       
	                        if(trim($k) != 'waived' && $val != 0) {
	                            if(trim($val[0]) != 0) {
	                                $contractt .= '<tr><td colspan="" align="center" height="25">'.$i.'</td>';
	                                $contractt .= '<td colspan="2" align="center" height="25">'.$val[1].'</td>';
	                                $contractt .= '<td colspan="" align="right" height="25">'.$val[0].'</td>';
	                                $tot += $val[0];
	                            }
	                            $i++;
	                        } 

	                        $contractt .= '</tr>';
	                                                                               
	                        if(trim($k) == 'waived' && $val != 0) {
	        //                 	if(isset($val[0]['oldwaiver']) && $val[0]['oldwaiver'] == 1){
									// $waiveddata[] =  $val[0]['waiver_type'];
		       //                      $waiveddata[] =  $val[0]['waiver_total']; 
		       //                      $wtot = $val[0]['waiver_total'];
	        //                 	}
	        //                 	else{
		                            $waiveddata[] =  $val[0]['waiver_type'];
		                            $waiveddata[] =  $val[0]['waiver_total']; 
		                            $wtot = $val[0]['waiver_total'];
	                        	// }
	                        }
	                        if( $k == $last_key && sizeof($waiveddata) > 0)  {

	                                $contractt .= '<tr><td colspan="3" align="right" height="25">Waiver - '.$waiveddata[0].'</td>';
	                                $contractt .= '<td colspan="" align="right" height="25">'.$waiveddata[1].'</td>';
	                                $contractt .= '</tr>';
	                            // }

	                        }  
	                                       
	                    }

	                    
	                    $amount = $tot - $wtot;                     
	                }

	                $contract[$key] .= $contractt;
	                $contract[$key].='<tr>
	                    <td colspan="3" align="right" height="25"><strong>Total</strong></td>
	                    <td align="right"> '.$amount.' </td>
	                </tr>';
	                $contract[$key].='<tr>
	                    <td colspan="4" align="left" height="25"><strong>Rupees in words : </strong>'.getCurrencyInWords($amount).' </td>
	                </tr>
	                <tr>
	                    <td align="left" colspan="4" height="25"><strong>Remarks</strong>: '.$remarks.' </td>
	                </tr>';
	                if( $pay_type == '' ) {
	                    $pay_type = 'Online';
	                }
	                $contract[$key].='<tr>
	                    <td align="left" height="25"><strong>Mode of Payment </strong></td>
	                    <td colspan="3" align="left" height="25">'.$pay_type.'</td>
	                </tr>';

	                if( $pay_type != 'Online') {
	                    $bank = $value['bank'];
	                    if($pdate){
	                      $pdate=date("d-m-Y", strtotime($pdate));
	                    }
	                    $contract[$key].='<tr>
	                        <td  align="left" height="25"><strong>Cheque / DD No.</strong></td>
	                        <td colspan="" align="left" height="25">'.$chequenoarray[$key].'</td>
	                        <td  align="left" height="25"><strong>Date</strong></td>
	                        <td colspan="" align="left" height="25">'.$pdate.'</td>
	                    </tr>';
	                    $contract[$key].='<tr>                
	                        <td  align="left" height="25"><strong>Bank</strong></td>
	                        <td colspan="" align="left" height="25">'.$chequebankarray[$key].'</td>
	                        <td  align="left" height="25"><strong>Branch</strong></td>
	                        <td colspan="" align="left" height="25"></td>
	                    </tr>';
	                    $contract[$key].='<tr>
	                        <td  align="left" height="25"><strong>Cashier / Manager</strong></td>
	                        <td colspan="" align="left" height="25"></td>
	                        <td  align="left" height="25"><strong>Signature of Remitter</strong></td>
	                        <td colspan="" align="left" height="25"></td>
	                    </tr>';
	                }
	                
	                $contract[$key].='</table>';          
	        }
	    }
	        else{
	            $contract[$key] =' <style> .noBorder td{ border:none} </style>
	            <table width="750" border="0" >
	                <tr> <td  align="center" valign="top"><img src="images/logo_pdf.jpg"  height="80" /> </td> 
	                <td align="right">'.$feegroup.'</td>
	                </tr>               
	              </table>';

	            $contract[$key].='<table width="750" border="1" cellspacing="5" cellpadding="15">
	                    <tr>
	                    <td colspan="2" align="left" height="25"><strong>Challan No.</strong>: '.$challanno.' </td>
	                    <td colspan="" align="left" height="25"><strong>Academic Year</strong>: '.getAcademicyrById($challanData1['academicYear']).' </td>
	                    <td colspan="" align="left" height="25"><strong>Date</strong>: '.date("d-m-Y").' </td>
	                </tr>               
	                <tr>
	                    <td align="left" colspan="3" height="25"><strong>NAME</strong>: '.$name.' </td>
	                    <td align="left" ><strong>SEMESTER</strong>: '.$Semester.' </td>
	                </tr>
	                <tr>
	                    <td align="left" colspan="2" height="25"><strong>Student ID</strong>: '.$studentId.' </td>
	                    <td align="left" ><strong>Class</strong>: '.$className.'-'. $challanData1['section'] .' </td>
	                    <td align="left" ><strong>Due Date</strong>: '.date("d-m-Y", strtotime($challanData1['duedate'])).' </td>
	                </tr>               
	                <tr>
	                    <td align="center" height="25"><strong>S.No</strong> </td>
	                    <td align="center" colspan="2" height="25"><strong>Particluars</strong> </td>
	                    <td align="center" height="25" ><strong>Amount</strong> </td>
	                </tr>';
	                $contracttt = '';
	                $contracttt .= '<tr><td colspan="" align="center" height="25">'. 1 .'</td>';
	                    $contracttt .= '<td colspan="2" align="center" height="25">'.$feegroup.'</td>';
	                    $contracttt .= '<td colspan="" align="right" height="25">'. $challanData1['org_total'] .'</td>';                       
	                    $contracttt .= '</tr>';
	                $contract[$key] .= $contracttt;
	                $contract[$key].='<tr>
	                    <td colspan="3" align="right" height="25"><strong>TOTAL</strong></td>
	                    <td align="right"> '. $challanData1['org_total'].' </td>
	                </tr>';
	                $contract[$key].='<tr>
	                    <td colspan="4" align="left" height="25"><strong>Rupees in words : </strong>'.getCurrencyInWords($challanData1['org_total']).' </td>
	                </tr>
	                <tr>
	                    <td align="left" colspan="4" height="25"><strong>REMARKS</strong>: '.$remarks.' </td>
	                </tr>';
	                if( $pay_type == '' ) {
	                    $pay_type = 'Online';
	                }
	                $contract[$key].='<tr>
	                    <td align="left" height="25"><strong>Mode of Payment </strong></td>
	                    <td colspan="3" align="left" height="25">'.$pay_type.'</td>
	                </tr>';

	                if( $pay_type != 'Online') {
	                    $bank = $value['bank'];
	                    if($pdate){
	                      $pdate=date("d-m-Y", strtotime($pdate));
	                    }
	                    $contract[$key].='<tr>
	                        <td  align="left" height="25"><strong>Cheque / DD No.</strong></td>
	                        <td colspan="" align="left" height="25">'.$chequenoarray[$key].'</td>
	                        <td  align="left" height="25"><strong>Date</strong></td>
	                        <td colspan="" align="left" height="25">'.$pdate.'</td>
	                    </tr>';
	                    $contract[$key].='<tr>                
	                        <td  align="left" height="25"><strong>Bank</strong></td>
	                        <td colspan="" align="left" height="25">'.$chequebankarray[$key].'</td>
	                        <td  align="left" height="25"><strong>Branch</strong></td>
	                        <td colspan="" align="left" height="25"></td>
	                    </tr>';
	                    $contract[$key].='<tr>
	                        <td  align="left" height="25"><strong>Cashier / Manager</strong></td>
	                        <td colspan="" align="left" height="25"></td>
	                        <td  align="left" height="25"><strong>Signature of Remitter</strong></td>
	                        <td colspan="" align="left" height="25"></td>
	                    </tr>';
	                }
	                $contract[$key].='</table>';          

	        }   
	        $groupdata = array();
	        $feeData = array();
	    }
	    // print_r($contract);
	    $data = implode('<br/>',$contract);
	    

	    // print_r($data);exit;


	    require_once 'vendor/autoload.php';

		$mpdf = new \Mpdf\Mpdf();
		$mpdf->SetWatermarkText('PAID', 0.08);
		$mpdf->showWatermarkText = true; 

		//$challanno = $studentId.'EVENT-'.trim($eventid[1]);
		$challanno = $challanno;

		$challanID = str_replace('/', '', trim($challanno));
		$mpdf->WriteHTML($data);
		$filename=$documentPath.$challanID.".pdf";
		$mpdf->Output($filename,'F');
		if($filename){
			$subject = 'Paid Transport Challan Receipt';
			$mail_content = 'Please find the attached receipt for the transport challan '.$chlno.'.';
			$sms_content='';
			$cc=1;
            sendNotificationToParents($studentId, $mail_content, $sms_content,$cc, $filename, $subject);
        }
    }

}


function completeTransportChallan($status, $parent_id, $challanNo, $student_id, $ptype=""){
	date_default_timezone_set("Asia/Kolkata");    
	$date = date('Y-m-d h:i:s');
    $challanNo=trim($challanNo);
    $status=trim($status);

    if(!empty($ptype)){
      $ptype=$ptype;
    }else{
    	$ptype='Online';
    }

     

	$updateChallan = sqlgetresult('UPDATE tbl_challans SET "challanStatus" = \''.$status.'\', "updatedBy" = \''.$parent_id.'\', "updatedOn" = \''.$date.'\', pay_type=\''.$ptype.'\' WHERE "challanNo" = \''.$challanNo.'\' AND deleted=0');

	$demandtblupd =sqlgetresult('UPDATE tbl_demand SET "challanStatus" = \''.$status.'\', "updatedOn" = CURRENT_TIMESTAMP, "updatedBy" = \''. $parent_id .'\' WHERE "challanNo" =\'' . $challanNo . '\' AND deleted=0');

	$waivertblupd = sqlgetresult('UPDATE tbl_waiver SET "challanStatus" = \''.$status.'\', "modified_on" = CURRENT_TIMESTAMP, "modified_by" = \''. $parent_id.'\' WHERE "challanNo" =\'' . $challanNo . '\' AND deleted=0');
	if($status==1){       
		createTFPDF($student_id,$challanNo);
		$receiptupd = updatereceipt($challanNo, $student_id);  

		$fromwhere = 'Receipt';
		flattableentry($challanNo, $student_id, $fromwhere);
    }
    return 1;
}


function partialPayProcessModified($challanids, $sid, $parent_id, $student_id, $term, $acad_year, $amount, $payment_id, $createdOn, $balance){
	//$date = date('Y-m-d h:i:s');
	$challanids=explode(",",$challanids);
	$empty='';
	//$cur_pabal=toGetPartialAmount($sid);
	$bal=0;
	$receivedAmt=$amount;
	foreach ($challanids as $chalnum) {
		$waivedAmt=0;
		$chalAmt=0;
		$advpaid=0;
		$chdata=getTotalbychallan($chalnum);

		//$advpaid=getPaidbyAdvancechallan($chalnum);

		if(isset($chdata['org_total']) && !empty($chdata['org_total'])){
		    $chalAmt=$chdata['org_total'];
		}
		$wdata=getwaiveramountbychallan($chalnum);
		if(isset($wdata['waiver_total']) && !empty($wdata['waiver_total'])){
		    $waivedAmt=$wdata['waiver_total'];
		}
		$needpay=$chalAmt-$waivedAmt;

		$alreadyPaidAmt=getAmtPaidbychallan($chalnum);

		//$receivedAmt=$amount+$balance;
		
		$remaining = $needpay-$alreadyPaidAmt;

		if($receivedAmt >  $remaining){
          //$clAmt=$receivedAmt-$remaining;
		  $clAmt=$remaining;
          $sts=1;
          $receivedAmt=$receivedAmt-$remaining;
          paidpartialchallansnew($chalnum, $clAmt, $payment_id, $parent_id, $bal);
		  updateChallanStatusNew($chalnum, $createdOn, $parent_id, $student_id, $term, $acad_year, $sts, $payment_id, $needpay);
			$balance=0;
		}else if($receivedAmt==$remaining){
          $clAmt=$receivedAmt;
          $sts=1;
          $receivedAmt=$receivedAmt-$remaining;
			paidpartialchallansnew($chalnum, $clAmt, $payment_id, $parent_id, $bal);
			updateChallanStatusNew($chalnum, $createdOn, $parent_id, $student_id, $term, $acad_year, $sts, $payment_id, $needpay);
			$balance=0;
			break;
		}else{
		  //$clAmt=$remaining-$receivedAmt;
			$clAmt=$receivedAmt;
		  $sts=2;
		  $receivedAmt=0;

		  paidpartialchallansnew($chalnum, $clAmt, $payment_id, $parent_id, $bal);
		updateChallanStatusNew($chalnum, $createdOn, $parent_id, $student_id, $term, $acad_year, $sts, $payment_id, $needpay);
		$balance=0;
		break;
		}

		
	}
}
/* To Update Challan Creation Date On Appl */
function toUpdateChallanCreationDateOnAppl($studentId,$created_date,$due_date){
    $applicantid = ''; 
    if ( stristr( $studentId, 'APPL' ) ) {
        $applicantid = trim($studentId);
    }
    if($applicantid){
     $data = sqlgetresult("SELECT * FROM updatechallandateonappl('$applicantid','$created_date','$due_date')");
    }
}
/* To Update Paid Date On Appl */
function toUpdatePaidDateOnAppl($studentId,$paiddate){    
    $applicantid = ''; 
    if ( stristr( $studentId, 'APPL' ) ) {
        $applicantid = trim($studentId);
    }
    if($applicantid){
     date_default_timezone_set("Asia/Kolkata");
     $paiddate = date('Y-m-d', strtotime($paiddate));
     $data = sqlgetresult("SELECT * FROM updatepaiddateonappl('$applicantid','$paiddate')");
    }
}

/* To Update Paid Date On Appl */
function toResetChallanDates($studentId){
    $applicantid = ''; 
    if ( stristr( $studentId, 'APPL' ) ) {
        $applicantid = $studentId;
    }
    if($applicantid){
     $data = sqlgetresult("SELECT * FROM resetChallanDatesOnAppl('$applicantid')");
    }
}

function getApplMailTemplate($applicantid = "")
{
	$html ='<style type="text/css">
	body {
	margin: 20px;
	padding: 0px;
	}
	a {
	color: #bb1b21;
	text-decoration:none;
	}
	a:hover {
	color: #000;
	text-decoration:underline;
	}
	h1 {
	font-family: Arial, Helvetica, sans-serif;
	font-size:24px;
	color:#bb1b21;
	padding-top:5px;
	margin:0;
	}
	h2 {
	font:bold 15px Tahoma, Arial, Helvetica, sans-serif;
	color:#f00;
	padding-top:5px;
	}
	p {
	font-family:Tahoma, Arial, Helvetica, sans-serif;
	font-size:14px;
	color:#494949;
	line-height:20px;
	}
	.click
	{
	color: #cb1717;
	}
	</style>
	<table width="640" border="0" align="center" cellpadding="0" cellspacing="0" style="width:614px;" >
	<tr>
	    <td valign="top" style="background:#fff; padding:15px; width:614px; ">
	        <table  border="0" cellpadding="0" cellspacing="0"  style=" background-color:#fff; padding:0px; margin:0px; width:612px;border: 10px solid #2a0192;">
	            <tr>
	                <td style="text-align:center;"><img height="100" src="https://www.omegaschools.org/feeapp/images/logo.png" alt="'.SITENAME.'"/></td>
	            </tr>
	            <tr>
	                <td style=" width:577px">
	                    <table cellspacing="0"  width="100%"  cellpadding="0" border="0" style="border-top:1px solid #040404;">
	                        <tr>
	                            <td style="color:#494949; padding:15px; background:#fff; font-size:14px;">
	                                <p><b>Dear Parent,</b></p>
	                                <p>Welcome to Omega. We are happy to inform you that your new application for your ward with the Application Id - '.$applicantid.' has been granted "Provisional Admissions" at our school and that a Fee Challan has been created accordingly.</p>
	                                <p>The "Provisional Admissions" will be moved to "Confirmed Admissions" only after you pay the Fee Challan and complete all the formalities of the documentation process which our Admin team will follow up with you separately.</p>
	                                <p>The Fee Challan must be paid online through our FEE PORTAL using our Omega&#39;s Fee App only, the details of which are here below.</p>
	                                <p>Please note that the Email id that you used to apply for admissions using our Admission App can be used for Fee App as well. But the registration process for the Fee App must be done separately.</p>
	                                <p>Kindly follow the steps below to register and make the payment through our Fee App.</p>
	                            </td>
	                        </tr>
	                        <tr>
	                            <td style="color:#494949; background:#fff; font-size:14px;"> 
	                                <ol style="color:#494949; font-size:14px;">
	                                    <li style="color:#494949;"><p>Fee app is a self-registration portal, please visit <a href="'.BASEURL.'login.php" target="_blank" style="color:#800000">'.BASEURL.'login.php</a></p></li>
	                                    <li style="color:#494949;"><p>Click Join Us.</p></li>
	                                    <li style="color:#494949;"><p>Provide your Email IDs and phone numbers, provide a password as indicated in the site like (Password123$).</p></li>
	                                    <li style="color:#494949;"><p>Verify your email id by following the link sent to your registered mail ID.</p></li>
	                                    <li style="color:#494949;"><p>Login with the registered Email Id and Password.</p></li>
	                                    <li style="color:#494949;"><p>Add your child by entering the complete Application ID from the Application Fee receipt without any space - '.$applicantid.'.</p></li>
	                                    <li style="color:#494949;"><p>Student details will be displayed in "MyInfo" section.</p></li>
	                                    <li style="color:#494949;"><p>Challan can be viewed under the "Mychildren" tab.</p></li>
	                                </ol>
	                            </td>
	                        </tr>
	                        <tr>
	                            <td style="color:#494949; padding:15px; background:#fff; font-size:14px;">
	                                <p>Click "View challan" to view the challan details.  Click "Pay Challan" to make the payment.</p>
	                                <p style="color:dodgerblue;">REFUNDABLE CAUTION DEPOSIT: Omega has a policy of collecting a one-time "Refundable Caution Deposit" for new admissions, which will be held in trust by the school and given back to the parent when their ward leaves the school (either after graduation or by taking a TC). You may choose to pay Caution deposit in either one single instalment or in two instalments, by selecting appropriately when you pay the fee challan.</p>
	                            </td>
	                        </tr>
	                        <tr>
	                            <td style="color:#494949; padding:15px; background:#fff; font-size:14px;">
	                            <p>Please pay on or before the due date mentioned in the challan.</p>
	                            <p><b>In case of any clarification in the registering process please feel free to mail us @ <a href="mailto:feeapp.support@omegaschools.org"  style="color:#800000">feeapp.support@omegaschools.org</a></b></p>
	                            </td>
	                        </tr>
	                        <tr>
	                            <td style="color:#494949; padding:15px; background:#fff; font-size:14px;">
	                            <p>Thanks & Regards, <br/>LMOIS Accounts</p>
	                            </td>
	                        </tr>
	                  </table>
	                </td>
	            </tr>
	            <tr>
	                <td style="width:577px; border-top:1px solid #040404;"> 
	                    <table cellspacing="0"  width="100%"  cellpadding="0" border="0" style="border-top:1px solid #040404;">
	                        <tr>
	                            <td align="center" style="color:#494949; padding:15px; background:#fff; font-size:12px; font-family:Tahoma, Arial, Helvetica, sans-serif; ">
	                                &copy; Copyright '.date('Y').' Omega International School
	                            </td>
	                        </tr>
	                    </table>
	                </td>
	            </tr>
	        </table>
	    </td>
	</tr>
</table>';
return $html;
}

function sendNotificationToApplParents($studentId, $mailbody, $smsbody, $type='',$attach='', $subject = '')
{

	$parentData = sqlgetresult('SELECT s."studentId", s."studentName", s."email" AS mail1, s."mobileNumber" AS mbl1,ay.year as academic_yr, s.stream FROM tbl_student s LEFT JOIN tbl_academic_year ay ON (ay.id = s.academic_yr::integer) WHERE s."studentId" =\'' . $studentId . '\' AND s."status" = \'1\' AND s."deleted" = \'0\' LIMIT 1');
	if(count($parentData) > 0){
		$copy=array("admissions@omegaschools.org","feeapp.support@omegaschools.org");
	    $streamcc=array(1=>'admissions.cbse@omegaschools.org', 2=>'cisadmissions@omegaschools.org', 3=>'admissions.nios@omegaschools.org', 4=>'ibadmissions@omegaschools.org', 5=>'admissions.indigo@omegaschools.org', 6=>'montadmissions@omegaschools.org', 7=>'heartful.kids@omegaschools.org');
		$toemail=trim($parentData['mail1']);
		if($toemail){
			$stream=trim($parentData['stream']);
			if(empty($subject)){
			  $subject = 'Fee Challan '.trim($parentData['academic_yr']);
			}
            if($stream){
            	if(isset($streamcc[$stream]) && !empty($streamcc[$stream])){
            		$copy[]=$streamcc[$stream];
            	}
            }
			$mailbody=getApplMailTemplate($studentId);
			SendApplMailId($toemail, $subject, $mailbody, '', '', $attach,'',$copy);
		}
	}
	else{
		$subject = 'Challan Created - Not mapped with parents - '.$studentId;
		$data = 'Dear Admin,<br/> '.$mailbody;
		createErrorlog($errordata);
	}
}

function SendApplMailId($to, $subject, $data, $fromname = "", $fromemail = "", $attachment = "", $type = "", $cc = []) {

	if( BASEURL == 'http://111.93.105.51/feeapp/' || BASEURL == 'http://172.16.0.25/feeapp/' || BASEURL == 'https://qa.omegaschools.org/feeapp/') {
		require_once(BASEPATH.'plugins/PHPMailer/class.phpmailer.php');
	} elseif ( BASEURL == 'https://www.omegaschools.org/feeapp/' ) {
		require_once(BASEPATH.'plugins/PHPMailer/class.phpmailer.php');
	} else {
		require_once(BASEPATH.'plugins\PHPMailer\class.PHPMailer.php');
	}
	
	$mail  = new PHPMailer();
	
	$mail->IsSMTP();
	$mail->SMTPDebug  = 0;  
	$mail->Host     = SMTPHOST; //"mail.autobulls.com"; 
	$mail->Username = SMTPUSERNAME;  
	$mail->Password = SMTPPASSWORD;
	$fromemail = SMTPFROM;
	$fromname = SMTPFROMNAME;
	
	$mail->SMTPSecure = 'tls'; // secure transfer enabled REQUIRED for GMail	 	
	$mail->Port = SMTPPORT; 
	$mail->SMTPAuth  = true;                  // enable SMTP authentication
	$mail->SMTPKeepAlive = true; 	
	
	$mail->SetFrom($fromemail,$fromname);

	$address = $to;
	
	$body=$data;
	$mail->AddAddress($address, "");
	$mail->Subject=$subject;
	$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
	$mail->MsgHTML($body);

	if(count($cc) > 0){
		foreach ($cc as $key => $value) {
			$mail->AddCC($value);
		}
	}

	if( $attachment != '') {
		$mail->AddAttachment($attachment); 
	}
	return $mail->Send();
}

/* To Update Paid Date On Appl */
function toUpdateDueDateOnAppl($studentId,$duedate){    
    $applicantid = ''; 
    if ( stristr( $studentId, 'APPL' ) ) {
        $applicantid = trim($studentId);
    }
    if($applicantid){
     date_default_timezone_set("Asia/Kolkata");
     $duedate = date('Y-m-d', strtotime($duedate));
     $data = sqlgetresult("SELECT * FROM updateduedateonappl('$applicantid','$duedate')");
    }
}

/* To get count Addtocart */
function countAddtocart($sid,$parentid){
    $num = 0;   
    $whereClauses=['"deleted"=0','"status"=0'];
    if ($parentid)
    {
        $whereClauses[]='"parentid"=\'' . $parentid . '\' ';

    }
    if ($sid)
    {
        $whereClauses[]='sid=\'' . $sid . '\' ';

    }
    $where ="";
    if (count($whereClauses) > 0) 
    { 
      $where = ' WHERE '.implode(' AND ',$whereClauses); 
      $sql = ('SELECT COUNT(*) as total FROM tbl_addtocart '.$where);
      $countchk = sqlgetresult($sql,true);
      $num=$countchk[0]['total'];
    }
    return $num; 
    
}

/* cart update other fees status */
function cartUpdateStatus($payment_id, $student_id){
 $paymentid=trim($payment_id);
 $cart = sqlgetresult('SELECT * FROM tbl_cart_payment_log WHERE id = \''.$paymentid.'\'', true);
 $num=count($cart);
 $status=2;
 $cartstaus=0;
 if($num > 0){
	$referenceids=$cart[0]['referenceids'];
	$transId=$cart[0]['transId'];
	$transDate=$cart[0]['transDate'];
	$returnCode=$cart[0]['returnCode'];
	if($cart[0]['remarks']){
		$remarksfull=trim($cart[0]['remarks']);
		$remarks=substr($cart[0]['remarks'], 0, 30);
	}else{
		$remarks="NA";
		$remarksfull="NA";
	}
	
	$transStatus=trim($cart[0]['transStatus']);
    if($transStatus=='Ok'){
       $status=1;
       $cartstaus=1;
    }

	$parentid=$cart[0]['parentid'];
	$refNum=explode(",", $referenceids);
	foreach ($refNum as $value) {
		// code...
		$ref=trim($value);
		if (strpos($ref, 'NFWC') !== false){
          $query1=sqlgetresult('SELECT * FROM tbl_partial_nfwpayment_log WHERE "transNum" = \''.$ref.'\'', true);
		  if(count($query1) > 0){
			foreach ($query1 as $nfwdata) {
				$challanNo=trim($nfwdata['challanids']);
				$amount=trim($nfwdata['receivedamount']);
				$cartid=$nfwdata['cartid'];
				$plogid=$nfwdata['id'];
				$paymentData = sqlgetresult("SELECT * FROM cartupdatenfwc('$ref','$parentid','$cartid','".$transStatus."','".$transId."','$transDate','".$remarksfull."','".$returnCode."','$cartstaus','$paymentid') ");
				if($paymentData['cartupdatenfwc'] && $status==1){
					$payment_id=toProcessNFWC($challanNo, $parentid, $student_id, $plogid, $amount, $transDate);
				}
			}
		  }
		}else if (strpos($ref, 'CNF-') !== false){
			$query2=sqlgetresult('SELECT * FROM tbl_nonfee_payments WHERE "transNum" = \''.$ref.'\'', true);
			if(count($query2) > 0){
				$payid=str_replace("CNF-","",$ref);
				foreach ($query2 as $nfdata) {
					$cartid=$nfdata['cartid'];
					$paymentData = sqlgetresult("SELECT * FROM cartcnfentry('$transStatus','$transId','".$returnCode."','".$remarks."','$transDate','".$parentid."','".$payid."','".$paymentid."','".$cartstaus."','".$cartid."') ");
					if($paymentData['cartcnfentry'] && $status==1){
						$challanNo="EVENT-".trim($nfdata['feetypeid']);
						$updatePaymentTable = sqlgetresult('UPDATE tbl_nonfee_payments SET "challanNo" = \''.$challanNo.'\' WHERE "id" = \''.$payid.'\' ');
						createCFPDF($payid,$student_id);
					}	
				}
			}
		}else{
			$query=sqlgetresult('SELECT * FROM tbl_otherfees_payment_log WHERE "refNum" = \''.$ref.'\'', true);
			if(count($query) > 0){
				foreach ($query as $querydata) {
					$typeid=$querydata['type'];
					$feeconfigid=$querydata['feeconfigid'];
					$feeTypeid=$querydata['feeType'];
					$quantity=$querydata['quantity'];
					$cartid=$querydata['cartid'];
					$amt=$querydata['amount'];
					$challanNo=trim($querydata['challanNo']);
					$paymentData = sqlgetresult("SELECT * FROM cartupdateotherfeestatus('$ref','$parentid','$cartid','$status','".$transStatus."','".$transId."','$transDate','".$remarks."','".$returnCode."','$cartstaus') ");
		     
				     	if($paymentData['cartupdateotherfeestatus'] && $status==1){
				     		if($typeid==1){
								$sfsfeeId=$feeTypeid;
								$sfsfeeName=getFeeTypebyId($sfsfeeId);
								$sfsutilitiesinputqty=$quantity;
								$singleqtyamount=($amt/$sfsutilitiesinputqty);

								$sfsqty = sqlgetresult("SELECT * FROM sfstableentry('".$challanNo."','". trim($sfsfeeName) ."','".$singleqtyamount."', '". trim($sfsutilitiesinputqty) ."', '". $amt ."','". $parentid ."','". $student_id ."')");
								createUFPDF($paymentData['cartupdateotherfeestatus'],$student_id);
				     	    }
				     	    if($typeid==4){
				     	    	createCOMFPDF($paymentData['cartupdateotherfeestatus'],$student_id);
				     	    }
				     	    if($typeid==2){
				     	    	createLFPDF($paymentData['cartupdateotherfeestatus'],$student_id);
				     	    }

				     	    if($typeid==3){
				     	    	$sstaus=1;
				     	    	$receiptupd= completeTransportChallan($sstaus, $parentid, $challanNo, $student_id, 'Online');
				     	    }

				     	      
				        }		
				}
			}
	    }
	}
     
 }

}

/* To Update Paid Date On Appl */
function cartCheckoutTotal($sid,$parentid){
    $total=0;   
    $whereClauses=['"deleted"=0','"status"=0'];
    if ($parentid)
    {
        $whereClauses[]='"parentid"=\'' . $parentid . '\' ';

    }
    if ($sid)
    {
        $whereClauses[]='studid=\'' . $sid . '\' ';

    }
    $where ="";
    if (count($whereClauses) > 0) 
    { 
      $where = ' WHERE '.implode(' AND ',$whereClauses); 
      $sql = ('SELECT * FROM cartlistlunuf '.$where);
      $cartdata = sqlgetresult($sql,true);
      $num=count($cartdata);
        if($num > 0){
        	foreach($cartdata as $data) {
				$quantity=$data['quantity'];
				$amountper=$data['amount'];
				$oftype=$data['type'];
				$quantity=$data['quantity'];
				if($oftype=="Uniform" || $oftype=="Common"){
					$amount=$amountper*$quantity;
					$total+=$amount;
				}
				if($oftype=="Lunch"){
					//$amount=$amountper;
					$total+=$amountper;
				}

				if($oftype=="Transport" || $oftype=='Non-Fee With Challan' || $oftype=='Common Non-Fee'){
					$amount=trim($data['challanamount']);
                    $total+=$amount;
				}
        	}
        }
    }
    return $total; 
    
}
/*  complete Transport Challan Admin Side */
function completeTransportChallanAdminSide($status, $parent_id, $challanNo, $student_id, $ptype="", $bank_val="", $transNum="", $transDate="", $remarks=""){
	date_default_timezone_set("Asia/Kolkata");    
	$date = date('Y-m-d h:i:s');
    $challanNo=trim($challanNo);
    $status=trim($status);

    if($ptype){
      $ptype=$ptype;
    }else{
      $ptype='Online';
    }

	$updateChallan = sqlgetresult('UPDATE tbl_challans SET "challanStatus" = \''.$status.'\', "updatedBy" = \''.$parent_id.'\', "updatedOn" = \''.$date.'\', pay_type=\''.$ptype.'\', cheque_dd_no=\''.$transNum.'\', bank=\''.$bank_val.'\', paid_date=\''.$transDate.'\', remarks=\''.$remarks.'\' WHERE "challanNo" = \''.$challanNo.'\' AND deleted=0');

	$demandtblupd =sqlgetresult('UPDATE tbl_demand SET "challanStatus" = \''.$status.'\', "updatedOn" = CURRENT_TIMESTAMP, "updatedBy" = \''. $parent_id .'\', pay_type=\''.$ptype.'\', cheque_dd_no=\''.$transNum.'\', bank=\''.$bank_val.'\', paid_date=\''.$transDate.'\', remarks=\''.$remarks.'\' WHERE "challanNo" =\'' . $challanNo . '\' AND deleted=0');

	$waivertblupd = sqlgetresult('UPDATE tbl_waiver SET "challanStatus" = \''.$status.'\', "modified_on" = CURRENT_TIMESTAMP, "modified_by" = \''. $parent_id.'\' WHERE "challanNo" =\'' . $challanNo . '\' AND deleted=0');
	if($status==1){       
		createTFPDF($student_id,$challanNo);
		$receiptupd = updatereceipt($challanNo, $student_id);  

		$fromwhere = 'Receipt';
		flattableentry($challanNo, $student_id, $fromwhere);
    }
    return 1;
}
/* To Check New Admission  */
function toCheckNewAdmission($studentId){    
    $applicantid = trim($studentId);
    $result=0;
    if ( stristr( $applicantid, 'APPL' ) ) {
	    $data = sqlgetresult('SELECT id FROM tbl_applicant WHERE "applicant_id" = \''.$applicantid.'\' AND "paid_date" IS NULL', true);
	     if($data[0]['id']){
	       $result=$data[0]['id'];
	     }else{
	     	$result=0;
	     }
    }
    else{
      $result=0;
    }
    return $result;
}

/* Acadamic years */
function getacadamicYears(){
	$yearchecks = sqlgetresult("SELECT * FROM yearcheck",true);
	$acadamicYears = array();
	foreach ($yearchecks as $k => $v) {
		$acadamicYears[$v['id']]=trim($v['year']);
	}
	return $acadamicYears;
}
/* class */
function getClassses(){
	$classstypes = sqlgetresult("SELECT * FROM classcheck",true);
	$classses = array();
	foreach ($classstypes as $class) {
		$classses[$class['id']]=trim($class['class_list']);
	}

	return $classses;
}
/* Streams */
function getStreams(){
	$streamtypes = sqlgetresult("SELECT * FROM streamcheck",true);
	$streams = array();
	foreach ($streamtypes as $stream) {
		$streams[$stream['id']]=trim($stream['stream']);
	}
	return $streams;
}
/* Student Details */
function getStudentDetails(){
	$stud = sqlgetresult('SELECT id,"studentName","studentId","old_studentId",application_no,"parentId",gender  FROM tbl_student WHERE deleted=0',true);
	$result = array();
	foreach ($stud as $k => $v) {
		$result[trim($v['studentId'])]=array("id"=>$v['id'],"studentName"=>trim($v['studentName']),"studentId"=>trim($v['studentId']),"old_studentId"=>$v['old_studentId'],"application_no"=>trim($v['application_no']),"parentId"=>trim($v['parentId']),"gender"=>trim($v['gender']));
	}
	return $result;
}

/* Student Details */
function getStudentDetailsByOldId($oldstudid){
	$oldstudid=trim($oldstudid);
	$result="";
	if($oldstudid){
		$data = sqlgetresult('SELECT id,"studentName","studentId","old_studentId",application_no,"parentId"  FROM tbl_student WHERE (application_no= \''.$oldstudid.'\' OR "old_studentId" ILIKE \'%'.$oldstudid.'%\') LIMIT 1');
		$result=$data['studentId'];
	}
	return $result;
}
/*to Get Group Name by feetype */

function toGetFeeGroupNameList(){
   $data = sqlgetresult('SELECT ft.id,ft."feeType",fg."feeGroup" FROM tbl_fee_group fg JOIN tbl_fee_type ft ON(ft."feeGroup"::INT=fg.id) WHERE fg.deleted=0', true);
   $result=[];
   foreach ($data as $key => $value) {
   	// code...
   	$ftype=trim($value['feeType']);
   	$fgroup=trim($value['feeGroup']);
   	$result[$ftype]=$fgroup;
   }
   return $result;
}
/*to get the challan not paid count */
function toGetChallanNotPaidCount($params){
  $result=0;
  $whereCond="";
  //id= 1 ###The challan paid status check while paying the other fees.###
  $chkqry='SELECT COUNT(*) FROM bkfunctioncheck WHERE id=1 AND "status"=\'ACTIVE\' AND deleted = \'0\'';
  $ckdata  = sqlgetresult($chkqry, true);
  $isActive=isset($ckdata[0]['count'])?$ckdata[0]['count']:0;
  if(count($params) > 0 && $isActive >0){
  	$parentid=isset($params['parentId'])?$params['parentId']:"";
  	$studentId=isset($params['studentId'])?$params['studentId']:"";
  	$term=isset($params['term'])?$params['term']:"";
  	if(!empty($parentid)){
  		$whereCond.=' AND "parentId"=\''.$parentid.'\'';
  	}

  	if(!empty($studentId)){
  		$whereCond.=' AND "studentId"=\''.$studentId.'\'';
  	}

  	if(!empty($term)){
  		$whereCond.=' AND "term"=\''.$term.'\'';
  	}
  	$rdflt='RD-';
  	//$qry='SELECT COUNT(*) FROM challandatanew WHERE "studStatus"!=\'Transport.Fee\' AND ("challanStatus" = \'0\' OR "challanStatus" = \'2\') AND "academicYear" >=7'.$whereCond;
  	$qry='SELECT COUNT(*) FROM challandatanew WHERE "studStatus"=\'Prov.Promoted\' AND ("challanStatus" = \'0\' OR "challanStatus" = \'2\') AND ("challanNo" NOT ILIKE \'%'.$rdflt.'%\') AND "academicYear" >=6'.$whereCond;
  	$data  = sqlgetresult($qry, true);
	$result=$data[0]['count'];
	if($result == 0){
		$datenow = date("Y-m-d");
		$result =  toGetPartialRDCount($studentId, $rdflt, $datenow);
	}
  }
  return $result;
}

/* Common Fee PDF*/
function createCOMFPDF($id, $student_id) {

	$data = sqlgetresult('SELECT *  FROM otherfeesreport WHERE pyid = \''.$id.'\' AND typeid=\'4\' ');
	$className = $data['class_list'];
	 
	$challanno=trim($data['challanNo']);
	$eventid = explode('-', $data['challanNo']);
	//$displayName = getEventNamebyid($eventid[1]);

	$displayName = trim($data['feetypename']);
	$streamName = trim($data['streamname']);
    //$datefolder = date("dmY", strtotime($data['transDate']));

    $datefolder = "common";

    if (!is_dir(BASEPATH."receipts/".$datefolder)) {
		mkdir(BASEPATH."receipts/".$datefolder);
	}	
	$documentPath = BASEPATH."receipts/".$datefolder."/";

    $Semester = $data['term'];
    $name = trim($data['studentName']);
    $studentId = trim($data['studentId']);
    $challanData1['org_total'] = $data['amount'];
    $challanData1['section'] = trim($data['section']);    
    $challanData1['academicYear'] = trim($data['academic_yr']); 
    $refnum=trim($data['transNum']);
    //$challanData1['transNum'] = $refnum;
    $challanData1['quantity'] = trim($data['quantity']);    


    $contract =' <style>.noBorder td{ border:none} table{border-collapse: collapse} .datatbl tr td{border:1px solid #9a9a9a;}</style>
    <table width="750" border="0" >
        <tr> <td  align="center" valign="top"><img src="images/logo_pdf.jpg"  height="80" /> </td> 
        <td align="right">COMMON-FEE</td>
        </tr>               
      </table>';
    $contract.='<table width="750" border="1" cellspacing="5" cellpadding="15" class="datatbl">
            <tr>
            <td align="left" height="25" width="250"><strong>Academic Year</strong>: '.$challanData1['academicYear'].' </td>
            <td colspan="2" align="left" height="25" width="200"><strong>Ref NO</strong>: '.$refnum.' </td>
            <td align="left" height="25" width="200"><strong>Date</strong>: '.date("d-m-Y").' </td>
        </tr>               
        <tr>
            <td align="left" colspan="3" height="25"><strong>Name</strong>: '.$name.' </td>
            <td align="left" ><strong>Semester</strong>: '.$Semester.' </td>
        </tr>
        <tr>
            <td align="left" colspan="3" height="25"><strong>Student ID</strong>: '.$studentId.' </td>
            <td align="left" ><strong>Class</strong>: '.$className.'-'. $challanData1['section'] .' </td>
        </tr>               
        <tr>
            <td align="center" height="25"><strong>S.No</strong> </td>
            <td align="center" colspan="2" height="25"><strong>Particluars</strong> </td>
            <td align="center" height="25" ><strong>Amount</strong> </td>
        </tr>';

    $contracttt = '';
    $contracttt .= '<tr><td colspan="" align="center" height="25">'. 1 .'</td>';
        $contracttt .= '<td colspan="2" align="center" height="25">'.$displayName.' - '.$challanData1['quantity'].'</td>';
        $contracttt .= '<td colspan="" align="right" height="25">'. $challanData1['org_total'] .'</td>';                       
        $contracttt .= '</tr>';
    $contract .= $contracttt;
    $contract.='<tr>
        <td colspan="3" align="right" height="25"><strong>Total</strong></td>
        <td align="right"> '. $challanData1['org_total'].' </td>
    </tr>';
    $contract.='<tr>
        <td colspan="4" align="left" height="25"><strong>Rupees in words : </strong>'.getCurrencyInWords($challanData1['org_total']).' </td>
    </tr>';    
    
    $contract.='</table><p>*This is a computer generated receipt and does not require authorization.</p>';   
    $data = $contract;


	require_once 'vendor/autoload.php';

	$mpdf = new \Mpdf\Mpdf();
	$mpdf->SetWatermarkText('PAID', 0.08);
	$mpdf->showWatermarkText = true; 

	//$challanno = $studentId.'EVENT-'.trim($eventid[1]);
	//$challanno = $data['challanNo'];

	$challanID = str_replace('/', '', $refnum);
	$mpdf->WriteHTML($data);
	$filename=$documentPath.$challanID.".pdf";
	$mpdf->Output($filename,'F');
}
/* For Non Fee With Challan */
function getAmtPaidbyNFWChallan($challanno){
   $data = sqlgetresult('SELECT paidamt FROM tbl_partialpaid_nfwchallan WHERE "challanNo" = \''.$challanno.'\' AND deleted=0',true);
    $amount=0;
	foreach ($data as $k =>$value) {
		$paid_total=isset($value['paidamt'])?$value['paidamt']:0;
		$amount+=$paid_total;
	}
	return $amount;

}

function paidPartialNFWC($challan, $amt, $plogid, $parentid){
	$paymenttablecheck = sqlgetresult('SELECT COUNT(*) as total FROM tbl_partialpaid_nfwchallan WHERE "challanNo" = \''.$challan.'\' AND deleted=0',true);
	$num=$paymenttablecheck[0]['total'];
	$serial=$num+1;
	$cusChallanNo=$challan."-PP-".$serial;
	$uid=$parentid;

	$plogid=str_replace("NFWC","",$plogid);
	$paymentdata=sqlgetresult('INSERT INTO tbl_partialpaid_nfwchallan (plogid,"challanNo","refchallanNo",paidamt,"createdBy","createdOn") VALUES (\''.$plogid.'\',\''.$challan.'\',\''.$cusChallanNo.'\',\''.$amt.'\',\''.$uid.'\',now()) RETURNING id');
	return $paymentdata['id'];
}



function toProcessNFWC($challanNo, $parent_id, $student_id, $payment_id, $amount, $createdOn){
     $payid=paidPartialNFWC($challanNo, $amount, $payment_id, $parent_id);
     if($payid){
     	$cdata=toGetNFWChallanAmount($challanNo);
     	if($cdata['n_due']==0){
     		$status=1;
     	}else{
     		$status=2;
     	}
     	$updat=updateNFWCStatus($challanNo, $createdOn, $parent_id, $student_id, $status, $payment_id, $amount);
     	if($updat){
          return 1;
     	}else{
     		return 0;
     	}
     }else{
     	return 0;
     }
}


function updateNFWCStatus($challanNo, $date, $parent_id, $student_id, $status, $payment_id, $amount){
	$challanNo=trim($challanNo);
	$updateChallan = sqlgetresult('UPDATE tbl_nonfee_challans SET "challanStatus" = \''.$status.'\', "updatedBy" = \''.$parent_id.'\', "updatedOn" = \''.$date.'\' WHERE "challanNo" = \''.$challanNo.'\' AND deleted=0');
	if($status==1){
		$transStatus="Ok";
		$createdby = $parent_id;
		$createdOn=$date;
		$returnCode=combineNFWCPartialRefnumber($challanNo);
		$numberattempt=0;
		if(!empty($returnCode)){
			$paratemp=explode(",", $returnCode);
			$numberattempt=count($paratemp);
		}

		if($numberattempt > 1){
			$transNum="partialpaid";
			//$retn=$returnCode;
			$payment_remarks=$transNum."/Online";
		}else{
			$transNum="NFWC".$payment_id;
			//$retn=$returnCode;
			$payment_remarks=$transNum."/Online";
		}

		//echo 'INSERT INTO tbl_nonfee_payments ("parentId", "studentId", "challanNo","amount","transStatus", "transNum", "returnCode", "remarks", "transDate", "createdby", "createdOn") VALUES (\''.$parent_id.'\',\''.$student_id.'\',\''.$challanNo.'\',\''.$amount.'\',\''.$transStatus.'\',\''.$transNum.'\',\''.$returnCode.'\',\''.$payment_remarks.'\',\''.$createdOn.'\',\''.$createdby.'\',\''.$createdOn.'\') RETURNING id';
		//	exit;

		$payment = sqlgetresult('INSERT INTO tbl_nonfee_payments ("parentId", "studentId", "challanNo","amount","transStatus", "returnCode", "remarks", "transDate", "createdby", "createdOn") VALUES (\''.$parent_id.'\',\''.$student_id.'\',\''.$challanNo.'\',\''.$amount.'\',\''.$transStatus.'\',\''.$returnCode.'\',\''.$payment_remarks.'\',\''.$createdOn.'\',\''.$createdby.'\',\''.$createdOn.'\') RETURNING id');
	    if($payment['id']){
	    	createNFPDFNEW($student_id,$challanNo,'');
	    	return 1;
	    }else{
	    	return 0;
	    }				
	}
    return 1;
}

function combineNFWCPartialRefnumber($challanno){
	$out="";
	$data = sqlgetresult('SELECT plogid,"challanNo","refchallanNo",paidamt FROM tbl_partialpaid_nfwchallan WHERE "challanNo" = \''.$challanno.'\' AND deleted=0',true);
    $refnumber=[];
	foreach ($data as $k =>$value) {
		$refnumber[]=$value['refchallanNo'];
	}
	if(count($refnumber) > 0){
	 $out=implode(",", $refnumber);
	}
	return $out;
}

function toGetNFWChallanAmount($challanno){
	$challanData = sqlgetresult('SELECT * FROM nonfeechallandata WHERE "challanNo" = \'' . $challanno . '\' AND ("challanStatus" = \'0 \' OR  "challanStatus" = \'2 \') AND "visible" = \'1\' ',true);
	$org_total=0;
	$no_of_instalments=0;
	$ispartial="";
	$result=[];
	$result['m_due']=0;
	$result['t_due']=0;
	$result['n_due']='';
	if(count($challanData) > 0){
		foreach ($challanData as $value) {
			$org_total+=$value['total'];       
			$ispartial=$value['partialpayment'];
			$no_of_instalments=$value['no_of_instalments'];
		}
        $result['t_due'] = $org_total;
		if($ispartial && $no_of_instalments){
			$minidue=($org_total/$no_of_instalments);
			$result['m_due'] = ceil($minidue);
		}
		$paidSoFor = getAmtPaidbyNFWChallan($challanno);
		if($paidSoFor >= $org_total){
           $result['n_due'] = 0;
		}else{
			$result['n_due'] = $org_total-$paidSoFor;
		}
	}
	return $result;
	
}

function createNFPDFNEW($studId,$chlno,$type='') {

	$getparentmailid = sqlgetresult('SELECT "email" AS parentMailId FROM tbl_student WHERE "studentId"=\''.$studId . '\'');
    $challanData = sqlgetresult('SELECT * FROM nonfeechallandata WHERE "studentId" =\''. $studId . '\' AND  "challanNo" = \'' . $chlno . '\' AND "challanStatus" = 1 AND "visible" = \'1\' ',true);
    // print_r($challanData);
	$feeTypes = sqlgetresult("SELECT * FROM tbl_nonfee_type");    
    $mailid = $getparentmailid['parentmailid'];
    $to = $mailid;

    if($type != '') {
    	$datefolder = date("dmY", strtotime($type));
    } else {
    	$datefolder = date('dmY');
    }

    if (!is_dir(BASEPATH."receipts/".$datefolder)) {
		mkdir(BASEPATH."receipts/".$datefolder);
	}	
	$documentPath = BASEPATH."receipts/".$datefolder."/";

    $total = 0;
    $feeData = array();   
    foreach ($challanData as $k=>$value) {
        $challanno = $value['challanNo'];
        $Semester = $value['term'];
        $challanData1['clid'] = $value['clid'];
        $name = $value['studentName'];
        $studentId = $value['studentId'];
        $className = $value['class_list'];
        $challanData1['duedate'] = $value['duedate'];
        $challanData1['stream'] = $value['stream'];
        $streamName = $value['steamname'];
        $challanData1['org_total'] = $value['total'];
        $challanData1['section'] = $value['section'];
        $cheque = $value['cheque_dd_no'];
        $pay_type = $value['pay_type'];
        $pdate = $value['paid_date'];
        //$challanData1['academicYear'] = $value['academic_yr'];
        //$challanData1['academicYear'] = getAcademicyrById($value['academic_yr']);
        $challanData1['academicYear'] = getAcademicyrById($value['chalayear']);
        $feename = $value['feename'];
        $feegroup = $value['feeGroup'];
        if($value['remarks'] != ''){
        $remarks = $value['remarks'];
        }
        else{
        $remarks = 'Nil';

        }

        $feetypedata = sqlgetresult('SELECT * FROM getnonfeetypedata WHERE class=\''.$value['clid'].'\' AND semester=\''.$Semester.'\' AND stream = \''.$value['stream'].'\' AND "academicYear" = \''.getAcademicyrIdByName($value['academic_yr']).'\' AND "feeType" = \''.$value['feeType'].'\' ');


        $contract[$k] =' <style>.noBorder td{ border:none} table{border-collapse: collapse} .datatbl tr td{border:1px solid #9a9a9a;}</style>
		<table width="750" border="0" >
		  	<tr> <td  align="center" valign="top"><img src="images/logo_pdf.jpg"  height="80" /> </td> 
		  	<td align="right">NON-FEE</td>
		  	</tr>			  	
		  </table>';
		$contract[$k].='<table width="750" border="1" cellspacing="5" cellpadding="15" class="datatbl">
				<tr>
				<td colspan="2" align="left" height="25" width="300"><strong>Challan No.</strong>: '.$challanno.' </td>
				<td colspan="" align="left" height="25" width="250"><strong>Academic Year</strong>: '.$challanData1['academicYear'].' </td>
				<td colspan="" align="left" height="25" width="200"><strong>Date</strong>: '.date("d-m-Y").' </td>
			</tr>				
			<tr>
				<td align="left" colspan="3" height="25"><strong>Name</strong>: '.$name.' </td>
				<td align="left" ><strong>Semester</strong>: '.$Semester.' </td>
			</tr>
			<tr>
				<td align="left" colspan="2" height="25"><strong>Student ID</strong>: '.$studentId.' </td>
				<td align="left" ><strong>Class</strong>: '.$className.'-'. $challanData1['section'] .' </td>
				<td align="left" ><strong>Due Date</strong>: '.date("d-m-Y", strtotime($challanData1['duedate'])).' </td>
			</tr>				
			<tr>
				<td align="center" height="25"><strong>S.No</strong> </td>
				<td align="center" colspan="2" height="25"><strong>Particluars</strong> </td>
				<td align="center" height="25" ><strong>Amount</strong> </td>
			</tr>';

			$contracttt = '';
			$contracttt .= '<tr><td colspan="" align="center" height="25">'. 1 .'</td>';
		    	$contracttt .= '<td colspan="2" align="center" height="25">'.$feename.'</td>';
		    	$contracttt .= '<td colspan="" align="right" height="25">'. $challanData1['org_total'] .'</td>';		    	       
		    	$contracttt .= '</tr>';
			$contract[$k] .= $contracttt;
			$contract[$k].='<tr>
				<td colspan="3" align="right" height="25"><strong>Total</strong></td>
				<td align="right"> '. $challanData1['org_total'].' </td>
			</tr>';
			$contract[$k].='<tr>
				<td colspan="4" align="left" height="25"><strong>Rupees in words : </strong>'.getCurrencyInWords($challanData1['org_total']).' </td>
			</tr>
			<tr>
				<td align="left" colspan="4" height="25"><strong>Remarks</strong>: '.$remarks.' </td>
			</tr>';
			if( $pay_type == '' ) {
				$pay_type = 'Online';
			}
			$contract[$k].='<tr>
				<td align="left" height="25"><strong>Mode of Payment </strong></td>
				<td colspan="3" align="left" height="25">'.$pay_type.'</td>
			</tr>';

			if( $pay_type != 'Online') {
				$bank = $value['bank'];
				$contract[$k].='<tr>
					<td  align="left" height="25"><strong>Cheque / DD No.</strong></td>
					<td colspan="" align="left" height="25">'.$cheque.'</td>
					<td  align="left" height="25"><strong>Date</strong></td>
					<td colspan="" align="left" height="25">'.$pdate.'</td>
				</tr>';
				$contract[$k].='<tr>				
					<td  align="left" height="25"><strong>Bank</strong></td>
					<td colspan="" align="left" height="25">'.$bank.'</td>
					<td  align="left" height="25"><strong>Branch</strong></td>
					<td colspan="" align="left" height="25"></td>
				</tr>';
				$contract[$k].='<tr>
					<td  align="left" height="25"><strong>Cashier / Manager</strong></td>
					<td colspan="" align="left" height="25"></td>
					<td  align="left" height="25"><strong>Signature of Remitter</strong></td>
					<td colspan="" align="left" height="25"></td>
				</tr>';
			}
			
			$contract[$k].='</table><p>*This is a computer generated receipt and does not require authorization.</p>'; 			
	}
    // print_r($contract);
    $data = implode('<br/>',$contract);

    // print_r($data);exit;

	require_once 'vendor/autoload.php';

	$mpdf = new \Mpdf\Mpdf();
	$mpdf->SetWatermarkText('PAID', 0.08);
	$mpdf->showWatermarkText = true; 

	$challanID = str_replace('/', '', trim($challanno));
	// $pdf->convert($data,$documentPath.$challanID.".pdf",1);

	$mpdf->WriteHTML($data);
	$mpdf->Output($documentPath.$challanID.".pdf",'F');
}
function getNonFeebyid($eventid){
	$data = sqlgetresult('SELECT * FROM tbl_nonfee_type WHERE status = \'1\' AND "id" =  \''.trim($eventid).'\'', true);
    $num=count($data);
    $result=[];
    if($num > 0){
    	foreach($data as $val){
         $result['ftype']=trim($val['feeType']);
         $result['feeGroup']=trim($val['feeGroup']);
         $result['description']=trim($val['description']);
         $result['acc_no']=trim($val['acc_no']);
    	}
    }
	return $result;
}

function toSubstr($input,$size=30){
	if($input){
		return substr($input, 0, $size);
	}
	return $input;
}
/* To Get Account number for NON-FEE with Challan */
function toGetNFWCAccountNo($challanno){
	$result=[];
	if($challanno){
		$data = sqlgetresult('SELECT c.id AS cid,c."challanNo",nf.id AS feetype_id,nf."feeType" AS feename,nf.acc_no FROM tbl_nonfee_challans c JOIN tbl_nonfee_type nf  ON (nf.id = c."feeType") WHERE nf.status = \'1\' AND c."challanNo" =  \''.trim($challanno).'\'', true);
		$num=count($data);
		if($num > 0){
			foreach($data as $val){
		     $result['acc_no']=trim($val['acc_no']);
			}
		}
	}
	return $result;
}

function getAmountFeeByFeeId($feeType, $class, $academicyear, $term, $stream) {
	$data = sqlgetresult('SELECT amount FROM tbl_fee_configuration WHERE "feeType" = \''.$feeType.'\' AND class = \''.$class.'\' AND "academicYear" = \''.$academicyear.'\' AND semester = \''.$term.'\' AND stream = \''.$stream.'\' AND deleted = 0 ');
	return $data['amount'];
}

/* To Get Caution Deposit Partial Option with Challan */
function isCautionDepositPartial($studId){
	//$par_ft_id=36;
	$caution = [];
	//$challanData = sqlgetresult('SELECT  * FROM challandatawithfeetype WHERE "studentId" =\'' . $studId . '\'  AND "studStatus"!=\'Transport.Fee\' AND "challanStatus" = \'0\' AND "academicYear" >=6 AND "ispartial" = \'1\'',true);
	$challanData = sqlgetresult('SELECT  * FROM challandatawithfeetypenew WHERE "studentId" =\'' . $studId . '\'  AND "studStatus"!=\'Transport.Fee\' AND "challanStatus" = \'0\' AND "academicYear" >=6 AND "ispartial" = \'1\'',true);
	 $num=count($challanData);
	if($num > 0){
		$paytotal=0;
		foreach ($challanData as $k => $value) {
			$org_total=$value['org_total'];
			$ft_id=trim($value['feeType']);
			$caution[$ft_id]['cid'] = $value['cid'];
			$caution[$ft_id]['challanNo'] = trim($value['challanNo']);
			$caution[$ft_id]['c_ft'] = $ft_id;
			$nxt_ftid=trim($value['next_feetype_id']);
			$caution[$ft_id]['n_ft'] = $nxt_ftid;
			$caution[$ft_id]['ispartial'] = trim($value['ispartial']);
			$caution[$ft_id]['next_due_date'] = trim($value['next_due_date']);
			$caution[$ft_id]['org_total'] = $org_total;

			$caution[$ft_id]['studentId'] = trim($value['studentId']);
			$caution[$ft_id]['studentName'] = trim($value['studentName']);
			$caution[$ft_id]['feeGroup'] = trim($value['feeGroup']);

			$class = trim($value['clid']);
			$academicyear = trim($value['academicYear']);
			$term = trim($value['term']);
			$stream = trim($value['chlstream']);

            $amt = getAmountFeeByFeeId($nxt_ftid, $class, $academicyear, $term, $stream);
            $remain_amt = ($amt)?$amt:0;

			if($org_total > $remain_amt){
				$paytotal= $org_total-$remain_amt;
			}
			$caution[$ft_id]['pay_total'] = $paytotal;
			$caution[$ft_id]['class'] = $class;
			$caution[$ft_id]['academicyear'] = $academicyear;
			$caution[$ft_id]['term'] = $term;
			$caution[$ft_id]['stream'] = $stream;
			$caution[$ft_id]['nxt_amt'] = $remain_amt;
		}
	}
	return $caution;
}
function toUpdateFeeTypePaidAmt($par_caution){
	$uid = (isset($_SESSION['uid']) && !empty($_SESSION['uid']))?$_SESSION['uid']:"";
	if(!$uid){
		$uid = (isset($_SESSION['myadmin']['adminid']) && !empty($_SESSION['myadmin']['adminid']))?$_SESSION['myadmin']['adminid']:0;
	}
	$caution = json_decode($par_caution, true);
	if(count($caution)){
		foreach($caution as $key=>$par_ft_details){
			$cid=$par_ft_details['cid'];
			$paid_total=$par_ft_details['pay_total'];
		    $paymentData = sqlgetresult("SELECT * FROM updatechallanamt('$paid_total','".$uid."','1','".$cid."')");
		}
	}
}

function toCheckFeeTypePartial($par_caution){
	$uid = (isset($_SESSION['uid']) && !empty($_SESSION['uid']))?$_SESSION['uid']:"";
	if(!$uid){
		$uid = (isset($_SESSION['myadmin']['adminid']) && !empty($_SESSION['myadmin']['adminid']))?$_SESSION['myadmin']['adminid']:0;
	}
	$caution = json_decode($par_caution, true);
	if(count($caution)){
		$i=1;
		foreach($caution as $key=>$par_ft_details){
			$cid=$par_ft_details['cid'];
		    $challanNo=trim($par_ft_details['challanNo']);
		    $c_ft=$par_ft_details['c_ft'];
		    $n_ft=$par_ft_details['n_ft'];
		    $next_due_date=$par_ft_details['next_due_date'];
		    $paid_total=$par_ft_details['pay_total'];
		    $nxt_amt=$par_ft_details['nxt_amt'];
		    $class=$par_ft_details['class'];
		    $academicyear=$par_ft_details['academicyear'];
		    $term=$par_ft_details['term'];
		    $stream=$par_ft_details['stream'];

		    $studentId=$par_ft_details['studentId'];
		    $studentName=$par_ft_details['studentName'];
		    $feeGroup=$par_ft_details['feeGroup'];
			//if($i==1){
				//$streamName = getStreambyId($stream);  
				//$newchallanNum=challanSequnceNumber($streamName);
				//$newchallan="RD-".$newchallanNum;
				$newchallan="RD-".$challanNo;
				$remarks='caution deposit - partial';
			//}
			$studstatus='Prov.Promoted';
			$sql = "SELECT * FROM createtempchallancautionfee('$newchallan','$studentId','$uid','".$n_ft."','$stream','$class','$term','$studentName','".$nxt_amt."','$remarks','$next_due_date','".$feeGroup."','$academicyear','$studstatus')";  
			$result = sqlgetresult($sql); 
			if ($result['createtempchallancautionfee'] > 0) {
				$rowid = sqlgetresult('SELECT * FROM tbl_temp_challans WHERE "challanNo"= \'' . $newchallan . '\' AND "feeGroup" IS NOT NULL', true);
				foreach ($rowid as $k => $row)
				{
					$id = $row['id'];
					$datas = sqlgetresult("SELECT * FROM createChallanNew('".$id."')");
				}
			}
		    $i++;
		}
	}
}
function toGetPartialRDCount($studId, $cautionflter, $datenow='') {
    $where=""; 
    if($datenow){
        $where=' AND DATE("duedate") <=\'' .$datenow. '\''; 
    }
    $data  = sqlgetresult('SELECT COUNT(*) as num FROM challandatanew WHERE "studentId" =\'' . $studId . '\'  AND "studStatus"!=\'Transport.Fee\' AND ("challanStatus" = \'0\' OR "challanStatus" = \'2\') AND ("challanNo" ILIKE \'%'.$cautionflter.'%\') AND "academicYear" >=6'. $where);
    return $data['num'];    
}
?>
