<?php
require_once('admnavbar.php');
$id=$_REQUEST['id'];
$query = 'SELECT * FROM tbl_challans c LEFT JOIN tbl_student s ON c."studentId" = s."studentId"  WHERE "challanNo"=\'' . $id . '\' ';
$res = sqlgetresult($query,true);
$feeData=array();
foreach($res as $result){
  $fee=explode(",",$result['feeTypes']);
  foreach ($fee as $v) {
    $feeData[] = $v;
  }
  $org=explode(",",$result['org_total']);
   foreach ($org as $va) {
    $org_total[] = $va;
  }
  $wav=explode(",",$result['waivedPercentage']);
   // print_r($wav);
   foreach ($wav as $va) {
    $wavied[] = $va;
  }
  $grp=explode(",",$result['feeGroup']);
   // print_r($wav);
   foreach ($grp as $gp) {
    $feegroup[] = $gp;
  }
}


// }
// print_r($feegroup);
// print_r($res);
$feegroups = trim(implode(",",$feegroup));
$selectedfeetypes = trim(implode(",",$feeData));
// print_r($feegroups);
// print_r($feeData);

?>
<div class="container-fluid">
   <div class="row">
      <div class="col-sm-2 col-md-3"></div>
      <div class="col-sm-8 col-md-6">
         <div class="tech-content">
            <p class="heading">Edit Challan</p>
          
               <form id="demandCreation" method="post" class="form-horizontal" action="adminactions.php">
                <input type="hidden"  name="status" id="status" value="<?php echo $res[0]['studStatus'];?>">
                  <input name="id" type="hidden" value="<?php echo $res[0]['id'];?>" />
                  <input name="challan" type="hidden" value="<?php echo $res[0]['challanNo'];?>">
                  <input name="studentId" id="studentId" type="hidden" value="<?php echo $res[0]['studentId'];?>" />
                  
                  <input name="semester" id="semester" type="hidden" value="<?php echo $res[0]['term'];?>">
                  <input name="class-list" id="class-list" type="hidden" value="<?php echo $res[0]['classList'];?>">
                  <input name="stream" id="stream" type="hidden" value="<?php echo $res[0]['stream'];?>">
                  <input name="oldduedate" id="oldduedate" type="hidden" value="<?php echo $res[0]['duedate'];?>">
                  <input name="oldremarks" id="oldremarks" type="hidden" value="<?php echo $res[0]['remarks'];?>">
                  <input name="academic" id="academic" type="hidden" value="<?php echo $res[0]['academicYear'];?>">
                  <input name="selectedfeetypes" id="selectedfeetypes" type="hidden" value="<?php print_r($selectedfeetypes);?>">
                   <input name="waived" id="waived" type="hidden" value="<?php print_r($wavied);?>">
                   <input name="org_total" id="org_total" type="hidden" value="<?php print_r($org_total);?>">
                  <input name="feegroup" id="feegroup" type="hidden" value="<?php print_r($feegroups);?>">

                    <div class="form-group">
                        <label class="control-label col-sm-4" for="studStatus">NAME:</label>
                        <div class="col-sm-8">
                            <p><?php echo getStudentNameById($res[0]['studentId']);?></p>
                        </div>
                    </div>                    
                          

                      <div id='demandData' class="feetab1">
                                <div class="row form-group">
                                    <label class="control-label col-sm-4" for="date">DUE DATE:</label>
                                    <div class="col-md-8">
                                        <input type="text" class="datepicker form-control" name="duedate" required value="<?php echo$res[0]['duedate'];?>">
                                    </div>
                                </div>
                                 <div class="form-group">
                                    <label class="control-label col-sm-4" for="streams">STREAM:</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="streams" id="streams" class="stream form-control" value="<?php echo getStreambyId(($res[0]['stream']));?>" readonly required>
                                    </div>                                    
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="class">CLASS:</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="class" id="class" class="class form-control" value="<?php echo getClassbyNameId(($res[0]['classList']));?>" readonly required>
                                    </div>                                    
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="semester">SEMESTER:</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="semester" id="semester" class="semester form-control" value="<?php echo ($res[0]['term']);?>" readonly required>
                                    </div>                                    
                                </div>
                               
                                <div class="form-group">
                                    <label class="control-label col-sm-4" for="studStatus">FEE TYPES:</label>
                                    <div class="col-sm-8">
                                      <input type="hidden" name="oldfeetype" id="oldfeetype" value="<?php echo $res[0]['feeTypes'];?>">
                                         <?php
                                            if( $res[0]['hostel_need'] == 'Y') {
                                                $feeTypes = sqlgetresult("SELECT * FROM getfeetypes WHERE applicable='DH' OR applicable='H'");
                                            } else {
                                                $feeTypes = sqlgetresult("SELECT * FROM getfeetypes WHERE applicable='DH' OR applicable='D' ");
                                            }
                                                    $selectedfeetypes = array_map('trim',$feeData);
                                            // print_r($feeTypes);                                           
                                        ?>
                                        <input type="hidden" name="selected_feetypes" class="selected_quizsetids">
                                        <select name="feetype"  class="quizsetid form-control" multiple="multiple" required>
                                    
                                            <?php
                                        foreach($feetypesgroup as $feegroup){
                                            echo '<optgroup label="'.$feegroup.'"></optgroup>';
                                            foreach($feeTypes as $type) {
                                              // print_r($feetype);
                                                if($feegroup == trim($type['feeGroup'])) {
                                                    if (stripos($res[0]['studentId'], 'new') !== false) {
                                                       if( in_array(trim($type['id']),$selectedfeetypes) ) {
                                                            echo '<option selected value="'.$type['id'].'">'.$type['feeType'].'</option>';
                                                        } else {
                                                            echo '<option  value="'.$type['id'].'">'.$type['feeType'].'</option>';
                                                        }                                                        
                                                    } else {
                                                       if(!in_array(trim($feetype['feeType']), $newstudent_feetypes)) {
                                                            if( in_array(trim($type['id']),$selectedfeetypes) ) {
                                                                echo '<option selected value="'.$type['id'].'">'.$type['feeType'].'</option>';
                                                            } else {
                                                                echo '<option  value="'.$type['id'].'">'.$type['feeType'].'</option>';
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
                                            <textarea placeholder="REMARKS" class="form-control remarks" name="remarks" required><?php echo trim($res[0]['remarks']);?></textarea>
                                        </div>
                                </div>
                                <div class="form-group text-center">
                                   
                                    <input type="hidden" name="editcreatedchallans" value="editcreatedchallans" >
                                    <button type="submit" name="editcreatedchallans" value="editcreatedchallans" class="btn btn-primary text-center" id="editcreatedchallans">Edit Challan</button>
                                    <a href="managecreatedchallans.php"><button type="button" value="Go Back" class="btn btn-warning text-center">Back</button></a>
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
