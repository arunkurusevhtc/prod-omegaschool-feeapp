<?php
	include_once('../header.php');
?>
	       
<div class="container login_box passchk">  
	<div class="row">
		<div class="col-xs-1 col-sm-3 col-md-3 col-lg-4"></div>
		<div class="col-xs-10 col-sm-6 col-md-6 col-lg-4 con">
			<h2 class="login">Change Password</h2>

                <?php
		          if(isset($_SESSION['error_msg2'])) {
		            echo $_SESSION['error_msg2'];
		            unset($_SESSION['error_msg2']);
		          }
		        ?>
			<form id="user_registered" method="post" action="adminactions.php" onsubmit="return submitUserForm();">
				<div class="form-group col-sm-12">
					<label for ="email" class="lab">New Password</label>
					<!-- <input type="hidden" name="email" value="<?php echo ($_SESSION['myadmin']['adminemail'])?>"> -->
					<?php
					if(isset($_GET['k'])){
					echo ('<input type="hidden" name="email" value="'. $_GET['k'] .'">');
				    }
				    ?>
					<input type="password" class="form-control" id="password" name="password" placeholder="New Password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" required>
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
				<div class="form-group col-md-12">
					<label for ="email" class="lab ">Confirm New Password</label>
					<input type="password" class="form-control"  id="password_confirmation" name="password_confirmation" placeholder="Confirm New Password" required>
                    <span id='error'></span>
				</div>
				<div class="form-group col-md-12">
					<div class="form-group g-recaptcha" data-sitekey="<?php echo $recaptch_site_key; ?>" data-callback="verifyCaptcha"></div>
	                <div id="g-recaptcha-error"></div>
	            </div>
				<div class="text-right form-group">
					<button type="submit" name="changepassword" value="change" id="ok" class="btn btn-sm sign">Change</button>
	  			</div>
            </form>
			<!-- <div class="col-md-12 form-group">
 		       <p><a class="link" href="login.php" >Sign in</a></p>
		       <p class="member">Not a member Yet? <span><a class="link" href="signup.php">Join Us</a></span></p>
		       <p><a class="link" href="resendconfirm.php">Didn't receive confirmation instructions?</a></p>
		       <p><a class="link" href="unlock.php">Didn't receive Unlock instructions?</a></p>
	        </div> -->

<!-- 	        <div>
       		<p class="h form-group">To facilitate email delivery add notification@omegaschools.org to your contacts.</p>
       		</div> -->
		</div>

          <div class="col-xs-1 col-sm-3 col-md-3 col-lg-4"></div>

	</div>
	<!-- <div class="row">
		<div class="bottom down">
			<p>There is no online fee payment facility for new student at this time.</p>
			<p>Online payment facility will be open till 30-Apr-2018.</p>		
		</div>
	</div> -->	
	<div class="row comment">
       
    </div>
</div>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script src="<?php echo BASEURL;?>js/validate-captcha.js" type="text/javascript" async defer></script>
<?php

include_once(BASEPATH.'footer.php');
?>
 