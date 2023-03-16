<?php
include_once('../config.php');

$challantype = 'CHALLAN';
$challanNo = 'CBSE2020/265319';
$studentid = '3532';
$semester = 'I';
$academicyear = '5';
$challanStatus = '0';
$demandtype = "DEMAND";
$receipttype = "RECEIPT";
$waivertype = "WAIVER";

$totalchallan = sqlgetresult('SELECT DISTINCT("challanNo") FROM tbl_challans WHERE "challanNo" = \''. $challanNo .'\' AND "studentId" = \''. $studentid .'\'',true);


    // WHERE "academicYear" = \''. $academicyear .'\' AND "term" = \''. $semester .'\'',true);

    // WHERE "challanNo" = \''. $challanNo .'\' AND "studentId" = \''. $studentid .'\'',true);

    

// $totalchallan = $challanNo;
// print_r($totalchallan);
// exit;

foreach($totalchallan AS $key => $challan){
	// Challan Details from Challan Table
	$challandatas = sqlgetresult('SELECT c."studentId", c."challanNo", s."studentName", a."year" AS "academicYear", cl."class_list", st."stream", c."term", g."feeGroup", f."feeType", c."createdOn", c."updatedOn", c."total", c."challanStatus"  FROM tbl_challans c 
	LEFT JOIN tbl_student s ON c."studentId" = s."studentId" OR c."studentId"::bpchar = s."application_no"::bpchar 
	LEFT JOIN tbl_academic_year a ON c."academicYear" = a.id
	LEFT JOIN tbl_class cl ON c."classList" = cl."id"
	LEFT JOIN tbl_stream st ON c."stream" = st.id
	LEFT JOIN tbl_fee_group g ON c."feeGroup" = g.id
	LEFT JOIN tbl_fee_type f ON c."feeType" = f.id
	WHERE c."challanNo" = \''. $challan['challanNo'] .'\'',true);
	// WHERE c."academicYear" = \''. $academicyear .'\' AND c."term" = \''. $semester .'\' AND c."challanStatus" = \''. $challanStatus .'\'',true);

    // print_r($challandatas);

	$challandata = array();
	$challanstatusunique = array();

    foreach ($challandatas as $key => $data) {
        if($data['feeGroup'] == ''){
            $data['feeGroup'] = 'LATE FEE';
        }
        else{
            $data['feeGroup'] = $data['feeGroup'];
        }
        if($data['feeType'] == ''){
            $data['feeType'] = 'LATE FEE';
        }
        else{
            $data['feeType'] = $data['feeType'];
        }

        
            $challandata[$data['challanNo']][$data['feeGroup']]['studentId']= $data['studentId']; 
            $challandata[$data['challanNo']][$data['feeGroup']]['studentName']= $data['studentName'];   
            $challandata[$data['challanNo']][$data['feeGroup']]['academicYear']= $data['academicYear'];  
            $challandata[$data['challanNo']][$data['feeGroup']]['class'] = $data['class_list'];   
            $challandata[$data['challanNo']][$data['feeGroup']]['stream'] = $data['stream'];    
            $challandata[$data['challanNo']][$data['feeGroup']]['term'] = $data['term'];
            $challandata[$data['challanNo']][$data['feeGroup']]['feeType'][] = $data['feeType'];
            $challandata[$data['challanNo']][$data['feeGroup']]['demandtotal'][] = $data['total'];
            $challandata[$data['challanNo']][$data['feeGroup']][$demandtype]['demanddate'] = date('Y-m-d',strtotime($data['createdOn'])); 
            $challandata[$data['challanNo']][$data['feeGroup']][$demandtype]['demandtotal'][] = $data['total']; 
            
            $challanstatus[]=$data['challanStatus'];    
    }
	// Challan Details from Waiver Table
    $waivedatas = sqlgetresult('SELECT * FROM tbl_waiver WHERE "studentId" = \''. $data['studentId'] .'\' AND "challanNo" = \''. $data['challanNo'].'\'',true);
    // echo('<hr/>');
    // print_r($waivedatas);

    if($waivedatas != ''){
        foreach ($waivedatas as $key => $data3){
            $challandata[$data['challanNo']][getFeeGroupbyId($data3['feeGroup'])][$waivertype]['waiverdate'] = date('Y-m-d',strtotime($data3['createdOn'])); 

            $challandata[$data['challanNo']][getFeeGroupbyId($data3['feeGroup'])][$waivertype]['waivertotal'][] = $data3['waiver_total'];
            $challandata[$data['challanNo']][getFeeGroupbyId($data3['feeGroup'])][$waivertype]['remarks'] = $data3['waiver_type'];
        } 
    }
// exit;
	// Challan Details from Receipt Table
    $challanstatusunique = array_unique($challanstatus);
    $value = 1;
    $statuschallan = array_search($value,$challanstatusunique);
    $statuschallan += $value;
    
    if($statuschallan != ''){
        $receiptdatas = sqlgetresult('SELECT g."feeGroup", c."total", c."updatedOn", c."pay_type", c,"cheque_dd_no", c."bank", c."paid_date", c."chequeRemarks" 
        FROM tbl_receipt c 
        LEFT JOIN tbl_fee_group g ON c."feeGroup" = g.id
        WHERE c."studentId" = \''. $data['studentId'] .'\' AND c."challanNo" = \''. $data['challanNo'].'\' ',true);
        // echo('<hr/>');
        // print_r($receiptdatas);

        foreach ($receiptdatas as $key => $data2) {
            if($data2['feeGroup'] == ''){
                $data2['feeGroup'] = 'LATE FEE';
            }
            else{
                $data2['feeGroup'] = $data2['feeGroup'];
            }

            // $updatedon = date('d-m-Y',strtotime($data2['updatedOn']));

            if($data2['updatedOn'] == ''){
                // print_r("kv ijfnvjknfjvnuidf");
                $challanupdate = sqlgetresult('SELECT "updatedOn" FROM tbl_challans WHERE "studentId" = \''. $data['studentId'] .'\' AND "challanNo" = \''. $data['challanNo'].'\' AND "feeGroup" = \''. getFeeGroupbyName($data2['feeGroup']).'\'',true);
               // print_r($challanupdate);
                // print_r("mbubvubufvbufbvjhfbvhjbjfvnjhjhnfjknvkjnfkjnvjk");
                $updatedonarray = reset(array_unique($challanupdate));
                 // $updatedonarr = reset($updatedonarray));
                // print_r($updatedonarray);
                $updatedon = date('Y-m-d',strtotime($updatedonarray['updatedOn']));
            }
            else{
                // print_r("hello");
                $updatedon = date('Y-m-d',strtotime($data2['updatedOn']));
            }

            $challandata[$data['challanNo']][$data2['feeGroup']][$receipttype]['receiptdate'] = $updatedon; 
            $challandata[$data['challanNo']][$data2['feeGroup']][$receipttype]['receipttotal'][] = $data2['total'];
            $challandata[$data['challanNo']][$data2['feeGroup']][$receipttype]['pay_type'] = $data2['pay_type'];
            $challandata[$data['challanNo']][$data2['feeGroup']][$receipttype]['cheque_dd_no'] = $data2['cheque_dd_no'];
            $challandata[$data['challanNo']][$data2['feeGroup']][$receipttype]['bank'] = $data2['bank'];
            $challandata[$data['challanNo']][$data2['feeGroup']][$receipttype]['paid_date'] = $data2['paid_date'];
            $challandata[$data['challanNo']][$data2['feeGroup']][$receipttype]['chequeRemarks'] = $data2['chequeRemarks'];

             $challandata[$data['challanNo']][$data2['feeGroup']][$receipttype]['waivedTotal'] =getwaiveramount($data['challanNo'], getFeeGroupbyName($data2['feeGroup']));
        }   
    }
    // echo('</hr>');
// print_r($challandata);
    foreach($challandata AS $key => $challan){
        // print_r("fjbhvbfvbfbvfvknkvnknvkn");
    	 		$chnNo = trim($key);
        foreach($challan AS $key2 => $chn){
                $studentId = trim($chn['studentId']);
                $studentName = trim($chn['studentName']);
                $academicYear = trim($chn['academicYear']);
                $class = trim($chn['class']);
                $stream = trim($chn['stream']);
                $term = $chn['term'];
                $feegroup = trim($key2);

            foreach($chn AS $key3 => $value){
                if($key3 == 'WAIVER'){
                    $date = trim($value['waiverdate']);
                    $amount = array_sum($value['waivertotal']);
                    $entrytype = $waivertype;
                    $remarks = $value['remarks']; 
                    $feeType = '';      
                }
                else if($key3 == 'RECEIPT'){
                    $date = trim($value['receiptdate']);
                    $amount = array_sum($value['receipttotal']);
                    $entrytype = $receipttype;
                    if($value['pay_type'] != ''){
                        $paymentremarks['transNum'] = trim($value['bank']) .'/'. trim($value['cheque_dd_no']) .'/'. trim($value['paid_date']) . '/' . trim($value['chequeRemarks']);
                    }
                    else{
                        $paymentremarks = sqlgetresult('SELECT "transNum" FROM tbl_payments WHERE "challanNo" = \''. $data['challanNo'].'\' AND "studentId" = \''. $data['studentId'].'\' AND "transNum" IS NOT NULL ');
                        $paymentremarks['transNum'] = trim($paymentremarks['transNum']) . '/' . 'Online';
                    }   
                    $remarks = $paymentremarks['transNum'];
                    $feeType = '';
                    if($value['waivedTotal'] != 0){
                        $amount = array_sum($value['receipttotal']) - $value['waivedTotal']['waiver_total'];
                    }  
                    else{
                        $amount = array_sum($value['receipttotal']);
                    }

                }else{
                    $date = trim($value['demanddate']);
                    $entrytype = $demandtype;    
                    $remarks = '';      
                }


	// For Inseritng into the Flat Table
                if($key3 == "RECEIPT" || $key3 == "WAIVER"){
                    $insertstudentledger = "SELECT * FROM studentledgeradddata('$studentId','$chnNo', '$studentName', '$academicYear', '$class', '$stream', '$term', '$date', '$feegroup', '$amount', '$remarks', '$entrytype')";
                    print_r($insertstudentledger);
                    // echo('<hr/>');
                    // print_r("SELECT * FROM studentledgeradddata('$studentId','$chnNo', '$studentName', '$academicYear', '$class', '$stream', '$term', '$date', '$feegroup', '$amount', '$remarks', '$entrytype')");
                    // if($insertstudentledger['studentledgeradddata'] != 0){
                    //     print_r("success");
                    //     echo('<hr/>');
                    // }else{
                    //     print_r("error");
                    //     echo('<hr/>');
                    // }
                }
                
                if($key3 == "DEMAND"){
                    if(is_array($chn['feeType'])){ 
                        foreach($chn['feeType'] AS $key => $feetype1){
                            $amount = $chn['demandtotal'][$key];
                            $feetype = trim($feetype1);
                                $insertstudentledger = "SELECT * FROM studentledgeradddatademand('$studentId','$chnNo', '$studentName', '$academicYear', '$class', '$stream', '$term', '$date', '$feegroup', '$feetype', '$amount', '$remarks', '$entrytype')";
                                print_r($insertstudentledger);
                                echo('<hr/>');
                                // print_r("SELECT * FROM studentledgeradddatademand('$studentId','$chnNo', '$studentName', '$academicYear', '$class', '$stream', '$term', '$date', '$feegroup', '$feetype', '$amount', '$remarks', '$entrytype')");
                                // if($insertstudentledger['studentledgeradddatademand'] != 0){
                                //     print_r("successdemand");
                                //     echo('<hr/>');
                                // }else{
                                //     print_r("errordemand");
                                //      echo('<hr/>');
                                // }
                        }
                    }
                }
            }
        }
    }

}
?>



