<?php
require_once ('config.php');
function validatecaptcha($recaptcha, $recaptch_secret_key){
    $secret_key = $recaptch_secret_key;
    // Hitting request to the URL, Google will
    // respond with success or error scenario
    $url = 'https://www.google.com/recaptcha/api/siteverify?secret='
      . $secret_key . '&response=' . $recaptcha;
    // Making request to verify captcha
    $response = file_get_contents($url);
    // Response return by google is in
    // JSON format, so we have to parse
    $response = json_decode($response);
    // Checking, if response is true or not
    if ($response->success == true) {
        return 1;
    } else {
         return 0;
    }
}
/*Login - Start */
if (isset($_POST["login"]) && $_POST["login"] == "signin")
{
    $emd = $_POST['email'];
    $pwd = $_POST['password'];
    $recaptcha = isset($_POST["g-recaptcha-response"])?trim($_POST["g-recaptcha-response"]):"";
    $isvalid=validatecaptcha($recaptcha, $recaptch_secret_key);
    if($isvalid==0){
        $_SESSION['error'] = "<p class='error-msg'>Invalid reCAPTACHA! Kindly try again.</p>";
        header("Location: login.php");
        exit;
    }else{
        if(isset($_SESSION['error'])) {
            unset($_SESSION['error']);
        }
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
                $_SESSION['error'] = "<p class='error-msg'>EmailId and Password don't match</p>";
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
                    $_SESSION["login_user1"] = $res['secondaryEmail'];
                    $_SESSION["fstname"] = $res['firstName'];
                    $_SESSION["lstname"] = $res['lastName'];
                    $_SESSION["phn"] = $res['mobileNumber'];
                    $_SESSION["sob"] = $res['secondaryNumber'];
                    $_SESSION["sessLoginType"] = 'Parents';
            
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
}
/*Login - End */
/*Resend Confirmation - Start */
if (isset($_POST["resendconfirmation"]) && $_POST["resendconfirmation"] == "confirmation")
{
    // print_r($_POST);
    $recaptcha = isset($_POST["g-recaptcha-response"])?trim($_POST["g-recaptcha-response"]):"";
    $isvalid=validatecaptcha($recaptcha, $recaptch_secret_key);
    if($isvalid==0){
        $_SESSION['error'] = "<p class='error-msg'>Invalid reCAPTACHA! Kindly try again.</p>";
        header("Location: resendconfirm.php");
        exit;
    }else{
        if(isset($_SESSION['error'])) {
            unset($_SESSION['error']);
        }
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
}
// PHP FOR FORGOT PASSWORD AND UNLOCK ISTRUCTIONS
/*Resend Confirmation - End */
/*FORGOT PASSWORD - Start */
if (isset($_POST['forgot']) && $_POST['forgot'] == "FORGOT PASSWORD")
{
    $e = isset($_POST["email"])?trim($_POST["email"]):"";
    $recaptcha = isset($_POST["g-recaptcha-response"])?trim($_POST["g-recaptcha-response"]):"";
    $isvalid=validatecaptcha($recaptcha, $recaptch_secret_key);
    if($isvalid==0){
        $_SESSION['errormsg'] = "<p class='error-msg'>Invalid reCAPTACHA! Kindly try again.</p>";
        header("Location: forgotpass.php");
        exit;
    }else{
        if(isset($_SESSION['errormsg'])) {
            unset($_SESSION['errormsg']);
        }
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
}
/*FORGOT PASSWORD - End */

/* Sign Up - Start */
if (isset($_POST['submit']) && $_POST['submit'] == 'Sign up')
{
    $recaptcha = isset($_POST["g-recaptcha-response"])?trim($_POST["g-recaptcha-response"]):"";
    $isvalid=validatecaptcha($recaptcha, $recaptch_secret_key);
    if($isvalid==0){
        $_SESSION['error_msg'] = "<p class='error-msg'>Invalid reCAPTACHA! Kindly try again.</p>";
        header("Location: signup.php");
        exit;
    }else{
        if(isset($_SESSION['error_msg'])) {
            unset($_SESSION['error_msg']);
        }
        $fname = $_POST["firstName"];
        $lname = $_POST["lastName"];
        $username = $fname;
        $emailprimary = $_POST["emailprimary"];
        $emailsecondary = $_POST["emailsecondary"] !='' ? $_POST["emailsecondary"] : 0;
        $phoneNumber1 = $_POST["mobileok"];
        $phoneNumber2 = $_POST["phoneNumber"]  !='' ? $_POST["phoneNumber"] : 0;
        $password = password_hash($_POST["password_confirmation"], PASSWORD_DEFAULT);
        $verify_code = generateVerifyCode();
        $sql = "SELECT * FROM reg_val('$fname','$lname','$username','$emailprimary','$emailsecondary','$phoneNumber1','$phoneNumber2','$password','$verify_code')";

        $to = $_POST["emailprimary"];
        $link = '<a href="'.BASEURL.'verifyemail.php?k='.$verify_code.'">Click here verify your account</a>';
        $subject = "Registration confirmation";
        $data = "Dear ".$username." <br><br>Please confirm your registration with Lalaji Memorial Omega International School by clicking the below link<br/>".$link;

        $result = sqlgetresult($sql);
       
        // print_r($result);
        if ($result['reg_val'] > 0)
        {
            $send = SendMailId($to,$subject,$data);    
            $_SESSION['success_msg'] = "<p class='success-msg'>Email Has Been Sent to your Email ID. Please Verify Your Email ID to Login.</p>";         
        }
        else if  ($result['reg_val'] === '0')
        {
            $_SESSION['error_msg'] = "<p class='error-msg'> User Already Exists</p>";
            $errordata = 'Email:' . $email . '</br>' . $_SESSION['error_msg'];
            createErrorlog($errordata);
        } else {
            $_SESSION['error_msg'] = "<p class='error-msg'> Some Error has occured. Try again later.</p>";
            $errordata = 'Email:' . $email . '</br>' . $_SESSION['error_msg'];
            createErrorlog($errordata);
        }
        // print_r($_SESSION);exit;
        header("Location: welcome.php");
    }
}
/* Sign Up - Start */
/*CHANGE PASSWORD - Start*/
if ((isset($_POST['changepassword']) && $_POST['changepassword'] == 'change'))
{
    $newpassword = password_hash($_POST["password_confirmation"], PASSWORD_DEFAULT);
    if (isset($_SESSION['login_user']))
    {
        $parentemail = $_SESSION['login_user'];
        $con = "SELECT * FROM changeparentpassword('$parentemail','$newpassword')";
        $result = sqlgetresult($con);
    }
    else
    {   $useremail = $_POST['email'];
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
        
        $errordata = 'Email:' . $mobileNumber . '</br>' . $_SESSION['error_msg'];
       
        $mappedstudentdetails = sqlgetresult('SELECT * FROM tbl_student WHERE "studentId" = \'' . $sid . '\'');
        $to = NOTIFICATIONEMAIL;
        $subject = "Try to Map Already Mapped Student";
        $data = "Hi <br><br>The Following Parent ". getParentNamebyId($mappedstudentdetails['parentId'])."(".$mappedstudentdetails['parentId'].") has tried to map this Student Name <b>".$mappedstudentdetails['studentName']."(".$sid.")</b> ".getClassbyNameId($mappedstudentdetails['class'])."- ".$mappedstudentdetails['section']." into their Acoount, which is already mapped to another parent.<br/>";
        // $send = SendMailId($to,$subject,$data);
         createErrorlog($errordata);
         $_SESSION['error_msg'] = "<p class='error-msg'> This Child Already mapped.</p>";
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

if (isset($_POST['submit']) && $_POST['submit'] == "getStudentData") {
    $pid = $_SESSION['uid'];
    $challanData = sqlgetresult('SELECT "challanNo", duedate, "studentId", clid, "academicYear", term, stream FROM challanDatanew WHERE "parentId" = ' . $pid . ' AND ("challanStatus" = \'0\' OR "challanStatus" = \'2\') AND "studStatus"!=\'Transport.Fee\' AND "academicYear" >=7 GROUP BY  "challanNo", duedate, "studentId", clid, "academicYear", term, stream ', true);
    //print_R($challanData);
    if (!empty($challanData)) {
        foreach($challanData as $challan) {
            $diffvalue = 1;
            $current = date_create(date("Y-m-d"));
            $now = time(); // or your date as well
            $duedate = strtotime($challan['duedate']);
            $datediff = $now - $duedate;
            $difference = round($datediff / (60 * 60 * 24));
            $diff = $difference - $diffvalue;
            $stId=trim($challan['studentId']);
            // print_r($diff);
            // exit;
            if ($diff > 0 && !stristr($stId, 'APPL')) {
                // echo $challan['duedate'].'-'.$diff;

                $latefeegroup = sqlgetresult('SELECT "waivedTotal" FROM tbl_challans WHERE "challanNo"=\'' . $challan['challanNo'] . '\' AND "feeGroup" = \'0\' ');
                if ($latefeegroup['waivedTotal'] == '' || $latefeegroup['waivedTotal'] == 0) {
                    $deletelatefee = sqlgetresult('DELETE FROM tbl_challans WHERE "challanNo"= \'' . $challan['challanNo'] . '\' AND "feeGroup" = \'0\' AND "challanStatus" = \'' . 0 . '\' AND deleted=0');
                    $deletelatefeedemand = sqlgetresult('DELETE FROM tbl_demand WHERE "challanNo"= \'' . trim($challan['challanNo']) . '\' AND "feeGroup" = \'0\' AND "challanStatus" = \'0\' AND deleted=0');
                }
                $feegroup = sqlgetresult('SELECT 1 FROM tbl_challans WHERE "challanNo"=\'' . $challan['challanNo'] . '\' AND "feeGroup" = \'0\' ');
                if ($feegroup == '') {
                    $challan['latefee'] = 0;
                    $latefeedata = sqlgetresult('SELECT * FROM latefeecheck', true);
                    $acayearid = trim($challan['academicYear']);
                    $clid = $challan['clid'];
                    foreach($latefeedata as $k => $v) {
                        if (isset($latefeedata[$k + 1])) {
                            $challan['latefee'] = $v['amount'];
                            if ($diff == trim($v['noOfDays'])) {
                                $createlatefeechallan = sqlgetresult("SELECT * FROM createlatefeechallan('" . $challan['challanNo'] . "','" . $challan['studentId'] . "','" . $challan['clid'] . "','" . $challan['term'] . "','" . $challan['latefee'] . "','" . $challan['stream'] . "','" . $challan['duedate'] . "','" . $acayearid . "', '" . $pid . "', '" . $clid . "')");
                            }
                            elseif ($diff > trim($v['noOfDays']) && $diff < trim($latefeedata[$k + 1]['noOfDays'])) {
                                $createlatefeechallan = sqlgetresult("SELECT * FROM createlatefeechallan('" . $challan['challanNo'] . "','" . $challan['studentId'] . "','" . $challan['clid'] . "','" . $challan['term'] . "','" . $challan['latefee'] . "','" . $challan['stream'] . "','" . $challan['duedate'] . "','" . $acayearid . "', '" . $pid . "', '" . $clid . "')");
                            }
                        }
                        else {
                            if ($diff > trim($v['noOfDays'])) {
                                $challan['latefee'] = end($latefeedata) ['amount'];
                                $createlatefeechallan = sqlgetresult("SELECT * FROM createlatefeechallan('" . $challan['challanNo'] . "','" . $challan['studentId'] . "','" . $challan['clid'] . "','" . $challan['term'] . "','" . $challan['latefee'] . "','" . $challan['stream'] . "','" . $challan['duedate'] . "','" . $acayearid . "', '" . $pid . "', '" . $clid . "')");
                            }
                        }
                        // echo $createlatefeechallan;
                    }
                }
            }
        }
    }
    $sql = 'SELECT * FROM getstudentdata where "parentId" = ' . $pid . ' AND ("challanStatus" = 0 OR "challanStatus" = 2) AND "studStatus"!=\'Transport.Fee\'';
    $res = sqlgetresult($sql, true);
    // print_r($res);
    $challanData = array();
    $total = 0;
    $tot = 0;
    $challanNo = '';
    $feeData = array();
    // echo count($res);
    if (count($res) > 0) {
        foreach($res as $k => $data) {
            if(isset($data['visibleStatus'])){
                $vstatus=trim($data['visibleStatus']);
            }else{
               $vstatus=1; 
            }

            $chlNum=trim($data['challanNo']);
            $chlStatus=trim($data['challanStatus']);
            $challanData[$data['challanNo']]['studentName'] = trim($data['studentName']);
            $challanData[$data['challanNo']]['class_list'] = trim($data['class_list']);
            $challanData[$data['challanNo']]['section'] = trim($data['section']);
            $challanData[$data['challanNo']]['term'] = trim($data['term']);
            //$challanData[$data['challanNo']]['studentId'] = trim($data['studentId']);
            $challanData[$data['challanNo']]['studentId'] = trim($data['studentid_in_challan']);
            $challanData[$data['challanNo']]['challanNo'] = $chlNum;
            $challanData[$data['challanNo']]['feeTypes'][] = trim($data['feeType']);
            $challanData[$data['challanNo']]['classList'] = trim($data['classList']);
            $challanData[$data['challanNo']]['stream'] = trim($data['stream']);
            $challanData[$data['challanNo']]['org_total'][] = trim($data['org_total']);
            $challanData[$data['challanNo']]['feeGroup'][] = trim($data['feeGroup']);
            $challanData[$data['challanNo']]['acdyear'] = trim($data['acdyear']);
            $challanData[$data['challanNo']]['visible'] = $vstatus;
            $waivedAmount = 0;
            $waivedArray=getwaiveddata($chlNum);
            if($waivedArray !=0 ){
                foreach ($waivedArray as $waived) {
                    $waivedAmount += $waived['waiver_total'];
                }
            }
            $challanData[$data['challanNo']]['waived'] = $waivedAmount;
        }
        // print_r($res);
        $data = array();
        foreach($challanData as $feeData) {
            //$feeData['fee'] = array_sum($feeData['org_total']);
            $feeData['fee'] = array_sum($feeData['org_total']) - $feeData['waived'];
            $total = 0;
            $data[] = $feeData;
        }
        // echo json_encode($data);
    }
    else {
        $data = '0';
    }
    echo json_encode($data);
}
/****Add Student Data END****/
if (isset($_POST['submit']) && $_POST['submit'] == "fetchstudentdata")
{
    $pid = $_SESSION['uid'];

    $sql = 'SELECT s."studentName", c."class_list",s."section",s."studentId" from tbl_student s LEFT JOIN tbl_class c ON s.class::integer = c.id where s."parentId" = \'' . $pid . '\' AND s."status" = \'1\' AND s."deleted" = \'0\'';
    $res = sqlgetresult($sql, true);
    echo json_encode($res);

}
/*CHANGE PASSWORD - Start*/
// if ((isset($_POST['changepassword']) && $_POST['changepassword'] == 'change'))
// {
//     $useremail = $_POST['email'];
//     $newpassword = password_hash($_POST['password_confirmation'], PASSWORD_DEFAULT);

//     $con = "SELECT * FROM changepass('$useremail','$newpassword')";
//     $result = sqlgetresult($con);
//     if ($result = 1)
//     {
//         $_SESSION['success_msg1'] = "<p class='success-msg'>Password Changed Successfully !!</p>";
//         header("Location:login.php");
//     }
//     // else
//     // {
//     //     $_SESSION['error_msg1']="<p class='error-msg'>Reset Password is Invalid !!</p>";
//     //     header("changepass.php");
//     // }
    
// }
/*CHANGE PASSWORD - End*/
/***** Pay Modal Data - Start ******/
if (isset($_POST['submit']) && $_POST['submit'] == "getChallanData")
{
    $cid = $_POST['cid'];
    $studId = $_POST['studId'];
    $feegroup = $_POST['feegroup'];
    $pid = $_SESSION['uid'];

    $type = isset($_POST['type'])?trim($_POST['type']):"";
    //$challanData = sqlgetresult('SELECT * FROM challanDatanew WHERE "studentId" =\'' . $studId . '\' AND  "challanNo" = \'' . $cid . '\' AND ("challanStatus" = \'0 \' OR "challanStatus" = \'2\') ',true);
    
        if($type == "moved"){
         $challanData = sqlgetresult('SELECT * FROM challanDatanew WHERE "parentId" = '.$pid.' AND  "challanNo" = \'' . $cid . '\' AND "challanStatus" = \'3\'',true);   
        }else{
         $challanData = sqlgetresult('SELECT * FROM challanDatanew WHERE "parentId" =\'' . $pid . '\' AND  "challanNo" = \'' . $cid . '\' AND ("challanStatus" = \'0\' OR "challanStatus" = \'2\') ',true);
        }
        if( $challanData[0]['hostel_need'] == 'Y') {
            $feeTypes = sqlgetresult('SELECT * FROM getfeetypes WHERE "mandatory" =\'0 \' AND (applicable=\'DH\' OR applicable=\'H\') ',true);
        } else {
            $feeTypes = sqlgetresult('SELECT * FROM getfeetypes WHERE "mandatory" =\'0 \' AND (applicable=\'DH\' OR applicable=\'D\') ',true);
        }
        $feeTypeArr = array();
        $otherFees1 = array();
        $otherFees2 = array();
        foreach ($feeTypes as $key => $value)
        {
            $feeTypeArr[$value['id']] = $value['feeType'];
            if ((trim($value['feegroupname'])) == 'SFS UTILITIES FEE' && !stristr($value['feeType'],"TRANSPORT"))
            {
                $otherFees1[$value['id']] = trim($value['feeType']);
            }
        }
        foreach ($feeTypes as $key => $value)
        {
            $feeTypeArr[$value['id']] = $value['feeType'];
            if ((trim($value['feegroupname'])) == 'SCHOOL UTILITY FEE') 
            {
                $otherFees2[$value['id']] = trim($value['feeType']);
            }
        }

        $challanData1 = array();
        $challanData1['sfsutilityotherfees'] = $otherFees1;
        $challanData1['schoolotherFees'] = $otherFees2;
        $waivedarray = array();
        $latefee = 0;
        $feeData = array();
        $chlncnt = count($challanData);
        foreach ($challanData as $k => $value) {
            $challanData1['challanNo'] = $value['challanNo'];
            $challanData1['term'] = $value['term'];
            $challanData1['clid'] = $value['clid'];
            $challanData1['section'] = trim($value['section']);
            $challanData1['studentName'] = trim($value['studentName']);
            $challanData1['studentId'] = $value['studentId'];
            $challanData1['class_list'] = trim($value['class_list']);
            //$challanData1['duedate'] = date("d-m-y",strtotime($value['duedate']));
            if($value['duedate'] == ''){
            $challanData1['duedate'] = "Nil";
            }else{
            $challanData1['duedate'] = date("d-m-y",strtotime($value['duedate']));
            }
            $challanData1['stream'] = $value['stream'];
            $challanData1['steamname'] = trim($value['steamname']);
            $challanData1['academic_yr'] = $value['academicYear'];
            $challanData1['academicYear'] = getAcademicyrById($value['academicYear']);
            // $challanData1['waivedAmount'] = $value['waivedAmount'];
            // $challanData1['waivedTotal'] = $value['waivedTotal'];
            $challanData1['feeGroup'] = $value['feeGroup'];
            $challanData1['studStatus'] = $value['studStatus'];
            $challanData1['org_total'] = $value['org_total'];
            if($value['remarks'] != ''){
                $challanData1['remarks'] = $value['remarks'];
            }
            else{
            $challanData1['remarks'] = 'Nill';

            }
            $fgroup_name=getFeeGroupbyId($value['feeGroup']);
            $fgroup_name=trim($fgroup_name);

            $ftype_name= getFeeTypebyId($value['feeType']);
            $ftype_name=trim($ftype_name);
            $feetypearray[$fgroup_name][$value['feeType']][] = $value['org_total'];
            if($fgroup_name=='REFUNDABLE DEPOSIT'){
                if($ftype_name =='Caution Deposit'){
                    $feetypearray[$fgroup_name][$value['feeType']][] = $ftype_name." (Caution Deposit can be paid in two instalments of INR 37,500 each)";
                }else{
                    $feetypearray[$fgroup_name][$value['feeType']][] = $ftype_name;
                }
            }else{
                $feetypearray[$fgroup_name][$value['feeType']][] = $ftype_name;
            }
            $feetypearray[$fgroup_name]['waived'] = getwaiveddata($value['challanNo'], $value['feeGroup']);
            $cnt = $k+1;
            if($cnt == $chlncnt) {
                $groupdata = $feetypearray;

            }  
        }

    if($latefee == 1){
        $late = 'LATE FEE';
        $latefee = sqlgetresult('SELECT "org_total" FROM tbl_challans WHERE "challanNo" = \''.$challanData1['challanNo'].'\' AND "challanStatus" = \'0 \' AND "feeGroup" = \''.$late.'\' AND deleted=0');
        // $latefeedata[$val['feeGroup']][] = $latefee['org_total'];
        $groupdata[$late][$late][1][] = $latefee['org_total'];
        $groupdata[$late][$late][1][] = $late;
    }
    uksort($groupdata, function ($key1, $key2) use($order)
    {
        return (array_search(trim($key1) , $order) > array_search(trim($key2) , $order));
    });
    $challanData1['feeData'] = $groupdata;
    $challanData1['waivedData'] = $waivedarray;

    echo json_encode($challanData1);
}
/***** Pay Modal Data - End ******/
// TABLE-STREAM
// }

if (isset($_POST['submit']) && $_POST['submit'] == "getComments")
{
    $sql = 'SELECT * FROM commentscheck WHERE startdate <= CURRENT_DATE  AND enddate > CURRENT_DATE ';
    $res = sqlgetresult($sql, true);
    echo json_encode($res);
}

if (isset($_POST['submit']) && $_POST['submit'] == 'getOtherFeeData')
{
   $feeId = $_POST['id'];
    $data = $_POST['data'];
    
    $idalreadyaddedornot = sqlgetresult('SELECT * FROM tbl_challans WHERE "challanNo" = \'' . trim($data[4]['value']) . '\' AND "studentId" = \'' . trim($data[0]['value']) . '\' AND "term" = \'' . trim($data[1]['value']) . '\' AND "academicYear"= \'' . trim($data[6]['value']) . '\' AND "feeType" = \'' . trim($feeId) . '\'');
    // print_r($idalreadyaddedornot);
    if($idalreadyaddedornot != ''){
        $getfeeData = 1;
    }
    else{
        $getfeeData = sqlgetresult('SELECT f."feeType" , c.amount , f.id FROM tbl_fee_type f LEFT JOIN tbl_fee_configuration c ON f.id = c."feeType"::integer WHERE f."id" =\'' . $feeId . '\' AND c.class=\'' . $data[2]['value'] . '\' AND c.semester = \'' . $data[1]['value'] . '\' AND c.stream = \'' . $data[5]['value'] . '\' AND c."academicYear"= \'' . trim($data[6]['value']) . '\' ');
    }

    // if( $_POST['feegroup'] == 'SFS') {
    //     $feegrp = 'SFS UTILITIES FEE';
    //     $feegrp_id = getFeeGroupbyName($feegrp);
    // } else if ( $_POST['feegroup'] == 'UTI') {
    //     $feegrp = 'SCHOOL UTILITY FEE';
    //     $feegrp_id = getFeeGroupbyName($feegrp);
    // }

    // $chkData = sqlgetresult('SELECT "feeTypes","org_total" FROM tbl_challans WHERE "feeGroup" = \''.$feegrp_id.'\' AND "challanNo" = \''.$data[4]['value'].'\' ');

    // if( count( $chkData ) > 0  ) {
    //     $feeTypes = trim($chkData['feeTypes']).','.$feeId;
    //     $amount = $chkData['org_total'] + ($getfeeData['amount']*$_POST['qty']);
    //     if (strpos(trim($chkData['feeTypes']) , $feeId) === false) {
    //         $sql = sqlgetresult('UPDATE tbl_challans SET "feeTypes" = \''.$feeTypes.'\', total = \''.$amount.'\', org_total = \''.$amount.'\'  WHERE "feeGroup" = \''.$feegrp_id.'\' AND "challanNo" = \''.$data[4]['value'].'\' ');
    //     }        
    // } else {
    //     $duedate = sqlgetresult('SELECT "duedate" FROM tbl_challans WHERE "challanNo" = \''.$data[4]['value'].'\' LIMIT 1 ');
    //     $amount =  $getfeeData['amount']*$_POST['qty'];
    //     $sql = sqlgetresult('INSERT INTO tbl_challans("challanNo", "studentId", "feeTypes", "classList", "term","createdBy","total","org_total","stream","duedate","feeGroup","academicYear")    VALUES (\''.$data[4]['value'].'\', \''.$data[0]['value'].'\', \''.$feeId.'\', \''.$data[2]['value'].'\',\''.$data[1]['value'].'\', \''.$_SESSION['uid'].'\', \''.$amount.'\', \''.$amount.'\', \''.$data[5]['value'].'\', \''.$duedate['duedate'].'\', \''.$feegrp_id.'\' , \''.getAcademicyrByStudentId($data[0]['value']).'\')');
    // } 
    
    echo json_encode($getfeeData);
}
// if (isset($_POST['submit']) && $_POST['submit'] == 'deleteotherfeedata')
// {
//     // print_r($_POST);exit;
//     $feeId = $_POST['id'];
//     $data = $_POST['data'];
//     $idamt = $_POST['amount'];
    
//     if( $_POST['feegroup'] == 'SFS') {
//         $feegrp = 'SFS UTILITIES FEE';
//         $feegrp_id = getFeeGroupbyName($feegrp);
//     } else if ( $_POST['feegroup'] == 'UTI') {
//         $feegrp = 'SCHOOL UTILITY FEE';
//         $feegrp_id = getFeeGroupbyName($feegrp);
//     }

//     $chkData = sqlgetresult('SELECT "feeTypes" FROM tbl_challans WHERE "feeGroup" = \''.$feegrp_id.'\' AND "challanNo" = \''.$data[4]['value'].'\' ');
//     $totalamount = sqlgetresult('SELECT "org_total" FROM tbl_challans WHERE "feeGroup" = \''.$feegrp_id.'\' AND "challanNo" = \''.$data[4]['value'].'\' ');
    
//     $updamt = $totalamount['org_total'] - $idamt;
//     $feeTypes = implode($chkData,",");
    
//     $updfeetypes = removeFromString(trim($feeTypes), trim($feeId));

//     $sql = sqlgetresult('UPDATE tbl_challans SET "feeTypes" = \''.$updfeetypes.'\', total = \''.$updamt.'\', org_total = \''.$updamt.'\'  WHERE "feeGroup" = \''.$feegrp_id.'\' AND "challanNo" = \''.$data[4]['value'].'\' returning id ');
//     // print_r($sql);
//     // exit;

//     if($sql['id'] != ""){
//         $getfeeData = '1';
//     }
//     else{

//          $getfeeData = '0';
//     }
//     // print_r($getfeeData);
//     // exit;
//  echo json_encode($getfeeData);
// }

if (isset($_POST['pay']) && $_POST['pay'] == 'confirm')
{
    $amount = $_POST['grand_tot'] == '' ? $_POST['tot'] : $_POST['grand_tot'];
    $parentData = sqlgetresult('SELECT * FROM getparentdatachallan WHERE "studentId" = \''.$_POST['studId'].'\' AND "challanNo" = \''.$_POST['challanNo'].'\' LIMIT 1',true);

    $cusName = $parentData[0]['userName'];
    $cusEmail = $parentData[0]['email'];
    $cusChallanNo = $parentData[0]['challanNo'];
    $cusMobile = $parentData[0]['mobileNumber'];
    $studentId = trim($_POST['studId']);
    $cusId = $parentData[0]['id'];
    $academicYear = $parentData[0]['academic_yr'];
    $term = $parentData[0]['term'];
    $pid = $_SESSION['uid'];
    $clientcode = trim($_POST['studId']) . '|' . trim(getAcademicyrById($parentData[0]['academic_yr'])) . '|' . trim($parentData[0]['term']);
    
    $productId = sqlgetresult("SELECT * FROM tbl_accounts");

    $sfsutilitiesinputqty = explode(",",$_POST['sfsextrautilitiesqty']);

    $sfsextrautilitiesid= explode(",",$_POST['sfsextrautilities']);
    $schoolextrautilitiesid = explode(",",$_POST['schoolextrautilities']);

    if(sizeof(array_filter($sfsextrautilitiesid)) > 0){
        $feegrp_id = '10';
        $studData = sqlgetresult('SELECT * FROM challandatanew WHERE "challanNo" = \''.$_POST['challanNo'].'\' LIMIT 1 ');
        $studData = array_map('trim',$studData);
        $duedate = sqlgetresult('SELECT "duedate" FROM tbl_challans WHERE "challanNo" = \''.$_POST['challanNo'].'\' AND deleted=0 LIMIT 1 ');
        $studStatus = 'Prov.Promoted';

        foreach ($sfsextrautilitiesid as $k=>$sfsfeeId) {
            $sfsfeeId = trim($sfsfeeId);
            $singleqtyamount = getSFSandSchoolFeeByFeeId($sfsfeeId, $studData['clid'],$studData['academicYear'],$studData['term'] );
            $totalAmt = $singleqtyamount * ($sfsutilitiesinputqty[$k]);
            $sql = sqlgetresult("SELECT * FROM editcreatedchallansnew('". $_POST['challanNo'] ."','". $studData['studentId'] ."', '". $studData['clid'] ."','".trim($sfsfeeId)."', '". $studData['term'] ."','$studStatus', '". $_SESSION['uid'] ."','".trim($totalAmt)."','". $studData['stream'] ."','". $studData['remarks'] ."','". $duedate['duedate'] ."','".trim($feegrp_id)."','". $studData['academicYear'] ."')");


            echo $sql ; echo "<hr/>";
            $sfsqty = sqlgetresult("SELECT * FROM sfstableentry('". $_POST['challanNo'] ."','". $sfsfeeId ."','$singleqtyamount', '". trim($sfsutilitiesinputqty[$k]) ."', '". $totalAmt ."','". $_SESSION['uid'] ."','". $studData['studentId'] ."')");
            echo $sfsqty ;echo "<hr/>";
        } 
      }

    if(sizeof(array_filter($schoolextrautilitiesid)) > 0){
        $feegrp_id = '9';
        $studData = sqlgetresult('SELECT * FROM challandatanew WHERE "challanNo" = \''.$_POST['challanNo'].'\' LIMIT 1 ');
        $studData = array_map('trim',$studData);
        $duedate = sqlgetresult('SELECT "duedate" FROM tbl_challans WHERE "challanNo" = \''.$_POST['challanNo'].'\' AND deleted=0 LIMIT 1 ');
        $studStatus = 'Prov.Promoted';

        foreach ($schoolextrautilitiesid as $k=>$schoolfeeId) {
            $schoolfeeId = trim($schoolfeeId);
            $singleqtyamount = getSFSandSchoolFeeByFeeId($schoolfeeId, $studData['clid'],$studData['academicYear'],$studData['term'] );

            $sql = sqlgetresult("SELECT * FROM editcreatedchallansnew('". $_POST['challanNo'] ."','". $studData['studentId'] ."', '". $studData['clid'] ."','".trim($schoolfeeId)."', '". $studData['term'] ."','$studStatus', '". $_SESSION['uid'] ."','".trim($singleqtyamount)."','". $studData['stream'] ."','". $studData['remarks'] ."','". $duedate['duedate'] ."','".trim($feegrp_id)."','". $studData['academicYear'] ."')");


            echo $sql ; echo "<hr/>";

        } 
    }
   
    $_SESSION['PSData'] = $parentData[0];

    $productData = sqlgetresult("SELECT * FROM tbl_accounts WHERE id = '$acc_id' ");

    $paygroup = array_combine($_POST['paygroup'], $_POST['paygroup_amt']);

    $i = 1;
    $product = ('<products>');
    foreach ($paygroup as $key => $value) {
        $key = trim($key);
        // echo $key;
        if( $key == 'SCHOOL FEE' && (trim($_POST['stream']) == '1' || trim($_POST['stream']) == '3' )) {
            $pname = $productData['1244172000004389'];
        } else if( $key == 'SCHOOL FEE' && (trim($_POST['stream']) == '2' || trim($_POST['stream']) == '4' ) ) {
            $pname = $productData['1244172000004365'];
        }  else if( $key == 'SCHOOL FEE' && (trim($_POST['stream']) == '6' ) ) {
            $pname = $productData['1244172000114886'];
        } else if( $key == 'LATE FEE') {
            $pname = $productData['1244172000004377'];
        }else {
            $pname = getProductByFeeGroup($key);
        }
              
        if($value != 0) {
            $product .=('<product><id>'.$i.'</id><name>'.trim($pname).'</name><amount>'.$value.'</amount></product>');
        }
        $i++;
    }
    $product .=('</products>');
    // $product = htmlentities($product);
    // $product = htmlspecialchars_decode(str_replace(' ','',$product));

    /******* Fee Pay Configuration ***/
    date_default_timezone_set('Asia/Calcutta');
    $datenow = date("d/m/Y h:m:s");
    $transactionDate = str_replace(" ", "%20", $datenow);
    $transactionId = rand(1, 1000000);

    $payment_id = sqlgetresult('INSERT INTO tbl_payments ("studentId","challanNo") VALUES (\''.$studentId.'\',\''.$cusChallanNo.'\') RETURNING id ');
    $payment_insert_id = $payment_id['id'];
    $encoded_data = base64_encode($cusId."_".$studentId."_".$_SESSION['uid']."_".$payment_id['id']."_".$cusChallanNo."_".$_SESSION['PSData']['term']."_".$_SESSION['PSData']['academic_yr']);  
    $returnstudscr = BASEURL.'parse_payment.php?studetscr='.$encoded_data;
    
    require_once 'atompay/TransactionRequest.php';
    $transactionRequest = new TransactionRequest();   

    //Setting all values here
    $transactionRequest->setMode($paymode);
    $transactionRequest->setLogin($login);
    $transactionRequest->setPassword($pass);    
    $transactionRequest->setProductId($proId);
    $transactionRequest->setAmount($amount);
    $transactionRequest->setTransactionCurrency($currency);
    $transactionRequest->setTransactionAmount($amount);
    $transactionRequest->setReturnUrl($returnstudscr);
    $transactionRequest->setClientCode($clientcode);
    $transactionRequest->setTransactionId($transactionId);
    $transactionRequest->setTransactionDate($transactionDate);
    $transactionRequest->setCustomerName($cusName);
    $transactionRequest->setCustomerEmailId($cusEmail);
    $transactionRequest->setCustomerChallanNo($cusChallanNo);
    $transactionRequest->setCustomerMobile($cusMobile);
    $transactionRequest->setCustomerBillingAddress("Chennai");
    $transactionRequest->setProducts($product);
    $transactionRequest->setCustomerAccount("639827");
    $transactionRequest->setReqHashKey($ReqHashKey);

    $url  = $transactionRequest->getPGUrl();
    // $url1 = htmlspecialchars_decode( $transactionRequest->getPGUrl());
    // print_r($url);
    // exit;

    // $payment_id = sqlgetresult("INSERT INTO tbl_payments (payment_url) VALUES ('$url') RETURNING id ");
    sqlgetresult('UPDATE tbl_payments SET payment_url=\''.$url.'\' WHERE id=\''.$payment_insert_id.'\'');
    $_SESSION['payment_id'] = $payment_id['id'];
    // print_r($url);
    // exit;

    header("Location: ".$url);
    exit;
}

if (isset($_POST['submit']) && $_POST['submit'] == 'updateparentdata' ) {
    $email = $_POST['email'];
    $mobileNumber = $_POST['mnum'];

    $otp = generateRandom();

    sqlgetresult('DELETE FROM tbl_parents_update WHERE "parentId" = \''.$_SESSION["uid"] .'\' ');

    $updateData = sqlgetresult('INSERT INTO tbl_parents_update (email, "mobileNumber", "parentId", otp, status, "updatedOn") VALUES (\''.$email.'\' , \''.$mobileNumber.'\' , \''. $_SESSION["uid"] .'\' , \''.$otp.'\', \'1\', CURRENT_TIMESTAMP) RETURNING id ');
    $mblNo = '918438219942';
    $smsTxt = urlencode("Your OTP is ".$otp." for 'LMOIS Parent Data Updation'.");
    $smsURL = "$smsBaseurl/sms.aspx?Id=$smsLoginId&Pwd=$smsLoginPass&PhNo=$mblNo&text=$smsTxt";
    // $ret = file($smsURL);

    echo json_encode($updateData['id']);
}

if ( isset($_POST['updateparentdata']) && $_POST['updateparentdata'] =='confirm' ) {
    $updateParentData = sqlgetresult('UPDATE tbl_parents par SET email = p.email , "mobileNumber" = p."mobileNumber" FROM tbl_parents_update p WHERE p.id = \''.$_POST['id'].'\' AND p.otp = \''.$_POST['otp'].'\' AND par.id = \''.$_SESSION['uid'].'\' RETURNING p.id ');
    // print_r($updateParentData);
    if( $updateParentData['id'] == '') {
        $_SESSION['error_msg'] = "<div class='error-msg'>OTP is invalid. Please try again later.</div>";
    } else {
        sqlgetresult('UPDATE tbl_parents_update SET status = \'0\' , "updatedOn" = CURRENT_TIMESTAMP WHERE id = \''.$_POST['id'].'\' ');
        $_SESSION['success_msg'] = "<div class='success-msg'>Your details are updated the portal successfully.</div>";
    }
    // print_r($_SESSION['success']);
    header("location:studetscr.php");

}

if ( isset($_POST['submit']) && $_POST['submit'] == 'checkparentupdate' ) {
    $checkparentupdate = sqlgetresult(' SELECT 1 AS updated FROM tbl_parents_update WHERE "parentId" = \''.$_SESSION['uid'].'\' AND status = \'0\' ');
    echo json_encode($checkparentupdate['updated']);
}

if ( isset($_POST['submit']) && $_POST['submit'] == 'checkStudentInfo' ) {
    $checkStudentupdate = sqlgetresult(' SELECT 1 AS updated FROM tbl_student WHERE "parentId" = \''.$_SESSION['uid'].'\' AND status = \'1\' LIMIT 1');
    echo json_encode($checkStudentupdate['updated']);
}

if ((isset($_GET['action']) == "unmap") && $_GET['id'] != ''){
    // print_r("hi");
    $stdid = $_GET['id'];
    $deletewarddetails = sqlgetresult('UPDATE tbl_student SET "parentId" = \''. 0 .'\' WHERE "studentId" = \''. $stdid .'\' ');
    // print_r($deletewarddetails);
    // exit;
    header("location:myaccount.php");
} 

if (isset($_POST['submit']) && $_POST['submit'] == "getstudentdetails"){
$stdid = $_POST['stdid'];
$getwarddetails = sqlgetresult('SELECT * FROM tbl_student WHERE "studentId" =  \''. $stdid .'\'');
if(isset($getwarddetails['class'])) {
    $getwarddetails['class'] = getClassbyNameId($getwarddetails['class']);
} else {
    $getwarddetails = 'null';
}
echo json_encode($getwarddetails);

// print_r($getwarddetails);
// exit;
}


if (isset($_POST['submit']) && $_POST['submit'] == "getNonFeeData"){ 

    $studId = $_POST['studId'];    
    $challanData = sqlgetresult('SELECT * FROM studentcheck WHERE "studentId" =\'' . $studId . '\' AND "parentId" = \''.$_SESSION["uid"].'\' ');   

    if ( sizeof($challanData) == 0 ) 
        $challanData = 'no_records';
    

    echo json_encode($challanData);
}

if (isset($_POST['pay_topup']) && $_POST['pay_topup'] == "pay"){ 

    $studentId = trim($_POST['studId']);

    $parentData = sqlgetresult('SELECT * FROM getparentdata WHERE "studentId" = \''.$studentId.'\' LIMIT 1',true);


    $parentData[0]['challanNo'] = trim($_POST['challanNo']);
    $_SESSION['PSNFData'] = $parentData[0];
    $amount = trim($_POST['tot']);   
    $cusName = $parentData[0]['userName'];
    $cusEmail = $parentData[0]['email'];
    $cusMobile = $parentData[0]['mobileNumber'];    
    $cusId = $parentData[0]['id'];
    $academicYear = $parentData[0]['academic_yr'];
    $term = $parentData[0]['term']; 
     
    $pname = getProductByFeeGroup('NON-FEE');
    $product = ('<products><product><id>1</id><name>'.$pname.'</name><amount>'.$amount.'</amount></product></products>');    
    $payment_id = sqlgetresult('INSERT INTO tbl_topup_payments ("studentId") VALUES (\''.$studentId.'\') RETURNING id');
    $encoded_data = base64_encode($cusId."_".$studentId."_".$_SESSION['uid']."_".$payment_id['id']."_".$cusChallanNo);  
    $returnonfeeURL = BASEURL.'parse_payment.php?cardtopup='.$encoded_data;
    // $product = htmlentities($product);
    // $product = htmlspecialchars_decode(str_replace(' ','',$product));

    /******* Fee Pay Configuration ***/
    date_default_timezone_set('Asia/Calcutta');
    $datenow = date("d/m/Y h:m:s");
    $transactionDate = str_replace(" ", "%20", $datenow);
    $transactionId = rand(1, 1000000);
    
    require_once 'atompay/TransactionRequest.php';
    $transactionRequest = new TransactionRequest();   

    //Setting all values here
    $transactionRequest->setMode($paymode);
    $transactionRequest->setLogin($login);
    $transactionRequest->setPassword($pass);    
    $transactionRequest->setProductId($proId);
    $transactionRequest->setAmount($amount);
    $transactionRequest->setTransactionCurrency($currency);
    $transactionRequest->setTransactionAmount($amount);
    $transactionRequest->setReturnUrl($returnonfeeURL);
    $transactionRequest->setClientCode($studentId);
    $transactionRequest->setTransactionId($transactionId);
    $transactionRequest->setTransactionDate($transactionDate);
    $transactionRequest->setCustomerName($cusName);
    $transactionRequest->setCustomerEmailId($cusEmail);
    $transactionRequest->setCustomerMobile($cusMobile);
    $transactionRequest->setCustomerBillingAddress("Chennai");
    $transactionRequest->setProducts($product);
    $transactionRequest->setCustomerAccount("639827");
    $transactionRequest->setReqHashKey($ReqHashKey);

    $url  = $transactionRequest->getPGUrl();
    // $url1 = htmlspecialchars_decode( $transactionRequest->getPGUrl());


    $payment_id = sqlgetresult('UPDATE tbl_topup_payments SET payment_url = \''.$url.'\' WHERE "id" = \''.$payment_id['id'].'\'') ;

    $_SESSION['nonfeepayment_id'] = $payment_id['id'];
    
    header("Location: ".$url);
    exit;
}

if (isset($_POST['submit']) && $_POST['submit'] == "getNonFeeStudentData")
{
    $pid = $_SESSION['uid'];
    $challanData = sqlgetresult('SELECT * FROM nonfeechallandata WHERE "parentId" = ' . $pid . ' AND ("challanStatus" = 0 OR "challanStatus" = 2) AND "visible" = \'1\' ',true);    

    $challanData1 =  array();
    if(count($challanData) > 0 ) {
        foreach ($challanData as $k => $data) {
            $challanData1[$data['challanNo']]['studentName'] = $data['studentName'];
            $challanData1[$data['challanNo']]['class_list'] = $data['class_list'];
            $challanData1[$data['challanNo']]['section'] = $data['section'];
            $challanData1[$data['challanNo']]['term'] = $data['term'];
            $challanData1[$data['challanNo']]['studentId'] = $data['studentId'];
            $challanData1[$data['challanNo']]['challanNo'] = $data['challanNo'];
            $challanData1[$data['challanNo']]['feeTypes'][] = trim($data['feeType']);
            $challanData1[$data['challanNo']]['classList'] = $data['clid'];   
            $challanData1[$data['challanNo']]['stream'] = $data['stream'];  
            $challanData1[$data['challanNo']]['org_total'][] = $data['total'];
            $challanData1[$data['challanNo']]['feeGroup'][] = $data['feeGroup'];  
            $challanData1[$data['challanNo']]['feename'][] = $data['feename'];    
        }
        // print_r($challanData1);
        $data =  array();
        foreach ($challanData1 as $feeData) {         
            $feeData['fee'] = array_sum($feeData['org_total']);            
            $data[] = $feeData;
        }  
        
    } else {
        $data = '0';
    }   
    echo json_encode($data);
}

if (isset($_POST['submit']) && $_POST['submit'] == "getNonFeeChallanData"){ 

    $cid = $_POST['cid'];
    $studId = $_POST['studId'];

    $params=[];
    $params['studentId']=$studId;
    $notpaid=toGetChallanNotPaidCount($params);
    
    $challanData = sqlgetresult('SELECT * FROM nonfeechallandata WHERE "studentId" =\'' . $studId . '\' AND  "challanNo" = \'' . $cid . '\' AND  ("challanStatus" = \'0 \' OR  "challanStatus" = \'2 \') AND "visible" = \'1\' ',true);
    if( $challanData[0]['hostel_need'] == 'Y') {
        $feeTypes = sqlgetresult('SELECT * FROM tbl_nonfee_type WHERE  (applicable=\'DH\' OR applicable=\'H\') ',true);
    } else {
        $feeTypes = sqlgetresult('SELECT * FROM tbl_nonfee_type WHERE  (applicable=\'DH\' OR applicable=\'D\') ',true);
    }
    $feeTypeArr = array();
    
    foreach ($feeTypes as $key => $value)  {
        $feeTypeArr[$value['id']] = $value['feeType'];       
    }    

    $challanData1 = array();
    $org_total=0;
    $feeData = array();
    $no_of_instalments="";
    $ispartial="";
    $challanno="";
    $groupdata=[];
    foreach ($challanData as $value) {
        $challanData1['paidornot'] = $notpaid;
        $challanno=$value['challanNo'];
        $challanData1['challanNo'] = $challanno;
        $challanData1['term'] = $value['term'];
        $challanData1['clid'] = $value['clid'];
        $challanData1['studentName'] = $value['studentName'];
        $challanData1['studentId'] = $value['studentId'];
        $challanData1['class_list'] = $value['class_list'];
        $challanData1['duedate'] = date("d-m-y",strtotime($value['duedate']));
        $challanData1['stream'] = $value['stream'];
        $challanData1['steamname'] = $value['steamname'];       
        $challanData1['feeGroup'] = $value['feeGroup'];       
        $org_total+=$value['total'];       
        $challanData1['org_total'] = $org_total;
        $ispartial=$value['partialpayment'];
        $no_of_instalments=$value['no_of_instalments'];

        if($value['remarks'] != ''){
            $challanData1['remarks'] = $value['remarks'];
        } else{
            $challanData1['remarks'] = 'Nill';
        }      

        /*$feetype = $value['feeType'];
        $feeData[trim($feetype)][] = $value['feeGroup'];
        $feeData[trim($feetype)][] = $value['total'];  */
        $feetype = trim($value['feeType']);
        $group_id = trim($value['feeGroup']);
        $group = getFeeGroupbyId($group_id);   
        $feeData[$feetype][] = $group_id;
        $feeData[$feetype][] = $value['total'];
        $groupdata[$group][$group_id][$feetype][] = $value['total'];
        $groupdata[$group][$group_id][$feetype][] = $value['feename'];

    }

    /*$feetypedata = sqlgetresult('SELECT * FROM getnonfeetypedata WHERE class=\''.$challanData1['clid'].'\' AND semester=\''.$challanData1['term'].'\' AND stream = \''.$challanData1['stream'].'\' ',true);   
    
    foreach ($feeData as $id=>$fee) {
        foreach($feetypedata as $val){
            if((trim($id)) == trim($val['feeType'])){
                $group = getFeeGroupbyId($val['feeGroup']);            
                $groupdata[$group][$val['feeGroup']][$val['id']][] = $val['amount'];
                $groupdata[$group][$val['feeGroup']][$val['id']][] = $val['feename'];
                $groupdata[$group][$val['feeGroup']][$val['id']][] = $val['feename'];                
            } 
        }
    }  */    
    $challanData1['feeData'] = $groupdata;

    $challanData1['ispartial'] = $ispartial;
    $challanData1['no_of_instalments'] = $no_of_instalments;
    $challanData1['minimumDue'] = 0;
    if($ispartial && $no_of_instalments){
        $minidue=($org_total/$no_of_instalments);
        $challanData1['minimumDue'] = ceil($minidue);
    } 

    $paidSoFor = getAmtPaidbyNFWChallan($challanno);
    $challanData1['paidSoFor']=$paidSoFor;
    $challanData1['netdue'] = $org_total-$paidSoFor;
   
    echo json_encode($challanData1);
}

// if (isset($_POST['paynonfeechallan']) && $_POST['paynonfeechallan'] == "confirm"){ 
if ($_GET['nonfeechallan'] != '' && $_GET['amount'] != '' && $_GET['studentId'] != ''){
    // print_r($_GET);
    // exit;

    $studentId = trim($_GET['studentId']);

    $parentData = sqlgetresult('SELECT * FROM getparentdatachallan WHERE "studentId" = \''.$studentId.'\' LIMIT 1',true);
    $parentData[0]['challanNo'] = trim($_GET['nonfeechallan']);
    $_SESSION['PSNFCData'] = $parentData[0];
    $amount = trim($_GET['amount']);   
    $cusName = $parentData[0]['userName'];
    $cusEmail = $parentData[0]['email'];
    $cusChallanNo = $parentData[0]['challanNo'];
    $cusMobile = $parentData[0]['mobileNumber'];    
    $cusId = $parentData[0]['id'];
    $academicYear = $parentData[0]['academic_yr'];
    $term = $parentData[0]['term']; 

    $pname = getProductByFeeGroup('NON-FEE');
    $product = ('<products><product><id>1</id><name>'.trim($pname).'</name><amount>'.$amount.'</amount></product></products>');    
    $payment_id = sqlgetresult('INSERT INTO tbl_nonfee_payments ("studentId") VALUES (\''.$studentId.'\') RETURNING id');
    $encoded_data = base64_encode($cusId."_".$studentId."_".$_SESSION['uid']."_".$payment_id['id']."_".$cusChallanNo);  
    $returnonfeeURL = BASEURL.'parse_payment.php?nonfeepayments='.$encoded_data;
    // $product = htmlentities($product);
    // $product = htmlspecialchars_decode(str_replace(' ','',$product));

    /******* Fee Pay Configuration ***/
    date_default_timezone_set('Asia/Calcutta');
    $datenow = date("d/m/Y h:m:s");
    $transactionDate = str_replace(" ", "%20", $datenow);
    $transactionId = rand(1, 1000000);
    
    require_once 'atompay/TransactionRequest.php';
    $transactionRequest = new TransactionRequest();   

    //Setting all values here
    $transactionRequest->setMode($paymode);
    $transactionRequest->setLogin($login);
    $transactionRequest->setPassword($pass);    
    $transactionRequest->setProductId($proId);
    $transactionRequest->setAmount($amount);
    $transactionRequest->setTransactionCurrency($currency);
    $transactionRequest->setTransactionAmount($amount);
    $transactionRequest->setReturnUrl($returnonfeeURL);
    $transactionRequest->setClientCode($studentId);
    $transactionRequest->setTransactionId($transactionId);
    $transactionRequest->setTransactionDate($transactionDate);
    $transactionRequest->setCustomerName($cusName);
    $transactionRequest->setCustomerEmailId($cusEmail);
    $transactionRequest->setCustomerChallanNo($cusChallanNo);
    $transactionRequest->setCustomerMobile($cusMobile);
    $transactionRequest->setCustomerBillingAddress("Chennai");
    $transactionRequest->setProducts($product);
    $transactionRequest->setCustomerAccount("639827");
    $transactionRequest->setReqHashKey($ReqHashKey);

    $url  = $transactionRequest->getPGUrl();
    // $url1 = htmlspecialchars_decode( $transactionRequest->getPGUrl());    

    $payment_id = sqlgetresult('UPDATE tbl_nonfee_payments SET payment_url = \''.$url.'\' WHERE "id" = \''.$payment_id['id'].'\'') ;
    $_SESSION['nonfeechallanpayment_id'] = $payment_id['id'];
    
    header("Location: ".$url);
    exit;
}

if (isset($_POST['paynonfee']) && $_POST['paynonfee'] == "confirm"){ 

    $studentId = trim($_POST['studId']);
    $params=[];
    $params['studentId']=$studentId;
    $notpaid=toGetChallanNotPaidCount($params);
    if($notpaid > 0){
        $_SESSION['error_msg'] = "<p class='error-msg'>Please pay school fees before attempting this payment.</p>";
        header("Location: nonfeewithoutchln.php");
        exit;
    }    

    $parentData = sqlgetresult('SELECT * FROM getparentdata WHERE "studentId" = \''.$studentId.'\' LIMIT 1',true);    
    $parentData[0]['challanNo'] = trim($_POST['challanNo']);
    $_SESSION['PSNFWCData'] = $parentData[0];
    $amount = trim($_POST['total']);   
    $cusName = $parentData[0]['userName'];
    $cusEmail = $parentData[0]['email'];
    $cusChallanNo = $parentData[0]['challanNo'];
    $cusMobile = $parentData[0]['mobileNumber'];    
    $cusId = $parentData[0]['id'];
    $academicYear = $parentData[0]['academic_yr'];
    $term = $parentData[0]['term']; 

    $pname = getProductByFeeGroup('NON-FEE');
    $product = ('<products><product><id>1</id><name>'.trim($pname).'</name><amount>'.$amount.'</amount></product></products>');    
     $payment_id = sqlgetresult('INSERT INTO tbl_nonfee_payments ("studentId") VALUES (\''.$studentId.'\') RETURNING id');
    $encoded_data = base64_encode($cusId."_".$studentId."_".$_SESSION['uid']."_".$payment_id['id']."_".$cusChallanNo);  
    $returnonfeeURL = BASEURL.'parse_payment.php?nonfeewithoutchln='.$encoded_data;
    // $product = htmlentities($product);
    // $product = htmlspecialchars_decode(str_replace(' ','',$product));

    /******* Fee Pay Configuration ***/
    date_default_timezone_set('Asia/Calcutta');
    $datenow = date("d/m/Y h:m:s");
    $transactionDate = str_replace(" ", "%20", $datenow);
    $transactionId = rand(1, 1000000);
    
    require_once 'atompay/TransactionRequest.php';
    $transactionRequest = new TransactionRequest();   

    //Setting all values here
    $transactionRequest->setMode($paymode);
    $transactionRequest->setLogin($login);
    $transactionRequest->setPassword($pass);    
    $transactionRequest->setProductId($proId);
    $transactionRequest->setAmount($amount);
    $transactionRequest->setTransactionCurrency($currency);
    $transactionRequest->setTransactionAmount($amount);
    $transactionRequest->setReturnUrl($returnonfeeURL);
    $transactionRequest->setClientCode($studentId);
    $transactionRequest->setTransactionId($transactionId);
    $transactionRequest->setTransactionDate($transactionDate);
    $transactionRequest->setCustomerName($cusName);
    $transactionRequest->setCustomerEmailId($cusEmail);
    $transactionRequest->setCustomerChallanNo($cusChallanNo);
    $transactionRequest->setCustomerMobile($cusMobile);
    $transactionRequest->setCustomerBillingAddress("Chennai");
    $transactionRequest->setProducts($product);
    $transactionRequest->setCustomerAccount("639827");
    $transactionRequest->setReqHashKey($ReqHashKey);

    $url  = $transactionRequest->getPGUrl();
    // $url1 = htmlspecialchars_decode( $transactionRequest->getPGUrl());    
    // print_r($url);exit;
    $payment_id = sqlgetresult('UPDATE tbl_nonfee_payments SET payment_url = \''.$url.'\' WHERE "id" = \''.$payment_id['id'].'\'') ;
    $_SESSION['nonfeewchallanpayment_id'] = $payment_id['id'];
    
    header("Location: ".$url);
    exit;
}

/********Common Non Fee - Start*********/

if (isset($_POST['paycommonnonfee']) && $_POST['paycommonnonfee'] == "pay"){ 
    date_default_timezone_set('Asia/Calcutta');
    $datenow = date("d/m/Y h:m:s");
    $studentId = trim($_POST['studId']);
    $parentData = sqlgetresult('SELECT * FROM getparentdata WHERE "studentId" = \''.$studentId.'\' LIMIT 1',true);
    $eventname = "EVENT-".trim($_POST['eventname']);
    $parentData[0]['challanNo'] = trim($eventname);
    $_SESSION['PSNFData'] = $parentData[0];
    $amount = trim($_POST['amountofevent']);   
    $cusName = $parentData[0]['userName'];
    $cusEmail = $parentData[0]['email'];
    $cusMobile = $parentData[0]['mobileNumber']; 
    $cusChallanNo = $parentData[0]['challanNo'];
    $cusId = $parentData[0]['id'];
    $academicYear = trim($parentData[0]['academic_yr']);
    $term = trim($parentData[0]['term']);
    $section = trim($parentData[0]['section']); 
    $class = trim($parentData[0]['class']); 
    $stream = trim($parentData[0]['stream']);
    $pname = getProductByFeeGroup('NON-FEE');;
    $product = ('<products><product><id>1</id><name>'.trim($pname).'</name><amount>'.$amount.'</amount></product></products>');    
    $payment_id = sqlgetresult('INSERT INTO tbl_nonfee_payments ("studentId","academicYear","term","stream","classList","section") VALUES (\''.$studentId.'\',\''.$academicYear.'\',\''.$term.'\',\''.$stream.'\',\''.$class.'\',\''.$section.'\') RETURNING id');
    if($payment_id['id']){
        $encoded_data = base64_encode($cusId."_".$studentId."_".$_SESSION['uid']."_".$payment_id['id']."_".$cusChallanNo);
            $returnonfeeURL = BASEURL.'parse_payment.php?commonnonfee='.$encoded_data;
            /******* Fee Pay Configuration ***/
            $transactionDate = str_replace(" ", "%20", $datenow);
            $transactionId = rand(1, 1000000);
            
            require_once 'atompay/TransactionRequest.php';
            $transactionRequest = new TransactionRequest();   

            //Setting all values here
            $transactionRequest->setMode($paymode);
            $transactionRequest->setLogin($login);
            $transactionRequest->setPassword($pass);    
            $transactionRequest->setProductId($proId);
            $transactionRequest->setAmount($amount);
            $transactionRequest->setTransactionCurrency($currency);
            $transactionRequest->setTransactionAmount($amount);
            $transactionRequest->setReturnUrl($returnonfeeURL);
            $transactionRequest->setClientCode($studentId);
            $transactionRequest->setTransactionId($transactionId);
            $transactionRequest->setTransactionDate($transactionDate);
            $transactionRequest->setCustomerName($cusName);
            $transactionRequest->setCustomerEmailId($cusEmail);
            $transactionRequest->setCustomerChallanNo($cusChallanNo);
            $transactionRequest->setCustomerMobile($cusMobile);
            $transactionRequest->setCustomerBillingAddress("Chennai");
            $transactionRequest->setProducts($product);
            $transactionRequest->setCustomerAccount("639827");
            $transactionRequest->setReqHashKey($ReqHashKey);

            $url  = $transactionRequest->getPGUrl();
            // $url1 = htmlspecialchars_decode( $transactionRequest->getPGUrl());


            $payment_id = sqlgetresult('UPDATE tbl_nonfee_payments SET payment_url = \''.$url.'\' WHERE "id" = \''.$payment_id['id'].'\'') ;
            $_SESSION['nonfeepayment_id'] = $payment_id['id'];

            header("Location: ".$url);
            exit;
    }else{
       $_SESSION['error_msg'] = "<p class='error-msg'> Some Error has occured. Try again later.</p>";
       header("Location: commonnonfee.php");
       exit; 
    }
}
if( isset($_POST['submit']) && $_POST['submit'] == 'geteventnames' ) {
    $stdid = $_POST['data'];

    $params=[];
    $params['studentId']=$stdid;
    $notpaid=toGetChallanNotPaidCount($params);
    if($notpaid > 0){
        $eventnames=2;
        echo json_encode($eventnames);
        exit;
    }

     $studentdetails = sqlgetresult('SELECT * FROM tbl_student WHERE "studentId" = \''.$stdid.'\'');

      $eventnames = sqlgetresult('SELECT n.*, f."amount" FROM tbl_nonfee_type n LEFT JOIN tbl_nonfee_configuration f ON f."feeType" = n."id" WHERE f."class" = \''.trim($studentdetails['class']).'\' AND f."academicYear" = \''.trim($studentdetails['academic_yr']).'\' AND f."semester" = \''.trim($studentdetails['term']).'\' AND n."applicable" = \'C\' AND n."status" = \'1\'',true);

      $paymenttablecheck = sqlgetresult('SELECT "challanNo" FROM tbl_nonfee_payments WHERE "studentId" = \''.$stdid.'\' AND "challanNo" ILIKE \'%EVENT%\' AND "transStatus" = \'Ok\'',true);
        // print_r($paymenttablecheck);
        if($paymenttablecheck != ''){
            foreach($paymenttablecheck AS $payments){
                $eventid[] = explode('-', trim($payments['challanNo']))[1];  

            }
 
        }
        else{
            $eventid = array();
        }
        $eventsnamesaftercheck = array();

        foreach($eventnames AS $events){
            if(!in_array(trim($events['id']), $eventid)){
                $eventsnamesaftercheck[$events['id']] = $events['feeType'];
            }
        }

      if($eventsnamesaftercheck != ''){
        $eventnames = $eventsnamesaftercheck;
      }
      else{
        $eventnames = 0;
      }

    echo json_encode($eventnames);
}

if( isset($_POST['submit']) && $_POST['submit'] == 'geteventamount' ) {
    $eventid = $_POST['eventid'];
    $stdid = $_POST['stdid'];

     $studentdetails = sqlgetresult('SELECT * FROM tbl_student WHERE "studentId" = \''.$stdid.'\'');

      $eventnames = sqlgetresult('SELECT n.*, f."amount" FROM tbl_nonfee_type n LEFT JOIN tbl_nonfee_configuration f ON f."feeType" = n."id" WHERE f."class" = \''.trim($studentdetails['class']).'\' AND f."academicYear" = \''.trim($studentdetails['academic_yr']).'\' AND f."semester" = \''.trim($studentdetails['term']).'\' AND n."applicable" = \'C\' AND n."id" = \''.trim($eventid).'\'');

    echo json_encode($eventnames);
}
/********Common Non Fee - End*********/

/********Advance Fee******/
if(isset($_POST['payadvance']) && $_POST['payadvance']=='Advance'){
   date_default_timezone_set('Asia/Calcutta');
   $uid = $_SESSION['uid'];
   $studentId = isset($_POST['studentId'])?trim($_POST['studentId']):"";
   $s_id = isset($_POST['s_id'])?trim($_POST['s_id']):"";
   $amount = isset($_POST['txtamt'])?trim($_POST['txtamt']):0;
   $in_type=1;
   $in_tstatus='';
   $in_transid='';
   $in_remarks='';
   $in_status=0;
   $balance=toGetAvailableBalance($s_id);

   if($amount >= 100){
      $parentData = sqlgetresult('SELECT * FROM getparentdata WHERE "studentId" = \''.$studentId.'\' LIMIT 1',true);
       if(count($parentData) > 0){
        //$eventname="ADV".$lastinsert_id;
        $_SESSION['PSLFData'] = $parentData[0];
        $amount = $amount;   
        $cusName = $parentData[0]['userName'];
        $cusEmail = $parentData[0]['email'];
        $cusMobile = $parentData[0]['mobileNumber']; 
        
        $cusId = $parentData[0]['id'];
        $academicYear = $parentData[0]['academic_yr'];
        $term = $parentData[0]['term'];
        $stream = trim($parentData[0]['stream']);
        $class = trim($parentData[0]['class']);  
        $sid = $parentData[0]['sid']; 
        $section = trim($parentData[0]['section']);
        $clientcode = $studentId . '|' . trim(getAcademicyrById($academicYear)); 
        $productData = sqlgetresult("SELECT * FROM tbl_accounts WHERE id = '$acc_id' ");
        /*SCHOOL FEE*/

        if($stream == '1' || $stream == '3' ) {
            $pname = $productData['1244172000004389'];
        } else if($stream == '2' || $stream == '4' ) {
            $pname = $productData['1244172000004365'];
        } else if($stream == '5') {
            $pname = $productData['1244172000114886'];
        } else {
            /*$stream == '6'*/
            $pname = $productData['1244172000114886'];
        }

        $product = ('<products><product><id>1</id><name>'.trim($pname).'</name><amount>'.$amount.'</amount></product></products>');    
 
        $run1=sqlgetresult("SELECT * FROM createadvancetransaction('$s_id','$uid','$product','$amount','$balance','$in_type','$in_tstatus','$in_transid','$in_remarks','$in_status','$class','$academicYear','$stream','$term','$section')",true);
         $lastinsert_id = isset($run1[0]['createadvancetransaction'])?$run1[0]['createadvancetransaction']:""; 
        if(!empty($lastinsert_id)){
                $eventname="ADV".$lastinsert_id;
                $parentData[0]['challanNo'] = trim($eventname);
                $cusChallanNo = $parentData[0]['challanNo'];

                $encoded_data = base64_encode($cusId."_".$studentId."_".$_SESSION['uid']."_".$lastinsert_id."_".$cusChallanNo."_".$sid);
                $returnonfeeURL = BASEURL.'parse_payment.php?advancePayment='.$encoded_data;

                
                $datenow = date("d/m/Y h:m:s");
                $transactionDate = str_replace(" ", "%20", $datenow);
                //$transactionId = rand(1, 1000000);

                $transactionId = $eventname;
                
                require_once 'atompay/TransactionRequest.php';
                $transactionRequest = new TransactionRequest();

                //Setting all values here
                $transactionRequest->setMode($paymode);
                $transactionRequest->setLogin($login);
                $transactionRequest->setPassword($pass);    
                $transactionRequest->setProductId($proId);
                $transactionRequest->setAmount($amount);
                $transactionRequest->setTransactionCurrency($currency);
                $transactionRequest->setTransactionAmount($amount);
                $transactionRequest->setReturnUrl($returnonfeeURL);
                $transactionRequest->setClientCode($clientcode);
                $transactionRequest->setTransactionId($transactionId);
                $transactionRequest->setTransactionDate($transactionDate);
                $transactionRequest->setCustomerName(trim($cusName));
                $transactionRequest->setCustomerEmailId(trim($cusEmail));
                $transactionRequest->setCustomerChallanNo($cusChallanNo);
                $transactionRequest->setCustomerMobile($cusMobile);
                $transactionRequest->setCustomerBillingAddress("Chennai");
                $transactionRequest->setProducts($product);
                $transactionRequest->setCustomerAccount("639827");
                $transactionRequest->setReqHashKey($ReqHashKey);

                $url  = $transactionRequest->getPGUrl();

                $payment_id = sqlgetresult('UPDATE tbl_advance_payment_log SET payment_url = \''.$url.'\',"transNum" = \''.$eventname.'\' WHERE "id" = \''.$lastinsert_id.'\'') ;
                $_SESSION['advpayment_id'] = $lastinsert_id;

                header("Location: ".$url);
                exit;
        }else{
           $_SESSION['error_msg'] = "<p class='error-msg'> Some Error has occured. Try again later.</p>";
            $errordata = 'Pay Advance create failed :</br>studentId:' . $studentId . '</br>amount:' . $amount . '</br>' . $_SESSION['error'];
            createErrorlog($errordata);
        }
       }else{
            $_SESSION['error_msg'] = "<p class='error-msg'> Some Error has occured. Try again later.</p>";
            $errordata = 'Pay Advance:</br>studentId:' . $studentId . '</br>amount:' . $amount . '</br>' . $_SESSION['error'];
            createErrorlog($errordata);
       }
   }else{
        $_SESSION['error_msg'] = "<p class='error-msg'> Advance amount should be greater than or equal to Rs.100</p>";
   }
   header("Location: advance.php");
   exit;
   
}


/********Full Payment (Summation of challans)******/
if(isset($_POST['paytotal']) && $_POST['paytotal']=='partial'){
   date_default_timezone_set('Asia/Calcutta');
   $createdOn = date("Y-m-d h:m:s");
   $uid = $_SESSION['uid'];
   $studentId = isset($_POST['studentId'])?trim($_POST['studentId']):"";
   $chalAcademicYear = isset($_POST['chalAcademicYear'])?trim($_POST['chalAcademicYear']):"";
   /* Student Primary ID */
   $s_id = isset($_POST['s_id'])?trim($_POST['s_id']):"";
   $grand_tot = isset($_POST['grand_tot'])?trim($_POST['grand_tot']):"";
   $challanids = isset($_POST['challanids'])?$_POST['challanids']:"";
   $sfs_amt = isset($_POST['sfs_amt'])?$_POST['sfs_amt']:[];
   $school_amt = isset($_POST['school_amt'])?$_POST['school_amt']:[];
   $feeTotal = isset($_POST['ftot'])?trim($_POST['ftot']):"";
   $tot = isset($_POST['tot'])?trim($_POST['tot']):"";
   $balance = isset($_POST['balamt'])?trim($_POST['balamt']):0;
   $waived_tot = isset($_POST['waived_tot'])?trim($_POST['waived_tot']):0;
   $amount = isset($_POST['partialamt'])?trim($_POST['partialamt']):0;
   $minamt = isset($_POST['minamt'])?trim($_POST['minamt']):0;
   $partialpaidamt = isset($_POST['partialpaidamt'])?trim($_POST['partialpaidamt']):0;
   $payop = isset($_POST['payop'])?trim($_POST['payop']):"";
   $disppart = isset($_POST['disppart'])?trim($_POST['disppart']):1;
   $actualAmt = $amount;

   



   if(($amount >= $minamt && $amount <= $grand_tot) || $disppart==0){
      
       $paygroupval = isset($_POST['paygroup'])?$_POST['paygroup']:"";
       if(!empty($paygroupval)){
        $paygroup=json_decode($paygroupval,true);
       }else{
        $paygroup=[];
       }

      /*if($amount > 50000){
        $amount=50000;
      }*/


        
       if(count($sfs_amt) > 0){
        $jsonsfs_amt=json_encode($sfs_amt);

        foreach($sfs_amt as $sfs){
           $arrdt=explode("-",$sfs);
           $gp=trim($arrdt[6]);
           $amt=trim($arrdt[4])*trim($arrdt[7]);
           $paygroup[$gp]+=$amt;
        }
       }else{
        $jsonsfs_amt="";
       }

       if(count($school_amt) > 0){
        $jsonschool_amt=json_encode($school_amt);
        foreach($school_amt as $sch){
           $arrdt1=explode("-",$sch);
           $gp1=trim($arrdt1[6]);
           $amt1=trim($arrdt1[4]);
           $paygroup[$gp1]+=$amt1;
        }
       }else{
        $jsonschool_amt="";
       }
       

       $accdatails=array('SCHOOL FEE'=>array(1=>'1244172000004389', 2=>'1244172000004365', 3=>'1244172000004389', 4=>'1244172000004365', 6=>'1244172000114886', 5=>'1244172000004389', 7=>'1244172000004860'));

       $parentData = sqlgetresult('SELECT * FROM getparentdata WHERE "studentId" = \''.$studentId.'\' LIMIT 1',true);
       // $parentData[0]['challanNo'] = trim($eventname);
        //$_SESSION['PSLFData'] = $parentData[0];
        //$amount = $amount;   
        $cusName = trim($parentData[0]['userName']);
        $cusEmail = trim($parentData[0]['email']);
        $cusMobile = trim($parentData[0]['mobileNumber']); 
        $cusId = $parentData[0]['id'];
        $academicYear = trim($parentData[0]['academic_yr']);
        $term = trim($parentData[0]['term']);
        $stream = trim($parentData[0]['stream']); 
        $sid = $parentData[0]['sid'];
        $class = trim($parentData[0]['class']);
        $section = trim($parentData[0]['section']);   
        
        //$clientcode = $studentId . '|' . trim(getAcademicyrById($academicYear)) . '|' . $term;
        if($chalAcademicYear){
         $clientcode = $studentId . '|' . trim(getAcademicyrById($chalAcademicYear));
        }else{
         $clientcode = $studentId . '|' . trim(getAcademicyrById($academicYear));
        }

        $productData = sqlgetresult("SELECT * FROM tbl_accounts WHERE id = '$acc_id' ");

        if($balance > 0){
            if($balance >= $amount){
               //$amount=0;
               $updatebal=$balance-$amount;
               $debitedAmt=$amount;
               $amount=0;
            }else{
              $amount=$amount-$balance;
              $updatebal=0;
              $debitedAmt=$balance;  
            }
        }
        /*SCHOOL FEE*/

        if($payop=='full' && $partialpaidamt == 0 && $balance==0){
            $i = 1;
            $product = ('<products>');
            foreach ($paygroup as $key1 => $value) {
                $key1=trim($key1);
                $key=getFeeGroupbyId($key1);
                $key = trim($key);
                //echo $key;
                //echo $key."__".$stream."-".$value."<br>";
                /*if( $stream == '5' ) {
                    $pname = $productData['1244172000004389'];
                }else{*/

                    switch ($key) {
                        case "SFS UTILITIES FEE":
                            $pname = $productData['1244172000018485'];
                            break;
                        case "REFUNDABLE DEPOSIT":
                            $pname = $productData['1244172000004353'];
                            break;
                        case "SCHOOL UTILITY FEE":
                            $pname = $productData['1244172000004377'];
                            break;    
                        case "TRANSPORT FEE":
                            $pname = $productData['1244172000004377'];
                            break;
                        case "LATE FEE":
                            $pname = $productData['1244172000004377'];
                            break;    
                        case "APPLICATION FEE":
                            $pname = $productData['1244155000122651'];
                            break;    
                        default:
                            $pdata=isset($accdatails[$key][$stream])?$accdatails[$key][$stream]:$accdatails['SCHOOL FEE'][$stream];
                            $pname = !empty($pdata)?$productData[$pdata]:"";
                    }

                //} 

                $pname=trim($pname);

                if($value != 0) {
                    if($pname == 'SIX'){
                        if($balance > 0){
                            $value=$value-$balance;
                        }
                        $product .=('<product><id>'.$i.'</id><name>'.$pname.'</name><amount>'.$value.'</amount></product>');

                    }else{
                         $product .=('<product><id>'.$i.'</id><name>'.$pname.'</name><amount>'.$value.'</amount></product>');
                    }
                   
                }
                $i++;
            }
           $product .=('</products>');
           //exit;
        }else{
            if($stream == '1' || $stream == '3' ) {
                $pname = $productData['1244172000004389'];
            } else if($stream == '2' || $stream == '4' ) {
                $pname = $productData['1244172000004365'];
            } else if($stream == '5') {
                $pname = $productData['1244172000004389'];
            } else if($stream == '7') {
                $pname = $productData['1244172000004860'];
            } else {
                /*$stream == '6'*/
                $pname = $productData['1244172000114886'];
            }
            $product = ('<products><product><id>1</id><name>'.trim($pname).'</name><amount>'.$amount.'</amount></product></products>');
        }
  
        
        $run1=sqlgetresult("SELECT * FROM createpartialtransaction('$s_id','$challanids','$jsonsfs_amt','$jsonschool_amt','$grand_tot','$feeTotal','$balance','$waived_tot','$uid','$product','$amount','$minamt','$payop')",true);
        //print_r($run1);
        
        $lastinsert_id = isset($run1[0]['createpartialtransaction'])?$run1[0]['createpartialtransaction']:""; 
        //exit;
        if(!empty($lastinsert_id)){
            $eventname="REF".$lastinsert_id;
            $parentData[0]['challanNo'] = $eventname;
            $_SESSION['PSLFData'] = $parentData[0];
            $datenow = date("d/m/Y h:m:s");
            $transactionDate = str_replace(" ", "%20", $datenow);
            //$transactionId = rand(1, 1000000);
            $transactionId = $eventname;
            $cusChallanNo = $eventname;

            if($amount == 0){
                //if($grand_tot == $amount){
                    $datenow1 = date("Y-m-d");
                    sqlgetresult('UPDATE tbl_partial_payment_log SET "transNum"=\''.$transactionId.'\',payment_url=\'Debited From wallet\',  "transStatus"=\'Ok\',"transDate"=\''.$datenow1.'\' WHERE id=\''.$lastinsert_id.'\'');
                    $_SESSION['partial_payment_id'] = $lastinsert_id;

                    //$balance=toGetAvailableBalance($sid);
                    //$tot=$balance-$amount;
                    $wallet = sqlgetresult("SELECT * FROM addAdvanceAmt('".$sid."','$updatebal','".$_SESSION['uid']."')");
                    $resadv=$wallet['addadvanceamt']?$wallet['addadvanceamt']:"";
                    if(!empty($resadv)){
                        $run1=sqlgetresult("SELECT * FROM createadvancetransaction('$s_id','$uid','$product','$debitedAmt','$balance','2','Ok','$lastinsert_id','$eventname','1','$class','$academicYear','$stream','$term','$section')",true);
                    }

                    //$rstAmt=0;

                    partialEwalletPayProcess($challanids, $sid, $uid, $studentId, $term, $academicYear, $actualAmt, $lastinsert_id, $createdOn, $amount);
                    /*$receiptupd=completePartialTransactionById($lastinsert_id);  
                    if($receiptupd > 0){*/
                        $_SESSION['success_msg'] = "<p class='success-msg'>Amount has been debited from the advance payment.</p>";
                    //}
                /*}else{

                }*/
            }
            else{

                //$encoded_data = base64_encode($studentId."_".$lastinsert_id."_".$cusChallanNo."_".$grand_tot."_".$challanids."_".$balance."_".$partialpaidamt);
                $encoded_data = base64_encode($studentId."_".$lastinsert_id."_".$grand_tot."_".$balance."_".$partialpaidamt); 
                $returnstudscr = BASEURL.'parse_payment.php?partialPayment='.$encoded_data;
                require_once 'atompay/TransactionRequest.php';
                $transactionRequest = new TransactionRequest();  

                if($payop=='full'){
                    $cust=$challanids;
                }else{
                    $cust=$cusChallanNo;
                } 

                //Setting all values here
                $transactionRequest->setMode($paymode);
                $transactionRequest->setLogin($login);
                $transactionRequest->setPassword($pass);    
                $transactionRequest->setProductId($proId);
                $transactionRequest->setAmount($amount);
                $transactionRequest->setTransactionCurrency($currency);
                $transactionRequest->setTransactionAmount($amount);
                $transactionRequest->setReturnUrl($returnstudscr);
                $transactionRequest->setClientCode($clientcode);
                $transactionRequest->setTransactionId($transactionId);
                $transactionRequest->setTransactionDate($transactionDate);
                $transactionRequest->setCustomerName($cusName);
                $transactionRequest->setCustomerEmailId($cusEmail);
                $transactionRequest->setCustomerChallanNo($cust);
                $transactionRequest->setCustomerMobile($cusMobile);
                $transactionRequest->setCustomerBillingAddress("Chennai");
                $transactionRequest->setProducts($product);
                $transactionRequest->setCustomerAccount("639827");
                $transactionRequest->setReqHashKey($ReqHashKey);

                $url  = $transactionRequest->getPGUrl();
                // $payment_id = sqlgetresult("INSERT INTO tbl_payments (payment_url) VALUES ('$url') RETURNING id ");
                sqlgetresult('UPDATE tbl_partial_payment_log SET "transNum"=\''.$transactionId.'\',payment_url=\''.$url.'\' WHERE id=\''.$lastinsert_id.'\'');
                $_SESSION['partial_payment_id'] = $lastinsert_id;
                header("Location: ".$url);
                exit;
            }
            
        }else{
            $_SESSION['error_msg'] = "<p class='error-msg'> Some Error has occured. Try again later.</p>";
            $errordata = 'Partial Amount of Challans:</br>studentId:' . $studentId . '</br>amount:' . $amount . '</br>' . $_SESSION['error'];
            createErrorlog($errordata);
        }
    }else{
        $_SESSION['error_msg'] = "<p class='error-msg'> Amount should be greater than or equal to minimum due.</p>";
   }
   header("Location: checkout.php");
   exit;
   
}

/********Full Payment (Summation of challans)******/
if(isset($_POST['paytotal']) && $_POST['paytotal']=='full-single'){
  
   
}

if (isset($_POST['filter']) && $_POST['filter'] == "fltconsolidatereport")
{
    $pid = isset($_POST['pid'])?trim($_POST['pid']):"";
    if(!empty($pid)){
        $studid = isset($_POST['studid'])?trim($_POST['studid']):"";
        $semesterselect = isset($_POST['semesterselect'])?trim($_POST['semesterselect']):"";
        $academicyr = isset($_POST['yearselect'])?trim($_POST['yearselect']):"";

        $where=[];
        $where[] = '(c.deleted = 0) AND (c.status = \'1\'::status) AND (c."academicYear" >=6)';
        if (!empty($studid))
        {
            $where[] = 's."studentId"=\'' . $studid . '\' ';

        }
        if (!empty($pid))
        {
            $where[] = 's."parentId"=\'' . $pid . '\' ';

        }
        
        if (!empty($semesterselect))
        {
            $where[] = 'c.term=\'' . $semesterselect . '\' ';

        }
        if (!empty($academicyr))
        {
            $where[] = 'c."academicYear"=\'' . $academicyr . '\' ';

        }

        
        $wherecond="";

        if(count($where) > 0){
            $wherecond="WHERE  ".implode(" AND ", $where);
        }
        

       $sql = 'SELECT c."studentId",s."studentName",cl.class_list,c.term,ay.year AS academic_yr,string_agg(c."challanNo", \',\') AS challanids,sum(c.org_total) AS demand  FROM tbl_challans c LEFT JOIN tbl_student s
     ON (((s."studentId")::bpchar = (c."studentId")::bpchar)  OR (((c."studentId")::bpchar)::TEXT = ANY (string_to_array ((s."old_studentId")::TEXT,\',\'::TEXT))) OR ((s.application_no)::bpchar =(c."studentId")::bpchar)) LEFT JOIN tbl_class cl ON (c."classList" = cl.id) LEFT JOIN tbl_academic_year ay ON (ay.id = c."academicYear") '.$wherecond.' GROUP BY c."studentId",s."studentName",cl.class_list,c.term,ay.year';


        $_SESSION['consolidatereportquery']=$sql; 
        $res = sqlgetresult($sql, true);
        $result_data=[];  
        foreach($res as $key=>$value){
            $demand=trim($value['demand']);
            $challanids=trim($value['challanids']);
            $challanarry=explode(",",$challanids);
            $challanarry=array_unique($challanarry);
            $waived=0;
            $paid=0;
            $partialpaid=0;
            $advpaid=0;
            $totalpaid=0;
            $receiptAmt1=0;
            $receiptAmt2=0;
             $paidDm=0;
             $paidDm1=0;
             $paidDm2=0;
            foreach ($challanarry as $challanno) {
                $wdata=getwaiveramountbychallan($challanno);
                if(isset($wdata['waiver_total']) && !empty($wdata['waiver_total'])){
                    $waived+=$wdata['waiver_total'];
                }

               //$paid+=getAmtPaidbychallan($challanno);
                $paid+=getReceiptChallan($challanno);
                $partialpaid+=getReceiptChallanPartial($challanno);

            }



             if($paid > 0){
               $receiptAmt1=$demand-$waived; 
             }

             if($partialpaid > 0){
               $receiptAmt2=$partialpaid; 
             }

             $totalpaid=$receiptAmt1+$receiptAmt2;


             //if($paid > 0){
               $paidDm1= $receiptAmt1+$waived;
             //}

             //if($partialpaid > 0){
               $paidDm2=$receiptAmt2; 
             //}

             $paidDm=$paidDm1+$paidDm2;



         /*if($waived > 0 && $paid > 0){
                if($paid >= $waived){
                    $receiptAmt=$paid-$waived; 
                }else{
                    $receiptAmt=$paid;
                }
            }else{
                $receiptAmt=$paid;
            }*/

            /*if($paid > 0){
                 $demand-$waived;
            }*/


            //$$demand



            $res[$key]['waiver']=$waived;
            $res[$key]['receipt']=$totalpaid;
            $res[$key]['outstanding']=$demand-$paidDm;
            //$res[$key]['outstanding']=$demand-$paid;
            unset($res[$key]['challanids']);
        }
        echo json_encode($res);

    }else{
        echo json_encode(null);
    }
}


if (isset($_POST['filter']) && $_POST['filter'] == "fltpartialpaid")
{
   
  $pid = isset($_POST['pid'])?trim($_POST['pid']):"";
  $studid = isset($_POST['studid'])?trim($_POST['studid']):"";
  if(!empty($pid)){

    $where=[];
    $where[] = '(deleted = 0) AND ("transStatus"=\'Ok\')';
    if (!empty($studid))
    {
        $where[] = '"studentId"=\'' . $studid . '\' ';

    }
    if (!empty($pid))
    {
        $where[] = '"parentId"=\'' . $pid . '\' ';

    }

    $wherecond="";

    if(count($where) > 0){
        $wherecond="WHERE  ".implode(" AND ", $where);
    }
        
    /* Log Details */
    $query = 'SELECT "studentId", "studentName","transNum" AS refnumber,"transStatus", receivedamount AS amount,"createdOn", challanids, grandtotal,"transId","transDate",payoption AS type FROM partialpaymentlogdetails '.$wherecond.' ORDER BY id DESC LIMIT 100';
    $res = sqlgetresult($query,true);
    foreach($res as $key=>$value){
        $studentId=trim($value['studentId']);
        $studentName=trim($value['studentName']);
        $refnumber=trim($value['refnumber']);
        $transStatus=trim($value['transStatus']);
        $amount=trim($value['amount']);
        $createdOn=trim($value['createdOn']);
        $challanids=trim($value['challanids']);
        $grandtotal=trim($value['grandtotal']);
        $transId=trim($value['transId']);
        $transDate=trim($value['transDate']);
        $type=trim($value['type']);

        if($transStatus=='Ok'){
            $status="<p style='color:green'>Success</p>";
        }else{
            $status="<p style='color:red'>Failed</p>";
        }

        $res[$key]['studentId']=$studentId;
        $res[$key]['studentName']=$studentName;
        $res[$key]['refnumber']=$refnumber;
        $res[$key]['transStatus']=$status;
        $res[$key]['amount']=$amount;
        $res[$key]['createdOn']=$createdOn;
        $res[$key]['challanids']=$challanids;
        $res[$key]['grandtotal']=$grandtotal;
        $res[$key]['transId']=$transId;
        $res[$key]['transDate']=$transDate;
        $res[$key]['type']=$type;
    }
    echo json_encode($res);
  }else{
     echo json_encode(null);
  } 
}


if (isset($_POST['filter']) && $_POST['filter'] == "fltadvancepaid")
{
   
  $pid = isset($_POST['pid'])?trim($_POST['pid']):"";
  $studid = isset($_POST['studid'])?trim($_POST['studid']):"";
  if(!empty($pid)){

    $where=[];
    //$where[] = '(deleted = 0)';
    if (!empty($studid))
    {
        $where[] = '"studentId"=\'' . $studid . '\' ';

    }
    if (!empty($pid))
    {
        $where[] = '"parentId"=\'' . $pid . '\' ';

    }

    $wherecond="";

    if(count($where) > 0){
        $wherecond='WHERE ("transStatus"=\'Ok\') AND '.implode(" AND ", $where);
    }
        
    /* Log Details */
    $query = 'SELECT id,"studentId", "studentName",type,"transNum" AS refnumber,"transStatus", amount, "createdOn" FROM advancePaymentLogDetails '.$wherecond.' ORDER BY id DESC LIMIT 100';
    $res = sqlgetresult($query,true);
    foreach($res as $key=>$value){
        $studentId=trim($value['studentId']);
        $studentName=trim($value['studentName']);
        $type=trim($value['type']);
        $refnumber=trim($value['refnumber']);
        $transStatus=trim($value['transStatus']);
        $amount=trim($value['amount']);
        $createdOn=trim($value['createdOn']);
        $date = date('Y-m-d', strtotime($createdOn));
        

        if($transStatus=='Ok'){
            $status="<p style='color:green'>Success</p>";
        }else{
            $status="<p style='color:red'>Failed</p>";
        }

        $res[$key]['studentId']=$studentId;
        $res[$key]['studentName']=$studentName;
        $res[$key]['refnumber']=$refnumber;
        $res[$key]['transStatus']=$status;
        $res[$key]['amount']=$amount;
        $res[$key]['transDate']=$date;
        $res[$key]['type']=$type;
    }
    echo json_encode($res);
  }else{
     echo json_encode(null);
  } 
}




/********Lunch Non-fee Start******/

if( isset($_POST['submit']) && $_POST['submit'] == 'geteventnames_lunch' ) {
    $stdid = trim($_POST['data']);
    $params=[];
    $params['studentId']=$stdid;
    $notpaid=toGetChallanNotPaidCount($params);
    if($notpaid > 0){
        $eventnames=2;
        echo json_encode($eventnames);
        exit;
    }

    $studentdetails = sqlgetresult('SELECT * FROM tbl_student WHERE "studentId" = \''.$stdid.'\'');
    $query='SELECT f."feeType" , c.amount , f.id, c.id as feeconfigid FROM tbl_fee_type f LEFT JOIN tbl_fee_configuration c ON f.id = c."feeType"::integer WHERE c.stream=\'' . trim($studentdetails['stream']) . '\' AND c.class=\'' . trim($studentdetails['class']) . '\' AND c.semester = \'' . trim($studentdetails['term']) . '\' AND c."academicYear"= \'' . trim($studentdetails['academic_yr']) . '\' AND f."status" = \'1\' AND f."deleted" = \'0\' AND f."applicable" ILIKE \'%L%\'';

    $eventnames = sqlgetresult($query,true);

   // print_r($eventnames);


    $paymenttablecheck = sqlgetresult('SELECT "challanNo" FROM tbl_payments WHERE "studentId" = \''.$stdid.'\' AND "challanNo" ILIKE \'%LUNCH%\' AND "transStatus" = \'Ok\'',true);
        // print_r($paymenttablecheck);
    $eventid = array();
    if($paymenttablecheck != ''){
        foreach($paymenttablecheck AS $payments){
            $eventid[] = explode('-', trim($payments['challanNo']))[2];  
        }
    }
    
    $eventsnamesaftercheck = array();

    foreach($eventnames AS $events){
        if(!in_array(trim($events['feeconfigid']), $eventid)){
            $ids=$events['amount']."_".$events['id']."_".$events['feeconfigid'];
            $eventsnamesaftercheck[$ids] = trim($events['feeType']);
        }
    }

    if($eventsnamesaftercheck != ''){
        $eventnames = $eventsnamesaftercheck;
    }
    else{
      $eventnames = 0;
    }
    echo json_encode($eventnames);
}

if (isset($_POST['paylunchfee']) && $_POST['paylunchfee'] == "pay"){ 
    $studentId = trim($_POST['studId']);
    $eventname=isset($_POST['eventname'])?trim($_POST['eventname']):"";
    $amount=isset($_POST['amountofevent'])?trim($_POST['amountofevent']):"";
    $uid = $_SESSION['uid'];
    if(!empty($amount) && $amount>0){
        $arr=explode("_",$eventname);
        //$challan_suffix=$arr[1]."-".$arr[2]."-".$studentId;
        // print_r($_POST);
        // exit;
        $parentData = sqlgetresult('SELECT * FROM getparentdata WHERE "studentId" = \''.$studentId.'\' LIMIT 1',true);
        $sid=$parentData[0]['sid'];
        $feetype=$arr[1];
        $feeconfigid=$arr[2];

        $challan_suffix=$feetype."-".$feeconfigid."-".$sid; 

        $eventname = "LUNCH-".$challan_suffix;
        $parentData[0]['challanNo'] = trim($eventname);
        $_SESSION['PSLFData'] = $parentData[0];
        //$amount = trim($_POST['amountofevent']);   
        $cusName = trim($parentData[0]['userName']);
        $cusEmail = trim($parentData[0]['email']);
        $cusMobile = $parentData[0]['mobileNumber']; 
        $cusChallanNo = $parentData[0]['challanNo'];
        $cusId = $parentData[0]['id'];
        $academicYear = $parentData[0]['academic_yr'];
        $term = $parentData[0]['term'];
        $stream = trim($parentData[0]['stream']);
        $class = trim($parentData[0]['class']);  
        $section = trim($parentData[0]['section']);
        $productData = sqlgetresult("SELECT * FROM tbl_accounts WHERE id = '$acc_id' ");  
        $pname = $productData['1244172000004377'];
        $product = ('<products><product><id>1</id><name>'.trim($pname).'</name><amount>'.$amount.'</amount></product></products>'); 

        /*Lunch*/
        $otherfeestype=2;
        $pay_type='Online';
        //echo "SELECT * FROM createotherfeestransaction('$sid','$uid','$amount','$otherfeestype','$feeconfigid','$feetype','0','$cusChallanNo','$class','$academicYear','$stream','$term','$section','$pay_type')";
        //exit;

        $run1=sqlgetresult("SELECT * FROM createotherfeestransaction('$sid','$uid','$amount','$otherfeestype','$feeconfigid','$feetype','0','$cusChallanNo','$class','$academicYear','$stream','$term','$section','$pay_type')",true);
        $lastinsert_id = isset($run1[0]['createotherfeestransaction'])?$run1[0]['createotherfeestransaction']:""; 
        if($lastinsert_id){
            $refnumber="LUN-".$lastinsert_id;

            $payment_id = sqlgetresult('INSERT INTO tbl_payments ("studentId","challanNo","transNum") VALUES (\''.$studentId.'\',\''.$cusChallanNo.'\',\''.$refnumber.'\') RETURNING id');
            $encoded_data = base64_encode($cusId."_".$studentId."_".$_SESSION['uid']."_".$payment_id['id']."_".$cusChallanNo."_".$lastinsert_id);
            $returnonfeeURL = BASEURL.'parse_payment.php?lunchfeepayments='.$encoded_data;

            $clientcode = $studentId . '|' . trim(getAcademicyrById($academicYear)); 
            // $product = htmlentities($product);
            // $product = htmlspecialchars_decode(str_replace(' ','',$product));
            // print_r($parentData);
            // exit;
            /******* Fee Pay Configuration ***/
            date_default_timezone_set('Asia/Calcutta');
            $datenow = date("d/m/Y h:m:s");
            $transactionDate = str_replace(" ", "%20", $datenow);
            //$transactionId = rand(1, 1000000);
            $transactionId = $refnumber;

            require_once 'atompay/TransactionRequest.php';
            $transactionRequest = new TransactionRequest();   

            //Setting all values here
            $transactionRequest->setMode($paymode);
            $transactionRequest->setLogin($login);
            $transactionRequest->setPassword($pass);    
            $transactionRequest->setProductId($proId);
            $transactionRequest->setAmount($amount);
            $transactionRequest->setTransactionCurrency($currency);
            $transactionRequest->setTransactionAmount($amount);
            $transactionRequest->setReturnUrl($returnonfeeURL);
            $transactionRequest->setClientCode($clientcode);
            $transactionRequest->setTransactionId($transactionId);
            $transactionRequest->setTransactionDate($transactionDate);
            $transactionRequest->setCustomerName($cusName);
            $transactionRequest->setCustomerEmailId($cusEmail);
            $transactionRequest->setCustomerChallanNo($cusChallanNo);
            $transactionRequest->setCustomerMobile($cusMobile);
            $transactionRequest->setCustomerBillingAddress("Chennai");
            $transactionRequest->setProducts($product);
            $transactionRequest->setCustomerAccount("639827");
            $transactionRequest->setReqHashKey($ReqHashKey);

            $url  = $transactionRequest->getPGUrl();
            // $url1 = htmlspecialchars_decode( $transactionRequest->getPGUrl());
            $payment_id = sqlgetresult('UPDATE tbl_payments SET payment_url = \''.$url.'\' WHERE "id" = \''.$payment_id['id'].'\'');

            $ref_id = sqlgetresult('UPDATE tbl_otherfees_payment_log SET "refNum" = \''.$refnumber.'\' WHERE "id" = \''.$lastinsert_id.'\'');
            $_SESSION['lunchfeepayment_id'] = $payment_id['id'];

            header("Location: ".$url);
        }else{
            $_SESSION['error_msg']="<p class='error-msg'> Some Error has occured. Try again later.</p>";
            header("Location: lunchfee.php");
        }
    }else{
        $_SESSION['error_msg'] = "<p class='error-msg'>Amount should be greater than 0.</p>";
        header("Location: lunchfee.php");
    }
    exit;
}

/********Lunch Non-fee End******/

/********Uniformfee Start******/

if( isset($_POST['submit']) && $_POST['submit'] == 'geteventnames_uniform' ) {
    $stdid = trim($_POST['data']);
    $params=[];
    $params['studentId']=$stdid;
    $notpaid=toGetChallanNotPaidCount($params);
    if($notpaid > 0){
        $eventnames=2;
        echo json_encode($eventnames);
        exit;
    }
    $studentdetails = sqlgetresult('SELECT * FROM tbl_student WHERE "studentId" = \''.$stdid.'\'');
    $query='SELECT f."feeType" , c.amount , f.id, c.id as feeconfigid FROM tbl_fee_type f LEFT JOIN tbl_fee_configuration c ON f.id = c."feeType"::integer WHERE c.stream=\'' . trim($studentdetails['stream']) . '\' AND c.class=\'' . trim($studentdetails['class']) . '\' AND c.semester = \'' . trim($studentdetails['term']) . '\' AND c."academicYear"= \'' . trim($studentdetails['academic_yr']) . '\' AND f."status" = \'1\' AND f."deleted" = \'0\' AND f."applicable" ILIKE \'%U%\'';
    $eventnames = sqlgetresult($query,true);

    //$paymenttablecheck = sqlgetresult('SELECT "challanNo" FROM tbl_payments WHERE "studentId" = \''.$stdid.'\' AND "challanNo" ILIKE \'%UNIFORM%\' AND "transStatus" = \'Ok\'',true);
        // print_r($paymenttablecheck);
    $eventid = array();
    /*if($paymenttablecheck != ''){
        foreach($paymenttablecheck AS $payments){
            $eventid[] = explode('-', trim($payments['challanNo']))[2];  
        }
    }*/
    
    $eventsnamesaftercheck = array();

    foreach($eventnames AS $events){
        //if(!in_array(trim($events['feeconfigid']), $eventid)){
            $ids=$events['amount']."_".$events['id']."_".$events['feeconfigid'];
            $eventsnamesaftercheck[$ids] = trim($events['feeType']);
        //}
    }

    if($eventsnamesaftercheck != ''){
        $eventnames = $eventsnamesaftercheck;
    }
    else{
      $eventnames = 0;
    }
    echo json_encode($eventnames);
}

if (isset($_POST['payuniformfee']) && $_POST['payuniformfee'] == "pay"){

    date_default_timezone_set('Asia/Calcutta');
    $datenow = date("d/m/Y h:m:s");
    $transactionDate = str_replace(" ", "%20", $datenow);
    $uid = $_SESSION['uid']; 
    $studentId = trim($_POST['studId']);
    $eventname=isset($_POST['eventname'])?trim($_POST['eventname']):"";
    $sfsextraqty=isset($_POST['sfsextraqty'])?trim($_POST['sfsextraqty']):"";

    $arr=explode("_",$eventname);
    //$challan_suffix=$arr[1]."-".$arr[2]."-".$studentId."-".$sfsextraqty;
    $parentData = sqlgetresult('SELECT * FROM getparentdata WHERE "studentId" = \''.$studentId.'\' LIMIT 1',true);
    
    $sid=$parentData[0]['sid'];
    $feetype=$arr[1];
    $feeconfigid=$arr[2];
    $challan_suffix=$feetype."-".$feeconfigid."-".$sid."-".$sfsextraqty;

    $eventname = "UNIFORM-".$challan_suffix;
    //$paymenttablecheck = sqlgetresult('SELECT COUNT(*) as total FROM tbl_payments WHERE "challanNo" = \''.$eventname.'\' AND "transStatus" = \'Ok\'',true);
    //$num=$paymenttablecheck[0]['total'];
    //if($num > 0){
    //$serial=$num+1;
    //$eventname=$eventname."-".$serial;
    //}
    $parentData[0]['challanNo'] = trim($eventname);
    $_SESSION['PSLFData'] = $parentData[0];
    $amount = trim($_POST['amountofevent']);   
    $cusName = trim($parentData[0]['userName']);
    $cusEmail = trim($parentData[0]['email']);
    $cusMobile = $parentData[0]['mobileNumber']; 
    $cusChallanNo = $parentData[0]['challanNo'];
    $cusId = $parentData[0]['id'];

    $academicYear = $parentData[0]['academic_yr'];
    $term = $parentData[0]['term'];
    $stream = trim($parentData[0]['stream']);
    $class = trim($parentData[0]['class']);  
    $section = trim($parentData[0]['section']);
    /*Uniform*/
    $otherfeestype=1;
    $pay_type='Online';

    $run1=sqlgetresult("SELECT * FROM createotherfeestransaction('$sid','$uid','$amount','$otherfeestype','$feeconfigid','$feetype','$sfsextraqty','$cusChallanNo','$class','$academicYear','$stream','$term','$section','$pay_type')",true);
    $lastinsert_id = isset($run1[0]['createotherfeestransaction'])?$run1[0]['createotherfeestransaction']:""; 
    if($lastinsert_id){
        $refnumber="UNI-".$lastinsert_id;
        $productData = sqlgetresult("SELECT * FROM tbl_accounts WHERE id = '$acc_id' ");  
        /*SFS*/
        $pname = $productData['1244172000018485'];
        $product = ('<products><product><id>1</id><name>'.trim($pname).'</name><amount>'.$amount.'</amount></product></products>');    
        $payment_id = sqlgetresult('INSERT INTO tbl_payments ("studentId","challanNo","transNum") VALUES (\''.$studentId.'\',\''.$cusChallanNo.'\',\''.$refnumber.'\') RETURNING id');
        $encoded_data = base64_encode($cusId."_".$studentId."_".$_SESSION['uid']."_".$payment_id['id']."_".$cusChallanNo."_".$lastinsert_id);
        $returnonfeeURL = BASEURL.'parse_payment.php?uniformfeepayments='.$encoded_data;
        $clientcode = $studentId . '|' . trim(getAcademicyrById($academicYear));

        /******* Fee Pay Configuration ***/
        //$transactionId = rand(1, 1000000);
        $transactionId = $refnumber;

        require_once 'atompay/TransactionRequest.php';
        $transactionRequest = new TransactionRequest();   

        //Setting all values here
        $transactionRequest->setMode($paymode);
        $transactionRequest->setLogin($login);
        $transactionRequest->setPassword($pass);    
        $transactionRequest->setProductId($proId);
        $transactionRequest->setAmount($amount);
        $transactionRequest->setTransactionCurrency($currency);
        $transactionRequest->setTransactionAmount($amount);
        $transactionRequest->setReturnUrl($returnonfeeURL);
        $transactionRequest->setClientCode($clientcode);
        $transactionRequest->setTransactionId($transactionId);
        $transactionRequest->setTransactionDate($transactionDate);
        $transactionRequest->setCustomerName($cusName);
        $transactionRequest->setCustomerEmailId($cusEmail);
        $transactionRequest->setCustomerChallanNo($cusChallanNo);
        $transactionRequest->setCustomerMobile($cusMobile);
        $transactionRequest->setCustomerBillingAddress("Chennai");
        $transactionRequest->setProducts($product);
        $transactionRequest->setCustomerAccount("639827");
        $transactionRequest->setReqHashKey($ReqHashKey);

        $url  = $transactionRequest->getPGUrl();
        // $url1 = htmlspecialchars_decode( $transactionRequest->getPGUrl());
        $payment_id = sqlgetresult('UPDATE tbl_payments SET payment_url = \''.$url.'\' WHERE "id" = \''.$payment_id['id'].'\'') ;
        $ref_id = sqlgetresult('UPDATE tbl_otherfees_payment_log SET "refNum" = \''.$refnumber.'\' WHERE "id" = \''.$lastinsert_id.'\'');
        $_SESSION['lunchfeepayment_id'] = $payment_id['id'];

        header("Location: ".$url);
    }else{
       $_SESSION['error_msg']="<p class='error-msg'> Some Error has occured. Try again later.</p>";
       header("Location: uniformfee.php");
    }
    exit;
}

/********Lunch Non-fee End******/

if (isset($_POST['submit']) && $_POST['submit'] == "getTransportFeeStudentData")
{
    $pid = $_SESSION['uid'];
    $challanData = sqlgetresult('SELECT * FROM challandatanew WHERE "parentId" = ' . $pid . ' AND "challanStatus" = 0 AND "studStatus"=\'Transport.Fee\'',true);    

    $challanData1 =  array();
    if(count($challanData) > 0 ) {
        foreach ($challanData as $k => $data) {
            $challanData1[$data['challanNo']]['studentName'] = trim($data['studentName']);
            $challanData1[$data['challanNo']]['class_list'] = trim($data['class_list']);
            $challanData1[$data['challanNo']]['section'] = trim($data['section']);
            $challanData1[$data['challanNo']]['term'] = trim($data['term']);
            $challanData1[$data['challanNo']]['studentId'] = trim($data['studentId']);
            $challanData1[$data['challanNo']]['challanNo'] = trim($data['challanNo']);
            $challanData1[$data['challanNo']]['feeTypes'][] = trim($data['feeType']);
            $challanData1[$data['challanNo']]['classList'] = trim($data['clid']);   
            $challanData1[$data['challanNo']]['stream'] = trim($data['stream']); 
            $challanData1[$data['challanNo']]['academicYear'] = getAcademicyrById(trim($data['academicYear'])); 
            $challanData1[$data['challanNo']]['org_total'][] = $data['total'];
            $challanData1[$data['challanNo']]['feeGroup'][] = trim($data['feeGroup']);  
            $challanData1[$data['challanNo']]['feename'][] = trim($data['feename']);
    
        }
        // print_r($challanData1);
        $data =  array();

        foreach ($challanData1 as $chno=>$feeData) {  
            $waived=0; 
            $wdata=getwaiveramountbychallan($chno);
            if(isset($wdata['waiver_total']) && !empty($wdata['waiver_total'])){
                $waived+=$wdata['waiver_total'];
            }      
            $feeData['fee'] = array_sum($feeData['org_total']) - $waived;            
            $data[] = $feeData;
        }  
        
    } else {
        $data = '0';
    }   
    echo json_encode($data);
}

if (isset($_POST['submit']) && $_POST['submit'] == "getTransportFeeChallanData"){ 

    $cid = $_POST['cid'];
    $studId = $_POST['studId'];
    $pid = $_SESSION['uid'];

    $params=[];
    $params['studentId']=$studId;
    $params['parentId']=$pid;
    $notpaid=toGetChallanNotPaidCount($params);
    
    $challanData = sqlgetresult('SELECT * FROM challandatanew WHERE "parentId" =\'' . $pid . '\' AND  "challanNo" = \'' . $cid . '\' AND "challanStatus" = \'0 \' AND "studStatus"=\'Transport.Fee\'  ',true);
    $feeTypes = sqlgetresult('SELECT id, "feeType" FROM getfeetypes WHERE applicable ILIKE \'%T%\' ',true);
    $feeTypeArr = array();
    $otherFees1 = array();
    $otherFees2 = array();
    foreach ($feeTypes as $key => $value)
    {
        $feeTypeArr[$value['id']] = $value['feeType'];
        if ((trim($value['feegroupname'])) == 'SFS UTILITIES FEE' && !stristr($value['feeType'],"TRANSPORT"))
        {
            $otherFees1[$value['id']] = $value['feeType'];
        }
    }
    foreach ($feeTypes as $key => $value)
    {
        $feeTypeArr[$value['id']] = $value['feeType'];
        if ((trim($value['feegroupname'])) == 'SCHOOL UTILITY FEE') 
        {
            $otherFees2[$value['id']] = $value['feeType'];
        }
    }   

    $challanData1 = array();
        $challanData1['sfsutilityotherfees'] = $otherFees1;
        $challanData1['schoolotherFees'] = $otherFees2;
        $waivedarray = array();
        $latefee = 0;
        $feeData = array();
        $chlncnt = count($challanData);
        foreach ($challanData as $k => $value) {
            $challanData1['paidornot'] = $notpaid;
            $challanData1['challanNo'] = $value['challanNo'];
            $challanData1['term'] = $value['term'];
            $challanData1['clid'] = $value['clid'];
            $challanData1['section'] = $value['section'];
            $challanData1['studentName'] = $value['studentName'];
            $challanData1['studentId'] = $value['studentId'];
            $challanData1['class_list'] = $value['class_list'];
            if($value['duedate'] == ''){
            $challanData1['duedate'] = "Nil";
            }else{
            $challanData1['duedate'] = date("d-m-y",strtotime($value['duedate']));
            }
            $challanData1['stream'] = $value['stream'];
            $challanData1['steamname'] = $value['steamname'];
            $challanData1['academic_yr'] = $value['academicYear'];
            $challanData1['academicYear'] = getAcademicyrById($value['academicYear']);
            // $challanData1['waivedAmount'] = $value['waivedAmount'];
            // $challanData1['waivedTotal'] = $value['waivedTotal'];
            $challanData1['feeGroup'] = $value['feeGroup'];
            $challanData1['studStatus'] = $value['studStatus'];
            $challanData1['org_total'] = $value['org_total'];
            if($value['remarks'] != ''){
                $challanData1['remarks'] = $value['remarks'];
            }
            else{
            $challanData1['remarks'] = 'Nill';

            }
            $feetypearray[getFeeGroupbyId($value['feeGroup'])][$value['feeType']][] = $value['org_total'];
            $feetypearray[getFeeGroupbyId($value['feeGroup'])][$value['feeType']][] = getFeeTypebyId($value['feeType']);

             $feetypearray[getFeeGroupbyId($value['feeGroup'])]['waived'] = getwaiveddata($value['challanNo'], $value['feeGroup']);
            $cnt = $k+1;
            if($cnt == $chlncnt) {
                $groupdata = $feetypearray;

            }  
        }

    if($latefee == 1){
        $late = 'LATE FEE';
        $latefee = sqlgetresult('SELECT "org_total" FROM tbl_challans WHERE "challanNo" = \''.$challanData1['challanNo'].'\' AND "challanStatus" = \'0 \' AND "feeGroup" = \''.$late.'\' AND deleted=0');
        // $latefeedata[$val['feeGroup']][] = $latefee['org_total'];
        $groupdata[$late][$late][1][] = $latefee['org_total'];
        $groupdata[$late][$late][1][] = $late;
    }
    uksort($groupdata, function ($key1, $key2) use($order)
    {
        return (array_search(trim($key1) , $order) > array_search(trim($key2) , $order));
    });
    $challanData1['feeData'] = $groupdata;
    $challanData1['waivedData'] = $waivedarray;

    echo json_encode($challanData1);
}

if (isset($_POST['paytransportfeechallan']) && $_POST['paytransportfeechallan'] == "confirm"){ 
    $studentId = isset($_POST['studId'])?trim($_POST['studId']):"";
    $cusChallanNo = isset($_POST['challanNo'])?trim($_POST['challanNo']):"";
    $amount = isset($_POST['tot'])?trim($_POST['tot']):0;
    $otherfeestype=3;
    $pay_type='Online';
    $uid = $_SESSION['uid'];
    $feeconfigid=11;
    $feetype=0;
    if(!empty($studentId) && !empty($cusChallanNo)){
        if($amount > 0){
            $parentData = sqlgetresult('SELECT * FROM getparentdata WHERE "studentId" = \''.$studentId.'\' LIMIT 1',true);
            $sid=$parentData[0]['sid'];
            $_SESSION['PSTFData'] = $parentData[0];
            $cusName = trim($parentData[0]['userName']);
            $cusEmail = trim($parentData[0]['email']);
            $cusMobile = $parentData[0]['mobileNumber']; 
            $cusId = $parentData[0]['id'];
            $academicYear = $parentData[0]['academic_yr'];
            $term = $parentData[0]['term'];
            $stream = trim($parentData[0]['stream']);
            $class = trim($parentData[0]['class']);  
            $section = trim($parentData[0]['section']);
            $productData = sqlgetresult("SELECT * FROM tbl_accounts WHERE id = '$acc_id' "); 
            /*if( $stream == '5' ) {
                $pname = $productData['1244172000114886'];
            }else{
                $pname = $productData['1244172000004377'];
            }*/
            /* SFS */
            $pname = $productData['1244172000018485']; 
            $product = ('<products><product><id>1</id><name>'.trim($pname).'</name><amount>'.$amount.'</amount></product></products>'); 

            $run1=sqlgetresult("SELECT * FROM createotherfeestransaction('$sid','$uid','$amount','$otherfeestype',NULL,NULL,NULL,'$cusChallanNo','$class','$academicYear','$stream','$term','$section','$pay_type')",true);
            $lastinsert_id = isset($run1[0]['createotherfeestransaction'])?$run1[0]['createotherfeestransaction']:""; 
            if($lastinsert_id){
                $refnumber="TRA-".$lastinsert_id;
                $payment_id = sqlgetresult('INSERT INTO tbl_payments ("studentId","challanNo","transNum") VALUES (\''.$studentId.'\',\''.$cusChallanNo.'\',\''.$refnumber.'\') RETURNING id');
                $encoded_data = base64_encode($cusId."_".$studentId."_".$uid."_".$payment_id['id']."_".$cusChallanNo."_".$lastinsert_id);
                $returtransfeeURL = BASEURL.'parse_payment.php?transportfeepayments='.$encoded_data;
                $clientcode = $studentId . '|' . trim(getAcademicyrById($academicYear)); 
                /******* Fee Pay Configuration ***/
                date_default_timezone_set('Asia/Calcutta');
                $datenow = date("d/m/Y h:m:s");
                $transactionDate = str_replace(" ", "%20", $datenow);
                $transactionId = $refnumber;

                require_once 'atompay/TransactionRequest.php';
                $transactionRequest = new TransactionRequest();   

                //Setting all values here
                $transactionRequest->setMode($paymode);
                $transactionRequest->setLogin($login);
                $transactionRequest->setPassword($pass);    
                $transactionRequest->setProductId($proId);
                $transactionRequest->setAmount($amount);
                $transactionRequest->setTransactionCurrency($currency);
                $transactionRequest->setTransactionAmount($amount);
                $transactionRequest->setReturnUrl($returtransfeeURL);
                $transactionRequest->setClientCode($clientcode);
                $transactionRequest->setTransactionId($transactionId);
                $transactionRequest->setTransactionDate($transactionDate);
                $transactionRequest->setCustomerName($cusName);
                $transactionRequest->setCustomerEmailId($cusEmail);
                $transactionRequest->setCustomerChallanNo($cusChallanNo);
                $transactionRequest->setCustomerMobile($cusMobile);
                $transactionRequest->setCustomerBillingAddress("Chennai");
                $transactionRequest->setProducts($product);
                $transactionRequest->setCustomerAccount("639827");
                $transactionRequest->setReqHashKey($ReqHashKey);

                $url  = $transactionRequest->getPGUrl();
                // $url1 = htmlspecialchars_decode( $transactionRequest->getPGUrl());
                $payment_id = sqlgetresult('UPDATE tbl_payments SET payment_url = \''.$url.'\' WHERE "id" = \''.$payment_id['id'].'\'');

                $ref_id = sqlgetresult('UPDATE tbl_otherfees_payment_log SET "refNum" = \''.$refnumber.'\' WHERE "id" = \''.$lastinsert_id.'\'');
                $_SESSION['transfeepayment_id'] = $payment_id['id'];

                header("Location: ".$url);
                exit;
            }else{
                $_SESSION['error_msg']="<p class='error-msg'> Some Error has occured. Try again later.</p>";
            }
        }else{
            $_SESSION['error_msg'] = "<p class='error-msg'>Amount should be greater than 0.</p>";
        } 
    }else{
        $_SESSION['error_msg'] = "<p class='error-msg'>Mandatory fields are Missing.</p>";
        
    }
    header("Location: transportfee.php");
    exit; 
}


if (isset($_POST['filter']) && $_POST['filter'] == "fltpaidchallandata")
{
  $pid = isset($_POST['pid'])?trim($_POST['pid']):$_SESSION['uid'];
  if(!empty($pid)){
    /* Paid Details */
    $query = 'SELECT "feeGroup","studentId","studentName","challanNo","org_total","updatedOn","studentid_in_challan",acdyear,"challanStatus","visibleStatus" FROM challanData WHERE ("challanStatus" = 1 OR "challanStatus" = 3) AND "studStatus"!=\'Transport.Fee\' AND "parentId"=\'' . $pid . '\' ORDER BY "updatedOn" DESC';
    $paiddata = sqlgetresult($query,true);
    $challanData = array();
    $total =0;
    $tot = 0;    
    $challanNo  = '';
    $feeData = array();
    $count = count($paiddata);
    if($count != 0) {
        foreach($paiddata as $k => $data){
            $challanData[$data['challanNo']]['studentName'] = trim($data['studentName']);
            $challanData[$data['challanNo']]['studentId'] = trim($data['studentId']);
            $challanData[$data['challanNo']]['challanNo'] = trim($data['challanNo']);
            $challanData[$data['challanNo']]['org_total'][] = $data['org_total'];   
            $challanData[$data['challanNo']]['updatedOn'] = $data['updatedOn'];
            $challanData[$data['challanNo']]['studentid_in_challan'] = trim($data['studentid_in_challan']);
            $challanData[$data['challanNo']]['challanStatus'] = trim($data['challanStatus']);
            $challanData[$data['challanNo']]['feeGroup'][] = $data['feeGroup']; 
            $challanData[$data['challanNo']]['visibleStatus'] = trim($data['visibleStatus']);
            $challanData[$data['challanNo']]['acdyear'] = trim($data['acdyear']);
                
        }
        $data =  array();
        foreach ($challanData as $feeData) {
            $challanno=$feeData['challanNo'];
            $cstatus=$feeData['challanStatus'];
            $studentid_in_challan=$feeData['studentid_in_challan'];
            $visibleStatus=$feeData['visibleStatus'];
            $date = date('dmY', strtotime($feeData['updatedOn']));
            $pdfpath = BASEURL.'receipts/'.$date.'/'.str_replace('/', '', $challanno).'.pdf';
            if($cstatus == 3){
               $fgrp=implode(",",$feeData['feeGroup']);
               $act="<a type='button' data-id='".$studentid_in_challan."' data-feegroup='".$fgrp."'class='paymodal' id='".$challanno."' data-visible='".$visibleStatus."'  data-type='moved' data-toggle='modal' data-target='#payModal' style='cursor:pointer'>View Challan</a>";
            }else{
                $act='<a href="'.$pdfpath.'" target="_blank">Download Receipt</a>';
            }
            $feeData['fee'] = array_sum($feeData['org_total']);
            $feeData['studentId'] = $studentid_in_challan;
            //$data['studentName'] = $studentName;
            //$data['challanNo'] = $challanno;
            //$data['studentId'] = $studentid_in_challan;
            $feeData['action'] = $act;

            $data[]=$feeData;
        } 
        echo json_encode($data);
    }
    else{
     echo json_encode(null);
    }
  }else{
     echo json_encode(null);
  } 
}
/* Add to Cart */
if (isset($_POST['addtocart']) && $_POST['addtocart'] == "add"){
    date_default_timezone_set('Asia/Calcutta');
    $uid = $_SESSION['uid']; 
    $studentId = trim($_POST['studId']);
    $eventname=isset($_POST['eventname'])?trim($_POST['eventname']):"";
    $sfsextraqty=isset($_POST['sfsextraqty'])?trim($_POST['sfsextraqty']):"";
    $otherfeestype=isset($_POST['otherfeestype'])?trim($_POST['otherfeestype']):"";
    $amount=isset($_POST['amount'])?trim($_POST['amount']):"";

    if($studentId && $eventname && $otherfeestype && $uid){
        $arr=explode("_",$eventname);
        //$challan_suffix=$arr[1]."-".$arr[2]."-".$studentId."-".$sfsextraqty;
        $parentData = sqlgetresult('SELECT * FROM getparentdata WHERE "studentId" = \''.$studentId.'\' LIMIT 1',true);

        $sid=$parentData[0]['sid'];
        $feetype=$arr[1];
        $feeconfigid=$arr[2];

        if($otherfeestype==1){
            $run1=sqlgetresult("SELECT * FROM addtocartuniform('$sid','$otherfeestype','$feeconfigid','$feetype','$sfsextraqty','$uid')",true);
            $lastinsert_id = isset($run1[0]['addtocartuniform'])?$run1[0]['addtocartuniform']:"";
        }else if($otherfeestype==4){
            $run1=sqlgetresult("SELECT * FROM addtocartcf('$sid','$otherfeestype','$feeconfigid','$feetype','$sfsextraqty','$uid')",true);
            $lastinsert_id = isset($run1[0]['addtocartcf'])?$run1[0]['addtocartcf']:"";
        }else if($otherfeestype==3){
            //$feeconfigid=11;
            $amount=isset($_POST['amount'])?trim($_POST['amount']):"";
            $run1=sqlgetresult("SELECT * FROM addtocarttransport('$sid','$otherfeestype','$amount','$eventname','$uid')",true);
            $lastinsert_id = isset($run1[0]['addtocarttransport'])?$run1[0]['addtocarttransport']:"";
        }else if($otherfeestype==5){
            $payop = ($_POST['paynfw'])?trim($_POST['paynfw']):"nopartial";
            $cdata=toGetNFWChallanAmount($eventname);
            if($payop =='minimum'){
                $amount=$cdata['m_due'];
            }else{
               $amount=$cdata['n_due'];
            }
            if($amount >0){
                $run1=sqlgetresult("SELECT * FROM addtocartnfwc('$sid','$otherfeestype','$amount','$eventname','$uid','$payop')",true);
                $lastinsert_id = isset($run1[0]['addtocartnfwc'])?$run1[0]['addtocartnfwc']:"";
            }
        }else if($otherfeestype==6){
           $challn="EVENT-".$eventname;
           $data=getNonFeebyid($eventname);
           if($data['feeGroup']){
            $feeconfigid="'".$data['feeGroup']."'";
           }else{
             $feeconfigid=NUll;
           }
           if($amount >0){
            $run1=sqlgetresult("SELECT * FROM addtocartcnf('$sid','$otherfeestype',$feeconfigid,'$eventname','$challn','$amount','$uid')",true);          
           }
           $lastinsert_id = isset($run1[0]['addtocartcnf'])?$run1[0]['addtocartcnf']:"";
        }else{
           $run1=sqlgetresult("SELECT * FROM addtocartlunch('$sid','$otherfeestype','$feeconfigid','$feetype','$uid')",true);
            $lastinsert_id = isset($run1[0]['addtocartlunch'])?$run1[0]['addtocartlunch']:"";
        }
        if($lastinsert_id){
            if($lastinsert_id==1){
               $data='e';
            }else{
                $data=countAddtocart(NULL,$uid);
            }
        }else{
            $data=0;
        }
    }else{
        $data='f';
    }
    echo json_encode($data);
}


if (isset($_POST['btnRemoveAction']) && $_POST['btnRemoveAction'] == "remove"){
    date_default_timezone_set('Asia/Calcutta');
    $uid = $_SESSION['uid'];
    $studentId = trim($_POST['sid']);
    $id = trim($_POST['cid']);
    if($id && $uid){
        $run1=sqlgetresult('UPDATE tbl_addtocart SET deleted=1,"updatedBy"=\''.$uid.'\',"updatedOn"=CURRENT_TIMESTAMP WHERE id=\''.$id.'\' AND parentid=\''.$uid.'\' RETURNING id');
        if($run1['id']){
            //$data=countAddtocart(NULL,$uid);
            $data=array("overall"=>countAddtocart(NULL,$uid),"tnum"=>countAddtocart($studentId,$uid),"totalamount"=>cartCheckoutTotal($studentId,$uid));
        }else{
            $data='e';
        }
    }else{
        $data='f';
    }
    echo json_encode($data);
}

if (isset($_POST['cartchkout']) && $_POST['cartchkout'] == "pay"){

    date_default_timezone_set('Asia/Calcutta');
    $datenow = date("d/m/Y h:m:s");
    $transactionDate = str_replace(" ", "%20", $datenow);
    $uid = $_SESSION['uid']; 
    $studId = trim($_POST['stId']);
    $payment_method = isset($_POST['payment_method'])?trim($_POST['payment_method']):"atom";
    $refnumber=[];
    $ref_chal=[];
    $total=0;
    $uftotal=0;
    $luntotal=0;
    $playfee=0;
    if($studId && $uid){
        $productData = sqlgetresult("SELECT * FROM tbl_accounts WHERE id = '$acc_id' ");
        $cartdata = sqlgetresult('SELECT * FROM cartlistlunuf WHERE "studentId" =\'' . $studId . '\' AND deleted=0 AND status=0',true);
        $num=count($cartdata);
        $productArr = array();  
        function productAmt(&$productArr, $pname, $amount){
            $pname = trim($pname);
            if(isset($productArr[$pname])){
                $productArr[$pname]+=$amount;
            }else{
                $productArr[$pname]=$amount;
            }
            return $productArr; 
        }

        if($num > 0){
          $productmap='<products>';
          $i=1;  
          foreach($cartdata as $data) {

            $id=$data['id'];
            $sid=$data['sid'];
            $oftype=$data['type'];
            $oftypeid=$data['typeid'];
            $feetypeid=$data['feetypeid'];
            $feeconfigid=$data['feeconfigid'];
            $quantity=$data['quantity'];
            $amountper=$data['amount'];
            $cusName = trim($data['userName']);
            $cusEmail = trim($data['email']);
            $cusMobile = $data['mobileNumber']; 
            $cusChallanNo = $data['challanNo'];
            $cusId = $data['parentid'];

            $academicYear = $data['academic_yr'];
            $term = $data['term'];
            $stream = trim($data['stream']);
            $class = trim($data['class']);  
            $section = trim($data['section']);
            $pay_type='Online';
            $payop=trim($data['payoption']);
            if($oftype=="Uniform"){
                $challan_suffix=$feetypeid."-".$feeconfigid."-".$sid."-".$quantity;
                $eventname = "UNIFORM-".$challan_suffix;
                $cusChallanNo=trim($eventname);
                $amount=$amountper*$quantity;
               // $uftotal+=$amount;

                $total+=$amount;
                /*SFS*/
                $pname = $productData['1244172000018485'];
                $productmap .= '<product><id>'.$i.'</id><name>'.trim($pname).'</name><amount>'.$amount.'</amount></product>';
                productAmt($productArr, $pname, $amount); 

                $run1=sqlgetresult("SELECT * FROM createotherfeestransaction('$sid','$uid','$amount','$oftypeid','$feeconfigid','$feetypeid','$quantity','$cusChallanNo','$class','$academicYear','$stream','$term','$section','$pay_type')",true);
                $lastinsert_id = isset($run1[0]['createotherfeestransaction'])?$run1[0]['createotherfeestransaction']:""; 
                if($lastinsert_id){
                    $refnm="UNI-".$lastinsert_id;
                    $refnumber[]=$refnm;
                    $ref_chal[]=$refnm;
                    $ref_id = sqlgetresult('UPDATE tbl_otherfees_payment_log SET "refNum" = \''.$refnm.'\', cartid=\''.$id.'\' WHERE "id" = \''.$lastinsert_id.'\'');
                    $payment_id = sqlgetresult('INSERT INTO tbl_payments ("parentId","studentId","challanNo","transNum","amount") VALUES (\''.$cusId.'\',\''.$studId.'\',\''.$cusChallanNo.'\',\''.$refnm.'\',\''.$amount.'\') RETURNING id');
                }

            }
            if($oftype=="Common"){
                $challan_suffix=$feetypeid."-".$feeconfigid."-".$sid."-".$quantity;
                $eventname = "COMMON-".$challan_suffix;
                $cusChallanNo=trim($eventname);
                $amount=$amountper*$quantity;
               // $uftotal+=$amount;

                $total+=$amount;
                /*UTILITY*/
                $pname = $productData['1244172000004377'];
                $productmap .= '<product><id>'.$i.'</id><name>'.trim($pname).'</name><amount>'.$amount.'</amount></product>'; 

                productAmt($productArr, $pname, $amount);

                $run1=sqlgetresult("SELECT * FROM createotherfeestransaction('$sid','$uid','$amount','$oftypeid','$feeconfigid','$feetypeid','$quantity','$cusChallanNo','$class','$academicYear','$stream','$term','$section','$pay_type')",true);
                $lastinsert_id = isset($run1[0]['createotherfeestransaction'])?$run1[0]['createotherfeestransaction']:""; 
                if($lastinsert_id){
                    //$refnm="COM-".$lastinsert_id;
                    $refnm="COM-".$feetypeid."-".$lastinsert_id;
                    $refnumber[]=$refnm;
                    $ref_chal[]=$refnm;
                    $ref_id = sqlgetresult('UPDATE tbl_otherfees_payment_log SET "refNum" = \''.$refnm.'\', cartid=\''.$id.'\' WHERE "id" = \''.$lastinsert_id.'\'');
                    $payment_id = sqlgetresult('INSERT INTO tbl_payments ("parentId","studentId","challanNo","transNum","amount") VALUES (\''.$cusId.'\',\''.$studId.'\',\''.$cusChallanNo.'\',\''.$refnm.'\',\''.$amount.'\') RETURNING id');
                }

            }
            if($oftype=="Lunch"){
                $challan_suffix=$feetypeid."-".$feeconfigid."-".$sid."-".$quantity;
                $eventname = "LUNCH-".$challan_suffix;
                $cusChallanNo=trim($eventname);
                $amount=$amountper;

                $total+=$amount;
                /*SFS*/
                $pname = $productData['1244172000004377'];
                $productmap .= '<product><id>'.$i.'</id><name>'.trim($pname).'</name><amount>'.$amount.'</amount></product>'; 
                productAmt($productArr, $pname, $amount);

                $run1=sqlgetresult("SELECT * FROM createotherfeestransaction('$sid','$uid','$amount','$oftypeid','$feeconfigid','$feetypeid',NULL,'$cusChallanNo','$class','$academicYear','$stream','$term','$section','$pay_type')",true);
                $lastinsert_id = isset($run1[0]['createotherfeestransaction'])?$run1[0]['createotherfeestransaction']:""; 
                if($lastinsert_id){
                    $refnm="LUN-".$lastinsert_id;
                    $refnumber[]=$refnm;
                    $ref_chal[]=$refnm;
                    $ref_id = sqlgetresult('UPDATE tbl_otherfees_payment_log SET "refNum" = \''.$refnm.'\', cartid=\''.$id.'\' WHERE "id" = \''.$lastinsert_id.'\'');
                    $payment_id = sqlgetresult('INSERT INTO tbl_payments ("parentId","studentId","challanNo","transNum","amount") VALUES (\''.$cusId.'\',\''.$studId.'\',\''.$cusChallanNo.'\',\''.$refnm.'\',\''.$amount.'\') RETURNING id');
                }
            }
            if($oftype=="Transport"){
                $cusChallanNo=trim($cusChallanNo);
                $amount=trim($data['challanamount']);

                $total+=$amount;
                /*SFS*/
                $pname = $productData['1244172000018485'];
                $productmap .= '<product><id>'.$i.'</id><name>'.trim($pname).'</name><amount>'.$amount.'</amount></product>';
                productAmt($productArr, $pname, $amount); 

                $run1=sqlgetresult("SELECT * FROM createotherfeestransaction('$sid','$uid','$amount','$oftypeid',NULL,NULL,NULL,'$cusChallanNo','$class','$academicYear','$stream','$term','$section','$pay_type')",true);
                $lastinsert_id = isset($run1[0]['createotherfeestransaction'])?$run1[0]['createotherfeestransaction']:""; 
                if($lastinsert_id){
                    $refnm="TRA-".$lastinsert_id;
                    $refnumber[]=$refnm;
                    $ref_chal[]=$refnm;
                    $ref_id = sqlgetresult('UPDATE tbl_otherfees_payment_log SET "refNum" = \''.$refnm.'\', cartid=\''.$id.'\' WHERE "id" = \''.$lastinsert_id.'\'');
                    $payment_id = sqlgetresult('INSERT INTO tbl_payments ("parentId","studentId","challanNo","transNum","amount") VALUES (\''.$cusId.'\',\''.$studId.'\',\''.$cusChallanNo.'\',\''.$refnm.'\',\''.$amount.'\') RETURNING id');
                }
            }
            if($oftype=="Non-Fee With Challan"){
                $cusChallanNo=trim($cusChallanNo);
                $cdata=toGetNFWChallanAmount($cusChallanNo);
                if($payop =='minimum'){
                    $amtval=$cdata['m_due'];
                }else{
                    $amtval=$cdata['n_due'];
                }
                $amount=trim($data['challanamount']);
                if($amount==$amtval){
                    $playfee+=$amount;
                    $total+=$amount;
                    /*PlayFees*/
                    $data_ac=toGetNFWCAccountNo($cusChallanNo);
                    if(isset($data_ac['acc_no']) && !empty($data_ac['acc_no'])){
                        $accid=$data_ac['acc_no'];
                    }else{
                        $accid="1244172000114886";
                    }
                    //$pname = $productData['1244172000114886'];
                    $pname = $productData[$accid];
                    $prodtxt = '<product><id>'.$i.'</id><name>'.trim($pname).'</name><amount>'.$amount.'</amount></product>';
                    $productmap.=$prodtxt;
                    productAmt($productArr, $pname, $amount);
                   // echo "SELECT * FROM createnfwcpayment('$sid','$cusChallanNo','$cusId','$prodtxt','$amount','$payop','$payment_method')";
                   // exit;
                    $run1=sqlgetresult("SELECT * FROM createnfwcpayment('$sid','$cusChallanNo','$cusId','$prodtxt','$amount','$payop','$payment_method')",true);
                    $lastinsert_id = isset($run1[0]['createnfwcpayment'])?$run1[0]['createnfwcpayment']:"";
                    if($lastinsert_id){
                        $refnm="NFWC".$lastinsert_id;
                        $refnumber[]=$refnm;
                        $ref_chal[]=$refnm;
                        $prod[]='PlayFee';
                        $ref_id = sqlgetresult('UPDATE tbl_partial_nfwpayment_log SET "transNum"=\''.$refnm.'\', cartid = \''.$id.'\' WHERE id = \''.$lastinsert_id.'\'');
                    }
                }
            }
            if($oftype=="Common Non-Fee"){
                $amount=trim($data['challanamount']);
                $playfee+=$amount;
                $total+=$amount;
                /*Play Fee */
                $data_ac=getNonFeebyid($feetypeid);
                if(isset($data_ac['acc_no']) && !empty($data_ac['acc_no'])){
                    $accid=$data_ac['acc_no'];
                }else{
                    $accid="1244172000114886";
                }
                //$pname = $productData['1244172000114886'];
                $pname = $productData[$accid];
                $productmap .= '<product><id>'.$i.'</id><name>'.trim($pname).'</name><amount>'.$amount.'</amount></product>'; 
                productAmt($productArr, $pname, $amount);
                $payment_id = sqlgetresult('INSERT INTO tbl_nonfee_payments ("studentId","parentId","academicYear","term","stream","classList","section","paymethod","paymode","amount","sid","cartid","feeconfigid","feetypeid") VALUES (\''.$studId.'\',\''.$uid.'\',\''.$academicYear.'\',\''.$term.'\',\''.$stream.'\',\''.$class.'\',\''.$section.'\',\''.$payment_method.'\',\'Online\',\''.$amount.'\',\''.$sid.'\',\''.$id.'\',\''.$feeconfigid.'\',\''.$feetypeid.'\') RETURNING id');
                $lastinsert_id=isset($payment_id['id'])?$payment_id['id']:"";
                if($lastinsert_id){
                    $refnm="CNF-".$lastinsert_id;
                    $refnumber[]=$refnm;
                    $ref_chal[]=$cusChallanNo;
                    $prod[]='PlayFee';
                    $ref_id = sqlgetresult('UPDATE tbl_nonfee_payments SET "transNum" = \''.$refnm.'\' WHERE "id" = \''.$lastinsert_id.'\'');
                }
            }
            $i++;
          }
          $productmap.='</products>';
            /* Recently Added To avoid dulicate */
            $productmapnew='<products>';
            $k=1;  
            foreach ($productArr as $name => $amt) {
              $productmapnew .= '<product><id>'.$k.'</id><name>'.$name.'</name><amount>'.$amt.'</amount></product>';
              $k++; 
            }
            $productmapnew.='</products>';


          if(count($refnumber) > 0 && $total >0){
             $refids=implode(",",$refnumber);
             $ref_chal_ids=implode(",",$ref_chal);
             $ref_chal_ids=substr($ref_chal_ids,0,500);
             $run2=sqlgetresult("SELECT * FROM createcartfeetransaction('$sid','$uid','$total','$total','$refids','$productmapnew',NULL,'$class','$academicYear','$stream','$term','$section','$pay_type')",true);
             $cart_lastinsert_id = isset($run2[0]['createcartfeetransaction'])?$run2[0]['createcartfeetransaction']:"";

             if($cart_lastinsert_id){
                $ref="CART-".$cart_lastinsert_id;
                $encoded_data = base64_encode($studId."_".$_SESSION['uid']."_".$cart_lastinsert_id);
                $returnonfeeURL = BASEURL.'parse_payment.php?cartpayments='.$encoded_data;
                $clientcode = $studId . '|' . trim(getAcademicyrById($academicYear));
                /******* Fee Pay Configuration ***/
                $transactionId = trim($ref);
                require_once 'atompay/TransactionRequest.php';
                $transactionRequest = new TransactionRequest();   

                $transactionRequest->setMode($paymode);
                $transactionRequest->setLogin($login);
                $transactionRequest->setPassword($pass);    
                $transactionRequest->setProductId($proId);
                $transactionRequest->setAmount($total);
                $transactionRequest->setTransactionCurrency($currency);
                $transactionRequest->setTransactionAmount($total);
                $transactionRequest->setReturnUrl($returnonfeeURL);
                $transactionRequest->setClientCode($clientcode);
                $transactionRequest->setTransactionId($transactionId);
                $transactionRequest->setTransactionDate($transactionDate);
                $transactionRequest->setCustomerName($cusName);
                $transactionRequest->setCustomerEmailId($cusEmail);
                $transactionRequest->setCustomerChallanNo($ref_chal_ids);
                $transactionRequest->setCustomerMobile($cusMobile);
                $transactionRequest->setCustomerBillingAddress("Chennai");
                $transactionRequest->setProducts($productmapnew);
                $transactionRequest->setCustomerAccount("639827");
                $transactionRequest->setReqHashKey($ReqHashKey);

                $url  = $transactionRequest->getPGUrl();
                $payment_id = sqlgetresult('UPDATE tbl_cart_payment_log SET payment_url = \''.$url.'\',"transNum"=\''.$transactionId.'\', paymentmethod = \''.$payment_method.'\' WHERE "id" = \''.$cart_lastinsert_id.'\'') ;
                $_SESSION['cartpayment_id'] = $cart_lastinsert_id;
                header("Location: ".$url);
                exit;
            }else{
               $_SESSION['error_msg']="<p class='error-msg'> Some Error has occured. Try again later.</p>";
            } 
          }
          else{
            $_SESSION['error_msg']="<p class='error-msg'> Some Error has occured. Try again later.</p>";
          }
        }else{
            $_SESSION['error_msg']="<p class='error-msg'> No Data Found.</p>";
        }
    }else{
        $_SESSION['error_msg']="<p class='error-msg'> Mandatory fields are Missing.</p>";
    }
    header("Location: cartcheckout.php");
    exit;
}
if (isset($_POST['qtyUpdateAction']) && $_POST['qtyUpdateAction'] == "updateuniformQty"){
    date_default_timezone_set('Asia/Calcutta');
    $uid = $_SESSION['uid'];
    $studentId = trim($_POST['sid']);
    $id = trim($_POST['cid']);
    $quantity = trim($_POST['quantity']);
    if($id && $uid && $quantity){
        $run1=sqlgetresult('UPDATE tbl_addtocart SET quantity=\''.$quantity.'\',"updatedBy"=\''.$uid.'\',"updatedOn"=CURRENT_TIMESTAMP WHERE id=\''.$id.'\' AND parentid=\''.$uid.'\' RETURNING id');
        if($run1['id']){
            //$data=countAddtocart(NULL,$uid);
            $data=array("overall"=>countAddtocart(NULL,$uid),"tnum"=>countAddtocart($studentId,$uid),"totalamount"=>cartCheckoutTotal($studentId,$uid));
        }else{
            $data='e';
        }
    }else{
        $data='f';
    }
    echo json_encode($data);
}

/********Common Fee Start******/

if( isset($_POST['submit']) && $_POST['submit'] == 'geteventnames_commfee' ) {
    $stdid = trim($_POST['data']);
    $params=[];
    $params['studentId']=$stdid;
    $notpaid=toGetChallanNotPaidCount($params);
    if($notpaid > 0){
        $eventnames=2;
        echo json_encode($eventnames);
        exit;
    }
    $studentdetails = sqlgetresult('SELECT * FROM tbl_student WHERE "studentId" = \''.$stdid.'\'');
    $query='SELECT f."feeType" , c.amount , f.id, c.id as feeconfigid, f.maxquantity FROM tbl_fee_type f LEFT JOIN tbl_fee_configuration c ON f.id = c."feeType"::integer WHERE c.stream=\'' . trim($studentdetails['stream']) . '\' AND c.class=\'' . trim($studentdetails['class']) . '\' AND c.semester = \'' . trim($studentdetails['term']) . '\' AND c."academicYear"= \'' . trim($studentdetails['academic_yr']) . '\' AND f."status" = \'1\' AND f."deleted" = \'0\' AND f."applicable" ILIKE \'%C%\'';
    $eventnames = sqlgetresult($query,true);

    $eventid = array();
    
    $eventsnamesaftercheck = array();

    foreach($eventnames AS $events){
        $ids=$events['amount']."_".$events['id']."_".$events['feeconfigid']."_".$events['maxquantity'];
        $eventsnamesaftercheck[$ids] = trim($events['feeType']);
    }

    if($eventsnamesaftercheck != ''){
        $eventnames = $eventsnamesaftercheck;
    }
    else{
      $eventnames = 0;
    }
    echo json_encode($eventnames);
}


/* Non Fee With Challan Partial Payment */
if (isset($_POST['paynonfeechallan']) && $_POST['paynonfeechallan'] == "confirmpp"){ 
    //print_r($_POST);
    $studentId = ($_POST['studId'])?trim($_POST['studId']):"";
    $amount = ($_POST['tot'])?trim($_POST['tot']):0; 
    $challanNo = ($_POST['challanNo'])?trim($_POST['challanNo']):"";
    $payop = ($_POST['paynfw'])?trim($_POST['paynfw']):"nopartial";
    $uid=$_SESSION['uid'];
    $errflds=[];
    if($studentId && $amount && $challanNo && $uid){
        $cdata=toGetNFWChallanAmount($challanNo);
        if($payop =='minimum'){
            $amtval=$cdata['m_due'];
        }else{
           $amtval=$cdata['n_due'];
        }
       // echo $amtval;
       if($amount==$amtval){
        $parentData = sqlgetresult('SELECT * FROM getparentdata WHERE "studentId" = \''.$studentId.'\' LIMIT 1',true);
        $cusName = trim($parentData[0]['userName']);
        $cusEmail = trim($parentData[0]['email']);
        $cusMobile = trim($parentData[0]['mobileNumber']); 
        $cusId = $parentData[0]['id'];
        $academicYear = trim($parentData[0]['academic_yr']);
        $term = trim($parentData[0]['term']);
        $stream = trim($parentData[0]['stream']); 
        $s_id = $parentData[0]['sid'];
        $class = trim($parentData[0]['class']);
        $section = trim($parentData[0]['section']);  

        //$productData = sqlgetresult("SELECT * FROM tbl_accounts WHERE id = '$acc_id' ");  
        //$pname = $productData['1244172000114886'];
        //$product = ('<products><product><id>1</id><name>'.trim($pname).'</name><amount>'.$amount.'</amount></product></products>'); 
        $pname = getProductByFeeGroup('NON-FEE');
        $product = ('<products><product><id>1</id><name>'.trim($pname).'</name><amount>'.$amount.'</amount></product></products>');
        /*Atom */
        $paymethod=1;
        //echo "SELECT * FROM createnfwcpayment('$s_id','$challanNo','$uid','$product','$amount','$payop','$paymethod')";
        //exit;
        $run1=sqlgetresult("SELECT * FROM createnfwcpayment('$s_id','$challanNo','$cusId','$product','$amount','$payop','$paymethod')",true);
        $lastinsert_id = isset($run1[0]['createnfwcpayment'])?$run1[0]['createnfwcpayment']:""; 
        //exit;
        if(!empty($lastinsert_id)){
            $eventname="NFWC".$lastinsert_id;
            $_SESSION['PSNFCData'] = $parentData[0];
            $parentData[0]['challanNo'] = $challanNo;
            date_default_timezone_set('Asia/Calcutta');
            $datenow = date("d/m/Y h:m:s");
            $transactionDate = str_replace(" ", "%20", $datenow);
            $transactionId = $eventname;
            $cusChallanNo = $eventname;
           // $cust=$challanNo;

            $clientcode = $studentId . '|' . trim(getAcademicyrById($academicYear));

            $encoded_data = base64_encode($lastinsert_id."_".$cusId."_".$challanNo."_".$studentId);  
            $returnstudscr = BASEURL.'parse_payment.php?ppnfwc='.$encoded_data;
            require_once 'atompay/TransactionRequest.php';
            $transactionRequest = new TransactionRequest();  

            //Setting all values here
            $transactionRequest->setMode($paymode);
            $transactionRequest->setLogin($login);
            $transactionRequest->setPassword($pass);    
            $transactionRequest->setProductId($proId);
            $transactionRequest->setAmount($amount);
            $transactionRequest->setTransactionCurrency($currency);
            $transactionRequest->setTransactionAmount($amount);
            $transactionRequest->setReturnUrl($returnstudscr);
            $transactionRequest->setClientCode($clientcode);
            $transactionRequest->setTransactionId($transactionId);
            $transactionRequest->setTransactionDate($transactionDate);
            $transactionRequest->setCustomerName($cusName);
            $transactionRequest->setCustomerEmailId($cusEmail);
            $transactionRequest->setCustomerChallanNo($challanNo);
            $transactionRequest->setCustomerMobile($cusMobile);
            $transactionRequest->setCustomerBillingAddress("Chennai");
            $transactionRequest->setProducts($product);
            $transactionRequest->setCustomerAccount("639827");
            $transactionRequest->setReqHashKey($ReqHashKey);

            $url  = $transactionRequest->getPGUrl();
            sqlgetresult('UPDATE tbl_partial_nfwpayment_log SET "transNum"=\''.$transactionId.'\',payment_url=\''.$url.'\' WHERE id=\''.$lastinsert_id.'\'');
            header("Location: ".$url);
            exit;

        }else{
            $_SESSION['error_msg'] = "<p class='error-msg'> Some Error has occured. Try again later.</p>";
            $errordata = 'Partial Amount of Non-FW Challans:</br>studentId:' . $studentId . '</br>amount:' . $amount;
            createErrorlog($errordata);
        }
       }else{
           $_SESSION['error_msg'] = "<p class='error-msg'> Amount should be matched with the challan value.</p>";
       } 
    }else{
      $_SESSION['error_msg'] = "<p class='error-msg'> Mandatory fields are missing! Try again later.</p>";
    }
   header("Location: nonfeepayments.php");
   exit;
}
?>
