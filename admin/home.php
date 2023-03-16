<?php
  include_once('admnavbar.php');

  if ($_SESSION['sessLoginType'] == 'Admin') {

?>

<h2 class="text-center">Welcome !!!</h2>
    <div class="row comment">
       
    </div>
<?php
  } else {
    $selectedClasses = explode(',', $_SESSION['class']);    
    // print_r($selectedClasses);
?>


<div class="container-fluid">
       <div class="row col-md-12">
         <div class="col-xs-1 col-sm-2 col-md-3 col-lg-4"></div>
         <div class="col-xs-10 col-sm-8 col-md-6 col-lg-4">
               

                    
                     <?php
                  if(isset($_SESSION['successstatusstudent'])) {
                  echo $_SESSION['successstatusstudent'];
                  unset($_SESSION['successstatusstudent']);
                      }
                  if(isset($_SESSION['errorstatusstudent'])) {
                  echo $_SESSION['errorstatusstudent'];
                  unset($_SESSION['errorstatusstudent']);
                      }
                    ?>
         </div>
         <div class="col-xs-1 col-sm-2 col-md-3 col-lg-4"></div>
   </div>
  <div class="row col-md-12">
         <div class="col-xs-1 col-sm-2 col-md-3 col-lg-4"></div>
         <div class="col-xs-10 col-sm-8 col-md-6 col-lg-4">  
            <?php
               if(isset($_SESSION['success'])) {
                  echo $_SESSION['success'];
                   unset($_SESSION['success']);
               } elseif(isset($_SESSION['error'])) {
                  echo $_SESSION['error'];
                  unset($_SESSION['error']);
               } elseif(isset($_SESSION['failure'])) {
                  echo $_SESSION['failure'];
                  unset($_SESSION['failure']);
               }
            ?>
         </div>
         <div class="col-xs-1 col-sm-2 col-md-3 col-lg-4"></div>
   </div> 


   <div class="col-md-12">
    <div class="col-md-6 p-l-0">
      <h2 class="top">CLASS CO-ORDINATOR PAGE</h2>
   </div>
    <div class="col-md-6 p-r-0 text-right">
    <form id="filterTeacherdetails" class="form-inline">
            
            <div class="form-group">
              <input type="hidden" class="stream" value = "<?php echo $_SESSION['stream'];?>" >
                <!-- <label for="studStatus">Class</label> -->
                <div>
                  <input type="hidden" name="classcostream" id="classcostream" value="<?php echo($_SESSION['stream'])?>">
                    <select name="classcoclass" required class="classcoclass form-control">
                        <option value="">Class</option>
                        <?php
                        
                         $arr = $_SESSION['class'];
                          $newarray = explode(",",$arr);
                            foreach($newarray as $value) {
                             echo '<option value="'.$value.'">'.getClassbyNameId($value).'</option>';
                                   // print_r($_SESSION);
                               }
                         
                            ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
           
                <div>
                    <select name="classcosectionselect"  class="classcosectionselect form-control">
                        <option value="">Section</option>
                    </select>
                </div>
            </div>
            <button type="submit" name="filter" value="filterteacher" class="btn btn-info">Filter</button>
        </form>
    </div>
</div>

<div class="col-md-12 m-t-15" >
   <form  method="POST" action="adminactions.php">
        <div class="table-resposive">
      
        <table class="table table-bordered filterteachertables admintab ">

      <thead>
      
        <tr>
          <th data-orderable="false"> 
                      <input type="checkbox" id="checkAll"  name="checkme<?php echo $i;?>" value="<?php echo $studData["studentId"];?>">
                    </th>

          <th>S.No</th>                  
          <th>ID</th>
          <th>NAME</th>
          <th>STREAM</th>
          <th>CLASS</th>
          <th>SECTION</th>
          <th>TERM</th>
          <th>ACTION</th>
        </tr>
      </thead>
      <tbody>

        <?php
          $i=1;
          
        foreach ($selectedClasses as $key => $value) {

            $currentacademicyear = getCurrentAcademicYear();
            $currentterm = getCurrentTerm();
            // print_r($currentterm);
            // exit;
        
           $studdata = sqlgetresult('SELECT * FROM tbl_student WHERE "studentId" NOT IN (SELECT "studentId" FROM   tbl_temp_challans WHERE "academicYear" = \''.$currentacademicyear.'\' AND term = \''.$currentterm.'\' ) AND tbl_student."class" = \''.$value.'\' AND tbl_student."stream" = \''. $_SESSION['stream'].'\' AND tbl_student."status" = \'1\' AND tbl_student."deleted" = \'0\' ORDER BY tbl_student."studentName" ASC',true);
           // print_r($studdata);
          

          if(!empty($studdata)) {
            foreach ($studdata as $data) {
            ?>

      
        <tr>

            <td><input type="checkbox" name="checkme[]" class="checkme" value="<?php echo $data["studentId"];?>" style="margin:10px;"></td>
            <td><?php echo $i;?></td>
            <td><?php echo $data['studentId'];?></td>
            <td><?php echo $data['studentName'];?></td>
            <td><?php echo getStreambyId($data['stream']);?></td>
            <td><?php echo getClassbyNameId($data['class']);?></td>
            <td><?php echo $data['section'];?></td>
            <td><?php echo $data['term'];?></td>


            <td class="fafa">
              <a href="updatestudentsstatus.php?id=<?php echo $data["studentId"]; ?>"><i class="fa fa-edit"></i></a> 

            </td>
                </tr>
        <?php 
            $i++;
          }
          }
        }
        ?>
      </tbody>
    </table>

    <button type = "submit" name="submit" id="clickme" value="changestudStatus" class = "btn btn-info sendnewsms"  disabled="disabled">Edit Students</button>
      </div>  
  </form>

</div>
<div class="row comment">
       
</div>
<script>

  
</script>
<?php
  }
  include_once(BASEPATH.'footer.php');
?>