<?php
    ob_start();
    include_once('navbar.php');       
     // print_r($_POST);
    if(isset($_POST['mmp_txn']) && $_POST['mmp_txn'] != '' && isset($_SESSION['payment_id'])) {
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

            $paymentData = sqlgetresult("SELECT * FROM paymentEntry('".$_SESSION['PSData']['id']."','".$_SESSION['PSData']['studentId']."','$amount','$f_code','$m_trans_id','".json_encode($_POST)."','".$desc."','".$auth_code."','$createdOn','".$_SESSION["uid"]."','".$_SESSION['payment_id']."') ");
            // print_r($_SESSION);

            if($f_code == 'Ok') {
                // $entry = sqlgetresult("SELECT *  FROM fee_entry_update('".$paymentData['paymententry']."','".$_SESSION['last_fee_entry_id']."','".$_SESSION['uid']."') ");
                date_default_timezone_set("Asia/Kolkata");    
                $cur_data = time();
                $date = date('Y-m-d h:i:s');
                $updateChallan = sqlgetresult('UPDATE tbl_challans SET "challanStatus" = 1, "updatedBy" = \''.$_SESSION['uid'].'\', "updatedOn" = \''.$date.'\' WHERE "challanNo" = \''.$_SESSION['PSData']['challanNo'].'\' ');
                // $demandtblupd =sqlgetresult('UPDATE tbl_demand SET "challanStatus" = 1, "updatedOn" = CURRENT_TIMESTAMP, "updatedBy" = \''. $_SESSION["uid"] .'\' WHERE "challanNo" =\'' . trim($_SESSION['PSData']['challanNo']) . '\'');
                $demandtblupd =sqlgetresult('UPDATE tbl_demand SET "challanStatus" = 1, "updatedOn" = CURRENT_TIMESTAMP, "updatedBy" = \''. $_SESSION["uid"] .'\' WHERE "challanNo" =\'' . trim($_SESSION['PSData']['challanNo']) . '\' AND "studentId" = \''.trim($_SESSION['PSData']['studentId']).'\' AND "term" = \''.trim($_SESSION['PSData']['term']).'\' AND "academicYear" = \''.trim($_SESSION['PSData']['academic_yr'] ).'\'');

                $waivertblupd = sqlgetresult('UPDATE tbl_waiver SET "challanStatus" = 1, "modified_on" = CURRENT_TIMESTAMP, "modified_by" = \''. $_SESSION["uid"] .'\' WHERE "challanNo" =\'' . trim($_SESSION['PSData']['challanNo']) . '\' AND "studentId" = \''.trim($_SESSION['PSData']['studentId']).'\' ');

                // $receiptupd = updatereceipt(trim($_SESSION['PSData']['challanNo']));
                createPDF($_SESSION['PSData']['studentId'],$_SESSION['PSData']['challanNo']);
                $receiptupd = updatereceipt(trim($_SESSION['PSData']['challanNo']), trim($_SESSION['PSData']['studentId']));        

                $fromwhere = 'Receipt';
                flattableentry(trim($_SESSION['PSData']['challanNo']), trim($_SESSION['PSData']['studentId']), $fromwhere);    
                // exit;
                // unset($_SESSION['last_fee_entry_id']);
                unset($_SESSION['PSData']);
                if($receiptupd > 0){
                    $_SESSION['success_msg'] = "<p class='success-msg'>Payment Completed Successfully.</p>";
                }
                
                // createErrorlog(json_encode($demandquery),'',2);
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
<div class="col-md-12">
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
	<!-- <h4>Challans</h4> -->
    <div class="col-md-12 p-r-0 p-l-0 table-responsive">
        <div class="form-group pull-right ">
            <a href="checkout.php"><button type="button" name="paychal" id="paychal" class="btn btn-info">PAY CHALLANS</button></a>
            <!--&nbsp;<a href="help/HelpDocument.pdf" target="_blank"><button type="button" name="help" id="help" class="btn btn-warning">Help</button></a>-->
        </div>
    </div>
        <!--<form id="stud_details" method="post" action="sql_actions.php">-->
            <table class="table table-bordered opayment" cellspacing="0">
                <tr>
                    <th>Student Id</th>
                    <th>Challan Number</th>
                    <th>Student Name</th>
                    <th>Class</th>
                    <th>Section</th>
                    <th>Academic Year</th>
                    <th>Semester</th>
                    <th>Amount</th>
                    <th>Action</th>
                </tr>
            </table>
       <!-- </form>-->
    </div>
    <!-- <div class="pagination"><p>Page:</p></div> -->
</div>
<hr>
<div class="container-fluid">
    <input type="hidden" name="pid" id="pid" value="<?php echo $_SESSION['uid']; ?>">
    <div class="table-responsive">
        <button type="button" class="btn button1" data-toggle="collapse" data-target="#listadv">+ Paid Challans</button>
        <div class="table-responsive collapse" id="listadv">
            <form>
                <table class="table table-bordered admintab dataTableUserPaid">
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Student ID</th>
                            <th>Student Name</th>
                            <th>Challan Number</th>
                            <th>Academic Year</th>
                            <th>Amount Paid</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </form>
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
                        <div class="text-center">
                            <p id="note" style="display: none; color:#FF0000">This unpaid challan is carried forward to the academic year 21-22 and flexible installment options have been given.</p>
                        </div>                                     
                        <div class="text-center">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <!--<button type="submit" name='pay' value="confirm" id="btnPay" class="btn btn-primary" >Confirm Payment</button>-->
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

        <!-- Pay Modal -->
<div class="modal fade" id="parentDataModal" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Update Parent Data</h4>
            </div>
            <div class="modal-body">
                <form id="updateparentdata">
                    <div class="form-group row">
                        <div class="col-sm-4">
                            <label for ="pemail" class="control-label">Email: </label>
                        </div>
                        <div class="col-sm-8">
                             <input type="email" id="pemail" name="pemail" placeholder="Enter Valid Email" required="" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-4">
                            <label for ="pmnumber" class="control-label">Mobile Number: </label>
                        </div>
                        <div class="col-sm-8">
                             <input type="number" id="pmnumber" name="pmnumber" required="" placeholder="Enter Valid Mobile Number" class="form-control">
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" name='updateparentdata' value="confirm" class="btn btn-primary" >Update</button>
                    </div>
                </form>
                <form id="otpfrm" method="post" action="sql_actions.php">
                    <p class="msg"></p>
                    <div class="form-group row">
                        <div class="col-sm-4">
                            <label for ="pmnumber" class="control-label">Enter OTP: </label>
                        </div>
                        <div class="col-sm-8">
                             <input type="number" id="otp" name="otp" required="" placeholder="Enter Valid OTP" class="form-control">
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" name='updateparentdata' value="confirm" class="btn btn-primary" >Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Add Child</h4>
        </div>
        <div class="modal-body con">
        <h4 class="addtitle">Please provide the below details.</h4>
        <!-- <h4 class="addtitle">Please provide the following details from current challan.</h4> -->
        <form name="addstudent" class="form-horizontal" id="studentdetailschecked" method=POST >
            
                <label class="control-label  col-sm-5" for="email">Registered MobileNo:</label>
                <div class="col-sm-7 form-group">
                    <input name="mobile_number" title="Please enter mobile Number" id="Mobile_number" type="text" placeholder="Mobile No." class="form-control" autofocus="" required="" readonly="" value="<?php echo $_SESSION['phn']; ?>">
                    </div>
                <label class="control-label col-sm-5" for="email">Student ID:</label>
                <div class="col-sm-7 form-group">
                 <input name="student_number" title="Student ID from current challan" id="student_number" type="text" placeholder="Student ID No." class="form-control stdid" autofocus="" required="">
                </div>
                <div class="stddetailscheck">
                    <label class="control-label  col-sm-5" for="email">Student Name:</label>
                        <div class="col-sm-7 form-group">
                           <span class="form-control stdname"></span>
                        </div>
                    
                    <label class="control-label  col-sm-5" for="email">Class:</label>
                        <div class="col-sm-7 form-group">
                            <span class="form-control stdclass"></span>
                        </div>
                </div>
                    <div style="margin-left:220px;" class="form-group">
                        <button class="btn btn-default" type="button" data-dismiss="modal">Close</button>
                        <button class="btn btn-primary addMyStudent" type="button" name="addstudent" value="addstudent">Check</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="instuctionsmodal" role="dialog">
    <div class="modal-dialog modal-lg">
    <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Instructions</h4>
            </div>
            <div class="modal-body" style="padding: 0px 35px;margin: 15px 0">
                <h4 class="text-center">Lalaji Memorial Omega International School</h4>
                <h4 class="text-center">FEEAPP – Online Fee Remittance</h4>
                <h4 class="text-center">NOTE TO PARENTS</h4>
                <div class="row">
                    <p>Dear Parents/Students,</p>
                    <p><strong>Please follow the steps below to pay the fees online</strong></p>
                    <ol>
                        <li>The last date for payment of fees is 20th March 2020 (For Existing Students).</li>
                        <li>Parents should have registered giving their Mail IDs and mobile numbers to receive challans.</li>
                        <li>Parents should login to FEE APP using their login credentials provided at the time of registration</li>
                        <li>After successful login, you can view My children page from where E-challan will be displayed for the child you had added.</li>
                        <li>Click “Pay challan” to open the challan. Check for the correctness of the child’s ID, Name, class and Fee Details.</li>
                        <li>Three FEE groups will be displayed. SCHOOL FEE, SCHOOL UTILITY FEE and SFS UTILITY FEE.</li>
                        <li>Term and Tuition Fee which is mandatory will be displayed under SCHOOL FEE.</li>
                        <li>Snacks and Other Services Fees which is mandatory will be displayed under SCHOOL UTILITY FEE.</li>
                        <li>Lunch fees is optional which can be chosen from drop down box under School Utilities.</li>
                        <!-- <li>Parents can choose Lunch if they would like to opt .</li> -->
                        <li>For transport fee, Call transport department (044-66241109) for your requirements (Change or new application or cancellation).</li>
                        <!-- <li>For changes in the transportation stages, please contact transport department. </li> -->
                        <li>Select the quantity of the uniforms/shoes if you need more than 1.</li>
                        <li>Check for the correctness of the total amount.</li>
                        <li>Check the grand total.</li>
                        <li>By default, payment mode would be online.</li>
                        <li>Click Confirm Payment to proceed with online payment.</li>
                        <li>You will be redirected to the payment gateway.</li>
                        <li>Make payment as per the instructions.</li>
                        <li>Once payment is successful, you will be redirected to the Fee App and will be able to see the paid challan with the “PAID” stamp. </li>
                        <li>For any issues related to the payment please mail us feeapp.support@omegaschools.org or call us @ 044-66241110</li>
                    </ol>
                    <p>For generation of receipts reach out to accounts@omegaschools.org with Subject.</p>
                    <p><b>"Receipt not Generated for payment – Student ID (XXXXX)”</b> a settlement report will be made available by Bank (within three days from date of remittance) post that accounts team can check and generate Receipt and mail parents.</p>
                    <p>Parents are advised to print the receipts, which has to be produced for obtaining uniform materials, books and note books. With regard to transport facility, Parents/Students are requested to approach transport department for verification of the applicable stage charged in the challan before remittance of fee.</p>
                </div>
                <!-- <h4 class="addtitle">Please provide the following details from current challan.</h4> -->
            </div>
            <div class="modal-footer text-center">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<?php
    include_once('footer.php');
?>