<?php
	include_once("config.php");

	$challanData = sqlgetresult('SELECT c.*, s."studentName" FROM tbl_challans c LEFT JOIN tbl_student s ON s."studentId" = c."studentId" WHERE "feeGroup" = \'10\' ');
	$challan = array();
	// print_r($challanData);
	foreach ($challanData as $k => $data) 
	{       
		$a = array_map('trim', array_keys($data));
		$b = array_map('trim', $data);
		$data = array_combine($a, $b);

	   	$challanData = array();
	   	$challanData['Student Id'] = $data['studentId'];
		$challanData['challan No'] = $data['challanNo'];
		$challanData['Student Name'] = $data['studentName'];
		$challanData['Academic Year'] = getAcademicyrById($data['academicYear']);
		$challanData['Stream'] = getStreambyId($data['stream']);  
		$challanData['Class'] = getClassbyNameId($data['classList']);
		$challanData['Term'] = $data['term'];
		$challanData['feeGroup'] = getFeeGroupbyId($data['feeGroup']);
		$challanData['FeeType']  = getFeeTypebyId($data['feeType']);		
		
		$challanData['Amount Per Qty'] = $data['total'];    
		$challanData['Total Amount'] = $data['org_total'];
		if($data['paid_date'] != '') {
			$challanData['Paid Date'] = $data['paid_date'];
		}		
		    
		$challanData['Cheque Remarks'] = $data['chequeRemarks']; 	
	   
	   	array_push($challan, $challanData);  	   
	}
	// echo count($challan);
	// exit;

	// print_r($challan);exit;
	
	foreach ($challan as $k => $v) {
		$keys = array();
		$values = array();
		foreach ($v as $field_code => $field_val)
		{		
			if($field_val != '') {
				$keys[]=$field_code;				
				// $values[]="'".pg_escape_string($field_val)."'";	
			}		
		}		
		$columns = $keys;		
	}
	// print_r($challan);exit;
	exportData($challan, 'SFS Report', $columns);
?>