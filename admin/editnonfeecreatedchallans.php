<?php
require_once('admnavbar.php');

$id=$_REQUEST['id'];
// exit;
$res = sqlgetresult('SELECT * FROM nonfeechallandata WHERE cid=\'' . $id . '\' ');
// print_r($res);
?>
<script type="text/javascript">

 function changeAction() {
    $('#challanModal').modal('show');
    return false;
 }
</script>
<div class="container-fluid challancreate">
   <div class="row">
      <div class="col-sm-2 col-md-3"></div>
      <div class="col-sm-8 col-md-6">
         <div class="tech-content">
            <p class="heading">Edit Temporary Challan</p>          
               <form  method="post" class="form-horizontal" action="adminactions.php">
                    <input name="id" type="hidden" value="<?php echo $res['cid'];?>" />
                  <input name="challan" type="hidden" value="<?php echo $res['challanNo'];?>">
                  <input name="studentId" id="studentId" type="hidden" value="<?php echo $res['studentId'];?>" />               
                  <input name="semester" id="semester" type="hidden" value="<?php echo $res['term'];?>">
                  <input name="class_list" id="class_list" type="hidden" value="<?php echo $res['clid'];?>">
                  <input name="stream" id="stream" type="hidden" value="<?php echo $res['stream'];?>">
                  <input name="oldduedate" id="oldduedate" type="hidden" value="<?php echo $res['duedate'];?>">
                  <input name="oldremarks" id="oldremarks" type="hidden" value="<?php echo $res['remarks'];?>">
                  <input name="academic" id="academic" type="hidden" value="<?php echo $res['academic_yr'];?>">
                  <input name="stream" id="stream" type="hidden" value="<?php echo $res['stream'];?>">
                  <input name="feegroup" id="feegroup" type="hidden" value="<?php echo $res['feeGroup'];?>">
                  <input name="studentName" id="studentName" type="hidden" value="<?php echo $res['studentName'];?>">
                  <input name="old_inst" id="old_inst" type="hidden" value="<?php echo $res['no_of_instalments'];?>">
                  <input name="selectedfeetype" id="selectedfeetype" type="hidden" value="<?php echo$res['feeType'];?>">
                 
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="studStatus">NAME:</label>
                        <div class="col-sm-8">
                            <p><?php echo $res['studentName'];?></p>
                        </div>
                    </div>               
                          
                    <div id='demandData' class="feetab1 edittech">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="date">DUE DATE:</label>
                            <div class="col-sm-8">
                                <input type="text" class="duedate datepicker form-control" name="duedate" required value="<?php echo$res['duedate'];?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="class">CLASS:</label>
                            <div class="col-sm-8">
                                <input type="text" name="class" id="<?php echo ($res['clid']);?>" class="classlist form-control" value="<?php echo $res['class_list'];?>"  readonly required>
                            </div>                                    
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="semester">SEMESTER:</label>
                            <div class="col-sm-8">
                                <input type="text" name="semester" id="semester" class="semester form-control" value="<?php echo ($res['term']);?>" readonly required>
                            </div>                                    
                        </div>                        
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="studStatus">FEE TYPES:</label>
                            <div class="col-sm-8">
                                 <?php
                                    if( $res['hostel_need'] == 'Y') {
                                        $feeTypes = sqlgetresult("SELECT * FROM tbl_nonfee_type WHERE applicable='DH' OR applicable='H'",true);
                                    } else {
                                        $feeTypes = sqlgetresult("SELECT * FROM tbl_nonfee_type WHERE applicable='DH' OR applicable='D' ",true);
                                    }
                                    // echo stripos($res['studentId'], '2018');
                                    // print_r($feeTypes);
                                ?>
                                <select name="selected_feetype" class="form-control" required>
                                    <?php
                                        $feetypesgroup = sqlgetresult("SELECT * FROM feegroupcheck",true);
                                        // print_r($feetypesgroup);
                                        
                                        echo '<optgroup label="NON-FEE"></optgroup>';
                                        foreach($feeTypes as $feetype) {
                                            if(trim($feetype['feeGroup']) == $res['feeGroup']) {                                      
                                                if( trim($feetype['id']) == trim($res['feeType']) ) {
                                                    echo '<option selected value="'.$feetype['id'].'">'.$feetype['feeType'].'</option>';
                                                } else {
                                                    echo '<option  value="'.$feetype['id'].'">'.$feetype['feeType'].'</option>';
                                                }                                                                          
                                            }    
                                        }                                   
                                        
                                    ?>
                                </select>
                            </div>                                    
                        </div>
                        <?php 
                        if($res['visible']=="1"){
                            if($res['partialpayment']) {  
                                $show='block';
                                $chk='checked="checked"';
                             }else{ 
                                $show='none'; 
                                $chk='';
                            }
                             ?>
                             <div class="form-group ">
                                <label class="control-label col-sm-4" for="remarks">Enable Partial Payment</label>
                                <div class="col-sm-8"> 
                                    <input type="checkbox" id="chkpartial"  name="chkpartial" value="1" <?php echo $chk; ?>>
                                </div>
                            </div>
                            <div class="form-group instalments" style="display: <?php echo $show; ?>;">
                                <label class="control-label col-sm-4" for="remarks">No. of installments</label>
                                <div class="col-sm-8">
                                    <select name="instalment" id="instalment"  class="form-control">
                                        <?php
                                            for($i=2;$i<=6;$i++) {
                                                if($res['no_of_instalments']==$i){
                                                    $sel="selected='selected'";
                                                }else{
                                                    $sel="";
                                                }
                                              echo '<option value="'.$i.'" '.$sel.'>'.$i.'</option>';
                                           }
                                        ?>
                                    </select>
                                </div>
                            </div>
                         <?php 
                         }
                         ?>   
                        <div class="form-group ">
                                <label class="control-label col-sm-4" for="remarks">REMARKS:</label>
                                <div class="col-sm-8">
                                    <textarea placeholder="REMARKS" class="form-control remarks" name="remarks" removeFromString maxlength="250"><?php echo trim($res['remarks']);?></textarea>
                                </div>
                        </div>
                        <div class="form-group text-center">
                            <input type="hidden" name="updatenonfeeChallan" value="updatenonfeeChallan" >
                            <button type="submit" name="updatenonfeeChallan" value="updatenonfeeChallan" class="btn btn-primary text-center" id="updatenonfeeChallan">Update Non-fee Challan</button>
                            <button type="button" value="back" name="back" id="goBack" class="btn btn-warning text-center">Back</button>

                        </div>
                    </div>  
                </form>                  
             </div>
        </div>
      <div class="col-sm-2 col-md-3"></div>
   </div>
</div>


<div class="row comment"></div>

<?php
  include_once(BASEPATH.'footer.php');
?>
