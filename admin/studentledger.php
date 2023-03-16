<?php
include_once('admnavbar.php');

?>
<div class="container-fluid">
	<div class="col-lg-12">
			<h2 class="top">Student Ledger</h2>
	</div>
    <div class="row col-md-12">
        <div class="col-xs-1 col-sm-2 col-md-3 col-lg-2"></div>
        <div class="col-xs-10 col-sm-8 col-md-6 col-lg-8 studentidthereornot">  
            <?php
                if(isset($_SESSION['successchallanstatus'])) {
                   echo $_SESSION['successchallanstatus'];
                    unset($_SESSION['successchallanstatus']);
                } else {
                   echo $_SESSION['errorchallanstatus'];
                   unset($_SESSION['errorchallanstatus']);
                } 
                ?>
        </div>
        <div class="col-xs-1 col-sm-2 col-md-3 col-lg-2"></div>
    </div>
    <form  id="filterstudentledger" class="form-inline">
    	<div class="col-lg-12 text-right">
    		<div class="form-group">
                <div>
                    <?php
                        $yearchecks = sqlgetresult("SELECT * FROM yearcheck",true);
                        ?>
                    <select id="yearselect" name="yearselect"  class="yearselect form-control ">
                        <option value="">Acad.Year</option>
                        <?php
                            foreach($yearchecks as $yearcheck) {
                            echo '<option value="'.$yearcheck['year'].'" >'.$yearcheck['year'].'</option>';
                            }
                            ?>
                    </select>
                </div>
            </div> 
    		<div class="form-group">
					<div>
					<input type="text" name="studentidreport" id="studentidreport" class="studentidreport form-control" required placeholder="Student Id">
					</div>
			</div>
			<div class="form-group">
				<button type="button" id="fltstudentledger" name="filter" value="filterstudentledger" class="btn btn-info">Filter</button>
			</div>
			<?php if($roleid == 1) { ?>
			<div class="form-group">
				<a href="" class="btn btn-info studentledgerexportexcel">Export Excel</a>
			</div>
			<?php } ?>
	 	</div>
	</form>
	<div class="col-lg-12 m-t-15" >
		<div class="table-responsive">
			<form>
				<table class="table table-bordered admintab dataTableDemandReport">
					<thead>
						<tr>
						<th style="width: 1%">S.No</th>
						<th style="width: 10%">Student ID</th>
						<th style="width: 10%">Challan No</th>
						<th style="width: 15%">Student Name </th>
						<th style="width: 1%">AcademicYear</th>
						<th style="width: 1%">Class</th>
						<th style="width: 1%">Stream</th>
						<th style="width: 1%">Term</th>
						<th style="width: 8%">Date</th>
						<th style="width: 15%">Fee Group</th>
						<th style="width: 20%">Fee Type</th>
						<th>Total</th>
						<th style="width: 15%">Remarks</th>
						<th style="width: 1%">EntryType</th>
						<th style="width: 1%">Status</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</form>
		</div>
	</div>
</div>