<?php
require_once('admnavbar.php');
$id=$_REQUEST['id'];
// print_r($id);
// exit;
$query = "SELECT * FROM studentcheck WHERE id='$id'"; 
$res = sqlgetresult($query);
// print_r($res);

?>
<div class="container">
	<div class="row">
		<div class="col-sm-2 col-md-3 col-lg-4"></div>
		<div class="col-sm-8 col-md-6 col-lg-4">
			<div class="content content1">
				<p class="heading">Edit Student</p>
                <div class="main">
					<form id="user_registered" method="post" action="adminactions.php">
						<input name="id" type="hidden" value="<?php echo $res['id'];?>" />
						<div class="form-group">
								<label for ="sid" class="control-label">Student Id</label>
								<input type="text" id="sid" name="sid" required placeholder="Student Id" class="form-control" value="<?php echo $res['studentId']; ?>">
								<input type="hidden" id="sid_current" name="sid_current" placeholder="Student Id" class="form-control" value="<?php echo $res['studentId']; ?>">
								<input type="hidden" id="sid_old" name="sid_old" placeholder="Student Old Id" class="form-control" value="<?php echo $res['old_studentId']; ?>">
						</div>
						<div class="form-group">
								<label for ="name" class="control-label">Student Name</label>
								<input type="text" name="name" required placeholder="Student Name" class="form-control name" title="Must contain only Alphabets,Dots and Spaces" value="<?php echo trim($res['studentName']);?>">
						</div>
						<div class="form-group">
							<label for ="gender" class="control-label">Gender</label>
							<select class="form-control" name="gender" required="">
								<option value="">--SELECT--</option>
								<?php
									foreach ($gender as $k=>$val) {										
										if (trim($res['gender']) == trim($k) )
									        	echo '<option value="'.$k.'" selected>'.$val.'</option>';
										else
 											echo '<option value="'.$k.'">'.$val.'</option>';			
									}
								?>
							</select>
						</div>
						<div class="form-group">
								<label for ="stream" class="control-label ">Stream</label>
								<select class="form-control streamchange streamselect" name="stream" required="">
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
								<select class="form-control classselect classchange" name="class" required="">
									<option value="">--SELECT--</option>
									<?php
										$classlist = sqlgetresult('SELECT c."displayOrder",c.id,c.class_list FROM tbl_class c LEFT JOIN tbl_student s ON c.id = s.class::int WHERE s.stream = \''.$res['stream'].'\' AND c.status =  \''. 1 .'\' GROUP BY c.id ORDER BY c."displayOrder" ',true);

										foreach ($classlist as $value) {										
											if (trim($res['class']) == trim($value['id']) )
   									        	echo '<option value="'.$value['id'].'" selected>'.$value['class_list'].'</option>';
    										else
     											echo '<option value="'.$value['id'].'">'.$value['class_list'].'</option>';						
										}
									?>
								</select>
														
						</div>
						<div class="form-group">
								<label for ="Section" class="control-label">Section</label>
								<select class="form-control sectionselect" name="section" required="">
									<option value="">--SELECT--</option>
									<?php
										$section = sqlgetresult('SELECT "section" FROM tbl_student WHERE "stream" = \'' . $res['stream'] . '\' AND "class" = \'' . $res['class'] . '\' GROUP BY "section" ORDER BY "section" ASC',true);
										foreach ($section as $value) {										
											if (trim($res['section']) == trim($value['section']))
   									        	echo '<option value="'.$value['section'].'" selected>'.$value['section'].'</option>';
    										else
     											echo '<option value="'.$value['section'].'">'.$value['section'].'</option>';						
										}
									?>
								</select>
						</div>

						<div class="form-group">
								<label for ="term" class="control-label">Term</label>
								<select class="form-control" name="term" required="">
									<option value="">--SELECT--</option>
									<?php
										$classlist = sqlgetresult("SELECT * FROM semestercheck", true);
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


				                <input type="hidden" id="oldpid" name="oldpid"  placeholder="Parent Id" class="form-control" value="<?php echo trim($res['parentId']);?>">

								<label for ="pid" class="control-label">Parent Id</label>
								<input type="number" id="pid" name="pid"  placeholder="Parent Id" class="form-control" value="<?php echo trim($res['parentId']);?>">
						</div>
						
					    <div class="form-group">
								<label for ="mail" class="control-label">Email</label>
								<input type="email" id="mail" name="mail" required placeholder="Email Id" class="form-control" value="<?php echo trim($res['email']);?>">
						</div>
						<div class="form-group">
								<label for ="mnumber" class="control-label">Mobile Number</label>
								<input type="number" id="mobile" name="mobile" required placeholder="Mobile Number" class="form-control" value="<?php echo trim($res['mobileNumber']);?>">
								<span id="moberror"></span>
						</div>
						<div class="form-group">
				                <input type="hidden" id="oldstg" name="oldstg"  placeholder="Transport Stage" class="form-control" value="<?php echo trim($res['transport_stg']);?>">

								<label for ="transtg" class="control-label">Transport Stage</label>
								<input type="number" id="transtg" name="transtg"  placeholder="Transport Stage" class="form-control" required value="<?php echo trim($res['transport_stg']);?>">
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
						<div class="form-group">
								<label for ="acadyear" class="control-label">Academic Year</label>
								<select class="form-control" name="acadyear" id="acadyear" required="">
									<option value="">--SELECT--</option>
									<?php
										$classlist = sqlgetresult("SELECT * FROM yearcheck", true);
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
							<button type="submit" value="update" name="editstd" class="btn btn-primary text-center" id="ok">Update</button>
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
