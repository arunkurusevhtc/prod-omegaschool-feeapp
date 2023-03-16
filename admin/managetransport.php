<?php
require_once('admnavbar.php');
?>

<div>
   <div class="row col-md-12">
         <div class="col-xs-1 col-sm-2 col-md-3 col-lg-4"></div>
         <div class="col-xs-10 col-sm-8 col-md-6 col-lg-4">
               

                    
                     <?php
                  if(isset($_SESSION['successtransport'])) {
                  echo $_SESSION['successtransport'];
                  unset($_SESSION['successtransport']);
                      }
                  if(isset($_SESSION['errortransport'])) {
                  echo $_SESSION['errortransport'];
                  unset($_SESSION['errortransport']);
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
   <h2 class="top">TRANSPORT</h2>
  </div>
   <!-- <a href="addclass.php"><button class="btn btn-info butt">Add Class</button></a> -->
      <div class="container-fluid col-md-12">
         <div class="table-responsive">
            <table class="table table-bordered admintab dataTableTransport">
            <!-- <caption class="title">Sales Data of Electronic Division</caption> -->
               <thead>
                  <tr>
                  <th>S.No</th>
                  <th>Pick-Up Point</th>
                  <th>Drop-Down Point</th>
                  <th>Stage</th>
                  <th>Amount</th>
                  <th>Status</th>
                  <th>Action</th>
                  </tr>
               </thead>

               <tbody>

                  <?php
                  $count=1;
                  $sql = 'SELECT * FROM transportcheck';
                  $res = sqlgetresult($sql,true);
                  $num = ($res!= null) ? count($res) : 0;
                  if($num > 0)
                  {
                  foreach ($res as $trans)
                  {
                  ?>
                     <tr>
                        <td><?php echo $count; ?></td>
                        <td><?php echo trim($trans["pickUp"]); ?></td>
                        <td><?php echo trim($trans["dropDown"]); ?></td>
                        <td><?php echo trim($trans["stage"]); ?></td>
                        <td class="text-right"><?php echo trim($trans["amount"]); ?></td>
                        <td><?php echo trim($trans["status"]); ?></td>
                        <td class="fafa">
                           <?php if($trans["status"] =="ACTIVE"){?>
                           <a href="adminactions.php?status=ACTIVE&id=<?php echo $trans["id"];?>&page=tr"><i class="fa fa-check fafaactive"></i></a>
                           <?php }else{?>
                           <a href="adminactions.php?status=INACTIVE&id=<?php echo
                           $trans["id"];?>&page=tr"><i class="fa fa-close fafainactive"></i></a>
                           <?php } ?>
                           <a href="edittransport.php?id=<?php echo $trans["id"]; ?>"><i class="fa fa-edit"></i></a> 
                           <a href="adminactions.php?action=delete&id=<?php echo $trans["id"]; ?>&page=tr" onclick="return confirmDelete('Stage')"><i class="fa fa-trash-o"></i></a>
                        </td>
                     </tr>
                     <?php $count++;
                     } 
                  }
                  // else {
                  //         echo "<tr><td colspan='7'>No Data Avaiable.</td></tr>";
                  //        }
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