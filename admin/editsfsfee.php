<?php 
    require_once('admnavbar.php');
    ?>
<div class="container_fluid">
	<div class="col-sm-2 col-md-3"></div>
	<div class="col-sm-8 col-md-6">
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
		<div class="cons">
			<p class="heading">Edit SFS</p>
            <div class="col-md-12 text-right m-b-15">
                <a href="getsfsdata.php" class="btn btn-warning">SFS Report</a>
            </div>
			<div class="main">
				<form id="getsfsdata">
					<div class="form-group">
                        <label for ="studentid" class="control-label col-md-4">Student ID</label>                
                        <div class="col-md-8">
                            <input type="text" id="studentid" name="studentid" required placeholder="Student ID" class="form-control">
                        </div>						
					</div>			
                    <div style="clear:both"></div>		
					<div class="form-group text-center m-t-15">
						<button type="submit" value="new" name="getsfsdata" id="getsfsdata" class="btn btn-primary text-center">Find</button>
				    </div>
                </form>
				    
				<form method="post" id="sfsdatachange" action="adminactions.php">
					<div class="challandetails">
                        <!-- <div id="challanData"></div> -->
                        <div  class="">
        	                <div class="form-group row">
        	                	<input type="hidden" class="cnum" id="cnum" name="cnum">
                                <input type="hidden" class="studid" id="studid" name="studid">
        	                	<input type="hidden" class="term" id="term" name="term">
                                <input type="hidden" class="class" id="class" name="class">
                                <input type="hidden" class="stream" id="stream" name="stream">
        	                	<input type="hidden" class="academicyear" id="academicyear" name="academicyear">
                                <input type="hidden" name="sfsextrautilities" id="sfsutilitiesinput">
                                <input type="hidden" name="sfsextrautilitiesqty" id="sfsutilitiesinputqty"/>
                                <div class="form-group col-md-8">
                                    <label for ="snum" class="control-label col-md-3">Stu.Name: </label>          
                                    <div class="col-md-9">
                                        <span class="snum"></span>
                                    </div>                      
                                </div>      
                                <div class="form-group col-md-4">
                                    <label for ="studid" class="control-label col-md-4">Stu.ID: </label>         
                                    <div class="col-md-8">
                                        <span class="studid"></span>                                        
                                    </div>                      
                                </div>                                 
                            </div>
                            <div class="form-group row">
                                <div class="form-group col-md-8">
                                    <label for ="streamid" class="control-label col-md-4">Stream: </label>
                                    <div class="col-md-8">
                                        <span class="streamid"></span>
                                    </div>                      
                                </div>
                                <div class="form-group col-md-4">
                                    <label for ="classid" class="control-label col-md-4">Class: </label>
                                    <div class="col-md-8">
                                        <span class="classid"></span>
                                    </div>                      
                                </div>                                                                   
                            </div>
                            <div class="groupdata"></div>
                    </div>                                  
                    <div class="text-center m-b-15">
                        <button type="button" id="closepay" class="btn btn-default" name='close' value="confirm">Close</button>
                        <button type="submit" name='changesfsqty' value="confirm" class="btn btn-primary" >Submit</button>
                    </div>
                </form>
			    </div>
			</div>
		</div>
		<div class="col-sm-2 col-md-3"></div>
    </div>
</div>
<div class="row comment"></div>

<?php
    include_once(BASEPATH.'footer.php');
?>