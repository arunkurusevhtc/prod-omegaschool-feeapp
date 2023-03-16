<?php
	require_once ('../config.php');
    //error_reporting(E_ALL);
    //ini_set('display_errors', TRUE);
    //ini_set('display_startup_errors', TRUE);
    ini_set('memory_limit', -1);
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

    $feegroupselect = isset($_POST['feegroupselect'])?$_POST['feegroupselect']:"";
    $feetypeselect = isset($_POST['feetypeselect'])?$_POST['feetypeselect']:"";

    //echo "<pre>";
    //print_r($_POST);

    

    $fgroucol= array();


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
     $fgrp="";

    if (!empty($feegroupselect)) {
      $whereClauses[] ="chlfeegroupid='".pg_escape_string ($feegroupselect)."'"; 
      $fgrp="'".pg_escape_string ($feegroupselect)."'";
    }else{
    	if($feegroupselect == "0"){
			$whereClauses[] ="chlfeegroupid='0'"; 
			$fgrp="'0'";
    	}
    }
    $ftypewhre="";
    if (!empty($feetypeselect)) {
      $whereClauses[] ="chlfeetypeid='".pg_escape_string ($feetypeselect)."'"; 
      //$ftype="'".pg_escape_string ($feetypeselect)."'";
      $ftypewhre=" AND  ft.id=".pg_escape_string ($feetypeselect);
    }


    if (!empty($from) && !empty($to))
    {
    	$whereClauses[] = 'DATE("date") BETWEEN \'' . pg_escape_string ($from) . '\'  AND  \'' . pg_escape_string ($to) . '\'';
    }
    
    if (count($whereClauses) > 0) 
    { 
      $where = ' WHERE '.implode(' AND ',$whereClauses); 
    } 
   // $sql = ('SELECT * FROM sfsdatareport '.$where);
    //$sql = ('SELECT * FROM sfsdatareportmod '.$where);

    // $sql = ('SELECT * FROM sfsdatareportmodified '.$where);

    if($isStudIdSearch){
        $sql = ('SELECT *,DATE("updatedOn") AS updated FROM paidreceiptreportmodified '.$where.' ORDER BY "challanNo" ASC');
    }else{
        $sql = ('SELECT *,DATE("updatedOn") AS updated FROM paidreceiptreport '.$where.' ORDER BY "challanNo" ASC');
    }
    //echo $sql;
   // exit;
    //$_SESSION['sfsdatareportquery']=$sql; 
    $challanDataa = sqlgetresult($sql, true);

	//$challanDataa = sqlgetresult('SELECT c."studentId",c."challanNo",c."createdOn",c."updatedOn",c."classList",c."duedate",c."term",s."studentName",s."section",c."feeType",c."org_total", c."stream",c."academicYear", s."gender",c."challanStatus" FROM tbl_challans c LEFT JOIN tbl_student s ON s."studentId" = c."studentId" OR s."application_no" = c."studentId" WHERE c."feeGroup" = 10 AND c."feeType" != ALL (\'{16,17,18,19}\'::int[])  ORDER BY c.id ASC' , true);
	$challan = array();
    if($fgrp){
       $fgroup = $fgrp;
    }else{
    	//$fgroup = "'8','9','10','11','12','13','14','15'";

    	$feegrouptypes = sqlgetresult("SELECT * FROM feegroupcheck",true);
    	 foreach($feegrouptypes as $feegroupt) {
    	 	$fgroucol[]= "'".$feegroupt['id']."'";
    	 }

    	 $fgroup =implode(",",$fgroucol);
    }
	

	if($yearselect){
		$ftquery='SELECT DISTINCT ft."feeType" FROM tbl_fee_type ft JOIN tbl_fee_configuration fc ON(ft.id=fc."feeType"::INTEGER)  WHERE ft."feeGroup" IN ('.$fgroup.') AND  fc."academicYear"=\'' . $yearselect . '\' AND fc.status= \'1\''.$ftypewhre;
	}else{
		$ftquery='SELECT ft."feeType" FROM tbl_fee_type ft WHERE  ft."feeGroup" IN ('.$fgroup.')'.$ftypewhre;
	}

	//exit;
	$SFSFeeTypes = sqlgetresult($ftquery, true);
	$SFSFeeTypes = array_column($SFSFeeTypes, "feeType");

	$SFSFeeData = array();

	$feegrpname=toGetFeeGroupNameList();

	foreach ($SFSFeeTypes as $sfs) {
		//$SFSFeeData[] = $sfs.' Qty';
		$sfs=trim($sfs);
		$SFSFeeData[] = $sfs.' ('.$feegrpname[$sfs].')';
	}
	if(empty($feegroupselect) || $feegroupselect=="0"){
		array_push($SFSFeeData, 'LATE FEE'.' (LATE FEE)');
	}
	
