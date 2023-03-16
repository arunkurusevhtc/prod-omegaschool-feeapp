<?php
include_once("../config.php");
ini_set('max_execution_time', 900);

   // $_POST = array_map('trim',$_POST);
    $classselect = isset($_POST['classselect'])?trim($_POST['classselect']):"";
    $streamselect = isset($_POST['streamselect'])?trim($_POST['streamselect']):"";
    $sectionselect = isset($_POST['sectionselect'])?trim($_POST['sectionselect']):"";
    $yearselect = isset($_POST['yearselect'])?trim($_POST['yearselect']):"";
    $semesterselect = isset($_POST['semesterselect'])?trim($_POST['semesterselect']):"";
    $tp=0;  
    if(isset($_POST['movedtopaid']) && $_POST['movedtopaid']=='movedtopaid'){
       $whereClauses = array(' WHERE "challanStatus" = 3'); 
       $file='Moved To Paid Challans Report';
       $tp=1; 
    }else{
        $whereClauses = array(' WHERE "challanStatus" = 0');
        $file='Unpaid Challan Report'; 
    }
   $where="";
   if (! empty($yearselect)) {
        $whereClauses[] ='"academicYear"=\''.pg_escape_string($yearselect).'\' ' ;
   }

    if (! empty($semesterselect)) {
      $whereClauses[] ="term='".pg_escape_string ($semesterselect)."'"; 
    }

    if (! empty($classselect)) {
        $whereClauses[] ='"classList"=\''.pg_escape_string($classselect).'\' ' ;
    }

    if (! empty($streamselect)) {
      $whereClauses[] ="stream='".pg_escape_string ($streamselect)."'"; 
    }

    if (! empty($sectionselect)) {
      $whereClauses[] ="section='".pg_escape_string ($sectionselect)."'"; 
    }

    if (count($whereClauses) > 0) 
    { 
      $where = implode(' AND ',$whereClauses); 
    }  

    $sql = ('SELECT "studentId", "challanNo", "studentName", "streamname", "class_list", "section", "academicYear", "term", "createdOn", "duedate", "org_total", "updatedOn" FROM  getchallandatanew'.$where.' AND deleted=\'0\'');
    $res = sqlgetresult($sql, true);
    // print_r($res);
    // exit;
    $challanData = array();
    $total = 0;
    $tot = 0;
    $challanNo = '';
    $feeData = array();
    $outputdata = array();
    $createdchallan = array();
    if ($res != 0)
    {
        foreach ($res as $k => $data)
        {
            $challanData[$data['challanNo']]['studentId'] = $data['studentId'];
            $challanData[$data['challanNo']]['challanNo'] = $data['challanNo'];
            $challanData[$data['challanNo']]['studentName'] = $data['studentName'];
            $challanData[$data['challanNo']]['streamname'] = $data['streamname'];
            $challanData[$data['challanNo']]['class_list'] = $data['class_list'];
            $challanData[$data['challanNo']]['section'] = $data['section'];
            $challanData[$data['challanNo']]['academicYear'] = getAcademicyrById($data['academicYear']);
            $challanData[$data['challanNo']]['term'] = $data['term'];
            $challanData[$data['challanNo']]['createdOn'] = date("d-m-Y", strtotime($data['createdOn']));
            $challanData[$data['challanNo']]['duedate'] = date("d-m-Y", strtotime($data['duedate']));
            $challanData[$data['challanNo']]['updatedOn'] = date("d-m-Y", strtotime($data['updatedOn']));

            $challanData[$data['challanNo']]['waived'] =getwaiveddata($data['challanNo']);
            // $challanData[$data['challanNo']]['feeTypes'][] = trim($data['feeType']);
            // $challanData[$data['challanNo']]['feeGroup'] = $data['feeGroup'];
            $challanData[$data['challanNo']]['org_total'][] = $data['org_total'];
            $waiveddata = $challanData[$data['challanNo']]['waived'];
            $orgtotal = $challanData[$data['challanNo']]['org_total'];
            $challanNo = $data['challanNo'];
            $waivertotal = 0;
            $waiverorgtotal = 0;
            if($waiveddata != '0'){
                if(isset($waiveddata[0]['oldwaiver']) && $waiveddata[0]['oldwaiver'] == 1){
                    $waivertotal = 0;
                    $waiverorgtotal = array_sum($orgtotal);

                } else {
                    $waivedAmount = array();
                    $waivedgrps = array();
                    foreach ($waiveddata as $waived)
                    {
                        $waivedAmount[] += $waived['waiver_total'];
                        $waivertotal = array_sum($waivedAmount);
                        $waiverorgtotal = array_sum($orgtotal);
                    }
                }
            }
            else{
                    $waivertotal = 0;
                    $waiverorgtotal = array_sum($orgtotal);
            }
            $challanData[$challanNo]['waiver_total'] = $waivertotal;
            $challanData[$challanNo]['waiver_org_total'] = $waiverorgtotal;
        }
        foreach($challanData AS $challan){
            $createdchallan[]= $challan;
        }

        $outputdata = $createdchallan;
    }
    else
    {
        $outputdata = array();
    }

        foreach($outputdata AS $key => $output){
            $result_data[$key]['Student Id']=$output['studentId'];
            $result_data[$key]['Challan Number']=$output['challanNo'];
            $result_data[$key]['Name']=$output['studentName'];
            $result_data[$key]['Stream']=$output['streamname'];
            $result_data[$key]['Class']=$output['class_list'];
            $result_data[$key]['Section']=$output['section'];
            $result_data[$key]['Academic Year']=$output['academicYear'];
            $result_data[$key]['Term']=$output['term'];
            $result_data[$key]['Created Date']=$output['createdOn'];
            $result_data[$key]['Due Date']=$output['duedate'];
            $result_data[$key]['Total']=($output['waiver_org_total']-$output['waiver_total']);
            if($tp==1){
              $result_data[$key]['Updated Date']=$output['updatedOn'];
            }



        }
// print_r($result_data);exit;
        foreach ($result_data as $k => $v) {
        $keys = array();
        foreach ($v as $field_code => $field_val)
        {       
            // if($field_val != '') {
                $keys[]=$field_code;                
            // }       
        }       
        $columns = $keys;
    }

    exportData($result_data, $file, $columns);
?>