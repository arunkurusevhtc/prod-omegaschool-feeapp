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
                    <select  id="sectionselect" name="sectionselect"  class="sectionselect  form-control">
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

                    <button type="button" name="filter" id="fltstud" value="filterstudent" class="btn btn-info">Filter</button>
                   
                   </form>
                 </div>
               </div>
         

        <div class="container-fluid">

         <div class="table-responsive col-md-12">
           <form method="post" action="adminactions.php">
            <table class="table table-bordered admintab dataTableStudent">
            <!-- <caption class="title">Sales Data of Electronic Division</caption> -->
               <thead>
                  <tr>
                  <th><input type="checkbox" id="checkAll"></th>
                  <th>S.No</th>
                  <th>Student Id</th>
                  <th>Student Name</th>                  
                  <th>Stream</th>
                  <th>Class</th>
                  <th>Section</th>
                  <th>Term</th>
                  <th>Parent's primary email id</th>
                  <th>Status</th>
                  <th>Actions</th>
                  </tr>
               </thead>

               <tbody>

                  <?php
                  $count=1;

                  $sql = "SELECT * FROM studentcheck WHERE streamname='CBSE' AND class_list = 'PreKG'";

                  $res = sqlgetresult($sql,true);
                  $num = ($res!= null) ? count($res) : 0;
                  if($num > 0)
                  {
                  foreach ($res as $std)
                  {
                    $sttid="checkme".$count;
                  ?>
                     <tr><td><input type="checkbox" name="checkme[]" class="checkme" id="<?php echo $sttid; ?>" value="<?php echo $std["id"];?>" style="margin:10px;"></td>
                        <td><?php echo $count; ?></td>
                        <td><?php echo trim($std["studentId"]);?></td>
                        <td><?php echo trim($std["studentName"]); ?></td>
                        <td><?php echo getStreambyId($std["stream"]); ?></td>
                        <td width="10%;"><?php echo trim($std["class_list"]); ?></td>              
                        <td><?php echo trim($std["section"]); ?></td>
                        <td><?php echo trim($current_term); ?></td>
                        <td><?php echo trim($std["parentprimaryemail"]); ?></td>
                        <td><?php echo trim($std["status"]); ?></td>
                        <td class="fafa">
                           <?php if($std["status"] =="ACTIVE"){?>
                           <a href="adminactions.php?status=ACTIVE&id=<?php echo $std["id"];?>&page=s"><i class="fa fa-check fafaactive"></i></a>
                           <?php }else{?>
                           <a href="adminactions.php?status=INACTIVE&id=<?php echo
                           $std["id"];?>&page=s"><i class="fa fa-close fafainactive"></i></a>
                           <?php } ?>
                           <a href="editstd.php?id=<?php echo $std["id"]; ?>"><i class="fa fa-edit"></i></a> 
                           <a href="adminactions.php?action=delete&id=<?php echo $std["id"]; ?>&page=s" onclick="return confirmDelete('Student')"><i class="fa fa-trash-o"></i></a>
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
            <button type = "submit" name="submit" id="clickme" value="enablePartialPayment" class = "btn btn-info sendnewsms"  disabled="disabled">Activate Partial Payment</button>
            <button type = "button" name="txtshow" data-toggle="modal" 
            data-target="#ayStudmodal" id="showpopup" class = "btn btn-warning sendnewsms blkupdt"  disabled="disabled">Acadamic Year</button>
            <div class="modal fade" id="ayStudmodal" role="dialog" data-keyboard="false" data-backdrop="static">
                <div class="modal-dialog modal-lg">
                <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Acadamic Year</h4>
                        </div>
                        <div class="modal-body" style="padding: 0px 35px;margin: 15px 0">
                            <div class="form-group">
                                <?php
                                    $yearchecks = sqlgetresult("SELECT * FROM yearcheck",true);
                                    ?>
                                <select name="yearselect" id="yearselect"  class="form-control ">
                                    <option value="">Acad.Year</option>
                                    <?php
                                        foreach($yearchecks as $yearcheck) {
                                        echo '<option value="'.$yearcheck['id'].'" >'.$yearcheck['year'].'</option>';
                                        }
                                        ?>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer text-center">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type = "submit" name="change_ay" id="change_ay" value="edit_ay" class = "btn btn-info sendnewsms"  disabled="disabled">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
            <button type = "button" name="txtterm" data-toggle="modal" 
            data-target="#termStudmodal" id="showpopup" class = "btn btn-primary sendnewsms blkupdt"  disabled="disabled">Term</button>
                <div class="modal fade" id="termStudmodal" role="dialog" data-keyboard="false" data-backdrop="static">
                    <div class="modal-dialog modal-lg">
                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Term</h4>
                            </div>
                            <div class="modal-body" style="padding: 0px 35px;margin: 15px 0">
                            <div class="form-group">
                                    <?php
                                        $semestercheck = sqlgetresult('SELECT * FROM tbl_semester',true);
                                    ?>
                                    <select id="semesterselect" name="semesterselect"  class="form-control ">
                                    <option value="">Semester</option>
                                    <?php
                                    foreach($semestercheck as $semester) {
                                    echo '<option value="'.$semester['semester'].'" >'.$semester['semester'].'</option>';
                                    }
                                    ?>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer text-center">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <button type = "submit" name="change_term" id="change_term" value="edit_tm" class = "btn btn-info sendnewsms"  disabled="disabled">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            <button type = "button" name="txtterm" data-toggle="modal" 
            data-target="#streamStudmodal" id="showpopup" class = "btn btn-primary sendnewsms blkupdt"  disabled="disabled">Stream/Class/Section</button>
                <div class="modal fade" id="streamStudmodal" role="dialog" data-keyboard="false" data-backdrop="static">
                    <div class="modal-dialog modal-lg">
                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Stream</h4>
                            </div>
                            <div class="modal-body" style="padding: 0px 35px;margin: 15px 0">
                                <div class="form-group">
                                    <?php
                                    $streamtypes = sqlgetresult("SELECT * FROM streamcheck",true);
                                    ?>
                                    <select id="streamselect" name="streamselect"  class="streamselect form-control streamchange streamblk">
                                        <option value="">-Stream-</option>
                                        <?php
                                        foreach($streamtypes as $stream) {
                                        echo '<option value="'.$stream['id'].'" >'.$stream['stream'].'</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <select id="classselect" name="classselect"  class="classselect  form-control classchange classblk">
                                    <option value="">-Class-</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <select  id="sectionselect" name="sectionselect"  class="sectionselect  form-control sectionblk">
                                    <option value="">-Section-</option>
                                    </select>
                                </div> 
                            </div>
                            <div class="modal-footer text-center">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <button type="submit" name="change_stream_class" id="change_stream_class" value="edit_stream_class" class = "btn btn-info sendnewsms"  disabled="disabled">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
                <button type = "button" name="txtstatus" data-toggle="modal" 
            data-target="#changeStatusModal" id="showpopup" class = "btn btn-primary sendnewsms blkupdt"  disabled="disabled">Change Status</button>
                <div class="modal fade" id="changeStatusModal" role="dialog" data-keyboard="false" data-backdrop="static">
                    <div class="modal-dialog modal-lg">
                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Change Status</h4>
                            </div>
                            <div class="modal-body" style="padding: 0px 35px;margin: 15px 0">
                                <div class="form-group">
                                    <select id="changestatus" name="changestatus" class="form-control">
                                        <option value="">-Change Status-</option>
                                        <option value="Active">Active</option>
                                        <option value="Inactive">In Active</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer text-center">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <button type="submit" name="std_status_change" id="std_status_change" value="std_status_change" class = "btn btn-info sendnewsms"  disabled="disabled">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
       </form>
         </div>
      </div>
   

<div class="row comment">
       
</div>
<?php
    





include_once(BASEPATH.'footer.php');
?>