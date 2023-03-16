<?php 
	ob_start();
	require_once('navbar.php');
	date_default_timezone_set("Asia/Kolkata");    
    $cur_data = time();
    $date = date('Y-m-d h:i:s');
    // print_r($_POST);
    if(isset($_POST['mmp_txn']) && $_POST['mmp_txn'] != '' ) {
        // print_r($_POST);
        require_once 'atompay/TransactionResponse.php';
        $transactionResponse = new TransactionResponse();
        $transactionResponse->setRespHashKey($respHashKey);

        if($transactionResponse->validateResponse($_POST)){
            createErrorlog(json_encode($_POST),"",3);
            $m_trans_id = $_POST['mer_txn']; //atomtransactionID. The ID is generatedby atom
            $a_trans_id = $_POST['mmp_txn']; //Merchant'stransactionID
            $amount = $_POST['amt'];
            $createdOn = $_POST['date'];
            $b_trans_id = $_POST['bank_txn']; //Bank TransactionID. This ID is generatedby the Bank
            $f_code = $_POST['f_code'];
            $createdOn = $_POST['date'];
            $desc = isset($_POST['desc']) ? $_POST['desc'] : '';
            $auth_code = isset($_POST['auth_code']) ? $_POST['auth_code'] : '';

            $paymentData = sqlgetresult("SELECT * FROM nonfeepaymententry('".$_SESSION['PSNFData']['id']."','".$_SESSION['PSNFData']['studentId']."','$amount','$f_code','$m_trans_id','".json_encode($_POST)."','".$desc."','$createdOn','".$_SESSION["uid"]."','".$_SESSION['nonfeepayment_id']."') ");     


            if($f_code == 'Ok') {                
                createCFPDF($paymentData['nonfeepaymententry']);
                // exit;
                unset($_SESSION['PSNFData']);
                $_SESSION['success_msg'] = "<p class='success-msg'>Payment Completed Successfully.</p>";
                $smsTxt = urlencode("Dear Parent, You have successfully top-up the amount of ".$amount." to your child's account.");
                $mblNumber = $_SESSION["phn"];
                $smsURL = "$smsBaseurl/sms.aspx?Id=$smsLoginId&Pwd=$smsLoginPass&PhNo=$mblNumber&text=$smsTxt";
                $ret = file($smsURL);
                header("Location: cardtopup.php");
                exit;
            } else {
                if($_POST['desc'] == "Transction Failure") {
                    createErrorlog(json_encode($_POST));
                    $_SESSION['error_msg'] = "<p class='error-msg'>Your Transaction has been failed. Please try again later.</p>";
                } elseif ($_POST['f_code'] == 'C' ) {
                    createErrorlog(json_encode($_POST));
                    $_SESSION['error_msg'] = "<p class='error-msg'>Your Transaction has been cancelled. Please try again later.</p>";
                } else {
                    createErrorlog(json_encode($_POST));
                    $_SESSION['error_msg'] = "<p class='error-msg'>Some Error Has Occurred. Please try again later.</p>";
                }
                header("Location: cardtopup.php");
                exit;
            }
           
        } else {
            createErrorlog(json_encode($_POST),"Something gone wrong.",1);
            $_SESSION['error_msg'] = "<p class='error-msg'>Invalid Signature. Please try again later.</p>";
            header("Location: cardtopup.php");
            exit;
        }
        // createErrorlog(json_encode($_POST),"Something gone wrong.",1);
        $_POST = '';
    }
    $studentIds = sqlgetresult('SELECT "studentId" FROM getparentdata WHERE id = \''.$_SESSION['uid'].'\' GROUP BY "studentId" ',true);

    // print_r($studentIds);
?>

<div class="container">
    <div class="col-sm-12">
        <?php
            if(isset($_SESSION['success_msg']) && $_SESSION['success_msg'] !='') {
                echo $_SESSION['success_msg'];
                unset($_SESSION['success_msg']);
            }
            if (isset($_SESSION['error_msg']) && $_SESSION['error_msg'] !='') {
                echo $_SESSION['error_msg'];
                unset($_SESSION['error_msg']);
            }
        ?>
    </div>
	<div class="row">
		<div id="topup-container" class="col-md-offset-3 col-md-5">
			<h4 class="crdtoph">CARD TOP-UP</h4>
			<form class="form-horizontal" action="sql_actions.php" method="post">
				<div class="form-group">
					<label class="control-label col-sm-5" for="email">StudentId: </label>
					<div class="col-sm-7">
						<!-- <input type="text" class="form-control" name="studId" id="studId" required="" placeholder="Enter StudentId"> -->
                        <select class="form-control" name="studId" id="studId" required="" >
                            <option>-SELECT-</option>
                            <?php
                                foreach ($studentIds as $sid) {
                                    echo "<option value='".$sid['studentId']."'>".$sid['studentId']."</option>";
                                }
                            ?>
                        </select>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-5" for="email">Top-up Amount: </label>
					<div class="col-sm-7">
						<select class="form-control" name="topupamt" id="topupamtchk" required="">
							<option value="">--SELECT AMOUNT--</option>
							<option value="50">50</option>
							<option value="100">100</option>
							<option value="200">200</option>
							<option value="500">500</option>
							<option value="1000">1000</option>
							<option value="other">other</option>
						</select>
					</div>
				</div>
				<div class="form-group" style="display:none;" id="topupamtdiv">
					<label class="control-label col-sm-5" for="email">Amount: </label>
					<div class="col-sm-7">
						<input type="text" class="form-control" id="topupamt" placeholder="Enter Amount">
					</div>
				</div>
				<div class="text-center">
					<!-- <button class="btn btn-primary" name="view_topup" >View Top-up Challan</button> -->
					<button type='button' class='btn btn-info view_topup' id="view_topup">View Top-up Challan</button>
					<!-- <button class="btn btn-primary" name="pay_topup" value="pay">Pay Top-up</button> -->
				</div>
			</form>
		</div>
	</div>
