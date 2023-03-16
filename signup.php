<?php
    include_once('header.php');
    if(isset($_SESSION['success_msg']) && $_SESSION['success_msg'] !='') {
        echo $_SESSION['success_msg'];
        unset($_SESSION['success_msg']);
    } elseif (isset($_SESSION['error_msg']) && $_SESSION['error_msg'] !='') {
        echo $_SESSION['error_msg'];
        unset($_SESSION['error_msg']);
    }
?>
<div class="container passchk">
    <div class="col-md-12">
        <div class="col-sm-2 col-md-3 col-lg-4"></div>
        <div class="col-sm-8 col-md-6 col-lg-4 content1">
            <h2 class="heading">SIGN UP</h2>
            <form id="user_registered" method="post" action="sql_actions.php" autocomplete="off" onsubmit="return submitUserForm();">
                <div class="form-group">
                    <label for="email">Your Email(Primary)</label>
                    <input type="email" class="form-control" id="email" name="emailprimary" autofocus required placeholder="mymail@mail.com">
                </div>

               
                <div class="form-group">
                    <label for="password">Your Password</label>
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
                        <p id="correct" class="invalid hide" >
                            <b>Correct</b>
                        </p>
                    </div>
                </div>
                <div class="form-group">
                    <label for="password_confirmation">Your Password Confirmation</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password" required>
                    <span id='error'></span>
                </div>
                <div class="form-group">
                    <label for="firstName">First Name</label>
                    <input type="text" class="form-control" id="firstName" name="firstName" pattern="[a-zA-Z]{1,}" title="Please type alphabets only" required placeholder="First Name">
                    <!-- <span id='error'></span> -->
                </div>
                <div class="form-group">
                    <label for="lastName">Last Name</label>
                    <input type="text" class="form-control" id="lastName" name="lastName" pattern="[a-zA-Z]{1,}" title="Please type alphabets only" required placeholder="Last Name">
                    <!-- <span id='error'></span> -->
                </div>
                 
                  <div class="form-group">
                    <label for="email">Email (Secondary)</label>
                    <input type="email" class="form-control" id="email" name="emailsecondary" autofocus placeholder="mymail@mail.com">
                </div>
                
                <div class="form-group">
                    <label for="mobileNumber"> Mobile Number (Primary)</label>
                    <input type="hidden" id=mobileok name="mobileok">
                    <input type="text" class="form-control" id="mobile" name="mobileNumber" required minlength="10" placeholder="Primary Mobile Number" onblur="formatPhone(this);" autocomplete="off">
                    <span id='moberror'></span>
                </div>
                <div class="form-group">
                    <label for="phoneNumber"> Mobile Number (Secondary)</label>
                    <input type="number" class="form-control" id="phone" name="phoneNumber" placeholder="Secondary Mobile Number"autocomplete="off">
                    <span id='phoneerror'></span>
                </div>
                <!-- div to show reCAPTCHA -->
                <div class="form-group g-recaptcha" data-sitekey="<?php echo $recaptch_site_key; ?>" data-callback="verifyCaptcha"></div>
                <div id="g-recaptcha-error"></div>
                <div class="form-group text-right">
                    <button class="btn btn-sm lgn" name="submit" type="submit" value="Sign up" id="ok">Sign up</button>
                </div>
            </form>
            <div>
                <p>
                    <a class="link1" href="login.php">Sign in</a>
                </p>
                <p>
                    <a class="link1" href="resendconfirm.php">Didn't receive confirmation instructions?</a>
                </p>
              
            </div>
           <div>
            <p class="note1">
                To facilitate email delivery add <a href="mailto:feeapp.support@omegaschools.org" target="_top">feeapp.support@omegaschools.org</a> to your contacts. Contact No: 044-66241110.
            </p>
          </div>
        
        
        <div class="col-sm-2 col-md-3 col-lg-4"></div>
    </div>
    <div style="clear:both;"></div>
<!--     <div class="row onlinepay">
        <p>There is no online fee payment facility for new student at this time.</p>
        <p>Online payment facility is closed.Please approach school for making fee payment.</p>
    </div> -->
    <div class="row comment">
       
    </div>
</div>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script src="<?php echo BASEURL;?>js/validate-captcha.js" type="text/javascript" async defer></script>
<?php
    include_once('footer.php');
?>