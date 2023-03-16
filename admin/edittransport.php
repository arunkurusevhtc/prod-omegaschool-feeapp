<?php
require_once('admnavbar.php');
$id=$_REQUEST['id'];
// print_r($id);
// exit;
$query = "SELECT * FROM transportcheck WHERE id='$id'"; 
$res = sqlgetresult($query);

?>
<div class="container">
	<div class="row">
		<div class="col-sm-2 col-md-3 col-lg-4"></div>
		<div class="col-sm-8 col-md-6 col-lg-4">
			<div class="content content1">
				<p class="heading">Edit Transport</p>
                <div class="main">
					<form  id="user_registered" method="post" action="adminactions.php" >
						<input name="id" type="hidden" value="<?php echo $res['id'];?>" />
						<div class="form-group">
								<label for ="ppoint" class="control-label">Pick-Up Point</label>
								<input type="text" id="ppoint" name="ppoint"  placeholder="Pick Up Point" class="form-control" value="<?php echo trim($res['pickUp']);?>">
						</div>
						<div class="form-group">
								<label for ="dpoint" class="control-label">Drop-Down Point</label>
								<input type="text" id="dpoint" name="dpoint" placeholder="Drop Down Point" class="form-control" value="<?php echo trim($res['dropDown']);?>">
						</div>
						<div class="form-group">
								<label for ="stage"  class="control-label">Stage</label>
								<input type="text" id="stage" name="stage" placeholder="Stage" class="form-control" value="<?php echo trim($res['stage']);?>">
						</div>
						<div class="form-group">
								<label for ="amt" class="control-label">Amount</label>
								<input type="number" id="amt" name="amt" placeholder="Amount" class="form-control" value="<?php echo trim($res['amount']);?>">
						</div>
						
						<div class="form-group text-center">
							<button type="submit" value="update" name="edittransport" class="btn btn-primary text-center">Update</button>
							<a href="managetransport.php"><button type="button" value="Go Back" class="btn btn-warning text-center">Back</button></a>
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
