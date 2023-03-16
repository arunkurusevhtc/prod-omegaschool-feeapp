<?php
include_once('admnavbar.php');

$columns = array('S.No','StudentId','ChallanNo','Name','AcademicYear','Term','Stream','Class','Event','Amount','PaidDate','Action');

?>
<div class="container-fluid  contentcheque">
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
<div class="col-md-12">
        <h2 class="top">COMMON-FEE PAID REPORT</h2>
        <form  id="paidnonfeefilters" class="form-inline">
            <div class=col-lg-12 p-r-0 text-right">
                <div class="form-group">
                <label>Student ID</label>
                <input type="text" name="stid" id="stid" placeholder="Student ID" class="form-control">
            </div>
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
            <?php
            $feeTypes = sqlgetresult("SELECT * FROM tbl_nonfee_type WHERE applicable='C'",true);
            ?>
            <input type="hidden" id="selected_feetypes" name="selected_feetypes" class="selected_quizsetids">
            <select name="feetype"  class="quizsetid form-control" multiple="multiple">
            <?php
                $feetypesgroup = sqlgetresult("SELECT * FROM feegroupcheck",true);
                echo '<optgroup label="COMMON-FEE TYPE"></optgroup>';
                foreach($feeTypes as $feetype) {
                    echo '<option value="'.$feetype['id'].'">'.$feetype['feeType'].'</option>';
                }                                   
            ?>
            </select>           
            </div>
            <div class="form-group">
              <button type="button" id="commonfeerpt" name="filter" value="commonfee_report" class="btn btn-info">Filter</button>
            </div>
            <div class="form-group">
                <a href="" class="btn btn-info commonfeereportexcel">Export Excel</a>
            </div>
            </div>
        </form>
    
</div>
<div style="clear:both">&nbsp;</div>
<div class="col-md-12">
    <div class="form-group pull-right ">
        <a href="commonnf.php" class="btn btn-info" ><span >Pay Common Fee</span></a>
    </div>
</div>
<div class="col-md-12 m-t-15" >
    <div class="table-responsive">
        <form>
            <table class="table table-bordered admintab dataTableSfs">
                <thead>
                    <tr>
                        <?php foreach ($columns as $key => $value) { ?>
                            <th><?php echo $value; ?></th>
                        <?php    
                        } 
                        ?>
                        
                    </tr>
                </thead>
            </table>
        </form>
    </div>
</div>
<!-- </div> -->
</div>

<div class="row comment">
</div>

<?php
include_once('..\footer.php');
?>