<?php 
require_once('admnavbar.php');

/*function getAdminMenus(){
    $st=1;
    $menu=[];
    $menudetails=[];
    $sqlmenu = 'SELECT mm.mainmenu,sm.id, sm.submenu, sm.link FROM tbl_admin_submenu sm JOIN tbl_adminmainmenu mm ON(mm.id=sm."mainmenuId") WHERE mm.status=\'' . $st . '\' AND sm.status=\'' . $st . '\'';
    $resmenu = sqlgetresult($sqlmenu, true);
    $numm=count($resmenu);
    if($numm > 0 )
    {
        foreach($resmenu as $key => $datamenu){
            $main=trim($datamenu['mainmenu']);
            $menudetails['sid']=trim($datamenu['id']);
            $menudetails['smenu']=trim($datamenu['submenu']);
            $menudetails['link']=trim($datamenu['link']);
            $menu[$main][]=$menudetails;
        }
    }
    return $menu; 
}*/


function getAdminMenuAccess(){
    $st=1;
    $menu=[];
    $menudetails=[];
    $sqlmenu = 'SELECT ac.id as accessid, mm.mainmenu,sm.id, sm.submenu, sm.link,ac."roleId",ar.role,ac.status FROM tbl_admin_menu_access ac JOIN tbl_admin_submenu sm ON(ac."menuId"=sm.id) JOIN tbl_adminmainmenu mm ON(mm.id=sm."mainmenuId") JOIN tbl_adminroles ar ON(ac."roleId"=ar.id)  WHERE ar.status=\'' . $st . '\' AND mm.status=\'' . $st . '\' AND sm.status=\'' . $st . '\' ORDER BY ac."roleId",mm."displayOrder",sm."displayOrder"';
    $resmenu = sqlgetresult($sqlmenu, true);
    $numm=count($resmenu);
    if($numm > 0 )
    {
        foreach($resmenu as $key => $datamenu){
            $role=trim($datamenu['role']);
            $main=trim($datamenu['mainmenu']);
            $menudetails['accessid']=trim($datamenu['accessid']);
            $menudetails['smenu']=trim($datamenu['submenu']);
            $menudetails['status']=trim($datamenu['status']);
            $menu[$role][$main][]=$menudetails;
        }
    }
    return $menu; 
}

$allMenus=getAdminMenuAccess();
$numrole=count($allMenus);





?>
<div class="container_fluid">
<div class="col-lg-12">
<div class="errormessage">
<?php
    if(isset($_SESSION['successcheque'])) {
       echo $_SESSION['successcheque'];
       unset($_SESSION['successcheque']);
    } elseif(isset($_SESSION['errorcheque'])) {
       echo $_SESSION['errorcheque'];
       unset($_SESSION['errorcheque']);
    }
    ?>
</div>
<div class="row well">
    <p class="heading">Manage Admin Access</p>
    <div class="main">
    <form method="post" id="studDataModal1" action="adminactions.php">
        <div class="challandetailsNew">
        <?php
        $unique=[];
        $allids="";
        if($numrole > 0 )
        {
            $i=1;
            foreach($allMenus as $key=>$menu){
            ?>
                <div class="row well">
                    <div class="col-lg-4">
                    <input class="feegroupcheckNew" type="checkbox" name="feetypechk[]" value="<?php echo $i; ?>">&nbsp;<label for ="ftype" class="control-label"><?php echo $key; ?></label>
                    </div>
                    <div class="col-lg-8 well" id="<?php echo $i; ?>" style="display: none;">
                    <?php
                    foreach($menu as $key1=>$submenu){
                    ?>
                    <div class="form-group row">
                     <label class="control-label"><?php echo $key1; ?></label>
                     </div>
                     <?php 
                    foreach($submenu as $key2=>$smenudetails){
                         $chk="";
                         $status=$smenudetails['status'];
                         if($smenudetails['status']==1){
                            $chk="checked=checked";
                         }
                         $accessIds=$smenudetails['accessid'];
                         $unique[]=$accessIds;
                     ?>
                     <div class="form-group row">
                        <div class="col-lg-8">
                        <input class="feegroupcheckNew" type="checkbox" name="<?php echo $accessIds; ?>" value="1" <?php echo $chk; ?>>&nbsp;<?php echo $smenudetails['smenu']; ?>
                        </div>
                     </div>   
                    <?php } ?>

                    <?php } ?>

                    </div>
                </div>
            <?php
            $i++;
            }
        }
        if(count($unique) > 0){
            $allids=implode(",",$unique);
        }
        ?>
        <div class="text-center">
            <input type="hidden" name="role_access_ids" value="<?php echo $allids; ?>">
            <button type="button" id="closepay_ftype" class="btn btn-default" name='close' value="confirm">Close</button>
            <button type="submit" name='add_access' value="confirm" class="btn btn-primary" >Submit</button>
        </div>
    </form>
        </div>
    </div>
</div>
<div class="col-sm-2 col-md-3 col-lg-3"></div>
</div>

</div>
<div class="row comment">
</div>

<?php
include_once(BASEPATH.'footer.php');
?>