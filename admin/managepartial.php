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
   <div class="row container  col-md-12">
     
        <div class="col-md-6 m-d-15">
        <h2 class="top"  style="padding-left: 15px;">Partial Payment - Student List</h2>   


         
       
         </div> 
         <div class="col-md-6 text-right">
            <form  id = "partialFltr" class="form-inline">
             <div class="form-group">
                <div>
                    <?php
                        $yearchecks = sqlgetresult("SELECT * FROM yearcheck",true);
                        ?>
                    <select id="yearselect" name="yearselect"  class="yearselect form-control ">
                        <option value="">Acad.Year</option>
                        <?php
                            foreach($yearchecks as $yearcheck) {
                            echo '<option value="'.$yearcheck['id'].'" >'.$yearcheck['year'].'</option>';
                            }
                            ?>
                    </select>
                </div>
            </div>    
            <div class="form-group">
                     <?php
                    $streamtypes = sqlgetresult("SELECT * FROM streamcheck",true);
                    ?>
                    <select id="streamselect" name="streamselect"  class="streamselect form-control streamchange">
                      <option value="">-Stream-</option>
                    
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
                       <select id="classselect" name="classselect"  class="classselect  form-control classchange">
                        <option value="">-Class-</option>
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
                    <select id="sectionselect" name="sectionselect"  class="sectionselect  form-control">
                      <option value="">-Section-</option>
                      <?php
                      foreach($sectiontypes as $section) {
                      echo '<option value="'.$section['section'].'">'.$section['section'].'</option>';
                      }
                      ?>
                    </select>
                  
                  </div>
                  <div class="form-group">
                        <select name="st_status" id="st_status"  class="st_status form-control st_status">
                            <option value="">-status-</option>
                            <option value="ACTIVE">Active</option>
                            <option value="INACTIVE">Inactive</option>
                        </select>
                    </div> 

                    <button type="button" name="filter" id="filterstudentpartial" value="filterstudentpartial" class="btn btn-info">Filter</button>
                 </form>
                  </div>
               </div>
      <div class="container-fluid col-md-12">
         <div class="table-responsive">
            <form method="post" action="adminactions.php">
            <table class="table table-bordered admintab dataTablePartial" style="width: 99%;">
            <!-- <caption class="title">Sales Data of Electronic Division</caption> -->
               <thead>
                  <tr>
                     <th><input type="checkbox" id="checkAll"></th>
                     <th>S.No</th>
                     <th>Student ID</th>
                     <th>Student Name</th>
                     <th>Class</th>
                     <th>Stream</th>
                     <th>Acadamic Year</th>
                     <th>Partial (%)</th>
                     <th>Status</th>
                     <th>Action</th>
                  </tr>
               </thead>
               <tbody>
                  <?php

                  $count=1;
                  $sql = 'SELECT p.*,s."studentId",s."studentName",st.stream, c.class_list,ay.year as academic_yr FROM partiallist as p JOIN  tbl_student s ON p.sId::int = s.id   JOIN tbl_stream st ON s.stream::int = st.id LEFT JOIN tbl_class c ON c.id = s.class::int LEFT JOIN tbl_academic_year ay ON (ay.id = p."academic_yr"::integer) ORDER BY p.id DESC LIMIT 100';
                  $res = sqlgetresult($sql,true);
                  $num = ($res!= null) ? count($res) : 0;
                  if($num > 0)
                  {
                  foreach ($res as $clas)
                  {
                      $sttid="checkme".$count;
                  ?>
                     <tr>
                        <td><input type="checkbox" name="checkme[]" class="checkme" id="<?php echo $sttid; ?>" value="<?php echo $clas["id"];?>" style="margin:10px;"></td>
                        <td><?php echo $count; ?></td>
                        <td><?php echo trim($clas["studentId"]); ?></td>
                        <td><?php echo trim($clas["studentName"]); ?></td>
                        <td><?php echo trim($clas["class_list"]); ?></td>
                        <td><?php echo trim($clas["stream"]); ?></td>
                        <td><?php echo trim($clas["academic_yr"]); ?></td>
                        <td><?php echo trim($clas["partial_min_percentage"]); ?></td>
                        <td><?php echo trim($clas["status"]); ?></td>
                        <td class="fafa">
                           <?php if($clas["status"] =="ACTIVE"){?>
                           <a href="adminactions.php?status=ACTIVE&id=<?php echo $clas["id"];?>&page=partial"><i class="fa fa-check fafaactive"></i></a>
                           <?php }else{?>
                           <a href="adminactions.php?status=INACTIVE&id=<?php echo
                           $clas["id"];?>&page=partial"><i class="fa fa-close fafainactive"></i></a>
                           <?php } ?>
                           <a href="editpartial.php?id=<?php echo $clas["id"]; ?>"><i class="fa fa-edit"></i></a> 
                           <a href="adminactions.php?action=delete&id=<?php echo $clas["id"]; ?>&page=partial"><i class="fa fa-trash-o"></i></a>
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
            <button type = "submit" name="submit" id="clickme" value="deletepartial" class = "btn btn-danger sendnewsms"  disabled="disabled" onclick="return confirm('Are you sure want to delete?');">Delete</button>
            <button type = "submit" name="partialstatus" value="1" class = "btn btn-success sendnewsms"  disabled="disabled" onclick="return confirm('Are you sure want to activate the partial payment option?');">Active</button>
            <button type = "submit" name="partialstatus" value="0" class = "btn btn-warning sendnewsms"  disabled="disabled" onclick="return confirm('Are you sure want to deactivate the partial payment option?');">In Active</button>
         </form>
         </div>
      </div>
   </div>
</div>
<div class="row comment">
       
</div>
<?php
    





include_once(BASEPATH.'footer.php');
?>