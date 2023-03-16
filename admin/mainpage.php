<?php
require_once('admnavbar.php');
?>

<div>
   <div class="row col-md-12">
         <div class="col-xs-1 col-sm-2 col-md-3 col-lg-4"></div>
         <div class="col-xs-10 col-sm-8 col-md-6 col-lg-4">
               

                    
                     <?php
                  if(isset($_SESSION['successadm'])) {
                  echo $_SESSION['successadm'];
                  unset($_SESSION['successadm']);
                      }
                  if(isset($_SESSION['erroradm'])) {
                  echo $_SESSION['erroradm'];
                  unset($_SESSION['erroradm']);
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
   <h2 class="top"  style="padding-left: 15px;">ADMIN </h2>
   
         <div class="container-fluid">
         
         <div class="table-responsive">
            <table class="table table-bordered admintab dataTableAdmin">
            <!-- <caption class="title">Sales Data of Electronic Division</caption> -->
               <thead>
                  <tr>
                  <th>S.No</th>
                  <th>Admin Name</th>
                  <th>Admin Email</th>
                  <th>Admin Role</th>
                  <th>Status</th>
                  <th>Action</th>
                  </tr>
               </thead>

               <tbody>

                  <?php
                  $count=1;
                  //$sql = 'SELECT * FROM admincheck';
                  $sql = 'SELECT a.id, a."adminName", a."adminEmail", a."status", b."role" FROM admincheck  a JOIN adminrolesscheck b ON (a."adminRole"=b.id)';
                  $res = sqlgetresult($sql,true);
                  $num = ($res!= null) ? count($res) : 0;
                  if($num > 0)
                  {

                  foreach ($res as $item)
                  {
                  ?>
                     <tr>
                        <td><?php echo $count; ?></td>
                        <td><?php echo trim($item["adminName"]); ?></td>
                        <td><?php echo trim($item["adminEmail"]); ?></td>
                        <td><?php echo trim($item["role"]); ?></td>
                        <td><?php echo trim($item["status"]); ?></td>
                        <td class="fafa">
                           <?php if($item["status"] =="ACTIVE"){?>
                           <a href="adminactions.php?status=ACTIVE&id=<?php echo $item["id"];?>&page=a"><i class="fa fa-check fafaactive"></i></a>
                           <?php }else{?>
                           <a href="adminactions.php?status=INACTIVE&id=<?php echo
                           $item["id"];?>&page=a"><i class="fa fa-close fafainactive"></i></a>
                           <?php } ?>
                           <a href="editadm.php?id=<?php echo $item["id"]; ?>"><i class="fa fa-edit"></i></a> 
                           <a href="adminactions.php?action=delete&id=<?php echo $item["id"]; ?>&page=a"><i class="fa fa-trash-o"></i></a>
                        </td>
                     </tr>
                     <?php $count++;
                      } 
                     }
                     // else {
                     //   echo "<tr><td colspan='5' style='text-align:center;'>No Data Avaiable.</td></tr>";
                     // }
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