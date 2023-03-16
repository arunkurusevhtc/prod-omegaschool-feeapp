<?php
require_once('admnavbar.php');
?>
<div class="container pagetax">
	<div class="row">
		<div class="col-sm-2 col-md-3 col-lg-4"></div>
		<div class="col-sm-8 col-md-6 col-lg-4">
			<div class="content content1">
				<p class="heading">Add Tax</p>
				<div class="main">
					<input name="id" type="hidden" value="<?php echo $res['id'];?>" />
					<form id="user_registered" method="post" action="adminactions.php">
						<div class="form-group">
								<label for ="date" class="control-label">Effective Date</label>
								<input type="text" name="date" required placeholder="Effective Date" class="form-control datepicker">
						</div>
						<div class="form-group">
								<label for ="type" class="control-label">Tax Type</label>
								<input type="text" id="type" name="type" required placeholder="Tax Type" class="form-control">
						</div>
						<div class="form-group">
								<label for ="ctax" class="control-label">Central Tax</label>
								<input type="number" id="ctax" name="ctax" required placeholder="Central Tax" class="form-control" step=".01">
						</div>

						<div class="form-group">
								<label for ="stax" class="control-label">State Tax</label>
								<input type="number" id="stax" name="stax" required placeholder="State Tax" class="form-control" step=".01">
						</div>
						<div class="form-group text-center">
							<button type="submit" value="new" name="addtax" class="btn btn-primary text-center">Add</button>
							<a href="managetax.php"><button type="button" value="Go Back" class="btn btn-warning text-center">Back</button></a>
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
