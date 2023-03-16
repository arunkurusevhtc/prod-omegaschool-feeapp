<?php
  include_once('header.php');
  checksession();  
  $parentid=$_SESSION['uid'];
  $items=countAddtocart($studentId=NULL,$parentid);
  // print_r($_SESSION);
?> 
<nav class="navbar navbar-default commentsload">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>                        
      </button>
      <a class="navbar-brand">Omega Fee App</a>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav">
        
      </ul>
      <ul class="nav navbar-nav navbar-right" id="top_menu">
        <li class="active"><a href="studetscr.php"><i class="fa fa-users icons"></i> My Children</a></li>
        <li><a href="myaccount.php"><i class="fa fa-cog icons" ></i> My Info</a></li>
        <li class="dropdown">
            <a href="#" class="dropdown-toggle case" data-toggle="dropdown"><i class="fa fa-user icons"></i> Non-fee Payments&nbsp;<b class="caret"></b></a>
            <ul class="dropdown-menu">
                <li><a href="nonfeepayments.php">Non-fee with Challans</a></li>
                <li><a href="commonnonfee.php">Common Non-fee</a></li>
            </ul>
        </li>
        <li class="dropdown">
            <a href="#" class="dropdown-toggle case" data-toggle="dropdown"><i class="fa fa-user icons"></i> Other Fees&nbsp;<b class="caret"></b></a>
            <ul class="dropdown-menu">
                <li><a href="lunchfee.php">Lunch Fee</a></li>
                <li><a href="uniformfee.php">Uniform Fee</a></li>
                <li><a href="transportfee.php">Transport Fee</a></li>
                <li><a href="cfee.php">Common Fee</a></li>
            </ul>
        </li>
         <li class="dropdown">
            <a href="cartcheckout.php"><i class="fa fa-shopping-cart icons"></i> Cart&nbsp;<span class="badge badge-light cart-count" id="cart-count" style="background-color: red;"><?php if($items > 0) { echo $items; } ?></span></a>
        </li>
        <li class="dropdown hidden-xs">
            <a href="#" class="dropdown-toggle case" data-toggle="dropdown"><i class="fa fa-user icons"></i> Reports&nbsp;<b class="caret"></b></a>
            <ul class="dropdown-menu">
                <li><a href="consolidated.php">Payment History</a></li>
            </ul>
        </li>
        <li class="dropdown hidden-xs">
          <a href="#" class="dropdown-toggle case" data-toggle="dropdown"><i class="fa fa-user icons"></i> <?php echo $_SESSION['fstname'];echo $_SESSION['lstname'];?>&nbsp;<b class="caret"></b></a>
          <ul class="dropdown-menu">
            <li><a href="changepass.php">Change Password</a></li>
            <li><a href="logout.php">Logout</a></li>
          </ul>
        </li>
        <li class="visible-xs"><a href="changepass.php">Change Password</a></li>
        <li class="visible-xs"><a href="logout.php"><i class="fa fa-user icons"></i> Logout</a></li>
      </ul>
    </div>
  </div>
</nav>

