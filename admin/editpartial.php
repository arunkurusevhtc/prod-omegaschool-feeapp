<?php
require_once('admnavbar.php');
$id=isset($_REQUEST['id'])?trim($_REQUEST['id']):"";
// print_r($id);
// exit;
if(empty($id)){
 header("location:managepartial.php");
}
$query = 'SELECT p.*,s."studentId",s."studentName" FROM partiallist as p JOIN  tbl_student s ON p.sId::int = s.id WHERE p.id='.$id; 
$res = sqlgetresult($query);

/*$slct1="";
if($res['status'] == "ACTIVE"){
	$slct1="selected='selected'";
}
$slct2="";
if($res['status'] == "INACTIVE"){
	$slct2="selected='selected'";
}*/

?>

<div class="container">
	<div class="row col-md-12">
			<div class="content1">
				<p class="heading">Edit</p>
                <div class="main">
					<form id="user_registered" method="post" action="adminactions.php">
						<input name="id" type="hidden" value="<?php echo $res['id'];?>" />
						<input name="sid" type="hidden" value="<?php echo $res['sid'];?>" />
						<div class="form-group">
								<label for ="name" class="control-label">Student Id : <?php echo $res['studentId']; ?></label>
						</div>	
						<div class="form-group">
								<label for ="name" class="control-label">Student Name : <?php echo $res['studentName']; ?></label>
						</div>	
						<div class="form-group">
								<label for ="name" class="control-label">Acad.Year</label>
								<?php
									$yearchecks = sqlgetresult("SELECT * FROM yearcheck",true);
								?>
								<select id="yearselect" name="yearselect"  class="yearselect form-control ">
								<option value="">Acad.Year</option>
								<?php

									foreach($yearchecks as $yearcheck) {
										$slct="";
										if($res['academic_yr'] == $yearcheck['id']){
											$slct="selected='selected'";
										}
									echo '<option value="'.$yearcheck['id'].'" '.$slct.'>'.$yearcheck['year'].'</option>';
									}
								?>
								</select>
						</div>
						<div class="form-group">
								<label for ="name" class="control-label">Partial Minimum (%)</label>
								<input type="number" name="partial_min" id="partial_min" value="<?php echo $res['partial_min_percentage'];?>">
						</div>
						<div class="form-group text-center">
							<button type="submit" value="update" name="editpartial" class="btn btn-primary text-center">Update</button>
							<a href="managepartial.php"><button type="button" value="Go Back" class="btn btn-warning text-center">Back</button></a>
					    </div>
					    
                    </form>
				</div>
			</div>
	</div>
</div>
<div class="row comment">
       
</div> 
<?php
include_once(BASEPATH.'footer.php');
?>
