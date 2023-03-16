<?php
require_once('admnavbar.php');
?>
<div class="container">
	<div class="row">
		<div class="col-sm-2 col-md-3 col-lg-4"></div>
		<div class="col-sm-8 col-md-6 col-lg-4">
			<div class="content content1">
				<p class="heading">Add Class Coordinator</p>
				<div class="main">
					<input name="id" type="hidden" value="<?php echo $res['id'];?>" />
					<form id="user_registered" method="post" action="adminactions.php">
						<div class="form-group">
								<label for ="name" class="control-label">Name</label>
								<input type="text" name="name" required placeholder="Class Coordinator Name" class="form-control name" title="Must contain only Alphabets,Dots and Spaces" >
						</div>						
						<div class="form-group">
								<label for ="email" class="control-label">Email</label>
								<input type="text" id="email" name="email" required placeholder="Email" class="form-control">
						</div>

						<div class="form-group">
								<label for ="stream" class="control-label">Stream</label>

								<select class="form-control strchange" name="stream" required="">
									<option value="">--SELECT-</option>
									<?php
										$streamlist = sqlgetresult("SELECT * FROM streamcheck WHERE status =  'ACTIVE' ");
										foreach ($streamlist as $value) {
									?>	
										<option value="<?php echo $value['id'] ?>"><?php echo $value['stream'];?></option>										
									<?php	
										}
									?>
								</select>
								
						</div>		
						<div class="form-group">
                            <label class="control-label" for="studStatus">Class</label>
                                 
                                <input type="hidden" name="selected_class" class="selected_quizsetids">
                                <select name="class" id="classlist"  class="quizsetid form-control" multiple="multiple" required>
                                   
                                </select>
                        </div>
						
						<div class="form-group">
								<label for ="pnum" class="control-label">Phone Number</label>
								<input type="number" id="phone" name="pnum" placeholder="Phone Number" class="form-control">
								<span id="phoneerror"></span>
						</div>
						<div class="form-group">
								<label for ="mnum" class="control-label">Mobile Number</label>
								<input type="number" id="mobile" required name="mnum" required placeholder="Mobile Number" class="form-control">
								<span id="moberror"></span>
						</div>
						
						<div class="form-group text-center">
							<button type="submit" value="new" name="addteacher" class="btn btn-primary text-center" id="ok">Add</button>
							<a href="manageteachers.php"><button type="button" value="Go Back" class="btn btn-warning text-center">Back</button></a>
					    </div>
					    
                    </form>    
				</div>
			</div>
		</div>
		<div class="col-sm-2 col-md-3 col-lg-4"></div>
	</div>
</div>
<div class="row comment">
       
</div>
<?php






include_once(BASEPATH.'footer.php');
?>
