<?php
    include_once('admnavbar.php');   
    if(isset($_SESSION['success_msg']) && $_SESSION['success_msg'] !='') {
        echo $_SESSION['success_msg'];
        unset($_SESSION['success_msg']);
    } elseif (isset($_SESSION['error_msg']) && $_SESSION['error_msg'] !='') {
        echo $_SESSION['error_msg'];
        unset($_SESSION['error_msg']);
    }
?>
<div class="container-fluid feeEntryReport">
</div>
    <div class="table-responsive feetab container-fluid">
            <form action="adminactions.php" method="post" class="form-inline">
               <div class="row text-right">
                        <div class="col-md-6 m-b-15">
                            <h2 class="fer">FEE ENTRY REPORT</h2>
                        </div> 
                        <div class="col-md-6 m-b-15"> 
                        <div class="fromto">
                            <!-- <label for ="from" class="rdate">From</label> -->
                            <input name="datetimes" id="drp" />
                            <button class="btn btn-primary" id="convertToexcel" type="exporttoexcel" name="ctoexcel" value="ctoexcel">Convert to excel</button>
                        </div>
                        </div>
            </div>
            </form>
            <form id="payment_details" class="form-inline">
              <div class="row text-right">
            <div class="row col-md-12">
               
             <div class="col-md-6 p-l-0"></div>
                <div class="col-md-6 p-r-0 text-right">
      
                    <div class="form-group">
                     <?php
                    $streamtypes = sqlgetresult("SELECT * FROM streamcheck",true);
                    ?>
                    <select name="streamselect"  class="streamselect form-control streamchange">
                      <option value="">Stream</option>
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
                     <select name="classselect"  class="classselect  form-control classchange">
                      <option value="">Class</option>                      
                     </select>
                    </div>

                    <div class="form-group">
                  <!-- <label for="studStatus">Class</label> -->
                  
                    <?php
                    $sectiontypes = sqlgetresult("SELECT DISTINCT section FROM tbl_student ORDER BY section ASC",true);
                    ?>
                    <select name="sectionselect"  class="sectionselect  form-control">
                      <option value="">Section</option>                      
                    </select>
                  
                  </div> 


                    <button type="submit" name="filter" value="feeentry" class="btn btn-info">Filter</button>
               </div>
            </div>
                      <div style="clear:both;">
                        </div>
                        </div>
     </form>  
        <table class="table table-bordered payreport admintab">
             <thead>
                <tr>
                    <th>S.No</th>
                    <th>Stuent Id</th>
                    <th>Student Name</th>
                    <th>Academic Year</th>
                    <th>Stream</th>
                    <th>Class</th>
                    <th>Section</th>
                    <th>Semester</th>                 
                    <th>Amount</th>
                </tr>
            </thead>            
            <tbody>

            
                
            </tbody>
        </table>           
    </div>

<div class="row comment">
       
</div> 
<?php
    





include_once(BASEPATH.'footer.php');
?>