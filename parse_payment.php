<?php
    require_once('config.php');	
    require_once 'atompay/TransactionResponse.php';
    date_default_timezone_set("Asia/Kolkata");    
    $cur_data = time();
    $date = date('Y-m-d h:i:s');
    if(isset($_GET['commonnonfee']) && $_GET['commonnonfee']!=''){
        $transactionResponse = new TransactionResponse();
        $transactionResponse->setRespHashKey($respHashKey);
        $decoded_data = base64_decode($_GET['commonnonfee']);
        $store_array = explode("_",$decoded_data);
        $parent_id = $store_array[0];
        $student_id = $store_array[1];
        $payment_id = $store_array[3];
        $challanNo = $store_array[4];
        $current_user_data = sqlgetresult("SELECT * from loginchk WHERE id='".$parent_id."' AND status ='1'");
        $_SESSION['uid']=$current_user_data['id'];
        $_SESSION['login_user'] = $current_user_data['email'];
        $_SESSION['login_user1'] = $current_user_data['secondaryEmail'];
        $_SESSION['fstname'] = $current_user_data['firstName'];
        $_SESSION['lstname'] = $current_user_data['lastName'];
        $_SESSION['phn'] = $current_user_data['mobileNumber'];
        $_SESSION['sob'] = $current_user_data['secondaryNumber'];
        $_SESSION['sessLoginType'] = 'Parents';
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
            $paymentData = sqlgetresult("SELECT * FROM nonfeechallanpaymententry('".$parent_id."','".$student_id."','$amount','$f_code','$m_trans_id','".json_encode($_POST)."','".$desc."','$createdOn','".$parent_id."','".$payment_id."') ");
            if($f_code == 'Ok') { 

            $updatePaymentTable = sqlgetresult('UPDATE tbl_nonfee_payments SET "challanNo" = \''.$challanNo.'\' WHERE "id" = \''.$paymentData['nonfeechallanpaymententry'].'\' ');                
                createCFPDF($paymentData['nonfeechallanpaymententry'],$student_id);
                $_SESSION['success_msg'] = "<p class='success-msg'>Payment Completed Successfully.</p>";
                $smsTxt = urlencode("Dear Parent, You have successfully paid the event amount successfully.");
                $mblNumber = $_SESSION["phn"];
                $smsURL = "$smsBaseurl/sms.aspx?Id=$smsLoginId&Pwd=$smsLoginPass&PhNo=$mblNumber&text=$smsTxt";
                header("Location: commonnonfee.php");
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
                header("Location: commonnonfee.php");
                exit;
            }
           
        } else {
            createErrorlog(json_encode($_POST),"Something gone wrong.",1);
            $_SESSION['error_msg'] = "<p class='error-msg'>Invalid Signature. Please confirm with accounts team before trying again.</p>";
            header("Location: commonnonfee.php");
            exit;
        }
        createErrorlog(json_encode($_POST),"Something gone wrong.",1);
        $_SESSION['error_msg'] = "<p class='error-msg'>Invalid Signature. Please confirm with accounts team before trying again.</p>";
        header("Location: commonnonfee.php");
    }
    if(isset($_GET['nonfeewithoutchln']) && $_GET['nonfeewithoutchln'] != '') {
        // print_r($_POST);
        require_once 'atompay/TransactionResponse.php';
        $transactionResponse = new TransactionResponse();
        $transactionResponse->setRespHashKey($respHashKey);

        if($transactionResponse->validateResponse($_POST)){
            $decoded_data = base64_decode($_GET['nonfeewithoutchln']);
            $store_array = explode("_",$decoded_data);
            $parent_id = $store_array[0];
            $student_id = $store_array[1];
            $payment_id = $store_array[3];
            $challanNo = $store_array[4];
            $current_user_data = sqlgetresult("SELECT * from loginchk WHERE id='".$parent_id."' AND status ='1'");
            $_SESSION['uid']=$current_user_data['id'];
            $_SESSION['login_user'] = $current_user_data['email'];
            $_SESSION['login_user1'] = $current_user_data['secondaryEmail'];
            $_SESSION['fstname'] = $current_user_data['firstName'];
            $_SESSION['lstname'] = $current_user_data['lastName'];
            $_SESSION['phn'] = $current_user_data['mobileNumber'];
            $_SESSION['sob'] = $current_user_data['secondaryNumber'];
            $_SESSION['sessLoginType'] = 'Parents';
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
            $paymentData = sqlgetresult("SELECT * FROM nonfeechallanpaymententry('".$parent_id."','".$student_id."','$amount','$f_code','$m_trans_id','".json_encode($_POST)."','".$desc."','$createdOn','".$parent_id."','".$payment_id."') ");  
            if($f_code == 'Ok') {       
                date_default_timezone_set("Asia/Kolkata");    
                $cur_data = time();
                $date = date('Y-m-d h:i:s');         
                $updateChallan = sqlgetresult('UPDATE tbl_nonfee_challans SET "challanStatus" = 1, "updatedBy" = \''.$parent_id.'\', "updatedOn" =  \''.$date.'\' WHERE "challanNo" = \''.$challanNo.'\' ');
                createNFWPDF($student_id,$challanNo,'');
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
            $_SESSION['error_msg'] = "<p class='error-msg'>Invalid Signature. Please confirm with accounts team before trying again.</p>";
        }
        // createErrorlog(json_encode($_POST),"Something gone wrong.",1);
        $_POST = '';
    }
    if(isset($_GET['nonfeepayments']) && $_GET['nonfeepayments'] != '') {
        // print_r($_POST);
        require_once 'atompay/TransactionResponse.php';
        $transactionResponse = new TransactionResponse();
        $transactionResponse->setRespHashKey($respHashKey);

        if($transactionResponse->validateResponse($_POST)){
            $decoded_data = base64_decode($_GET['nonfeepayments']);
            $store_array = explode("_",$decoded_data);
            $parent_id = $store_array[0];
            $student_id = $store_array[1];
            $payment_id = $store_array[3];
            $challanNo = $store_array[4];
            $current_user_data = sqlgetresult("SELECT * from loginchk WHERE id='".$parent_id."' AND status ='1'");
            $_SESSION['uid']=$current_user_data['id'];
            $_SESSION['login_user'] = $current_user_data['email'];
            $_SESSION['login_user1'] = $current_user_data['secondaryEmail'];
            $_SESSION['fstname'] = $current_user_data['firstName'];
            $_SESSION['lstname'] = $current_user_data['lastName'];
            $_SESSION['phn'] = $current_user_data['mobileNumber'];
            $_SESSION['sob'] = $current_user_data['secondaryNumber'];
            $_SESSION['sessLoginType'] = 'Parents';
            $m_trans_id = $_POST['mer_txn']; //atomtransactionID. The ID is generatedby atom
            $a_trans_id = $_POST['mmp_txn']; //Merchant'stransactionID
            $amount = $_POST['amt'];
            $createdOn = $_POST['date'];
            $b_trans_id = $_POST['bank_txn']; //Bank TransactionID. This ID is generatedby the Bank
            $f_code = $_POST['f_code'];
            $createdOn = $_POST['date'];
            $desc = isset($_POST['desc']) ? $_POST['desc'] : '';
            $auth_code = isset($_POST['auth_code']) ? $_POST['auth_code'] : '';

            $paymentData = sqlgetresult("SELECT * FROM nonfeechallanpaymententry('".$parent_id."','".$student_id."','$amount','$f_code','$m_trans_id','".json_encode($_POST)."','".$desc."','$createdOn','".$parent_id."','".$payment_id."') ");  
            
            if($f_code == 'Ok') {       
                date_default_timezone_set("Asia/Kolkata");    
                $cur_data = time();
                $date = date('Y-m-d h:i:s');         
                $updateChallan = sqlgetresult('UPDATE tbl_nonfee_challans SET "challanStatus" = 1, "updatedBy" = \''.$parent_id.'\', "updatedOn" =  \''.$date.'\' WHERE "challanNo" = \''.$challanNo.'\' ');
                createNFPDF($student_id,$challanNo,'');
                // exit;
                unset($_SESSION['PSNFCData']);
                $_SESSION['success_msg'] = "<p class='success-msg'>Non-fee Payment Completed Successfully.</p>";
                header("Location: nonfeepayments.php");
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
                header("Location: nonfeepayments.php");
            }
           
        } else {
            // print_r($_POST);exit;
            createErrorlog(json_encode($_POST),"Something gone wrong.",1);
            $_SESSION['error_msg'] = "<p class='error-msg'>Invalid Signature. Please confirm with accounts team before trying again.</p>";
        }
        // createErrorlog(json_encode($_POST),"Something gone wrong.",1);
        $_POST = '';
    }
    if(isset($_GET['cardtopup']) && $_GET['cardtopup'] != '' ) {
        // print_r($_POST);
        require_once 'atompay/TransactionResponse.php';
        $transactionResponse = new TransactionResponse();
        $transactionResponse->setRespHashKey($respHashKey);

        if($transactionResponse->validateResponse($_POST)){
            $decoded_data = base64_decode($_GET['cardtopup']);
            $store_array = explode("_",$decoded_data);
            $parent_id = $store_array[0];
            $student_id = $store_array[1];
            $payment_id = $store_array[3];
            $challanNo = $store_array[4];
            $current_user_data = sqlgetresult("SELECT * from loginchk WHERE id='".$parent_id."' AND status ='1'");
            $_SESSION['uid']=$current_user_data['id'];
            $_SESSION['login_user'] = $current_user_data['email'];
            $_SESSION['login_user1'] = $current_user_data['secondaryEmail'];
            $_SESSION['fstname'] = $current_user_data['firstName'];
            $_SESSION['lstname'] = $current_user_data['lastName'];
            $_SESSION['phn'] = $current_user_data['mobileNumber'];
            $_SESSION['sob'] = $current_user_data['secondaryNumber'];
            $_SESSION['sessLoginType'] = 'Parents';
            $m_trans_id = $_POST['mer_txn']; //atomtransactionID. The ID is generatedby atom
            $a_trans_id = $_POST['mmp_txn']; //Merchant'stransactionID
            $amount = $_POST['amt'];
            $createdOn = $_POST['date'];
            $b_trans_id = $_POST['bank_txn']; //Bank TransactionID. This ID is generatedby the Bank
            $f_code = $_POST['f_code'];
            $createdOn = $_POST['date'];
            $desc = isset($_POST['desc']) ? $_POST['desc'] : '';
            $auth_code = isset($_POST['auth_code']) ? $_POST['auth_code'] : '';
            $paymentData = sqlgetresult("SELECT * FROM nonfeepaymententry('".$parent_id."','".$student_id."','$amount','$f_code','$m_trans_id','".json_encode($_POST)."','".$desc."','$createdOn','".$parent_id."','".$payment_id."') ");     
            if($f_code == 'Ok') {                
                createCFPDF($paymentData['nonfeepaymententry']);
                // exit;
                unset($_SESSION['PSNFData']);
                $_SESSION['success_msg'] = "<p class='success-msg'>Payment Completed Successfully.</p>";
                $smsTxt = urlencode("Dear Parent, You have successfully top-up the amount of ".$amount." to your child's account.");
                $mblNumber = $_SESSION["phn"];
                $smsURL = "$smsBaseurl/sms.aspx?Id=$smsLoginId&Pwd=$smsLoginPass&PhNo=$mblNumber&text=$smsTxt";
                // $ret = file($smsURL);
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
            $_SESSION['error_msg'] = "<p class='error-msg'>Invalid Signature. Please confirm with accounts team before trying again.</p>";
            header("Location: cardtopup.php");
            exit;
        }
        // createErrorlog(json_encode($_POST),"Something gone wrong.",1);
        $_POST = '';
    }
    if(isset($_GET['studetscr']) && $_GET['studetscr'] != '') {
        // print_r($_POST); 
        require_once 'atompay/TransactionResponse.php';
        $transactionResponse = new TransactionResponse();
        $transactionResponse->setRespHashKey($respHashKey);

        if($transactionResponse->validateResponse($_POST)){
            $decoded_data = base64_decode($_GET['studetscr']);
            $store_array = explode("_",$decoded_data);
            $parent_id = $store_array[0];
            $student_id = $store_array[1];
            $payment_id = $store_array[3];
            $challanNo = $store_array[4];
            $term = $store_array[5];
            $acad_year = $store_array[6];
            $current_user_data = sqlgetresult("SELECT * from loginchk WHERE id='".$parent_id."' AND status ='1'");
            $_SESSION['uid']=$current_user_data['id'];
            $_SESSION['login_user'] = $current_user_data['email'];
            $_SESSION['login_user1'] = $current_user_data['secondaryEmail'];
            $_SESSION['fstname'] = $current_user_data['firstName'];
            $_SESSION['lstname'] = $current_user_data['lastName'];
            $_SESSION['phn'] = $current_user_data['mobileNumber'];
            $_SESSION['sob'] = $current_user_data['secondaryNumber'];
            $_SESSION['sessLoginType'] = 'Parents';
            $m_trans_id = $_POST['mer_txn']; //atomtransactionID. The ID is generatedby atom
            $a_trans_id = $_POST['mmp_txn']; //Merchant'stransactionID
            $amount = $_POST['amt'];
            $createdOn = $_POST['date'];
            $b_trans_id = $_POST['bank_txn']; //Bank TransactionID. This ID is generatedby the Bank
            $f_code = $_POST['f_code'];
            $createdOn = $_POST['date'];
            $desc = isset($_POST['desc']) ? $_POST['desc'] : '';
            $auth_code = isset($_POST['auth_code']) ? $_POST['auth_code'] : '';

            $paymentData = sqlgetresult("SELECT * FROM paymentEntry('".$parent_id."','".$student_id."','$amount','$f_code','$m_trans_id','".json_encode($_POST)."','".$desc."','".$auth_code."','$createdOn','".$parent_id."','".$payment_id."') ");
            // print_r($_SESSION);

            if($f_code == 'Ok') {
                // $entry = sqlgetresult("SELECT *  FROM fee_entry_update('".$paymentData['paymententry']."','".$_SESSION['last_fee_entry_id']."','".$_SESSION['uid']."') ");
                date_default_timezone_set("Asia/Kolkata");    
                $cur_data = time();
                $date = date('Y-m-d h:i:s');
                $updateChallan = sqlgetresult('UPDATE tbl_challans SET "challanStatus" = 1, "updatedBy" = \''.$parent_id.'\', "updatedOn" = \''.$date.'\' WHERE "challanNo" = \''.$challanNo.'\' ');
                // $demandtblupd =sqlgetresult('UPDATE tbl_demand SET "challanStatus" = 1, "updatedOn" = CURRENT_TIMESTAMP, "updatedBy" = \''. $_SESSION["uid"] .'\' WHERE "challanNo" =\'' . trim($_SESSION['PSData']['challanNo']) . '\'');
                $demandtblupd =sqlgetresult('UPDATE tbl_demand SET "challanStatus" = 1, "updatedOn" = CURRENT_TIMESTAMP, "updatedBy" = \''. $parent_id .'\' WHERE "challanNo" =\'' . trim($challanNo) . '\' AND "studentId" = \''.trim($student_id).'\' AND "term" = \''.trim($term).'\' AND "academicYear" = \''.trim($acad_year ).'\'');

                $waivertblupd = sqlgetresult('UPDATE tbl_waiver SET "challanStatus" = 1, "modified_on" = CURRENT_TIMESTAMP, "modified_by" = \''. $parent_id.'\' WHERE "challanNo" =\'' . trim($challanNo) . '\' AND "studentId" = \''.trim($student_id).'\' ');

                // $receiptupd = updatereceipt(trim($_SESSION['PSData']['challanNo']));
                createPDF($student_id,$challanNo);
                $receiptupd = updatereceipt(trim($challanNo), trim($student_id));  

                $fromwhere = 'Receipt';
                flattableentry(trim($challanNo), trim($student_id), $fromwhere);         
                // exit;
                // unset($_SESSION['last_fee_entry_id']);
                unset($_SESSION['PSData']);
                if($receiptupd > 0){
                    $_SESSION['success_msg'] = "<p class='success-msg'>Payment Completed Successfully.</p>";
                }
                header("Location: studetscr.php");
                exit;
                // createErrorlog(json_encode($demandquery),'',2);
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
                header("Location: studetscr.php");
                exit;
            }
        } else {
            createErrorlog(json_encode($_POST),"Something gone wrong.",1);
            $_SESSION['error_msg'] = "<p class='error-msg'>Invalid Signature. Please confirm with accounts team before trying again.</p>";
            header("Location: studetscr.php");
                exit;
        }
        // createErrorlog(json_encode($_POST),"Something gone wrong.",1);
        $_POST = '';
    } 

    /* Uniform Fee Start */
    if(isset($_GET['advancePayment']) && $_GET['advancePayment']!=''){
        // print_r($_POST);
        $transactionResponse = new TransactionResponse();
        $transactionResponse->setRespHashKey($respHashKey);
        $decoded_data = base64_decode($_GET['advancePayment']);
      
            $store_array = explode("_",$decoded_data);
            $parent_id = $store_array[0];
            $student_id = $store_array[1];
            $payment_id = $store_array[3];
            $challanNo = $store_array[4];
            $sid = $store_array[5];
            $current_user_data = sqlgetresult("SELECT * from loginchk WHERE id='".$parent_id."' AND status ='1'");
            $_SESSION['uid']=$current_user_data['id'];
            $_SESSION['login_user'] = $current_user_data['email'];
            $_SESSION['login_user1'] = $current_user_data['secondaryEmail'];
            $_SESSION['fstname'] = $current_user_data['firstName'];
            $_SESSION['lstname'] = $current_user_data['lastName'];
            $_SESSION['phn'] = $current_user_data['mobileNumber'];
            $_SESSION['sob'] = $current_user_data['secondaryNumber'];
            $_SESSION['sessLoginType'] = 'Parents';
        if($transactionResponse->validateResponse($_POST)){
            $m_trans_id = $_POST['mer_txn']; //atomtransactionID. The ID is generatedby atom
            //$a_trans_id = $_POST['mmp_txn']; //Merchant'stransactionID
            $a_trans_id = isset($_POST['mmp_txn']) ? $_POST['mmp_txn'] : 'NA';
            $amount = $_POST['amt'];
            $createdOn = $_POST['date'];
            $b_trans_id = $_POST['bank_txn']; //Bank TransactionID. This ID is generatedby the Bank
            //$f_code = isset($_POST['f_code']) ? $_POST['f_code'] : 'NA';
            $f_code = isset($_POST['f_code']) ? trim($_POST['f_code']) : 'F';
            $createdOn = $_POST['date'];
            $desc = isset($_POST['desc']) ? $_POST['desc'] : 'NA';

            $desc=substr($desc, 0, 30);
            $auth_code = isset($_POST['auth_code']) ? $_POST['auth_code'] : '';

            $paymentData = sqlgetresult("SELECT * FROM advancetransactionentry('$amount','$f_code','".json_encode($_POST)."','".$desc."','".$a_trans_id."','$createdOn','".$parent_id."','".$payment_id."') ");;
           
            if($f_code == 'Ok') { 

                $balance=toGetAvailableBalance($sid);
                $tot=$amount+$balance;
                //echo "SELECT * FROM addAdvanceAmt('".$sid."','$tot','".$parent_id."')";
                //exit;
                $wallet = sqlgetresult("SELECT * FROM addAdvanceAmt('".$sid."','$tot','".$parent_id."')");

                createPDF_advance($paymentData['advancetransactionentry']);
                // exit;
                unset($_SESSION['PSLFData']);
                print_r("SUCCESS");
                $_SESSION['success_msg'] = "<p class='success-msg'>Payment Completed Successfully.</p>";
                $smsTxt = urlencode("Dear Parent, You have successfully added the advance amount successfully.");
                $mblNumber = $_SESSION["phn"];
                $smsURL = "$smsBaseurl/sms.aspx?Id=$smsLoginId&Pwd=$smsLoginPass&PhNo=$mblNumber&text=$smsTxt";
                // $ret = file($smsURL);
                //header("Location: advance.php");
                //exit;
            } else {
                createErrorlog(json_encode($_POST));
                if($desc == "Transction Failure") {
                    //createErrorlog(json_encode($_POST));
                    $_SESSION['error_msg'] = "<p class='error-msg'>Your Transaction has been failed. Please try again later.</p>";
                } elseif ($_POST['f_code'] == 'C' ) {
                    //createErrorlog(json_encode($_POST));
                    $_SESSION['error_msg'] = "<p class='error-msg'>Your Transaction has been cancelled. Please try again later.</p>";
                } elseif ($_POST['f_code'] == 'F' ) {
                    //createErrorlog(json_encode($_POST));
                    $_SESSION['error_msg'] = "<p class='error-msg'>Your Transaction has been failed. Please try again later.</p>";
                } else {
                   // createErrorlog(json_encode($_POST));
                    // print_r($_POST);
                    $_SESSION['error_msg'] = "<p class='error-msg'>".$desc." Please try again later.</p>";
                }
               // header("Location: advance.php");
               // exit;
              

            }
           
        } else {
            createErrorlog(json_encode($_POST),"Something gone wrong.",1);
            $_SESSION['error_msg'] = "<p class='error-msg'>Invalid Signature. Please confirm with accounts team before trying again.</p>";
            // header("Location: lunchfee.php");
            //exit;
        }
        createErrorlog(json_encode($_POST),"Something gone wrong.",1);
        $_POST = '';
        header("Location: advance.php");
        exit;
    } 

    /* partialPayment Fee Start */
    if(isset($_GET['partialPayment']) && $_GET['partialPayment']!=''){
        date_default_timezone_set("Asia/Kolkata");    
        $date = date('Y-m-d h:i:s');
        require_once 'atompay/TransactionResponse.php';
        $transactionResponse = new TransactionResponse();
        $transactionResponse->setRespHashKey($respHashKey);

        $decoded_data = base64_decode($_GET['partialPayment']);
        $store_array = explode("_",$decoded_data);
        //$parent_id = $store_array[0];
        $student_id = trim($store_array[0]);
        $payment_id = $store_array[1];
        $paymentData = sqlgetresult('SELECT * FROM tbl_partial_payment_log WHERE id = \''.$payment_id.'\' LIMIT 1',true);
        $challanNo = "REF".$payment_id;
        $grand_tot = $store_array[2];
        $challanids = trim($paymentData[0]['challanids']);
        $balance = $store_array[3];
        $partialamt = $store_array[4];

        $caution_json = ($paymentData[0]['partial_caution_deposit'])?trim($paymentData[0]['partial_caution_deposit']):"";
        $payoption = ($paymentData[0]['payoption'])?trim($paymentData[0]['payoption']):"";

        $parentData = sqlgetresult('SELECT * FROM getparentdata WHERE "studentId" = \''.$student_id.'\' LIMIT 1',true);
        $acad_year = trim($parentData[0]['academic_yr']);
        $term = trim($parentData[0]['term']);
        $stream = trim($parentData[0]['stream']); 
        $sid = $parentData[0]['sid'];
        $class = trim($parentData[0]['class']);
        $section = trim($parentData[0]['section']);
        $parent_id = $parentData[0]['id'];
     

        $current_user_data = sqlgetresult("SELECT * from loginchk WHERE id='".$parent_id."' AND status ='1'");
        $_SESSION['uid']=$current_user_data['id'];
        $_SESSION['login_user'] = $current_user_data['email'];
        $_SESSION['login_user1'] = $current_user_data['secondaryEmail'];
        $_SESSION['fstname'] = $current_user_data['firstName'];
        $_SESSION['lstname'] = $current_user_data['lastName'];
        $_SESSION['phn'] = $current_user_data['mobileNumber'];
        $_SESSION['sob'] = $current_user_data['secondaryNumber'];
        $_SESSION['sessLoginType'] = 'Parents';
        
        $empty='';

        if($transactionResponse->validateResponse($_POST)){
            $m_trans_id = isset($_POST['mer_txn']) ? $_POST['mer_txn'] : ''; //atomtransactionID. The ID is generatedby atom
            $a_trans_id = isset($_POST['mmp_txn']) ? $_POST['mmp_txn'] : 'NA'; //Merchant'stransactionID
            $amount = $_POST['amt'];
            $createdOn = $_POST['date'];
            $b_trans_id = $_POST['bank_txn']; //Bank TransactionID. This ID is generatedby the Bank
            //$f_code = trim($_POST['f_code']);
            $f_code = isset($_POST['f_code']) ? trim($_POST['f_code']) : 'F';
            $createdOn = $_POST['date'];
            $desc = isset($_POST['desc']) ? $_POST['desc'] : 'NA';
            $auth_code = isset($_POST['auth_code']) ? $_POST['auth_code'] : '';

            $paymentData = sqlgetresult("SELECT * FROM partialtransactionentry('$amount','$f_code','".json_encode($_POST)."','".$desc."','".$a_trans_id."','$createdOn','".$parent_id."','".$payment_id."') ");
            if($f_code == 'Ok') {
                /* caution deposit partial */
                if(!empty($caution_json) && $payoption=='caution'){
                    $chkcaution = toUpdateFeeTypePaidAmt($caution_json);
                }
                /* Update Balance Start */
                if(isset($balance) && !empty($balance)){
                    $curbalance=toGetAvailableBalance($sid);
                    $upewal=$curbalance-$balance;
                    $wallet = sqlgetresult("SELECT * FROM addAdvanceAmt('".$sid."','$upewal','".$parent_id."')");

                    $resadv=$wallet['addadvanceamt']?$wallet['addadvanceamt']:"";
                    if(!empty($resadv)){
                       // echo "SELECT * FROM createadvancetransaction('$sid','$parent_id','','$balance','$curbalance','2','Ok','$payment_id','$challanNo','1','$class','$acad_year','$stream','$term')";
                        //exit;
                        $run1=sqlgetresult("SELECT * FROM createadvancetransaction('$sid','$parent_id','','$balance','$curbalance','2','Ok','$payment_id','$challanNo','1','$class','$acad_year','$stream','$term','$section')",true);
                    }
                    $amount=$amount+$balance;
                }
                //$cur_pabal=toGetPartialAmount($sid);
                //$updt=$cur_pabal-$partialamt;

                $receivedAmt=$amount+$balance;

                partialPayProcessModified($challanids, $sid, $parent_id, $student_id, $term, $acad_year, $amount, $payment_id, $createdOn, $balance);
                if(!empty($caution_json) && $payoption=='caution'){
                    toCheckFeeTypePartial($caution_json);
                }
                $_SESSION['success_msg'] = "<p class='success-msg'>Payment Completed Successfully.</p>";
            } else {
                if($_POST['desc'] == "Transction Failure") {
                    createErrorlog(json_encode($_POST));
                    $_SESSION['error_msg'] = "<p class='error-msg'>Your Transaction has been failed. Please try again later.</p>";
                } elseif ($_POST['f_code'] == 'C' ) {
                    createErrorlog(json_encode($_POST));
                    $_SESSION['error_msg'] = "<p class='error-msg'>Your Transaction has been cancelled. Please try again later.</p>";
                } else {
                    createErrorlog(json_encode($_POST));
                    //$_SESSION['error_msg'] = "<p class='error-msg'>Some Error Has Occurred. Please try again later.</p>";
                    $_SESSION['error_msg'] = "<p class='error-msg'>".$desc.". Please try again later.</p>";
                }
            }
        } else {
            createErrorlog(json_encode($_POST),"Something gone wrong.",1);
            $_SESSION['error_msg'] = "<p class='error-msg'>Invalid Signature. Please confirm with accounts team before trying again.</p>";
        }
        $_POST = '';
       header("Location: checkout.php");
       exit;
    } 

    if(isset($_GET['sumofchallans']) && $_GET['sumofchallans'] != '') {
        require_once 'atompay/TransactionResponse.php';
        $transactionResponse = new TransactionResponse();
        $transactionResponse->setRespHashKey($respHashKey);

        $decoded_data = base64_decode($_GET['sumofchallans']);
        $store_array = explode("_",$decoded_data);
        $parent_id = $store_array[0];
        $student_id = $store_array[1];
        $payment_id = $store_array[3];
        $challanNo = $store_array[4];
        $term = $store_array[5];
        $acad_year = $store_array[6];
        $sid = $store_array[7];
        $stream = $store_array[8];
        $class = $store_array[9];
        $section = $store_array[10];
        $current_user_data = sqlgetresult("SELECT * from loginchk WHERE id='".$parent_id."' AND status ='1'");
        $_SESSION['uid']=$current_user_data['id'];
        $_SESSION['login_user'] = $current_user_data['email'];
        $_SESSION['login_user1'] = $current_user_data['secondaryEmail'];
        $_SESSION['fstname'] = $current_user_data['firstName'];
        $_SESSION['lstname'] = $current_user_data['lastName'];
        $_SESSION['phn'] = $current_user_data['mobileNumber'];
        $_SESSION['sob'] = $current_user_data['secondaryNumber'];
        $_SESSION['sessLoginType'] = 'Parents';
        $m_trans_id = isset($_POST['mer_txn']) ? $_POST['mer_txn'] : ''; //atomtransactionID. The ID is generatedby atom
        $a_trans_id = isset($_POST['mmp_txn']) ? $_POST['mmp_txn'] : ''; //Merchant'stransactionID
        $amount = $_POST['amt'];
        $createdOn = $_POST['date'];
        $b_trans_id = $_POST['bank_txn']; //Bank TransactionID. This ID is generatedby the Bank
        $f_code = trim($_POST['f_code']);
        $createdOn = $_POST['date'];
        $desc = isset($_POST['desc']) ? $_POST['desc'] : '';
        $auth_code = isset($_POST['auth_code']) ? $_POST['auth_code'] : '';

        //echo "SELECT * FROM transactionentry('$amount','$f_code','".json_encode($_POST)."','".$desc."','".$auth_code."','$createdOn','".$parent_id."','".$payment_id."') ";

        //exit;


        $paymentData = sqlgetresult("SELECT * FROM transactionentry('$amount','$f_code','".json_encode($_POST)."','".$desc."','".$auth_code."','$createdOn','".$parent_id."','".$payment_id."') ");

        if($transactionResponse->validateResponse($_POST)){
            
            // print_r($_SESSION);

            if($f_code == 'Ok') {
                /* Update Balance Start */
                $balance=toGetAvailableBalance($sid);

                if($balance >= $amount){
                    $tot=$balance-$amount;
                }else{
                    $tot=0;
                }
                
                $wallet = sqlgetresult("SELECT * FROM addAdvanceAmt('".$sid."','$tot','".$parent_id."')");
                $resadv=$wallet['addadvanceamt']?$wallet['addadvanceamt']:"";
                if(!empty($resadv)){
                    $run1=sqlgetresult("SELECT * FROM createadvancetransaction('$sid','$parent_id','','$amount','$balance','2','Ok','$payment_id','$challanNo','1','$class','$acad_year','$stream','$term','$section')",true);
                }
                /* Update Balance End */
                $receiptupd=completeTransactionById($payment_id);  
                if($receiptupd > 0){
                    $_SESSION['success_msg'] = "<p class='success-msg'>Payment Completed Successfully.</p>";
                }
                header("Location: studetscr.php");
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
                header("Location: studetscr.php");
                exit;
            }
        } else {
            createErrorlog(json_encode($_POST),"Something gone wrong.",1);
            $_SESSION['error_msg'] = "<p class='error-msg'>Invalid Signature. Please confirm with accounts team before trying again.</p>";
            header("Location: studetscr.php");
                exit;
        }
        // createErrorlog(json_encode($_POST),"Something gone wrong.",1);
        $_POST = '';
    } 

/* Lunch Fee Start */
if(isset($_GET['lunchfeepayments']) && $_GET['lunchfeepayments']!=''){
    // print_r($_POST);
    $transactionResponse = new TransactionResponse();
    $transactionResponse->setRespHashKey($respHashKey);
    $decoded_data = base64_decode($_GET['lunchfeepayments']);


        $store_array = explode("_",$decoded_data);
        $parent_id = $store_array[0];
        $student_id = $store_array[1];
        $payment_id = $store_array[3];
        $challanNo = $store_array[4];
        $refid = $store_array[5];
        $current_user_data = sqlgetresult("SELECT * from loginchk WHERE id='".$parent_id."' AND status ='1'");
        $_SESSION['uid']=$current_user_data['id'];
        $_SESSION['login_user'] = $current_user_data['email'];
        $_SESSION['login_user1'] = $current_user_data['secondaryEmail'];
        $_SESSION['fstname'] = $current_user_data['firstName'];
        $_SESSION['lstname'] = $current_user_data['lastName'];
        $_SESSION['phn'] = $current_user_data['mobileNumber'];
        $_SESSION['sob'] = $current_user_data['secondaryNumber'];
        $_SESSION['sessLoginType'] = 'Parents';
        $sstaus=1;
    if($transactionResponse->validateResponse($_POST)){
        $m_trans_id = $_POST['mer_txn']; //atomtransactionID. The ID is generatedby atom
        //$a_trans_id = $_POST['mmp_txn']; //Merchant'stransactionID
        $a_trans_id = isset($_POST['mmp_txn']) ? $_POST['mmp_txn'] : 'NA';
        $amount = $_POST['amt'];
        $createdOn = $_POST['date'];
        $b_trans_id = $_POST['bank_txn']; //Bank TransactionID. This ID is generatedby the Bank
        //$f_code = $_POST['f_code'];
        $f_code = isset($_POST['f_code']) ? trim($_POST['f_code']) : 'F';
        $createdOn = $_POST['date'];
        $desc = isset($_POST['desc']) ? $_POST['desc'] : 'NA';

        $desc=substr($desc, 0, 30);
        $auth_code = isset($_POST['auth_code']) ? $_POST['auth_code'] : '';

      //  echo "SELECT * FROM paymentEntry('".$parent_id."','".$student_id."','$amount','$f_code','$m_trans_id','".json_encode($_POST)."','".$desc."','".$auth_code."','$createdOn','".$parent_id."','".$payment_id."') ";

        $paymentData = sqlgetresult("SELECT * FROM paymentEntry('".$parent_id."','".$student_id."','$amount','$f_code','$m_trans_id','".json_encode($_POST)."','".$desc."','".$a_trans_id."','$createdOn','".$parent_id."','".$payment_id."') ");
        // print_r($paymentData); 
        //exit;    

        

        if($f_code == 'Ok') { 
        //$updatePaymentTable = sqlgetresult('UPDATE tbl_payments SET "challanNo" = \''.$challanNo.'\' WHERE "id" = \''.$paymentData['paymentEntry'].'\' ');    
            $sstaus=1;            
            createLFPDF($paymentData['paymententry'],$student_id);
            // exit;
            unset($_SESSION['PSLFData']);
            $_SESSION['success_msg'] = "<p class='success-msg'>Payment Completed Successfully.</p>";
            $smsTxt = urlencode("Dear Parent, You have successfully paid the lunch amount successfully.");
            $mblNumber = $_SESSION["phn"];
            $smsURL = "$smsBaseurl/sms.aspx?Id=$smsLoginId&Pwd=$smsLoginPass&PhNo=$mblNumber&text=$smsTxt";
            // $ret = file($smsURL);
        } else {
            $sstaus=2;
            if($_POST['desc'] == "Transction Failure") {
                createErrorlog(json_encode($_POST));
                $_SESSION['error_msg'] = "<p class='error-msg'>Your Transaction has been failed. Please try again later.</p>";
            } elseif ($_POST['f_code'] == 'C' ) {
                createErrorlog(json_encode($_POST));
                $_SESSION['error_msg'] = "<p class='error-msg'>Your Transaction has been cancelled. Please try again later.</p>";
            } else {
                createErrorlog(json_encode($_POST));
                // print_r($_POST);
                $_SESSION['error_msg'] = "<p class='error-msg'>Some Error Has Occurred. Please try again later.</p>";
            }
        }
    } else {
        $sstaus=3;
        createErrorlog(json_encode($_POST),"Something gone wrong.",1);
        $_SESSION['error_msg'] = "<p class='error-msg'>Invalid Signature. Please confirm with accounts team before trying again.</p>";
    }
    sqlgetresult('UPDATE tbl_otherfees_payment_log SET status = \''.$sstaus.'\' WHERE "id" = \''.$refid.'\'');
    header("Location: lunchfee.php");
    exit;
}
    /* Lunch Fee End */
    /* Uniform Fee Start */
if(isset($_GET['uniformfeepayments']) && $_GET['uniformfeepayments']!=''){
    $transactionResponse = new TransactionResponse();
    $transactionResponse->setRespHashKey($respHashKey);
    $decoded_data = base64_decode($_GET['uniformfeepayments']);
    $store_array = explode("_",$decoded_data);
    $parent_id = $store_array[0];
    $student_id = $store_array[1];
    $payment_id = $store_array[3];
    $challanNo = $store_array[4];
    $refid = $store_array[5];
    $current_user_data = sqlgetresult("SELECT * from loginchk WHERE id='".$parent_id."' AND status ='1'");
    $_SESSION['uid']=$current_user_data['id'];
    $_SESSION['login_user'] = $current_user_data['email'];
    $_SESSION['login_user1'] = $current_user_data['secondaryEmail'];
    $_SESSION['fstname'] = $current_user_data['firstName'];
    $_SESSION['lstname'] = $current_user_data['lastName'];
    $_SESSION['phn'] = $current_user_data['mobileNumber'];
    $_SESSION['sob'] = $current_user_data['secondaryNumber'];
    $_SESSION['sessLoginType'] = 'Parents';
    $sstaus=1;
if($transactionResponse->validateResponse($_POST)){
    $m_trans_id = $_POST['mer_txn']; //atomtransactionID. The ID is generatedby atom
   // $a_trans_id = $_POST['mmp_txn']; //Merchant'stransactionID
    $a_trans_id = isset($_POST['mmp_txn']) ? $_POST['mmp_txn'] : 'NA';
    $amount = $_POST['amt'];
    $createdOn = $_POST['date'];
    $b_trans_id = $_POST['bank_txn']; //Bank TransactionID. This ID is generatedby the Bank
    //$f_code = trim($_POST['f_code']);
    $f_code = isset($_POST['f_code']) ? trim($_POST['f_code']) : 'F';
    $createdOn = $_POST['date'];
    $desc = isset($_POST['desc']) ? $_POST['desc'] : 'NA';

    $desc=substr($desc, 0, 30);
    $auth_code = isset($_POST['auth_code']) ? $_POST['auth_code'] : '';
    $paymentData = sqlgetresult("SELECT * FROM paymentEntry('".$parent_id."','".$student_id."','$amount','$f_code','$m_trans_id','".json_encode($_POST)."','".$desc."','".$a_trans_id."','$createdOn','".$parent_id."','".$payment_id."') ");
    if($f_code == 'Ok') { 
        $sstaus=1;
        $gdata=explode("-",$challanNo);
        $sfsfeeId=$gdata[1];
        $sfsfeeName=getFeeTypebyId($gdata[1]);
        $sfsutilitiesinputqty=$gdata[4];
        $singleqtyamount=($amount/$sfsutilitiesinputqty);

        $sfsqty = sqlgetresult("SELECT * FROM sfstableentry('".$challanNo."','". trim($sfsfeeName) ."','".$singleqtyamount."', '". trim($sfsutilitiesinputqty) ."', '". $amount ."','". $_SESSION['uid'] ."','". $student_id ."')");
        createUFPDF($paymentData['paymententry'],$student_id);
        // exit;
        unset($_SESSION['PSLFData']);
        $_SESSION['success_msg'] = "<p class='success-msg'>Payment Completed Successfully.</p>";
        $smsTxt = urlencode("Dear Parent, You have successfully paid the lunch amount successfully.");
        $mblNumber = $_SESSION["phn"];
        $smsURL = "$smsBaseurl/sms.aspx?Id=$smsLoginId&Pwd=$smsLoginPass&PhNo=$mblNumber&text=$smsTxt";
        // $ret = file($smsURL);
    } else {
        $sstaus=2;
        if($_POST['desc'] == "Transction Failure") {
            createErrorlog(json_encode($_POST));
            $_SESSION['error_msg'] = "<p class='error-msg'>Your Transaction has been failed. Please try again later.</p>";
        } elseif ($_POST['f_code'] == 'C' ) {
            createErrorlog(json_encode($_POST));
            $_SESSION['error_msg'] = "<p class='error-msg'>Your Transaction has been cancelled. Please try again later.</p>";
        } else {
            createErrorlog(json_encode($_POST));
            // print_r($_POST);
            $_SESSION['error_msg'] = "<p class='error-msg'>Some Error Has Occurred. Please try again later.</p>";
        }
    }
   
} else {
    $sstaus=3;
    createErrorlog(json_encode($_POST),"Something gone wrong.",1);
    $_SESSION['error_msg'] = "<p class='error-msg'>Invalid Signature. Please confirm with accounts team before trying again.</p>";
}
sqlgetresult('UPDATE tbl_otherfees_payment_log SET status = \''.$sstaus.'\' WHERE "id" = \''.$refid.'\'');
header("Location: uniformfee.php");
exit;
}
/* Uniform Fee End */
/* Transport Fee Start */
if(isset($_GET['transportfeepayments']) && $_GET['transportfeepayments']!=''){
    // print_r($_POST);
    $transactionResponse = new TransactionResponse();
    $transactionResponse->setRespHashKey($respHashKey);
    $decoded_data = base64_decode($_GET['transportfeepayments']);
      
    $store_array = explode("_",$decoded_data);
    $parent_id = $store_array[0];
    $student_id = $store_array[1];
    $payment_id = $store_array[3];
    $challanNo = $store_array[4];
    $refid = $store_array[5];
    $current_user_data = sqlgetresult("SELECT * from loginchk WHERE id='".$parent_id."' AND status ='1'");
    $_SESSION['uid']=$current_user_data['id'];
    $_SESSION['login_user'] = $current_user_data['email'];
    $_SESSION['login_user1'] = $current_user_data['secondaryEmail'];
    $_SESSION['fstname'] = $current_user_data['firstName'];
    $_SESSION['lstname'] = $current_user_data['lastName'];
    $_SESSION['phn'] = $current_user_data['mobileNumber'];
    $_SESSION['sob'] = $current_user_data['secondaryNumber'];
    $_SESSION['sessLoginType'] = 'Parents';
    $sstaus=1;
    if($transactionResponse->validateResponse($_POST)){
        $m_trans_id = $_POST['mer_txn']; //atomtransactionID. The ID is generatedby atom
        //$a_trans_id = $_POST['mmp_txn']; //Merchant'stransactionID
        $a_trans_id = isset($_POST['mmp_txn']) ? $_POST['mmp_txn'] : 'NA';
        $amount = $_POST['amt'];
        $createdOn = $_POST['date'];
        $b_trans_id = $_POST['bank_txn']; //Bank TransactionID. This ID is generatedby the Bank
        //$f_code = $_POST['f_code'];
        $f_code = isset($_POST['f_code']) ? trim($_POST['f_code']) : 'F';
        $createdOn = $_POST['date'];
        $desc = isset($_POST['desc']) ? $_POST['desc'] : 'NA';

        $desc=substr($desc, 0, 30);
        $auth_code = isset($_POST['auth_code']) ? $_POST['auth_code'] : '';

        $paymentData = sqlgetresult("SELECT * FROM paymentEntry('".$parent_id."','".$student_id."','$amount','$f_code','$m_trans_id','".json_encode($_POST)."','".$desc."','".$a_trans_id."','$createdOn','".$parent_id."','".$payment_id."') ");
        
        if($f_code == 'Ok') { 
            $sstaus=1;
            $receiptupd= completeTransportChallan($sstaus, $parent_id, $challanNo, $student_id, 'Online'); 
            unset($_SESSION['PSTFData']);         
            if($receiptupd!=1){
               createErrorlog(json_encode($_POST));
            }
            $_SESSION['success_msg'] = "<p class='success-msg'>Payment Completed Successfully.</p>";         
        } else {
            $sstaus=2;
            if($_POST['desc'] == "Transction Failure") {
                createErrorlog(json_encode($_POST));
                $_SESSION['error_msg'] = "<p class='error-msg'>Your Transaction has been failed. Please try again later.</p>";
            } elseif ($_POST['f_code'] == 'C' ) {
                createErrorlog(json_encode($_POST));
                $_SESSION['error_msg'] = "<p class='error-msg'>Your Transaction has been cancelled. Please try again later.</p>";
            } else {
                createErrorlog(json_encode($_POST));
                // print_r($_POST);
                $_SESSION['error_msg'] = "<p class='error-msg'>Some Error Has Occurred. Please try again later.</p>";
            }
        }
       
    } else {
        $sstaus=3;
        createErrorlog(json_encode($_POST),"Something gone wrong.",1);
        $_SESSION['error_msg'] = "<p class='error-msg'>Invalid Signature. Please confirm with accounts team before trying again.</p>";
    }
    sqlgetresult('UPDATE tbl_otherfees_payment_log SET status = \''.$sstaus.'\' WHERE "id" = \''.$refid.'\'');
    header("Location: transportfee.php");
    exit;
}  

/* Cart Fee Start */
if(isset($_GET['cartpayments']) && $_GET['cartpayments']!=''){

        $transactionResponse = new TransactionResponse();
        $transactionResponse->setRespHashKey($respHashKey);
        $decoded_data = base64_decode($_GET['cartpayments']);
        $store_array = explode("_",$decoded_data);
        
        $student_id = $store_array[0];
        $parent_id = $store_array[1];
        $payment_id = $store_array[2];
        $current_user_data = sqlgetresult("SELECT * from loginchk WHERE id='".$parent_id."' AND status ='1'");
        $_SESSION['uid']=$current_user_data['id'];
        $_SESSION['login_user'] = $current_user_data['email'];
        $_SESSION['login_user1'] = $current_user_data['secondaryEmail'];
        $_SESSION['fstname'] = $current_user_data['firstName'];
        $_SESSION['lstname'] = $current_user_data['lastName'];
        $_SESSION['phn'] = $current_user_data['mobileNumber'];
        $_SESSION['sob'] = $current_user_data['secondaryNumber'];
        $_SESSION['sessLoginType'] = 'Parents';
        $sstaus=1;
    if($transactionResponse->validateResponse($_POST)){
        $m_trans_id = $_POST['mer_txn']; //atomtransactionID. The ID is generatedby atom
       // $a_trans_id = $_POST['mmp_txn']; //Merchant'stransactionID
        $amount = $_POST['amt'];
        $createdOn = $_POST['date'];
        $b_trans_id = $_POST['bank_txn']; //Bank TransactionID. This ID is generatedby the Bank
        $f_code = isset($_POST['f_code']) ? trim($_POST['f_code']) : 'F';
        $createdOn = $_POST['date'];
        $desc = isset($_POST['desc']) ? $_POST['desc'] : 'NA';
        $b_trans_id = isset($_POST['bank_txn']) ? $_POST['bank_txn'] : 'NA';
        $a_trans_id = isset($_POST['mmp_txn']) ? $_POST['mmp_txn'] : 'NA'; //Merchant'stransactionID
        

        //$desc=substr($desc, 0, 30);
        $auth_code = isset($_POST['auth_code']) ? $_POST['auth_code'] : '';
        $paymentData = sqlgetresult("SELECT * FROM cartpaymententry('$amount','$f_code','".json_encode($_POST)."','".$desc."','".$a_trans_id."','$createdOn','".$parent_id."','".$payment_id."') ");
        if($paymentData['cartpaymententry']){
            cartUpdateStatus($paymentData['cartpaymententry'], $student_id);
            if($f_code == 'Ok') { 
                $_SESSION['success_msg'] = "<p class='success-msg'>Payment Completed Successfully.</p>";
                $smsTxt = urlencode("Dear Parent, You have successfully paid the lunch amount successfully.");
                $mblNumber = $_SESSION["phn"];
                $smsURL = "$smsBaseurl/sms.aspx?Id=$smsLoginId&Pwd=$smsLoginPass&PhNo=$mblNumber&text=$smsTxt";
                // $ret = file($smsURL);
            } else {
                 createErrorlog(json_encode($_POST));
                 $_SESSION['error_msg'] = "<p class='error-msg'>".$desc." Please try again later.</p>";
            }
        }else{
            createErrorlog(json_encode($_POST),"Something gone wrong.",1);
            $_SESSION['error_msg'] = "<p class='error-msg'>Something gone wrong. Please try again later.</p>";
        } 
    } else {
        createErrorlog(json_encode($_POST),"Something gone wrong.",1);
        $_SESSION['error_msg'] = "<p class='error-msg'>Invalid Signature. Please confirm with accounts team before trying again.</p>";
    }
    header("Location: cartcheckout.php");
    exit;
}
/*NON Fee With Challan partialPayment Fee Start */
if(isset($_GET['ppnfwc']) && $_GET['ppnfwc']!=''){
    date_default_timezone_set("Asia/Kolkata");    
    $date = date('Y-m-d h:i:s');
    require_once 'atompay/TransactionResponse.php';
    $transactionResponse = new TransactionResponse();
    $transactionResponse->setRespHashKey($respHashKey);

    $decoded_data = base64_decode($_GET['ppnfwc']);
    $store_array = explode("_",$decoded_data);
    //$parent_id = $store_array[0];
   
    $payment_id = trim($store_array[0]);
    $parent_id = trim($store_array[1]);
    $challanNo = trim($store_array[2]);
    $student_id = trim($store_array[3]);

    $current_user_data = sqlgetresult("SELECT * from loginchk WHERE id='".$parent_id."' AND status ='1'");
    $_SESSION['uid']=$current_user_data['id'];
    $_SESSION['login_user'] = $current_user_data['email'];
    $_SESSION['login_user1'] = $current_user_data['secondaryEmail'];
    $_SESSION['fstname'] = $current_user_data['firstName'];
    $_SESSION['lstname'] = $current_user_data['lastName'];
    $_SESSION['phn'] = $current_user_data['mobileNumber'];
    $_SESSION['sob'] = $current_user_data['secondaryNumber'];
    $_SESSION['sessLoginType'] = 'Parents';
    
    $empty='';
    if($transactionResponse->validateResponse($_POST)){
        $m_trans_id = isset($_POST['mer_txn']) ? $_POST['mer_txn'] : ''; //atomtransactionID. The ID is generatedby atom
        $a_trans_id = isset($_POST['mmp_txn']) ? $_POST['mmp_txn'] : 'NA'; //Merchant'stransactionID
        $amount = $_POST['amt'];
        $createdOn = $_POST['date'];
        $b_trans_id = $_POST['bank_txn']; //Bank TransactionID. This ID is generatedby the Bank
        $f_code = isset($_POST['f_code']) ? trim($_POST['f_code']) : 'F';
        $createdOn = $_POST['date'];
        $desc = isset($_POST['desc']) ? $_POST['desc'] : 'NA';
        $auth_code = isset($_POST['auth_code']) ? $_POST['auth_code'] : 'NA';

        $paymentData = sqlgetresult("SELECT * FROM partialnfwcentry('$amount','$f_code','".json_encode($_POST)."','".$desc."','".$a_trans_id."','$createdOn','".$parent_id."','".$payment_id."') ");
        if($f_code == 'Ok') {
            $payment_id=toProcessNFWC($challanNo, $parent_id, $student_id, $payment_id, $amount, $createdOn);
            if($payment_id){
                $_SESSION['success_msg'] = "<p class='success-msg'>Payment Completed Successfully.</p>";
            }else{
              $_SESSION['error_msg'] = "<p class='error-msg'>The receipt has not been generated. Please confirm with the accounts team before trying again.</p>";
            }
        } else {
            createErrorlog(json_encode($_POST));
            $_SESSION['error_msg'] = "<p class='error-msg'>".$desc."</p>";
        }
    } else {
        createErrorlog(json_encode($_POST),"Something gone wrong.",1);
        $_SESSION['error_msg'] = "<p class='error-msg'>Invalid Signature. Please confirm with accounts team before trying again.</p>";
    }
    $_POST = '';
   header("Location: nonfeepayments.php");
   exit;
} 
?>