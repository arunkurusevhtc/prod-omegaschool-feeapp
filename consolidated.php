<?php
ob_start();
include_once('navbar.php');
//$payment_id=100012;
//echo completeTransactionById($payment_id);


//exit;

$studentIds = sqlgetresult('SELECT "studentId" FROM getparentdata WHERE id = \''.$_SESSION['uid'].'\' AND status = \'1\' AND deleted = \'0\'  GROUP BY "studentId"',true);


?>
<div id="consolidated" class="container-fluid  contentcheque">
    <div class="col-lg-12">
            <h2 class="top">Consolidated Report</h2>
    </div>
<!--    <div class="col-lg-12 challanthereornot">
    </div> -->
    <div class="row col-md-12">
        <div class="col-xs-1 col-sm-2 col-md-3 col-lg-2"></div>
        <div class="col-xs-10 col-sm-8 col-md-6 col-lg-8 studentidthereornot">  
             <?php
                if(isset($_SESSION['success_msg']) && $_SESSION['success_msg'] !='') {
                    echo $_SESSION['success_msg'];
                    unset($_SESSION['success_msg']);
                }
                if (isset($_SESSION['error_msg']) && $_SESSION['error_msg'] !='') {
                    echo $_SESSION['error_msg'];
                    unset($_SESSION['error_msg']);
                }
    ?>
        </div>
        <div class="col-xs-1 col-sm-2 col-md-3 col-lg-2"></div>
    </div>

    <form  id="filteradvancereport" class="form-inline">
        <input type="hidden" name="pid" id="pid" value="<?php echo $_SESSION['uid']; ?>">
        <div class="col-lg-12 text-right">
            <div class="form-group">
                    <select class="form-control nonfeestudid" name="studId" id="studId">
                        <option value="">-Select-</option>
                        <?php
                            foreach ($studentIds as $vals) {
                                if($studId==$vals['studentId']){
                                    $selct="selected='selected'";
                                }else{
                                    $selct="";
                                }
                                echo "<option value='".$vals['studentId']."' ".$selct.">".$vals['studentId']."</option>";
                            }
                        ?>
                    </select>
            </div>     
            <div class="form-group">
                    <?php
                        $yearchecks = sqlgetresult("SELECT * FROM yearcheck",true);
                        ?>
                    <select name="yearselect" id="yearselect"  class="yearselect form-control ">
                        <option value="">Acad.Year</option>
                        <?php
                            foreach($yearchecks as $yearcheck) {
                                $ids=$yearcheck['id'];
                                if($ids >= 6){
                                    $sel="";
                                    if($ids==6){
                                        $sel="selected='selected'";
                                    }
                                    echo '<option value="'.$ids.'" '.$sel.'>'.$yearcheck['year'].'</option>';
                                }
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
                <button type="button" id="fltconsolid" name="filter" value="fltconsolid" class="btn btn-info">Filter</button>
            </div>
            <div class="form-group">
               <!-- <a href="" class="btn btn-info consolidatedexportexcel">Export Excel</a>-->
                <!--<a  id="paidexportexcel" name="paidexportexcel" class="btn btn-info paidexportexcel" style="display: none;">Export Excel</a>-->
            </div>
        </div>
    </form>
    <div class="col-lg-12 m-t-15" >
        <div class="table-responsive">
            <form>
                <table class="table table-bordered admintab dataTableUserConsolidated">
                    <thead>
                        <tr>
                        <th>S.No</th>
                        <!--<th>Ref Number</th>-->
                        <th>Student Id</th>
                        <th>Student Name</th>
                        <th>Class</th>
                        <th>Semester</th>
                        <th>Academic Year</th>
                        <th>Demand</th>
                        <th>Waiver</th>
                        <th>Receipt</th>
                        <th>Outstanding</th>
                        <!--<th>Status</th>-->
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
    include_once('footer.php');
?>