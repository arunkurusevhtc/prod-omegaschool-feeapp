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
if(isset($_REQUEST['getfeedetails']) && !empty($_REQUEST['getfeedetails'])){
  $studentId = (isset($_REQUEST['studentId']) && !empty($_REQUEST['studentId']))?trim($_REQUEST['studentId']):"";
  if(!empty($studentId)){

  $sql = 'SELECT "parentId",f."feeType" , c.amount , f.id as fid, c.id as feeconfigid,s."studentId",s."studentName",cl."class_list",ay.year as academicyr,s.section,s.term,str.stream as streamname,c."createdOn", c."feeType" as feetypeid,f."applicable",s.id as sid, s.class, s.academic_yr,s.stream,f.maxquantity FROM tbl_fee_type f JOIN tbl_fee_configuration c ON f.id = c."feeType"::integer JOIN tbl_student s ON(c.stream = s.stream AND c.class = s.class::integer AND c.semester = s.term AND c."academicYear"=s.academic_yr) LEFT JOIN tbl_class cl ON (cl.id = c.class::integer) LEFT JOIN tbl_stream str ON (str.id = c.stream::integer) LEFT JOIN tbl_academic_year ay ON (ay.id = c."academicYear"::integer) WHERE f."status" = \'1\' AND f."deleted" = \'0\' AND (f."applicable" ILIKE \'%L%\' OR f."applicable" ILIKE \'%U%\' OR f."applicable" ILIKE \'%C%\') AND s."studentId"=\'' . $studentId . '\'';
    $query = sqlgetresult($sql, true);
   

     //$query = sqlgetresult('SELECT * FROM chequedddata WHERE "challanNo" =\'' . $cno . '\' AND "challanStatus" = \''. 0 .'\'',true);
     $num=count($query);
     if($num > 0){

       // echo "<pre>";
    //print_r($query);
    //exit;
      foreach($query as $key => $q){
            $sid=trim($q['studentId']);
            $pid=trim($q['parentId']);
            $term=trim($q['term']);
            $section=trim($q['section']);
            /*$academicyear=trim($q['academic_yr']);
            $academicyearid=trim($q['academic_yr']);
            $stream=trim($q['streamname']);
            $class=trim($q['class_list']);*/
            $academicyear=trim($q['academicyr']);
            $academicyearid=trim($q['academic_yr']);
            $stream=trim($q['streamname']);
            $streamid=trim($q['stream']);
            $class=trim($q['class_list']);
            $classid=trim($q['class']);

            $snum=trim($q['studentName']);
            $applicable=trim($q['applicable']);
            $feeconfigid=trim($q['feeconfigid']);
            $id=trim($q['fid']);
            $s_uid=trim($q['sid']);

            //$group_id = trim($q['feeGroup']);
            $type_id = trim($q['feetypeid']);
            $type = trim($q['feeType']);
            if($type == ''){
              $type =  "Late Fee";
            }
            
             
            $ptype="ptype_".$type_id;
            $cbank="cbank_".$type_id;
            $bank1="bank_".$type_id;
            $paymentmode="paymentmode_".$type_id;
            $paymentmodetrans="paymentmodetrans_".$type_id;
            $amount="amount_".$type_id;
            $paiddate="paiddate_".$type_id;
            $remarks="remarks_".$type_id;
            $sendmail="sendmail_".$type_id;
            $fullwaived="fullwaived_".$type_id;
            $feegroup="feegroup_".$type_id;
            $aid="applicable_".$type_id;
            $eid="eventname_".$type_id;
            $unit_price=$q['amount'];

            $qty="qty_".$type_id;
            $match="U";
            $num=0;
            $sqty="";
            if (strpos($applicable, "L") !== false) {
                $eventname = "LUNCH-".$id."-".$feeconfigid."-".$s_uid;
                $paymenttablecheck = sqlgetresult('SELECT COUNT(*) as total FROM tbl_payments WHERE "challanNo" = \''.$eventname.'\' AND "transStatus" = \'Ok\'',true);
                $num=$paymenttablecheck[0]['total'];
            }

            if (strpos($applicable, "C") !== false) {
                $maxquantity=trim($q['maxquantity']);
                $sqty='<select name="'.$qty.'" id="'.$qty.'" class="form-control" required="required" onchange="calcAmount(this,'.$unit_price.','.$type_id.')">';
                for ($i=1; $i <=$maxquantity ; $i++) { 
                    $sqty.='<option value="'.$i.'">'.$i.'</option>';
                }
                $sqty.='</select>';
                $eventname = "COMMON-".$id."-".$feeconfigid."-".$s_uid."-".$maxquantity;
            }

            if (strpos($applicable, "U") !== false) {
                $sqty='<select name="'.$qty.'" id="'.$qty.'" class="form-control" required="required" onchange="calcAmount(this,'.$unit_price.','.$type_id.')">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                </select>';
                $eventname = "UNIFORM-".$id."-".$feeconfigid."-".$s_uid;
            }

           $content="";
           if($num == 0){
            $content='<div class="row well">
                <input type="hidden" class="sid" id="'.$aid.'" name="'.$aid.'" value="'.$applicable.'">
                <input type="hidden" class="sid" id="'.$eid.'" name="'.$eid.'" value="'.$eventname.'">
                <div class="col-lg-4"><input class="feegroupcheckNew" type="checkbox" name="feetypechk[]" value="'.$type_id.'">&nbsp;'.$type.'<br >'.$sqty.'</div>
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
                             <input type="text" id="'.$bank1.'" name="'.$bank1.'"  placeholder="Bank Name" class="form-control" value="Atom" readonly>
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
                            <input type="text" readonly id="'.$amount.'" name="'.$amount.'" required placeholder="Amount" value="'.$unit_price.'" class="form-control">
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

        }
     }else{
        $_SESSION['errorcheque']="<p class='error-msg'>Fee Type not configured for the given student id.</p>";
     }
  }else{
    $_SESSION['errorcheque']="<p class='error-msg'>Please enter the student id</p>";
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
                <p class="heading">Create Manual Receipt(Uniform/Lunch/Common)</p>
                <div class="main">
                    <form id="getfeedetails" method="post">
                        <div class="form-group">
                                <label for ="challanno" class="control-label">Student Id</label>
                                <input type="text" id="studentId" name="studentId" required placeholder="StudentId" value="<?php echo $studentId; ?>" class="form-control">
                        </div>
                        
                        <div class="form-group text-center">
                            <button type="submit" value="new" name="getfeedetails" id="getfeedetails" class="btn btn-primary text-center">Submit</button>
                        </div>
                    </form>
                        <?php if(!empty($sid)) { ?>
                        <form method="post" id="studDataModal1" action="adminactions.php">
                            <div class="challandetailsNew">
                                <div  class="feetab1">
                                    <div class="form-group row">
                                        <input type="hidden" class="sid" id="pid" name="pid" value="<?php echo $pid; ?>">
                                        <input type="hidden" class="sid" id="sid" name="sid" value="<?php echo $sid; ?>">
                                        <input type="hidden" class="term" id="term" name="term" value="<?php echo $term; ?>">
                                        <input type="hidden" class="class" id="class" name="class" value="<?php echo $class; ?>">
                                        <input type="hidden" class="stream" id="stream" name="stream" value="<?php echo $stream; ?>">
                                        <input type="hidden" class="academicyear" id="academicyear" name="academicyear" value="<?php echo $academicyear; ?>">
                                        <input type="hidden" class="s_uid" id="s_uid" name="s_uid" value="<?php echo $s_uid; ?>">
                                        <input type="hidden" class="academicyearid" id="academicyearid" name="academicyearid" value="<?php echo $academicyearid; ?>">
                                        <input type="hidden" class="streamid" id="streamid" name="streamid" value="<?php echo $streamid; ?>">
                                        <input type="hidden" class="classid" id="classid" name="classid" value="<?php echo $classid; ?>">
                                        <input type="hidden" class="section" id="section" name="section" value="<?php echo $section; ?>">
                                        <div class="col-lg-2">
                                            <label for ="snum" class="control-label">Student Name: </label>
                                        </div>
                                        <div class="col-lg-4">
                                            <span class="snum"><?php echo $snum; ?></span>
                                            <input type="hidden" class="snum" id="snum" name="snum">
                                        </div>
                                         <div class="col-lg-2">
                                            <label for ="studid" class="control-label">Academic Year: </label>
                                        </div>
                                         <div class="col-lg-4">
                                            <span class="cno"><?php echo $academicyear; ?></span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-2">
                                            <label for ="streamid" class="control-label">Stream: </label>
                                        </div>
                                        <div class="col-md-4">
                                            <span class="streamid"><?php echo $stream; ?></span>
                                        </div>
                                        <div class="col-md-2">
                                            <label for ="classid" class="control-label">Class: </label>
                                        </div>
                                        <div class="col-md-4">
                                            <span class="classid"><?php echo $class; ?></span>
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
                                <button type="submit" name='fee_other_pay' value="confirm" class="btn btn-primary" >Submit</button>
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