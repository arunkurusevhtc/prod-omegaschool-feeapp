<?php
require_once('admnavbar.php');
// print_r($_SESSION);
// unset($_SESSION['selectedchallans']);
if(isset($_SESSION['selectedchallans'])){
    $studentNames=array();
$selectedchallan=$_SESSION['selectedchallans'];
for($i=0; $i < count($selectedchallan); $i++){

$query = 'SELECT * FROM tbl_temp_challans c LEFT JOIN tbl_student s ON c."studentId" = s."studentId" WHERE c."studentId"=\'' . $selectedchallan[$i] . '\' ';

$res = sqlgetresult($query,true);
array_push($studentNames, $res[0]['studentName']);
}
$id=20;
// print_r($_SESSION);
}
else{
$id=$_REQUEST['id'];
// exit;
$query = 'SELECT * FROM tbl_temp_challans c LEFT JOIN tbl_student s ON s."studentId" = c."studentId" WHERE s."studentId"=\'' . $id . '\' ';
$res = sqlgetresult($query,true);
}
// print_r($res);
?>
<div class="container-fluid challancreate">
   <div class="row">
      <div class="col-sm-2 col-md-3"></div>
      <div class="col-sm-8 col-md-6">
         <div class="tech-content">
            <p class="heading">Edit Temporary Challan</p>          
               <form id="demandCreation" method="post" class="form-horizontal" action="adminactions.php">
                    <?php
                        if(isset($id)) {
                    ?>
                  <input name="id" type="hidden" value="<?php echo $res[0]['id'];?>" />
                  <input name="challan" type="hidden" value="<?php echo $res[0]['challanNo'];?>">
                  <input name="studentId" id="studentId" type="hidden" value="<?php echo $res[0]['studentId'];?>" />
                  <input name="studentName" id="studentName" type="hidden" value="<?php echo $res[0]['studentName'];?>">
                  <input name="semester" id="semester" type="hidden" value="<?php echo $res[0]['term'];?>">
                  <input name="class_list" id="class_list" type="hidden" value="<?php echo $res[0]['classList'];?>">
                  <input name="academic" id="academic" type="hidden" value="<?php echo $res[0]['academic_yr'];?>">
                  <input name="stream" id="stream" type="hidden" value="<?php echo $res[0]['stream'];?>">
                  <?php
                    }else{
                  ?>
                  <input name="studentName" id="studentName" type="hidden" value="<?php echo trim(implode(",",$studentNames)); ?>">
                  <input name="id" type="hidden" value="<?php echo $res[0]['id'];?>" />
                  <input name="challan" type="hidden" value="<?php echo $res[0]['challanNo'];?>">
                  <input name="studentId" id="studentId" type="hidden" value="<?php echo $res[0]['studentId'];?>" />
                  <input name="studentName" id="studentName" type="hidden" value="<?php echo $res[0]['studentName'];?>">
                  <input name="semester" id="semester" type="hidden" value="<?php echo $res[0]['term'];?>">
                  <input name="class_list" id="class_list" type="hidden" value="<?php echo $res[0]['classList'];?>">
                  <input name="academic" id="academic" type="hidden" value="<?php echo $res[0]['academic_yr'];?>">
                  <input name="stream" id="stream" type="hidden" value="<?php echo $res[0]['stream'];?>">
                    <?php
                    }
                    ?>
                    <div class="form-group">
                        <?php
                         
                          if(!isset($_SESSION['selectedchallans'])){
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
                          
                    <div id='demandData' class="feetab1">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="date">DUE DATE:</label>
                            <div class="col-sm-8">
                                <input type="text" class="duedate datepicker form-control" name="duedate" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="class">CLASS:</label>
                            <div class="col-sm-8">
                                <input type="text" name="class" id="<?php echo ($res[0]['classList']);?>" class="classlist form-control" value="<?php echo getClassbyNameId(($res[0]['classList']));?>"  readonly required>
                            </div>                                    
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="semester">SEMESTER:</label>
                            <div class="col-sm-8">
                                <input type="text" name="semester" id="semester" class="semester form-control" value="<?php echo ($current_term);?>" readonly required>
                            </div>                                    
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="status">STATUS:</label>
                            <div class="col-sm-8">
                                <input type="text" name="status" id="status" class="status form-control" value="<?php echo ($res[0]['studStatus']);?>" readonly required>
                            </div>                                    
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="studStatus">FEE TYPES:</label>
                            <div class="col-sm-8">
                                 <?php
                                    if( $res[0]['hostel_need'] == 'Y') {
                                        $feeTypes = sqlgetresult("SELECT * FROM getfeetypes WHERE applicable='DH' OR applicable='H'");
                                    } else {
                                        $feeTypes = sqlgetresult("SELECT * FROM getfeetypes WHERE applicable='DH' OR applicable='D' ");
                                    }
                                    // print_r($feeTypes);
                                ?>
                                <input type="hidden" name="selected_feetypes" class="selected_quizsetids">
                                <select name="feetype"  class="quizsetid form-control" multiple="multiple" required>
                                    <?php
                                        foreach($feetypesgroup as $feegroup){
                                            echo '<optgroup label="'.$feegroup.'"></optgroup>';
                                            foreach($feeTypes as $feetype) {
                                                if($feegroup == trim($feetype['feeGroup'])) {
                                                    if (stripos($res[0]['studentId'], 'new') !== false) {
                                                      if($feetype['mandatory'] == 1){
                                                        echo '<option selected value="'.$feetype['id'].'">'.$feetype['feeType'].'</option>';
                                                      }
                                                      else{
                                                        echo '<option value="'.$feetype['id'].'">'.$feetype['feeType'].'</option>';
                                                      }
                                  
                                                    } else {
                                                        if(!in_array(trim($feetype['feeType']), $newstudent_feetypes)) {
                                                          if($feetype['mandatory'] == 1){
                                                        echo '<option selected value="'.$feetype['id'].'">'.$feetype['feeType'].'</option>';
                                                      }
                                                      else{
                                                        echo '<option value="'.$feetype['id'].'">'.$feetype['feeType'].'</option>';
                                                      }
                                                        }
                                                    }
                                                }    
                                            }                                    
                                        }
                                    ?>
                                </select>
                            </div>                                    
                        </div>
                        <div class="form-group ">
                                <label class="control-label col-sm-4" for="remarks">REMARKS:</label>
                                <div class="col-sm-8">
                                    <textarea placeholder="REMARKS" class="form-control remarks" name="remarks" required></textarea>
                                </div>
                        </div>
                        <div class="form-group text-center">
                          <!--   <a class="btn btn-primary" data-id="<?php echo $res['studentId'];?>" id="createChallan" href="#challanModal" data-toggle="modal">Create Challan</a> -->
                            <input type="hidden" name="updateTempChallan" value="updateTempChallan" >
                            <a href="#challanModal" data-toggle="modal"><button type="submit" name="updateTempChallan" value="updateTempChallan" class="btn btn-primary text-center" id="updateTempChallan">Create Temporary Challan</button></a>
                            <a href="managechallans.php"><button type="button" value="Go Back" class="btn btn-warning text-center">Back</button></a>
                        </div>
                    </div>  
                </form>                  
             </div>
        </div>
      <div class="col-sm-2 col-md-3"></div>
   </div>
</div>

<!-- Challan Modal -->
<div class="modal fade" id="challanModal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Fee Challan - Payment</h4>
            </div>
            <div class="modal-body">
                <p>Please review the details of  challan and confirm to continue...</p>
                <div class="table-responsive">
                    <form method="post" action="adminactions.php">
                        <div id="challanData1"></div>                
                        <div class="text-center">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" id="createchallan" name="createchallan" value="createchallan">Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!--- End of Challan Moadl  -->


<div class="row comment">
       
</div>



<?php
// unset($_SESSION['selectedchallans']);  






include_once(BASEPATH.'footer.php');
?>
