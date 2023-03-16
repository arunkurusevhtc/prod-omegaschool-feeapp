<?php
require_once('admnavbar.php');
?>
<div class="container">
	<div class="row">
		<div class="col-sm-2 col-md-3 col-lg-4"></div>
		<div class="col-sm-8 col-md-6 col-lg-4">
			<div class="content content1">
				<p class="heading">Add Stream</p>
				<div class="main">
					<input name="id" type="hidden" value="<?php echo $res['id'];?>" />
					<form id="user_registered" method="post" action="adminactions.php">
						<div class="form-group">
								<label for ="name" class="control-label">Stream Name</label>
								<input type="text" id="cname" name="cname" required placeholder="Stream Name" class="form-control">
						</div>
						<div class="form-group">
								<label for ="des" class="control-label">Description</label>
								<input type="text" id="des" name="des" placeholder="Description" class="form-control">
						</div>
						<div class="form-group">
								<label for ="des" class="control-label">Partial Minimum Amount</label>
								<input type="number" maxlength="2" id="min" name="min" placeholder="Partial Minimum Amount" class="form-control">
						</div>
						<div class="form-group text-center">
							<button type="submit" value="new" name="addstream" class="btn btn-primary text-center">Add</button>
							<a href="managestream.php"><button type="button" value="Go Back" class="btn btn-warning text-center">Back</button></a>
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
