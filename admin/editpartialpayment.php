<?php
include_once('admnavbar.php');
$methods= array('atom','razorpay');
$logid=isset($_REQUEST['logid'])?trim($_REQUEST['logid']):"";

//exit;
$sid="";
$studId="";
$feeTotal=0;
$waivedAmt=0;
$advpaid=0;
$balance=0;
$minimum=0;
$challanNos=[];
$partialmin = 0;
$sfsfees = array();
$schoolutilityies = array();
$eventid = array();
$challanids="";
$paygroupamt=[];
$paygroup=[];
//$tx="full-single";
$tx="partial";
$pstatus="";

$payStatus=array("Ok"=>"Completed","F"=>"Failed","C"=>"Canceled");

$ptxt="";
$chlstatus=array();
$challanData=array();
$paymentmethod='atom';
if(!empty($logid)){
    //echo 'SELECT * FROM challanDatanew WHERE "studentId" =\'' . $studId . '\' AND ("challanStatus" = \'0\' OR "challanStatus" = \'2\') AND "academicYear" >=7  ORDER BY cid ASC';
    $pData = sqlgetresult('SELECT id AS plid,receivedamount,balance,sid,"transStatus",paymentmethod FROM tbl_partial_payment_log WHERE id=\''.$logid.'\'  AND paymentmode=\'Online\' AND deleted=0 LIMIT 1',true);
    $numpData=count($pData);
    if($numpData > 0){
       $siduniq=$pData[0]['sid'];
       $receivedamount=trim($pData[0]['receivedamount']);
       $pstatus=trim($pData[0]['transStatus']);
       $paymentmethod=trim($pData[0]['paymentmethod']);
       $challanData = sqlgetresult('SELECT * FROM  challanDatanew WHERE sid=\''.$siduniq.'\' AND ("challanStatus" = \'0\' OR "challanStatus" = \'2\') AND "academicYear" >=6  ORDER BY cid ASC',true);
    }
    //$challanData = sqlgetresult('SELECT pl.id AS plid,pl.receivedamount,pl.balance,s.* FROM  tbl_partial_payment_log pl JOIN challanDatanew s ON(pl.sid=s.sid) WHERE pl.id=\''.$logid.'\' AND pl.paymentmode=\'Online\' AND pl.deleted=0 AND (s."challanStatus" = \'0\' OR s."challanStatus" = \'2\') AND s."academicYear" >=6  ORDER BY s.cid ASC',true);
    $num=count($challanData);
    if($num > 0){
        
        $studId=trim($challanData[0]['studentId']);
        $stream=trim($challanData[0]['stream']);
        $class=trim($challanData[0]['class']);
        $term=trim($challanData[0]['term']);
        $academic_yr=trim($challanData[0]['academic_yr']);
        foreach ($challanData as $k => $value) {
            $chal_id=trim($value['challanNo']);
            $challanNos[]=$chal_id;
            $studentName = trim($value['studentName']);
            $studentId = trim($value['studentId']);
            $steam = $value['steam'];
            $steamname = $value['steamname'];
            $sid = $value['sid'];
            $partialmin = $value['partialmin'];
            $org_total=$value['org_total'];
            $cstatus=$value['challanStatus'];
            if($cstatus==2){
               $chlstatus[]="partial";
            }
            $pid=$value['parentId'];
            $feeGroup=$value['feeGroup'];
            $feeType=$value['feeType'];           
            $feeTotal+=$org_total;
        }
        $challanNos = array_unique($challanNos);       
    }
    else{
        /*$qry='SELECT id AS sid FROM studentcheck WHERE "studentId" = \''.$studId.'\'';
        $details = sqlgetresult($qry,true);
        $sid=$details[0]['sid'];*/
        $_SESSION['error_msg']="<p class='error-msg'>No data found! Please try again later.</p>";
    }
}
/* Waiver Amount */
$ctot=0;
$wtot=0;
$cNums=count($challanNos);
$partialpaid=0;
if($cNums > 0){
    foreach($challanNos as $challan){
        $wdata=getwaiveramountbychallan($challan);
        if(isset($wdata['waiver_total']) && !empty($wdata['waiver_total'])){
            $waivedAmt+=$wdata['waiver_total'];
        }
        $partialpaid+=getAmtPaidbychallan($challan);
        
    }

 $challanids=implode(",", $challanNos);
}
$partialActive=0;

$where="";
if(!empty($sid)){
  $balance=toGetAvailableBalance($sid);

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
          <h4 class="crdtoph">Update Partial Payment - Online</h4>
           <?php 
           if(!empty($studId)){
           if($num > 0){
             
            //print_r($paygroup);

             //print_r($paygroupamt);
//exit;
           ?>
         <div class="form-group well">
                <form method="post" id="studDataModal" action="adminactions.php">
                    <input type="hidden" name="plogid" id="plogid" value="<?php echo $logid; ?>">
                    <input type="hidden" name="pid" id="pid" value="<?php echo $pid; ?>">
                <div class="table-responsive">
                        <div id="challanData">
                            <input type="hidden" name="studentId" id="studentId" value="<?php echo $studentId; ?>">
                            <input type="hidden" name="s_id" value="<?php echo $sid; ?>" />
                            
  
                            <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <td><label>Name : </label>&nbsp;<?php echo $studentName; ?></td>
                                        <td><label>ID : </label>&nbsp;<?php echo $studentId; ?></td>
                                        <td><label>Reference Number : </label>&nbsp;<?php echo "REF".$logid; ?></td>
                                    </tr>
                                    <tr>
                                        <td><p class="tot">Total Challan Value: <span id="f_tot"><?php echo $feeTotal; ?></span></p></td>
                                        <td><p class="tot">Waived Total: <span><?php echo $waivedAmt; ?></span></p></td>
                                        <td><p class="tot">Paid So Far: <span><?php echo $partialpaid; ?></span></p></td>
                                    </tr>
                                    
                                     <tr>
                                        <td colspan="3"><p class="tot">Net Due: <span id="grand_tot"><?php
                                        $wtotaa = $feeTotal-$waivedAmt;
                                        echo $tot=$feeTotal-$waivedAmt-$partialpaid;

                                      ?></span></p></td>
                                    </tr>
                                    <tr>
                                        <td colspan="3"><p class="tot">Advance Paid: <span><?php echo $balance; ?></span></p></td>
                                    </tr>
                                    <tr>
                                        <td colspan="3"><div><label>Transaction Amount : </label>&nbsp;<input type="number" name="partialamt" id="partialamt" readonly="readonly" value="<?php echo $receivedamount; ?>" class="tot"></div></td>
                                    </tr>
                                    </tbody>
                                </table>
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
                        <input type="text" id="bank" name="bank" placeholder="Bank Name" class="form-control" value="<?php echo $paymentmethod; ?>" readonly>
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
                    <button type="button" class="btn btn-default" data-dismiss="modal" onclick="window.location.href='managepartialpayment.php'">Close</button>
                    <button type="submit" name="paytotaledit_adm" value="paytotaledit" id="paytotaledit_adm" class="btn btn-primary">Confirm Payment</button>
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