<?php
/*error_reporting(E_ALL);
ini_set('display_errors', '1');*/
require_once('admnavbar.php');
unset($_SESSION['data']);

$studentNames=array();
$className=array();
$semester=array();
$studentIds=array();
$st_name="";
$st_class="";
$st_sem="";
$st_academic_yr="";
$st_stream="";
$type='';
$st_hostel_need="";
if(isset($_GET['id']) && !empty($_GET['id'])){
    $type='single';
    $studID = trim($_GET['id']);
    $query = 'SELECT * FROM studentcheck WHERE "studentId"=\'' . $studID . '\' ';
    $res = sqlgetresult($query,true);
    $id=isset($res[0]['id'])?trim($res[0]['id']):"";
    $st_name=isset($res[0]['studentName'])?trim($res[0]['studentName']):"";
    $st_class=isset($res[0]['class'])?trim($res[0]['class']):"";
    $st_sem=isset($res[0]['term'])?trim($res[0]['term']):"";
    $st_academic_yr=isset($res[0]['academic_yr'])?trim($res[0]['academic_yr']):"";
    $st_stream=isset($res[0]['stream'])?trim($res[0]['stream']):"";
    $st_hostel_need=isset($res[0]['hostel_need'])?trim($res[0]['hostel_need']):"";
    array_push($studentNames, $st_name);
    array_push($className, $st_class);
    array_push($semester, $st_sem);
}else if(isset($_SESSION['selectedfeechallans'])) {
    $type='bulk';
    $selectedchallan=$_SESSION['selectedfeechallans'];
    for($i=0; $i < count($selectedchallan); $i++){
        $query = 'SELECT * FROM studentcheck WHERE "studentId"=\'' . $selectedchallan[$i] . '\' ';
        $res = sqlgetresult($query,true);
        $st_id=isset($res[0]['studentId'])?trim($res[0]['studentId']):"";
        $st_name=isset($res[0]['studentName'])?trim($res[0]['studentName']):"";
        $st_class=isset($res[0]['class'])?getClassbyNameId(trim($res[0]['class'])):"";
        $st_sem=isset($res[0]['term'])?trim($res[0]['term']):"";
        //array_push($studentIds, $st_id);
        array_push($studentNames, $st_name);
        array_push($className, $st_class);
        array_push($semester, $st_sem);
    }
}else {
    header("Location: addfeechallan.php");
}


/* Active Academic Year*/
$yr = sqlgetresult("select id,year from tbl_academic_year where active=1 LIMIT 1");
$academicId = isset($yr['id'])?trim($yr['id']):"";
$academicYear = isset($yr['year'])?trim($yr['year']):"";

if(count($studentNames) > 0){
 $name=implode(",",$studentNames);
}else{
  $name="";  
}

if(count($className) > 0){
 $class=implode(",",array_unique($className));
}else{
  $class="";  
}

if(count($semester) > 0){
 $sem=implode(",",array_unique($semester));
}else{
  $sem="";  
}

if(count($studentIds) > 0){
 $sids=implode(",",$studentIds);
}else{
  $sids="";  
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
            <p class="heading">Create Fee Challan</p>          
               <form id="feechallanform" method="post" class="form-horizontal" onsubmit="return changeAction();" action="adminactions.php">
                 <input name="academicId" id="academicId" type="hidden" value="<?php echo $academicId; ?>">
                 <input name="type" type="hidden" value="<?php echo $type; ?>" />
                    <?php
                        if(isset($studID)) {
                    ?>
                  <input name="id" type="hidden" value="<?php echo $id; ?>" />
                  <input name="studentId" id="studentId" type="hidden" value="<?php echo $studID; ?>" />
                  <input name="studentName" id="studentName" type="hidden" value="<?php echo $st_name;?>">
                  <input name="semester" id="semester" type="hidden" value="<?php echo $st_sem;?>">
                  <input name="class_list" id="class_list" type="hidden" value="<?php echo $st_class;?>">
                  <input name="academic" id="academic" type="hidden" value="<?php echo $st_academic_yr; ?>">
                  <input name="stream" id="stream" type="hidden" value="<?php echo $st_stream; ?>">
                  <?php
                    }else{
                  ?>
                  <input name="studentId" id="studentId" type="hidden" value="<?php echo $st_id; ?>" />
                  <input name="studentName" id="studentName" type="hidden" value="<?php echo $st_name; ?>">
                  <input name="class_list" id="class_list" type="hidden" value="<?php echo $st_class;?>">
                  <input name="semester" id="semester" type="hidden" value="<?php echo $st_sem;?>">
                    <?php
                    }
                    ?>
                    <div class="form-group">
                        <?php
                           echo('<label class="control-label col-sm-4" for="studStatus">NAME:</label>');
                            echo('<div class="col-sm-8">');
                            echo($name);
                            echo('</div>');
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
                            <label class="control-label col-sm-4" for="class">Academic Year:</label>
                            <div class="col-sm-8">
                                <input type="text" name="academicYear" id="<?php echo ($academicYear);?>" class="academicYear form-control" value="<?php echo $academicYear;?>"  readonly required>
                            </div>                                    
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="class">CLASS:</label>
                            <div class="col-sm-8">
                                <input type="text" name="class" id="<?php echo $class;?>" class="classlist form-control" value="<?php echo $class;?>"  readonly required>
                            </div>                                    
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="semester">SEMESTER:</label>
                            <div class="col-sm-8">
                                <input type="text" name="semester" id="semester" class="semester form-control" value="<?php echo $sem;?>" readonly required>
                            </div>                                    
                        </div>                        
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="studStatus">FEE TYPES:</label>
                            <div class="col-sm-8">
                                 <?php
                                   if($type=='single'){
                                    if($st_hostel_need == 'Y') {
                                        $feeTypes = sqlgetresult('SELECT id, "feeType" FROM getfeetypes WHERE applicable=\'DH\' OR applicable=\'H\' ',true);
                                    } else {
                                        $feeTypes = sqlgetresult('SELECT id, "feeType" FROM getfeetypes WHERE applicable=\'DH\' OR applicable=\'D\' OR applicable  = \'0\' ',true);
                                    }
                                   }else{
                                     $feeTypes = sqlgetresult('SELECT id, "feeType" FROM getfeetypes WHERE applicable=\'DH\' OR applicable=\'D\' OR applicable=\'H\' OR applicable  = \'0\' ',true);
                                   }
                                    // echo stripos($res[0]['studentId'], '2018');
                                    // print_r($feeTypes);
                                ?>
                                <input type="hidden" name="selected_feetypes" class="selected_quizsetids">
                                <select name="feetype"  class="quizsetid form-control" multiple="multiple" required>
                                    <?php
                                    $feetypesgroup = sqlgetresult("SELECT * FROM feegroupcheck",true);
                                    // print_r($feetypesgroup);                                   
                                        echo '<optgroup label="FEE GROUP"></optgroup>';
                                        foreach($feeTypes as $feetype) {
                                            echo '<option value="'.$feetype['id'].'">'.$feetype['feeType'].'</option>';
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
                        	<input type="hidden" name="showfeechallan" value="showfeechallan" >                         
                            <button type="submit" name="showfeechallan" value="showfeechallan" class="btn btn-primary text-center" id="showfeechallan">Create Fee Challan</button>
                            <button type="button" value="back" name="back" id="goBack" class="btn btn-warning text-center">Back</button>
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
<div class="row comment"></div>
<?php
	include_once(BASEPATH.'footer.php');
?>
