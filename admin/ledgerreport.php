<?php
include_once('admnavbar.php');

?>
<div class="container-fluid">
	<div class="col-lg-12">
			<h2 class="top">Ledger Report</h2>
	</div>
    <div class="row col-md-12">
        <div class="col-xs-1 col-sm-2 col-md-3 col-lg-2"></div>
        <div class="col-xs-10 col-sm-8 col-md-6 col-lg-8">  
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
    	<div class="clear:both">&nbsp;</div>
	<div class="clear:both">&nbsp;</div>

	<div class="col-lg-12 text-right">
		<div class="col-lg-4">
		</div>
		<div class="col-lg-8 text-right">
			<form  id="changechallanstatus" class="form-inline" method="POST" action="adminactions.php">
				<div class="col-lg-8">
				</div>
				<div class="col-lg-4 form-group">
					<a href="#ledgermodal" data-toggle="modal" name="ledgermodal" class="ledgermodal btn btn-info" >Change Challan Status</a>
				</div>	
			</form>
		</div>
		<br/>
	</div>
	<div class="clear:both">&nbsp;</div>

    <form  id="filterledgerreport" class="form-inline">
    	<div class="col-lg-12 text-right">
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
					<select id="entrytype" name="entrytype"  class="entrytype form-control">
                  	<option value="">Entry Type</option>
					<option value="DEMAND">Demand</option>
					<option value="RECEIPT">Receipt</option>
					<option value="WAIVER">Waiver</option>
					</select>
				</div>
			</div> 
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
                	$classstypes = sqlgetresult("SELECT * FROM classcheck",true);
                ?>
                <select id="classselect" name="classselect"  class="classselect  form-control classchange">
                  <option value="">Class</option>                     
                </select>
              </div>                                    
            </div>
			<div class="form-group">
				<div>
					<select id="challanStatus" name="challanStatus"  class="challanStatus form-control">
                  	<option value="">Challan Status</option>
					<option value="1">Active</option>
					<option value="0">Inactive</option>
					
					</select>
				</div>
			</div>
			<div class="form-group">
	            <div>
	                <?php
	                    $feegrouptypes = sqlgetresult("SELECT * FROM feegroupcheck",true);
                    ?>
	                <select id="feegroupselect" name="feegroupselect"  class="feegroupselect form-control feegroupchange">
	                    <option value="">Fee Group</option>
	                    <?php
	                        foreach($feegrouptypes as $feegroup) {
	                        echo '<option value="'.$feegroup['id'].'" >'.$feegroup['feeGroup'].'</option>';
	                        }
	                        ?>
	                </select>
	            </div>
	        </div>
	        <div class="form-group">
	            <div>
	                <?php
	                    $feetypetypes = sqlgetresult("SELECT * FROM feetypecheck",true);
                    ?>
	                <select id="feetypeselect" name="feetypeselect"  class="feetypeselect form-control ">
	                    <option value="">Fee Type</option>                       
	                </select>
	            </div>
	        </div>
   	    </div>
   	    <div class="clear:both">&nbsp;</div>
   	    <div class="text-right">
			<div class="form-group">
				<button type="button" id="fltledgerreport" name="filter" value="filterledgerreport" class="btn btn-info">Filter</button>
			</div>
			<div class="form-group">
				<a href="#ledgermodal" class="btn btn-info ledgerreportexportexcel">Export Excel</a>
			</div>
            <div class="form-group">
                <!-- <button type="submit" name="ledgerreportexportexcel" value="ledgerreportexportexcel" class="btn btn-info ledgerreportexportexcel">Export Excel</button> -->
                <a href="#ledgermodal" class="btn btn-info ledgerreportcoloumnwiseexportexcel">Coloumn Wise Export Excel</a>
            </div>
		</div>
	</form>
	<div class="col-lg-12 m-t-15" >
		<div class="table-responsive">
			<form>
				<table class="table table-bordered admintab dataTableDemandReport">
					<thead>
						<tr>
						<th>S.No</th>
						<th>Student ID</th>
						<th>Challan No</th>
						<th>Student Name </th>
						<th>Academic Year</th>
						<th>Class</th>
						<th>Stream</th>
						<th>Term</th>
						<th>Date</th>
						<th>Fee Group</th>
						<th>Fee Type</th>
						<th>Total</th>
						<th>Remarks</th>
						<th>Entry Type</th>
						<th>Challan Status</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</form>
		</div>
	</div>
