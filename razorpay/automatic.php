<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<form name='razorpayform' action="verify_rz_payments.php" method="POST">
    <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
    <input type="hidden" name="razorpay_signature"  id="razorpay_signature" >
    <input type="hidden" name="invoice_id" value="<?php echo $invoice_id; ?>">
    <input type="hidden" name="ptype" value="<?php echo $ptype; ?>">
    <input type="hidden" name="student_id" value="<?php echo $st_id; ?>">
    <input type="hidden" name="parent_id" value="<?php echo $parnt_id; ?>">
</form>

<script>
// Checkout details as a json
var options = <?php echo $data_json; ?>;
/**
 * The entire list of Checkout fields is available at
 * https://docs.razorpay.com/docs/checkout-form#checkout-fields
 */
options.handler = function (response){
    document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;
    document.getElementById('razorpay_signature').value = response.razorpay_signature;
    document.razorpayform.submit();
};
// Boolean whether to show image inside a white frame. (default: true)
options.theme.image_padding = false;
options.modal = {
    ondismiss: function() {
        console.log("This code runs when the popup is closed");
        window.location = '<?php echo $curl; ?>';
    },
    // Boolean indicating whether pressing escape key 
    // should close the checkout form. (default: true)
    escape: true,
    // Boolean indicating whether clicking translucent blank
    // space outside checkout form should close the form. (default: false)
    backdropclose: false
};
var rzp = new Razorpay(options);
rzp.open();
</script>
