<?php
require_once ('../config.php');

	$studentId = array(6398      , 6377      , 6429      , 6433      , 6341      , 6172      , 6236      , 6324      , 6281      , 6232      , 6237      , 6269      , 6372      , 6226      , 6271      , 6422      , 6319      , 6181      , 6234      , 6311      , 6323      , 6423      , 6356      , 6392      , 6388      , 6194      , 6174      , 6205      , 6332      , 6308      , 6437      , 6367      , 6379      , 6277      , 6310      , 6354      , 6442      , 6259      , 6197      , 6303      , 6390      , 6425      , 6258      , 6443      , 6184      , 6339      , 6411      , 6424      , 6314      , 6385      , 6386      , 6231      , 6209      , 6233      , 6252      , 6221      , 6325      , 6345      , 6421      , 6284      , 6432      , 6293      , 6185      , 6407      , 6296      , 6327      , 6317      , 6305      , 6408      , 6175      , 6426      , 6301      , 6375      , 6240      , 6328      , 6387      , 6427      , 6405      , 6435      , 6414      , 6270      , 6188      , 6264      , 6401      , 6331      , 6330      , 6395      , 6430      , 6334      , 6229      , 6295      , 6329      , 6420      , 6291      , 6173      , 6186      , 6215      , 6286      , 6290      , 6256      , 6297      , 6261      , 6355      , 6208      , 6357      , 5814      , 5859      , 6165      , 6550      , 6669      , "IG 1218   ", 6352      , 5544      , 6272      , 6381      , 6239      , 6337      , 6300      );
	$transport_stg = array( 2         , 1         , 2         , 4         , 2         , 2         , 2         , 3         , 2         , 2         , 3         , 3         , 4         , 2         , 3         , 2         , 2         , 3         , 2         , 4         , 2         , 4         , 4         , 2         , 4         , 3         , 3         , 2         , 3         , 1         , 3         , 2         , 1         , 3         , 4         , 4         , 2         , 3         , 2         , 3         , 3         , 3         , 2         , 4         , 3         , 4         , 4         , 4         , 2         , 4         , 4         , 1         , 2         , 2         , 2         , 3         , 2         , 3         , 3         , 2         , 1         , 2         , 3         , 3         , 2         , 3         , 2         , 4         , 3         , 4         , 4         , 3         , 3         , 1         , 1         , 4         , 3         , 2         , 3         , 2         , 3         , 2         , 1         , 1         , 2         , 1         , 4         , 3         , 2         , 3         , 3         , 2         , 3         , 3         , 2         , 2         , 2         , 2         , 2         , 3         , 4         , 2         , 4         , 1         , 2         , 4         , 3         , 2         , 4         , 4         , 3         , 2         , 3         , 3         , 3         , 4         , 2         , 3         );

	$data =array_combine($studentId,$transport_stg);
	// print_r($data);
	foreach ($data as $key => $value) {
		
		$trans_id = 0;
		$trans_amt = 0;
		if( $value == '1' ) {
			$trans_id = '16';
			$trans_amt = '5335';
		} else if( $value == '2' ) {
			$trans_id = '17';
			$trans_amt = '8665';
		} else if( $value == '3' ) {
			$trans_id = '18';
			$trans_amt = '12010';
		} else if( $value == '4' ) {
			$trans_id = '19';
			$trans_amt = '13340';
		}
		// echo $trans_id;echo "<hr/>";
		if($trans_id != 0) {
			$challan = sqlgetresult('SELECT "feeTypes", "challanNo", "total", "org_total" FROM tbl_challans WHERE "feeGroup" = \'9\' AND "studentId" = \''.$key.'\' ');
			$feeIds = trim($challan['feeTypes']).','.$trans_id;
			$total = $challan['total'] + $trans_amt;
			$org_total = $challan['org_total'] + $trans_amt;
			$updateChallan = sqlgetresult('UPDATE tbl_challans SET "feeTypes" = \''.$feeIds.'\', "total" = \''.$total.'\', "org_total" = \''.$org_total.'\' WHERE "challanNo" = \''.$challan['challanNo'].'\' AND "studentId" =   \''.$key.'\' AND "feeGroup" = \'9\' RETURNING id');
			$edata[$key]['studentId'] =  $key;
			$edata[$key]['transport_stg'] =  $trans_id;
			$edata[$key]['tamount'] =  $trans_amt;
			$edata[$key]['feetypes'] =  $feeIds;
			$edata[$key]['after_total'] = $total;
			$edata[$key]['org_feetypes'] = $challan['feeTypes'];
			$edata[$key]['before_total'] = $challan['total'];
			$edata[$key]['challanNo'] = $challan['challanNo'];
		}
	}
	$columns = array('studentId','transport_stg','tamount','feetypes','after_total','org_feetypes','before_total','challanNo');
	exportData($edata, "mismatchTransport", $columns);

?>