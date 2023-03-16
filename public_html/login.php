<?php
   include_once('header.php');
   ?>
<div class="col-md-12">
   <div class="col-xs-1 col-sm-2 col-md-3 col-lg-4"></div>
   <div class="col-xs-10 col-sm-8 col-md-6 col-lg-4">
      <?php
         if(isset($_SESSION['successmsg'])) {
         echo $_SESSION['successmsg'];
         unset($_SESSION['successmsg']);
             }
         
         if(isset($_SESSION['success_msg1'])) {
         echo $_SESSION['success_msg1'];
         unset($_SESSION['success_msg1']);
             }
           ?>
   </div>
   <div class="col-xs-1 col-sm-2 col-md-3 col-lg-4"></div>
</div>
<div class="container login_box">
   <div class="row">
      <div class="col-xs-1 col-sm-3 col-md-3 col-lg-4"></div>
      <div class="col-xs-10 col-sm-6 col-md-6 col-lg-4 con">
         <h2 class="login">LOGIN</h2>
         <?php
            if(isset($_SESSION['error'])) {
              echo $_SESSION['error'];
              unset($_SESSION['error']);
            }
            ?>
         <form action="sql_actions.php" method="post">
            <div class="form-group col-sm-12">
               <label for ="email" class="lab">Your Email</label>
               <input type="email" name="email" id="email" value="<?php if(isset($_COOKIE["email"])) { echo $_COOKIE["email"]; } ?>" class="form-control" placeholder="mymail@mail.com" required autofocus>
            </div>
            <div class="form-group col-md-12">
               <label for ="email" class="lab ">Your Password</label>
               <input type="password" name="password" id="pass" value="<?php if(isset($_COOKIE["password"])) { echo $_COOKIE["password"]; } ?>" class="form-control" placeholder="**********" required>
            </div>
            <div class="checkbox col-md-12 lab">
               <label><strong><input type="checkbox" name="remember_me" value="remember">Remember Me</strong></label>
            </div>
            <div class="text-right form-group">
               <button type="submit" name="login" value="signin" class="btn btn-sm sign">Sign in</button>
            </div>
         </form>
         <div class="col-md-12 form-group">
            <p class="member">Not a member Yet? <span><a class="link" href="signup.php">Join Us</a></span></p>
            <p><a class="link" href="forgotpass.php" >Forgot Password?</a></p>
            <p><a class="link" href="resendconfirm.php">Didn't receive confirmation instructions?</a></p>
         </div>
         <div>
            <p class="h form-group">To facilitate email delivery add <a href="mailto:notification@omegaschools.org" target="_top">notification@omegaschools.org</a> to your contacts.</p>
         </div>
      </div>
      <div class="col-xs-1 col-sm-3 col-md-3 col-lg-4"></div>
   </div>
   <!-- <div class="row">
      <div class="bottom">
      	<p>There is no online fee payment facility for new student at this time.</p>
      	<p>Online payment facility will be open till 30-Apr-2018.</p>		
      </div>
      </div>	 -->
   <div class="row comment">
   </div>
</div>
<?php
   include_once('footer.php');
   ?>