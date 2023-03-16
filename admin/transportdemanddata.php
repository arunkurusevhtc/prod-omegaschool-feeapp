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
      //$whereClauses[] = '(DATE("paid_date") BETWEEN \'' . pg_escape_string ($from) . '\'  AND  \'' . pg_escape_string ($to) . '\' OR DATE("updatedOn") BETWEEN \'' . pg_escape_string ($from) . '\'  AND  \'' . pg_escape_string ($to) . '\')';
    	//$whereClauses[] = 'DATE("createdOn") BETWEEN \'' . pg_escape_string ($from) . '\'  AND  \'' . pg_escape_string ($to) . '\'';
    	$whereClauses[] ='DATE("createdOn") BETWEEN \''.trim(pg_escape_string(date("m/d/Y", strtotime($from)))).'\' AND \''.trim(pg_escape_string(date("m/d/Y", strtotime($to)))).'\'' ;
    }
    
    if (count($whereClauses) > 0) 
    { 
      $where = ' WHERE '.implode(' AND ',$whereClauses); 
    } 
   // $sql = ('SELECT * FROM sfsdatareport '.$where);
    //$sql = ('SELECT * FROM sfsdatareportmod '.$where);

    // $sql = ('SELECT * FROM sfsdatareportmodified '.$where);

    if($isStudIdSearch){
        $sql = ('SELECT * FROM transportreportmodified '.$where.' ORDER BY "challanNo" ASC');
    }else{
        $sql = ('SELECT * FROM transportfeedata '.$where.' ORDER BY "challanNo" ASC');
    }
    //echo $sql;
    //exit;
    //$_SESSION['sfsdatareportquery']=$sql; 
    $challanDataa = sqlgetresult($sql, true);

	//$challanDataa = sqlgetresult('SELECT c."studentId",c."challanNo",c."createdOn",c."updatedOn",c."classList",c."duedate",c."term",s."studentName",s."section",c."feeType",c."org_total", c."stream",c."academicYear", s."gender",c."challanStatus" FROM tbl_challans c LEFT JOIN tbl_student s ON s."studentId" = c."studentId" OR s."application_no" = c."studentId" WHERE c."feeGroup" = 10 AND c."feeType" != ALL (\'{16,17,18,19}\'::int[])  ORDER BY c.id ASC' , true);
	$challan = array();
	$txt="Transport";

	if($yearselect){
		//$ftquery='SELECT DISTINCT ft."feeType" FROM tbl_fee_type ft JOIN tbl_fee_configuration fc ON(ft.id=fc."feeType"::INTEGER)  WHERE ft."feeGroup" = \'11\' AND  fc."academicYear"=\'' . $yearselect . '\' AND fc.status= \'1\'';
		$ftquery='SELECT DISTINCT ft."feeType" FROM tbl_fee_type ft JOIN tbl_fee_configuration fc ON(ft.id=fc."feeType"::INTEGER)  WHERE (ft."feeGroup" = \'10\' OR ft."feeGroup" = \'11\') AND  fc."academicYear"=\'' . $yearselect . '\' AND ft."feeType" ILIKE \'%'.pg_escape_string($txt).'%\'' ;
	}else{
		$ftquery='SELECT "feeType" FROM tbl_fee_type WHERE ("feeGroup" = \'10\' OR "feeGroup" = \'11\')  AND "feeType" ILIKE \'%'.pg_escape_string($txt).'%\'';
	}


	$SFSFeeTypes = sqlgetresult($ftquery,true);
	//print_r($SFSFeeTypes);
	$SFSFeeTypes = array_column($SFSFeeTypes, "feeType");
	//print_r($SFSFeeTypes);

	$SFSFeeData = array();

	foreach ($SFSFeeTypes as $sfs) {
		//$SFSFeeData[] = $sfs.' Qty';
		$SFSFeeData[] = $sfs.' Amount';
	}

	//print_r($SFSFeeData); exit;

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
		   		$challanData['Demand Date'] = date('d/m/Y',strtotime($data['createdOn']));	
		   	} else {
		   		$challanData['Demand Date'] = date('d/m/Y',strtotime($data['updatedOn']));	
		   	}

		   	if($data['paid_date']){
                $challanData['Paid Date'] = date("d/m/Y", strtotime($data['paid_date']));
            }else{
                if($data['updatedOn']){
                	$challanData['Paid Date'] = date("d/m/Y", strtotime($data['updatedOn']));
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
		   		$challanData['Due Date'] = date("d/m/Y", strtotime($data['duedate']));
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
				//$qty = $v.' Qty';
				$amt = $v.' Amount';
				if ( trim($v) == $feeType['name'] ) {
					$perQtyAmt = getSFSandSchoolFeeByFeeId($feeType['id'], $data['classList'], $data['academicYear'], $data['term']);
					//$SFSFee[$qty][] = $data['org_total']/$perQtyAmt;
					$SFSFee[$amt][] = $data['org_total'];
				} else {
					//$SFSFee[$qty][] = '0';
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

	exportData($challan, 'Transport Demand - Export ', $columns);
?>