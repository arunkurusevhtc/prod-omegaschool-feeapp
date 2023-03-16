<?php
    include_once('admnavbar.php');
    $studData = sqlgetresult('SELECT *,c."createdOn"::date FROM tbl_challans c LEFT JOIN tbl_student s ON s."studentId" = c."studentId" ORDER BY c."classList" , c."term" ASC
    ',true);

      // print_r($studData);
    $count = ($studData == null ) ? 0 : count($studData) ;
    $challanData = array();
       $total =0;
       $tot = 0;
       $challanNo  = '';
       $feeData = array();
       if($count != 0) {
        // echo("hello");
           foreach ($studData as $k => $data) {
               $challanData[$data['challanNo']]['studentName'] = $data['studentName'];
               $challanData[$data['challanNo']]['stream'] = $data['stream'];
               $challanData[$data['challanNo']]['section'] = $data['section'];
               $challanData[$data['challanNo']]['term'] = $data['term'];
     
               $challanData[$data['challanNo']]['challanNo'] = $data['challanNo'];
               $challanData[$data['challanNo']]['feeTypes'][] = trim($data['feeTypes']);
               $challanData[$data['challanNo']]['classList'] = $data['classList'];   
               $challanData[$data['challanNo']]['duedate'] = $data['duedate']; 
               $challanData[$data['challanNo']]['org_total'][] = $data['org_total'];  
               $challanData[$data['challanNo']]['createdOn'] = $data['createdOn'];    
  
   
        
           }
           // print_r($challanData);
           $total = 0;
           $data =  array();
           foreach ($challanData as $feeData) {
               $feeTypes = sqlgetresult('SELECT * FROM getfeetypedata WHERE semester = \''.$current_term.'\' AND class = \''.$feeData['classList'].'\' AND stream = \''.$feeData['stream'].'\'',true);
               // print_r($feeTypes);
    
               $fee = implode(',',$feeData['feeTypes']); 
               $feeData['feeTypes'] = explode(',',$fee);
    
               foreach ($feeData['feeTypes'] as $v) {
                    foreach($feeTypes as $val) {
                      // $feeData['createdOn'] = $val['createdOn'];
                       if(in_array(trim($v), $val)) {
                           $total  += $val['amount'];
                       }
                   }            
               }
               $feeData['fee'] = $total;
               $total = 0;
               $data[] = $feeData;
           }    
       } 
           else {
           $data = array();
       }
    ?>
<div class="container-fluid">
    <div class="row col-md-12">
        <div class="col-xs-1 col-sm-2 col-md-3 col-lg-2"></div>
        <div class="col-xs-10 col-sm-8 col-md-6 col-lg-8">  
            <?php
                if(isset($_SESSION['successdelete'])) {
                   echo $_SESSION['successdelete'];
                    unset($_SESSION['successdelete']);
                } elseif(isset($_SESSION['errordelete'])) {
                   echo $_SESSION['errordelete'];
                   unset($_SESSION['errordelete']);
                } elseif(isset($_SESSION['failure'])) {
                   echo $_SESSION['failure'];
                   unset($_SESSION['failure']);
                }elseif(isset($_SESSION['success'])) {
                   echo $_SESSION['success'];
                   unset($_SESSION['success']);
                }elseif(isset($_SESSION['successchallan'])) {
                   echo $_SESSION['successchallan'];
                   unset($_SESSION['successchallan']);
                }
                ?>
        </div>
        <div class="col-xs-1 col-sm-2 col-md-3 col-lg-2"></div>
    </div>
    <!-- <h2 class="top">CHALLAN PAGE</h2> -->
    <!-- <form  method="post" action="adminactions.php"> -->
</div>
<div class="col-md-12 ">
    <div class="col-md-6 p-l-0">
        <h2 class="top">CHALLANS</h2>
    </div>
    <div class="col-md-6 p-r-0 text-right">
        <form  id="filtercreatedchallans" class="form-inline">
            <div class="form-group">
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
            <button type="submit" name="filter" value="filternewchallan" class="btn btn-info">Filter</button>
        </form>
    </div>
</div>
<div class="col-md-12 m-t-15" >
    <div class="table-responsive">
        <form>
            <table class="table table-bordered admintab dataTableTeachers">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Challan No</th>
                        <th>Name</th>
                        <th>Stream</th>
                        <th>Class</th>
                        <th>Section</th>
                        <th>Term</th>
                        <th>Created Date</th>
                        <th>Due Date</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $i=1;
                        // print_r($data);
                        
                        // if(count($data) > 0) { 
                        $num = ($data!= null) ? count($data) : 0;
                        if($num > 0)
                        {      

                        // print_r($data);   
                        foreach ($data as $data) {
                          ?>
                          <!-- date("d-m-Y", strtotime($data['createdOn'])) -->
                    <tr class="filterrowchallan">
                        <td><?php echo $i;?></td>
                        <td><?php echo $data['challanNo'];?></td>
                        <td><?php echo $data['studentName'];?></td>
                        <td><?php echo getStreambyId($data['stream']);?></td>
                        <td><?php echo getClassbyNameId($data['classList'])?></td>
                        <td><?php echo $data['section'];?></td>
                        <td><?php echo $current_term;?></td>
                        <td><?php print_r(date("d-m-Y", strtotime($data['createdOn'])));?></td>
                        <td><?php print_r(date("d-m-Y", strtotime($data['duedate'])));?></td>
                        <td class="text-right"><?php echo (array_sum($data['org_total']));?></td>
                        <td class="fafa">
                            <a href="editcreatedchallans.php?id=<?php echo $data["challanNo"]; ?>"><i class="fa fa-edit"></i></a>
                            <a href="adminactions.php?actions=delete&id=<?php echo $data["challanNo"]; ?>"><i class="fa fa-trash-o"></i></a> 
                        </td>
                    </tr>
                    <?php 
                        $i++;
                        }
                        } 
                        // else {
                        //            echo "<tr><td colspan='11' style='text-align:center;'>No Data Avaiable.</td></tr>";
                        //           }
                        ?>
                </tbody>
            </table>
        </form>
    </div>
</div>
</div>
<div class="row comment">
</div>
<?php


    include_once('..\footer.php');
    ?>