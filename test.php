<?php
require_once ('config.php');


print_r("fpvkjaoivjiovpj ntofibvjoitfb");
createNFWPDFtest('IG 1205', 'IGCSE2020NF/00008769');

function createNFWPDFtest($studId,$chlno,$type='') {
    print_r("njnjhnjnjnj");
    exit;

    $getparentmailid = sqlgetresult('SELECT "email" AS parentMailId FROM tbl_student WHERE "studentId"=\''.$studId . '\'');
    $challanData = sqlgetresult('SELECT * FROM nonfeechallandata WHERE "studentId" =\''. $studId . '\' AND  "challanNo" = \'' . $chlno . '\' AND "challanStatus" = 1 AND "visible" = \'0\'',true);
    // print_r('SELECT * FROM nonfeechallandata WHERE "studentId" =\''. $studId . '\' AND  "challanNo" = \'' . $chlno . '\' AND "challanStatus" = 1 AND "visible" = \'0\' ');
    print_r($challanData);
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
    print_r($contract);
    $data = implode('<br/>',$contract);

    print_r($data);exit;

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
?>
