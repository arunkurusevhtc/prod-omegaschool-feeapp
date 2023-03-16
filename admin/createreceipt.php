<?php
	include_once("admnavbar.php");
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
            <p class="heading">Create/Update Receipt</p>
            <div class="main">
                <form id="createreceipt" method="post" action="adminactions.php" >
                    <div class="form-group">
                        <label for ="name" class="control-label">Challan No</label>
                        <input type="text" id="chlnno" name="chlnno" required placeholder="Enter Challan Number" class="form-control">
                    </div>
                    <div class="form-group text-center">
                        <button type="submit" name="createreceipt" value="create" class="btn btn-primary text-center">Create</button>
                    </div>
                </form>
            </div>
        </div>
        <div id="studentData">
        	
        </div>
    </div>
    <div class="col-sm-2 col-md-3 col-lg-4"></div>
</div>