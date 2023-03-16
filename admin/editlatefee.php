<?php
require_once('admnavbar.php');
$id=$_REQUEST['id'];
// print_r($id);
// exit;
$query = "SELECT * FROM latefeecheck WHERE id='$id'"; 
$res = sqlgetresult($query);

?>
<div class="container">
	<div class="row">
		<div class="col-sm-2 col-md-3 col-lg-4"></div>
		<div class="col-sm-8 col-md-6 col-lg-4">
			<div class="content content1">
				<p class="heading">Edit Late Fee</p>
                <div class="main">
					<form id="user_registered" method="post" action="adminactions.php">
						<input name="id" type="hidden" value="<?php echo $res['id'];?>" />
						<div class="form-group">
								<label for ="days" class="control-label">No Of Days</label>
								<input type="number" id="days" name="days" required placeholder="No Of Days" class="form-control" value="<?php echo trim($res['noOfDays']);?>">
						</div>
						<div class="form-group">
								<label for ="amt" class="control-label">Amount</label>
								<input type="number" id="amt" name="amt" required placeholder="Amount" class="form-control" value="<?php echo trim($res['amount']);?>" step=".01">
						</div>
						
						<div class="form-group text-center">
							<button type="submit" value="update" name="editlatefee" class="btn btn-primary text-center">Update</button>
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
