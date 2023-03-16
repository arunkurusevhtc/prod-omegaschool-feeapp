<?php
include_once('admnavbar.php');
$methods= array('atom','razorpay');
?>
<div class="container-fluid  contentcheque">
	<div class="col-lg-12">
			<h2 class="top">Manage Partial Payment</h2>
	</div>
<!-- 	<div class="col-lg-12 challanthereornot">
	</div> -->
    <div class="row col-md-12">
        <div class="col-xs-1 col-sm-2 col-md-3 col-lg-2"></div>
        <div class="col-xs-10 col-sm-8 col-md-6 col-lg-8 studentidthereornot">  
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

    <form  id="filteradvancereport" class="form-inline">
    	<div class="col-lg-12 text-right">
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
                    <select name="streamselect" id="streamselect"  class="streamselect form-control  streamchange">
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
                    <label>Ref Number</label>
                    <input type="text" name="txtref" id="txtref" placeholder="Ref Number" class="form-control">
                    <label>Student ID</label>
                    <input type="text" name="stid" id="stid" placeholder="Student ID" class="form-control">
            </div>
            <div class="form-group">
                <label>Payment Method</label>
                <select name="method" id="method"  class="form-control ">
                    <option value="">-All-</option>
                    <?php 
                    foreach ($methods as $value) {
                        ?>
                        <option value="<?php echo $value; ?>"><?php echo ucfirst($value); ?></option>
                        <?php
                    }
                    ?>                    
                </select>
            </div>
			<div class="form-group">
				<button type="button" id="fltpartialreport" name="filter" value="fltpartialreport" class="btn btn-info">Filter</button>
			</div>
			<?php if($roleid == 1) { ?>
			<div class="form-group">
				<a href="" class="btn btn-info partialexportexcel">Export Excel</a>
				<!--<a  id="paidexportexcel" name="paidexportexcel" class="btn btn-info paidexportexcel" style="display: none;">Export Excel</a>-->
			</div>
		  <?php } ?>
	 	</div>
	</form>
    <div style="clear:both">&nbsp;</div>
<div class="col-md-12">
    <div class="form-group pull-right ">
        <a href="addpartialpayment.php" class="btn btn-info" ><span >Add Partial Payment</span></a>
    </div>
</div>
	<div class="col-lg-12 m-t-15" >
		<div class="table-responsive">
			<form>
				<table class="table table-bordered admintab dataTablePartialReport">
					<thead>
						<tr>
                        <th>S.No</th>
                        <th>Ref Number</th>
                        <th>Student Id</th>
                        <th>Student Name</th>
                        <th>Stream</th>
                        <th>Challan No</th>
                        <th>Pay Option</th>
                        <th>Amount</th>
                        <!--<th>Status</th>-->
                        <th>Date</th>
                        <th>PayMethod</th>
                        <th>Action</th> 
                        
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
include_once(BASEPATH.'footer.php');
?>