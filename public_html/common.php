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
							<p><b>If you wish to contact us, please do not reply to this message.</a></b></p>
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

function SendMailId($to, $subject, $data, $fromname = "", $fromemail = "", $attachment = "", $type = "") {	

	if( BASEURL == 'http://111.93.105.51/feeapp/') {
		require_once('/var/www/html/feeapp/plugins/PHPMailer/class.phpmailer.php');
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
	
 	// $address = $to;
 	$address = 'manojkumarp@vishwak.com';

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

	if(is_array($attachment))
	{
		$attachment=$this->trim_array($attachment);
	}
	if(!empty($attachment))
	{
		foreach($attachment as $attachments)
		{
			$mail->AddAttachment($attachments); 
		}
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
            <td colspan="5" style="text-align:center;"><b>'.$filename.'</b></td>
            </tr>
            <tr>';
            foreach($columns as $heading) {
            	$html .= '<th align="center">'.$heading.'</th>';
            }
            
            $html .= '</tr><tr><td colspan="'.count($columns).'"></td></tr>';

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

function getAcademicyrById($yrid) {
	$data  = sqlgetresult('SELECT "year" FROM tbl_academic_year WHERE "id" = \''.$yrid.'\' ');
	return $data['year'];	
}

function createErrorlog($data,$msg = '',$page = 0)
{
	if (!is_dir(BASEPATH."logs/".date('dmY'))) {
		mkdir(BASEPATH."logs/".date('dmY'));
	}
	$error_log = fopen(BASEPATH."/logs/".date('dmY')."/error_log".time().".txt", "a+");
	fwrite($error_log,$data);
	fclose($error_log);

	if($page == 1) {
		$_SESSION['errorpage_content'] = $msg;
		header("location:errorpage.php");
	}
}

function createPDF($studId,$chlno) {

	$getparentmailid = sqlgetresult('SELECT "email" AS parentMailId FROM tbl_student WHERE "studentId"=\''.$studId . '\'');
    $challanData = sqlgetresult('SELECT * FROM challandatanew WHERE "studentId" =\''. $studId . '\' AND  "challanNo" = \'' . $chlno . '\' AND "challanStatus" = 1 ',true);
    // print_r($challanData);
	$feeTypes = sqlgetresult("SELECT * FROM getFeeTypes");    
    $mailid = $getparentmailid['parentmailid'];
    $to = $mailid;

    if (!is_dir(BASEPATH."receipts/".date('dmY'))) {
		mkdir(BASEPATH."receipts/".date('dmY'));
	}	

	$documentPath = BASEPATH."receipts/".date('dmY')."/";

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
        $challanData1['org_total'] = $value['org_total'];
        $challanData1['section'] = $value['section'];
        $cheque = $value['cheque_dd_no'];
        $pay_type = $value['pay_type'];
        $pdate = $value['paid_date'];
        $challanData1['academicYear'] = $value['academic_yr'];

        $feetype = explode(',',$value['feeTypes']);
        foreach ($feetype as $v) {
            $feeData[trim($v)][] = $value['feeGroup'];
            $feeData[trim($v)][] = $value['org_total'];
        }

        $feetypedata = sqlgetresult('SELECT * FROM getfeetypedata WHERE class=\''.$challanData1['clid'].'\' AND semester=\''.$Semester.'\' AND stream = \''.$challanData1['stream'].'\' AND "academicYear" = \''.$challanData1['academicYear'].'\' ',true);
        // print_r($feetypedata);
	    foreach ($feeData as $id=>$fee) {
	        foreach($feetypedata as $val){
	        	if ( trim($id) == trim($val['feeType'])) {	           
	                $total  = $val['amount'];
	                $groupdata[$val['feeGroup']][$val['id']][] = $val['amount'];
	                $groupdata[$val['feeGroup']][$val['id']][] = $val['feename'];
	            } 
	        }
	    }

	    foreach ($groupdata as $key => $feegroup) {
	    	$contract[$k] =' <style> .noBorder td{ border:none} </style>
			<table width="750" border="0" >
			  	<tr> <td  align="center" valign="top"><img src="images/logo_pdf.jpg"  height="80" /> </td> 
			  	<td align="right">'.$key.'</td>
			  	</tr>			  	
			  </table>';

			$contract[$k].='<table width="750" border="1" cellspacing="5" cellpadding="40">
					<tr>
					<td colspan="2" align="left" height="40"><strong>Challan No.</strong>: '.$challanno.' </td>
					<td colspan="" align="left" height="40"><strong>Academic Year</strong>: '.$challanData1['academicYear'].' </td>
					<td colspan="" align="left" height="40"><strong>Date</strong>: '.date("d-m-Y").' </td>
				</tr>				
				<tr>
					<td align="left" colspan="3" height="40"><strong>NAME</strong>: '.$name.' </td>
					<td align="left" ><strong>SEMESTER</strong>: '.$Semester.' </td>
				</tr>
				<tr>
					<td align="left" colspan="2" height="40"><strong>Student ID</strong>: '.$studentId.' </td>
					<td align="left" ><strong>Class</strong>: '.$className.'-'. $challanData1['section'] .' </td>
					<td align="left" ><strong>Due Date</strong>: '.date("d-m-Y", strtotime($challanData1['duedate'])).' </td>
				</tr>				
				<tr>
					<td align="center" height="25"><strong>S.No</strong> </td>
					<td align="center" colspan="2" height="25"><strong>Particluars</strong> </td>
					<td align="center" height="25" ><strong>Amount</strong> </td>
				</tr>';

				$tot = 0;
			    $i = 1;
			    // print_r($groupdata);
			    $contractt = '';
			   foreach ($feegroup as $v) {
		    		$contractt .= '<tr><td colspan="" align="center" height="40">'.$i.'</td>';
			    	$contractt .= '<td colspan="2" align="center" height="40">'.$v[1].'</td>';
			    	$contractt .= '<td colspan="" align="right" height="40">'.$v[0].'</td>';		    	       
			    	$contractt .= '</tr>';
			    	$tot += $v[0];
			    	$i++;
		    	}

				$contract[$k] .= $contractt;
				$contract[$k].='<tr>
					<td colspan="3" align="right" height="40"><strong>TOTAL</strong></td>
					<td align="right"> '.$tot.' </td>
				</tr>';
				$contract[$k].='<tr>
					<td colspan="4" align="left" height="40"><strong>Rupees in words : </strong>'.getCurrencyInWords($tot).' </td>
				</tr>';
				if( $pay_type == '' ) {
					$pay_type = 'Online';
				}
				$contract[$k].='<tr>
					<td align="left" height="40"><strong>Mode of Payment </strong></td>
					<td colspan="3" align="left" height="25">'.$pay_type.'</td>
				</tr>';

				if( $pay_type != 'Online') {
					$bank = $value['bank'];
					$contract[$k].='<tr>
						<td  align="left" height="40"><strong>Cheque / DD No.</strong></td>
						<td colspan="" align="left" height="25">'.$cheque.'</td>
						<td  align="left" height="25"><strong>Date</strong></td>
						<td colspan="" align="left" height="25">'.$pdate.'</td>
					</tr>';
					$contract[$k].='<tr>				
						<td  align="left" height="40"><strong>Bank</strong></td>
						<td colspan="" align="left" height="25">'.$bank.'</td>
						<td  align="left" height="25"><strong>Branch</strong></td>
						<td colspan="" align="left" height="25"></td>
					</tr>';
					$contract[$k].='<tr>
						<td  align="left" height="40"><strong>Cashier / Manager</strong></td>
						<td colspan="" align="left" height="25"></td>
						<td  align="left" height="40"><strong>Signature of Remitter</strong></td>
						<td colspan="" align="left" height="25"></td>
					</tr>';
				}
				
				$contract[$k].='</table>'; 			
	    }	
    }
    // print_r($contract);
    $data = implode('<br/>',$contract);

    // print_r($data);exit;

	include_once(BASEPATH."plugins/pdf.class.php");
	$pdf= new pdf() ; 

	$challanID = str_replace('/', '', trim($challanno));
	$pdf->convert($data,$documentPath.$challanID.".pdf",1);

	header("Location:studetscr.php");
 }

  function getCurrencyInWords($number)
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
            $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
            $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
            $str [] = ($number < 21) ? $words[$number].' '. $digits[$counter]. $plural.' '.$hundred:$words[floor($number / 10) * 10].' '.$words[$number % 10]. ' '.$digits[$counter].$plural.' '.$hundred;
        } else $str[] = null;
    }
    $Rupees = implode('', array_reverse($str));
    $paise = ($decimal) ? "." . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . ' Paise' : '';
    return ($Rupees ? strtoupper($Rupees) . 'ONLY ' : '') . $paise ;
}

?>
