<?php
require_once('admnavbar.php');
?>
<div class="container">
	<div class="row">
		<div class="col-sm-2 col-md-3 col-lg-4"></div>
		<div class="col-sm-8 col-md-6 col-lg-4">
			<div class="content content1">
				<p class="heading">Add Admin Role</p>
				<div class="main">
					<form id="user_registered" method="post" action="adminactions.php">
						<div class="form-group">
								<label for ="name" class="control-label">Role</label>
								<input type="text" id="role" name="role" required placeholder="Admin Role" class="form-control">
						</div>
						<div class="form-group">
								<label for ="des" class="control-label">Description</label>
								<input type="text" id="des" name="des" placeholder="Description" class="form-control">
						</div>
						
						<div class="form-group text-center">
							<button type="submit" value="new" name="addrole" class="btn btn-primary text-center">Add</button>
							<a href="manageroles.php"><button type="button" value="Go Back" class="btn btn-warning text-center">Back</button></a>
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
