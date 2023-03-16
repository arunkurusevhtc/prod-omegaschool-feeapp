<?php
    include_once('admnavbar.php');
?>

<div class="container-fluid chequerevoke">
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
                elseif(isset($_SESSION['successchallanrevoke'])) {
                   echo $_SESSION['successchallanrevoke'];
                   unset($_SESSION['successchallanrevoke']);
                }
                elseif(isset($_SESSION['failurechequerevoke'])) {
                   echo $_SESSION['failurechequerevoke'];
                   unset($_SESSION['failurechequerevoke']);
                }
                ?>
        </div>
        <div class="col-xs-1 col-sm-2 col-md-3 col-lg-2"></div>
    </div>
</div>

<div class="col-md-12">
    <div class="col-md-6 p-l-0">
        <h2 class="top">CHALLANS</h2>
    </div>

    <div class="col-md-6 p-r-0 text-right">
        <form  id="filterpaidchallans" class="form-inline">
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
            <button type="button" id="fltpaidchallan" name="filter" value="filterpaidchallan" class="btn btn-info">Filter</button>
        </form>
    </div>
</div>

<div class="col-md-12 m-t-15" >
    <div class="table-responsive">
        <form>
            <table class="table table-bordered admintab dataTableTeachers">
                <thead>
                    <tr>
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
        </form>
    </div>
</div>
<!-- </div> -->



<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close closed" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Cheque Revoke</h4>
            </div>
            <div class="modal-body chequerevokecon">
                <h4 class="addtitle">Are you Sure you want to Revoke the particular Challan.</h4>
                <div class = "col-xs-2-12" >
                <div class = "col-xs-2"></div>
                    <form action="adminactions.php" method=POST id="chequerevokeform">
                        <div class = "col-xs-8 chequerevokemain">
                            <input type="hidden" name="stdidforcheque" id="stdidforcheque" class="stdidforcheque">
                            <input type="checkbox" id="chequerevokecheck" name="chequerevokecheck" class="chequerevokecheck" value="revoke"> Revoke<br>
                            <button class="btn btn-primary chequerevokesubmit" id="chequerevokesubmit" name="chequerevoke" value="chequerevoke" type="submit">Submit</button>
                        </div>
                    </form>
                </div>
                <div class = "col-xs-2"></div>
            </div>
        </div>
    </div>
</div>


<div class="row comment">
</div>

<?php
include_once('..\footer.php');
?>