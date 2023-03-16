<?php
	require_once ('../config.php');

	$k = 'APPL2018MONT/000908';

	$challanDataa = sqlgetresult('SELECT c."studentId",c."challanNo",c."createdOn",c."updatedOn",c."classList",c."duedate",c."term",s."studentName",s."section",c."feeType",c."org_total", c."stream",c."academicYear", s."gender", c."challanStatus" FROM tbl_challans c LEFT JOIN tbl_student s ON s."studentId" = c."studentId" OR s."application_no" = c."studentId" WHERE c."feeGroup" = 10 AND c."feeType" != ALL (\'{16,17,18,19}\'::int[])  ORDER BY c.id ASC' , true);
	$challan = array();

	$SFSFeeTypes = sqlgetresult('SELECT "feeType" FROM tbl_fee_type WHERE "feeGroup" = \'10\' ');
	$SFSFeeTypes = array_column($SFSFeeTypes, "feeType");

	$SFSFeeData = array();

	foreach ($SFSFeeTypes as $sfs) {
		$SFSFeeData[] = $sfs.' Qty';
		$SFSFeeData[] = $sfs.' Amount';
	}

	// print_r($challanDataa); echo "<hr/>";

	$columns = array('S.No','VCH Type','Demand Date','ID','Name','Gender','Class','Section','Due Date','Academic Year','Term','Invoice Ref. No','Invoice Amount',"Paid Status");
	$columns = array_merge($columns,$SFSFeeData);
	$challanData = array();
	$i =0;

	foreach ($challanDataa as $k => $data) 
	{    
		$a = array_map('trim', array_keys($data));
		$b = array_map('trim', $data);
		$data = array_combine($a, $b);		   	
	   	
	   	$challanData['VCH Type'] = '-';
	   	if($data['createdOn'] != '') {
	   		$challanData['Demand Date'] = date('d-m-Y',strtotime($data['createdOn']));	
	   	} else {
	   		$challanData['Demand Date'] = date('d-m-Y',strtotime($data['updatedOn']));	
	   	}
	   	   
	   	$challanData['ID'] = trim($data['studentId']);
	   	$challanData['Name'] = $data['studentName'];
	   	if($data['gender'] == 'M'){
	   	$challanData['Gender'] = "Male";
	   	}
	   	elseif ($data['gender'] == 'F'){
	   	$challanData['Gender'] = "Female";	
	   	}
	   	else{
   		$challanData['Gender'] = " ";	
	   	}
	   	$challanData['Class'] = getClassbyNameId($data['classList']);
	   	$challanData['Section'] = $data['section'];
	   	$challanData['Due Date'] = date("d-m-Y", strtotime($data['duedate']));
	   	$challanData['Academic Year'] = getAcademicyrById($data['academicYear']);
	   	$challanData['Term'] = $data['term'];
		$challanData['Invoice Ref. No'] = $data['challanNo'];
		if($data['challanStatus'] == 1){
	   	$challanData['Paid Status'] = "Paid";
	   	}
	   	else{
	   	$challanData['Paid Status'] = "Not Paid";	
	   	}		
		$feeType['name'] = trim(getFeeTypebyId($data['feeType']));	
		$feeType['id'] = trim($data['feeType']);

		foreach ($SFSFeeTypes as $v) {
			$qty = $v.' Qty';
			$amt = $v.' Amount';
			if ( trim($v) == $feeType['name'] ) {
				$perQtyAmt = getSFSandSchoolFeeByFeeId($feeType['id'], $data['classList'], $data['academicYear'], $data['term']);
				$SFSFee[$qty][] = $data['org_total']/$perQtyAmt;
				$SFSFee[$amt][] = $data['org_total'];
			} else {
				$SFSFee[$qty][] = '0';
				$SFSFee[$amt][] = '0';
			}
		}

		if( $data['challanNo'] != $challanDataa[$k+1]['challanNo'] ) {
			$i++;
			$challanData['S.No'] = $i;
			$total = 0;
			foreach ($SFSFee as $fee => $val) {
				$challanData[$fee] = array_sum($val);
				if (stripos($fee,'amount')) {
					$total += array_sum($val);
				}				
			}
			$challanData['Invoice Amount'] = $total;
			array_push($challan, $challanData);
			$SFSFee = array();			
		}	   	   
	}
	// print_r($challan);
	// exit;
	$columns = array_unique($columns);

	exportData($challan, 'SFS Report', $columns);
?>