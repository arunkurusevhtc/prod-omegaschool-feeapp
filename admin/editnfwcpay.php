<?php
include_once('admnavbar.php');

$ref=isset($_REQUEST['ref'])?trim($_REQUEST['ref']):"";
$logid="";
if($ref){
   $logid=str_replace("NFWC", "", $ref);
}
//exit;
$sid="";
$studId="";
$challanids="";
$pstatus="";

$payStatus=array("Ok"=>"Completed","F"=>"Failed","C"=>"Canceled");
$challanData=array();
if(!empty($logid)){
    $pData = sqlgetresult('SELECT id AS plid,receivedamount,challanids,sid,"transStatus" FROM tbl_partial_nfwpayment_log WHERE id=\''.$logid.'\' and ("transStatus"!=\'Ok\' OR "transStatus" IS NULL) AND deleted=0 LIMIT 1',true);
    $numpData=count($pData);
    if($numpData > 0){
       $siduniq=$pData[0]['sid'];
       $receivedamount=trim($pData[0]['receivedamount']);
       $pstatus=trim($pData[0]['transStatus']);
       $challanids=trim($pData[0]['challanids']);
       $challanData = sqlgetresult('SELECT * FROM  nonfeechallandata WHERE "challanNo"=\''.$challanids.'\' AND ("challanStatus" = \'0\' OR "challanStatus" = \'2\')  LIMIT 1',true);
       $num=count($challanData);
        if($num > 0){
            
            $studId=trim($challanData[0]['studentId']);
            $stream=trim($challanData[0]['stream']);
            $class=trim($challanData[0]['class']);
            $term=trim($challanData[0]['term']);
            $studentName=trim($challanData[0]['studentName']);
            $studentId=trim($challanData[0]['studentId']);
            $academic_yr=trim($challanData[0]['academic_yr']);
            $steamname=trim($challanData[0]['steamname']);
            $pid=trim($challanData[0]['parentId']);
        }
        else{
            $_SESSION['error_msg']="<p class='error-msg'>Already paid for the challan ".$challanids."</p>";
        }
    }else{
       $_SESSION['error_msg']="<p class='error-msg'>Can't change the status of this payment - NFWC".$logid."</p>";
    }
    
}

?>


<div class="container-fluid contentcheque">
   <div class="row">
        <div class="col-md-2">&nbsp;</div>
        <div id="topup-container" class="col-md-8">
             <div class="col-md-12">
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
          <h4 class="crdtoph">Update NON-Fee Challan Payment - Online</h4>
           <?php 
           if(!empty($studId)){
           if($num > 0){
           ?>
         <div class="form-group well">
                <form method="post" id="studDataModal" action="adminactions.php">
                    <input type="hidden" name="plogid" id="plogid" value="<?php echo $logid; ?>">
                <div class="row">
                    <input type="hidden" name="studentId" id="studentId" value="<?php echo $studentId; ?>">
                    <input type="hidden" name="challanNo" id="challanNo" value="<?php echo $challanids; ?>">
                    <input type="hidden" name="amount" id="amount" value="<?php echo $receivedamount; ?>">
                    <input type="hidden" name="s_id" value="<?php echo $sid; ?>" />
                    <div class="col-lg-6">
                        <label for ="ftype" class="control-label">Transaction Amount: ₹ </label>
                        <?php echo $receivedamount; ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <label for ="ftype" class="control-label">Pay Type</label>
                        <select class="form-control" name="ptypechk" id="ptypechk" required="required">
                            <option value="Online" default>Online</option>
                        </select>
                    </div>
                    <div class="col-lg-6 Online">
                        <label for ="des" class="control-label">Transaction Status</label>
                        <select class="form-control" name="tstatus" id="tstatus" required="required">
                            <option value="" default>-Select-</option>
                            <?php
                            foreach($payStatus as $key=>$val){
                                ?>
                                <option value="<?php echo $key; ?>" <?php if($key==$pstatus) { ?>selected<?php } ?>><?php echo $val; ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 Online">
                        <label for ="des" class="control-label">Bank</label>
                        <input type="text" id="bank" name="bank" placeholder="Bank Name" class="form-control" value="Atom" readonly>
                    </div>
                    <div class="col-lg-6">
                        <label for ="des" class="control-label">Transaction Number</label>
                        <input type="text" id="paymentmodetrans" name="paymentmodetrans" placeholder="Transaction Number" class="form-control" required="required">
                    </div>
                </div>
                <div class="row">
                <div class="col-lg-6">
                        <label for ="des" class="control-label">Date</label>
                        <input type="text" id="paiddate" name="paiddate" placeholder="Paid Date" class="form-control datepicker" required="required">
                    </div>    
                    <div class="col-lg-6">
                        <label for ="remarks" class="control-label" >Remarks</label>
                        <textarea placeholder="Remarks" class="form-control remarks" name="remarks" maxlength="250" required="required"></textarea>
                    </div>
                </div>
                <div class="row text-center" style="padding-top: 20px;">
                    <button type="button" class="btn btn-default" data-dismiss="modal" onclick="window.location.href='nfwcreport.php'">Close</button>
                    <button type="submit" name="paynfwc_adm" value="paynfwcadm" id="paynfwc_adm" class="btn btn-primary">Confirm Payment</button>
                </div>
 
                </form>
            </div>
            <?php
        }
            ?>
            
        <?php    
    }
            ?>

            </div>
            <div class="col-md-2">&nbsp;</div>
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