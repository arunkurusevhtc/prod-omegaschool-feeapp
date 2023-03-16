<?php
include_once('admnavbar.php');
date_default_timezone_set('Asia/Calcutta');
$datenow = date("Y-m-d");
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

$ptxt="";
$chlstatus=array();
$par_ft_details=[];
$num=0;
$rdcount=0;
$rdduecount=0;
$no="checked='checked'";
$yes="";
if(isset($_POST['studId']) && !empty($_POST['studId'])){
    $studId = trim($_POST['studId']);
    $cautionflter='RD-';

    $rdcount =  toGetPartialRDCount($studId, $cautionflter);
    $rdnum=0;
    if($rdcount > 0){
        $rdduecount =  toGetPartialRDCount($studId, $cautionflter, $datenow);
        if(isset($_REQUEST['rd']) && trim($_REQUEST['rd']) == 1 && $rdduecount==0){
            $wheredt='';
            $yes="checked='checked'";
            $no="";
        }else{
            $wheredt=' AND DATE("duedate") <=\'' .$datenow. '\''; 
            $no="checked='checked'";
            $yes="";
        }
        $rdqry = 'SELECT * FROM challandatanew WHERE "studentId" =\'' . $studId . '\'  AND "studStatus"!=\'Transport.Fee\' AND ("challanStatus" = \'0\' OR "challanStatus" = \'2\') AND ("challanNo" ILIKE \'%'.$cautionflter.'%\') AND "academicYear" >=6 '.$wheredt.' ORDER BY cid ASC';
        $rdChallanData = sqlgetresult($rdqry,true);
        $rdnum=count($rdChallanData); 
    }
    $chaData = sqlgetresult('SELECT * FROM challanDatanew WHERE "studentId" =\'' . $studId . '\' AND "studStatus"!=\'Transport.Fee\' AND ("challanStatus" = \'0\' OR "challanStatus" = \'2\') AND ("challanNo" NOT ILIKE \'%'.$cautionflter.'%\') AND "academicYear" >=6  ORDER BY cid ASC',true);
    $chlnum=count($chaData);
    if($rdnum >0){
        if($chlnum >0){
            $challanData = array_merge($rdChallanData, $chaData);
        }else{
           $challanData = $rdChallanData; 
        }
    }else{
        $challanData = $chaData;
    }
    $num=count($challanData);
    if($num > 0){

        $stream=trim($challanData[0]['stream']);
        $class=trim($challanData[0]['class']);
        $term=trim($challanData[0]['term']);
        $academic_yr=trim($challanData[0]['academic_yr']);
        $par_ft_details = isCautionDepositPartial($studId);
        foreach ($challanData as $k => $value) {
            $chal_id=trim($value['challanNo']);
            $challanNos[]=$chal_id;
            $studentName = trim($value['studentName']);
            $studentId = trim($value['studentId']);
            $steam = $value['steam'];
            $steamname = $value['steamname'];
            $sid = $value['sid'];
            //$partialmin = $value['partialmin'];
            $classwise=trim($value['partialminclass']);
            $streamwise=trim($value['partialmin']);
            if($classwise){
                $partialmin = $classwise;
            }else{
               $partialmin = $streamwise; 
            }
            $org_total=$value['org_total'];
            $cstatus=$value['challanStatus'];
            if($cstatus==2){
               $chlstatus[]="partial";
            }
            $pid=$value['parentId'];
            $feeGroup=$value['feeGroup'];
            $feeType=$value['feeType'];           
            $feeTotal+=$org_total;
            //$waiveamt=getwaiveddata($chal_id, $feeGroup);
            $paygroup[$chal_id][$feeGroup][] = $org_total;

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
        foreach($paygroup[$challan] as $keyg=>$valg){
            $wtot=getWaiverAmtbyFeeGroup($challan, $keyg);
            $ctot=getChallanAmtbyFeeGroup($challan, $keyg);  
            $paygroupamt[$keyg]+=$ctot-$wtot;
            
        }
    }

 $challanids=implode(",", $challanNos);
}
$partialActive=0;

$where="";
if(!empty($sid)){
    $yr=getCurrentAcademicYear();
    $query = "SELECT * FROM partiallist WHERE sid=".$sid." AND academic_yr=".$yr." AND status='ACTIVE' LIMIT 1";
    $partial = sqlgetresult($query,true);
    $partialActive=count($partial);
    if($partialActive > 0){
        //$partialpaid=toGetPartialAmount($sid);
        $parper=trim($partial[0]['partial_min_percentage']);
        if($parper){
          $partialmin = $parper;
        }
        $tx="partial";
        $ptxt='<h4>Partial payment has been enabled.</h4>';
    }

    $balance=toGetAvailableBalance($sid);

   $where=' AND sid= \''.$sid.'\'';

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
          <h4 class="crdtoph">Fee Challan - Payment</h4>
          <form method="post" name="studfltr" id="studfltr" action="">
            <input type="hidden" name="rd" id="rd" value="">
            <div class="form-group">
                    <label for="challanno" class="control-label">Student Id</label>
                    <input type="text" id="studId" name="studId" required="required" placeholder="Student Id" value="<?php echo $studId; ?>" class="form-control">
            </div>

            <div class="form-group text-center">
                <button type="button" class="btn btn-default" data-dismiss="modal" onclick="window.location.href='managepartialpayment.php'">Close</button>
                <button type="submit" value="new" name="getchallan" id="getchallan" class="btn btn-primary text-center">Submit</button>
            </div>
           </form> 
           <?php 
           if(!empty($studId)){
           if($num > 0){
           ?>
         <div class="form-group well">
                <form method="post" id="studDataModal" action="adminactions.php">
                    <input type="hidden" name="pid" id="pid" value="<?php echo $pid; ?>">
                <div class="table-responsive">
                        <div id="challanData">
                            <input type="hidden" name="studentId" id="studentId" value="<?php echo $studentId; ?>">
                            <input type="hidden" name="s_id" value="<?php echo $sid; ?>" />
                            <input type="hidden" name="paygroup" value='<?php echo json_encode($paygroupamt); ?>' />
  
                            <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <td><label>Name : </label>&nbsp;<?php echo $studentName; ?></td>
                                        <td colspan="2"><label>ID : </label>&nbsp;<?php echo $studentId; ?></td>
                                    </tr>
                                    <tr>
                                        <td><p class="tot">Total Challan Value: <span id="f_tot"><?php echo $feeTotal; ?></span></p></td>
                                        <td><p class="tot">Waived Total: <span><?php echo $waivedAmt; ?></span></p></td>
                                        <td><p class="tot">Paid So Far: <span><?php echo $partialpaid; ?></span></p></td>
                                    </tr>
                                    <?php
                                    if($partialActive == 0){
                                        //$minimum=$tot * ($partialmin/100);
                                    ?>
                                      
                                    
                                    <?php
                                    }
                                    ?>
                                     <tr>
                                        <td colspan="3"><p class="tot">Net Due: <span id="grand_tot"><?php
                                        $wtotaa = $feeTotal-$waivedAmt;
                                        echo $tot=$feeTotal-$waivedAmt-$partialpaid;

                                      ?></span></p></td>
                                    </tr>
                                    <?php
                                    
                                    if($partialActive > 0){
                                        //$minimum=$tot * ($partialmin/100);
                                        $minimum=$wtotaa * ($partialmin/100);
                                        $minimum=round($minimum);
                                        /*if($partialpaid > 0){
                                           $tx="partial"; 
                                        }*/
                                        $disppart=1; 
                                        if($tot >= $minimum ){
                                           $chkpar="checked='checked'";
                                           $chkfull="";
                                           $dispamt=$minimum;
                                           $disppart=1;
                                        }else{
                                          $chkpar="";
                                          $chkfull="checked='checked'";
                                           $dispamt=$tot;
                                           $disppart=0;   
                                        }


                                        if($balance >= $tot){
                                            $chkpar="";
                                            $chkfull="checked='checked'";
                                            $dispamt=$tot;
                                            $disppart=0;   
                                        }

                                       // if($dispamt>=$balance)

                                    ?>
                                    <tr>
                                        <td colspan="2"><p><?php echo $ptxt; ?> Pay Options:</p></td>
                                        <td colspan="1"><p class="tot">Advance Paid: <span><?php echo $balance; ?></span></p></td>
                                    </tr>
                                    <tr>
                                        <td colspan="3"><input type="radio" name="payop" value="partialfull"  checked="checked"><label>Pay full due amount</label></td>
                                    </tr>
                                    <?php 

                                    if($disppart ==1){

                                    ?>
                                    <tr>
                                        <td colspan="3"><input type="radio" name="payop" value="minimum"><label>Pay minimum amount</label></td>
                                    </tr>
                                    <tr>
                                        <td colspan="3"><input type="radio" name="payop" value="any"><label>Pay any amount</label></td>
                                    </tr> 
                                    <?php
                                    }
                                    ?>

                                    <tr>
                                        <td colspan="3"><div><label>Amount : </label>&nbsp;<input type="text" name="partialamt" id="partialamt" readonly="readonly" value="<?php echo $tot; ?>" class="tot"></div></td>
                                    </tr>
                                    <?php
                                    }else{
                                        ?>
                                        <tr>
                                        <td colspan="2"><p><?php echo $ptxt; ?> Pay Options:</p></td>
                                        <td colspan="1"><p class="tot">Advance Paid: <span><?php echo $balance; ?></span></p></td>
                                    </tr>
                                    <?php 
                                     $par_ft_details_num = count($par_ft_details);
                                     $labelsuff="";
                                     if($par_ft_details_num >0){ 
                                        $labelsuff=" (This includes Entire Caution Deposit)";
                                      }  
                                     ?>
                                    <tr>
                                        <td colspan="3"><input type="radio" name="payop" value="full" checked="checked"><label>Pay Full Amount <?php echo $labelsuff; ?></label></td>
                                    </tr>
                                    <?php
                                    $caution_tot=0;
                                    $ca_tot=0;
                                    if($par_ft_details_num >0){
                                        foreach($par_ft_details as $key=>$val){
                                             $caution_tot+=$par_ft_details[$key]['nxt_amt'];
                                        }

                                        $ca_tot=$tot-$caution_tot;
                                    ?>
                                    <tr>
                                        <td colspan="3"><input type="radio" name="payop" value="caution"><label>Pay Partial Amount (This includes 50% of Caution Deposit)</label></td>
                                    </tr>
                                    <tr>
                                        <td colspan="3"><p style="color:#FF0000">Note: If you select the option to pay 50% of caution deposit, please note that the remaining 50% of Caution Deposit can be paid on or before next semester due.</p></td>
                                    </tr>
                                    <?php } ?>
                                    <tr>
                                        <td colspan="3"><div><label>Amount : </label>&nbsp;<input type="text" name="partialamt" id="partialamt" readonly="readonly" value="<?php echo $tot; ?>" class="tot"></div></td>
                                    </tr>
                                        <?php

                                    }
                                    ?>
                                    <!--<tr>
                                        <td><strong> Payment Mode</strong></td>
                                        <td><input type="radio" class="payment_mode" name="payment_mode" value="online" checked="">Online</td>
                                    </tr>-->
                                    </tbody>
                                </table>
                            </div>
                            
                        <!--<div class="text-center">
                            <p id="note" style="display: none; color:#FF0000">20-21 Unpaid challans  are carried forward to next academic year and flexible options of 2 instalments have been given</p>
                        </div> -->                                    
                         <?php
                         if($rdcount >0 && $rdduecount==0 && $chlnum >0){
                        ?>
                        <div class="form-group">
                            <div class="col-md-12">
                                <label class="control-label">Would you like to include the outstanding Caution Deposit in this payment?</label>
                                 <label class="btn btn-default active"><input type="radio" name="rddue" value="1" <?php echo $yes; ?>>Yes</label>
                                <label class="btn btn-danger"><input type="radio" name="rddue" value="0" <?php echo $no; ?>>No</label>
                            </div>
                       </div>
                       <div class="form-group">&nbsp;</div>
                        <?php 
                         } 
                        ?> 
                    
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <label for ="ftype" class="control-label">Pay Type</label>
                        <select class="form-control" name="ptypechk" id="ptypechk" required="required">
                            <option value="" default>--SELECT--</option>
                            <option value="Cheque">Cheque</option>
                        </select>
                    </div>
                    <div class="col-lg-6 Offline" style="display:none">
                        <label for ="des" class="control-label">Bank</label>
                        <select class="form-control" name="cbank" id ="cbank" style="">
                        <option value="">--Select--</option>
                        <?php
                        foreach($banks as $k => $bank){
                        ?>
                        <option value="<?php echo $bank; ?>"><?php echo $bank; ?></option>
                        <?php
                        }
                        ?>
                        </select>
                        
                    </div>
                    <div class="col-lg-6 Online" style="display:none">
                        <label for ="des" class="control-label">Bank</label>
                        <input type="text" id="bank" name="bank" placeholder="Bank Name" class="form-control" value="Atom" readonly>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <label for ="des" class="control-label" id="transLbl">Transaction Number</label>
                        <input type="text" id="paymentmodetrans" name="paymentmodetrans" placeholder="Transaction Number" class="form-control" required="required">
                    </div>
                    <div class="col-lg-6">
                        <label for ="des" class="control-label">Date</label>
                        <input type="text" id="paiddate" name="paiddate" placeholder="Paid Date" class="form-control datepicker" required="required">
                    </div>
                    <!--<div class="col-lg-6 chequeno_1" style="display:none">
                        <label for ="des" class="control-label">Cheque/DD Number</label>
                        <input type="text" id="paymentmode" name="paymentmode" placeholder="Cheque No/ DD Number" class="form-control">
                    </div>-->
                </div>
                <div class="row">    
                    <div class="col-lg-6">
                        <label for ="remarks" class="control-label" >Remarks</label>
                        <textarea placeholder="Remarks" class="form-control remarks" name="remarks" maxlength="250" required="required"></textarea>
                    </div>
                </div>
                <div class="row text-center" style="padding-top: 20px;">
                    <button type="button" class="btn btn-default" data-dismiss="modal" onclick="window.location.href='addpartialpayment.php'">Close</button>
                    <button type="submit" name="paytotal_adm" value="<?php echo $tx; ?>" id="paytotal_adm" class="btn btn-primary">Confirm Payment</button>
                </div>
                <input type="hidden" name="partialpaidamt" id="partialpaidamt" value="<?php echo $partialpaid; ?>"/>
                <input type="hidden" name="disppart" id="disppart" value="<?php echo $disppart; ?>" />
                <input type="hidden" name="ftot" id="ftot" value="<?php echo $feeTotal; ?>" />
                <input type="hidden" name="tot" id="tot" value="<?php echo $tot; ?>" />
                <input type="hidden" name="balamt" id="balamt" value="<?php echo $balance; ?>" />
                <input type="hidden" name="challanids" id="challanids" value="<?php echo $challanids; ?>" />
                <input type="hidden" name="minper" id="minper" value="<?php echo $partialmin; ?>" />
                <input type="hidden" name="minamt" id="minamt" value="<?php echo $minimum; ?>" />
                <input class="grand_tot" type="hidden" name="grand_tot" id="g_tot" value="<?php echo $tot; ?>"/> 
                <input class="waived_tot" type="hidden" name="waived_tot" value="<?php echo $waivedAmt; ?>"/>
                <input type="hidden" name="cau_tot" id="c_tot" value="<?php echo $ca_tot; ?>"/>
                <input type="hidden" name="cautionamt" id="cautionamt" value="<?php echo $caution_tot; ?>"/>

                <input type="hidden" name="caution_deposit" value='<?php echo json_encode($par_ft_details, true); ?>'"/>
                </form>
            </div>
            <?php
        }else{
            if($rdcount >0 && $rdduecount==0){
            ?>
            <div class="text-center">
                <button id="remaindue" name="remaindue" type="button" class="btn btn-primary remaindue">Click to Pay Outstanding Amount</button>
            </div>      
        <?php 
          }
        }   
    }
            ?>

            </div>
            <div class="col-md-2">&nbsp;</div>
    </div>        

</div>
<hr>
<div class="container-fluid">
    <div class="table-responsive">
        <button type="button" class="btn button1" data-toggle="collapse" data-target="#list">+ Partial Payment Details</button>
        <div class="table-responsive collapse" id="list">
            <form>
                <table class="table table-bordered admintab dataTableUserPartial" style="width: 99%;">
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Student ID</th>
                            <th>Student Name</th>
                            <th>Ref Number</th>
                            <th>Status</th>
                            <th>Amount</th>
                            <th>Date</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </form>
        </div>
    </div>
</div>
<!-- <div class="onlinepay">
    <p>There is no online fee payment facility for new student at this time.</p>
    <p>Online payment facility is closed.Please approach school for making fee payment.</p>
</div> -->
<div class="row comment">
    
</div>
<!-- Add Student Modal -->
<script>
$(document).ready(function(){
    $('.remaindue').click(function(e) {
        e.preventDefault();
        $("#rd").val(1);
        $("#studfltr").submit();
    });
    $("input[name='rddue']").change(function(e){
    // Do something interesting here
      var val=$(this).val();
      e.preventDefault();
        $("#rd").val(val);
        $("#studfltr").submit();
    });
});
</script>
<?php
    include_once('footer.php');
?>