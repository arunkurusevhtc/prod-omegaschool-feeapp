<?php 
require_once('admnavbar.php');


/*$chl="CBSE2019/214628";
$sid="5772";
createPDF_by_feetype($sid, $chl,$chequedd,$type_ids);

exit;*/

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
if(isset($_REQUEST['getchallan_new']) && !empty($_REQUEST['getchallan_new'])){
  $cno = (isset($_REQUEST['challanno']) && !empty($_REQUEST['challanno']))?trim($_REQUEST['challanno']):"";
  if(!empty($cno)){
     $query = sqlgetresult('SELECT * FROM chequedddata WHERE "challanNo" =\'' . $cno . '\' AND "challanStatus" = \''. 0 .'\'',true);
     $num=count($query);
     if($num > 0){
        foreach($query as $key => $q){
            $sid=trim($q['studentId']);
            $cnum=trim($q['challanNo']);
            $term=trim($q['term']);
            $academicyear=trim($q['academicYear']);
            $stream=trim($q['stream']);
            $class=trim($q['classList']);
            $snum=trim($q['studentName']);
            $cno=trim($q['challanNo']);
            $classid=trim($q['class_list']);
            $streamid=trim($q['streamname']);

            $group_id = trim($q['feeGroup']);
            $type_id = trim($q['feeType']);
            if($group_id == 'LATE FEE'){
                //$feegroupname[] = $q['feeGroup'];
               // $feetypename[] = getFeeTypebyId($q['feeType']);
                 $group_id = $group_id;
                 $type =  getFeeTypebyId($type_id);
            }
            else{
                //$group = getFeeGroupbyId($group_id);
                $type=getFeeTypebyId($type_id);
                /* Fee Type*/
                $ptype="ptype_".$type_id;
                $cbank="cbank_".$type_id;
                $bank="bank_".$type_id;
                $paymentmode="paymentmode_".$type_id;
                $paymentmodetrans="paymentmodetrans_".$type_id;
                $amount="amount_".$type_id;
                $paiddate="paiddate_".$type_id;
                $remarks="remarks_".$type_id;
                $sendmail="sendmail_".$type_id;
                $fullwaived="fullwaived_".$type_id;
                $feegroup="feegroup_".$type_id;
            }
            $content='<div class="row well">
                <div class="col-lg-4"><input class="feegroupcheckNew" type="checkbox" name="feetypechk[]" value="'.$type_id.'">&nbsp;'.$type.'</div>
                <div class="col-lg-8 well" id="'.$type_id.'" style="display:none">
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label for ="ftype" class="control-label">Pay Type</label>
                            <select class="form-control" name="'.$ptype.'" id="'.$ptype.'" onchange="tohandlechange(this,'.$type_id.')">
                            <option value="" default>--SELECT--</option>
                            <option value="Online">Online</option>
                            <option value="Cheque">Cheque</option>
                            </select>
                        </div>
                        <div class="col-lg-6 chequebank_'.$type_id.'" style="display:none">
                            <label for ="des" class="control-label">Bank</label>
                             <select class="form-control" name="'.$cbank.'" id ="'.$cbank.'">
                               <option value="">--Select--</option>';
                                foreach($banks as $k => $bank){
                                    $content.='<option value="'.$bank.'">'.$bank.'</option>';
                                }
                            $content.='</select>
                        </div>
                        <div class="col-lg-6 onlinebank_'.$type_id.'">
                            <label for ="des" class="control-label">Bank</label>
                             <input type="text" id="'.$bank.'" name="'.$bank.'"  placeholder="Bank Name" class="form-control" value="Atom" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-6 chequeno_'.$type_id.'" style="display:none">
                            <label for ="des" class="control-label">Cheque/DD Number</label>
                             <input type="text" id="'.$paymentmode.'" name="'.$paymentmode.'" placeholder="Cheque No/ DD Number" class="form-control">
                        </div>
                        <div class="col-lg-6 onlinetrans_'.$type_id.'">
                            <label for ="des" class="control-label">Transaction Number</label>
                            <input type="text" id="'.$paymentmodetrans.'" name="'.$paymentmodetrans.'" placeholder="Transaction Number" class="form-control">
                        </div>
                        <div class="col-lg-6">
                            <label for ="des" class="control-label">Amount</label>
                            <input type="text" readonly id="'.$amount.'" name="'.$amount.'" required placeholder="Amount" value="'.$q['org_total'].'" class="form-control">
                            <input type="hidden" class="feetypes" id="'.$feegroup.'" name="'.$feegroup.'" value="'.$group_id.'">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label for ="des" class="control-label">Date</label>
                            <input type="text" id="'.$paiddate.'" name="'.$paiddate.'" placeholder="Paid Date" class="form-control datepicker">
                        </div>
                        <div class="col-lg-6">
                            <label for ="remarks" class="control-label" >Remarks</label>
                            <textarea placeholder="Remarks" class="form-control remarks" name="'.$remarks.'" maxlength="250"></textarea>
                        </div>
                    </div> 
                </div>
            </div>';

            $html.=$content;

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
                <p class="heading">Cheque/DD FeeType Wise</p>
                <div class="main">
                    <input name="id" type="hidden" value="<?php echo $res['id'];?>" />
                    <form id="getchallan_new" method="post">
                        <div class="form-group">
                                <label for ="challanno" class="control-label">Challan No</label>
                                <input type="text" id="challanno" name="challanno" required placeholder="Challan No" value="<?php echo $cno; ?>" class="form-control">
                        </div>
                        
                        <div class="form-group text-center">
                            <button type="submit" value="new" name="getchallan_new" id="getchallan_new" class="btn btn-primary text-center">Submit</button>
                        </div>
                    </form>
                        <?php if(!empty($sid)) { ?>
                        <form method="post" id="studDataModal1" action="adminactions.php">
                            <div class="challandetailsNew">
                                <div  class="feetab1">
                                    <div class="form-group row">
                                        <input type="hidden" class="sid" id="sid" name="sid" value="<?php echo $sid; ?>">
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
                            <?php echo $html; ?>
                            <div class="row well">
                                <div class="col-lg-6">
                                    <input type="checkbox" class="sendmail" id="sendmail" name="sendmail" checked="checked">
                                     <label for="sendmail" class="control-label">Send Receipt</label>
                                </div>
                                <div class="col-lg-6">
                                    <!--<input type="checkbox" class="fullwaived" id="'.$fullwaived.'" name="'.$fullwaived.'">
                                     <label for="fullwaived" class="control-label">100% Waiver</label>-->
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="button" id="closepay_ftype" class="btn btn-default" name='close' value="confirm">Close</button>
                                <button type="submit" name='fee_pay' value="confirm" class="btn btn-primary" >Submit</button>
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