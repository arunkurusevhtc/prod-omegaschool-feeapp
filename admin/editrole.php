<?php
require_once('admnavbar.php');
$id=isset($_REQUEST['id'])?trim($_REQUEST['id']):"";
// print_r($id);
// exit;
if(empty($id)){
 header("location:manageroles.php");
}
$query = "SELECT * FROM adminrolesscheck WHERE id='$id'"; 
$res = sqlgetresult($query);

?>
<div class="container">
	<div class="row">
		<div class="col-sm-2 col-md-3 col-lg-4"></div>
		<div class="col-sm-8 col-md-6 col-lg-4">
			<div class="content content1">
				<p class="heading">Edit Admin Role</p>
                <div class="main">
					<form id="user_registered" method="post" action="adminactions.php">
						<input name="id" type="hidden" value="<?php echo $res['id'];?>" />
						<div class="form-group">
								<label for ="waivertype" class="control-label">Role</label>
								<input type="text" id="role" name="role" required placeholder="Admin Role" class="form-control" value="<?php echo trim($res['roles']);?>">
						</div>
						<div class="form-group">
								<label for ="des" class="control-label">Description</label>
								<input type="text" id="des" name="des" placeholder="Description" class="form-control" value="<?php echo trim($res['description']);?>">
						</div>
						
						<div class="form-group text-center">
							<button type="submit" value="update" name="editrole" class="btn btn-primary text-center">Update</button>
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
