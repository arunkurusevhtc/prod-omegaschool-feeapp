<?php
	include_once('header.php');
?>

<div class="row col-md-12">
		<div class="col-xs-1 col-sm-2 col-md-2 col-lg-3"></div>
		<div class="col-xs-10 col-sm-8 col-md-8 col-lg-6">

           <?php
	          if(isset($_SESSION['success'])) {
	            echo $_SESSION['success'];
	            unset($_SESSION['success']);
	                }
	              ?>           
		</div>
		<div class="col-xs-1 col-sm-2 col-md-2 col-lg-3"></div>
     </div>	

<div class="container resend_box">  
	<div class="row">
		<div class="col-xs-1 col-sm-3 col-lg-4"></div>
		<div class="col-xs-10 col-sm-6 col-lg-4 cons">
			<h2 class="rescss">Resend Confirmation</h2>
			<h4 class="rescss">Instructions</h4>
			       <?php
          if(isset($_SESSION['error'])) {
            echo $_SESSION['error'];
            unset($_SESSION['error']);
                }
              ?>
			<form action="sql_actions.php" method="post" id= "myform">	
				<div>
					<label for ="email" class="sendlab">Your Email</label>
					<input type="email" name="email"  class="form-control form-group" placeholder="mymail@mail.com" required autofocus>
				</div>
				<p class="sendnew"><strong>Send email to get your Confirmation Instructions</strong></p>

			    <div class="text-right form-group">
					<button type="submit" name="resendconfirmation" value="confirmation" class="btn btn-md btsend">Send</button>
				</div>
			</form>

			 <div class=" form-group">

				<p><a class="blink" href="login.php">Sign in</a></p>
				<p class="remsend"><span>Not a member Yet?</span> <a class="blink" href="signup.php">Join Us</a></p>
		       	<p><a class="blink" href="forgotpass.php" >Forgot Password?</a></p>
		         

		     <div>
       			<p class="dwn">To facilitate email delivery add <a href= "">notification&copy;omegaschools.org</a> to your contacts.<p>
       		</div>
       </div>
       <div class="col-xs-1 col-sm-3  col-lg-4"></div>
	</div>
	   <div class="row">
		<!-- <div class="bottom text-center">
			<p>There is no online fee payment facility for new student at this time.</p>
			<p>Online payment facility will be open till 30-Apr-2018.</p>		
		</div> -->
		<div class="row comment">
       
        </div>
	</div>	
</div>	

















<?php
	include_once('footer.php');
?>



















<!-- 


<?php
	// include_once('footer.php');
?>
 -->