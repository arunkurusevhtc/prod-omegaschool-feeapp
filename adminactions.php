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
                "adminstatus" => $res['status']
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
    } else {
        $query = "SELECT * FROM statusupdate('$tbl','$status','$uid','$id')";
    }
    $res = sqlgetresult($query);
    // echo $query;exit;
    if ($res["statusupdate"] == 1)
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
    $query = "SELECT * FROM editadm('$id','$adminname','$adminemail','$adminpass','$uid')";
    $run = sqlgetresult($query);

    if ($run['editadm'] == 1)
    {
        $_SESSION['successadm'] = "<p class='success-msg'>Data Edited Successfully.</p>";
        if($var == 1){
        SendMailId($adminemail, 'Edited Admin Login', $data);
        }
        header('location:mainpage.php');
    }
    else if ($run['editadm'] == 0)
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
    $adminpass = password_hash($_POST['password_confirmation'], PASSWORD_DEFAULT);
    $query = "SELECT * FROM addadm('$id','$adminname','$adminemail','$adminpass')";
    $run = sqlgetresult($query);

    if ($run['addadm'] == 1)
    {
        $_SESSION['successadm'] = "<p class='success-msg'>Data Added Successfully.</p>";
        $data = 'Hi,<br/>&nbsp;&nbsp;&nbsp;&nbsp;Please find the Admin login credentials below,</br>';
        $data .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;UserName : ' . $adminemail;
        $data .= '<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Password : ' . $pass;
        SendMailId($adminemail, 'New Admin Login', $data);
        header('location:mainpage.php');
    }
    else if ($run['addadm'] == 0)
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


    $query = "SELECT * FROM editstd('$studentId','$studentName','$stream','$class','$section','$term','$parentId','$email','$mnum','$transportstage','$transportneed','$hostel','$lunch','$year','$uid','$id','$gender')";
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
if (isset($_POST["editstream"]) && $_POST["editstream"] == "update")
{
    $id = $_POST['id'];
    $uid = $_SESSION['myadmin']['adminid'];
    $cname = $_POST['cname'];
    $des = $_POST['des'];
    $query = "SELECT * FROM editstream('$id','$cname','$des','$uid')";

    $run = sqlgetresult($query);
    if ($run["editstream"] == 1)
    {
        $_SESSION['successstream'] = "<p class='success-msg'>Data Edited Successfully.</p>";
        header('location:managestream.php');
    }
    else if ($run["editstream"] == 0)
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
    $cname = $_POST['cname'];
    $des = $_POST['des'];
    $query = "SELECT * FROM addstream('$cname','$des','$uid')";
    $run = sqlgetresult($query);
    if ($run["addstream"] == 1)
    {
        $_SESSION['successstream'] = "<p class='success-msg'>Data Added Successfully.</p>";
        header('location:managestream.php');
    }
    else if ($run["addstream"] == 0)
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
    $query = "SELECT * FROM editfeetype('$id','$cname','$des','$uid','$taxtypes','$group','$man','$app')";
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

    $query = "SELECT * FROM addfeetype('$cname','$des','$uid','$taxtypes','$group','$man','$app')";
    // print_r($query);exit;
    $run = sqlgetresult($query);

    if ($run['addfeetype'] == 1)
    {
        // print_r($run);
        $_SESSION['successftype'] = "<p class='success-msg'>Data Added Successfully.</p>";
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
    if ($result['createtempchallan'] == '0')   {
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

                $feetypearray[getFeeGroupbyId($value['feeGroup'])][$value['feeType']][] = $value['org_total'];
                $feetypearray[getFeeGroupbyId($value['feeGroup'])][$value['feeType']][] = getFeeTypebyId($value['feeType']);
                $cnt = $k+1;
                if($cnt == $chlncnt) {
                    $groupdata = $feetypearray;
                } 
            }

            // $msg = "Hello " . $getparentmailid['userName'] . "! <br/>";
            $msg = "<p style='padding-left:20px;'>New challan has been created for  " . $challanData1['studentName'] . ".<br/>Please make a note that the <b>CHALLAN</b> has to be PAID on or before <b style='color:red'>" . date("d-m-Y", strtotime($challanData1['duedate'])) . "</b>.<br/>For Online Payment Please <b>LOGIN</b> to our <a href=" . BASEURL . " style='color:red'>FEE PORTAL</a>. </p>";

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
                sendNotificationToParents($studentId, $mailbody, $smsbody, $type); 
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


                $feetypearray[getFeeGroupbyId($value['feeGroup'])][$value['feeType']][] = $value['org_total'];
                $feetypearray[getFeeGroupbyId($value['feeGroup'])][$value['feeType']][] = getFeeTypebyId($value['feeType']);
                $cnt = $k+1;
                if($cnt == $chlncnt) {
                    $groupdata = $feetypearray;

                }
        }
        // $msg = "Hello " . $getparentmailid['userName'] . "! <br/>";
        $msg = "<p style='padding-left:20px;'>New challan has been created for  " . $challanData1['studentName'] . ".<br/>Please make a note that the <b>CHALLAN</b> has to be PAID on or before <b style='color:red'>" . date("d-m-Y", strtotime($challanData1['duedate'])) . "</b>.<br/>For Online Payment Please <b>LOGIN</b> to our <a href=" . BASEURL . " style='color:red'>FEE PORTAL</a>. </p>";

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
            sendNotificationToParents($studentId, $mailbody, $smsbody, 1,'','Omega - Challan Created');         
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
if (isset($_POST['submit']) && $_POST['submit'] == "getwavierchallanno")
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
}

