
<?php
  include_once('../header.php');
  checkadmsession(); 
  // print_r($_SESSION);
?> 
<nav class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>                        
            </button>
            <a class="navbar-brand" href="#">Omega Fee App</a>
        </div>
        <div class="collapse navbar-collapse" id="myNavbar">
            <ul class="nav navbar-nav">
            </ul>
            <ul class="nav navbar-nav navbar-right" id="top_menu">
                <li class="active"><a href="home.php">Home</a></li>
                <?php
                    if($_SESSION['sessLoginType'] == 'Admin') {
                ?>
                 <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">Challan Management<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="managechallans.php">Temporary Challans</a></li>
                        <li><a href="managecreatedchallans.php">Challans</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">Fees Management<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="feeconfiguration.php">Fee Configuration</a></li>
                        <li><a href="managefeetype.php">Fee Types</a></li>
                        
                        <li><a href="feeentryreport.php">Fee Entry Report </a></li>
                        <li><a href="paymentreport.php">Payment Report</a></li>
                       
                        <li><a href="managelatefee.php">Late Fee</a></li>
                        <li><a href="managefeewavier.php">Fee Waiver</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">User Management<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="mainpage.php">Admin</a></li>
                        <li><a href="managepar.php">Parents</a></li>
                        <li><a href="managestd.php">Students</a></li>
                        <li><a href="manageteachers.php">Class Coordinators</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">Others<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="manageyear.php">Academic Year</a></li>
                        <li><a href="manageclass.php">Class</a></li>
                        <li><a href="managestream.php">Stream</a></li>
                         <li><a href="managetax.php">Tax</a></li>
                        <li><a href="managecomments.php">Messages</a></li>
                        <li><a href="managetransport.php">Transport</a></li>

                    </ul>
                </li>
                <?php
                    }
                ?>     
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">My Accounts<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="changepassword.php">Change Password</a></li>
                         <li><a href="admlogout.php"><i class="fa fa-user icons"></i> Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>