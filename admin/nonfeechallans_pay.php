<?php 
ob_start();
require_once('admnavbar.php');
$id=isset($_REQUEST['id'])?trim($_REQUEST['id']):"";
if($id){
    $res = sqlgetresult('SELECT * FROM nonfeechallandata WHERE cid=\'' . $id . '\' ');
}else{
   header("Location: createnonfeechallans.php");
exit();
}

?>
<div class="container_fluid">
        <div class="col-sm-2 col-md-2 col-lg-2"></div>
        <div class="col-sm-8 col-md-8 col-lg-8">
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
                <p class="heading">NON-FEE CHALLANS</p>
                <div class="main">
                        <form method="post" action="adminactions.php">
                            <input name="id" type="hidden" value="<?php echo $res['cid'];?>" />
                            <div class="row">
                                 <div class="col-lg-6">
                                    <label class="control-label col-md-4 col-sm-4" for="studStatus">StudentID:</label>
                                    <div class="col-md-8 col-sm-8">
                                        <p><?php echo $res['studentId'];?></p>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <label class="control-label col-md-4 col-sm-4" for="studStatus">Name:</label>
                                    <div class="col-md-8 col-sm-8">
                                        <p><?php echo $res['studentName'];?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                 <div class="col-lg-6">
                                    <label class="control-label col-md-4 col-sm-4" for="studStatus">Semester:</label>
                                    <div class="col-md-8 col-sm-8">
                                        <p><?php echo ($res['term']);?></p>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <label class="control-label col-md-4 col-sm-4" for="studStatus">Class:</label>
                                    <div class="col-md-8 col-sm-8">
                                        <p><?php echo $res['class_list'];?></p>
                                    </div>
                                </div>
                            </div>
                             <div class="row">
                                 <div class="col-lg-6">
                                    <label class="control-label col-md-4 col-sm-4" for="studStatus">ChallanNo:</label>
                                    <div class="col-md-8 col-sm-8">
                                        <p><?php echo $res['challanNo'];?></p>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <label class="control-label col-md-4 col-sm-4" for="studStatus">DueDate:</label>
                                    <div class="col-md-8 col-sm-8">
                                        <p><?php echo $res['duedate'];?></p>
                                    </div>
                                </div>
                            </div>                             
                            <div class="clear:both;">&nbsp;</div>
                            <?php 
                            $status=trim($res['challanStatus']);
                            if($status=="0") { 
                            ?>
                            <div class="dd">
                                <div class="form-group row">
                                    <div class="col-lg-4">
                                        <label for ="ftype" class="control-label">Pay Type</label>
                                    </div>
                                    <div class="col-lg-8">
                                        <!-- <input type="text" id="ptype" name="ptype"  required placeholder="Pay Type" class="form-control"> -->
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
                                         <input type="text" id="bank" name="bank"  placeholder="Bank Name" class="form-control" value="Atom" readonly>
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
                                        <input type="text" readonly id="amount" name="amount" required placeholder="Amount" value="<?php echo $res['total'];?>" class="form-control">
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
                            </div>                
                            <div class="text-center">
                                <button type="button" id="closepay" class="btn btn-default" name='close' value="confirm" onclick="window.location.href='createnonfeechallans.php'">Close</button>
                                <button type="submit" name='nonfeechallanpay' value="confirm" class="btn btn-primary" >Submit</button>
                            </div>
                            <?php
                             }else{
                                ?>
                                <div class="errormessage"><p class='error-msg' style="color:red">Already paid!</p></div>
                                <?php
                             }
                            ?>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-sm-2 col-md-2 col-lg-2"></div>
</div>

</div>
<div class="row comment">
</div>

<?php
include_once(BASEPATH.'footer.php');
?>