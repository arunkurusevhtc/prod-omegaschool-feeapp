<?php
    ob_start();
    include_once('navbar.php');       
?>
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
    ?>
</div>
<div class="container-fluid nonfeestudData">
	<div class="row">
        <div class="col-md-10">
            <h4>Non-Fee Challans</h4> 
        </div>        
    </div>
    <div class="col-md-12 p-r-0 p-l-0 table-responsive">
        <form id="stud_details" method="post" action="sql_actions.php">
            <table class="table table-bordered ononfeepayment" cellspacing="0">
                <tr>
                    <th>S.No</th>
                    <th>Student Name</th>
                    <th>Class</th>
                    <th>Section</th>
                    <th>Semester</th>
                    <th>FeeType</th>
                    <th>Amount</th>
                    <th>Action</th>
                </tr>
            </table>
        </form>
    </div>
    <!-- <div class="pagination"><p>Page:</p></div> -->
</div>
<hr>
<div class="container-fluid">
    <div class="table-responsive">
        <button type="button" class="btn button1" data-toggle="collapse" data-target="#list">+ Non-fee Challans Paid</button>
        <div id="list" class="collapse">
            <table class="table table-bordered opayment1" cellspacing="0" width="400">
                <?php
                    $paiddata = sqlgetresult('SELECT "feeGroup","studentId","studentName","challanNo","total","updatedOn","feename" FROM nonfeechallandata WHERE "challanStatus" = 1 AND "parentId" = \''.$_SESSION['uid'].'\' AND "visible" = \'1\'  ' , true);          

                    $challanData = array();
                   $total =0;
                   $tot = 0;	
                   $challanNo  = '';
                   $feeData = array();
                   $count = $paiddata == '' ? 0 : count($paiddata);
                   if($count != 0) {
                       foreach ($paiddata as $k => $data) {
                           $challanData[$data['challanNo']]['studentName'] = $data['studentName'];                          
                           $challanData[$data['challanNo']]['studentId'] = $data['studentId'];
                           $challanData[$data['challanNo']]['challanNo'] = $data['challanNo'];
                           $challanData[$data['challanNo']]['org_total'][] = $data['total'];   
                           $challanData[$data['challanNo']]['updatedOn'] = $data['updatedOn']; 
                           $challanData[$data['challanNo']]['feename'][] = $data['feename'];                     
                       }
                       $data =  array();
                       foreach ($challanData as $feeData) {
                           $feeData['fee'] = array_sum($feeData['org_total']); 
                           $data[] = $feeData;
                       }    
                   } else {
                       $data = array();
                   }
                ?>

                <tr>
                    <th>S.No</th>
                    <th>Student ID</th>
                    <th>Student Name</th>
                    <th>Challan Number</th>
                    <th>FeeType</th>
                    <th>Amount Paid</th>
                    <th>Action</th>
                </tr>
                <?php
                $i = 1;
                    if(count($data) > 0) {
                    foreach($data AS $data) {
                        // print_r($data);
                        $date = date('dmY', strtotime($data['updatedOn']));
                        $pdfpath = BASEURL.'receipts/'.$date.'/'.str_replace('/', '', trim($data['challanNo'])).'.pdf';
                ?>
                    <tr>
                        <td><?php echo $i ;?></td>
                        <td><?php echo $data['studentId'] ;?></td>
                        <td><?php echo $data['studentName'] ;?></td>
                        <td><?php echo $data['challanNo'] ;?></td>
                        <td><?php echo implode(',',$data['feename']) ;?></td>
                        <td class='text-right'><?php echo $data['fee'] ;?></td>                        
                        <td><a href="<?php echo $pdfpath;?>" target="_blank">Download Receipt</a></td>
                    </tr>
                <?php
                    $i++;
                    }
                } else {
                    echo "<td colspan='7'><center>No Paid Challan Data Available.</center></td>";
                }
                ?>
            </table>
        </div>
    </div>
</div>

<div class="row comment">
    
</div>

<!-- Pay Modal -->
<div class="modal fade" id="nonpayModal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Fee Challan - Payment</h4>
            </div>
            <div class="modal-body">
                <p>Please review the details of your challan and confirm to continue...</p>
                <div class="table-responsive">
                    <form method="post" id="studDataModal" action="sql_actions.php">
                        <div id="nonfeechallanData"></div>
                        <p class='error-msg' id="error-msg" style="display: none;">Please pay school fees before attempting this payment.</p>                                     
                        <div class="text-center">
                            <input type="hidden" name="otherfeestype" id="otherfeestype" value="5">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="button" name="addtocart" value="add" class="btn btn-info addtocart" id="paynonfeechallan">Add to Cart</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>       


<?php
    include_once('footer.php');
?>