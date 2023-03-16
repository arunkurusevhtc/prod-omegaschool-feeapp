<?php
require_once('admnavbar.php');
?>
<div>
   <div class="row col-md-12">
         <div class="col-xs-1 col-sm-2 col-md-3 col-lg-4"></div>
         <div class="col-xs-10 col-sm-8 col-md-6 col-lg-4">
               

                    
                     <?php
                  if(isset($_SESSION['successclass'])) {
                  echo $_SESSION['successclass'];
                  unset($_SESSION['successclass']);
                      }
                  if(isset($_SESSION['errorclass'])) {
                  echo $_SESSION['errorclass'];
                  unset($_SESSION['errorclass']);
                      }
                  if(isset($_SESSION['success'])) {
                  echo $_SESSION['success'];
                  unset($_SESSION['success']);
                      }
                  if(isset($_SESSION['failure'])) {
                  echo $_SESSION['failure'];
                  unset($_SESSION['failure']);
                      }


                    ?>
         </div>
         <div class="col-xs-1 col-sm-2 col-md-3 col-lg-4"></div>
   </div>   
   <div class="col-md-12 p-r-0">
   <h2 class="top">Manage Admin Menus</h2>
</div>
      <div class="container-fluid col-md-12">
         <div class="table-responsive">
            <table class="table table-bordered admintab dataTableAdminMenus">
            <!-- <caption class="title">Sales Data of Electronic Division</caption> -->
               <thead>
                  <tr>
                  <th>S.No</th>
                  <th>Main Menu</th>
                  <th>SUb Menu</th>
                  <th>Link</th>
                  <th>Order</th>
                  <th>Status</th>
                  <th>Action</th>
                  </tr>
               </thead>
               <tbody>
                  <?php
                  $count=1;
                  $sql = 'SELECT mm.mainmenu,sm.id, sm.submenu, sm.link, sm."displayOrder", sm.status FROM tbl_admin_submenu sm JOIN tbl_adminmainmenu mm ON(mm.id=sm."mainmenuId")  ORDER BY sm.id DESC';
                  $res = sqlgetresult($sql,true);
                  $num = ($res!= null) ? count($res) : 0;
                  if($num > 0)
                  {
                  foreach ($res as $clas)
                  {
                  ?>
                     <tr>
                        <td><?php echo $count; ?></td>
                        <td><?php echo trim($clas["mainmenu"]); ?></td>
                        <td><?php echo trim($clas["submenu"]); ?></td>
                        <td><?php echo trim($clas["link"]); ?></td>
                        <td><?php echo trim($clas["displayOrder"]); ?></td>
                        <td><?php echo trim($clas["status"]); ?></td>
                        <td class="fafa">
                           <?php if($clas["status"] ==1){?>
                           <a href="adminactions.php?status=ACTIVE&id=<?php echo $clas["id"];?>&page=menu"><i class="fa fa-check fafaactive"></i></a>
                           <?php }else{?>
                           <a href="adminactions.php?status=INACTIVE&id=<?php echo
                           $clas["id"];?>&page=menu"><i class="fa fa-close fafainactive"></i></a>
                           <?php } ?>
                           <a href="editmenu.php?id=<?php echo $clas["id"]; ?>"><i class="fa fa-edit"></i></a> 
                           <a href="adminactions.php?action=deletesubmenu&id=<?php echo $clas["id"]; ?>"><i class="fa fa-trash-o"></i></a>
                        </td>
                     </tr>
                     <?php $count++;
                     } 
                  }
                  // else {
                  //       echo "<tr><td colspan='5' style='text-align:center;'>No Data Avaiable.</td></tr>";
                  //       }
                     ?>


               </tbody>

            </table>
         </div>
      </div>
   </div>
</div>
<div class="row comment">
       
</div>
<?php
    





include_once(BASEPATH.'footer.php');
?>