if (isset($_POST['addstudent']) && $_POST['addstudent'] == "Update Amount")
{

    $uid = $_SESSION['myadmin']['adminid'];
    $wavierchallan = $_POST['id'];
    $waviergroup = getFeeGroupbyName($_POST['grouptype']);
   
    $wavingamount = $_POST['WavingAmount'];
    $wavingpercentage = $_POST['WavingPercentage'];
    $waivingtype = $_POST['waivertype'];
    $rid = $_POST['rid'];

    $total = sqlgetresult('SELECT SUM("org_total") AS "org_total" FROM tbl_challans WHERE "challanNo"=\'' . $wavierchallan . '\' AND "feeGroup"=\'' . $waviergroup . '\'');

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

    $sql = "SELECT * FROM updatewavingamountnew('$wavierchallan','$newtotal','$uid','$waviergroup','$wavingpercentage','$amountwaving','$wavingamount','$studentId','$waivingtype')";
    // print_R($sql);exit;
    createErrorlog($sql);
    $res = sqlgetresult($sql);
    createErrorlog($res);

    $fromwhere = 'Waiver';
    flattableentry($wavierchallan, $studentId, $fromwhere);
    $sid = sqlgetresult('SELECT * FROM waviercheck WHERE "challanNo"=\'' . $wavierchallan . '\' AND "feeGroup" = \'' . $waviergroup . '\'');
    $getparentmailid = sqlgetresult('SELECT p."userName", p."email" AS mail1 , p."mobileNumber" AS mbl1 , p."phoneNumber" AS mbl2 , p."secondaryEmail" AS mail2 FROM tbl_student s LEFT JOIN tbl_parents p ON s."parentId" = p."id" WHERE s."studentId" =\'' . $sid[0]['studentId'] . '\'');

    if ($res['updatewavingamountnew'] == 0) {
        $_SESSION['errorwavier'] = "<p class='error-msg'>Some Error Has Occured</p>";
    } else {
        $_SESSION['successwavier'] = "<p class='success-msg'>Waving Amount has been Credited</p>";
        $feegroup = getFeeGroupbyId($sid[0]['feeGroup']);        

        $to = $getparentmailid['userName'];
        $getparentmailid = sqlgetresult('SELECT p."userName", p."email" AS mail1 , p."mobileNumber" AS mbl1 , p."phoneNumber" AS mbl2 , p."secondaryEmail" AS mail2 FROM tbl_student s LEFT JOIN tbl_parents p ON s."parentId" = p."id" WHERE s."studentId" =\'' . $sid[0]['studentId'] . '\'');
        
        $challanData = sqlgetresult('SELECT * FROM challanData WHERE "studentId" =\'' . $sid[0]['studentId'] . '\' AND  "challanNo" = \'' . $wavierchallan . '\' ');

        // $feeTypes = sqlgetresult("SELECT * FROM getFeeTypes");

        // $total = 0;
        // $feeData = array();
        // $chlncnt = count($challanData);
        // foreach ($challanData as $k => $value)
        // {
        //     $challanData1['challanNo'] = $value['challanNo'];
        //     $challanData1['term'] = $value['term'];
        //     $challanData1['clid'] = $value['clid'];
        //     $challanData1['studentName'] = $value['studentName'];
        //     $challanData1['studentId'] = $value['studentId'];
        //     $challanData1['class_list'] = $value['class_list'];
        //     $challanData1['duedate'] = $value['duedate'];
        //     $challanData1['stream'] = $value['stream'];
        //     $challanData1['steamname'] = $value['steamname'];
        //     $challanData1['org_total'][] = $value['org_total'];
        //     $challanData1['waivedTotal'][] = $value['waivedTotal'];

        //     $feetypearray[getFeeGroupbyId($value['feeGroup'])][$value['feeType']][] = $value['org_total'];
        //     $feetypearray[getFeeGroupbyId($value['feeGroup'])][$value['feeType']][] = getFeeTypebyId($value['feeType']);

        //      $feetypearray[getFeeGroupbyId($value['feeGroup'])]['waived'] = getwaiveddata($value['challanNo'], $value['feeGroup']);

        //     $cnt = $k+1;
        //     if($cnt == $chlncnt) {
        //         $groupdata = $feetypearray;

        //     }
        // }
        // $msg = "<p style='padding-left:20px;'>Please find the new challan created for  " . $challanData1['studentName'] . ".<br/>Please make a note that the <b>CHALLAN</b> has to be PAID on or before <b style='color:red'>" . date("d-m-Y", strtotime($challanData1['duedate'])) . "</b>.<br/>For Online Payment Please <b>LOGIN</b> to our <a href=" . BASEURL . " style='color:red'>FEE PORTAL</a>. </p>";

        // $msg .= "<table border='1' style='border:1px solid grey;border-collapse: collapse;' cellpadding='10' width = '100%'>
        //         <tr><td colspan='2'><label> <b>School Name</b>: </label> LMOIS - CBSE</td></tr>
        //         <tr><td><label><b>Name</b>: </label> " . $challanData1['studentName'] . " </td><td><label><b>Semester</b>: </label>" . $challanData1['term'] . " </td></tr>
        //         <tr><td><label><b>ID</b>: </label> " . $challanData1['studentId'] . " </td><td><label><b>Class</b>: </label> " . $challanData1['class_list'] . " </td></tr>
        //         <tr><td><label><b>Challan Number</b>: </label>" . $challanData1['challanNo'] . "</td><td><label><b>Due Date</b>: </label> " . date("d-m-Y", strtotime($challanData1['duedate'])) . "</td></tr>
        //         <tr><td colspan='2' style='text-align:center'><b>FEE DETAILS</b></td></tr>";

        // foreach ($groupdata as $grp => $data)
        // {
        //     $msg .= '<tr style="border:0;border-right:1px solid grey;"><td colspan="2"><b>' . $grp . '</b></td></tr>';
        //     $tot = 0;
        //     $wtot = 0;
        //     $amount = 0;
        //     $last_key = end(array_keys($data));
        //     $waiveddata = array();
        //     foreach ($data as $k => $val){

        //         if(trim($k) != 'waived' && $val[0] != 0){
                    
        //             $msg .= '<tr style="border:0;"><td >' . $val[1] . '</td><td style="border-right:1px solid grey;text-align:right;">' . $val[0] . '</td></tr>';
        //             $tot += $val[0];
                    
        //         }
        //         if(trim($k) == 'waived' && $val != 0) {
        //             $waiveddata[] =  $val[0]['waiver_type'];
        //             $waiveddata[] =  $val[0]['waiver_total']; 
        //             $wtot = $val[0]['waiver_total'];
        //         }
        //         if( $k == $last_key && sizeof($waiveddata) > 0)  {

        //                 $msg .= '<tr style="border:0;"><td><b>Waiver</b> - ' . $waiveddata[0] . '</td><td style="border-right:1px solid grey;text-align:right;">' . $waiveddata[1] . '</td></tr>';

        //         }
        //     }
        //     $amount += $tot;
        //     $amount -= $wtot;
        //     $org_total += $amount;
        //     $msg .= '<tr style="border-top:1px solid grey;"><td style="border-top:1px solid grey;"><b>TOTAL</b></td><td style="border-top:1px solid grey;text-align:right;"><b>' . $amount . '</td></tr></b>';
        // }
        // $msg .= '<tr style="border-top:1px solid grey;"><td style="border-top:1px solid grey;"><b>GRAND  TOTAL</b></td><td style="border-top:1px solid grey;text-align:right;"><b>' . $org_total . '</td></tr></b>';
        // $msg .= "</table>";

        $msg = '<p>Please note that the challan has been updated for ' . $challanData[0]['studentId'] . ': ' . $challanData[0]['studentName'] . '.</p>';

        
        $mailbody = $msg;
        $studentId = trim($challanData[0]['studentId']);
        $type = "Waived Challan";

        $smsbody = "Dear Parent, Your child's challan has been waived. Please logon our feeapp to see more details.";
        sendNotificationToParents($studentId, $mailbody, $smsbody, $type);
    }
    header("Location:managefeewavier.php");
}

