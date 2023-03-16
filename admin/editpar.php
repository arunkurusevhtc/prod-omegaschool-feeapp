<?php
require_once('admnavbar.php');
$id=$_REQUEST['id'];
// print_r($id);
// exit;
$query = "SELECT * FROM parentcheck WHERE id='$id'"; 
$res = sqlgetresult($query);
// print_r($res);

?>
<div class="container passchk">
	<div class="row">
		<div class="col-sm-2 col-md-3 col-lg-4"></div>
		<div class="col-sm-8 col-md-6 col-lg-4">
			<div class="content content1">
				<p class="heading">Edit Parent</p>
				<div class="main">
					<form id="user_registered" method="post" action="adminactions.php">
						<input name="id" type="hidden" value="<?php echo $res['id'];?>" />
						<div class="form-group">
								<label for ="fname" class="control-label">First Name</label>
								<input type="text" id="fname" name="fname" required placeholder="First Name" required value="<?php echo trim($res['firstName']);?>" class="form-control name" title="Must contain only Alphabets,Dots and Spaces" >
						</div>
						<div class="form-group">
								<label for ="lname" class="control-label">Last Name</label>
								<input type="text" id="lname" name="lname" required placeholder="Last Name" required value="<?php echo trim($res['lastName']);?>" class="form-control name" title="Must contain only Alphabets,Dots and Spaces">
						</div>
						<div class="form-group">
								<label for ="email" class="control-label">Email</label>
								<input type="text" id="email" name="email" required placeholder="Email" required value="<?php echo trim($res['email']);?>" class="form-control">
						</div>

						<div class="form-group">
								<label for ="email" class="control-label">Secondary Email</label>
								<input type="text" id="email" name="emailsecondary" required placeholder="Email" required value="<?php echo trim($res['secondaryEmail']);?>" class="form-control">
						</div>
						<div class="form-group">
							<label for ="password" class="control-label">New Password</label>
							<input type="hidden" name="id" value="<?php echo ($_GET['id'])?>">
							<input type="password" class="form-control" id="password" name="password" placeholder="Password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters">
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
								<input type="password" id="password_confirmation" name="password_confirmation" placeholder=" Confirm Password" required class="form-control">
								<span id='error' ></span>
						</div>
						<div class="form-group">
								<input type="hidden" id="oldpass" name="hiddenpass" placeholder="Password" class="form-control" value="<?php echo trim($res['password']);?>">
								<span id='error' ></span>
						</div>
						<div class="form-group">
								<label for ="pnum" class="control-label">Mobile Number(Primary)</label>
								<input type="number" id="phone" name="pnum" placeholder="Phone Number"required value="<?php echo trim($res['mobileNumber']);?>" class="form-control">
								<span id="phoneerror"></span>

						</div>
						<div class="form-group">
								<label for ="mnum" class="control-label">Mobile Number(Secondary)</label>
								<input type="number" id="mobile" name="mnum" required placeholder="Mobile Number"required value="<?php echo trim($res['secondaryNumber']);?>" class="form-control" >
							    <span id="moberror"></span>

						</div>
						<div class="form-group text-center">
							<button type="submit" value="update" name="editpar" class="btn btn-primary text-center" id="ok">Update</button>
							<a href="managepar.php"><button type="button" value="Go Back" class="btn btn-warning text-center">Back</button></a>
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
