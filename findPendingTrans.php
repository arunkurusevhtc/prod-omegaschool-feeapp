<?php
	include_once("config.php");

	$challanData = sqlgetresult('SELECT payment_url FROM tbl_payments WHERE "transStatus" IS NULL ');
	// $challan = array();
	$paidStudents = array();

	foreach ($challanData as $k => $data) 
	{     
		$paymentData = explode('&',$data['payment_url']);
		$studentId = explode("=",$paymentData[8])[1];
		$studentId = str_replace('%3D', '', $studentId);
		$studentId = base64_decode($studentId);

		$paidStudents[] = $studentId;		
	}

	$i =0;

	foreach ($paidStudents as $k=>$v) {
		$findChallan = sqlgetresult('SELECT "studentId","challanNo" FROM tbl_challans WHERE "studentId" = \''.$v.'\' AND "challanStatus" = \''.$i.'\' GROUP BY "studentId","challanNo" ');
		// print_r($findChallan) ; echo "<hr/>";
		if(sizeof($findChallan) > 0){
			$challan[$k]['ChallanNo'] = $findChallan['challanNo'];
			$challan[$k]['StudentId'] = $findChallan['studentId'];
		}
		// $challan[$k]['Remarks'] = $findChallan['remarks'];
		// $challan[$k]['TransactionStatus'] = $findChallan['transStatus'];
	}
	// print_r($challan);
	$columns = array('ChallanNo','StudentId');
	exportData($challan, 'Pending Payment Report', $columns);	
?>