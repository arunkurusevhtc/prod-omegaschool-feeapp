<?php
	require_once('admnavbar.php');
?>

<div class="container_fluid">
	<div class="col-sm-2 col-md-3 "></div>
	<div class="col-sm-8 col-md-6 ">
        <div class="errormessage">
        <?php
            if(isset($_SESSION['success'])) {
               echo $_SESSION['success'];
               unset($_SESSION['success']);
            } elseif(isset($_SESSION['error'])) {
               echo $_SESSION['error'];
               unset($_SESSION['error']);
            }
            ?>
        </div>
		<div class="content1">
			<p class="heading">Notifications</p>
			<div class="main">
				<form class="form-horizontal" method="post" action="adminactions.php">
					<div class="form-group">
				        <label class="control-label col-sm-4" for="pwd">Notification Type:</label>
				        <div class="col-sm-8">
				            <label class="control-label"><input type="radio" class="notifyType" name="notifyType" value="sms" checked=""> SMS</label>
                         	<label class="control-label"><input type="radio" class="notifyType"  name="notifyType" value="mail"> Mail</label>
				        </div>
				    </div>	
				    <div id="smscontent">
				    	<form class="form-horizontal" method="post" action="adminactions.php">
					    	<div class="form-group">
						        <label class="control-label col-sm-4" for="email">Send Type:</label>
						        <div class="col-sm-8">
						            <select name="sendType" id="sendType" class="form-control" onchange="chkSendType();" required="">
										<option value="">-Select-</option>
										<option value='1'>Send To All</option>
										<option value='2'>Send To unpaid</option>
										<option value='3'>Send To Paid</option>
										<option value='4'>Send To One</option>
									</select>
						        </div>
						    </div>
						    <div class="form-group studID_input">
						        <label class="control-label col-sm-4" for="pwd">StudentID:</label>
						        <div class="col-sm-8">
						            <input type="text" id="studId" name="studId" class="form-control"  />
						        </div>
						    </div>
						    <div class="form-group">
						        <label class="control-label col-sm-4" for="pwd">Message Content:</label>
						        <div class="col-sm-8">
						            <textarea id="msg_content" name="msg_content" class="form-control" rows="5" maxlength="110" ></textarea>
						        </div>
						    </div>	
						    <div class="form-group">
						        <div class="text-center">
						            <button type="submit" value="send" name="sendsms" id="sendsms" class="btn btn-primary text-center">Send SMS</button>
						        </div>
						    </div>	
						</form>
				    </div>
				    <div id="mailcontent">
				    	<form class="form-horizontal" method="post" action="adminactions.php">
						    <div class="form-group">
						        <label class="control-label col-sm-4" for="email">Send Type:</label>
						        <div class="col-sm-8">
						            <select name="msendType" id="msendType" class="form-control" onchange="chkMSendType();" required="">
										<option value="">-Select-</option>
										<option value='1'>Send To All</option>
										<option value='2'>Send To unpaid</option>
										<option value='3'>Send To Paid</option>
										<option value='4'>Send To One</option>
									</select>
						        </div>
						    </div>	
						    <div class="form-group studID_input">
						        <label class="control-label col-sm-4" for="pwd">StudentID:</label>
						        <div class="col-sm-8">
						            <input type="text" id="studId" name="studId" class="form-control"  />
						        </div>
						    </div>
						    <div class="form-group">
						        <label class="control-label col-sm-4" for="pwd">Mail Subject:</label>
						        <div class="col-sm-8">
						            <input tyep="text" id="mail_sub" name="mail_sub" class="form-control" />
						        </div>
						    </div>			    
						    <div class="form-group">
						        <label class="control-label col-sm-4" for="pwd">Mail Content:</label>
						        <div class="col-sm-8">
						            <textarea id="mail_content" name="mail_content" class="form-control" rows="5" maxlength="5000" ></textarea>
						        </div>
						    </div>		
						    <div class="form-group">
						        <div class="text-center">
						            <button type="submit" value="send" name="sendmail" id="sendmail" class="btn btn-primary text-center">Send Mail</button>
						        </div>
						    </div>	
					  	</form>		    				   
				    </div>
				</form>
					
			</div>
		</div>
	</div>
	<div class="col-sm-2 col-md-3 "></div>
</div>

</div>
	<div class="row comment">
</div>

<?php
	require_once(BASEPATH.'footer.php');
?>
