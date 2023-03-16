<?php
    include_once('admnavbar.php');
?>


   <div class="row col-md-12">
         <div class="col-xs-1 col-sm-2 col-md-3 col-lg-4"></div>
         <div class="col-xs-10 col-sm-8 col-md-6 col-lg-4">
               
              <?php
                  if(isset($_SESSION['successs'])) {
                  echo $_SESSION['successstd'];
                  unset($_SESSION['successstd']);
                      }
                  if(isset($_SESSION['errorstd'])) {
                  echo $_SESSION['errorstd'];
                  unset($_SESSION['errorstd']);
                      }
                  if(isset($_SESSION['success'])) {
                  echo $_SESSION['success'];
                  unset($_SESSION['success']);
                      }
                  if(isset($_SESSION['failure'])) {
                  echo $_SESSION['failure'];
                  unset($_SESSION['failure']);
                      }
                  if(isset($_SESSION['error'])) {
                  echo $_SESSION['error'];
                  unset($_SESSION['error']);
                      }
				$sql = 'SELECT * FROM gettaxexemapplied WHERE deleted = \'0\'';
	              $res = sqlgetresult($sql,true);
	              $num = ($res!= null) ? count($res) : 0;
                    ?>
                    
          

                    
         </div>
         <div class="col-xs-1 col-sm-2 col-md-3 col-lg-4"></div>
   
   </div>
     
<div class="row container  col-md-12">
 
    <div class="col-md-6 m-d-15">
    	<h2 class="top"  style="padding-left: 15px;">Tax Exemption</h2>   
	</div> 

	<div class="col-md-6 text-right">

        <form  id="tax_exemption_filter" class="form-inline">
            <div class="form-group">
                <div>
                    <?php
                        $streamtypes = sqlgetresult("SELECT * FROM streamcheck",true);
                        ?>
                    <select name="streamselect"  class="streamselect form-control streamchange">
                        <option value="">Stream</option>
                        <?php
                            foreach($streamtypes as $stream) {
                            echo '<option value="'.$stream['id'].'" >'.$stream['stream'].'</option>';
                            }
                            ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <!-- <label for="studStatus">Class</label> -->
                <div>
                    <?php
                        $classstypes = sqlgetresult("SELECT * FROM classcheck",true);
                        ?>
                    <select name="classselect"  class="classselect  form-control classchange">
                        <option value="">Class</option>
                       
                    </select>
                </div>
            </div>
            <div class="form-group">
           
                <div>
                    <?php
                        $sectiontypes = sqlgetresult("SELECT DISTINCT section FROM tbl_student ORDER BY section ASC",true);
                        ?>
                    <select name="sectionselect"  class="sectionselect  form-control">
                        <option value="">Section</option>                        
                    </select>
                </div>
            </div>
            <button type="submit" name="filter" value="tax_exemption_filter" class="btn btn-info">Filter</button>
        </form>

		<?php if($num==0){
		?>
		<a href="addtax_exemption.php" style="padding-bottom: 5px;padding-top: 5px;" class="pull-right"><button class="btn btn-info">Generate Tax Cert.</button></a>
    	<?php }
    	?>

    </div>
</div>


        <div class="container-fluid">

         <div class="table-responsive col-md-12">
            <table class="table table-bordered admintab dataTableTaxExemption">
            <!-- <caption class="title">Sales Data of Electronic Division</caption> -->
               <thead>
                  <tr>
                  <th>S.No</th>
                  <th>Student Id</th>
                  <th>Student Name</th>                  
                  <th>Stream</th>
                  <th>Class</th>
                  <th>Section</th>
                  <th>Amount</th>
                  <th>Year</th>
                  <th>Parent Name</th>
                  <th>Actions</th>
                  </tr>
               </thead>

               <tbody>

                  <?php
                  $count=1;

                  
                  if($num > 0)
                  {
                  foreach ($res as $std)
                  {
                  ?>
                     <tr>
                        <td><?php echo $count; ?></td>
                        <td><?php echo trim($std["student_id"]);?></td>
                        <td><?php echo trim($std["studentName"]); ?></td>
                        <td><?php echo trim($std["streamname"]); ?></td>
                        <td width="10%;"><?php echo ($std["class_list"]); ?></td>              
                        <td><?php echo trim($std["section"]); ?></td>
                        <td><?php echo trim($std["amount"]); ?></td>
                        <td><?php echo trim($std["year"]); ?></td>
                        <td><?php echo trim($std["userName"]); ?></td>
                        <td class="fafa">
  
                           <a class="edit_amt" id="<?php echo trim($std["id"]);?>"><i class="fa fa-edit"></i></a> 
                           <a href="<?php echo $std['pdf_url'];?>" target = '_blank'><i class="fa fa-eye"></i></a>
                           <a onclick="print('<?php echo $std['pdf_url'];?>');"><i class="fa fa-print"></i></a>
                           <a class="delete_entry" id="<?php echo trim($std["id"]);?>"><i class="fa fa-trash-o"></i></a>
                        </td>
                     </tr>
                     <?php $count++;
                     } 
                  }
                  else {
                       echo "<tr><td colspan='10'>No Data Available.</td></tr>";
                  }
                     ?>


               </tbody>

            </table>
         </div>
      </div>
<div class="modal fade preview_modal" id="modal_edit"  role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="container col-lg-12">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" id="emailclose" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Edit Reciept</h4>
                </div>
                <div class="modal-body" id="tax_preview_modal">
                	<form>
	                  	<div class="form-group">
							<label for ="fname" class="control-label">Student ID</label>
							<input type="text" id="fname"  name="student_id" required placeholder="Student ID" class="form-control student_id_get" disabled>
						</div> 
						<div class="form-group">
							<label for ="fdes" class="control-label">Academic Year</label>
							<select class="form-control academic_year" required name="academic_year">
								<option>--SELECT YEAR--</option>
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
							<input type="hidden" class="flag_value" name="edit_insert" value="1">
              <input type="hidden" class="row_id" value = "1" >
					</form>
                </div>
                <div class="modal-footer">
                	<button class="btn btn-primary download_tax_exemption">Save</button>
                	
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row comment">
       
</div>
<?php
    





include_once(BASEPATH.'footer.php');
?>