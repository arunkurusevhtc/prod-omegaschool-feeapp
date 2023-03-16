<?php
	include_once('config.php');	

	$findOldWaiver  = sqlgetresult('SELECT * FROM tbl_challans WHERE "waivedTotal" <> 0');

	$waiver = array();

	foreach ($findOldWaiver as $key => $oldwaiver) {
		$waiver[$oldwaiver['challanNo']][$oldwaiver['feeGroup']]['studentId'] = $oldwaiver['studentId'];
		$waiver[$oldwaiver['challanNo']][$oldwaiver['feeGroup']]['challanNo'] = $oldwaiver['challanNo'];
		$waiver[$oldwaiver['challanNo']][$oldwaiver['feeGroup']]['waiver_type'][] = $oldwaiver['waivedType'];
		$waiver[$oldwaiver['challanNo']][$oldwaiver['feeGroup']]['waiver_percentage'][] = $oldwaiver['waivedPercentage'];
		$waiver[$oldwaiver['challanNo']][$oldwaiver['feeGroup']]['waiver_amount'][] = $oldwaiver['waivedAmount'];
		$waiver[$oldwaiver['challanNo']][$oldwaiver['feeGroup']]['waiver_total'][] = $oldwaiver['waivedTotal'];
		$waiver[$oldwaiver['challanNo']][$oldwaiver['feeGroup']]['total'][] = $oldwaiver['total'];
		$waiver[$oldwaiver['challanNo']][$oldwaiver['feeGroup']]['feeGroup'][] = $oldwaiver['feeGroup'];
	}

	// print_r($waiver);
	// exit;
	$waivedData = array();

	foreach ($waiver as $data) {
		foreach ($data as $value) {
			$value['feeGroup'] = array_unique($value['feeGroup'])[0];
			$value['waiver_type'] = array_unique($value['waiver_type'])[0];
			$value['waiver_percentage'] = array_unique($value['waiver_percentage'])[0];
			$value['waiver_amount'] = array_unique($value['waiver_amount'])[0];
			$value['waiver_total'] = array_unique($value['waiver_total'])[0];
			if($value['feeGroup'] != 0) {
		 		$value['total'] = array_sum($value['total']);
		 	} else {
		 		$value['total'] = $value['waiver_total'];
		 	}
			$waivedData[] = $value;
		}	
			
	}

	foreach ($waivedData as $v) {
		$sql = sqlgetresult('INSERT INTO tbl_waiver ("studentId","challanNo","waiver_type", "waiver_percentage", "waiver_amount", "waiver_total", "createdOn", "createdBy", "total", "feeGroup") VALUES (\''.$v['studentId'].'\', \''.$v['challanNo'].'\', \''.$v['waiver_type'].'\', \''.$v['waiver_percentage'].'\', \''.$v['waiver_amount'].'\', \''.$v['waiver_total'].'\', CURRENT_TIMESTAMP, \'1\', \''.$v['total'].'\', \''.$v['feeGroup'].'\') returning id as rowid ');
		print_r($sql);echo "<hr/>";
	}
			echo(sizeof($waivedData));

?>