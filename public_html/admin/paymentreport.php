<div class="row comment">

</div>
<?php
    include_once('admnavbar.php');   
    if(isset($_SESSION['success_msg']) && $_SESSION['success_msg'] !='') {
        echo $_SESSION['success_msg'];
        unset($_SESSION['success_msg']);
    } elseif (isset($_SESSION['error_msg']) && $_SESSION['error_msg'] !='') {
        echo $_SESSION['error_msg'];
        unset($_SESSION['error_msg']);
    }
?>
    <div class="container-fluid  paymentReport">

    </div>

 
       <div class="table-responsive col-md-12 feetab container">     
     <form id="paid_details" class="form-inline">
               <div class="row text-right">
                        <div class="col-md-6 m-b-15">
                            <h2 class="fer">PAYMENT REPORT</h2>
                        </div> 
                        <div class="col-md-6 m-b-15">
                      <div class="fromto">
                        <!-- <label for ="from" class="rdate">From</label> -->
                        <input name="datetimes" id="drp" />
                        <button class="btn btn-primary" id="cToexcel" type="exporttoexcel" name="ctoe" value="ctoe">Convert to excel</button>
                    </div>
                </div>
              </div>
            <div class="row col-md-12">

             <div class="col-md-6  p-l-0"></div>
                <div class="col-md-6 p-r-0 text-right">
      
                    <div class="form-group">
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
                    <div class="form-group">
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


                  <div class="form-group">
                  <!-- <label for="studStatus">Class</label> -->
                  
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

                    <button type="submit" name="filter" value="filterpayment" class="btn btn-info">Filter</button>
               </div>
            </div>
     </form>  
   
                      <div style="clear:both;">
                        </div>
               
        <table class="table table-bordered table-responsive admintab paidreport">
            <thead>
                <tr>
                    <th>S.No</th>
                    <th>Student Id</th>
                    <th>Challan No</th>
                    <th>Student Name</th>
                    <th>Parent Name</th>
                     <th>Stream</th>
                    <th>Class</th>
                   
                    <th>Section</th>
                    <th>Transaction Date</th>
                    <th>Transaction Number</th>
                    <th>Transaction Status</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>

                 <?php
              $count=1;
              $sql = 'SELECT * FROM getpaiddata';
              $res = sqlgetresult($sql,true);
              $num = ($res!= null) ? count($res) : 0;
              if($num > 0)
              {

              foreach ($res as $pay)
              {
              ?>
                 <tr class="filterpayment">
            
            
                    <td><?php echo $count;?></td>
                    <td><?php echo trim($pay["studentId"]); ?></td>
                    <td><?php echo trim($pay["challanNo"]); ?></td>
                    <td><?php echo trim($pay["studentName"]); ?></td>
                    <td><?php echo trim($pay["parentname"]); ?></td>
                    <td><?php echo getStreambyId($pay["stream"]); ?></td>
                    <td><?php echo getClassbyNameId($pay["class"]); ?></td>                    
                    <td><?php echo trim($pay["section"]); ?></td>
                    <td><?php echo date("d-m-y",strtotime(trim($pay["transDate"]))); ?></td>
                    <td><?php echo trim($pay["transNum"]); ?></td>
                    <td><?php 
                        if(trim($pay["transStatus"]) == 'Ok') {
                            echo "Success";
                        } else {
                            echo trim($pay["remarks"]);
                        }                        
                    ?></td>
                    <td class="text-right"><?php echo trim($pay["amount"]); ?></td>               
                 </tr>
                 <?php $count++;
                 } 
              }
                 ?>

            </tbody>
        </table>
    </div>

    <div class="row comment">

    </div>
    <?php
    





include_once(BASEPATH.'footer.php');
?>