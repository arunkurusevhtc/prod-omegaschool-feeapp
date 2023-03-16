<?php
	require_once('admnavbar.php');

	$data = sqlgetresult('SELECT * FROM nonfeechallandata WHERE "challanStatus" = 1', true);
	// print_r($data);
?>

<div class="container-fluid">
	<div class="col-md-12 ">
	    <div class="col-md-3 p-l-0">
	        <h2 class="top">PAID NON-FEE CHALLANS</h2>
	    </div>
	    <div class="col-md-4 p-r-0 text-right">
	        <form  id="filternonfeepaidchallans" class="form-inline">
	        	<input type="hidden" name="common" id="commonfee" value="0">
	            <div class="form-group">
	                <div>
	                    <?php
	                        $streamtypes = sqlgetresult("SELECT * FROM streamcheck",true);
	                        ?>
	                    <select id="streamselect"  name="streamselect"  class="streamselect form-control streamchange">
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
	                    <select id="classselect" name="classselect"  class="classselect  form-control classchange">
	                        <option value="">Class</option>
	                       
	                    </select>
	                </div>
	            </div>
	            <div class="form-group">
	           
	                <div>
	                    <?php
	                        $sectiontypes = sqlgetresult("SELECT DISTINCT section FROM tbl_student ORDER BY section ASC",true);
	                        ?>
	                    <select id="sectionselect" name="sectionselect"  class="sectionselect  form-control">
	                        <option value="">Section</option>                        
	                    </select>
	                </div>
	            </div>
	            <button type="button" id="fltnonfeepaidchallan" name="filter" value="filternonfeepaidchallan" class="btn btn-info">Filter</button>
	        </form>
	    </div>
	    <div class="col-md-3 p-r-0 text-right">
			<div class="btn-group btn-toggle"> 
				<button class="btn btn-md btn-primary active" value="non-fee">Non-Fee</button>
				<button class="btn btn-md btn-default" value="common-fee">Common-Fee</button>
			</div>
	    </div>
	    <div class="col-md-2 text-right p-r-0">
	    	<a href="adminactions.php?excel=nonfeepaid&common=0" class="btn btn-primary btnexcel">Download Excel</a>
	    </div>
	</div>
	<div class="col-md-12 m-t-15" >
	    <div class="table-responsive">
	        <form>
	            <table class="table table-bordered admintab dataTableNonfeePaid">
	                <thead>
	                    <tr>
	                        <th>S.No</th>
	                        <th>Student ID</th>
	                        <th>Challan No</th>
	                        <th>Name</th>
	                        <th>Stream</th>
	                        <th>Class</th>
	                        <th>Section</th>
	                        <th>Term</th>
	                        <th>Event</th>
	                        <th>Amount</th>
	                        <th>Created Date</th>
	                        <th>Paid Date</th>
	                        <th>Action</th>	                        
	                    </tr>
	                </thead>
	                <tbody>
	                    <?php
	                        $i=1;
	                        // print_r($data);
	                        
	                        // if(count($data) > 0) { 
	                        $num = ($data!= null) ? count($data) : 0;
	                        if($num > 0)
	                        {      

	                        // print_r($data);   
	                        foreach ($data as $data) {
	                          ?>
	                          <!-- date("d-m-Y", strtotime($data['createdOn'])) -->
	                    <tr class="filterrowchallan">
	                        <td><?php echo $i;?></td>
	                        <td><?php echo $data['studentId'];?></td>
	                        <td><?php echo $data['challanNo'];?></td>
	                        <td><?php echo $data['studentName'];?></td>
	                        <td><?php echo $data['steamname'];?></td>
	                        <td><?php echo $data['class_list']?></td>
	                        <td><?php echo $data['section'];?></td>
	                        <td><?php echo $data['term'];?></td>
	                        <td><?php echo $data['feename'];?></td>
	                        <td class="text-right"><?php echo $data['total'];?></td>	  
	                        <td><?php print_r(date("d-m-Y", strtotime($data['createdOn'])));?></td>
	                        <td><?php print_r(date("d-m-Y", strtotime($data['updatedOn'])));?></td>
	                        <td>&nbsp;</td>
	                                              
	                    </tr>
	                    <?php 
	                        $i++;
	                        }
	                        } 
	                        // else {
	                        //            echo "<tr><td colspan='11' style='text-align:center;'>No Data Avaiable.</td></tr>";
	                        //           }
	                        ?>
	                </tbody>
	            </table>
	        </form>
	    </div>
	</div>
</div>

<div class="row comment"></div>
<?php
	include_once(BASEPATH.'footer.php');
?>