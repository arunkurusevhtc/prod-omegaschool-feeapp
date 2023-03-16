<?php
	require_once("config.php");

	$fee_group_data = serialize(sqlgetresult("SELECT * FROM tbl_fee_group"));
	$fee_type_data = serialize(sqlgetresult("SELECT * FROM tbl_fee_type"));
	$fee_config_data = serialize(sqlgetresult("SELECT * FROM tbl_fee_configuration"));
	$challan_data = serialize(sqlgetresult("SELECT * FROM tbl_challans"));

	if (!is_dir(BASEPATH."audit_logs/".date('dmY'))) {
		mkdir(BASEPATH."audit_logs/".date('dmY'));
	}

	$grpfile = fopen(AUDITLOGPATH."\/".date('dmY')."/fee_group_audit_log.txt", "w") or die("Unable to open file!");		
	fwrite($grpfile, $fee_group_data);	
	fclose($grpfile);

	$typfile = fopen(AUDITLOGPATH."\/".date('dmY')."/fee_type_audit_log.txt", "w") or die("Unable to open file!");		
	fwrite($typfile, $fee_type_data);	
	fclose($typfile);

	$configfile = fopen(AUDITLOGPATH."\/".date('dmY')."/fee_config_audit_log.txt", "w") or die("Unable to open file!");		
	fwrite($configfile, $fee_config_data);	
	fclose($configfile);

	$challanfile = fopen(AUDITLOGPATH."\/".date('dmY')."/challan_audit_log.txt", "w") or die("Unable to open file!");		
	fwrite($challanfile, $challan_data);	
	fclose($challanfile);

	/*** To Recover Data ***/

	// $recoveredData = file_get_contents(AUDITLOGPATH."\/".date('dmY')."/fee_group_audit_log.txt");	
	// $recoveredArray = unserialize($recoveredData);
	// print_r($recoveredArray);
?>