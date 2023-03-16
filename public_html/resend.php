<?php
	include_once('header.php');
?>


<style>
	
	
	.heading {
    font-size: 22px;
    font-family: "Trebuchet MS",Arial,Helvetica,sans-serif;
    font-weight: bold;
    text-align: center;
    color: #3e85c3;
    padding-top: 5px;
    padding-right: 0px;
    padding-bottom: 10px;
    padding-left: 0px;

}
.content {
       background: #f7f7f7;
    border: 1px solid rgba(147,184,189,0.8);
    -webkit-box-shadow: 0pt 2px 5px rgba(105,108,109,0.7),0px 0px 8px 5px rgba(208,223,226,0.4) inset;
    -moz-box-shadow: 0pt 2px 5px rgba(105,108,109,0.7),0px 0px 8px 5px rgba(208,223,226,0.4) inset;
    box-shadow: 0pt 2px 5px rgba(105,108,109,0.7), inset 0px 0px 8px 5px rgba(208,223,226,0.4);
    -webkit-box-shadow: 5px;
    -moz-border-radius: 5px;
    border-radius: 5px;
    padding-top: 5px;
    padding-right: 18px;
    padding-bottom: 5px;
    padding-left: 18px;
    /*padding-top: 10px;
    padding-right: 18px;
    padding-bottom: 18px;
    padding-left: 18px;*/
    margin-top: -25px;
    margin-right: auto;
    margin-bottom: 0px;
    margin-left: auto;
    width: 35%;
    }
p.button input {
    width: 30%;
    cursor: pointer;
    background: #3e85c3;
    font-family: "Lucida Sans Unicode", "Lucida Grande", Sans-Serif;
    color: #fff;
    font-size: 16px;
    background-image: linear-gradient(to bottom, #428bca 0%, #3071a9 100%);
    background-repeat: repeat-x;
    border-color: #2d6ca2;
    }

label {
    color: #265e8e;
    position: relative;
}
label {
    display: inline-block;
    margin-bottom: 5px;
    font-family:verdana, arial, helvetica, sans-serif;
    font-weight: bold;
    font-size: 12px;
    line-height: 16px;
    margin-right: 5px;
}

input:not([type="checkbox"]) {    
    border: 1px solid #b2b2b2;
     -webkit-box-shadow: 0px 1px 4px 0px rgba(168,168,168,0.6) inset;
    -moz-box-shadow: 0px 1px 4px 0px rgba(168,168,168,0.6) inset;
    box-shadow: 0px 1px 4px 0px rgba(168,168,168,0.6) inset;
     -webkit-transition: all 0.2s linear;
    -moz-transition: all 0.2s linear;
    -o-transition: all 0.2s linear;
    transition: all 0.2s linear; 

}

p.button input {
    width: 30%;
    cursor: pointer;
    background: #3e85c3;
    font-family: verdana, arial, helvetica, sans-serif;
    color: #fff;
    background-image: linear-gradient(to bottom, #428bca 0%, #3071a9 100%);
    background-repeat: repeat-x;
    border-color: #2d6ca2;
    line-height: inherit;
    align-items: flex-start;
    text-align: center;
    user-select: none;
    white-space: pre;
    text-rendering: auto;
    letter-spacing: normal;
    word-spacing: normal;
    text-transform: none;
    text-indent: 0px;
    text-shadow: none;
    display: inline-block;
    font: 400 13.3333px Arial;
    padding-top: 10px;
    padding-right: 5px;
    padding-bottom: 10px;
    padding-left: 10px;
    margin-top: 2px;

    }

a:-webkit-any-link {
    cursor: pointer;
    color: #3c82bf;
    text-decoration: underline;
    font-size: 12px;
    }
.note {
		color: #666867; 
		font-size: 10px; 
		font-family: arial;
		text-align: center;
		line-height: 18px;"
	    display: block;

}
body {
    font-family: arial;
    font-size: 14px;
}

.onlinepay {
    font-family: "Lucida Sans Unicode", "Lucida Grande", Sans-Serif;
    font-size: 95%;
    font-weight: bold;
    color: #292E81;

}
</style>

	<div class="container">	
		<div class="row">
		<div class="col-sm-3.5">
		</div>
			<div class="col-sm-3.5 content">	
					<h2 class="heading">Resend Confirmation <br>Instruction</h2>
						<form id="resned" method="post" action="revalidation.php"> 
							<div class="form-group">
								    <label for="email">Your Email</label>
								    <input type="email" class="form-control" id="email" placeholder="mymail@mail.com">
						    </div> 	
						    <div class="form-group">
								   <h6><b>Send email to get your confirmation instruction</b></h6>
						    </div> 		
						    <div class="form-group">
								    <p class="login button" align="right"><input class="form-control" name="commit" type="submit" value="Send	" ></p>
						    </div> 
					      	
						</form>
			        	    <div>  
						    <p> <a href="#">Sign in</a></p>
						    <p> Not a member yet?<a href="#">Join Us</a></p>
						    <p><a href="resend.php">Didn't receive confirmation instructions?</a></p>
						    <p> <a href="#">Didn't receive unlock instructions?</a></p>
						    </div>
						
					  <div class="note">
							<br>To facilitate email delivery add notification@omegaschools.org to your contacts.
					  </div>
			</div>
			<div class="onlinepay">
			<p align="center">There is no online fee payment facility for new students at this time.</p>
			<p align="center">Online payment facility will be open till 30-Apr-2018.</p>
		</div>
		<div class="col-sm-5">
		</div>
		

	</div>

<?php
	include_once('footer.php');
?>