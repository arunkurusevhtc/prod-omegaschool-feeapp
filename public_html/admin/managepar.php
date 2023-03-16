<?php
    include_once('admnavbar.php');
?>

<div>
   <div class="row col-md-12">
         <div class="col-xs-1 col-sm-2 col-md-3 col-lg-4"></div>
         <div class="col-xs-10 col-sm-8 col-md-6 col-lg-4">
               

                    
                     <?php
                  if(isset($_SESSION['successpar'])) {
                  echo $_SESSION['successpar'];
                  unset($_SESSION['successpar']);
                      }
                  if(isset($_SESSION['errorpar'])) {
                  echo $_SESSION['errorpar'];
                  unset($_SESSION['errorpar']);
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
   <h2 class="top" style="padding-left: 15px;">PARENTS </h2>
   
      <div class="container-fluid">
         <div class="table-responsive">
            <table class="table table-bordered admintab dataTableParents">
            <!-- <caption class="title">Sales Data of Electronic Division</caption> -->
               <thead>
                  <tr>
                  <th>S.NO</th>
                  <th>First Name</th>
                  <th>Last Name</th>
                  <th>User Name</th>
                  <th>Email</th>
                  <th>Phone Number</th>
                  <th>Mobile NUumber</th>
                  <th>Status</th>
                  <th>Actions</th>
                  </tr>
               </thead>

               <tbody>

                  <?php
                  $count=1;
                  $sql = 'SELECT * FROM parentcheck';
                  $res = sqlgetresult($sql,true);
                  $num = ($res!= null) ? count($res) : 0;
                  if($num > 0)
                  {

                  foreach ($res as $par)
                  {
                  ?>
                     <tr>
                        <td><?php echo $count; ?></td>
                        <td><?php echo trim($par["firstName"]); ?></td>
                        <td><?php echo trim($par["lastName"]); ?></td>
                        <td><?php echo trim($par["userName"]); ?></td>
                        <td><?php echo trim($par["email"]); ?></td>
                        <td><?php echo trim($par["phoneNumber"]); ?></td>
                        <td><?php echo trim($par["mobileNumber"]); ?></td>
                        <td><?php echo trim($par["status"]); ?></td>
                        <td class="fafa">
                           <?php if($par["status"] =="ACTIVE"){?>
                           <a href="adminactions.php?status=ACTIVE&id=<?php echo $par["id"];?>&page=p"><i class="fa fa-check fafaactive"></i></a>
                           <?php }else{?>
                           <a href="adminactions.php?status=INACTIVE&id=<?php echo
                           $par["id"];?>&page=p"><i class="fa fa-close fafainactive"></i></a>
                           <?php } ?>
                           <a href="editpar.php?id=<?php echo $par["id"]; ?>"><i class="fa fa-edit"></i></a> 
                           <a href="adminactions.php?action=delete&id=<?php echo $par["id"]; ?>&page=p"><i class="fa fa-trash-o"></i></a>
                        </td>
                     </tr>
                     <?php $count++;
                     } 
                  }
                  // else {
                  //                  echo "<tr><td colspan='9'>No Data Avaiable.</td></tr>";
                  //                 }
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