</div>	

<div class="modal fade" id="paynfModal" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Non-fee Challan - Payment</h4>
            </div>
            <div class="modal-body">
                <p>Please review the details of your non-fee challan and confirm to continue...</p>
                <div class="table-responsive">
                    <form method="post" id="studDataModal" action="sql_actions.php">
                        <div id="nonfeechallanData"></div>                                     
                        <div class="text-center">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <span id="confirmpay"></span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<hr>
<div class="container-fluid">
    <div class="table-responsive">
        <button type="button" class="btn button1" data-toggle="collapse" data-target="#list">+Paid Top-up</button>
        <div id="list" class="collapse">
            <table class="table table-bordered opayment1" cellspacing="0" width="400">
                <?php                                      

                    $topupdata = sqlgetresult('SELECT * FROM topupdata WHERE "parentId" = \''.$_SESSION['uid'].'\' ' , true);
                    
                    foreach ($topupdata as $k => $v) {
                        $topup[$k]['feeGroup'] = 'NON-FEE' ;
                        $topup[$k]['studentId'] = $v['studentId'];
                        $topup[$k]['studentName'] = $v['studentName'];
                        $topup[$k]['challanNo'] = 'CARDTOPUP'.$v['tpid'].'/'.$v['studentId'];
                        $topup[$k]['total'] = $v['amount'];
                        $topup[$k]['updatedOn'] = $v['createdOn'];
                    }

                    $paiddata = $topup;

                    $challanData = array();
                   $total =0;
                   $tot = 0;    
                   $challanNo  = '';
                   $feeData = array();
                   $count = $paiddata == '' ? 0 : count($paiddata);
                   if($count != 0) {
                       foreach ($paiddata as $k => $data) {
                           $challanData[$data['challanNo']]['studentName'] = $data['studentName'];                          
                           $challanData[$data['challanNo']]['studentId'] = $data['studentId'];
                           $challanData[$data['challanNo']]['challanNo'] = $data['challanNo'];
                           $challanData[$data['challanNo']]['org_total'][] = $data['total'];   
                           $challanData[$data['challanNo']]['updatedOn'] = $data['updatedOn'];                      
                       }
                       $data =  array();
                       foreach ($challanData as $feeData) {
                           $feeData['fee'] = array_sum($feeData['org_total']); 
                           $data[] = $feeData;
                       }    
                   } else {
                       $data = array();
                   }
                ?>

                <tr>
                    <th>S.No</th>
                    <th>Student ID</th>
                    <th>Student Name</th>
                    <th>Challan Number</th>
                    <th>Amount Paid</th>
                    <th>Action</th>
                </tr>
                <?php
                $i = 1;
                    if(count($data) > 0) {
                    foreach($data AS $data) {
                        // print_r($data);
                        $date = date('dmY', strtotime($data['updatedOn']));
                        $pdfpath = BASEURL.'receipts/'.$date.'/'.str_replace('/', '', trim($data['challanNo'])).'.pdf';
                ?>
                    <tr>
                        <td><?php echo $i ;?></td>
                        <td><?php echo $data['studentId'] ;?></td>
                        <td><?php echo $data['studentName'] ;?></td>
                        <td><?php echo $data['challanNo'] ;?></td>
                        <td class='text-right'><?php echo $data['fee'] ;?></td>                        
                        <td><a href="<?php echo $pdfpath;?>" target="_blank">Download Receipt</a></td>
                    </tr>
                <?php
                    $i++;
                    }
                } else {
                    echo "<td colspan='6'><center>No Paid Challan Data Available.</center></td>";
                }
                ?>
            </table>
        </div>
    </div>
</div>


<div class="row comment"></div>

<?php
  include_once('footer.php');
?>
