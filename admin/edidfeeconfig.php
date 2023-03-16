<?php
require_once('admnavbar.php');
$id=$_REQUEST['id'];

$query = "SELECT * FROM feeconigdata WHERE id='$id'"; 
$res = sqlgetresult($query);
// print_r($res);
?>
<div class="container">
	<div class="row">
		<div class="col-sm-2 col-md-3 col-lg-4"></div>
		<div class="col-sm-8 col-md-6 col-lg-4">
			<div class="content content1">
				<p class="heading">Edit Fee Configuration</p>
				<div class="main">
					<form id="user_registered" method="post" action="adminactions.php">
						<input name="id" type="hidden" value="<?php echo $res['id'];?>" />
						<input name="dueDate" type="hidden" value="<?php echo $res['dueDate'];?>" />
						<input name="academic" type="hidden" value="<?php echo $res['academicYear'];?>" />
						<div class="form-group">
								<label for ="class" class="control-label">Class</label>
								<select name="class" class="form-control" >									
									<?php
										$classData = sqlgetresult("SELECT * FROM classcheck");
										foreach ($classData as $value) {
											if ($res['class_list'] == $value['class_list'])
										        echo '<option value="'.$value['id'].'" selected>'.$value['class_list'].'</option>';
										    else
										        echo '<option value="'.$value['id'].'">'.$value['class_list'].'</option>';
										}
									?>
								</select>
						</div>						
						<div class="form-group">
								<label for ="email" class="control-label">Stream</label>
								<select name="stream" class="form-control" >									
									<?php
										$streamData = sqlgetresult("SELECT * FROM streamcheck");
										foreach ($streamData as $value) {
											if ($res['stream'] == $value['stream'])
										        echo '<option value="'.$value['id'].'" selected>'.$value['stream'].'</option>';
										    else
										        echo '<option value="'.$value['id'].'">'.$value['stream'].'</option>';
										}
									?>
								</select>
						</div>
						<div class="form-group">
								<label for ="lname" class="control-label">Semester</label>
								<select name="semester" class="form-control" >									
									<?php
										$termData = sqlgetresult("SELECT * FROM termData");
										foreach ($termData as $value) {
											if ($res['semester'] == $value['semester'])
										        echo '<option value="'.$value['semester'].'" selected>'.$value['semester'].'</option>';
										    else
										        echo '<option value="'.$value['semester'].'">'.$value['semester'].'</option>';
										}
									?>
								</select>
						</div>
						<div class="form-group">
							<label for ="password" class="control-label">Fee Type</label>
							<select name="feeType" class="form-control" >									
									<?php
										$feeData = sqlgetresult("SELECT * FROM tbl_fee_type");
										foreach ($feeData as $value) {
											if ($res['feeType'] == $value['feeType'])
										        echo '<option value="'.$value['id'].'" selected>'.$value['feeType'].'</option>';
										    else
										        echo '<option value="'.$value['id'].'">'.$value['feeType'].'</option>';
										}
									?>
								</select>
						</div>
						<div class="form-group">
								<label for ="amount" class="control-label ">Amount</label>
								<input type="text" id="amount" name="amount" class="form-control" value="<?php echo $res['amount'];?>">
						</div>						
						<div class="form-group text-center">
							<button type="submit" value="update" name="editfeeconfig" class="btn btn-primary text-center">Update</button>
							<a href="feeconfiguration.php"><button type="button" value="Go Back" class="btn btn-warning text-center">Back</button></a>
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
