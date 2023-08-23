<?php
    require_once('admnavbar.php');
    $columns = array('S.No','StudentId','ChallanNo','Name','AYear','Term','Stream','Class','FeeType','Amount','CreatedDate','DueDate','Action');
    
?>

<div class="container-fluid contentcheque">
    <div class="row col-md-12">
        <div class="col-xs-1 col-sm-2 col-md-3 col-lg-2"></div>
        <div class="col-xs-10 col-sm-8 col-md-6 col-lg-8">  
            <?php
                if(isset($_SESSION['success'])) {
                   echo $_SESSION['success'];
                    unset($_SESSION['success']);
                } elseif(isset($_SESSION['error'])) {
                   echo $_SESSION['error'];
                   unset($_SESSION['error']);
                }
                ?>
        </div>
        <div class="col-xs-1 col-sm-2 col-md-3 col-lg-2"></div>
    </div>
<div class="col-md-12 ">
    <div class="col-md-3 p-l-0">
        <h2 class="top">NON-FEE CHALLANS</h2>
    </div>
    <div class="col-md-9 p-r-0 text-right">
        <form  class="form-inline" method="post" action="">
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
                if( $res[0]['hostel_need'] == 'Y') {
                    $feeTypes = sqlgetresult("SELECT * FROM tbl_nonfee_type WHERE applicable='DH' OR applicable='H'",true);
                } else {
                    $feeTypes = sqlgetresult("SELECT * FROM tbl_nonfee_type WHERE applicable='DH' OR applicable='D' ",true);
                }
                ?>
                <input type="hidden" id="selected_feetypes" name="selected_feetypes" class="selected_quizsetids">
                <select name="feetype"  class="quizsetid form-control" multiple="multiple">
                    <?php
                    $feetypesgroup = sqlgetresult("SELECT * FROM feegroupcheck",true);
                    // print_r($feetypesgroup);                                   
                    echo '<optgroup label="NON-FEE GROUP"></optgroup>';
                    foreach($feeTypes as $feetype) {
                        echo '<option value="'.$feetype['id'].'">'.$feetype['feeType'].'</option>';
                    }                               
                    ?>
                </select>           
            </div>
            <div class="form-group">
                <select id="challanstatus" name="challanstatus"  class="challanstatus  form-control">
                    <option value="1">Active</option>                       
                    <option value="2">In Active</option>                       
                </select>
            </div>
            <div class="form-group">
                <button type="button" id="fltcreatenonfeechallan" name="fltcreatenonfeechallan" class="btn btn-info">Filter</button>
            </div>
            <div class="form-group">
                <a href="" class="btn btn-info exportnonexcel">Export Excel</a>
            </div>
        </form>
    </div>
</div>
<div class="col-md-12 m-t-15" >
    <div class="table-responsive">
        <form method="post" action="adminactions.php">
            <table class="table table-bordered admintab dataTableNonfee">
                <thead>
                     <tr>
                        <th><input type="checkbox" id="checkAll"></th>
                        <?php foreach ($columns as $key => $value) { ?>
                        <th><?php echo $value; ?></th>
                        <?php    
                        } 
                        ?>
                    </tr>
                </thead>
            </table>
            <button type = "submit" name="submit" id="clickme" value="enablenfchallan" class = "btn btn-primary sendnewsms"  disabled="disabled" onclick="return confirm('Are you sure want to enable?');">Enable</button>
            <button type = "submit" name="submit" id="clickme" value="disablenfchallan" class = "btn btn-warning sendnewsms"  disabled="disabled" onclick="return confirm('Are you sure want to disable?');">Disable</button>
        </form>
    </div>
</div>
</div>
</div>
<div class="row comment"></div>
<?php
include_once(BASEPATH.'footer.php');
?>