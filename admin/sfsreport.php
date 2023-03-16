<?php
include_once('admnavbar.php');
$status= array('1' => 'Paid', '2' => 'Partial Paid', '3' => 'Not Paid');

$columns = array('S.No','Student Id','Student Name','Challan No','Academic Year','Term','Stream','Class','Paid Date','Challan Status','Paid Status');

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
    <div class="col-md-6 p-l-0">
        <h2 class="top">SFS Paid Report</h2>
    </div>

        <form  id="otherfeefilters" class="form-inline">
            <div class=col-lg-12 p-r-0 text-right">
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
                <label>Student ID</label>
                <input type="text" name="stid" id="stid" placeholder="Student ID" class="form-control">
            </div>
            <div class="form-group">
                    <label>From</label>
                    <input type="text" name="from" id="from" placeholder="From Date" class="form-control datepicker">
                    <label>To</label>
                    <input type="text" name="to" id="to" placeholder="To Date" class="form-control datepicker">
            </div>
            <div class="form-group">
                <select name="tstatus" id="tstatus"  class="tstatus form-control ">
                    <option value="">-All-</option>
                    <?php 
                    foreach ($status as $key => $value) {
                        
                        if($key=='1'){
                            $sel1="selected='selected'";
                        }else{
                           $sel1=""; 
                        }
                    ?>
                    <option value="<?php echo $key; ?>" <?php echo $sel1; ?>><?php echo $value; ?></option>
                    <?php
                    }
                    ?>                    
                </select>
            </div>
            <div class="form-group">
              <button type="button" id="sfsreportid" name="filter" value="sfsreportfilter" class="btn btn-info">Filter</button>
            </div>
            <div class="form-group">
                <a href="" class="btn btn-info sfsreportexcel">Export Excel</a>
            </div>
            </div>
        </form>
    
</div>
<div style="clear:both">&nbsp;</div>
<!--<div class="col-md-12">
    <div class="form-group pull-right ">
        <a href="addpayment.php" class="btn btn-info" ><span >Add Payment</span></a>
    </div>
</div>-->
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