<?php
require_once ('config.php');
/*Login - Start */
if (isset($_POST["login"]) && $_POST["login"] == "signin")
{
    $emd = $_POST['email'];
    $pwd = $_POST['password'];
    $sql = "SELECT * from loginchk WHERE email='$emd' AND status ='1'";
    $res = sqlgetresult($sql);

    if (count($res) > 0)
    {
        $check_pass = 0;
        if (password_verify(trim($pwd) , trim($res['password'])))
        {
            $check_pass = 1;
        }
        
        if ($check_pass == 0)
        {
            $_SESSION['error'] = "<p class='error-msg'>EmailId and Password doesn't match</p>";
            $errordata = 'Email:' . $emd . '</br>Password:.' . $pwd . '</br>' . $_SESSION['error'];
            createErrorlog($errordata);
            header("location:login.php");
        }
        else
        {
            $verify_code = $res['verifyCode'];
            if (preg_match('/\d/', $verify_code)) 
                $verify_codee = 0;
            if (preg_match('/[a-zA-Z]/', $verify_code))
                $verify_codee = 1;
         //    print_r($verify_codee);
         // exit;
            if ($verify_codee == 0)
            {
                $_SESSION["uid"] = $res['id'];
                $_SESSION["login_user"] = $emd;
                $_SESSION["fstname"] = $res['firstName'];
                $_SESSION["lstname"] = $res['lastName'];
                $_SESSION["phn"] = $res['phoneNumber'];
                $_SESSION["sob"] = $res['mobileNumber'];
        
                if (isset($_POST["remember_me"]))
                {
                    setcookie("email", $_POST["email"], time() + 3600);
                    setcookie("password", $_POST["password"], time() + 3600);
                    echo "Cookies Set Successfully";
                }
                else
                {
                    setcookie("email", "");
                    setcookie("password", "");
                    echo "Cookies Not Set";
                }
                header("location:studetscr.php");
            }
            else
            {
                $_SESSION['error'] = "<p class='error-msg'>Your Email Verification Is Not Done. Please log on your registered email to verify account. </p>";
           
                header("location:login.php");
            }
        }
    }
    else
    {
        $_SESSION['error'] = "<p class='error-msg'>Invalid EmailId</p>";
        $errordata = 'Email:' . $emd . '</br>Password:' . $pwd . '</br>' . $_SESSION['error'];
        createErrorlog($errordata);
        header("location:login.php");
    }
}
/*Login - End */
/*Resend Confirmation - Start */
if (isset($_POST["resendconfirmation"]) && $_POST["resendconfirmation"] == "confirmation")
{
    // print_r($_POST);
    $eid = $_POST['email'];
    $msg = "Welcome " . $eid . "!<br>";
    $sql = "SELECT * from loginchk WHERE email='$eid'";
    $res = sqlgetresult($sql);

    $verify_code = $res['verifyCode'];
    
    $msgs = "You can confirm your account email through the link below:<br>";
    // $link = "<a href='" . BASEURL . "verifyemail.php?k=".$verify_code."'>Click Here to Verify Your Account.</a>";
    $link = "<a href='" . BASEURL ."verifyemail.php?k=".$verify_code."'>Click here to Verify Your Email</a>";
    $subject = "Confirmation instructions";
    $data = $msg . $msgs . $link;

    
    if (count($res) == 0)
    {
        $_SESSION['error'] = "<p class='error-msg'>Invalid EmailId</p>";
        $errordata = 'Email:' . $eid . '</br>' . $_SESSION['error'];
        createErrorlog($errordata);
        header("location:resendconfirm.php");
    }
    else
    {
           $send = SendMailId($eid, $subject, $data);
        $_SESSION['success'] = "<div class='success-msg'>You will receive an email with instructions about how to confirm your account in a few minutes</div>";
        header("location:resendconfirm.php");
    }
}
// PHP FOR FORGOT PASSWORD AND UNLOCK ISTRUCTIONS
/*Resend Confirmation - End */
/*FORGOT PASSWORD - Start */
if (isset($_POST['forgot']) && $_POST['forgot'] == "FORGOT PASSWORD")
{
    $e = $_POST["email"];
    $sql1 = "SELECT * FROM loginchk WHERE email='$e'";
    $result = sqlgetresult($sql1);
    if (!empty($result))
    {
        $to = $_POST["email"];
        $msg = "Hello " . $to . "! <br><br>";
        $msg2 = "Someone has requested a link to change your password. You can do this through the link below.<br><br><a href='" . BASEURL . "changepass.php?k=$to'>Change my password</a><br><br>If you didn't request this, please ignore this email.<br><br>Your password won't change until you access the link above and create a new one.";
        // $msg3 = "PASSWORD : ".$rpass."<br>";
        // print_r($msg2);
        // exit;
        $subject = "Reset Password Instructions";
        $data = $msg . $msg2;
        $send = SendMailId($to, $subject, $data);
        if ($send == true)
        {
            $_SESSION['successmsg'] = "<div class='success-msg'>You will receive an email with instructions about how to reset your password in few minutes.</div>";
            // echo($_SESSION['errormsg']);
            header('location:login.php');
        }
        else
        {
            echo "Failure";
            $errordata = 'Email:' . $e . '</br>';
            createErrorlog($errordata);
        }
    }
    else
    {
        $_SESSION['errormsg'] = "<div class='error-msg'>Email Not Found</div>";
        $errordata = 'Email:' . $eid . '</br>' . $_SESSION['errormsg'];
        createErrorlog($errordata);
        header("location:forgotpass.php");
    }
}
/*FORGOT PASSWORD - End */

