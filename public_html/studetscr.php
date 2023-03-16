<?php
    include_once('navbar.php');       
   
    if(isset($_POST['mmp_txn']) && $_POST['mmp_txn'] != '' && isset($_SESSION['last_fee_entry_id'])) {
        require_once 'atompay/TransactionResponse.php';
        $transactionResponse = new TransactionResponse();
        $transactionResponse->setRespHashKey("KEYRESP123657234");

        if($transactionResponse->validateResponse($_POST)){
            $m_trans_id = $_POST['mer_txn']; //atomtransactionID. The ID is generatedby atom
            $a_trans_id = $_POST['mmp_txn']; //Merchant'stransactionID
            $amount = $_POST['amt'];
            $createdOn = $_POST['date'];
            $b_trans_id = $_POST['bank_txn']; //Bank TransactionID. This ID is generatedby the Bank
            $f_code = $_POST['f_code'];
            $createdOn = $_POST['date'];
            $desc = isset($_POST['desc']) ? $_POST['desc'] : '';
            $auth_code = isset($_POST['auth_code']) ? $_POST['auth_code'] : '';

            $paymentData = sqlgetresult("SELECT * FROM paymentEntry('".$_SESSION['PSData']['id']."','".$_SESSION['PSData']['studentId']."','$amount','$f_code','$m_trans_id','".json_encode($_POST)."','".$desc."','".$auth_code."','$createdOn','".$_SESSION["uid"]."') ");
            // print_r($_SESSION);

            if($f_code == 'Ok') {
                $entry = sqlgetresult("SELECT *  FROM fee_entry_update('".$paymentData['paymententry']."','".$_SESSION['last_fee_entry_id']."','".$_SESSION['uid']."') ");
                $updateChallan = sqlgetresult('UPDATE tbl_challans SET "challanStatus" = 1, "updatedBy" = \''.$_SESSION['uid'].'\', "updatedOn" = CURRENT_TIMESTAMP WHERE "challanNo" = \''.$_SESSION['PSData']['challanNo'].'\' AND "feeGroup"=\''.$_SESSION['feegroup'].'\' ');
                createPDF($_SESSION['PSData']['studentId'],$_SESSION['PSData']['challanNo']);
                // exit;
                unset($_SESSION['last_fee_entry_id']);
                unset($_SESSION['PSData']);
                $_SESSION['success_msg'] = "<p class='success-msg'>Payment Completed Successfully.</p>";
            } else {
                if($_POST['desc'] == "Transction Failure") {
                    createErrorlog(json_encode($_POST));
                    $_SESSION['error_msg'] = "<p class='error-msg'>Your Transction has been failed. Please try again later.</p>";
                } elseif ($_POST['f_code'] == 'C' ) {
                    createErrorlog(json_encode($_POST));
                    $_SESSION['error_msg'] = "<p class='error-msg'>Your Transction has been cancelled. Please try again later.</p>";
                } else {
                    createErrorlog(json_encode($_POST));
                    $_SESSION['error_msg'] = "<p class='error-msg'>Some Error Has Occurred. Please try again later.</p>";
                }
            }
           
        } else {
            createErrorlog(json_encode($_POST),"Something gone wrong.",1);
            $_SESSION['error_msg'] = "<p class='error-msg'>Invalid Signature. Please try again later.</p>";
        }
        // createErrorlog(json_encode($_POST),"Something gone wrong.",1);
        $_POST = '';
    }  
    // $_POST = '';
?>
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
<div class="container-fluid studData">
	
    <div class="col-md-12 p-r-0 p-l-0 table-responsive">
        <form id="stud_details" method="post" action="sql_actions.php">
            <table class="table table-bordered opayment" cellspacing="0">
                <tr>
                    <th>S.No</th>
                    <th>Student Name</th>
                    <th>Class</th>
                    <th>Section</th>
                    <th>Semester</th>
                    <th>Amount</th>
                    <th>Action</th>
                </tr>
            </table>
        </form>
    </div>
    <!-- <div class="pagination"><p>Page:</p></div> -->
