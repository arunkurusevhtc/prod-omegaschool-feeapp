<?php

include_once('admnavbar.php');
$ReceiptData = "";
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

<div class="col-md-12 ">
    <div class="col-md-6 p-l-0">
        <h2 class="top">RECEIPT REPORT</h2>
    </div>
    <div class="col-md-6 p-r-0 text-right">
        <form  id="filterreceiptreport" class="form-inline">
            <div class="col-md-3 form-group">
                <div>
                    <?php
                        $yearchecks = sqlgetresult("SELECT * FROM yearcheck",true);
                        ?>
                    <select name="yearselect"  class="yearselect form-control ">
                        <option value="">Acad.Year</option>
                        <?php
                            foreach($yearchecks as $yearcheck) {
                            echo '<option value="'.$yearcheck['id'].'" >'.$yearcheck['year'].'</option>';
                            }
                            ?>
                    </select>
                </div>
            </div>            
            <div class="col-md-3 form-group">
                <div>
                    <?php
                        $streamtypes = sqlgetresult("SELECT * FROM streamcheck",true);
                        ?>
                    <select name="streamselect"  class="streamselect form-control ">
                        <option value="">Stream</option>
                        <?php
                            foreach($streamtypes as $stream) {
                            echo '<option value="'.$stream['id'].'" >'.$stream['stream'].'</option>';
                            }
                            ?>
                    </select>
                </div>
            </div>
            <div class="col-md-3 form-group">
                <div>
                    <?php
                        $semestercheck = sqlgetresult('SELECT * FROM tbl_semester',true);
                        ?>
                    <select name="semesterselect"  class="semesterselect form-control ">
                        <option value="">Semester</option>
                        <?php
                        
                            foreach($semestercheck as $semester) {
                            echo '<option value="'.$semester['semester'].'" >'.$semester['semester'].'</option>';
                            }
                            ?>
                    </select>
                </div>
            </div>
            <div class="col-md-3 form-group">
                <button type="submit" name="filter" value="filterreceiptreport" class="btn btn-info">Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="col-md-12 m-t-15" >
    <div class="table-responsive">
        <form>
            <table class="table table-bordered admintab dataTableReceiptReport">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Student Id</th>
                        <th>Student Name</th>
                        <th>Stream</th> 
                        <th>Action</th>                      
                    </tr>
                </thead>
                <tbody>
                    <tr></td><td></td><td></td><td style="text-align: center;">No Data Available.<td></td><td></td></tr>
                </tbody>
            </table>
        </form>
    </div>
</div>
</div>

<div class="row comment">
</div>

