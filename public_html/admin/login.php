<?php
	include_once('../header.php');

?>

<div class="container">
	<div class="row">
		<div class="col-xs-2 col-sm-2 col-md-3 col-lg-4"></div>
		<div class="col-xs-8 col-sm-8 col-md-6 col-lg-4 consadmin">
			
			<h2 class="login">LOGIN</h2>
			

        <?php
	            if(isset($_SESSION['error'])) {
	            echo $_SESSION['error'];
	            unset($_SESSION['error']);
		          }

                if(isset($_SESSION['success_msg2'])) {
	            echo $_SESSION['success_msg2'];
	            unset($_SESSION['success_msg2']);

	             }

	             	
        ?>
			<form action="adminactions.php" method="post">	
				<div class="form-group col-sm-12">
					<label for ="email" class="lab">Your Email</label>
					<input type="email" name="email" id="email" value="<?php if(isset($_COOKIE["email"])) { echo $_COOKIE["email"]; } ?>" class="form-control" placeholder="mymail@mail.com" required autofocus>
				</div>
				<div class="col-md-12">
					<label for ="email" class="lab ">Your Password</label>
					<input type="password" name="password" id="pass" value="<?php if(isset($_COOKIE["password"])) { echo $_COOKIE["password"]; } ?>" class="form-control" placeholder="**********" required>
				</div>
				<div class="checkbox form-group col-md-12 lab">
					<div class="col-md-6">
						<label><strong><input type="checkbox" name="remember_me" value="remember">Remember Me</strong></label>
					</div>					
					<div class="col-md-6 text-right">
						<input type="checkbox" class="pull-right" name="tec_login">Coordinator's Login
					</div>
				</div>
				<div class="text-right col-md-12 form-group">
					<button type="submit" name="login" value="signin" class="btn btn-sm signcss">Sign in</button>
	  			</div>
            </form>
           </div>
        <div class="col-xs-2 col-sm-2 col-md-3 col-lg-4"></div>
    
    </div>
</div>
<div class="row comment">
       
</div>
<?php
	





include_once(BASEPATH.'footer.php');
?>