<?php
	require_once ('../config.php');

	ini_set('max_execution_time', 600);

	$_POST = array_map('trim',$_POST);
    $classselect = isset($_POST['classselect'])?$_POST['classselect']:"";
    $streamselect = isset($_POST['streamselect'])?$_POST['streamselect']:"";
    $yearselect = isset($_POST['yearselect'])?$_POST['yearselect']:"";
    $semesterselect = isset($_POST['semesterselect'])?$_POST['semesterselect']:"";
    $stid = isset($_POST['stid'])?$_POST['stid']:"";
    $stats = isset($_POST['tstatus'])?$_POST['tstatus']:"";
    //$type = isset($_POST['type'])?$_POST['type']:1;
    $from = isset($_POST['from'])?$_POST['from']:"";
    $to = isset($_POST['to'])?$_POST['to']:"";


    $whereClauses = array();
    $where = ''; 

    $isStudIdSearch=false;

    if(!empty($stid)){
        $whereClauses[] ='"studentId"=\''.pg_escape_string($stid).'\' ' ;
        $isStudIdSearch=true;
    }
    
    if($stats){
        if($stats==3){
          $whereClauses[] ='"challanStatus"=\'0\' ' ;
        }else{
          $whereClauses[] ='"challanStatus"=\''.pg_escape_string($stats).'\' ' ;
        }
    }

    if (!empty($yearselect)){
        $whereClauses[] ='"academicYear"=\''.pg_escape_string($yearselect).'\' ' ;
    }

    if (!empty($semesterselect)) {
      $whereClauses[] ="term='".pg_escape_string ($semesterselect)."'"; 
    }

    if (!empty($classselect)) {
        $whereClauses[] ='"classList"=\''.pg_escape_string($classselect).'\' ' ;
    }

    if (!empty($streamselect)) {
      $whereClauses[] ="stream='".pg_escape_string ($streamselect)."'"; 
    }


    if (!empty($from) && !empty($to))
    {
    	$whereClauses[] = 'DATE("createdOn") BETWEEN \'' . pg_escape_string ($from) . '\'  AND  \'' . pg_escape_string ($to) . '\'';
    }
    
    if (count($whereClauses) > 0) 
    { 
      $where = ' WHERE '.implode(' AND ',$whereClauses); 
    } 
 
    if($isStudIdSearch){
        $sql = ('SELECT *,DATE("createdOn") AS created,DATE("updatedOn") AS updated FROM sfsdatareportmodified '.$where.' ORDER BY "challanNo" ASC');
    }else{
        $sql = ('SELECT *,DATE("createdOn") AS created,DATE("updatedOn") AS updated FROM sfsdatareportlatest '.$where.' ORDER BY "challanNo" ASC');
    }
    $challanDataa = sqlgetresult($sql, true);
	$challan = array();
	if($yearselect){
		$ftquery='SELECT DISTINCT ft."feeType" FROM tbl_fee_type ft JOIN tbl_fee_configuration fc ON(ft.id=fc."feeType"::INTEGER)  WHERE ft."feeGroup" = \'10\' AND ft.id NOT IN (16,17,18,19) AND ft."feeType" NOT ILIKE \'%Transport%\' AND  fc."academicYear"=\'' . $yearselect . '\' AND fc.status= \'1\'';
	}else{
		$ftquery='SELECT "feeType" FROM tbl_fee_type WHERE "feeGroup" = \'10\' AND id NOT IN (16,17,18,19) AND "feeType" NOT ILIKE \'%Transport%\' ';
	}

	$SFSFeeTypes = sqlgetresult($ftquery);
	$SFSFeeTypes = array_column($SFSFeeTypes, "feeType");

	$SFSFeeData = array();

	foreach ($SFSFeeTypes as $sfs) {
		$SFSFeeData[] = $sfs.' Qty';
		$SFSFeeData[] = $sfs.' Amount';
	}

	// print_r($challanDataa); echo "<hr/>";

	//$columns = array('S.No','VCH Type','Demand Date','ID','Name','Gender','Class','Stream','Academic Year','Term','Invoice Ref. No','Invoice Amount','Waived Amount','Due Date','Paid Date','Paid Status');
	$columns = array('S.No','VCH Type','Demand Date','ID','Name','Gender','Class','Stream','Academic Year','Term','Invoice Ref. No','Invoice Amount','Waived Amount','Due Date');
	$columns = array_merge($columns,$SFSFeeData);
	$challanData = array();
	$i =0;
	$waived=array();

	if(count($challanDataa) > 0){
		$aYears=getacadamicYears();
        $classes=getClassses();
        $streams=getStreams();
        $students=getStudentDetails();

		foreach ($challanDataa as $k => $data) 
		{    
			$a = array_map('trim', array_keys($data));
			$b = array_map('trim', $data);
			$data = array_combine($a, $b);

			$challanNo=trim($data['challanNo']);
			$fgroupid=trim($data['feeGroup']);
		   	$challanData['VCH Type'] = '-';
		   	if($data['createdOn']) {
		   		$challanData['Demand Date'] = $data['created'];	
		   	} else {
		   		$challanData['Demand Date'] = $data['updated'];	
		   	}

		   	if($data['paid_date']){
                $challanData['Paid Date'] = $data['paid_date'];
            }else{
                if($data['updatedOn']){
                	$challanData['Paid Date'] = $data['updated'];
                }else{
                	$challanData['Paid Date'] = "";
                }
                
            }
		   	$studentId=trim($data['studentId']);

		   	if($isStudIdSearch){
			   	$challanData['ID'] = $studentId;
			   	$challanData['Name'] = trim($data['studentName']);
			   	if($data['gender'] == 'M'){
				   	$challanData['Gender'] = "Male";
			   	}
			   	elseif ($data['gender'] == 'F'){
				   	$challanData['Gender'] = "Female";	
			   	}
			   	else{
			   		$challanData['Gender'] = " ";	
			   	}
		    }else{
				if($students[$studentId]['studentId']){
				    $challanData['ID'] = $students[$studentId]['studentId'];
				    $challanData['Name'] = $students[$studentId]['studentName'];
				    $challanData['Gender'] = $students[$studentId]['gender'];
				}else{
				    $oldStudentId=getStudentDetailsByOldId($studentId);
				    if($oldStudentId){
				        $challanData['ID'] = $students[$oldStudentId]['studentId'];
				        $challanData['Name'] = $students[$oldStudentId]['studentName'];
				        $challanData['Gender'] = $students[$oldStudentId]['gender'];
				    }else{
				        $challanData['ID'] = "";
				        $challanData['Name'] = "";
				        $challanData['Gender'] = "";
				    }
				}
		    }
		   	$challanData['Class'] = $classes[$data['classList']];
		   	$challanData['Stream'] = $streams[$data['stream']];
		   	if($data['duedate']){
		   		$challanData['Due Date'] = date("d-m-Y", strtotime($data['duedate']));
		   	}else{
		   		$challanData['Due Date'] = "";
		   	}
		   	
		   	$challanData['Academic Year'] = $aYears[$data['academicYear']];
		   	$challanData['Term'] = $data['term'];
			$challanData['Invoice Ref. No'] = $challanNo;
			/* Waiver Amount */
			$waiver_total=trim($data['waiver_total']);
            if($waiver_total){
              $challanData['Waived Amount']=$waiver_total;
            }else{
              $challanData['Waived Amount']=0;
            } 
            

			$pstatus=trim($data['challanStatus']);
			if($pstatus == 1){
		   	$challanData['Paid Status'] = "Paid";
		   	}
		   	else if($pstatus == 2){
		   	$challanData['Paid Status'] = "Partial Paid";
		   	}
		   	else{
		   	$challanData['Paid Status'] = "Not Paid";	
		   	}		
			$feeType['name'] = trim($data['feetypename']);	
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

			if( $challanNo != trim($challanDataa[$k+1]['challanNo']) ) {
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
	}
	// print_r($challan);
	// exit;
	$columns = array_unique($columns);

	exportData($challan, 'SFS Report - Export ', $columns);
?>