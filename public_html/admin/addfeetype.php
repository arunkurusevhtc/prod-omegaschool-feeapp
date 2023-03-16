<?php
require_once('admnavbar.php');
?>
<div class="container">
	<div class="row">
		<div class="col-sm-2 col-md-3 col-lg-4"></div>
		<div class="col-sm-8 col-md-6 col-lg-4">
			<div class="content content1">
				<p class="heading">Add Fee Type</p>
				<div class="main">
					<input name="id" type="hidden" value="<?php echo $res['id'];?>" />
					<form id="user_registered" method="post" action="adminactions.php">
						<div class="form-group">
								<label for ="ftype" class="control-label">Fee Type</label>
								<input type="text" id="ftype" name="ftype" required placeholder="Class Name" class="form-control">
						</div>
						<div class="form-group">
								<label for ="des" class="control-label">Description</label>
								<input type="text" id="des" name="des" placeholder="Description" class="form-control">
						</div>
						<div class="form-group">
								<label for ="des" class="control-label">Group</label>
								<select name="group"  class="group  form-control">
									  <option value="Select">Select</option>
				                      <option value="SCHOOL FEE">SCHOOL FEE</option>
				                      <option value="SCHOOL UTILITY FEE">SCHOOL UTILITY FEE</option>
				                      <option value="SFS UTILITIES FEE">SFS UTILITIES FEE</option>
				                      <option value="TRANSPORT FEE">TRANSPORT FEE</option>

				                      
			                    </select>
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
								
									<div class="col-md-4 p-l-0">
                          <label class="control-label">Applicable For</label>  
                                   </div>


                        <div class="col-md-8 p-r-0">
							
                         <label class="control-label"><input type="checkbox" name="dayscholar"> Day-Scholar</label>
                         <label for ="mandatory" class="control-label"><input type="checkbox"  name="hosteller"> Hosteller</label>
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
