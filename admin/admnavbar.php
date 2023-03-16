
<?php
  include_once('../header.php');
  checkadmsession(); 
  $roleid=isset($_SESSION['myadmin']['adminrole'])?$_SESSION['myadmin']['adminrole']:"";
  $menu=[];
  $menudetails=[];
  if(!empty($roleid)){
    $st=1;
    $sqlmenu = 'SELECT mm.mainmenu, sm.submenu, sm.link FROM tbl_admin_menu_access ac JOIN tbl_admin_submenu sm ON(ac."menuId"=sm.id) JOIN tbl_adminmainmenu mm ON(mm.id=sm."mainmenuId") WHERE ac."roleId"=\'' . $roleid . '\' AND ac.status=\'' . $st . '\' AND sm.status=\'' . $st . '\'  ORDER BY mm."displayOrder",sm."displayOrder"';
     $resmenu = sqlgetresult($sqlmenu, true);
     $numm=count($resmenu);
     if($numm > 0 )
     {
        foreach($resmenu as $key => $datamenu){
            $main=trim($datamenu['mainmenu']);
            $menudetails['smenu']=trim($datamenu['submenu']);
            $menudetails['link']=trim($datamenu['link']);
            $menu[$main][]=$menudetails;
        }
     }   
  }
  $p_methods= array('atom','razorpay');
?> 
<nav class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>                        
            </button>
            <a class="navbar-brand" >Omega Fee App</a>
        </div>
        <div class="collapse navbar-collapse" id="myNavbar">
            <ul class="nav navbar-nav">
            </ul>
            <ul class="nav navbar-nav navbar-right" id="top_menu">
                <!--<li class="active"><a href="home.php">Home</a></li>-->
                <?php
                    if($_SESSION['sessLoginType'] == 'Admin') {
                        if(count($menu) > 0){

                       foreach ($menu as $key => $value) {
                           // code...
                       
                ?>
                 <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#"><?php echo $key; ?><span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <?php foreach ($value as $key1 => $value1) {
                            ?>
                              <li><a href="<?php echo $value1['link']; ?>"><?php echo $value1['smenu']; ?></a></li>
                            <?php
                            // code...
                        } ?>
                    </ul>
                </li>
                
                <?php
                 }
                   }
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
<style type="text/css">
    .form-group{
        padding: 3px;
    }
    #DataTables_Table_0{
        width: 99%;
    }
</style>