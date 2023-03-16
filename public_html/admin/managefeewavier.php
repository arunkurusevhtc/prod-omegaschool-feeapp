 <?php
    include_once('admnavbar.php');
?>

<div>
   <div class="row col-md-12">
         <div class="col-xs-1 col-sm-2 col-md-3 col-lg-4"></div>
         <div class="col-xs-10 col-sm-8 col-md-6 col-lg-4">
               

                    
                     <?php
                  if(isset($_SESSION['successwavier'])) {
                  echo $_SESSION['successwavier'];
                  unset($_SESSION['successwavier']);
                      }
                  if(isset($_SESSION['errorwavier'])) {
                  echo $_SESSION['errorwavier'];
                  unset($_SESSION['errorwavier']);
                      }

                    ?>
         </div>
         <div class="col-xs-1 col-sm-2 col-md-3 col-lg-4"></div>
   </div>   
  <div class="row col-md-12">
        <div class="col-md-6">
          <h2 class="top">FEE WAIVER</h2>
        </div>
     <div class="col-md-6">
              <form id="filterform"  class="form-inline">
                <div id="waviererror">
                </div>
                <div class="form-group">
                  <!-- <label for="studStatus">Stream</label> -->
                  <div>
                    <?php
                    $streamtypes = sqlgetresult("SELECT * FROM streamcheck",true);
                    ?>
                    <select name="streamselect"  class="streamselect form-control">
                      <option value="">Stream</option>
                      <?php
                      foreach($streamtypes as $stream) {
                      echo '<option value="'.$stream['id'].'" >'.$stream['stream'].'</option>';
                      }
                      ?>
                    </select>
                  </div>
                </div> 

                <div class="form-group">
                  <!-- <label for="studStatus">Class</label> -->
                  <div>
                    <?php
                    $classstypes = sqlgetresult("SELECT * FROM classcheck",true);
                    ?>
                    <select name="classselect"  class="classselect  form-control">
                      <option value="">Class</option>
                      <?php
                      foreach($classstypes as $class) {
                      echo '<option value="'.$class['id'].'">'.$class['class_list'].'</option>';
                      }
                      ?>
                    </select>
                  </div>                                    
                </div>

               <div class="form-group">
                  <!-- <label for="studStatus">Class</label> -->
                  <div>
                    <?php
                    $sectiontypes = sqlgetresult("SELECT DISTINCT section FROM tbl_student ORDER BY section ASC",true);
                    ?>
                    <select name="sectionselect"  class="sectionselect  form-control">
                      <option value="">Section</option>
                      <?php
                      foreach($sectiontypes as $section) {
                       echo '<option value="'.$section['section'].'">'.$section['section'].'</option>';
                      }
                      ?>
                    </select>
                  </div> 
                </div>
                <button type="submit" name="filter" value="filterbut" class="btn btn-info">Filter</button>
              </form>
  </div>
