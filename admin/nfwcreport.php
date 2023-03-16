<?php
include_once('admnavbar.php');

?>
<div class="container-fluid  contentcheque">
	<div class="col-lg-12">
			<h2 class="top">NON-Fee with Challan - Paid Report</h2>
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
    	<div class="col-lg-12">
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
                    <select name="streamselect" id="streamselect"  class="streamselect form-control ">
                        <option value="">Stream</option>
                        <?php
                            foreach($streamtypes as $stream) {
                            echo '<option value="'.$stream['id'].'" >'.$stream['stream'].'</option>';
                            }
                            ?>
                    </select>
            </div>
             <div class="form-group">

                        <?php
                          $classstypes = sqlgetresult("SELECT * FROM classcheck",true);
                        ?>
                       <select id="classselect" name="classselect"  class="classselect  form-control classchange">
                        <option value="">-Class-</option>
                      <?php
                      foreach($classstypes as $class) {
                      echo '<option value="'.$class['id'].'">'.$class['class_list'].'</option>';
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
                <select name="status" id="status" class="form-control ">
                    <option value="">-All-</option>
                    <option value="Ok" selected="selected">Completed</option>
                    <option value="F">Failed</option>
                    <option value="C">Canceled</option>
                </select>
             </div>  
             </div>
             <div style="clear:both">&nbsp;</div>
             <div class="col-lg-12"> 
            <div class="form-group">
                    <label>From</label>
                    <input type="text" name="from" id="from" placeholder="From Date" class="form-control datepicker">
                    <label>To</label>
                    <input type="text" name="to" id="to" placeholder="To Date" class="form-control datepicker">
            </div>
           <div class="form-group">
                <?php
                if( $res[0]['hostel_need'] == 'Y') {
                    $feeTypes = sqlgetresult("SELECT * FROM tbl_nonfee_type WHERE applicable='DH' OR applicable='H'",true);
                } else {
                    $feeTypes = sqlgetresult("SELECT * FROM tbl_nonfee_type WHERE applicable='DH' OR applicable='D' ",true);
                }
                ?>
                <input type="hidden" id="selected_feetypes" name="selected_feetypes" class="selected_quizsetids">
                <select name="feetype"  class="quizsetid form-control" multiple="multiple">
                    <?php
                    $feetypesgroup = sqlgetresult("SELECT * FROM feegroupcheck",true);
                    // print_r($feetypesgroup);                                   
                    echo '<optgroup label="NON-FEE GROUP"></optgroup>';
                    foreach($feeTypes as $feetype) {
                        echo '<option value="'.$feetype['id'].'">'.$feetype['feeType'].'</option>';
                    }                               
                    ?>
                </select>           
            </div> 
			<div class="form-group">
				<button type="button" id="nfwcfltpaidreport" name="filter" value="nfwcfltpaidreport" class="btn btn-info">Filter</button>
			</div>
			<?php if($roleid == 1) { ?>
			<div class="form-group">
				<a href="" class="btn btn-info nfwcpaidexportexcel">Export Excel</a>
			</div>
		  <?php } ?>
	 	</div>
	</form>
  
	<div class="col-lg-12 m-t-15" >
		<div class="table-responsive">
			<form>
				<table class="table table-bordered admintab dataTableNFWCReport">
					<thead>
						<tr>
                        <th>S.No</th>
                        <th>RefNumber</th>
                        <th>StudentId</th>
                        <th>StudentName</th>
                        <th>Stream</th>
                        <th>Class</th>
                        <th>Term</th>
                        <th>AYear</th>
                        <th>FeeType</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Date</th> 
                        <th>ChallanNo</th>
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