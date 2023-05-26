<?php
/*****Login - Start*****/
require_once ('../config.php');
if (isset($_POST["login"]) && $_POST["login"] == "signin")
{
    $emd = $_POST['email'];
    $pwd = $_POST['password'];
    if (isset($_POST['tec_login']) && $_POST['tec_login'] == 'on')
    {
        $sql = "SELECT * FROM teacherchk WHERE email = '$emd'";
        $res = sqlgetresult($sql);
        $res['adminPassword'] = $res['password'];
        $_SESSION['sessLoginType'] = 'Teacher';
        $_SESSION['class'] = $res['class'];
        $_SESSION['email'] = $res['email'];
        $_SESSION['section'] = $res['section'];
        $_SESSION['stream'] = $res['stream'];
    }
    else
    {
        $sql = 'SELECT * from adminchk WHERE "adminEmail"=\'' . $emd . '\'';
        $res = sqlgetresult($sql);
        $_SESSION['sessLoginType'] = 'Admin';
    }

    if (count($res) > 0)
    {
        $check_pass = 0;
        if (password_verify(trim($pwd) , trim($res['adminPassword'])))
        {
            $check_pass = 1;
        }
        // print_r($check_pass);exit;
        if ($check_pass == 0)
        {
            $_SESSION['error'] = "<p class='error-msg'>EmailId and Password don't match</p>";
            header("location:login.php");
        }
        else
        {
            // echo("hi");
            // exit;
            $admin = array(
                "adminid" => $res['id'],
                "adminemail" => $emd,
                "created" => $res['createdOn'],
                "adminstatus" => $res['status'],
                "adminrole" => $res['adminRole']
            );
            // echo($admin);
            // exit;
            $_SESSION['myadmin'] = $admin;
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
            header("location:home.php");
        }
    }
    else
    {
        createErrorlog($res);
        $_SESSION['error'] = "<p class='error-msg'>Invalid EmailId</p>";
        header("location:login.php");
    }
}
/*****Login - End*****/

/*****Common Status Change Function - Start*****/
if (isset($_GET['status']) && $_GET['page'] != '')
{
    if ($_GET['page'] == 't')
    {
        $tbl = 'tbl_teachers';
        $page = 'manageteachers.php';
    }
    elseif ($_GET['page'] == 'p')
    {
        $tbl = 'tbl_parents';
        $page = 'managepar.php';
    }
    elseif ($_GET['page'] == 's')
    {
        $tbl = 'tbl_student';
        $page = 'managestd.php';
    }
    elseif ($_GET['page'] == 'a')
    {
        $tbl = 'tbl_admin';
        $page = 'mainpage.php';
    }
    elseif ($_GET['page'] == 'c')
    {
        $tbl = 'tbl_class';
        $page = 'manageclass.php';
    }
    elseif ($_GET['page'] == 'st')
    {
        $tbl = 'tbl_stream';
        $page = 'managestream.php';
    }
    elseif ($_GET['page'] == 'l')
    {
        $tbl = 'tbl_late_fee';
        $page = 'managelatefee.php';
    }
    elseif ($_GET['page'] == 'ta')
    {
        $tbl = 'tbl_tax';
        $page = 'managetax.php';
    }
    elseif ($_GET['page'] == 'f')
    {
        $tbl = 'tbl_fee_type';
        $page = 'managefeetype.php';
    }
    elseif ($_GET['page'] == 'com')
    {
        $tbl = 'tbl_comments';
        $page = 'managecomments.php';
    }
    elseif ($_GET['page'] == 'tr')
    {
        $tbl = 'tbl_transport';
        $page = 'managetransport.php';
    }
    elseif ($_GET['page'] == 'ye')
    {
        $tbl = 'tbl_academic_year';
        $page = 'manageyear.php';
    }
    elseif ($_GET['page'] == 'fg')
    {
        $tbl = 'tbl_fee_group';
        $page = 'managefeegroup.php';
    }
    elseif ($_GET['page'] == 'nf')
    {
        $tbl = 'tbl_nonfee_type';
        $page = 'nonfeetype.php';
    }
    elseif ($_GET['page'] == 'product') {
        $tbl = 'tbl_products';
        $page = 'manageproducts.php';
    } elseif ($_GET['page'] == 'semester') {
        $tbl = 'tbl_semester';
        $page = 'manageterms.php';        
    }
    elseif ($_GET['page'] == 'challan') {
        $tbl = 'tbl_challans';
        $page = 'managecreatedchallans.php';        
    }
    elseif ($_GET['page'] == 'wt') {
        $tbl = 'tbl_waivertypes';
        $page = 'managewaivertype.php';        
    }
    elseif ($_GET['page'] == 'rol') {
        $tbl = 'tbl_adminroles';
        $page = 'manageroles.php';        
    }
    elseif ($_GET['page'] == 'bank') {
        $tbl = 'tbl_banklist';
        $page = 'managebank.php';        
    }
    elseif ($_GET['page'] == 'menu') {
        $tbl = 'tbl_admin_submenu';
        $page = 'managemenulist.php';        
    }
    elseif ($_GET['page'] == 'partial') {
        $tbl = 'tbl_partial_payment';
        $page = 'managepartial.php';        
    }
    elseif ($_GET['page'] == 'ctrlfunction') {
        $tbl = 'tbl_ctrl_bkfunctions';
        $page = 'managefunc.php';        
    }
    if ($_GET["status"] == "ACTIVE")
    {
        $status = 0;
    }
    else
    {
        $status = 1;
    }

    $id = $_REQUEST['id'];
    $uid = $_SESSION['myadmin']['adminid'];
    if ($_GET['page'] == 'semester') {
        $update = sqlgetresult("UPDATE $tbl SET status = '0'");
        $query = "UPDATE $tbl SET status = '$status' WHERE id = $id returning 1 as statusupdate";
    }else if ($_GET['page'] == 'challan') {
        
        $query1 = 'UPDATE tbl_demand SET "visibleStatus" = '.$status.' WHERE "challanNo"=\''.$id.'\'';
        sqlgetresult($query1);
        $query = 'UPDATE tbl_challans SET "visibleStatus" = '.$status.' WHERE "challanNo"=\''.$id.'\' returning 1 as statusupdate';
        //exit;
    } else {
        $query = "SELECT * FROM statusupdate('$tbl','$status','$uid','$id')";
    }
    $res = sqlgetresult($query);
    // echo $query;exit;
    //if ($res["statusupdate"] == 1)
    if (count($res) > 0)
    {
        $_SESSION['success'] = "<p class='success-msg'>Change Status Was Done Successfully</p>";
        header("Location:" . $page);
    }
    else
    {
        createErrorlog($res);
        $_SESSION['failure'] = "<p class='error-msg'>Change Status Was Not Done</p>";
        header("Location:" . $page);
    }
}
/*****Common Status Change Function - End*****/

/*****Common Delete Function - Start*****/
if (isset($_GET['action']) && $_GET['page'] != '')
{
    if ($_GET['page'] == 't')
    {
        $tbl = 'tbl_teachers';
        $page = 'manageteachers.php';
    }
    elseif ($_GET['page'] == 'p')
    {
        $tbl = 'tbl_parents';
        $page = 'managepar.php';
    }
    elseif ($_GET['page'] == 's')
    {
        $tbl = 'tbl_student';
        $page = 'managestd.php';
    }
    elseif ($_GET['page'] == 'a')
    {
        $tbl = 'tbl_admin';
        $page = 'mainpage.php';
    }
    elseif ($_GET['page'] == 'c')
    {
        $tbl = 'tbl_class';
        $page = 'manageclass.php';
    }
    elseif ($_GET['page'] == 'st')
    {
        $tbl = 'tbl_stream';
        $page = 'managestream.php';
    }
    elseif ($_GET['page'] == 'l')
    {
        $tbl = 'tbl_late_fee';
        $page = 'managelatefee.php';
    }
    elseif ($_GET['page'] == 'ta')
    {
        $tbl = 'tbl_tax';
        $page = 'managetax.php';
    }
    elseif ($_GET['page'] == 'f')
    {
        $tbl = 'tbl_fee_type';
        $page = 'managefeetype.php';
    }
    elseif ($_GET['page'] == 'com')
    {
        $tbl = 'tbl_comments';
        $page = 'managecomments.php';
    }
    elseif ($_GET['page'] == 'feeconfig')
    {
        $tbl = 'tbl_fee_configuration';
        $page = 'feeconfiguration.php';
    }
    elseif ($_GET['page'] == 'tr')
    {
        $tbl = 'tbl_transport';
        $page = 'managetransport.php';
    }
    elseif ($_GET['page'] == 'ye')
    {
        $tbl = 'tbl_academic_year';
        $page = 'manageyear.php';
    }
    elseif ($_GET['page'] == 'fg')
    {
        $tbl = 'tbl_fee_group';
        $page = 'managefeegroup.php';
    }
    elseif ($_GET['page'] == 'nf')
    {
        $tbl = 'tbl_nonfee_type';
        $page = 'nonfeetype.php';
    } elseif ($_GET['page'] == 'nfc') {
        $tbl = 'tbl_nonfee_challans';
        $page = 'createnonfeechallans.php';
    } elseif ($_GET['page'] == 'product') {
        $tbl = 'tbl_products';
        $page = 'manageproducts.php';
    }
    elseif ($_GET['page'] == 'wt') {
        $tbl = 'tbl_waivertypes';
        $page = 'managewaivertype.php';        
    }
    elseif ($_GET['page'] == 'rol') {
        $tbl = 'tbl_adminroles';
        $page = 'manageroles.php';        
    }
    elseif ($_GET['page'] == 'bank') {
        $tbl = 'tbl_banklist';
        $page = 'managebank.php';        
    }
    elseif ($_GET['page'] == 'menu') {
        $tbl = 'tbl_admin_submenu';
        $page = 'managemenulist.php';        
    }
     elseif ($_GET['page'] == 'partial') {
        $tbl = 'tbl_partial_payment';
        $page = 'managepartial.php';        
    }

    $id = $_REQUEST['id'];
    $uid = $_SESSION['myadmin']['adminid'];
    $query = "SELECT * FROM deleteupdate('$tbl','$uid','$id')";
    $res = sqlgetresult($query);

    if ($res['deleteupdate'] == 1)
    {
        $_SESSION['success'] = "<p class='success-msg'>Deleted Successfully</p>";
        header("Location:" . $page);
    }
    else
    {
        createErrorlog($res);
        $_SESSION['failure'] = "<p class='error-msg'>Deleted Unsuccessful</p>";
        header("Location:" . $page);
    }
}
/*****Common Delete Function - End*****/

/*****Admin Table - Start*****/
if (isset($_POST["edit"]) && $_POST["edit"] == "update")
{
    $id = $_POST['id'];
    $uid = $_SESSION['myadmin']['adminid'];
    $adminname = $_POST['admname'];
    $adminemail = $_POST['admmail'];
    $role = isset($_POST['role'])?$_POST['role']:1;
    $var = 0;
    if ($_POST['password'] == "")
    {
        $adminpass = $_POST['hiddenpass'];
    }
    else
    {
        $adminpass = password_hash($_POST['password_confirmation'], PASSWORD_DEFAULT);
        $password = $_POST['password_confirmation'];
        $data = 'Hi,<br/>&nbsp;&nbsp;&nbsp;&nbsp;Please find the Edited Admin login credentials below,</br>';
        $data .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;UserName : ' . $adminemail;
        $data .= '<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Password : ' . $password;
        $var = 1;

    }
    // $adminpass = password_hash($_POST['password_confirmation'],PASSWORD_DEFAULT);
    //$query = "SELECT * FROM editadm('$id','$adminname','$adminemail','$adminpass','$uid')";
    $query = "SELECT * FROM editadmnew('$id','$adminname','$adminemail','$adminpass','$uid','$role')";
    $run = sqlgetresult($query);

    if ($run['editadmnew'] == 1)
    {
        $_SESSION['successadm'] = "<p class='success-msg'>Data Edited Successfully.</p>";
        if($var == 1){
        SendMailId($adminemail, 'Edited Admin Login', $data);
        }
        header('location:mainpage.php');
    }
    else if ($run['editadmnew'] == 0)
    {
        $_SESSION['erroradm'] = "<p class='error-msg'>User Already Exist</p>";
        header('location:mainpage.php');
    }
    else
    {
        createErrorlog($run);
        $_SESSION['erroradm'] = "<p class='error-msg'>Some error has been occured. Please try again.</p>";
        header('location:mainpage.php');
    }
}

if (isset($_POST["add"]) && $_POST["add"] == "new")
{
    $n = 0;
    $id = $_SESSION['myadmin']['adminid'];
    $adminname = $_POST['admname'];
    $adminemail = $_POST['admmail'];
    $pass = $_POST['password_confirmation'];
    $role = isset($_POST['role'])?$_POST['role']:1;
    $adminpass = password_hash($_POST['password_confirmation'], PASSWORD_DEFAULT);
    //$query = "SELECT * FROM addadm('$id','$adminname','$adminemail','$adminpass')";
    $query = "SELECT * FROM addadmnew('$id','$adminname','$adminemail','$adminpass','$role')";
    $run = sqlgetresult($query);

    if ($run['addadmnew'] == 1)
    {
        $_SESSION['successadm'] = "<p class='success-msg'>Data Added Successfully.</p>";
        $data = 'Hi,<br/>&nbsp;&nbsp;&nbsp;&nbsp;Please find the Admin login credentials below,</br>';
        $data .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;UserName : ' . $adminemail;
        $data .= '<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Password : ' . $pass;
        SendMailId($adminemail, 'New Admin Login', $data);
        header('location:mainpage.php');
    }
    else if ($run['addadmnew'] == 0)
    {
        $_SESSION['erroradm'] = "<p class='error-msg'>User Already Exist</p>";
        header('location:mainpage.php');
    }
    else
    {
        createErrorlog($run);
        $_SESSION['erroradm'] = "<p class='error-msg'>Some error has been occured. Please try again.</p>";
        header('location:mainpage.php');
    }
}
/*****Admin Table - End*****/

/*****Parent Table - Start*****/
if (isset($_POST["editpar"]) && $_POST["editpar"] == "update")
{
    $var = 0;
    $id = $_POST['id'];
    $uid = $_SESSION['myadmin']['adminid'];
    $firstname = $_POST['fname'];
    $lastname = $_POST['lname'];
    $username = $firstname . " " . $lastname;
    $email = $_POST['email'];
    $secondaryemail = $_POST['secondaryemail'];
    if ($_POST['password'] == "")
    {
        $pass = $_POST['hiddenpass'];
    }
    else
    {
        $password = $_POST['password_confirmation'];
        $pass = password_hash($_POST['password_confirmation'], PASSWORD_DEFAULT);
        $data = 'Hi,<br/>&nbsp;&nbsp;&nbsp;&nbsp;Please find the Edited Parent login credentials below,</br>';
        $data .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;UserName : ' . $email;
        $data .= '<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Password : ' . $password;
        $var = 1;

    }
    // $pass = password_hash($_POST['password'],PASSWORD_DEFAULT);
    $pnumber = $_POST['pnum'];
    $mnumber = $_POST['mnum'];
    $query = "SELECT * FROM editpar('$id','$firstname','$lastname','$username','$email','$secondaryemail','$pass','$pnumber','$mnumber','$uid')";
    $run = sqlgetresult($query);
    if ($run['editpar'] == 1)
    {

        $_SESSION['successpar'] = "<p class='success-msg'>Data Edited Successfully.</p>";
        if($var == 1){
        SendMailId($email, 'Edited Parent Login', $data);
        }
        header('location:managepar.php');
    }
    elseif ($run['editpar'] == 0)
    {
        $_SESSION['errorpar'] = "<p class='error-msg'>User Already Exists</p>";
        header('location:managepar.php');
    }
    else
    {
        createErrorlog($run);
        $_SESSION['errorpar'] = "<p class='error-msg'>Some error has been occured. Please try again.</p>";
        header('location:managepar.php');
    }
}
if (isset($_POST["addpar"]) && $_POST["addpar"] == "new")
{
    $n = 0;
    // $id=$_POST['id'];
    $uid = $_SESSION['myadmin']['adminid'];
    $firstname = $_POST['fname'];
    $lastname = $_POST['lname'];
    $username = $firstname . " " . $lastname;
    $email = $_POST['email'];
    $secondaryemail = $_POST['secondaryemail'];
    $password = $_POST['password_confirmation'];
    $pass = password_hash($_POST['password_confirmation'], PASSWORD_DEFAULT);
    $pnumber = $_POST['pnum'];
    $mnumber = $_POST['mnum'];
    $query = "SELECT * FROM addpar('$firstname','$lastname','$username','$email','$secondaryemail','$pass','$pnumber','$mnumber','$uid')";
    $run = sqlgetresult($query);
    // print_r($query);
    // exit;
    if ($run['addpar'] == 1)
    {

        $_SESSION['successpar'] = "<p class='success-msg'>Data Added Successfully.</p>";
        $data = 'Hi,<br/>&nbsp;&nbsp;&nbsp;&nbsp;Please find the Parent login credentials below,</br>';
        $data .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;UserName : ' . $email;
        $data .= '<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Password : ' . $password;
        SendMailId($email, 'New Parent Login', $data);
        header('location:managepar.php');
    }
    else if ($run['addpar'] == 0)
    {
        $_SESSION['errorpar'] = "<p class='error-msg'>User Already Exist</p>";
        header('location:managepar.php');
    }
    else
    {
        createErrorlog($run);
        $_SESSION['errorpar'] = "<p class='error-msg'>Some error has been occured. Please try again.</p>";
        header('location:managepar.php');
    }
}
/*****Parent Table - End*****/

/*****Student Table - Start*****/
if (isset($_POST["editstd"]) && $_POST["editstd"] == "update")
{
    $_POST = array_map('trim',$_POST);
    $n = 0;
    $id = $_POST['id'];
    $studentId = $_POST['sid'];
    $sid_current = $_POST['sid_current'];
    $uid = $_SESSION['myadmin']['adminid'];
    $studentName = $_POST['name'];
    $stream = $_POST['stream'];
    $class = $_POST['class'];
    $section = $_POST['section'];
    $term = $_POST['term'];
    $hostel = $_POST['hostelneed'];
    $lunch = $_POST['lunchneed'];
    $email = $_POST['mail'];
    $mnum = $_POST['mobile'];
    $year = $_POST['acadyear'];
    $gender = $_POST['gender'];
    $sid_old = isset($_POST['sid_old'])?$_POST['sid_old']:"";

    if ($_POST['pid'] == '')
    {
        $parentId = $_POST['oldpid'] == '' ? '0' : $_POST['oldpid'];

    }
    else
    {
        $parentId = $_POST['pid'];
    }

   if ($_POST['transtg'] == '')
    {
        $transportstage = $_POST['oldstg'] == '' ? '0' : $_POST['oldstg'];

    }
    else
    {
        $transportstage = $_POST['transtg'];
    }
    if($transportstage != 0){

        $transportneed = 'Y';
    }
    else{

        $transportneed = 'N';
    }

    // $myString = 'Hello, there!';

    // if ( strstr( $studentId, 'APPL' ) ) {
    //     $applicantid = $studentId;
    // }
    // else{
    //     $applicantid = '';
    // }
    
    if($sid_current!=$studentId){
        if(!empty($sid_old)){
           $oldId=$sid_old.",".$sid_current;
        }else{
            $oldId=$sid_current;
        }
    }

    $studID_old=isset($oldId)?$oldId:$sid_old;


    $query = "SELECT * FROM editstd('$studentId','$studentName','$stream','$class','$section','$term','$parentId','$email','$mnum','$transportstage','$transportneed','$hostel','$lunch','$year','$uid','$id','$gender','$studID_old')";
    $run = sqlgetresult($query);
    // print_r($query);
    // print_r($run);
    // exit;
    if ($run['editstd'] == 1)
    {

        $_SESSION['successstd'] = "<p class='success-msg'>Data Edited Successfully.</p>";
        header('location:managestd.php');
    }
    else if ($run['editstd'] == 0)
    {
        $_SESSION['errorstd'] = "<p class='error-msg'>Student Id Already Exist</p>";
        header('location:managestd.php');
    }
    else
    {
        createErrorlog($run);
        $_SESSION['errorstd'] = "<p class='error-msg'>Some error has been occured. Please try again.</p>";
        header('location:managestd.php');
    }
}
if (isset($_POST["addstd"]) && $_POST["addstd"] == "new")
{
    $_POST = array_map('trim',$_POST);
    $n = 0;
    $studentId = $_POST['sid'];
    $uid = $_SESSION['myadmin']['adminid'];
    $studentName = $_POST['name'];
    $stream = $_POST['stream'];
    $class = $_POST['class'];
    $section = $_POST['section'];
    $term = $_POST['term'];
    $hostel = $_POST['hostelneed'];
    $lunch = $_POST['lunchneed'];
    $email = $_POST['mail'];
    $mnum = $_POST['mobile'];
    $year = $_POST['acadyear'];
    $gender = $_POST['gender'];

    if ($_POST['pid'] == '')
    {
        $parentId = $_POST['oldpid'];
    }
    else
    {
        $parentId = $_POST['pid'];
    }

    if ($_POST['transtg'] == '')
    {
        $transportstage = 0;
    }
    else
    {
        $transportstage = $_POST['transtg'];
    }

    if($transportstage != 0){

        $transportneed = 'Y';
    }
    else{

        $transportneed = 'N';
    }
    // $newstr = strstr($str, 'APPL');
    if ( stristr( $studentId, 'APPL' ) ) {
        $applicantid = $studentId;
    }
    else{
        $applicantid = '';
    }
    // print_r($applicantid);  

    $query = "SELECT * FROM addstd('$studentId','$studentName','$stream','$class','$section','$term','$parentId','$email','$mnum','$transportstage','$transportneed','$hostel','$lunch','$year','$uid','$applicantid','$gender')";
    // print_r($query);
    // exit;
    $run = sqlgetresult($query);

    if ($run['addstd'] == 1)
    {
        $_SESSION['successstd'] = "<p class='success-msg'>Data Added Successfully.</p>";
        header('location:managestd.php');
    }
    else if ($run['addstd'] == 0)
    {
        $_SESSION['errorstd'] = "<p class='error-msg'>Student Id Already Exist</p>";
        header('location:managestd.php');
    }
    else
    {
        createErrorlog($run);
        $_SESSION['errorstd'] = "<p class='error-msg'>Some error has been occured. Please try again</p>";
        header('location:managestd.php');
    }
}
/*****Student Table - End*****/

/*****Class Table - Start*****/
if (isset($_POST["editclass"]) && $_POST["editclass"] == "update")
{
    $id = $_POST['id'];
    $uid = $_SESSION['myadmin']['adminid'];
    $cname = $_POST['cname'];
    $des = $_POST['des'];
    $stream = $_POST['stream'];
    $query = "SELECT * FROM editclass('$id','$cname','$des','$stream','$uid')";
    $run = sqlgetresult($query);
    // print_r($run);
    // exit;
    if ($run['editclass'] == 1)
    {
        $_SESSION['successclass'] = "<p class='success-msg'>Data Edited Successfully.</p>";
        header('location:manageclass.php');
    }
    else if ($run['editclass'] == 0)
    {
        $_SESSION['errorclass'] = "<p class='error-msg'>Class Already Exist</p>";
        header('location:manageclass.php');
    }
    else
    {
        createErrorlog($run);
        $_SESSION['errorclass'] = "<p class='error-msg'>Some error has been occured. Please try again.</p>";
        header('location:manageclass.php');
    }
}

if (isset($_POST["addclass"]) && $_POST["addclass"] == "new")
{
    // $n = 0;
    // $id=$_POST['id'];
    $uid = $_SESSION['myadmin']['adminid'];
    $cname = $_POST['cname'];
    $des = $_POST['des'];
    $stream = $_POST['stream'];
    $query = "SELECT * FROM addclass('$cname','$des','$stream','$uid')";
    $run = sqlgetresult($query);
    // print_r($query);
    // exit;
    if ($run['addclass'] == 1)
    {
        $_SESSION['successclass'] = "<p class='success-msg'>Data Added Successfully.</p>";
        header('location:manageclass.php');
    }
    else if ($run['addclass'] == 0)
    {
        $_SESSION['errorclass'] = "<p class='error-msg'>Class Already Exist.</p>";
        header('location:manageclass.php');
    }
    else
    {
        createErrorlog($run);
        $_SESSION['errorclass'] = "<p class='error-msg'>Some error has been occured. Please try again.</p>";
        header('location:manageclass.php');
    }
}
/*****Class Table - End*****/
/*****Class Table - Start*****/
if (isset($_POST["editfeegroup"]) && $_POST["editfeegroup"] == "update")
{
    $id = $_POST['id'];
    $uid = $_SESSION['myadmin']['adminid'];
    $fname = $_POST['fname'];
    $des = $_POST['fdes'];
    $product = $_POST['product'];
    $query = "SELECT * FROM editfeegroup('$id','$fname','$des','$uid','$product')";
    // print_r($query);exit;
    $run = sqlgetresult($query);
    if ($run['editfeegroup'] > 0)
    {
        $_SESSION['successfeegroup'] = "<p class='success-msg'>Feegroup Edited Successfully.</p>";
        header('location:managefeegroup.php');
    }
    else if ($run['editfeegroup'] == 0)
    {
        $_SESSION['errorfeegroup'] = "<p class='error-msg'>Feegroup Already Exist</p>";
        header('location:managefeegroup.php');
    }
    else
    {
        createErrorlog($run);
        $_SESSION['errorfeegroup'] = "<p class='error-msg'>Some error has been occured. Please try again.</p>";
        header('location:managecfeegroup.php');
    }
}

if (isset($_POST["addfeegroup"]) && $_POST["addfeegroup"] == "new")
{
    $n = 0;
    // $id=$_POST['id'];
    $uid = $_SESSION['myadmin']['adminid'];
    $fname = $_POST['fname'];
    $des = $_POST['fdes'];
    $product = $_POST['product'];
    $query = "SELECT * FROM addfeegroup('$fname','$des','$uid','$product')";
    // print_r($query);exit;
    $run = sqlgetresult($query);
    if ($run['addfeegroup'] > 0)
    {
        $_SESSION['successfeegroup'] = "<p class='success-msg'>Feegroup Added Successfully.</p>";
        header('location:managefeegroup.php');
    }
    else if ($run['addfeegroup'] == 0)
    {
        $_SESSION['errorfeegroup'] = "<p class='error-msg'>Feegroup Already Exist.</p>";
        header('location:managefeegroup.php');
    }
    else
    {
        createErrorlog($run);
        $_SESSION['errorfeegroup'] = "<p class='error-msg'>Some error has been occured. Please try again.</p>";
        header('location:managefeegroup.php');
    }
}
/*****Class Table - End*****/

/*****Stream Table - Start*****/

/*****Stream Table - Start*****/
if (isset($_POST["editstream"]) && $_POST["editstream"] == "update")
{
    $id = $_POST['id'];
    $uid = $_SESSION['myadmin']['adminid'];
    $cname = isset($_POST['cname'])?trim($_POST['cname']):"";
    $des = isset($_POST['des'])?trim($_POST['des']):"";
    $min = isset($_POST['min'])?trim($_POST['min']):0;
    $query = "SELECT * FROM editstreamnew('$id','$cname','$des','$min','$uid')";

    $run = sqlgetresult($query);
    if ($run["editstreamnew"] == 1)
    {
        $_SESSION['successstream'] = "<p class='success-msg'>Data Edited Successfully.</p>";
        header('location:managestream.php');
    }
    else if ($run["editstreamnew"] == 0)
    {
        $_SESSION['errorstream'] = "<p class='error-msg'>Stream Already Exist</p>";
        header('location:managestream.php');
    }
    else
    {
        createErrorlog($run);
        $_SESSION['errorstream'] = "<p class='error-msg'>Some error has been occured. Please try again.</p>";
        header('location:managestream.php');
    }
}

if (isset($_POST["addstream"]) && $_POST["addstream"] == "new")
{
    $n = 0;
    // $id=$_POST['id'];
    $uid = $_SESSION['myadmin']['adminid'];
    $cname = isset($_POST['cname'])?trim($_POST['cname']):"";
    $des = isset($_POST['des'])?trim($_POST['des']):"";
    $min = isset($_POST['min'])?trim($_POST['min']):0;
    $query = "SELECT * FROM addstreamnew('$cname','$des','$min','$uid')";
    $run = sqlgetresult($query);
    if ($run["addstreamnew"] == 1)
    {
        $_SESSION['successstream'] = "<p class='success-msg'>Data Added Successfully.</p>";
        header('location:managestream.php');
    }
    else if ($run["addstreamnew"] == 0)
    {
        $_SESSION['errorstream'] = "<p class='error-msg'>Stream Already Exist</p>";
        header('location:managestream.php');
    }
    else
    {
        createErrorlog($run);
        $_SESSION['errorstream'] = "<p class='error-msg'>Some error has been occured. Please try again.</p>";
        header('location:managestream.php');
    }
}
/*****Stream Table - End*****/

/*****Late Fee Table - Start*****/
if (isset($_POST["editlatefee"]) && $_POST["editlatefee"] == "update")
{
    $id = $_POST['id'];
    $uid = $_SESSION['myadmin']['adminid'];
    $days = $_POST['days'];
    $amt = $_POST['amt'];
    $query = "SELECT * FROM editlatefee('$id','$days','$amt','$uid')";
    // print_r($query);
    // exit;
    $run = sqlgetresult($query);
    if ($run["editlatefee"] == 1)
    {
        $_SESSION['successlatefee'] = "<p class='success-msg'>Data Edited Successfully.</p>";
        header('location:managelatefee.php');
    }
    else if ($run["editlatefee"] == 0)
    {
        $_SESSION['errorlatefee'] = "<p class='error-msg'>Data Already Exist.</p>";
        header('location:managelatefee.php');
    }
    else
    {
        createErrorlog($run);
        $_SESSION['errorlatefee'] = "<p class='error-msg'>Some error has been occured. Please try again.</p>";
        header('location:managelatefee.php');
    }
}

if (isset($_POST["addlatefee"]) && $_POST["addlatefee"] == "new")
{
    $n = 0;
    $uid = $_SESSION['myadmin']['adminid'];
    $days = $_POST['days'];
    $amt = $_POST['amt'];
    $query = "SELECT * FROM addlatefee('$days','$amt','$uid')";
    // print_r($query);
    // exit;
    $run = sqlgetresult($query);
    if ($run["addlatefee"] == 1)
    {
        $_SESSION['successlatefee'] = "<p class='success-msg'>Data Added Successfully.</p>";
        header('location:managelatefee.php');
    }
    else if ($run["addlatefee"] == 0)
    {
        $_SESSION['errorlatefee'] = "<p class='error-msg'>Data Already Exist.</p>";
        header('location:managelatefee.php');
    }
    else
    {
        createErrorlog($run);
        $_SESSION['errorlatefee'] = "<p class='error-msg'>Some error has been occured. Please try again.</p>";
        header('location:managelatefee.php');
    }
}
/*****Late Fee Table - End*****/

/*****Tax Table - Start*****/
if (isset($_POST["edittax"]) && $_POST["edittax"] == "update")
{
    $id = $_POST['id'];
    $uid = $_SESSION['myadmin']['adminid'];
    $date = $_POST['Date'];
    $type = $_POST['type'];
    $central = $_POST['ctax'];
    $state = $_POST['stax'];
    $query = "SELECT * FROM edittax('$id','$date','$type','$central','$state','$uid')";
    $run = sqlgetresult($query);

    if ($run['edittax'] == 1)
    {

        $_SESSION['successtax'] = "<p class='success-msg'>Data Eedited Successfully.</p>";
        header('location:managetax.php');
    }
    else if ($run['edittax'] == 0)
    {

        $_SESSION['errortax'] = "<p class='error-msg'>Data Already Exist.</p>";
        header('location:managetax.php');
    }
    else
    {
        createErrorlog($run);
        $_SESSION['errortax'] = "<p class='error-msg'>Some error has been occured. Please try again.</p>";
        header('location:managetax.php');
    }
}
if (isset($_POST["addtax"]) && $_POST["addtax"] == "new")
{
    $n = 0;
    $uid = $_SESSION['myadmin']['adminid'];
    $date = $_POST['date'];
    $type = $_POST['type'];
    $central = $_POST['ctax'];
    $state = $_POST['stax'];

    $query = "SELECT * FROM addtax('$date','$type','$central','$state','$uid')";
    // print_r($query);/
    // exit;    
    $run = sqlgetresult($query);

    if ($run['addtax'] == 1)
    {

        $_SESSION['successtax'] = "<p class='success-msg'>Data Added Successfully.</p>";
        header('location:managetax.php');
    }
    else if ($run['addtax'] == 0)
    {

        $_SESSION['errortax'] = "<p class='error-msg'>Data Already Exist.</p>";
        header('location:managetax.php');
    }
    else
    {
        createErrorlog($run);
        $_SESSION['errortax'] = "<p class='error-msg'>Some error has been occured. Please try again.</p>";
        header('location:managetax.php');
    }
}
/*****Tax Table - End*****/

/*****Fee Type Table - Start*****/
if (isset($_POST["editfeetype"]) && $_POST["editfeetype"] == "update")
{
    $id = $_POST['id'];
    $uid = $_SESSION['myadmin']['adminid'];
    $cname = $_POST['ftype'];
    $des = $_POST['des'];
    $group = $_POST['feegroup'];
    $max="NULL";
    //     // $tax = $_POST['taxtype'];
    // $man = $_POST['mandatory'];
    if (isset($_POST['mandatory']))
    {
        $man = 1;
    }
    else
    {
        $man = 0;
    }

    if (isset($_POST['dayscholar']) && isset($_POST['hosteller']))
    {
        $app = 'DH';
    }
    elseif (isset($_POST['dayscholar']))
    {
        $app = 'D';
    }
    elseif (isset($_POST['hosteller']))
    {
        $app = 'H';
    }
    else
    {

        $app = 0;

    }

     if(isset($_POST['lunch'])){
       if($app == '0'){
         $app = 'L'; 
       }else{
         $app .= 'L'; 
       }  
    }
    if(isset($_POST['uniform'])){
       if($app == '0'){
         $app = 'U'; 
       }else{
         $app .= 'U'; 
       }
    }
    if(isset($_POST['transport'])){
       if($app == '0'){
         $app = 'T'; 
       }else{
         $app .= 'T'; 
       }
    }

    if(isset($_POST['common'])){
        if($app != 0){
          $app .= 'C';
        }else{
           $app = 'C'; 
        }
        $max=(isset($_POST['max'])&&!empty($_POST['max']))?trim($_POST['max']):"NULL";
    }

    //     // $taxtypes = $_POST['selected_taxtypes'];
    //     // $group =$_POST['group'];
    if ($_POST['selected_taxtypes'] == "")
    {
        $taxtypes = $_POST['oldtax'];
    }
    else
    {
        $taxtypes = $_POST['selected_taxtypes'];
    }

    // if ($_POST['group'] == "")
    // {
    //     $group = $_POST['oldgroup'];
    // }
    // else
    // {
    //     $group = $_POST['group'];
    // }
    $chkpar=(isset($_POST['ispartial']) && !empty($_POST['ispartial']))?trim($_POST['ispartial']):0;

    $ispartial=(isset($_POST['ispartial']) && !empty($_POST['ispartial']))?"'".trim($_POST['ispartial'])."'":"NULL";
    $nextft=(isset($_POST['feetype']) && !empty($_POST['feetype']))?"'".trim($_POST['feetype'])."'":"NULL";
    $duedate=(isset($_POST['duedate']) && !empty($_POST['duedate']))?"'".trim($_POST['duedate'])."'":"NULL";
    if($chkpar==0){
        $ispartial="NULL";
        $nextft="NULL";
        $duedate="NULL"; 
    }
    $query = "SELECT * FROM editfeetype('$id','$cname','$des','$uid','$taxtypes','$group','$man','$app',$max, $ispartial, $nextft, $duedate)";
    $run = sqlgetresult($query);
    // print_r($query);exit;
    // print_r($query);
    // exit;
    if ($run['editfeetype'] == 1)
    {
        $_SESSION['successftype'] = "<p class='success-msg'>Data Edited Successfully.</p>";
        header('location:managefeetype.php');
    }
    else if ($run['editfeetype'] == 0)
    {
        $_SESSION['errorftype'] = "<p class='error-msg'>Same Data Already Exist.</p>";
        header('location:managefeetype.php');
    }
    else
    {
        createErrorlog($run);
        $_SESSION['errorftype'] = "<p class='error-msg'>Some error has been occured. Please try again.</p>";
        header('location:managefeetype.php');
    }
}

if (isset($_POST["addfeetype"]) && $_POST["addfeetype"] == "new")
{
    $n = 0;
    // $id=$_POST['id'];
    $uid = $_SESSION['myadmin']['adminid'];
    $cname = $_POST['ftype'];
    $des = $_POST['des'];
    $taxtypes = $_POST['selected_taxtypes'];
    $group = $_POST['feegroup'];
    $max="NULL";
    if ($_POST['selected_taxtypes'] == "")
    {
        $taxtypes = $_POST['oldtax'];
    }
    else
    {
        $taxtypes = $_POST['selected_taxtypes'];
    }

    // if ($_POST['group'] == "")
    // {
    //     $group = $_POST['oldgroup'];
    // }
    // else
    // {
    //     $group = $_POST['group'];
    // }

    if (isset($_POST['mandatory']))
    {
        $man = 1;
    }
    else
    {
        $man = 0;
    }

    if (isset($_POST['dayscholar']) && isset($_POST['hosteller']))
    {
        $app = 'DH';
    }
    elseif (isset($_POST['dayscholar']))
    {
        $app = 'D';
    }
    elseif (isset($_POST['hosteller']))
    {
        $app = 'H';
    }
    else
    {

        $app = 0;

    }

    if(isset($_POST['lunch'])){
        if($app != 0){
          $app .= 'L';
        }else{
           $app = 'L'; 
        }
    }
    if(isset($_POST['uniform'])){
        if($app != 0){
          $app .= 'U';
        }else{
           $app = 'U'; 
        }
    }
    if(isset($_POST['transport'])){
        if($app != 0){
          $app .= 'T';
        }else{
           $app = 'T'; 
        }
    }
    if(isset($_POST['common'])){
        if($app != 0){
          $app .= 'C';
        }else{
           $app = 'C'; 
        }
        $max=(isset($_POST['max'])&&!empty($_POST['max']))?trim($_POST['max']):"NULL";
    }

    $chkpar=(isset($_POST['ispartial']) && !empty($_POST['ispartial']))?trim($_POST['ispartial']):0;
    $ispartial=(isset($_POST['ispartial']) && !empty($_POST['ispartial']))?"'".trim($_POST['ispartial'])."'":"NULL";
    $nextft=(isset($_POST['feetype']) && !empty($_POST['feetype']))?"'".trim($_POST['feetype'])."'":"NULL";
    $duedate=(isset($_POST['duedate']) && !empty($_POST['duedate']))?"'".trim($_POST['duedate'])."'":"NULL";

    if($chkpar==0){
        $ispartial="NULL";
        $nextft="NULL";
        $duedate="NULL"; 
    }

    $query = "SELECT * FROM addfeetype('$cname','$des','$uid','$taxtypes','$group','$man','$app', $max, $ispartial, $nextft, $duedate)";
    $run = sqlgetresult($query);

    if ($run['addfeetype'] == 1)
    {
        // print_r($run);
        $_SESSION['successftype'] = "<p class='success-msg'>Data Added Successfully.</p>";
        header('location:managefeetype.php');
    }
    else if ($run['addfeetype'] == 0)
    {
        $_SESSION['errorftype'] = "<p class='error-msg'>Same Data Already Exist.</p>";
        header('location:managefeetype.php');
    }
    else
    {
        createErrorlog($run);
        $_SESSION['errorftype'] = "<p class='error-msg'>Some error has been occured. Please try again.</p>";
        header('location:managefeetype.php');
    }
}
/*****Fee Type Table - End*****/

// /*****Fee Type Table - Start*****/
// if (isset($_POST["subyear"]) && $_POST["subyear"] == "academicyear")
// {
//     $id = $_POST['id'];
//     $year = $_POST['year'];
//     $query = "SELECT * FROM edityear('$id','$year')";
//     $run = sqlgetresult($query);

//     if ($run['edityear'] == 1)
//     {
//         $_SESSION['successyear'] = "<p class='success-msg'>Data Edited Successfully.</p>";
//         header('location:manageyear.php');
//     }
//     else if ($run['edityear'] == 0)
//     {
//         $_SESSION['erroryear'] = "<p class='error-msg'>Data Already Exist.</p>";
//         header('location:manageyear.php');
//     }
//     else
//     {
//         createErrorlog($run);
//         $_SESSION['erroryear'] = "<p class='error-msg'>Some error has been occured. Please try again.</p>";
//         header('location:manageyear.php');
//     }
// }

// /*****Fee Type Table - End*****/

/***Fee Entry Report start***/
if (isset($_POST['ctoexcel']) && $_POST['ctoexcel'] == "ctoexcel")
{
    $postedData = json_decode($_POST['datetimes'], true);
    $fromdate = $postedData['start'];
    $todate = $postedData['end'];
    // print_r($_POST['fromdate']);exit;
    

    $sql = 'SELECT * FROM getPaymentData WHERE "createdOn"::date >= \'' . $fromdate . '\' AND "createdOn"::date <= \'' . $todate . '\' ORDER BY id DESC';
    // $sql = "SELECT * FROM getPaymentData";
    $res = sqlgetresult($sql, true);

    $columns = array(
        'id',
        'studentName',
        'studentId',
        'academicYear',
        'stream',
        'class'
    );

    exportData($res, 'Fee Entry Report', $columns);
}

if (isset($_POST['submit']) && $_POST['submit'] == 'getPaymentData')
{

    $classselect = $_POST['classselect'] != '' ? getClassbyNameId($_POST['classselect']) : $_POST['classselect'];
    $streamselect = $_POST['streamselect'] != '' ? getStreambyId($_POST['streamselect']) : $_POST['streamselect'];

    if (isset($_POST['fromdate']) && $_POST['fromdate'] != '')
    {
        $sql = 'SELECT * FROM getPaymentData WHERE "createdOn"::date >= \'' . $fromdate . '\' AND "createdOn"::date <= \'' . $todate . '\' ORDER BY id DESC ';
    }
    else
    {
        $sql = 'SELECT * FROM getPaymentData';
    }
    $res = sqlgetresult($sql, true);
    echo json_encode($res);
}
/****Fee Entry Report END****/

/***Payment Report start***/
if (isset($_POST['ctoe']) && $_POST['ctoe'] == "ctoe")
{
    $postedData = json_decode($_POST['datetimes'], true);
    $fromdate = $postedData['start'];
    $todate = $postedData['end'];
    // print_r($fromdate);
    $sql = 'SELECT * FROM getPaidData WHERE "transDate" >= \'' . $fromdate . '\' AND "transDate" <= \'' . $todate . '\'';
    // $sql = "SELECT * FROM getPaymentData";
    // print_r($sql);exit;
    $res = sqlgetresult($sql, true);
    // print_r($res);exit;
    $columns = array(
        'id',
        'parentId',
        'studentId',
        'transDate',
        'transNum',
        'transStatus',
        'amount'
    );
    exportData($res, 'Payment Report', $columns);
}

if (isset($_POST['submit']) && $_POST['submit'] == 'getPaidData')
{
    if (isset($_POST['fromdate']) && $_POST['fromdate'] != '')
    {
        $sql = 'SELECT * FROM getPaidData WHERE "transDate" >= \'' . $fromdate . '\' AND "transDate" <= \'' . $todate . '\'';

    }
    else
    {
        $sql = 'SELECT * FROM getpaiddata';
    }
    echo $sql;
    $res = sqlgetresult($sql, true);
    echo json_encode($res);
}
/****Payment Report END****/

/******* Teacher - Start *****/

if (isset($_POST['addteacher']) && $_POST['addteacher'] == 'new')
{
    $name = $_POST['name'];
    $email = $_POST['email'];
    $stream = $_POST['stream'];
    $class = $_POST['selected_class'];
    // $section =$_POST['section'];
    $phoneNumber = $_POST['pnum'];
    $mobileNumber = $_POST['mnum'];
    $createdBy = $_SESSION['myadmin']['adminid'];
    $pass = generateVerifyCode(12);
    $password = password_hash($pass, PASSWORD_DEFAULT);

    $sql = sqlgetresult("SELECT * FROM addTeacher('$name', '$email','$password','$class','$phoneNumber','$mobileNumber','$createdBy','$stream')");
    // print_r($sql);exit;
    if ($sql['addteacher'] == '0')
    {
        $_SESSION['success'] = "<p class='success-msg'>Data Added Successfully</p>";
        $data = 'Hi,<br/>&nbsp;&nbsp;&nbsp;&nbsp;Please find the teacher login credentials below,</br>';
        $data .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;UserName : ' . $email;
        $data .= '<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Password : ' . $pass;
        SendMailId($email, 'New Teacher Login', $data);
    }
    elseif ($sql['addteacher'] == '1')
    {
        $_SESSION['failure'] = "<p class='error-msg'>User already exist.</p>";
    }
    else
    {
        createErrorlog($sql);
        $_SESSION['error'] = "<p class='error-msg'>Some error has occurred. Please Try Again Later.</p>";
    }
    header("Location:manageteachers.php");
}

if (isset($_POST['editteacher']) && $_POST['editteacher'] == 'update')
{   
    $var = 0;
    $id = $_POST['id'];
    $uid = $_SESSION['myadmin']['adminid'];
    $name = $_POST['name'];
    $stream = $_POST['stream'];
    $class = $_POST['selected_class'];
    // $section = $_POST['section'];
    $email = $_POST['email'];
    $phoneNumber = $_POST['pnum'];
    $mobileNumber = $_POST['mnum'];
    $password = ($_POST['password'] == '') ? $_POST['pass_old'] : password_hash($_POST['password'], PASSWORD_DEFAULT);

    $query = "SELECT * FROM editTeacher('$id','$name','$class','$email','$phoneNumber','$mobileNumber','$password','$uid','$stream')";
    $run = sqlgetresult($query);
    // print_r($query);exit;
    if ($run['editteacher'] == '0')
    {
        if ($_POST['password'] != '')
        {
            $data = 'Hi,<br/>&nbsp;&nbsp;&nbsp;&nbsp;Please find the Edited Teacher login credentials below,</br>';
            $data .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;UserName : ' . $email;
            $data .= '<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Password : ' . $_POST['password'];
            $var = 1;
            if($var == 1){
            SendMailId($email, 'Edited Teacher Login', $data);
            }
        }
        $_SESSION['success'] = "<p class='success-msg'>Data Edited Successfully.</p>";
    }
    elseif ($sql['editteacher'] == '1')
    {
        $_SESSION['failure'] = "<p class='error-msg'>User already exist.</p>";
    }
    else
    {
        createErrorlog($run);
        $_SESSION['error'] = "<p class='error-msg'>Some error has occurred. Please Try Again Later.</p>";
    }
    header('location:manageteachers.php');
}

/****** Teacher - End ****/

if (isset($_POST['updatestatus']) && $_POST['updatestatus'] == 'updatestatus')
{

    $_POST = array_map('trim',$_POST);
    $id = $_POST['studentId'];
    $createdby = $_SESSION['myadmin']['adminid'];
    $stream = $_POST['stream'];
    $promotedclass = $_POST['promotedclass'];
    if($promotedclass != '' && $_POST['semestercheck'] == 'I'){
        $class = $promotedclass;
        $academicyearcheck = sqlgetresult('SELECT "id" FROM yearcheck WHERE "active" = \'1\'');
        $academic = $academicyearcheck['id'];
        $term = trim($_POST['semestercheck']);
    }
    else{
        $class = $_POST['class']; 
        $academic = $_POST['academicyear'];
        $term = trim($_POST['semestercheck']);
    }
    $streamName = getStreambyId($stream);

    if (isset($_SESSION['selectedstudid']))
    {
        $selected = array_map('trim',$_SESSION['selectedstudid']);
        for ($i = 0;$i < count($selected);$i++)
        {
            $studentname = trim(getStudentNameById($selected[$i]));

            $lastrecordID = sqlgetresult("SELECT SETVAL('tbl_temp_challans_id_seq',MAX(id)) AS max FROM tbl_temp_challans");
            if (!ctype_digit(strval($lastrecordID['max'])))
            {
                $challanNo = trim($streamName) . date('Y') . '/000001';
            }
            else
            {
                $no = str_pad(++$lastrecordID['max'], 6, '0', STR_PAD_LEFT);;
                $challanNo = trim($streamName) . date('Y') . '/' . $no;
            }

            $findStudData = sqlgetresult('SELECT * FROM tbl_student WHERE "studentId" = \''. $selected[$i].'\' ');
            $findStudData=array_map('trim',$findStudData);

            $mclass = trim($findStudData['class']);

            $sql = "SELECT * FROM createTempChallanNew('$challanNo','$selected[$i]','$createdby','$stream','$class','$term','$studentname','$academic')";
            $result = sqlgetresult($sql);
        }
    }
    else
    {
        $studentId = $_POST['studentId'];
        $studentName = $_POST['studentName'];

        $lastrecordID = sqlgetresult("SELECT SETVAL('tbl_temp_challans_id_seq',MAX(id)) AS max FROM tbl_temp_challans");
        if (!ctype_digit(strval($lastrecordID['max'])))
        {
            $challanNo = trim($streamName) . date('Y') . '/000001';
        }
        else
        {
            $no = str_pad(++$lastrecordID['max'], 6, '0', STR_PAD_LEFT);;
            $challanNo = trim($streamName) . date('Y') . '/' . $no;
        }

        $sql = "SELECT * FROM createTempChallanNew('$challanNo','$studentId','$createdby','$stream','$class','$term','$studentName','$academic')";
        $result = sqlgetresult($sql);
    }
    if ($result['createTempChallanNew'] == '0')   {
        $_SESSION['errorstatusstudent'] = "<p class='error-msg'>Some error has been occured. Please try again.</p>";
        unset($_SESSION['selectedstudid']);
        header('location:home.php');
    }  else {
        $_SESSION['successstatusstudent'] = "<p class='success-msg'>Data updated successfully.</p>";
        unset($_SESSION['selectedstudid']);
        header('location:home.php');
    }
}

if (isset($_POST['updateTempChallan']) && $_POST['updateTempChallan'] == 'updateTempChallan')
{   

    $_POST = array_map('trim',$_POST);
    $selected_feetypes = explode(',', $_POST['selected_feetypes']);
    $class = trim($_POST['class_list']);
    $term = trim($_POST['semester']);
    $feetypes = trim($_POST['selected_feetypes']);
    $createdby = trim($_SESSION['myadmin']['adminid']);
    $studStatus = trim($_POST['status']);
    $stream = trim($_POST['stream']);
    $remarks = trim($_POST['remarks']);
    $duedate = trim($_POST['duedate']);
    $academic = trim($_POST['academic']);
    $streamName = getStreambyId($stream);
    $feetypedata = sqlgetresult('SELECT * FROM getfeetypedata WHERE class=\'' . $class . '\' AND semester=\'' . $term . '\' AND stream = \'' . ($stream) . '\' AND "academicYear" = \'' . $academic . '\'', true);
    
    $selectedids = array();

    if (sizeof($feetypedata) == 0)
    {
        echo json_encode("Fee Types empty");
        exit;
    }
    if (isset($_SESSION['selectedchallans']))
    {
        $selected = array_map('trim',$_SESSION['selectedchallans']);
        for ($i = 0;$i < count($selected);$i++)
        {
            $challanNo = sqlgetresult('SELECT "challanNo" FROM tbl_temp_challans WHERE "studentId" = \'' . $selected[$i] . '\' AND term=\'' . $term . '\' AND "academicYear" = \'' . $academic . '\' ', true);
            foreach ($challanNo as $challan)
            {
                foreach ($challan as $key => $val)
                {
                    $name = trim(getStudentNameById($selected[$i]));
                    $totalamount = 0;
                    $selectedData = array();

                    $findStudData = sqlgetresult('SELECT * FROM tbl_student WHERE "studentId" = \''. $selected[$i].'\' ');
                    $findStudData=array_map('trim',$findStudData);

                    $mclass = $findStudData['class'];
                    $mterm = $findStudData['term'];
                    $mstream = $findStudData['stream'];
                    $mtransport = $findStudData['transport_need'];
                    $mtransport_stg = $findStudData['transport_stg'];
                    $macademic = $findStudData['academic_yr'];

                    $feetypedata = sqlgetresult('SELECT * FROM getfeetypedata WHERE class=\'' . $class . '\' AND semester=\'' . $term . '\' AND stream = \'' . ($mstream) . '\' AND "academicYear" = \'' . $academic . '\'',true);

                    $feeData = explode(',', $feetypes);
                    if( $findStudData['transport_stg'] != '' || $findStudData['transport_stg'] != 0) {
                        array_push($feeData, getTransportStage(trim($mtransport_stg)));
                    }
                    $groupdata = array();
                    foreach ($feeData as $k => $v)
                    {
                        foreach ($feetypedata as $val)
                        {
                            if ( $v == trim($val['feeType']))
                            {
                                $group = getFeeGroupbyId($val['feeGroup']);
                                $groupdata[$group][$val['feeGroup']][$val['id']][] = $val['amount'];
                                $groupdata[$group][$val['feeGroup']][$val['id']][] = $val['feename'];

                                $deletetempchallan = sqlgetresult('DELETE FROM tbl_temp_challans WHERE "challanNo" = \''.$challan[$key].'\' AND "studentId" = \''.$selected[$i].'\' AND "feeGroup"= \''.trim($val['feeGroup']).'\' AND term = \''.$term.'\' AND "academicYear" =\''.$academic.'\' AND "feeType" = \''.trim($val['feeType']).'\'');

                                $sql = "SELECT * FROM updatetempchallanNew('$challan[$key]','$selected[$i]','$class','" . trim($val['feeType']) . "','$term','$name' ,'$studStatus','$createdby','".trim($val['amount'])."','$mstream','$remarks','$duedate','". trim($val['feeGroup']) ."','$academic')";
                                $result = sqlgetresult($sql);

                                if ($result['updatetempchallannew'] == '0')
                                {
                                    $challanData['exist'] = 'Challan Already Exists';
                                }

                                $challanData = sqlgetresult('SELECT * FROM tempChallan WHERE "challanNo"=\'' . $challan[$key] . '\' LIMIT 1');
                                $selectedData['challanData'] = $challanData;
                                $selectedData['feeData'] = $groupdata;
                                $challanId = sqlgetresult('SELECT id FROM tempChallan WHERE "challanNo"=\'' . $challan[$key] . '\' AND "feeGroup" IS NOT NULL ',true); 

                                array_push($selectedids, $challan[$key]);
                    
                             }
                        }
                    }
                    
                }
            }
        }
    $_SESSION['createdchallanids'] = $selectedids;

    unset($_SESSION['selectedchallans']);
    unset($_SESSION['createdchallans']);
    }
    else
    {   
        unset($_SESSION['selectedchallans']);
        unset($_SESSION['createdchallans']);
        $id = $_POST['studentId'];
        $name = $_POST['studentName'];
        $challanNo = $_POST['challan'];
        $totalamount = 0;
        $findStudData = sqlgetresult('SELECT * FROM tbl_student WHERE "studentId" = \''. trim($id).'\' ');
        $findStudData=array_map('trim',$findStudData);
        $selectedData = array();
        $feeData = explode(',', $feetypes);
        if( $findStudData['transport_stg'] != '' || $findStudData['transport_stg'] != 0) {
            array_push($feeData, getTransportStage(trim($findStudData['transport_stg'])));
        }
        foreach ($feeData as $k => $v)
        {
            foreach ($feetypedata as $val)
            {
                if ( $v == trim($val['feeType']))
                {
                    $group = getFeeGroupbyId($val['feeGroup']);
                    $groupdata[$group][$val['feeGroup']][$val['id']][] = $val['amount'];
                    $groupdata[$group][$val['feeGroup']][$val['id']][] = $val['feename'];

                    $deletetempchallan = sqlgetresult('DELETE FROM tbl_temp_challans WHERE "challanNo" = \''.$challanNo.'\' AND "studentId" = \''.$id.'\' AND "feeGroup"= \''.trim($val['feeGroup']).'\' AND term = \''.$term.'\' AND "academicYear" =\''.$academic.'\' AND "feeType" = \''.trim($val['feeType']).'\' ');                    
                    $sql = "SELECT * FROM updatetempchallanNew('$challanNo','$id','$class','" . trim($val['feeType']) . "','$term','$name' ,'$studStatus','$createdby','".trim($val['amount'])."','$stream','$remarks','$duedate','". trim($val['feeGroup']) ."','$academic')";
                    $result = sqlgetresult($sql);
                    if ($result['updatetempchallannew'] == '0')
                    {
                        $challanData['exist'] = 'Challan Already Exists';
                    }

                    $challanData = sqlgetresult('SELECT * FROM tempChallan WHERE "challanNo"=\'' . $challanNo . '\' ORDER BY id ASC LIMIT 1');
                    $selectedData['challanData'] = $challanData;
                 }
            }
        }
        $selectedData['feeData'] = $groupdata;
    }
    echo json_encode($selectedData);
}

if (isset($_GET['c']) && $_GET['c'] != '')
{
    if (isset($_SESSION['createdchallanids']))
    {
        $createdchallans = array_unique($_SESSION['createdchallanids']);

        foreach ($createdchallans as $challans)
        {
            $challanNo = sqlgetresult('SELECT "studentId" FROM tbl_temp_challans WHERE "challanNo"=\'' . $challans . '\' LIMIT 1');
            $del = sqlgetresult('DELETE FROM tbl_temp_challans WHERE "challanNo"= \'' . $challans . '\' AND "feeGroup" IS NULL ');
            $rowid = sqlgetresult('SELECT * FROM tbl_temp_challans WHERE "challanNo"= \'' . $challans . '\' AND "feeGroup" IS NOT NULL', true);
            foreach ($rowid as $k => $row)
            {
                $id = $row['id'];
                $datas = sqlgetresult("SELECT * FROM createChallanNew('".$id."')");
                if(count($datas) > 0 && !strstr($row['challanNo'], 'TF-')){
                    toUpdateChallanCreationDateOnAppl($row['studentId'],$row['createdOn'],$row['duedate']);
                }
                if( trim($row['feeGroup']) == 10) {
                    $sfsqty = sqlgetresult("SELECT * FROM sfstableentry('" . $row['challanNo'] . "','" . $row['feeType'] . "','" . $row['total'] . "', 1, '" . $row['total'] . "','" . $_SESSION['myadmin']['adminid'] . "','" . $row['studentId'] . "')");
                    // echo $sfsqty;echo "<hr/>";
                }
                // echo "SELECT * FROM createChallan('$id')";
                flattableentry(trim($row['challanNo']), trim($row['studentId']));
            }
            // print_r($createdchallans);
            // exit;
            $challanData = sqlgetresult('SELECT * FROM challanData WHERE "studentId" =\'' . $challanNo['studentId'] . '\' AND  "challanNo" = \'' . $challans . '\' ', true);
            $feeTypes = sqlgetresult("SELECT * FROM getFeeTypes");
           
            $total = 0;
            $feeData = array();
            $chlncnt = count($challanData);
            $addtional=0;
            foreach ($challanData as $k => $value)
            {
                $challanData1['challanNo'] = $value['challanNo'];
                $challanData1['term'] = $value['term'];
                $challanData1['clid'] = $value['clid'];
                $challanData1['studentName'] = $value['studentName'];
                $challanData1['studentId'] = $value['studentId'];
                $challanData1['class_list'] = $value['class_list'];
                $challanData1['duedate'] = $value['duedate'];
                $challanData1['stream'] = $value['stream'];
                $challanData1['steamname'] = $value['steamname'];
                $challanData1['org_total'] = $value['org_total'];
                $challanData1['academic_yr'] = $value['academic_yr'];
                /* To check the challan type start*/
                $studStatus=trim($value['studStatus']);
                if(stristr($studStatus, 'Transport')){
                  $textconcat="transport";
                  $txtguide="<p style='color:#800000'>Transport challan is available under Other Fees -> Transport Fee option.</p>";
                }else{
                  $textconcat="";
                  $txtguide="";
                }
                /* To check the challan type end*/

                $feetypearray[getFeeGroupbyId($value['feeGroup'])][$value['feeType']][] = $value['org_total'];
                $feetypearray[getFeeGroupbyId($value['feeGroup'])][$value['feeType']][] = getFeeTypebyId($value['feeType']);
                $cnt = $k+1;
                if($cnt == $chlncnt) {
                    $groupdata = $feetypearray;
                } 
                
                if(stristr($studStatus, 'Additional')){
                    $addtional=1;
                }
            }

            // $msg = "Hello " . $getparentmailid['userName'] . "! <br/>";
            $msg = "<p>New ".$textconcat." challan has been created for " . $challanData1['studentName'] . " and the due date is <b style='color:red'>" . date("d-m-Y", strtotime($challanData1['duedate'])) . "</b>.</p><p>For online payment please login to our <a href=" . BASEURL . " style='color:red'>FEE PORTAL</a>.</p>".$txtguide;

            // $msg .= "<table border='1' style='border:1px solid grey;border-collapse: collapse;' cellpadding='10' width = '100%'>
            //     <tr><td colspan='2'><label> <b>School Name</b>: </label> LMOIS - CBSE</td></tr>
            //     <tr><td><label><b>Name</b>: </label> " . $challanData1['studentName'] . " </td><td><label><b>Semester</b>: </label>" . $challanData1['term'] . " </td></tr>
            //     <tr><td><label><b>ID</b>: </label> " . $challanData1['studentId'] . " </td><td><label><b>Class</b>: </label> " . $challanData1['class_list'] . " </td></tr>
            //     <tr><td><label><b>Challan Number</b>: </label>" . $challanData1['challanNo'] . "</td><td><label><b>Due Date</b>: </label> " . date("d-m-Y", strtotime($challanData1['duedate'])) . "</td></tr>
            //     <tr><td colspan='2' style='text-align:center'><b>FEE DETAILS</b></td></tr>";          

            // $tot = 0;
           
            // foreach ($groupdata as $grp => $data)
            // {
            //     $msg .= '<tr style="border:0;border-right:1px solid grey;"><td colspan="2"><b>' . $grp . '</b></td></tr>';

            //     foreach ($data as $k => $val){

            //         // foreach($val as $va => $v){
            //             if(trim($val[0]) != 0){
            //             $msg .= '<tr style="border:0;"><td >' . $val[1] . '</td><td style="border-right:1px solid grey;text-align:right;">' . $val[0] . '</td></tr>';
            //             $tot += $val[0];
            //             }
            //         // }
            //     }
            // }
            // $msg .= '<tr style="border-top:1px solid grey;"><td style="border-top:1px solid grey;"><b>TOTAL</b></td><td style="border-top:1px solid grey;text-align:right;"><b>' . $tot . '</b></td></tr>';
            // $msg .= "</table>";

            $mailbody = $msg;
            $smsbody = "Dear Parent, New Challan(".trim($challanData1['challanNo']).") has been created and available for payment from FeeApp. Please login and pay.";
            $studentId = trim($challanData1['studentId']);
            $type = "Challan Creation";

            if ($datas['createchallannew'] > 0)
            {
                $_SESSION['successchallan'] = "<p class='success-msg'>Challan created successfully and mail has been sent to the parent’s email address</p>";
                $chkNew=toCheckNewAdmission($studentId);
                if($addtional==1){
                    sendNotificationToParents($studentId, $mailbody, $smsbody, $type); 
                }else{
                    if ($chkNew && $textconcat!='transport') {
                        sendNotificationToApplParents($studentId, $mailbody, $smsbody, $type);
                    }else{
                        sendNotificationToParents($studentId, $mailbody, $smsbody, $type); 
                    }
                }
                
                header('location:managecreatedchallans.php');
            } else {
                createErrorlog($data);
                $_SESSION['error'] = "<p class='error-msg'>Some error has occurred.Please Try Again Later.</p>";
                header('Location:managechallans.php');
            }
        }
        unset($_SESSION['createdchallanids']);
        exit;
    }
    else
    {
        $challanId = $_GET['c'];
        $challanNo = sqlgetresult('SELECT "challanNo", "studentId" FROM tbl_temp_challans WHERE "id"=\'' . $challanId . '\'');
        $del = sqlgetresult('DELETE FROM tbl_temp_challans WHERE "challanNo"= \'' . $challanNo['challanNo'] . '\' AND "feeGroup" IS NULL ');
        $rowid = sqlgetresult('SELECT * FROM tbl_temp_challans WHERE "challanNo"= \'' . $challanNo['challanNo'] . '\'',true);
        foreach ($rowid as $k => $row)
        {
            $id = $row['id'];
            $datas = sqlgetresult("SELECT * FROM createChallanNew('".$id."')");
            if(count($datas) > 0 && !strstr($row['challanNo'], 'TF-')){
                toUpdateChallanCreationDateOnAppl($row['studentId'],$row['createdOn'],$row['duedate']);
            }
            // echo $datas;echo "<hr/>";
            if( trim($row['feeGroup']) == 10) {
                $sfsqty = sqlgetresult("SELECT * FROM sfstableentry('" . $row['challanNo'] . "','" . $row['feeType'] . "','" . $row['total'] . "', 1, '" . $row['total'] . "','" . $_SESSION['myadmin']['adminid'] . "','" . $row['studentId'] . "')");
                // echo $sfsqty;echo "<hr/>";
            }
            flattableentry(trim($row['challanNo']), trim($row['studentId']));
        }
        $challanData = sqlgetresult('SELECT * FROM challanData WHERE "studentId" =\'' . $challanNo['studentId'] . '\' AND  "challanNo" = \'' . $challanNo['challanNo'] . '\' ', true);
        $feeTypes = sqlgetresult("SELECT * FROM getFeeTypes");
        
        $total = 0;
        $feeData = array();
        $chlncnt = count($challanData);
        $addtional=0;
        foreach ($challanData as $k => $value)
        {
            $challanData1['challanNo'] = trim($value['challanNo']);
            $challanData1['term'] = $value['term'];
            $challanData1['clid'] = $value['clid'];
            $challanData1['studentName'] = trim($value['studentName']);
            $challanData1['studentId'] = trim($value['studentId']);
            $challanData1['class_list'] = $value['class_list'];
            $challanData1['duedate'] = $value['duedate'];
            $challanData1['stream'] = $value['stream'];
            $challanData1['steamname'] = $value['steamname'];
            $challanData1['org_total'] = $value['org_total'];
            $challanData1['academic_yr'] = trim($value['academic_yr']);
            /* To check the challan type start*/
            $studStatus=trim($value['studStatus']);
            if(stristr($studStatus, 'Transport')){
              $textconcat="transport";
              $txtguide="<p style='color:#800000'>Transport challan is available under Other Fees -> Transport Fee option.</p>";
            }else{
              $textconcat="";
              $txtguide="";
            }
            /* To check the challan type end*/


                $feetypearray[getFeeGroupbyId($value['feeGroup'])][$value['feeType']][] = $value['org_total'];
                $feetypearray[getFeeGroupbyId($value['feeGroup'])][$value['feeType']][] = getFeeTypebyId($value['feeType']);
                $cnt = $k+1;
                if($cnt == $chlncnt) {
                    $groupdata = $feetypearray;

                }

                if(stristr($studStatus, 'Additional')){
                    $addtional=1;
                }
        }
        // $msg = "Hello " . $getparentmailid['userName'] . "! <br/>";
        $msg = "<p>New ".$textconcat." challan has been created for " . $challanData1['studentName'] . " and the due date is <b style='color:red'>" . date("d-m-Y", strtotime($challanData1['duedate'])) . "</b>.<p><p>For online payment please login to our <a href=" . BASEURL . " style='color:red'>FEE PORTAL</a>.</p>".$txtguide;

        // $msg .= "<table border='1' style='border:1px solid grey;border-collapse: collapse;' cellpadding='10' width = '100%'>
        //         <tr><td colspan='2'><label> <b>School Name</b>: </label> LMOIS - CBSE</td></tr>
        //         <tr><td><label><b>Name</b>: </label> " . $challanData1['studentName'] . " </td><td><label><b>Semester</b>: </label>" . $challanData1['term'] . " </td></tr>
        //         <tr><td><label><b>ID</b>: </label> " . $challanData1['studentId'] . " </td><td><label><b>Class</b>: </label> " . $challanData1['class_list'] . " </td></tr>
        //         <tr><td><label><b>Challan Number</b>: </label>" . $challanData1['challanNo'] . "</td><td><label><b>Due Date</b>: </label> " . date("d-m-Y", strtotime($challanData1['duedate'])) . "</td></tr>
        //         <tr><td colspan='2' style='text-align:center'><b>FEE DETAILS</b></td></tr>";        

        // $tot = 0;
        // foreach ($groupdata as $grp => $data)
        // {
        //     $msg .= '<tr style="border:0;border-right:1px solid grey;"><td colspan="2"><b>' . $grp . '</b></td></tr>';

        //     foreach ($data as $k => $val){

        //         if(trim($val[0]) != 0){                
        //             $msg .= '<tr style="border:0;"><td >' . $val[1] . '</td><td style="border-right:1px solid grey;text-align:right;">' . $val[0] . '</td></tr>';
        //             $tot += $val[0];
        //         }
        //     }
        // }
        // $msg .= '<tr style="border-top:1px solid grey;"><td style="border-top:1px solid grey;"><b>TOTAL</b></td><td style="border-top:1px solid grey;text-align:right;"><b>' . $tot . '</b></td></tr>';
        // $msg .= "</table>";

        
        $mailbody = $msg;

        $smsbody = "Dear Parent, New challan (".trim($challanData1['challanNo']).")
            has been created for " . trim($challanData1['studentName']) . " (" . trim($challanData1['studentId']) . ") for ".trim($challanData1['term'])." sem(".trim(getAcademicyrById($challanData1['academic_yr'])).").";

        $studentId = trim($challanData1['studentId']);
        $type = "Challan Creation";

        if ($datas['createchallannew'] > 0) {
            $_SESSION['successchallan'] = "<p class='success-msg'>Challan created successfully and mail has been sent to the parent’s email address</p>";
            $chkNew=toCheckNewAdmission($studentId);
            if($addtional==1){
               sendNotificationToParents($studentId, $mailbody, $smsbody, $type,'','Notification From Omega');
            }else{
                if ($chkNew && $textconcat!='transport') {
                    sendNotificationToApplParents($studentId, $mailbody, $smsbody, $type);
                }else{
                    sendNotificationToParents($studentId, $mailbody, $smsbody, $type,'','Notification From Omega'); 
                } 
            }       
            header('location:managecreatedchallans.php');        
        } else {
            createErrorlog($data);
            $_SESSION['error'] = "<p class='error-msg'>Some error has occurred Please Try Again Laterr.</p>";
            header('Location:managechallans.php');
        }        
    }
}

if (isset($_POST['submit']) && $_POST['submit'] == 'feeconfiguration')
{
    $academicyear = trim($_POST['academic']);
    $stream = $_POST['stream'];
    $semester = $_POST['semester'];
    $feetype = $_POST['feetype'];
    // $duedate = $_POST['duedate'];
    $createdby = $_SESSION['myadmin']['adminid'];

    $keys = array_keys($_POST);
    $commondata = array();
    $feeClassbased = array();
    // console.log($commondata);
    foreach ($keys as $key)
    {
        // echo $key;
        if (strpos($key, '*') !== false)
        {
            $k = explode('**', $key);
            $feeClassbased[$k[1]] = $_POST[$key];
        }
        else
        {
            $commondata[$key] = $_POST[$key];
        }
    }
    // $feeClassbased = array_filter($feeClassbased); 
    $feeClassbased = array_filter($feeClassbased,"strlen");

    foreach ($feeClassbased as $key => $value)
    {
        // $commondata['feeType'][$key] = $value;
        $query = 'SELECT * FROM taxtypecheck WHERE id=\'' . $feetype . '\' AND "effectiveDate" <= current_date';
        $res = sqlgetresult($query, true);            
        // print_r($query);
        // print_r($res);
        $newpercentage = 0;
        foreach ($res as $num => $val)
        {
            if ($res[$num]['tax'] != "")
            {
               $percentage = ((($value / 100) * ($res[$num]['centralTax'])) + (($value / 100) * ($res[$num]['stateTax'])));

            }
            $newpercentage = $newpercentage + $percentage;
        }

        $lastvalue = round($value + $newpercentage);
        $query = "SELECT * FROM addfeeconfiguration('$academicyear','$stream','$semester','$feetype','$createdby','$lastvalue','$key')";
        // print_r($query);
        // exit;
        $result = sqlgetresult($query);
        // print_r($result);echo "<hr/>";
        if ($result['addfeeconfiguration'] == '1')
        {
            $_SESSION['success'] = "<p class='success-msg'>Data Inserted Successfully</p>";
        }
        elseif ($result['addfeeconfiguration'] == '0')
        {
            $_SESSION['error'] = "<p class='error-msg'>Same Configurtion Data Already Exists.</p>";
        }
        else
        {
            createErrorlog($result);
            $_SESSION['error'] = "<p class='error-msg'>Some error has occurred Please Try Again Later.</p>";
        }
    }

    // exit;
    header('location:feeconfiguration.php');

}

if (isset($_POST['editfeeconfig']) && $_POST['editfeeconfig'] == 'update')
{

    $stream = $_POST['stream'];
    $semester = $_POST['semester'];
    $feetype = $_POST['feeType'];
    $duedate = $_POST['dueDate'];
    $updatedby = $_SESSION['myadmin']['adminid'];
    $class = $_POST['class'];
    $amt = round($_POST['amount']);
    $id = $_POST['id'];
    $academicyear = $_POST['academic'];

    $result = sqlgetresult("SELECT * FROM editfeeconfiguration('$academicyear','$stream','$semester','$feetype','$updatedby','$amt','$class','$id')");
    // print_r($result);exit;
    if ($result['editfeeconfiguration'] == '1')
    {
        $_SESSION['success'] = "<p class='success-msg'>Data Updated Successfully</p>";
    }
    elseif ($result['editfeeconfiguration'] == '0')
    {
        $_SESSION['error'] = "<p class='error-msg'>Same Configurtion Data Already Exists.</p>";
    }
    else
    {
        createErrorlog($result);
        $_SESSION['error'] = "<p class='error-msg'>Some error has occurred Please Try Again Later.</p>";
    }
    header('location:feeconfiguration.php');

}

/*****Class Table - Start*****/
if (isset($_POST["editcomments"]) && $_POST["editcomments"] == "update")
{
    $id = $_POST['id'];
    $uid = $_SESSION['myadmin']['adminid'];
    $pname = $_POST['pname'];
    $com = $_POST['txtEditorContent'];
    $startdate = $_POST['startdate'];
    $enddate  = $_POST['enddate'];
    $query = "SELECT * FROM editcomments('$id','$pname','$com','$startdate','$enddate','$uid')";

    $run = sqlgetresult($query);
   // print_r($run);
   // exit;

    if ($run['editcomments'] == 1)
    {
        $_SESSION['successcomments'] = "<p class='success-msg'>Data edited successfully.</p>";
        header('location:managecomments.php');
    }
    else if ($run['editcomments'] == 0)
    {
        $_SESSION['errorcomments'] = "<p class='error-msg'>Page Name Already Exist</p>";
        header('location:managecomments.php');
    }
    else
    {
        createErrorlog($run);
        $_SESSION['errorcomments'] = "<p class='error-msg'>Some error has been occured. Please try again.</p>";
        header('location:managecomments.php');
    }
}

if (isset($_POST["addcomments"]) && $_POST["addcomments"] == "new")
{
    $n = 0;
    // $id=$_POST['id'];
    $uid = $_SESSION['myadmin']['adminid'];
    $pname = trim($_POST['pname']);
    $com = $_POST['txtEditorContent'];
    $startdate = $_POST['startdate'];
    $enddate  = $_POST['enddate'];
    $query = "SELECT * FROM addcomments('$pname','$com','$startdate','$enddate','$uid')";
    
    $run = sqlgetresult($query);
    // print_r($run);
    // exit;
    if ($run['addcomments'] == 1)
    {
        $_SESSION['successcomments'] = "<p class='success-msg'>Data Added Successfully.</p>";
        header('location:managecomments.php');
    }
    else if ($run['addcomments'] == 0)
    {
        $_SESSION['errorcomments'] = "<p class='error-msg'>Page Name already exist.</p>";
        header('location:managecomments.php');
    }
    else
    {
        createErrorlog($run);
        $_SESSION['errorcomments'] = "<p class='error-msg'>Some error has been occured. Please try again.</p>";
        header('location:managecomments.php');
    }
}
/*****Class Table - End*****/

/***********Get Comments - Start***********/



if (isset($_POST['submit']) && $_POST['submit'] == "getComments")
 {

   $sql = "SELECT * FROM commentscheck WHERE startdate <= CURRENT_DATE  AND  enddate > CURRENT_DATE ;";
    
    $res = sqlgetresult($sql, true);
      // print_r($sql);
   echo json_encode($res);
     
}
     
        /************Get Comments - End**********/

if ((isset($_POST['changepassword']) && $_POST['changepassword'] == 'change'))
{
    $useremail = isset($_POST['email'])?trim($_POST['email']):"";
    $recaptcha = isset($_POST["g-recaptcha-response"])?trim($_POST["g-recaptcha-response"]):"";
    $isvalid=validatecaptcha($recaptcha, $recaptch_secret_key);
    if($isvalid==0){
        $_SESSION['error_msg2'] = "<p class='error-msg'>Invalid reCAPTACHA! Kindly try again.</p>";
        header("Location: changepassword.php");
        exit;
    }else{
        if($useremail){
            $useremail=base64_decode($useremail);
        }else{
            $_SESSION['error_msg2'] = "<p class='error-msg'>Something went wrong !!</p>";
            header("changepassword.php");
            exit;
        }
        $newpassword = password_hash($_POST["password_confirmation"], PASSWORD_DEFAULT);
        $sql1 = 'SELECT * from adminchk WHERE "adminEmail"=\'' . $useremail . '\'';
        $result = sqlgetresult($sql1);
        if (!empty($result))
        {
            $con = "SELECT * FROM changeadminpassword('$useremail','$newpassword')";
            $result = sqlgetresult($con);
        }else{
            $result = 0;
        }
        
        // print_r($con);
        // exit;
        if ($result = 1)
        {
            $_SESSION['success_msg2'] = "<p class='success-msg'>Password Changed Successfully</p>";
            header("Location:login.php");
            exit;
        }
        else
        {
            createErrorlog($result);
            $_SESSION['error_msg2'] = "<p class='error-msg'>Reset Password is Invalid !!</p>";
            header("changepassword.php");
            exit;
        }
    }
}
if ((isset($_POST['changepass']) && $_POST['changepass'] == 'change'))
{
    $useremail = $_SESSION['myadmin']['adminemail'];
    $newpassword = password_hash($_POST["password_confirmation"], PASSWORD_DEFAULT);
    if ($_SESSION['sessLoginType'] == 'Admin')
    {
        $con = "SELECT * FROM changeadminpassword('$useremail','$newpassword')";
        $result = sqlgetresult($con);

    }

    else
    {
        $con = "SELECT * FROM changeteacherpassword('$useremail','$newpassword')";
        $result = sqlgetresult($con);

    }
    // print_r($con);
    // exit;
    if ($result = 1)
    {
        $_SESSION['success_msg2'] = "<p class='success-msg'>Password Changed Successfully</p>";
        header("Location:login.php");
    }
    else
    {
        createErrorlog($result);
        $_SESSION['error_msg2'] = "<p class='error-msg'>Reset Password is Invalid !!</p>";
        header("changepassword.php");
    }
}

/*****Transport Table - Start*****/
if (isset($_POST["edittransport"]) && $_POST["edittransport"] == "update")
{
    $id = $_POST['id'];
    $uid = $_SESSION['myadmin']['adminid'];
    $ppoint = $_POST['ppoint'];
    $dpoint = $_POST['dpoint'];
    $stage = $_POST['stage'];
    $amount = $_POST['amt'];
    $query = "SELECT * FROM edittransport('$id','$ppoint','$dpoint','$uid','$stage','$amount')";
    $run = sqlgetresult($query);

    if ($run['edittransport'] == 1)
    {
        $_SESSION['successtransport'] = "<p class='success-msg'>Data Edited Successfully.</p>";
        header('location:managetransport.php');
    }
    else if ($run['edittransport'] == 0)
    {
        $_SESSION['errortransport'] = "<p class='error-msg'>Route Already Exixt</p>";
        header('location:managetransport.php');
    }
    else
    {
        createErrorlog($run);
        $_SESSION['errortransport'] = "<p class='error-msg'>Some error has been occured. Please try again.</p>";
        header('location:managetransport.php');
    }
}

if (isset($_POST["addtransport"]) && $_POST["addtransport"] == "new")
{
    $uid = $_SESSION['myadmin']['adminid'];
    $ppoint = $_POST['ppoint'];
    $dpoint = $_POST['dpoint'];
    $stage = $_POST['stage'];
    $amount = $_POST['amt'];
    $query = "SELECT * FROM addtransport('$ppoint','$dpoint','$uid','$stage','$amount')";
    // print_r($query);
    $run = sqlgetresult($query);

    // print_r($run);
    // exit;
    if ($run['addtransport'] == 1)
    {
        $_SESSION['successtransport'] = "<p class='success-msg'>Data Added Successfully.</p>";
        header('location:managetransport.php');
    }
    else if ($run['addtransport'] == 0)
    {
        $_SESSION['errortransport'] = "<p class='error-msg'>Route already exist.</p>";
        header('location:managetransport.php');
    }
    else
    {
        createErrorlog($run);
        $_SESSION['errortransport'] = "<p class='error-msg'>Some error has been occured. Please try again.</p>";
        header('location:managetransport.php');
    }
}
/*****Transport Table - End*****/

// Edit Fee Configuration - Start
if (isset($_POST['checkfeeconfig']) && $_POST['checkfeeconfig'] == 'Add Data')
{
    $class = array();
    $academic_yr = explode('-',getAcademicyrById($_POST['academic'])) ;
    // print_r($academic_yr);
    if ($_POST['semester'] == 'II')
    {
        $feedata = sqlgetresult('SELECT f.class,f.amount,c."displayOrder" FROM tbl_fee_configuration f LEFT JOIN tbl_class c ON f."class" = c."id" WHERE f."stream" = \'' . $_POST['stream'] . '\' AND f."semester" = \'' . $_POST['semester'] . '\' AND f."feeType" = \'' . $_POST['feetype'] . '\' AND f."academicYear" = \'' . ($_POST['academic']) . '\' ORDER BY c."displayOrder" ASC ', true);
        if (!empty($feedata) == 0)
        {
            $feedata = sqlgetresult('SELECT f.class,f.amount, c."displayOrder" FROM tbl_fee_configuration f LEFT JOIN tbl_class c WHERE f.stream = \'' . $_POST['stream'] . '\' AND f.semester = \'I\'  AND f."feeType" = \'' . $_POST['feetype'] . '\' AND f."academicYear" = \'' . ($_POST['academic']) . '\' ORDER BY c."displayOrder" ASC ', true);
        }
    }
    // else if ($academic_yr[0] == (date("Y") + 1))
    // {
    //     $yr = sqlgetresult("select max(id) from tbl_academic_year");
    //     $yr = $yr['max']-1;

    //     $feedata = sqlgetresult('SELECT f.class,f.amount,c."displayOrder" FROM tbl_fee_configuration f LEFT JOIN tbl_class c ON f."class" = c."id" WHERE f."stream" = \'' . $_POST['stream'] . '\' AND f."semester" = \'' . $_POST['semester'] . '\' AND f."feeType" = \'' . $_POST['feetype'] . '\' AND f."academicYear" = \'' . ($yr) . '\' ORDER BY c."displayOrder" ASC ', true);
    // }
    else
    {
        $feedata = sqlgetresult('SELECT f.class,f.amount,c."displayOrder" FROM tbl_fee_configuration f LEFT JOIN tbl_class c ON f."class" = c."id" WHERE f."stream" = \'' . $_POST['stream'] . '\' AND f."semester" = \'' . $_POST['semester'] . '\' AND f."feeType" = \'' . $_POST['feetype'] . '\' AND f."academicYear" = \'' . ($_POST['academic']) . '\' ORDER BY c."displayOrder" ASC', true);
    }

    $classdetails = sqlgetresult('SELECT DISTINCT c."displayOrder",c."id",c."class_list" from tbl_student s LEFT JOIN tbl_class c ON s."class"::int = c."id" WHERE s."stream" = \'' . $_POST['stream'] . '\' AND c."class_list" IS NOT NULL ORDER BY c."displayOrder" ASC ',true);

    $feeClass = array_column($feedata, 'class');
    $classData = array_column($classdetails, 'id');

    $diff = array_diff($classData,$feeClass);

    $diffData = array();

    foreach ($diff as $k => $v) {
        $diffData[$k]['class'] = $v;
        $diffData[$k]['amount'] = '';
        $diffData[$k]['displayOrder'] = getDisplayOrderById($v);
    }
    
    $feedata =  array_merge($feedata,$diffData);

    array_multisort(array_column($feedata, 'displayOrder'), SORT_ASC, $feedata);
    // print_r($feedata);echo "<hr/>";
    // print_r($classdetails);echo "<hr/>";
    // print_r($diffData);
    // exit;

    $class['feeData'] = $feedata;
    $class['classdetails'] = $classdetails;
    echo json_encode($class);
}

// Edit Fee Configuration - End
/*******Fee Waiver Section -Start*****/
/*if (isset($_POST['submit']) && $_POST['submit'] == "getwavierchallanno")
{
    $groupdata = array();
    $feegrouparray = array();
    $feegrouparrayunique = array();
    $challan = $_POST['data'];
    $query = 'SELECT "feeGroup" FROM tbl_challans WHERE "challanNo"=\'' . $challan . '\' AND "challanStatus"=\'' . 0 . '\'';
    $res = sqlgetresult($query,true);
    // print_r($res);
    $feegrouparray = array();
    $feegroupunique =array();
    foreach ($res as $result)
    {
        array_push($feegrouparray, getFeeGroupbyId($result['feeGroup']));
    }
    $feegroupunique = array_unique($feegrouparray);
    echo json_encode($feegroupunique);
}

if (isset($_POST['submit']) && $_POST['submit'] == "getgroupamount")
{
    $wavierchallan = $_POST['cno'];
    $waviergroup = $_POST['gt'];

    $total = sqlgetresult('SELECT SUM("org_total") AS org_total FROM tbl_challans WHERE "challanNo"=\'' . $wavierchallan . '\' AND "feeGroup"=\'' . getFeeGroupbyName($waviergroup) . '\'');
     
    echo json_encode($total['org_total']);
}*/
if (isset($_POST['submit']) && $_POST['submit'] == "getwavierchallanno")
{
    $groupdata = array();
    $feegrouparray = array();
    $feegrouparrayunique = array();

    $data = isset($_POST['data'])?trim($_POST['data']):"";
    //$datalist=explode(",",$data);
    $datalist=str_replace(",","','",$data);
    $feegrouparray = array();
    $feegroupunique =array();
    
    $challan = $datalist;
    $query = 'SELECT "feeGroup" FROM tbl_challans WHERE "challanNo" IN (\'' . $challan . '\') AND ("challanStatus"=\'' . 0 . '\' OR "challanStatus"=\'' . 2 . '\') AND deleted=0';
    $res = sqlgetresult($query,true);
    // print_r($res);

    foreach ($res as $result)
    {
        array_push($feegrouparray, getFeeGroupbyId($result['feeGroup']));
    }
    $feegroupunique = array_unique($feegrouparray);
    echo json_encode($feegroupunique);
}

if (isset($_POST['submit']) && $_POST['submit'] == "getgroupamount")
{
    $cno = isset($_POST['cno'])?trim($_POST['cno']):"";
    //$datalist=explode(",",$data);
    $wavierchallan=str_replace(",","','",$cno);

    $waviergroup = $_POST['gt'];
    $total = sqlgetresult('SELECT SUM("org_total") AS org_total FROM tbl_challans WHERE "challanNo" IN (\'' . $wavierchallan . '\') AND "feeGroup"=\'' . getFeeGroupbyName($waviergroup) . '\' AND deleted=0 GROUP BY "challanNo" ORDER BY "org_total" DESC');
    if(isset($total[0]['org_total'])){
        echo json_encode($total[0]['org_total']);
    }else{
        echo json_encode($total['org_total']);
    } 
}

if (isset($_POST['addstudent']) && $_POST['addstudent'] == "Update Amount")
{

    $uid = $_SESSION['myadmin']['adminid'];

    $id = isset($_POST['id'])?trim($_POST['id']):"";
    $waviergroup = getFeeGroupbyName($_POST['grouptype']);
    $wavingamount = isset($_POST['WavingAmount'])?trim($_POST['WavingAmount']):"";
    $wavingpercentage = isset($_POST['WavingPercentage'])?trim($_POST['WavingPercentage']):"";
    $waivingtypeid = isset($_POST['waivertype'])?trim($_POST['waivertype']):"";
    $waivingtype="";
    if(!empty($waivingtypeid)){
      $waivingtype=getWaiverTypebyId($waivingtypeid);
      //exit;
    }
    
    $rid = isset($_POST['rid'])?trim($_POST['rid']):"";
    $remarks = isset($_POST['remarks'])?trim($_POST['remarks']):"";

    $chNumbers=explode(",",$id); 
    foreach ($chNumbers as $challannumber) {
        $wavierchallan = $challannumber;        
    
        $total = sqlgetresult('SELECT SUM("org_total") AS "org_total" FROM tbl_challans WHERE "challanNo"=\'' . $wavierchallan . '\' AND "feeGroup"=\'' . $waviergroup . '\' AND ("challanStatus"=\'' . 0 . '\' OR "challanStatus"=\'' . 2 . '\') AND deleted=0');

        $studentIdarray = sqlgetresult('SELECT "studentId" FROM tbl_challans WHERE "challanNo"=\'' . $wavierchallan . '\' AND "feeGroup"=\'' . $waviergroup . '\'',true);
        // print_r($sid);
        $studentId = $studentIdarray[0]['studentId'];
        $newtotal = $total['org_total'];
        if($wavingpercentage != ""){
            $discountamount = ($total['org_total'] * $wavingpercentage / 100);
            if ($discountamount == $wavingamount) {
                $amountwaving = 0;
            } else {
                $amountwaving = $wavingamount - $discountamount;
                // $newtotal = $total['org_total'] - $wavingamount;
            }
        } else{
            $amountwaving = 0;
            $wavingpercentage = 0;
            // $newtotal = $total['org_total'] - $wavingamount;
        }

        $sql = "SELECT * FROM updatewavingamountnew_alt('$wavierchallan','$newtotal','$uid','$waviergroup','$wavingpercentage','$amountwaving','$wavingamount','$studentId','$waivingtype','$remarks','$waivingtypeid')";
        // print_R($sql);exit;
        $res = sqlgetresult($sql);
        $fromwhere = 'Waiver';
        flattableentry($wavierchallan, $studentId, $fromwhere);
        $sid = sqlgetresult('SELECT * FROM waviercheck WHERE "challanNo"=\'' . $wavierchallan . '\' AND "feeGroup" = \'' . $waviergroup . '\'');
        $getparentmailid = sqlgetresult('SELECT p."userName", p."email" AS mail1 , p."mobileNumber" AS mbl1 , p."phoneNumber" AS mbl2 , p."secondaryEmail" AS mail2 FROM tbl_student s LEFT JOIN tbl_parents p ON s."parentId" = p."id" WHERE s."studentId" =\'' . $sid[0]['studentId'] . '\'');

        if ($res['updatewavingamountnew_alt'] == 0) {
            $_SESSION['errorwavier'] = "<p class='error-msg'>Some Error Has Occured</p>";
        } else {
            $_SESSION['successwavier'] = "<p class='success-msg'>Waving Amount has been Credited</p>";
            $feegroup = getFeeGroupbyId($sid[0]['feeGroup']);        

            $to = $getparentmailid['userName'];
            $getparentmailid = sqlgetresult('SELECT p."userName", p."email" AS mail1 , p."mobileNumber" AS mbl1 , p."phoneNumber" AS mbl2 , p."secondaryEmail" AS mail2 FROM tbl_student s LEFT JOIN tbl_parents p ON s."parentId" = p."id" WHERE s."studentId" =\'' . $sid[0]['studentId'] . '\'');
            
            $challanData = sqlgetresult('SELECT * FROM challanData WHERE "studentId" =\'' . $sid[0]['studentId'] . '\' AND  "challanNo" = \'' . $wavierchallan . '\' ');

            $msg = '<p>Please note that the challan has been updated for ' . $challanData[0]['studentId'] . ': ' . $challanData[0]['studentName'] . '.</p>';

            
            $mailbody = $msg;
            $studentId = trim($challanData[0]['studentId']);
            $type = "Waived Challan";

            $smsbody = "Dear Parent, Your child's challan has been waived. Please logon our feeapp to see more details.";
            //sendNotificationToParents($studentId, $mailbody, $smsbody, $type);
        }
    }
    header("Location:managefeewavier.php");
}

/*******Fee Waiver Section -End*****/

/*******Filters - Start*****/

if (isset($_POST['filter']) && $_POST['filter'] == "filterstudent")
{
    $classselect = isset($_POST['classselect'])?trim($_POST['classselect']):"";
    $streamselect = isset($_POST['streamselect'])?trim($_POST['streamselect']):"";
    $sectionselect = isset($_POST['sectionselect'])?trim($_POST['sectionselect']):"";
    $status = isset($_POST['status'])?trim($_POST['status']):"";

    $whereClauses=[];
    if (!empty($streamselect))
    {
        $whereClauses[]='"stream"=\'' . $streamselect . '\' ';

    }
    if (!empty($classselect))
    {
        $whereClauses[]='"class"=\'' . $classselect . '\' ';

    }
    if (!empty($sectionselect))
    {
        $whereClauses[]='"section"=\'' . $sectionselect . '\' ';

    }
    if (!empty($status))
    {
        $whereClauses[]='"status"=\'' . $status . '\' ';

    }
    $where ="";
    if (count($whereClauses) > 0) 
    { 
      $where = ' WHERE '.implode(' AND ',$whereClauses); 
    }  
    $sql = ('SELECT * FROM filterstudentdetails '.$where);
    $res = sqlgetresult($sql, true);
    echo json_encode($res);

}


if (isset($_POST['filter']) && $_POST['filter'] == "filterfeeconfiguration")
{

    $classselect = $_POST['classselect'];
    $streamselect = @$_POST['streamselect'];

    if ($streamselect != '' && $classselect == '')
    {

        $sql = 'SELECT * FROM filterfeedetails WHERE "strid"=\'' . $streamselect . '\' ';

    }
    else if ($classselect != '' && $streamselect == '')
    {

        $sql = 'SELECT * FROM filterfeedetails WHERE "clid"=\'' . $classselect . '\' ';

    }
    else if ($classselect != '' && $streamselect != '')
    {
        $sql = ('SELECT * FROM filterfeedetails WHERE "clid"=\'' . $classselect . '\' AND "strid" = \'' . $streamselect . '\'');

    } else {
       $sql = ('SELECT * FROM filterfeedetails '); 
    }

    // echo $sql;
    // exit;
    $res = sqlgetresult($sql, true);

    echo json_encode($res);

}
/*******Filters - End*****/

if (isset($_POST['filter']) && $_POST['filter'] == "filterteacher")
{

    $classselect = $_POST['classselect'];
    $sectionselect = $_POST['sectionselect'];
    $stream = $_POST['stream'];

    $whereClauses = array(); 
     if (! empty($_POST['classselect'])) 
      $whereClauses[] ="s.class='".pg_escape_string ($_POST['classselect'])."'"; 
    $where = ''; 

    if (! empty($_POST['stream'])) 
      $whereClauses[] ="s.stream='".pg_escape_string (trim($_POST['stream']))."'"; 
    $where = ''; 

    if (! empty($_POST['sectionselect'])) 
      $whereClauses[] ="s.section='".pg_escape_string ($_POST['sectionselect'])."'"; 
    $where = '';   

    if (count($whereClauses) > 0) 
    { 
      $where = implode(' AND ',$whereClauses); 
    }   

    $currentacademicyear = getCurrentAcademicYear();
    $currentterm = getCurrentTerm();

    $sql = ('SELECT *,st.stream, c.class_list FROM tbl_student s LEFT JOIN tbl_stream st ON s.stream::int = st.id  
            LEFT JOIN tbl_class c ON c.id = s.class::int WHERE s."studentId" NOT IN (SELECT t."studentId" FROM tbl_temp_challans t WHERE t."academicYear" = \''.$currentacademicyear.'\' AND t.term = \''.$currentterm.'\' ) AND s."status" = \'1\' AND s."deleted" = \'0\' AND '. $where);    

    // echo $sql;
    $res = sqlgetresult($sql, true);

    if(count($res) > 0) {
        foreach ($res as $data) {
            $data['term'] = $current_term;
        }
    } else {
        $res = 'no data';
    }

    echo json_encode($res);

}
/*******Filters - Start*****/

if (isset($_POST['filter']) && $_POST['filter'] == "filterbut")
{
    $_POST = array_map('trim',$_POST);
    $classselect = $_POST['classselect'] != '' ? getClassbyNameId($_POST['classselect']) : $_POST['classselect'];
    $streamselect = $_POST['streamselect'] != '' ? getStreambyId($_POST['streamselect']) : $_POST['streamselect'];
    $sectionselect = $_POST['sectionselect'];
    $yearselect = @$_POST['yearselect'];
    $semesterselect = @$_POST['semesterselect'];

    $whereClauses = array(); 

    if (! empty($yearselect)) 
        $whereClauses[] ='"academicYear"=\''.pg_escape_string($yearselect).'\' ' ;
    $where='';

    if (! empty($semesterselect)) 
      $whereClauses[] ="term='".pg_escape_string ($semesterselect)."'"; 
    $where = '';

    if (! empty($classselect)) 
        $whereClauses[] ='"class_list"=\''.pg_escape_string($classselect).'\' ' ;
    $where='';

    if (! empty($streamselect)) 
      $whereClauses[] ="stream='".pg_escape_string ($streamselect)."'"; 
    $where = ''; 

    if (! empty($sectionselect)) 
      $whereClauses[] ="section='".pg_escape_string ($sectionselect)."'"; 
    $where = ''; 

    if (count($whereClauses) > 0) 
    { 
      $where = ' WHERE '.implode(' AND ',$whereClauses); 
    }   

    $sql = ('SELECT * FROM  waviercheck '. $where. 'AND ("challanStatus" = \'' . 0 . '\' OR "challanStatus" = \'' . 2 . '\')');
    $res = sqlgetresult($sql, true);

    $challanData = array();
    $total = 0;
    $tot = 0;
    $orgtotal = 0;
    $challanNo = '';
    $feeData = array();
    $outputdata = array();
    $waivedresult = array();

    if ($res != 0)
    {
        foreach ($res as $data)
        {
            if($data['studentName'] == '') {
                $challanData[$data['challanNo']]['studentName'] = $data['app_name'];
            } else {
                $challanData[$data['challanNo']]['studentName'] = $data['studentName'];
            }
            
            $challanData[$data['challanNo']]['stream'] = $data['stream'];
            $challanData[$data['challanNo']]['waived'] =getwaiveddata($data['challanNo']);
            $challanData[$data['challanNo']]['term'] = $data['term'];
            if($data['section'] == '') {
                $challanData[$data['challanNo']]['section'] = $data['asec'];
            } else {
                $challanData[$data['challanNo']]['section'] = $data['section'];
            }
            // $challanData[$data['challanNo']]['section'] = $data['section'];
            $challanData[$data['challanNo']]['studentId'] = $data['studentId'];
            $challanData[$data['challanNo']]['challanNo'] = $data['challanNo'];
            $challanData[$data['challanNo']]['feeType'][] = trim($data['feeType']);
            $challanData[$data['challanNo']]['class_list'] = $data['class_list'];
            $challanData[$data['challanNo']]['org_total'][] = $data['org_total'];
            $challanData[$data['challanNo']]['total'][] = $data['total'];
            $waiveddata = $challanData[$data['challanNo']]['waived'];
            $orgtotal = $challanData[$data['challanNo']]['org_total'];
            $totalcoloumn = $challanData[$data['challanNo']]['total'];
            $challanNo = $data['challanNo'];
            $waivertotal = 0;
            $waivergroup = 'Null';
            $waiverorgtotal = 0;
            $waiverdiff = 0;

            if($waiveddata != '0'){

                if(isset($waiveddata[0]['oldwaiver']) && trim($waiveddata[0]['oldwaiver']) == 1){
                        $waivertotal = array_sum($waiveddata[0]['waiver_total_sum']);
                        $waivergroup = implode(',',$waiveddata[0]['waivedgroups']);
                        $waiverorgtotal = array_sum($orgtotal);
                        $waiverdiff = $waiverorgtotal;
 
                } else {
                    $waivedAmount = array();
                    $waivedgrps = array();
                    foreach ($waiveddata as $waived)
                    {
                        $waivedAmount[] += $waived['waiver_total'];
                        $waivedgrps[] = getFeeGroupbyId($waived['feeGroup']);
                        $waivertotal = array_sum($waivedAmount);
                        $waivergroup = implode(',',$waivedgrps);
                        $waiverorgtotal = array_sum($orgtotal);
                        $waiverdiff = $waiverorgtotal - $waivertotal;
                    }
                }
            }
            $challanData[$challanNo]['waiver_total'] = $waivertotal;
            $challanData[$challanNo]['waiver_group'] =  $waivergroup;
            $challanData[$challanNo]['waiver_org_total'] = array_sum($totalcoloumn);
            $challanData[$challanNo]['waiverdiff'] = $waiverdiff;
        }

        foreach($challanData AS $challan){
            $waivedresult[]= $challan;
        }

        $outputdata = $waivedresult;
    }
    else
    {
        $outputdata = array();
    }
    echo json_encode($outputdata);
}

// ***********PAYMENTREPORT***********
if (isset($_POST['filter']) && $_POST['filter'] == "filterpayment")
{

    $classselect = $_POST['classselect'];
    $streamselect = $_POST['streamselect'];
    $sectionselect = $_POST['sectionselect'];

    if ($streamselect != '' && $classselect == '' && $sectionselect == '')
    {
        $sql = 'SELECT * FROM getpaiddatafilter WHERE "stream"=\'' . $streamselect . '\' ';

    }
    else if ($classselect != '' && $streamselect == '' && $sectionselect == '')
    {
        $sql = 'SELECT * FROM  getpaiddatafilter WHERE "class"=\'' . $classselect . '\' ';

    }
    else if ($sectionselect != '' && $classselect == '' && $streamselect == '')
    {
        $sql = 'SELECT * FROM  getpaiddatafilter WHERE "section"=\'' . $sectionselect . '\' ';
    }

    else if ($classselect != '' && $streamselect != '' && $sectionselect == '')
    {
        $sql = 'SELECT * FROM getpaiddatafilter WHERE "class"=\'' . $classselect . '\' AND "stream" = \'' . $streamselect . '\'';

    }
    else if ($classselect != '' && $streamselect == '' && $sectionselect != '')
    {
        $sql = 'SELECT * FROM  getpaiddatafilter WHERE "class"=\'' . $classselect . '\' AND "section" = \'' . $sectionselect . '\'';

    }
    else if ($classselect != '' && $streamselect != '' && $sectionselect != '')
    {
        $sql = ('SELECT * FROM  getpaiddatafilter WHERE "class"=\'' . $classselect . '\' AND "stream" =\'' . $streamselect . '\' AND "section" = \'' . $sectionselect . '\'');

    } else {
         $sql = ('SELECT * FROM  getpaiddatafilter');

    }

    $res = sqlgetresult($sql, true);

    echo json_encode($res);

}
// ************FEE ENTRY REPORT***********
if (isset($_POST['filter']) && $_POST['filter'] == "feeentry")
{

    $classselect = $_POST['classselect'];
    $streamselect = $_POST['streamselect'];
    $sectionselect = $_POST['sectionselect'];
    // print_r($_POST);
    $res = array();
    if ($streamselect != '' && $classselect == '' && $sectionselect == '')
    {
        $sql = 'SELECT * FROM getpaymentdata WHERE "stream"=\'' . $streamselect . '\' ';

    }
    else if ($classselect != '' && $streamselect == '' && $sectionselect == '')
    {
        $sql = 'SELECT * FROM  getpaymentdata WHERE "class"=\'' . $classselect . '\' ';

    }
    else if ($sectionselect != '' && $classselect == '' && $streamselect == '')
    {
        $sql = 'SELECT * FROM  getpaymentdata WHERE "section"=\'' . $sectionselect . '\' ';

    }
    else if ($classselect != '' && $streamselect != '' && $sectionselect == '')
    {
        $sql = 'SELECT * FROM getpaymentdata WHERE "class"=\'' . $classselect . '\' AND "stream" = \'' . $streamselect . '\'';

    }
    else if ($classselect != '' && $streamselect == '' && $sectionselect != '')
    {
        $sql = 'SELECT * FROM  getpaymentdata WHERE "class"=\'' . $classselect . '\' AND "section" = \'' . $sectionselect . '\'';
    }

    else if ($classselect != '' && $streamselect != '' && $sectionselect != '')
    {
        $sql = ('SELECT * FROM  getpaymentdata WHERE "class"=\'' . $classselect . '\' AND "stream" =\'' . $streamselect . '\' AND "section" = \'' . $sectionselect . '\'');

    } else {
        $sql = ('SELECT * FROM  getpaymentdata ');
    }
    $res = sqlgetresult($sql, true);
    // if($res > 0){
    echo json_encode($res);
    // } else {
    //     echo json_encode("null");
    // }
    // echo $sql;
    

    
}
// *************FEE ENTRY REPORT END************
//*************TEMP CHALLAN************//
if (isset($_POST['filter']) && $_POST['filter'] == "filterchallan")
{
    $studdatatype = $_POST['studdatatype'];

    $whereClauses = array(); 
    if (! empty($_POST['classselect'])) 
        $whereClauses[] ='"classList"=\''.pg_escape_string($_POST['classselect']).'\' ' ;
    $where='';

    if (! empty($_POST['streamselect'])) 
      $whereClauses[] ="stream='".pg_escape_string ($_POST['streamselect'])."'"; 
    $where = ''; 

    if (! empty($_POST['sectionselect'])) 
      $whereClauses[] ="section='".pg_escape_string ($_POST['sectionselect'])."'"; 
    $where = ''; 

    if (! empty($_POST['studtype'])) 
      $whereClauses[] ="hostel_need='".pg_escape_string ($_POST['studtype'])."'"; 
    $where = ''; 

    if (! empty($_POST['ttype'])) 
      $whereClauses[] ="transport_stg='".pg_escape_string ($_POST['ttype'])."'"; 
    $where = ''; 

    if (count($whereClauses) > 0) 
    { 
      $where = ' WHERE '.implode(' AND ',$whereClauses); 
    }   

    $sql = ('SELECT * FROM  gettempdatanew'. $where);

    // print_r($sql);
    $res = sqlgetresult($sql, true);

    $filteredData = array();

    if (!empty($res) > 0)
    {
        foreach ($res as $data)
        {
            // echo $data['studentId'];
            if ($studdatatype != '') {
                if ($studdatatype == 'N' && stripos($data['studentId'], '2018') !== false) {
                    $filteredData[] = $data;
                } else if ($studdatatype == 'E' && stripos($data['studentId'], '2018') === false) {
                    $filteredData[] = $data;
                }
            } else {
                $filteredData[] = $data;
            }
            // $data['term'] = $current_term;
        }
    }
    else{
        $filteredData = null;
    }

    echo json_encode($filteredData);

}
// **********TEMP CHALLAN END************//


//*************PAID CHALLAN FILTER- Start************//
if (isset($_POST['filter']) && $_POST['filter'] == "filterpaidchallan")
{
    $_POST = array_map('trim',$_POST);
    $classselect = $_POST['classselect'];
    $streamselect = @$_POST['streamselect'];
    $sectionselect = @$_POST['sectionselect'];
    $yearselect = @$_POST['yearselect'];
    $semesterselect = @$_POST['semesterselect'];


    $whereClauses = array(); 

    if (! empty($yearselect)) 
        $whereClauses[] ='"academicYear"=\''.pg_escape_string($yearselect).'\' ' ;
    $where='';

    if (! empty($semesterselect)) 
      $whereClauses[] ="term='".pg_escape_string ($semesterselect)."'"; 
    $where = '';

    if (! empty($classselect)) 
        $whereClauses[] ='"classList"=\''.pg_escape_string($classselect).'\' ' ;
    $where='';


    if (! empty($streamselect)) 
      $whereClauses[] ="stream='".pg_escape_string ($streamselect)."'"; 
    $where = ''; 

    if (! empty($sectionselect)) 
      $whereClauses[] ="section='".pg_escape_string ($sectionselect)."'"; 
    $where = ''; 

    if (count($whereClauses) > 0) 
    { 
      $where = ' WHERE '.implode(' AND ',$whereClauses); 
    }   

    $sql = ('SELECT * FROM  getchallandatanew '. $where. 'AND ("challanStatus" = 1 OR "challanStatus" = 3) AND deleted=\'0\'');
    $res = sqlgetresult($sql, true);
    $challanData = array();
    $total = 0;
    $tot = 0;
    $challanNo = '';
    $feeData = array();
    $outputdata = array();
    $paidchallan = array();
    if ($res != 0)
    {
        foreach ($res as $k => $data)
        {
            
            $challanData[$data['challanNo']]['studentId'] = $data['studentId'];
            $challanData[$data['challanNo']]['challanNo'] = $data['challanNo'];
            $challanData[$data['challanNo']]['studentName'] = $data['studentName'];
            $challanData[$data['challanNo']]['streamname'] = $data['streamname'];
            $challanData[$data['challanNo']]['class_list'] = $data['class_list'];
            $challanData[$data['challanNo']]['section'] = $data['section'];
            $challanData[$data['challanNo']]['term'] = $data['term'];
            $challanData[$data['challanNo']]['createdOn'] = date("d-m-Y", strtotime($data['createdOn']));
            $challanData[$data['challanNo']]['duedate'] = date("d-m-Y", strtotime($data['duedate']));

            $challanData[$data['challanNo']]['feeTypes'][] = trim($data['feeType']);
            $challanData[$data['challanNo']]['waived'] =getwaiveddata($data['challanNo']);
            $challanData[$data['challanNo']]['feeGroup'] = $data['feeGroup'];
            $challanData[$data['challanNo']]['org_total'][] = $data['org_total'];
            $challanData[$data['challanNo']]['pay_type'] = $data['pay_type'];
            $waiveddata = $challanData[$data['challanNo']]['waived'];
            $orgtotal = $challanData[$data['challanNo']]['org_total'];
            $challanNo = $data['challanNo'];
            $waivertotal = 0;
            $waiverorgtotal = 0;

            if($waiveddata != '0'){
                if(isset($waiveddata[0]['oldwaiver']) && $waiveddata[0]['oldwaiver'] == 1){
                    $waivertotal = 0;
                    $waiverorgtotal = array_sum($orgtotal);

                } else {
                    $waivedAmount = array();
                    $waivedgrps = array();
                    foreach ($waiveddata as $waived)
                    {
                        $waivedAmount[] += $waived['waiver_total'];
                        $waivertotal = array_sum($waivedAmount);
                        $waiverorgtotal = array_sum($orgtotal);
                    }
                }
            }
            else{
                    $waivertotal = 0;
                    $waiverorgtotal = array_sum($orgtotal);
            }
            $challanData[$challanNo]['waiver_total'] = $waivertotal;
            $challanData[$challanNo]['waiver_org_total'] = $waiverorgtotal;

        }
        foreach($challanData AS $challan){
            $paidchallan[]= $challan;
        }

        $outputdata = $paidchallan;
    }
    else
    {
        $outputdata = array();
    }
    echo json_encode($outputdata);

}
/*******PAID CHALLAN FILTER - End*****/
//*************CREATED CHALLAN FILTER-  Start************//
if (isset($_POST['filter']) && $_POST['filter'] == "filtercreatedchallan")
{

    $_POST = array_map('trim',$_POST);
    $classselect = $_POST['classselect'];
    $streamselect = @$_POST['streamselect'];
    $sectionselect = @$_POST['sectionselect'];
    $yearselect = @$_POST['yearselect'];
    $semesterselect = @$_POST['semesterselect'];
    $challanstatus = isset($_POST['challanstatus'])?$_POST['challanstatus']:"";

   $whereClauses = array(); 
   if (! empty($yearselect)) 
        $whereClauses[] ='"academicYear"=\''.pg_escape_string($yearselect).'\' ' ;
    $where='';

    if (! empty($semesterselect)) 
      $whereClauses[] ="term='".pg_escape_string ($semesterselect)."'"; 
    $where = '';

    if (! empty($classselect)) 
        $whereClauses[] ='"classList"=\''.pg_escape_string($classselect).'\' ' ;
    $where='';

    if (! empty($streamselect)) 
      $whereClauses[] ="stream='".pg_escape_string ($streamselect)."'"; 
    $where = ''; 

    if (! empty($sectionselect)) 
      $whereClauses[] ="section='".pg_escape_string ($sectionselect)."'"; 
    $where = ''; 

    if (! empty($challanstatus)){
        if (!empty($challanstatus))
        {
            if($challanstatus=='3'){
                $status=1;
                $dalete = 0;
            }else{
                $status=0;
                $dalete = 1;
            }
            $whereClauses[] ='status=\''.pg_escape_string($status).'\' AND deleted=\''.pg_escape_string($dalete).'\' ';
        }
    }

    if (count($whereClauses) > 0) 
    { 
      $where = ' WHERE '.implode(' AND ',$whereClauses); 
    }   
    $studentid = "3576";
    $challanno = 'CBSE2018/010583';
    //$sql = ('SELECT * FROM  getchallandatanew '. $where. 'AND "challanStatus" = \'' . 0 . '\'');
    //$sql = ('SELECT * FROM  getchallandatanew '. $where. 'AND ("challanStatus" = \'0\' OR "challanStatus" = \'2\') AND deleted=\'0\' ');
    $sql = ('SELECT * FROM  getchallandatanew '. $where. 'AND ("challanStatus" = \'0\' OR "challanStatus" = \'2\') ');
    $res = sqlgetresult($sql, true);
    $challanData = array();
    $total = 0;
    $tot = 0;
    $challanNo = '';
    $feeData = array();
    $outputdata = array();
    $createdchallan = array();
    if ($res != 0)
    {
        foreach ($res as $k => $data)
        {
            if(isset($data['visibleStatus'])){
                $vstatus=trim($data['visibleStatus']);
            }else{
               $vstatus=1; 
            }
            $challanData[$data['challanNo']]['studentId'] = $data['studentId'];
            $challanData[$data['challanNo']]['challanNo'] = $data['challanNo'];
            $challanData[$data['challanNo']]['studentName'] = $data['studentName'];
            $challanData[$data['challanNo']]['streamname'] = $data['streamname'];
            $challanData[$data['challanNo']]['class_list'] = $data['class_list'];
            $challanData[$data['challanNo']]['section'] = $data['section'];
            $challanData[$data['challanNo']]['term'] = $data['term'];
            $challanData[$data['challanNo']]['createdOn'] = date("d-m-Y", strtotime($data['createdOn']));
            $challanData[$data['challanNo']]['duedate'] = date("d-m-Y", strtotime($data['duedate']));

            //$challanData[$data['challanNo']]['waived'] =getwaiveddata($data['challanNo'], $data['challanStatus']);
            $challanData[$data['challanNo']]['waived'] =getwaiveddata($data['challanNo']);
            $challanData[$data['challanNo']]['feeTypes'][] = trim($data['feeType']);
            $challanData[$data['challanNo']]['feeGroup'] = $data['feeGroup'];
            $challanData[$data['challanNo']]['total'][] = $data['total'];
            $waiveddata = $challanData[$data['challanNo']]['waived'];
            $orgtotal = $challanData[$data['challanNo']]['total'];
            $challanNo = $data['challanNo'];
            $waivertotal = 0;
            $waiverorgtotal = 0;
            if($waiveddata != '0'){
                if(isset($waiveddata[0]['oldwaiver']) && $waiveddata[0]['oldwaiver'] == 1){
                    $waivertotal = 0;
                    $waiverorgtotal = array_sum($orgtotal);

                } else {
                    $waivedAmount = array();
                    $waivedgrps = array();
                    foreach ($waiveddata as $waived)
                    {
                        $waivedAmount[] += $waived['waiver_total'];
                        $waivertotal = array_sum($waivedAmount);
                        $waiverorgtotal = array_sum($orgtotal);
                    }
                }
            }
            else{
                    $waivertotal = 0;
                    $waiverorgtotal = array_sum($orgtotal);
            }
            $challanData[$challanNo]['waiver_total'] = $waivertotal;
            $challanData[$challanNo]['waiver_org_total'] = $waiverorgtotal;
            /*Newly Added*/
            $challanData[$challanNo]['visible'] = $vstatus;
        }
        foreach($challanData AS $challan){
            $createdchallan[]= $challan;
        }

        $outputdata = $createdchallan;
    }
    else
    {
        $outputdata = array();
    }

    echo json_encode($outputdata);

}
/*******CREATED CHALLAN  FILTER- End*****/

/*******Filters - End*****/

// **********TEMP NEW CHALLAN END************//

/*****Tax Table - Start*****/
if (isset($_POST["edityear"]) && $_POST["edityear"] == "update")
{
    if($_POST['currentyear'] == 'on'){
        $activeyear = 1;
        $updateyear = sqlgetresult('UPDATE tbl_academic_year SET "active" = 0');
    }
    else{
        $activeyear = 0;
    }
    $id = $_POST['id'];
    $uid = $_SESSION['myadmin']['adminid'];
    $year = $_POST['year'];
    $query = "SELECT * FROM edityear('$id','$year','$uid','$activeyear')";
    $run = sqlgetresult($query);
    if ($run['edityear'] == 1)
    {

        $_SESSION['successyear'] = "<p class='success-msg'>Data Edited Successfully.</p>";
        header('location:manageyear.php');
    }
    else if ($run['edityear'] == 0)
    {
        $_SESSION['erroryear'] = "<p class='error-msg'>Year Already Exist</p>";
        header('location:manageyear.php');
    }
    else
    {
        createErrorlog($run);
        $_SESSION['erroryear'] = "<p class='error-msg'>Some error has been occured. Please try again.</p>";
        header('location:manageyear.php');
    }
}
if (isset($_POST["addyear"]) && $_POST["addyear"] == "new")
{
    // print_r($_POST);
    // exit;
    if($_POST['currentyear'] == 'on'){
        $activeyear = 1;
        $updateyear = sqlgetresult('UPDATE tbl_academic_year SET "active" = 0');
    }
    else{
        $activeyear = 0;
    }
    $n = 0;
    $uid = $_SESSION['myadmin']['adminid'];
    $year = $_POST['year'];
    $query = "SELECT * FROM addyear('$year','$uid', '$activeyear')";
    $run = sqlgetresult($query);

    if ($run['addyear'] == 1)
    {

        $_SESSION['successyear'] = "<p class='success-msg'>Data Added Successfully.</p>";
        header('location:manageyear.php');
    }
    else if ($run['addyear'] == 0)
    {
        $_SESSION['erroryear'] = "<p class='error-msg'>Year Already Exist</p>";
        header('location:manageyear.php');
    }
    else
    {
        createErrorlog($run);
        $_SESSION['erroryear'] = "<p class='error-msg'>Some error has been occured. Please try again.</p>";
        header('location:manageyear.php');
    }
}
/*****Tax Table - End*****/

if (isset($_GET["actions"]) && $_GET["actions"] == "delete")
{
    $cn = $_GET['id'];
    $getchallandata = sqlgetresult('SELECT * FROM tbl_challans WHERE "challanNo" = \'' . $cn . '\' LIMIT 1');
    
    $query1 = 'DELETE FROM tbl_demand WHERE "challanNo" = \'' . $cn . '\' AND "studentId" = \'' . $getchallandata['studentId'] . '\' AND "academicYear" = \'' . $getchallandata['academicYear'] . '\' AND "term" = \'' . $getchallandata['term'] . '\'';
    $res1 = sqlgetresult($query1);
    
    $query2 = 'DELETE FROM tbl_challans WHERE "challanNo"=\'' . $cn . '\' ';
    $res2 = sqlgetresult($query2);

    $query3 = 'DELETE FROM tbl_temp_challans WHERE "challanNo"=\'' . $cn . '\' ';
    $res3 = sqlgetresult($query3);

    // $query4 = 'DELETE FROM tbl_receipt WHERE "challanNo"=\'' . $cn . '\' ';
    // $res4 = sqlgetresult($query4);

    $getwaiverdata = getwaiveddata(trim($cn));
    $getsfsdata = getsfsdatabychn(trim($cn));

    if($getwaiverdata != 0){
        $query4 = 'DELETE FROM tbl_waiver WHERE "challanNo"=\'' . $cn . '\' AND "studentId" = \'' . $getchallandata['studentId'] . '\' ';
        $res4 = sqlgetresult($query4);
    }
    
    if($getsfsdata != 0){
        $query5 = 'DELETE FROM tbl_sfs_qty WHERE "challanNo"=\'' . $cn . '\' AND "studentId" = \'' . $getchallandata['studentId'] . '\' ';
        $res5 = sqlgetresult($query5);
    }

    $challanstatus = 0;
    $query6 = 'UPDATE tbl_student_ledger SET "challanStatus" = \'' . $challanstatus . '\' WHERE "challanNo"=\'' . $cn . '\' ';
    $res6 = sqlgetresult($query6);

    if (($res1[deleteupdate] == 0) && ($res2[deleteupdate] == 0) && ($res3[deleteupdate] == 0)) {
        $_SESSION['successdelete'] = "<p class='success-msg'>Deleted Successfully</p>";
        header('location:managecreatedchallans.php');
    } else {
        $_SESSION['errordelete'] = "<p class='error-msg'>Deleted Unsuccessful</p>";
        header('location:managecreatedchallans.php');
    }
}

/*****Temp Challan Delete - Start*****/
if (isset($_GET["actions"]) && $_GET["actions"] == "deletetemp")
{
    $cn = $_GET['id'];
    $getchallandata = sqlgetresult('SELECT * FROM tbl_temp_challans WHERE "challanNo" = \'' . $cn . '\' LIMIT 1');

    $query1 = 'DELETE FROM tbl_temp_challans WHERE "challanNo"=\'' . $cn . '\' ';
    $res1 = sqlgetresult($query1);
    
    if (($res1[deleteupdate] == 0)) {
        $_SESSION['successdelete'] = "<p class='success-msg'>Deleted Successfully</p>";
        header('location:managechallans.php');
    } else {
        $_SESSION['errordelete'] = "<p class='error-msg'>Deleted Unsuccessful</p>";
        header('location:managechallans.php');
    }
}
/*****Temp Challan Delete - End*****/

if (isset($_POST["submit"]) && $_POST["submit"] == "changestudStatus")
{
    // print_r($_POST);
    // echo("hi");
    if (!empty($_POST['checkme']))
    {
        $selectedcheck = [];
        foreach ($_POST['checkme'] as $selected)
        {
            // echo $selected."</br>";s
            array_push($selectedcheck, $selected);
        }
        $_SESSION['selectedstudid'] = $selectedcheck;
        header('location:updatestudentsstatus.php');
    }
    echo json_encode($selectedcheck);
}

if (isset($_POST["submit"]) && $_POST["submit"] == "createtempchallan")
{    
    // print_r($_POST);
    if (!empty($_POST['checkme']))
    {
        if($_POST['cidd'] != ''){
            $scid = explode(',',$_POST['cidd']);
            $scid = array_unique($scid);

            foreach ($scid as $value) {
                $sid[] = explode('-',$value)[0];
                $cid[] = explode('-',$value)[1];
            }
        } else {
            $sid = $_POST['checkme'];
            $cid = $_POST['cid'];
        }
        
        $selectedstudents = [];
        $selectedchallans = [];
        foreach ($sid as $selected) {
            // echo $selected."</br>";s
            array_push($selectedstudents, $selected);
        }
        foreach ($cid as $challanNo) {
            array_push($selectedchallans, $challanNo);
        } 

        $_SESSION['selectedchallans'] = $selectedstudents;
        $_SESSION['selectedchallanNos'] = $selectedchallans;
        // print_r($_SESSION);exit;
        header('location:editStudent.php');
    }
    echo json_encode($selectedchallans);
}


if (isset($_POST['editcreatedchallans']) && $_POST['editcreatedchallans'] == "editcreatedchallans")
{
    // print_r($_POST);
    $_POST = array_map('trim',$_POST);
    $studStatus = 'Prov.Promoted';
    $rowid = $_POST['id'];
    $challanNo = $_POST['challan'];
    $id = $_POST['studentId'];
    $name = getStudentNameById($_POST['studentId']);
    $term = $_POST['semester'];
    $class = $_POST['class-list'];
    $stream = $_POST['stream'];
    $feegroup = $_POST['feegroup'];
    $academic = $_POST['academic'];
    $feetypes = trim($_POST['selectedfeetypes']);

    if (isset($_POST['duedate'])) {
        $duedate = $_POST['duedate'];
    } else {
        $duedate = $_POST['oldduedate'];
    }
    
    if ((isset($_POST['selected_feetypes'])) && ($_POST['selected_feetypes'] != '')) {
        $feetypes = $_POST['selected_feetypes'];
        $selected_feetypes = explode(',', $_POST['selected_feetypes']);
    } else {
        $feetypes = $_POST['selectedfeetypes'];
        $selected_feetypes = explode(',', $_POST['selectedfeetypes']);
    }

    $createdby = $_SESSION['myadmin']['adminid'];
    if (isset($_POST['remarks']))  {
        $remarks = $_POST['remarks'];
    } else {
        $remarks = $_POST['oldremarks'];
    }

    $feetypedata = sqlgetresult('SELECT * FROM getfeetypedata WHERE class=\'' . $class . '\' AND semester=\'' . $term . '\' AND stream = \'' . $stream . '\' AND "academicYear" = \'' . $academic . '\'', true);
    $selectedData = array();
    $feeData = explode(',', $feetypes);
    foreach ($feeData as $k => $v) {
        foreach ($feetypedata as $val) {
            if ( $v == trim($val['feeType'])) {
                $group = getFeeGroupbyId($val['feeGroup']);
                $groupdata[$group][$val['feeGroup']][$val['id']][] = $val['amount'];
                $groupdata[$group][$val['feeGroup']][$val['id']][] = $val['feename'];
                
                $sql = "SELECT * FROM editcreatedchallansnew('$challanNo','$id','$class','" . trim($val['feeType']) . "','$term','$studStatus','$createdby','".trim($val['amount'])."','$stream','$remarks','$duedate','". trim($val['feeGroup']) ."','$academic')";
                $result = sqlgetresult($sql);
                flattableentry(trim($challanNo), trim($id)); 
             }
        }
    }

    if ($result['editcreatedchallansnew']){
         toUpdateDueDateOnAppl($id,$duedate);
    }

    $flag = 0;
    $tblfeetype = array();
    $selectfeetype = 'SELECT "feeType" FROM tbl_challans WHERE "challanNo" =\'' . $challanNo . '\' AND "feeGroup" != 10 ';
    $sql = sqlgetresult($selectfeetype,true);
    // print_r($sql);
    foreach($sql AS $fee) {
        array_push($tblfeetype, $fee['feeType']);
    }
    $feetypediff = array_diff($tblfeetype,$feeData);
    if(count($feetypediff) > 0) {
        foreach($feetypediff AS $feetype) {      
         $feeGroup = '10';     
            $deletefeetype = ('DELETE FROM tbl_challans WHERE "challanNo"=\'' . ($challanNo) . '\' AND "feeType" = \'' . ($feetype) . '\'AND "feeGroup" != \'10\'');
            $deletefeetypeindemand = ('DELETE FROM tbl_demand WHERE "challanNo"=\'' . $challanNo . '\' AND "feeType" = \'' . $feetype . '\' AND "feeGroup" != \'10\' AND "studentId" = \'' . $_POST['studentId'] . '\' AND "academicYear" = \'' . $_POST['academic'] . '\' AND "term" = \'' . $_POST['semester'] . '\'');

            $deletefeetypeinledger = ('DELETE FROM tbl_student_ledger WHERE "challanNo"=\'' . trim($challanNo) . '\' AND "feeType" = \'' . trim(getFeeTypebyId($feetype)) . '\' AND "feeGroup" != \'' . trim(getFeeGroupbyId($feeGroup)) . '\' AND "studentId" = \'' . trim($_POST['studentId']) . '\' AND "academicYear" = \'' . trim(getAcademicyrById($_POST['academic'])) . '\' AND "term" = \'' . trim($_POST['semester']) . '\'');

            $res = sqlgetresult($deletefeetype);
            $res = sqlgetresult($deletefeetypeindemand);
            $res = sqlgetresult($deletefeetypeinledger);
            $flag = 1;
        }
    }
    $challanData = sqlgetresult('SELECT * FROM challanData WHERE "studentId" =\'' . $id . '\' AND  "challanNo" = \'' . $challanNo . '\' ', true);

    $feeTypes = sqlgetresult("SELECT * FROM getFeeTypes");

    $total = 0;
    $feeData = array();
    $chlncnt = count($challanData);
    foreach ($challanData as $k => $value)
    {
        $challanData1['challanNo'] = $value['challanNo'];
        $challanData1['term'] = $value['term'];
        $challanData1['clid'] = $value['clid'];
        $challanData1['studentName'] = $value['studentName'];
        $challanData1['studentId'] = $value['studentId'];
        $challanData1['class_list'] = $value['class_list'];
        $challanData1['duedate'] = $value['duedate'];
        $challanData1['stream'] = $value['stream'];
        $challanData1['steamname'] = $value['steamname'];
        $challanData1['org_total'][] = $value['org_total'];
        $challanData1['waivedTotal'][] = $value['waivedTotal'];

        $feetypearray[getFeeGroupbyId($value['feeGroup'])][$value['feeType']][] = $value['org_total'];
        $feetypearray[getFeeGroupbyId($value['feeGroup'])][$value['feeType']][] = getFeeTypebyId($value['feeType']);

        $feetypearray[getFeeGroupbyId($value['feeGroup'])]['waived'] = getwaiveddata($value['challanNo'], $value['feeGroup']);

        $cnt = $k+1;
        if($cnt == $chlncnt) {
            $groupdata = $feetypearray;
        }
    }
    $msg = "<p style='padding-left:20px;'>Please find the new challan created for  " . $challanData1['studentName'] . ".<br/>Please make a note that the <b>CHALLAN</b> has to be PAID on or before <b style='color:red'>" . date("d-m-Y", strtotime($challanData1['duedate'])) . "</b>.<br/>For Online Payment Please <b>LOGIN</b> to our <a href=" . BASEURL . " style='color:red'>FEE PORTAL</a>. </p>";

    $msg .= "<table border='1' style='border:1px solid grey;border-collapse: collapse;' cellpadding='10' width = '100%'>
            <tr><td colspan='2'><label> <b>School Name</b>: </label> LMOIS - CBSE</td></tr>
            <tr><td><label><b>Name</b>: </label> " . $challanData1['studentName'] . " </td><td><label><b>Semester</b>: </label>" . $challanData1['term'] . " </td></tr>
            <tr><td><label><b>ID</b>: </label> " . $challanData1['studentId'] . " </td><td><label><b>Class</b>: </label> " . $challanData1['class_list'] . " </td></tr>
            <tr><td><label><b>Challan Number</b>: </label>" . $challanData1['challanNo'] . "</td><td><label><b>Due Date</b>: </label> " . date("d-m-Y", strtotime($challanData1['duedate'])) . "</td></tr>
            <tr><td colspan='2' style='text-align:center'><b>FEE DETAILS</b></td></tr>";

        
    foreach ($groupdata as $grp => $data)
    {
        $msg .= '<tr style="border:0;border-right:1px solid grey;"><td colspan="2"><b>' . $grp . '</b></td></tr>';
        $tot = 0;
        $wtot = 0;
        $amount = 0;
        $last_key = end(array_keys($data));
        $waiveddata = array();

        foreach ($data as $k => $val){
            if(trim($k) != 'waived' && $val[0] != 0){                
                $msg .= '<tr style="border:0;"><td >' . $val[1] . '</td><td style="border-right:1px solid grey;text-align:right;">' . $val[0] . '</td></tr>';
                $tot += $val[0];                
            }

            if(trim($k) == 'waived' && $val != 0) {
                $waiveddata[] =  $val[0]['waiver_type'];
                $waiveddata[] =  $val[0]['waiver_total']; 
                $wtot = $val[0]['waiver_total'];
            }
            if( $k == $last_key && sizeof($waiveddata) > 0)  {
                $msg .= '<tr style="border:0;"><td><b>Waiver</b> - ' . $waiveddata[0] . '</td><td style="border-right:1px solid grey;text-align:right;">' . $waiveddata[1] . '</td></tr>';
            }
        }
        $amount += $tot;
        $amount -= $wtot;
        $org_total += $amount;
        $msg .= '<tr style="border-top:1px solid grey;"><td style="border-top:1px solid grey;"><b>TOTAL</b></td><td style="border-top:1px solid grey;text-align:right;"><b>' . $amount . '</td></tr></b>';
    }
            
    $msg .= '<tr style="border-top:1px solid grey;"><td style="border-top:1px solid grey;"><b>GRAND  TOTAL</b></td><td style="border-top:1px solid grey;text-align:right;"><b>' . $org_total . '</td></tr></b>';
    $msg .= "</table>";

    $data = $msg;

    $mailbody = $msg;
    $studentId = trim($challanData1['studentId']);
    $type = "Update Challan";

    $smsbody = "Dear Parent, Your child's latest challan has been updated. Please logon our feeapp to check the details.";


    if ($result['editcreatedchallansnew'] > 0 || $flag == 1)
    {
        $_SESSION['successdelete'] = "<p class='success-msg'>Challan updated successfully and mail has been sent to the parent’s email address</p>";
        
        sendNotificationToParents($studentId, $mailbody, $smsbody, $type);
    }
    else
    {
        $_SESSION['errordelete'] = "<p class='error-msg'>Some Error Has Occured</p>";
    }
    header("Location:managecreatedchallans.php");
}

if (isset($_POST['submit']) && $_POST['submit'] == "addomega")
{
    $streamsql = 'SELECT * FROM streamcheck';
    $streamdata = sqlgetresult($streamsql, true);

    $semestersql = 'SELECT semester FROM addsemesterdata';

    $semesterdata = sqlgetresult($semestersql, true);

    $feesql = 'SELECT * FROM feetypecheck';

    $feedata = sqlgetresult($feesql, true);

    $classql = 'SELECT * FROM classcheck';

    $classdata = sqlgetresult($classql, true);

    $academicsql = 'SELECT * FROM yearcheck';

    $academicdata = sqlgetresult($academicsql, true);

    $arr = array();
    array_push($arr, $streamdata, $semesterdata, $feedata, $classdata, $academicdata);
    echo json_encode($arr);
}
//***********Chequedd section - Start***********//

if (isset($_POST["getchallan"]) && $_POST["getchallan"] == "new")
{ 
    $cno = $_POST['cno'];
    $query = sqlgetresult('SELECT * FROM chequedddata WHERE "challanNo" =\'' . $cno . '\' AND "challanStatus" = \''. 0 .'\'',true);
    $query2 = sqlgetresult('SELECT * FROM chequedddata WHERE "challanNo" =\'' . $cno . '\' AND "challanStatus" = \''. 1 .'\'',true);
   $query1 = array();
    if($query != ""){
        foreach($query as $key => $q){
            if(trim($q['feeGroup']) == 'LATE FEE'){
                $feegroupname[] = $q['feeGroup'];
            }
            else{
                $feegroupname[] = getFeeGroupbyId($q['feeGroup']);
                $feegroup[] = $q['feeGroup'];
            }
            $c = array_combine(array_unique($feegroup), array_unique($feegroupname));
            $query1['feegroups'] = $c;
            $query1['challandata'] = $query;

        }
        echo json_encode($query1);
    } else if($query2 != "") {
        $query1 = 1;
        echo json_encode($query1);
    } else{
        $query1 = 0;
        echo json_encode($query1);
    }     
}
if (isset($_POST["getfeegroupamount"]) && $_POST["getfeegroupamount"] == "new")
{ 
    $feegroup = $_POST['feegroup'];
    $cno = $_POST['cno'];
    $waiveddata = getwaiveddata($cno, $feegroup);
    $totalamount= 0 ;

    if($waiveddata != 0){
        $query1 = sqlgetresult('SELECT SUM("total") AS org_total FROM tbl_challans WHERE "challanNo" =\'' . $cno . '\' AND "feeGroup" = \''. $feegroup .'\' AND deleted=0');
        foreach($waiveddata AS $waiver){
            $waivertotal = $waiver['waiver_total'];
        }
        $totalamount = $query1['org_total'] - $waivertotal;
    }
    else{
        $query = sqlgetresult('SELECT SUM("total") AS org_total FROM tbl_challans WHERE "challanNo" =\'' . $cno . '\' AND "feeGroup" = \''. $feegroup .'\' AND deleted=0');

        $totalamount = $query['org_total'];
    }
        echo json_encode($totalamount);
}

if (isset($_POST['pay']) && $_POST['pay'] == 'confirm'){
    $_POST = array_map('trim',$_POST);

    if($_POST['ptype'] == "Online"){
        $bank = $_POST['bank'];
        $paymentmode = $_POST['paymentmodetrans'];
    }
    else{
        $bank = $_POST['cbank'];
        $paymentmode = $_POST['paymentmode'];
    }
    date_default_timezone_set("Asia/Kolkata");    
    $cur_data = time();
    $date = date('Y-m-d h:i:s');

    $uid = $_SESSION['myadmin']['adminid'];

    $feegroupradio = trim($_POST['feegroupradio']);

        $entry = sqlgetresult("SELECT *  FROM new_cheque_fee_entry('" . $_POST['sid'] . "','" . $_POST['term'] . "','" . $_POST['cnum'] . "','" . $_POST['ptype'] . "','" . $bank . "','" . $paymentmode . "','" . $_POST['paiddate'] . "','" . $_POST['feegroupradio'] . "','" . $_POST['academicyear'] . "','" . $uid . "','" . $_POST['remarks'] . "','" . $date . "') ");
        /* Paid date */
        if(count($entry) > 0){
            toUpdatePaidDateOnAppl($_POST['sid'],$_POST['paiddate']);
        }

        if (trim($_POST['feegroupradio']) == 'LATE FEE') {
            $feegroup = '0';
        } else {
            $feegroup = $_POST['feegroupradio'];
        }
        $receiptupd = updatereceipt(trim($_POST['cnum']),trim($_POST['sid']),trim($_POST['feegroupradio']));

        $fromwhere = 'Receipt';
        flattableentry(trim($_POST['cnum']), trim($_POST['sid']), $fromwhere);

        if($_POST['fullwaived'] == 'on'){
            $updatechallanstatus = sqlgetresult('UPDATE tbl_challans SET "fullyWaived" = \'1\' WHERE "challanNo" = \''.$_POST['cnum'].'\'');
        }
        if($_POST['sendmail'] == 'on'){
             $chequedd = "cheque";
            createPDF($_POST['sid'], $_POST['cnum'], $chequedd);
        }
        
        if($receiptupd > 0){
        $_SESSION['successcheque'] = "<p class='success-msg'>Payment Updated Successfully.</p>";
        header("Location: cheque_dd.php");
        }
        else{
        $_SESSION['errorcheque'] = "<p class='success-msg'>Payment was not updated in Receipt table.</p>";
        header("Location: cheque_dd.php");  
        }
    }
//***********Chequedd section - End***********//

if( isset($_POST['submit']) && $_POST['submit'] == 'getClassData' ) {
    $strId = $_POST['data'];

    $classData = sqlgetresult(' SELECT c."displayOrder", c.id AS value, c.class_list AS label FROM tbl_class c LEFT JOIN tbl_student s ON s.class::int = c.id WHERE s.stream = \''.$strId.'\' GROUP BY c.id ORDER BY c."displayOrder" ASC', true);
    echo json_encode($classData);
}

if( isset($_POST['submit']) && $_POST['submit'] == 'getClassDataForStd' ) {
    $strId = $_POST['data'];

    $classData = sqlgetresult('SELECT "displayOrder", "class_list" AS label, id AS value FROM tbl_class WHERE "streamId" = \''.$strId.'\' AND "deleted" = \'0\' AND "status" =  \'1\' ORDER BY "displayOrder" ASC', true);
    echo json_encode($classData);
}

if( isset($_POST['submit']) && $_POST['submit'] == 'getSectionData' ) {
    $strId = $_POST['strId'];
    $classId = $_POST['classId'];

    $classData = sqlgetresult(" SELECT section FROM tbl_student WHERE stream = '$strId' AND class = '$classId' GROUP BY section ORDER BY section ASC", true);
    if(sizeof($classData) > 0){
        array_walk_recursive($classData, function(&$v) { $v = trim($v); }); 
        $sectiondata = array(0 => array("section"=> "NEW"));
        $fullsectiondata = array_merge($classData, $sectiondata);
        $fullsection = array_map("unserialize", array_unique(array_map("serialize", $fullsectiondata)));
    }
    else{
        $fullsection = $classData;
    }
    echo json_encode($fullsection);
}

if( isset($_POST['submit']) && $_POST['submit'] == 'getClassCoSectionData' ) {
    $strId = $_POST['strId'];
    $classId = $_POST['classId'];

    $classCoData = sqlgetresult(" SELECT section FROM tbl_student WHERE stream = '$strId' AND class = '$classId' GROUP BY section ORDER BY section ASC", true);
    echo json_encode($classCoData);
}
if (isset($_POST['chequerevoke']) && $_POST['chequerevoke'] == "chequerevoke")
{
    $cno = trim($_POST['stdidforcheque']);
    $uid = $_SESSION['myadmin']['adminid'];

    $challandata = sqlgetresult('SELECT "studentId", "academicYear", "term" FROM tbl_challans WHERE "challanNo" = \''.$cno.'\' LIMIT 1');
    $acayear = $challandata['academicYear'];
    $term = $challandata['term'];
    $stdid = $challandata['studentId'];
    $datasend = $cno." ".$uid." ".$stdid." ".$term." ".$acayear;
    $chequerevoke = "SELECT * FROM chequerevoke('$cno','$uid', '$stdid', '$term', '$acayear')";
    $runchequerevoke = sqlgetresult($chequerevoke);
   
    if ($runchequerevoke['chequerevoke'] > 0)
    {
        $_SESSION['successchallanrevoke'] = "<p class='success-msg'>Challan Revoked Successfully.</p>";
        header('location:managepaidchallans.php');
    }
    else
    {
        createErrorlog($datasend);
        $_SESSION['failurechequerevoke'] = "<p class='error-msg'>Some error has been occured. Please try again.</p>";
        header('location:managepaidchallans.php');
    }
}

if( isset($_POST['Goback']) && $_POST['Goback'] == 'Goback' ) {

    unset($_SESSION['selectedstudid']);
     // header('location:updatestudentsstatus.php');
    $done = 1;
    echo json_encode($done);
}

if( isset($_POST['back']) && $_POST['back'] == 'back' ) {

    $page = $_SERVER['HTTP_REFERER'];
     //print_r($_SERVER);
    if ( strpos($page, 'nonfeechallancreation') !== false) {
        unset($_SESSION['selectednonfeechallans']);
        $done = 1;
    } else if ( strpos($page, 'feechallancreation') !== false) {
        unset($_SESSION['selectedfeechallans']);
        $done = 4;
    } else if ( strpos($page, 'editStudent') !== false) {
        unset($_SESSION['selectedchallans']);
        unset($_SESSION['selectedchallanNos']);
        $done = 2;
    } else {
        unset($_SESSION['selectedstudid']);
        $done = 3;
    }
    echo json_encode($done);
}

if (isset($_POST['submit']) && $_POST['submit'] == "viewChallanData")
{
    $cid = $_POST['cid'];
    $studId = $_POST['studId'];
    $feegroup = $_POST['feegroup'];
    $challanData = sqlgetresult('SELECT * FROM challanDatanew WHERE "challanNo" = \'' . $cid . '\' AND ("challanStatus" = \'0\' OR "challanStatus" = \'2\') ',true);
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
        if ((trim($value['feegroupname'])) == 'SFS UTILITIES FEE')
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
    $feeData = array();
    $latefee = 0;
    $chlncnt = count($challanData);
    
    foreach ($challanData as $k => $value) {
        $challanData1['challanNo'] = $value['challanNo'];
        $challanData1['term'] = $value['term'];
        $challanData1['clid'] = $value['clid'];
        $challanData1['studentName'] = $value['studentName'];
        $challanData1['studentId'] = $value['studentId'];
        $challanData1['class_list'] = $value['class_list'];
        $challanData1['duedate'] = date("d-m-y",strtotime($value['duedate']));
        $challanData1['stream'] = $value['stream'];
        $challanData1['steamname'] = $value['steamname'];
        $challanData1['waivedPercentage'] = $value['waivedPercentage'];
        $challanData1['waivedAmount'] = $value['waivedAmount'];
        $challanData1['waivedTotal'] = $value['waivedTotal'];
        $challanData1['feeGroup'] = $value['feeGroup'];
        $challanData1['studStatus'] = $value['studStatus'];
        $challanData1['org_total'] = $value['total'];
        if($value['remarks'] != ''){
            $challanData1['remarks'] = $value['remarks'];
        } else{
            $challanData1['remarks'] = 'Nill';
        }
        if(trim($challanData1['feeGroup']) != "LATE FEE"){
            $group = getFeeGroupbyId($challanData1['feeGroup']);
            $waivedarray[$group][] = $challanData1['waivedTotal'];
        } else {
            $latefee++;
        }

        $feetypearray[getFeeGroupbyId($value['feeGroup'])][$value['feeType']][] = $value['total'];
        $feetypearray[getFeeGroupbyId($value['feeGroup'])][$value['feeType']][] = getFeeTypebyId($value['feeType']);

         $feetypearray[getFeeGroupbyId($value['feeGroup'])]['waived'] = getwaiveddata($value['challanNo'], $value['feeGroup']);

        $cnt = $k+1;
        if($cnt == $chlncnt) {
            $groupdata = $feetypearray;
        } 
    }
    if($latefee == 1){
        $late = '0';
        $latefee = sqlgetresult('SELECT "org_total" FROM tbl_challans WHERE "challanNo" = \''.$challanData1['challanNo'].'\' AND ("challanStatus" = \'0\' OR "challanStatus" = \'2\') AND "feeGroup" = \''.$late.'\' AND deleted=0');
        // $latefeedata[$val['feeGroup']][] = $latefee['org_total'];
        $groupdata[$late][$late][1][] = $latefee['org_total'];
        $groupdata[$late][$late][1][] = $late;
    }
    uksort($groupdata, function($key1, $key2) use ($order) {
        return (array_search(trim($key1), $order) > array_search(trim($key2), $order));
    });
    $challanData1['feeData'] = $groupdata;
    echo json_encode($challanData1);
}

if( isset($_POST['createtranschallan']) && $_POST['createtranschallan'] == 'createtranschallan' ) 
{   
    $gettransportstage = sqlgetresult('SELECT * FROM tbl_student WHERE "studentId" =  \''.$_POST['transstdid'].'\' LIMIT 1');
    $challantransportstage = sqlgetresult('SELECT * FROM tbl_challans WHERE "studentId" =  \''.$_POST['transstdid'].'\' LIMIT 1');

    if($gettransportstage['studentId'] != "" && $challantransportstage['studentId'] != "")
    {
        $allchallandata = sqlgetresult('SELECT * FROM tbl_challans WHERE "studentId" =  \''.$_POST['transstdid'].'\'');

            $transport_arr = array(16,17,18,19); 
        foreach($allchallandata AS $challan){
            $selectedFeeTypes = explode(',',$challan['feeTypes']);
            $diff = array();
                foreach($selectedFeeTypes as $key=>$val){
                if(in_array($val,$transport_arr)){
                $diff[] = $val;
                }
            } 
        }   
            if(sizeof($diff) > 0){
                $_SESSION['successtransport'] = "<p class='error-msg'>Transport has been already mapped with this student Id.</p>";
                header('location:managetransportchallans.php');
            }

            else if($gettransportstage['transport_stg'] != 0)
            {

                if(trim($gettransportstage['transport_stg']) == '1'){
                    $stageid = '16';
                    $stageamount = '5335';
                }
                if(trim($gettransportstage['transport_stg']) == '2'){
                    $stageid = '17';
                    $stageamount = '8665';
                }
                if(trim($gettransportstage['transport_stg']) == '3'){
                    $stageid = '18';
                    $stageamount = '12010';
                }
                if(trim($gettransportstage['transport_stg']) == '4'){
                    $stageid = '19';
                    $stageamount = '13340';
                }

                $challandatafortrans = sqlgetresult('SELECT * FROM tbl_challans WHERE "studentId" =  \''.$_POST['transstdid'].'\' LIMIT 1');

                $challanNo = $challandatafortrans['challanNo'];
                $studentId = $challandatafortrans['studentId'];
                $classList = $challandatafortrans['classList'];
                $term = $challandatafortrans['term'];
                $studStatus = $challandatafortrans['studStatus'];
                $createdby = $_SESSION['myadmin']['adminid'];
                $status = $challandatafortrans['status'];
                $delete = $challandatafortrans['deleted'];
                $challanstatus = $challandatafortrans['challanStatus'];
                $stream = $challandatafortrans['stream'];
                $remarks = $challandatafortrans['remarks'];
                $duedate = $challandatafortrans['duedate'];
                $waivedPercentage = $challandatafortrans['waivedPercentage'];
                $waivedAmount = $challandatafortrans['waivedAmount'];
                $waivedTotal = $challandatafortrans['waivedTotal'];
                $academicyear = $challandatafortrans['academicYear'];
                $feegroup = '9';


                $createtranschallan = "SELECT * FROM transportchallancreate('$challanNo','$studentId','$stageid','$classList','$term','$studStatus','$createdby','$challanstatus','$stageamount','$stageamount','$stream','$duedate','$feegroup','$academicyear')";
                // print_r($createtranschallan);
                // exit;
                $run = sqlgetresult($createtranschallan);
                if($run['transportchallancreate']){
                    $_SESSION['successtransport'] = "<p class='success-msg'>Transport Challan created successfully.</p>";
                     header('location:managetransportchallans.php');

                }
                else{
                    $_SESSION['successtransport'] = "<p class='error-msg'>Some Error has occured.Please try after some time.</p>";
                     header('location:managetransportchallans.php');
                }

            }

            else
            {  
                 $stage = $gettransportstage['transport_need'];
                // $stage = 'Y';
                    // print_r($stage);

                    if($stage != 0)
                    {
                        // print_r("hi");
                            

                            if(trim($stage) == '1'){
                                $stageid = '16';
                                $stageamount = '5335';
                            }
                            if(trim($stage) == '2'){
                                $stageid = '17';
                                $stageamount = '8665';
                            }
                            if(trim($stage) == '3'){
                                $stageid = '18';
                                $stageamount = '12010';
                            }
                            if(trim($stage) == '4'){
                                $stageid = '19';
                                $stageamount = '13340';
                            }

                            $challandatafortrans = sqlgetresult('SELECT * FROM tbl_challans WHERE "studentId" =  \''.$_POST['transstdid'].'\' LIMIT 1');
                            // print_r($challandatafortrans);
                            // print_r($stage);
                            $challanNo = $challandatafortrans['challanNo'];
                            $studentId = $challandatafortrans['studentId'];
                            $classList = $challandatafortrans['classList'];
                            $term = $challandatafortrans['term'];
                            $studStatus = $challandatafortrans['studStatus'];
                            $createdby = 
                            $status = $challandatafortrans['status'];
                            $delete = $challandatafortrans['deleted'];
                            $challanstatus = $challandatafortrans['challanStatus'];
                            $stream = $challandatafortrans['stream'];
                            $remarks = $challandatafortrans['remarks'];
                            $duedate = $challandatafortrans['duedate'];
                            $waivedPercentage = $challandatafortrans['waivedPercentage'];
                            $waivedAmount = $challandatafortrans['waivedAmount'];
                            $waivedTotal = $challandatafortrans['waivedTotal'];
                            $academicyear = $challandatafortrans['academicYear'];
                            $feegroup = '9';

                            $createtranschallan = "SELECT * FROM transportchallancreate('$challanNo','$studentId','$stageid','$classList','$term','$studStatus','$createdby','$challanstatus','$stageamount','$stageamount','$stream','$duedate','$feegroup','$academicyear')";

                            // print_r($createtranschallan);
                            // exit;
                            $run = sqlgetresult($createtranschallan);
                            if($run['transportchallancreate']){
                                $_SESSION['successtransport'] = "<p class='success-msg'>Transport Challan created successfully.</p>";
                                 header('location:managetransportchallans.php');

                            }
                            else{
                                $_SESSION['successtransport'] = "<p class='error-msg'>Some Error has occured.Please try after some time.</p>";
                                 header('location:managetransportchallans.php');
                            }
                    // exit;
            }
            else{
                $_SESSION['successtransport'] = "<p class='error-msg'>Transport Data is not mapped with this Student Id</p>";
                header('location:managetransportchallans.php');
            }


        }       
    }

    else
    {
        $_SESSION['successtransport'] = "<p class='error-msg'>Please provide Correct Student ID.</p>";
        header('location:managetransportchallans.php');
    }
}

/****** Start -  Bulk SMS  ****/

if (isset($_POST['sendsms']) && $_POST['sendsms'] == "send") {
    $sendType = trim($_POST['sendType']);
    $content = trim($_POST['msg_content']);
    // echo $content;
    $where = 'WHERE';
    if($sendType == '1') {
        $where .= ' 1=1 GROUP BY p."id"';
    } else if($sendType == '2') {
        $where .= ' c."challanStatus" = 0 GROUP BY p."id" ';
    } else if($sendType == '3') {
        $where .= ' c."challanStatus" = 1 GROUP BY p."id" ';
    } else if($sendType == '4') {
        $where .= ' s."studentId" = \''.$_POST['studId'].'\' GROUP BY p."id" ';
    }

    $contacts = sqlgetresult('SELECT p.id, p."mobileNumber",p."secondaryNumber",MAX(s."studentId") AS stud FROM tbl_parents p LEFT JOIN tbl_student s ON p."id" = s."parentId" LEFT JOIN tbl_challans c ON s."studentId" = c."studentId" '.$where, true);
    $fileData = '';
    if(sizeof($contacts) > 0) {
        foreach ($contacts as $contact) {
            $studId = $contact['stud'];
            $contact = array_values($contact);
            foreach ($contact as $val) {
                if($val > 0 && strlen($val) >= 10) {
                    $smsURL = "$smsBaseurl/sms.aspx?Id=$smsLoginId&Pwd=$smsLoginPass&PhNo=$val&text=$content";
                    $ret = file($smsURL);
                    if($studId != '' && $ret[0] == "Message Submitted") {
                        $fileData .= trim($studId).'-'.trim($val).',' ;
                    }
                }                
            }           
        }
        downloadFile($fileData, "bulksms.php","sms");
        $_SESSION['error'] = "<p class='success-msg'>SMS Sent Successfully.</p>";
        header("location:bulksms.php");
    } else {
        $_SESSION['error'] = "<p class='error-msg'>No Records!!!</p>";
        header("location:bulksms.php");
    }
}

if (isset($_POST['sendmail']) && $_POST['sendmail'] == "send") {
    $sendType = $_POST['msendType'];
    $content = $_POST['mail_content'];
    $subject = $_POST['mail_sub'];
    // echo $content;
    $where = 'WHERE';
    if($sendType == '1') {
        $where .= ' 1=1 GROUP BY p."id"';
    } else if($sendType == '2') {
        $where .= ' c."challanStatus" = 0 GROUP BY p."id" ';
    } else if($sendType == '3') {
        $where .= ' c."challanStatus" = 1 GROUP BY p."id" ';
    } else if($sendType == '4') {
        $where .= ' s."studentId" = \''.$_POST['studId'].'\' GROUP BY p."id" ';
    }

    $contacts = sqlgetresult('SELECT p.id, p."email",p."secondaryEmail",MAX(s."studentId") AS stud FROM tbl_parents p LEFT JOIN tbl_student s ON p."id" = s."parentId" LEFT JOIN tbl_challans c ON s."studentId" = c."studentId" '.$where, true);
    $fileData = '';
    if(sizeof($contacts) > 0) {
        foreach ($contacts as $contact) {
            $studId = $contact['stud'];
            $contact = array_values($contact);
            foreach ($contact as $val) {
                if (stristr($val,"@") || stristr($val,".")) {
                    $send = SendMailId($val, $subject, $content ,"","","","challan");
                    if($studId != '') {
                        $fileData .= trim($studId).'-'.trim($val).',' ;
                    }
                }                
            }           
        }
        downloadFile($fileData, "bulksms.php","sms");
        $_SESSION['error'] = "<p class='success-msg'>Mail Sent Successfully.</p>";
        header("location:bulksms.php");
    } else {
        $_SESSION['error'] = "<p class='error-msg'>No Records!!!</p>";
        header("location:bulksms.php");
    }
}

/****** End -  Bulk SMS  ****/

/*******CREATE RECEIPT START*********/
if ( isset($_POST['createreceipt']) && $_POST['createreceipt'] == 'create' ) {
    $challanNo = trim($_POST['chlnno']);
    $findChallanPaid = sqlgetresult('SELECT DATE("updatedOn") AS updated, "challanStatus" FROM tbl_challans WHERE "challanNo" = \''.$challanNo.'\' LIMIT 1 ');
    // print_r($findChallanPaid);exit;
    if ( $findChallanPaid['challanStatus'] == 1 ) {
        $studentId = getStudentIdByChallan($challanNo);
        createPDF($studentId,$challanNo,$findChallanPaid['updated']);
    } else {
        $_SESSION['error'] = "<p class='error-msg'>Provided Challan is Unpaid/Invalid.</p>";
        header('location:createreceipt.php');
    }    
}
/*******CREATE RECEIPT END*********/

/*******EDIT CHALLANS START********/

if( isset($_POST['submit']) && $_POST['submit'] == 'getStudData') {
    $challanNo = trim($_POST['chlno']);

    $getstudentData = sqlgetresult('SELECT "studentId","studentName", class_list, section, term, hostel_need FROM challanDatanew WHERE "challanNo" = \''.$challanNo.'\' GROUP BY "studentId","studentName", class_list, section, term, hostel_need ');
    if( sizeof($getstudentData) > 0) {
        if( $getstudentData['hostel_need'] == 'Y') {
            $feeTypeData = sqlgetresult('SELECT id, "feeType" FROM getfeetypes WHERE applicable=\'DH\' OR applicable=\'H\' OR applicable ILIKE \'%T%\' ',true);
        } else {
            $feeTypeData = sqlgetresult('SELECT id, "feeType" FROM getfeetypes WHERE applicable=\'DH\' OR applicable=\'D\' OR applicable  = \'0\' OR applicable ILIKE \'%T%\' ',true);
        }
        // $feeTypeData = array_map('trim',$feeTypes);
        $getstudentData['feeData'] = $feeTypeData;
    }    

    echo json_encode($getstudentData);
}
if( isset($_POST['updatec']) && $_POST['updatec'] == 'update') {
    $studentId = trim($_POST['studId']);
    $feeType = trim($_POST['feetype']);
    $challanNo = trim($_POST['challanNo']);


    $studStaus = 'Prov.Promoted';
    $sdata = sqlgetresult('SELECT "studentId","studentName", clid, term, duedate, "academicYear", stream, remarks FROM challanDatanew WHERE "challanNo" = \''.$challanNo.'\' GROUP BY  "studentId","studentName", clid, term, duedate, "academicYear", stream, remarks',true);    

    if( $sdata[0]['remarks'] == '' ) {        
        $sdata[0]['remarks'] = 'Nil';
    }
    
    $academicyrid = $sdata[0]['academicYear'];

    $feeTypeData = sqlgetresult('SELECT amount, "feeGroup" from getfeetypedata WHERE "feeType" = \''.$feeType.'\' AND "class" = \''.$sdata[0]['clid'].'\' AND "semester" = \''.$sdata[0]['term'].'\' AND "academicYear" = \''.$sdata[0]['academicYear'].'\' AND "stream" = \''.$sdata[0]['stream'].'\' ');

    if(trim($feeTypeData['feeGroup']) == '10' ) {
        $sfsqty = sqlgetresult("SELECT * FROM sfstableentry('". $_POST['challanNo'] ."','". $feeType ."','". $feeTypeData['amount'] ."','1', '". $feeTypeData['amount'] ."','". $_SESSION['myadmin']['adminid'] ."','". $studentId ."')");
    }


    $updateinfo = sqlgetresult('SELECT * FROM editcreatedchallansnew( \''.$challanNo.'\', \''.$studentId.'\',\''.$sdata[0]['clid'].'\', \''.$feeType.'\',  \''.$sdata[0]['term'].'\',\''.$studStaus.'\',\''.$_SESSION['myadmin']['adminid'].'\', \''.$feeTypeData['amount'].'\', \''.$sdata[0]['stream'].'\',\''.$sdata[0]['remarks'].'\', \''.$sdata[0]['duedate'].'\', \''.$feeTypeData['feeGroup'].'\', \''.trim($academicyrid).'\') ');
    flattableentry(trim($challanNo), trim($studentId));
    if( $updateinfo['editcreatedchallansnew'] > 0) {
        $_SESSION['success'] = "<p class='success-msg'>Challan Data Updated Successfully.</p>";
        header('location:managetransportchallans.php');
    } else {
        $_SESSION['error'] = "<p class='error-msg'>Some error has haapened. Please try again later.</p>";
        header('location:managetransportchallans.php');
    }         
}


if( isset($_POST['submit']) && $_POST['submit'] == 'viewChallanUpdatedData') {
    $studentId = trim($_POST['studId']);
    $selectedFeeType = trim($_POST['feetype']);
    $challanNo = trim($_POST['challanNo']);
    $findExist = array();
    $findExist  = sqlgetresult('SELECT 1 FROM tbl_challans WHERE "feeType" = \''.$selectedFeeType.'\' AND "challanNo" = \''.$challanNo.'\' LIMIT 1 ');
    if (!$findExist || sizeof($findExist) == 0) {
        $challanData = sqlgetresult('SELECT * FROM challanDatanew WHERE "studentId" =\'' . $studentId . '\' AND  "challanNo" = \'' . $challanNo . '\' ',true);
        
        $challanData1 = array();           
        $waivedarray = array();
        $feeData = array();

        foreach ($challanData as $k=>$value) {
            $challanData1['challanNo'] = $value['challanNo'];
            $challanData1['term'] = $value['term'];
            $challanData1['clid'] = $value['clid'];
            $challanData1['studentName'] = $value['studentName'];
            $challanData1['studentId'] = $value['studentId'];
            $challanData1['class_list'] = $value['class_list'];
            $challanData1['duedate'] = date("d-m-y",strtotime($value['duedate']));
            $challanData1['stream'] = $value['stream'];
            $challanData1['steamname'] = $value['steamname'];
            // $challanData1['waivedPercentage'] = $value['waivedPercentage'];
            // $challanData1['waivedAmount'] = $value['waivedAmount'];
            // $challanData1['waivedTotal'] = $value['waivedTotal'];
            $challanData1['feeGroup'] = $value['feeGroup'];
            $challanData1['studStatus'] = $value['studStatus'];
            $challanData1['org_total'] = $value['org_total'];
            $challanData1['academicYear'] = $value['academicYear'];
            if($value['remarks'] != ''){
                $challanData1['remarks'] = $value['remarks'];
            }
            else{
            $challanData1['remarks'] = 'Nill';

            }
            if(trim($challanData1['feeGroup']) != "LATE FEE"){
                $group = getFeeGroupbyId($challanData1['feeGroup']);
                // $waivedarray[$group][] = $challanData1['waivedTotal'];
            }
            
            $feeData[$k]['feeType'] = $value['feeType'];
            $feeData[$k]['feeGroup'] = $value['feeGroup']; 
            $feeData[$k]['org_total'] = $value['org_total'];  
            $feeData[$k]['feeGroupname'] = $group;         
        }

        $selectFeeGroupId = getFeeGrpByFeeId($selectedFeeType);
        $selectedFeeAmount = getSFSandSchoolFeeByFeeId( $selectedFeeType, $challanData1['clid'],$challanData1['academicYear'], $challanData1['term'] );
        $selectedFeeGroupName = getFeeGroupbyId($selectFeeGroupId);

        $feeData[] = array('feeType'=> $selectedFeeType, 'feeGroup' => $selectFeeGroupId,'org_total'=> $selectedFeeAmount, 'feeGroupname' => $selectedFeeGroupName );

        $newfee = array('feeType'=> $selectedFeeType, 'feeGroup' => $selectFeeGroupId,'org_total'=> $selectedFeeAmount, 'feeGroupname' => $selectedFeeGroupName );

        // print_r($challanData1);
        foreach ($feeData as $k => $v) {
            $groupdata[$v['feeGroupname']][$v['feeGroup']][$v['feeType']][] = $v['org_total'];
            $groupdata[$v['feeGroupname']][$v['feeGroup']][$v['feeType']][] = getFeeTypebyId($v['feeType']);
        }
        
        $challanData1['feeData'] = $groupdata;
        $challanData1['waivedData'] = $waivedarray;
    } else {
        $challanData1 = 'already exist';
    }  
    echo json_encode($challanData1);
}

/*******EDIT CHALLANS END********/

/***** Non Fee Payment - Start *****/

if (isset($_POST["addnonfeetype"]) && $_POST["addnonfeetype"] == "new")
{
    $n = 0;
    $uid = $_SESSION['myadmin']['adminid'];
    $cname = $_POST['nftype'];
    $des = $_POST['des'];
    $group = $_POST['feegroup'];
    $challan = $_POST['challan'];

    $acc_no="NULL"; 
    
    if (isset($_POST['dayscholar']) && isset($_POST['hosteller'])) {
        $app = 'DH';
    } elseif (isset($_POST['dayscholar'])) {
        $app = 'D';
    } elseif (isset($_POST['hosteller'])) {
        $app = 'H';
    } elseif (isset($_POST['common'])) {
        $app = 'C';
    } else {
        $app = 0;
    }

    $acc_no=(isset($_POST['acc_id']) && !empty($_POST['acc_id']))?"'".trim($_POST['acc_id'])."'":"NULL";

    $query = "SELECT * FROM addnonfeetype('$cname','$des','$uid','$group', '$app','$challan',$acc_no)";
    // print_r($query);exit;
    $run = sqlgetresult($query);

    if ($run['addnonfeetype'] > 0) {
        // print_r($run);
        $_SESSION['success'] = "<p class='success-msg'>Non-Fee Type Added Successfully.</p>";
        header('location:nonfeetype.php');
    } else if ($run['addnonfeetype'] === 0) {
        $_SESSION['error'] = "<p class='error-msg'>Non-Fee Type Already Exist</p>";
        header('location:nonfeetype.php');
    } else {
        createErrorlog($run);
        $_SESSION['error'] = "<p class='error-msg'>Some error has been occured. Please try again.</p>";
        header('location:nonfeetype.php');
    }
}

if (isset($_POST["editnonfeetype"]) && $_POST["editnonfeetype"] == "update")
{
    $id = $_POST['id'];
    $uid = $_SESSION['myadmin']['adminid'];
    $cname = $_POST['nftype'];
    $des = $_POST['des'];
    $group = $_POST['feegroup'];
    $challan = $_POST['challan'];

    $acc_no="NULL"; 
 
    if (isset($_POST['dayscholar']) && isset($_POST['hosteller'])) {
        $app = 'DH';
    } elseif (isset($_POST['dayscholar'])) {
        $app = 'D';
    } elseif (isset($_POST['hosteller'])) {
        $app = 'H';
    } elseif (isset($_POST['common'])) {
        $app = 'C';
    } else {
        $app = 0;
    }

    $acc_no=(isset($_POST['acc_id']) && !empty($_POST['acc_id']))?"'".trim($_POST['acc_id'])."'":"NULL";

    $query = "SELECT * FROM editnonfeetype('$id','$cname','$des','$uid','$group','$app','$challan',$acc_no)";
    $run = sqlgetresult($query);
    // print_r($query);exit;
    if ($run['editnonfeetype'] > 0) {
        // print_r($run);
        $_SESSION['success'] = "<p class='success-msg'>Non-Fee Type Updated Successfully.</p>";
        header('location:nonfeetype.php');
    } else if ($run['editnonfeetype'] === 0) {
        $_SESSION['error'] = "<p class='error-msg'>Non-Fee Type Already Exist</p>";
        header('location:nonfeetype.php');
    } else {
        createErrorlog($run);
        $_SESSION['error'] = "<p class='error-msg'>Some error has been occured. Please try again.</p>";
        header('location:nonfeetype.php');
    }
}

if (isset($_POST['submit']) && $_POST['submit'] == "loadfilterdata")
{
    $streamsql = 'SELECT * FROM streamcheck';
    $streamdata = sqlgetresult($streamsql, true);

    $semestersql = 'SELECT semester FROM addsemesterdata';

    $semesterdata = sqlgetresult($semestersql, true);

    $feesql = 'SELECT * FROM nonfeetypecheck';

    $feedata = sqlgetresult($feesql, true);

    $classql = 'SELECT * FROM classcheck';

    $classdata = sqlgetresult($classql, true);

    $academicsql = 'SELECT * FROM yearcheck';

    $academicdata = sqlgetresult($academicsql, true);

    $arr = array();
    array_push($arr, $streamdata, $semesterdata, $feedata, $classdata, $academicdata);
    echo json_encode($arr);
}

if (isset($_POST['checknonfeeconfig']) && $_POST['checknonfeeconfig'] == 'Add Data')
{
    $class = array();
    $academic_yr = explode('-',getAcademicyrById($_POST['academic'])) ;
    // print_r($academic_yr);
    if ($_POST['semester'] == 'II')
    {
        $feedata = sqlgetresult('SELECT f.class,f.amount,c."displayOrder" FROM tbl_nonfee_configuration f LEFT JOIN tbl_class c ON f."class" = c."id" WHERE f."stream" = \'' . $_POST['stream'] . '\' AND f."semester" = \'' . $_POST['semester'] . '\' AND f."feeType" = \'' . $_POST['feetype'] . '\' AND f."academicYear" = \'' . ($_POST['academic']) . '\' ORDER BY c."displayOrder" ASC ', true);
        if (!empty($feedata) == 0)
        {
            $feedata = sqlgetresult('SELECT f.class,f.amount, c."displayOrder" FROM tbl_nonfee_configuration f LEFT JOIN tbl_class c ON c.id=f.class WHERE f.stream = \'' . $_POST['stream'] . '\' AND f.semester = \'I\'  AND f."feeType" = \'' . $_POST['feetype'] . '\' AND f."academicYear" = \'' . ($_POST['academic']) . '\' ORDER BY c."displayOrder" ASC ', true);
        }
    }
    else if ($academic_yr[0] == (date("Y") + 1))
    {
        $yr = sqlgetresult("select max(id) from tbl_academic_year");
        $yr = $yr['max']-1;

        $feedata = sqlgetresult('SELECT f.class,f.amount,c."displayOrder" FROM tbl_nonfee_configuration f LEFT JOIN tbl_class c ON f."class" = c."id" WHERE f."stream" = \'' . $_POST['stream'] . '\' AND f."semester" = \'' . $_POST['semester'] . '\' AND f."feeType" = \'' . $_POST['feetype'] . '\' AND f."academicYear" = \'' . ($yr) . '\' ORDER BY c."displayOrder" ASC ', true);
    }
    else
    {
        $feedata = sqlgetresult('SELECT f.class,f.amount,c."displayOrder" FROM tbl_nonfee_configuration f LEFT JOIN tbl_class c ON f."class" = c."id" WHERE f."stream" = \'' . $_POST['stream'] . '\' AND f."semester" = \'' . $_POST['semester'] . '\' AND f."feeType" = \'' . $_POST['feetype'] . '\' AND f."academicYear" = \'' . ($_POST['academic']) . '\' ORDER BY c."displayOrder" ASC', true);
    }

    $classdetails = sqlgetresult('SELECT DISTINCT c."displayOrder",c."id",c."class_list" from tbl_student s LEFT JOIN tbl_class c ON s."class"::int = c."id" WHERE s."stream" = \'' . $_POST['stream'] . '\' AND c."class_list" IS NOT NULL ORDER BY c."displayOrder" ASC ',true);

    // $feeClass = array_column($feedata, 'class');
    // $classData = array_column($classdetails, 'id');

    // $diff = array_diff($classData,$feeClass);

    // $diffData = array();

    // foreach ($diff as $k => $v) {
    //     $diffData[$k]['class'] = $v;
    //     $diffData[$k]['amount'] = '';
    //     $diffData[$k]['displayOrder'] = getDisplayOrderById($v);
    // }
    
    // $feedata =  array_merge($feedata,$diffData);

    // array_multisort(array_column($feedata, 'displayOrder'), SORT_ASC, $feedata);
    // // print_r($feedata);echo "<hr/>";
    // print_r($classdetails);echo "<hr/>";
    // print_r($diffData);
    // exit;

    $class['feeData'] = $feedata;
    $class['classdetails'] = $classdetails;
    echo json_encode($class);
}

if (isset($_POST['submit']) && $_POST['submit'] == 'nonfeeconfiguration')
{
    $academicyear = $_POST['academic'];
    $stream = $_POST['stream'];
    $semester = $_POST['semester'];
    $feetype = $_POST['feetype'];
    // $duedate = $_POST['duedate'];
    $createdby = $_SESSION['myadmin']['adminid'];

    $keys = array_keys($_POST);
    $commondata = array();
    $feeClassbased = array();
    // console.log($commondata);
    foreach ($keys as $key)
    {
        // echo $key;
        if (strpos($key, '*') !== false)
        {
            $k = explode('**', $key);
            $feeClassbased[$k[1]] = $_POST[$key];
        }
        else
        {
            $commondata[$key] = $_POST[$key];
        }
    }
    // $feeClassbased = array_filter($feeClassbased); 
    $feeClassbased = array_filter($feeClassbased,"strlen");

    foreach ($feeClassbased as $key => $value)
    {
        // $commondata['feeType'][$key] = $value;       
        $query = "SELECT * FROM addnonfeeconfiguration($academicyear,'$stream','$semester','$feetype','$createdby','$value','$key')";
        // print_r($_POST);
        // print_r($query);
        // exit;
        $result = sqlgetresult($query);
        // print_r($result);echo "<hr/>";
        if ($result['addnonfeeconfiguration'] == '1')
        {
            $_SESSION['success'] = "<p class='success-msg'>Data Inserted Successfully</p>";
        }
        elseif ($result['addnonfeeconfiguration'] == '0')
        {
            $_SESSION['error'] = "<p class='error-msg'>Same Configurtion Data Already Exists.</p>";
        }
        else
        {
            createErrorlog($result);
            $_SESSION['error'] = "<p class='error-msg'>Some error has occurred Please Try Again Later.</p>";
        }
    }

    // exit;
    header('location:nonfeeconfig.php');

}

if (isset($_POST['filter']) && $_POST['filter'] == "filternonfeeconfiguration")
{
    $whereClauses = array(); 
    if (! empty($_POST['classselect'])) 
        $whereClauses[] ='"class_list_id"=\''.pg_escape_string($_POST['classselect']).'\' ' ;
    $where='';

    if (! empty($_POST['streamselect'])) 
      $whereClauses[] ="streamid='".pg_escape_string($_POST['streamselect'])."'"; 
    $where = ''; 

    if (count($whereClauses) > 0) { 
      $where = ' WHERE '.implode(' AND ',$whereClauses); 
    }

    $sql = ('SELECT * FROM  nonfeeconigdata'. $where);
    // echo $sql;
    $res = sqlgetresult($sql, true);
    
    echo json_encode($res);
}

if (isset($_POST['editnonfeeconfig']) && $_POST['editnonfeeconfig'] == 'update')
{

    $stream = $_POST['stream'];
    $semester = trim($_POST['semester']);
    $feetype = $_POST['feeType'];
    $duedate = $_POST['dueDate'];
    $updatedby = $_SESSION['myadmin']['adminid'];
    $class = $_POST['class'];
    $amt = round($_POST['amount']);
    $id = $_POST['id'];
    $academicyear = $_POST['academic'];

    $result = sqlgetresult("SELECT * FROM editnonfeeconfiguration('$academicyear','$stream','$semester','$feetype','$updatedby','$amt','$class','$id')");
    // print_r($result);exit;
    if ($result['editnonfeeconfiguration'] == '1')
    {
        $_SESSION['success'] = "<p class='success-msg'>Data Updated Successfully</p>";
    }
    elseif ($result['editnonfeeconfiguration'] == '0')
    {
        $_SESSION['error'] = "<p class='error-msg'>Same Configurtion Data Already Exists.</p>";
    }
    else
    {
        createErrorlog($result);
        $_SESSION['error'] = "<p class='error-msg'>Some error has occurred Please Try Again Later.</p>";
    }
    header('location:nonfeeconfig.php');
}

if (isset($_POST['submit']) && $_POST['submit'] == "nonfeeselection")
{
    if($typename == "Stream Wise")  {
        $streamid = $_POST['stream'];
        $nonfeetypequerry = sqlgetresult('SELECT * FROM tbl_student WHERE "stream" =\'' . $streamid . '\'',true);
    } else if($typename == "Class Wise") {
        $streamid = $_POST['stream'];
        $classid = $_POST['class'];
        $nonfeetypequerry = sqlgetresult('SELECT * FROM tbl_student WHERE "stream" =\'' . $streamid . '\' AND  "class" =\'' . $classid . '\'',true);
    } else if($typename == "Section Wise") {
        $streamid = $_POST['stream'];
        $classid = $_POST['class'];
        $sectonid = $_POST['section'];

        $nonfeetypequerry = sqlgetresult('SELECT * FROM tbl_student WHERE "stream" =\'' . $streamid . '\' AND  "class" =\'' . $classid . '\' AND "section" =\'' . $sectionid . '\'',true);
    } else {
        $studId = $_POST['type'];
        $nonfeetypequerry = sqlgetresult('SELECT * FROM tbl_student WHERE "studentId" =\'' . $studId . '\' ',true);  
    }

    echo json_encode($challanData1);
}

if (isset($_POST['nonfeetypefilter']) && $_POST['nonfeetypefilter'] == "nonfeetypechallanfilter")
{
    //print_r($_POST);    

    $whereClauses = array(); 
    if (! empty($_POST['classselect'])) 
        $whereClauses[] ='"class"=\''.pg_escape_string($_POST['classselect']).'\' ' ;
    $where='';

    if (! empty($_POST['streamselect'])) 
      $whereClauses[] ="stream='".pg_escape_string($_POST['streamselect'])."'"; 
    $where = ''; 

    if (! empty($_POST['sectionselect'])) 
      $whereClauses[] ="section='".pg_escape_string($_POST['sectionselect'])."'"; 
    $where = ''; 

    if (! empty($_POST['studentid'])) 
      $whereClauses[] ='"studentId"=\''.pg_escape_string($_POST['studentid']).'\' ' ;
    $where = '';    

    if (count($whereClauses) > 0) { 
        $where = ' WHERE '.implode(' AND ',$whereClauses); 
    }

    $sql = ('SELECT * FROM  studentcheck'. $where);   
    $res = sqlgetresult($sql, true);
    $_SESSION['data'] = $res;
    header('Location: addnonfeechallan.php');
}
/* Feetype Challan Filter for Additional Challan Start */
if (isset($_POST['feetypefilter']) && $_POST['feetypefilter'] == "feetypefilter")
{
    //print_r($_POST);    

    $whereClauses = array(); 
    if (! empty($_POST['classselect'])) 
        $whereClauses[] ='"class"=\''.pg_escape_string($_POST['classselect']).'\' ' ;
    $where='';

    if (! empty($_POST['streamselect'])) 
      $whereClauses[] ="stream='".pg_escape_string($_POST['streamselect'])."'"; 
    $where = ''; 

    if (! empty($_POST['sectionselect'])) 
      $whereClauses[] ="section='".pg_escape_string($_POST['sectionselect'])."'"; 
    $where = ''; 

    if (! empty($_POST['studentid'])) 
      $whereClauses[] ='"studentId"=\''.pg_escape_string($_POST['studentid']).'\' ' ;
    $where = '';    

    if (count($whereClauses) > 0) { 
        $where = ' WHERE '.implode(' AND ',$whereClauses); 
    }

    $sql = ('SELECT * FROM  studentcheck'. $where);   
    $res = sqlgetresult($sql, true);
    $_SESSION['data'] = $res;
    header('Location: addfeechallan.php');
}

function toGenerateChallanSequnceNumber($streamName){
    $lastrecordID = sqlgetresult("SELECT SETVAL('tbl_temp_challans_id_seq',MAX(id)) AS max FROM tbl_temp_challans");
    if (!ctype_digit(strval($lastrecordID['max'])))
    {
        $challanNo = trim($streamName) . date('Y') . '/000001';
    }
    else
    {
        $no = str_pad(++$lastrecordID['max'], 6, '0', STR_PAD_LEFT);;
        $challanNo = trim($streamName) . date('Y') . '/' . $no;
    }
    return $challanNo;
}

/* Create fee group challan */
if (isset($_POST['showfeechallan']) && $_POST['showfeechallan'] == "showfeechallan") {

    unset($_SESSION['createdchallanids']);
    $class = isset($_POST['class_list'])?trim($_POST['class_list']):"";
    $term = isset($_POST['semester'])?trim($_POST['semester']):"";
    $feetypes = isset($_POST['selected_feetypes'])?trim($_POST['selected_feetypes']):"";
    $createdby = isset($_SESSION['myadmin']['adminid'])?trim($_SESSION['myadmin']['adminid']):"";
    $stream = isset($_POST['stream'])?trim($_POST['stream']):"";
    $remarks = isset($_POST['remarks'])?trim($_POST['remarks']):"";
    $duedate = isset($_POST['duedate'])?trim($_POST['duedate']):"";
    //$academic = 7;
    $streamName = getStreambyId($stream);    
    $id = isset($_POST['studentId'])?trim($_POST['studentId']):"";
    $studentId=$id;
    $name = isset($_POST['studentName'])?trim($_POST['studentName']):"";
    $selectedData = array();
    $feeData = explode(',', $feetypes);

    /* Active Academic Year*/
    $academicId = isset($_POST['academicId'])?trim($_POST['academicId']):"";
    $type = isset($_POST['type'])?trim($_POST['type']):"";
    //$feegrp = getFeeGroupbyName('NON-FEE');
    $streamName = getStreambyId($stream);
    if($type == 'single'){
       $groupdata=array();
       $feetypedata = sqlgetresult('SELECT * FROM getfeetypedata WHERE class=\'' . $class . '\' AND semester=\'' . $term . '\' AND stream = \'' . ($stream) . '\' AND "academicYear" = \'' . ($academicId) . '\' ', true);    
        foreach ($feeData as $k => $v)
        {
            $v=trim($v);
            foreach ($feetypedata as $val)
            {
                $gid=trim($val['id']);
                if ( $v == $gid)
                {                
                    $gfeeGroup=trim($val['feeGroup']);
                    $group = getFeeGroupbyId($gfeeGroup);
                    $groupdata[$gfeeGroup][$gid][] = trim($val['amount']);
                    $groupdata[$gfeeGroup][$gid][] = trim($val['feename']);
                    $groupdata[$gfeeGroup][$gid][] = trim($gfeeGroup);
                    $groupdata[$gfeeGroup][$gid][] = trim($group);
                }
            }
        }
    
        if (count($groupdata) > 0)
        {
            /* Call Generate Challan Sequnce Number */
            $challanNo=toGenerateChallanSequnceNumber($streamName);
            $exists_fee_type=array();
            $challanData="";
            $feedata=array();
            foreach ($groupdata as $grp => $data)
            {     
                foreach ($data as $k => $val)
                {       
                    $amt=$val[0];
                    $ftype=$val[2];
                    $fname=$val[1];
                    $sql = "SELECT * FROM createtempchallanadditionalfee('$challanNo','$studentId','$createdby','".$k."','$stream','$class','$term','$name','".$amt."','$remarks','$duedate','".$ftype."','$academicId')";    
                    //echo $sql;
                    //exit;
                    $result = sqlgetresult($sql);  
                   if ($result['createtempchallanadditionalfee'] > 0) {
                        $challanData = sqlgetresult('SELECT * FROM tempChallan WHERE "challanNo"=\'' . $challanNo . '\' ORDER BY id ASC LIMIT 1');
                        $group_name=$val[3];
                        $group_id=$val[2];

                        $feedata[$group_name][$group_id][$k][] = $amt;
                        $feedata[$group_name][$group_id][$k][] = $fname;
                    }else{
                        $exists_fee_type[$k][] = $val[1];
                    }
                }            
            }
            $selectedData['feeData'] = $feedata;
            $selectedData['is_exists']=count($exists_fee_type);
            $selectedData['exists']=$exists_fee_type;
            $selectedData['challanData'] = $challanData;
        } else {
            $selectedData = 'Fee Types empty';
        }
    }else{
        if(isset($_SESSION['selectedfeechallans']) && count($_SESSION['selectedfeechallans']) > 0 ) {
            $selectedIds  = $_SESSION['selectedfeechallans'];
            $createdChallans=[];
            foreach ($selectedIds as $k => $id) {
                $groupdata = array();
                $studentData = sqlgetresult('SELECT class, term, stream, "academic_yr" AS "academicYear","studentId" FROM tbl_student WHERE "studentId" = \''.trim($id).'\' LIMIT 1 ');
                $studentData = array_map('trim', $studentData);
                $class = $studentData['class'];
                $term = $studentData['term'];
                $stream = $studentData['stream'];
                //$academicId = $studentData['academicYear'];
                $studentId = $studentData['studentId'];
                $name = $studentData['studentName'];
                $streamName = getStreambyId($stream); 
                // print_r($studentData);
                $feetypedata = sqlgetresult('SELECT * FROM getfeetypedata WHERE class=\''.$class.'\' AND semester=\''.$term.'\' AND stream = \''.$stream.'\' AND "academicYear" = \''.$academicId.'\' ', true); 
               if(count($feetypedata) > 0)
                {
                   foreach ($feeData as $k => $v)
                   {
                    $v=trim($v);
                    foreach ($feetypedata as $val)
                    {
                        $gid=trim($val['id']);
                        if ( $v == $gid)
                        {                
                            $gfeeGroup=trim($val['feeGroup']);
                            $group = getFeeGroupbyId($gfeeGroup);
                            $groupdata[$gfeeGroup][$gid][] = trim($val['amount']);
                            $groupdata[$gfeeGroup][$gid][] = trim($val['feename']);
                            $groupdata[$gfeeGroup][$gid][] = trim($gfeeGroup);
                            $groupdata[$gfeeGroup][$gid][] = trim($group);
                        }
                    }
                   }
               }

                if (count($groupdata) > 0)
                {
                    /* Call Generate Challan Sequnce Number */
                    $challanNo=toGenerateChallanSequnceNumber($streamName);
                    $exists_fee_type=array();
                    $challanData="";
                    $feedata=array();
                    foreach ($groupdata as $grp => $data)
                    {     
                        foreach ($data as $k => $val)
                        {       
                            $amt=$val[0];
                            $ftype=$val[2];
                            $fname=$val[1];
                            $sql = "SELECT * FROM createtempchallanadditionalfee('$challanNo','$studentId','$createdby','".$k."','$stream','$class','$term','$name','".$amt."','$remarks','$duedate','".$ftype."','$academicId')";    
                            //echo $sql;
                            //exit;
                            $result = sqlgetresult($sql);  
                            //print_r($result);
                            //exit;               
                           if ($result['createtempchallanadditionalfee'] > 0) {
                                array_push($createdChallans, $challanNo);
                                $challanData = sqlgetresult('SELECT * FROM tempChallan WHERE "challanNo"=\'' . $challanNo . '\' ORDER BY id ASC LIMIT 1');
                                $group_name=$val[3];
                                $group_id=$val[2];
                                $feedata[$group_name][$group_id][$k][] = $amt;
                                $feedata[$group_name][$group_id][$k][] = $fname;
                            }else{
                                $exists_fee_type[$k][] = $val[1];
                            }
                        }            
                    }
                    if(count($createdChallans) >0){
                        $_SESSION['createdchallanids']=array_unique($createdChallans);
                        $challanData['challanNo']=$createdChallans[0];
                        //$challanData['challanNo']=implode(",",$_SESSION['createdchallanids']);
                    }
                    $selectedData['feeData'] = $feedata;
                    $selectedData['is_exists']=count($exists_fee_type);
                    $selectedData['exists']=$exists_fee_type;
                    $selectedData['challanData'] = $challanData;
                } else {
                    $selectedData = 'Fee Types empty';
                }
            }
        }
    }
    echo json_encode($selectedData);
}

if (isset($_POST["submit"]) && $_POST["submit"] == "createfeechallan")
{   
    if (!empty($_POST['checkme']))
    {
        $selectedchallans = [];
        foreach ($_POST['checkme'] as $selected)
        {
            // echo $selected."</br>";s
            array_push($selectedchallans, $selected);
        }
        $_SESSION['selectedfeechallans'] = $selectedchallans;
        header('location:feechallancreation.php');
    }
    echo json_encode($selectedchallans);
}
/* Feetype Challan Filter for Additional Challan End */
if (isset($_POST['shownonfeechallan']) && $_POST['shownonfeechallan'] == "shownonfeechallan") {

    $class = trim($_POST['class_list']);
    $term = trim($_POST['semester']);
    $feetypes = trim($_POST['selected_feetypes']);
    $createdby = trim($_SESSION['myadmin']['adminid']);
    $stream = trim($_POST['stream']);
    $remarks = trim($_POST['remarks']);
    $duedate = trim($_POST['duedate']);
    $academic = trim($_POST['academic']);
    $streamName = getStreambyId($stream);    
    $id = trim($_POST['studentId']);
    $name = trim($_POST['studentName']); 
    $selectedData = array();
    $nonfeeData = explode(',', $feetypes);
    $academicId = trim($_POST['academic']);
    $feegrp = getFeeGroupbyName('NON-FEE');

    $chkp="NULL";
    $instalments="NULL";
    if(isset($_POST['chkpartial'])){
        $chkp="'".trim($_POST['chkpartial'])."'";
        $instalments=(isset($_POST['instalment'])&&!empty($_POST['instalment']))?"'".trim($_POST['instalment'])."'":"NULL";
    }

    // print_r(sizeof($_SESSION['selectednonfeechallans']));

    if( isset($_SESSION['selectednonfeechallans']) && sizeof($_SESSION['selectednonfeechallans']) > 0 ) {
        $selectedIds  = $_SESSION['selectednonfeechallans'];

        foreach ($selectedIds as $k => $id) {
            $groupdata = array();
            $studentData = sqlgetresult('SELECT class, term, stream, "academic_yr" AS "academicYear" FROM tbl_student WHERE "studentId" = \''.trim($id).'\' LIMIT 1 ');
            $studentData = array_map('trim', $studentData);
            $nonfeetypedata = sqlgetresult('SELECT * FROM getnonfeetypedata WHERE class=\'' . $studentData['class'] . '\' AND semester=\'' . $studentData['term'] . '\' AND stream = \'' . $studentData['stream'] . '\' AND "academicYear" = \'' . $studentData['academicYear'] . '\' ', true); 

            // echo 'SELECT * FROM getnonfeetypedata WHERE class=\'' . $studentData['class'] . '\' AND semester=\'' . $studentData['term'] . '\' AND stream = \'' . $studentData['stream'] . '\' AND "academicYear" = \'' . $studentData['academicYear'] . '\' ';
           
            if ( count($nonfeetypedata) > 0 ) {               
                foreach ($nonfeeData as $k => $v)
                {
                    foreach ($nonfeetypedata as $val)
                    {
                        if ( $v == trim($val['id']))
                        {                
                            $groupdata[$val['feeGroup']][$val['id']][] = $val['amount'];
                            $groupdata[$val['feeGroup']][$val['id']][] = $val['feename'];
                            $groupdata[$val['feeGroup']][$val['id']][] = $val['challan'];
                        }
                    }
                }
               $selectedData['feeData'] = $groupdata;
            }           
           

            if (sizeof($groupdata) > 0)
            {
                $lastrecordID = sqlgetresult("SELECT SETVAL('tbl_nonfee_challans_id_seq',MAX(id)+1) AS max FROM tbl_nonfee_challans");
                if (!ctype_digit(strval($lastrecordID['max'])))
                {
                    $challanNo = trim($streamName) . date('Y') . 'NF/00000001';
                }
                else
                {
                    $no = str_pad(++$lastrecordID['max'], 8, '0', STR_PAD_LEFT);;
                    $challanNo = trim($streamName) . date('Y') . 'NF/' . $no;
                }
                foreach ($groupdata as $grp => $data)
                {     
                    foreach ($data as $k => $val)
                    {       
                        $sql = "SELECT * FROM createnonfeechallannew('$challanNo','$id','".$studentData['class']."','".$k."','".$studentData['term']."','$createdby','".$val[0]."','".$studentData['stream']."','$remarks','$duedate','$feegrp','".$studentData['academicYear']."','".$val[2]."',$chkp,$instalments)";    
                        // echo $sql;
                        $result = sqlgetresult($sql);                 
                        if ($result['createnonfeechallannew'] == '0') {
                            $challanData['exist'] = 'Challan Already Exists';
                        } else if ($result['createnonfeechallannew'] > '0') {
                            sendNotificationToParents($id, $_POST['mail_content'],$_POST['sms_content'],  "nonfeechallan");
                        }
                    }            
                }
                // echo $challanNo;
                $challanData = sqlgetresult('SELECT * FROM tbl_nonfee_challans WHERE "studentId"=\'' . $challanNo . '\' ORDER BY id ASC LIMIT 1');
                $selectedData['challanData'] = $challanData;
            } else {
                $selectedData = "Fee Types empty";
            }

        }

    } else {
        $nonfeetypedata = sqlgetresult('SELECT * FROM getnonfeetypedata WHERE class=\'' . $class . '\' AND semester=\'' . $term . '\' AND stream = \'' . ($stream) . '\' AND "academicYear" = \'' . ($academicId) . '\' ', true);    

        foreach ($nonfeeData as $k => $v)
        {
            foreach ($nonfeetypedata as $val)
            {
                if ( $v == trim($val['id']))
                {                
                    $groupdata[$val['feeGroup']][$val['id']][] = $val['amount'];
                    $groupdata[$val['feeGroup']][$val['id']][] = $val['feename'];
                    $groupdata[$val['feeGroup']][$val['id']][] = $val['challan'];
                }
            }
        }
        
        $selectedData['feeData'] = $groupdata;
        // $selectedData['groupsoffee'] = $groupsoffee;

        if ($groupdata != 0)
        {
            $lastrecordID = sqlgetresult("SELECT SETVAL('tbl_nonfee_challans_id_seq',MAX(id)+1) AS max FROM tbl_nonfee_challans");
            if (!ctype_digit(strval($lastrecordID['max'])))
            {
                $challanNo = trim($streamName) . date('Y') . 'NF/00000001';
            }
            else
            {
                $no = str_pad(++$lastrecordID['max'], 8, '0', STR_PAD_LEFT);;
                $challanNo = trim($streamName) . date('Y') . 'NF/' . $no;
            }
            foreach ($groupdata as $grp => $data)
            {     
                foreach ($data as $k => $val)
                {       
                    $sql = "SELECT * FROM createnonfeechallannew('$challanNo','$id','$class','".$k."','$term','$createdby','".$val[0]."','$stream','$remarks','$duedate','$feegrp','$academicId','".$val[2]."',$chkp,$instalments)";    
                    // echo $sql;exit;
                    $result = sqlgetresult($sql);                 
                    if ($result['createnonfeechallannew'] == '0') {
                        $challanData['exist'] = 'Challan Already Exists';
                    } else if ($result['createnonfeechallannew'] > 0) {
                        sendNotificationToParents($id, $_POST['mail_content'],$_POST['sms_content'], "nonfeechallan");
                    }
                }            
            }
            // echo $challanNo;
            $challanData = sqlgetresult('SELECT * FROM tbl_nonfee_challans WHERE "studentId"=\'' . $challanNo . '\' ORDER BY id ASC LIMIT 1');
            $selectedData['challanData'] = $challanData;
        } else {
            $selectedData = 'Fee Types empty';
        }
    }   

    echo json_encode($selectedData);
}

if (isset($_POST["submit"]) && $_POST["submit"] == "createnonfeechallan")
{   
    if (!empty($_POST['checkme']))
    {
        $selectedchallans = [];
        foreach ($_POST['checkme'] as $selected)
        {
            // echo $selected."</br>";s
            array_push($selectedchallans, $selected);
        }
        $_SESSION['selectednonfeechallans'] = $selectedchallans;
        header('location:nonfeechallancreation.php');
    }
    echo json_encode($selectedchallans);
}

if (isset($_POST["submit"]) && $_POST["submit"] == "updatenonfeechallan")
{   
    // print_r($_POST); exit;
    if (!empty($_POST['checkme']))
    {
        $selectedchallansupd = [];
        foreach ($_POST['checkme'] as $selected)
        {
            // echo $selected."</br>";s
            array_push($selectedchallansupd, $selected);
        }
        $_SESSION['selectednonfeechallansupd'] = $selectedchallansupd;
        header('location:editnonfeecreatedchallans.php');
    }
    echo json_encode($selectedchallans);
}

if (isset($_POST['filter']) && $_POST['filter'] == "filternonfeecreatedchallan")

{
    $whereClauses = array(); 
    if (! empty($_POST['classselect'])) 
        $whereClauses[] ='"clid"=\''.pg_escape_string($_POST['classselect']).'\' ' ;
    $where='';

    if (! empty($_POST['streamselect'])) 
      $whereClauses[] ="stream='".pg_escape_string($_POST['streamselect'])."'"; 
    $where = ''; 

    if (! empty($_POST['sectionselect'])) 
      $whereClauses[] ="section='".pg_escape_string ($_POST['sectionselect'])."'"; 
    $where = ''; 

    if (count($whereClauses) > 0) { 
      $where = ' WHERE '.implode(' AND ',$whereClauses); 
    }

    $sql = ('SELECT * FROM  nonfeechallandata'. $where);
    // echo $sql;
    $res = sqlgetresult($sql, true);
    
    echo json_encode($res);
}

if (isset($_POST['updatenonfeeChallan']) && $_POST['updatenonfeeChallan'] == "updatenonfeeChallan")
{
    print_r($_POST); echo "<hr/>";
    $rowid = trim($_POST['id']);
    $challanNo = trim($_POST['challan']);
    $id = trim($_POST['studentId']);
    $name =trim($_POST['studentName']);
    $term = trim($_POST['semester']);
    $class = trim($_POST['class_list']);
    $stream = trim($_POST['stream']);
    $feegroup = trim($_POST['feegroup']);
    //$academic = getAcademicyrIdByName(trim($_POST['academic']));
    $academic = trim($_POST['academic']);
    $instalments="NULL";

    if (isset($_POST['duedate'])) {
        $duedate = $_POST['duedate'];
    } else {
        $duedate = $_POST['oldduedate'];
    }

    if ((isset($_POST['selected_feetype'])) && ($_POST['selected_feetype'] != '')) {
        $feetypes = $_POST['selected_feetype'];        
    } else {
        $feetypes = $_POST['selectedfeetype'];       
    }

    $createdby = $_SESSION['myadmin']['adminid'];
    if (isset($_POST['remarks'])) {
        $remarks = $_POST['remarks'];
    } else {
        $remarks = $_POST['oldremarks'];
    }

    $chkp="NULL";
    if(isset($_POST['chkpartial'])){
        $chkp="'".trim($_POST['chkpartial'])."'";
        $instalments=(isset($_POST['instalment'])&&!empty($_POST['instalment']))?"'".trim($_POST['instalment'])."'":"NULL";

        $old_inst=(isset($_POST['old_inst'])&&!empty($_POST['old_inst']))?trim($_POST['old_inst']):"";
        if($old_inst && $old_inst!=$instalments){
            $paidSoFor = getAmtPaidbyNFWChallan($challanNo);
            if($paidSoFor){
                $_SESSION['error'] = "<p class='error-msg'>Cannot change the installment.</p>";
                header('location:createnonfeechallans.php');
                exit;
            }
        }
    }

    $nonfeetypedata = sqlgetresult('SELECT * FROM getnonfeetypedata WHERE class=\'' . $class . '\' AND semester=\'' . $term . '\' AND stream = \'' . $stream . '\' AND "academicYear" = \''.$academic.'\' AND "feeType" = \''.$feetypes.'\' ');   

    // print_r($nonfeetypedata);

    if( sizeof($nonfeetypedata) > 0 ) {
        $qry = sqlgetresult("SELECT * FROM updatenonfeechallannew('$challanNo','$id','$class','".$feetypes."','$term','$createdby','".$nonfeetypedata['amount']."','$stream','$remarks','$duedate','2','$academic',$chkp,$instalments)");    

        if( $qry['updatenonfeechallannew'] > 0 ) {
            $_SESSION['success'] = "<p class='success-msg'>Non-Fee challan updated Successfully.</p>";
            header('location:createnonfeechallans.php');
        } else if( $qry['updatenonfeechallannew'] == 0 ) {
            $_SESSION['error'] = "<p class='error-msg'>Non-Fee challan Already Exist</p>";
            header('location:createnonfeechallans.php');
        } else {
            createErrorlog($run);
            $_SESSION['error'] = "<p class='error-msg'>Some error has been occured. Please try again.</p>";
            header('location:createnonfeechallans.php');
        }        
    } else{
        $_SESSION['error'] = "<p class='error-msg'>No Fee data available.</p>";
        header('location:createnonfeechallans.php');
    }  
    
    // $result = sqlgetresult($sql); 
}

if (isset($_POST['filter']) && $_POST['filter'] == "filtertopupchallan")
{
    $whereClauses = array(); 
    if (! empty($_POST['classselect'])) 
        $whereClauses[] ='"clid"=\''.pg_escape_string($_POST['classselect']).'\' ' ;
    $where='';

    if (! empty($_POST['streamselect'])) 
      $whereClauses[] ="stream='".pg_escape_string($_POST['streamselect'])."'"; 
    $where = ''; 

    if (! empty($_POST['sectionselect'])) 
      $whereClauses[] ="section='".pg_escape_string ($_POST['sectionselect'])."'"; 
    $where = ''; 

    if (count($whereClauses) > 0) { 
      $where = ' WHERE '.implode(' AND ',$whereClauses); 
    }

    $sql = ('SELECT * FROM  topupdata'. $where);
    // echo $sql;
    $res = sqlgetresult($sql, true);
    
    echo json_encode($res);
}

if( isset($_GET['excel']) && $_GET['excel'] == 'nonfeepaid' ) {

    if( $_GET['common'] == 0) {
        $paiddata = sqlgetresult('SELECT * FROM nonfeechallanpaid WHERE "challanStatus" = 1 ', true);
    } else {
        $commonfeedata = sqlgetresult('SELECT np."challanNo", np."studentId", np."amount", np."transDate", DATE(np."createdOn") AS "createdOn", s."studentName",s."term", st.stream, c.class_list, s.section  FROM tbl_nonfee_payments np LEFT JOIN tbl_student s ON s."studentId" = np."studentId" OR s."application_no" = np."studentId" LEFT JOIN tbl_stream st ON s.stream::int = st.id LEFT JOIN tbl_class c ON s.class::int = c.id WHERE "transStatus" = \'Ok\' AND "challanNo" ILIKE \'%EVENT%\' ', true);
        $paiddata = array();
        foreach ($commonfeedata as $k => $val) {
            $eventId = explode('-', $val['challanNo'])[1]; 
            $paiddata[$k] = $val;
            $paiddata[$k]['eventname'] = getNonfeeTypeById($eventId);
        }
    }

    foreach ($paiddata  as $k => $v) {
        $keys = array();
        $values = array();
        foreach ($v as $field_code => $field_val)
        {       
            if($field_val != '') {
                $keys[]=$field_code;                
                $values[$field_code]=pg_escape_string($field_val); 
            }       
        }
        $columns = $keys;
        $data[] = $values;        
    }
    // print_r($data);
    // exit;
    exportData($data, 'Non-fee Paid Challans Report', $columns);
}

if( isset($_GET['excel']) && $_GET['excel'] == 'topup' ) {

    $paiddata = sqlgetresult('SELECT * FROM topupdata ', true);

    foreach ($paiddata  as $k => $v) {
        $keys = array();
        $values = array();
        foreach ($v as $field_code => $field_val)
        {       
            if($field_val != '') {
                $keys[]=$field_code;                
                $values[$field_code]=pg_escape_string($field_val); 
            }       
        }
        $columns = $keys;
        $data[] = $values;        
    }
    // print_r($data);
    // exit;
    exportData($data, 'Top-up Paid Report', $columns);
}

if (isset($_POST['filter']) && $_POST['filter'] == "filternonfeepaidchallan")
{
    $whereClauses = array(); 

    if( $_POST['common'] == 0 ) {

        if (! empty($_POST['classselect'])) 
            $whereClauses[] ='"clid"=\''.pg_escape_string($_POST['classselect']).'\' ' ;
        $where='';

        if (! empty($_POST['streamselect'])) 
          $whereClauses[] ="stream='".pg_escape_string($_POST['streamselect'])."'"; 
        $where = ''; 

        if (! empty($_POST['sectionselect'])) 
          $whereClauses[] ="section='".pg_escape_string($_POST['sectionselect'])."'"; 
        $where = ''; 

        if (count($whereClauses) > 0) { 
          $where = ' WHERE '.implode(' AND ',$whereClauses) .' AND  "challanStatus" = 1 '; 
        }

        $sql = ('SELECT * FROM  nonfeechallanpaid'. $where);
        $res = sqlgetresult($sql, true);
    } else {
        if (! empty($_POST['classselect'])) 
            $whereClauses[] ="s.class='".pg_escape_string($_POST['classselect'])."'"; 
        $where='';

        if (! empty($_POST['streamselect'])) 
          $whereClauses[] ="s.stream='".pg_escape_string($_POST['streamselect'])."'"; 
        $where = ''; 

        if (! empty($_POST['sectionselect'])) 
          $whereClauses[] ="s.section='".pg_escape_string($_POST['sectionselect'])."'"; 
        $where = ''; 

        if (count($whereClauses) > 0) { 
          $where = 'AND '.implode(' AND ',$whereClauses) ; 
        }

        $commonfeedata = sqlgetresult('SELECT np."challanNo", np."studentId", np."amount" AS total, np."transDate" AS "updatedOn", DATE(np."createdOn") AS "createdOn", s."studentName",s."term", st.stream AS steamname, c.class_list, s.section  FROM tbl_nonfee_payments np LEFT JOIN tbl_student s ON s."studentId" = np."studentId" LEFT JOIN tbl_stream st ON s.stream::int = st.id LEFT JOIN tbl_class c ON s.class::int = c.id WHERE "transStatus" = \'Ok\' AND "challanNo" ILIKE \'%EVENT%\' '.$where, true);
        
        $paiddata = array();
        foreach ($commonfeedata as $k => $val) {
            $eventId = explode('-', $val['challanNo'])[1]; 
            $paiddata[$k] = $val;
            $paiddata[$k]['feename'] = getNonfeeTypeById($eventId);
        }
        $res = $paiddata;
    }
    
    echo json_encode($res);
}

/************* Non Fee payment - End ********/

/************* Demand Receipt Report - Start ********/

if( isset($_POST['submit']) && $_POST['submit'] == 'getFeeTypeData' ) {
    $feeGrpId = $_POST['data'];

    $feeTypeData = sqlgetresult(' SELECT id AS value, "feeType" AS label FROM tbl_fee_type WHERE  "feeGroup" = \''.$feeGrpId.'\' ORDER BY id ASC', true);

    echo json_encode($feeTypeData);
}

if (isset($_POST['submit']) && $_POST['submit'] == "viewdemandreport")
{
    $cid = $_POST['cid'];
    $studId = $_POST['studId'];
    $challanData = sqlgetresult('SELECT * FROM getdemanddatanew WHERE "studentId" =\'' . $studId . '\' AND  "challanNo" = \'' . $cid . '\'',true);
       
    $challanData1 = array();
    $feedata = array();
    $waivedarray = array();

    foreach ($challanData as $data) {
        $challanData1['academicYear'] = getAcademicyrById($data['academicYear']);
        $challanData1['studentId'] = $data['studentId'];
        $challanData1['studentName'] = $data['studentName'];
        $challanData1['challanNo'] = $data['challanNo'];
        $challanData1['streamName'] = $data['streamName'];        
       
        $challanData1['total'] = $data['total'];    
        $challanData1['org_total'] = $data['org_total'];
        $challanData1['createdOn'] = date("d-m-Y", strtotime($data['createdOn']));
        $challanData1['adminName'] = $data['adminName']; 
        $challanData1['updatedOn'] = date("d-m-Y", strtotime($data['updatedOn']));   
        $challanData1['parentName'] = $data['parentName'];
        $challanData1['remarks'] = $data['remarks'];
        $challanData1['duedate'] = date("d-m-Y", strtotime($data['duedate']));
        if($data['feeGroup'] != ''){
            $feeGroup = $data['feeGroup'];
        }
        else{
            $feeGroup = "LATE FEE";
        }
        if($data['feeType'] != ''){
            $feetype = $data['feeType'];
        }
        else{
            $feetype = "Late Fee";
        }
        $feedata[$feeGroup][$feetype][] = $data['total'];
        $feedata[$feeGroup][$feetype][] = $feetype;
    }

    $challanData1['feeData'] = $feedata;
    echo json_encode($challanData1);
}

if (isset($_POST['submit']) && $_POST['submit'] == "viewreceiptreport")
{
    // print_r($_POST);
    $cid = $_POST['cid'];
    $studId = $_POST['studId'];
    $challanData = sqlgetresult('SELECT * FROM getreceiptdata WHERE "studentId" =\'' . $studId . '\' AND  "challanNo" = \'' . $cid . '\'',true);
    
       
    $challanData1 = array();
    $feedata = array();
    $waivedarray = array();

    foreach ($challanData as $data) {
        $challanData1['academicYear'] = getAcademicyrById($data['academicYear']);
        $challanData1['studentId'] = $data['studentId'];
        $challanData1['studentName'] = $data['studentName'];
        $challanData1['challanNo'] = $data['challanNo'];
        $challanData1['streamName'] = $data['streamName'];    
      
        $challanData1['total'] = $data['total'];    
        $challanData1['org_total'] = $data['org_total'];
        $challanData1['createdOn'] = date("d-m-Y", strtotime($data['createdOn']));
        $challanData1['adminName'] = $data['adminName']; 
        $challanData1['updatedOn'] = date("d-m-Y", strtotime($data['updatedOn']));   
        $challanData1['parentName'] = $data['parentName'];
        $challanData1['remarks'] = $data['remarks'];
        $challanData1['duedate'] = date("d-m-Y", strtotime($data['duedate']));
        $challanData1['paid_date'] = $data['paid_date'];    
        $challanData1['pay_type'] = $data['pay_type'];    
        $challanData1['bank'] = $data['bank'];    
        $challanData1['cheque_dd_no'] = $data['cheque_dd_no'];    
        $challanData1['waivedPercentage'] = $data['waivedPercentage'];    
        $challanData1['waivedAmount'] = $data['waivedAmount'];    
        $challanData1['waivedType'] = $data['waivedType']; 
        $challanData1['chequeRemarks'] = $data['chequeRemarks'];
        // $feegroupdetails = trim(getFeeGroupbyId($data['feeGroup']));
        $challanData1['feeGroup'] = $data['feeGroup'];

        if(trim($data['feeGroup']) != "LATE FEE"){
            // $group = getFeeGroupbyId($data['feeGroup']);
            $waivedarray[$feegroupdetails] = $data['waivedTotal'];
        }
        else{
            $challanData1['waivedTotal'] = $data['waivedTotal'];
        }

        $feedata[$data['feeGroup']][$data['feeType']][] = $data['total'];
        $feedata[$data['feeGroup']][$data['feeType']][] = $data['feeType'];
        // $feedata[$data['feeGroup']][$data['id']][] = $data['feename'];
    }

    $challanData1['feeData'] = $feedata;
    $challanData1['waivedData'] = $waivedarray;    
    // print_r($challanData1);
    // exit;
    echo json_encode($challanData1);
}
/************* Demand Receipt Report - End ********/

// **********DEMAND CHALLAN FILTER START************//

if (isset($_POST['filter']) && $_POST['filter'] == "filterdemandreport")
{
    $year = trim($_POST['yearselect']);
    $stream = trim($_POST['streamselect']);
    $feegroup = trim($_POST['feegroupselect']);
    $feetype = trim($_POST['feetypeselect']);
    $status = trim($_POST['paidselect']);

    $whereClauses = array(); 
    if (!empty($year)) 
        $whereClauses[] =" \"academicYear\" = ".$year;
    $where='';

    if (!empty($stream)) 
      $whereClauses[] =" stream = ".$stream; 
    $where = ''; 

    if (!empty($feetype))
      $whereClauses[] =" \"feeid\" = ".$feetype;
    $where = ''; 
    
    if (!empty($feegroup))
      $whereClauses[] =" \"feegid\" = ".$feegroup; 
    $where = '';

     if (!empty($status) || $status === '0' ) 
      $whereClauses[] =" \"challanStatus\" = ".$status; 
    $where = '';  

  
    if (count($whereClauses) > 0) 
    { 
      $where = ' WHERE '.implode(' AND ',$whereClauses); 
    }   

    $sql = ('SELECT * FROM  getdemanddatanew'. $where);
    $res = sqlgetresult($sql, true);
      
      if ($res != 0)
    {
        foreach ($res as $k => $data)
        {                  
                           $challanData[$k]['academicYear'] = getAcademicyrById($data['academicYear']);
                           $challanData[$k]['studentId'] = $data['studentId'];
                           $challanData[$k]['studentName'] = $data['studentName'];
                           $challanData[$k]['challanNo'] = $data['challanNo'];
                           $challanData[$k]['streamName'] = $data['streamName'];
                           $challanData[$k]['challanStatus'] = $data['challanStatus'];
                            if($challanData[$k]['challanStatus'] == '1'){ 
                                $challanData[$k]['challanStatus'] = "Paid";
                            }else{
                                $challanData[$k]['challanStatus'] = "Not Paid";
                            }
                            if($data['feeGroup'] == ''){
                                $challanData[$k]['feeGroup'] = "Late Fee"; 
                            }
                            else{
                                $challanData[$k]['feeGroup'] = $data['feeGroup']; 
                            }
                            if($data['feeType'] == ''){
                                 $challanData[$k]['feeType'] = "Late Fee";
                            }
                            else{
                                 $challanData[$k]['feeType'] = $data['feeType'];
                            }
                           $challanData[$k]['total'] = $data['total'];    
                           $challanData[$k]['createdOn'] = date("d-m-Y", strtotime($data['createdOn']));
                           $challanData[$k]['adminName'] = $data['adminName']; 
                           $challanData[$k]['remarks'] = $data['remarks'];
                           $challanData[$k]['duedate'] = date("d-m-Y", strtotime($data['duedate']));
        }
    }
    echo json_encode($challanData);
}

if (isset($_POST['filter']) && $_POST['filter'] == "filterreceiptreport")
{
    $year = trim($_POST['yearselect']);
    $stream = trim($_POST['streamselect']);
    $semester = trim($_POST['semesterselect']);
    $whereClauses = array(); 
    
    if (!empty($year)) 
        $whereClauses[] ='"academic_yr" = \''.$year.'\' ';
    $where='';

    if (!empty($stream)) 
        $whereClauses[] ='stream=\''.$stream.'\' ' ;
    $where = ''; 

    if (! empty($semester)) 
      $whereClauses[] ='term=\''.$semester.'\' ' ;
    $where = '';

    if (count($whereClauses) > 0) 
    { 
      $where = ' WHERE '.implode(' AND ',$whereClauses); 
    }   

    $sql = ('SELECT * FROM  studentcheck'. $where);
    $res = sqlgetresult($sql, true);

    $challanData1 = array();
//     $feedata = array();
//     $waivedarray = array();

      
      if ($res != 0)
    {
        foreach ($res as $k => $data)
        {                  
                           $challanData[$k]['studentId'] = $data['studentId'];
                           $challanData[$k]['studentName'] = $data['studentName'];
                           $challanData[$k]['stream'] = getStreambyId($data['stream']);    
        }
    }
    else{
        $challanData = 0;
    }
    echo json_encode($challanData);

}

 if (isset($_POST['filter']) && $_POST['filter'] == "filterreceiptreportview")
{

    $year = trim($_POST['yearselect']);
    $stream = trim($_POST['studentid']);
    $semester = trim($_POST['semesterselect']);

    if (!empty($year)) 
        $whereClauses[] ='"academicYear" = \''.$year.'\' ';
    $where='';

    if (!empty($stream)) 
        $whereClauses[] ='"studentId"=\''.$stream.'\' ' ;
    $where = ''; 

    if (! empty($semester)) 
      $whereClauses[] ='term=\''.$semester.'\' ' ;
    $where = '';


    if (count($whereClauses) > 0) 
    { 
      $where = ' WHERE '.implode(' AND ',$whereClauses); 
    }   

    $sql = ('SELECT * FROM  tbl_demand'. $where);
    $res = sqlgetresult($sql, true);

    echo json_encode($res);

}
// **********DEMAND CHALLAN FILTER END************//

/********* Product - Start *******/

if (isset($_POST["addproduct"]) && $_POST["addproduct"] == "new")
{
    $n = 0;
    // $id=$_POST['id'];
    $uid = $_SESSION['myadmin']['adminid'];
    $pname = $_POST['pname'];
    $acc_no = $_POST['acc_no'];
    $query = "SELECT * FROM addproduct('$pname','$acc_no','$uid')";
    // print_r($query);exit;
    $run = sqlgetresult($query);
    if ($run['addproduct'] > 0)
    {
        $_SESSION['success'] = "<p class='success-msg'>Product Added Successfully.</p>";
        header('location:manageproducts.php');
    }
    else if ($run['addproduct'] == 0)
    {
        $_SESSION['error'] = "<p class='error-msg'>Product Already Exist.</p>";
        header('location:manageproducts.php');
    }
    else
    {
        createErrorlog($run);
        $_SESSION['error'] = "<p class='error-msg'>Some error has been occured. Please try again.</p>";
        header('location:manageproducts.php');
    }
}

if (isset($_POST["editproduct"]) && $_POST["editproduct"] == "update") {
    $id = $_POST['id'];
    $uid = $_SESSION['myadmin']['adminid'];
    $pname = $_POST['pname'];
    $acc_no = $_POST['acc_no'];
    $query = "SELECT * FROM editproduct('$id','$pname','$acc_no','$uid')";
    // print_r($query);exit;
    $run = sqlgetresult($query);
    // print_r($run);exit;
    if ($run['editproduct'] > 0) {
        $_SESSION['success'] = "<p class='success-msg'>Product Edited Successfully.</p>";
        header('location:manageproducts.php');
    } else if ($run['editproduct'] == 0) {
        $_SESSION['error'] = "<p class='error-msg'>Product Already Exist</p>";
        header('location:manageproducts.php');
    } else {
        createErrorlog($run);
        $_SESSION['error'] = "<p class='error-msg'>Some error has been occured. Please try again.</p>";
        header('location:manageproducts.php');
    }
}

/********* Product - End *******/


/***** Duedate configuration - start ********/

if (isset($_POST["editduedate"]) && $_POST["editduedate"] == "update") {   
    $duedate = $_POST['duedate'];
    $currentAcademicyr = getCurrentAcademicYear();
    $currentTerm = getCurrentTerm();
    $query = "SELECT * FROM editduedate('$duedate','$currentAcademicyr','$currentTerm')";
    // print_r($query);exit;
    $run = sqlgetresult($query);
    // print_r($run);exit;
    if ($run['editduedate'] == 1) {
        $_SESSION['success'] = "<p class='success-msg'>DueDate Edited Successfully.</p>";
        header('location:manageduedate.php');
    } else {
        createErrorlog($run);
        $_SESSION['error'] = "<p class='error-msg'>Some error has been occured. Please try again.</p>";
        header('location:manageduedate.php');
    }
}

/***** Duedate configuration - End ********/

/********* Edit SFS - Start *******/

if (isset($_POST["getsfsdata"]) && $_POST["getsfsdata"] == "find")
{ 
    $sid = $_POST['sid'];
    $olddata = sqlgetresult('SELECT * FROM getsfsdata WHERE "studentId" =\'' . $sid . '\' AND "challanStatus" = \''. 0 .'\'',true);
    // $olddata['nofee'] = '0';
    if(count($olddata)== 0) {
        $olddata = sqlgetresult('SELECT * FROM getsfsstuddata WHERE "studentId" =\'' . $sid . '\' AND "challanStatus" = \''. 0 .'\'',true);
        // $olddata['nofee'] = '1';
    }
    $sfscheck = sqlgetresult('SELECT id, "feeType" FROM tbl_fee_type WHERE "feeGroup" = \'10\' ', true);
    $query['sfs'] = $sfscheck;
    $query['selecteddata'] = $olddata;
    // echo 'SELECT * FROM tbl_fee_type WHERE "feeGroup" = \'10\' ';
    echo json_encode($query);
}

if( isset($_POST['changesfsqty']) && $_POST['changesfsqty'] == 'confirm' ) {
    // print_R($_POST);exit;

    $sfsextrautilitiesid= explode(",",$_POST['sfsextrautilities']);
    $sfsutilitiesinputqty = explode(",",$_POST['sfsextrautilitiesqty']);
    $feegrp_id = '10';
    $_POST['challanNo'] = $_POST['cnum'];

    $studData = sqlgetresult('SELECT * FROM challanData WHERE "challanNo" = \''.$_POST['challanNo'].'\' LIMIT 1 ');
    $studStatus = 'Prov.Promoted';
    $studData = array_map('trim',$studData);
    $sfsutilitiesinputqty = array_map('trim',$sfsutilitiesinputqty);
    $studData['remarks'] = 'Nil';

    foreach ($sfsextrautilitiesid as $k=>$sfsfeeId) {
        $sfsfeeId = trim($sfsfeeId);
        $singleqtyamount = getSFSandSchoolFeeByFeeId($sfsfeeId, $studData['clid'],$studData['academicYear'],$studData['term'] );
        $totalAmt = $singleqtyamount * ($sfsutilitiesinputqty[$k]);
        $sql = sqlgetresult("SELECT * FROM editcreatedchallansnew('". $_POST['challanNo'] ."','". $studData['studentId'] ."', '". $studData['clid'] ."','$sfsfeeId', '". $studData['term'] ."','$studStatus', '". $_SESSION['myadmin']['adminid'] ."','$totalAmt','". $studData['stream'] ."','". $studData['remarks'] ."', '". $studData['duedate'] ."','$feegrp_id','". $studData['academicYear'] ."')");
        // echo $sql ; echo "<hr/>";
        $sfsqty = sqlgetresult("SELECT * FROM sfstableentry('". $_POST['challanNo'] ."','". $sfsfeeId ."',$singleqtyamount, '". $sfsutilitiesinputqty[$k] ."', '". $totalAmt ."','". $_SESSION['myadmin']['adminid'] ."','". $studData['studentId'] ."')");
        // echo $sfsqty ;echo "<hr/>";

        flattableentry(trim($_POST['challanNo']), trim($studData['studentId']));
    }
    $_SESSION['success'] = "<p class='success-msg'>SFS Utilities Edited/Added Successfully. </p>";
    header('location:editsfsfee.php');  
}

if (isset($_POST['submit']) && $_POST['submit'] == 'getOtherFeeData')
{
    $feeId = $_POST['id'];
    $data = $_POST['data'];
    $getfeeData = sqlgetresult('SELECT f."feeType" , c.amount , f.id FROM tbl_fee_type f LEFT JOIN tbl_fee_configuration c ON f.id = c."feeType"::integer WHERE f."id" =\'' . $feeId . '\' AND c.class=\'' . $data[3]['value'] . '\' AND c.semester = \'' . $data[2]['value'] . '\' AND c.stream = \'' . $data[4]['value'] . '\' AND c."academicYear" = \'' . $data[5]['value'] . '\' ');

    echo json_encode($getfeeData);
}

if ( isset($_GET['remove']) && $_GET['remove'] == 'sfs') {
    $sfsid = $_GET['sid'];
    $cid = $_GET['cid'];
    $cno = sqlgetresult('SELECT "challanNo","studentId", "feeGroup","feeType" FROM tbl_challans WHERE id= \''.$cid.'\' LIMIT 1');
    $deleteDemand = sqlgetresult('DELETE FROM tbl_demand WHERE "challanNo" = \''.$cno['challanNo'].'\' AND "studentId" = \''.$cno['studentId'].'\' AND "feeGroup" = \''.$cno['feeGroup'].'\' AND "feeType" = \''.$cno['feeType'].'\' ');
    // print_r($deleteDemand);exit;

    $deleteChallan = sqlgetresult("DELETE FROM tbl_challans WHERE id= $cid RETURNING id");
    if( $deleteChallan['id'] > 0 ) {        
        $deleteSFS = sqlgetresult("DELETE FROM tbl_sfs_qty WHERE id= $sfsid RETURNING id");

        $deletefeetypeinledger = sqlgetresult('DELETE FROM tbl_student_ledger WHERE "challanNo"=\'' . trim($cno['challanNo']) . '\' AND "feeType" = \'' . trim(getFeeTypebyId($cno['feeType'])) . '\' AND "feeGroup" = \'' . trim(getFeeGroupbyId($cno['feeGroup'])) . '\' AND "studentId" = \'' . trim($cno['studentId']) . '\'');

    }
    if( $deleteSFS['id'] > 0) {
        $_SESSION['success'] = "<p class='success-msg'>SFS Utilities Edited/Added Successfully. </p>";
    } else {
        $_SESSION['error'] = "<p class='error-msg'>Some error has been occured. Please try again.</p>";
    }
    header('location:editsfsfee.php');  
}

/********* Edit SFS - End *******/

/********* Generate Tax Exemption - Start ******/
if(isset($_POST['generate_tax_exemption']) && $_POST['generate_tax_exemption']=='generate_certificate'){
    $student_id = $_POST['student_id'];
    $query = sqlgetresult('SELECT * FROM studentcheck WHERE "studentId" = \''.$student_id.'\'');
    $row_id = $_POST['row_id'];
    $studentname = $query['studentName'];
    $parentname = $query['userName'];
    $class = $query['class_list'];
    $section = $query['section'];
    $year = $_POST['academic_year'];
    $amount = $_POST['amount'];
    $flag_value = $_POST['flag_value'];

    $amountinWords = getCurrencyInWords($amount,1);    
    $year_query = sqlgetresult('SELECT * FROM yearcheck WHERE id = \''.$year.'\'');
    $year_val = $year_query['year'];
    $result_doc = generate_tax_exemption($parentname,$studentname,$student_id,$class,$section,$amount,$amountinWords,$year_val,$year);
    $result_doc_path = BASEURL.$result_doc;
    $admid = $_SESSION['myadmin']['adminid'];
    $insert_tax_exemption = sqlgetresult("SELECT * FROM tax_exemption('$student_id','$year','$result_doc_path','$admid','$amount','$flag_value','$row_id')");
    // print_r($insert_tax_exemption);
    // echo('<hr/>');
    $body = 'Please find the attached Tax Exemption Certificate for '.$studentname.' for tuition fee of Rs.'.$amount.'('.$amountinWords.')'; 
    $attachmant = '../'.$result_doc;
    sendNotificationToParents($student_id,$body,'',1,$attachmant); 

    // print_r($insert_tax_exemption);
    // exit;
    
    if($insert_tax_exemption['tax_exemption'] == 0){
        $_SESSION['success'] = "<p class='success-msg'>Generated Certificate Successfully</p>";
    }
    elseif($insert_tax_exemption['tax_exemption'] == 1){
        $_SESSION['success'] = "<p class='success-msg'>Generated Certificate Successfully</p>";
    }
    elseif($insert_tax_exemption['tax_exemption'] == 2){
        $_SESSION['error'] = "<p class='error-msg'>Please Edit the Existing Reciept</p>";
    }
    elseif($insert_tax_exemption['tax_exemption'] == 3){
        $_SESSION['success'] = "<p class='success-msg'>Updated Receipt Successfully</p>";
    }
    elseif($insert_tax_exemption['tax_exemption'] == 4) {
         $_SESSION['error'] = "<p class='error-msg'>Please Edit the Existing Reciept</p>";
    }
    elseif($insert_tax_exemption['tax_exemption'] == 5){
        $_SESSION['success'] = "<p class='success-msg'>Updated Receipt Successfully</p>";
    }
    elseif($insert_tax_exemption['tax_exemption'] == 6) {
         $_SESSION['error'] = "<p class='error-msg'>Please Edit the Existing Reciept</p>";
    }
    print_r(json_encode($insert_tax_exemption['tax_exemption']));
    
}
/********* Generate Tax Exemption - End ********/


/********* Preview Tax Exemption - Start *******/
if(isset($_POST['preview_tax_exemption']) && $_POST['preview_tax_exemption']=='preview_tax_exemption'){
    $student_id = $_POST['student_id'];

    $year = $_POST['academic_year'];
    $amount = $_POST['amount'];
    $query = sqlgetresult('SELECT * FROM studentcheck WHERE "studentId" = \''.$student_id.'\'');
    $studentname = $query['studentName'];
    $parentname = $query['userName'];
    $class = $query['class_list'];
    $section = $query['section'];
    $amountinWords = getCurrencyInWords($amount,1);    
    $year_query = sqlgetresult('SELECT * FROM yearcheck WHERE id = \''.$year.'\'');
    $year_val = $year_query['year'];
    if($query!=null){
        $result_doc = preview_tax_exemption_content($parentname,$studentname,$student_id,$class,$section,$amount,$amountinWords,$year_val,$year);
    }
    else{
        $result_doc = '';
    }
    print_r(json_encode($result_doc));
    
}

/********* Preview Tax Exemption - End *********/

/********* Edit Tax Exemption - Start **********/
if(isset($_POST['edit_amount_tax_exemption']) && $_POST['edit_amount_tax_exemption']=='edit_amount_tax_exemption'){
    $student_id = $_POST['id'];  
    $tbl_contents = sqlgetresult('SELECT * FROM tbl_tax_exemption WHERE id = \''.$student_id.'\'');
    print_r(json_encode($tbl_contents));
}

/********* Edit Tax Exemption - End ************/

/********* Filter Tax Exemption - Start ********/

if(isset($_POST['filtertaxexemption']) && $_POST['filtertaxexemption']=='filtertaxexemption' ){
     $whereClauses = array(); 
    if (! empty($_POST['classselect'])) 
        $whereClauses[] ='"class"=\''.pg_escape_string($_POST['classselect']).'\' ' ;
    $where='';

    if (! empty($_POST['streamselect'])) 
      $whereClauses[] ="stream='".pg_escape_string($_POST['streamselect'])."'"; 
    $where = ''; 

    if (! empty($_POST['sectionselect'])) 
      $whereClauses[] ="section='".pg_escape_string($_POST['sectionselect'])."'"; 
    $where = ''; 

    if (count($whereClauses) > 0) { 
      $where = ' WHERE '.implode(' AND ',$whereClauses) .' AND  "deleted" = \'0\' '; 
    }

    $sql = ('SELECT * FROM  gettaxexemapplied'. $where);
    // echo $sql;
    $res = sqlgetresult($sql, true);
    
    echo json_encode($res);

}

/********* Filter Tax Exemption - End **********/

/********* Delete Tax Exemption - Start ********/

if(isset($_POST['delete_entry']) && $_POST['delete_entry']=='delete_entry' ){
    $student_id = $_POST['id'];  
    $tbl_contents = sqlgetresult("SELECT * FROM tax_exemption_delete('$student_id')");
    if($tbl_contents['tax_exemption_delete'] == 0){
        $_SESSION['success'] = "<p class='success-msg'>Successfully Deleted</p>";
    }
    elseif($tbl_contents['tax_exemption_delete'] == 1){
        $_SESSION['error'] = "<p class='error-msg'>No Data Available</p>";
    }
    else{
        $_SESSION['error'] = "<p class='error-msg'>Some Error Occured</p>";

    }
    print_r(json_encode($tbl_contents));

}


/********* Delete Tax Exemption - End **********/

/******** Non- Fee Toggle - Start *******/

if( isset($_POST['filter']) && $_POST['filter'] == 'nonfeetoggle' ) {
    if( $_POST['selected'] ==  'non-fee') {
        $paiddata = sqlgetresult('SELECT * FROM nonfeechallanpaid WHERE "challanStatus" = 1 ', true);
    } elseif ( $_POST['selected'] == 'common-fee' ) {
        $commonfeedata = sqlgetresult('SELECT np."challanNo", np."studentId", np."amount" AS total, np."transDate" AS "updatedOn", DATE(np."createdOn") AS "createdOn", s."studentName",s."term", st.stream AS steamname, c.class_list, s.section  FROM tbl_nonfee_payments np LEFT JOIN tbl_student s ON s."studentId" = np."studentId" OR s."application_no" = np."studentId" LEFT JOIN tbl_stream st ON s.stream::int = st.id LEFT JOIN tbl_class c ON s.class::int = c.id WHERE "transStatus" = \'Ok\' AND "challanNo" ILIKE \'%EVENT%\' ', true);
        $paiddata = array();
        foreach ($commonfeedata as $k => $val) {
            $eventId = explode('-', $val['challanNo'])[1]; 
            $paiddata[$k] = $val;
            $paiddata[$k]['feename'] = getNonfeeTypeById($eventId);
            $date = date('dmY', strtotime($val['updatedOn']));
            $receiptname = $val['studentId'].trim($val['challanNo']);
            $pdfpath = BASEURL.'receipts/'.$date.'/'.str_replace('/', '', trim($receiptname)).'.pdf';
            $paiddata[$k]['pdfpath']='<a href="'.$pdfpath.'" target="_blank" title="Download Receipt"><i class="fa fa-download"></i></a>';
        }
    }
    echo json_encode($paiddata);
}

/******** Non- Fee Toggle - End *******/


// **********LEDGER REPORT START************//

if (isset($_POST['filter']) && $_POST['filter'] == "filterledgerreport")
{
    $year = trim($_POST['yearselect']);
    $semester = trim($_POST['semesterselect']);
    if($_POST['feegroupselect'] != ''){
    $feeGroup = getFeeGroupbyId(trim($_POST['feegroupselect']));
    }
    if($_POST['feetypeselect'] != ''){
    $feeType = getFeeTypebyId($_POST['feetypeselect']);
    }
    $stream = getStreambyId(trim($_POST['streamselect']));
    $class = getClassbyNameId(trim($_POST['classselect']));
    $section = trim($_POST['sectionselect']);
    $entryType = trim($_POST['entrytype']);
    $status = trim($_POST['challanStatus']);

    $whereClauses = array(); 
    if (! empty($year)) 
        $whereClauses[] ='"academicYear"=\''.trim(pg_escape_string($year)).'\' ' ;
    $where='';

    if (! empty($semester)) 
        $whereClauses[] ='"term"=\''.trim(pg_escape_string($semester)).'\' ' ;
    $where='';

    if (! empty($feeGroup)) 
        $whereClauses[] ='"feeGroup"=\''.trim(pg_escape_string($feeGroup)).'\' ' ;
    $where='';

    if (! empty($feeType)) 
        $whereClauses[] ='"feeType"=\''.trim(pg_escape_string($feeType)).'\' ' ;
    $where='';

    if (! empty($stream)) 
        $whereClauses[] ='"stream"=\''.trim(pg_escape_string($stream)).'\' ' ;
    $where='';

    if (! empty($class)) 
        $whereClauses[] ='"class"=\''.trim(pg_escape_string($class)).'\' ' ;
    $where='';

    if (! empty($entryType)) 
        $whereClauses[] ='"entryType"=\''.trim(pg_escape_string($entryType)).'\' ' ;
    $where='';

    if (!empty($status) || $status === '0' ) 
        $whereClauses[] =" \"challanStatus\" = ".trim($status); 
    $where = ''; 

  
    if (count($whereClauses) > 0) 
    { 
      $where = ' WHERE "Deleted"=\'0\' AND '.implode(' AND ',$whereClauses); 
    }   

    $sql = ('SELECT * FROM  tbl_student_ledger'. $where);
    $_SESSION['ledgerreportquery']=$sql;
    $res = sqlgetresult($sql, true);
      
      if ($res != 0)
    {
        foreach ($res as $k => $data)
        {                  
            $challanData[$k]['studentId'] = $data['studentId'];
            $challanData[$k]['challanNo'] = $data['challanNo'];
            $challanData[$k]['studentName'] = $data['studentName'];
            $challanData[$k]['academicYear'] = $data['academicYear'];
            $challanData[$k]['class'] = $data['class'];
            $challanData[$k]['stream'] = $data['stream'];
            $challanData[$k]['term'] = $data['term'];
            $challanData[$k]['date'] = $data['date'];
            $challanData[$k]['feeGroup'] = $data['feeGroup'];
            $challanData[$k]['feeType'] = $data['feeType'];
            $challanData[$k]['total'] = $data['amount'];
            $challanData[$k]['remarks'] = $data['remarks'];
            $challanData[$k]['entryType'] = $data['entryType'];
            if($data['challanStatus'] == 1){
                $challanData[$k]['challanStatus'] = 'Active';
            }
            else{
                $challanData[$k]['challanStatus'] = 'Inactive';
            }

        }
    }
    echo json_encode($challanData);
}

if (isset($_POST['findchallanforstdid']) && $_POST['findchallanforstdid'] == "findchallanforstdid"){
    $studentId = $_POST['stdid'];

    $findchallanNo = sqlgetresult('SELECT "challanNo" FROM tbl_challans WHERE "studentId" = \''.$studentId.'\'',true);
    if($findchallanNo != ''){
        foreach($findchallanNo AS $challan){
           foreach($challan AS $chn)
            $challanNo[] = $chn;
        }
        $challanNumbers = array_unique($challanNo);
    }
    else{
        $challanNumbers = 0;
    }
    echo json_encode($challanNumbers);
}

if (isset($_POST['ledgerchangestatus']) && $_POST['ledgerchangestatus'] == "Change Status"){
    print_r($_POST);
    $studentId = $_POST['ledgerstudentid'];
    $challanNo = $_POST['challanselect'];
    $status = $_POST['status'];
    $changechallanstatus = sqlgetresult('UPDATE tbl_student_ledger SET "challanStatus" = \''.$status.'\' WHERE "studentId" = \''.$studentId.'\' AND "challanNo" = \''.$challanNo.'\' RETURNING id as "changechallanstatus"');

    if(is_array($changechallanstatus)){
        $_SESSION['successchallanstatus'] = "<p class='success-msg'>Challan Status Updated Successfully</p>";
    }
    else{
        $_SESSION['errorchallanstatus'] = "<p class='error-msg'>Some Error Occured</p>";

    }
    header('location:ledgerreport.php');
}


if(isset($_GET['ledgerreport']) && $_GET['ledgerreport']=='exportexcel'){
$sql = $_SESSION['ledgerreportquery'];
$sqlrun = sqlgetresult($sql);

       foreach($sqlrun AS $key => $output){
            $result_data[$key]['Student Id']=$output['studentId'];
            $result_data[$key]['Challan Number']=$output['challanNo'];
            $result_data[$key]['Student Name']=$output['studentName'];
            $result_data[$key]['Academic Year']=$output['academicYear'];
            $result_data[$key]['Class']=$output['class'];
            $result_data[$key]['Stream']=$output['stream'];
            $result_data[$key]['Term']=$output['term'];
            $result_data[$key]['Created Date']=$output['date'];
            $result_data[$key]['Fee Group']=$output['feeGroup'];
            $result_data[$key]['Fee Type']=$output['feeType'];
            $result_data[$key]['Total']=$output['amount'];
            $result_data[$key]['Remarks']=$output['remarks'];
            $result_data[$key]['Entry Type']=$output['entryType'];
            if($output['challanStatus'] == 1){
                $result_data[$key]['Challan Status']='Active';
            }
            else{
                $result_data[$key]['Challan Status']='Inactive';
            }
        }
        foreach ($result_data as $k => $v) {
        $keys = array();
        foreach ($v as $field_code => $field_val)
        {       
                $keys[]=$field_code;                
        }       
        $columns = $keys;
    }

    exportData($result_data, 'Ledger Report', $columns);

}


if (isset($_POST['getstudentledger']) && $_POST['getstudentledger'] == "getstudentledger"){
    $challanno = $_POST['challannumber'];
    $studentdetails = sqlgetresult('SELECT * FROM tbl_student_ledger WHERE "challanNo" = \''.$challanno.'\'',true);
        echo json_encode($studentdetails);
}

if(isset($_GET['ledgerreportcolumnwise']) && $_GET['ledgerreportcolumnwise']=='exportexcel'){

$sql = $_SESSION['ledgerreportquery'] .'ORDER BY "challanNo"';
$sqlrun = sqlgetresult($sql, true);

      $challan = array();
    
    $FeeTypes = sqlgetresult('SELECT "feeType" FROM tbl_fee_type ');
    $FeeTypes = array_column($FeeTypes, "feeType");
    array_push($FeeTypes,"LATE FEE");

    $FeetypeData = array();

    foreach ($FeeTypes as $feetype) {
        $FeetypeData[] = ucwords(strtolower($feetype)).' Amount';
    }

    $FeeGroups = sqlgetresult('SELECT "feeGroup" FROM tbl_fee_group ');
    $FeeGroups = array_column($FeeGroups, "feeGroup");
    array_push($FeeGroups, "LATE FEE");

    $FeegroupData = array();

    foreach ($FeeGroups as $feegroup) {
        $FeegroupData[] = ucwords(strtolower($feegroup)).' Receipt';
        $FeegroupData[] = ucwords(strtolower($feegroup)).' Waiver';
    }


    $columns = array('S.No','Student Id','Challan No','Student Name','Academic Year','Term','Stream','Class','Date','Challan Status');
    $columns = array_merge($columns,$FeetypeData);
    // array_push($columns,"Demand");
    $columns = array_merge($columns,$FeegroupData);
    array_push($columns,"Demand","Receipt","Waiver","Outstanding Amount","Receipt Details");

    foreach ($sqlrun as $k => $data) 
    {    
        $a = array_map('trim', array_keys($data));
        $b = array_map('trim', $data);
        $data = array_combine($a, $b);          
        $challanData['Student Id'] = trim($data['studentId']);
        $challanData['Challan No'] = trim($data['challanNo']);
        $challanData['Student Name'] = trim($data['studentName']);
        $challanData['Academic Year'] = trim($data['academicYear']);
        $challanData['Term'] = trim($data['term']);
        $challanData['Stream'] = trim($data['stream']);
        $challanData['Class'] = trim($data['class']);
        $challanData['Date'] = $data['date'];
        if($data['challanStatus'] == 1){
        $challanData['Challan Status'] = "Active";
        }
        else{
        $challanData['Challan Status'] = "In Active";   
        }   
        $feeType['name'] = trim($data['feeType']);  
            foreach ($FeeTypes as $v) {
                $amt = ucwords(strtolower($v)).' Amount';
                if ( trim($v) == $feeType['name'] ) {
                    $FEETYPE[$amt][] = getfeetypeamountfromledger($feeType['name'], $data['challanNo']);
                }
                 else {
                    $FEETYPE[$amt][] = '0';
                }
            }
        $feeGroup['name'] = trim($data['feeGroup']);
            foreach ($FeeGroups as $v) {
                $amt = ucwords(strtolower($v)).' Receipt';
                if ( trim($v) == $feeGroup['name'] ) {
                    $entrytype = 'RECEIPT';
                    $FEEGROUPRECEIPT[$amt][] = getfeegroupamountfromledger($feeGroup['name'], $data['challanNo'],$entrytype);
                }
                 else {
                    $FEEGROUPRECEIPT[$amt][] = '0';
                }
            }   

            foreach ($FeeGroups as $v) {
                $amt = ucwords(strtolower($v)).' Waiver';
                if ( trim($v) == $feeGroup['name'] ) {
                    $entrytype = 'WAIVER';
                    $FEEGROUPWAIVER[$amt][] = getfeegroupamountfromledger($feeGroup['name'], $data['challanNo'], $entrytype);
                }
                 else {
                    $FEEGROUPWAIVER[$amt][] = '0';
                }
            }
            if($data['entryType'] == 'RECEIPT'){
                $challanData['Receipt Details'] = $data['remarks'];
            }

        if( $data['challanNo'] != $sqlrun[$k+1]['challanNo'] ) {
            $i++;
            $challanData['S.No'] = $i;
            $total = 0;
            $receipttotal = 0;
            $waivertotal = 0;
            $receiptpluswaiver = 0;
            $outstanding = 0;
            foreach ($FEETYPE as $fee => $val) {
                $challanData[$fee] = array_sum($val);
                if (stripos($fee,'amount')) {
                    $total += array_sum($val);
                }               
            }
            foreach ($FEEGROUPRECEIPT as $fee1 => $val1) {
                $challanData[$fee1] = array_sum(array_unique($val1));
                if (stripos($fee1,'receipt')) {
                    $receipttotal += array_sum(array_unique($val1));
                }               
            }
            foreach ($FEEGROUPWAIVER as $fee2 => $val2) {
                $challanData[$fee2] = array_sum(array_unique($val2));
                if (stripos($fee2,'waiver')) {
                    $waivertotal += array_sum(array_unique($val2));
                }               
            }
            $challanData['Demand'] = $total;
            $challanData['Receipt'] = $receipttotal;
            $challanData['Waiver'] = $waivertotal;
            $receiptpluswaiver = $receipttotal + $waivertotal;
            $outstanding = $total - $receiptpluswaiver;
            $challanData['Outstanding Amount'] = $outstanding;
            array_push($challan, $challanData);
            $FEETYPE = array(); 
            $FEEGROUPRECEIPT = array(); 
            $FEEGROUPWAIVER = array();          
        }      
    }
    $columns = array_unique($columns);

    $header = "LALAJI MEMORIAL OMEGA INTERNATIONAL SCHOOL - LEDGER REPORT (".$data['academicYear']. "-" .$data['term']." SEM)";

    exportData($challan, $header, $columns);
}

// **********LEDGER REPORT END************//


// **********STUDENT LEDGER START************//

if (isset($_POST['filter']) && $_POST['filter'] == "filterstudentledger")
{
    $studentid = isset($_POST['studentid'])?trim($_POST['studentid']):"";
    $yearselect = isset($_POST['yearselect'])?trim($_POST['yearselect']):"";
    /*$whereClauses = array(); 
    if (!empty($studentid)) 
        $whereClauses[] = '"studentId"=\''.trim(pg_escape_string($studentid)).'\' ' ;
    $where = ''; 

  
    if (count($whereClauses) > 0) 
    { 
      $where = ' WHERE "Deleted"=\'0\' AND '.implode(' AND ',$whereClauses); 
    }*/
    $whereClauses = array(); 
    if (!empty($studentid)) {
        $whereClauses[] = 'a."studentId"=\''.trim(pg_escape_string($studentid)).'\' ' ;
    }

    if(!empty($yearselect)){
        $whereClauses[] = 'a."academicYear"=\''.trim(pg_escape_string($yearselect)).'\' ' ; 
    }
    $where = ''; 

    $zero = '0';
    if (count($whereClauses) > 0) 
    { 
      $where = ' WHERE a."Deleted" ='.$zero.' AND '.implode(' AND ',$whereClauses); 
    }

    $joinQry='SELECT a."studentId",a."challanNo",a."studentName",a."academicYear",a.class,a.stream,a.term,a.date,a."feeGroup",a."feeType",a.amount,a."remarks",a."entryType",a."challanStatus" FROM  tbl_student_ledger as a LEFT JOIN tbl_student as b ON (((a."studentId")::bpchar)::TEXT = ANY(string_to_array ((b."old_studentId")::TEXT,\',\'::TEXT))) '.$where;   

    //$sql = ('SELECT * FROM  tbl_student_ledger'. $where);
    $sql = ($joinQry);
    $_SESSION['studentledgerquery']=$sql;
    $res = sqlgetresult($sql, true);
      
      if ($res != 0)
    {
        foreach ($res as $k => $data)
        {                  
            $challanData[$k]['studentId'] = $data['studentId'];
            $challanData[$k]['challanNo'] = $data['challanNo'];
            $challanData[$k]['studentName'] = $data['studentName'];
            $challanData[$k]['academicYear'] = $data['academicYear'];
            $challanData[$k]['class'] = $data['class'];
            $challanData[$k]['stream'] = $data['stream'];
            $challanData[$k]['term'] = $data['term'];
            $challanData[$k]['date'] = date("d-m-Y", strtotime($data['date']));
            $challanData[$k]['feeGroup'] = $data['feeGroup'];
            $challanData[$k]['feeType'] = $data['feeType'];
            $challanData[$k]['total'] = $data['amount'];
            $challanData[$k]['remarks'] = $data['remarks'];
            $challanData[$k]['entryType'] = $data['entryType'];
            // 
            if($data['challanStatus'] == 1){
                $challanData[$k]['challanStatus'] = 'Active';
            }
            else{
                $challanData[$k]['challanStatus'] = 'Inactive';
            }

        }
    }
    echo json_encode($challanData);
}

if(isset($_GET['studentledger']) && $_GET['studentledger']=='exportexcel'){
$sql = $_SESSION['studentledgerquery'];
$sqlrun = sqlgetresult($sql);

       foreach($sqlrun AS $key => $output){
            $result_data[$key]['Student Id']=$output['studentId'];
            $result_data[$key]['Challan Number']=$output['challanNo'];
            $result_data[$key]['Student Name']=$output['studentName'];
            $result_data[$key]['Academic Year']=$output['academicYear'];
            $result_data[$key]['Class']=$output['class'];
            $result_data[$key]['Stream']=$output['stream'];
            $result_data[$key]['Term']=$output['term'];
            $result_data[$key]['Created Date']=$output['date'];
            $result_data[$key]['Fee Group']=$output['feeGroup'];
            $result_data[$key]['Fee Type']=$output['feeType'];
            $result_data[$key]['Total']=$output['amount'];
            $result_data[$key]['Remarks']=$output['remarks'];
            $result_data[$key]['Entry Type']=$output['entryType'];
            if($output['challanStatus'] == 1){
                $result_data[$key]['Challan Status']='Active';
            }
            else{
                $result_data[$key]['Challan Status']='Inactive';
            }
        }
        foreach ($result_data as $k => $v) {
        $keys = array();
        foreach ($v as $field_code => $field_val)
        {       
                $keys[]=$field_code;                
        }       
        $columns = $keys;
    }

    exportData($result_data, 'Student Ledger Report', $columns);
}
// **********STUDENT LEDGER END************//


// **********SPLIT REPORT START************//


if (isset($_POST['excel']) && $_POST['excel'] == "splitreportexcel"){

    $year = trim($_POST['yearselect']);
    $semester = trim($_POST['semesterselect']);
    $stream = getStreambyId(trim($_POST['streamselect']));
    $class = getClassbyNameId(trim($_POST['classselect']));
    $reporttype = trim($_POST['reporttype']);
    $fromdate = trim($_POST['fromdate']);
    $todate = trim($_POST['todate']);

    $whereClauses = array(); 
    if (! empty($year)) 
        $whereClauses[] ='"academicYear"=\''.trim(pg_escape_string($year)).'\' ' ;
    $where='';

    if (! empty($semester)) 
        $whereClauses[] ='"term"=\''.trim(pg_escape_string($semester)).'\' ' ;
    $where='';

    if (! empty($stream)) 
        $whereClauses[] ='"stream"=\''.trim(pg_escape_string($stream)).'\' ' ;
    $where='';

    if (! empty($class)) 
        $whereClauses[] ='"class"=\''.trim(pg_escape_string($class)).'\' ' ;
    $where='';

    if (! empty($reporttype)) 
        $whereClauses[] ='"entryType"=\''.trim(pg_escape_string($reporttype)).'\' ' ;
    $where='';

    if (! empty($fromdate) && ! empty($todate)) 
        $whereClauses[] ='"date" BETWEEN \''.trim(pg_escape_string(date("m-d-Y", strtotime($fromdate)))).'\' AND \''.trim(pg_escape_string(date("m-d-Y", strtotime($todate)))).'\'' ;
    $where='';
  
    if (count($whereClauses) > 0) 
    { 
      $where = ' WHERE "Deleted"= \'0\' AND '.implode(' AND ',$whereClauses); 
    }   

    $sql = ('SELECT * FROM  tbl_student_ledger'. $where . ' ORDER BY "challanNo"');
    // print_r($sql);
    // exit;

    $sqlrun = sqlgetresult($sql, true);
    // print_r($sqlrun);
    // exit;

    if($sqlrun != ''){
        if($_POST['reporttype'] == 'DEMAND'){
            $challan = array();
            
            $FeeTypes = sqlgetresult('SELECT "feeType" FROM tbl_fee_type ');
            $FeeTypes = array_column($FeeTypes, "feeType");
            array_push($FeeTypes,"LATE FEE");

            $FeetypeData = array();

            foreach ($FeeTypes as $feetype) {
                $FeetypeData[] = ucwords(strtolower($feetype)).' Amount';
            }

            $FeeGroups = sqlgetresult('SELECT "feeGroup" FROM tbl_fee_group ');
            $FeeGroups = array_column($FeeGroups, "feeGroup");
            array_push($FeeGroups, "LATE FEE");

            $columns = array('S.No','Student Id','Challan No','Student Name','Academic Year','Term','Stream','Class','Date','Challan Status');
            $columns = array_merge($columns,$FeetypeData);
            array_push($columns,"Demand");

            foreach ($sqlrun as $k => $data) 
            {    
                $a = array_map('trim', array_keys($data));
                $b = array_map('trim', $data);
                $data = array_combine($a, $b);          
                $challanData['Student Id'] = trim($data['studentId']);
                $challanData['Challan No'] = trim($data['challanNo']);
                $challanData['Student Name'] = trim($data['studentName']);
                $challanData['Academic Year'] = trim($data['academicYear']);
                $challanData['Term'] = trim($data['term']);
                $challanData['Stream'] = trim($data['stream']);
                $challanData['Class'] = trim($data['class']);
                $challanData['Date'] = $data['date'];
                if($data['challanStatus'] == 1){
                $challanData['Challan Status'] = "Active";
                }
                else{
                $challanData['Challan Status'] = "In Active";   
                }   
                
                $feeType['name'] = trim($data['feeType']);  
                    foreach ($FeeTypes as $v) {
                        $amt = ucwords(strtolower($v)).' Amount';
                        if ( trim($v) == $feeType['name'] ) {
                            $FEETYPE[$amt][] = getfeetypeamountfromledger($feeType['name'], $data['challanNo']);
                        }
                         else {
                            $FEETYPE[$amt][] = '0';
                        }
                    }

                if( $data['challanNo'] != $sqlrun[$k+1]['challanNo'] ) {
                    $i++;
                    $challanData['S.No'] = $i;
                    $total = 0;
                    foreach ($FEETYPE as $fee => $val) {
                        $challanData[$fee] = array_sum($val);
                        if (stripos($fee,'amount')) {
                            $total += array_sum($val);
                        }               
                    }
                    $challanData['Demand'] = $total;
                    array_push($challan, $challanData);
                    $FEETYPE = array();         
                }      
            }
            $columns = array_unique($columns);

            $header = "LALAJI MEMORIAL OMEGA INTERNATIONAL SCHOOL - LEDGER REPORT (".$data['academicYear']. "-" .$data['term']." SEM)";
            $_SESSION['splitreportsuccess'] = "<p class='success-msg'>Excel Exported Successfully</p>";

            exportData($challan, $header, $columns);
            header('location:splitreport.php');

        }
        else if($_POST['reporttype'] == 'RECEIPT'){
            $challan = array();
        
            $FeeGroups = sqlgetresult('SELECT "feeGroup" FROM tbl_fee_group ');
            $FeeGroups = array_column($FeeGroups, "feeGroup");
            array_push($FeeGroups, "LATE FEE");

            $FeegroupData = array();

            foreach ($FeeGroups as $feegroup) {
                $FeegroupData[] = ucwords(strtolower($feegroup)).' Receipt';
            }


            $columns = array('S.No','Student Id','Challan No','Student Name','Academic Year','Term','Stream','Class','Date','Challan Status');
            $columns = array_merge($columns,$FeegroupData);
            array_push($columns,"Receipt","Receipt Details");

            foreach ($sqlrun as $k => $data) 
            {    
                $a = array_map('trim', array_keys($data));
                $b = array_map('trim', $data);
                $data = array_combine($a, $b);          
                $challanData['Student Id'] = trim($data['studentId']);
                $challanData['Challan No'] = trim($data['challanNo']);
                $challanData['Student Name'] = trim($data['studentName']);
                $challanData['Academic Year'] = trim($data['academicYear']);
                $challanData['Term'] = trim($data['term']);
                $challanData['Stream'] = trim($data['stream']);
                $challanData['Class'] = trim($data['class']);
                $challanData['Date'] = $data['date'];
                $challanData['Receipt Details'] = $data['remarks'];
                if($data['challanStatus'] == 1){
                $challanData['Challan Status'] = "Active";
                }
                else{
                $challanData['Challan Status'] = "In Active";   
                }   
                
                $feeGroup['name'] = trim($data['feeGroup']);
                    foreach ($FeeGroups as $v) {
                        $amt = ucwords(strtolower($v)).' Receipt';
                        if ( trim($v) == $feeGroup['name'] ) {
                            $entrytype = 'RECEIPT';
                            $FEEGROUPRECEIPT[$amt][] = getfeegroupamountfromledger($feeGroup['name'], $data['challanNo'],$entrytype);
                        }
                         else {
                            $FEEGROUPRECEIPT[$amt][] = '0';
                        }
                    }   


                if( $data['challanNo'] != $sqlrun[$k+1]['challanNo'] ) {
                    $i++;
                    $challanData['S.No'] = $i;
                    $receipttotal = 0;
                    foreach ($FEEGROUPRECEIPT as $fee1 => $val1) {
                        $challanData[$fee1] = array_sum(array_unique($val1));
                        if (stripos($fee1,'receipt')) {
                            $receipttotal += array_sum(array_unique($val1));
                        }               
                    }

                    $challanData['Receipt'] = $receipttotal;
                    array_push($challan, $challanData);
                    $FEEGROUPRECEIPT = array();         
                }      
            }
            $columns = array_unique($columns);

            $header = "LALAJI MEMORIAL OMEGA INTERNATIONAL SCHOOL - RECEIPT REPORT (".$data['academicYear']. "-" .$data['term']." SEM)";
            $_SESSION['splitreportsuccess'] = "<p class='success-msg'>Excel Exported Successfully</p>";

            exportData($challan, $header, $columns);
            header('location:splitreport.php');

        }
        else{

            $challan = array();
            $FeeGroups = sqlgetresult('SELECT "feeGroup" FROM tbl_fee_group ');
            $FeeGroups = array_column($FeeGroups, "feeGroup");
            array_push($FeeGroups, "LATE FEE");

            $FeegroupData = array();

            foreach ($FeeGroups as $feegroup) {
                $FeegroupData[] = ucwords(strtolower($feegroup)).' Waiver';
            }


            $columns = array('S.No','Student Id','Challan No','Student Name','Academic Year','Term','Stream','Class','Date','Challan Status');

            $columns = array_merge($columns,$FeegroupData);
            array_push($columns,"Waiver");

            foreach ($sqlrun as $k => $data) 
            {    
                $a = array_map('trim', array_keys($data));
                $b = array_map('trim', $data);
                $data = array_combine($a, $b);          
                $challanData['Student Id'] = trim($data['studentId']);
                $challanData['Challan No'] = trim($data['challanNo']);
                $challanData['Student Name'] = trim($data['studentName']);
                $challanData['Academic Year'] = trim($data['academicYear']);
                $challanData['Term'] = trim($data['term']);
                $challanData['Stream'] = trim($data['stream']);
                $challanData['Class'] = trim($data['class']);
                $challanData['Date'] = $data['date'];
                if($data['challanStatus'] == 1){
                $challanData['Challan Status'] = "Active";
                }
                else{
                $challanData['Challan Status'] = "In Active";   
                }   
                
                $feeGroup['name'] = trim($data['feeGroup']); 

                    foreach ($FeeGroups as $v) {
                        $amt = ucwords(strtolower($v)).' Waiver';
                        if ( trim($v) == $feeGroup['name'] ) {
                            $entrytype = 'WAIVER';
                            $FEEGROUPWAIVER[$amt][] = getfeegroupamountfromledger($feeGroup['name'], $data['challanNo'], $entrytype);
                        }
                         else {
                            $FEEGROUPWAIVER[$amt][] = '0';
                        }
                    }

                if( $data['challanNo'] != $sqlrun[$k+1]['challanNo'] ) {
                    $i++;
                    $challanData['S.No'] = $i;
                    $waivertotal = 0;
                    foreach ($FEEGROUPWAIVER as $fee2 => $val2) {
                        $challanData[$fee2] = array_sum(array_unique($val2));
                        if (stripos($fee2,'waiver')) {
                            $waivertotal += array_sum(array_unique($val2));
                        }               
                    }
                    $challanData['Waiver'] = $waivertotal;
                    array_push($challan, $challanData);
                    $FEEGROUPWAIVER = array();          
                }      
            }
            $columns = array_unique($columns);

            $header = "LALAJI MEMORIAL OMEGA INTERNATIONAL SCHOOL - WAIVER REPORT (".$data['academicYear']. "-" .$data['term']." SEM)";
            $_SESSION['splitreportsuccess'] = "<p class='success-msg'>Excel Exported Successfully</p>";
            exportData($challan, $header, $columns);
            header('location:splitreport.php');

        }
        
    }
    else{
        $_SESSION['splitreporterror'] = "<p class='error-msg'>No Data available</p>";
    }


    header('location:splitreport.php');
}
// **********SPLIT REPORT END************//
/*****Waivertype Table - Start*****/
if (isset($_POST["editWaivertype"]) && $_POST["editWaivertype"] == "update")
{
    $id = $_POST['id'];
    $uid = $_SESSION['myadmin']['adminid'];
    $wname = $_POST['waivertype'];
    $des = $_POST['des'];
    $query = "SELECT * FROM editwaivertypes('$id','$wname','$des','$uid')";
    $run = sqlgetresult($query);
    // print_r($run);
    // exit;
    if ($run['editwaivertypes'] == 1)
    {
        $_SESSION['successclass'] = "<p class='success-msg'>Data Edited Successfully.</p>";
        header('location:managewaivertype.php');
    }
    else if ($run['editwaivertypes'] == 0)
    {
        $_SESSION['errorclass'] = "<p class='error-msg'>Waiver Type Already Exist</p>";
        header('location:managewaivertype.php');
    }
    else
    {
        createErrorlog($run);
        $_SESSION['errorclass'] = "<p class='error-msg'>Some error has been occured. Please try again.</p>";
        header('location:managewaivertype.php');
    }
}

if (isset($_POST["addWaivertype"]) && $_POST["addWaivertype"] == "new")
{
    // $n = 0;
    // $id=$_POST['id'];
    $uid = $_SESSION['myadmin']['adminid'];
    $wname = isset($_POST['waivertype'])?trim($_POST['waivertype']):"";
    $des = isset($_POST['des'])?trim($_POST['des']):"";
    $query = "SELECT * FROM addwaivertypes('$wname','$des','$uid')";
    $run = sqlgetresult($query);
    // print_r($query);
    // exit;
    if ($run['addwaivertypes'] == 1)
    {
        $_SESSION['successclass'] = "<p class='success-msg'>Data Added Successfully.</p>";
        header('location:managewaivertype.php');
    }
    else if ($run['addwaivertypes'] == 0)
    {
        $_SESSION['errorclass'] = "<p class='error-msg'>Waiver Type Already Exist.</p>";
        header('location:managewaivertype.php');
    }
    else
    {
        createErrorlog($run);
        $_SESSION['errorclass'] = "<p class='error-msg'>Some error has been occured. Please try again.</p>";
        header('location:managewaivertype.php');
    }
}
/*****Waivertype Table - End*****/
//***********Chequedd Feetye section - Start***********//
if (isset($_POST['fee_pay']) && $_POST['fee_pay'] == 'confirm'){
    //$_POST = array_map('trim',$_POST);
    $feeTypes=$_POST['feetypechk'];
    $numfeeType=count($feeTypes);
    if($numfeeType > 0){
        date_default_timezone_set("Asia/Kolkata");    
        $cur_data = time();
        $date = date('Y-m-d h:i:s');

        $uid = $_SESSION['myadmin']['adminid'];

        $sid = isset($_POST['sid'])?trim($_POST['sid']):"";
        $term = isset($_POST['term'])?trim($_POST['term']):"";
        $cnum = isset($_POST['cnum'])?trim($_POST['cnum']):"";
        $academicyear = isset($_POST['academicyear'])?trim($_POST['academicyear']):"";
        $sendmail = isset($_POST['sendmail'])?trim($_POST['sendmail']):"";
        $type_ids=implode(",", $feeTypes);
        foreach ($feeTypes as $type_id) {
            # code...
            $type_id=trim($type_id);
            $ptype="ptype_".$type_id;
            $cbank="cbank_".$type_id;
            $bank="bank_".$type_id;
            $paymentmode="paymentmode_".$type_id;
            $paymentmodetrans="paymentmodetrans_".$type_id;
            $amount="amount_".$type_id;
            $paiddate="paiddate_".$type_id;
            $remarks="remarks_".$type_id;
            //$sendmail="sendmail_".$type_id;
            $fullwaived="fullwaived_".$type_id;
            $feegroup_id="feegroup_".$type_id;

            $ptype_val=isset($_POST[$ptype])?trim($_POST[$ptype]):"";
            $paiddate_val=isset($_POST[$paiddate])?trim($_POST[$paiddate]):"";
            $remarks_val=isset($_POST[$remarks])?trim($_POST[$remarks]):"";

            $fullwaived_val=isset($_POST[$fullwaived])?trim($_POST[$fullwaived]):"";
            //$sendmail_val=isset($_POST[$sendmail])?trim($_POST[$sendmail]):"";
            $feegroup_val=isset($_POST[$feegroup_id])?trim($_POST[$feegroup_id]):"";


            if($ptype_val == "Online"){
                $bank_val = isset($_POST[$bank])?trim($_POST[$bank]):"Atom";
                $paymentmode_val = isset($_POST[$paymentmodetrans])?trim($_POST[$paymentmodetrans]):"";
            }
            else{
                $bank_val = isset($_POST[$cbank])?trim($_POST[$cbank]):"";
                $paymentmode_val = isset($_POST[$paymentmode])?trim($_POST[$paymentmode]):"";
            }

            $feegroupradio = $feegroup_val;
            $entry = sqlgetresult("SELECT *  FROM new_cheque_fee_entry_by_feetype('".$sid."','".$term."','".$cnum."','".$ptype_val."','".$bank_val."','".$paymentmode_val."','".$paiddate_val."','".$feegroupradio."','".$type_id."','".$academicyear."','".$uid."','".$remarks_val."','".$date."') ");
            /* Paid date */
            if(count($entry) > 0){
                toUpdatePaidDateOnAppl($sid,$paiddate_val);
            }

            /*if (trim($feegroupradio) == 'LATE FEE') {
                $feegroup = 0;
            } else {
                $feegroup = $feegroupradio;
            }*/

            $receiptupd = updatereceipt_by_feetype($cnum,$sid,$feegroupradio,$type_id);
            $fromwhere = 'Receipt';
            flattableentry_feetype($cnum, $sid, $fromwhere);

            /*if($fullwaived_val == 'on'){
              $updatechallanstatus = sqlgetresult('UPDATE tbl_challans SET "fullyWaived" = \'1\' WHERE "challanNo" = \''.$cnum.'\' AND "feeType"= \''.$type_id.'\'');
            }*/
        }
        if($sendmail == 'on'){
            $chequedd = "cheque";
            createPDF_by_feetype($sid, $cnum,$chequedd,$type_ids);
        }

        if($receiptupd > 0){
            $_SESSION['successcheque'] = "<p class='success-msg'>Payment Updated Successfully.</p>";
            header("Location: cheque_dd_feetype.php");
        }
        else{
            $_SESSION['errorcheque'] = "<p class='error-msg'>Payment was not updated in Receipt table.</p>";
            header("Location: cheque_dd_feetype.php");  
        }

    }else{
       $_SESSION['errorcheque'] = "<p class='error-msg'>Please choose the feetype</p>";
       header("Location: cheque_dd_feetype.php"); 
    }
}
//***********Chequedd Feetye section - End***********//

/*****Admin Role Table - Start*****/
if (isset($_POST["editrole"]) && $_POST["editrole"] == "update")
{
    $id = $_POST['id'];
    $uid = $_SESSION['myadmin']['adminid'];
    $wname = $_POST['role'];
    $des = $_POST['des'];
    $query = "SELECT * FROM editrole('$id','$wname','$des','$uid')";
    $run = sqlgetresult($query);
    // print_r($run);
    // exit;
    if ($run['editrole'] == 1)
    {
        $_SESSION['successclass'] = "<p class='success-msg'>Data Edited Successfully.</p>";
        header('location:manageroles.php');
    }
    else if ($run['editrole'] == 0)
    {
        $_SESSION['errorclass'] = "<p class='error-msg'>Admin Role Already Exist</p>";
        header('location:manageroles.php');
    }
    else
    {
        createErrorlog($run);
        $_SESSION['errorclass'] = "<p class='error-msg'>Some error has been occured. Please try again.</p>";
        header('location:manageroles.php');
    }
}

if (isset($_POST["addrole"]) && $_POST["addrole"] == "new")
{
    // $n = 0;
    // $id=$_POST['id'];
    $uid = $_SESSION['myadmin']['adminid'];
    $wname = isset($_POST['role'])?trim($_POST['role']):"";
    $des = isset($_POST['des'])?trim($_POST['des']):"";
    $query = "SELECT * FROM addrole('$wname','$des','$uid')";
    $run = sqlgetresult($query);
    $last_id = $run['addrole'];
    if ($last_id > 0)
    {
        $st=1;
        $sqlmenu = 'SELECT id FROM  tbl_admin_submenu  WHERE status=\''.$st.'\'';
        $resmenu = sqlgetresult($sqlmenu, true);
        $numm=count($resmenu);
        if($numm > 0 )
        {
            foreach($resmenu as $key => $datamenu){
                $sid=$datamenu['id'];
                $run1=sqlgetresult("SELECT * FROM addmenuaccess('$sid','$last_id','$uid')");
            }
        }
        $_SESSION['successclass'] = "<p class='success-msg'>Data Added Successfully.</p>";
        header('location:manageaccess.php');
    }
    else if ($run['addrole'] == 0)
    {
        $_SESSION['errorclass'] = "<p class='error-msg'>Admin Role Already Exist.</p>";
        header('location:manageroles.php');
    }
    else
    {
        createErrorlog($run);
        $_SESSION['errorclass'] = "<p class='error-msg'>Some error has been occured. Please try again.</p>";
        header('location:manageroles.php');
    }
}
if (isset($_REQUEST["action"]) && $_REQUEST["action"] == "deleteroles"){
    $id=isset($_REQUEST["id"])?trim($_REQUEST["id"]):"";
    if(!empty($id)){
        $delete=sqlgetresult("DELETE FROM tbl_adminroles WHERE id= $id RETURNING id");
        if( $delete['id'] > 0 ) {   
          $deleteSFS = sqlgetresult('DELETE FROM tbl_admin_menu_access WHERE "roleId"= \''.$id.'\'');
          $_SESSION['success'] = "<p class='success-msg'>Deleted Successfully</p>";
        }else{
          createErrorlog($res);
          $_SESSION['failure'] = "<p class='error-msg'>Deleted Unsuccessful</p>";
        }

    }else{
     $_SESSION['failure'] = "<p class='error-msg'>Deleted Unsuccessful. Mandatory field missing.</p>";
    }
    header("Location: manageroles.php");
}

/*****Add Bank - Start*****/
if (isset($_POST["editbank"]) && $_POST["editbank"] == "update")
{
    $id = isset($_POST['id'])?trim($_POST['id']):"";;
    $uid = $_SESSION['myadmin']['adminid'];
    $wname = isset($_POST['bank'])?trim($_POST['bank']):"";
    $des = isset($_POST['des'])?trim($_POST['des']):"";
    $query = "SELECT * FROM editbank('$id','$wname','$des','$uid')";
    $run = sqlgetresult($query);
    // print_r($run);
    // exit;
    if ($run['editbank'] == 1)
    {
        $_SESSION['successclass'] = "<p class='success-msg'>Data Edited Successfully.</p>";
    }
    else if ($run['editbank'] == 0)
    {
        $_SESSION['errorclass'] = "<p class='error-msg'>Given Bank Name Already Exist</p>";
    }
    else
    {
        createErrorlog($run);
        $_SESSION['errorclass'] = "<p class='error-msg'>Some error has been occured. Please try again.</p>";
    }
    header('location:managebank.php');
}

if (isset($_POST["addbank"]) && $_POST["addbank"] == "new")
{
    // $n = 0;
    // $id=$_POST['id'];
    $uid = $_SESSION['myadmin']['adminid'];
    $wname = isset($_POST['bank'])?trim($_POST['bank']):"";
    $des = isset($_POST['des'])?trim($_POST['des']):"";
    $query = "SELECT * FROM addbank('$wname','$des','$uid')";
    $run = sqlgetresult($query);
    $last_id = $run['addbank'];
    if ($last_id > 0)
    {
        $_SESSION['successclass'] = "<p class='success-msg'>Data Added Successfully.</p>";
    }
    else if ($last_id == 0)
    {
        $_SESSION['errorclass'] = "<p class='error-msg'>Given Bank Name Already Exist.</p>";
    }
    else
    {
        createErrorlog($run);
        $_SESSION['errorclass'] = "<p class='error-msg'>Some error has been occured. Please try again.</p>";
    }
    header('location:managebank.php');
}
/* Bank End */
/*****Waivertype Table - End*****/
/*****Add Menu*****/
if (isset($_POST["addmenu"]) && $_POST["addmenu"] == "new")
{
  $uid = $_SESSION['myadmin']['adminid'];
  $menu = isset($_POST['menu'])?trim($_POST['menu']):"";
  $smenu = isset($_POST['smenu'])?trim($_POST['smenu']):"";
  $link = isset($_POST['link'])?trim($_POST['link']):"";
  $roleary = isset($_POST['role'])?$_POST['role']:[];
  $disp=isset($_POST['disp'])?$_POST['disp']:20;
   

    $query = "SELECT * FROM addmenu('$menu','$smenu','$link','$uid','$disp')";
    $run = sqlgetresult($query);
    $last_id = $run['addmenu'];
    if ($last_id > 0)
    {
        if(count($roleary) > 0){
            foreach ($roleary as $key => $value) {
                $status=0;
                if($value == 1){
                    $status=1;
                }
                $run1=sqlgetresult("SELECT * FROM addmenuaccess('$last_id','$value','$uid')");
                $lastaccess_id = $run1['addmenuaccess'];
                if($lastaccess_id > 0 && $status==0)
                {
                    $tbl = 'tbl_admin_menu_access';
                    $query1 = "SELECT * FROM statusupdate('$tbl','$status','$uid','$lastaccess_id')";
                    sqlgetresult($query1);
                }
            }
        }
        $_SESSION['successclass'] = "<p class='success-msg'>Data Added Successfully.</p>";
        header('location:managemenulist.php');
    }
    else if ($last_id == 0)
    {
        $_SESSION['errorclass'] = "<p class='error-msg'>Menu Already Exist.</p>";
        header('location:managemenulist.php');
    }
    else
    {
        createErrorlog($run);
        $_SESSION['errorclass'] = "<p class='error-msg'>Some error has been occured. Please try again.</p>";
        header('location:managemenulist.php');
    }

}

if (isset($_POST["editmenu"]) && $_POST["editmenu"] == "update")
{
    $id = $_POST['id'];
    $uid = $_SESSION['myadmin']['adminid'];
    $menu = isset($_POST['menu'])?trim($_POST['menu']):"";
    $smenu = isset($_POST['smenu'])?trim($_POST['smenu']):"";
    $link = isset($_POST['link'])?trim($_POST['link']):"";
    $disp=isset($_POST['disp'])?$_POST['disp']:20;
    $query = "SELECT * FROM editmenunew('$id','$menu','$smenu','$link','$uid','$disp')";
    $run = sqlgetresult($query);
    // print_r($run);
    // exit;
    if ($run['editmenunew'] == 1)
    {
        $_SESSION['successclass'] = "<p class='success-msg'>Data Edited Successfully.</p>";
        header('location:managemenulist.php');
    }
    else if ($run['editmenunew'] == 0)
    {
        $_SESSION['errorclass'] = "<p class='error-msg'>Menu Already Exist</p>";
        header('location:managemenulist.php');
    }
    else
    {
        createErrorlog($run);
        $_SESSION['errorclass'] = "<p class='error-msg'>Some error has been occured. Please try again.</p>";
        header('location:managemenulist.php');
    }
}

if (isset($_REQUEST["action"]) && $_REQUEST["action"] == "deletesubmenu"){
    $id=isset($_REQUEST["id"])?trim($_REQUEST["id"]):"";
    if(!empty($id)){
        $delete=sqlgetresult("DELETE FROM tbl_admin_submenu WHERE id= $id RETURNING id");
        if( $delete['id'] > 0 ) {   
          $deleteSFS = sqlgetresult('DELETE FROM tbl_admin_menu_access WHERE "menuId"= \''.$id.'\'');
          $_SESSION['success'] = "<p class='success-msg'>Deleted Successfully</p>";
        }else{
          createErrorlog($res);
          $_SESSION['failure'] = "<p class='error-msg'>Deleted Unsuccessful</p>";
        }

    }else{
     $_SESSION['failure'] = "<p class='error-msg'>Deleted Unsuccessful. Mandatory field missing.</p>";
    }
    header("Location: managemenulist.php");
}


if(isset($_POST['add_access']) && $_POST['add_access']=='confirm'){
/*echo "<pre>";
print_r($_POST);
print_r($menudetails);*/


$get_access_ids=isset($_POST['role_access_ids'])?trim($_POST['role_access_ids']):"";
$arrQry=[];
if(!empty($get_access_ids)){

  $ids=explode(",",$get_access_ids);
  foreach($ids as $id){
    $status= isset($_POST[$id])?trim($_POST[$id]):0;
    //$arrQry[]="('".$val."',".$id.")";
    $query = "UPDATE tbl_admin_menu_access SET status = '$status' WHERE id = $id";
    $res = sqlgetresult($query);
  } 
  $_SESSION['successcheque'] = "<p class='success-msg'>Admin role access has been modified successfully.</p>";
}else{
    $_SESSION['errorcheque'] = "<p class='error-msg'>Something went wrong.</p>";
}

header("Location: manageaccess.php");
 /*$valQry=implode(",",$arrQry);
}

echo 'UPDATE tbl_admin_menu_access as t set status = c.status FROM (VALUES '.$valQry.') as c (status, id) WHERE c.id = t.id';


print_r($arrQry);
exit;*/
}

/* Add Notify */
if (isset($_POST["addnotify"]) && $_POST["addnotify"] == "new")
{
    // $n = 0;
    // $id=$_POST['id'];
    $uid = $_SESSION['myadmin']['adminid'];
    $type = isset($_POST['type'])?trim($_POST['type']):"";
    $page = isset($_POST['page'])?trim($_POST['page']):"";
    $com = isset($_POST['txtEditorContent'])?htmlspecialchars($_POST['txtEditorContent']):"";
    $des = isset($_POST['des'])?trim($_POST['des']):"";
    $query = "SELECT * FROM addnotify('$type','$page','$des','".pg_escape_string ($com)."','$uid')";
    $run = sqlgetresult($query);
    $last_id = $run['addnotify'];
    if ($last_id > 0)
    {
        $_SESSION['successclass'] = "<p class='success-msg'>Data Added Successfully.</p>";
    }
    else if ($last_id == 0)
    {
        $_SESSION['errorclass'] = "<p class='error-msg'>Already Exist.</p>";
    }
    else
    {
        createErrorlog($run);
        $_SESSION['errorclass'] = "<p class='error-msg'>Some error has been occured. Please try again.</p>";
    }
    header('location:managenotification.php');
}
/* Update Notify */
if (isset($_POST["editnotify"]) && $_POST["editnotify"] == "update")
{
    $id = isset($_POST['id'])?trim($_POST['id']):"";;
    $uid = $_SESSION['myadmin']['adminid'];
    $type = isset($_POST['type'])?trim($_POST['type']):"";
    $page = isset($_POST['page'])?trim($_POST['page']):"";
    $com = isset($_POST['txtEditorContent'])?htmlspecialchars($_POST['txtEditorContent']):"";
    $des = isset($_POST['des'])?trim($_POST['des']):"";
    $query = "SELECT * FROM editnotify('$id','$type','$page','$des','".pg_escape_string ($com)."','$uid')";
    $run = sqlgetresult($query);
    $last_id = $run['editnotify'];
    // print_r($run);
    // exit;
    if ($last_id > 0)
    {
        $_SESSION['successclass'] = "<p class='success-msg'>Data Added Successfully.</p>";
    }
    else if ($last_id == 0)
    {
        $_SESSION['errorclass'] = "<p class='error-msg'>Already Exist.</p>";
    }
    else
    {
        createErrorlog($run);
        $_SESSION['errorclass'] = "<p class='error-msg'>Some error has been occured. Please try again.</p>";
    }
    header('location:managenotification.php');
}

function deleteChallans($cn){
    $cn = trim($cn);
    $status=0;
    if(!empty($cn)){
        $getchallandata = sqlgetresult('SELECT * FROM tbl_challans WHERE "challanNo" = \'' . $cn . '\' LIMIT 1');
        $query1 = 'DELETE FROM tbl_demand WHERE "challanNo" = \'' . $cn . '\' AND "studentId" = \'' . $getchallandata['studentId'] . '\' AND "academicYear" = \'' . $getchallandata['academicYear'] . '\' AND "term" = \'' . $getchallandata['term'] . '\'';
        $res1 = sqlgetresult($query1);

        $query2 = 'DELETE FROM tbl_challans WHERE "challanNo"=\'' . $cn . '\' ';
        $res2 = sqlgetresult($query2);

        $query3 = 'DELETE FROM tbl_temp_challans WHERE "challanNo"=\'' . $cn . '\' ';
        $res3 = sqlgetresult($query3);

        /* Reset Challan Create & Due date*/
        /*if($getchallandata['studentId']){
           toResetChallanDates($getchallandata['studentId']); 
        }*/

        // $query4 = 'DELETE FROM tbl_receipt WHERE "challanNo"=\'' . $cn . '\' ';
        // $res4 = sqlgetresult($query4);

        $getwaiverdata = getwaiveddata(trim($cn));
        $getsfsdata = getsfsdatabychn(trim($cn));

        if($getwaiverdata != 0){
            $query4 = 'DELETE FROM tbl_waiver WHERE "challanNo"=\'' . $cn . '\' AND "studentId" = \'' . $getchallandata['studentId'] . '\' ';
            $res4 = sqlgetresult($query4);
        }

        if($getsfsdata != 0){
            $query5 = 'DELETE FROM tbl_sfs_qty WHERE "challanNo"=\'' . $cn . '\' AND "studentId" = \'' . $getchallandata['studentId'] . '\' ';
            $res5 = sqlgetresult($query5);
        }
        $challanstatus = 0;
        $query6 = 'UPDATE tbl_student_ledger SET "challanStatus" = \'' . $challanstatus . '\' WHERE "challanNo"=\'' . $cn . '\' ';
        $res6 = sqlgetresult($query6);
        if (($res1[deleteupdate] == 0) && ($res2[deleteupdate] == 0) && ($res3[deleteupdate] == 0)) {
          $status=1; 
        } else {
          $status=0;
        }
    }
    return $status;
}

function enableDisableChallans($cn, $st){
    $cn = trim($cn);
    if($st == 1){
        $dalete = 0;
    }else{
        $dalete = 1;
    }
    $status=0;
    if(!empty($cn)){
        $query1 = 'UPDATE tbl_demand SET status=\'' . $st . '\', deleted=\'' . $dalete . '\' WHERE "challanNo" = \'' . $cn . '\'';
        $res1 = sqlgetresult($query1);

        $query2 = 'UPDATE tbl_challans SET status=\'' . $st . '\', deleted=\'' . $dalete . '\' WHERE "challanNo"=\'' . $cn . '\' ';
        $res2 = sqlgetresult($query2);

        $query3 = 'UPDATE tbl_temp_challans SET status=\'' . $st . '\', deleted=\'' . $dalete . '\' WHERE "challanNo"=\'' . $cn . '\' ';
        $res3 = sqlgetresult($query3);


        $getwaiverdata = getwaiveddata(trim($cn));
        if($getwaiverdata != 0){
            $query4 = 'UPDATE tbl_waiver SET status=\'' . $st . '\', deleted=\'' . $dalete . '\' WHERE "challanNo"=\'' . $cn . '\' AND "studentId" = \'' . $getchallandata['studentId'] . '\' ';
            $res4 = sqlgetresult($query4);
        }

        if (strpos($cn, 'TF-') !== false) {
            $st = ($st==1)?0:1;
            $query6 = 'UPDATE tbl_addtocart SET status=\'' . $st . '\' WHERE "challanNo"=\'' . $cn . '\' AND deleted=\'0\'';
            $res6 = sqlgetresult($query6);
        }

        if (($res1[deleteupdate] == 0) && ($res2[deleteupdate] == 0) && ($res3[deleteupdate] == 0)) {
          $status=1; 
        } else {
          $status=0;
        }
    }
    return $status;
}

function enableDisableNonFeeChallans($cn, $st){
    $cn = trim($cn);
    $status=0;
    if(!empty($cn)){
        $query1 = 'UPDATE tbl_nonfee_challans SET status=\'' . $st . '\' WHERE "challanNo" = \'' . $cn . '\'';
        $res1 = sqlgetresult($query1);
        if($res1[deleteupdate] == 0) {
          $status=1; 
        } else {
          $status=0;
        }
    }
    return $status;
}


if (isset($_POST["submit"]) && $_POST["submit"] == "deletechallan")
{   
    $ids=isset($_POST['checkme'])?$_POST['checkme']:[];
    if (count($ids) > 0)
    {
        foreach ($ids as $selected)
        {
          $status=deleteChallans($selected);
        }
    }
    $_SESSION['successdelete'] = "<p class='success-msg'>Deleted Successfully</p>";
    header('location:managecreatedchallans.php');
}
if (isset($_POST["submit"]) && $_POST["submit"] == "enablechallan")
{   
    $ids=isset($_POST['checkme'])?$_POST['checkme']:[];
    if (count($ids) > 0)
    {
        foreach ($ids as $selected)
        {
          $status=enableDisableChallans($selected, 1);
        }
    }
    $_SESSION['successdelete'] = "<p class='success-msg'>Enabled Successfully</p>";
    header('location:managecreatedchallans.php');
}

if (isset($_POST["submit"]) && $_POST["submit"] == "disablechallan")
{   
    $ids=isset($_POST['checkme'])?$_POST['checkme']:[];
    if (count($ids) > 0)
    {
        foreach ($ids as $selected)
        {
          $status=enableDisableChallans($selected, 0);
        }
    }
    $_SESSION['successdelete'] = "<p class='success-msg'>Disabled Successfully</p>";
    header('location:managecreatedchallans.php');
}
/* Non-Fee Challans */
if (isset($_POST["submit"]) && $_POST["submit"] == "enablenfchallan")
{   
    $ids=isset($_POST['checkme'])?$_POST['checkme']:[];
    if (count($ids) > 0)
    {
        foreach ($ids as $selected)
        {
          $status=enableDisableNonFeeChallans($selected, 1);
        }
    }
    $_SESSION['success'] = "<p class='success-msg'>Enabled Successfully</p>";
    header('location:createnonfeechallans.php');
}

if (isset($_POST["submit"]) && $_POST["submit"] == "disablenfchallan")
{   
    $ids=isset($_POST['checkme'])?$_POST['checkme']:[];
    if (count($ids) > 0)
    {
        foreach ($ids as $selected)
        {
          $status=enableDisableNonFeeChallans($selected, 0);
        }
    }
    $_SESSION['success'] = "<p class='success-msg'>Disabled Successfully</p>";
    header('location:createnonfeechallans.php');
}
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
/*if (isset($_POST['forgot']) && $_POST['forgot'] == "FORGOT PASSWORD")
{
    $e = isset($_POST["email"])?trim($_POST["email"]):"";
    $sql1 = 'SELECT * from adminchk WHERE "adminEmail"=\'' . $e . '\'';
    $result = sqlgetresult($sql1);
    if (!empty($result))
    {
        $to = $e;
        $name=ucfirst($result['adminName']);
        $churl=BASEURL."admin/changepassword.php?k=".base64_encode($to);
        $msg = "Hello " . $name . "! <br><br>";
        $msg2 = "Someone has requested a link to change your password. You can do this through the link below.<br><br><a href='" . $churl . "'>Change my password</a><br><br>If you didn't request this, please ignore this email.<br><br>Your password won't change until you access the link above and create a new one.";*/
        // $msg3 = "PASSWORD : ".$rpass."<br>";
        // print_r($msg2);
        // exit;
       /* $subject = "Admin Login - Reset Password Instructions";
        $data = $msg . $msg2;
        $send = SendMailId($to, $subject, $data);
        if ($send == true)
        {
            $_SESSION['successmsg'] = "<div class='success-msg'>You will receive an email with instructions about how to reset your password in few minutes.</div>";
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
}*/
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
        $sql1 = 'SELECT * from adminchk WHERE "adminEmail"=\'' . $e . '\'';
        $result = sqlgetresult($sql1);
        if (!empty($result))
        {
            $to = $e;
            $name=ucfirst($result['adminName']);
            $churl=BASEURL."admin/changepassword.php?k=".base64_encode($to);
            $msg = "Hello " . $name . "! <br><br>";
            $msg2 = "Someone has requested a link to change your password. You can do this through the link below.<br><br><a href='" . $churl . "'>Change my password</a><br><br>If you didn't request this, please ignore this email.<br><br>Your password won't change until you access the link above and create a new one.";
            // $msg3 = "PASSWORD : ".$rpass."<br>";
            // print_r($msg2);
            // exit;
            $subject = "Admin Login - Reset Password Instructions";
            $data = $msg . $msg2;
            $send = SendMailId($to, $subject, $data);
            if ($send == true)
            {
                $_SESSION['success_msg2'] = "<div class='success-msg'>You will receive an email with instructions about how to reset your password in few minutes.</div>";
                // echo($_SESSION['errormsg']);
                header('location:login.php');
                exit;
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
            exit;
        }
    }
}
/*FORGOT PASSWORD - End */

/* Filter Parents By Student ID */

if (isset($_POST['filter']) && $_POST['filter'] == "filterParentsbystudID")
{
    $studentid = isset($_POST['studentid'])?trim($_POST['studentid']):"";
    $where = '';
    $whereClauses = array(); 
    if (!empty($studentid)) {
        $whereClauses[] = '("studentId"=\''.trim(pg_escape_string($studentid)).'\' OR "application_no"=\''.trim(pg_escape_string($studentid)).'\' OR "old_studentId"  ILIKE \'%'.pg_escape_string($studentid).'%\')';

        if (count($whereClauses) > 0) 
        { 
            $where = ' WHERE '.implode(' AND ',$whereClauses); 
        } 

       $joinQry='SELECT parentid,pfirstname,plname,pemail,pmobile,parentstatus,"studentName","studentId" FROM  studentcheckflter '.$where;
        $sql = ($joinQry);
        //exit;
        $res = sqlgetresult($sql, true);      
        if ($res != 0)
        {
            foreach ($res as $k => $data)
            {                  
                $parentData[$k]['id'] = $data['parentid'];
                $parentData[$k]['studentId'] = trim($data['studentId']);
                $parentData[$k]['studentName'] = trim($data['studentName']);
                $parentData[$k]['parentname'] = trim($data['pfirstname'])." ".trim($data['plname']);
                $parentData[$k]['pemail'] = trim($data['pemail']);
                $parentData[$k]['pmobile'] = trim($data['pmobile']);
                $parentData[$k]['status']=trim($data['parentstatus']);
                // 
                /*if($data['parentstatus'] == 1){
                    $parentData[$k]['status'] = 'Active';
                }
                else{
                    $parentData[$k]['status'] = 'Inactive';
                }*/

            }
        }
    }
    echo json_encode($parentData);
}

/* Enable Partial payment */
if (isset($_POST["submit"]) && $_POST["submit"] == "enablePartialPayment")
{
    // $n = 0;
    // $id=$_POST['id'];
    $uid = $_SESSION['myadmin']['adminid'];
    $ids=isset($_POST['checkme'])?$_POST['checkme']:[];

    //$yr = sqlgetresult("SELECT MAX(id) from tbl_academic_year WHERE status='1' AND active=1 AND deleted=0");
    $acadamicyr=getCurrentAcademicYear();
    if (count($ids) > 0)
    {
        foreach ($ids as $selected)
        {
            $query = "SELECT * FROM addpartial('$selected','$acadamicyr','$uid')";
            $run = sqlgetresult($query);
        }
        $_SESSION['successclass'] = "<p class='success-msg'>Partial payment successfully enabled.</p>";
        header('location:managepartial.php');
    }else{
        $_SESSION['errorclass'] = "<p class='error-msg'>Some error has been occured. Please try again.</p>";
        header('location:managestd.php');
    }
    

}

/* Enable Student Status Active/Inactive */
if (isset($_POST["std_status_change"]) && $_POST["std_status_change"] == "std_status_change")
{
    $uid = isset($_SESSION['myadmin']['adminid'])?trim($_SESSION['myadmin']['adminid']):"";
    $ids = isset($_POST['checkme'])?$_POST['checkme']:[];
    $chkstatus = isset($_POST['changestatus'])?trim($_POST['changestatus']):"";
    if (count($ids) > 0 && !empty($chkstatus) && !empty($uid))
    {
        if($chkstatus == 'Active'){
           $status=1;
        }else{
            $status=0;
        }
        $tbl = 'tbl_student';
        foreach ($ids as $selected)
        {
            //$query = "SELECT * FROM tochangestudentstatus('$selected','$uid','$status')";
            $query = "SELECT * FROM statusupdate('$tbl','$status','$uid','$selected')";
            $run = sqlgetresult($query);
        }
        $_SESSION['successstd'] = "<p class='success-msg'>Student Status Updated Successfully.</p>";
        header('location:managestd.php');
    }else{
        $_SESSION['errorclass'] = "<p class='error-msg'>Some error has been occured. Please try again.</p>";
        header('location:managestd.php');
    }
}
/* To Change Acadamic Year For the Student */
if (isset($_POST["change_ay"]) && $_POST["change_ay"] == "edit_ay")
{
    $uid = isset($_SESSION['myadmin']['adminid'])?trim($_SESSION['myadmin']['adminid']):"";
    $ids=isset($_POST['checkme'])?$_POST['checkme']:[];
    $yearselect=isset($_POST['yearselect'])?trim($_POST['yearselect']):"";
    if (count($ids) > 0 && !empty($yearselect) && !empty($uid))
    {
        foreach ($ids as $selected)
        {
            $query = "SELECT * FROM tochangeayearinstudtbl('$selected','$yearselect','$uid')";
            $run = sqlgetresult($query);
        }
        $_SESSION['successstd'] = "<p class='success-msg'>Successfully updated.</p>";
    }else{
        $_SESSION['errorstd'] = "<p class='error-msg'>Some error has been occured. Please try again.</p>";
    }
    header('location:managestd.php');
}
/* To Change Term For the Student */
if (isset($_POST["change_term"]) && $_POST["change_term"] == "edit_tm")
{
    $uid = isset($_SESSION['myadmin']['adminid'])?trim($_SESSION['myadmin']['adminid']):"";
    $ids=isset($_POST['checkme'])?$_POST['checkme']:[];
    $semesterselect=isset($_POST['semesterselect'])?trim($_POST['semesterselect']):"";
    if (count($ids) > 0 && !empty($semesterselect) && !empty($uid))
    {
        foreach ($ids as $selected)
        {
           $query = "SELECT * FROM tochangeterminstudtbl('$selected','$semesterselect','$uid')";
           $run = sqlgetresult($query);
        }
        $_SESSION['successstd'] = "<p class='success-msg'>Successfully updated.</p>";
    }else{
        $_SESSION['errorstd'] = "<p class='error-msg'>Some error has been occured. Please try again.</p>"; 
    }
    header('location:managestd.php');
}

/* To Change Stream/Class/Section For the Student */
if (isset($_POST["change_stream_class"]) && $_POST["change_stream_class"] == "edit_stream_class")
{
    $uid = isset($_SESSION['myadmin']['adminid'])?trim($_SESSION['myadmin']['adminid']):"";
    $ids=isset($_POST['checkme'])?$_POST['checkme']:[];
    $stream = isset($_POST['streamselect'])?trim($_POST['streamselect']):"";
    $class = isset($_POST['classselect'])?trim($_POST['classselect']):"";
    $section = isset($_POST['sectionselect'])?trim($_POST['sectionselect']):"";
    if (count($ids) > 0 && !empty($stream) && !empty($class) && !empty($section) && !empty($uid))
    {
        foreach ($ids as $selected)
        {
           $query = "SELECT * FROM tochangestreamclassstud('$selected','$uid','$stream','$class','$section')";
           $run = sqlgetresult($query);
        }
        $_SESSION['successstd'] = "<p class='success-msg'>Successfully updated.</p>";
    }else{
        $_SESSION['errorstd'] = "<p class='error-msg'>Some error has been occured. Please try again.</p>"; 
    }
    header('location:managestd.php');
}

/* Delete partial payment student list */

if (isset($_POST["submit"]) && $_POST["submit"] == "deletepartial")
{   
    $uid = $_SESSION['myadmin']['adminid'];
    $ids=isset($_POST['checkme'])?$_POST['checkme']:[];
    $tbl = 'tbl_partial_payment';
    $page = 'managepartial.php';
    if (count($ids) > 0)
    {
        foreach ($ids as $selected)
        {
            $id=trim($selected);
            $query = "SELECT * FROM deleteupdate('$tbl','$uid','$id')";
            $res = sqlgetresult($query);
            if ($res['deleteupdate'] != 1)
            {
                createErrorlog($res);
            }
        }
        $_SESSION['success'] = "<p class='success-msg'>Deleted Successfully</p>";
    }else{
        //createErrorlog($res);
        $_SESSION['failure'] = "<p class='error-msg'>Deleted Unsuccessful</p>";
    }
    header("Location:" . $page);
}

/*******Filters - Start*****/

if (isset($_POST['filter']) && $_POST['filter'] == "filterstudentpartial")
{

    $classselect = isset($_POST['classselect'])?trim($_POST['classselect']):"";
    $streamselect = isset($_POST['streamselect'])?trim($_POST['streamselect']):"";
    $sectionselect = isset($_POST['sectionselect'])?trim($_POST['sectionselect']):"";
    $academicyr = isset($_POST['academicyr'])?trim($_POST['academicyr']):"";
    $status = isset($_POST['status'])?trim($_POST['status']):"";

    $where=[];
    if (!empty($classselect))
    {
        $where[] = 's."class"=\'' . $classselect . '\' ';

    }
    if (!empty($streamselect))
    {
        $where[] = 's."stream"=\'' . $streamselect . '\' ';

    }
    if (!empty($sectionselect))
    {
        $where[] = 's."section"=\'' . $sectionselect . '\' ';

    }
    if (!empty($academicyr))
    {
        $where[] = 'p."academic_yr"=\'' . $academicyr . '\' ';

    }
    if (!empty($status))
    {
        $where[]='p."status"=\'' . $status . '\' ';

    }
    $wherecond="";

    if(count($where) > 0){
        $wherecond="WHERE ".implode(" AND ", $where);
    }
    
    $sql = 'SELECT p.*,s."studentId",s."studentName",st.stream, c.class_list,ay.year as academic_yr FROM partiallist as p JOIN  tbl_student s ON p.sId::int = s.id   JOIN tbl_stream st ON s.stream::int = st.id LEFT JOIN tbl_class c ON c.id = s.class::int LEFT JOIN tbl_academic_year ay ON (ay.id = p."academic_yr"::integer) '.$wherecond.'  ORDER BY p.id DESC';

    $res = sqlgetresult($sql, true);

    echo json_encode($res);

}

/* Update Partial */
if (isset($_POST["editpartial"]) && $_POST["editpartial"] == "update")
{
    $id = isset($_POST['id'])?trim($_POST['id']):"";
    $uid = $_SESSION['myadmin']['adminid'];
    $yearselect = isset($_POST['yearselect'])?trim($_POST['yearselect']):"";
    $partial_min = isset($_POST['partial_min'])?trim($_POST['partial_min']):"";
    //$status = isset($_POST['status'])?trim($_POST['status']):"";
    $sid = isset($_POST['sid'])?trim($_POST['sid']):"";
    $query = "SELECT * FROM editpartial('$id','$sid','$yearselect','$uid')";
    $run = sqlgetresult($query);
    $last_id = $run['editpartial'];
    // print_r($run);
    // exit;
    if($partial_min){
        if($partial_min < 100){
            sqlgetresult('UPDATE tbl_partial_payment SET partial_min_percentage=\''.$partial_min.'\',"updatedBy"=\''.$uid.'\' WHERE id=\''.$id.'\''); 
        }
   }else{
     sqlgetresult('UPDATE tbl_partial_payment SET partial_min_percentage=NULL,"updatedBy"=\''.$uid.'\' WHERE id=\''.$id.'\''); 
   }
    
    if ($last_id > 0)
    {
        $_SESSION['successclass'] = "<p class='success-msg'>Data Added Successfully.</p>";
    }
    else if ($last_id == 0)
    {
        $_SESSION['errorclass'] = "<p class='error-msg'>Already Exist.</p>";
    }
    else
    {
        createErrorlog($run);
        $_SESSION['errorclass'] = "<p class='error-msg'>Some error has been occured. Please try again.</p>";
    }
    header('location:managepartial.php');
}



if (isset($_POST['filter']) && $_POST['filter'] == "fltadvancereport")
{

    $classselect = isset($_POST['classselect'])?trim($_POST['classselect']):"";
    $streamselect = isset($_POST['streamselect'])?trim($_POST['streamselect']):"";
    $sectionselect = isset($_POST['sectionselect'])?trim($_POST['sectionselect']):"";
    $academicyr = isset($_POST['yearselect'])?trim($_POST['yearselect']):"";
    $status = isset($_POST['status'])?trim($_POST['status']):"";
    $type = isset($_POST['type'])?trim($_POST['type']):"";
    $from = isset($_POST['from'])?trim($_POST['from']):"";
    $to = isset($_POST['to'])?trim($_POST['to']):"";

    $where=[];
    if (!empty($classselect))
    {
        $where[] = '"classList"=\'' . $classselect . '\' ';

    }
    if (!empty($streamselect))
    {
        $where[] = '"stream"=\'' . $streamselect . '\' ';

    }
    if (!empty($sectionselect))
    {
        $where[] = '"section"=\'' . $sectionselect . '\' ';

    }
    if (!empty($academicyr))
    {
        $where[] = '"academicYear"=\'' . $academicyr . '\' ';

    }
    if (!empty($type))
    {
        $where[]='"type"=\'' . $type . '\' ';

    }
    
    if (!empty($from) && !empty($to))
    {
        
        $where[] = 'DATE("createdOn") BETWEEN \'' . $from . '\'  AND  \'' . $to . '\'';
        

    }

    $wherecond="";

    if(count($where) > 0){
        $wherecond="WHERE ".implode(" AND ", $where);
    }
    
    //$sql = 'SELECT p.*,s."studentId",s."studentName",st.stream, c.class_list,ay.year as academic_yr FROM tbl_advance_payment_log as p JOIN  tbl_student s ON p.sId::int = s.id   JOIN tbl_stream st ON a.stream::int = st.id LEFT JOIN tbl_class c ON c.id = s.class::int LEFT JOIN tbl_academic_year ay ON (ay.id = p."academicYear"::integer) '.$wherecond.'  ORDER BY p.id DESC';

    $sql = 'SELECT * FROM advancepaymentlogdetails '.$wherecond.'  ORDER BY id DESC';

    $_SESSION['advancereportquery']=$sql; 

    $res = sqlgetresult($sql, true);

    echo json_encode($res);

}

if(isset($_GET['advancereport']) && $_GET['advancereport']=='exportexcel'){
// print_r($_SESSION['studentledgerquery']);
$sql = $_SESSION['advancereportquery'];
$sqlrun = sqlgetresult($sql, true);
// print_r($sqlrun);

       foreach($sqlrun AS $key => $output){
            $result_data[$key]['Ref Number']="ADV".$output['id'];
            $result_data[$key]['Student Id']=$output['studentId'];
            $result_data[$key]['Student Name']=$output['studentName'];
            $result_data[$key]['Class']=$output['class_list'];
            $result_data[$key]['Section']=$output['section'];
            $result_data[$key]['Academic Year']=$output['academic_yr'];            
            $result_data[$key]['Total']=$output['amount'];
            //$result_data[$key]['Pay Option']=$output['payoption'];
            if(trim($output['transStatus']) == 'Ok'){
                $result_data[$key]['Transaction Status']='Success';
            }
            else{
                $result_data[$key]['Transaction Status']='Failed';
            }
            $result_data[$key]['Type']=$output['type'];
            $result_data[$key]['Created Date']=$output['cdate'];
            if($output['remarks']){
                $result_data[$key]['Remarks']=trim($output['remarks']);
            }else{
                $result_data[$key]['Remarks']="";
            }
        }
// print_r($result_data);exit;
        foreach ($result_data as $k => $v) {
        $keys = array();
        foreach ($v as $field_code => $field_val)
        {       
            // if($field_val != '') {
                $keys[]=$field_code;                
            // }       
        }       
        $columns = $keys;
    }

    exportData($result_data, 'Advance Payment Report', $columns);
}


/*if (isset($_POST['filter']) && $_POST['filter'] == "fltpaidreport")
{

    $classselect = isset($_POST['classselect'])?trim($_POST['classselect']):"";
    $streamselect = isset($_POST['streamselect'])?trim($_POST['streamselect']):"";
    $semesterselect = isset($_POST['semesterselect'])?trim($_POST['semesterselect']):"";
    $academicyr = isset($_POST['yearselect'])?trim($_POST['yearselect']):"";
    $from = isset($_POST['from'])?trim($_POST['from']):"";
    $to = isset($_POST['to'])?trim($_POST['to']):"";
    $paymethod = isset($_POST['paymethod'])?$_POST['paymethod']:"";

    $where=[];
    $where[] = '"transStatus" =\'Ok\' AND ("challanStatus" = \'1\' OR "challanStatus" = \'2\') ';
    if (!empty($classselect))
    {
        $where[] = '"classList"=\'' . $classselect . '\' ';

    }
    if (!empty($streamselect))
    {
        $where[] = '"stream"=\'' . $streamselect . '\' ';

    }
    if (!empty($semesterselect))
    {
        $where[] = 'term=\'' . $semesterselect . '\' ';

    }
    if (!empty($academicyr))
    {
        $where[] = '"academicYear"=\'' . $academicyr . '\' ';

    }

    if (!empty($from) && !empty($to))
    {
        
        $where[] = 'DATE("transDate") BETWEEN \'' . $from . '\'  AND  \'' . $to . '\'';
        

    }

    if (!empty($paymethod)) {
      $where[] ="paymentmethod ILIKE '%".pg_escape_string ($paymethod)."%'"; 
    }
    
    $wherecond="";

    if(count($where) > 0){
        $wherecond="WHERE  ".implode(" AND ", $where);
    }
    
    $sql = 'SELECT DISTINCT "refchallanNo",* FROM partialpaymentreport '.$wherecond.'  ORDER BY id DESC';

    $_SESSION['paidreportquery']=$sql; 
    $res = sqlgetresult($sql, true);

    echo json_encode($res);

}*/

if (isset($_POST['filter']) && $_POST['filter'] == "fltpaidreport")
{

    $classselect = isset($_POST['classselect'])?trim($_POST['classselect']):"";
    $streamselect = isset($_POST['streamselect'])?trim($_POST['streamselect']):"";
    $semesterselect = isset($_POST['semesterselect'])?trim($_POST['semesterselect']):"";
    $academicyr = isset($_POST['yearselect'])?trim($_POST['yearselect']):"";
    $from = isset($_POST['from'])?trim($_POST['from']):"";
    $to = isset($_POST['to'])?trim($_POST['to']):"";
    $paymethod = isset($_POST['paymethod'])?$_POST['paymethod']:"";

    $where=[];
    $where[] = '"transStatus" =\'Ok\' AND ("challanStatus" = \'1\' OR "challanStatus" = \'2\') ';
    if (!empty($classselect))
    {
        $where[] = '"classList"=\'' . $classselect . '\' ';

    }
    if (!empty($streamselect))
    {
        $where[] = '"stream"=\'' . $streamselect . '\' ';

    }
    if (!empty($semesterselect))
    {
        $where[] = 'term=\'' . $semesterselect . '\' ';

    }
    if (!empty($academicyr))
    {
        $where[] = '"academicYear"=\'' . $academicyr . '\' ';

    }

    if (!empty($from) && !empty($to))
    {
        
        $where[] = 'DATE("transDate") BETWEEN \'' . $from . '\'  AND  \'' . $to . '\'';
        

    }

    if (!empty($paymethod)) {
      $where[] ="paymentmethod ILIKE '%".pg_escape_string ($paymethod)."%'"; 
    }
    
    $wherecond="";

    if(count($where) > 0){
        $wherecond="WHERE  ".implode(" AND ", $where);
    }
    
    $sql = 'SELECT DISTINCT "refchallanNo",* FROM partialpaymentreport '.$wherecond.'  ORDER BY id DESC';

    $_SESSION['paidreportquery']=$sql; 
    $res = sqlgetresult($sql, true);
    $fdata=[];
    foreach($res AS $key => $output){
        //$refnum=$output['id'];
        $refnum=$key;
        $fdata[$refnum]['transNum']=$output['transNum'];
        $chlstudentid=trim($output['chlstudentid']);
        $plstudentid=trim($output['plstudentid']);
        if($plstudentid){
            $fdata[$refnum]['studentId']=$plstudentid;
        }else{
            $fdata[$refnum]['studentId']=$chlstudentid;
        }
        $fdata[$refnum]['studentName']=trim($output['studentName']);
        $fdata[$refnum]['streamname']=trim($output['streamname']);
        $fdata[$refnum]['class_list']=trim($output['class_list']);
        $fdata[$refnum]['academic_yr']=trim($output['academic_yr']);
        $fdata[$refnum]['term']=$output['term'];
        //$fdata[$refnum]['receivedamount']=$output['receivedamount'];
        $fdata[$refnum]['paidamt']=$output['paidamt'];
        $fdata[$refnum]['transDate']=$output['transDate'];
        $type = $output['type'];
        $fdata[$refnum]['type']=$type;
        if($type == 'full'){
            $fdata[$refnum]['challanNo'] = $output['challanNo'];
        }else{
            $fdata[$refnum]['challanNo'] = $output['refchallanNo'];
        }
        if($output['paymentmethod']){
            $fdata[$refnum]['paymentmethod']=ucfirst($output['paymentmethod']);
        }else{
            $fdata[$refnum]['paymentmethod']="";
        }
    }
    $outputdata=[]; 
    foreach($fdata AS $data){
        $outputdata[]= $data;
    }
    echo json_encode($outputdata);

}

if(isset($_GET['paidreport']) && $_GET['paidreport']=='exportexcel'){
// print_r($_SESSION['studentledgerquery']);
$sql = $_SESSION['paidreportquery'];
$sqlrun = sqlgetresult($sql, true);
// print_r($sqlrun);

       foreach($sqlrun AS $key => $output){
            $result_data[$key]['Ref Number']=$output['transNum'];
            $chlstudentid=trim($output['chlstudentid']);
            $plstudentid=trim($output['plstudentid']);
            if($plstudentid){
                $result_data[$key]['Student Id']=$plstudentid;
            }else{
                $result_data[$key]['Student Id']=$chlstudentid;
            }
            $result_data[$key]['Student Name']=$output['studentName'];
            if($output['type']=='full'){
                $result_data[$key]['Challan Number']=$output['parchallanno'];
            }else{
               $result_data[$key]['Challan Number']=$output['refchallanNo']; 
            }
            $result_data[$key]['Stream']=$output['streamname'];
            $result_data[$key]['Class']=$output['class_list'];
            $result_data[$key]['Academic Year']=$output['academic_yr'];
            $result_data[$key]['Term']=$output['term'];
            
            //$result_data[$key]['Total']=$output['receivedamount'];
            //$result_data[$key]['Total']=$output['paidamt']+$output['balanceamt'];
            //$result_data[$key]['Total']=$output['receivedamount'];
            $result_data[$key]['Total']=$output['paidamt'];
            //$result_data[$key]['Status']=$output['transStatus'];
            $result_data[$key]['Created Date']=$output['transDate'];
            $result_data[$key]['Remarks']=$output['remarks'];
            if($output['paymentmethod']){
                $result_data[$key]['Payment Method']=ucfirst($output['paymentmethod']);
            }else{
                $result_data[$key]['Payment Method']="";
            }
            //$result_data[$key]['Pay Option']=$output['payoption'];
            /*if($output['challanStatus'] == 1){
                $result_data[$key]['Challan Status']='Active';
            }
            elseif($output['challanStatus'] == 2){
                $result_data[$key]['Challan Status']='Partial Paid';
            }
            else{
                $result_data[$key]['Challan Status']='Inactive';
            }*/
        }
// print_r($result_data);exit;
        foreach ($result_data as $k => $v) {
        $keys = array();
        foreach ($v as $field_code => $field_val)
        {       
            // if($field_val != '') {
                $keys[]=$field_code;                
            // }       
        }       
        $columns = $keys;
    }

    exportData($result_data, 'Paid Report', $columns);
}


if (isset($_POST['filter']) && $_POST['filter'] == "fltconsolidatereport")
{
   
    $classselect = isset($_POST['classselect'])?trim($_POST['classselect']):"";
    $streamselect = isset($_POST['streamselect'])?trim($_POST['streamselect']):"";
    $semesterselect = isset($_POST['semesterselect'])?trim($_POST['semesterselect']):"";
    $academicyr = isset($_POST['yearselect'])?trim($_POST['yearselect']):"";
    $challanstatus = isset($_POST['challanstatus'])?trim($_POST['challanstatus']):"";
    $stid = isset($_POST['stid'])?trim($_POST['stid']):"";
    //$from = isset($_POST['from'])?trim($_POST['from']):"";
    //$to = isset($_POST['to'])?trim($_POST['to']):"";

    $where=[];
    $where[] = '(c.deleted = 0) AND (c.status = \'1\'::status) AND (c."academicYear" >=6) ';
    if (!empty($classselect))
    {
        $where[] = 'c."classList"=\'' . $classselect . '\' ';

    }
    if (!empty($streamselect))
    {
        $where[] = 'c."stream"=\'' . $streamselect . '\' ';

    }
    if (!empty($semesterselect))
    {
        $where[] = 'c.term=\'' . $semesterselect . '\' ';

    }
    if (!empty($academicyr))
    {
        $where[] = 'c."academicYear"=\'' . $academicyr . '\' ';

    }

    if (!empty($stid))
    {
        $where[] = 'c."studentId"=\'' . $stid . '\' ';

    }

    if (!empty($challanstatus))
    {
        if($challanstatus=='3'){
          $where[] ='c."challanStatus"=\'0\' ' ;
        }else{
          $where[] ='c."challanStatus"=\'' .pg_escape_string($challanstatus). '\' ';
        }
    }

    /*if (!empty($from) && !empty($to))
    {
        
        $where[] = 'DATE("createdOn") BETWEEN \'' . $from . '\'  AND  \'' . $to . '\'';
        

    }*/
    
    $wherecond="";

    if(count($where) > 0){
        $wherecond="WHERE  ".implode(" AND ", $where);
    }
    
    //$sql = 'SELECT p.*,s."studentId",s."studentName",st.stream, c.class_list,ay.year as academic_yr FROM tbl_advance_payment_log as p JOIN  tbl_student s ON p.sId::int = s.id   JOIN tbl_stream st ON a.stream::int = st.id LEFT JOIN tbl_class c ON c.id = s.class::int LEFT JOIN tbl_academic_year ay ON (ay.id = p."academicYear"::integer) '.$wherecond.'  ORDER BY p.id DESC';

  $sql = 'SELECT c."studentId",s."studentName",cl.class_list,c.term,ay.year AS academic_yr,string_agg(c."challanNo", \',\') AS challanids,sum(c.org_total) AS demand  FROM tbl_challans c LEFT JOIN tbl_student s
 ON (((s."studentId")::bpchar = (c."studentId")::bpchar)  OR (((c."studentId")::bpchar)::TEXT = ANY (string_to_array ((s."old_studentId")::TEXT,\',\'::TEXT))) OR ((s.application_no)::bpchar =(c."studentId")::bpchar)) LEFT JOIN tbl_class cl ON (c."classList" = cl.id) LEFT JOIN tbl_academic_year ay ON (ay.id = c."academicYear") '.$wherecond.' GROUP BY c."studentId",s."studentName",cl.class_list,c.term,ay.year';
 //exit;

    //$sql = 'SELECT "studentId","classList",term,"academicYear",string_agg("challanNo", \',\') AS challanids,sum(org_total) AS demand FROM tbl_challans LEFT JOIN tbl_class cl ON (c."classList" = cl.id) WHERE "academicYear" >=7 '.$wherecond.' GROUP BY "studentId","classList",term,"academicYear" ';

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
        $advpaid=0;
        $partialpaid=0;
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

            /*$pdata=getTotalPaidbychallan($challanno);
            if(isset($pdata['paid_total']) && !empty($pdata['paid_total'])){
                $paid+=$pdata['paid_total'];
            }
           $advpaid+=getPaidbyAdvancechallan($challanno);*/
           //$paid+=getAmtPaidbychallan($challanno);
           $paid+=getReceiptChallan($challanno);
           $partialpaid+=getReceiptChallanPartial($challanno);

        }
        /*if($waived > 0 && $paid > 0){
            if($paid >= $waived){
                $receiptAmt=$paid-$waived; 
            }else{
                $receiptAmt=$waived-$paid; 
            }
        }else{
            $receiptAmt=$paid;
        }*/
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
        $res[$key]['waiver']=$waived;
        $res[$key]['receipt']=$totalpaid;
        $res[$key]['outstanding']=$demand-$paidDm;
        unset($res[$key]['challanids']);
    }

    //print_r($res);

    echo json_encode($res);



}


if(isset($_GET['consolidated']) && $_GET['consolidated']=='exportexcel'){
$sql = $_SESSION['consolidatereportquery'];
$sqlrun = sqlgetresult($sql, true);

       foreach($sqlrun AS $key => $output){
            //$result_data[$key]['Ref Number']=$output['transNum'];
            $result_data[$key]['Student Id']=$output['studentId'];
            $result_data[$key]['Student Name']=$output['studentName'];
            $result_data[$key]['Class']=$output['class_list'];
            $result_data[$key]['Academic Year']=$output['academic_yr'];
            $result_data[$key]['Term']=$output['term'];
            
            $demand=trim($output['demand']);
            $result_data[$key]['Demand']=$demand;
            $challanids=trim($output['challanids']);
            $challanarry=explode(",",$challanids);
            $challanarry=array_unique($challanarry);
            $waived=0;
            $paid=0;
            $advpaid=0;
            $partialpaid=0;
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
                /*$pdata=getTotalPaidbychallan($challanno);
                if(isset($pdata['paid_total']) && !empty($pdata['paid_total'])){
                    $paid+=$pdata['paid_total'];
                }
                $advpaid+=getPaidbyAdvancechallan($challanno);*/
                //$paid+=getAmtPaidbychallan($challanno);
                $paid+=getReceiptChallan($challanno);
                $partialpaid+=getReceiptChallanPartial($challanno);
            }
            /*if($waived > 0 && $paid > 0){
                if($paid >= $waived){
                    $receiptAmt=$paid-$waived; 
                }else{
                    $receiptAmt=$waived-$paid; 
                }
            }else{
                $receiptAmt=$paid;
            }*/
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
            $result_data[$key]['Waiver']=$waived;
            //$result_data[$key]['Receipt']=$paid;
            $result_data[$key]['receipt']=$totalpaid;
            //$result_data[$key]['AdvancePaid']=$advpaid;
            //$result_data[$key]['Outstanding']=$demand-$paid-$waived;
            $result_data[$key]['outstanding']=$demand-$paidDm;
                
        }
        foreach ($result_data as $k => $v) {
        $keys = array();
        foreach ($v as $field_code => $field_val)
        {       
                $keys[]=$field_code;                
        }       
        $columns = $keys;
    }

    //$columns=array('Student Id','Student Name','Student Name','Class','Academic Year','Term','Demand','Waiver','Receipt','Outstanding');

    exportData($result_data, 'Consolidated Report', $columns);
}

//*************OTHER FEES (LUNCH, UNIFORM) FILTER- Start************//
if (isset($_POST['filter']) && $_POST['filter'] == "otherfeefilter")
{
    $_POST = array_map('trim',$_POST);
    $classselect = isset($_POST['classselect'])?$_POST['classselect']:"";
    $streamselect = isset($_POST['streamselect'])?$_POST['streamselect']:"";
    $sectionselect = isset($_POST['sectionselect'])?$_POST['sectionselect']:"";
    $yearselect = isset($_POST['yearselect'])?$_POST['yearselect']:"";
    $semesterselect = isset($_POST['semesterselect'])?$_POST['semesterselect']:"";
    $refnumber = isset($_POST['refnumber'])?$_POST['refnumber']:"";
    $stats = isset($_POST['tstatus'])?$_POST['tstatus']:"";
    $type = isset($_POST['type'])?$_POST['type']:1;
    $from = isset($_POST['from'])?$_POST['from']:"";
    $to = isset($_POST['to'])?$_POST['to']:"";
    $typename="UNIFORM";

    $paymethod = isset($_POST['paymethod'])?$_POST['paymethod']:"";
    $feetypes = isset($_POST['feetypes'])?$_POST['feetypes']:"";
    $selected_feetypes=[];
    $squery=[];
    $qtxt="";
    if($feetypes){
       $selected_feetypes = explode(',', $feetypes);
       if(count($selected_feetypes) > 0){
          foreach ($selected_feetypes as $value) {
            $squery[]='"feeType"= \''.pg_escape_string($value).'\'';
          }
          $qtxt=implode(" OR ", $squery);
       }
    }


    $whereClauses = array();
    $where = '';

    $whereClausesOldOld = array();
    $whereOld = ''; 

    $challanData = array();
    $challanNo = '';
    $outputdata = array();
    $paidchallan = array();

    if(!empty($refnumber)){
        $whereClauses[] ='"refNum"=\''.pg_escape_string($refnumber).'\' ' ;
    }
    if($qtxt){
        $whereClauses[] ='('.$qtxt.')' ;
    }
    if(!empty($type)){
        $whereClauses[] ='typeid=\''.pg_escape_string($type).'\' ' ;

        if($type==1){
           $typename="UNIFORM";
           $whereClausesOld[] ='p."challanNo" ILIKE \'%UNIFORM%\' ' ;
        }

        if($type==2){
           $typename="LUNCH"; 
           $whereClausesOld[] ='p."challanNo" ILIKE \'%LUNCH%\' ' ;
        }
    }
    if(!empty($stats)){
        $whereClauses[] ='"transStatus"=\''.pg_escape_string($stats).'\' ' ;
        $whereClausesOld[] ='p."transStatus"=\''.pg_escape_string($stats).'\' ' ;
    }

    if (!empty($yearselect)){
        $whereClauses[] ='"academicYear"=\''.pg_escape_string($yearselect).'\' ' ;
        $whereClausesOld[] ='s."academic_yr"=\''.pg_escape_string($yearselect).'\' ' ;
    }

    if (!empty($paymethod)) {
        $whereClauses[] ="paymethod ILIKE '%".pg_escape_string ($paymethod)."%'"; 
    }

    if (!empty($semesterselect)) {
      $whereClauses[] ="term='".pg_escape_string ($semesterselect)."'";
      $whereClausesOld[] ="s.term='".pg_escape_string ($semesterselect)."'";
    }

    if (!empty($classselect)) {
        $whereClauses[] ='"classList"=\''.pg_escape_string($classselect).'\' ';
        $whereClausesOld[] ='s."class"=\''.pg_escape_string($classselect).'\' ';
    }

    if (!empty($streamselect)) {
      $whereClauses[] ="stream='".pg_escape_string ($streamselect)."'";
      $whereClausesOld[] ="s.stream='".pg_escape_string ($streamselect)."'";  
    }

    if (!empty($sectionselect)) {
      $whereClauses[] ="section='".pg_escape_string ($sectionselect)."'";
      $whereClausesOld[] ="s.section='".pg_escape_string ($sectionselect)."'";  
    }

    if (!empty($from) && !empty($to))
    {
      $whereClauses[] = 'DATE("transDate") BETWEEN \'' . pg_escape_string ($from) . '\'  AND  \'' . pg_escape_string ($to) . '\'';
      $whereClausesOld[] = 'DATE(p."transDate") BETWEEN \'' . $from . '\'  AND  \'' . $to . '\'';
    }

    if (count($whereClauses) > 0) 
    { 
      $where = ' WHERE '.implode(' AND ',$whereClauses); 
    } 
    
    if($type==3){
        $sql = ('SELECT * FROM otherfeetransportreport '.$where);
    }else{
        $sql = ('SELECT * FROM otherfeesreport '.$where);
    }
    $_SESSION['otherfeereportquery']=$sql; 
    $res = sqlgetresult($sql, true);
    if (count($res) > 0)
    {
        foreach ($res as $k => $data)
        {
            
            $unique_id = $data['id'];
            $challanno=trim($data['challanNo']);
            $status=trim($data['transStatus']);
            $challanData[$unique_id]['id'] = $data['id'];
            $challanData[$unique_id]['studentId'] = trim($data['studentId']);
            $challanData[$unique_id]['chlstudentid'] = trim($data['chlstudentid']);
            $challanData[$unique_id]['challanNo'] = $challanno;
            $challanData[$unique_id]['studentName'] = trim($data['studentName']);
            $challanData[$unique_id]['academic_yr'] = trim($data['academic_yr']);
            $challanData[$unique_id]['term'] = trim($data['term']);
            $challanData[$unique_id]['streamname'] = trim($data['streamname']);
            $challanData[$unique_id]['class_list'] = trim($data['class_list']);
            $challanData[$unique_id]['section'] = trim($data['section']);
            if($data['typeid'] == 1 || $data['typeid'] == 4){
               $challanData[$unique_id]['feetypename'] = trim($data['feetypename'])." - ".trim($data['quantity']);
               //createUFPDF(trim($data['pyid']),trim($data['studentId']));
            }else{
              $challanData[$unique_id]['feetypename'] = trim($data['feetypename']);
            }
            $ref_num=trim($data['transNum']);
            $challanData[$unique_id]['term'] = $data['term'];
            if($data['transDate']){
                $challanData[$unique_id]['transDate'] = date("d-m-Y", strtotime($data['transDate']));
            }else{
                $challanData[$unique_id]['transDate'] = "";
            }
            $challanData[$unique_id]['amount'] = $data['amount'];
            $challanData[$unique_id]['transStatus'] = $status;
            $challanData[$unique_id]['transNum'] = $ref_num;
            $challanData[$unique_id]['transId'] = trim($data['transId']);
            $challanData[$unique_id]['pay_type'] = trim($data['pay_type']);
            if($data['paymethod']){
              $challanData[$unique_id]['method'] = ucfirst($data['paymethod']);
            }else{
              $challanData[$unique_id]['method'] = "";
            }
            if($status=='Ok'){
              //$datefolder = date("dmY", strtotime($data['transDate']));
              $datefolder = strtolower(trim($data['type']));
              if($data['typeid'] == 1 || $data['typeid'] == 2 || $data['typeid'] == 4){
                $isold=trim($data['isold']);
                if($isold==1){
                  $challanData[$unique_id]['pdf'] = "../receipts/".$datefolder."/".str_replace('/', '', $challanno).".pdf";
                }else{
                  $challanData[$unique_id]['pdf'] = "../receipts/".$datefolder."/".str_replace('/', '', $ref_num).".pdf";
                }
              }else{
                 $challanData[$unique_id]['pdf'] = "../receipts/".$datefolder."/".str_replace('/', '', $challanno).".pdf";
              }
            }else{
              $challanData[$unique_id]['pdf'] = "nil";
            } 
        }
       

        //$outputdata = $paidchallan;
    }

    /* Other Fees OLD data*/
    if($type == 1){
        if (count($whereClausesOld) > 0) 
        { 
        $whereOld = ' AND '.implode(' AND ',$whereClausesOld); 
        } 

        $sqlOld = 'SELECT p.id,s."studentId",p."challanNo",s."studentName",p."transDate",p.amount,p."transDate",p."transStatus",p."transNum",p."transId",p."createdOn",s.id as sid,s.term,cl.class_list,str.stream as streamname,ay.year as academic_yr,s.section FROM tbl_payments p JOIN tbl_student s ON (s."studentId" = p."studentId" OR s."application_no" = p."studentId" OR ((p."studentId")::bpchar)::TEXT = ANY (string_to_array ((s."old_studentId")::TEXT,\',\'::TEXT))) LEFT JOIN tbl_class cl ON (cl.id = s.class::integer) LEFT JOIN tbl_stream str ON (str.id = s.stream::integer) LEFT JOIN tbl_academic_year ay ON (ay.id = s.academic_yr::integer) WHERE s."status" = \'1\' AND s."deleted" = \'0\' AND p."transStatus"=\'Ok\' AND p."transNum" NOT LIKE \'%UNI%\' '.$whereOld.' ORDER BY p.id DESC';
        $_SESSION['otherfeereportqueryold']=$sqlOld; 
        $resOld = sqlgetresult($sqlOld, true);
        if (count($resOld) > 0)
        {
        foreach ($resOld as $kold => $dataold)
        {
            
            $unique_id = $dataold['id'];
            $challanno=trim($dataold['challanNo']);
            $status=trim($dataold['transStatus']);
            $challanData[$unique_id]['id'] = $dataold['id'];
            $challanData[$unique_id]['studentId'] = trim($dataold['studentId']);
            $challanData[$unique_id]['challanNo'] = $challanno;
            $challanData[$unique_id]['studentName'] = trim($dataold['studentName']);
            $challanData[$unique_id]['streamname'] = trim($dataold['streamname']);
            $challanData[$unique_id]['class_list'] = $dataold['class_list'];
            $challanData[$unique_id]['academic_yr'] = $dataold['academic_yr'];
            $challanData[$unique_id]['section'] = $dataold['section'];
            $challanData[$unique_id]['feetypename'] = "";
            $challanData[$unique_id]['term'] = $dataold['term'];
            $challanData[$unique_id]['transDate'] = date("d-m-Y", strtotime($dataold['transDate']));
            $challanData[$unique_id]['amount'] = $dataold['amount'];
            $challanData[$unique_id]['transStatus'] = $status;
            $challanData[$unique_id]['transNum'] = trim($dataold['transNum']);
            $challanData[$unique_id]['transId'] = trim($dataold['transId']);
            $challanData[$unique_id]['pay_type'] = "";
            if($status=='Ok'){
              $datefolder = strtolower($typename);
              $challanData[$unique_id]['pdf'] = "../receipts/".$datefolder."/".str_replace('/', '', $challanno).".pdf";
            }else{
              $challanData[$unique_id]['pdf'] = "nil";
            } 
        }

        }
    }
    

    foreach($challanData AS $challan){
        $paidchallan[]= $challan;
    }


    if (count($paidchallan) > 0){
        $outputdata = $paidchallan;
    }
    else
    {
        $outputdata = array();
    }
    echo json_encode($outputdata);

}
/*******OTHER FEES (LUNCH, UNIFORM) FILTER - End*****/

//*************Filter (LUNCH, UNIFORM) FILTER- Start************//
if (isset($_POST['filter']) && $_POST['filter'] == "filterfeepayment")
{
    $_POST = array_map('trim',$_POST);
    $classselect = isset($_POST['classselect'])?$_POST['classselect']:"";
    $streamselect = isset($_POST['streamselect'])?$_POST['streamselect']:"";
    $sectionselect = isset($_POST['sectionselect'])?$_POST['sectionselect']:"";
    $yearselect = isset($_POST['yearselect'])?$_POST['yearselect']:"";
    $semesterselect = isset($_POST['semesterselect'])?$_POST['semesterselect']:"";
    $type = isset($_POST['type'])?$_POST['type']:"";


    $whereClauses = array();
    $where = ''; 

    if(!empty($type)){
        $whereClauses[] ='f."feeType" ILIKE \'%'.pg_escape_string($type).'%\' ' ;
    } 

    if (!empty($yearselect)){
        $whereClauses[] ='c."academicYear"=\''.pg_escape_string($yearselect).'\' ' ;
    }

    if (!empty($semesterselect)) {
      $whereClauses[] ="c.semester='".pg_escape_string ($semesterselect)."'"; 
    }

    if (!empty($classselect)) {
        $whereClauses[] ='c."class"=\''.pg_escape_string($classselect).'\' ' ;
    }

    if (!empty($streamselect)) {
      $whereClauses[] ="c.stream='".pg_escape_string ($streamselect)."'"; 
    }

    if (!empty($sectionselect)) {
      $whereClauses[] ="c.section='".pg_escape_string ($sectionselect)."'"; 
    }
    

    if (count($whereClauses) > 0) 
    { 
      $where = ' AND '.implode(' AND ',$whereClauses); 
    } 

    //$outputdata = array();

   //$sql = 'SELECT p.id,s."studentId",p."challanNo",s."studentName",c1."class_list",ay.year as academic_yr,s.section FROM tbl_payments p JOIN tbl_student s ON (s."studentId" = p."studentId" OR s."application_no" = p."studentId" OR ((p."studentId")::bpchar)::TEXT = ANY (string_to_array ((s."old_studentId")::TEXT,\',\'::TEXT))) LEFT JOIN tbl_class cl ON (cl.id = s.class) LEFT JOIN tbl_stream str ON (str.id = s.stream) LEFT JOIN tbl_academic_year ay ON (ay.id = s.academic_yr) WHERE s."status" = \'1\' AND s."deleted" = \'0\''.$where;
    //$sql = 'SELECT f."feeType" , c.amount , f.id, c.id as feeconfigid,s."studentId",s."studentName",c1."class_list",ay.year as academic_yr,s.section,s.term,str.streamname FROM tbl_fee_type f JOIN tbl_fee_configuration c ON f.id = c."feeType"::integer JOIN tbl_student s ON(c.stream= s.stream AND c.class = s.class AND c.semester = s.term AND c."academicYear"=s.academic_yr) LEFT JOIN tbl_class cl ON (cl.id = c.class::integer) LEFT JOIN tbl_stream str ON (str.id = c.stream::integer) LEFT JOIN tbl_academic_year ay ON (ay.id = c."academicYear"::integer)  WHERE f."status" = \'1\' '.$where;

    $sql = 'SELECT f."feeType" , c.amount , f.id, c.id as feeconfigid,s."studentId",s."studentName",cl."class_list",ay.year as academic_yr,s.section,s.term,str.stream as streamname,c."createdOn",s.id as sid FROM tbl_fee_type f JOIN tbl_fee_configuration c ON f.id = c."feeType"::integer JOIN tbl_student s ON(c.stream = s.stream AND c.class = s.class::integer AND c.semester = s.term AND c."academicYear"=s.academic_yr) LEFT JOIN tbl_class cl ON (cl.id = c.class::integer) LEFT JOIN tbl_stream str ON (str.id = c.stream::integer) LEFT JOIN tbl_academic_year ay ON (ay.id = c."academicYear"::integer) WHERE f."status" = \'1\' AND (f."applicable" ILIKE \'%L%\' OR f."applicable" ILIKE \'%U%\') '.$where;
    $res = sqlgetresult($sql, true);

    //print_r($res);
    //exit;
    $challanData = array();
    $challanNo = '';
    $outputdata = array();
    $paidchallan = array();
    if (count($res) > 0)
    {
        foreach ($res as $k => $data)
        {
            
            $amount=trim($data['amount']);
            $id=trim($data['id']);
            $feeconfigid=trim($data['feeconfigid']);
            $studentId = trim($data['studentId']);
            $unique_id = trim($data['sid']);
            $appl = trim($data['applicable']);
            if($appl == 'U'){
              $eventname = "UNIFORM-".$id."-".$feeconfigid."-".$unique_id;
            }else{
              $eventname = "LUNCH-".$id."-".$feeconfigid."-".$unique_id;   
            }
            $challanData[$k]['feeconfigid'] = $feeconfigid;
            $challanData[$k]['studentId'] = trim($data['studentId']);
            $challanData[$k]['studentName'] = trim($data['studentName']);
            $challanData[$k]['streamname'] = trim($data['streamname']);
            $challanData[$k]['class_list'] = $data['class_list'];
            $challanData[$k]['academic_yr'] = $data['academic_yr'];
            $challanData[$k]['section'] = $data['section'];
            $challanData[$k]['term'] = $data['term'];
            $challanData[$k]['createdOn'] = date("d-m-Y", strtotime($data['createdOn']));
            $challanData[$k]['amount'] = $amount;
            $challanData[$k]['feeType'] = trim($data['feeType']);
            $challanData[$k]['feeTypeId'] = trim($data['id']);
            $challanData[$k]['event'] = $eventname;
        }
        foreach($challanData AS $challan){
            $paidchallan[]= $challan;
        }

        $outputdata = $paidchallan;
    }
    else
    {
        $outputdata = array();
    }
    echo json_encode($outputdata);

}
/*******OTHER FEES (LUNCH, UNIFORM) FILTER - End*****/
//***********Chequedd Feetye section - Start***********//
if (isset($_POST['fee_other_pay']) && $_POST['fee_other_pay'] == 'confirm'){
    //$_POST = array_map('trim',$_POST);
    $feeTypes=$_POST['feetypechk'];
    $numfeeType=count($feeTypes);
    $payment_insert_id = 0;
    if($numfeeType > 0){
        date_default_timezone_set("Asia/Kolkata");    
        $cur_data = time();
        $date = date('Y-m-d h:i:s');

        $uid = $_SESSION['myadmin']['adminid'];
        $s_uid = isset($_POST['s_uid'])?trim($_POST['s_uid']):"";
        
        $student_id = isset($_POST['sid'])?trim($_POST['sid']):"";
        $pid = isset($_POST['pid'])?trim($_POST['pid']):"";
        $term = isset($_POST['term'])?trim($_POST['term']):"";
        $stream = isset($_POST['stream'])?trim($_POST['stream']):"";
        $class = isset($_POST['class'])?trim($_POST['class']):"";
        $academicyear = isset($_POST['academicyear'])?trim($_POST['academicyear']):"";
        $classid = isset($_POST['classid'])?trim($_POST['classid']):"";
        $streamid = isset($_POST['streamid'])?trim($_POST['streamid']):"";
        $academicyearid = isset($_POST['academicyearid'])?trim($_POST['academicyearid']):"";
        $section = isset($_POST['section'])?trim($_POST['section']):"";
        $sendmail = isset($_POST['sendmail'])?trim($_POST['sendmail']):"";
        $type_ids=implode(",", $feeTypes);
        foreach ($feeTypes as $type_id) {
            # code...
            $type_id=trim($type_id);
            $ptype="ptype_".$type_id;
            $cbank="cbank_".$type_id;
            $bank="bank_".$type_id;
            $paymentmode="paymentmode_".$type_id;
            $paymentmodetrans="paymentmodetrans_".$type_id;
            $amount="amount_".$type_id;
            $paiddate="paiddate_".$type_id;
            $remarks="remarks_".$type_id;
            //$sendmail="sendmail_".$type_id;
            $fullwaived="fullwaived_".$type_id;
            $feegroup_id="feegroup_".$type_id;

            $applicable_id="applicable_".$type_id;
            $eventname_id="eventname_".$type_id;
            $qty="qty_".$type_id;


            $transStatus = 'Ok';

            $ptype_val=isset($_POST[$ptype])?trim($_POST[$ptype]):"";
            $transDate=isset($_POST[$paiddate])?trim($_POST[$paiddate]):"";
            $remarks_val=isset($_POST[$remarks])?trim($_POST[$remarks]):"";

            $fullwaived_val=isset($_POST[$fullwaived])?trim($_POST[$fullwaived]):"";
            //$sendmail_val=isset($_POST[$sendmail])?trim($_POST[$sendmail]):"";
            $feegroup_val=isset($_POST[$feegroup_id])?trim($_POST[$feegroup_id]):"";

            $amount_val=isset($_POST[$amount])?trim($_POST[$amount]):"";

            $applicable=isset($_POST[$applicable_id])?trim($_POST[$applicable_id]):"";
            $eventname=isset($_POST[$eventname_id])?trim($_POST[$eventname_id]):"";
            $qty_val=isset($_POST[$qty])?trim($_POST[$qty]):"";

            $arrsdfd=explode("-",$eventname);
           $feetyp=$arrsdfd[1];
           $feeconfigid=$arrsdfd[2];

            if(!empty($qty_val)){
                $eventname=$eventname."-".$qty_val;

            }
            
            if (strpos($eventname, 'UNIFORM') !== false) {
                $otherfeestype=1;
                //$paymenttablecheck = sqlgetresult('SELECT COUNT(*) as total FROM tbl_payments WHERE "challanNo" = \''.$eventname.'\' AND "transStatus" = \'Ok\'',true);
                //$num=$paymenttablecheck[0]['total'];
                //$serial=$num+1;
               // $eventname=$eventname."-".$serial;
                $suffix="UNI-";
            }

            if (strpos($eventname, 'COMMON') !== false) {
                $otherfeestype=4;
                //$suffix="COM-";
                $suffix="COM-".$feetyp."-";
            }

            if (strpos($eventname, 'LUNCH') !== false) {
                $otherfeestype=2;
                $suffix="LUN-";
            }

            if($ptype_val == "Online"){
                $bank_val = isset($_POST[$bank])?trim($_POST[$bank]):"Atom";
                $transNum = isset($_POST[$paymentmodetrans])?trim($_POST[$paymentmodetrans]):"";
            }
            else{
                $bank_val = isset($_POST[$cbank])?trim($_POST[$cbank]):"";
                $transNum = isset($_POST[$paymentmode])?trim($_POST[$paymentmode]):"";
            }

            $feegroupradio = $feegroup_val;
           $remarks=substr($remarks_val, 0, 30);

           $returnCode=$ptype_val."_".$bank_val."_".$transNum."_".$amount_val."_".$remarks_val."_".$transDate;

           

          // echo "SELECT * FROM createotherfeestransaction('$s_uid','$uid','$amount_val','$otherfeestype','$feeconfigid','$feetyp','$qty_val','$eventname','$classid','$academicyearid','$streamid','$term','$section','$ptype_val')";
           //exit;
           if($qty_val){
              $run1=sqlgetresult("SELECT * FROM createotherfeestransaction('$s_uid','$uid','$amount_val','$otherfeestype','$feeconfigid','$feetyp','$qty_val','$eventname','$classid','$academicyearid','$streamid','$term','$section','$ptype_val')",true);
           }else{
             $run1=sqlgetresult("SELECT * FROM createotherfeestransaction('$s_uid','$uid','$amount_val','$otherfeestype','$feeconfigid','$feetyp',NULL,'$eventname','$classid','$academicyearid','$streamid','$term','$section','$ptype_val')",true);
           }
           $lastinsert_id = isset($run1[0]['createotherfeestransaction'])?$run1[0]['createotherfeestransaction']:"";
           if($lastinsert_id){

                $refnum=$suffix.$lastinsert_id;
                //date_default_timezone_set('Asia/Calcutta');
                $createdOn = date("Y-m-d");
                //$createdOn = str_replace(" ", "%20", $datenow);
                $payment_id = sqlgetresult('INSERT INTO tbl_payments ("parentId","studentId","challanNo","amount","transStatus","transNum", "transId", "remarks", "returnCode","transDate", "createdby", "createdOn") VALUES (\''.$pid.'\',\''.$student_id.'\',\''.$eventname.'\',\''.$amount_val.'\',\''.$transStatus.'\',\''.$refnum.'\',\''.$transNum.'\',\''.$remarks.'\',\''.$returnCode.'\',\''.$transDate.'\',\''.$uid.'\',\''.$createdOn.'\') RETURNING id');
                $payment_insert_id = isset($payment_id['id'])?$payment_id['id']:0;
                /*$receiptupd = updatereceipt_by_feetype($cnum,$sid,$feegroupradio,$type_id);
                $fromwhere = 'Receipt';
                flattableentry_feetype($cnum, $sid, $fromwhere);*/
                sqlgetresult('UPDATE tbl_otherfees_payment_log SET "refNum" = \''.$refnum.'\', status=1 WHERE "id" = \''.$lastinsert_id.'\'');
           }

        }
        
        if($payment_insert_id > 0){

            //if($sendmail == 'on'){
                if (strpos($eventname, 'UNIFORM') !== false) {
                    $sfsfeeName=getFeeTypebyId($type_id);
                    $sfsutilitiesinputqty=$qty_val;
                    $singleqtyamount=($amount_val/$sfsutilitiesinputqty);

                    $sfsqty = sqlgetresult("SELECT * FROM sfstableentry('".$eventname."','". trim($sfsfeeName) ."','".$singleqtyamount."', '". trim($sfsutilitiesinputqty) ."', '". $amount_val ."','".$uid."','". $student_id ."')");
                    createUFPDF($payment_insert_id,$student_id);
                }
                if (strpos($eventname, 'COMMON') !== false) {
                 createCOMFPDF($payment_insert_id,$student_id);
                }
                if (strpos($eventname, 'LUNCH') !== false) {
                 createLFPDF($payment_insert_id,$student_id);
                }
            //}
            $_SESSION['successcheque'] = "<p class='success-msg'>Payment Updated Successfully.</p>";
            header("Location: addpayment.php");
            //exit;
        }
        else{
            $_SESSION['errorcheque'] = "<p class='error-msg'>Payment was not updated in Receipt table.</p>";
            header("Location: addpayment.php");  
        }

    }else{
       $_SESSION['errorcheque'] = "<p class='error-msg'>Please choose the feetype</p>";
       header("Location: addpayment.php"); 
    }
}
if (isset($_POST['edit_fee_other_pay']) && $_POST['edit_fee_other_pay'] == 'confirm'){
    
    $student_id = isset($_POST['sid'])?trim($_POST['sid']):"";
    $pid = isset($_POST['pid'])?trim($_POST['pid']):"";
    $payid = isset($_POST['payid'])?trim($_POST['payid']):"";
    $id = isset($_POST['id'])?trim($_POST['id']):"";
    $ptype_val = isset($_POST['ptype'])?trim($_POST['ptype']):"";
    $cbank = isset($_POST['cbank'])?trim($_POST['cbank']):"";
    $bank = isset($_POST['bank'])?trim($_POST['bank']):"Atom";
    $transNum = isset($_POST['paymentmodetrans'])?trim($_POST['paymentmodetrans']):"";
    $amount_val = isset($_POST['amount'])?trim($_POST['amount']):0;
    $transDate = isset($_POST['paiddate'])?trim($_POST['paiddate']):"";
    $remarks_val = isset($_POST['remarks'])?trim($_POST['remarks']):"";
    $status = isset($_POST['status'])?trim($_POST['status']):"";
    $eventname = isset($_POST['status'])?trim($_POST['status']):"";
    $remarksfull = $remarks_val;

    if(!empty($remarks_val)){
        $remarks_val=substr($remarks_val, 0, 30);
    }

    if($ptype_val == "Online"){
        $bank_val = $bank;
    }
    else{
        $bank_val = $cbank;
    }

    $returnCode=$ptype_val."_".$bank_val."_".$transNum."_".$amount_val."_".$remarks_val."_".$transDate;
    date_default_timezone_set("Asia/Kolkata");    
    $cur_data = time();
    $date = date('Y-m-d h:i:s');

    $uid = $_SESSION['myadmin']['adminid'];


    $query = "SELECT * FROM editotherpayment('$payid','$transNum','$amount_val','$status','$remarks_val','$transDate','$uid')";
    $run = sqlgetresult($query);
    
    if($run['editotherpayment'] == 1 ){
        if($status == 'Ok'){
            sqlgetresult('UPDATE tbl_otherfees_payment_log SET status = \'1\', remarks = \''.$remarksfull.'\' WHERE "id" = \''.$id.'\'');
            if (strpos($eventname, 'UNIFORM') !== false) {
                $sfsfeeName=getFeeTypebyId($type_id);
                $sfsutilitiesinputqty=$qty_val;
                $singleqtyamount=($amount_val/$sfsutilitiesinputqty);

                $sfsqty = sqlgetresult("SELECT * FROM sfstableentry('".$eventname."','". trim($sfsfeeName) ."','".$singleqtyamount."', '". trim($sfsutilitiesinputqty) ."', '". $amount_val ."','".$uid."','". $student_id ."')");
                createUFPDF($id,$student_id);
            }
            if (strpos($eventname, 'LUNCH') !== false) {
             createLFPDF($id,$student_id);
            }
            if (strpos($eventname, 'COMMON') !== false) {
                createCOMFPDF($id,$student_id);
            }
               
        }else{
            sqlgetresult('UPDATE tbl_otherfees_payment_log SET status = \'2\', remarks = \''.$remarksfull.'\' WHERE "id" = \''.$id.'\'');
        }
        $_SESSION['successclass'] = "<p class='success-msg'>Payment Updated Successfully.</p>";
        header("Location: manageotherfees.php");
        //exit;
    }
    else if ($run['editotherpayment'] == 0){
        $_SESSION['errorcheque'] = "<p class='error-msg'>Given transaction number Already Exist</p>";
        header('location:editotherfee.php?id='.$id);
    }
    else{
        createErrorlog($run);
        $_SESSION['errorcheque'] = "<p class='error-msg'>Payment was not updated in Payment table.</p>";
        header("Location: editotherfee.php?id=".$id);  
    }
   
}
//***********Chequedd Feetye section - End***********//

if(isset($_GET['otherfeeexport']) && $_GET['otherfeeexport']=='exportexcel'){
    $result_data=[];
    $sql = $_SESSION['otherfeereportquery'];
    $sqlrun = sqlgetresult($sql,true);
    foreach($sqlrun AS $key => $output){
        $id=trim($output['id']);
        //$result_data[$key]['Ref Number']=$output['transNum'];
        $result_data[$id]['Student Id']=trim($output['studentId']);
        $result_data[$id]['Student Name']=trim($output['studentName']);
        $result_data[$id]['Stream'] = trim($output['streamname']);
        $result_data[$id]['Semester'] = trim($output['term']);
        $result_data[$id]['Class'] = trim($output['class_list']);
        $result_data[$id]['Section'] = $output['section'];
        //$result_data[$key]['Challan No']=trim($output['challanNo']);
        $result_data[$id]['Academic Year'] = trim($output['academic_yr']);

        $result_data[$id]['Fee Type'] = trim($output['feetypename']);
        /*if($output['typeid'] == 1){
           $result_data[$key]['Quantity'] = trim($output['quantity']);
        }*/
        $result_data[$id]['Quantity'] = trim($output['quantity']);
        //$result_data[$key]['Semester'] = $output['term'];
        $result_data[$id]['Transaction Date']=date("d-m-Y", strtotime($output['transDate']));
        $result_data[$id]['Ref Number']=trim($output['transNum']);
        $result_data[$id]['Transaction Id']=trim($output['transId']);
        $result_data[$id]['Total']=$output['amount'];
        
    }

    $sqlold = $_SESSION['otherfeereportqueryold'];
    $sqlrunold = sqlgetresult($sqlold,true);
    foreach($sqlrunold AS $keyold => $outputold){
        $result_data[$keyold]['Student Id']=trim($outputold['studentId']);
        $result_data[$keyold]['Student Name']=trim($outputold['studentName']);
        $result_data[$keyold]['Stream'] = trim($outputold['streamname']);
        $result_data[$keyold]['Semester'] = trim($outputold['term']);
        $result_data[$keyold]['Class'] = trim($outputold['class_list']);
        $result_data[$keyold]['Section'] = $outputold['section'];
        $result_data[$keyold]['Academic Year'] = trim($outputold['academic_yr']);

        $result_data[$keyold]['Fee Type'] = "";
        $result_data[$keyold]['Quantity'] = "";
        $result_data[$keyold]['Transaction Date']=date("d-m-Y", strtotime($outputold['transDate']));
        $result_data[$keyold]['Ref Number']=trim($outputold['transNum']);
        $result_data[$keyold]['Transaction Id']=trim($outputold['transId']);
        $result_data[$keyold]['Total']=$outputold['amount'];
        
    }

    /*echo "<pre>";
    print_r($result_data);
    exit;*/

    foreach ($result_data as $k => $v) {
        $keys = array();
        foreach ($v as $field_code => $field_val)
        {       
                $keys[]=$field_code;                
        }       
        $columns = $keys;
    }
    exportData($result_data, 'Other Fee Report', $columns);
}


/********Full Payment (Summation of challans)******/
if(isset($_POST['paytotal_adm']) && $_POST['paytotal_adm']=='partial'){
   
   date_default_timezone_set('Asia/Calcutta');
   $createdOn = date("Y-m-d h:m:s");
   $uid = $_SESSION['myadmin']['adminid'];
   $studentId = isset($_POST['studentId'])?trim($_POST['studentId']):"";
   /* Student Primary ID */
   $parent_id = isset($_POST['pid'])?trim($_POST['pid']):"";
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
   $ptypechk = isset($_POST['ptypechk'])?trim($_POST['ptypechk']):"";
   $actualAmt = $amount;
   $cautionamt = 0;

   $caution_deposit_json = isset($_POST['caution_deposit'])?$_POST['caution_deposit']:"";
   if($payop=='caution'){
     $caution_deposit=($caution_deposit_json)?"'".$caution_deposit_json."'":"NULL";
     $cautionamt = isset($_POST['cautionamt'])?trim($_POST['cautionamt']):0;
   }else{
     $caution_deposit="NULL";
   }

   if($ptypechk=='Online'){
     $cbank="Atom";
   }else{
     $cbank = isset($_POST['cbank'])?trim($_POST['cbank']):"";
   }
   $auth_code = isset($_POST['paymentmodetrans'])?trim($_POST['paymentmodetrans']):"";
   $transactiondate = isset($_POST['paiddate'])?trim($_POST['paiddate']):"";
   if(!empty($transactiondate)){
     $transactiondate=date("Y-m-d", strtotime($transactiondate));
   }
   $remarks = isset($_POST['remarks'])?trim($_POST['remarks']):"";

   $remarksadd=$ptypechk."/".$cbank."/".$auth_code."-".$remarks;


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
       

       $accdatails=array('SCHOOL FEE'=>array(1=>'1244172000004389', 2=>'1244172000004365', 3=>'1244172000004389', 4=>'1244172000004365', 6=>'1244172000114886', 5=>'1244172000004389'));

       //$parentData = sqlgetresult('SELECT * FROM getparentdata WHERE "studentId" = \''.$studentId.'\' LIMIT 1',true);
       $parentData = sqlgetresult('SELECT * FROM getparentdata WHERE sid = \''.$s_id.'\' LIMIT 1',true);
       // $parentData[0]['challanNo'] = trim($eventname);
        //$_SESSION['PSLFData'] = $parentData[0];
        //$amount = $amount;   
        $cusName = trim($parentData[0]['userName']);
        $cusEmail = trim($parentData[0]['email']);
        $cusMobile = trim($parentData[0]['mobileNumber']); 
        $parent_id = $parentData[0]['id'];
        $academicYear = trim($parentData[0]['academic_yr']);
        $term = trim($parentData[0]['term']);
        $stream = trim($parentData[0]['stream']); 
        $sid = $parentData[0]['sid'];
        $class = trim($parentData[0]['class']);
        $section = trim($parentData[0]['section']);  

        $f_code = 'Ok';

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
        
        $productData = sqlgetresult("SELECT * FROM tbl_accounts WHERE id = '$acc_id' ");
        //print_r($productData);
        $minus=0;
        /*SCHOOL FEE*/
        if(($payop=='full' || $payop=='caution') && $partialpaidamt > 0 && $balance==0){
            $i = 1;
            $product = ('<products>');
            foreach ($paygroup as $key1 => $value) {
                $key1=trim($key1);
                $key=getFeeGroupbyId($key1);
                $key = trim($key);
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
                            if($value >$cautionamt){
                                $minus=1;
                            }
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
                        if($minus==1){
                            $value = $value-$cautionamt;
                        }
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
            } else {
            /*$stream == '6'*/
            $pname = $productData['1244172000114886'];
            }

            $product = ('<products><product><id>1</id><name>'.trim($pname).'</name><amount>'.$amount.'</amount></product></products>');
        } 

        $run1=sqlgetresult("SELECT * FROM createpartialtransactionadmin('$s_id','$challanids','$jsonsfs_amt','$jsonschool_amt','$grand_tot','$feeTotal','$balance','$waived_tot','$uid','$product','$amount','$minamt','$payop','$ptypechk')",true);
        //print_r($run1);
        
        $lastinsert_id = isset($run1[0]['createpartialtransactionadmin'])?$run1[0]['createpartialtransactionadmin']:""; 
        //exit;
        if(!empty($lastinsert_id)){
            $eventname="REF".$lastinsert_id;
            $parentData[0]['challanNo'] = $eventname;
            //$transactionId = rand(1, 1000000);
            $transactionId = $eventname;

            if($amount == 0){

                //if($grand_tot == $amount){
                    sqlgetresult('UPDATE tbl_partial_payment_log SET "transNum"=\''.$transactionId.'\',"payment_url"=\'Debited From wallet\', "remarks"=\''.$remarksadd.'\',  "transStatus"=\'Ok\', "transId"=\''.$auth_code.'\', "transDate"=\''.$transactiondate.'\',"studentId" = \''.$studentId.'\' WHERE id=\''.$lastinsert_id.'\'');
                    //$_SESSION['partial_payment_id'] = $lastinsert_id;

                    /* caution deposit partial */
                    if(!empty($caution_deposit_json) && $payop=='caution'){
                        $chkcaution = toUpdateFeeTypePaidAmt($caution_deposit_json);
                    }

                    //$balance=toGetAvailableBalance($sid);
                    $tot=$balance-$amount;
                    $wallet = sqlgetresult("SELECT * FROM addAdvanceAmt('".$sid."','$updatebal','".$uid."')");
                    $resadv=$wallet['addadvanceamt']?$wallet['addadvanceamt']:"";
                    if(!empty($resadv)){
                        $run1=sqlgetresult("SELECT * FROM createadvancetransaction('$s_id','$uid','$product','$debitedAmt','$balance','2','Ok','$lastinsert_id','$eventname','1','$class','$academicYear','$stream','$term','$section')",true);
                    }

                    //$rstAmt=0;
                    partialEwalletPayProcessOffline($challanids, $sid, $uid, $studentId, $term, $academicYear, $actualAmt, $lastinsert_id, $createdOn, $amount, $ptypechk, $cbank, $auth_code, $transactiondate, $remarks);
                    if(!empty($caution_deposit_json) && $payop=='caution'){
                        toCheckFeeTypePartial($caution_deposit_json);
                    }
                    $_SESSION['success_msg'] = "<p class='success-msg'>Payment Completed Successfully.</p>";
                //}
            }
            else{
                $returncode="Added By Admin From Backend";
                /*echo "SELECT * FROM partialtransactionentryadmin('$amount','$f_code','".$returncode."','".$remarksadd."','".$auth_code."','$transactiondate','".$parent_id."','".$lastinsert_id."','".$transactionId."') ";*/
                //echo "SELECT * FROM partialtransactionentryadmin('$amount','$f_code','".$returncode."','".$remarksadd."','".$auth_code."','$transactiondate','".$parent_id."','".$lastinsert_id."','".$transactionId."') ";
               // exit;

                if(strlen($auth_code) > 20){
                    $auth_code=substr($auth_code, 0, 20);
                }
                $stud_id=($studentId)?"'".$studentId."'":"NULL";
                $paymentData = sqlgetresult("SELECT * FROM partialtransactionentryadmin('$amount','$f_code','".$returncode."','".$remarksadd."','".$auth_code."','$transactiondate','".$parent_id."','".$lastinsert_id."','".$transactionId."', $caution_deposit, $stud_id) ",true);
                $payment_lastinsert_id = isset($paymentData[0]['partialtransactionentryadmin'])?$paymentData[0]['partialtransactionentryadmin']:"";
                if($f_code == 'Ok' && $payment_lastinsert_id) {
                    /* caution deposit partial */
                    if(!empty($caution_deposit_json) && $payop=='caution'){
                        $chkcaution = toUpdateFeeTypePaidAmt($caution_deposit_json);
                    }
                    /* Update Balance Start */
                    if(isset($balance) && !empty($balance)){
                        $curbalance=toGetAvailableBalance($sid);
                        $upewal=$curbalance-$balance;
                        $wallet = sqlgetresult("SELECT * FROM addadvanceamt('".$sid."','$upewal','".$parent_id."')");

                        $resadv=$wallet['addadvanceamt']?$wallet['addadvanceamt']:"";
                        if(!empty($resadv)){
                            $run1=sqlgetresult("SELECT * FROM createadvancetransaction('$sid','$parent_id',NULL,'$balance','$curbalance','2','Ok','$lastinsert_id','$eventname','1','$class','$academicYear','$stream','$term','$section')",true);
                        }
                        $amount=$amount+$balance;
                    }
                    $receivedAmt=$amount+$balance;
                    partialPayProcessOffline($challanids, $sid, $parent_id, $studentId, $term, $academicYear, $amount, $lastinsert_id, $transactiondate, $balance, $ptypechk, $cbank, $auth_code, $transactiondate, $remarks);
                    /* caution deposit partial */
                    if(!empty($caution_deposit_json) && $payop=='caution'){
                       toCheckFeeTypePartial($caution_deposit_json);
                    }
                    $_SESSION['success_msg'] = "<p class='success-msg'>Payment Completed Successfully.</p>";
                } else {
                    $_SESSION['error_msg'] = "<p class='error-msg'>Some Error Has Occurred. Please try again later.</p>";
                }
            }
        }else{
            $_SESSION['error_msg'] = "<p class='error-msg'> Some Error has occured. Try again later.</p>";
            $errordata = 'Partial Amount of Challans:</br>studentId:' . $studentId . '</br>amount:' . $amount . '</br>' . $_SESSION['error'];
            createErrorlog($errordata);
        }
    }else{
        $_SESSION['error_msg'] = "<p class='error-msg'> Amount should be greater than or equal to minimum due.</p>";
   }
   header("Location: addpartialpayment.php");
   exit;
   
}

/********Full Payment (Summation of challans)******/
if(isset($_POST['paytotaledit_adm']) && $_POST['paytotaledit_adm']=='paytotaledit'){
   date_default_timezone_set('Asia/Calcutta');
   $createdOn = date("Y-m-d h:m:s");
   $uid = $_SESSION['myadmin']['adminid'];
   /* Student Primary ID */
   $plogid = isset($_POST['plogid'])?trim($_POST['plogid']):"";
   $parent_id = isset($_POST['pid'])?trim($_POST['pid']):"";
   $ptypechk = isset($_POST['ptypechk'])?trim($_POST['ptypechk']):"";
   $cbank=isset($_POST['bank'])?trim($_POST['bank']):"atom";
   $f_code = isset($_POST['tstatus'])?trim($_POST['tstatus']):"";
   $auth_code = isset($_POST['paymentmodetrans'])?trim($_POST['paymentmodetrans']):"";
   $transactiondate = isset($_POST['paiddate'])?trim($_POST['paiddate']):"";
   if(!empty($transactiondate)){
     $transactiondate=date("Y-m-d", strtotime($transactiondate));
   }
   $remarks = isset($_POST['remarks'])?trim($_POST['remarks']):"";
   $remarksadd=$ptypechk."/".$cbank."/".$auth_code."-".$remarks;
   $data = sqlgetresult('SELECT pl.*,s."studentId",s."studentName",s.stream, s.class, s.term, s.academic_yr FROM tbl_partial_payment_log pl JOIN tbl_student s ON(pl.sid=s.id) WHERE pl.id=\''.$plogid.'\' AND pl.paymentmode=\'Online\' AND (pl."transStatus" IS NULL OR pl."transStatus"=\'F\' OR pl."transStatus"=\'C\') AND pl.deleted=0  AND s.deleted=0',true);
   if(count($data) > 0){
        $academicYear = trim($data[0]['academic_yr']);
        $term = trim($data[0]['term']);
        $stream = trim($data[0]['stream']); 
        $sid = $data[0]['sid'];
        $class = trim($data[0]['class']);
        $section = trim($data[0]['section']);  
        $amount = trim($data[0]['receivedamount']);
        $balance = isset($data[0]['balance'])?trim($data[0]['balance']):0;
        $student_id = trim($data[0]['studentId']);
        $challanids = trim($data[0]['challanids']);
        $lastinsert_id = trim($data[0]['id']);

        $returncode="Added By Admin From Backend";

        $caution_json = ($data[0]['partial_caution_deposit'])?trim($data[0]['partial_caution_deposit']):"";
        $payoption = ($data[0]['payoption'])?trim($data[0]['payoption']):"";

        /*echo "SELECT * FROM partialtransactionentry('$amount','$f_code','".$returncode."','".$remarksadd."','".$auth_code."','$transactiondate','".$parent_id."','".$lastinsert_id."') ";
        exit;*/

        $paymentData = sqlgetresult("SELECT * FROM partialtransactionentry('$amount','$f_code','".$returncode."','".$remarksadd."','".$auth_code."','$transactiondate','".$parent_id."','".$lastinsert_id."') ");
        if($f_code == 'Ok') {
        $eventname="REF".$lastinsert_id;
        /* caution deposit partial */
        if(!empty($caution_json) && $payoption=='caution'){
            $chkcaution = toUpdateFeeTypePaidAmt($caution_json);
        }
        /* Update Balance Start */
        if(isset($balance) && $balance > 0){
            $curbalance=toGetAvailableBalance($sid);
            $upewal=$curbalance-$balance;
            $wallet = sqlgetresult("SELECT * FROM addAdvanceAmt('".$sid."','$upewal','".$parent_id."')");

            $resadv=$wallet['addadvanceamt']?$wallet['addadvanceamt']:"";
            if(!empty($resadv)){
                $run1=sqlgetresult("SELECT * FROM createadvancetransaction('$sid','$parent_id','','$balance','$curbalance','2','Ok','$lastinsert_id','$eventname','1','$class','$academicYear','$stream','$term','$section')",true);
            }
            $amount=$amount+$balance;
        }
        $receivedAmt=$amount+$balance;
        partialPayProcessOffline($challanids, $sid, $parent_id, $student_id, $term, $academicYear, $amount, $lastinsert_id, $transactiondate, $balance, $ptypechk, $cbank, $auth_code, $transactiondate, $remarks);
        if(!empty($caution_json) && $payoption=='caution'){
            toCheckFeeTypePartial($caution_json);
        }
        $_SESSION['success_msg'] = "<p class='success-msg'>Payment Completed Successfully.</p>";
        header("Location: managepartialpayment.php");
        } else {
            $_SESSION['error_msg'] = "<p class='error-msg'>Some Error Has Occurred. Please try again later.</p>";
            header("Location: editpartialpayment.php?logid=".$plogid);
        }

   }else{
        $_SESSION['error_msg'] = "<p class='error-msg'>No data Found.</p>";
        header("Location: editpartialpayment.php?logid=".$plogid);
   }
   
   exit;
}

if (isset($_POST['filter']) && $_POST['filter'] == "fltpartialreport")
{
    date_default_timezone_set('Asia/Calcutta');
    $classselect = isset($_POST['classselect'])?trim($_POST['classselect']):"";
    $streamselect = isset($_POST['streamselect'])?trim($_POST['streamselect']):"";
    $semesterselect = isset($_POST['semesterselect'])?trim($_POST['semesterselect']):"";
    $academicyr = isset($_POST['yearselect'])?trim($_POST['yearselect']):"";
    $from = isset($_POST['from'])?trim($_POST['from']):"";
    $to = isset($_POST['to'])?trim($_POST['to']):"";
    $stid = isset($_POST['stid'])?trim($_POST['stid']):"";
    $txtref = isset($_POST['txtref'])?trim($_POST['txtref']):"";
    $paymethod = isset($_POST['paymethod'])?$_POST['paymethod']:"";

    $where=[];
    $where[] = '(pp."transStatus" IS NULL OR pp."transStatus"=\'F\' OR pp."transStatus"=\'C\') AND (pp.paymentmode = \'Online\' AND pp.deleted=0) ';
    if (!empty($stid))
    {
        $where[] = 's."studentId"=\'' . $stid . '\' ';

    }
    if (!empty($txtref))
    {
        $txtref=str_ireplace("REF","",$txtref);
        $where[] = 'pp.id=\'' . $txtref . '\' ';
    }
    if (!empty($classselect))
    {
        $where[] = 's."classList"=\'' . $classselect . '\' ';

    }
    if (!empty($streamselect))
    {
        $where[] = 's."stream"=\'' . $streamselect . '\' ';

    }
    if (!empty($semesterselect))
    {
        $where[] = 's.term=\'' . $semesterselect . '\' ';

    }
    if (!empty($academicyr))
    {
        $where[] = 's."academicYear"=\'' . $academicyr . '\' ';

    }

    if (!empty($from) && !empty($to))
    {
        
        $where[] = 'DATE(pp."createdOn") BETWEEN \'' . $from . '\'  AND  \'' . $to . '\'';
        

    }

    if (!empty($paymethod)) {
        $where[] ="pp.paymentmethod ILIKE '%".pg_escape_string ($paymethod)."%'"; 
    }
    
    $wherecond="";

    if(count($where) > 0){
        $wherecond="WHERE  ".implode(" AND ", $where);
    }
    
    //$sql = 'SELECT p.*,s."studentId",s."studentName",st.stream, c.class_list,ay.year as academic_yr FROM tbl_advance_payment_log as p JOIN  tbl_student s ON p.sId::int = s.id   JOIN tbl_stream st ON a.stream::int = st.id LEFT JOIN tbl_class c ON c.id = s.class::int LEFT JOIN tbl_academic_year ay ON (ay.id = p."academicYear"::integer) '.$wherecond.'  ORDER BY p.id DESC';

    $sql = 'SELECT pp.id,pp."transNum",pp."challanids",pp."payoption",pp."receivedamount",DATE(pp."createdOn") AS cdate,s."studentId",s."studentName",st.stream, c.class_list,ay.year as academic_yr, s.term,pp.paymentmethod FROM tbl_partial_payment_log pp JOIN tbl_student s ON pp.sid::int = s.id JOIN tbl_stream st ON s.stream::int = st.id LEFT JOIN tbl_class c ON c.id = s.class::int LEFT JOIN tbl_academic_year ay ON (ay.id = s."academic_yr"::integer) '.$wherecond.'  ORDER BY id DESC';

    $_SESSION['partialreportquery']=$sql; 
    $res = sqlgetresult($sql, true);

    echo json_encode($res);

}

if(isset($_GET['partialreport']) && $_GET['partialreport']=='exportexcel'){
       $sql = $_SESSION['partialreportquery'];
       $sqlrun = sqlgetresult($sql,true);
       foreach($sqlrun AS $key => $output){
            $result_data[$key]['Ref Number']=$output['transNum'];
            $result_data[$key]['Student Id']=$output['studentId'];
            $result_data[$key]['Student Name']=$output['studentName'];
            $result_data[$key]['Stream']=$output['stream'];
            $result_data[$key]['Challan Number']=$output['challanids'];
            $result_data[$key]['Pay Option']=$output['payoption'];
            $result_data[$key]['Amount']=$output['receivedamount'];
            $result_data[$key]['Created Date']=$output['cdate'];
        }
        foreach ($result_data as $k => $v) {
        $keys = array();
        foreach ($v as $field_code => $field_val)
        {       
                $keys[]=$field_code;                
        }       
        $columns = $keys;
    }
    exportData($result_data, 'Partial Payment Details', $columns);
}

/* Create fee group challan */
if (isset($_POST['showtransfeechallan']) && $_POST['showtransfeechallan'] == "showtransfeechallan") {

    unset($_SESSION['createdchallanids']);
    $class = isset($_POST['class_list'])?trim($_POST['class_list']):"";
    $term = isset($_POST['semester'])?trim($_POST['semester']):"";
    $feetypes = isset($_POST['selected_feetypes'])?trim($_POST['selected_feetypes']):"";
    $createdby = isset($_SESSION['myadmin']['adminid'])?trim($_SESSION['myadmin']['adminid']):"";
    $stream = isset($_POST['stream'])?trim($_POST['stream']):"";
    $remarks = isset($_POST['remarks'])?trim($_POST['remarks']):"";
    $duedate = isset($_POST['duedate'])?trim($_POST['duedate']):"";
    //$academic = 7;
    $streamName = getStreambyId($stream);    
    $id = isset($_POST['studentId'])?trim($_POST['studentId']):"";
    $studentId=$id;
    $name = isset($_POST['studentName'])?trim($_POST['studentName']):"";
    $selectedData = array();
    $feeData = explode(',', $feetypes);
    $challanSuffix="TF-";

    /* Active Academic Year*/
    $academicId = isset($_POST['academicId'])?trim($_POST['academicId']):"";
    $type = isset($_POST['type'])?trim($_POST['type']):"";
    //$feegrp = getFeeGroupbyName('NON-FEE');
    $streamName = getStreambyId($stream);
    /* Current Semester */
    $cur_term=getCurrentTerm();

    if($type == 'single'){
       $groupdata=array();
       $feetypedata = sqlgetresult('SELECT * FROM getfeetypedata WHERE class=\'' . $class . '\' AND semester=\'' . $cur_term . '\' AND stream = \'' . ($stream) . '\' AND "academicYear" = \'' . ($academicId) . '\' ', true);    
        foreach ($feeData as $k => $v)
        {
            $v=trim($v);
            foreach ($feetypedata as $val)
            {
                $gid=trim($val['id']);
                if ( $v == $gid)
                {                
                    $gfeeGroup=trim($val['feeGroup']);
                    $group = getFeeGroupbyId($gfeeGroup);
                    $groupdata[$gfeeGroup][$gid][] = trim($val['amount']);
                    $groupdata[$gfeeGroup][$gid][] = trim($val['feename']);
                    $groupdata[$gfeeGroup][$gid][] = trim($gfeeGroup);
                    $groupdata[$gfeeGroup][$gid][] = trim($group);
                }
            }
        }
    
        if (count($groupdata) > 0)
        {
            /* Call Generate Challan Sequnce Number */
            $challanNo=$challanSuffix.toGenerateChallanSequnceNumber($streamName);
            $exists_fee_type=array();
            $challanData="";
            $feedata=array();
            foreach ($groupdata as $grp => $data)
            {     
                foreach ($data as $k => $val)
                {       
                    $amt=$val[0];
                    $ftype=$val[2];
                    $fname=$val[1];
                    $sql = "SELECT * FROM createtempchallantransportfee('$challanNo','$studentId','$createdby','".$k."','$stream','$class','$cur_term','$name','".$amt."','$remarks','$duedate','".$ftype."','$academicId')";    
                    //echo $sql;
                    //exit;
                    $result = sqlgetresult($sql);  
                   if ($result['createtempchallantransportfee'] > 0) {
                        $challanData = sqlgetresult('SELECT * FROM tempChallan WHERE "challanNo"=\'' . $challanNo . '\' ORDER BY id ASC LIMIT 1');
                        $group_name=$val[3];
                        $group_id=$val[2];

                        $feedata[$group_name][$group_id][$k][] = $amt;
                        $feedata[$group_name][$group_id][$k][] = $fname;

                       // sendNotificationToParents($studentId, $_POST['mail_content'],$_POST['sms_content'],  "additionalfeechallan");
                    }else{
                        $exists_fee_type[$k][] = $val[1];
                    }
                }            
            }
            $selectedData['feeData'] = $feedata;
            $selectedData['is_exists']=count($exists_fee_type);
            $selectedData['exists']=$exists_fee_type;
            $selectedData['challanData'] = $challanData;
        } else {
            $selectedData = 'Fee Types empty';
        }
    }else{
        if(isset($_SESSION['selectedtransfeechallans']) && count($_SESSION['selectedtransfeechallans']) > 0 ) {
            $selectedIds  = $_SESSION['selectedtransfeechallans'];
            $createdChallans=[];
            foreach ($selectedIds as $k => $id) {
                $groupdata = array();
                //echo 'SELECT class, term, stream, "academic_yr" AS "academicYear","studentId" FROM tbl_student WHERE "studentId" = \''.trim($id).'\' LIMIT 1 ';
                $studentData = sqlgetresult('SELECT class, term, stream, "academic_yr" AS "academicYear","studentId","studentName" FROM tbl_student WHERE "studentId" = \''.trim($id).'\' LIMIT 1 ');
                $studentData = array_map('trim', $studentData);
                $class = $studentData['class'];
                $term = $studentData['term'];
                $stream = $studentData['stream'];
                //$academicId = $studentData['academicYear'];
                $studentId = $studentData['studentId'];
                $name = $studentData['studentName'];
                $streamName = getStreambyId($stream); 
                // print_r($studentData);
                $feetypedata = sqlgetresult('SELECT * FROM getfeetypedata WHERE class=\''.$class.'\' AND semester=\''.$cur_term.'\' AND stream = \''.$stream.'\' AND "academicYear" = \''.$academicId.'\' ', true); 
               if(count($feetypedata) > 0)
                {
                   foreach ($feeData as $k => $v)
                   {
                    $v=trim($v);
                    foreach ($feetypedata as $val)
                    {
                        $gid=trim($val['id']);
                        if ( $v == $gid)
                        {                
                            $gfeeGroup=trim($val['feeGroup']);
                            $group = getFeeGroupbyId($gfeeGroup);
                            $groupdata[$gfeeGroup][$gid][] = trim($val['amount']);
                            $groupdata[$gfeeGroup][$gid][] = trim($val['feename']);
                            $groupdata[$gfeeGroup][$gid][] = trim($gfeeGroup);
                            $groupdata[$gfeeGroup][$gid][] = trim($group);
                        }
                    }
                   }
               }

                if (count($groupdata) > 0)
                {
                    /* Call Generate Challan Sequnce Number */
                    $challanNo=$challanSuffix.toGenerateChallanSequnceNumber($streamName);
                    $exists_fee_type=array();
                    $challanData="";
                    $feedata=array();
                    foreach ($groupdata as $grp => $data)
                    {     
                        foreach ($data as $k => $val)
                        {       
                            $amt=$val[0];
                            $ftype=$val[2];
                            $fname=$val[1];
                            $sql = "SELECT * FROM createtempchallantransportfee('$challanNo','$studentId','$createdby','".$k."','$stream','$class','$cur_term','$name','".$amt."','$remarks','$duedate','".$ftype."','$academicId')";    
                            //echo $sql;
                            //exit;
                            $result = sqlgetresult($sql);  
                            //print_r($result);
                            //exit;               
                           if ($result['createtempchallantransportfee'] > 0) {
                                array_push($createdChallans, $challanNo);
                                $challanData = sqlgetresult('SELECT * FROM tempChallan WHERE "challanNo"=\'' . $challanNo . '\' ORDER BY id ASC LIMIT 1');
                                $group_name=$val[3];
                                $group_id=$val[2];
                                $feedata[$group_name][$group_id][$k][] = $amt;
                                $feedata[$group_name][$group_id][$k][] = $fname;

                                //sendNotificationToParents($studentId, $_POST['mail_content'],$_POST['sms_content'],  "additionalfeechallan");
                            }else{
                                $exists_fee_type[$k][] = $val[1]."-".$name;
                            }
                        }            
                    }
                    if(count($createdChallans) >0){
                        $_SESSION['createdchallanids']=array_unique($createdChallans);
                        $challanData['challanNo']=$createdChallans[0];
                        //$challanData['challanNo']=implode(",",$_SESSION['createdchallanids']);
                    }
                    $selectedData['feeData'] = $feedata;
                    $selectedData['is_exists']=count($exists_fee_type);
                    $selectedData['exists']=$exists_fee_type;
                    $selectedData['challanData'] = $challanData;
                } else {
                    $selectedData = 'Fee Types empty';
                }
            }
        }
    }
    echo json_encode($selectedData);
}


//***********Chequedd Feetye section - Start***********//
if (isset($_POST['fee_pay_adm']) && $_POST['fee_pay_adm'] == 'confirm'){
    //$_POST = array_map('trim',$_POST);
    date_default_timezone_set("Asia/Kolkata");    
    $cur_data = time();
    $date = date('Y-m-d h:i:s');
    $uid = $_SESSION['myadmin']['adminid'];
    $s_uid = isset($_POST['s_uid'])?trim($_POST['s_uid']):"";
    $student_id = isset($_POST['sid'])?trim($_POST['sid']):"";
    $pid = isset($_POST['pid'])?trim($_POST['pid']):"";
    $term = isset($_POST['term'])?trim($_POST['term']):"";
    $classid = isset($_POST['class'])?trim($_POST['class']):"";
    $streamid = isset($_POST['stream'])?trim($_POST['stream']):"";
    $academicyearid = isset($_POST['academicyear'])?trim($_POST['academicyear']):"";
    $section = isset($_POST['section'])?trim($_POST['section']):"";
    $cnum = isset($_POST['cnum'])?trim($_POST['cnum']):"";
    $ptype = isset($_POST['ptype'])?trim($_POST['ptype']):"";
    $transNum = isset($_POST['paymentmodetrans'])?trim($_POST['paymentmodetrans']):"";
    $amount_val = isset($_POST['amount'])?trim($_POST['amount']):"";
    $transStatus = isset($_POST['status'])?trim($_POST['status']):"";
    $transDate = isset($_POST['paiddate'])?trim($_POST['paiddate']):"";
    $remarks = isset($_POST['remarks'])?trim($_POST['remarks']):"";
    $fullwaived = isset($_POST['fullwaived'])?trim($_POST['fullwaived']):"";
    $remarksfull = $remarks;
    if($remarks){
        $remarks=substr($remarks, 0, 30);
    }

    $otherfeestype=3;
    $feeGroupid=11;
    $feetyp='';
    $qty_val=0;

    if($ptype == "Online"){
     $bank_val = "Atom";
    }
    else{
    $bank_val = isset($_POST['cbank'])?trim($_POST['cbank']):"";
    }
    $suffix="TRA-";

    if($transStatus=='Ok'){
        $sstatus=1;
    }else{
        $sstatus=2;
    }

    /*echo "<pre>";
    print_r($_POST);
    exit;*/



    $payment_insert_id = 0;
    if(!empty($cnum) && (!empty($amount_val) || $fullwaived=='full waiver applied')){
        //echo "SELECT * FROM createotherfeestransaction('$s_uid','$uid','$amount_val','$otherfeestype',NULL,NULL,NULL,'$cnum','$classid','$academicyearid','$streamid','$term','$section','$ptype')";
        //exit;
       $run1=sqlgetresult("SELECT * FROM createotherfeestransaction('$s_uid','$uid','$amount_val','$otherfeestype',NULL,NULL,NULL,'$cnum','$classid','$academicyearid','$streamid','$term','$section','$ptype')",true);
           $lastinsert_id = isset($run1[0]['createotherfeestransaction'])?$run1[0]['createotherfeestransaction']:"";
           if($lastinsert_id){

                $refnum=$suffix.$lastinsert_id;
                //date_default_timezone_set('Asia/Calcutta');
                $createdOn = date("Y-m-d");
                $returnCode=$ptype."_".$bank_val."_".$transNum."_".$amount_val."_".$remarks."_".$transDate."_".$fullwaived;

                //echo 'INSERT INTO tbl_payments ("parentId","studentId","challanNo","amount","transStatus","transNum", "transId", "remarks", "returnCode","transDate", "createdby", "createdOn") VALUES (\''.$pid.'\',\''.$student_id.'\',\''.$cnum.'\',\''.$amount_val.'\',\''.$transStatus.'\',\''.$refnum.'\',\''.$transNum.'\',\''.$remarks.'\',\''.$returnCode.'\',\''.$transDate.'\',\''.$uid.'\',\''.$createdOn.'\') RETURNING id';
               // exit;
                $payment_id = sqlgetresult('INSERT INTO tbl_payments ("parentId","studentId","challanNo","amount","transStatus","transNum", "transId", "remarks", "returnCode","transDate", "createdby", "createdOn") VALUES (\''.$pid.'\',\''.$student_id.'\',\''.$cnum.'\',\''.$amount_val.'\',\''.$transStatus.'\',\''.$refnum.'\',\''.$transNum.'\',\''.$remarks.'\',\''.$returnCode.'\',\''.$transDate.'\',\''.$uid.'\',\''.$createdOn.'\') RETURNING id');
                $payment_insert_id = isset($payment_id['id'])?$payment_id['id']:0;
                /*$receiptupd = updatereceipt_by_feetype($cnum,$sid,$feegroupradio,$type_id);
                $fromwhere = 'Receipt';
                flattableentry_feetype($cnum, $sid, $fromwhere);*/
                sqlgetresult('UPDATE tbl_otherfees_payment_log SET "refNum" = \''.$refnum.'\', status=\''.$sstatus.'\',remarks=\''.$remarksfull.'\' WHERE "id" = \''.$lastinsert_id.'\'');
           }
        
        if($payment_insert_id > 0){
            if($transStatus=='Ok'){
                if($fullwaived=='full waiver applied'){
                    $updatechallanstatus = sqlgetresult('UPDATE tbl_challans SET "fullyWaived" = \'1\' WHERE "challanNo" = \''.$cnum.'\'');
                } 
                completeTransportChallanAdminSide(1, $uid, $cnum, $student_id, $ptype, $bank_val, $transNum, $transDate, $remarksfull);
            }
            $_SESSION['successcheque'] = "<p class='success-msg'>Payment Updated Successfully.</p>";     
        }
        else{
            $_SESSION['errorcheque'] = "<p class='error-msg'>Payment was not updated in Receipt table.</p>";
        }

    }else{
       $_SESSION['errorcheque'] = "<p class='error-msg'>Mandatory fields are missing.</p>";
    }
    header("Location: addtransportfee.php");
    exit; 
}

if (isset($_POST["submit"]) && $_POST["submit"] == "createtransportchallan")
{   
    if (!empty($_POST['checkme']))
    {
        $selectedchallans = [];
        foreach ($_POST['checkme'] as $selected)
        {
            // echo $selected."</br>";s
            array_push($selectedchallans, $selected);
        }
        $_SESSION['selectedtransfeechallans'] = $selectedchallans;
        header('location:createtransportfee.php');
    }
    echo json_encode($selectedchallans);
}

if (isset($_POST['edit_fee_trans_pay']) && $_POST['edit_fee_trans_pay'] == 'confirm'){
    
    $student_id = isset($_POST['sid'])?trim($_POST['sid']):"";
    $pid = isset($_POST['pid'])?trim($_POST['pid']):"";
    $payid = isset($_POST['payid'])?trim($_POST['payid']):"";
    $id = isset($_POST['id'])?trim($_POST['id']):"";
    $ptype_val = isset($_POST['ptype'])?trim($_POST['ptype']):"";
    $cbank = isset($_POST['cbank'])?trim($_POST['cbank']):"";
    $bank = isset($_POST['bank'])?trim($_POST['bank']):"Atom";
    $transNum = isset($_POST['paymentmodetrans'])?trim($_POST['paymentmodetrans']):"";
    $amount_val = isset($_POST['amount'])?trim($_POST['amount']):0;
    $transDate = isset($_POST['paiddate'])?trim($_POST['paiddate']):"";
    $remarks_val = isset($_POST['remarks'])?trim($_POST['remarks']):"";
    $status = isset($_POST['status'])?trim($_POST['status']):"";
    $challanNo = isset($_POST['challanNo'])?trim($_POST['challanNo']):"";
    $fullwaived = isset($_POST['fullwaived'])?trim($_POST['fullwaived']):"";
    $remarksfull = $remarks_val;
    $uid = $_SESSION['myadmin']['adminid'];

    if(!empty($remarks_val)){
        $remarks_val=substr($remarks_val, 0, 30);
    }

    if($ptype_val == "Online"){
        $bank_val = $bank;
    }
    else{
        $bank_val = $cbank;
    }
    $sts=2;
    if($status == 'Ok'){
        $sts=1;
    }

   // echo $query = "SELECT * FROM editotherpayment('$payid','$transNum','$amount_val','$status','$remarks_val','$transDate','$uid')";

    //exit;
    if(!empty($challanNo) && !empty($payid) && (!empty($amount_val) || $fullwaived=='full waiver applied')){
        $sqlchk = 'SELECT COUNT(*) AS numq FROM otherfeesreport WHERE "challanNo" = \'' . $challanNo . '\' AND "transStatus"= \'Ok\'';
        $querychk = sqlgetresult($sqlchk);
        if($querychk['numq'] == 0){

            $returnCode=$ptype_val."_".$bank_val."_".$transNum."_".$amount_val."_".$remarks_val."_".$transDate."_".$fullwaived;

            $query = "SELECT * FROM edittransportpayment('$payid','$transNum','$amount_val','$status','$remarks_val','$transDate','$uid','$returnCode')";
            $run = sqlgetresult($query);
            
            if($run['edittransportpayment'] == 1){
                sqlgetresult('UPDATE tbl_otherfees_payment_log SET pay_type=\''.$ptype_val.'\', status = \''.$sts.'\', remarks = \''.$remarksfull.'\' WHERE "id" = \''.$id.'\'');
                if($status == 'Ok'){
                    if($fullwaived=='full waiver applied'){
                        $updatechallanstatus = sqlgetresult('UPDATE tbl_challans SET "fullyWaived" = \'1\' WHERE "challanNo" = \''.$cnum.'\'');
                    } 
                    completeTransportChallanAdminSide(1, $uid, $challanNo, $student_id, $ptype_val, $bank_val, $transNum, $transDate, $remarksfull);
                }
                $_SESSION['successcheque'] = "<p class='success-msg'>Payment Updated Successfully.</p>";
            }
            else if ($run['editotherpayment'] == 0){
                $_SESSION['errorcheque'] = "<p class='error-msg'>Given transaction number Already Exist</p>";
            }
            else{
                createErrorlog($run);
                $_SESSION['errorcheque'] = "<p class='error-msg'>Payment was not updated in Payment table.</p>";
            }
        }else{
            $_SESSION['errorcheque'] = "<p class='error-msg'>Already payment paid for this challan number ".$challanNo."</p>";
        }
    }else{
       $_SESSION['errorcheque'] = "<p class='error-msg'>Mandatory fields are missing.</p>";
    }   
   
   header("Location: managetransportreport.php"); 
   exit;
}

if(isset($_GET['othertransportexportexcel']) && $_GET['othertransportexportexcel']=='exportexcel'){
    $sql = $_SESSION['otherfeereportquery'];
    $sqlrun = sqlgetresult($sql,true);
    foreach($sqlrun AS $key => $output){
        //$result_data[$key]['Ref Number']=$output['transNum'];
        $result_data[$key]['Student Id']=trim($output['chlstudentid']);
        $result_data[$key]['Student Name']=trim($output['studentName']);
        $result_data[$key]['Stream'] = trim($output['streamname']);
        $result_data[$key]['Semester'] = trim($output['term']);
        $result_data[$key]['Class'] = trim($output['class_list']);
        $result_data[$key]['Section'] = $output['section'];
        $result_data[$key]['Challan No']=trim($output['challanNo']);
        $result_data[$key]['Academic Year'] = trim($output['academic_yr']);
        $result_data[$key]['Semester'] = trim($output['term']);
        $result_data[$key]['Fee Type'] = trim($output['feetypename']);
        if($output['transDate']){
          $result_data[$key]['Transaction Date']=date("d-m-Y", strtotime($output['transDate']));
        }else{
           $result_data[$key]['Transaction Date']="";
        }
        $result_data[$key]['Ref Number']=trim($output['transNum']);
        $result_data[$key]['Transaction Id']=trim($output['transId']);
        $result_data[$key]['Total']=$output['amount'];
        if($output['paymethod']){
          $result_data[$key]['Payment Method']=$output['paymethod']; 
        }else{
          $result_data[$key]['Payment Method']=""; 
        }          
    }
    foreach ($result_data as $k => $v) {
        $keys = array();
        foreach ($v as $field_code => $field_val)
        {       
                $keys[]=$field_code;                
        }       
        $columns = $keys;
    }
    exportData($result_data, 'Transport Fee Report', $columns);
}


if (isset($_POST['chequerevoketrans']) && $_POST['chequerevoketrans'] == "chequerevoketrans")
{
    $cno = trim($_POST['stdidforcheque']);
    $uid = $_SESSION['myadmin']['adminid'];
    $logid = $_POST['logid'] ?? "";

    if(!empty($cno) && !empty($logid)){
        $challandata = sqlgetresult('SELECT "studentId", "academicYear", "term" FROM tbl_challans WHERE "challanNo" = \''.$cno.'\' AND "studStatus"=\'Transport.Fee\' LIMIT 1');
        $acayear = $challandata['academicYear'];
        $term = $challandata['term'];
        $stdid = $challandata['studentId'];

        $chequerevoke = "SELECT * FROM chequerevoke('$cno','$uid', '$stdid', '$term', '$acayear')";
        $runchequerevoke = sqlgetresult($chequerevoke);

        if ($runchequerevoke['chequerevoke'] > 0)
        {
          $refnum="TRA-".$logid;
          $updatechallanstatus = sqlgetresult('UPDATE tbl_payments SET "transStatus" = \'C\' WHERE "transNum" = \''.$refnum.'\'');
          $_SESSION['successcheque'] = "<p class='success-msg'>Challan Revoked Successfully.</p>";
        }
        else
        {
            createErrorlog($run);
            $_SESSION['errorcheque'] = "<p class='error-msg'>Some error has been occured. Please try again.</p>";
        }
    }else{
        $_SESSION['errorcheque'] = "<p class='error-msg'>Mandatory field missing.</p>";
    }
    header('location:managetransportreport.php');
    exit;
}


/* Transport Challan Filter */
if (isset($_POST['feetransfilter']) && $_POST['feetransfilter'] == "feetransfilter")
{

    $whereClauses = array(); 
    if (! empty($_POST['classselect'])) 
        $whereClauses[] ='"class"=\''.pg_escape_string($_POST['classselect']).'\' ' ;
    $where='';

    if (! empty($_POST['streamselect'])) 
      $whereClauses[] ="stream='".pg_escape_string($_POST['streamselect'])."'"; 
    $where = ''; 

    if (! empty($_POST['sectionselect'])) 
      $whereClauses[] ="section='".pg_escape_string($_POST['sectionselect'])."'"; 
    $where = ''; 

    if (! empty($_POST['studentid'])) 
      $whereClauses[] ='"studentId"=\''.pg_escape_string($_POST['studentid']).'\' ' ;
    $where = '';    

    if (count($whereClauses) > 0) { 
        $where = ' WHERE '.implode(' AND ',$whereClauses); 
    }

    $sql = ('SELECT * FROM  studentcheck'. $where);   
    $res = sqlgetresult($sql, true);
    $_SESSION['data'] = $res;
    header('Location: transportstdlist.php');
}

/* Moved to Paid List Start */
if (isset($_POST["submit"]) && $_POST["submit"] == "movetopaid")
{   
    $ids=isset($_POST['checkme'])?$_POST['checkme']:[];
    $status=0;
    $success=[];
    $failed=[];
    if (count($ids) > 0)
    {
        foreach ($ids as $selected)
        {
          $status=moveToPaidChallans($selected);
          if($status == 1){
           $success[]=$selected;
          }else{
            $failed[]=$selected;
          }
        }
    }
    if(count($success) > 0){
        $ok=implode(",",$success);
        $_SESSION['successdelete'] = "<p class='success-msg'>(".$ok.") moved Successfully.</p>";
    }

    if(count($failed) > 0){
        $ntok=implode(",",$failed);
        $_SESSION['errordelete'] = "<p class='error-msg'>(".$ntok.") not moved to paid list.</p>";
    }
    header('location:managecreatedchallans.php');
}
function moveToPaidChallans($cn){
    $cn = trim($cn);
    if(!empty($cn)){
        //$updatedOn = date("Y-m-d h:m:s");
        $updatedby = $_SESSION['myadmin']['adminid'];
        $challanstatus = 3;
        $updatechallanstatus = "SELECT * FROM updatechallanstatus('$cn','$updatedby', '$challanstatus')";
        $runupdatechallanstatus = sqlgetresult($updatechallanstatus);
        $out=isset($runupdatechallanstatus['updatechallanstatus'])?$runupdatechallanstatus['updatechallanstatus']:0;
        return $out;
    }
}

/* Moved to Paid List End */
if (isset($_POST['filter']) && $_POST['filter'] == "fltpartialpaid")
{
   
  $pid = isset($_POST['pid'])?trim($_POST['pid']):"";
  $studid = isset($_POST['studid'])?trim($_POST['studid']):"";
  if(!empty($studid)){

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

/*Bulk Partial Status Update */
if (isset($_POST["partialstatus"]))
{   
    $status=trim($_POST['partialstatus']);
    $uid = $_SESSION['myadmin']['adminid'];
    $ids=isset($_POST['checkme'])?$_POST['checkme']:[];
    $tbl = 'tbl_partial_payment';
    $page = 'managepartial.php';
    if (count($ids) > 0)
    {
        foreach ($ids as $selected)
        {
            $id=trim($selected);
            $query = "SELECT * FROM statusupdate('$tbl','$status','$uid','$id')";
            $res = sqlgetresult($query);
        }
        $_SESSION['success'] = "<p class='success-msg'>Status has been updated successfully.</p>";
    }else{
        //createErrorlog($res);
        $_SESSION['failure'] = "<p class='error-msg'>Some error has been occured. Please try again later.</p>";
    }
    header("Location:" . $page);
}

/*Inline Update partial minimum amount classwise */
if (isset($_POST["column"]) && $_POST["column"]=='inline' && !empty($_POST['id']))
{   
    $editval=trim($_POST['editval']);
    $uid = $_SESSION['myadmin']['adminid'];
    $id=isset($_POST['id'])?trim($_POST['id']):"";
    $tbl = 'tbl_class';

    if($editval){
        if($editval < 100){
            sqlgetresult('UPDATE tbl_class SET partial_min_percentage=\''.$editval.'\',"updatedBy"=\''.$uid.'\' WHERE id=\''.$id.'\'');
        }
   }else{
    sqlgetresult('UPDATE tbl_class SET partial_min_percentage=NULL,"updatedBy"=\''.$uid.'\' WHERE id=\''.$id.'\'');
   } 
}


//*************OTHER FEES (LUNCH, UNIFORM) FILTER- Start************//
if (isset($_POST['filter']) && $_POST['filter'] == "cartchkoutfilter")
{
    $_POST = array_map('trim',$_POST);
    $classselect = isset($_POST['classselect'])?$_POST['classselect']:"";
    $streamselect = isset($_POST['streamselect'])?$_POST['streamselect']:"";
    $sectionselect = isset($_POST['sectionselect'])?$_POST['sectionselect']:"";
    $yearselect = isset($_POST['yearselect'])?$_POST['yearselect']:"";
    $semesterselect = isset($_POST['semesterselect'])?$_POST['semesterselect']:"";
    $refnumber = isset($_POST['refnumber'])?$_POST['refnumber']:"";
    $stats = isset($_POST['tstatus'])?$_POST['tstatus']:"";
    $type = isset($_POST['type'])?$_POST['type']:1;
    $from = isset($_POST['from'])?$_POST['from']:"";
    $to = isset($_POST['to'])?$_POST['to']:"";


    $whereClauses = array();
    $where = ''; 

    if(!empty($refnumber)){
        $whereClauses[] ='"transNum"=\''.pg_escape_string($refnumber).'\' ' ;
    }
    
    if(!empty($stats)){
        $whereClauses[] ='"transStatus"=\''.pg_escape_string($stats).'\' ' ;
    }

    if (!empty($yearselect)){
        $whereClauses[] ='"academicYear"=\''.pg_escape_string($yearselect).'\' ' ;
    }

    if (!empty($semesterselect)) {
      $whereClauses[] ="term='".pg_escape_string ($semesterselect)."'"; 
    }

    if (!empty($classselect)) {
        $whereClauses[] ='"classList"=\''.pg_escape_string($classselect).'\' ' ;
    }

    if (!empty($streamselect)) {
      $whereClauses[] ="stream='".pg_escape_string ($streamselect)."'"; 
    }

    if (!empty($sectionselect)) {
      $whereClauses[] ="section='".pg_escape_string ($sectionselect)."'"; 
    }

    if (!empty($from) && !empty($to))
    {
      $whereClauses[] = 'DATE("transDate") BETWEEN \'' . pg_escape_string ($from) . '\'  AND  \'' . pg_escape_string ($to) . '\'';
    }
    
    if (count($whereClauses) > 0) 
    { 
      $where = ' WHERE '.implode(' AND ',$whereClauses); 
    } 
   $sql = ('SELECT * FROM cartcheckoutreport '.$where);
    $_SESSION['cartcheckoutreportquery']=$sql; 
    $res = sqlgetresult($sql, true);
    //print_r($res);
    //exit;
    $challanData = array();
    $challanNo = '';
    $outputdata = array();
    $paidchallan = array();
    if (count($res) > 0)
    {
        foreach ($res as $k => $data)
        {
            
            $unique_id = $data['id'];
            $challanno=trim($data['transNum']);
            $status=trim($data['transStatus']);
            $challanData[$unique_id]['id'] = $data['id'];
            $challanData[$unique_id]['studentId'] = trim($data['studentId']);
            $challanData[$unique_id]['studentName'] = trim($data['studentName']);
            $challanData[$unique_id]['academic_yr'] = trim($data['academic_yr']);
            $challanData[$unique_id]['term'] = trim($data['term']);
            $challanData[$unique_id]['streamname'] = trim($data['streamname']);
            $challanData[$unique_id]['class_list'] = trim($data['class_list']);
            $challanData[$unique_id]['section'] = trim($data['section']);
            
            $ref_num=trim($data['transNum']);
            $challanData[$unique_id]['term'] = $data['term'];
            if($data['transDate']){
                $challanData[$unique_id]['transDate'] = date("d-m-Y", strtotime($data['transDate']));
            }else{
                $challanData[$unique_id]['transDate'] = "";
            }
            
            $challanData[$unique_id]['amount'] = $data['receivedamount'];
            $challanData[$unique_id]['transStatus'] = $status;
            $challanData[$unique_id]['transNum'] = $ref_num;
            $challanData[$unique_id]['transId'] = trim($data['transId']);
            $challanData[$unique_id]['pay_type'] = trim($data['paymentmode']);
            if($status=='Ok'){
              $challanData[$unique_id]['action'] = "";
            }else{
              $challanData[$unique_id]['action'] = '<a href="editcartcheckout.php?id='.$unique_id.'"><i class="fa fa-edit fafa"></i></a>';
            } 
        }
        foreach($challanData AS $challan){
            $paidchallan[]= $challan;
        }

        $outputdata = $paidchallan;
    }
    else
    {
        $outputdata = array();
    }
    echo json_encode($outputdata);

}

if (isset($_POST['edit_cart_fee_pay']) && $_POST['edit_cart_fee_pay'] == 'confirm'){
    
    $student_id = isset($_POST['sid'])?trim($_POST['sid']):"";
    $pid = isset($_POST['pid'])?trim($_POST['pid']):"";
    $id = isset($_POST['id'])?trim($_POST['id']):"";
    $ptype_val = isset($_POST['ptype'])?trim($_POST['ptype']):"";
    $cbank = isset($_POST['cbank'])?trim($_POST['cbank']):"";
    $bank = isset($_POST['bank'])?trim($_POST['bank']):"Atom";
    $transNum = isset($_POST['paymentmodetrans'])?trim($_POST['paymentmodetrans']):"";
    $amount_val = isset($_POST['amount'])?trim($_POST['amount']):0;
    $transDate = isset($_POST['paiddate'])?trim($_POST['paiddate']):"";
    $remarks_val = isset($_POST['remarks'])?trim($_POST['remarks']):"";
    $status = isset($_POST['status'])?trim($_POST['status']):"";
    $refids = isset($_POST['refids'])?trim($_POST['refids']):"";
    $remarksfull = $remarks_val;
    $uid = $_SESSION['myadmin']['adminid'];

    /*if(!empty($remarks_val)){
        $remarks_val=substr($remarks_val, 0, 30);
    }*/

    if($ptype_val == "Online"){
        $bank_val = $bank;
    }
    else{
        $bank_val = $cbank;
    }
    
    if($id && $refids){
       $chkdata = sqlgetresult('SELECT * FROM tbl_cart_payment_log WHERE "referenceids" =\'' . $refids . '\'  AND "transStatus"=\'Ok\' AND "deleted" = \'0\' ', true);
       if(count($chkdata) > 0){
          $_SESSION['errorcheque'] = "<p class='error-msg'>Already payment paid for this reference id ".$refids."</p>";
       }else{
        $returnCode=$ptype_val."_".$bank_val."_".$transNum."_".$amount_val."_".$remarksfull."_".$transDate;
        if(strlen($transNum) > 20){
            $transNum=substr($transNum, 0, 20);
        }
        $paymentData = sqlgetresult("SELECT * FROM cartpaymententry('$amount_val','$status','".$returnCode."','".$remarksfull."','".$transNum."','$transDate','".$uid."','".$id."') ");
        if($paymentData['cartpaymententry']){
            cartUpdateStatus($paymentData['cartpaymententry'], $student_id);
            $_SESSION['successcheque'] = "<p class='success-msg'>Payment has been updated Successfully.</p>";
        }else{
            $_SESSION['errorcheque'] = "<p class='error-msg'>Some error has been occured. Please try again.</p>";
        }
       }
    }else{
       $_SESSION['errorcheque'] = "<p class='error-msg'>Mandatory fields are missing.</p>";
    }   
   header("Location: editcartcheckout.php?id=".$id); 
   exit;
}


if(isset($_GET['cartexportexcel']) && $_GET['cartexportexcel']=='exportexcel'){
    $sql = $_SESSION['cartcheckoutreportquery'];
    $sqlrun = sqlgetresult($sql,true);
    foreach($sqlrun AS $key => $output){
        //$result_data[$key]['Ref Number']=$output['transNum'];
        $result_data[$key]['Student Id']=trim($output['studentId']);
        $result_data[$key]['Student Name']=trim($output['studentName']);
        $result_data[$key]['Stream'] = trim($output['streamname']);
        $result_data[$key]['Semester'] = trim($output['term']);
        $result_data[$key]['Class'] = trim($output['class_list']);
        $result_data[$key]['Section'] = $output['section'];
        $result_data[$key]['Academic Year'] = trim($output['academic_yr']);
        //$result_data[$key]['Semester'] = $output['term'];
        if($output['transDate']){
            $result_data[$key]['Transaction Date']=date("d-m-Y", strtotime($output['transDate']));
        }else{
            $result_data[$key]['Transaction Date']="";
        }
        
        $result_data[$key]['Ref Number']=trim($output['transNum']);
        $result_data[$key]['Transaction Id']=trim($output['transId']);
        $result_data[$key]['Reference Ids']=$output['referenceids'];
        $result_data[$key]['Transaction Status']=$output['transStatus'];
        $result_data[$key]['Total']=$output['receivedamount'];
        
    }
    foreach ($result_data as $k => $v) {
        $keys = array();
        foreach ($v as $field_code => $field_val)
        {       
                $keys[]=$field_code;                
        }       
        $columns = $keys;
    }
    exportData($result_data, 'Cart Checkout Report', $columns);
}

if (isset($_POST['filter']) && $_POST['filter'] == "sfspaidreportflter")
{
    $_POST = array_map('trim',$_POST);
    $classselect = isset($_POST['classselect'])?$_POST['classselect']:"";
    $streamselect = isset($_POST['streamselect'])?$_POST['streamselect']:"";
    $yearselect = isset($_POST['yearselect'])?$_POST['yearselect']:"";
    $semesterselect = isset($_POST['semesterselect'])?$_POST['semesterselect']:"";
    $stid = isset($_POST['stid'])?$_POST['stid']:"";
    $stats = isset($_POST['tstatus'])?$_POST['tstatus']:"";
    $type = isset($_POST['type'])?$_POST['type']:1;
    $from = isset($_POST['from'])?$_POST['from']:"";
    $to = isset($_POST['to'])?$_POST['to']:"";


    $whereClauses = array();
    $where = ''; 
    $_SESSION['sfsdatareportquery']='';

    $isStudIdSearch=false;

    if(!empty($stid)){
        $whereClauses[] ='"studentId"=\''.pg_escape_string($stid).'\' ' ;
        $isStudIdSearch=true;
    }
    
    if($stats){
        if($stats==3){
          $whereClauses[] ='"challanStatus"=\'0\' ' ;
        }else{
          $whereClauses[] ='"challanStatus"=\''.pg_escape_string($stats).'\' ' ;
        }
    }

    if (!empty($yearselect)){
        $whereClauses[] ='"academicYear"=\''.pg_escape_string($yearselect).'\' ' ;
    }

    if (!empty($semesterselect)) {
      $whereClauses[] ="term='".pg_escape_string ($semesterselect)."'"; 
    }

    if (!empty($classselect)) {
        $whereClauses[] ='"classList"=\''.pg_escape_string($classselect).'\' ' ;
    }

    if (!empty($streamselect)) {
      $whereClauses[] ="stream='".pg_escape_string ($streamselect)."'"; 
    }


    if (!empty($from) && !empty($to))
    {
        $whereClauses[] = '(DATE("paid_date") BETWEEN \'' . pg_escape_string ($from) . '\'  AND  \'' . pg_escape_string ($to) . '\' OR DATE("updatedOn") BETWEEN \'' . pg_escape_string ($from) . '\'  AND  \'' . pg_escape_string ($to) . '\')';
    }
    
    if (count($whereClauses) > 0) 
    { 
      $where = ' WHERE '.implode(' AND ',$whereClauses); 
    } 
 
    /*if($isStudIdSearch){
        $sql = ('SELECT *,DATE("updatedOn") AS updated FROM sfsdatareportmodified '.$where);
    }else{*/
        $sql = ('SELECT *,DATE("updatedOn") AS updated FROM sfsdatareportlatest '.$where);
   // }
    $_SESSION['sfsdatareportquery']=$sql; 
    $res = sqlgetresult($sql, true);
    $challanData = array();
    $challanNo = '';
    $outputdata = array();
    $paidchallan = array();
    if (count($res) > 0)
    {
        $aYears=getacadamicYears();
        $classes=getClassses();
        $streams=getStreams();
        $students=getStudentDetails();
        foreach ($res as $k => $data)
        {
            $challanno=trim($data['challanNo']);
            $unique_id = $challanno;
            $paidstatus=$data['challanStatus'];
            $status=$data['status'];
            $studentId=trim($data['studentId']);
            $challanData[$unique_id]['id'] = $data['id'];
            $challanData[$unique_id]['studentId'] = $studentId;
            /*if($isStudIdSearch){
                $challanData[$unique_id]['studentId'] = $studentId;
                $challanData[$unique_id]['studentName'] = $students[$studentId]['studentName'];
            }else{*/
                if($students[$studentId]['studentId']){
                    $challanData[$unique_id]['studentName'] = $students[$studentId]['studentName'];
                }else{
                    $oldStudentId=getStudentDetailsByOldId($studentId);
                    if($oldStudentId){
                        $challanData[$unique_id]['studentName'] = $students[$oldStudentId]['studentName'];
                    }else{
                        $challanData[$unique_id]['studentName'] = "";
                    }
                }
           // }
            
            
            $challanData[$unique_id]['academic_yr'] = $aYears[$data['academicYear']];
            $challanData[$unique_id]['streamname'] = $streams[$data['stream']];
            $challanData[$unique_id]['class_list'] = $classes[$data['classList']];
            $challanData[$unique_id]['term'] = $data['term'];
            if($data['paid_date']){
                $challanData[$unique_id]['paid_date'] = $data['paid_date'];
            }else{
                if($data['updatedOn']){
                    $challanData[$unique_id]['paid_date'] = $data['updated'];
                }else{
                   $challanData[$unique_id]['paid_date'] = "";  
                }
                
            }
           
            $challanData[$unique_id]['challanNo'] = $challanno;
            if($status == 1){
                $challanData[$unique_id]['status'] = "Active";
            }
            else{
                $challanData[$unique_id]['status'] = "In-Active";   
            }
            if($paidstatus == 1){
                $challanData[$unique_id]['paid_status'] = "Paid";
            }
            else if($paidstatus == 2){
                $challanData[$unique_id]['paid_status'] = "Partial Paid";
            }
            else{
                $challanData[$unique_id]['paid_status'] = "Not Paid";
            }
        }
        foreach($challanData AS $challan){
            $paidchallan[]= $challan;
        }

        $outputdata = $paidchallan;
    }
    else
    {
        $outputdata = array();
    }
    echo json_encode($outputdata);
}
if(isset($_GET['sfsreportexcel']) && $_GET['sfsreportexcel']=='exportexcel'){
    $sql = $_SESSION['sfsdatareportquery'];
    $sqlrun = sqlgetresult($sql,true);
    if (count($sqlrun) > 0)
    {
        $aYears=getacadamicYears();
        $classes=getClassses();
        $streams=getStreams();
        $students=getStudentDetails();
        foreach($sqlrun as $k => $data){
            $challanno=trim($data['challanNo']);
            $unique_id = $challanno;
            $paidstatus=$data['challanStatus'];
            $status=$data['status'];
            $studentId=trim($data['studentId']);
            $challanData[$unique_id]['studentId'] = $studentId;
            /*if($isStudIdSearch){
                $challanData[$unique_id]['studentId'] = $studentId;
                $challanData[$unique_id]['studentName'] = $students[$studentId]['studentName'];
            }else{*/
                if($students[$studentId]['studentId']){
                    $challanData[$unique_id]['studentName'] = $students[$studentId]['studentName'];
                }else{
                    $oldStudentId=getStudentDetailsByOldId($studentId);
                    if($oldStudentId){
                        $challanData[$unique_id]['studentName'] = $students[$oldStudentId]['studentName'];
                    }else{
                        $challanData[$unique_id]['studentName'] = "";
                    }
                }
           // }
            
            
            $challanData[$unique_id]['academic_yr'] = $aYears[$data['academicYear']];
            $challanData[$unique_id]['streamname'] = $streams[$data['stream']];
            $challanData[$unique_id]['class_list'] = $classes[$data['classList']];
            $challanData[$unique_id]['term'] = $data['term'];
            if($data['paid_date']){
                $challanData[$unique_id]['paid_date'] = $data['paid_date'];
            }else{
                if($data['updatedOn']){
                    $challanData[$unique_id]['paid_date'] = $data['updated'];
                }else{
                    $challanData[$unique_id]['paid_date'] = "";
                }
                
            }
            $challanData[$unique_id]['challanNo'] = $challanno;
            if($status == 1){
                $challanData[$unique_id]['status'] = "Active";
            }
            else{
                $challanData[$unique_id]['status'] = "In-Active";   
            }
            if($paidstatus == 1){
                $challanData[$unique_id]['paid_status'] = "Paid";
            }
            else if($paidstatus == 2){
                $challanData[$unique_id]['paid_status'] = "Partial Paid";
            }
            else{
                $challanData[$unique_id]['paid_status'] = "Not Paid";
            }
        }
    }
    foreach ($challanData as $k => $v) {
        $keys = array();
        foreach ($v as $field_code => $field_val)
        {       
            $keys[]=$field_code;                
        }       
        $columns = $keys;
    }
    exportData($challanData, 'SFS Report', $columns);
}
/* Late Fee Enable/Disable Start */
if (isset($_POST["latefee"]))
{   
    $ids=isset($_POST['checkme'])?$_POST['checkme']:[];
    $val=isset($_POST['latefee'])?trim($_POST['latefee']):'';
    $status=0;
    $success=[];
    $failed=[];
    if (count($ids) > 0)
    {
        if($val==1){
            $txt="disabled";
        }else{
            $txt="enabled";
        }
        foreach ($ids as $selected)
        {
          $status=latefeeChallanStatusUpdate($selected,$val);
          if($status == 1){
           $success[]=$selected;
          }else{
            $failed[]=$selected;
          }
        }
    }
    if(count($success) > 0){
        //$ok=implode(",",$success);
        $_SESSION['successdelete'] = "<p class='success-msg'>Late fee ".$txt." successfully.</p>";
    }else{
        //$ntok=implode(",",$failed);
        $_SESSION['errordelete'] = "<p class='error-msg'>Late fee not ".$txt.".</p>";
    }
    header('location:managecreatedchallans.php');
}
function latefeeChallanStatusUpdate($cn,$challanstatus){
    $cn = trim($cn);
    if(!empty($cn)){
        //$updatedOn = date("Y-m-d h:m:s");
        $updatedby = $_SESSION['myadmin']['adminid'];
        $updatelatefeechallanstatus = "SELECT * FROM updatelatefeechallanstatus('$cn','$updatedby', '$challanstatus')";
        $runupdatechallanstatus = sqlgetresult($updatelatefeechallanstatus);
        $out=isset($runupdatechallanstatus['updatelatefeechallanstatus'])?$runupdatechallanstatus['updatelatefeechallanstatus']:0;
        return $out;
    }
}
/* Late Fee Enable/Disable End */
/* Non Fee with challan - Manual Receipt Generation */
if (isset($_POST['nonfeechallanpay']) && $_POST['nonfeechallanpay'] == "confirm")
{
   $updatedby = $_SESSION['myadmin']['adminid'];
   $id=isset($_REQUEST['id'])?trim($_REQUEST['id']):"";
   $ptype=isset($_REQUEST['ptype'])?trim($_REQUEST['ptype']):"";
   $bank=isset($_REQUEST['bank'])?trim($_REQUEST['bank']):"";
   $cbank=isset($_REQUEST['cbank'])?trim($_REQUEST['cbank']):"";
   $paymentmode=isset($_REQUEST['paymentmode'])?trim($_REQUEST['paymentmode']):"";
   $paymentmodetrans=isset($_REQUEST['paymentmodetrans'])?trim($_REQUEST['paymentmodetrans']):"";
   $createdOn=isset($_REQUEST['paiddate'])?trim($_REQUEST['paiddate']):date("Y-m-d");
   $remarks=isset($_REQUEST['remarks'])?trim($_REQUEST['remarks']):"";
   if($ptype=="Online"){
     $m_trans_id = $paymentmodetrans;
     $bank='Atom';
   }else{
     $m_trans_id = $paymentmode;
     $bank=$cbank;
   }
   $f_code="Ok";
   if($id && $ptype && $bank && $m_trans_id && $remarks){
    $res = sqlgetresult('SELECT * FROM nonfeechallandata WHERE cid=\'' . $id . '\' ');
    $studentId=trim($res['studentId']);
    $parent_id=trim($res['parentId']);
    $amount=trim($res['total']);
    $status=trim($res['challanStatus']);
    $challanNo=trim($res['challanNo']);
    $visible=trim($res['visible']);
    if($status=="0"){
        $payment_id = sqlgetresult('INSERT INTO tbl_nonfee_payments ("studentId","challanNo") VALUES (\''.$studentId.'\',\''.$challanNo.'\') RETURNING id');
        $payment_insert_id = isset($payment_id['id'])?$payment_id['id']:0;
        if($payment_insert_id > 0){
            $desc=substr($remarks,0,50);
            $paymentData = sqlgetresult("SELECT * FROM nonfeechallanpaymententry('".$parent_id."','".$studentId."','$amount','$f_code','$m_trans_id','".json_encode($_POST)."','".$desc."','$createdOn','".$updatedby."','".$payment_insert_id."') ");
            if($paymentData['nonfeechallanpaymententry']){
                date_default_timezone_set("Asia/Kolkata");    
                $date = date('Y-m-d h:i:s');         
                $updateChallan = sqlgetresult('UPDATE tbl_nonfee_challans SET "challanStatus" = 1, "updatedBy" = \''.$updatedby.'\', "updatedOn" =  \''.$date.'\', "pay_type" =  \''.$ptype.'\', "bank" =  \''.$bank.'\', "cheque_dd_no" =  \''.$m_trans_id.'\', "paid_date" =  \''.$createdOn.'\', "chequeRemarks" =  \''.$remarks.'\', "remarks" =  \''.$remarks.'\' WHERE "challanNo" = \''.$challanNo.'\' ');
                 //if($f_code == 'Ok') {  
                 if($visible == '1'){
                    createNFPDF($studentId,$challanNo,'');
                 } else{
                    createNFWPDF($studentId,$challanNo,'');
                 }    
                 $_SESSION['successcheque'] = "<p class='success-msg'>Non-fee Payment Completed Successfully.</p>";
               // }

            }else{
              $_SESSION['errorcheque'] = "<p class='error-msg'>Something gone wrong. Please try again later.</p>";
            }
        }else{
          $_SESSION['errorcheque'] = "<p class='error-msg'>Payment was not updated in Receipt table.</p>";
        }
    }else{
        $_SESSION['errorcheque'] = "<p class='error-msg'>Already paid!</p>";
    }
   }else{
    $_SESSION['errorcheque'] = "<p class='error-msg'>Mandatory field missing.</p>";
   }
    header("Location:nonfeechallans_pay.php?id=".$id);
    exit;
}
/********Common Non-fee Start******/
if( isset($_POST['paycommonnonfee']) && $_POST['paycommonnonfee'] == 'confirm' ) {
   $updatedby = $_SESSION['myadmin']['adminid'];
   $eventname=isset($_REQUEST['eventname'])?trim($_REQUEST['eventname']):"";
   $amount=isset($_REQUEST['amountofevent'])?trim($_REQUEST['amountofevent']):"";
   $studentId=isset($_REQUEST['nonfeestudid'])?trim($_REQUEST['nonfeestudid']):"";
   $ptype=isset($_REQUEST['ptype'])?trim($_REQUEST['ptype']):"";
   $bank=isset($_REQUEST['bank'])?trim($_REQUEST['bank']):"";
   $cbank=isset($_REQUEST['cbank'])?trim($_REQUEST['cbank']):"";
   $paymentmode=isset($_REQUEST['paymentmode'])?trim($_REQUEST['paymentmode']):"";
   $paymentmodetrans=isset($_REQUEST['paymentmodetrans'])?trim($_REQUEST['paymentmodetrans']):"";
   $createdOn=isset($_REQUEST['paiddate'])?trim($_REQUEST['paiddate']):date("Y-m-d");
   $remarks=isset($_REQUEST['remarks'])?trim($_REQUEST['remarks']):"";
   if($ptype=="Online"){
     $m_trans_id = $paymentmodetrans;
     $bank='Atom';
   }else{
     $m_trans_id = $paymentmode;
     $bank=$cbank;
   }
   $f_code="Ok";
   if($eventname && $ptype && $bank && $m_trans_id && $remarks && $amount && $studentId){
       /* Parent Data Start */
       $parentData = sqlgetresult('SELECT * FROM getparentdata WHERE "studentId" = \''.$studentId.'\' LIMIT 1',true);
       $parent_id = isset($parentData[0]['id'])?$parentData[0]['id']:0;
       $academicYear = trim($parentData[0]['academic_yr']);
       $term = trim($parentData[0]['term']);
       $section = trim($parentData[0]['section']); 
       $class = trim($parentData[0]['class']); 
       $stream = trim($parentData[0]['stream']);
       $sid=trim($parentData[0]['sid']);
       $feetypeid=$eventname;
       $dataft=getNonFeebyid($feetypeid);
       if($dataft['feeGroup']){
        $feeconfigid=$dataft['feeGroup'];
       }else{
         $feeconfigid=0;
       }
       /* Parent Data End */
       $challanNo = "EVENT-".$eventname;
       $payment_id = sqlgetresult('INSERT INTO tbl_nonfee_payments ("studentId","academicYear","term","stream","classList","section","sid","feeconfigid","feetypeid","paymode") VALUES (\''.$studentId.'\',\''.$academicYear.'\',\''.$term.'\',\''.$stream.'\',\''.$class.'\',\''.$section.'\',\''.$sid.'\',\''.$feeconfigid.'\',\''.$feetypeid.'\',\''.$paymentmode.'\') RETURNING id');
        $payment_insert_id = isset($payment_id['id'])?$payment_id['id']:0;
        if($payment_insert_id > 0){
            $desc=substr($remarks,0,50);
            $refnum="CNF-".$payment_insert_id;
            $paymentData = sqlgetresult("SELECT * FROM nonfeechallanpaymententry('".$parent_id."','".$studentId."','$amount','$f_code','$m_trans_id','".json_encode($_POST)."','".$desc."','$createdOn','".$updatedby."','".$payment_insert_id."') ");
            if($paymentData['nonfeechallanpaymententry']){
                $updatePaymentTable = sqlgetresult('UPDATE tbl_nonfee_payments SET "challanNo" = \''.$challanNo.'\',"transNum" = \''.$refnum.'\' WHERE "id" = \''.$paymentData['nonfeechallanpaymententry'].'\' ');                
                createCFPDF($paymentData['nonfeechallanpaymententry'],$studentId);
                $_SESSION['successcheque'] = "<p class='success-msg'>Payment Completed Successfully.</p>";
            }else{
              $_SESSION['errorcheque'] = "<p class='error-msg'>Something gone wrong. Please try again later.</p>";
            }
        }else{
          $_SESSION['errorcheque'] = "<p class='error-msg'>Payment was not updated in Receipt table.</p>";
        }    
   }else{
    $_SESSION['errorcheque'] = "<p class='error-msg'>Mandatory field missing.</p>";
   }
   header("Location:commonnf.php");
   exit;
}
//*************OTHER FEES (LUNCH, UNIFORM) FILTER- Start************//
if (isset($_POST['filter']) && $_POST['filter'] == "paidnonfee_report")
{
    $_POST = array_map('trim',$_POST);

   // echo "<pre>";
   // print_r($_POST);
    //exit;
    $classselect = isset($_POST['classselect'])?$_POST['classselect']:"";
    $streamselect = isset($_POST['streamselect'])?$_POST['streamselect']:"";
    $yearselect = isset($_POST['yearselect'])?$_POST['yearselect']:"";
    $semesterselect = isset($_POST['semesterselect'])?$_POST['semesterselect']:"";
    $stid = isset($_POST['stid'])?$_POST['stid']:"";
    $feetypes = isset($_POST['feetypes'])?$_POST['feetypes']:"";
    $type = isset($_POST['type'])?$_POST['type']:1;
    $from = isset($_POST['from'])?$_POST['from']:"";
    $to = isset($_POST['to'])?$_POST['to']:"";
    $chlantype = isset($_POST['chlantype'])?$_POST['chlantype']:"";
    $selected_feetypes=[];
    $squery=[];
    $qtxt="";
    if($feetypes){
       $selected_feetypes = explode(',', $feetypes);
       if(count($selected_feetypes) > 0){
          foreach ($selected_feetypes as $value) {
              $squery[]='"feeType"=\''.pg_escape_string($value).'\'';
          }
          $qtxt=implode(" OR ", $squery);
       }
    }
    


    $whereClauses = array();
    $where = ''; 
    $_SESSION['paidnonfeereportquery']='';

    $isStudIdSearch=false;

    if(!empty($stid)){
        $whereClauses[] ='"studentId"=\''.pg_escape_string($stid).'\' ' ;
        $isStudIdSearch=true;
    }

    if($qtxt){
        $whereClauses[] ='('.$qtxt.')' ;
    }
    
    $whereClauses[] ='"challanStatus"=\'1\'' ;

    if (!empty($chlantype)){
        if($chlantype=='with'){
            $whereClauses[] ='visible=\'1\' ' ;
        }
        if($chlantype=='without'){
            $whereClauses[] ='visible=\'0\' ' ;
        }  
    }

    if (!empty($yearselect)){
        $whereClauses[] ='"clacadamicyrid"=\''.pg_escape_string($yearselect).'\' ' ;
    }

    if (!empty($semesterselect)) {
      $whereClauses[] ="term='".pg_escape_string ($semesterselect)."'"; 
    }

    if (!empty($classselect)) {
        $whereClauses[] ='"clid"=\''.pg_escape_string($classselect).'\' ' ;
    }

    if (!empty($streamselect)) {
      $whereClauses[] ="clstreamid='".pg_escape_string ($streamselect)."'"; 
    }


    if (!empty($from) && !empty($to))
    {
      $whereClauses[] = '(DATE("paid_date") BETWEEN \'' . pg_escape_string ($from) . '\'  AND  \'' . pg_escape_string ($to) . '\' OR DATE("updatedOn") BETWEEN \'' . pg_escape_string ($from) . '\'  AND  \'' . pg_escape_string ($to) . '\')';

    }
    
    if (count($whereClauses) > 0) 
    { 
      $where = ' WHERE '.implode(' AND ',$whereClauses); 
    } 

    if($isStudIdSearch){
        $sql = ('SELECT *,DATE("updatedOn") AS updated FROM nonfeechallanpaidlatest '.$where.' ORDER BY cid DESC');
    }else{
        $sql = ('SELECT *,DATE("updatedOn") AS updated FROM nonfeechallanpaidmod '.$where.' ORDER BY cid DESC');
    }
    $_SESSION['paidnonfeereportquery']=$sql; 
    $res = sqlgetresult($sql, true);
    //print_r($res);
    //exit;
    $challanData = array();
    $challanNo = '';
    $outputdata = array();
    $paidchallan = array();
    if (count($res) > 0)
    {
        $aYears=getacadamicYears();
        $classes=getClassses();
        $streams=getStreams();
        $students=getStudentDetails();
        //echo "<pre>";
        //print_r($dbdata);
        //exit;
        foreach ($res as $k => $data)
        {
            $challanno=trim($data['challanNo']);
            //$unique_id = $challanno;
            $unique_id = $data['cid'];
            $paidstatus=$data['challanStatus'];
            $feename=$data['feename'];
            $studentId=trim($data['studentId']);
            $total=trim($data['total']);
            $challanData[$unique_id]['id'] = $data['id'];
            if($isStudIdSearch){
                $challanData[$unique_id]['studentId'] = $studentId;
                $challanData[$unique_id]['studentName'] = $students[$studentId]['studentName'];
            }else{
                if($students[$studentId]['studentId']){
                    $challanData[$unique_id]['studentId'] = $students[$studentId]['studentId'];
                    $challanData[$unique_id]['studentName'] = $students[$studentId]['studentName'];
                }else{
                    $oldStudentId=getStudentDetailsByOldId($studentId);
                    if($oldStudentId){
                        $challanData[$unique_id]['studentId'] = $students[$oldStudentId]['studentId'];
                        $challanData[$unique_id]['studentName'] = $students[$oldStudentId]['studentName'];
                    }else{
                        $challanData[$unique_id]['studentId'] = "";
                        $challanData[$unique_id]['studentName'] = "";
                    }
                }
            }
            
            
            $challanData[$unique_id]['academic_yr'] = $aYears[$data['clacadamicyrid']];
            $challanData[$unique_id]['streamname'] = $streams[$data['clstreamid']];
            $challanData[$unique_id]['class_list'] = $classes[$data['clid']];
            $challanData[$unique_id]['term'] = $data['term'];
            $challanData[$unique_id]['feename'] = $feename;
            $challanData[$unique_id]['amount']= $total;
            if($data['paid_date']){
                $challanData[$unique_id]['paid_date'] = $data['paid_date'];
            }else{
                if($data['updatedOn']){
                    $challanData[$unique_id]['paid_date'] = $data['updated'];
                }else{
                   $challanData[$unique_id]['paid_date'] = "";  
                }
            }

            $date = date('dmY', strtotime($data['updatedOn']));
            $pdfpath = BASEURL.'receipts/'.$date.'/'.str_replace('/', '', $challanno).'.pdf';
            $pdfhtml = '<a href="'.$pdfpath.'" target="_blank" title="Download Receipt"><i class="fa fa-download"></i></a>';
           
            $challanData[$unique_id]['challanNo'] = $challanno;
            $challanData[$unique_id]['pdfhtml'] = $pdfhtml;
            
        }
        foreach($challanData AS $challan){
            $paidchallan[]= $challan;
        }

        $outputdata = $paidchallan;
    }
    else
    {
        $outputdata = array();
    }
    echo json_encode($outputdata);

}


if(isset($_GET['paidnonfeereportexcel']) && $_GET['paidnonfeereportexcel']=='exportexcel'){
   $sql = $_SESSION['paidnonfeereportquery'];
    $sqlrun = sqlgetresult($sql,true);
    if (count($sqlrun) > 0)
    {
        $aYears=getacadamicYears();
        $classes=getClassses();
        $streams=getStreams();
        $students=getStudentDetails();
        foreach($sqlrun as $k => $data){
            $challanno=trim($data['challanNo']);
            $unique_id = $data['cid'];
            $paidstatus=$data['challanStatus'];
            $feename=$data['feename'];
            $studentId=trim($data['studentId']);
             $total=trim($data['total']);
            if($isStudIdSearch){
                $challanData[$unique_id]['Student Id'] = $studentId;
                $challanData[$unique_id]['Student Name'] = $students[$studentId]['studentName'];
            }else{
                if($students[$studentId]['studentId']){
                    $challanData[$unique_id]['Student Id'] = $students[$studentId]['studentId'];
                    $challanData[$unique_id]['Student Name'] = $students[$studentId]['studentName'];
                }else{
                    $oldStudentId=getStudentDetailsByOldId($studentId);
                    if($oldStudentId){
                        $challanData[$unique_id]['Student Id'] = $students[$oldStudentId]['studentId'];
                        $challanData[$unique_id]['Student Name'] = $students[$oldStudentId]['studentName'];
                    }else{
                        $challanData[$unique_id]['Student Id'] = "";
                        $challanData[$unique_id]['Student Name'] = "";
                    }
                }
            }
            
            
            $challanData[$unique_id]['Academic Year'] = $aYears[$data['clacadamicyrid']];
            $challanData[$unique_id]['Stream'] = $streams[$data['clstreamid']];
            $challanData[$unique_id]['Class'] = $classes[$data['clid']];
            $challanData[$unique_id]['Term'] = $data['term'];
            $challanData[$unique_id]['Event'] = $feename;
            $challanData[$unique_id]['Amount']= $total;
            if($data['paid_date']){
                $challanData[$unique_id]['Paid Date'] = $data['paid_date'];
            }else{
                if($data['updatedOn']){
                    $challanData[$unique_id]['Paid Date'] = $data['updated'];
                }else{
                    $challanData[$unique_id]['Paid Date'] = "";
                }
            }
           
            $challanData[$unique_id]['Challan No'] = $challanno;
            
            
        }
    }
    // print_r($result_data);exit;
    foreach ($challanData as $k => $v) {
        $keys = array();
        foreach ($v as $field_code => $field_val)
        {       
            // if($field_val != '') {
                $keys[]=$field_code;                
            // }       
        }       
        $columns = $keys;
    }
    exportData($challanData, 'Non-fee Paid Challans Report', $columns);
}


if (isset($_POST['filter']) && $_POST['filter'] == "commonfee_report")
{
    $_POST = array_map('trim',$_POST);
    $classselect = isset($_POST['classselect'])?$_POST['classselect']:"";
    $streamselect = isset($_POST['streamselect'])?$_POST['streamselect']:"";
    $yearselect = isset($_POST['yearselect'])?$_POST['yearselect']:"";
    $semesterselect = isset($_POST['semesterselect'])?$_POST['semesterselect']:"";
    $stid = isset($_POST['stid'])?$_POST['stid']:"";
    $feetypes = isset($_POST['feetypes'])?$_POST['feetypes']:"";
    $type = isset($_POST['type'])?$_POST['type']:1;
    $from = isset($_POST['from'])?$_POST['from']:"";
    $to = isset($_POST['to'])?$_POST['to']:"";
    $selected_feetypes=[];
    $squery=[];
    $qtxt="";
    if($feetypes){
       $selected_feetypes = explode(',', $feetypes);
       if(count($selected_feetypes) > 0){
          foreach ($selected_feetypes as $value) {
            $modvalue="EVENT-".$value;
            $squery[]='"challanNo" ILIKE \'%'.pg_escape_string($modvalue).'%\'';
          }
          $qtxt=implode(" OR ", $squery);
       }
    }
    
    $whereClauses = array();
    $where = ''; 
    $_SESSION['commonfeereportquery']='';

    $isStudIdSearch=false;

    if(!empty($stid)){
        $whereClauses[] ='"studentId"=\''.pg_escape_string($stid).'\' ' ;
        $isStudIdSearch=true;
    }

    if($qtxt){
        $whereClauses[] ='('.$qtxt.')' ;
    }

    if (!empty($yearselect)){
        $whereClauses[] ='"academic_yr"=\''.pg_escape_string($yearselect).'\' ' ;
    }

    if (!empty($semesterselect)) {
      $whereClauses[] ="term='".pg_escape_string ($semesterselect)."'"; 
    }

    if (!empty($classselect)) {
        $whereClauses[] ='"class"=\''.pg_escape_string($classselect).'\' ' ;
    }

    if (!empty($streamselect)) {
      $whereClauses[] ="stream='".pg_escape_string ($streamselect)."'"; 
    }


    if (!empty($from) && !empty($to))
    {
      $whereClauses[] = '(DATE("transDate") BETWEEN \'' . pg_escape_string ($from) . '\'  AND  \'' . pg_escape_string ($to) . '\' OR DATE("updatedOn") BETWEEN \'' . pg_escape_string ($from) . '\'  AND  \'' . pg_escape_string ($to) . '\')';

    }
    
    if (count($whereClauses) > 0) 
    { 
      $where = ' WHERE '.implode(' AND ',$whereClauses); 
    } 

    if($isStudIdSearch){
        $sql = ('SELECT * FROM paidcommonfeemod '.$where.' ORDER BY id DESC');
    }else{
        $sql = ('SELECT * FROM paidcommonfee '.$where.' ORDER BY id DESC');
    }

    $_SESSION['commonfeereportquery']=$sql; 
    $res = sqlgetresult($sql, true);
    $challanData = array();
    $challanNo = '';
    $outputdata = array();
    $paidchallan = array();
    if (count($res) > 0)
    {
        $aYears=getacadamicYears();
        $classes=getClassses();
        $streams=getStreams();
        $students=getStudentDetails();
        foreach ($res as $k => $data)
        {
            $challanno=trim($data['challanNo']);
            $unique_id = $data['id'];
            $eventId = explode('-', $challanno)[1]; 
            $feename=getNonfeeTypeById($eventId);
            $studentId=trim($data['studentId']);
            $total=trim($data['total']);
            $challanData[$unique_id]['id'] = $data['id'];
            if($isStudIdSearch){
                $challanData[$unique_id]['studentId'] = $studentId;
                $challanData[$unique_id]['studentName'] = $students[$studentId]['studentName'];
            }else{
                if($students[$studentId]['studentId']){
                    $challanData[$unique_id]['studentId'] = $students[$studentId]['studentId'];
                    $challanData[$unique_id]['studentName'] = $students[$studentId]['studentName'];
                }else{
                    $oldStudentId=getStudentDetailsByOldId($studentId);
                    if($oldStudentId){
                        $challanData[$unique_id]['studentId'] = $students[$oldStudentId]['studentId'];
                        $challanData[$unique_id]['studentName'] = $students[$oldStudentId]['studentName'];
                    }else{
                        $challanData[$unique_id]['studentId'] = $studentId;
                        $challanData[$unique_id]['studentName'] = "";
                    }
                }
            }
            
            $challanData[$unique_id]['academic_yr'] = ($data['npacademic_yr'])?$aYears[trim($data['npacademic_yr'])]:$aYears[trim($data['academic_yr'])];
            $challanData[$unique_id]['streamname'] = ($data['npstream'])?$streams[trim($data['npstream'])]:$streams[trim($data['stream'])];
            $challanData[$unique_id]['class_list'] = ($data['npclass'])?$classes[trim($data['npclass'])]:$classes[trim($data['class'])];
            $challanData[$unique_id]['term'] = ($data['npterm'])?trim($data['npterm']):trim($data['term']);
            $challanData[$unique_id]['feename'] = $feename;
            $challanData[$unique_id]['amount']= $total;
            if($data['transDate']){
                $challanData[$unique_id]['paid_date'] = $data['transDate'];
            }else{
                if($data['updatedOn']){
                    $challanData[$unique_id]['paid_date'] = $data['updated'];
                }else{
                   $challanData[$unique_id]['paid_date'] = "";  
                }
            }

            //$date = date('dmY', strtotime($data['transDate']));
            $date = date("dmY", strtotime($data['createdOn']));
            $receiptname = $studentId.$challanno;
            $pdfpath = BASEURL.'receipts/'.$date.'/'.str_replace('/', '', $receiptname).'.pdf';
            $pdfhtml = '<a href="'.$pdfpath.'" target="_blank" title="Download Receipt"><i class="fa fa-download"></i></a>';
           
            $challanData[$unique_id]['challanNo'] = $challanno;
            $challanData[$unique_id]['pdfhtml'] = $pdfhtml;
            
        }
        foreach($challanData AS $challan){
            $paidchallan[]= $challan;
        }

        $outputdata = $paidchallan;
    }
    else
    {
        $outputdata = array();
    }
    echo json_encode($outputdata);
}

if(isset($_GET['commonfeereportexcel']) && $_GET['commonfeereportexcel']=='exportexcel'){
   $sql = $_SESSION['commonfeereportquery'];
    $sqlrun = sqlgetresult($sql,true);
    if (count($sqlrun) > 0)
    {
        $aYears=getacadamicYears();
        $classes=getClassses();
        $streams=getStreams();
        $students=getStudentDetails();
        foreach($sqlrun as $k => $data){
            $challanno=trim($data['challanNo']);
            $unique_id = $data['id'];
            $eventId = explode('-', $challanno)[1]; 
            $feename=getNonfeeTypeById($eventId);
            $studentId=trim($data['studentId']);
             $total=trim($data['total']);
            if($isStudIdSearch){
                $challanData[$unique_id]['Student Id'] = $studentId;
                $challanData[$unique_id]['Student Name'] = $students[$studentId]['studentName'];
            }else{
                if($students[$studentId]['studentId']){
                    $challanData[$unique_id]['Student Id'] = $students[$studentId]['studentId'];
                    $challanData[$unique_id]['Student Name'] = $students[$studentId]['studentName'];
                }else{
                    $oldStudentId=getStudentDetailsByOldId($studentId);
                    if($oldStudentId){
                        $challanData[$unique_id]['Student Id'] = $students[$oldStudentId]['studentId'];
                        $challanData[$unique_id]['Student Name'] = $students[$oldStudentId]['studentName'];
                    }else{
                        $challanData[$unique_id]['Student Id'] = "";
                        $challanData[$unique_id]['Student Name'] = "";
                    }
                }
            }
            
            $challanData[$unique_id]['Academic Year'] = ($data['npacademic_yr'])?$aYears[trim($data['npacademic_yr'])]:$aYears[trim($data['academic_yr'])];
            $challanData[$unique_id]['Stream'] = ($data['npstream'])?$streams[trim($data['npstream'])]:$streams[trim($data['stream'])];
            $challanData[$unique_id]['Class'] = ($data['npclass'])?$classes[trim($data['npclass'])]:$classes[trim($data['class'])];
            $challanData[$unique_id]['Term'] = ($data['npterm'])?trim($data['npterm']):trim($data['term']);
            $challanData[$unique_id]['Section'] = ($data['npsection'])?trim($data['npsection']):trim($data['section']);
            $challanData[$unique_id]['Event'] = $feename;
            $challanData[$unique_id]['Amount']= $total;
            if($data['transDate']){
                $challanData[$unique_id]['Paid Date'] = $data['transDate'];
            }else{
                if($data['updatedOn']){
                    $challanData[$unique_id]['Paid Date'] = $data['updated'];
                }else{
                    $challanData[$unique_id]['Paid Date'] = "";
                }
            }
           
            $challanData[$unique_id]['Challan No'] = $challanno;          
        }
    }
    // print_r($result_data);exit;
    foreach ($challanData as $k => $v) {
        $keys = array();
        foreach ($v as $field_code => $field_val)
        {       
            // if($field_val != '') {
                $keys[]=$field_code;                
            // }       
        }       
        $columns = $keys;
    }
    exportData($challanData, 'Common-Fee Paid Report', $columns);
}


if (isset($_POST['filter']) && $_POST['filter'] == "nfwcfltpaidreport")
{

    $classselect = isset($_POST['classselect'])?trim($_POST['classselect']):"";
    $streamselect = isset($_POST['streamselect'])?trim($_POST['streamselect']):"";
    $semesterselect = isset($_POST['semesterselect'])?trim($_POST['semesterselect']):"";
    $academicyr = isset($_POST['yearselect'])?trim($_POST['yearselect']):"";
    $from = isset($_POST['from'])?trim($_POST['from']):"";
    $to = isset($_POST['to'])?trim($_POST['to']):"";
    $status = isset($_POST['status'])?trim($_POST['status']):"";
    $feetypes = isset($_POST['feetypes'])?trim($_POST['feetypes']):"";

    $selected_feetypes=[];
    $squery=[];
    $qtxt="";
    if($feetypes){
       $selected_feetypes = explode(',', $feetypes);
       if(count($selected_feetypes) > 0){
          foreach ($selected_feetypes as $value) {
              $squery[]='"feeType"=\''.pg_escape_string($value).'\'';
          }
          $qtxt=implode(" OR ", $squery);
       }
    }

    $where=[];
    //$where[] = '("challanStatus" = \'1\' OR "challanStatus" = \'2\') ';
    if (!empty($status))
    {
        $where[] = '"transStatus" =\'' . $status . '\' ';
    }
    if (!empty($classselect))
    {
        $where[] = '"classList"=\'' . $classselect . '\' ';
    }
    if (!empty($streamselect))
    {
        $where[] = '"stream"=\'' . $streamselect . '\' ';
    }
    if (!empty($semesterselect))
    {
        $where[] = 'term=\'' . $semesterselect . '\' ';
    }
    if (!empty($academicyr))
    {
        $where[] = '"academicYear"=\'' . $academicyr . '\' ';
    }

    if (!empty($from) && !empty($to))
    {
        $where[] = 'DATE("transDate") BETWEEN \'' . $from . '\'  AND  \'' . $to . '\'';
    }
    if($qtxt){
        $where[] ='('.$qtxt.')' ;
    }
    
    $wherecond="";
    if(count($where) > 0){
        $wherecond="WHERE  ".implode(" AND ", $where);
    }
    $sql = 'SELECT * FROM nfwcppreport '.$wherecond.'';

    $_SESSION['nfwcpaidreportquery']=$sql; 
    $res = sqlgetresult($sql, true);
    $fdata=[];
    foreach($res AS $key => $output){
        $refnum=$output['id'];
        $fdata[$refnum]['transNum']=$output['transNum'];
        $fdata[$refnum]['studentId']=$output['studentId'];
        $fdata[$refnum]['studentName']=trim($output['studentName']);
        $fdata[$refnum]['streamname']=trim($output['streamname']);
        $fdata[$refnum]['class_list']=trim($output['class_list']);
        $fdata[$refnum]['academic_yr']=trim($output['academic_yr']);
        $fdata[$refnum]['term']=$output['term'];
        $fdata[$refnum]['receivedamount']=$output['receivedamount'];
        $fdata[$refnum]['transDate']=$output['transDate'];
        $fdata[$refnum]['transStatus']=$output['transStatus'];
        $fdata[$refnum]['feetype']=trim($output['feename']);
     
        $fdata[$refnum]['challanNo'] = $output['parchallanno'];  
        $fdata[$refnum]['refchallanNo'] = ($output['refchallanNo'])?$output['refchallanNo']:$output['parchallanno'];  
    }
    $outputdata=[]; 
    foreach($fdata AS $data){
        $outputdata[]= $data;
    }
    echo json_encode($outputdata);
}


if(isset($_GET['nfwcpaidreport']) && $_GET['nfwcpaidreport']=='exportexcel'){
    $sql = $_SESSION['nfwcpaidreportquery'];
    $sqlrun = sqlgetresult($sql,true);
    foreach($sqlrun AS $output){
        $key=$output['id'];
        $result_data[$key]['Ref Number']=$output['transNum'];
        $result_data[$key]['Student Id']=$output['studentId'];
        $result_data[$key]['Student Name']=trim($output['studentName']);
        if($output['type']=='nopartial'){
           $result_data[$key]['Challan Number'] = $output['parchallanno'];
        }else{
           $result_data[$key]['Challan Number'] = ($output['refchallanNo'])?$output['refchallanNo']:$output['parchallanno'];  
        }
        $result_data[$key]['Stream']=trim($output['streamname']);
        $result_data[$key]['Class']=trim($output['class_list']);
        $result_data[$key]['Academic Year']=$output['academic_yr'];
        $result_data[$key]['Term']=$output['term'];
        $result_data[$key]['Fee Type']=trim($output['feename']);
        $result_data[$key]['Total']=$output['receivedamount'];
        $result_data[$key]['Created Date']=$output['transDate'];
        $result_data[$key]['Transaction Status']=$output['transStatus'];
    }
    foreach ($result_data as $k => $v) {
        $keys = array();
        foreach ($v as $field_code => $field_val)
        {       
            $keys[]=$field_code;                
        }       
        $columns = $keys;
    }
    exportData($result_data, 'NON-Fee With Challan - Paid Report', $columns);
}

/* Non Fee with challan - Manual Receipt Generation */
if (isset($_POST['nfwchallanpay']) && $_POST['nfwchallanpay'] == "confirm")
{
   $updatedby = $_SESSION['myadmin']['adminid'];
   $id=isset($_REQUEST['id'])?trim($_REQUEST['id']):"";
   $ptype=isset($_REQUEST['ptype'])?trim($_REQUEST['ptype']):"";
   $bank=isset($_REQUEST['bank'])?trim($_REQUEST['bank']):"";
   $cbank=isset($_REQUEST['cbank'])?trim($_REQUEST['cbank']):"";
   $paymentmode=isset($_REQUEST['paymentmode'])?trim($_REQUEST['paymentmode']):"";
   $paymentmodetrans=isset($_REQUEST['paymentmodetrans'])?trim($_REQUEST['paymentmodetrans']):"";
   $createdOn=isset($_REQUEST['paiddate'])?trim($_REQUEST['paiddate']):date("Y-m-d");
   $remarks=isset($_REQUEST['remarks'])?trim($_REQUEST['remarks']):"NA";
   if($ptype=="Online"){
     $m_trans_id = $paymentmodetrans;
     $bank='Atom';
   }else{
     $m_trans_id = $paymentmode;
     $bank=$cbank;
   }
   $f_code="Ok";
   $payop = ($_POST['paynfw'])?trim($_POST['paynfw']):"nopartial";
   $amount = ($_POST['amount'])?trim($_POST['amount']):0;
   if($id && $ptype && $bank && $m_trans_id && $remarks){
    $res = sqlgetresult('SELECT * FROM nonfeechallandata WHERE cid=\'' . $id . '\' ');
    $studentId=trim($res['studentId']);
    $parent_id=trim($res['parentId']);
    //$amount=trim($res['total']);
    $status=trim($res['challanStatus']);
    $challanNo=trim($res['challanNo']);
    $visible=trim($res['visible']);
    $s_id=trim($res['sid']);
    if($status=="0" || $status=="2"){
        $cdata=toGetNFWChallanAmount($challanNo);
        if($payop =='minimum'){
            $amtval=$cdata['m_due'];
        }else{
            $amtval=$cdata['n_due'];
        }
        if($amount==$amtval){
            $run1=sqlgetresult("SELECT * FROM createnfwcpayment('$s_id','$challanNo','$updatedby',NULL,'$amount','$payop',NULL)",true);
            $lastinsert_id = isset($run1[0]['createnfwcpayment'])?$run1[0]['createnfwcpayment']:""; 
            if(!empty($lastinsert_id)){
                $eventname="NFWC".$lastinsert_id;
                $paymentData = sqlgetresult("SELECT * FROM partialnfwcentryadm('$f_code','".json_encode($_POST)."','".$remarks."','".$m_trans_id."','$createdOn','".$updatedby."','".$lastinsert_id."','$eventname','Offline') ");
                if($paymentData['partialnfwcentryadm']) {
                    $payment_id=toProcessNFWC($challanNo, $updatedby, $studentId, $lastinsert_id, $amount, $createdOn);
                    if($payment_id){
                        $_SESSION['successcheque'] = "<p class='success-msg'>Payment Completed Successfully.</p>";
                    }else{
                      $_SESSION['errorcheque'] = "<p class='error-msg'>The receipt has not been generated. Please confirm with the accounts team before trying again.</p>";
                    }
                }else{
                   $_SESSION['errorcheque'] = "<p class='error-msg'>Ref number is not updated in receipt table.</p>"; 
                }
            }else{
              $_SESSION['errorcheque'] = "<p class='error-msg'>Payment is not updated in receipt table.</p>";
            }
        }else{
           $_SESSION['errorcheque'] = "<p class='error-msg'> Amount should be matched with the challan value.</p>";
        }
    }else{
        $_SESSION['errorcheque'] = "<p class='error-msg'>Already paid!</p>";
    }
   }else{
    $_SESSION['errorcheque'] = "<p class='error-msg'>Mandatory field missing.</p>";
   }
    header("Location:addnfwcpayment.php?id=".$id);
    exit;
}
/* Non Fee with challan - Manual Receipt Generation */
if (isset($_POST['paynfwc_adm']) && $_POST['paynfwc_adm'] == "paynfwcadm")
{
    $updatedby = $_SESSION['myadmin']['adminid'];
    $id=isset($_REQUEST['plogid'])?trim($_REQUEST['plogid']):"";
    $tstatus=isset($_REQUEST['tstatus'])?trim($_REQUEST['tstatus']):"";
    $m_trans_id=isset($_REQUEST['paymentmodetrans'])?trim($_REQUEST['paymentmodetrans']):"NA";
    $createdOn=isset($_REQUEST['paiddate'])?trim($_REQUEST['paiddate']):date("Y-m-d");
    $remarks=isset($_REQUEST['remarks'])?trim($_REQUEST['remarks']):"NA";
    $challanNo=isset($_REQUEST['challanNo'])?trim($_REQUEST['challanNo']):"";
    $amount=isset($_REQUEST['amount'])?trim($_REQUEST['amount']):"";
    $studentId=isset($_REQUEST['studentId'])?trim($_REQUEST['studentId']):"";
    $f_code="Ok";
    if($id && $updatedby && $challanNo && $amount && $studentId){
        $paymentData = sqlgetresult("SELECT * FROM nfwcforceupdateadm('$f_code','".$remarks."','".$m_trans_id."','$createdOn','".$updatedby."','".$id."') ");
        if($paymentData['nfwcforceupdateadm']) {
            $payment_id=toProcessNFWC($challanNo, $updatedby, $studentId, $id, $amount, $createdOn);
            if($payment_id){
                $_SESSION['successcheque'] = "<p class='success-msg'>Payment Completed Successfully.</p>";
            }else{
              $_SESSION['errorcheque'] = "<p class='error-msg'>The receipt has not been generated. Please confirm with the accounts team before trying again.</p>";
            }
        }else{
           $_SESSION['errorcheque'] = "<p class='error-msg'>Not updated in receipt table.</p>"; 
        }
    }else{
      $_SESSION['errorcheque'] = "<p class='error-msg'>Mandatory fields are missing.</p>"; 
    }
    header("Location:nfwcreport.php");
    exit;
}
/*#### Create Transport Challan From Paid Challans ####*/
if (isset($_POST["submit"]) && $_POST["submit"] == "createtransportchallanfrmpaid")
{   
    if (!empty($_POST['checkme']))
    {
        $selectedchallans = [];
        foreach ($_POST['checkme'] as $selected)
        {
            // echo $selected."</br>";s
            array_push($selectedchallans, $selected);
        }
        $_SESSION['selectedtransfeechallans'] = $selectedchallans;
        header('location:createtransportfeefrmpaid.php');
    }
    echo json_encode($selectedchallans);
}

/* Create transport challans from paid */
if (isset($_POST['showtransfeechallanFrmPaid']) && $_POST['showtransfeechallanFrmPaid'] == "showtransfeechallanFrmPaid") {

    unset($_SESSION['createdchallanids']);
    $class = isset($_POST['class_list'])?trim($_POST['class_list']):"";
    $term = isset($_POST['semester'])?trim($_POST['semester']):"";
    $feetypes = isset($_POST['selected_feetypes'])?trim($_POST['selected_feetypes']):"";
    $createdby = isset($_SESSION['myadmin']['adminid'])?trim($_SESSION['myadmin']['adminid']):"";
    $stream = isset($_POST['stream'])?trim($_POST['stream']):"";
    $remarks = isset($_POST['remarks'])?trim($_POST['remarks']):"";
    $duedate = isset($_POST['duedate'])?trim($_POST['duedate']):"";
    //$academic = 7;
    $streamName = getStreambyId($stream);    
    $id = isset($_POST['studentId'])?trim($_POST['studentId']):"";
    $studentId=$id;
    $name = isset($_POST['studentName'])?trim($_POST['studentName']):"";
    $selectedData = array();
    $feeData = explode(',', $feetypes);
    $challanSuffix="TF-";

    /* Active Academic Year*/
    $academicId = isset($_POST['academicId'])?trim($_POST['academicId']):"";
    $type = isset($_POST['type'])?trim($_POST['type']):"";
    //$feegrp = getFeeGroupbyName('NON-FEE');
    $streamName = getStreambyId($stream);
    /* Current Semester */
    //$cur_term=getCurrentTerm();
    $cur_term=isset($_POST['semester'])?trim($_POST['semester']):"";

    if($type == 'single'){
       $groupdata=array();
       $feetypedata = sqlgetresult('SELECT * FROM getfeetypedata WHERE class=\'' . $class . '\' AND semester=\'' . $cur_term . '\' AND stream = \'' . ($stream) . '\' AND "academicYear" = \'' . ($academicId) . '\' ', true);    
        foreach ($feeData as $k => $v)
        {
            $v=trim($v);
            foreach ($feetypedata as $val)
            {
                $gid=trim($val['id']);
                if ( $v == $gid)
                {                
                    $gfeeGroup=trim($val['feeGroup']);
                    $group = getFeeGroupbyId($gfeeGroup);
                    $groupdata[$gfeeGroup][$gid][] = trim($val['amount']);
                    $groupdata[$gfeeGroup][$gid][] = trim($val['feename']);
                    $groupdata[$gfeeGroup][$gid][] = trim($gfeeGroup);
                    $groupdata[$gfeeGroup][$gid][] = trim($group);
                }
            }
        }
    
        if (count($groupdata) > 0)
        {
            /* Call Generate Challan Sequnce Number */
            $challanNo=$challanSuffix.toGenerateChallanSequnceNumber($streamName);
            $exists_fee_type=array();
            $challanData="";
            $feedata=array();
            foreach ($groupdata as $grp => $data)
            {     
                foreach ($data as $k => $val)
                {       
                    $amt=$val[0];
                    $ftype=$val[2];
                    $fname=$val[1];
                    $sql = "SELECT * FROM createtempchallantransportfee('$challanNo','$studentId','$createdby','".$k."','$stream','$class','$cur_term','$name','".$amt."','$remarks','$duedate','".$ftype."','$academicId')";    
                    //echo $sql;
                    //exit;
                    $result = sqlgetresult($sql);  
                   if ($result['createtempchallantransportfee'] > 0) {
                        $challanData = sqlgetresult('SELECT * FROM tempChallan WHERE "challanNo"=\'' . $challanNo . '\' ORDER BY id ASC LIMIT 1');
                        $group_name=$val[3];
                        $group_id=$val[2];

                        $feedata[$group_name][$group_id][$k][] = $amt;
                        $feedata[$group_name][$group_id][$k][] = $fname;

                       // sendNotificationToParents($studentId, $_POST['mail_content'],$_POST['sms_content'],  "additionalfeechallan");
                    }else{
                        $exists_fee_type[$k][] = $val[1];
                    }
                }            
            }
            $selectedData['feeData'] = $feedata;
            $selectedData['is_exists']=count($exists_fee_type);
            $selectedData['exists']=$exists_fee_type;
            $selectedData['challanData'] = $challanData;
        } else {
            $selectedData = 'Fee Types empty';
        }
    }else{
        if(isset($_SESSION['selectedtransfeechallans']) && count($_SESSION['selectedtransfeechallans']) > 0 ) {
            $selectedIds  = $_SESSION['selectedtransfeechallans'];
            $createdChallans=[];
            foreach ($selectedIds as $k => $id) {
                $groupdata = array();
                //echo 'SELECT class, term, stream, "academic_yr" AS "academicYear","studentId" FROM tbl_student WHERE "studentId" = \''.trim($id).'\' LIMIT 1 ';
                $studentData = sqlgetresult('SELECT class, term, stream, "academic_yr" AS "academicYear","studentId","studentName" FROM tbl_student WHERE "studentId" = \''.trim($id).'\' LIMIT 1 ');
                $studentData = array_map('trim', $studentData);
                $class = $studentData['class'];
                $term = $studentData['term'];
                $stream = $studentData['stream'];
                //$academicId = $studentData['academicYear'];
                $studentId = $studentData['studentId'];
                $name = $studentData['studentName'];
                $streamName = getStreambyId($stream); 
                // print_r($studentData);
                $feetypedata = sqlgetresult('SELECT * FROM getfeetypedata WHERE class=\''.$class.'\' AND semester=\''.$cur_term.'\' AND stream = \''.$stream.'\' AND "academicYear" = \''.$academicId.'\' ', true); 
               if(count($feetypedata) > 0)
                {
                   foreach ($feeData as $k => $v)
                   {
                    $v=trim($v);
                    foreach ($feetypedata as $val)
                    {
                        $gid=trim($val['id']);
                        if ( $v == $gid)
                        {                
                            $gfeeGroup=trim($val['feeGroup']);
                            $group = getFeeGroupbyId($gfeeGroup);
                            $groupdata[$gfeeGroup][$gid][] = trim($val['amount']);
                            $groupdata[$gfeeGroup][$gid][] = trim($val['feename']);
                            $groupdata[$gfeeGroup][$gid][] = trim($gfeeGroup);
                            $groupdata[$gfeeGroup][$gid][] = trim($group);
                        }
                    }
                   }
               }

                if (count($groupdata) > 0)
                {
                    /* Call Generate Challan Sequnce Number */
                    $challanNo=$challanSuffix.toGenerateChallanSequnceNumber($streamName);
                    $exists_fee_type=array();
                    $challanData="";
                    $feedata=array();
                    foreach ($groupdata as $grp => $data)
                    {     
                        foreach ($data as $k => $val)
                        {       
                            $amt=$val[0];
                            $ftype=$val[2];
                            $fname=$val[1];
                            $sql = "SELECT * FROM createtempchallantransportfee('$challanNo','$studentId','$createdby','".$k."','$stream','$class','$cur_term','$name','".$amt."','$remarks','$duedate','".$ftype."','$academicId')";    
                            //echo $sql;
                            //exit;
                            $result = sqlgetresult($sql);  
                            //print_r($result);
                            //exit;               
                           if ($result['createtempchallantransportfee'] > 0) {
                                array_push($createdChallans, $challanNo);
                                $challanData = sqlgetresult('SELECT * FROM tempChallan WHERE "challanNo"=\'' . $challanNo . '\' ORDER BY id ASC LIMIT 1');
                                $group_name=$val[3];
                                $group_id=$val[2];
                                $feedata[$group_name][$group_id][$k][] = $amt;
                                $feedata[$group_name][$group_id][$k][] = $fname;

                                //sendNotificationToParents($studentId, $_POST['mail_content'],$_POST['sms_content'],  "additionalfeechallan");
                            }else{
                                $exists_fee_type[$k][] = $val[1]."-".$name;
                            }
                        }            
                    }
                    if(count($createdChallans) >0){
                        $_SESSION['createdchallanids']=array_unique($createdChallans);
                        $challanData['challanNo']=$createdChallans[0];
                        //$challanData['challanNo']=implode(",",$_SESSION['createdchallanids']);
                    }
                    $selectedData['feeData'] = $feedata;
                    $selectedData['is_exists']=count($exists_fee_type);
                    $selectedData['exists']=$exists_fee_type;
                    $selectedData['challanData'] = $challanData;
                } else {
                    $selectedData = 'Fee Types empty';
                }
            }
        }
    }
    echo json_encode($selectedData);
}

/* Add Advance Payment */
if (isset($_POST['payadvance']) && $_POST['payadvance'] == "Advance")
{
    $updatedby = $_SESSION['myadmin']['adminid'];
    $studentId = isset($_POST['studentId'])?trim($_POST['studentId']):"";
    $s_id = isset($_POST['s_id'])?trim($_POST['s_id']):"";
    $amount = isset($_POST['txtamt'])?trim($_POST['txtamt']):0;
    $ptype=isset($_REQUEST['ptype'])?trim($_REQUEST['ptype']):"";
    $bank=isset($_REQUEST['bank'])?trim($_REQUEST['bank']):"atom";
   $cbank=isset($_REQUEST['cbank'])?trim($_REQUEST['cbank']):"";
   $paymentmode=isset($_REQUEST['paymentmode'])?trim($_REQUEST['paymentmode']):"";
   $paymentmodetrans=isset($_REQUEST['paymentmodetrans'])?trim($_REQUEST['paymentmodetrans']):"";
   $createdOn=isset($_REQUEST['paiddate'])?trim($_REQUEST['paiddate']):"";
   $remarks=isset($_REQUEST['remarks'])?trim($_REQUEST['remarks']):"NA";
   if($ptype=="Online"){
     $in_transid = $paymentmodetrans;
     $bank=$bank;
     $paymethod=$bank;
   }else{
     $in_transid = $paymentmode;
     $bank=$cbank;
     $paymethod="";
   }
    $in_type=1;
    $in_tstatus='Ok';
    //$in_transid='';
    $in_remarks=$remarks;
    $in_status=0;
    $product='';
    $balance=toGetAvailableBalance($s_id);

    if(!empty($createdOn)){
     $createdOn=date("Y-m-d", strtotime($createdOn));
    }else{
     $createdOn=date("Y-m-d");
    }
    if($amount >= 100){
       $parentData = sqlgetresult('SELECT * FROM getparentdata WHERE "studentId" = \''.$studentId.'\' LIMIT 1',true);
       if(count($parentData) > 0){
        
            $academicYear = $parentData[0]['academic_yr'];
            $term = $parentData[0]['term'];
            $stream = trim($parentData[0]['stream']);
            $class = trim($parentData[0]['class']);  
            $sid = $parentData[0]['sid']; 
            $section = trim($parentData[0]['section']);
            
            $run1=sqlgetresult("SELECT * FROM createadvancetransaction('$s_id','$updatedby',Null,'$amount','$balance','$in_type','$in_tstatus','$in_transid','$in_remarks','$in_status','$class','$academicYear','$stream','$term','$section')",true);
            $lastinsert_id = isset($run1[0]['createadvancetransaction'])?$run1[0]['createadvancetransaction']:""; 
            if(!empty($lastinsert_id)){
                $eventname="ADV".$lastinsert_id;
                $returnCode=$ptype."_".$bank."_".$eventname."_".$amount."_".$in_remarks."_".$createdOn;

                $update=sqlgetresult("SELECT * FROM advpayentryupdateadm('$ptype','$paymethod','$eventname','$returnCode','1','$createdOn','$updatedby','$lastinsert_id')",true);
                $update_id = isset($update[0]['advpayentryupdateadm'])?$update[0]['advpayentryupdateadm']:""; 
                if(!empty($update_id)){
                    $balance=toGetAvailableBalance($sid);
                    $tot=$amount+$balance;
                    $wallet = sqlgetresult("SELECT * FROM addAdvanceAmt('".$sid."','$tot','".$updatedby."')");
                       // createPDF_advance($lastinsert_id);
                        $_SESSION['success_msg'] = "<p class='success-msg'>Payment Completed Successfully.</p>";
                }else{
                    $_SESSION['error_msg'] = "<p class='error-msg'> Unable to update balance amount. Try again later.</p>";
                }
            }else{
            $_SESSION['error_msg'] = "<p class='error-msg'> The payment details are not added. Try again later.</p>";
           }
        }else{
            $_SESSION['error_msg'] = "<p class='error-msg'> Unable to map the parent data. Try again later.</p>";
       }
    }else{
        $_SESSION['error_msg'] = "<p class='error-msg'> Advance amount should be greater than or equal to Rs.100</p>";
   }
   header("Location: addadvancepayment.php");
   exit;
}

if (isset($_POST['filter']) && $_POST['filter'] == "fltadvBalRpt")
{
    $classselect = isset($_POST['classselect'])?trim($_POST['classselect']):"";
    $streamselect = isset($_POST['streamselect'])?trim($_POST['streamselect']):"";
    $sectionselect = isset($_POST['sectionselect'])?trim($_POST['sectionselect']):"";
    $academicyr = isset($_POST['yearselect'])?trim($_POST['yearselect']):"";

    $where=[];
    if (!empty($classselect))
    {
        $where[] = '"class"=\'' . $classselect . '\' ';

    }
    if (!empty($streamselect))
    {
        $where[] = '"stream"=\'' . $streamselect . '\' ';

    }
    if (!empty($sectionselect))
    {
        $where[] = '"section"=\'' . $sectionselect . '\' ';

    }
    if (!empty($academicyr))
    {
        $where[] = '"academic_yr"=\'' . $academicyr . '\' ';
    }
    

    $wherecond="";
    if(count($where) > 0){
        $wherecond="WHERE ".implode(" AND ", $where);
    }
    
    $sql = 'SELECT * FROM advancepayment '.$wherecond;
    $_SESSION['fltadvbalrptquery']=$sql; 
    $output = sqlgetresult($sql, true);
    $result_data=[];  
    foreach($output as $key=>$res){
        $result_data[$key]['studentId']=trim($res['studentId']);
        $result_data[$key]['studentName']=trim($res['studentName']);
        $result_data[$key]['class_list']=trim($res['class_list']);
        $result_data[$key]['streamname']=trim($res['streamname']);
        $result_data[$key]['section']=trim($res['section']);
        $result_data[$key]['academic_yr']=trim($res['ayear']);
        $result_data[$key]['amount']=trim($res['amount']);
    }
    echo json_encode($result_data);

}

if(isset($_GET['advbalexportexcel']) && $_GET['advbalexportexcel']=='exportexcel'){
    $sql = $_SESSION['fltadvbalrptquery'];
    $sqlrun = sqlgetresult($sql,true);
    foreach($sqlrun AS $key => $output){
        $result_data[$key]['Student Id']=trim($output['studentId']);
        $result_data[$key]['Student Name']=trim($output['studentName']);
        $result_data[$key]['Academic Year']=trim($output['ayear']);
        $result_data[$key]['Stream']=trim($output['streamname']);
        $result_data[$key]['Class']=trim($output['class_list']);
        $result_data[$key]['Section']=trim($output['section']);
        $result_data[$key]['Available Amount']=trim($output['amount']);
    }
    foreach ($result_data as $k => $v) {
        $keys = array();
        foreach ($v as $field_code => $field_val)
        {       
            $keys[]=$field_code;                
        }       
        $columns = $keys;
    }
    exportData($result_data, 'Advance Payment - Balance Report', $columns);
}
if (isset($_POST['filter']) && $_POST['filter'] == "fltcreatenonfeechallan")
{
    $_POST = array_map('trim',$_POST);
    $_SESSION['nonfeechallanquery']="";
    $classselect = isset($_POST['classselect'])?$_POST['classselect']:"";
    $streamselect = isset($_POST['streamselect'])?$_POST['streamselect']:"";
    $yearselect = isset($_POST['yearselect'])?$_POST['yearselect']:"";
    $semesterselect = isset($_POST['semesterselect'])?$_POST['semesterselect']:"";
    $sectionselect = isset($_POST['sectionselect'])?$_POST['sectionselect']:"";
    $feetypes = isset($_POST['feetypes'])?$_POST['feetypes']:"";
    $type = isset($_POST['type'])?$_POST['type']:1;
    $from = isset($_POST['from'])?$_POST['from']:"";
    $to = isset($_POST['to'])?$_POST['to']:"";
    $selected_feetypes=[];
    $squery=[];
    $qtxt="";
    if($feetypes){
       $selected_feetypes = explode(',', $feetypes);
       if(count($selected_feetypes) > 0){
          foreach ($selected_feetypes as $value) {
              $squery[]='"feeType"=\''.pg_escape_string($value).'\'';
          }
          $qtxt=implode(" OR ", $squery);
       }
    }
    $where = array();

    if (!empty($classselect))
    {
        $where[] = '"clid" =\'' . pg_escape_string($classselect) . '\' ';
    }
    if (!empty($streamselect))
    {
        $where[] = '"stream"=\'' .pg_escape_string($streamselect). '\' ';
    }
    if (!empty($sectionselect))
    {
        $where[] = '"section"=\'' .pg_escape_string($sectionselect).'\' ';
    }
    if (!empty($yearselect))
    {
        $where[] = '"chalayear"=\'' .pg_escape_string($yearselect).'\' ';
    }
    if (!empty($semesterselect))
    {
        $where[] = '"term"=\'' .pg_escape_string($semesterselect).'\' ';
    }

    if (!empty($from) && !empty($to))
    {
        $where[] = 'DATE("created") BETWEEN \'' . $from . '\'  AND  \'' . $to . '\'';
    }

    if($qtxt){
        $where[] ='('.$qtxt.')' ;
    }
    $wherecond="";
    if (count($where) > 0) 
    { 
      $wherecond = ' WHERE '.implode(' AND ',$where); 
    }

    $sql='SELECT * FROM nonfeechallandata '.$wherecond.' ORDER BY "created" DESC';
    $_SESSION['nonfeechallanquery']=$sql;
    $data =sqlgetresult($sql,true);

    if (count($data) > 0)
    {
        $aYears=getacadamicYears();
        foreach($data AS $output){
            $key=$output['cid'];
            $challanNo=trim($output['challanNo']);
            $result_data[$key]['challanNo']=$challanNo;
            $result_data[$key]['studentId']=$output['studentId'];
            $result_data[$key]['studentName']=trim($output['studentName']);
            $result_data[$key]['streamname']=trim($output['steamname']);
            $result_data[$key]['class_list']=trim($output['class_list']);
            $result_data[$key]['academic_yr']=$aYears[$output['chalayear']];
            $result_data[$key]['term']=$output['term'];
            $result_data[$key]['feename']=$output['feename'];
            $result_data[$key]['amount']=$output['total'];
            if($output['createdOn']){
                $result_data[$key]['cdate']=$output['createdOn'];
            }else{
                $result_data[$key]['cdate']="-";
            }
            if($output['duedate']){
                $result_data[$key]['ddate']=$output['duedate'];
            }else{
                $result_data[$key]['ddate']="-";
            }
            $visible = trim($output['visible']);
            $status=trim($output['challanStatus']);
            if($status=="0" || $status=="2"){
                if($visible == '1'){
                   $add='<a href="addnfwcpayment.php?id='.$key.'" title="Add Payment"><i class="fa fa-plus"></i></a>&nbsp;';
                }else{
                    $add='<a href="nonfeechallans_pay.php?id='.$key.'" title="Add Payment"><i class="fa fa-plus"></i></a>&nbsp;';
                }
            }else{
                $date = date('dmY', strtotime($output['updatedOn']));
                $pdfpath = BASEURL.'receipts/'.$date.'/'.str_replace('/', '', $challanNo).'.pdf';
                $add='<a href="'.$pdfpath.'" target="_blank" title="Download Receipt"><i class="fa fa-download"></i></a>&nbsp;';
            }
            $action=$add.'<a href="editnonfeecreatedchallans.php?id='.$key.'"><i class="fa fa-edit"></i></a>&nbsp;<a href="adminactions.php?action=delete&page=nfc&id='.$key.'"><i class="fa fa-trash-o"></i></a>';
            $result_data[$key]['pdfhtml'] = $action;
        }

        foreach($result_data AS $challan){
            $nonchallan[]= $challan;
        }
        $outputdata = $nonchallan;
    }else{
        $outputdata=[];
    }
    echo json_encode($outputdata);
}
if(isset($_SESSION['nonfeechallanquery']) && isset($_GET['nonfeechallandata']) && $_GET['nonfeechallandata']=='exportexcel'){
    $sql = $_SESSION['nonfeechallanquery'];
    $sqlrun = sqlgetresult($sql,true);
    foreach($sqlrun AS $output){
        $key=$output['cid'];
        $result_data[$key]['Challan No']=$output['challanNo'];
        $result_data[$key]['Student Id']=$output['studentId'];
        $result_data[$key]['Student Name']=trim($output['studentName']);
        $result_data[$key]['Stream']=trim($output['steamname']);
        $result_data[$key]['Class']=trim($output['class_list']);
        $result_data[$key]['Academic Year']=getAcademicyrById($output['chalayear']);
        $result_data[$key]['Term']=$output['term'];
        $result_data[$key]['Fee Type']=$output['feename'];
        $result_data[$key]['Total']=$output['total'];
        if($output['createdOn']){
            $result_data[$key]['Created Date']=$output['createdOn'];
        }else{
            $result_data[$key]['Created Date']="-";
        }
        if($output['duedate']){
            $result_data[$key]['Due Date']=$output['duedate'];
        }else{
            $result_data[$key]['Due Date']="-";
        }
    }
    foreach ($result_data as $k => $v) {
        $keys = array();
        foreach ($v as $field_code => $field_val)
        {       
            $keys[]=$field_code;                
        }       
        $columns = $keys;
    }
    exportData($result_data, 'NON-FEE CHALLANS - Demand Report', $columns);   
}
/*******************Dashboard select start***************************/
/**
 * Class-Wise Report
**/
function toGetChallanDataForDashboard($stream_select, $selectedYear, $ststatus, $term, $from='', $to=''){
    /*$sfs_grp='10';
    $lmois_grp='9,8';
    $lmes_grp='12';*/
    $fgrp='8,9,10,12';
    $feeGroupArray=array(8=>'lmois',9=>'lmois',10=>'sfs',12=>'lmes');
    $where=[];
    $where[]='"studStatus"!=\'Transport.Fee\'';
    $where[]='"feeGroup" IN ('.$fgrp.')';
    
    if(!empty($class_id)){
        $where[] = '"classList"=\'' .pg_escape_string($class_id). '\' ';
    }
    if(!empty($selectedYear)){
        $where[] = '"academicYear"=\'' .pg_escape_string($selectedYear). '\' ';
    }
    if(!empty($stream_select)){
        $where[] = 'stream=\'' .pg_escape_string($stream_select). '\' ';
    }
    if(!empty($term)){
        $where[] = 'term=\'' .pg_escape_string($term). '\' ';
    }
    if(!empty($ststatus)){
        $stchk='APPL';
        if($ststatus=='new'){
            $where[] = '"studentId" ILIKE \'%' .pg_escape_string($stchk). '%\' ';
        }
        if($ststatus=='existing'){
            $where[] = '"studentId" NOT ILIKE \'%' .pg_escape_string($stchk). '%\' ';
        }
    }

    if (!empty($from) && !empty($to))
    {
        $where[] = 'DATE("createdOn") BETWEEN \'' . $from . '\'  AND  \'' . $to . '\'';
    }

    if (count($where) > 0) 
    { 
      $wherecond = ' WHERE '.implode(' AND ',$where); 
    }
    $sql = 'SELECT * FROM admindashboardchallandata'.$wherecond; 
    $chldata =sqlgetresult($sql,true);
    $data=[];
    $demandLabel='demand';
    $receiptLabel='receipt';
    $waiverLabel='waiver';
    $totLabel='total';
    $studLabel='student_count';
    if(count($chldata) > 0){
        foreach ($chldata as $key => $type) {
            $studentId = trim($type['studentId']);
            $feeGroup = trim($type['feeGroup']);
            $academicYear = trim($type['academicYear']);
            $classList = trim($type['classList']);
            $challanNo = trim($type['challanNo']);
            $chlstatus = $type['challanStatus'];
            $org_total = $type['org_total'];
            $receipt_total=$org_total;
            $wv_deleted = trim($type['wv_deleted']);
            $wv_status = trim($type['wv_status']);
            $waiver_total = $type['waiver_total'];
            $feeGroupName=($feeGroupArray[$feeGroup])??$feeGroup;
            /* Demand */
            if($wv_deleted == '0'){
               // $waiverAmt=getWaiverAmtbyFeeGroup($challanNo, $feeGroup);
                $chal_fg_id = $challanNo.'_'.$feeGroup;
                if(!in_array($chal_fg_id, $data[$challanNo][$feeGroup]['waiver_challan_fgrpid'], true)){
                    $data[$challanNo][$feeGroup]['waiver_challan_fgrpid'][]=$chal_fg_id;
                    $data[$challanNo][$feeGroup]['waiver_amt'][]=$waiver_total;
                    $receipt_total=$org_total-$waiver_total;
                    $data[$classList][$feeGroupName][$waiverLabel][$totLabel]+=$waiver_total;
                }
            }else{
                $data[$classList][$feeGroupName][$waiverLabel][$totLabel]+=0;
            }
            $data[$classList][$feeGroupName][$demandLabel][$totLabel]+=$org_total;
            if(!in_array($studentId, $data[$classList][$demandLabel][$studLabel], true)){
                $data[$classList][$demandLabel][$studLabel][]=$studentId;
            }
            /* Receipt */
            if($chlstatus==1){
                //$totAmt = $type['org_total']-$waiverAmt;
                $data[$classList][$feeGroupName][$receiptLabel][$totLabel]+=$receipt_total;
                if(!in_array($studentId, $data[$classList][$receiptLabel][$studLabel], true)){
                    $data[$classList][$receiptLabel][$studLabel][] = $studentId;
                }
            }   
        }
    }
    return $data;
}

if(isset($_POST['dashboard']) && $_POST['dashboard']=='class-wise'){
    $stream_select = (isset($_POST['stream']) && !empty($_POST['stream']))?trim($_POST['stream']):"";
    $selectedYear = (isset($_POST['ayear']) && !empty($_POST['ayear']))?trim($_POST['ayear']):"";
    $ststatus = (isset($_POST['status']) && !empty($_POST['status']))?trim($_POST['status']):"";
    $term = (isset($_POST['term']) && !empty($_POST['term']))?trim($_POST['term']):"";
    $from = (isset($_POST['from']) && !empty($_POST['from']))?trim($_POST['from']):"";
    $to = (isset($_POST['to']) && !empty($_POST['to']))?trim($_POST['to']):"";
    $class_details = array();
    $output_string = [];
    $wherecond = '';
    $sfs='sfs';
    $lmois='lmois';
    $lmes='lmes';
    $fgrp='8,9,10,12';
    $demandLabel='demand';
    $receiptLabel='receipt';
    $waiverLabel='waiver';
    $totLabel='total';
    $studLabel='student_count';
    $wherecond = 'WHERE c.deleted=\'0\' AND c.status=\'1\'';
    if(!empty($stream_select)){
        $wherecond .= ' AND c."streamId"=\'' .pg_escape_string($stream_select). '\'';
    }
    $streams=getStreams();

    $challanData = toGetChallanDataForDashboard($stream_select, $selectedYear, $ststatus, $term, $from, $to);
    /*echo "<pre>";
    print_r($challanData);
    exit;*/
    $classData = sqlgetresult('SELECT c."displayOrder",c.allowed_application, c.id AS value, c.class_list AS label, c."streamId" FROM tbl_class c '.$wherecond.' GROUP BY c.id ORDER BY c."displayOrder" ASC', true);
    foreach($classData as $classes){
      $class_id = $classes['value'];
      $label = trim($classes['label']);
      $streamId = trim($classes['streamId']);
      $output_string[$class_id]['stream']=$streams[$streamId];
      $output_string[$class_id]['class']=$label;
      $stud_count = count($challanData[$class_id][$demandLabel][$studLabel]);
      $output_string[$class_id]['stud_count'] = $stud_count;
      
      $count_of_paid_stud = count($challanData[$class_id][$receiptLabel][$studLabel]);
      $output_string[$class_id]['count_of_paid_stud'] = $count_of_paid_stud;
      /* Demand */
      $output_string[$class_id]['sum_of_sfs_demand'] = ($challanData[$class_id][$sfs][$demandLabel][$totLabel])??0;
      $output_string[$class_id]['sum_of_lmois_demand'] = ($challanData[$class_id][$lmois][$demandLabel][$totLabel])??0;
      $output_string[$class_id]['sum_of_lmes_demand'] = ($challanData[$class_id][$lmes][$demandLabel][$totLabel])??0;

      /* Waiver */
      $sfs_waiver=$challanData[$class_id][$sfs][$waiverLabel][$totLabel];
      $lmois_waiver = $challanData[$class_id][$lmois][$waiverLabel][$totLabel];
      $lmes_waiver = $challanData[$class_id][$lmes][$waiverLabel][$totLabel];
      $output_string[$class_id]['sum_of_waivers']=$sfs_waiver+$lmois_waiver+$lmes_waiver;
      
      /* Receipt */
      $sfs_receipt = ($challanData[$class_id][$sfs][$receiptLabel][$totLabel])??0;
      $lmois_receipt = ($challanData[$class_id][$lmois][$receiptLabel][$totLabel])??0;
      $lmes_receipt = ($challanData[$class_id][$lmes][$receiptLabel][$totLabel])??0;
      /* Receipt  Minus Waiver */
      //$sfs_receipt_wv =($sfs_receipt > 0)? toSubraction($sfs_receipt, $sfs_waiver):0;
      //$lmois_receipt_wv =($lmois_receipt > 0)? toSubraction($lmois_receipt, $lmois_waiver):0;
      //$lmes_receipt_wv =($lmes_receipt > 0)?toSubraction($lmes_receipt, $lmes_waiver):0;

      $output_string[$class_id]['sum_of_sfs_receipt'] = $sfs_receipt;
      $output_string[$class_id]['sum_of_lmois_receipt'] = $lmois_receipt;
      $output_string[$class_id]['sum_of_lmes_receipt'] = $lmes_receipt;
    }
    if(count($output_string) >0){
        foreach($output_string AS $data){
            $countdata[]= $data;
        }
        $outputdata = $countdata;
    }else{
        $outputdata = [];
    }
    echo json_encode($outputdata);
}
/**
 * Summary Report
**/
function toGetSummaryDataForDashboard($stream_select, $selectedYear, $ststatus, $term, $from='', $to=''){
    /*$sfs_grp='10';
    $lmois_grp='9,8';
    $lmes_grp='12';*/
    $fgrp='8,9,10,12';
    $feeGroupArray=array(8=>'lmois',9=>'lmois',10=>'sfs',12=>'lmes');
    $where=[];
    $where[]='"studStatus"!=\'Transport.Fee\'';
    $where[]='"feeGroup" IN ('.$fgrp.')';
    
    if(!empty($class_id)){
        $where[] = '"classList"=\'' .pg_escape_string($class_id). '\' ';
    }
    if(!empty($selectedYear)){
        $where[] = '"academicYear"=\'' .pg_escape_string($selectedYear). '\' ';
    }
    if(!empty($stream_select)){
        $where[] = 'stream=\'' .pg_escape_string($stream_select). '\' ';
    }
    if(!empty($term)){
        $where[] = 'term=\'' .pg_escape_string($term). '\' ';
    }
    
    if (!empty($from) && !empty($to))
    {
        $where[] = 'DATE("createdOn") BETWEEN \'' . $from . '\'  AND  \'' . $to . '\'';
    }

    if (count($where) > 0) 
    { 
      $wherecond = ' WHERE '.implode(' AND ',$where); 
    }
    $sql = 'SELECT * FROM admindashboardchallandata'.$wherecond; 
    $chldata =sqlgetresult($sql,true);
    $data=[];
    $demandLabel='demand';
    $receiptLabel='receipt';
    $waiverLabel='waiver';
    $totLabel='total';
    $studLabel='student_count';
    if(count($chldata) > 0){
        foreach ($chldata as $key => $type) {
            $studentId = trim($type['studentId']);
            $feeGroup = trim($type['feeGroup']);
            $academicYear = trim($type['academicYear']);
            $classList = trim($type['classList']);
            $challanNo = trim($type['challanNo']);
            $chlstatus = $type['challanStatus'];
            $org_total = $type['org_total'];
            $receipt_total=$org_total;
            $wv_deleted = trim($type['wv_deleted']);
            $wv_status = trim($type['wv_status']);
            $waiver_total = $type['waiver_total'];
            $feeGroupName=($feeGroupArray[$feeGroup])??$feeGroup;

            if ( stristr( $studentId, 'APPL' ) ) {
                $stud_sts='new';
            }
            else{
               $stud_sts='exists';
            }

            /* Demand */
            if($wv_deleted == '0'){
               // $waiverAmt=getWaiverAmtbyFeeGroup($challanNo, $feeGroup);
                $chal_fg_id = $challanNo.'_'.$feeGroup;
                if(!in_array($chal_fg_id, $data[$challanNo][$feeGroup]['waiver_challan_fgrpid'], true)){
                    $data[$challanNo][$feeGroup]['waiver_challan_fgrpid'][]=$chal_fg_id;
                    $data[$challanNo][$feeGroup]['waiver_amt'][]=$waiver_total;
                    $receipt_total=$org_total-$waiver_total;
                    $data[$academicYear][$feeGroupName][$waiverLabel][$totLabel][$stud_sts]+=$waiver_total;
                }
            }else{
                $data[$academicYear][$feeGroupName][$waiverLabel][$totLabel][$stud_sts]+=0;
            }
            $data[$academicYear][$feeGroupName][$demandLabel][$totLabel][$stud_sts]+=$org_total;
            if(!in_array($studentId, $data[$academicYear][$studLabel][$demandLabel][$stud_sts], true)){
                $data[$academicYear][$studLabel][$demandLabel][$stud_sts][]=$studentId;
            }
            /* Receipt */
            if($chlstatus==1){
                //$totAmt = $type['org_total']-$waiverAmt;
                $data[$academicYear][$feeGroupName][$receiptLabel][$totLabel][$stud_sts]+=$receipt_total;
                if(!in_array($studentId, $data[$academicYear][$studLabel][$receiptLabel][$stud_sts], true)){
                    $data[$academicYear][$studLabel][$receiptLabel][$stud_sts][] = $studentId;
                }
            }   
        }
    }
    return $data;
}
/**
 * Date-wise Report
**/
function getBetweenDates($startDate, $endDate) {
    $rangArray = [];
 
    $startDate = strtotime($startDate);
    $endDate = strtotime($endDate);
 
    for ($currentDate = $startDate; $currentDate <= $endDate; $currentDate += (86400)) {
        $date = date('d-m-Y', $currentDate);
        //$date = date('Y-m-d', $currentDate);
        $rangArray[] = $date;
    }
 
    return $rangArray;
}
function toGetDatewiseReportForDashboard($stream_select, $selectedYear, $ststatus, $term, $from='', $to=''){
    /*$sfs_grp='10';
    $lmois_grp='9,8';
    $lmes_grp='12';*/
    $fgrp='8,9,10,12';
    $feeGroupArray=array(8=>'lmois',9=>'lmois',10=>'sfs',12=>'lmes');
    $where=[];
    //$where[]='"studStatus"!=\'Transport.Fee\'';
    //$where[]='"feeGroup" IN ('.$fgrp.')';
    $where[] = '"transStatus" =\'Ok\' AND ("challanStatus" = \'1\' OR "challanStatus" = \'2\') ';
    if(!empty($class_id)){
        $where[] = '"classList"=\'' .pg_escape_string($class_id). '\' ';
    }
    if(!empty($selectedYear)){
        $where[] = '"academicYear"=\'' .pg_escape_string($selectedYear). '\' ';
    }
    if(!empty($stream_select)){
        $where[] = 'stream=\'' .pg_escape_string($stream_select). '\' ';
    }
    if(!empty($term)){
        $where[] = 'term=\'' .pg_escape_string($term). '\' ';
    }
    
    if (!empty($from) && !empty($to))
    {
        $where[] = 'DATE("transDate") BETWEEN \'' . $from . '\'  AND  \'' . $to . '\'';
    }

    if (count($where) > 0) 
    { 
      $wherecond = ' WHERE '.implode(' AND ',$where); 
    }
    $sql = 'SELECT DISTINCT "refchallanNo",* FROM partialpaymentreport '.$wherecond;
    //exit;
    $chldata =sqlgetresult($sql,true);
    $data=[];
    if(count($chldata) > 0){
        foreach ($chldata as $key => $type) {
            $studentId = trim($type['chlstudentid']);
            $org_total = $type['paidamt'];
            $receipt_total=$org_total;
            $transDate = trim($type['transDate'])??'';
            if($transDate){
               $transDate =  date('d-m-Y', strtotime($transDate));
            }
        
            if ( stristr( $studentId, 'APPL' ) ) {
                $stud_sts='new';
            }
            else{
               $stud_sts='exists';
            }
            $data[$transDate][$stud_sts]+=$org_total;
        }
    }
    return $data;
}
if(isset($_POST['dashboard']) && $_POST['dashboard']=='date-wise'){
    $stream_select = (isset($_POST['stream']) && !empty($_POST['stream']))?trim($_POST['stream']):"";
    $selectedYear = (isset($_POST['ayear']) && !empty($_POST['ayear']))?trim($_POST['ayear']):"";
    $ststatus = (isset($_POST['status']) && !empty($_POST['status']))?trim($_POST['status']):"";
    $term = (isset($_POST['term']) && !empty($_POST['term']))?trim($_POST['term']):"";
    $cur_date=date("Y-m-d");
    $from = (isset($_POST['from']) && !empty($_POST['from']))?trim($_POST['from']):$cur_date;
    $to = (isset($_POST['to']) && !empty($_POST['to']))?trim($_POST['to']):$cur_date;
    $output_string = [];
    //$fgrp='8,9,10,12';
    $dates = getBetweenDates($from, $to);
    $challanData = toGetDatewiseReportForDashboard($stream_select, $selectedYear, $ststatus, $term, $from, $to);
    $output=[];
    foreach($dates as $date){
        $output_string[$date]['lbl_title'] = $date;  
        $output_string[$date]['new'] = ($challanData[$date]['new'])??0;
        $output_string[$date]['exists'] = ($challanData[$date]['exists'])??0;
        $output_string[$date]['all'] = $output_string[$date]['new']+$output_string[$date]['exists'];
    }
    if(count($output_string) >0){
        foreach($output_string AS $data){
            $countdata[]= $data;
        }
        $outputdata = $countdata;
    }else{
        $outputdata = [];
    }
    echo json_encode($outputdata);
}
function toSubraction($receipt, $waiver){
    $result=0;
    if($receipt >= $waiver){
       $result=$receipt-$waiver;
    }else{
       $result=$waiver-$receipt;
    }
    return $result;

}
if(isset($_POST['dashboard']) && $_POST['dashboard']=='summary'){
    $stream_select = (isset($_POST['stream']) && !empty($_POST['stream']))?trim($_POST['stream']):"";
    $selectedYear = (isset($_POST['ayear']) && !empty($_POST['ayear']))?trim($_POST['ayear']):"";
    $ststatus = (isset($_POST['status']) && !empty($_POST['status']))?trim($_POST['status']):"";
    $term = (isset($_POST['term']) && !empty($_POST['term']))?trim($_POST['term']):"";
    $from = (isset($_POST['from']) && !empty($_POST['from']))?trim($_POST['from']):"";
    $to = (isset($_POST['to']) && !empty($_POST['to']))?trim($_POST['to']):"";
    $class_details = array();
    $output_string = [];
    $wherecond = '';
    $sfs='sfs';
    $lmois='lmois';
    $lmes='lmes';
    $fgrp='8,9,10,12';
    $demandLabel='demand';
    $receiptLabel='receipt';
    $waiverLabel='waiver';
    $totLabel='total';
    $studLabel='student_count';
    $particulars=array($studLabel, $lmois, $sfs, $lmes);
    if(!empty($stream_select)){
        $wherecond = 'WHERE id=\'' .pg_escape_string($stream_select). '\'';
    }
    //$streams=getStreams();

    $challanData = toGetSummaryDataForDashboard($stream_select, $selectedYear, $ststatus, $term, $from, $to);
    /*echo "<pre>";
    print_r($challanData);
    exit;*/
    $output=[];
    //$aYearData = sqlgetresult('SELECT * FROM yearcheck '.$wherecond.' ORDER BY id DESC', true);
    $total_receipts='total_receipts';
    $total_receipts_amt=0;
    foreach($particulars as $particular){
      $streamId = $selectedYear;
      $label = $streams[$streamId];
      $part_dmnd=$particular."_".$demandLabel;
      $part_recpt=$particular."_".$receiptLabel;
      $part_waiver=$particular."_".$waiverLabel;
      $part_yet=$particular."_yet";

      $part_space=$particular."_space";
      $particularName=strtoupper($particular);
      if($particular =='student_count'){
            
            $output_string[$part_dmnd]['lbl_title'] = 'Demand Raised Count';
            $output_string[$part_dmnd]['exists'] = count($challanData[$streamId][$particular][$demandLabel]['exists'])??0;
            $output_string[$part_dmnd]['new'] = count($challanData[$streamId][$particular][$demandLabel]['new'])??0;
            $output_string[$part_dmnd]['all'] = $output_string[$part_dmnd]['exists']+$output_string[$part_dmnd]['new'];


            $output_string[$part_recpt]['lbl_title'] = 'Students Paid As On Date';
            $output_string[$part_recpt]['exists'] = count($challanData[$streamId][$particular][$receiptLabel]['exists'])??0;
            $output_string[$part_recpt]['new'] = count($challanData[$streamId][$particular][$receiptLabel]['new'])??0;
            $output_string[$part_recpt]['all'] = $output_string[$part_recpt]['exists']+$output_string[$part_recpt]['new'];

          // $part_yet=$particular."_yet";

            $output_string[$part_yet]['lbl_title'] = 'Students yet to pay';
            $output_string[$part_yet]['exists'] = toSubraction($output_string[$part_dmnd]['exists'], $output_string[$part_recpt]['exists']);
            $output_string[$part_yet]['new'] = toSubraction($output_string[$part_dmnd]['new'], $output_string[$part_recpt]['new']);
            $output_string[$part_yet]['all'] = toSubraction($output_string[$part_dmnd]['all'], $output_string[$part_recpt]['all']);

            $output_string[$part_space]['lbl_title'] = '';
            $output_string[$part_space]['exists'] = '';
            $output_string[$part_space]['new'] = '';
            $output_string[$part_space]['all'] = '';
           
      }else{
        
        $output_string[$part_dmnd]['lbl_title'] = $particularName.' Fee demand';  
        $output_string[$part_dmnd]['new'] = ($challanData[$streamId][$particular][$demandLabel][$totLabel]['new'])??0;
        $output_string[$part_dmnd]['exists'] = ($challanData[$streamId][$particular][$demandLabel][$totLabel]['exists'])??0;
        $output_string[$part_dmnd]['all'] = $output_string[$part_dmnd]['new']+$output_string[$part_dmnd]['exists'];

        $output_string[$part_waiver]['lbl_title'] = $particularName.' Waiver'; 
        $output_string[$part_waiver]['new'] = ($challanData[$streamId][$particular][$waiverLabel][$totLabel]['new'])??0;
        $output_string[$part_waiver]['exists'] = ($challanData[$streamId][$particular][$waiverLabel][$totLabel]['exists'])??0;
        $output_string[$part_waiver]['all'] = $output_string[$part_waiver]['new']+$output_string[$part_waiver]['exists'];
        
        $output_string[$part_recpt]['lbl_title'] = $particularName.' Fee Received';
        $output_string[$part_recpt]['new'] = ($challanData[$streamId][$particular][$receiptLabel][$totLabel]['new'])??0;
        $output_string[$part_recpt]['exists'] = ($challanData[$streamId][$particular][$receiptLabel][$totLabel]['exists'])??0;
        $output_string[$part_recpt]['all'] = $output_string[$part_recpt]['new']+$output_string[$part_recpt]['exists'];

        $total_receipts_amt+=$output_string[$part_recpt]['all'];

        $output_string[$part_yet]['lbl_title'] = '<p class="text-right"><b>Fee yet to receive</b></p>';
        $output_string[$part_yet]['new'] = (toSubraction($output_string[$part_dmnd]['new'], ($output_string[$part_waiver]['new']+$output_string[$part_recpt]['new'])))??0;
        $output_string[$part_yet]['exists'] = (toSubraction($output_string[$part_dmnd]['exists'], ($output_string[$part_waiver]['exists']+$output_string[$part_recpt]['exists'])))??0;
        $output_string[$part_yet]['all'] = (toSubraction($output_string[$part_dmnd]['all'], ($output_string[$part_waiver]['all']+$output_string[$part_recpt]['all'])))??0;

        $output_string[$part_space]['lbl_title'] = '';
        $output_string[$part_space]['exists'] = '';
        $output_string[$part_space]['new'] = '';
        $output_string[$part_space]['all'] = '';
      }
    }
    $output_string[$total_receipts]['lbl_title'] = '<p class="text-right"><b>Total Receipts</b></p>';
    $output_string[$total_receipts]['exists'] = '';
    $output_string[$total_receipts]['new'] = '';
    $output_string[$total_receipts]['all']= $total_receipts_amt;
    if(count($output_string) >0){
        foreach($output_string AS $data){
            $countdata[]= $data;
        }
        $outputdata = $countdata;
    }else{
        $outputdata = [];
    }
    echo json_encode($outputdata);
}

// **********SFS RECEIPT REPORT EXPORT START************//
if (isset($_POST['excel']) && $_POST['excel'] == "sfsreceiptreportexport"){
    $year = trim($_POST['yearselect']);
    $semester = trim($_POST['semesterselect']);
    $stream = getStreambyId(trim($_POST['streamselect']));
    $class = getClassbyNameId(trim($_POST['classselect']));
    $reporttype = trim($_POST['reporttype']);
    $fromdate = trim($_POST['fromdate']);
    $todate = trim($_POST['todate']);

    $whereClauses = array(); 
    if (! empty($year)) 
        $whereClauses[] ='"academicYear"=\''.trim(pg_escape_string($year)).'\' ' ;
    $where='';

    if (! empty($semester)) 
        $whereClauses[] ='"term"=\''.trim(pg_escape_string($semester)).'\' ' ;
    $where='';

    if (! empty($stream)) 
        $whereClauses[] ='"stream"=\''.trim(pg_escape_string($stream)).'\' ' ;
    $where='';

    if (! empty($class)) 
        $whereClauses[] ='"class"=\''.trim(pg_escape_string($class)).'\' ' ;
    $where='';

    if (! empty($reporttype)) 
        $whereClauses[] ='"entryType"=\''.trim(pg_escape_string($reporttype)).'\' ' ;
    $where='';

    if (! empty($fromdate) && ! empty($todate)) 
        $whereClauses[] ='"date" BETWEEN \''.trim(pg_escape_string(date("m/d/Y", strtotime($fromdate)))).'\' AND \''.trim(pg_escape_string(date("m/d/Y", strtotime($todate)))).'\'' ;
        $where='';
  
    if (count($whereClauses) > 0) 
    { 
      $where = ' WHERE '.implode(' AND ',$whereClauses); 
    }   

    $sql = ('SELECT * FROM  tbl_student_ledger'. $where . ' ORDER BY "challanNo"');
    
    $sqlrun = sqlgetresult($sql, true);
    
    if($sqlrun != ''){
        
            $challan = array();
            $feeGroupList = sqlgetresult('SELECT "feeGroup" FROM tbl_fee_group WHERE id=10');
            $FeeGroups[] = $feeGroupList['feeGroup'];
            $FeegroupData = array();

            foreach ($FeeGroups as $feegroup) {
                $FeegroupData[] = ucwords(strtolower($feegroup)).' Receipt';
            }

            $columns = array('S.No','Student Id','Challan No','Student Name','Academic Year','Term','Stream','Class','Date','Challan Status');
            $columns = array_merge($columns, $FeegroupData);
            array_push($columns,"Receipt","Receipt Details", "Payment Method");
            $i = 1;
            foreach ($sqlrun as $k => $data) 
            {    
                $a = array_map('trim', array_keys($data));
                $b = array_map('trim', $data);
                $data = array_combine($a, $b);          
                $challanData['Student Id'] = trim($data['studentId']);
                $challanData['Challan No'] = trim($data['challanNo']);
                $challanData['Student Name'] = trim($data['studentName']);
                $challanData['Academic Year'] = trim($data['academicYear']);
                $challanData['Term'] = trim($data['term']);
                $challanData['Stream'] = trim($data['stream']);
                $challanData['Class'] = trim($data['class']);
                $challanData['Date'] = $data['date'];
                $challanData['Receipt Details'] = $data['remarks'];
                if(substr($data['remarks'], 0, 3) === 'REF'){
                    $challanData['Payment Method'] = getPaymentMethodType(substr($data['remarks'], 0, 9));
                }else{
                    $challanData['Payment Method'] = '';
                }
                if($data['challanStatus'] == 1){
                $challanData['Challan Status'] = "Active";
                }
                else{
                $challanData['Challan Status'] = "In Active";   
                }   
                
                $feeGroup['name'] = trim($data['feeGroup']);
                    foreach ($FeeGroups as $v) {
                        $amt = ucwords(strtolower($v)).' Receipt';
                        if ( trim($v) == $feeGroup['name'] ) {
                            $entrytype = 'RECEIPT';
                            $FEEGROUPRECEIPT[$amt][] = getfeegroupamountfromledger($feeGroup['name'], $data['challanNo'],$entrytype);
                        }
                         else {
                            $FEEGROUPRECEIPT[$amt][] = '0';
                        }
                    }   


                if( $data['challanNo'] != $sqlrun[$k+1]['challanNo'] ) {
                    $challanData['S.No'] = $i;
                    $receipttotal = 0;
                    foreach ($FEEGROUPRECEIPT as $fee1 => $val1) {
                        $challanData[$fee1] = array_sum(array_unique($val1));
                        if (stripos($fee1,'receipt')) {
                            $receipttotal += array_sum(array_unique($val1));
                        }               
                    }

                    $challanData['Receipt'] = $receipttotal;
                    if($challanData['Receipt'] !== 0){
                        array_push($challan, $challanData);
                        $i++;
                    }
                    $FEEGROUPRECEIPT = array();         
                }      
            }
            $columns = array_unique($columns);
            $header = "LALAJI MEMORIAL OMEGA INTERNATIONAL SCHOOL -SFS RECEIPT REPORT (".$data['academicYear']. "-" .$data['term']." SEM)";
            $_SESSION['splitreportsuccess'] = "<p class='success-msg'>Excel Exported Successfully</p>";
            exportData($challan, $header, $columns);
            header('location:sfsreportreceiptexport.php');

    }
    else{
        $_SESSION['splitreporterror'] = "<p class='error-msg'>No Data available</p>";
    }


    header('location:sfsreportreceiptexport.php');
}

// **********SFS RECEIPT REPORT EXPORT END************//
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
?>
