<?php
    include_once('admnavbar.php');
?>
<div class="container">
	<div class="row col-md-12">
         <div class="col-xs-1 col-sm-2 col-md-3 col-lg-4"></div>
         <div class="col-xs-10 col-sm-8 col-md-6 col-lg-4">  
            <?php
               if(isset($_SESSION['success'])) {
                  echo $_SESSION['success'];
     	             unset($_SESSION['success']);
               } elseif(isset($_SESSION['error'])) {
                  echo $_SESSION['error'];
                  unset($_SESSION['error']);
               } elseif(isset($_SESSION['failure'])) {
                  echo $_SESSION['failure'];
                  unset($_SESSION['failure']);
               }
            ?>
         </div>
         <div class="error"></div>
         <div class="col-xs-1 col-sm-2 col-md-3 col-lg-4"></div>
   </div>
	<div class="row">
		<div class="col-sm-2 col-md-3 col-lg-3"></div>
		<div class="col-sm-8 col-md-6 col-lg-6" >
			<div class="content content1" style="width: 100%">
				<p class="heading">Generate Tax Exemption Receipt</p>
				<div class="main">
					<input name="id" type="hidden" value="<?php echo $res['id'];?>" />
					<form id="tax_exemption_form" class="tax_exemption_form" method="post" >
						<div class="form-group">
								<label for ="fname" class="control-label">Student ID</label>
								<input type="text" id="fname"  name="student_id" required placeholder="Student ID" class="form-control student_id_get">
						</div>
						<div class="form-group">
							<label for ="fdes" class="control-label">Academic Year</label>
							<select class="form-control academic_year" required name="academic_year">
								<option >--SELECT YEAR--</option>
								<?php
									$yearlist = sqlgetresult("SELECT * FROM yearcheck", true);
									foreach ($yearlist as $year) {
										echo "<option value='".$year['id']."'>".$year['year']."</option>";
									}
								?>
							</select>
						</div>
						<div class="form-group">
								<label for ="fdes" class="control-label">Amount</label>
								<input type="number" id="fdes" name="amount" placeholder="Amount" class="form-control amount" required>
						</div>
						<input type="hidden" class="flag_value" name="edit_insert" value="2">
						<input type="hidden" class="row_id" name="edit_insert" value="0">
						<div class="form-group text-center">
							<button type="submit" value="generate_certificate" name="generate_tax_exemption" class="btn btn-primary text-center">Preview</button>
							<a href="tax_exemption.php"><button type="button" value="Go Back" class="btn btn-warning text-center">Back</button></a>
					    </div>
					    
                    </form>
				</div>
			</div>
		</div>
		<div class="col-sm-2 col-md-3 col-lg-3"></div>
	</div>
	<div class="modal fade preview_modal"  role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="container col-lg-12">
            <div class="modal-content">
                <div id="nxtlevel" class="modal-header">
                    <button type="button" id="emailclose" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Tax Exemption Preview</h4>
                </div>
                <div class="modal-body tax_preview_modal" id = "tax_preview_modal">
                   
                </div>
                <div class="modal-footer">
                	<button id="download_tax_exemption" class="btn btn-primary download_tax_exemption">Save</button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="hide_contents">
	
</div>
</div>
<?php
	include_once(BASEPATH.'footer.php');
?>