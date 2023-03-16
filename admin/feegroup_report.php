<?php
require_once ('../config.php');
ini_set('error_reporting','1');

$findChallans = sqlgetresult('SELECT "challanNo", "studentId", total, org_total, waived, "feeGroup", "classList", "stream" FROM tbl_challans WHERE "challanStatus" = \'1\' ',true);

$challanData  = array();

foreach ($findChallans as $k => $challan) {
	$product = '';
	$challan = array_map('trim', $challan);
	if( $challan['feeGroup'] == '8' && (trim($challan['stream']) == '1' || trim($challan['stream']) == '3' )) {
        $product = 'CBSEFEES';
    } else if( $challan['feeGroup'] == '8' && (trim($challan['stream']) == '2' || trim($challan['stream']) == '4' ) ) {
        $product = 'CICFEES';
    } else if( $challan['feeGroup'] == '10') {
        $product = 'SFS';
    } else if( $challan['feeGroup'] == '12') {
        $product = 'LMES';
    } else if( $challan['feeGroup'] == '9' ) {
        $product = 'UTILITY';
    } else if( trim($challan['stream']) == '5' ) {
        $product = 'PLAYFEES';
    }

    $challanData[$k]['ChallanNo'] =  $challan['challanNo'];
    $challanData[$k]['StudentId'] =  $challan['studentId'];
    $challanData[$k]['Stream'] =  getStreambyId($challan['stream']);
    $challanData[$k]['Class'] =  getClassbyNameId($challan['classList']);
    $challanData[$k]['FeeGroup'] =  getFeeGroupbyId($challan['feeGroup']); 
    $challanData[$k]['Amount'] =  $challan['total'];
    $challanData[$k]['Waived'] =  $challan['waived'] = '' ? '-' : $challan['waived'];
    $challanData[$k]['Total Amount'] =  $challan['org_total'];   
    $challanData[$k]['Product'] =  $product;    
}
// print_r($challanData);
// exit;
$columns = array('ChallanNo','StudentId','Stream','FeeGroup','Amount','Waived','Total Amount','Product');
exportData($challanData, 'Product Report', $columns);

?>