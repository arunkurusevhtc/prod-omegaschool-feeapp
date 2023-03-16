<?php 
    require_once('admnavbar.php');
    ?>
<div class="container_fluid">
    <div class="col-sm-2 col-md-3 col-lg-4"></div>
    <div class="col-sm-8 col-md-6 col-lg-4 transchallan">
        <div class="errormessage">
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
        <div class="">
            <p class="heading">Add/Update Challan</p>
            <div class="main">
                <form id="getStudentData">
                    <div class="form-group">
                        <label for ="name" class="control-label">Challan Number</label>
                        <input type="text" id="chlnno" name="chlnno" required placeholder="Enter Challan Number" class="form-control">
                    </div>
                    <div class="form-group text-center">
                        <button type="submit" id="createtranschallan" class="btn btn-primary text-center">Confirm</button>
                    </div>
                </form>
            </div>
        </div>
        <div id="studentData">
        	
        </div>
    </div>
    <div class="col-sm-2 col-md-3 col-lg-4"></div>
</div>

<div class="row comment"></div>

<div class="modal fade" id="viewChallanModal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Fee Challan - Payment</h4>
            </div>
            <div class="modal-body">
                <p>Please review the details of your challan and confirm to continue...</p>
                <div class="table-responsive">
                    <form method="post" id="studDataModal" action="adminactions.php">
                        <div id="viewchallanData"></div>                                     
                        <div class="text-center">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" name='updatec' value="update" id="updatec" class="btn btn-primary" >Update Challan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
	include_once(BASEPATH.'footer.php');
?>