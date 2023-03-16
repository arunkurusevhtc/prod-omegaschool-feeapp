<?php
	include_once("config.php");

	$challanData = sqlgetresult('SELECT * FROM tbl_temp_challans_backup');
	$challan = array();
	// print_r($challanData);
	foreach ($challanData as $k => $data) 
	{       
		$a = array_map('trim', array_keys($data));
		$b = array_map('trim', $data);
		$data = array_combine($a, $b);

	   $fee = explode(',',$data['feeTypes']); 
	   $fee = array_filter($fee);
	   
	   if(count($fee) > 0 ) {
	   		foreach ($fee as $key => $v) 
		   {
		   		$feeTypes = sqlgetresult('SELECT * FROM getfeetypedata WHERE semester = \''.$data['term'].'\' AND class = \''.$data['classList'].'\' AND stream = \''.$data['stream'].'\'',true);
		   		
		        foreach($feeTypes as $val) 
		        {	        	
		          	if(trim($v) == trim($val['feeType']) ) 
			        {
						$challanData = array();
						// $challanData['id'] = $data['id'];
						$challanData['challanNo'] = $data['challanNo'];
						$challanData['studentId'] = $data['studentId'];
						if ($data['feeGroup'] == 'LATE FEE') {
						 	$challanData['feeGroup'] = 0;
						} else {
						 	$challanData['feeGroup'] = $data['feeGroup'];
						}
						$challanData['term'] = $data['term'];
						$challanData['createdOn'] = $data['createdOn'];
						$challanData['createdBy'] = $data['createdBy'];
						// $challanData['updatedOn'] = $data['updatedOn'];   
						$challanData['updatedBy'] = $data['updatedBy']; 
						$challanData['stream'] = $data['stream'];  
						$challanData['classList'] = $data['classList'];
						$challanData['challanStatus'] = $data['challanStatus'];    
						if($data['org_total'] != 0) {
							if($data['waivedTotal'] != 0 ) {
								$n = count(explode(',',$data['feeTypes']));
								$amt = $data['org_total']/$n;
								$challanData['total'] = $val['amount'];  
								// $challanData['org_total'] = $amt;
							} else {
								$challanData['total'] = $val['amount'];    
								// $challanData['org_total'] = $val['amount'];
							}						
						}  else {
							$challanData['total'] = $val['amount'];    
							// $challanData['org_total'] = '0';
						}    
						$challanData['remarks'] = $data['remarks'];
						$challanData['duedate'] = $data['duedate'];    
						// $challanData['pay_type'] = $data['pay_type'];    
						// $challanData['bank'] = $data['bank'];    
						// $challanData['cheque_dd_no'] = $data['cheque_dd_no'];    
						if($data['paid_date'] != '') {
							// $challanData['paid_date'] = $data['paid_date'];
						}

						if($data['updatedOn'] != '') {
							// $challanData['updatedOn'] = $data['updatedOn'];
						}
						 
						// $challanData['waivedPercentage'] = $data['waivedPercentage'];    
						// $challanData['waivedAmount'] = $data['waivedAmount'];    
						// $challanData['waivedTotal'] = $data['waivedTotal'];
						$challanData['academicYear'] = getAcademicyrIdByName($data['academicYear']);    
						// $challanData['chequeRemarks'] = $data['chequeRemarks'];    
						// $challanData['waivedType'] = $data['waivedType']; 
						$challanData['feeType']  = $val['feeType'];
						$challanData['studStatus']  = $data['studStatus'];
			        }
		        }  
		        // print_r($challanData);echo "<hr/>";
		        array_push($challan, $challanData);
		   }
	   } else {
	   		// print_r($data); echo "<hr/>";
	   		$challanData = array();
			// $challanData['id'] = $data['id'];

			$challanData['challanNo'] = $data['challanNo'];
			$challanData['studentId'] = $data['studentId'];
			if ($data['feeGroup'] == 'LATE FEE') {
			 	$challanData['feeGroup'] = '0';
			} else {
			 	$challanData['feeGroup'] = $data['feeGroup'];
			}
			// $challanData['className'] = $data['className'];
			$challanData['term'] = $data['term'];
			// $challanData['section'] = $data['section'];
			$challanData['createdOn'] = $data['createdOn'];
			$challanData['createdBy'] = $data['createdBy'];
			// $challanData['updatedOn'] = $data['updatedOn'];   
			$challanData['updatedBy'] = $data['updatedBy']; 
			$challanData['stream'] = $data['stream'];  
			$challanData['classList'] = $data['classList'];
			$challanData['challanStatus'] = $data['challanStatus'];    
			$challanData['total'] = $data['org_total'];    
			// $challanData['org_total'] = $data['org_total'];  
			// $challanData['streamName'] = $data['streamName'];    
			$challanData['remarks'] = $data['remarks'];
			$challanData['duedate'] = $data['duedate'];    
			// $challanData['pay_type'] = $data['pay_type'];    
			// $challanData['bank'] = $data['bank'];    
			// $challanData['cheque_dd_no'] = $data['cheque_dd_no'];    
			// $challanData['feegroupName'] = $data['feegroupName'];    
			if($data['paid_date'] != '') {
				$challanData['paid_date'] = $data['paid_date'];
			}

			if($data['updatedOn'] != '') {
				// $challanData['updatedOn'] = $data['updatedOn'];
			}
			 
			// $challanData['waivedPercentage'] = $data['waivedPercentage'];    
			// $challanData['waivedAmount'] = $data['waivedAmount'];    
			// $challanData['waivedTotal'] = $data['waivedTotal'];
			$challanData['academicYear'] = getAcademicyrIdByName($data['academicYear']);    
			// $challanData['chequeRemarks'] = $data['chequeRemarks'];    
			// $challanData['waivedType'] = $data['waivedType']; 
			// $challanData['studentName'] = $data['studentName'];    
			// $challanData['parentName'] = $data['parentName'];
			// $challanData['adminName'] = $data['adminName']; 
			$challanData['feeType']  = '0';
			// $challanData['feetypeAmount']  = $val['amount'];
			$challanData['studStatus']  = $data['studStatus'];
			array_push($challan, $challanData);
	   }	   	   
	}
	echo count($challan);
	// exit;

	// print_r($challan);exit;
	
	foreach ($challan as $k => $v) {
		$keys = array();
		$values = array();
		foreach ($v as $field_code => $field_val)
		{		
			if($field_val != '') {
				$keys[]=$field_code;				
				$values[]="'".pg_escape_string($field_val)."'";	
			}		
		}

		$keys = '"'.implode('","', $keys).'"';
		$values = implode(",",$values);
		$sql = sqlgetresult('INSERT INTO tbl_temp_challans ('.$keys.') VALUES ('.$values.')') ; 	
		// print_r($sql);echo "<hr/>";
	}
	// print_r($challan);exit;
	// exportData($challan, 'Demand Report', $columns);
?>