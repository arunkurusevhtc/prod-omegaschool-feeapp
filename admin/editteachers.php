<?php
require_once('admnavbar.php');
$id=$_REQUEST['id'];
// print_r($id);
// exit;
$query = "SELECT * FROM teacherchk WHERE id='$id'"; 
$res = sqlgetresult($query);
// print_r($res['class']);
$selectedclass = explode(",",$res['class']);
$selectedclass=array_map('trim',$selectedclass);
// print_r($selectedclass);
?>
<div class="container passchk edittech">
	<div class="row">
		<div class="col-sm-2 col-md-3 col-lg-4"></div>
		<div class="col-sm-8 col-md-6 col-lg-4">
			<div class="content content1">
				<p class="heading">Edit Class Coordinator</p>
				<div class="main">
					<form id="user_registered" method="post" action="adminactions.php">
						<input name="id" type="hidden" value="<?php echo $res['id'];?>" />
						<div class="form-group">
								<label for ="fname" class="control-label">Name</label>
								<input type="text" id="name" name="name" required placeholder="Class Coordinator Name" required value="<?php echo trim($res['name']);?>" class="form-control name" title="Must contain only Alphabets,Dots and Spaces" >
						</div>						
						<div class="form-group">
								<label for ="email" class="control-label">Email</label>
								<input type="text" id="email" name="email" required placeholder="Email"required value="<?php echo trim($res['email']);?>" class="form-control">
					</div>

						<div class="form-group">
								<label for ="lname" class="control-label">Stream</label>
								<select class="form-control strchange" name="stream" required="">
									<option value="">--SELECT--</option>
									<?php
										$streamlist = sqlgetresult("SELECT * FROM streamcheck WHERE status =  'ACTIVE' ");
										foreach ($streamlist as $value) {										
											if (trim($res['stream']) == trim($value['id']) )
   									        	echo '<option value="'.$value['id'].'" selected>'.$value['stream'].'</option>';
    										else
     											echo '<option value="'.$value['id'].'">'.$value['stream'].'</option>';						
										}
									?>
								</select>
														
						</div>
						<div class="form-group">
                            <label class="control-label" for="studStatus">Class</label>
                                 <?php
                                    $classtypes = sqlgetresult('SELECT c."displayOrder",c.id,c.class_list FROM tbl_class c LEFT JOIN tbl_student s ON c.id = s.class::int WHERE s.stream = \''.$res['stream'].'\' AND c.status =  \''. 1 .'\' GROUP BY c.id ORDER BY c."displayOrder" ',true);
                                ?>
                                <input type="hidden" name="selected_class" class="selected_quizsetids">
                                <select name="class_list" id="classlist"  class="quizsetid form-control" multiple="multiple" >
                                    <?php
                                            foreach($classtypes as $clas) {
                                            	
                                            	if(in_array(trim($clas['id']), $selectedclass)){

                                                    echo '<option value="'.$clas['id'].'" selected>'.$clas['class_list'].'</option>';
                                                  }
                                                  else{
                                                    echo '<option value="'.$clas['id'].'">'.$clas['class_list'].'</option>';
                                                  }
                                            }
                                    ?> 
                                </select>
                        </div>
						
						<div class="form-group">
							<label for ="password" class="control-label">New Password</label>
							<input type="hidden" name="pass_old" value="<?php echo $res['password'];?>">
							<input type="password" class="form-control" id="password" name="password" placeholder="New  Password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters">
		                    <!-- <span id='error'></span> -->
		                    <div id="message">
		                        <h5 style="color:blue;">
                                    <b>Password must contain the following:</b>
                                </h5>
		                        <p id="letter" class="invalid">A
		                            <b>lowercase</b> letter
		                        </p>
		                        <p id="capital" class="invalid">A
		                            <b>capital (uppercase)</b> letter
		                        </p>
		                        <p id="number" class="invalid">A
		                            <b>number</b>
		                        </p>
		                        <p id="length" class="invalid">Minimum
		                            <b>8 characters</b>
		                        </p>
		                        <p id="correct" class="invalid hide">
		                            <b>Correct</b>
		                        </p>
		                    </div>
						</div>
						<!--  -->
						<div class="form-group">
								<label for ="password_confirmation" class="control-label ">Confirm New Password</label>
								<input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm  Password" class="form-control">
								<span id='error' ></span>
						</div>
						<div class="form-group">
								<label for ="pnum" class="control-label">Phone Number</label>
								<input type="number" id="phone" name="pnum" placeholder="Phone Number"required value="<?php echo trim($res['phoneNumber']);?>" class="form-control">
								<span id="phoneerror"></span>
						</div>
						<div class="form-group">
								<label for ="mnum" class="control-label">Mobile Number</label>
								<input type="number" id="mobile" name="mnum" required placeholder="Mobile Number"required value="<?php echo trim($res['mobileNumber']);?>" class="form-control" onkeyup="check();">
							    <span id="moberror"></span>

						</div>
						<div class="form-group text-center">
							<button type="submit" value="update" name="editteacher" class="btn btn-primary text-center" id="ok">Update</button>
							<a href="manageteachers.php"><button type="button" value="Go Back" class="btn btn-warning text-center">Back</button></a>
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
