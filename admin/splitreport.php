<?php
include_once('admnavbar.php');

?>
<div class="container-fluid">
	<div class="col-lg-12">
			<h2 class="top">Detailed Report</h2>
	</div>
<!-- 	<div class="col-lg-12 challanthereornot">
	</div> -->
    <div class="row col-md-12">
        <div class="col-xs-1 col-sm-2 col-md-3 col-lg-2"></div>
        <div class="col-xs-10 col-sm-8 col-md-6 col-lg-8">  
            <?php
                if(isset($_SESSION['splitreportsuccess'])) {
                   echo $_SESSION['splitreportsuccess'];
                    unset($_SESSION['splitreportsuccess']);
                } else {
                   echo $_SESSION['splitreporterror'];
                   unset($_SESSION['splitreporterror']);
                } 
                ?>
        </div>
        <div class="col-xs-1 col-sm-2 col-md-3 col-lg-2"></div>
    </div>

	<div class="col-lg-12 text-right">
		<div class="col-lg-4">
		</div>
		<br/>
	</div>
	<div class="clear:both">&nbsp;</div>
	<div class="splitreport">
	    <form  id="filtersplitreport" class="form-inline"action = "adminactions.php" method="POST">
	    	<div class="col-lg-12 text-right">

		        <div class="form-group">
		            <div>
		                <?php
		                    $yearchecks = sqlgetresult("SELECT * FROM yearcheck",true);
	                    ?>
		                <select name="yearselect"  class="yearselect form-control ">
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
	        	<div class="form-group">
	             <div>
	                <?php
		                $streamtypes = sqlgetresult("SELECT * FROM streamcheck",true);
	                ?>
	                <select name="streamselect"  class="streamselect form-control streamchange">
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
	                <select name="classselect"  class="classselect  form-control classchange">
	                  <option value="">Class</option>                     
	                </select>
	              </div>                                    
	            </div>
	            <div class="form-group">
					<div>
						<select name="reporttype"  class="reporttype form-control">
	                  	<option value="">Report Type</option>
						<option value="DEMAND">Demand Report</option>
						<option value="RECEIPT">Receipt Report</option>
						<option value="WAIVER">Waiver Report</option>
						</select>
					</div>
				</div> 
				<div class="form-group">
					<!-- <label for ="date" class="control-label">From Date</label> -->
					<input type="text" name="fromdate" id="startdate" placeholder="From Date" class="form-control datepicker">
				</div>
				<div class="form-group">
					<!-- <label for ="date" class="control-label">To Date</label> -->
					<input type="text" name="todate" id="enddate" placeholder="To Date" class="form-control datepicker">
				</div>

	   	    <!-- </div> -->
	   	    <!-- <div class="clear:both">&nbsp;</div> -->
	   	    <!-- <div class="text-right"> -->
				<div class="form-group">
					<button type="submit" name="excel" value="splitreportexcel" class="btn btn-info">Export Excel</button>
				</div>
			</div>
		</form>
	</div>