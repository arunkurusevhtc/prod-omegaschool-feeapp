<?php
require_once('admnavbar.php');
$id=$_REQUEST['id'];
$query = "SELECT * FROM feetypecheck WHERE id='$id'"; 
$res = sqlgetresult($query);
// print_r($res);
$data = explode (",", $res['tax']);
$grpdata = explode (",", $res['feeGroup']);
$max = !empty($res['maxquantity'])?$res['maxquantity']:0;
$ispartial = !empty($res['ispartial'])?$res['ispartial']:0;
if($ispartial>0){
	$partdisp="block";
	$req="required='required'";
}else{
	$partdisp="none";
	$req="";
}
$next_feetype_id = !empty($res['next_feetype_id'])?$res['next_feetype_id']:"";
$next_due_date = !empty($res['next_due_date'])?$res['next_due_date']:"";
if($max>0){
	$disp="block";
}else{
	$disp="none";
}
?>
<div class="container contentcheque">
	<div class="row">
		<div class="col-sm-2 col-md-3 col-lg-4"></div>
		<div class="col-sm-8 col-md-6 col-lg-4">
			<div class="content content1">
				<p class="heading">Edit Fee Type</p>
                <div class="main">
					<form id="user_registered" method="post" action="adminactions.php">
						<input name="id" type="hidden" value="<?php echo $res['id'];?>" />
						<div class="form-group">
								<label for ="ftype" class="control-label">Fee Type</label>
								<input type="text" id="ftype" name="ftype" required disabled placeholder="Fee Type" class="form-control" value="<?php echo trim($res['feeType']);?>">
						</div>
						<div class="form-group">
								<label for ="des" class="control-label">Description</label>
								<input type="text" id="des" name="des" required placeholder="Description" class="form-control" value="<?php echo trim($res['description']);?>">
						</div>
						<div class="form-group">
                            <label class="control-label" for="studStatus">Fee Group</label>
                            <div>
                                 <?php
                                    $feeGroups = sqlgetresult('SELECT * FROM feegroupcheck',true);
                                    $grpdata=array_map('trim',$grpdata);
                                ?>
                                <select name="feegroup"  class="form-control" required>
                                
                                    <?php
                                        
                                     foreach ($feeGroups as $feegroup) {  
                          				if (in_array(trim($feegroup['feeGroup']), $grpdata)) {
	                                    	echo '<option selected  value="'.$feegroup['id'].'">'.$feegroup['feeGroup'].'</option>';
	                                    }else{
	                                    	echo '<option value="'.$feegroup['id'].'">'.$feegroup['feeGroup'].'</option>';
	                                    }
		                            }                                   
                                  
                                    ?>
                                </select>
                        </div>                                    
                        </div>
						<div class="form-group">
                            <label class="control-label" for="studStatus">Enable Tax:</label>
                            <div>
                                 <?php
                                    $taxtype = sqlgetresult('SELECT * FROM taxcheck',true);
                                  
                                    $data=array_map('trim',$data);
                                ?>
                                <input type="hidden" name="oldtax" id="oldtax" value="<?php echo trim($res['tax']);?>">
                                <input type="hidden" name="selected_taxtypes" class="selected_quizsetids">
                                <select name="taxtype"  class="quizsetid form-control" multiple="multiple">
                                    <?php
                                        
                                     foreach ($taxtype as $taxtypes) {  
                          				if (in_array(trim($taxtypes['id']), $data)) {
	                                    	echo '<option selected  value="'.$taxtypes['id'].'">'.$taxtypes['taxType'].'</option>';
	                                    }else{
	                                    	echo '<option value="'.$taxtypes['id'].'">'.$taxtypes['taxType'].'</option>';
	                                    }
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
							$par_chked='';
							if($ispartial == 1){
								$par_chked='checked="checked"';
							}
							echo'<input type="checkbox"  id="ispartial"  name="ispartial" value="1" '.$par_chked.'>';
							?>
							</div>
						</div>
						<div class="form-group container col-md-12 p-l-0 p-r-0 duedetails"  style="display: <?php echo $partdisp; ?>;">
							<div class="form-group">
								<div class="col-md-4 p-l-0 p-r-0">
									<label class="control-label">Due Fee Types:</label>  
								</div>
	                            <div class="col-sm-8 p-r-0">
	                                <select name="feetype" id="feetype"  class="form-control"  <?php echo $req; ?>>
	                                	<option value="">-select-</option>
	                                    <?php
										$feeTypes = sqlgetresult('SELECT id, "feeType" FROM getfeetypes',true);
										foreach($feeTypes as $feetype) {
												if($next_feetype_id == $feetype['id']){
													$sel="selected='selected'";
												}else{
													$sel="";
												}
												echo '<option value="'.$feetype['id'].'" '.$sel.'>'.$feetype['feeType'].'</option>';
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
	                                <input type="text" class="duedate datepicker form-control" id="duedate" name="duedate" placeholder="Due Date" value="<?php echo $next_due_date; ?>" <?php echo $req; ?> />
                                </div>
	                        </div>
	                    </div>

                        	   <div class="form-group container col-md-12 p-l-0 p-r-0">
						   	
									<div class="col-md-4 p-l-0 p-r-0">
                              <label class="control-label">Applicable For</label>  
                                   </div>

						  <div class="col-md-8 p-r-0">
                             <?php 
						  	 $appl=trim($res['applicable']);
						  	 $d_chked='';$h_chked='';$l_chked='';$u_chked='';$t_chked='';$c_chked='';

						  	 $arr_appl=str_split($appl);
					  	 	 foreach($arr_appl as $val){
					  	 		if($val == 'D'){
                                  $d_chked='checked="checked"';
					  	 		}
					  	 		if($val == 'H'){
                                  $h_chked='checked="checked"';
					  	 		}
					  	 		if($val == 'L'){
                                  $l_chked='checked="checked"';
					  	 		}
					  	 		if($val == 'U'){
                                  $u_chked='checked="checked"';
					  	 		}
					  	 		if($val == 'T'){
                                  $t_chked='checked="checked"';
					  	 		}
					  	 		if($val == 'C'){
                                  $c_chked='checked="checked"';
					  	 		}
					  	 	 }

						  	 	echo '<input type="checkbox" id="mandatory" name="dayscholar" '.$d_chked.'>';
								echo '<label for ="mandatory" class="control-label"> Day-Scholar</label>';
								echo'<input type="checkbox" id="mandatory" name="hosteller" '.$h_chked.'>';
								echo '<label for ="mandatory" class="control-label"> Hosteller</label>';
								echo'<input type="checkbox" name="lunch" '.$l_chked.'>';
								echo "<label for ='mandatory' class='control-label'> Lunch</label>";
								echo'<input type="checkbox" name="uniform" '.$u_chked.'>';
								echo "<label class='control-label'> Uniform</label>";
								echo'<input type="checkbox" name="transport" '.$t_chked.'>';
								echo "<label class='control-label'> Transport</label>";
								echo'<input type="checkbox"  id="commfee"  name="common" '.$c_chked.'>';
								echo "<label class='control-label'> Common-Fees</label>";

								?>
							
								</div>
							  

								
								

						</div>
						  <div class="form-group productdetails" style="display: <?php echo $disp; ?>;">
                            <label class="control-label">Max Quantity</label>
                            <div>
                                <select name="max" id="max"  class="form-control">
                                    <?php
                                        for($i=1;$i<=10;$i++) {
                                        	if($i==$max){
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
								
								<?php
								if($res['mandatory'] == 1){
								// echo("hi");
								echo'<input type="checkbox" id="mandatory" name="mandatory" checked="checked">';
							    }
							    else{
							    echo'<input type="checkbox" id="mandatory" name="mandatory">';
							    }
								?>
								<label for ="mandatory" class="control-label">Mandatory</label>
						</div>

					
								
								
					
						<div class="form-group text-center">
							<button type="submit" value="update" name="editfeetype" class="btn btn-primary text-center">Update</button>
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