/*******Fee Waiver Section -End*****/

/*******Filters - Start*****/

if (isset($_POST['filter']) && $_POST['filter'] == "filterstudent")
{

    $classselect = $_POST['classselect'];
    $streamselect = $_POST['streamselect'];
    $sectionselect = $_POST['sectionselect'];

    if ($streamselect != '' && $classselect == '' && $sectionselect == '')
    {
        $sql = 'SELECT * FROM filterstudentdetails WHERE "stream"=\'' . $streamselect . '\' ';

    }
    else if ($classselect != '' && $streamselect == '' && $sectionselect == '')
    {
        $sql = 'SELECT * FROM filterstudentdetails WHERE "class"=\'' . $classselect . '\' ';

    }
    else if ($sectionselect != '' && $classselect == '' && $streamselect == '')
    {
        $sql = 'SELECT * FROM filterstudentdetails WHERE "section"=\'' . $sectionselect . '\' ';
    }
    else if ($classselect != '' && $streamselect != '' && $sectionselect == '')
    {
        $sql = 'SELECT * FROM filterstudentdetails WHERE "class"=\'' . $classselect . '\' AND "stream" = \'' . $streamselect . '\'';

    }
    else if ($classselect != '' && $streamselect == '' && $sectionselect != '')
    {
        $sql = 'SELECT * FROM filterstudentdetails WHERE "class"=\'' . $classselect . '\' AND "section" = \'' . $sectionselect . '\'';

    }
    else if ($classselect != '' && $streamselect != '' && $sectionselect != '')
    {
        $sql = ('SELECT * FROM filterstudentdetails WHERE "class"=\'' . $classselect . '\' AND "stream" =\'' . $streamselect . '\' AND "section" = \'' . $sectionselect . '\'');

    } else {
        $sql = ('SELECT * FROM filterstudentdetails');
    }

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

    $sql = ('SELECT * FROM  waviercheck '. $where. 'AND "challanStatus" = \'' . 0 . '\'');
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

    $sql = ('SELECT * FROM  getchallandatanew '. $where. 'AND "challanStatus" = 1');
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
    $studentid = "3576";
    $challanno = 'CBSE2018/010583';
    $sql = ('SELECT * FROM  getchallandatanew '. $where. 'AND "challanStatus" = \'' . 0 . '\'');
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
            $challanData[$data['challanNo']]['studentId'] = $data['studentId'];
            $challanData[$data['challanNo']]['challanNo'] = $data['challanNo'];
            $challanData[$data['challanNo']]['studentName'] = $data['studentName'];
            $challanData[$data['challanNo']]['streamname'] = $data['streamname'];
            $challanData[$data['challanNo']]['class_list'] = $data['class_list'];
            $challanData[$data['challanNo']]['section'] = $data['section'];
            $challanData[$data['challanNo']]['term'] = $data['term'];
            $challanData[$data['challanNo']]['createdOn'] = date("d-m-Y", strtotime($data['createdOn']));
            $challanData[$data['challanNo']]['duedate'] = date("d-m-Y", strtotime($data['duedate']));

            $challanData[$data['challanNo']]['waived'] =getwaiveddata($data['challanNo'], $data['challanStatus']);
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
        $query1 = sqlgetresult('SELECT SUM("total") AS org_total FROM tbl_challans WHERE "challanNo" =\'' . $cno . '\' AND "feeGroup" = \''. $feegroup .'\'');
        foreach($waiveddata AS $waiver){
            $waivertotal = $waiver['waiver_total'];
        }
        $totalamount = $query1['org_total'] - $waivertotal;
    }
    else{
        $query = sqlgetresult('SELECT SUM("total") AS org_total FROM tbl_challans WHERE "challanNo" =\'' . $cno . '\' AND "feeGroup" = \''. $feegroup .'\'');

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
    
    $chequerevoke = "SELECT * FROM chequerevoke('$cno','$uid', '$stdid', '$term', '$acayear')";
    $runchequerevoke = sqlgetresult($chequerevoke);
   
    if ($runchequerevoke['chequerevoke'] > 0)
    {
        $_SESSION['successchallanrevoke'] = "<p class='success-msg'>Challan Revoked Successfully.</p>";
        header('location:managepaidchallans.php');
    }
    else
    {
        createErrorlog($run);
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
    // print_r($_SERVER);
    if ( strpos($page, 'nonfeechallancreation') !== false) {
        unset($_SESSION['selectednonfeechallans']);
        $done = 1;
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
    $challanData = sqlgetresult('SELECT * FROM challanDatanew WHERE "studentId" =\'' . $studId . '\' AND  "challanNo" = \'' . $cid . '\' AND "challanStatus" = 0 ',true);
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
        $latefee = sqlgetresult('SELECT "org_total" FROM tbl_challans WHERE "challanNo" = \''.$challanData1['challanNo'].'\' AND "challanStatus" = \'0 \' AND "feeGroup" = \''.$late.'\'');
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
            $feeTypeData = sqlgetresult('SELECT id, "feeType" FROM getfeetypes WHERE applicable=\'DH\' OR applicable=\'H\' ',true);
        } else {
            $feeTypeData = sqlgetresult('SELECT id, "feeType" FROM getfeetypes WHERE applicable=\'DH\' OR applicable=\'D\' OR applicable  = \'0\' ',true);
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

    $query = "SELECT * FROM addnonfeetype('$cname','$des','$uid','$group', '$app','$challan')";
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

    $query = "SELECT * FROM editnonfeetype('$id','$cname','$des','$uid','$group','$app','$challan')";
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
    print_r($_POST);    

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
                        $sql = "SELECT * FROM createnonfeechallan('$challanNo','$id','".$studentData['class']."','".$k."','".$studentData['term']."','$createdby','".$val[0]."','".$studentData['stream']."','$remarks','$duedate','$feegrp','".$studentData['academicYear']."','".$val[2]."')";    
                        // echo $sql;
                        $result = sqlgetresult($sql);                 
                        if ($result['createnonfeechallan'] == '0') {
                            $challanData['exist'] = 'Challan Already Exists';
                        } else if ($result['createnonfeechallan'] > '0') {
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
                    $sql = "SELECT * FROM createnonfeechallan('$challanNo','$id','$class','".$k."','$term','$createdby','".$val[0]."','$stream','$remarks','$duedate','$feegrp','$academicId','".$val[2]."')";    
                    // echo $sql;exit;
                    $result = sqlgetresult($sql);                 
                    if ($result['createnonfeechallan'] == '0') {
                        $challanData['exist'] = 'Challan Already Exists';
                    } else if ($result['createnonfeechallan'] > 0) {
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
    $academic = getAcademicyrIdByName(trim($_POST['academic']));

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

    $nonfeetypedata = sqlgetresult('SELECT * FROM getnonfeetypedata WHERE class=\'' . $class . '\' AND semester=\'' . $term . '\' AND stream = \'' . $stream . '\' AND "academicYear" = \''.$academic.'\' AND "feeType" = \''.$feetypes.'\' ');   

    // print_r($nonfeetypedata);

    if( sizeof($nonfeetypedata) > 0 ) {
        $qry = sqlgetresult("SELECT * FROM updatenonfeechallan('$challanNo','$id','$class','".$feetypes."','$term','$createdby','".$nonfeetypedata['amount']."','$stream','$remarks','$duedate','2','$academic')");    

        if( $qry['updatenonfeechallan'] > 0 ) {
            $_SESSION['success'] = "<p class='success-msg'>Non-Fee challan updated Successfully.</p>";
            header('location:createnonfeechallans.php');
        } else if( $qry['updatenonfeechallan'] == 0 ) {
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
      $where = ' WHERE '.implode(' AND ',$whereClauses); 
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
        $challanData['Receipt Details'] = $data['remarks'];
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
    $studentid = trim($_POST['studentid']);

    $whereClauses = array(); 
    if (!empty($studentid)) 
        $whereClauses[] = '"studentId"=\''.trim(pg_escape_string($studentid)).'\' ' ;
    $where = ''; 

  
    if (count($whereClauses) > 0) 
    { 
      $where = ' WHERE '.implode(' AND ',$whereClauses); 
    }   

    $sql = ('SELECT * FROM  tbl_student_ledger'. $where);
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
      $where = ' WHERE '.implode(' AND ',$whereClauses); 
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
?>
