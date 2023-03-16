<?php
	require_once('admnavbar.php');
?>

<div class="container">
	 <div class="row col-md-12">
         <div class="col-xs-1 col-sm-2 col-md-3 col-lg-4"></div>
         <div class="col-xs-10 col-sm-8 col-md-6 col-lg-4">  
               <?php                
                  if(isset($_SESSION['success'])) {
                     echo $_SESSION['success'];
                     unset($_SESSION['success']);
                  }
                  if(isset($_SESSION['error'])) {
                     echo $_SESSION['error'];
                     unset($_SESSION['error']);
                  }
               ?>
         </div>
         <div class="col-xs-1 col-sm-2 col-md-3 col-lg-4"></div>
   </div>  
	<div class="row">
		<div class="col-sm-2 col-md-3 col-lg-4"></div>
		<div class="col-sm-8 col-md-6 col-lg-4">
			<div class="content content1">
				<p class="heading">Edit Duedate</p>
                <div class="main">
					<form id="user_registered" method="post" action="adminactions.php">
						<div class="form-group pagetax">
							<label for ="duedate" class="control-label">Due Date</label>
							<input type="text" class="duedate datepicker form-control" name="duedate" required >
						</div>
						<div class="form-group text-center">
							<button type="submit" value="update" name="editduedate" class="btn btn-primary text-center">Update</button>
							<a href="manageproducts.php"><button type="button" value="Go Back" class="btn btn-warning text-center">Back</button></a>
					    </div>
					    
                    </form>
				</div>
			</div>
		</div>
		<div class="col-sm-2 col-md-3 col-lg-4"></div>
	</div>
</div>

<div class="row comment"></div> 

<?php
	include_once(BASEPATH.'footer.php');
?>
