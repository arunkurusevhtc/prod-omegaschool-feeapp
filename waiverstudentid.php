<?php
require_once ('config.php');
$getwaiverdata = sqlgetresult("SELECT * FROM tbl_waiver");

$studentidarray = array();
foreach($getwaiverdata AS $waiver){

	if($waiver['studentId'] == ''){
		$findstudentid = sqlgetresult('SELECT "studentId" FROM tbl_challans WHERE "challanNo" = \'' . trim($waiver['challanNo']) . '\'',true);
		$studentIdUpdate = sqlgetresult('UPDATE tbl_waiver SET "studentId" = \''.$findstudentid[0]['studentId'].'\' WHERE "challanNo" =\''. trim($waiver['challanNo']) .'\' returning 1 as statusupdate ');
		if($studentIdUpdate['statusupdate'] != 1){
			print_r("Not Done");
			echo("<hr/>");
		}
		else{
			print_r("Done");
			echo("<hr/>");
		}
	}
}
?>