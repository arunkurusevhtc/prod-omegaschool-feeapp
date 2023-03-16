<?php
    ob_start();
    include_once('navbar.php');       
    
     // print_r($_POST);
    if(isset($_POST['amt']) && $_POST['amt'] != '') {
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
            // print_r($_SESSION['PSNFWCData']);
            $paymentData = sqlgetresult("SELECT * FROM nonfeechallanpaymententry('".$_SESSION['PSNFWCData']['id']."','".$_SESSION['PSNFWCData']['studentId']."','$amount','$f_code','$m_trans_id','".json_encode($_POST)."','".$desc."','$createdOn','".$_SESSION["uid"]."','".$_SESSION['nonfeewchallanpayment_id']."') ");  
            
            if($f_code == 'Ok') {       
                date_default_timezone_set("Asia/Kolkata");    
                $cur_data = time();
                $date = date('Y-m-d h:i:s');         
                $updateChallan = sqlgetresult('UPDATE tbl_nonfee_challans SET "challanStatus" = 1, "updatedBy" = \''.$_SESSION['uid'].'\', "updatedOn" =  \''.$date.'\' WHERE "challanNo" = \''.$_SESSION['PSNFWCData']['challanNo'].'\' ');
                $updatePaymentTable = sqlgetresult('UPDATE tbl_nonfee_payments SET "challanNo" = \''.$_SESSION['PSNFWCData']['challanNo'].'\' WHERE "id" = \''.$_SESSION['nonfeewchallanpayment_id'].'\' ');
                createNFWPDF($_SESSION['PSNFWCData']['studentId'],$_SESSION['PSNFWCData']['challanNo'],'');
                // exit;
                unset($_SESSION['PSNFWCData']);
                $_SESSION['success_msg'] = "<p class='success-msg'>Non-fee Payment Completed Successfully.</p>";
                header("Location: nonfeewithoutchln.php");
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
                header("Location: nonfeewithoutchln.php");
            }
           
        } else {
            // print_r($_POST);exit;
            createErrorlog(json_encode($_POST),"Something gone wrong.",1);
            $_SESSION['error_msg'] = "<p class='error-msg'>Invalid Signature. Please try again later.</p>";
        }
        // createErrorlog(json_encode($_POST),"Something gone wrong.",1);
        $_POST = '';
    } 

    $data = sqlgetresult('SELECT * FROM nonfeechallandata WHERE "parentId" = \''.$_SESSION['uid'].'\' AND "challanStatus" = 0 AND "visible" = \'0\' ', true);       
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
<div class="container-fluid">
	<div class="row">
        <div class="col-md-10">
            <h4>Non-Fee Without Challans</h4> 
        </div>        
    </div>
    <div class="col-md-12 p-r-0 p-l-0 table-responsive">
        <form id="stud_details" method="post" action="sql_actions.php">
            <table class="table table-bordered ononfeepayment" cellspacing="0">
                <tr>
                    <th>S.No</th>
                    <th>Student Name</th>
                    <th>Class</th>
                    <th>Section</th>
                    <th>Semester</th>
                    <th>FeeType</th>
                    <th>Amount</th>
                    <th>Action</th>
                </tr>
                <tbody>
                    <?php
                        if(sizeof($data) > 0) {
                            $i = 1;
                            foreach ($data as $value) {
                                echo "<tr><td>".$i."</td>";
                                echo "<td>".$value['studentName']."</td>";
                                echo "<td>".$value['class_list']."</td>";
                                echo "<td>".$value['section']."</td>";
                                echo "<td>".$value['term']."</td>";
                                echo "<td>".$value['feename']."</td>";
                                echo "<td>".$value['total']."</td>";
                                echo '<td><input type="hidden" name="studId" value="'.$value['studentId'].'"/><input type="hidden" name="total" value="'.$value['total'].'"/><input type="hidden" name="challanNo" value="'.$value['challanNo'].'"/><a href="sql_actions.php?nonfeechallan='.$value['challanNo'].'&amount='.$value['total'].'&studentId='.$value['studentId'].'";><b>Pay<b/></a></td></tr>';
                                $i++;
                            }
                        } else {
                            echo "<td colspan='8'><center>No Non-fee without challans found!!!</center></td>";
                        }
                    ?>
                </tbody>
            </table>
        </form>
    </div>
    <!-- <div class="pagination"><p>Page:</p></div> -->
</div>
<hr>
<div class="container-fluid">
    <div class="table-responsive">
        <button type="button" class="btn button1" data-toggle="collapse" data-target="#list">+ Non-fee Without Challans Paid</button>
        <div id="list" class="collapse">
            <table class="table table-bordered opayment1" cellspacing="0" width="400">
                <?php
                    $paiddata = sqlgetresult('SELECT "feeGroup","studentId","studentName","challanNo","total","updatedOn" FROM nonfeechallandata WHERE "challanStatus" = 1 AND "parentId" = \''.$_SESSION['uid'].'\' AND "visible" = \'0\' ' , true);          

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

<div class="row comment">
    
</div>

<!-- Pay Modal -->
<div class="modal fade" id="nonpayModal" role="dialog">
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
                        <div id="nonfeechallanData"></div>                                     
                        <div class="text-center">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" name='paynonfeechallan' value="confirm" class="btn btn-primary" >Confirm Payment</button>
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