</div>

<div class="modal fade" id="ledgermodal" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close closed" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Student Details</h4>
            </div>
            <div class="modal-body con">
                <h4 class="addtitle">Please verify the Students details</h4>
                    <div class="row col-md-12">
				        <div class="col-xs-1 col-sm-2 col-md-3 col-lg-2"></div>
				        <div class="col-xs-10 col-sm-8 col-md-6 col-lg-8 challanthereornot">  
				        </div>
				        <div class="col-xs-1 col-sm-2 col-md-3 col-lg-2"></div>
				    </div>
                <form class="form-horizontal" action="adminactions.php" method="post">
                    <div class="form-group">
                            <label class="control-label col-sm-4" for="studStatus">Student Id:</label>
                            <div class="col-sm-8">
                                <input type="text" name="ledgerstudentid" id="ledgerstudentid" class="ledgerstudentid form-control" required placeholder="Student Id">
                        </div>                                    
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="email">Challan No:</label>
                        <div class="col-sm-8">
	                    <?php
	                    	$classstypes = sqlgetresult("SELECT * FROM classcheck",true);
	                    ?>
	                    <select name="challanselect"  class="challanselect  form-control">
	                      <option value="">Challan No</option>                     
	                    </select>
                        </div>
                        
                    </div> 
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="studStatus">Student Name:</label>
                        <div class="col-sm-8">
                            <input type="text" placeholder="Student Name" class="ldgstdname form-control" readonly>
                        </div>                                    
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="email">Academic Year:</label>
                        <div class="col-sm-8">
                           <input type="text" placeholder="Academic Year" class="ldgacayear form-control" readonly>
                    	</div>
                    </div>   
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="waivertype">Semester:</label>
                        <div class="col-sm-8">
                           <input type="text" placeholder="Semseter" class="ldgsem form-control" readonly>
                    	</div>
                    </div>	
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="email">Class:</label>
                        <div class="col-sm-8">
                            <input type="text" placeholder="Class" class="ldgclass form-control" readonly>
                    	</div>
                    </div> 
                    <div>
                        <label class="control-label col-sm-4" for="email">Status:</label>
                        <div class="col-sm-8">
                            <input type="radio" name="status" value="1" id="active">Active
							<input type="radio" name="status" value="0" id="inactive">In Active
                    	</div>
                    </div>   
                    <br/> 
                    <br/>           
                    <div class="text-center">
                        <button class="btn btn-default closed"  type="button" data-dismiss="modal">Close</button>
                        <input class="btn btn-primary" id="ledgerchangestatus" type="submit" name="ledgerchangestatus" value="Change Status">
                    </div>                    
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).on("change", ".feegroupchange", function() {
        var feeGrpId = $(this).val();
        if (feeGrpId.length != 0) {
            $.ajax({
                url: 'adminactions.php',
                method: 'post',
                dataType: 'json',
                data: {
                    'submit': 'getFeeTypeData',
                    'data': feeGrpId
                },
                success: function(response) {
                    console.log(response);
                    var options = '<option value="">Fee Type</option>';
                    $.each(response, function(i, val) {
                        options += '<option value="' + val.value + '">' + val.label + '</option>'
                    });
                    $(".feetypeselect").html(options)
                }
            })
        } else {
            $(".feetypeselect").html('<option value="">Fee Type</option>')
        }
    });
</script>