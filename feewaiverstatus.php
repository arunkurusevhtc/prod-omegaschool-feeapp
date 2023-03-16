<?php
require_once('config.php');

$waiverchallans = sqlgetresult('SELECT * FROM tbl_waiver');

// print_r($waiverchallans);

foreach($waiverchallans AS $waiverchallan){

	$studentId = $waiverchallan['studentId'];
	$challanNo = $waiverchallan['challanNo'];
	$feeGroup = $waiverchallan['feeGroup'];

	$challanStatus = sqlgetresult('SELECT "challanStatus" FROM tbl_challans WHERE "studentId" = \''. $studentId .'\' AND "challanNo" = \''. $challanNo .'\' AND "feeGroup" = \''.$feeGroup.'\'',true);
	// print_r($challanStatus);

	$updatewaiverstatus = sqlgetresult('UPDATE tbl_waiver SET "challanStatus" = \''.$challanStatus[0]['challanStatus'].'\' WHERE "studentId" = \''. $studentId .'\' AND "challanNo" = \''. $challanNo .'\' AND "feeGroup" = \''.$feeGroup.'\' returning 1 as statusupdate');
	// echo('<hr/>');
	// print_r($updatewaiverstatus);
	// echo('<hr/>');

	if($updatewaiverstatus['statusupdate'] != 1){
			print_r("Not Done");
			echo("<hr/>");
	}
	else{
		print_r("Done");
		echo("<hr/>");
	}
}
?>