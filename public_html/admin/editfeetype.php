<?php
require_once('admnavbar.php');
$id=$_REQUEST['id'];
$query = "SELECT * FROM feetypecheck WHERE id='$id'"; 
$res = sqlgetresult($query);
$data = explode (",", $res['tax']);

?>
<div class="container">
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
								<input type="text" id="ftype" name="ftype" required placeholder="Class Name" class="form-control" value="<?php echo trim($res['feeType']);?>">
						</div>
						<div class="form-group">
								<label for ="des" class="control-label">Description</label>
								<input type="text" id="des" name="des" placeholder="Description" class="form-control" value="<?php echo trim($res['description']);?>">
						</div>
						<div class="form-group">
							<input type="hidden" name="oldgroup" id="oldgroup" value="<?php echo trim($res['feeGroup']);?>">
								<label for ="des" class="control-label">Group</label>
								<select name="group"  class="group  form-control">
									  <option value="<?php echo($res['feeGroup']);?>"><?php echo($res['feeGroup']);?></option>
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
						   	
									<div class="col-md-5 p-l-0 p-r-0">
                              <label class="control-label">Applicable For</label>  
                                   </div>

						  <div class="col-md-7 p-r-0">

						  	
								<?php
								if(trim($res['applicable']) == 'D'){
							
								echo'<input type="checkbox" id="mandtory" name="dayscholar" checked="checked">';

								echo "<label for ='mandatory' class='control-label'> Day-Scholar</label>";
                                   echo'<input type="checkbox" id="mandatory" name="hosteller">';
		           			    echo "<label for ='mandatory' class='control-label'> Hosteller</label>";

							    }

							   elseif(trim($res['applicable']) == 'H'){
					   	    echo'<input type="checkbox" id="mandatory" name="dayscholar">';
		           			    echo "<label for ='mandatory' class='control-label'> Day-Scholar</label>";
								echo'<input type="checkbox" id="mandatory" name="hosteller" checked="checked">';
								echo '<label for ="mandatory" class="control-label">Hosteller</label>';
							    }

							   elseif(trim($res['applicable']) == 'DH'){
								// echo("hi");
								echo'<input type="checkbox" id="mandatory" name="dayscholar" checked="checked">';
								
								echo "<label for ='mandatory' class='control-label'> Day-Scholar</label>";
								

								echo'<input type="checkbox" id="mandatory" name="hosteller" checked="checked">';
								echo '<label for ="mandatory" class="control-label"> Hosteller</label>';
							    }
						
			         		    else{

		           			    echo'<input type="checkbox" id="mandatory" name="dayscholar">';
                            echo '<label for ="mandatory" class="control-label">Day-Scholar</label>';
							     echo'<input type="checkbox" id="mandatory" name="hosteller">';
							     echo '<label for ="mandatory" class="control-label"> Hosteller</label>';
							    }
								?>
							
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
