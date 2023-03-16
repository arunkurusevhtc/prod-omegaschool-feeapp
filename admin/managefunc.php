<?php
   require_once('admnavbar.php');
?>

<div class="container-fluid">
   <div class="row col-md-12">
         <div class="col-xs-1 col-sm-2 col-md-3 col-lg-4"></div>
         <div class="col-xs-10 col-sm-8 col-md-6 col-lg-4">  
               <?php                
                  if(isset($_SESSION['success'])) {
                     echo $_SESSION['success'];
                     unset($_SESSION['success']);
                  }
                  if(isset($_SESSION['error'])) {
                     echo $_SESSION['error'];
                     unset($_SESSION['error']);
                  }
               ?>
         </div>
         <div class="col-xs-1 col-sm-2 col-md-3 col-lg-4"></div>
   </div>   
   <div class="col-md-12 p-r-0">
      <h2 class="top">Manage Functions</h2>
   </div>
   <div class="container-fluid col-md-12">
      <div class="table-responsive">
         <table class="table table-bordered admintab">
            <thead>
               <tr>
               <th>S.No</th>
               <th>Name</th>
               <th>Description</th>
               <th>Status</th>              
               <th>Action</th>
               </tr>
            </thead>
            <tbody>
               <?php
               $count=1;
               $sql = 'SELECT * FROM bkfunctioncheck';
               $res = sqlgetresult($sql,true);
               // print_r($res);
               $num = ($res!= null) ? count($res) : 0;
               if($num > 0)
               {
               foreach ($res as $fee)
               {
               ?>
                  <tr>
                     <td><?php echo $count; ?></td>
                     <td><?php echo trim($fee["name"]); ?></td>
                     <td><?php echo trim($fee["description"]); ?></td>
                     <td><?php echo trim($fee["status"]); ?></td>
                     <td class="fafa">
                        <?php if($fee["status"] =="ACTIVE"){?>
                        <a href="adminactions.php?status=ACTIVE&id=<?php echo $fee["id"];?>&page=ctrlfunction"><i class="fa fa-check fafaactive"></i></a>
                        <?php }else{?>
                        <a href="adminactions.php?status=INACTIVE&id=<?php echo
                        $fee["id"];?>&page=ctrlfunction"><i class="fa fa-close fafainactive"></i></a>
                        <?php } ?>
                     </td>
                  </tr>
                  <?php $count++;
                  } 
               }

                  ?>
            </tbody>
         </table>
      </div>
   </div> 
</div>
<div class="row comment"></div>

<?php
   include_once(BASEPATH.'footer.php');
?>