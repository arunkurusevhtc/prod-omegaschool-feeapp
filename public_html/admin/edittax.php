<?php
require_once('admnavbar.php');
$id=$_REQUEST['id'];
// print_r($id);
// exit;
$query = "SELECT * FROM taxcheck WHERE id='$id'"; 
$res = sqlgetresult($query);

?>
<div class="container pagetax">
	<div class="row">
		<div class="col-sm-2 col-md-3 col-lg-4"></div>
		<div class="col-sm-8 col-md-6 col-lg-4">
			<div class="content content1">
				<p class="heading">Edit Tax</p>
                <div class="main">
					<form id="user_registered" method="post" action="adminactions.php">
						<input name="id" type="hidden" value="<?php echo $res['id'];?>" />
						<div class="form-group">
								<label for ="Date" class="control-label">Effective Date</label>
								<input type="text" name="Date" required placeholder="Effective Date" class="form-control name datepicker" title="Must contain only Alphabets,Dots and Spaces" value="<?php echo trim($res['effectiveDate']);?>">
						</div>
						<div class="form-group">
								<label for ="type" class="control-label">Tax Type</label>
								<input type="text" id="type" name="type" required placeholder="Tax Type" class="form-control" value="<?php echo trim($res['taxType']);?>">
						</div>
						<div class="form-group">
								<label for ="ctax" class="control-label">Central Tax</label>
								<input type="number" id="ctax" name="ctax" required placeholder="central Tax" class="form-control" value="<?php echo trim($res['centralTax']);?>">
						</div>

						<div class="form-group">
								<label for ="stax" class="control-label">State Tax</label>
								<input type="number" id="stax" name="stax" required placeholder="State Tax" class="form-control" value="<?php echo trim($res['stateTax']);?>">
						</div>
						<div class="form-group text-center">
							<button type="submit" value="update" name="edittax" class="btn btn-primary text-center">Update</button>
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
