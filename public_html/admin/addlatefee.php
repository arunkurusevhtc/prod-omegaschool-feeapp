<?php
require_once('admnavbar.php');
?>
<div class="container">
	<div class="row">
		<div class="col-sm-2 col-md-3 col-lg-4"></div>
		<div class="col-sm-8 col-md-6 col-lg-4">
			<div class="content content1">
				<p class="heading">Add Late Fee</p>
				<div class="main">
					<input name="id" type="hidden" value="<?php echo $res['id'];?>" />
					<form id="user_registered" method="post" action="adminactions.php">
						<div class="form-group">
								<label for ="days" class="control-label">No Of Days</label>
								<input type="number" id="days" name="days" required placeholder="No Of Days" class="form-control">
						</div>
						<div class="form-group">
								<label for ="amt" class="control-label">Amount</label>
								<input type="number" id="amt" name="amt" required placeholder="Amount" class="form-control">
						</div>
						
						<div class="form-group text-center">
							<button type="submit" value="new" name="addlatefee" class="btn btn-primary text-center">Add</button>
							<a href="managelatefee.php"><button type="button" value="Go Back" class="btn btn-warning text-center">Back</button></a>
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
