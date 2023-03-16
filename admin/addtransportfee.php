<?php 
require_once('admnavbar.php');


/*$chl="CBSE2019/214628";
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
$html="";
$sid="";
$cnum="";
$term="";
$academicyear="";
$stream="";
$class="";
$snum="";
$cno="";
$classid="";
$streamid="";
$amount=0;
$tamount=0;
$pid="";
$waivedAmt=0;
if(isset($_REQUEST['getchallan_new']) && !empty($_REQUEST['getchallan_new'])){
  $cno = (isset($_REQUEST['challanno']) && !empty($_REQUEST['challanno']))?trim($_REQUEST['challanno']):"";
  if(!empty($cno)){
     $query = sqlgetresult('SELECT * FROM chequedddata WHERE "challanNo" =\'' . $cno . '\' AND "challanStatus" = \''. 0 .'\' AND "studStatus"=\'Transport.Fee\' ' ,true);
     $num=count($query);
     if($num > 0){
        foreach($query as $key => $q){


            $sid=trim($q['studentId']);
            $s_uid=trim($q['sid']);
            $cnum=trim($q['challanNo']);
            $term=trim($q['term']);
            $academicyear=trim($q['academicYear']);
            $stream=trim($q['stream']);
            $class=trim($q['classList']);
            $snum=trim($q['studentName']);
            $cno=trim($q['challanNo']);
            $classid=trim($q['class_list']);
            $streamid=trim($q['streamname']);
            $section=trim($q['section']);

            $pid=trim($q['parentId']);

            $amount+=$q['org_total'];

        }

        $waived=getwaiveramountbychallan($cno);
        $waivedAmt=$waived['waiver_total'] ?? 0;
        
        if($waivedAmt > 0){
          $tamount=$amount-$waivedAmt;
        }else{
          $tamount= $amount;
        }

        if($tamount==0){
            $chked=" checked='checked'";
        }else{
            $chked="";
        }


     }else{
        $_SESSION['errorcheque']="<p class='error-msg'>Given challan number doesnot exist.</p>";
     }
  }else{
    $_SESSION['errorcheque']="<p class='error-msg'>Please enter the challan number</p>";
  }
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
                <p class="heading">Add Transport Fee - Challan Wise</p>
                <div class="main">
                    <input name="id" type="hidden" value="<?php echo $res['id'];?>" />
                    <form id="getchallan_new" method="post">
                        <div class="form-group">
                                <label for ="challanno" class="control-label">Challan No</label>
                                <input type="text" id="challanno" name="challanno" required placeholder="Challan No" value="<?php echo $cno; ?>" class="form-control">
                        </div>
                        
                        <div class="form-group text-center">
                            <button type="button" class="btn btn-default" name='close' onclick="window.location.href='managetransportreport.php'">Close</button>&nbsp;<button type="submit" value="new" name="getchallan_new" id="getchallan_new" class="btn btn-primary text-center">Submit</button>
                        </div>
                    </form>
                        <?php if(!empty($sid)) { ?>
                        <form method="post" id="studDataModal1" action="adminactions.php">
                            <div class="challandetailsNew">
                                <div  class="feetab1">
                                    <div class="form-group row">
                                        <input type="hidden" class="pid" id="pid" name="pid" value="<?php echo $pid; ?>">
                                        <input type="hidden" class="sid" id="sid" name="sid" value="<?php echo $sid; ?>">
                                        <input type="hidden" class="section" id="section" name="section" value="<?php echo $section; ?>">
                                        <input type="hidden" class="s_uid" id="s_uid" name="s_uid" value="<?php echo $s_uid; ?>">
                                        <input type="hidden" class="cnum" id="cnum" name="cnum" value="<?php echo $cnum; ?>">
                                        <input type="hidden" class="term" id="term" name="term" value="<?php echo $term; ?>">
                                        <input type="hidden" class="class" id="class" name="class" value="<?php echo $class; ?>">
                                        <input type="hidden" class="stream" id="stream" name="stream" value="<?php echo $stream; ?>">
                                        <input type="hidden" class="academicyear" id="academicyear" name="academicyear" value="<?php echo $academicyear; ?>">
                                        <div class="col-lg-2">
                                            <label for ="snum" class="control-label">Student Name: </label>
                                        </div>
                                        <div class="col-lg-4">
                                            <span class="snum"><?php echo $snum; ?></span>
                                            <input type="hidden" class="snum" id="snum" name="snum">
                                        </div>
                                         <div class="col-lg-2">
                                            <label for ="studid" class="control-label">Challan Number: </label>
                                        </div>
                                         <div class="col-lg-4">
                                            <span class="cno"><?php echo $cnum; ?></span>
                                            <input type="hidden" class="cno" id="cno" name="cno" value="<?php echo $cnum; ?>">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-2">
                                            <label for ="streamid" class="control-label">Stream: </label>
                                        </div>
                                        <div class="col-md-4">
                                            <span class="streamid"><?php echo $streamid; ?></span>
                                            <input type="hidden" class="streamid" id="streamid" name="streamid" value="<?php echo $streamid; ?>">
                                        </div>
                                        <div class="col-md-2">
                                            <label for ="classid" class="control-label">Class: </label>
                                        </div>
                                        <div class="col-md-4">
                                            <span class="classid"><?php echo $classid; ?></span>
                                            <input type="hidden" class="classid" id="classid" name="classid" value="<?php echo $classid; ?>">
                                        </div>
                                         
                                         
                                    </div>
                                   
                            </div>
                            <div class="clear:both;">&nbsp;</div>
                            <div class="col-lg-12 well">
                                <div class="form-group row">
                                    <div class="col-lg-6">
                                        <label for="ftype" class="control-label">Pay Type</label>
                                        <select class="form-control" name="ptype" id="ptype" onchange="tohandlechange(this,60)" required="required">
                                        <option value="">--SELECT--</option>
                                        <option value="Online" selected="selected">Online</option>
                                        <option value="Cheque">Cheque</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-6 chequebank" style="display:none;">
                                        <label for="des" class="control-label">Bank</label>
                                         <select class="form-control" name="cbank" id="cbank">
                                           <option value="">--Select--</option>
                                           <?php 
                                           foreach($banks as $k => $bank){
                                           ?>
                                           <option value="<?php echo $bank; ?>"><?php echo $bank; ?></option>
                                       <?php } ?>
                                       </select>
                                    </div>
                                    <div class="col-lg-6 onlinebank" style="display:block;">
                                        <label for="des" class="control-label">Bank</label>
                                         <input type="text" id="bank" name="bank" placeholder="Bank Name" class="form-control" value="Atom" readonly="readonly">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-lg-6">
                                        <label for="des" class="control-label">Transaction/Cheque/DD Number Number</label>
                                        <input type="text" id="paymentmodetrans" name="paymentmodetrans" placeholder="Transaction Number" class="form-control" required="required"> 
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="des" class="control-label">Amount</label>
                                        <input type="text" readonly="" id="amount" name="amount" required="" placeholder="Amount" class="form-control" value="<?php echo $tamount; ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-lg-6">
                                        <label for="des" class="control-label">Date</label>
                                        <input type="text" id="paiddate" name="paiddate" placeholder="Paid Date" class="form-control datepicker" required="required" value="<?php echo date("Y-m-d"); ?>">
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="remarks" class="control-label">Remarks</label>
                                        <textarea placeholder="Remarks" class="form-control remarks" name="remarks" maxlength="250" required="required"></textarea>
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
                                           <option value="<?php echo $status; ?>"><?php echo $status; ?></option>
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
                                <button type="button" id="closepay_ftype" class="btn btn-default" name='close' value="confirm">Close</button>
                                <button type="submit" name='fee_pay_adm' value="confirm" class="btn btn-primary" >Submit</button>
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