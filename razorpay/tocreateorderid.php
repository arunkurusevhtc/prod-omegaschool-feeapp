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
	

?>