</div>
<div style="clear:both">&nbsp;</div>
<div class="col-md-12">
     <div class="table-responsive">
        <table class="table table-bordered admintab dataTableWaiver">
           <thead>
              <tr>
              <!-- <th>  
               <input type="checkbox" id="checkAll" name="checkme<?php echo $i;?>" value="<?php echo $wave["challanNo"];?>">
              </th> -->
              <th>S.No</th>
              <th>Student Name</th>
              <th>Stream</th>
              <th>Class </th>
              <th>Section</th>
              <th>Term</th>
              <th>Total</th>
              <th>Waived</th>
              <th>Grand Total</th>
              <th>Waived Groups</th>
              <th>Action</th>
             
              </tr>
           </thead>

           <tbody>

              <?php
              $count=1;
              $sql = 'SELECT * FROM waviercheck';
              $res = sqlgetresult($sql,true);
            $challanData = array();
            $challanNo  = '';
            $feeData = array();           
    
              $num = ($res!= null) ? count($res) : 0;
              if($num > 0)
              {
                foreach ($res as $k => $data) {
                  $challanData[$data['challanNo']]['studentName'] = $data['studentName'];
                  $challanData[$data['challanNo']]['stream'] = $data['stream'];
                  $challanData[$data['challanNo']]['waived'][] = $data['waived'];
                  $challanData[$data['challanNo']]['term'] = $data['term'];
                  $challanData[$data['challanNo']]['section'] = $data['section'];
                  $challanData[$data['challanNo']]['studentId'] = $data['studentId'];
                  $challanData[$data['challanNo']]['challanNo'] = $data['challanNo'];
                  $challanData[$data['challanNo']]['feeTypes'][] = trim($data['feeTypes']);
                  $challanData[$data['challanNo']]['class_list'] = $data['class_list'];   
                  $challanData[$data['challanNo']]['org_total'][] = $data['org_total']; 
                  $challanData[$data['challanNo']]['total'][] = $data['total']; 
                  $challanData[$data['challanNo']]['waivedPercentage'][] = $data['waivedPercentage'];  
                  $challanData[$data['challanNo']]['waivedAmount'][] = $data['waivedAmount'];  
                  $challanData[$data['challanNo']]['waivedTotal'][] = $data['waivedTotal'];
                  $challanData[$data['challanNo']]['feeGroup'][] = @$data['feeGroup'];

                    if($data['waivedTotal'] != 0){
                        $challanData[$data['challanNo']]['waivedgroups'][]= $data['feeGroup'];
                    }         
                }

              foreach ($challanData as $wave)
              {
              ?>
                 <tr>     
                   
                    <td><?php echo $count;?></td>
                    <td><?php echo trim($wave["studentName"]); ?></td>
                    <td><?php echo trim($wave["stream"]); ?></td>
                    <td><?php echo trim($wave["class_list"]); ?></td>
                    <td><?php echo trim($wave["section"]); ?></td>
                    <td><?php echo trim($current_term); ?></td>
                    <td class="text-right"><?php echo array_sum($wave['total']); ?></td>
                    <td class="text-right"><?php echo array_sum($wave['waivedTotal']); ?></td>
                    <td class="text-right"><?php echo array_sum($wave['org_total']); ?></td>
                    <td>
                    <?php if(isset($wave['waivedgroups']) &&  $wave['waivedgroups'] != 0){
                      echo implode(", ",$wave['waivedgroups']);
                    }
                    else{
                      echo('NULL');
                    } 
                    ?>
                      
                    </td>

                     <td class="fafa"><a href="#myModal" data-toggle="modal" name="wavier" id="<?php echo($wave["challanNo"]);?>" class="feewaivermodal feewavier"><i class="fa fa-edit"></i></a></td>
                 </tr>
                 <?php $count++;
                 } 
              }
              // else {
              //        echo "<tr ><td colspan='10' class='text-center' >No Data Avaiable.</td></tr>";
              //       }
                 ?>
           </tbody>
        </table>
     </div>
  </div>
</div>
<div class="row comment">
       
</div>

<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close closed" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add Waiving Amount</h4>
            </div>
            <div class="modal-body con">
                <h4 class="addtitle">Please provide the Waving Amount Details.</h4>
                <form name="addstudent" class="form-horizontal" id="addstudent"  action="adminactions.php" method="post">
                  <input type="hidden" class="id" name="id">
                   <!-- <input type="text" class="total" name="total" value="<?php echo($par["total"]);?>"> -->
                    <div class="form-group">
                            <label class="control-label col-sm-4" for="studStatus">Select the Fee Group you need to Waiver:</label>
                            <div class="selectwavier col-sm-8">
                                
                        </div>                                    
                    </div>
                    <div class="form-group">
                            <label class="control-label col-sm-4" for="studStatus">Amount (INR):</label>
                            <div class="groupamount col-sm-8">
                                <input type="number" name="groupamount" id="groupamount" placeholder="Group Amount" class="form-control" readonly>
                            </div>                                    
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="email">Waiving Percentage (%):</label>
                        <div class="col-sm-8">
                            <input name="WavingPercentage" title="Student ID from current challan" id="WavingPercentage" type="number" placeholder="Waving Percentage" class="form-control" autofocus="">
                        </div>
                    </div> 
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="email">Waiving Amount:(INR)</label>
                        <div class="col-sm-8">
                            <input name="WavingAmount" title="Student ID from current challan" id="WavingAmount" type="text" placeholder="Waving Amount" class="form-control" autofocus="">
                    </div>
                    </div>                    
                    <div class="text-center">
                        <button class="btn btn-default closed"  type="button" data-dismiss="modal">Close</button>
                        <input class="btn btn-primary" id="addMyStudent" type="submit" name="addstudent" value="Update Amount">
                
                    </div>                    
                </form>
            </div>
        </div>
    </div>
</div>
<?php
    





include_once(BASEPATH.'footer.php');
?>