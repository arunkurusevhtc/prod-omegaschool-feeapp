<?php
require_once('admnavbar.php');
?>

<div>
   <div class="row col-md-12">
         <div class="col-xs-1 col-sm-2 col-md-3 col-lg-4"></div>
         <div class="col-xs-10 col-sm-8 col-md-6 col-lg-4">
               

                    
                     <?php
                  if(isset($_SESSION['successcomments'])) {
                  echo $_SESSION['successcomments'];
                  unset($_SESSION['successcomments']);
                      }
                  if(isset($_SESSION['errorcomments'])) {
                  echo $_SESSION['errorcomments'];
                  unset($_SESSION['errorcomments']);
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
   <h2 class="top">MESSAGES</h2>
   </div>
  
      <div class="container-fluid col-md-12">
         <div class="table-responsive">
            <table class="table table-bordered admintab dataTableComments">
            <!-- <caption class="title">Sales Data of Electronic Division</caption> -->
               <thead>
                  <tr>
                  <th>S.No</th>
                  <th>Page Name</th>
                  <th>Page Content</th>
                  <th>Start Date</th>
                  <th>End Date</th>
                  <th>Status</th>
                  <th>Action</th>
                  </tr>
               </thead>

               <tbody>

                  <?php
                  $count=1;
                  $sql = 'SELECT * FROM commentscheck';
                  $res = sqlgetresult($sql,true);
                  $num = ($res!= null) ? count($res) : 0;
                  if($num > 0)
                  {
                  foreach ($res as $com)
                  {
                  ?>
                     <tr>
                        <td><?php echo $count; ?></td>
                        <td><?php echo trim($com["pageName"]); ?></td>
                        <td><?php echo trim($com["comments"]); ?></td>
                        <td><?php echo trim($com["startdate"]); ?></td>
                        <td><?php echo trim($com["enddate"]); ?></td>
                        <td><?php echo trim($com["status"]); ?></td>
                        <td class="fafa">
                           <?php if($com["status"] =="ACTIVE"){?>
                           <a href="adminactions.php?status=ACTIVE&id=<?php echo $com["id"];?>&page=com"><i class="fa fa-check fafaactive"></i></a>
                           <?php }else{?>
                           <a href="adminactions.php?status=INACTIVE&id=<?php echo
                           $com["id"];?>&page=com"><i class="fa fa-close fafainactive"></i></a>
                           <?php } ?>
                           <a href="editcomments.php?id=<?php echo $com["id"]; ?>"><i class="fa fa-edit"></i></a> 
                           <a href="adminactions.php?action=delete&id=<?php echo $com["id"]; ?>&page=com"><i class="fa fa-trash-o"></i></a>
                        </td>
                     </tr>
                     <?php $count++;
                     } 
                  }
                  // else {
                  //        echo "<tr><td colspan='5' style='text-align:center;'>No Data Avaiable.</td></tr>";
                  //      }
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