<?php
include_once('admnavbar.php');

?>
<div class="container-fluid  contentcheque">
    <div class="col-lg-12">
            <h2 class="top">Advance Payment REPORT</h2>
    </div>
<!--    <div class="col-lg-12 challanthereornot">
    </div> -->
    <div class="row col-md-12">
        <div class="col-xs-1 col-sm-2 col-md-3 col-lg-2"></div>
        <div class="col-xs-10 col-sm-8 col-md-6 col-lg-8 studentidthereornot">  
            <?php
                if(isset($_SESSION['successdelete'])) {
                   echo $_SESSION['successdelete'];
                    unset($_SESSION['successdelete']);
                } elseif(isset($_SESSION['errordelete'])) {
                   echo $_SESSION['errordelete'];
                   unset($_SESSION['errordelete']);
                } elseif(isset($_SESSION['failure'])) {
                   echo $_SESSION['failure'];
                   unset($_SESSION['failure']);
                }elseif(isset($_SESSION['success'])) {
                   echo $_SESSION['success'];
                   unset($_SESSION['success']);
                }elseif(isset($_SESSION['successchallan'])) {
                   echo $_SESSION['successchallan'];
                   unset($_SESSION['successchallan']);
                }
                ?>
        </div>
        <div class="col-xs-1 col-sm-2 col-md-3 col-lg-2"></div>
    </div>

    <form  id="filteradvancereport" class="form-inline">
            <div class="form-group">
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
            <div class="form-group">
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
             <div class="form-group">
                       <select id="classselect" name="classselect"  class="classselect  form-control classchange">
                        <option value="">-Class-</option>
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
            <div class="form-group">
                    <label>From</label>
                    <input type="text" name="from" id="from" placeholder="From Date" class="form-control datepicker">
                    <label>To</label>
                    <input type="text" name="to" id="to" placeholder="To Date" class="form-control datepicker">
            </div>
            <div class="form-group">
                    <select name="type" id="type"  class="form-control ">
                        <option value="">Type</option>
                        <option value="Credit">Credit</option>
                        <option value="Debit">Debit</option>
                    </select>
            </div>
            <div class="form-group">
            <button type="button" id="fltadvancereport" name="filter" value="fltadvancereport" class="btn btn-info">Filter</button>
           </div>
           <?php if($roleid == 1) { ?>
            <div class="form-group">
                <a href="" class="btn btn-info advanceexportexcel">Export Excel</a>
                <!--<a  id="paidexportexcel" name="paidexportexcel" class="btn btn-info paidexportexcel" style="display: none;">Export Excel</a>-->
            </div>
          <?php } ?>
        </form>
    <div style="clear:both">&nbsp;</div>
    <div class="col-md-12">
        <div class="form-group pull-left ">
            <a href="balamtreport.php" class="btn btn-info" ><span >Balance</span></a>
        </div>
        <div class="form-group pull-right ">
            <a href="addadvancepayment.php" class="btn btn-info" ><span >Add Advance Payment</span></a>
        </div>
    </div>
    <div class="col-lg-12 m-t-15" >
        <div class="table-responsive">
            <form>
                 <table class="table table-bordered admintab dataTableAdvanceReport">
                <thead>
                   <tr>
                        <th>S.No</th>
                        <th>RefNumber</th>
                        <th>StudentId</th>
                        <th>StudentName</th>
                        <th>Class</th>
                        <th>Section</th>
                        <th>AYear</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Type</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            </form>
        </div>
    </div>
</div>
<div class="row comment">
</div>

<?php
include_once(BASEPATH.'footer.php');
?>