</div>
<hr>
<div class="container-fluid">
    <div class="table-responsive">
        <button type="button" class="btn button1" data-toggle="collapse" data-target="#list">+ Paid Challans</button>
        <div id="list" class="collapse">
            <table class="table table-bordered opayment1" cellspacing="0" width="400">
                <?php
                    $paiddata = sqlgetresult('SELECT DISTINCT (challanData."feeGroup"),"studentId","studentName","challanNo","org_total","updatedOn" FROM challanData WHERE "challanStatus" = 1 AND "parentId" = \''.$_SESSION['uid'].'\'  ' , true);
                    // print_r($paiddata);

                    $challanData = array();
                   $total =0;
                   $tot = 0;	
                   $challanNo  = '';
                   $feeData = array();
                   $count = $paiddata == '' ? 0 : count($paiddata);
                   if($count != 0) {
                       foreach ($paiddata as $k => $data) {
                           $challanData[$data['challanNo']]['studentName'] = $data['studentName'];
                           // $challanData[$data['challanNo']]['stream'] = $data['stream'];
                           // $challanData[$data['challanNo']]['section'] = $data['section'];
                           // $challanData[$data['challanNo']]['term'] = $data['term'];
                           $challanData[$data['challanNo']]['studentId'] = $data['studentId'];
                           $challanData[$data['challanNo']]['challanNo'] = $data['challanNo'];
                           // $challanData[$data['challanNo']]['feeTypes'][] = trim($data['feeTypes']);
                           $challanData[$data['challanNo']]['org_total'][] = $data['org_total'];   
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
<!-- <div class="onlinepay">
    <p>There is no online fee payment facility for new student at this time.</p>
    <p>Online payment facility is closed.Please approach school for making fee payment.</p>
</div> -->
<div class="row comment">
    
</div>
<!-- Add Student Modal -->

       
        <!-- Pay Modal -->
<div class="modal fade" id="payModal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Fee Challan - Payment</h4>
            </div>
            <div class="modal-body">
                <p>Please review the details of your challan and confirm to continue...</p>
                <div class="table-responsive">
                    <form method="post" id="studDataModal" action="sql_actions.php">
                        <div id="challanData"></div>
                        <div id="manualentry" class="table-responsive feetab1">
                            <table class="table table-striped">
                                <tr>
                                    <td>
                                        <form>
                                            <div class="form-group row">
                                                <div class="col-sm-4">
                                                    <label for ="ftype" class="control-label">Pay Type</label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <input type="text" id="ptype" name="ptype"  placeholder="Pay Type" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-sm-4">
                                                    <label for ="des" class="control-label">Bank</label>
                                                </div>
                                                <div class="col-sm-8">
                                                     <input type="text" id="bank" name="bank" placeholder="Bank Name" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-sm-4">
                                                    <label for ="des" class="control-label">Cheque/DD</label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <input type="text" id="paymentmode" name="paymentmode" placeholder="Cheque No/ DD Number" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-sm-4">
                                                    <label for ="des" class="control-label">Amount</label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <input type="text" id="amount" name="amount" placeholder="Amount" class="form-control">
                                                </div>                                        
                                             </div>
                                            <div class="form-group row">
                                                <div class="col-sm-4">
                                                    <label for ="des" class="control-label">Date</label>
                                                </div>
                                                <div class="col-sm-8">
                                                     <input type="text" id="paiddate" name="paiddate" placeholder="Paid Date" class="form-control datepicker">
                                                </div>                                               
                                            </div>
                                        </form>
                                    </td>
                                </tr>                                
                            </table>                            
                        </div>                
                        <div class="text-center">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" name='pay' value="confirm" class="btn btn-primary" >Confirm Payment</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
    include_once('footer.php');
?>