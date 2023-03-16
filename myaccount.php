
<?php 
require_once('navbar.php');

 ?>
<!DOCTYPE html>
<html>
<head>
  <title>MY ACCOUNT</title>
  <style>
    


</style>

</head>
<body>
   <div class="col-sm-12">
          <?php
              if(isset($_SESSION['success_msg']) && $_SESSION['success_msg'] !='') {
                  echo $_SESSION['success_msg'];
                  unset($_SESSION['success_msg']);
              }
              if (isset($_SESSION['error_msg']) && $_SESSION['error_msg'] !='') {
                  echo $_SESSION['error_msg'];
                  unset($_SESSION['error_msg']);
              }
          ?>
      </div>



    <div class="container-fluid text-right">
             <div class="col-md-12 m-b-15">
              <a title="Please add child using studentId and your registered mobile number." class="btn btn-primary" id="addstudent" href="#myModal" data-toggle="modal">Add Child</a>
          </div>
          </div>

                    
<div class="container studentdata">
    <div class="row">
        <div class="modal fade" id="myModal" role="dialog">
            <div class="modal-dialog">
            <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Add Child</h4>
        </div>
        <div class="modal-body con">
        <h4 class="addtitle">Please provide the below details.</h4>
        <!-- <h4 class="addtitle">Please provide the following details from current challan.</h4> -->
        <form name="addstudent" class="form-horizontal" id="studentdetailschecked" method=POST >
            
                <label class="control-label  col-sm-5" for="email">Registered MobileNo:</label>
                <div class="col-sm-7 form-group">
                    <input name="mobile_number" title="Please enter mobile Number" id="Mobile_number" type="text" placeholder="Mobile No." class="form-control" autofocus="" required="" readonly="" value="<?php echo $_SESSION['phn']; ?>">
                    </div>
                <label class="control-label col-sm-5" for="email">Student ID:</label>
                <div class="col-sm-7 form-group">
                 <input name="student_number" title="Student ID from current challan" id="student_number" type="text" placeholder="Student ID No." class="form-control stdid" autofocus="" required="">
                </div>
                <div class="stddetailscheck">
                    <label class="control-label  col-sm-5" for="email">Student Name:</label>
                        <div class="col-sm-7 form-group">
                           <span class="form-control stdname"></span>
                        </div>
                    
                    <label class="control-label  col-sm-5" for="email">Class:</label>
                        <div class="col-sm-7 form-group">
                            <span class="form-control stdclass"></span>
                        </div>
                </div>
                    <div style="margin-left:220px;" class="form-group">
                        <button class="btn btn-default" type="button" data-dismiss="modal">Close</button>
                        <button class="btn btn-primary addMyStudent" type="button" name="addstudent" value="addstudent">Check</button>
                    </div>
                </form>
            </div>
        </div>
            </div>
        </div>
        <div class="col-sm-3"></div>
        <div class="col-sm-6">
            <div class="col-xs-6 col-sm-6 col-md-6 form-group settings1">Email (Primary):</div>
            <div class="col-xs-6 col-sm-6 col-md-6 form-group settings">
                <?php echo $_SESSION['login_user']; ?>
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6 form-group settings1">First Name:</div>
            <div class="col-xs-6 col-sm-6  col-md-6 form-group settings">
                <?php echo $_SESSION['fstname']; ?>
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6 form-group settings1">Last Name:</div>
            <div class="col-xs-6 col-sm-6 col-md-6 form-group settings">
                <?php echo $_SESSION['lstname']; ?>
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6 form-group settings1">Mobile Number (Primary):</div>
            <div class="col-xs-6 col-sm-6 col-md-6 form-group settings">
                <?php echo $_SESSION['phn']; ?>
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6 form-group settings1">Mobile Number (Secondary):</div>
            <div class="col-xs-6 col-sm-6 col-md-6 settings form-group">
                <?php if(isset($_SESSION['sob'])){echo $_SESSION['sob'];}else{ echo '-';} ?>
            </div>
        </div>
        <div class="col-sm-3"></div>
    </div>
</div>
    
  <hr>


      <!-- <form  method="post" action="sql_actions.php"> -->
        <div class="col-sm-12">
                 <div class="col-sm-1"></div>
         <div class=" form-group container col-sm-10">
      <table class="table-responsive table table-bordered student">
        <tr class="ward">
                <th> <b>Student Name</b></th>
                <th><b>Class</b> </th>
                 <th><b>Section</b></th> 
                 <th><b>Action</b></th> 
              </tr>
              <tr>
            
          </tr>
          </table>
     </div>
     <div class="col-sm-1"></div>
  </div>
  



<div class="row comment">
    
</div>
</body>
</html>


<?php
  include_once('footer.php');
?>


