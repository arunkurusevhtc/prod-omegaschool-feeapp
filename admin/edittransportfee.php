<?php 
ob_start();
require_once('admnavbar.php');
/*$chldg="CBSE2019/214628";
$sid="5772";
createPDF_by_feetype($sid, $chl,$chequedd,$type_ids);

exit;*/

/*$fromwhere = 'Receipt';
$cnum='MONT2019/216361';
$sid="MONT/190049";
flattableentry_feetype($cnum, $sid, $fromwhere);

exit;
*/
$ptypes=array("Online","Cheque");
$statuses=array("Ok","C");
$pid = (isset($_REQUEST['id']) && !empty($_REQUEST['id']))?trim($_REQUEST['id']):"";
if(!empty($pid)){

   //echo $sql = 'SELECT * FROM otherfeesreport WHERE id = \'' . $pid . '\'';
    $sql = 'SELECT * FROM otherfeesreport WHERE id = \'' . $pid . '\'';
    $query = sqlgetresult($sql, true);
    $num=count($query);
    if($num > 0){
       // echo "<pre>";
    //print_r($query);
    //exit;
      foreach($query as $key => $q){
            $sid=trim($q['studentId']);
            $pid=trim($q['parentId']);
            $amount=trim($q['amount']);
            $transStatus=isset($q['transStatus']) ? trim($q['transStatus']):"";
            $transNum=isset($q['transNum']) ? trim($q['transNum']):"";
            $transId=isset($q['transId']) ? trim($q['transId']):"";
            $remarks=isset($q['remarks']) ? trim($q['remarks']):"";
            $transDate=isset($q['transDate']) ? trim($q['transDate']):date("Y-m-d");
            $returnCode=isset($q['returnCode']) ? trim($q['returnCode']):"";

            $p_type=isset($q['pay_type']) ? trim($q['pay_type']):"";
            //$feeconfigid=trim($q['feeconfigid']);

            if($transId === "null"){
              $transId="";
            }
            $payid=trim($q['pyid']);
            $id=trim($q['id']);

            $arrdt=explode("_",$returnCode);

            //$p_type=isset($arrdt[0])?$arrdt[0]:"";
            $bankname=isset($arrdt[1])?$arrdt[1]:"";
            $selchq="";
            $selon="";
            $dispchq="none";
            $disponl="none";

            if($p_type=='Cheque'){
                $selchq="selected='selected'";
                $txtlabl="Cheque/DD Number";
                $dispchq="block";
            }else{
                $disponl="block";
                $selon="selected='selected'";
                $txtlabl="Transaction Number";
            }

            if($amount==0){
            $chked=" checked='checked'";
        }else{
            $chked="";
        }

            

            //$group_id = trim($q['feeGroup']);
            $challanNo = trim($q['challanNo']);
        }
     }else{
        $_SESSION['errorcheque']="<p class='error-msg'>Payment data not found for the given payment id.</p>";
     }
  }else{
    header("location:managetransportreport.php");
    ob_end_flush();
  }
