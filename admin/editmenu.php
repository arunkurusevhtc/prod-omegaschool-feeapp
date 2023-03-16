<?php
require_once('admnavbar.php');
$id=isset($_REQUEST['id'])?trim($_REQUEST['id']):"";
if(empty($id)){
 header("location:managemenulist.php");
}
$query = "SELECT * FROM tbl_admin_submenu WHERE id='$id'"; 
$res = sqlgetresult($query);
?>
<div class="container">
	<div class="row">
		<div class="col-sm-2 col-md-3 col-lg-4"></div>
		<div class="col-sm-8 col-md-6 col-lg-4">
			<div class="content content1">
				<p class="heading">Edit Menu</p>
                <div class="main">
					<form id="user_registered" method="post" action="adminactions.php">
						<input name="id" type="hidden" value="<?php echo $res['id'];?>" />
						<div class="form-group">
							<label for ="menu" class="control-label ">Main Menu</label>
							<select class="form-control" name="menu" required="required">
                              <option value="">--Select--</option>
                              <?php
                              $menus = sqlgetresult('SELECT * FROM tbl_adminmainmenu WHERE status=\'1\' ORDER BY "displayOrder"',true);
                              foreach($menus as $menu){
                              	if($menu['id'] == $res['mainmenuId']){
                                   echo('<option value="'.$menu['id'].'" selected="selected">'.$menu['mainmenu'].'</option>');
                              	}else{
                              		echo('<option value="'.$menu['id'].'" >'.$menu['mainmenu'].'</option>');
                              	}
                              }
                              ?>
                            </select>
						</div>
						<div class="form-group">
								<label for ="name" class="control-label">Sub Menu</label>
								<input type="text" id="smenu" name="smenu" placeholder="Sub Menu" class="form-control" required="required" value="<?php echo $res['submenu']; ?>">
						</div>
						<div class="form-group">
								<label for ="des" class="control-label">Link</label>
								<input type="text" id="link" name="link" placeholder="Link" class="form-control" required="required" value="<?php echo $res['link']; ?>">
						</div>
						<div class="form-group">
								<label for ="des" class="control-label">Display Order</label>
								<input type="number" id="disp" name="disp" placeholder="Display Order" class="form-control" value="<?php echo $res['displayOrder']; ?>">
						</div>

						<div class="form-group text-center">
							<button type="submit" value="update" name="editmenu" class="btn btn-primary text-center">Update</button>
							<a href="managemenulist.php"><button type="button" value="Go Back" class="btn btn-warning text-center">Back</button></a>
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
