<?php
require_once('admnavbar.php');
?>
<div class="container contentcheque">
	<div class="row">
		<div class="col-sm-2 col-md-3 col-lg-4"></div>
		<div class="col-sm-8 col-md-6 col-lg-4">
			<div class="content content1">
				<p class="heading">Add Fee Type</p>
				<div class="main">
					<input name="id" type="hidden" value="<?php echo $res['id'];?>" />
					<form method="post" action="adminactions.php">
						<div class="form-group">
								<label for ="ftype" class="control-label">Fee Type</label>
								<input type="text" id="ftype" name="ftype" required placeholder="Fee Type" class="form-control">
						</div>
						<div class="form-group">
								<label for ="des" class="control-label">Description</label>
								<input type="text" id="des" name="des" required placeholder="Description" class="form-control">
						</div>
						<div class="form-group">
                            <label class="control-label" for="studStatus">Fee Group</label>
                            <div>
                                 <?php
                                    $feeGroups = sqlgetresult("SELECT * FROM feegroupcheck",true);
                                    // print_r($feeTypes);
                                ?>
                                <!-- <input type="hidden" name="selected_feegroup"> -->
                                <select name="feegroup"  class="form-control" required>
                                	<option value="">--Select--</option>
                                    <?php
                                        foreach($feeGroups as $feegroup) {
                                             echo '<option value="'.$feegroup['id'].'">'.$feegroup['feeGroup'].'</option>';
                                       }
                                    ?>
                                </select>
                            </div>                                    
                        </div>
						<div class="form-group">
                            <label class="control-label" for="studStatus">Enable Tax:</label>
                            <div>
                                 <?php
                                    $feeTypes = sqlgetresult("SELECT * FROM taxcheck",true);
                                    // print_r($feeTypes);
                                ?>
                                <input type="hidden" name="selected_taxtypes" class="selected_quizsetids">
                                <select name="taxtype"  class="quizsetid form-control" multiple="multiple">
                                    <?php
                                        foreach($feeTypes as $feetype) {
                                             echo '<option value="'.$feetype['id'].'">'.$feetype['taxType'].'</option>';
                                       }
                                    ?>
                                </select>
                            </div>                                    
                        </div>
                        <div class="form-group container col-md-12 p-l-0 p-r-0">
							<div class="col-md-4 p-l-0 p-r-0">
								<label class="control-label">Partial Option</label>  
							</div>
							<div class="col-md-8 p-r-0">
							<?php 
							echo'<input type="checkbox"  id="ispartial"  name="ispartial" value="1">';
							?>
							</div>
						</div>
						<div class="form-group container col-md-12 p-l-0 p-r-0 duedetails"  style="display: none;">
							<div class="form-group">
								<div class="col-md-4 p-l-0 p-r-0">
									<label class="control-label">Due Fee Types:</label>  
								</div>
	                            <div class="col-sm-8 p-r-0">
	                                <select name="feetype" id="feetype"  class="form-control">
	                                	<option value="">-select-</option>
	                                    <?php
										$feeTypes = sqlgetresult('SELECT id, "feeType" FROM getfeetypes',true);
										foreach($feeTypes as $feetype) {
												echo '<option value="'.$feetype['id'].'">'.$feetype['feeType'].'</option>';
											}                             
	                                    ?>
	                                </select>
	                            </div>                                    
                            </div>
                            <div class="form-group">&nbsp;</div>
	                        <div class="form-group">
	                        	<div class="col-md-4 p-l-0 p-r-0">
		                            <label class="control-label">Due Date</label>
		                        </div>
	                            <div class="col-sm-8 p-r-0">
	                                <input type="text" class="duedate datepicker form-control" id="duedate" name="duedate" placeholder="Due Date" />
                                </div>
	                        </div>
	                    </div>
                        <div class="form-group container col-md-12 p-l-0 p-r-0">
							<div class="col-md-4 p-l-0">
		                          <label class="control-label">Applicable For</label>  
                            </div>
	                        <div class="col-md-8 p-r-0">
		                         <label class="control-label"><input type="checkbox" name="dayscholar"> Day-Scholar</label>
		                         <label for ="mandatory" class="control-label"><input type="checkbox"  name="hosteller"> Hosteller</label>
		                         <label class="control-label"><input type="checkbox"  name="lunch"> Lunch</label>
		                         <label class="control-label"><input type="checkbox"  name="uniform"> Uniform</label>
		                         <label class="control-label"><input type="checkbox"  name="transport"> Transport</label>
		                         <label class="control-label"><input type="checkbox" id="commfee"  name="common"> Common-Fees</label>
					         </div> 
					    </div>
					    <div class="form-group productdetails" style="display: none;">
                            <label class="control-label">Max Quantity</label>
                            <div>
                                <select name="max" id="max"  class="form-control">
                                    <?php
                                        for($i=1;$i<=10;$i++) {
                                        	if($i==4){
                                        		$sel="selected='selected'";
                                        	}else{
                                        		$sel="";
                                        	}
                                          echo '<option value="'.$i.'" '.$sel.'>'.$i.'</option>';
                                       }
                                    ?>
                                </select>
                            </div>                                    
                        </div>
						 <div class="form-group">
								
								<input type="checkbox" id="mandatory" name="mandatory">
								<label for ="mandatory" class="control-label">Mandatory</label>
						</div>
					    <!-- <input type="checkbox"> -->
						<div class="form-group text-center">
							<button type="submit" value="new" name="addfeetype" class="btn btn-primary text-center">Add</button>
							<a href="managefeetype.php"><button type="button" value="Go Back" class="btn btn-warning text-center">Back</button></a>
					    </div>
					    
					    
                    </form>
				</div>
			</div>
		</div>
		<div class="col-sm-2 col-md-3 col-lg-4"></div>
	</div>
</div>
<div class="row comment">
       
</div>
<?php






include_once(BASEPATH.'footer.php');
?>
