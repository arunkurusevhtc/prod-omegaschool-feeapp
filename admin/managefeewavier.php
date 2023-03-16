
<?php
    include_once('admnavbar.php');
?>

<div>
   <div class="row col-md-12">
         <div class="col-xs-1 col-sm-2 col-md-3 col-lg-4"></div>
         <div class="col-xs-10 col-sm-8 col-md-6 col-lg-4">                     
            <?php
                if(isset($_SESSION['successwavier'])) {
                    echo $_SESSION['successwavier'];
                    unset($_SESSION['successwavier']);
                }
                if(isset($_SESSION['errorwavier'])) {
                    echo $_SESSION['errorwavier'];
                    unset($_SESSION['errorwavier']);
                }
            ?>
         </div>
         <div class="col-xs-1 col-sm-2 col-md-3 col-lg-4"></div>
    </div>

    <div class="row col-md-12">
        <div class="col-md-6">
            <h2 class="top">FEE WAIVER</h2>
        </div>
        <div class="col-md-6">
            <form id="filterform"  class="form-inline">
                <div id="waviererror">
                </div>
                <div class="form-group">
                    <div>
                        <?php
                            $yearchecks = sqlgetresult("SELECT * FROM yearcheck",true);
                            ?>
                        <select name="yearselect" id="yearselect"  class="yearselect form-control ">
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
                    <div>
                        <?php
                            $semestercheck = sqlgetresult('SELECT * FROM tbl_semester',true);
                            ?>
                        <select name="semesterselect" id="semesterselect"  class="semesterselect form-control ">
                            <option value="">Semester</option>
                            <?php
                            
                                foreach($semestercheck as $semester) {
                                echo '<option value="'.$semester['semester'].'" >'.$semester['semester'].'</option>';
                                }
                                ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <!-- <label for="studStatus">Stream</label> -->
                    <div>
                        <?php
                            $streamtypes = sqlgetresult("SELECT * FROM streamcheck",true);
                        ?>
                        <select name="streamselect" id="streamselect"  class="streamselect form-control streamchange">
                            <option value="">Stream</option>
                            <?php
                                foreach($streamtypes as $stream) {
                                    echo '<option value="'.$stream['id'].'" >'.$stream['stream'].'</option>';
                                }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <!-- <label for="studStatus">Class</label> -->
                    <div>
                        <?php
                            $classstypes = sqlgetresult("SELECT * FROM classcheck",true);
                        ?>
                        <select name="classselect" id="classselect"  class="classselect  form-control classchange">
                            <option value="">Class</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <!-- <label for="studStatus">Class</label> -->
                    <div>
                        <?php
                            $sectiontypes = sqlgetresult("SELECT DISTINCT section FROM tbl_student ORDER BY section ASC",true);
                        ?>
                        <select name="sectionselect"  class="sectionselect  form-control">
                            <option value="">Section</option>
                        </select>
                    </div>
                </div>
                <button type="button" name="filter" id="fltfrm" value="filterbut" class="btn btn-info">Filter</button>
            </form>
        </div>
    </div>

    <div style="clear:both">&nbsp;</div>
    <div class="col-md-12">
     <div class="table-responsive">
        <table class="table table-bordered admintab dataTableWaiver">
           <thead>
              <tr>
                  <th>  
                   <input type="checkbox" id="checkAll" name="checkme" value="">
                  </th>
                  <th>S.No</th>
                  <th>Student Id</th>
                  <th>Student Name</th>
                  <th>Stream</th>
                  <th>Class </th>
                  <th>Section</th>
                  <th>Term</th>
                  <th>Total</th>
                  <th>Waived</th>
                  <th>Net Total</th>
                  <th>Waived Groups</th>
                  <th>Action</th>             
              </tr>
           </thead>
        </table>
     </div>
  </div>
  <div class="col-md-12"><button type = "button" name="submit" id="clickme" class = "btn btn-info sendnewsms feewavier"  disabled="disabled"  href="#myModal" data-toggle="modal">Apply Waiver</button></div>
</div>

<div class="row comment">
</div>

<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close closed" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add Waiving Amount</h4>
            </div>
            <div class="modal-body con">
                <h4 class="addtitle">Please provide the Waving Amount Details.</h4>
                <form name="addstudent" class="form-horizontal" id="addstudent"  action="adminactions.php" method="post">
                  <input type="hidden" class="id" name="id">
                  <input type="hidden" id="feegrp" name="grouptype">
                   <!-- <input type="text" class="total" name="total" value="<?php echo($par["total"]);?>"> -->
                    <div class="form-group">
                            <label class="control-label col-sm-4" for="studStatus">Select the Fee Group you need to Waiver:</label>
                            <div class="selectwavier col-sm-8">
                                
                        </div>                                    
                    </div>
                    <div class="form-group">
                            <label class="control-label col-sm-4" for="studStatus">Amount (INR):</label>
                            <div class="groupamount col-sm-8">
                                <input type="number" name="groupamount" id="groupamount" placeholder="Group Amount" class="form-control" readonly>
                            </div>                                    
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="email">Waiving Percentage (%):</label>
                        <div class="col-sm-8">
                            <input name="WavingPercentage" title="Student ID from current challan" id="WavingPercentage" type="number" placeholder="Waving Percentage" class="form-control" autofocus="" step=".01">
                        </div>
                    </div> 
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="email">Waiving Amount:(INR)</label>
                        <div class="col-sm-8">
                            <input name="WavingAmount" title="Student ID from current challan" id="WavingAmount" type="text" placeholder="Waving Amount" class="form-control" autofocus="" step=".01">
                    </div>
                    </div>   
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="waivertype">Waiver Type</label>
                        <div class="col-sm-8">
                            <select class="form-control" name="waivertype" required="required">
                              <option value="">--Select--</option>
                              <?php
                              $waivertypes = sqlgetresult("SELECT * FROM waivertypescheck",true);
                              foreach($waivertypes as $feewaivertype){
                                echo('<option value="'.$feewaivertype['id'].'">'.$feewaivertype['waivertypes'].'</option>');
                              }
                              ?>
                            </select>
                    </div>
                    </div>
                    <div class="form-group ">
                                <label class="control-label col-sm-4" for="remarks">REMARKS:</label>
                                <div class="col-sm-8">
                                    <textarea placeholder="REMARKS" required="required" class="form-control remarks" name="remarks" removeFromString maxlength="250"></textarea>
                                </div>
                        </div>                 
                    <div class="text-center">
                        <button class="btn btn-default closed"  type="button" data-dismiss="modal">Close</button>
                        <input class="btn btn-primary" id="addMyStudent" type="submit" name="addstudent" value="Update Amount">
                
                    </div>                    
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var isAlreadytClicked = false;
    $('#addMyStudent').on("click", function (e) {
        if (isAlreadytClicked == false) {
            isAlreadytClicked = true;
            return;
        }

        if (isAlreadytClicked) {
            $('#addMyStudent').attr('disabled','disabled');
        }
    });
</script>

<?php
    include_once(BASEPATH.'footer.php');
?>