//echo "<pre>";
	 //print_r($SFSFeeData); exit;
	$columns = array('S.No','ID','Name','Class','Stream','Academic Year','Term','Invoice Ref. No','Paid Date','Paid Status','Waiver Amount');
	$columns = array_merge($columns,$SFSFeeData);
	$challanData = array();
	$i =0;
	$waived=array();

	if(count($challanDataa) > 0){
		$aYears=getacadamicYears();
        $classes=getClassses();
        $streams=getStreams();
        $students=getStudentDetails();
       //print_r($challanDataa);
       //exit;
		foreach ($challanDataa as $k => $data) 
		{    
			$challanNo=$data['challanNo'];
			$chlfeetypeid=$data['chlfeetypeid'];
			$ftname=trim($data['feetypename']);
			$fgname=trim($data['chlfeegroupid']);

			if($fgname == 0){
				$ftypedetails= 'LATE FEE'.' (LATE FEE)';
			}else{
				$ftypedetails= $ftname.' ('.$feegrpname[$ftname].')';
			}
			
			$a = array_map('trim', array_keys($data));
			$b = array_map('trim', $data);
			$data = array_combine($a, $b);
			/*$challanNo=$data['challanNo'];
			$chlfeetypeid=$data['chlfeetypeid'];
            $chlfeegroupid=$data['chlfeegroupid'];
            $feetypename=$data['feetypename'];
            $feeGroup=$data['feeGroup'];*/
			$studentId=trim($data['studentId']);
		   	if($isStudIdSearch){
			   	$challanData[$challanNo]['ID'] = $studentId;
			   	$challanData[$challanNo]['Name'] = trim($data['studentName']);
		    }else{
				if($students[$studentId]['studentId']){
				    $challanData[$challanNo]['ID'] = $students[$studentId]['studentId'];
				    $challanData[$challanNo]['Name'] = $students[$studentId]['studentName'];
				}else{
				    $oldStudentId=getStudentDetailsByOldId($studentId);
				    if($oldStudentId){
				        $challanData[$challanNo]['ID'] = $students[$oldStudentId]['studentId'];
				        $challanData[$challanNo]['Name'] = $students[$oldStudentId]['studentName'];
				    }else{
				        $challanData[$challanNo]['ID'] = "";
				        $challanData[$challanNo]['Name'] = "";
				    }
				}
		    }
		   	$challanData[$challanNo]['Class'] = $classes[$data['classList']];
		   	$challanData[$challanNo]['Stream'] = $streams[$data['stream']];
			$challanData[$challanNo]['Academic Year']=$aYears[$data['academicYear']];
			$challanData[$challanNo]['Term']=$data['term'];
			if($data['paid_date']){
                $challanData[$challanNo]['Paid Date'] = $data['paid_date'];
            }else{
                if($data['updatedOn']){
                	$challanData[$challanNo]['Paid Date'] = $data['updated'];
                }else{
                	$challanData[$challanNo]['Paid Date'] = "";
                }
            }
            $pstatus=trim($data['challanStatus']);
            if($pstatus == 1){
			   	$challanData[$challanNo]['Paid Status'] = "Paid";
		   	}
		   	else if($pstatus == 2){
			   	$challanData[$challanNo]['Paid Status'] = "Partial Paid";
		   	}
		   	else{
			   	$challanData[$challanNo]['Paid Status'] = "Not Paid";
			   	$challanData[$challanNo]['Paid Date'] = "";	
		   	}
			$challanData[$challanNo]['Invoice Ref. No']=$challanNo;
			$challanData[$challanNo][$ftypedetails]=$data['org_total'];		
		}

		foreach ($SFSFeeData as $SFSFeeData) {
			// code...
			$i=1;
			foreach ($challanData as $key => $value) {
				// code...
                if($value[$SFSFeeData]){
                      $challanData[$key][$SFSFeeData]=$value[$SFSFeeData];
                }else{
                	$challanData[$key][$SFSFeeData]=0;
                }
                $challanData[$key]['S.No']=$i;
                if($feegroupselect){
                   $wdata=getwaiveramount($key,$feegroupselect);
                }else{
                  $wdata=getwaiveramountbychallan($key);
                }
                
                 if($wdata['waiver_total']){
                   $waivedAmt=$wdata['waiver_total'];
                 }else{
                 	$waivedAmt=0;
                 }
                 $challanData[$key]['Waiver Amount']=$waivedAmt;
                $i++;
			}
		} 

	}
	//print_r($challanData);
	 //exit;
	$columns = array_unique($columns);
	exportData(array_values($challanData), 'Fee Type Report - Export ', $columns);
?>