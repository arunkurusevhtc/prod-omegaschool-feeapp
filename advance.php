<?php
ob_start();
header("location:checkout.php");
exit;
include_once('navbar.php');
$studentIds = sqlgetresult('SELECT sid,"studentId" FROM getparentdata WHERE id = \''.$_SESSION['uid'].'\' AND status = \'1\' AND deleted = \'0\'  GROUP BY sid,"studentId"',true);

//createPDF_advance(34799);
//exit;
$sid="";
$studId="";
$feeTotal=0;
$waivedAmt=0;
$balance=0;
$minimum=0;
$challanNos=[];
$partialmin = 0;
$sfsfees = array();
$schoolutilityies = array();
$eventid = array();
$steamname="";
$studentId="";
$studentName="";

$where="";

if(isset($_POST['studId']) && !empty($_POST['studId'])){
    //$studId = "MONT/190049";
    $studId = trim($_POST['studId']);
    $qry='SELECT s.id AS sid,a.id AS aid,s."studentId",a.amount,s."studentName",s.streamname FROM studentcheck s LEFT JOIN tbl_advance_payment a ON (s.id=a.sid) WHERE s.id = \''.$studId.'\' AND s."parentId" = \''.$_SESSION['uid'].'\'';
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

    $where=' AND sid= \''.$studId.'\'';
}

/* Log Details */
//$query = 'SELECT a."studentId", a."studentName",a.type,p."challanNo" AS refnumber,p."transStatus", a.amount,p.id AS pid, a."createdOn" FROM advancePaymentLogDetails a JOIN tbl_payments p ON (a.id=p.advanceid) WHERE a."parentId" = \''.$_SESSION['uid'].'\''.$where.' ORDER BY a.id DESC LIMIT 25';
/*$query = 'SELECT id,"studentId", "studentName",type,"transNum" AS refnumber,"transStatus", amount, "createdOn" FROM advancePaymentLogDetails WHERE "parentId" = \''.$_SESSION['uid'].'\''.$where.' ORDER BY id DESC LIMIT 25';
$credit = sqlgetresult($query,true);*/



/* Log Details */
/*$debitqry = 'SELECT s."studentId", s."studentName",\'Debit\' AS type, a."transNum" AS refnumber, a."transStatus", a.balance AS amount,a.id AS pid, a."createdOn" FROM tbl_transaction a JOIN tbl_student s ON (a.sid=s.id) WHERE s."parentId" = \''.$_SESSION['uid'].'\' AND a."transStatus"=\'Ok\' AND (a.balance > 0 OR a.balance IS NOT NULL)'.$where.' ORDER BY a.id DESC LIMIT 25';
$debit = sqlgetresult($debitqry,true);*/



$log=array_merge($credit,$debit);


?>
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

<div class="container-fluid">
   <div class="row">
        <div class="col-md-2">&nbsp;</div>
        <div id="topup-container" class="col-md-8">
          <h4 class="crdtoph">Pay In Advance</h4>
          <form method="post" name="studfltr" action="">
            <div class="form-group row">
                <label class="control-label col-sm-3" for="email">StudentId: </label>
                <div class="col-md-6">
                    <select class="form-control nonfeestudid" name="studId" id="studId" required onchange="document.studfltr.submit();">
                        <option>-Select-</option>
                        <?php
                            foreach ($studentIds as $val) {
                                if($studId==$val['sid']){
                                    $selct="selected='selected'";
                                }else{
                                    $selct="";
                                }
                                echo "<option value='".$val['sid']."' ".$selct.">".$val['studentId']."</option>";
                            }
                        ?>
                    </select>
                </div>
            </div>
           </form> 
           <?php 
           if(!empty($studId)){
           if($num > 0){
           ?>
         <div class="form-group well">
                <form method="post" id="studDataModal" action="sql_actions.php">
                    <input type="hidden" name="studentId" id="studentId" value="<?php echo $studentId; ?>">
                    <input type="hidden" name="s_id" id="s_id" value="<?php echo $sid; ?>">
                <div class="table-responsive">
                        <div id="challanData">
                            <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <td colspan="3"><label> School Name : </label> LMOIS - <?php echo $steamname; ?></td>
                                    </tr>
                                    <tr>
                                        <td><label>Name : </label>&nbsp;<?php echo $studentName; ?></td>
                                        <td colspan="2"><label>ID : </label>&nbsp;<?php echo $studentId; ?></td>
                                    </tr>
                                    
                                    <tr>
                                        <td colspan="3"><p class="tot">Balance: <span><?php echo $balance; ?></span></p></td>
                                    </tr>

                                    <tr>
                                        <td colspan="3"><label>Enter Amount : </label>&nbsp;<input type="number" name="txtamt" required="required"></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>     
                        <div class="text-center">
                            <button type="button" class="btn btn-default" data-dismiss="modal" onclick="window.location.href='studetscr.php'">Close</button>
                            <button type="submit" name="payadvance" value="Advance" id="btnPay" class="btn btn-primary">Advance Payment</button>
                        </div>
                    
                </div>
                </form>
            </div>
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