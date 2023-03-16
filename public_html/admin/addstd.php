<?php
require_once('admnavbar.php');
?>
<div class="container">
	<div class="row">
		<div class="col-sm-2 col-md-3 col-lg-4"></div>
		<div class="col-sm-8 col-md-6 col-lg-4">
			<div class="content content1">
				<p class="heading">Add Student</p>
				<div class="main">
					<input name="id" type="hidden" value="<?php echo $res['id'];?>" />
					<form id="user_registered" method="post" action="adminactions.php">
						<div class="form-group">
								<label for ="sid" class="control-label">Student Id</label>
								<input type="text" id="sid" name="sid" required placeholder="Student Id" class="form-control">
						</div>
						<div class="form-group">
								<label for ="name" class="control-label">Student Name</label>
								<input type="text" name="name" required placeholder="Student Name" class="form-control name" title="Must contain only Alphabets,Dots and Spaces" >
						</div>
						<div class="form-group">
								<label for ="stream" class="control-label">Stream</label>
								<select class="form-control" name="stream" required="">
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
								<!-- <input type="text" id="class" name="class" required placeholder="ADMIN NAME" required value="<?php echo trim($res['class']);?>" class="form-control name" title="Must contain only Alphabets,Dots and Spaces"> -->								
						</div>
					<!-- 	<div class="form-group">
								<label for ="class" class="control-label">Class</label>
								<input type="text" id="class" name="class" required placeholder="Class" class="form-control">
						</div> -->
						<div class="form-group">
								<label for ="class" class="control-label">Class</label>
								<select class="form-control" name="class" required="">
									<option value="">--SELECT--</option>
									<?php
										$classlist = sqlgetresult("SELECT * FROM classcheck WHERE status =  'ACTIVE' ");
										foreach ($classlist as $value) {										
											if (trim($res['class']) == trim($value['id']) )
   									        	echo '<option value="'.$value['id'].'" selected>'.$value['class_list'].'</option>';
    										else
     											echo '<option value="'.$value['id'].'">'.$value['class_list'].'</option>';						
										}
									?>
								</select>
								<!-- <input type="text" id="class" name="class" required placeholder="ADMIN NAME" required value="<?php echo trim($res['class']);?>" class="form-control name" title="Must contain only Alphabets,Dots and Spaces"> -->								
						</div>
						<div class="form-group">
								<label for ="Section" class="control-label">Section</label>
								<input type="text" id="Section" name="section" required placeholder="Section" class="form-control">
						</div>

						<!-- <div class="form-group">
								<label for ="term" class="control-label">Term</label>
								<input type="text" id="term" name="term" required placeholder="Term" class="form-control">
						</div> -->
						<div class="form-group">
								<label for ="term" class="control-label">Term</label>
								<select class="form-control" name="term" required="">
									<option value="">--SELECT--</option>
									<?php
										$classlist = sqlgetresult("SELECT * FROM termdata");
										foreach ($classlist as $value) {										
											if (trim($res['term']) == trim($value['semester']) )
   									        	echo '<option value="'.$value['semester'].'" selected>'.$value['semester'].'</option>';
    										else
     											echo '<option value="'.$value['semester'].'">'.$value['semester'].'</option>';						
										}
									?>
								</select>
								<!-- <input type="text" id="class" name="class" required placeholder="ADMIN NAME" required value="<?php echo trim($res['class']);?>" class="form-control name" title="Must contain only Alphabets,Dots and Spaces"> -->								
						</div>

						<div class="form-group">
							<input type="hidden" id="oldpid" name="oldpid"  placeholder="Parent Id" class="form-control" value=0>
								<label for ="pid" class="control-label">PARENT ID</label>
								<input type="number" id="pid" name="pid" placeholder="Parent Id" class="form-control">
						</div>
					    <div class="form-group">
								<label for ="mail" class="control-label">EMAIL</label>
								<input type="email" id="mail" name="mail" required placeholder="Email Id" class="form-control">
						</div>
						<div class="form-group">
								<label for ="mnumber" class="control-label">MOBILE NUMBER</label>
								<input type="number" id="mobile" name="mobile" required placeholder="Mobile Number" class="form-control">
								<span id="moberror"></span>
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
