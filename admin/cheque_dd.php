<?php 
    require_once('admnavbar.php');
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
                <p class="heading">Cheque/DD</p>
                <div class="main">
                    <input name="id" type="hidden" value="<?php echo $res['id'];?>" />
                    <form id="getchallan">
                        <div class="form-group">
                                <label for ="challanno" class="control-label">Challan No</label>
                                <input type="text" id="challanno" name="challanno" required placeholder="Challan No" class="form-control">
                        </div>
                        
                        <div class="form-group text-center">
                            <button type="submit" value="new" name="getchallan" id="getchallan" class="btn btn-primary text-center">Submit</button>
                        </div>
                    </form>
                        
                        <form method="post" id="studDataModal1" action="adminactions.php">
                            <div class="challandetails">
                                <!-- <div id="challanData"></div> -->
                                <div  class="feetab1">
                                    <div class="form-group row">
                                        <input type="hidden" class="sid" id="sid" name="sid">
                                        <input type="hidden" class="cnum" id="cnum" name="cnum">
                                        <input type="hidden" class="term" id="term" name="term">
                                        <input type="hidden" class="class" id="class" name="class">
                                        <input type="hidden" class="stream" id="stream" name="stream">
                                        <input type="hidden" class="academicyear" id="academicyear" name="academicyear">


                                        <div class="col-lg-2">
                                            <label for ="snum" class="control-label">Student Name: </label>
                                        </div>
                                        <div class="col-lg-4">
                                            <span class="snum"></span>
                                            <input type="hidden" class="snum" id="snum" name="snum">
                                        </div>
                                         <div class="col-lg-2">
                                            <label for ="studid" class="control-label">Student Id: </label>
                                        </div>
                                         <div class="col-lg-4">
                                            <span class="sid"></span>
                                            <input type="hidden" class="sid" id="sid" name="sid">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-2">
                                            <label for ="streamid" class="control-label">Stream: </label>
                                        </div>
                                        <div class="col-md-4">
                                            <span class="streamid"></span>
                                            <input type="hidden" class="streamid" id="streamid" name="streamid">
                                        </div>
                                        <div class="col-md-2">
                                            <label for ="classid" class="control-label">Class: </label>
                                        </div>
                                        <div class="col-md-4">
                                            <span class="classid"></span>
                                            <input type="hidden" class="classid" id="classid" name="classid">
                                        </div>
                                         
                                         
                                    </div>
                                    <div class="groupdata">
                                    </div>

                            </div>
                            <div class="clear:both;">&nbsp;</div>
                            <div class="chequeDetails">
                                <div class="form-group row">
                                    <div class="col-lg-4">
                                        <label for ="ftype" class="control-label">Pay Type</label>
                                    </div>
                                    <div class="col-lg-8">
                                        <input type="hidden" class="feegroupradio" id="feegroupradio" name="feegroupradio">

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
                                        <input type="text" readonly id="amount" name="amount" required placeholder="Amount" class="form-control">
                                        <input type="hidden" class="feetypes" id="feetypes" name="feetypes">

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
                                         <!-- <input type="text" id="remarks" name="remarks" required placeholder="Remarks" class="form-control"> -->
                                          <textarea placeholder="Remarks" class="form-control remarks" name="remarks" maxlength="250"></textarea>
                                    </div>                                               
                                </div>
                                <div class="form-group">
                                    <div class="col-lg-4">

                                    </div>
                                    <div class="col-lg-8">
                                         <input type="checkbox" class="sendmail" id="sendmail" name="sendmail">
                                         <label for="sendmail" class="control-label">Send Receipt</label>
                                    </div> 
                                                                     
                                </div>
                                <div class="form-group">
                                    <div class="col-lg-4">

                                    </div>
                                    <div class="col-lg-8">
                                         <input type="checkbox" class="fullwaived" id="fullwaived" name="fullwaived">
                                         <label for="fullwaived" class="control-label">100% Waiver</label>
                                    </div> 
                                                                     
                                </div>
                            </div>                
                            <div class="text-center">
                                <button type="button" id="closepay" class="btn btn-default" name='close' value="confirm">Close</button>
                                <button type="submit" name='pay' value="confirm" class="btn btn-primary" >Submit</button>
                            </div>
                        </form>
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