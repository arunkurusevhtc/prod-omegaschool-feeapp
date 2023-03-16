<?php
	 require_once 'Razorpay.php';
	 use Razorpay\Api\Api;

	 $rzpapi = new Api(RAZOR_KEY_ID, RAZOR_KEY_SECRET);
	 $orderData = [
		'receipt' => $raz_data["receipt"],
		'amount' =>  $raz_data["amount"] * 100,
		'currency'=> $raz_data["display_currency"],
		'payment_capture' => 1
	 ];

		if(count($raz_data['transfers']) > 0){
			$orderData['transfers']=$raz_data['transfers'];
		}
	 $razorpayOrder = $rzpapi->order->create($orderData);
	 $razorpayOrderId = isset($razorpayOrder['id'])?$razorpayOrder['id']:"";
	 if($razorpayOrderId){
	 	$_SESSION['razorpay_order_id']=$razorpayOrderId;
	 	$data_ary = [
			 "key"  => $raz_data["key"],
			 "amount"=> $orderData['amount'],
			 "name" => "Omega School FeeAPP",
			 "description"  => $raz_data['description'],
			 "image" => "https://qa.omegaschools.org/feeapp/images/razorpay_logo.png",
			 "prefill"  => [
				 "name" => $raz_data['name'],
				 "email" => $raz_data['email'],
				 "contact"  => $raz_data['contact']
			 ],
			 "notes" => $raz_data['notes'],
			 "theme" => ["color" => "#5181f0"],
			 "order_id"  => $razorpayOrderId,
			 "display_currency"  => $raz_data['display_currency'],
			 "display_amount"  => $raz_data['amount']
	    ];

	    $invoice_id=$raz_data["receipt"];

	    $surl="verify.php";
	    $curl="cartcheckout.php";
	    $ptype="cartpayments";

	    $data_json = json_encode($data_ary);
	    require_once("automatic.php");
	    exit;
	 }else{
		$_SESSION['error_msg']="<p class='error-msg'> Some Error has occured. Try again later.</p>";
	 }


?>
