<?php
include_once('admnavbar.php');
$sid="";
$studId="";
$balance=0;
$steamname="";
$studentId="";
$studentName="";

$where="";

if(isset($_POST['studId']) && !empty($_POST['studId'])){
    //$studId = "MONT/190049";
    $studId = trim($_POST['studId']);
    $qry='SELECT s.id AS sid,a.id AS aid,s."studentId",a.amount,s."studentName",s.streamname FROM studentcheck s LEFT JOIN tbl_advance_payment a ON (s.id=a.sid) WHERE s."studentId" = \''.$studId.'\'';
    $details = sqlgetresult($qry,true);
    $num=count($details);
    if($num > 0){
        /*Student Primary Id*/
        $sid=trim($details[0]['sid']);
        $steamname=trim($details[0]['streamname']);
        $studentId=trim($details[0]['studentId']);
        $studentName=trim($details[0]['studentName']);
        $balance=isset($details[0]['amount'])?trim($details[0]['amount']):0;
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
          <h4 class="crdtoph">Add Advance Payment</h4>
          <form method="post" name="studfltr" action="">
            <div class="form-group">
                    <label for="challanno" class="control-label">Student Id</label>
                    <input type="text" id="studId" name="studId" required="required" placeholder="Student Id" value="<?php echo $studId; ?>" class="form-control">
            </div>

            <div class="form-group text-center">
                <button type="submit" value="new" name="getchallan" id="getchallan" class="btn btn-primary text-center">Submit</button>
            </div>
           </form> 
           <?php 
           if(!empty($studId)){
           if($num > 0){
           ?>
                <form method="post" id="studDataModal" action="adminactions.php">
                    <input type="hidden" name="studentId" id="studentId" value="<?php echo $studentId; ?>">
                    <input type="hidden" name="s_id" id="s_id" value="<?php echo $sid; ?>">
                     <div class="form-group row">
                                 <div class="col-lg-6">
                                    <label class="control-label col-md-4 col-sm-4" for="studStatus">StudentID:</label>
                                    <div class="col-md-8 col-sm-8">
                                        <label><?php echo $studentId;?></label>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <label class="control-label col-md-4 col-sm-4" for="studStatus">Name:</label>
                                    <div class="col-md-8 col-sm-8">
                                        <label><?php echo $studentName;?></label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                 <div class="col-lg-6">
                                    <label class="control-label col-md-4 col-sm-4" for="studStatus">Balance:</label>
                                    <div class="col-md-8 col-sm-8">
                                        <label>Rs. <?php echo $balance;?></label>
                                    </div>
                                </div>
                                <div class="col-lg-6">&nbsp;</div>
                            </div>
                                <div class="form-group row">
                                    <div class="col-lg-4">
                                        <label for ="ftype" class="control-label">Pay Type</label>
                                    </div>
                                    <div class="col-lg-8">
                                        <select class="form-control" name="ptype" id="ptype" required="">
                                        <option value="" default>--SELECT--</option>
                                        <option value="Online">Online</option>
                                        <option value="Cheque">Cheque</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row chequebank">
                                    <div class="col-lg-4">
                                        <label for ="des" class="control-label">Bank</label>
                                    </div>
                                    <div class="col-lg-8">
                                        <select class="form-control" name="cbank" id ="cbank">
                                            <option value="">--Select--</option>
                                            <?php
                                            foreach($banks as $k => $bank){
                                            echo('<option value="'.$bank.'">'.$bank.'</option>');
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row onlinebank">
                                    <div class="col-lg-4">
                                        <label for ="des" class="control-label">Bank</label>
                                    </div>
                                    <div class="col-lg-8">
                                        <select class="form-control" name="bank" id="bank"  placeholder="Bank Name">
                                        <option value="">-select-</option>
                                        <?php 
                                        foreach ($p_methods as $value) {
                                            ?>
                                            <option value="<?php echo $value; ?>"><?php echo ucfirst($value); ?></option>
                                            <?php
                                        }
                                        ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row chequeno">
                                    <div class="col-lg-4">
                                        <label for ="des" class="control-label">Cheque/DD Number</label>
                                    </div>
                                    <div class="col-lg-8">
                                        <input type="text" id="paymentmode" name="paymentmode" placeholder="Cheque No/ DD Number" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row onlinetrans">
                                    <div class="col-lg-4">
                                        <label for ="des" class="control-label">Transaction Number</label>
                                    </div>
                                    <div class="col-lg-8">
                                        <input type="text" id="paymentmodetrans" name="paymentmodetrans" placeholder="Transaction Number" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-lg-4">
                                        <label for ="des" class="control-label">Amount</label>
                                    </div>
                                    <div class="col-lg-8">
                                        <input type="number" name="txtamt" required="required" placeholder="Amount" class="form-control">
                                    </div>                                        
                                 </div>
                                <div class="form-group row">
                                    <div class="col-lg-4">
                                        <label for ="des" class="control-label">Date</label>
                                    </div>
                                    <div class="col-lg-8">
                                         <input type="text" id="paiddate" name="paiddate" required placeholder="Paid Date" class="form-control datepicker">
                                    </div>                                               
                                </div>
                                <div class="form-group row">
                                    <div class="col-lg-4">
                                        <label for ="remarks" class="control-label" >Remarks</label>
                                    </div>
                                    <div class="col-lg-8">
                                          <textarea placeholder="Remarks" class="form-control remarks" name="remarks" required maxlength="250"></textarea>
                                    </div>                                               
                                </div>
                        <div class="text-center">
                            <button type="button" class="btn btn-default" data-dismiss="modal" onclick="window.location.href='advancereport.php'">Close</button>
                            <button type="submit" name="payadvance" value="Advance" id="btnPay" class="btn btn-primary">Advance Payment</button>
                        </div>
                </form>
            <?php
        }else{

            ?>
            <div class="form-group well">
                <p><center>No Data Available.</center></p>
            </div>
           <?php   
        } 
          
    }
    ?>
            </div>
            <div class="col-md-2">&nbsp;</div>
    </div>        

</div>
<div class="row comment">
    
</div>
<!-- Add Student Modal -->

<?php
    include_once('footer.php');
?>