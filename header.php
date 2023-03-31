<?php
	require_once('config.php');	
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo SITENAME; ?></title>	
	<link href="<?php echo BASEURL;?>images/favicon.ico" rel="shortcut icon">

	<!-- Jquery -->
	<script src="<?php echo BASEURL;?>js/jquery.min.js"></script>

	<!-- Bootstrap -->
	<link rel="stylesheet" href="<?php echo BASEURL;?>css/bootstrap.min.css" />
	<script src="<?php echo BASEURL;?>js/bootstrap.min.js" type="text/javascript" async defer></script>

	<!-- fontawesome -->
	<link rel="stylesheet" href="<?php echo BASEURL;?>css/font-awesome.min.css" />	

	<?php
		$page = $_SERVER['REQUEST_URI'];
		// $page = explode("/",$url);
		if ( strpos($page, 'editStudent') !== false ||strpos($page, 'addcomments') !== false ||strpos($page,'editcomments')!== false || strpos($page, 'feeentryreport') !== false ||strpos($page, 'paymentreport') !== false ||strpos($page, 'studetscr') !== false  ||strpos($page, 'editcreatedchallans') !== false || strpos($page, 'editstudent') !== false || strpos($page, 'addtax') !== false || strpos($page, 'edittax') !== false || strpos($page, 'cheque_dd') !== false || strpos($page, 'nonfeechallancreation') !== false || strpos($page, 'feechallancreation') !== false || strpos($page, 'manageduedate') !== false ||strpos($page, 'editnonfeecreatedchallans') !== false ||strpos($page, 'splitreport') !== false || strpos($page, 'addpayment') !== false || strpos($page, 'editotherfee') !== false || strpos($page, 'paidreport') !== false || strpos($page, 'advancereport') !== false || strpos($page, 'manageotherfees') !== false || strpos($page, 'addpartialpayment') !== false || strpos($page, 'managepartialpayment') !== false || strpos($page, 'editpartialpayment') !== false || strpos($page, 'createtransportfee') !== false || strpos($page, 'managetransportreport') !== false || strpos($page, 'addtransportfee') !== false || strpos($page, 'edittransportfee') !== false || strpos($page, 'managecartpayment') !== false || strpos($page, 'editcartcheckout') !== false || strpos($page, 'sfsreport') !== false || strpos($page, 'transportdemand') !== false || strpos($page, 'paidreceiptreport') !== false || strpos($page, 'nonfeechallans_pay') !== false || strpos($page, 'commonnf') !== false || strpos($page, 'paidnonfee_report') !== false || strpos($page, 'commonfee_report') !== false || strpos($page, 'nfwcreport') !== false || strpos($page, 'addnfwcpayment') !== false || strpos($page, 'editnfwcpay') !== false || strpos($page, 'createnonfeechallans') !== false || strpos($page, 'addfeetype') !== false || strpos($page, 'editfeetype') !== false || strpos($page, 'dashboard') !== false || strpos($page, 'sfsreportreceiptexport') !== false) {		
	?>
			
		    <link rel="stylesheet" href="<?php echo BASEURL;?>css/jquery-ui.css">
		    <script src="<?php echo BASEURL;?>js/jquery-ui.min.js"></script>
		    <?php
				if (strpos($page, 'feeentryreport') !== false) {		
			?>
			<script src="<?php echo BASEURL;?>js/jquery-1.9.js"></script>
			<?php
			}
			?>
			<script src="<?php echo BASEURL;?>js/jquery-ui-dp.js"></script>
			<script src="<?php echo BASEURL;?>js/moment.js"></script>
		    <link rel="stylesheet" href="<?php echo BASEURL;?>css/datepicker.css">	
		    <script src="<?php echo BASEURL;?>js/datepicker.js"></script>
	<?php
		}
	?>

	

     <!-- MultiSelect -->
    <link rel="stylesheet" type="text/css" href="<?php echo BASEURL;?>css/multiselect.css">
    <script type="text/javascript" charset="utf8" src="<?php echo BASEURL;?>js/multiselect.js"></script>

    <!-- DataTable -->
    <link rel="stylesheet" type="text/css" href="<?php echo BASEURL;?>css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="<?php echo BASEURL;?>js/jquery.dataTables.js"></script>   

    <!-- Custom -->
    <link rel="stylesheet" href="<?php echo BASEURL;?>css/style.min.css" />
    <?php
    	if ( strpos($page, 'admin') !== false ) {
    ?>
		<script src="<?php echo BASEURL;?>admin/js/adminscript.min.js?v=25.1" type="text/javascript" async defer></script>
	<?php
		} else {
	?>	
		<script src="<?php echo BASEURL;?>js/script.min.js?v=22.0" type="text/javascript" async defer></script>
	<?php
		}
	?>
</head>
<body>
	<div class="header">
		<p><img src="<?php echo BASEURL;?>images/logo.png" alt="" class="img-responsive"></p>
		<p class="logo-txt">ONLINE FEE PAYMENT</p>		
	</div>
	<div class="pageloader"></div>