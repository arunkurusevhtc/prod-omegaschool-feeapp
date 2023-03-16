<?php
require_once('admnavbar.php');
$id=$_REQUEST['id'];
// print_r($id);
// exit;
$query = "SELECT * FROM yearcheck WHERE id='$id'"; 
$res = sqlgetresult($query);
// print_r($res);

?>
<div class="container passchk">
	<div class="row">
		<div class="col-sm-2 col-md-3 col-lg-4"></div>
		<div class="col-sm-8 col-md-6 col-lg-4">
			<div class="content content1">
				<p class="heading">Edit Year</p>
                <div class="main">
					<form id="user_registered" method="post" action="adminactions.php">
						<input name="id" type="hidden" value="<?php echo $res['id'];?>" />
						<div class="form-group">
								<label for ="year" class="control-label">Academic Year</label>
								<input type="text" id="year" name="year" required placeholder="Academic Year" class="form-control" value="<?php echo trim($res['year']);?>">
						</div>
						<div class="form-group">
							<label for="currentyear" class="control-label">Current Year</label>
							<input type="checkbox" class="currentyear" id="currentyear" name="currentyear" <?php if(trim($res['active'] == 1)){echo "checked";}?>>
						</div>
						<div class="form-group text-center">
							<button type="submit" value="update" name="edityear" class="btn btn-primary text-center">Update</button>
							<a href="manageyear.php"><button type="button" value="Go Back" class="btn btn-warning text-center">Back</button></a>
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
