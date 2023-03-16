<?php
require('config.php');
require('razorpay/Razorpay.php');
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;

$success = true;
$error = "Payment Failed";
date_default_timezone_set('Asia/Calcutta');
$razorpay_payment_id = isset($_POST['razorpay_payment_id'])?trim($_POST['razorpay_payment_id']):"";
$razorpay_signature = isset($_POST['razorpay_signature'])?trim($_POST['razorpay_signature']):"";
$invoice_id = isset($_POST['invoice_id'])?trim($_POST['invoice_id']):"";
$ptype = isset($_POST['ptype'])?trim($_POST['ptype']):"";
$student_id = isset($_POST['student_id'])?trim($_POST['student_id']):"";
$parent_id = isset($_POST['parent_id'])?trim($_POST['parent_id']):"";
$payment_id="";
$razorpay_order_id="";
if($ptype=='cartpayments'){
    if (strpos($invoice_id, 'CART') !== false){
        $payment_id=str_replace("CART-", "", $invoice_id);
         $cart = sqlgetresult('SELECT razorpayorderid FROM tbl_cart_payment_log WHERE id = \''.$payment_id.'\'', true);
         $num=count($cart);
         if($num > 0){
            $razorpay_order_id=$cart[0]['razorpayorderid'];
        }
    }
}
$chkPayData=[];
$numchl=0;
if($ptype=='challanpayments'){
    if (strpos($invoice_id, 'REF') !== false){
        $payment_id=str_replace("REF", "", $invoice_id);
         $chkPayData = sqlgetresult('SELECT * FROM tbl_partial_payment_log WHERE id = \''.$payment_id.'\'', true);
         $numchl=count($chkPayData);
         if($numchl > 0){
            $razorpay_order_id=$chkPayData[0]['razorpayorderid'];
        }
    }
}

if (empty($razorpay_payment_id) === false)
{
    $api = new Api(RAZOR_KEY_ID, RAZOR_KEY_SECRET);
    try
    {
        // Please note that the razorpay order ID must
        // come from a trusted source (session here, but
        // could be database or something else)
        $attributes = array(
            'razorpay_order_id' => $razorpay_order_id,
            'razorpay_payment_id' => $razorpay_payment_id,
            'razorpay_signature' => $razorpay_signature
        );

        $api->utility->verifyPaymentSignature($attributes);
    }
    catch(SignatureVerificationError $e)
    {
        $success = false;
        $error = 'Razorpay Error : ' . $e->getMessage();
    }
}

$trans_id='NA';
$createdOn=date("Y-m-d");
if ($success === true)
{
    $f_code='Ok';
    $desc = "Your payment was successful. Payment ID: {$razorpay_payment_id}";
    $trans_id=$razorpay_payment_id;
}
else
{
    $f_code='F';
    $desc = "Your payment failed. {$error}";
}


if($ptype=='cartpayments'){
    if(empty($payment_id) === false){
         $paymentData = sqlgetresult("SELECT * FROM cartpaymententrynew('$f_code','".json_encode($_POST)."','".$desc."','".$trans_id."','$createdOn','".$parent_id."','".$payment_id."') ");
        if($paymentData['cartpaymententrynew']){
            cartUpdateStatus($paymentData['cartpaymententrynew'], $student_id);
            if($f_code == 'Ok') { 
                $_SESSION['success_msg'] = "<p class='success-msg'>Payment Completed Successfully.</p>";
            } else {
                 createErrorlog(json_encode($_POST));
                 $_SESSION['error_msg'] = "<p class='error-msg'>".$desc." Please try again later.</p>";
            }
        }else{
            createErrorlog(json_encode($_POST),"Something gone wrong.",1);
            $_SESSION['error_msg'] = "<p class='error-msg'>Something gone wrong. Please try again later.</p>";
        }
    }else{
        createErrorlog(json_encode($_POST),"Something gone wrong.",1);
        $_SESSION['error_msg'] = "<p class='error-msg'>Unable to find the payment id. Please try again later.</p>";
    }
    header("Location: cartcheckout.php");
    exit;
}


if($ptype=='challanpayments' && $numchl >0){
    if(!empty($payment_id)){
        $challanNo = "REF".$payment_id;
        $grand_tot = trim($chkPayData[0]['grandtotal']);
        $challanids = trim($chkPayData[0]['challanids']);
        $balance = trim($chkPayData[0]['balance']);
        $amount=trim($chkPayData[0]['receivedamount']);

        $caution_json = ($chkPayData[0]['partial_caution_deposit'])?trim($chkPayData[0]['partial_caution_deposit']):"";
        $payoption = ($chkPayData[0]['payoption'])?trim($chkPayData[0]['payoption']):"";

        $parentData = sqlgetresult('SELECT * FROM getparentdata WHERE "studentId" = \''.$student_id.'\' LIMIT 1',true);
        $acad_year = trim($parentData[0]['academic_yr']);
        $term = trim($parentData[0]['term']);
        $stream = trim($parentData[0]['stream']); 
        $sid = $parentData[0]['sid'];
        $class = trim($parentData[0]['class']);
        $section = trim($parentData[0]['section']);
        $parent_id = $parentData[0]['id'];

        $paymentData = sqlgetresult("SELECT * FROM partialtransactionentry('$amount','$f_code','".json_encode($_POST)."','".$desc."','".$trans_id."','$createdOn','".$parent_id."','".$payment_id."') ");
            if($f_code == 'Ok') {
                /* caution deposit partial */
                if(!empty($caution_json) && $payoption=='caution'){
                    $chkcaution = toUpdateFeeTypePaidAmt($caution_json);
                }
                /* Update Balance Start */
                if(isset($balance) && !empty($balance)){
                    $curbalance=toGetAvailableBalance($sid);
                    $upewal=$curbalance-$balance;
                    $wallet = sqlgetresult("SELECT * FROM addAdvanceAmt('".$sid."','$upewal','".$parent_id."')");

                    $resadv=$wallet['addadvanceamt']?$wallet['addadvanceamt']:"";
                    if(!empty($resadv)){
                        $run1=sqlgetresult("SELECT * FROM createadvancetransaction('$sid','$parent_id','','$balance','$curbalance','2','Ok','$payment_id','$challanNo','1','$class','$acad_year','$stream','$term','$section')",true);
                    }
                    $amount=$amount+$balance;
                }
                $receivedAmt=$amount+$balance;
                partialPayProcessModified($challanids, $sid, $parent_id, $student_id, $term, $acad_year, $amount, $payment_id, $createdOn, $balance);
                if(!empty($caution_json) && $payoption=='caution'){
                    toCheckFeeTypePartial($caution_json);
                }
                $_SESSION['success_msg'] = "<p class='success-msg'>Payment Completed Successfully.</p>";
            } else {
                 createErrorlog(json_encode($_POST));
                 $_SESSION['error_msg'] = "<p class='error-msg'>".$desc." Please try again later.</p>";
            }
    }else{
        createErrorlog(json_encode($_POST),"Something gone wrong.",1);
        $_SESSION['error_msg'] = "<p class='error-msg'>Unable to find the payment id. Please try again later.</p>";
    }
    header("Location: checkout.php");
    exit;
}
