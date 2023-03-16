<?php
include_once('admnavbar.php');
$typelist= array(1 => 'Uniform', 2 => 'Lunch');
$status= array('Ok' => 'Completed', 'F' => 'Failed', 'C' => 'Canceled', 'N' => 'Not Updated');
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
        <h2 class="top">Cart Checkout Report</h2>
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
                       <select id="classselect" name="classselect"  class="classselect  form-control">
                        <option value="">-Class-</option>
                     </select>
                    </div>
               
            
            <div class="form-group">
                <label>Ref Number</label>
                <input type="text" name="txtref" id="txtref" placeholder="Ref Number" class="form-control">
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
                        
                        if($key=='Ok'){
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
              <button type="button" id="cartfeeflt" name="filter" value="otherfeefilter" class="btn btn-info">Filter</button>
            </div>
            <div class="form-group">
                <a href="" class="btn btn-info cartexportexcel">Export Excel</a>
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
            <table class="table table-bordered admintab dataTableTeachers">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Student Id</th>
                        <th>Name</th>
                        <th>Stream</th>
                        <th>Semester</th>
                        <th>Class</th>
                        <th>Section</th>
                        <th>Academic Year</th>
                        <th>Paid Date</th>
                        <th>Ref Number</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Action</th>
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