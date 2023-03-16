<?php 
ob_start();
require_once('admnavbar.php');
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
            <div class="error_stdid" style="display: none;"></div>
            <div class="contentcheque">
                <p class="heading">Academic Support Charges</p>
                <div class="main">
                        <form method="post" action="adminactions.php">                          
                            <div class="dd">
                                <div class="form-group row">
                                    <label class="control-label col-lg-4" for="email">Student Id </label>
                                    <div class="col-lg-8">
                                        <input type="text" class="form-control" name="nonfeestudid" id="nonfeestudid" required />
                                    </div>
                                </div>
                                <input type="hidden" name="studentidfornonfee" id="studentidfornonfee" value="">
                                <div class="form-group row eventdetails">
                                    <label class="control-label col-lg-4" for="email">Event Name </label>
                                    <div class="col-lg-8">
                                        <select name="eventname"  class="eventname form-control" id="eventnameid" required>
                                        <option value="">--Select--</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-lg-4" for="email">Amount </label>
                                    <div class="col-lg-8">
                                        <input type= "text" readonly value="0" name = "amountofevent" id="amountofevent" class="form-control" required>
                                    </div>
                                </div>
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
                                <button type="button" id="closepay" class="btn btn-default" name='close' value="confirm" onclick="window.location.href='commonfee_report.php'">Close</button>
                                <button type="submit" name='paycommonnonfee' id="paycommonnonfee" value="confirm" class="btn btn-primary" >Submit</button>
                            </div>
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