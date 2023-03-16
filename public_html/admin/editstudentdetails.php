<?php
require_once('admnavbar.php');





?>



<div class="container-fluid">
   <div class="row">
      <div class="col-sm-2 col-md-3"></div>
      <div class="col-sm-8 col-md-6">
         <div class="tech-content">
            <p class="heading">Edit Student</p>

            <form id="demand" method="post" class="form-horizontal" action="adminactions.php">
              
            	 
            	  <?php
                    if(isset($_GET['id'])) {
                  ?>
                 
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="studStatus">NAME:</label>
                        <div class="col-sm-8">
                        
                        </div>
                    </div>  

                      <?php
                    }
                  ?>
                                 
            <div class="form-group">
                <label class="control-label col-sm-4" for="studStatus">SELECT STATUS:</label>
                <div class="col-sm-8">
                     <select name="studStatus" id="studStatus" class="form-control">
                        <option value="">--SELECT--</option>
                        <option value="SUCCESS">SUCCESS</option>
                        <option value="HOLD">HOLD</option>
                        <option value="DETAINED">DETAINED</option>
                    </select>
                </div>
            </div>                    

              <div id='demandData'>
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="studStatus">CLASS:</label>
                            <div class="col-sm-8">
                                 <?php
                                    $classlists = sqlgetresult("SELECT * FROM classcheck");
                                ?>
                                <select name="classlist"  class="classlist form-control">
                                    <option value="">--Select--</option>
                                    <?php
                                        foreach($classlists as $classlist) {
                                            echo '<option value="'.$classlist['id'].'">'.$classlist['class_list'].'</option>';
                                        }
                                    ?>
                                </select>
                            </div>                                    
                        </div>
                <div class="form-group">
                    <label class="control-label col-sm-4" for="studStatus">TERM:</label>
                        <div class="col-sm-8">
                                 <?php
                                    $terms = sqlgetresult("SELECT * FROM termData");
                                ?>
                                <select name="term"  class="term form-control">
                                    <option value="">--Select--</option>
                                    <?php
                                        foreach($terms as $term) {
                                            echo '<option value="'.$term['semester'].'">'.$term['semester'].'</option>';
                                        }
                                    ?>
                                </select>
                            </div>                                    
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="studStatus">FEETYPES:</label>
                            <div class="col-sm-8">
                                 <?php
                                    $feeTypes = sqlgetresult("SELECT * FROM getfeetypes");
                                ?>
                                <input type="hidden" name="selected_feetypes" class="selected_quizsetids">
                                <select name="feetype"  class="quizsetid form-control" multiple="multiple
                                ">
                                    <?php
                                        foreach($feeTypes as $feetype) {
                                            echo '<option value="'.$feetype['id'].'">'.$feetype['feeType'].'</option>';
                                        }
                                    ?>
                                </select>
                            </div>                                    
                        </div>


                            <div class="form-group text-center">
                            <a class="btn btn-primary" data-id="<?php echo $res['studentId'];?>" id="createChallannumber" href="#challanModal" data-toggle="modal">Create Challan</a>
                            <input type="hidden" name="showChallan" value="showChallan" >
                            <!-- <button type="submit" name="createChallan" value="new" class="btn btn-primary text-center">Create Challan</button> -->
                            <a href="home.php"><button type="button" value="Go Back" class="btn btn-warning text-center">Back</button></a>
                        </div>
              </div>  
            </form>

                     <div class="form-group text-center hidedemand">
                     <a href="home.php"><button type="button" value="Go Back" class="btn btn-warning text-center ">Back</button></a>
                   </div>

               </div>

                    </div>
        </div>
      <div class="col-sm-2 col-md-3"></div>
   </div>
</div>

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
                        <div id="challanData2"></div>                
                        <div class="text-center">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" id="createchallan" >Create</button>
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






include_once(BASEPATH.'footer.php');
?>