<?php
    include_once('admnavbar.php');
?>


   <div class="row col-md-12">
         <div class="col-xs-1 col-sm-2 col-md-3 col-lg-4"></div>
         <div class="col-xs-10 col-sm-8 col-md-6 col-lg-4">
               
              <?php
                  if(isset($_SESSION['successstd'])) {
                  echo $_SESSION['successstd'];
                  unset($_SESSION['successstd']);
                      }
                  if(isset($_SESSION['errorstd'])) {
                  echo $_SESSION['errorstd'];
                  unset($_SESSION['errorstd']);
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
     
<div class="row container  col-md-12">
     
        <div class="col-md-6 m-d-15">
        <h2 class="top"  style="padding-left: 15px;">STUDENTS</h2>   


         
       
         </div> 
         <div class="col-md-6 text-right">
             <form  id = "filterstudentdetails" class="form-inline">

     
            <div class="form-group">
                     <?php
                    $streamtypes = sqlgetresult("SELECT * FROM streamcheck",true);
                    ?>
                    <select name="streamselect"  class="streamselect form-control">
                    
                      <?php
                      foreach($streamtypes as $stream) {
                      echo '<option value="'.$stream['id'].'" >'.$stream['stream'].'</option>';
                      }
                      ?>
                    </select>
                </div>
                    <div class="form-group">
                        <?php
                          $classstypes = sqlgetresult("SELECT * FROM classcheck",true);
                        ?>
                       <select name="classselect"  class="classselect  form-control">
                     
                      <?php
                      foreach($classstypes as $class) {
                      echo '<option value="'.$class['id'].'">'.$class['class_list'].'</option>';
                      }
                      ?>
                     </select>
                    </div>


                  <div class="form-group">
                  
                  
                     <?php
                       $sectiontypes = sqlgetresult("SELECT DISTINCT section FROM tbl_student WHERE section IS NOT NULL ORDER BY section ASC",true);
                        ?>
                    <select name="sectionselect"  class="sectionselect  form-control">
                      <option value="">Section</option>
                      <?php
                      foreach($sectiontypes as $section) {
                      echo '<option value="'.$section['section'].'">'.$section['section'].'</option>';
                      }
                      ?>
                    </select>
                  
                  </div> 

                    <button type="submit" name="filter" value="filterstudent" class="btn btn-info">Filter</button>
                   
                   </form>
                 </div>
               </div>
         

        <div class="container-fluid">

         <div class="table-responsive col-md-12">
            <table class="table table-bordered admintab dataTableStudent">
            <!-- <caption class="title">Sales Data of Electronic Division</caption> -->
               <thead>
                  <tr>
                  <th>S.No</th>
                  <th>Student Id</th>
                  <th>Student Name</th>                  
                  <th>Stream</th>
                  <th>Class</th>
                  <th>Section</th>
                  <th>Term</th>
                  <th>Parent Name</th>
                  <th>Status</th>
                  <th>Actions</th>
                  </tr>
               </thead>

               <tbody>

                  <?php
                  $count=1;

                  $sql = "SELECT * FROM studentcheck WHERE streamname='CBSE' AND class_list = 'PLAY SCHOOL'";

                  $res = sqlgetresult($sql,true);
                  $num = ($res!= null) ? count($res) : 0;
                  if($num > 0)
                  {
                  foreach ($res as $std)
                  {
                  ?>
                     <tr>
                        <td><?php echo $count; ?></td>
                        <td><?php echo trim($std["studentId"]);?></td>
                        <td><?php echo trim($std["studentName"]); ?></td>
                        <td><?php echo getStreambyId($std["stream"]); ?></td>
                        <td width="10%;"><?php echo getClassbyNameId($std["class"]); ?></td>              
                        <td><?php echo trim($std["section"]); ?></td>
                        <td><?php echo trim($current_term); ?></td>
                        <td><?php echo trim($std["userName"]); ?></td>
                        <td><?php echo trim($std["status"]); ?></td>
                        <td class="fafa">
                           <?php if($std["status"] =="ACTIVE"){?>
                           <a href="adminactions.php?status=ACTIVE&id=<?php echo $std["id"];?>&page=s"><i class="fa fa-check fafaactive"></i></a>
                           <?php }else{?>
                           <a href="adminactions.php?status=INACTIVE&id=<?php echo
                           $std["id"];?>&page=s"><i class="fa fa-close fafainactive"></i></a>
                           <?php } ?>
                           <a href="editstd.php?id=<?php echo $std["id"]; ?>"><i class="fa fa-edit"></i></a> 
                           <a href="adminactions.php?action=delete&id=<?php echo $std["id"]; ?>&page=s"><i class="fa fa-trash-o"></i></a>
                        </td>
                     </tr>
                     <?php $count++;
                     } 
                  }
                  // else {
                  //                  echo "<tr><td colspan='10'>No Data Avaiable.</td></tr>";
                  //                 }
                     ?>


               </tbody>

            </table>
         </div>
      </div>
   

<div class="row comment">
       
</div>
<?php
    





include_once(BASEPATH.'footer.php');
?>