/* Sign Up - Start */
if (isset($_POST['submit']) && $_POST['submit'] == 'Sign up')
{
    $fname = $_POST["firstName"];
    $lname = $_POST["lastName"];
    $username = $fname . " " . $lname;
    $email = $_POST["email"];
    $phoneNumber1 = $_POST["phoneNumber"];
    $phoneNumber2 = $_POST["mobileok"];
    $password = password_hash($_POST["password_confirmation"], PASSWORD_DEFAULT);
    $verify_code = generateVerifyCode();
    $sql = "SELECT * FROM reg_val('$fname','$lname','$username','$email','$phoneNumber1','$phoneNumber2','$password','$verify_code')";
      $to = $_POST["email"];
        $link = '<a href="'.BASEURL.'verifyemail.php?k='.$verify_code.'">Click here verify your account</a>';
          $subject = "Registration confirmation";
          $data = "Dear ".$username." <br><br>Please confirm your registration with Lalaji Memorial Omega International School by clicking the below link<br/>".$link;
          $send = SendMailId($to,$subject,$data);

    $result = sqlgetresult($sql);
     // print_r($data);
    

      
       

    if ($result['reg_val'] == 0)
    {

        
        $_SESSION['success_msg'] = "<p class='success-msg'>Email Has Been Sent to your Email ID. Please Verify Your Email ID to Login.</p>";
         
    }

    else
    {
        $_SESSION['error_msg'] = "<p class='error-msg'> User Already Exists</p>";
        $errordata = 'Email:' . $email . '</br>' . $_SESSION['error_msg'];
        createErrorlog($errordata);
    }


    header("Location:welcome.php");
}
/* Sign Up - Start */
/*CHANGE PASSWORD - Start*/
if ((isset($_POST['changepassword']) && $_POST['changepassword'] == 'change'))
{
    $useremail = $_POST['email'];
    $newpassword = password_hash($_POST["password_confirmation"], PASSWORD_DEFAULT);
    if (isset($_SESSION['sessLoginType']) && $_SESSION['sessLoginType'] == 'Parents')
    {
        $parentemail = $_SESSION['login_user'];
        $con = "SELECT * FROM changeparentpassword('$parentemail','$newpassword')";
        $result = sqlgetresult($con);
    }
    else
    {
        $con = "SELECT * FROM changepass('$useremail','$newpassword')";
        $result = sqlgetresult($con);
    }
    // print_r($con);
    // exit;
    if ($result = 1)
    {
        $_SESSION['success_msg1'] = "<p class='success-msg'>Password Changed Successfully !!</p>";
        header("Location:login.php");
    }
    else
    {
        $_SESSION['error_msg1'] = "<p class='error-msg'>Reset Password is Invalid !!</p>";
        $errordata = 'Email:' . $parentemail . '</br>' . $_SESSION['error_msg1'];
        createErrorlog($errordata);
        header("changepass.php");
    }
}
/*CHANGE PASSWORD - End*/
/***Add Student Details***/
if (isset($_POST['addstudent']) && $_POST['addstudent'] == "addstudent")
{
    $pid = $_SESSION['uid'];
    $sid = $_POST['student_number'];
    $mobileNumber = $_POST['mobile_number'];
    $sql = "SELECT * FROM add_stud('$pid','$mobileNumber','$sid')";
    $result = sqlgetresult($sql);
    // print_r($sql);
    // print_r($result);
    // exit;
    if ($result['add_stud'] > 0)
    {
        $_SESSION['success_msg'] = "<p class='success-msg'>Student Added Successfully.</p>";
        header("Location:myaccount.php");
    }
    elseif ($result['add_stud'] == 0)
    {
        $_SESSION['error_msg'] = "<p class='error-msg'> This Child Already mapped with ur profile.</p>";
        $errordata = 'Email:' . $mobileNumber . '</br>' . $_SESSION['error_msg'];
        createErrorlog($errordata);
        header("Location:myaccount.php");
    }
    else
    {
        $_SESSION['error_msg'] = "<p class='error-msg'> Please verify the Student / Mobile Number</p>";
        $errordata = 'Email:' . $mobileNumber . '</br>' . $_SESSION['error_msg'];
        createErrorlog($errordata);
        header("Location:myaccount.php");
    }
}
if (isset($_POST['submit']) && $_POST['submit'] == "getStudentData")
{
    $pid = $_SESSION['uid'];

    $challanData = sqlgetresult('SELECT * FROM challanDatanew WHERE "parentId" = ' . $pid . '');

    if(sizeof($challanData) != 0) {  
        $duedate = date_create($challanData[0]['duedate']);
        $current = date_create(date("Y-m-d"));
        $diff = date_diff($duedate, $current);
        $diff = $diff->format('%d');

        $duedatee = new DateTime($challanData[0]['duedate']);
        $currentt = new DateTime();

        if ($duedatee < $current ) {
            foreach ($challanData AS $challan)
            {
                $feegroup = sqlgetresult('SELECT 1 FROM tbl_challans WHERE "challanNo"=\'' . $challanData[0]['challanNo'] . '\' AND "feeGroup" =\'LATE FEE\' ');
                if ($feegroup == '')
                {
                    $challan['latefee'] = 0;
                    $latefeedata = sqlgetresult('SELECT * FROM latefeecheck', true);    
                    if($diff > 0) {
                        foreach ($latefeedata as $k => $v) {
                            if(isset($latefeedata[$k+1])){
                               if( $diff == trim($v['noOfDays']) ) {
                                    $challanData[0]['latefee'] = $v['amount'];
                                    $createlatefeechallan =sqlgetresult("SELECT * FROM createlatefeechallan('".$challan['challanNo']."','".$challan['studentId']."','".$challan['clid']."','".$challan['term']."','".$challanData[0]['latefee']."','".$challan['stream']."','".$challan['duedate']."','".$challan['academic_yr']."')");
                               } elseif ( $diff > trim($v['noOfDays']) && $diff < trim($latefeedata[$k+1]['noOfDays']) ) {
                                    $challanData[0]['latefee'] = $v['amount'];
                                    $createlatefeechallan =sqlgetresult("SELECT * FROM createlatefeechallan('".$challan['challanNo']."','".$challan['studentId']."','".$challan['clid']."','".$challan['term']."','".$challanData[0]['latefee']."','".$challan['stream']."','".$challan['duedate']."','".$challan['academic_yr']."')");
                               } elseif ( $diff > trim($latefeedata[$k+1]['noOfDays']) )  {
                                    $challanData[0]['latefee'] = $latefeedata[$k+1]['amount'];
                                    $createlatefeechallan =sqlgetresult("SELECT * FROM createlatefeechallan('".$challan['challanNo']."','".$challan['studentId']."','".$challan['clid']."','".$challan['term']."','".$challanData[0]['latefee']."','".$challan['stream']."','".$challan['duedate']."','".$challan['academic_yr']."')");
                               }
                            }
                        }              
                    }
                } 
            }
        }
    $sql = 'SELECT * FROM getstudentdata where "parentId" = ' . $pid . ' AND "challanStatus" = 0 ';
    $res = sqlgetresult($sql, true); 
} else {
    $res = 'nodata';
}
    echo json_encode($res);
}
/****Add Student Data END****/
if (isset($_POST['submit']) && $_POST['submit'] == "fetchstudentdata")
{
    $pid = $_SESSION['uid'];

    $sql = 'SELECT s."studentName", c."class_list",s."section" from tbl_student s LEFT JOIN tbl_class c ON s.class::integer = c.id where s."parentId" = \'' . $pid . '\' ';
    $res = sqlgetresult($sql, true);
    echo json_encode($res);

}
/*CHANGE PASSWORD - Start*/
if ((isset($_POST['changepassword']) && $_POST['changepassword'] == 'change'))
{
    $useremail = $_POST['email'];
    $newpassword = password_hash($_POST['password_confirmation'], PASSWORD_DEFAULT);

    $con = "SELECT * FROM changepass('$useremail','$newpassword')";
    $result = sqlgetresult($con);
    if ($result = 1)
    {
        $_SESSION['success_msg1'] = "<p class='success-msg'>Password Changed Successfully !!</p>";
        header("Location:login.php");
    }
    // else
    // {
    //     $_SESSION['error_msg1']="<p class='error-msg'>Reset Password is Invalid !!</p>";
    //     header("changepass.php");
    // }
    
}
/*CHANGE PASSWORD - End*/
/***** Pay Modal Data - Start ******/
if (isset($_POST['submit']) && $_POST['submit'] == "getChallanData")
{
    $cid = $_POST['cid'];
    $studId = $_POST['studId'];
    $feegroup = $_POST['feegroup'];
    // print_r($_POST);
    $challanData = sqlgetresult('SELECT * FROM challanDatanew WHERE "studentId" =\'' . $studId . '\' AND  "challanNo" = \'' . $cid . '\' AND "feeGroup" = \'' . $feegroup . '\'');
// print_r($challanData);
    if (trim($challanData['feeGroup']) != 'LATE FEE')
    {
        // print_r($challanData);
        $feeTypes = sqlgetresult("SELECT * FROM getFeeTypes");

        $feeTypeArr = array();
        $otherFees = array();
        foreach ($feeTypes as $key => $value)
        {
            $feeTypeArr[$value['id']] = $value['feeType'];
            // echo $value['description'];
            if (trim($value['feeGroup']) == 'SFS UTILITIES FEE')
            {
                $otherFees[$value['id']] = $value['feeType'];
            }
        }

        $challanData1 = array();
        $challanData1['otherFees'] = $otherFees;

        $transportData = sqlgetresult("SELECT * FROM transportcheck");
        // /
        $challanData1['transportData'] = $transportData;

        $feeData = array();
        // foreach ($challanData as $value) {
        $challanData1['challanNo'] = $challanData['challanNo'];
        $challanData1['term'] = $challanData['term'];
        $challanData1['clid'] = $challanData['clid'];
        $challanData1['studentName'] = $challanData['studentName'];
        $challanData1['studentId'] = $challanData['studentId'];
        $challanData1['class_list'] = $challanData['class_list'];
        $challanData1['duedate'] = date("d-m-y",strtotime($challanData['duedate']));
        $challanData1['stream'] = $challanData['stream'];
        $challanData1['steamname'] = $challanData['steamname'];
        $challanData1['waivedPercentage'] = $challanData['waivedPercentage'];
        $challanData1['waivedAmount'] = $challanData['waivedAmount'];
        $challanData1['waivedTotal'] = $challanData['waivedTotal'];
        $challanData1['feeGroup'] = $challanData['feeGroup'];
        $challanData1['studStatus'] = $challanData['studStatus'];

        $feetype = explode(',', $challanData['feeTypes']);
        foreach ($feetype as $v)
        {
            $feeData[] = $v;
        }
        // }
        // print_r($challanData1);
        $feetypedata = sqlgetresult('SELECT * FROM getfeetypedata WHERE class=\'' . $challanData1['clid'] . '\' AND semester=\'' . $challanData1['term'] . '\' AND stream = \'' . $challanData1['stream'] . '\' AND "feeGroup" = \'' . $feegroup . '\'  ', true);

        foreach ($feeData as $fee)
        {
            foreach ($feetypedata as $val)
            {
                if (in_array(trim($fee) , $val))
                {
                    $groupdata[$val['feeGroup']][$val['id']][] = $val['amount'];
                    $groupdata[$val['feeGroup']][$val['id']][] = $val['feename'];
                }
            }
        }
        $challanData1['feeData'] = $groupdata;
        // print_r($groupdata);
        // Late fee Calculation -Start
        // $duedate = date_create($challanData['duedate']);
        // $current   = date_create(date("Y-m-d"));
        // $diff    = date_diff($duedate,$current);
        // $diff    = $diff->format('%d');
        // // echo $diff;
        // $challanData['latefee'] = 0;
        // $latefeedata = sqlgetresult('SELECT * FROM latefeecheck', true);
        // if($diff > 0) {
        //     foreach ( $latefeedata as $data ) {
        //         if(in_array($diff,$data)) {
        //             $challanData1['latefee'] = $data['amount'];
        //             // $createlatefeechallan = sqlgetresult("SELECT * FROM createlatefeechallan('".$challanData1['challanNo']."','".$challanData1['studentId']."','".$challanData1['class_list']."','".$challanData1['term']."','".$challanData1['latefee']."','".$challanData1['stream']."','".$challanData1['duedate']."')",true);
        //         }
        //     }
        // }
        
    }
    else
    {
        $challanData1['challanNo'] = $challanData['challanNo'];
        $challanData1['term'] = $challanData['term'];
        $challanData1['clid'] = $challanData['clid'];
        $challanData1['studentName'] = $challanData['studentName'];
        $challanData1['studentId'] = $challanData['studentId'];
        $challanData1['class_list'] = $challanData['class_list'];
        $challanData1['duedate'] = $challanData['duedate'];
        $challanData1['stream'] = $challanData['stream'];
        $challanData1['steamname'] = $challanData['steamname'];
        $challanData1['waivedPercentage'] = $challanData['waivedPercentage'];
        $challanData1['waivedAmount'] = $challanData['waivedAmount'];
        $challanData1['waivedTotal'] = $challanData['waivedTotal'];
        $challanData1['feeGroup'] = $challanData['feeGroup'];
        $challanData1['studStatus'] = $challanData['studStatus'];

        $duedate = date_create($challanData['duedate']);
        $current = date_create(date("Y-m-d"));
        $diff = date_diff($duedate, $current);
        $diff = $diff->format('%d');
        // print_r($diff);
        // print_r ($challanData1);
        $challanData['latefee'] = 0;
        $latefeedata = sqlgetresult('SELECT * FROM latefeecheck', true);
        // print_R($latefeedata);
        if ($diff > 0)
        {
            foreach ($latefeedata as $data)
            {
                // print_r($data);
                if (in_array($diff, $data))
                {
                    // print_r(eeeeeeeeeeeeee);
                    $challanData1['latefee'] = $data['amount'];

                    // $createlatefeechallan = sqlgetresult("SELECT * FROM createlatefeechallan('".$challanData1['challanNo']."','".$challanData1['studentId']."','".$challanData1['class_list']."','".$challanData1['term']."','".$challanData1['latefee']."','".$challanData1['stream']."','".$challanData1['duedate']."')",true);
                    
                }
            }
        }
    }
    // // Late fee Calculation -End
    // print_r($challanData);
    echo json_encode($challanData1);
}
/***** Pay Modal Data - End ******/
// TABLE-STREAM


