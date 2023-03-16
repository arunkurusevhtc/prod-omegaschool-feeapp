<?php
	require_once("config.php");
	
	// $SFSdata = sqlgetresult('SELECT c.* FROM tbl_challans c LEFT JOIN tbl_sfs_qty q ON c."studentId" = q."studentId" WHERE c."feeGroup" = 10 AND q."studentId" IS NULL ', true);
	// // print_r($SFSdata);
	// echo count($SFSdata);

	// foreach ($SFSdata as $key => $value) {
	// 	// print_r($value);
	// 	$perfeeamt = getSFSandSchoolFeeByFeeId($value['feeType'], $value['classList'], $value['academicYear'], $value['term']);
	// 	// echo $data;
	// 	$qty = $value['org_total']/$perfeeamt;
	// 	$insert = 'INSERT INTO tbl_sfs_qty("challanNo", "feeTypes", amount, quantity, "totalAmount", "createdBy", "createdOn","studentId") VALUES (\''.$value['challanNo'].'\', \''.$value['feeType'].'\', \''.$perfeeamt.'\', \''.$qty.'\', \''.$value['org_total'].'\', \'1\', CURRENT_TIMESTAMP, \''.$value['studentId'].'\')';
	// 	// sqlgetresult($insert);
	// 	echo $insert;echo "<hr/>";
	// }
	$ID = 'IGCSE2019/059908';
	$SFSPartial = sqlgetresult('SELECT * FROM tbl_challans c WHERE NOT EXISTS (SELECT FROM tbl_sfs_qty WHERE  "studentId" = c."studentId" AND "challanNo" = c."challanNo" AND "feeTypes"::int = c."feeType") AND "feeGroup" = 10 ');
	print_r(count($SFSPartial));

	foreach ($SFSPartial as $key => $value) {
		// print_r($value);echo "<hr/>";
		$perfeeamt = getSFSandSchoolFeeByFeeId($value['feeType'], $value['classList'], $value['academicYear'], $value['term']);
		$qty = $value['org_total']/$perfeeamt;
		$insert = 'INSERT INTO tbl_sfs_qty("challanNo", "feeTypes", amount, quantity, "totalAmount", "createdBy", "createdOn","studentId") VALUES (\''.$value['challanNo'].'\', \''.$value['feeType'].'\', \''.$perfeeamt.'\', \''.$qty.'\', \''.$value['org_total'].'\', \'1\', CURRENT_TIMESTAMP, \''.$value['studentId'].'\')';
		// sqlgetresult($insert);
		echo $insert;echo "<hr/>";
	}
?>