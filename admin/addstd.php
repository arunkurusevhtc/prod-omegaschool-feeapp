<?php
require_once('admnavbar.php');
// print_r($res);
?>
<div class="container">
	<div class="row">
		<div class="errordivforaddstd col-sm-12"></div>
		<div class="col-sm-2 col-md-3 col-lg-4"></div>
		<div class="col-sm-8 col-md-6 col-lg-4">
			<div class="content content1">
				<p class="heading">Add Student</p>
				<div class="main">
					<input name="id" type="hidden" value="<?php echo $res['id'];?>" />
					<form id="user_registered" method="post" action="adminactions.php">
						<div class="form-group">
								<label for ="sid" class="control-label">Student Id</label>
								<input type="text" id="sid" name="sid" required placeholder="Student Id" class="form-control addstdapplid">
						</div>
						<div class="form-group">
								<label for ="name" class="control-label">Student Name</label>
								<input type="text" name="name" required placeholder="Student Name" class="form-control name" title="Must contain only Alphabets,Dots and Spaces" >
						</div>
						<div class="form-group">
							<label for ="gender" class="control-label">Gender</label>
							<select class="form-control" name="gender" required="">
								<option value="">--SELECT--</option>
								<?php
									foreach ($gender as $k=>$val) {										
										echo '<option value="'.$k.'">'.$val.'</option>';										
									}
								?>
							</select>
						</div>
						<div class="form-group">
								<label for ="stream" class="control-label">Stream</label>
								<select class="form-control streamchangeforstud streamselect" name="stream" required="">
									<option value="">--SELECT--</option>
									<?php
										$classlist = sqlgetresult("SELECT * FROM streamcheck");
										foreach ($classlist as $value) {										
											if (trim($res['stream']) == trim($value['id']) )
   									        	echo '<option value="'.$value['id'].'" selected>'.$value['stream'].'</option>';
    										else
     											echo '<option value="'.$value['id'].'">'.$value['stream'].'</option>';						
										}
									?>
								</select>
																
						</div>
					
						<div class="form-group">
								<label for ="class" class="control-label">Class</label>
								<select class="form-control classselectstud classchange" name="class" required="">
								<option value="">--SELECT--</option>
								</select>
															
						</div>
						<div class="form-group">
								<label for ="Section" class="control-label">Section</label>
								<select class="form-control sectionselect" name="section" required="">
									<option value="">--SELECT--</option>
									
								</select>
						</div>

						<div class="form-group">
								<label for ="term" class="control-label">Term</label>
								<select class="form-control" name="term" required="">
									<option value="">--SELECT--</option>
									<?php
										$classlist = sqlgetresult("SELECT * FROM semestercheck WHERE status = 'ACTIVE' ", true);
										print_r($classlist);
										foreach ($classlist as $value) {										
											if (trim($res['term']) == trim($value['semester']) )
   									        	echo '<option value="'.$value['semester'].'" selected>'.$value['semester'].'</option>';
    										else
     											echo '<option value="'.$value['semester'].'">'.$value['semester'].'</option>';						
										}
									?>
								</select>
													
						</div>

						<div class="form-group">
							<input type="hidden" id="oldpid" name="oldpid"  placeholder="Parent Id" class="form-control" value=0>
								<label for ="pid" class="control-label">PARENT ID</label>
								<input type="number" id="pid" name="pid" placeholder="Parent Id" class="form-control">
						</div>
						
					    <div class="form-group">
								<label for ="mail" class="control-label">Email</label>
								<input type="email" id="mail" name="mail" required placeholder="Email Id" class="form-control">
						</div>
						<div class="form-group">
								<label for ="mnumber" class="control-label">Mobile Number</label>
								<input type="number" id="mobile" name="mobile" required placeholder="Mobile Number" class="form-control">
								<span id="moberror"></span>
						</div>
						<div class="form-group">
								<label for ="transtg" class="control-label">Transport Stage</label>
								<input type="number" id="transtg" name="transtg"  placeholder="Transport Stage" class="form-control" required>
						</div>
						<div class="form-group">
								<label for ="class" class="control-label">Hostel Need</label>
								<select class="form-control" name="hostelneed" id="hostelneed" required="">
									<option value="">--SELECT--</option>
									<?php
										
                                        $hostelneed = array('Y','N');
										foreach ($hostelneed as $value) {										
											if (trim($res['hostel_need']) == trim($value) )
   									        	echo '<option value="'.$value.'" selected>'.$value.'</option>';
    										else
     											echo '<option value="'.$value.'">'.$value.'</option>';						
										}
									?>
								</select>
														
						</div>
						<div class="form-group">
								<label for ="class" class="control-label">Lunch Need</label>
								<select class="form-control" name="lunchneed" required="">
									<option value="">--SELECT--</option>
									<?php
										
                                        $lunchneed = array('Y','N');
										foreach ($lunchneed as $value) {										
											if (trim($res['lunch_need']) == trim($value) )
   									        	echo '<option value="'.$value.'" selected>'.$value.'</option>';
    										else
     											echo '<option value="'.$value.'">'.$value.'</option>';						
										}
									?>
								</select>
														
						</div>
						<!-- <div class="form-group">

								<label for ="acadyear" class="control-label">Academic Year</label>
								<input type="text" id="acadyear" name="acadyear"  placeholder="Academic Year" class="form-control" required>
						</div> -->
						<div class="form-group">
								<label for ="acadyear" class="control-label">Academic Year</label>
								<select class="form-control" name="acadyear" id="acadyear" required="">
									<option value="">--SELECT--</option>
									<?php
										$classlist = sqlgetresult("SELECT * FROM yearcheck WHERE active = 1 ", true);
										print_r($classlist);
										foreach ($classlist as $value) {										
											if (trim($res['academic_yr']) == trim($value['id']) )
   									        	echo '<option value="'.$value['id'].'" selected>'.$value['year'].'</option>';
    										else
     											echo '<option value="'.$value['id'].'">'.$value['year'].'</option>';						
										}
									?>
								</select>
													
						</div>
						<div class="form-group text-center">
							<button type="submit" value="new" name="addstd" class="btn btn-primary text-center" id="ok">Add</button>
							<a href="managestd.php"><button type="button" value="Go Back" class="btn btn-warning text-center">Back</button></a>
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
