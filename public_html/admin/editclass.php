<?php
require_once('admnavbar.php');
$id=$_REQUEST['id'];
// print_r($id);
// exit;
$query = "SELECT * FROM classcheck WHERE id='$id'"; 
$res = sqlgetresult($query);

?>
<div class="container">
	<div class="row">
		<div class="col-sm-2 col-md-3 col-lg-4"></div>
		<div class="col-sm-8 col-md-6 col-lg-4">
			<div class="content content1">
				<p class="heading">Edit Class</p>
                <div class="main">
					<form id="user_registered" method="post" action="adminactions.php">
						<input name="id" type="hidden" value="<?php echo $res['id'];?>" />
						<div class="form-group">
								<label for ="cname" class="control-label">Class Name</label>
								<input type="text" id="cname" name="cname" required placeholder="Class Name" class="form-control" value="<?php echo trim($res['class_list']);?>">
						</div>
						<div class="form-group">
								<label for ="des" class="control-label">Description</label>
								<input type="text" id="des" name="des" placeholder="Description" class="form-control" value="<?php echo trim($res['description']);?>">
						</div>
						
						<div class="form-group text-center">
							<button type="submit" value="update" name="editclass" class="btn btn-primary text-center">Update</button>
							<a href="manageclass.php"><button type="button" value="Go Back" class="btn btn-warning text-center">Back</button></a>
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
