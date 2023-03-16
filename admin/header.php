<?php
	require_once('config.php');	
?>

<!DOCTYPE html>
<html lang="">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php echo SITENAME; ?></title>	
	<link href="<?php echo BASEURL;?>images/favicon.ico" rel="shortcut icon">

	<!-- Jquery -->
	<script src="<?php echo BASEURL;?>js/jquery.min.js"></script>

	<!-- Bootstrap -->
	<link rel="stylesheet" href="<?php echo BASEURL;?>css/bootstrap.min.css" />
	<script src="<?php echo BASEURL;?>js/bootstrap.min.js" async defer></script>

	<!-- fontawesome -->
	<link rel="stylesheet" href="<?php echo BASEURL;?>css/font-awesome.min.css" />	

	<?php
		$page = $_SERVER['REQUEST_URI'];
		// $page = explode("/",$url);
		if ( strpos($page, 'editStudent') !== false || strpos($page, 'feeentryreport') !== false ||strpos($page, 'paymentreport') !== false ||strpos($page, 'studetscr') !== false  ||strpos($page, 'editcreatedchallans') !== false || strpos($page, 'editstudent') !== false ) {		
	?>
			<!-- Datepicker -->	
		    <link rel="stylesheet" href="<?php echo BASEURL;?>css/jquery-ui.css">
		    <script src="<?php echo BASEURL;?>js/jquery-ui.min.js"></script>
		    <?php
				if (strpos($page, 'feeentryreport') !== false ||strpos($page, 'paymentreport') !== false ) {		
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
    <script  src="<?php echo BASEURL;?>js/multiselect.js"></script>

    <!-- DataTable -->
    <link rel="stylesheet" type="text/css" href="<?php echo BASEURL;?>css/jquery.dataTables.css">
    <script src="<?php echo BASEURL;?>js/jquery.dataTables.js"></script>   

    <!-- Custom -->
    <link rel="stylesheet" href="<?php echo BASEURL;?>css/style.css" />
	<script src="<?php echo BASEURL;?>js/script.js"  async defer></script>	
</head>
<body>
	<div class="header">
		<p><img src="<?php echo BASEURL;?>images/logo.png" alt="" class="img-responsive"></p>
		<p class="logo-txt">ONLINE FEE PAYMENT FOR EXISTING STUDENTS</p>		
	</div>