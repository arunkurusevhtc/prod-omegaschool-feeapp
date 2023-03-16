<?php
	include_once('admnavbar.php');

	$studData = sqlgetresult('SELECT c.*,s."studentName", s."section",s."hostel_need" FROM tbl_temp_challans c LEFT JOIN tbl_student s ON s."studentId" = c."studentId"  WHERE c."feeTypes" IS  NULL ORDER BY s."studentName" ASC',true);
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
               } elseif(isset($_SESSION['failure'])) {
                  echo $_SESSION['failure'];
                  unset($_SESSION['failure']);
               }
            ?>
         </div>
         <div class="col-xs-1 col-sm-2 col-md-3 col-lg-4"></div>
   </div>
   <div class="col-md-12"> 
   <div class="col-sm-6 p-l-0">
   <h2 class="top">TEMPORARY CHALLANS</h2>
</div>
     <div class="col-sm-6 p-r-0 text-right">
		<form method="post" id = "filterchallan" class="form-inline" action="adminactions.php">
            <div class="form-group">
                 <div>
                    <select name="studtype"  class="studtype form-control">
                      <option value="">Type</option>
                      <option value="N">Day-Scholar</option>
                      <option value="Y">Boarders</option>
                    </select>
                  </div>
                </div> 
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
                  <!-- <label for="studStatus">Class</label> -->
                  <div>
                    <?php
                    $sectiontypes = sqlgetresult("SELECT DISTINCT section FROM tbl_student ORDER BY section ASC",true);
                    ?>
                    <select name="sectionselectt"  class="sectionselect  form-control">
                      <option value="">Section</option>                     
                    </select>
                  </div>                                    
                </div>
                <button type="submit" name="filter" value="filterchallan" class="btn btn-info">Filter</button>
              </form>
              </div>
           </div>

<div class="col-md-12 m-t-15" >

  

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
          <th>Status</th>
          <th>Hostel Need</th>
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
						<td><?php echo getClassbyNameId($data['classList']);?></td>
            <td><?php echo $data['section'];?></td>
						<td><?php echo $current_term;?></td>
            <td><?php echo $data['studStatus'];?></td>
            <td><?php echo $data['hostel_need'];?></td>
						<td class="fafa">
					    	<a href="editStudent.php?id=<?php echo trim($data["studentId"]); ?>"><i class="fa fa-edit"></i></a>
					    </td>

		            </tr>
				<?php	
						$i++;
					}
				}
        // else {
        //         echo "<tr><td colspan='10' style='text-align:center;'>No Data Avaiable.</td></tr>";
        //       }
				?>
			</tbody>
		 </table>
    </div>

		 <button type = "submit" name="submit" id="clickme" value="createtempchallan" class = "btn btn-info sendnewsms"  disabled="disabled">Edit Students</button>

	   </form>
	</div>	
</div>
<div class="row comment">
       
</div>

<?php



// unset($_SESSION['selectedchallans']);
	include_once('..\footer.php');
?>