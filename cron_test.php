<?php
	include_once("config.php");
	$insertsql = sqlgetresult('INSERT INTO tbl_old_challans SELECT * FROM tbl_challans WHERE DATE_PART(\'day\', CURRENT_TIMESTAMP - "updatedOn") > \'14\' ');	
	$deletesql = sqlgetresult('DELETE FROM tbl_challans WHERE DATE_PART(\'day\', CURRENT_TIMESTAMP - "updatedOn") > \'14\' ');
	// echo $sql;
?>