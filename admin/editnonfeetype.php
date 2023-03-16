<?php
	require_once('admnavbar.php');
	$id= $_GET['id'];
	if( $id > 0 )
		$nonfeetypedata = sqlgetresult("SELECT * FROM tbl_nonfee_type WHERE id= $id ");
	else 
		header('Location:nonfeetype.php');
?>

<div class="container">
	<div class="row">
		<div class="col-sm-2 col-md-3 col-lg-4"></div>
		<div class="col-sm-8 col-md-6 col-lg-4">
			<div class="content content1">
				<p class="heading">Edit Non Fee Type</p>
				<div class="main">
					<input name="id" type="hidden" value="<?php echo $res['id'];?>" />
					<form id="user_registered" method="post" action="adminactions.php">
						<input type="hidden" name="id" value="<?php echo $nonfeetypedata['id'];?>">
						<div class="form-group">
								<label for ="ftype" class="control-label">Non Fee Type</label>
								<input type="text" id="nftype" name="nftype" required placeholder="Fee Name" class="form-control" value="<?php echo $nonfeetypedata['feeType'];?>">
						</div>
						<div class="form-group">
								<label for ="des" class="control-label">Description</label>
								<input type="text" id="des" name="des" placeholder="Description" class="form-control" value="<?php echo $nonfeetypedata['description'];?>">
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
								<label class="control-label"><input type="checkbox" name="dayscholar" <?php if($nonfeetypedata['applicable'] == 'D' || $nonfeetypedata['applicable'] == 'DH') echo 'checked';?>> Day-Scholar</label>
								<label for ="mandatory" class="control-label"><input type="checkbox" <?php if($nonfeetypedata['applicable'] == 'H' || $nonfeetypedata['applicable'] == 'DH') echo 'checked';?> name="hosteller"> Hosteller</label>
								<label for ="common" class="control-label"><input type="checkbox" <?php if($nonfeetypedata['applicable'] == 'C') echo 'checked';?> name="common" id="commonnonfeetype"> Common</label>
							</div> 
					    </div>	
					    <div class="form-group container col-md-12 p-l-0 p-r-0">								
							<div class="col-md-4 p-l-0">
								<label class="control-label">Create Challan</label>  
							</div>
							<div class="col-md-8 p-r-0">
								<label class="control-label"><input type="radio" name="challan" value="1" required="" <?php if($nonfeetypedata['challan'] == '1') echo 'checked';?> > Yes</label>
								<label for ="mandatory" class="control-label"><input type="radio"  name="challan" value="0" <?php if($nonfeetypedata['challan'] == '0' ) echo 'checked';?> > No</label>
							</div> 
					    </div>
					    <?php
					   
					    $acc="";
					    if($nonfeetypedata['acc_no']){
					    	$acc=trim($nonfeetypedata['acc_no']);
					    }
					    ?>
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
	                                		    if($acc==$key){
	                                               $sel='selected="selected"';
		                                		}else{
	                                               $sel='';
		                                		}
                                              echo '<option value="'.$key.'" '.$sel.'>'.$value.' - '.$key.'</option>';
	                                		}
	                                    }
                                    ?>
                                </select>
                            </div>                                    
                        </div>						 
					    <!-- <input type="checkbox"> -->
						<div class="form-group text-center">
							<button type="submit" value="update" name="editnonfeetype" class="btn btn-primary text-center">update</button>
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