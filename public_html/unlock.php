<?php
	include_once('header.php');
?>
<div class="container">
	<div class="row">
		<div class="col-sm-2 col-md-3 col-lg-4"></div>
		<div class="col-sm-8 col-md-6 col-lg-4">
			<div class="content content1">
				<p class="heading">Resend Unlock Instructions</p>
				<div class="main">
						<?php
	                		// print_r($_SESSION)
							if( isset($_SESSION['errormsg2']))
							{
							echo ($_SESSION['errormsg2']);
							unset($_SESSION['errormsg2']);
							}
							?>
		            <form id ="unlock" action="sql_actions.php" method="POST">
						<p><label for="mail" class="lab">Your Email</label></p>
			            <p><input type="email" autofocus="autofocus" class="form-control" id="mail" name="email" placeholder="mymail@mail.com" required></p>
			            <p><strong>Send email to get reset password instructions</strong></p>
			            <div class="buttonalign"><button class="btn pull-right buttoncolor" name="unlock" type="submit" value="UNLOCK">Send</button></div>
		            </form>
		            <div style="clear:both">&nbsp;</div>
		            <div>
						<p><a href="login.php">Sign in</a></p>
						<p>Not a member yet? <a href="signup.php">Join Us</a></p>
						<p><a href="forgotpass.php">Forgot your password?</a></p>
						<p><a href="resendconfirm.php">Didn't receive any confirmation information?</a></p>
					</div>
	            </div>
				<div class="note">To facilitate email delivery add notification@omegaschools.org to your contacts.</div>
			</div>
		</div>
		<div class="col-sm-2 col-md-3 col-lg-4"></div>
	</div>
   <!--  <div class="row bottom">
			<p>There is no online fees payment facility for new students at this time.</p>
			<p>Online payment facility will be open till 30-Apr-2018.</p>
	</div> -->
	<div class="row comment">
       
    </div>
</div>
<?php
	include_once('footer.php');
?>