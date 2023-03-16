<?php
require_once('admnavbar.php');
?>
<div class="container">
	<div class="row">
		<div class="col-sm-2 col-md-3 col-lg-4"></div>
		<div class="col-sm-8 col-md-6 col-lg-4">
			<div class="content content1">
				<p class="heading">Add Fee Group</p>
				<div class="main">
					<input name="id" type="hidden" value="<?php echo $res['id'];?>" />
					<form id="user_registered" method="post" action="adminactions.php">
						<div class="form-group">
								<label for ="fname" class="control-label">Product Name</label>
								<input type="text" id="pname" name="pname" required placeholder="Product Name" class="form-control">
						</div>
						<div class="form-group">
								<label for ="fdes" class="control-label">Acc No</label>
								<input type="text" id="acc_no" name="acc_no" placeholder="Account Number" class="form-control" required="">
						</div>						
						<div class="form-group text-center">
							<button type="submit" value="new" name="addproduct" class="btn btn-primary text-center">Add</button>
							<a href="manageproducts.php"><button type="button" value="Go Back" class="btn btn-warning text-center">Back</button></a>
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
