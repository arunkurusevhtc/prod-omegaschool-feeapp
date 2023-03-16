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
            $_SESSION['error'] = "<p class='error-msg'>EmailId and Password doesn't match</p>";
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
    $query = "SELECT * FROM statusupdate('$tbl','$status','$uid','$id')";
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

    $id = $_REQUEST['id'];
    $uid = $_SESSION['myadmin']['adminid'];
    $query = "SELECT * FROM deleteupdate('$tbl','$uid','$id')";
    $res = sqlgetresult($query);

    if ($res['deleteupdate'] == 1)
    {
        $_SESSION['success'] = "<p class='success-msg'>Delete Function Was Done Successfully</p>";
        header("Location:" . $page);
    }
    else
    {
        createErrorlog($res);
        $_SESSION['failure'] = "<p class='error-msg'>Delete Function Was Done</p>";
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

    }
    // $adminpass = password_hash($_POST['password_confirmation'],PASSWORD_DEFAULT);
    $query = "SELECT * FROM editadm('$id','$adminname','$adminemail','$adminpass','$uid')";
    $run = sqlgetresult($query);

    if ($run['editadm'] == 1)
    {

        $_SESSION['successadm'] = "<p class='success-msg'>Data edited successfully.</p>";
        SendMailId($adminemail, 'Edited Admin Login', $data);
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
    $id = $_POST['id'];
    $uid = $_SESSION['myadmin']['adminid'];
    $firstname = $_POST['fname'];
    $lastname = $_POST['lname'];
    $username = $firstname . " " . $lastname;
    $email = $_POST['email'];
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

    }
    // $pass = password_hash($_POST['password'],PASSWORD_DEFAULT);
    $pnumber = $_POST['pnum'];
    $mnumber = $_POST['mnum'];
    $query = "SELECT * FROM editpar('$id','$firstname','$lastname','$username','$email','$pass','$pnumber','$mnumber','$uid')";
    $run = sqlgetresult($query);
    if ($run['editpar'] == 1)
    {

        $_SESSION['successpar'] = "<p class='success-msg'>Data edited successfully.</p>";
        SendMailId($email, 'Edited Parent Login', $data);
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
    $password = $_POST['password_confirmation'];
    $pass = password_hash($_POST['password_confirmation'], PASSWORD_DEFAULT);
    $pnumber = $_POST['pnum'];
    $mnumber = $_POST['mnum'];
    $query = "SELECT * FROM addpar('$firstname','$lastname','$username','$email','$pass','$pnumber','$mnumber','$uid')";
    $run = sqlgetresult($query);
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
    $n = 0;
    $id = $_POST['id'];
    $studentId = $_POST['sid'];
    $uid = $_SESSION['myadmin']['adminid'];
    $studentName = $_POST['name'];
    $stream = $_POST['stream'];
    $class = $_POST['class'];
    $section = $_POST['section'];
    $term = $_POST['term'];
    // $demandNo = $_POST['dno'];
    // $parentId = $_POST['pid'];
    $email = $_POST['mail'];
    $mnum = $_POST['mobile'];

    if ($_POST['pid'] == '')
    {
        $parentId = $_POST['oldpid'] == '' ? '0' : $_POST['oldpid'];

    }
    else
    {
        $parentId = $_POST['pid'];
    }

    $query = "SELECT * FROM editstd('$studentId','$studentName','$stream','$class','$section','$term','$parentId','$email','$mnum','$uid','$id')";
    $run = sqlgetresult($query);
    // print_r($query);
    // print_r($run);
    // exit;
    if ($run['editstd'] == 1)
    {

        $_SESSION['successstd'] = "<p class='success-msg'>Data edited successfully.</p>";
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
    $n = 0;
    // $id=$_POST['id'];
    $studentId = $_POST['sid'];
    $uid = $_SESSION['myadmin']['adminid'];
    $studentName = $_POST['name'];
    $stream = $_POST['stream'];
    $class = $_POST['class'];
    $section = $_POST['section'];
    $term = $_POST['term'];
    $email = $_POST['mail'];
    $mnum = $_POST['mobile'];
    if ($_POST['pid'] == '')
    {
        $parentId = $_POST['oldpid'];

    }
    else
    {
        $parentId = $_POST['pid'];
    }
    $query = "SELECT * FROM addstd('$studentId','$studentName','$stream','$class','$section','$term','$parentId','$email','$mnum','$uid')";
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
    $query = "SELECT * FROM editclass('$id','$cname','$des','$uid')";
    $run = sqlgetresult($query);
    if ($run['editclass'] == 1)
    {
        $_SESSION['successclass'] = "<p class='success-msg'>Data edited successfully.</p>";
        header('location:manageclass.php');
    }
    else if ($run['editclass'] == 0)
    {
        $_SESSION['errorclass'] = "<p class='error-msg'>Class Already Exixt</p>";
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
    $n = 0;
    // $id=$_POST['id'];
    $uid = $_SESSION['myadmin']['adminid'];
    $cname = $_POST['cname'];
    $des = $_POST['des'];
    $query = "SELECT * FROM addclass('$cname','$des','$uid')";
    $run = sqlgetresult($query);
    if ($run['addclass'] == 1)
    {
        $_SESSION['successclass'] = "<p class='success-msg'>Data Added Successfully.</p>";
        header('location:manageclass.php');
    }
    else if ($run['addclass'] == 0)
    {
        $_SESSION['errorclass'] = "<p class='error-msg'>Class already exist.</p>";
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
        $_SESSION['successstream'] = "<p class='success-msg'>Data edited successfully.</p>";
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
    $run = sqlgetresult($query);
    if ($run["editlatefee"] == 1)
    {
        $_SESSION['successlatefee'] = "<p class='success-msg'>Data edited successfully.</p>";
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
    $run = sqlgetresult($query);
    if ($run["addlatefee"] == 1)
    {
        $_SESSION['successlatefee'] = "<p class='success-msg'>Data Added Successfully.</p>";
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

        $_SESSION['successtax'] = "<p class='success-msg'>Data edited successfully.</p>";
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
    $run = sqlgetresult($query);

    if ($run['addtax'] == 1)
    {

        $_SESSION['successtax'] = "<p class='success-msg'>Data Added Successfully.</p>";
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

    if ($_POST['group'] == "")
    {
        $group = $_POST['oldgroup'];
    }
    else
    {
        $group = $_POST['group'];
    }
    $query = "SELECT * FROM editfeetype('$id','$cname','$des','$uid','$taxtypes','$group','$man','$app')";
    $run = sqlgetresult($query);
    // print_r($query);
    // exit;
    if ($run['editfeetype'] == 1)
    {
        $_SESSION['successftype'] = "<p class='success-msg'>Data edited successfully.</p>";
        header('location:managefeetype.php');
    }
    else if ($run['editfeetype'] == 0)
    {
        $_SESSION['errorftype'] = "<p class='error-msg'>Same data already exist.</p>";
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
    $group = $_POST['group'];

    if ($_POST['selected_taxtypes'] == "")
    {
        $taxtypes = $_POST['oldtax'];
    }
    else
    {
        $taxtypes = $_POST['selected_taxtypes'];
    }

    if ($_POST['group'] == "")
    {
        $group = $_POST['oldgroup'];
    }
    else
    {
        $group = $_POST['group'];
    }

    if (isset($_POST['mandatory']))
    {
        $man = 1;
    }
    else
    {
        $man = 0;
    }

    if (isset($_POST['dayscholar']) && $_POST['hosteller'])
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
    $run = sqlgetresult($query);

    if ($run['addfeetype'] == 1)
    {
        // print_r($run);
        $_SESSION['successftype'] = "<p class='success-msg'>Data Added Successfully.</p>";
        header('location:managefeetype.php');
    }
    else if ($run['editfeetype'] == 0)
    {
        $_SESSION['errorftype'] = "<p class='error-msg'>Same data already exist.</p>";
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

/*****Fee Type Table - Start*****/
if (isset($_POST["subyear"]) && $_POST["subyear"] == "academicyear")
{
    $id = $_POST['id'];
    $year = $_POST['year'];
    $query = "SELECT * FROM edityear('$id','$year')";
    $run = sqlgetresult($query);

    if ($run['edityear'] == 1)
    {
        $_SESSION['successyear'] = "<p class='success-msg'>Data edited successfully.</p>";
        header('location:manageyear.php');
    }
    else if ($run['edityear'] == 0)
    {
        $_SESSION['erroryear'] = "<p class='error-msg'>Data Already Exixt.</p>";
        header('location:manageyear.php');
    }
    else
    {
        createErrorlog($run);
        $_SESSION['erroryear'] = "<p class='error-msg'>Some error has been occured. Please try again.</p>";
        header('location:manageyear.php');
    }
}

/*****Fee Type Table - End*****/

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
        $_SESSION['success'] = "<p class='success-msg'>Record Added Successfully</p>";
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
            SendMailId($email, 'Edited Teacher Login', $data);
        }
        $_SESSION['success'] = "<p class='success-msg'>Data edited successfully.</p>";
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
    $id = $_POST['studentId'];
    $createdby = $_SESSION['myadmin']['adminid'];
    $stream = $_POST['stream'];
    $class = $_POST['class'];
    $term = $_POST['semester'];
    $term = trim($_POST['semester']);
    $academic = $_POST['academicyear'];
    $streamName = getStreambyId($stream);

    $feetypedata = sqlgetresult('SELECT * FROM getfeetypedata WHERE class=\'' . $class . '\' AND semester=\'' . $term . '\' AND stream = \'' . ($stream) . '\' ');

    if (isset($_SESSION['selectedstudid']))
    {
        $selected = $_SESSION['selectedstudid'];
        for ($i = 0;$i < count($selected);$i++)
        {
            $studentname = getStudentNameById($selected[$i]);

            $lastrecordID = sqlgetresult("SELECT SETVAL('tbl_temp_challans_id_seq',MAX(id)+1) AS max FROM tbl_temp_challans");
            if (!ctype_digit(strval($lastrecordID['max'])))
            {
                $challanNo = trim($streamName) . date('Y') . '/000001';
            }
            else
            {
                $no = str_pad(++$lastrecordID['max'], 6, '0', STR_PAD_LEFT);;
                $challanNo = trim($streamName) . date('Y') . '/' . $no;
            }

            $sql = "SELECT * FROM createTempChallan('$challanNo','$selected[$i]','$createdby','$stream','$class','$term','$studentname','$academic')";
            // print_r($sql);
            $result = sqlgetresult($sql);
        }
    }
    else
    {
        $studentId = $_POST['studentId'];
        $studentName = $_POST['studentName'];

        $lastrecordID = sqlgetresult("SELECT SETVAL('tbl_temp_challans_id_seq',MAX(id)+1) AS max FROM tbl_temp_challans");
        if (!ctype_digit(strval($lastrecordID['max'])))
        {
            $challanNo = trim($streamName) . date('Y') . '/000001';
        }
        else
        {
            $no = str_pad(++$lastrecordID['max'], 6, '0', STR_PAD_LEFT);;
            $challanNo = trim($streamName) . date('Y') . '/' . $no;
        }

        $sql = "SELECT * FROM createTempChallan('$challanNo','$studentId','$createdby','$stream','$class','$term','$studentName','$academic')";
        // print_r($sql);
        // exit;
        $result = sqlgetresult($sql);

    }
    if ($result['createtempchallan'] == '0')
    {
        $_SESSION['errorstatusstudent'] = "<p class='error-msg'>Some error has been occured. Please try again.</p>";
        unset($_SESSION['selectedstudid']);
        header('location:home.php');
    }
    else
    {

        $_SESSION['successstatusstudent'] = "<p class='success-msg'>Data updated successfully.</p>";
        unset($_SESSION['selectedstudid']);
        header('location:home.php');
    }
}

if (isset($_POST['updateTempChallan']) && $_POST['updateTempChallan'] == 'updateTempChallan')
{
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
    $feetypedata = sqlgetresult('SELECT * FROM getfeetypedata WHERE class=\'' . $class . '\' AND semester=\'' . $term . '\' AND stream = \'' . ($stream) . '\' ', true);
    $selectedids = array();

    if (count($feetypedata) == 0)
    {
        echo json_encode("Fee Types empty");
        exit;
    }
    // print_r($feetypedata);
    // print_r($feetypedata);
    if (isset($_SESSION['selectedchallans']))
    {
        // print_r($_SESSION);
        $selected = $_SESSION['selectedchallans'];
        for ($i = 0;$i < count($selected);$i++)
        {
            $challanNo = sqlgetresult('SELECT "challanNo" FROM tbl_temp_challans WHERE "studentId" = \'' . $selected[$i] . '\'', true);
            foreach ($challanNo as $challan)
            {
                foreach ($challan as $key => $val)
                {
                    // print_r($challan[$key]);
                    $name = getStudentNameById($selected[$i]);
                    $totalamount = 0;
                    $selectedData = array();

                    $feeData = explode(',', $feetypes);
                    // print_r($feeData);
                    foreach ($feeData as $k => $v)
                    {
                        foreach ($feetypedata as $val)
                        {
                            if ( $v == trim($val['feeType']))
                            {
                                // print_r($val);
                                $groupdata[$val['feeGroup']][$val['id']][] = $val['amount'];
                                $groupdata[$val['feeGroup']][$val['id']][] = $val['feename'];
                            }
                        }
                    }

                    $selectedData['feeData'] = $groupdata;

                    if ($groupdata != 0)
                    {
                        foreach ($groupdata as $grp => $data)
                        {
                            $feegrp = $grp;
                            $feeId = array();
                            $total = 0;
                            foreach ($data as $k => $val)
                            {
                                $feeId[] = $k;
                                $total += $val[0];
                            }
                            $feeIds = implode(',', $feeId);

                            $sql = "SELECT * FROM updatetempchallan('$challan[$key]','$selected[$i]','$class','" . $feeIds . "','$term','$name' ,'$studStatus','$createdby','$total','$stream','$remarks','$duedate','$feegrp','$academic')";
                            
                            // print_r($sql);echo('<hr/>');
                            $result = sqlgetresult($sql);
                            $feeId = '';
                            $total = 0;

                            if ($result['updatetempchallan'] == '0')
                            {
                                $challanData['exist'] = 'Challan Already Exists';
                            }
                        }

                        $challanData = sqlgetresult('SELECT * FROM tempChallan WHERE "challanNo"=\'' . $challan[$key] . '\' LIMIT 1');
                        $selectedData['challanData'] = $challanData;
                        $challanId = sqlgetresult('SELECT id FROM tempChallan WHERE "challanNo"=\'' . $challan[$key] . '\' AND "feeGroup" IS NOT NULL ',true); 
                        array_push($selectedids, $challan[$key]);
                        // print_r($challanId);
                        // foreach ($challanId as $cid)
                        // {
                        //     // print_r($cid);
                        //     array_push($selectedids, $cid['id']);
                        // }

                    }
                    else
                    {
                        $selectedData = 'Fee Types empty';
                    }
                }
            }
        }
        // exit;
        // $_SESSION['createdchallans'] = $_SESSION['selectedchallans'];
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
        $selectedData = array();
        $feeData = explode(',', $feetypes);
        foreach ($feeData as $k => $v)
        {
            foreach ($feetypedata as $val)
            {
                // echo $v;
                if ( $v == trim($val['feeType']))
                {
                    // print_r($val);
                    $groupdata[$val['feeGroup']][$val['id']][] = $val['amount'];
                    $groupdata[$val['feeGroup']][$val['id']][] = $val['feename'];
                }
            }
        }

        // print_r($groupdata);
        // exit;
        $selectedData['feeData'] = $groupdata;

        if ($groupdata != 0)
        {
            foreach ($groupdata as $grp => $data)
            {
                $feegrp = $grp;
                $feeId = array();
                $total = 0;
                foreach ($data as $k => $val)
                {
                    $feeId[] = $k;
                    $total += $val[0];
                }
                $feeIds = implode(',', $feeId);

                $sql = "SELECT * FROM updatetempchallan('$challanNo','$id','$class','" . $feeIds . "','$term','$name' ,'$studStatus','$createdby','$total','$stream','$remarks','$duedate','$feegrp','$academic')";
                // print_r($sql);
                $result = sqlgetresult($sql);
                $feeId = '';
                $total = 0;

                if ($result['updatetempchallan'] == '0')
                {
                    $challanData['exist'] = 'Challan Already Exists';
                }
            }
            $challanData = sqlgetresult('SELECT * FROM tempChallan WHERE "challanNo"=\'' . $challanNo . '\' ORDER BY id ASC LIMIT 1');
            $selectedData['challanData'] = $challanData;
        }
        else
        {
            $selectedData = 'Fee Types empty';
        }

    }
    echo json_encode($selectedData);
    // exit;
}

if (isset($_GET['c']) && $_GET['c'] != '')
{
    if (isset($_SESSION['createdchallanids']))
    {
        $createdchallans = $_SESSION['createdchallanids'];
        
        foreach ($createdchallans as $challans)
        {
            $challanNo = sqlgetresult('SELECT "studentId" FROm tbl_temp_challans WHERE "challanNo"=\'' . $challans . '\' LIMIT 1');
            $del = sqlgetresult('DELETE FROM tbl_temp_challans WHERE "challanNo"= \'' . $challans . '\' AND "feeGroup" IS NULL ');
            $rowid = sqlgetresult('SELECT id FROM tbl_temp_challans WHERE "challanNo"= \'' . $challans . '\'', true);
            foreach ($rowid as $k => $row)
            {
                $id = $row['id'];
                $datas = sqlgetresult("SELECT * FROM createChallan('$id')");
            }
            // print_r($createdchallans);
            // exit;
            $getparentmailid = sqlgetresult('SELECT "email" AS parentMailId,"mobileNumber" FROM tbl_student WHERE "studentId"=\'' . $challanNo['studentId'] . '\'');
            $challanData = sqlgetresult('SELECT * FROM challanData WHERE "studentId" =\'' . $challanNo['studentId'] . '\' AND  "challanNo" = \'' . $challans . '\' ', true);
            $feeTypes = sqlgetresult("SELECT * FROM getFeeTypes");
            $mailid = $getparentmailid['parentmailid'];
            $to = $mailid;
            $mblNumber = $getparentmailid['mobileNumber'];

            $total = 0;
            $feeData = array();
            foreach ($challanData as $value)
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

                $feetype = explode(',', $value['feeTypes']);
                foreach ($feetype as $v)
                {
                    $feeData[trim($v) ][] = $value['feeGroup'];
                    $feeData[trim($v) ][] = $value['org_total'];
                }
            }

            $msg = "Hello " . $mailid . "! <br/>";
            $msg .= "<p style='padding-left:20px;'>Please find the new challan created for  " . $challanData1['studentName'] . ".<br/>Please make a note that the <b>CHALLAN</b> has to be PAID on or before <b style='color:red'>" . date("d-m-Y", strtotime($challanData1['duedate'])) . "</b>.<br/>For Online Payment Please <b>LOGIN</b> to our <a href=" . BASEURL . " style='color:red'>FEE PORTAL</a>. </p>";

            $msg .= "<table border='1' style='border:1px solid grey;border-collapse: collapse;' cellpadding='10' width = '100%'>
                <tr><td colspan='2'><label> <b>School Name</b>: </label> LMOIS - CBSE</td></tr>
                <tr><td><label><b>Name</b>: </label> " . $challanData1['studentName'] . " </td><td><label><b>Semester</b>: </label>" . $challanData1['term'] . " </td></tr>
                <tr><td><label><b>ID</b>: </label> " . $challanData1['studentId'] . " </td><td><label><b>Class</b>: </label> " . $challanData1['class_list'] . " </td></tr>
                <tr><td><label><b>Challan Number</b>: </label>" . $challanData1['challanNo'] . "</td><td><label><b>Due Date</b>: </label> " . date("d-m-Y", strtotime($challanData1['duedate'])) . "</td></tr>
                <tr><td colspan='2' style='text-align:center'><b>FEE DETAILS</b></td></tr>";

            $feetypedata = sqlgetresult('SELECT * FROM getfeetypedata WHERE class=\'' . $challanData1['clid'] . '\' AND semester=\'' . $challanData1['term'] . '\' AND stream = \'' . $challanData1['stream'] . '\' ', true);
            foreach ($feeData as $id => $fee)
            {
                foreach ($feetypedata as $val)
                {
                    // if (in_array(trim($id) , $val))
                    if ( trim($id) == trim($val['feeType']))    
                    {
                        $total += $val['amount'];
                        $groupdata[$val['feeGroup']][$val['id']][] = $val['amount'];
                        $groupdata[$val['feeGroup']][$val['id']][] = $val['feename'];
                    }
                }
            }

            $tot = 0;
            foreach ($groupdata as $k => $v)
            {
                $msg .= '<tr style="border:0;border-right:1px solid grey;"><td colspan="2"><b>' . $k . '</b></td></tr>';

                foreach ($v as $fee)
                {
                    $msg .= '<tr style="border:0;"><td >' . $fee[1] . '</td><td style="border-right:1px solid grey;text-align:right;">' . $fee[0] . '</td></tr>';
                    $tot += $fee[0];
                }
            }
            $msg .= '<tr style="border-top:1px solid grey;"><td style="border-top:1px solid grey;"><b>TOTAL</b></td><td style="border-top:1px solid grey;text-align:right;"><b>' . $tot . '</b></td></tr>';
            $msg .= "</table>";

            // print_r($msg);exit;
            $subject = "Fee Challan for " . $challanData1['studentName'] . "";
            $data = $msg;
               if ($datas['createchallan'] == '1')
        {
            $_SESSION['successchallan'] = "<p class='success-msg'>Challan Created Successfully and Mail has been Sent to the parent Mail Id</p>";
            $mblNo = '918939747556';

            $smsTxt = urlencode("Dear Parent, New challan (".$challanData1['challanNo'].")
                has been created for " . $challanData1['studentName'] . " (" . $challanData1['studentId'] . ") for ".$challanData1['term']." sem(".$challanData1['academic_yr'].").");  


            $smsURL = "$smsBaseurl/sms.aspx?Id=$smsLoginId&Pwd=$smsLoginPass&PhNo=$mblNo&text=$smsTxt";
            // $ret = file($smsURL);
            $send = SendMailId($to, $subject, $data);
            // if ($send == true)
            // {http://111.93.105.51/feeapp
            //     $_SESS
            // ION['success'] = "<div class='success-msg'>Mail has been Sent.</div>";
            // }
                header('location:managecreatedchallans.php');

            
        }
        else
        {
            createErrorlog($data);
            $_SESSION['error'] = "<p class='error-msg'>Some error has occurred Please Try Again Later.</p>";
            header('Location:managechallans.php');
        }
        }
        unset($_SESSION['createdchallanids']);
    }
    else
    {
        $challanId = $_GET['c'];
        $challanNo = sqlgetresult('SELECT "challanNo", "studentId" FROM tbl_temp_challans WHERE "id"=\'' . $challanId . '\'');
        $del = sqlgetresult('DELETE FROM tbl_temp_challans WHERE "challanNo"= \'' . $challanNo['challanNo'] . '\' AND "feeGroup" IS NULL ');
        $rowid = sqlgetresult('SELECT id FROM tbl_temp_challans WHERE "challanNo"= \'' . $challanNo['challanNo'] . '\'',true);
        foreach ($rowid as $k => $row)
        {
            foreach ($row as $key => $value)
            {
                $datas = sqlgetresult("SELECT * FROM createChallan('$value')");
                // print_r($data);exit;
                // $updatestud = sqlgetresult('UPDATE tbl_student SET class = (SELECT "classList" FROM tbl_temp_challans WHERE id= \'' . $value . '\') WHERE "studentId" = (SELECT "studentId" FROM tbl_temp_challans WHERE id = \'' . $value . '\') ');
                
            }
        }
        $getparentmailid = sqlgetresult('SELECT "email" AS parentMailId FROM tbl_student WHERE "studentId"=\'' . $challanNo['studentId'] . '\'');
        $challanData = sqlgetresult('SELECT * FROM challanData WHERE "studentId" =\'' . $challanNo['studentId'] . '\' AND  "challanNo" = \'' . $challanNo['challanNo'] . '\' ', true);
        $feeTypes = sqlgetresult("SELECT * FROM getFeeTypes");
        $mailid = $getparentmailid['parentmailid'];
        $to = $mailid;

        $total = 0;
        $feeData = array();
        foreach ($challanData as $value)
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


            $feetype = explode(',', $value['feeTypes']);
            foreach ($feetype as $v)
            {
                $feeData[trim($v) ][] = $value['feeGroup'];
                $feeData[trim($v) ][] = $value['org_total'];
            }
        }
        $msg = "Hello " . $mailid . "! <br/>";
        $msg .= "<p style='padding-left:20px;'>Please find the new challan created for  " . $challanData1['studentName'] . ".<br/>Please make a note that the <b>CHALLAN</b> has to be PAID on or before <b style='color:red'>" . date("d-m-Y", strtotime($challanData1['duedate'])) . "</b>.<br/>For Online Payment Please <b>LOGIN</b> to our <a href=" . BASEURL . " style='color:red'>FEE PORTAL</a>. </p>";

        $msg .= "<table border='1' style='border:1px solid grey;border-collapse: collapse;' cellpadding='10' width = '100%'>
                <tr><td colspan='2'><label> <b>School Name</b>: </label> LMOIS - CBSE</td></tr>
                <tr><td><label><b>Name</b>: </label> " . $challanData1['studentName'] . " </td><td><label><b>Semester</b>: </label>" . $challanData1['term'] . " </td></tr>
                <tr><td><label><b>ID</b>: </label> " . $challanData1['studentId'] . " </td><td><label><b>Class</b>: </label> " . $challanData1['class_list'] . " </td></tr>
                <tr><td><label><b>Challan Number</b>: </label>" . $challanData1['challanNo'] . "</td><td><label><b>Due Date</b>: </label> " . date("d-m-Y", strtotime($challanData1['duedate'])) . "</td></tr>
                <tr><td colspan='2' style='text-align:center'><b>FEE DETAILS</b></td></tr>";

        $feetypedata = sqlgetresult('SELECT * FROM getfeetypedata WHERE class=\'' . $challanData1['clid'] . '\' AND semester=\'' . $challanData1['term'] . '\' AND stream = \'' . $challanData1['stream'] . '\' ', true);
        foreach ($feeData as $id => $fee)
        {
            foreach ($feetypedata as $val)
            {
                // if (in_array(trim($id) , $val))
                    if ( trim($id) == trim($val['feeType']))
                {
                    $total += $val['amount'];
                    $groupdata[$val['feeGroup']][$val['id']][] = $val['amount'];
                    $groupdata[$val['feeGroup']][$val['id']][] = $val['feename'];
                }
            }
        }

        $tot = 0;
        foreach ($groupdata as $k => $v)
        {
            $msg .= '<tr style="border:0;border-right:1px solid grey;"><td colspan="2"><b>' . $k . '</b></td></tr>';

            foreach ($v as $fee)
            {
                $msg .= '<tr style="border:0;"><td >' . $fee[1] . '</td><td style="border-right:1px solid grey;text-align:right;">' . $fee[0] . '</td></tr>';
                $tot += $fee[0];
            }
        }
        $msg .= '<tr style="border-top:1px solid grey;"><td style="border-top:1px solid grey;"><b>TOTAL</b></td><td style="border-top:1px solid grey;text-align:right;"><b>' . $tot . '</b></td></tr>';
        $msg .= "</table>";

        // print_r($msg);exit;
        $subject = "Fee Challan for " . $challanData1['studentName'] . "";
        $data = $msg;

        // $deletetempchallan = sqlgetresult('DELETE FROM tbl_temp_challans WHERE "challanNo" = \'' . $challanNo['challanNo'] . '\' ');
            if ($datas['createchallan'] == '1')
    {
        $_SESSION['successchallan'] = "<p class='success-msg'>Challan Created Successfully and Mail has been Sent to the parent Mail Id</p>";
        $mblNo = '918939747556';

        $smsTxt = urlencode("Dear Parent, New challan (".$challanData1['challanNo'].")
            has been created for " . $challanData1['studentName'] . " (" . $challanData1['studentId'] . ") for ".$challanData1['term']." sem(".$challanData1['academic_yr'].").");  


        $smsURL = "$smsBaseurl/sms.aspx?Id=$smsLoginId&Pwd=$smsLoginPass&PhNo=$mblNo&text=$smsTxt";
        // $ret = file($smsURL);
        $send = SendMailId($to, $subject, $data);
        // if ($send == true)
        // {http://111.93.105.51/feeapp
        //     $_SESS
        // ION['success'] = "<div class='success-msg'>Mail has been Sent.</div>";
        // }
            header('location:managecreatedchallans.php');

        
    }
    else
    {
        createErrorlog($data);
        $_SESSION['error'] = "<p class='error-msg'>Some error has occurred Please Try Again Later.</p>";
        header('Location:managechallans.php');
    }
        
    }


}

if (isset($_POST['submit']) && $_POST['submit'] == 'feeconfiguration')
{
    $academicyear = getAcademicyrById($_POST['academic']);
    $stream = $_POST['stream'];
    $semester = $_POST['semester'];
    $feetype = $_POST['feetype'];
    // $duedate = $_POST['duedate'];
    $createdby = $_SESSION['myadmin']['adminid'];

    $keys = array_keys($_POST);
    $commondata = array();
    $feeClassbased = array();
    // console.log($commondata);
    // print_r($_POST);
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
    $feeClassbased = array_filter($feeClassbased);
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

        $lastvalue = $value + $newpercentage;
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
            $_SESSION['error'] = "<p class='error-msg'>Same Configurtion Data is already exists.</p>";
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
    $amt = $_POST['amount'];
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
        $_SESSION['error'] = "<p class='error-msg'>Same Configurtion Data is already exists.</p>";
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
    $query = "SELECT * FROM editcomments('$id','$pname','$com','$uid')";

    $run = sqlgetresult($query);
    if ($run['editcomments'] == 1)
    {
        $_SESSION['successcomments'] = "<p class='success-msg'>Data edited successfully.</p>";
        header('location:managecomments.php');
    }
    else if ($run['editcomments'] == 0)
    {
        $_SESSION['errorcomments'] = "<p class='error-msg'>Page Name Already Exixt</p>";
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
    $query = "SELECT * FROM addcomments('$pname','$com','$uid')";
    // print_r($query);
    // exit;
    $run = sqlgetresult($query);
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
    $sql = 'SELECT * FROM commentscheck';
    $res = sqlgetresult($sql, true);
    // print_r($res);
    // exit;
    echo json_encode($res);
    /************Get Comments - End**********/
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
        $_SESSION['successtransport'] = "<p class='success-msg'>Data edited successfully.</p>";
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
    $academic_yr = explode('-',getAcademicyrById($_POST['academic'])) ;
    // print_r($academic_yr);
    if ($_POST['semester'] == 'II')
    {
        $feedata = sqlgetresult('SELECT class,amount FROM tbl_fee_configuration WHERE stream = \'' . $_POST['stream'] . '\' AND semester = \'' . $_POST['semester'] . '\'  AND "feeType" = \'' . $_POST['feetype'] . '\' AND "academicYear" = \'' . getAcademicyrById($_POST['academic']) . '\' ', true);
        if (count($feedata) == 0)
        {
            $feedata = sqlgetresult('SELECT class,amount FROM tbl_fee_configuration WHERE stream = \'' . $_POST['stream'] . '\' AND semester = \'I\'  AND "feeType" = \'' . $_POST['feetype'] . '\' AND "academicYear" = \'' . getAcademicyrById($_POST['academic']) . '\' ', true);
        }
    }
    else if ($academic_yr[0] == (date("Y") + 1))
    {
        $yr = sqlgetresult("select max(id) from tbl_academic_year");
        $yr = $yr['max']-1;

        $feedata = sqlgetresult('SELECT class,amount FROM tbl_fee_configuration WHERE stream = \'' . $_POST['stream'] . '\' AND semester = \'' . $_POST['semester'] . '\'  AND "feeType" = \'' . $_POST['feetype'] . '\' AND "academicYear" = \'' . getAcademicyrById($yr) . '\' ', true);
    }
    else
    {
        $feedata = sqlgetresult('SELECT class,amount FROM tbl_fee_configuration WHERE stream = \'' . $_POST['stream'] . '\' AND semester = \'' . $_POST['semester'] . '\'  AND "feeType" = \'' . $_POST['feetype'] . '\' AND "academicYear" = \'' . getAcademicyrById($_POST['academic']) . '\' ', true);
    }

    echo json_encode($feedata);
}

// Edit Fee Configuration - End
if (isset($_POST['addstudent']) && $_POST['addstudent'] == "Update Amount")
{

    // print_r($_POST);
    $uid = $_SESSION['myadmin']['adminid'];
    $wavierchallan = $_POST['id'];
    $waviergroup = $_POST['grouptype'];
    $wavingamount = $_POST['WavingAmount'];
    $wavingpercentage = $_POST['WavingPercentage'];

    $total = sqlgetresult('SELECT total FROM tbl_challans WHERE "challanNo"=\'' . $wavierchallan . '\' AND "feeGroup"=\'' . $waviergroup . '\'');

// print_r($sid);
// exit;
    $discountamount = ($total['total'] * $wavingpercentage / 100);
    $newtotal = $total['total'] - $discountamount;

    if ($discountamount == $wavingamount)
    {
        $amountwaving = 0;
    }
    else
    {
        $amountwaving = $wavingamount - $discountamount;
        $newtotal = $total['total'] - $wavingamount;

    }
    $sql = "SELECT * FROM updatewavingamount('$wavierchallan','$newtotal','$uid','$waviergroup','$wavingpercentage','$amountwaving','$wavingamount')";
    $res = sqlgetresult($sql);
    $sid = sqlgetresult('SELECT * FROM waviercheck WHERE "challanNo"=\'' . $wavierchallan . '\' AND "feeGroup" = \'' . $waviergroup . '\'');
    $mail = $sid['email'];

    if ($res['updatewavingamount'] == 0)
    {
        $_SESSION['errorwavier'] = "<p class='error-msg'>Some Error Has Occured</p>";

    }
    else
    {
        $_SESSION['successwavier'] = "<p class='success-msg'>Waving Amount has been Credited</p>";
        header("Location:managefeewavier.php");

        $to = $mail;
        $msg = "Hello " . $to . "! <br/>";
        $msg .= "<p style='padding-left:20px;'>Please find the Fee Concession has created for  " . $sid['studentName'] . ".<br/>";
        $msg .= "<table border='1' style='border:1px solid grey;border-collapse: collapse;' cellpadding='10' width = '100%'>
                <tr><td colspan='2'><label> <b>School Name</b>: </label> LMOIS - CBSE</td></tr>
                <tr><td><label><b>Name</b>: </label> " . $sid['studentName'] . " </td><td><label><b>Semester</b>: </label>" . $sid['term'] . " </td></tr>
                <tr><td><label><b>ID</b>: </label> " . $sid['studentId'] . " </td><td><label><b>Class</b>: </label> " . $sid['class_list'] . "</td></tr>
                <tr><td><label><b>Challan Number</b>: </label>" . $sid['challanNo'] . "</td><td><label><b>Section</b>: </label>" . $sid['section'] . "</td></tr>
                <tr><td colspan='2' style='text-align:center'><b>FEE WAVING DETAILS</b></td></tr>
                <tr style='border:0;border-right:1px solid grey;''><td colspan='2'><b>FEEGROUP-" . $sid['feeGroup'] . "</b></td></tr>";

        //        $wavefeetype = explode(",",$sid['feeTypes']);
        //        $feetype = sqlgetresult('SELECT "feeType" FROM tbl_fee_type WHERE "feeGroup" = \''.$waviergroup.'\'' );
        // foreach($wavefeetype as $value){
        // foreach($feetype as $fee) {
        //  if(in_array($value,trim($fee))){
        

        // $msg .= '<tr style="border:0;"><td>'.$fee.'</td><td style="border-right:1px solid grey;">'.$sid['total'].'</td></tr>';
        //       }
        //    }
        // }

        $msg .= '<tr style="border-top:1px solid grey;"><td style="border-top:1px solid grey;"><b>TOTAL</b></td><td style="border-top:1px solid grey;" style="text-align:right;">' . $sid['total'] . '</td></tr>
                <tr style="border-top:1px solid grey;"><td style="border-top:1px solid grey;"><b>WAIVED TOTAL</b></td><td style="border-top:1px solid grey;" style="text-align:right;">' . $sid['waivedTotal'] . '</td></tr>
                <tr style="border-top:1px solid grey;"><td style="border-top:1px solid grey;"><b>TOTAL AMOUNT</b></td><td style="border-top:1px solid grey;" style="text-align:right;">' . $sid['org_total'] . '</td></tr>';

       


        $msg .= "</table>";

        $subject = "Fee Concession Receipt for " . $sid['studentName'] . "";
        $data = $msg;
        // print_r($msg);
        // exit;

        $send = SendMailId($to, $subject, $data);
        $mblNo = '918939747556';
        $smsTxt = urlencode("This is an example for message");
        $smsURL = "$smsBaseurl/sms.aspx?Id=$smsLoginId&Pwd=$smsLoginPass&PhNo=$mblNo&text=$smsTxt";
        // $ret = file($smsURL);

    }

    header("Location:managefeewavier.php");

}
if (isset($_POST['submit']) && $_POST['submit'] == "getgroupamount")
{
    $wavierchallan = $_POST['cno'];
    $waviergroup = $_POST['gt'];

    $total = sqlgetresult('SELECT total FROM tbl_challans WHERE "challanNo"=\'' . $wavierchallan . '\' AND "feeGroup"=\'' . $waviergroup . '\'');
    echo json_encode($total['total']);
}

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
    else
    {
        $sql = ('SELECT * FROM filterstudentdetails WHERE "class"=\'' . $classselect . '\' AND "stream" =\'' . $streamselect . '\' AND "section" = \'' . $sectionselect . '\'');

    }

    $res = sqlgetresult($sql, true);

    echo json_encode($res);

}

if (isset($_POST['filter']) && $_POST['filter'] == "filterfeeconfiguration")
{

    $classselect = $_POST['classselect'];
    $streamselect = $_POST['streamselect'];

    if ($streamselect != '' && $classselect == '')
    {

        $sql = 'SELECT * FROM filterfeedetails WHERE "stream"=\'' . $streamselect . '\' ';

    }
    else if ($classselect != '' && $streamselect == '')
    {

        $sql = 'SELECT * FROM filterfeedetails WHERE "class_list"=\'' . $classselect . '\' ';

    }
    else
    {
        $sql = ('SELECT * FROM filterfeedetails WHERE "class_list"=\'' . $classselect . '\' AND "stream" = \'' . $streamselect . '\'');

    }

    // echo $sql;
    $res = sqlgetresult($sql, true);

    echo json_encode($res);

}
if (isset($_POST['filter']) && $_POST['filter'] == "filterteacher")
{

    $classselect = $_POST['classselect'];
    $sectionselect = $_POST['sectionselect'];

    if ($classselect != '' && $sectionselect == '')
    {
        $sql = 'SELECT s."studentId",s."studentName",
        s.stream,
        str.stream AS streamname,
        s.class,
        c.class_list,
        s.section,
        s.term
   FROM tbl_student s LEFT JOIN tbl_class c ON c.id = s.class::integer
     LEFT JOIN tbl_stream str ON str.id = s.stream::integer WHERE "studentId" NOT IN (SELECT "studentId" FROM   tbl_temp_challans) AND "class" = \'' . $classselect . '\'  ORDER BY s."studentName" ASC';

    }
    else if ($sectionselect != '' && $classselect == '')
    {

        $sql = 'SELECT * FROM filterteacher WHERE "section"=\'' . $sectionselect . '\' ';

    }
    else
    {
        $sql = ('SELECT  s."studentId",s."studentName",
        s.stream,
        str.stream AS streamname,
        s.class,
        c.class_list,
        s.section,
        s.term
   FROM tbl_student s LEFT JOIN tbl_class c ON c.id = s.class::integer
     LEFT JOIN tbl_stream str ON str.id = s.stream::integer WHERE "studentId" NOT IN (SELECT "studentId" FROM   tbl_temp_challans) AND "class" = \'' . $classselect . '\'  AND "section" = \'' . $sectionselect . '\' ORDER BY s."studentName" ASC');

    }

    // echo $sql;
    $res = sqlgetresult($sql, true);

    foreach ($res as $data) {
        $data['term'] = $current_term;
    }

    echo json_encode($res);

}

if (isset($_POST['filter']) && $_POST['filter'] == "filterbut")
{

    $classselect = $_POST['classselect'] != '' ? getClassbyNameId($_POST['classselect']) : $_POST['classselect'];
    $streamselect = $_POST['streamselect'] != '' ? getStreambyId($_POST['streamselect']) : $_POST['streamselect'];
    $sectionselect = $_POST['sectionselect'];

    if ($streamselect != '' && $classselect == '' && $sectionselect == '')
    {
        $sql = 'SELECT * FROM waviercheck WHERE "stream"=\'' . $streamselect . '\' ';

    }
    else if ($classselect != '' && $streamselect == '' && $sectionselect == '')
    {
        $sql = 'SELECT * FROM waviercheck WHERE "class_list"=\'' . $classselect . '\' ';

    }
    else if ($sectionselect != '' && $classselect == '' && $streamselect == '')
    {
        $sql = 'SELECT * FROM  waviercheck WHERE "section"=\'' . $sectionselect . '\' ';
    }
    else if ($classselect != '' && $streamselect != '' && $sectionselect == '')
    {
        $sql = 'SELECT * FROM waviercheck WHERE "class_list"=\'' . $classselect . '\' AND "stream" = \'' . $streamselect . '\'';

    }
    else if ($classselect != '' && $streamselect == '' && $sectionselect != '')
    {
        $sql = 'SELECT * FROM waviercheck WHERE "class_list"=\'' . $classselect . '\' AND "section" = \'' . $sectionselect . '\'';

    }
    else
    {
        $sql = ('SELECT * FROM waviercheck WHERE "class_list"=\'' . $classselect . '\' AND "stream" =\'' . $streamselect . '\' AND "section" = \'' . $sectionselect . '\'');

    }

    $res = sqlgetresult($sql, true);

    // $sql = 'SELECT * FROM waviercheck';
    // $res = sqlgetresult($sql,true);
    $challanData = array();
    $total = 0;
    $tot = 0;
    $orgtotal = 0;
    $challanNo = '';
    $feeData = array();
    if ($res != 0)
    {
        foreach ($res as $k => $data)
        {
            $challanData[$data['challanNo']]['studentName'] = $data['studentName'];
            $challanData[$data['challanNo']]['stream'] = $data['stream'];
            $challanData[$data['challanNo']]['waived'][] = $data['waived'];
            $challanData[$data['challanNo']]['term'] = $data['term'];
            $challanData[$data['challanNo']]['section'] = $data['section'];
            $challanData[$data['challanNo']]['studentId'] = $data['studentId'];
            $challanData[$data['challanNo']]['challanNo'] = $data['challanNo'];
            $challanData[$data['challanNo']]['feeTypes'][] = trim($data['feeTypes']);
            $challanData[$data['challanNo']]['class_list'] = $data['class_list'];
            $challanData[$data['challanNo']]['org_total'][] = $data['org_total'];
            $challanData[$data['challanNo']]['total'][] = $data['total'];
        }
        // print_r($challanData);
        // $total = 0;
        $data = array();
        foreach ($challanData as $feeData)
        {
            $feeData['org_total'] = array_sum($feeData['org_total']);
            $feeData['total'] = array_sum($feeData['total']);
            $feeData['waived'] = array_sum($feeData['waived']);
            $data[] = $feeData;
        }
    }
    else
    {
        $data = array();
    }

    // echo json_encode($res);
    echo json_encode($data);

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
    else
    {
        $sql = ('SELECT * FROM  getpaiddatafilter WHERE "class"=\'' . $classselect . '\' AND "stream" =\'' . $streamselect . '\' AND "section" = \'' . $sectionselect . '\'');

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

    else
    {
        $sql = ('SELECT * FROM  getpaymentdata WHERE "class"=\'' . $classselect . '\' AND "stream" =\'' . $streamselect . '\' AND "section" = \'' . $sectionselect . '\'');

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
    // print_r($_POST);
    $classselect = $_POST['classselect'];
    $streamselect = $_POST['streamselect'];
    $sectionselect = $_POST['sectionselect'];
    $studtypeselect = $_POST['studtype'];

    if ($streamselect != '' && $classselect == '' && $sectionselect == '' && $studtypeselect == '')
    {
        $sql = 'SELECT * FROM gettempdata WHERE "stream"=\'' . $streamselect . '\' ';

    }
    else if ($classselect != '' && $streamselect == '' && $sectionselect == '' && $studtypeselect == '')
    {
        $sql = 'SELECT * FROM  gettempdata WHERE "classList"=\'' . $classselect . '\' ';
    }
    else if ($sectionselect != '' && $classselect == '' && $streamselect == '' && $studtypeselect == '')
    {
        $sql = 'SELECT * FROM  gettempdata WHERE "section"=\'' . $sectionselect . '\' ';
    }
    else if ($sectionselect == '' && $classselect == '' && $streamselect == '' && $studtypeselect != '')
    {
        $sql = 'SELECT * FROM  gettempdata WHERE "hostel_need"=\'' . $studtypeselect . '\' ';
    }
    else if ($classselect != '' && $streamselect != '' && $sectionselect == '' && $studtypeselect == '')
    {
        $sql = 'SELECT * FROM gettempdata WHERE "classList"=\'' . $classselect . '\' AND "stream" = \'' . $streamselect . '\'';

    }
    else if ($classselect != '' && $streamselect == '' && $sectionselect == '' && $studtypeselect != '')
    {
        $sql = 'SELECT * FROM gettempdata WHERE "classList"=\'' . $classselect . '\' AND "hostel_need" = \'' . $studtypeselect . '\'';

    }
    else if ($classselect == '' && $streamselect != '' && $sectionselect == '' && $studtypeselect != '')
    {
        $sql = 'SELECT * FROM gettempdata WHERE "stream"=\'' . $streamselect . '\' AND "hostel_need" = \'' . $studtypeselect . '\'';

    }
    else if ($classselect != '' && $streamselect == '' && $sectionselect != '' && $studtypeselect != '')
    {
        $sql = 'SELECT * FROM  gettempdata WHERE "classList"=\'' . $classselect . '\' AND "section" = \'' . $sectionselect . '\'';

    }
    else if ($classselect != '' && $streamselect != '' && $sectionselect != '' && $studtypeselect == '')
    {
        $sql = ('SELECT * FROM  gettempdata WHERE "classList"=\'' . $classselect . '\' AND "stream" =\'' . $streamselect . '\' AND "section" = \'' . $sectionselect . '\'');
    }

    else if ($classselect != '' && $streamselect != '' && $sectionselect == '' && $studtypeselect != '')
    {
        $sql = ('SELECT * FROM  gettempdata WHERE "classList"=\'' . $classselect . '\' AND "stream" =\'' . $streamselect . '\' AND "hostel_need" = \'' . $studtypeselect . '\'');
    }

    else
    {
        $sql = ('SELECT * FROM  gettempdata WHERE "classList"=\'' . $classselect . '\' AND "stream" =\'' . $streamselect . '\' AND "hostel_need" = \'' . $studtypeselect . '\' AND "section" = \'' . $sectionselect . '\'');
    }

    // print_r($sql);
    $res = sqlgetresult($sql, true);

    $filteredData = array();

    if (count($res) > 0)
    {
        foreach ($res as $data)
        {
            $data['term'] = $current_term;
            $filteredData[] = $data;
        }
    }

    echo json_encode($filteredData);

}
// **********TEMP CHALLAN END************//


//*************TEMP CHALLAN************//
if (isset($_POST['filter']) && $_POST['filter'] == "filternewchallan")
{

    $classselect = $_POST['classselect'];
    $streamselect = $_POST['streamselect'];
    $sectionselect = $_POST['sectionselect'];

    if ($streamselect != '' && $classselect == '' && $sectionselect == '')
    {
        $sql = 'SELECT * FROM getchallandata WHERE "stream"=\'' . $streamselect . '\' ';

    }
    else if ($classselect != '' && $streamselect == '' && $sectionselect == '')
    {
        $sql = 'SELECT * FROM getchallandata WHERE "classList"=\'' . $classselect . '\' ';

    }
    else if ($sectionselect != '' && $classselect == '' && $streamselect == '')
    {
        $sql = 'SELECT * FROM getchallandata WHERE "section"=\'' . $sectionselect . '\' ';

    }

    else if ($classselect != '' && $streamselect != '' && $sectionselect == '')
    {
        $sql = 'SELECT * FROM getchallandata WHERE "classList"=\'' . $classselect . '\' AND "stream" = \'' . $streamselect . '\'';

    }
    else if ($classselect != '' && $streamselect == '' && $sectionselect != '')
    {
        $sql = 'SELECT * FROM getchallandata WHERE "classList"=\'' . $classselect . '\' AND "section" = \'' . $sectionselect . '\'';

    }
    else
    {
        $sql = ('SELECT * FROM getchallandata WHERE "classList"=\'' . $classselect . '\' AND "stream" =\'' . $streamselect . '\' AND "section" = \'' . $sectionselect . '\'');

    }

    $res = sqlgetresult($sql, true);
    // print_r($res);

    $challanData = array();
    $total = 0;
    $tot = 0;
    $challanNo = '';
    $feeData = array();
    if ($res != 0)
    {
        foreach ($res as $k => $data)
        {
            $challanData[$data['challanNo']]['studentName'] = $data['studentName'];
            $challanData[$data['challanNo']]['streamname'] = $data['streamname'];
            $challanData[$data['challanNo']]['section'] = $data['section'];
            $challanData[$data['challanNo']]['term'] = $data['term'];
            $challanData[$data['challanNo']]['challanNo'] = $data['challanNo'];
            $challanData[$data['challanNo']]['feeTypes'][] = trim($data['feeTypes']);
            $challanData[$data['challanNo']]['class_list'] = $data['class_list'];
            $challanData[$data['challanNo']]['duedate'] = $data['duedate'];
            $challanData[$data['challanNo']]['createddate'] = $data['createdOn'];
        }
        $total = 0;
        $data = array();
        foreach ($challanData as $feeData)
        {
            $groupclass = getClassbyName($feeData['class_list']);
            $feeTypes = sqlgetresult('SELECT * FROM getfeetypedata WHERE semester = \'' . $feeData['term'] . '\' AND class = \'' . $groupclass . '\'');

            $fee = implode(',', $feeData['feeTypes']);
            $feeData['feeTypes'] = explode(',', $fee);

            foreach ($feeData['feeTypes'] as $v)
            {
                foreach ($feeTypes as $val)
                {
                    if (in_array(trim($v) , $val))
                    {
                        $total += $val['amount'];
                    }
                }
            }
            $feeData['fee'] = $total;
            $total = 0;
            $data[] = $feeData;
        }
    }
    else
    {
        $data = array();
    }

    echo json_encode($data);

}

// **********TEMP NEW CHALLAN END************//


/*****Tax Table - Start*****/
if (isset($_POST["edityear"]) && $_POST["edityear"] == "update")
{
    $id = $_POST['id'];
    $uid = $_SESSION['myadmin']['adminid'];
    $year = $_POST['year'];
    $query = "SELECT * FROM edityear('$id','$year','$uid')";
    $run = sqlgetresult($query);
    if ($run['edityear'] == 1)
    {

        $_SESSION['successyear'] = "<p class='success-msg'>Data edited successfully.</p>";
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
    $n = 0;
    $uid = $_SESSION['myadmin']['adminid'];
    $year = $_POST['year'];
    $query = "SELECT * FROM addyear('$year','$uid')";
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
    $query = 'DELETE FROM tbl_challans WHERE "challanNo"=\'' . $cn . '\' ';
    // print_r($query);
    // exit;
    $res = sqlgetresult($query);
    if ($res[deleteupdate] == 0)
    {
        $_SESSION['successdelete'] = "<p class='success-msg'>Delete Function Was Done Successfuly</p>";
        header('location:managecreatedchallans.php');
    }
    else
    {
        $_SESSION['errordelete'] = "<p class='error-msg'>Delete Function Was Not Done</p>";
        header('location:managecreatedchallans.php');

    }
}

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
    // echo("hi");
    //         print_r($_POST);
    // exit;
    if (!empty($_POST['checkme']))
    {
        $selectedchallans = [];
        foreach ($_POST['checkme'] as $selected)
        {
            // echo $selected."</br>";s
            array_push($selectedchallans, $selected);
        }
        $_SESSION['selectedchallans'] = $selectedchallans;
        header('location:editStudent.php');
    }
    echo json_encode($selectedchallans);
}

if (isset($_POST['submit']) && $_POST['submit'] == "getwavierchallanno")
{
    $groupdata = array();
    $challan = $_POST['data'];
    $query = 'SELECT "feeGroup" FROM tbl_challans WHERE "challanNo"=\'' . $challan . '\'';
    $res = sqlgetresult($query);
    foreach ($res as $result)
    {
        $feegroup[] = trim($result['feeGroup']);
    }
    echo json_encode($feegroup);
}

if (isset($_POST['editcreatedchallans']) && $_POST['editcreatedchallans'] == "editcreatedchallans")
{
    $studStatus = $_POST['status'];
    $rowid = $_POST['id'];
    $challanNo = $_POST['challan'];
    $id = $_POST['studentId'];
    $name = getStudentNameById($_POST['studentId']);
    $term = $_POST['semester'];
    $class = $_POST['class-list'];
    $stream = $_POST['stream'];
    $feegroup = $_POST['feegroup'];
    $academic = $_POST['academic'];

    if (isset($_POST['duedate']))
    {
        $duedate = $_POST['duedate'];
    }
    else
    {
        $duedate = $_POST['oldduedate'];
    }
    if ((isset($_POST['selected_feetypes'])) && ($_POST['selected_feetypes'] != ''))
    {
        $feetypes = $_POST['selected_feetypes'];
        $selected_feetypes = explode(',', $_POST['selected_feetypes']);
    }
    else
    {
        $feetypes = $_POST['selectedfeetypes'];
        $selected_feetypes = explode(',', $_POST['selectedfeetypes']);
    }

    $createdby = $_SESSION['myadmin']['adminid'];
    if (isset($_POST['remarks']))
    {
        $remarks = $_POST['remarks'];
    }
    else
    {
        $remarks = $_POST['oldremarks'];
    }

    $feetypedata = sqlgetresult('SELECT * FROM getfeetypedata WHERE class=\'' . $class . '\' AND semester=\'' . $term . '\' AND stream = \'' . ($stream) . '\' ', true);
    $selectedids = array();

    $totalamount = 0;
    $selectedfeegroup = array();
    $selectedData = array();
    $feeData = explode(',', $feetypes);
    foreach ($feeData as $k => $v)
    {
        foreach ($feetypedata as $val)
        {
            // if (in_array(trim($v) , $val))
            if ( trim($v) == trim($val['feeType']))
            {
                $groupdata[$val['feeGroup']][$val['id']][] = $val['amount'];
                $groupdata[$val['feeGroup']][$val['id']][] = $val['feename'];
                array_push($selectedfeegroup, $val['feeGroup']);
                $selectedfeegroups = array_unique($selectedfeegroup);

            }
        }
    }

    $feegroups = explode(",", $feegroup);
    $trimmed_feegroup = array_map('trim', $feegroups);

    $selectedData['feeData'] = $groupdata;
    
    foreach ($selectedfeegroups as $extra)
    {
        if (in_array(trim($extra) , $trimmed_feegroup))
        {
            $query = ('SELECT * FROM tbl_challans WHERE "challanNo"=\'' . $challanNo . '\' AND "feeGroup"=\'' . $extra . '\'');
            $res = sqlgetresult($query, true);
            $rowid = $res[0]['id'];
            $waivedperentage = $res[0]['waivedPercentage'];
            $waivedamount = $res[0]['waivedAmount'];
            $waivedtotal = $res[0]['waivedTotal'];
            $orgtotal = $res[0]['org_total'];
           
            if ($groupdata != 0)
            {

                foreach ($groupdata as $grp => $data)
                {
                    if (trim($grp) == trim($extra))
                    {
                        $feegrp = $grp;
                        $feeId = array();
                        $total = 0;
                        foreach ($data as $k => $val)
                        {
                            $feeId[] = $k;
                            $total += $val[0];
                        }
                        $feeIds = implode(',', $feeId);

                        if ($waivedperentage != '')
                        {
                            $discountamount = ($total * $waivedperentage / 100);
                            $newtotal = $total - $discountamount;

                        }
                        else
                        {
                            $newtotal = $orgtotal;
                            $discountamount = $waivedtotal;

                        }

                        if ($waivedamount != 0)
                        {
                            $newtotal = $total - $waivedtotal;
                            $discountamount = $waivedtotal;
                        }

                        $sql = "SELECT * FROM editcreatedchallans('$challanNo','$id','$class','" . $feeIds . "','$term','$studStatus','$createdby','$total','$stream','$remarks','$duedate','$feegrp','$waivedperentage','$waivedamount','$discountamount','$newtotal','$academic')";
                        $result = sqlgetresult($sql);
                        $delete = 'DELETE FROM tbl_challans WHERE "challanNo"=\'' . ($challanNo) . '\' AND "id" = \'' . ($rowid) . '\'';
                        $res = sqlgetresult($delete);
                        $feeId = '';
                        $total = 0;                        
                    }
                }
            }

        }
        else
        {
            if (($groupdata != 0))
            {
                foreach ($groupdata as $grp => $data)
                {
                    if (trim($grp) == trim($extra))
                    {
                        $feegrp = $grp;
                        $feeId = array();
                        $total = 0;
                        foreach ($data as $k => $val)
                        {
                            $feeId[] = $k;
                            $total += $val[0];
                        }
                        $feeIds = implode(',', $feeId);

                        $sql = "SELECT * FROM editcreatedchallans('$challanNo','$id','$class','" . $feeIds . "','$term','$studStatus','$createdby','$total','$stream','$remarks','$duedate','$feegrp','0','0','0','$total','$academic')";

                        $result = sqlgetresult($sql);
                        $feeId = '';
                        $total = 0;                      
                    }
                }
            }

        }
    }
    $getparentmailid = sqlgetresult('SELECT "email" AS parentMailId FROM tbl_student WHERE "studentId"=\'' . $id . '\'');
    $challanData = sqlgetresult('SELECT * FROM challanData WHERE "studentId" =\'' . $id . '\' AND  "challanNo" = \'' . $challanNo . '\' ', true);

        $feeTypes = sqlgetresult("SELECT * FROM getFeeTypes");
        $mailid = $getparentmailid['parentmailid'];
        $to = $mailid;

        $total = 0;
        $feeData = array();
        foreach ($challanData as $value)
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

            $feetype = explode(',', $value['feeTypes']);
            foreach ($feetype as $v)
            {
                $feeData[trim($v) ][] = $value['feeGroup'];
                $feeData[trim($v) ][] = $value['org_total'];
            }
        }
        $msg = "Hello " . $mailid . "! <br/>";
        $msg .= "<p style='padding-left:20px;'>Please find the new challan created for  " . $challanData1['studentName'] . ".<br/>Please make a note that the <b>CHALLAN</b> has to be PAID on or before <b style='color:red'>" . date("d-m-Y", strtotime($challanData1['duedate'])) . "</b>.<br/>For Online Payment Please <b>LOGIN</b> to our <a href=" . BASEURL . " style='color:red'>FEE PORTAL</a>. </p>";

        $msg .= "<table border='1' style='border:1px solid grey;border-collapse: collapse;' cellpadding='10' width = '100%'>
                <tr><td colspan='2'><label> <b>School Name</b>: </label> LMOIS - CBSE</td></tr>
                <tr><td><label><b>Name</b>: </label> " . $challanData1['studentName'] . " </td><td><label><b>Semester</b>: </label>" . $challanData1['term'] . " </td></tr>
                <tr><td><label><b>ID</b>: </label> " . $challanData1['studentId'] . " </td><td><label><b>Class</b>: </label> " . $challanData1['class_list'] . " </td></tr>
                <tr><td><label><b>Challan Number</b>: </label>" . $challanData1['challanNo'] . "</td><td><label><b>Due Date</b>: </label> " . date("d-m-Y", strtotime($challanData1['duedate'])) . "</td></tr>
                <tr><td colspan='2' style='text-align:center'><b>FEE DETAILS</b></td></tr>";

        $feetypedata = sqlgetresult('SELECT * FROM getfeetypedata WHERE class=\'' . $challanData1['clid'] . '\' AND semester=\'' . $challanData1['term'] . '\' AND stream = \'' . $challanData1['stream'] . '\' ', true);
        foreach ($feeData as $id => $fee)
        {
            foreach ($feetypedata as $val)
            {
                // if (in_array(trim($id) , $val))
                if ( trim($id) == trim($val['feeType']))
                {
                    $total += $val['amount'];
                    $groupdata[$val['feeGroup']][$val['id']][] = $val['amount'];
                    $groupdata[$val['feeGroup']][$val['id']][] = $val['feename'];
                }
            }
        }

        $tot = 0;
        foreach ($groupdata as $k => $v)
        {
            $msg .= '<tr style="border:0;border-right:1px solid grey;"><td colspan="2"><b>' . $k . '</b></td></tr>';

            foreach ($v as $fee)
            {
                $msg .= '<tr style="border:0;"><td >' . $fee[1] . '</td><td style="border-right:1px solid grey;text-align:right;">' . $fee[0] . '</td></tr>';
                $tot += $fee[0];
            }
        }
        $msg .= '<tr style="border-top:1px solid grey;"><td style="border-top:1px solid grey;"><b>TOTAL</b></td><td style="border-top:1px solid grey;text-align:right;"><b>' . $tot . '</td></tr></b>';
        $msg .= "</table>";

        $subject = "Updated Fee Challan for " . $challanData1['studentName'] . "";
        $data = $msg;


    if ($result['editcreatedchallans'] == 1)
    {
        $_SESSION['successdelete'] = "<p class='success-msg'>Challan Has Been Updated and Mail has been Sent to the parent Mail Id</p>";
         $mblNo = '918939747556';
        $smsTxt = urlencode("This is an example for message");
        $smsURL = "$smsBaseurl/sms.aspx?Id=$smsLoginId&Pwd=$smsLoginPass&PhNo=$mblNo&text=$smsTxt";
        // $ret = file($smsURL);
        $send = SendMailId($to, $subject, $data);
        header("Location:managecreatedchallans.php");
    }
    else
    {
        $_SESSION['errordelete'] = "<p class='error-msg'>Some Error Has Occured</p>";
        header("Location:managecreatedchallans.php");
    }

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

?>
