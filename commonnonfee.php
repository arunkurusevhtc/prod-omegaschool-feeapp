<?php 
	ob_start();
	require_once('navbar.php');
	date_default_timezone_set("Asia/Kolkata");    
    $cur_data = time();
    $date = date('Y-m-d h:i:s');
    
    $studentIds = sqlgetresult('SELECT "studentId" FROM getparentdata WHERE id = \''.$_SESSION['uid'].'\' AND status = \'1\' AND deleted = \'0\' GROUP BY "studentId" ',true);

    // print_r($studentIds);
?>

<div class="container">
    <div class="col-sm-12" id="msg">
        <?php
            if(isset($_SESSION['success_msg']) && $_SESSION['success_msg'] !='') {
                echo $_SESSION['success_msg'];
                unset($_SESSION['success_msg']);
            }
            if (isset($_SESSION['error_msg']) && $_SESSION['error_msg'] !='') {
                echo $_SESSION['error_msg'];
                unset($_SESSION['error_msg']);
            }
            // if (isset($_SESSION['error_stdid']) && $_SESSION['error_stdid'] !='') {
            //     echo $_SESSION['error_stdid'];
            //     unset($_SESSION['error_stdid']);
            // }
        ?>
    </div>
    <div class="error_stdid"></div>
	<div class="row">
		<div id="topup-container" class="col-md-offset-3 col-md-5">
			<h4 class="crdtoph">Common Non-Fee</h4>
			<form class="form-horizontal" action="sql_actions.php" method="post" id="addcartfrm">
				<div class="form-group">
					<label class="control-label col-sm-5" for="email">StudentId: </label>
					<div class="col-sm-7">
                        <select class="form-control nonfeestudid" name="studId" id="studId" required>
                            <option>-Select-</option>
                            <?php
                                foreach ($studentIds as $sid) {
                                    echo "<option value='".$sid['studentId']."'>".$sid['studentId']."</option>";
                                }
                            ?>
                        </select>
					</div>
				</div>
                <input type="hidden" name="studentidfornonfee" id="studentidfornonfee" value="">
                <div class= "eventdetails">
    				<div class="form-group">
    					<label class="control-label col-sm-5" for="email">Event Name: </label>
    					<div class="col-sm-7">
                         <select name="eventname"  class="eventname form-control" id="eventnameid">
                            <option value="">--Select--</option>
                         </select>
    					</div>
    				</div>
                    <div class="form-group">
                        <label class="control-label col-sm-5" for="email">Amount </label>
                        <div class="col-sm-7">
                            <input type= "text" readonly value="0" name = "amountofevent" id="amountofevent" class="form-control">
                        </div>
                    </div>
                </div>
				<div class="text-center">
                    <input type="hidden" name="otherfeestype" id="otherfeestype" value="6">
					<button type="button" name="addtocart" value="add" class="btn btn-info addtocart" id="paycommonnonfee">Add to Cart</button>
				</div>
			</form>
		</div>
	</div>
</div>	


 <hr>
<div class="container-fluid">
    <div class="table-responsive">
        <button type="button" class="btn button1" data-toggle="collapse" data-target="#list">+ Common Non-fee Paid</button>
        <div id="list" class="collapse">
            <table class="table table-bordered opayment1" cellspacing="0" width="400">
                <?php
                    $paiddata = sqlgetresult('SELECT p."amount" AS "total", p."createdOn" AS "updatedOn",p."challanNo",p."createdOn",s."class",s."stream",s."term",s."studentName",s."studentId",s."section",s."academic_yr"  FROM tbl_nonfee_payments p LEFT JOIN tbl_student s ON s."studentId" = p."studentId" OR s."application_no" = p."studentId" WHERE s."parentId" = \''.$_SESSION['uid'].'\' AND p."challanNo" ILIKE \'%EVENT%\' AND p."transStatus" = \'Ok\' AND s."status" = \'1\' AND s."deleted" = \'0\'',true);

                    // SELECT p."amount" AS "total", p."updatedOn",p."challanNo",p."createdOn",s."class",s."stream",s."term",s."studentName",s."studentId",s."section",s."academic_yr"  FROM tbl_nonfee_payments p LEFT JOIN tbl_student s ON s."studentId" = p."studentId" WHERE p.id = \''.$id.'\' AND "challanNo" ILIKE \'%EVENT%\' AND p."transStatus" = \'Ok\'  

                    // print_r($paiddata);          

                    $challanData = array();
                   $total =0;
                   $tot = 0;    
                   $challanNo  = '';
                   $feeData = array();
                   $count = $paiddata == '' ? 0 : count($paiddata);
                   if($count != 0) {
                       foreach ($paiddata as $k => $data) {
                           $challanData[$k]['studentName'] = $data['studentName'];                          
                           $challanData[$k]['studentId'] = $data['studentId'];
                           $challanData[$k]['challanNo'] = $data['challanNo'];
                           $challanData[$k]['org_total'] = $data['total'];   
                           $challanData[$k]['updatedOn'] = $data['updatedOn'];                      
                       }
                       $data =  $challanData;    
                   } else {
                       $data = array();
                   }
                ?>

                <tr>
                    <th>S.No</th>
                    <th>Student ID</th>
                    <th>Student Name</th>
                    <th>Amount Paid</th>
                    <th>Action</th>
                </tr>
                <?php
                $i = 1;
                    if(count($data) > 0) {
                    foreach($data AS $data) {
                        // print_r($data);
                        $date = date('dmY', strtotime($data['updatedOn']));
                        $receiptname = $data['studentId'].trim($data['challanNo']);
                        $pdfpath = BASEURL.'receipts/'.$date.'/'.str_replace('/', '', trim($receiptname)).'.pdf';
                ?>
                    <tr>
                        <td><?php echo $i ;?></td>
                        <td><?php echo $data['studentId'] ;?></td>
                        <td><?php echo $data['studentName'] ;?></td>
                        <td class='text-right'><?php echo $data['org_total'] ;?></td>                        
                        <td><a href="<?php echo $pdfpath;?>" target="_blank">Download Receipt</a></td>
                    </tr>
                <?php
                    $i++;
                    }
                } else {
                    echo "<td colspan='6'><center>No Paid Challan Data Available.</center></td>";
                }
                ?>
            </table>
        </div>
    </div>
</div>


<div class="row comment"></div>

<?php
  include_once('footer.php');
?>
