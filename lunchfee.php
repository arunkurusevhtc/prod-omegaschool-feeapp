<?php 
ob_start();
require_once('navbar.php');
date_default_timezone_set("Asia/Kolkata");    
$cur_data = time();
$date = date('Y-m-d h:i:s');

$type = (isset($_REQUEST['type']) && !empty($_REQUEST['type']))?trim($_REQUEST['type']):"lunch";
$title=ucfirst($type);
$studentIds = sqlgetresult('SELECT "studentId" FROM getparentdata WHERE id = \''.$_SESSION['uid'].'\' AND status = \'1\' AND deleted = \'0\' GROUP BY "studentId" ',true);

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
    ?>
    </div>
    <div class="error_stdid"></div>
    <div class="row">
        <div id="topup-container" class="col-md-offset-3 col-md-5">
            <h4 class="crdtoph"><?php echo $title;  ?>&nbsp;Fee</h4>
            <form class="form-horizontal" action="sql_actions.php" method="post" id="addcartfrm">
                <div class="form-group">
                    <label class="control-label col-sm-5" for="email">StudentId: </label>
                    <div class="col-sm-7">
                        <select class="form-control lunchfeestudid" name="studId" id="studId"  required="readonly">
                            <option>-Select-</option>
                            <?php
                                foreach ($studentIds as $sid) {
                                    echo "<option value='".$sid['studentId']."'>".$sid['studentId']."</option>";
                                }
                            ?>
                        </select>
                    </div>
                </div>
                <input type="hidden" name="type" id="type" value="<?php echo $type; ?>">
                <input type="hidden" name="studentidforfee" id="studentidforfee" value="">
                <div class= "eventdetails">
                    <div class="form-group">
                        <label class="control-label col-sm-5" for="email">Fee Type: </label>
                        <div class="col-sm-7">
                         <select name="eventname"  class="eventname form-control" id="lunchnameid" required="readonly">
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
                    <input type="hidden" name="otherfeestype" id="otherfeestype" value="2">
                    <button type='button' name = "addtocart" value= "add" class='btn btn-info addtocart' id="paylunchfee">Add to Cart</button>
                </div>
            </form>
        </div>
    </div>
</div>  
<hr>
<div class="container-fluid">
    <div class="table-responsive">
        <button type="button" class="btn button1" data-toggle="collapse" data-target="#list">+Common&nbsp;<?php echo $title;  ?>-fee Paid</button>
        <div id="list" class="collapse">
            <table class="table table-bordered opayment1" cellspacing="0" width="400">
                <?php
                    $paiddata = sqlgetresult('SELECT *  FROM otherfeesreport WHERE "parentId" = \''.$_SESSION['uid'].'\' AND "transStatus" = \'Ok\' AND "stustatus" = \'1\' AND "deleted" = \'0\' AND typeid = \'2\' LIMIT 20',true);
                   $challanData = array();
                   $total =0;
                   $tot = 0;    
                   $challanNo  = '';
                   $feeData = array();
                   $count = $paiddata == '' ? 0 : count($paiddata);
                   if($count != 0) {
                       foreach ($paiddata as $k => $data) {
                           $challanData[$k]['studentName'] = $data['studentName'];
                           $challanData[$k]['transNum'] = $data['transNum'];
                           $challanData[$k]['studentId'] = $data['studentId'];
                           $challanData[$k]['challanNo'] = $data['challanNo'];
                           $challanData[$k]['org_total'] = $data['amount'];   
                           $challanData[$k]['updatedOn'] = $data['updatedOn']; 
                           $challanData[$k]['id'] = $data['id'];                     
                       }
                       $data =  $challanData;    
                   } else {
                       $data = array();
                   }
                ?>
                <tr>
                    <th>S.No</th>
                    <th>REF Number</th>
                    <th>Student ID</th>
                    <th>Student Name</th>
                    <th>Amount Paid</th>
                    <th>Action</th>
                </tr>
                <?php
                    $i = 1;
                    if(count($data) > 0) {
                    foreach($data AS $data) {
                    $datefolder = "lunch";
                    //$receiptname = trim($data['challanNo']);
                    $receiptname = trim($data['transNum']);
                    $pdfpath = BASEURL.'receipts/'.$datefolder.'/'.str_replace('/', '', trim($receiptname)).'.pdf';
                ?>
                    <tr>
                        <td><?php echo $i ;?></td>
                        <td><?php echo $data['transNum']; ?></td>
                        <td><?php echo $data['studentId']; ?></td>
                        <td><?php echo $data['studentName']; ?></td>
                        <td class='text-right'><?php echo $data['org_total']; ?></td>
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
<div class="modal fade" id="commentmodal" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg">
    <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">NOTE TO PARENTS</h4>
            </div>
            <div class="modal-body" style="padding: 0px 35px;margin: 15px 0">
                <div class="row comment"></div>
                <!-- <h4 class="addtitle">Please provide the following details from current challan.</h4> -->
            </div>
            <div class="modal-footer text-center">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<?php
  include_once('footer.php');
?>
