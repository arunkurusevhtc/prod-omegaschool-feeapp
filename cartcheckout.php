<?php
ob_start();
include_once('navbar.php');
//$payment_id=100012;
//echo completeTransactionById($payment_id);

$paid=$_SESSION['uid'];
//exit;
$sname="";
$studentIds = sqlgetresult('SELECT "studentId" FROM getparentdata WHERE id = \''.$paid.'\' AND status = \'1\' AND deleted = \'0\'  GROUP BY "studentId"',true);
$sid="";
$studId="";
$num=0;
if(isset($_POST['studId']) && !empty($_POST['studId'])){
    //$studId = "MONT/190049";
    $studId = trim($_POST['studId']);
    $params=[];
    $params['studentId']=$studId;
    $notpaid=toGetChallanNotPaidCount($params);
    if($notpaid > 0){
        $_SESSION['error_msg'] = "<p class='error-msg'>Please pay school fees before attempting this payment.</p>";
        $num=0;
    }else{
        $cartdata = sqlgetresult('SELECT * FROM cartlistlunuf WHERE "studentId" =\'' . $studId . '\' AND deleted=0 AND status=0',true);
        $num=count($cartdata);
        if($num > 0){
            $sname=$cartdata[0]['studentName'];
        }
    } 
}

?>
<div class="col-md-12" id="msg">
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

<div class="container-fluid">
   <div class="row">
        <div class="col-md-1">&nbsp;</div>
        <div id="topup-container" class="col-md-10">
          <h4 class="crdtoph">Cart - Checkout</h4>
          <form method="post" name="studfltr" action="">
            <div class="form-group row">
                <div class="col-md-1">
                    <label class="control-label" for="email">Student Id: </label>
                </div>
                <div class="col-md-3">
                    <select class="form-control nonfeestudid" name="studId" id="studId" required onchange="document.studfltr.submit();">
                        <option value="">-Select-</option>
                        <?php

                            foreach ($studentIds as $vals) {
                                if($studId==$vals['studentId']){
                                    $selct="selected='selected'";
                                }else{
                                    $selct="";
                                }
                                echo "<option value='".$vals['studentId']."' ".$selct.">".$vals['studentId']."</option>";
                            }
                        ?>
                    </select>
                </div>
                <div class="col-md-6">
                <?php if($sname) { ?>
                <label class="control-label" for="email">Student Name:</label>
                 <?php echo $sname; ?>
               <?php } ?>
               </div>
            </div>
           </form> 
           <?php 
           if(!empty($studId)){
           if($num > 0){
             

           ?>
         <div class="form-group">
                <form method="post" id="studDataModal" action="sql_actions.php">
                    <input type="hidden"  name="stId" id="stId" value="<?php echo $studId; ?>">
                <div class="table-responsive">

                 <table class="table table-bordered admintab dataTableChk">
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Type</th>
                            <th>Fee Type</th>
                            <th>Amount</th>
                            <th>Action</th>
                       </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        $total=0;
                        if($num > 0){ 
                           foreach($cartdata AS $data) {

                            $qy=$data['quantity'];
                            $maxqy=$data['maxquantity'];

                            if($maxqy){
                                $lpqy=$maxqy;
                            }else{
                                $lpqy=5;
                            }
                            
                            $id=$data['id'];
                            $type=$data['type'];
                            if($type=='Transport' || $type=='Non-Fee With Challan' || $type=='Common Non-Fee'){
                              $amt=$data['challanamount'];
                            }else{
                              $amt=$data['amount'];
                            }

                            $singleamt=$data['amount'];

                            if($qy){
                               $amt=$qy*$amt;
                            }

                            $total+=$amt;

                            $sid=$data['sid'];

                            $qid="qty-".$id;

                            $txtamt="txtamt_".$id;
                            $totamt="totamt_".$id;

                            if($type=='Common Non-Fee'){
                                $ftypeid=$data['feetypeid'];
                                $ftype=getEventNamebyid($ftypeid);
                                $data['feetypename']=$ftype;

                            }

                            ?>
                           <tr id="<?php echo "row-".$id; ?>">
                                <td><?php echo $i ;?></td>
                                <td><?php echo $type; ?></td>
                                <?php if($type=='Transport' || $type=='Non-Fee With Challan') { ?>
                                    <td><?php echo $data['challanNo']; ?></td>
                                <?php } else{ ?>    
                                <td><?php echo $data['feetypename']; ?><?php if($qy) { ?> - <select name="<?php echo $qid; ?>" id="<?php echo $qid; ?>" class="product-quantity" data-id="<?php echo $id; ?>" data-stid="<?php echo $sid; ?>" data-pid="<?php echo $paid; ?>"  data-single-amt="<?php echo $singleamt; ?>"  style="width:50px; height:30px">
                                    <?php for($q=1;$q<=$lpqy;$q++) { ?>
                                    <option value="<?php echo $q; ?>" <?php if($q==$qy) { ?> selected="selected"<?php } ?>><?php echo $q; ?></option>
                                   <?php } ?>
                                </select>
                                <input type="hidden" name="<?php echo $txtamt; ?>" id="<?php echo $txtamt; ?>" value="<?php echo $amt; ?>">
                                
                                <?php } ?></td>
                                <?php } ?>
                                <td class='text-right'><span id="<?php echo "amt-".$id; ?>"><?php echo $amt; ?></span></td>                        
                                <td style="text-align:center;"><a data-id="<?php echo $id; ?>" data-stid="<?php echo $sid; ?>" data-pid="<?php echo $paid; ?>"  data-amt="<?php echo $amt; ?>" data-totamt="<?php echo $total; ?>" class="text-danger btnRemoveAction" id="btnRemoveAction" style="cursor: pointer;"><i class="fa fa-times" aria-hidden="true"></i></a></td>
                        </tr>
                        <?php 
                              $i++;
                            }
                            if($total > 0) {
                            ?>
                            <tr>
            <td colspan="3" align="right"><input type="hidden" name="totalamt" id="totalamt" value="<?php echo $total; ?>">Total:</td>
            <td align="right" id="render-total"><strong><?php echo "₹ ".$total; ?></strong></td>
            <td></td>
        </tr>
                            <?php
                        }
                         }else { ?>
                        <tr>
                           <td colspan='6'><center>No Data Available.</center></td>
                         </tr>  
                        <?php } ?>    
                    </tbody>
                </table>                              
                <div class="text-center" id="processbtn">
                    <p>
                        <button type="button" class="btn btn-default" data-dismiss="modal" onclick="window.location.href='cartcheckout.php'">Close</button>
                        <button type="submit" name="cartchkout" id="cartchkout" value="pay" class="btn btn-primary">Confirm Payment</button>
                   </p>
                </div>
                    
                </div>
                </form>
            </div>
            <?php
        }else{
            ?>
            <div class="form-group well">
                
                <p><center><button type="button" class="btn btn-primary" data-dismiss="modal" onclick="window.location.href='uniformfee.php'">Click Here to Add</button></center></p>
            </div>
        <?php    
        }
    }
            ?>

            </div>
            <div class="col-md-1">&nbsp;</div>
    </div>        

</div>

<!-- <div class="onlinepay">
    <p>There is no online fee payment facility for new student at this time.</p>
    <p>Online payment facility is closed.Please approach school for making fee payment.</p>
</div> -->
<div class="row comment">
    
</div>
<!-- Add Student Modal -->

<?php
    include_once('footer.php');
?>