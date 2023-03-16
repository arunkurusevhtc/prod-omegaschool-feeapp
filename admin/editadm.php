<?php
require_once('admnavbar.php');
$id=$_REQUEST['id'];
// print_r($id);
// exit;
$query = "SELECT * FROM admincheck WHERE id='$id'"; 
$res = sqlgetresult($query);

?>
<div class="container passchk">
	<div class="row">
		<div class="col-sm-2 col-md-3 col-lg-4"></div>
		<div class="col-sm-8 col-md-6 col-lg-4">
			<div class="content content1">
				<p class="heading">Edit Admin</p>
				<div class="main">
					<input name="id" type="hidden" value="<?php echo $res['id'];?>" />
					<form id="user_registered" method="post" action="adminactions.php">
					    <input name="id" type="hidden" value="<?php echo $res['id'];?>" />
						<div class="form-group">
								<label for ="name" class="control-label">Admin Name</label>
								<input type="text" id="name" name="admname" required placeholder="Admin Name" required value="<?php echo trim($res['adminName']);?>" class="form-control name"  title="Must contain only Alphabets,Dots and Spaces" >
						</div>
						<div class="form-group">
								<label for ="email" class="control-label">Admin Email</label>
								<input type="text" id="email" name="admmail" required placeholder="Admin Email"required value="<?php echo trim($res['adminEmail']);?>" class="form-control">
						</div>
						<div class="form-group">
							<label for ="password" class="control-label">New Password</label>
							<!-- <input type="hidden" name="id" value="<?php echo ($_GET['id'])?>"> -->
							<input type="password" class="form-control" id="password" name="password" placeholder="Admin Password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters">
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
								<input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm Admin Password" required class="form-control">
								<span id='error' ></span>
						</div>
						<div class="form-group">
								<label for ="password_confirmation" class="control-label ">Admin Role</label>
								<select class="form-control" name="role" required="required">
                              <option value="">--Select--</option>
                              <?php
                              $roles = sqlgetresult("SELECT * FROM adminrolesscheck ORDER BY role",true);
                              foreach($roles as $role){
                              	if($res['adminRole'] == $role['id']){
                                     echo('<option value="'.$role['id'].'" selected="selected">'.$role['role'].'</option>');
                              	}else{
                              		echo('<option value="'.$role['id'].'">'.$role['role'].'</option>');
                              	}
                                
                              }
                              ?>
                            </select>
						</div>
						<div class="form-group">
								<input type="hidden" id="oldpass" name="hiddenpass" placeholder="Password" class="form-control" value="<?php echo trim($res['adminPassword']);?>">
								<span id='error' ></span>
						</div>

						<div class="form-group text-center">
							<button type="submit" value="update" name="edit" class="btn btn-primary text-center" id="ok">Update</button>
							<a href="mainpage.php"><button type="button" value="Go Back" class="btn btn-warning text-center">Back</button></a>
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
