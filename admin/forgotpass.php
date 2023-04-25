<?php
	include_once('../header.php');
	// session_start();
?>
<div class="container">
	<div class="row">
		<div class="col-sm-2 col-md-3 col-lg-4"></div>
		<div class="col-sm-8 col-md-6 col-lg-4">
			<div class="content content1">
				<p class="heading">Forgot Password</p>
				<div class="main">
						<?php
	                		// print_r($_SESSION)
							if( isset($_SESSION['errormsg']))
							{
							echo ($_SESSION['errormsg']);
							unset($_SESSION['errormsg']);
							}
							?>
					<form id ="forgot" action="adminactions.php" method="POST" onsubmit="return submitUserForm();">
						<p><label for="mail" class="lab">Your Email</label></p>
			            <p><input type="email" autofocus="autofocus" class="form-control" id="mail" name="email" placeholder="mymail@mail.com" required></p>
			            <p><strong>Send email to get reset password instructions</strong></p>
			            <div class="form-group g-recaptcha" data-sitekey="<?php echo $recaptch_site_key; ?>" data-callback="verifyCaptcha"></div>
                        <div id="g-recaptcha-error"></div>
			            <div class="buttonalign"><button class="btn pull-right buttoncolor" name="forgot" type="submit" value="FORGOT PASSWORD">Send</button></div>
		            </form>
		            <div style="clear:both">&nbsp;</div>
		            <div class="form-group">
						<p><a href="login.php">Sign In</a></p>
						
					</div>
	            </div>
	            <div>
	        </div>
				
			</div>

		</div>
		<div class="col-sm-2 col-md-3 col-lg-4"></div>
	</div>
    <!-- <div class="row bottom">
			<p>There is no online fees payment facility for new students at this time.</p>
			<p>Online payment facility will be open till 30-Apr-2018.</p>
	</div> -->
	<div class="row comment">
       
    </div>
</div>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script src="<?php echo BASEURL;?>js/validate-captcha.js" type="text/javascript" async defer></script>
<?php
	include_once('footer.php');
?>