?>
<div class="container_fluid">

        <div class="col-sm-2 col-md-3 col-lg-3"></div>
        <div class="col-sm-8 col-md-6 col-lg-6">
            <div class="errormessage">
            <?php
                if(isset($_SESSION['successcheque'])) {
                   echo $_SESSION['successcheque'];
                   unset($_SESSION['successcheque']);
                } elseif(isset($_SESSION['errorcheque'])) {
                   echo $_SESSION['errorcheque'];
                   unset($_SESSION['errorcheque']);
                }
                ?>
            </div>
            <div class="contentcheque">
                <p class="heading">Edit Payment - <?php echo $challanNo; ?></p>
                <div class="main">
                        <?php if(!empty($sid)) { ?>
                        <form method="post" id="studDataModal1" action="adminactions.php">
                            <input type="hidden" class="form-control" id="pid" name="pid" value="<?php echo $pid; ?>">
                            <input type="hidden" class="form-control" id="sid" name="sid" value="<?php echo $sid; ?>">
                            <input type="hidden" class="form-control" id="payid" name="payid" value="<?php echo $payid; ?>">
                            <input type="hidden" class="form-control" id="id" name="id" value="<?php echo $id; ?>">
                            <input type="hidden" class="form-control" id="challanNo" name="challanNo" value="<?php echo $challanNo; ?>">
                            <div class="challandetailsNew">
                            <div class="col-lg-12 well">
                                <div class="form-group row">
                                    <div class="col-lg-6">
                                        <label for="ftype" class="control-label">Pay Type</label>
                                        <select class="form-control" name="ptype" id="ptype" onchange="tohandlechange(this,60)" required="required">
                                        <option value="" default="">--SELECT--</option>
                                        <option value="Online" <?php echo $selon; ?>>Online</option>
                                        <option value="Cheque" <?php echo $selchq; ?>>Cheque</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-6 chequebank" style="display: <?php echo $dispchq; ?>">
                                        <label for="des" class="control-label">Bank</label>
                                         <select class="form-control" name="cbank" id="cbank">
                                           <option value="">--Select--</option>
                                           <?php 
                                           foreach($banks as $k => $bank){
                                           ?>
                                           <option value="<?php echo $bank; ?>" <?php if($bank==$bankname) { ?> selected="selected"<?php } ?>><?php echo $bank; ?></option>
                                       <?php } ?>
                                       </select>
                                    </div>
                                    <div class="col-lg-6 onlinebank" style="display: <?php echo $disponl; ?>">
                                        <label for="des" class="control-label">Bank</label>
                                         <input type="text" id="bank" name="bank" placeholder="Bank Name" class="form-control" value="Atom" readonly="readonly">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-lg-6">
                                        <label for="des" class="control-label">Transaction/Cheque/DD Number Number</label>
                                        <input type="text" id="paymentmodetrans" name="paymentmodetrans" placeholder="Transaction Number" class="form-control" value="<?php echo $transId; ?>">
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="des" class="control-label">Amount</label>
                                        <input type="text" readonly="" id="amount" name="amount" required="" placeholder="Amount" class="form-control" value="<?php echo $amount; ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-lg-6">
                                        <label for="des" class="control-label">Date</label>
                                        <input type="text" id="paiddate" name="paiddate" placeholder="Paid Date" class="form-control datepicker" required="required" value="<?php echo $transDate; ?>">
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="remarks" class="control-label">Remarks</label>
                                        <textarea placeholder="Remarks" class="form-control remarks" name="remarks" maxlength="250" required="required"><?php echo $remarks; ?></textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                <div class="col-lg-6">
                                        <label for="des" class="control-label">Status</label>
                                         <select class="form-control" name="status" id="status" required="required">
                                           <option value="">--Select--</option>
                                           <?php 
                                           foreach($statuses as $k => $status){
                                           ?>
                                           <option value="<?php echo $status; ?>" <?php if($status==$transStatus) { ?> selected="selected"<?php } ?>><?php echo $status; ?></option>
                                       <?php } ?>
                                       </select>
                                </div>
                                <div class="col-lg-6">
                                    <input type="checkbox" class="fullwaived" id="fullwaived" name="fullwaived" value="full waiver applied" <?php echo $chked; ?>>
                                    <label for="fullwaived" class="control-label">100% Waiver</label>
                                </div>
                            </div>
                            </div>
                            <div class="text-center">
                                <button type="button" id="closepay_ftype" class="btn btn-default" name='close' onclick="window.location.href='managetransportreport.php'">Close</button>
                                <button type="submit" name='edit_fee_trans_pay' value="confirm" class="btn btn-primary" >Submit</button>
                            </div>
                        </form>
                    <?php } ?>
                    </div>
                </div>
            </div>
            <div class="col-sm-2 col-md-3 col-lg-3"></div>
</div>

</div>
<div class="row comment">
</div>

<?php
include_once(BASEPATH.'footer.php');
?>