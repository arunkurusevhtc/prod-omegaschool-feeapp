<?php
    include_once('admnavbar.php');
?>

<div>
   <div class="row col-md-12">
         <div class="col-xs-1 col-sm-2 col-md-3 col-lg-4"></div>
         <div class="col-xs-10 col-sm-8 col-md-6 col-lg-4">
               

                    
                     <?php
                  if(isset($_SESSION['successyear'])) {
                  echo $_SESSION['successyear'];
                  unset($_SESSION['successyear']);
                      }
                  if(isset($_SESSION['erroryear'])) {
                  echo $_SESSION['erroryear'];
                  unset($_SESSION['erroryear']);
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
   <h2 class="top">ACADEMIC YEAR</h2> 
   </div>  
      <div class="container-fluid col-md-12">
         <div class="table-responsive">
            <table class="table table-bordered admintab dataTableYear">
            <!-- <caption class="title">Sales Data of Electronic Division</caption> -->
               <thead>
                  <tr>
                  <th>S.No</th>
                  <th>Year</th>
                  <th>Status</th>
                  <th>Actions</th>
                  </tr>
               </thead>

               <tbody>

                  <?php
                  $count=1;
                  $sql = 'SELECT * FROM yearcheck';
                  $res = sqlgetresult($sql,true);
                  $num = ($res!= null) ? count($res) : 0;
                  if($num > 0)
                  {
                  foreach ($res as $year)
                  {
                  ?>
                     <tr>
                        <td><?php echo $count; ?></td>
                        <td><?php echo trim($year["year"]); ?></td>
                        <td><?php echo trim($year["status"]); ?></td>
                        <td class="fafa">
                           <?php if($year["status"] =="ACTIVE"){?>
                           <a href="adminactions.php?status=ACTIVE&id=<?php echo $year["id"];?>&page=ye"><i class="fa fa-check fafaactive"></i></a>
                           <?php }else{?>
                           <a href="adminactions.php?status=INACTIVE&id=<?php echo
                           $year["id"];?>&page=ye"><i class="fa fa-close fafainactive"></i></a>
                           <?php } ?>
                           <a href="edityear.php?id=<?php echo $year["id"]; ?>"><i class="fa fa-edit"></i></a> 
                           <a href="adminactions.php?action=delete&id=<?php echo $year["id"]; ?>&page=ye"><i class="fa fa-trash-o"></i></a>
                        </td>
                     </tr>
                     <?php $count++;
                     } 
                     }
                     // else {
                     //               echo "<tr><td colspan='4'>No Data Avaiable.</td></tr>";
                     //              }
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