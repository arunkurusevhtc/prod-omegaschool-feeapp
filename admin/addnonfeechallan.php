<?php
	require_once('admnavbar.php');
	$studData = array();
	if (isset($_SESSION['data'])) {
		$studData = $_SESSION['data'];
	}
	// print_r($studData);
?>

<div class="container-fluid">
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
               } 
            ?>
         </div>
         <div class="col-xs-1 col-sm-2 col-md-3 col-lg-4"></div>
  </div>
	<div class="col-md-12"> 
		<h2 class="top text-right">NON FEE CHALLAN CREATION</h2>
	</div>
	<div class="col-md-12">
		<p style="color:red;font-size:11px;">Please use below filters to create non-fee challans.</p>
	</div>
	<div class="row col-md-12">
		<form action="adminactions.php" method="post" >
			<div class="col-md-2">
				<div class="form-group addsub">
					<select class="form-control" name="typefornonfee" id="typefornonfee">
						<option>--SELECT TYPE--</option>
						<option>Stream Wise</option>
						<option>Class Wise</option>
						<option>Section Wise</option>
						<option>Particular Student</option>
					</select>
				</div>
			</div>
			<div class="col-md-3">
				<div class="col-md-9 form-group addsubs">		
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
			<div class="col-md-3">
				<div class="col-md-9 form-group addsubc">
					<?php
						$classstypes = sqlgetresult("SELECT * FROM classcheck",true);
					?>
					<select name="classselect"  class="classselect  form-control classchange">
						<option value="">Class</option>                     
					</select>
				</div>
			</div>
			<div class="col-md-2">
				<div class="col-md-9 form-group addsubse">
					<?php
						$sectiontypes = sqlgetresult("SELECT DISTINCT section FROM tbl_student ORDER BY section ASC",true);
					?>
					<select name="sectionselect"  class="sectionselect  form-control">
						<option value="">Section</option>                     
					</select>
				</div>
			</div>
			<div class="col-md-2">
				<div class="col-md-9 form-group addsubss">
					<div class = "studentidsection">
						<input type="text" id="studentid" name="studentid" class="studentid form-control" placeholder="Student ID">
					</div>
				</div>
			</div>			
		</form>
	</div>   
   

	<div class="col-md-12" >
		<form  method="post" action="adminactions.php">
			<div class="table-responsive">
				<table class="table table-bordered dataTableChallan admintab">
					<thead>
						<tr>
							<th>  
							<input type="checkbox" id="checkAll" name="checkme<?php echo $i;?>" value="<?php echo $studData["studentId"];?>">
							</th>
							<th>S.No</th> 
							<th>Id</th>
							<th>Name</th>
							<th>Stream</th>
							<th>Class</th>
							<th>Section</th>
							<th>Term</th>							
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<?php
							$i=1;

							// if(count($studData) > 0) {					
							$num = ($studData!= null) ? count($studData) : 0;
							if($num > 0)
							{
								foreach ($studData as $data) {
								?>
								<tr class="filtertempchallan">
								<td><input type="checkbox" name="checkme[]" class="checkme" id="checkme" value="<?php echo $data["studentId"];?>" style="margin:10px;"></td>
								<td><?php echo $i;?></td>
								<td><?php echo $data['studentId'];?></td>
								<td><?php echo $data['studentName'];?></td>
								<td><?php echo getStreambyId($data['stream']);?></td>
								<td><?php echo getClassbyNameId($data['class']);?></td>
								<td><?php echo $data['section'];?></td>
								<td><?php echo $data['term'];?></td>
								<td class="fafa">
								<a href="nonfeechallancreation.php?id=<?php echo trim($data["studentId"]); ?>"><i class="fa fa-edit"></i></a>
								</td>

								</tr>
								<?php	
								$i++;
								}
							}							
						?>
					</tbody>
				</table>
			</div>
			<button type = "submit" name="submit" id="clickme" value="createnonfeechallan" class = "btn btn-info sendnewsms"  disabled="disabled">Edit Students</button>
		</form>
	</div>  
</div>

<div class="row comment"></div>
<?php
	include_once(BASEPATH.'footer.php');
?>

<script type="text/javascript">

	$(".streamselect").hide();
	$(".classselect").hide();
	$(".sectionselect").hide();
	$(".studentidsection").hide();
	
	var btnhtml = '<div class="col-md-1 subtn"><button type="submit" name="nonfeetypefilter" value="nonfeetypechallanfilter" id="nonfeetypechallanfilter" class="btn btn-info nonfeetypechallan">Filter</button></div>';

    $(document).on("change", "#typefornonfee", function() {
        var typename = $("#typefornonfee").val();
        var streamname = $("#streamselect").val();
        var classname = $("#classselect").val();
        var sectionname = $("#sectionselect").val();
        var studentid = $("#studentidsection").val();
        $(".subtn").hide();

        if(typename == "Stream Wise") {
			$(".streamselect").show();
			$(".classselect").hide();
			$(".sectionselect").hide();
			$(".studentidsection").hide();
			$(".addsubs").after(btnhtml);
			
        } else if(typename == "Class Wise") {
			$(".streamselect").show();
			$(".classselect").show();
			$(".sectionselect").hide();
			$(".studentidsection").hide();
			$(".addsubc").after(btnhtml);
		} else if(typename == "Section Wise") {
			$(".streamselect").show();
			$(".classselect").show();
			$(".sectionselect").show();
			$(".addsubse").after(btnhtml);
		} else {
			$(".streamselect").hide();
			$(".classselect").hide();
			$(".sectionselect").hide();
			$(".studentidsection").show();
			$(".addsubss").after(btnhtml);
		}
		
        $.ajax({
            url: 'adminactions.php',
            method: 'post',
            dataType: 'json',
            data: {
                'submit': 'nonfeeselection',
                'type': typename,
                'stream': streamname,
                'class': classname,
                'section':  sectionname,
                'studentid': studentid
                // 'feegroup': feegroup
            },
            success: function(response) {
    
            }
        });
    });
</script> 