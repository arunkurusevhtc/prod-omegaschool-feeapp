<?php
require_once('admnavbar.php');
$roles = sqlgetresult('SELECT id,role FROM adminrolesscheck WHERE status=\'ACTIVE\' ORDER BY id',true);
?>
<div class="container">
	<div class="row">
		<div class="col-sm-2 col-md-3 col-lg-4"></div>
		<div class="col-sm-8 col-md-6 col-lg-4">
			<div class="content content1">
				<p class="heading">Add Menu</p>
				<div class="main">
					<form id="user_registered" method="post" action="adminactions.php">
						<div class="form-group">
							<label for ="menu" class="control-label ">Main Menu</label>
							<select class="form-control" name="menu" required="required">
                              <option value="">--Select--</option>
                              <?php
                              $menus = sqlgetresult('SELECT * FROM tbl_adminmainmenu WHERE status=\'1\' ORDER BY "displayOrder"',true);
                              foreach($menus as $menu){
                                echo('<option value="'.$menu['id'].'">'.$menu['mainmenu'].'</option>');
                              }
                              ?>
                            </select>
						</div>
						<div class="form-group">
								<label for ="name" class="control-label">Sub Menu</label>
								<input type="text" id="smenu" name="smenu" required placeholder="Sub Menu" class="form-control" required="required">
						</div>
						<div class="form-group">
								<label for ="des" class="control-label">Link</label>
								<input type="text" id="link" name="link" placeholder="Link" class="form-control" required="required">
						</div>
						<div class="form-group">
								<label for ="des" class="control-label">Display Order</label>
								<input type="number" id="disp" name="disp" placeholder="Display Order" class="form-control">
						</div>

						<div class="form-group" style="display:none">
							<label for ="des" class="control-label">Access By</label>
							<?php foreach($roles as $role){ 
                             $rolename=trim($role['role']);
                             $chk="checked=checked";
                             /*if($rolename=='Super Admin'){
                             	$chk="checked=checked";
                             }*/
							?>
							<div class="col-lg-12">
							<input class="feegroupcheckNew" type="checkbox" name="role[]" value="<?php echo $role['id']; ?>" <?php echo $chk; ?>>&nbsp;<?php echo $rolename; ?>
							</div>
						<?php }  ?>
                        </div>

						<div class="form-group text-center">
							<button type="submit" value="new" name="addmenu" class="btn btn-primary text-center">Add Menu</button>
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
