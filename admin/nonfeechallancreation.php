<?php
	require_once('admnavbar.php');
    unset($_SESSION['data']);
    if(isset($_SESSION['selectednonfeechallans'])) {
        $studentNames=array();
        $selectedchallan=$_SESSION['selectednonfeechallans'];
        for($i=0; $i < count($selectedchallan); $i++){
            $query = 'SELECT * FROM studentcheck WHERE "studentId"=\'' . $selectedchallan[$i] . '\' ';
            $res = sqlgetresult($query,true);
            array_push($studentNames, $res[0]['studentName']);
        }
    // print_r($_SESSION);
    } else if (isset($_GET['id'])) {
		$studID = $_GET['id'];
		$query = 'SELECT * FROM studentcheck WHERE "studentId"=\'' . $studID . '\' ';
		$res = sqlgetresult($query,true);
	} else {
		header("Location: addnonfeechallan.php");
	}
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
            <div class="msg"></div>
        </div>
        <div class="col-sm-2 col-md-3"></div>
    </div>
   <div class="row">
      <div class="col-sm-2 col-md-3"></div>
      <div class="col-sm-8 col-md-6">
         <div class="tech-content">
            <p class="heading">Create Non-fee Challan</p>          
               <form id="nonfeechallanform" method="post" class="form-horizontal" onsubmit="return changeAction();" action="adminactions.php">
                    <?php
                        if(isset($id)) {
                    ?>
                  <input name="id" type="hidden" value="<?php echo $res[0]['id'];?>" />
                  <input name="studentId" id="studentId" type="hidden" value="<?php echo $res[0]['studentId'];?>" />
                  <input name="studentName" id="studentName" type="hidden" value="<?php echo $res[0]['studentName'];?>">
                  <input name="semester" id="semester" type="hidden" value="<?php echo $res[0]['term'];?>">
                  <input name="class_list" id="class_list" type="hidden" value="<?php echo $res[0]['class'];?>">
                  <input name="academic" id="academic" type="hidden" value="<?php echo $res[0]['academic_yr'];?>">
                  <input name="stream" id="stream" type="hidden" value="<?php echo $res[0]['stream'];?>">
                  <?php
                    }else{
                  ?>
                  <input name="studentName" id="studentName" type="hidden" value="<?php echo trim(implode(",",$studentNames)); ?>">
                  <input name="id" type="hidden" value="<?php echo $res[0]['id'];?>" />
                  <input name="studentId" id="studentId" type="hidden" value="<?php echo $res[0]['studentId'];?>" />
                  <input name="studentName" id="studentName" type="hidden" value="<?php echo $res[0]['studentName'];?>">
                  <input name="semester" id="semester" type="hidden" value="<?php echo $res[0]['term'];?>">
                  <input name="class_list" id="class_list" type="hidden" value="<?php echo $res[0]['class'];?>">
                  <input name="academic" id="academic" type="hidden" value="<?php echo $res[0]['academic_yr'];?>">
                  <input name="stream" id="stream" type="hidden" value="<?php echo $res[0]['stream'];?>">
                    <?php
                    }
                    ?>
                    <div class="form-group">
                        <?php
                         
                          if(!isset($_SESSION['selectednonfeechallans'])){
                            echo('<label class="control-label col-sm-4" for="studStatus">NAME:</label>');
                            echo('<div class="col-sm-8">');
                            echo($res[0]['studentName']);
                            echo('</div>');
                          }
                          else{
                             echo('<label class="control-label col-sm-4" for="studStatus">NAME:</label>');
                            echo('<div class="col-sm-8">');
                            echo(implode(",",$studentNames));
                            echo('</div>');
                          }
                          ?>
                    </div>                 
                          
                    <div id='demandData' class="feetab1 edittech">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="date">DUE DATE:</label>
                            <div class="col-sm-8">
                                <input type="text" class="duedate datepicker form-control" name="duedate" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="class">CLASS:</label>
                            <div class="col-sm-8">
                                <input type="text" name="class" id="<?php echo ($res[0]['class']);?>" class="classlist form-control" value="<?php echo getClassbyNameId(($res[0]['class']));?>"  readonly required>
                            </div>                                    
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="semester">SEMESTER:</label>
                            <div class="col-sm-8">
                                <input type="text" name="semester" id="semester" class="semester form-control" value="<?php echo ($res[0]['term']);?>" readonly required>
                            </div>                                    
                        </div>                        
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="studStatus">NON-FEE TYPES:</label>
                            <div class="col-sm-8">
                                 <?php
                                    if( $res[0]['hostel_need'] == 'Y') {
                                        $feeTypes = sqlgetresult("SELECT * FROM tbl_nonfee_type WHERE applicable='DH' OR applicable='H'",true);
                                    } else {
                                        $feeTypes = sqlgetresult("SELECT * FROM tbl_nonfee_type WHERE applicable='DH' OR applicable='D' ",true);
                                    }
                                    // echo stripos($res[0]['studentId'], '2018');
                                    // print_r($feeTypes);
                                ?>
                                <input type="hidden" name="selected_feetypes" class="selected_quizsetids">
                                <select name="feetype"  class="quizsetid form-control" multiple="multiple" required>
                                    <?php
                                    $feetypesgroup = sqlgetresult("SELECT * FROM feegroupcheck",true);
                                    // print_r($feetypesgroup);                                   
                                        echo '<optgroup label="NON-FEE GROUP"></optgroup>';
                                        foreach($feeTypes as $feetype) {
                                            echo '<option value="'.$feetype['id'].'">'.$feetype['feeType'].'</option>';
                                        }                               
                                        
                                    ?>
                                </select>
                            </div>                                    
                        </div>
                        <div class="form-group ">
                            <label class="control-label col-sm-4" for="remarks">Enable Partial Payment</label>
                            <div class="col-sm-8"> 
                                <input type="checkbox" id="chkpartial"  name="chkpartial" value="1">
                            </div>
                        </div>
                        <div class="form-group instalments" style="display: none;">
                            <label class="control-label col-sm-4" for="remarks">No. of instalments</label>
                            <div class="col-sm-8">
                                <select name="instalment" id="instalment"  class="form-control">
                                    <?php
                                        for($i=2;$i<=6;$i++) {
                                            if($i==3){
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
                        <div class="form-group ">
                                <label class="control-label col-sm-4" for="remarks">REMARKS:</label>
                                <div class="col-sm-8">
                                    <textarea placeholder="REMARKS" class="form-control remarks" name="remarks" removeFromString maxlength="250"></textarea>
                                </div>
                        </div>
                        <div class="form-group ">
                                <label class="control-label col-sm-4" for="remarks">MAIL CONTENT:</label>
                                <div class="col-sm-8">
                                    <textarea placeholder="MAIL CONTENT" class="form-control mail_content" name="mail_content" rows="5" required=""></textarea>
                                </div>
                        </div>
                        <div class="form-group ">
                                <label class="control-label col-sm-4" for="remarks">SMS CONTENT:</label>
                                <div class="col-sm-8">
                                    <textarea placeholder="SMS CONTENT" class="form-control sms_content" name="sms_content" rows="5" required="" maxlength="120"></textarea>
                                </div>
                        </div>
                        <div class="form-group text-center"> 
                        	<input type="hidden" name="shownonfeechallan" value="shownonfeechallan" >                         
                            <button type="submit" name="shownonfeechallan" value="shownonfeechallan" class="btn btn-primary text-center" id="shownonfeechallan">Create Non-fee Challan</button>
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
