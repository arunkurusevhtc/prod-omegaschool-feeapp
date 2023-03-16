<?php
    include_once('admnavbar.php');
?>
<div class="container-fluid">
    <div class="row col-md-12">
        <div class="col-xs-1 col-sm-2 col-md-3 col-lg-2"></div>
        <div class="col-xs-10 col-sm-8 col-md-6 col-lg-8">  
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
</div>
<form method="post" id="filtercreatedchallans" class="form-inline" action="unpaidchallandata.php">
<input type="hidden" id="button_action" name="button_action" value="filter" />
<input type="hidden" id="admrole" name="admrole" value="<?php  echo $roleid; ?>" />
<div class="col-md-12 ">
    <div class="col-md-6 p-l-0">
        <h2 class="top">CHALLANS</h2>
    </div>
    <div class="col-md-6 p-r-0 text-right">
            <div class="form-group">
                <div>
                    <?php
                        $yearchecks = sqlgetresult("SELECT * FROM yearcheck",true);
                        ?>
                    <select id="yearselect" name="yearselect"  class="yearselect form-control ">
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
                    <select id="semesterselect" name="semesterselect"  class="semesterselect form-control ">
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
                <div>
                    <?php
                        $streamtypes = sqlgetresult("SELECT * FROM streamcheck",true);
                        ?>
                    <select id="streamselect" name="streamselect"  class="streamselect form-control streamchange">
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
                <div>
                    <?php
                        $classstypes = sqlgetresult("SELECT * FROM classcheck",true);
                        ?>
                    <select id="classselect" name="classselect"  class="classselect  form-control classchange">
                        <option value="">Class</option>                      
                    </select>
                </div>
            </div>
            <div class="form-group">
                <div>
                    <?php
                        $sectiontypes = sqlgetresult("SELECT DISTINCT section FROM tbl_student ORDER BY section ASC",true);
                        ?>
                    <select id="sectionselect" name="sectionselect"  class="sectionselect  form-control">
                        <option value="">Section</option>                       
                    </select>
                </div>
            </div>

            <button type="button" id="fltcreatedchallan" name="fltcreatedchallan" value="filtercreatedchallan" class="btn btn-info">Filter</button>
       
    </div>
</div>
<div style="clear:both">&nbsp;</div>
<?php if($roleid == 1) { ?>
<div class="col-md-12">
    <div class="col-md-6">
        <div class="form-group">
            <button type="submit" name="movedtopaid" id="movedtopaid" value="movedtopaid" class="btn btn-info">Moved To Paid Challans Report</button>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group pull-right">
            <button type="submit" name="unpaid" id="unpaid" value="unpaid" class="btn btn-info">Unpaid Challans Report</button>
        </div>
    </div>
</div>
<?php } ?>
</form>
<div class="col-md-12 m-t-15" >
    <div class="table-responsive">
        <form method="post" action="adminactions.php">
            <table class="table table-bordered admintab dataTableTeachers">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="checkAll"></th>
                        <th>S.No</th>
                        <th>Student Id</th>
                        <th>Challan No</th>
                        <th>Name</th>
                        <th>Stream</th>
                        <th>Class</th>
                        <th>Section</th>
                        <th>Term</th>
                        <th>Created Date</th>
                        <th>Due Date</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
            <button type = "submit" name="submit" id="clickme" value="deletechallan" class = "btn btn-info sendnewsms"  disabled="disabled" onclick="return confirm('Are you sure want to delete?');">Delete</button>
            <button type = "submit" name="submit" id="movetopaid" value="movetopaid" class = "btn btn-info sendnewsms"  disabled="disabled" onclick="return confirm('Are you sure want to move to paid list?');">Move to Paid</button>
            <button type = "submit" name="latefee" value="0" class = "btn btn-primary sendnewsms"  disabled="disabled" onclick="return confirm('Are you sure want to enable late fee?');">Enable Late Fee</button>
            <button type = "submit" name="latefee" value="1" class = "btn btn-warning sendnewsms"  disabled="disabled" onclick="return confirm('Are you sure want to disable late fee?');">Disable Late Fee</button>
        </form>
    </div>
</div>

<div class="modal fade" id="viewModal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Fee Challan - Payment</h4>
            </div>
            <div class="modal-body">
                <p>Please review the details of your challan and confirm to continue...</p>
                <div class="table-responsive">
                    <form method="post" id="studDataModal" action="sql_actions.php">
                        <div id="challanData"></div>                                     
                        <div class="text-center">
                            <button type="button" class="btn btn-info" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row comment">
</div>

<?php
include_once('..\footer.php');
?>