if (isset($_POST['submit']) && $_POST['submit'] == "getComments")
{
    $sql = 'SELECT * FROM commentscheck';
    $res = sqlgetresult($sql, true);
    echo json_encode($res);
}

if (isset($_POST['submit']) && $_POST['submit'] == 'getOtherFeeData')
{
    $feeId = $_POST['id'];
    $data = $_POST['data'];

    $getfeeData = sqlgetresult('SELECT f."feeType" , c.amount , f.id FROM tbl_fee_type f LEFT JOIN tbl_fee_configuration c ON f.id = c."feeType"::integer WHERE f."id" =\'' . $feeId . '\' AND c.class=\'' . $data[2]['value'] . '\' AND c.semester = \'' . $data[1]['value'] . '\' AND c.stream = \'1\' ');

    echo json_encode($getfeeData);
}

if (isset($_POST['pay']) && $_POST['pay'] == 'confirm')
{
    // print_r($_POST);
    // exit;
    $amount = $_POST['total'];
    $paygroup = $_POST['paygroup'];
    $parentData = sqlgetresult('SELECT * FROM getparentdata WHERE "studentId" = \'' . $_POST['studId'] . '\' AND "challanNo" = \'' . $_POST['challanNo'] . '\' AND "feeGroup" = \'' . $paygroup . '\'', true);

    print_r($parentData);

    $cusName = $parentData[0]['userName'];
    $cusEmail = $parentData[0]['email'];
    $cusMobile = $parentData[0]['mobileNumber'];
    $studentId = $_POST['studId'];
    $cusId = $parentData[0]['id'];
    // $academicYear = currentAcademicyr();
    $academicYear = $parentData[0]['academic_yr'];
    $productId = sqlgetresult("SELECT * FROM tbl_accounts");
    //  print_r($productId);
    // exit;
    $feeTypes = '';
    foreach ($parentData as $val)
    {
        $feeTypes .= trim($val['feeTypes']) . ',';
    }
    $feeTypes = rtrim($feeTypes, ',');
    $feeTypes = $_POST['extrautilities'] != '' ? trim($feeTypes . ',' . $_POST['extrautilities']) : $feeTypes;

    $fee_entry = sqlgetresult("SELECT * FROM fee_entry('$studentId','" . $parentData[0]['studentName'] . "','$academicYear','" . $parentData[0]['stream'] . "','" . $parentData[0]['class'] . "','" . $current_term . "','" . $feeTypes . "','$amount','" . $_SESSION["uid"] . "','" . $_POST['challanNo'] . "')");
    // print_r($fee_entry);exit;
    $_SESSION['last_fee_entry_id'] = $fee_entry['fee_entry'];
    $_SESSION['PSData'] = $parentData[0];
    $_SESSION['feegroup'] = $paygroup;
    date_default_timezone_set('Asia/Calcutta');
    $datenow = date("d/m/Y h:m:s");
    $transactionDate = str_replace(" ", "%20", $datenow);

    $transactionId = rand(1, 1000000);

    if ($_POST['payment_mode'] == 'online')
    {

        require_once 'atompay/TransactionRequest.php';

        $transactionRequest = new TransactionRequest();

        $productId = sqlgetresult("SELECT * FROM tbl_accounts");
        $cbsefee = $productId['CBSEFEES'];
        $cicfee = $productId['CICFEES'];
        $Latefee = $productId['LMES'];
        $sfsfee = $productId['SFS'];
        $utilitefee = $productId['UTILITIES'];

        //Setting all values here
        $transactionRequest->setMode("test");
        $transactionRequest->setLogin('197');
        $transactionRequest->setPassword("Test@123");
        // if( $paygroup == 'SCHOOL FEE' && $_POST['stream'] == '1'){
        //     $transactionRequest->setProductId('ONE');
        // } else if( $paygroup == 'SCHOOL FEE' && $_POST['stream'] != '1' ) {
        //     $transactionRequest->setProductId('TWO');
        // } else if( $paygroup == 'SFS UTILITY FEE') {
        //     $transactionRequest->setProductId('THREE');
        // } else if( $paygroup == 'LATE FEE') {
        //     $transactionRequest->setProductId('FOUR');
        // } else if( $paygroup == 'SCHOOL UTILITY FEE' ) {
        //     $transactionRequest->setProductId('FIVE');
        // }
        $transactionRequest->setProductId("NSE");
        $transactionRequest->setAmount($amount);
        $transactionRequest->setTransactionCurrency("INR");
        $transactionRequest->setTransactionAmount($amount);
        $transactionRequest->setReturnUrl(BASEURL . "studetscr.php");
        $transactionRequest->setClientCode(123);
        $transactionRequest->setTransactionId($transactionId);
        $transactionRequest->setTransactionDate($transactionDate);
        $transactionRequest->setCustomerName($cusName);
        $transactionRequest->setCustomerEmailId($cusEmail);
        $transactionRequest->setCustomerMobile($cusMobile);
        $transactionRequest->setCustomerBillingAddress("Chennai");

        $transactionRequest->setCustomerAccount("639827");
        $transactionRequest->setReqHashKey("KEY123657234");

        // print_r($transactionRequest);exit;
        $url = $transactionRequest->getPGUrl();

        header("Location: $url");
    }
    else
    {
        $entry = sqlgetresult("SELECT *  FROM cheque_fee_entry_update('" . $fee_entry['fee_entry'] . "','" . $_SESSION['uid'] . "','" . $_POST['ptype'] . "','" . $_POST['bank'] . "','" . $_POST['paymentmode'] . "','" . $_POST['paiddate'] . "') ");
        $updateChallan = sqlgetresult('UPDATE tbl_challans SET "challanStatus" = 1, "updatedBy" = \'' . $_SESSION['uid'] . '\', "updatedOn" = CURRENT_TIMESTAMP WHERE "challanNo" = \'' . $parentData[0]['challanNo'] . '\' AND "feeGroup" = \'' . $parentData[0]['feeGroup'] . '\' ');
        // echo $entry;
        createPDF($_SESSION['PSData']['studentId'], $parentData[0]['challanNo']);
        unset($_SESSION['last_fee_entry_id']);
        unset($_SESSION['PSData']);
        $_SESSION['success_msg'] = "<p class='success-msg'>Payment Updated Successfully.</p>";
        header("Location: studetscr.php");
    }
}

?>
