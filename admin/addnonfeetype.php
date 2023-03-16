<?php
	require_once('admnavbar.php');
?> 

<div class="container">
	<div class="row">
		<div class="col-sm-2 col-md-3 col-lg-4"></div>
		<div class="col-sm-8 col-md-6 col-lg-4">
			<div class="content content1">
				<p class="heading">Add Non Fee Type</p>
				<div class="main">
					<input name="id" type="hidden" value="<?php echo $res['id'];?>" />
					<form id="user_registered" method="post" action="adminactions.php">
						<div class="form-group">
								<label for ="ftype" class="control-label">Non Fee Type</label>
								<input type="text" id="nftype" name="nftype" required placeholder="Fee Name" class="form-control">
						</div>
						<div class="form-group">
								<label for ="des" class="control-label">Description</label>
								<input type="text" id="des" name="des" placeholder="Description" class="form-control">
						</div>
						<div class="form-group">
                            <label class="control-label" for="studStatus">Fee Group</label>
                            <div>
                                 <?php
                                    $feeGroups = sqlgetresult("SELECT * FROM feegroupcheck WHERE id=13");
                                    // print_r($feeGroups);
                                ?>
                                <!-- <input type="hidden" name="selected_feegroup"> -->
                                <select name="feegroup"  class="form-control">                                	
                                    <?php
                                        echo '<option value="'.$feeGroups['id'].'">'.$feeGroups['feeGroup'].'</option>';
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
								<label for ="common" class="control-label"><input type="checkbox"  name="common" id="commonnonfeetype"> Common</label>
							</div> 
					    </div>	
					    <div class="form-group container col-md-12 p-l-0 p-r-0">								
							<div class="col-md-4 p-l-0">
								<label class="control-label">Create Challan</label>  
							</div>
							<div class="col-md-8 p-r-0">
								<label class="control-label"><input type="radio" name="challan" value="1" required=""> Yes</label>
								<label for ="mandatory" class="control-label"><input type="radio" id="noforchallaninnonfee" name="challan" value="0"> No</label>
							</div> 
					    </div>
					    <div class="form-group" id="productdetails">
                            <label class="control-label" for="studStatus">Product Details</label>
                            <div>
                                <?php
								$accounts = sqlgetresult("SELECT * FROM tbl_accounts WHERE id = '$acc_id'",1);
								$accountdetails=$accounts[0];
								asort($accountdetails);
                                ?>
                                <select name="acc_id"  class="form-control" id="acc_id" required>
                                	<option value="">--select--</option>
                                	<?php
	                                	foreach($accountdetails as $key=>$value) {
	                                		if($value && $key!='id'){
                                              echo '<option value="'.$key.'">'.$value.' - '.$key.'</option>';
	                                		}
	                                    }
                                    ?>
                                </select>
                            </div>                                    
                        </div>							 
					    <!-- <input type="checkbox"> -->
						<div class="form-group text-center">
							<button type="submit" value="new" name="addnonfeetype" class="btn btn-primary text-center">Add</button>
							<a href="nonfeetype.php"><button type="button" value="Go Back" class="btn btn-warning text-center">Back</button></a>
					    </div>		    
					    
                    </form>
				</div>
			</div>
		</div>
		<div class="col-sm-2 col-md-3 col-lg-4"></div>
	</div>
</div>

<?php
	include_once(BASEPATH.'footer.php');
?>