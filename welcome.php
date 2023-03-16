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
<div class="container-fluid">
    <div class="col-md-12">
        <div class="alert-msg">
            <span>A message with a confirmation link has been sent to your email address.please open the link to activate your account</span>
            
        </div>
            <hr>
            <form id="sign-in" method="post" action="login.php">
                
                <div class="welcome">
                    <button class="btn btn-primary" name="submit" type="submit" value="Signin">Sign in</button>
                </div>
            </form>
    </div>
    </div> 

    <!-- <div class="row onlinepay">
        <p>There is no online fee payment facility for new student at this time.</p>
        <p>Online payment facility is closed.Please approach school for making fee payment.</p>
    </div> -->
    <div class="row comment">
       
    </div>

<?php
    include_once('footer.php');
?>