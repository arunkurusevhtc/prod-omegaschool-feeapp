<?php
require_once('admnavbar.php');
?>
<div class="container passchk">
	<div class="row">
		<div class="col-sm-2 col-md-3 col-lg-4"></div>
		<div class="col-sm-8 col-md-6 col-lg-4">
			<div class="content content1">
				<p class="heading">Add Parent</p>
				<div class="main">
					<input name="id" type="hidden" value="<?php echo $res['id'];?>" />
					<form id="user_registered" method="post" action="adminactions.php">
						<div class="form-group">
								<label for ="name" class="control-label">First Name</label>
								<input type="text" name="fname" required placeholder="First Name" class="form-control name" title="Must contain only Alphabets,Dots and Spaces" >
						</div>
						<div class="form-group">
								<label for ="name" class="control-label">Last Name</label>
								<input type="text" name="lname" required placeholder="Last Name" class="form-control name" title="Must contain only Alphabets,Dots and Spaces">
						</div>
						<div class="form-group">
								<label for ="email" class="control-label">Email</label>
								<input type="text" id="email" name="email" required placeholder="Email" class="form-control">
						</div>

						<div class="form-group">
								<label for ="email" class="control-label">Secondary Email</label>
								<input type="text" id="email" name="secondaryemail" required placeholder="Email" class="form-control">
						</div>
						<div class="form-group">
							<label for ="password" class="control-label">New Password</label>
							<input type="hidden" name="id" value="<?php echo ($_GET['id'])?>">
							<input type="password" class="form-control" id="password" name="password" placeholder="Password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" required>
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
								<input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password" required class="form-control">
								<span id='error' ></span>
						</div>
						<div class="form-group">
								<label for ="pnum" class="control-label">Mobile Number(Primary)</label>
								<input type="number" id="phone" name="pnum" placeholder="Phone Number" class="form-control">
								<span id="phoneerror"></span>
						</div>
						<div class="form-group">
								<label for ="mnum" class="control-label">Mobile Number(Secondary)</label>
								<input type="number" id="mobile" required name="mnum" required placeholder="Mobile Number" class="form-control">
								<span id="moberror"></span>
						</div>
						<div class="form-group text-center">
							<button type="submit" value="new" name="addpar" class="btn btn-primary text-center" id="ok">Add</button>
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
