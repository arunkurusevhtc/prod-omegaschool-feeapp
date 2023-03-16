<?php
require_once('admnavbar.php');

if(isset($_SESSION['selectedstudid'])){
  $studentNames=array();
  $selectedid=$_SESSION['selectedstudid'];
  for($i=0; $i < count($selectedid); $i++){
    $query = 'SELECT *,clid AS class FROM challanData WHERE "studentId"= \'' . $selectedid[$i] . '\' AND "challanStatus"=\'0\' ';
    $res = sqlgetresult($query,true);
    $nochallan= 0;
    if(($res) == 0) {
    $query = 'SELECT * FROM fetchstudentdata WHERE "studentId"= \'' . $selectedid[$i] . '\' ';
    $res = sqlgetresult($query,true);
    $nochallan= 1;
    }
    array_push($studentNames, $res[0]['studentName']);
  }
}
else{
  $id=$_REQUEST['id'];
  $query = 'SELECT *,clid AS class FROM challanData WHERE "studentId"= \'' . $id . '\' AND "challanStatus"=\'0\' ';
  $res = sqlgetresult($query,true);
  $nochallan= 0;
  if(($res) == 0) {
      $query = 'SELECT * FROM fetchstudentdata WHERE "studentId"= \'' . $id . '\' ';
      $res = sqlgetresult($query,true);
      $nochallan= 1;
  }
}
$semestercheck = sqlgetresult('SELECT semester FROM semestercheck WHERE "status" = \'ACTIVE\'');
?>
<div class="container-fluid promotionclass">
   <div class="row">

      <div class="col-sm-2 col-md-3"></div>
      <div class="col-sm-8 col-md-6">
         <div class="tech-content">
            <p class="heading">Update Status</p>
          
               <form id="demandCreation" method="post" class="form-horizontal" action="adminactions.php">
                  <input name="id" type="hidden" value="<?php echo $res[0]['id'];?>" />
                  <input name="studentId" id="studentId" type="hidden" value="<?php echo $res[0]['studentId'];?>" />
                  <input name="studentName" id="studentName" type="hidden" value="<?php echo $res[0]['studentName'];?>">
                  <input name="semester" id="semester" type="hidden" value="<?php echo $res[0]['term'];?>">
                  <input name="class" id="class" type="hidden" value="<?php echo $res[0]['class'];?>">
                  <input name="stream" id="stream" type="hidden" value="<?php echo $res[0]['stream'];?>">
                  <input name="academicyear" id="academicyear" type="hidden" value="<?php echo $res[0]['academic_yr'];?>">
                  <input name="semestercheck" id="semestercheck" type="hidden" value="<?php echo $semestercheck['semester'];?>">
                  <input type="hidden" name="selectedid" id="selectedid" value="<?php echo json_encode($_SESSION['selectedstudid']);?>">
                    <div class="form-group">
                        
                        
                          <?php
                         
                          if(!isset($_SESSION['selectedstudid'])){
                            echo('<label class="control-label col-sm-4" for="studStatus">NAME:</label>');
                            echo('<div class="col-sm-8">');
                            echo($res[0]['studentName']);
                            echo('</div>');
                          }
                          else{
                            echo('<label class="control-label col-sm-4" for="studStatus">NAME:</label>');
                            echo('<div class="col-sm-8">');
                            // print_r($studentNames);
                            echo(implode(",",$studentNames));
                            echo('</div>');
                          }
                          ?>
                            
                        
                    </div>  
                    <div class="form-group" id= "promotionclassselect">
                      <label for ="class" class="control-label col-sm-4">Class</label>
                      <div class="col-sm-8">
                        <select class="form-control classselect classchange" name="promotedclass" required>
                          <option value="">--SELECT--</option>
                          <?php
                          $classlist = sqlgetresult('SELECT c."displayOrder",c.id,c.class_list FROM tbl_class c LEFT JOIN tbl_student s ON c.id = s.class::int WHERE s.stream = \''.$res[0]['stream'].'\' AND c.status =  \''. 1 .'\' GROUP BY c.id ORDER BY c."displayOrder" ',true);

                          foreach ($classlist as $value) {                    
                            if (trim($res['class']) == trim($value['id']) )
                            echo '<option value="'.$value['id'].'" selected>'.$value['class_list'].'</option>';
                            else
                            echo '<option value="'.$value['id'].'">'.$value['class_list'].'</option>';            
                          }
                          ?>
                        </select>
                      </div>
                    </div>                    
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="studStatus">STATUS:</label>
                        <div class="col-sm-8">
                             <!-- <select name="studStatus" id="studStatus" class="form-control">
                                <option value="">--SELECT--</option>
                                <option value="PROMOTED">PROMOTED</option>
                                <option value="HOLD">HOLD</option>
                                <option value="DETAINED">DETAINED</option>
                            </select> -->
                          <h4>Provisional Promoted</h4>
                        </div>
                    </div>                    

                      <div id='demandData'>
                
                                <div class="form-group text-center">
                                   
                                    <input type="hidden" name="showChallan" value="showChallan" >
                                    <button type="submit" name="updatestatus" value="updatestatus" class="btn btn-primary text-center">Update Status</button>
                                    <button type="button" value="Goback" name="Goback" id="goBack" class="btn btn-warning text-center">Back</button>
                                </div>
                      </div>  
                    </form>
             </div>
        </div>
      <div class="col-sm-2 col-md-3"></div>
   </div>
</div>


<div class="row comment">
       
</div>
<?php
include_once(BASEPATH.'footer.php');
?>
