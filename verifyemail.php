<?php
	require_once('config.php');
	include_once('header.php');

	$verify_code = pg_escape_string($dbconn,$_GET['k']);
	$chkverify = sqlgetresult("SELECT * FROM verifyCode('$verify_code')");
	// print_r($verify_code);

	if($verify_code == 0 ){
		$msg = '<p class="verified">Your Email Already Verified Successfully</p><br/><br/><a href="'.BASEURL.'login.php" >Click Here to Login</a>';
	} else {
		if($chkverify['verifycode'] == 1) {
			$msg = '<p class="verified">Your Email Verified Successfully</p><br/><br/><a href="'.BASEURL.'login.php" >Click Here to Login</a>';
		} else {
			$msg = '<p class="expired">Your Verification Code Has Been Expired</p><br/><br/><a href="'.BASEURL.'resendconfirm.php" >Click Here to Resend Confirm Details.</a>';
		}
	}	
	
?>

<h1 class="verifyemail">
	<?php echo $msg; ?>
</h1>

<?php
	include_once('footer.php');
?>