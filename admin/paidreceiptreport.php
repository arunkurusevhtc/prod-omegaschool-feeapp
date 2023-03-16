<?php
include_once('admnavbar.php');
$status= array('1' => 'Paid', '2' => 'Partial Paid', '3' => 'Not Paid');

?>
<div class="container-fluid  contentcheque">
<div class="row col-md-12">
         <div class="col-xs-1 col-sm-2 col-md-3 col-lg-4"></div>
         <div class="col-xs-10 col-sm-8 col-md-6 col-lg-4">    
      <?php
                  if(isset($_SESSION['successclass'])) {
                  echo $_SESSION['successclass'];
                  unset($_SESSION['successclass']);
                      }
                  if(isset($_SESSION['errorclass'])) {
                  echo $_SESSION['errorclass'];
                  unset($_SESSION['errorclass']);
                      }
                  if(isset($_SESSION['success'])) {
                  echo $_SESSION['success'];
                  unset($_SESSION['success']);
                      }
                  if(isset($_SESSION['failure'])) {
                  echo $_SESSION['failure'];
                  unset($_SESSION['failure']);
                      }


                    ?>
         </div>
         <div class="col-xs-1 col-sm-2 col-md-3 col-lg-4"></div>
</div>
<div class="col-md-12">
    <div class="col-md-6 p-l-0">
        <h2 class="top">Fee Type Report</h2>
    </div>

        <form  id="otherfeefilters" class="form-inline" method="post" action="exportpaidreceipt.php">
            <div class=col-lg-12 p-r-0 text-right">
                <div class="form-group">
                    <?php
                        $yearchecks = sqlgetresult("SELECT * FROM yearcheck",true);
                        ?>
                    <select name="yearselect" id="yearselect"  class="yearselect form-control ">
                        <option value="">Acad.Year</option>
                        <?php
                            foreach($yearchecks as $yearcheck) {
                            echo '<option value="'.$yearcheck['id'].'" >'.$yearcheck['year'].'</option>';
                            }
                            ?>
                    </select>
            </div>            
            <div class="form-group">
                    <?php
                        $streamtypes = sqlgetresult("SELECT * FROM streamcheck",true);
                        ?>
                    <select name="streamselect" id="streamselect"  class="streamselect form-control streamchange">
                        <option value="">Stream</option>
                        <?php
                            foreach($streamtypes as $stream) {
                            echo '<option value="'.$stream['id'].'" >'.$stream['stream'].'</option>';
                            }
                            ?>
                    </select>
            </div>
             <div class="form-group">

                       <select id="classselect" name="classselect"  class="classselect  form-control classchange">
                        <option value="">-Class-</option>
                     </select>
            </div>
            <div class="form-group">
                    <?php
                        $semestercheck = sqlgetresult('SELECT * FROM tbl_semester',true);
                        ?>
                    <select name="semesterselect" id="semesterselect"  class="semesterselect form-control ">
                        <option value="">Semester</option>
                        <?php
                        
                            foreach($semestercheck as $semester) {
                            echo '<option value="'.$semester['semester'].'" >'.$semester['semester'].'</option>';
                            }
                            ?>
                    </select>
            </div>
            
            <div class="form-group">
                <label>Student ID</label>
                <input type="text" name="stid" id="stid" placeholder="Student ID" class="form-control">
            </div>
            <div class="form-group">
                    <label>From</label>
                    <input type="text" name="from" id="from" placeholder="From Date" class="form-control datepicker">
                    <label>To</label>
                    <input type="text" name="to" id="to" placeholder="To Date" class="form-control datepicker">
            </div>
            <div class="form-group">
	            <div>
	                <?php
	                    $feegrouptypes = sqlgetresult("SELECT * FROM feegroupcheck",true);
                    ?>
	                <select name="feegroupselect"  class="feegroupselect form-control feegroupchange">
	                    <option value="">Fee Group</option>
	                    <?php
	                        foreach($feegrouptypes as $feegroup) {
	                        echo '<option value="'.$feegroup['id'].'" >'.$feegroup['feeGroup'].'</option>';
	                        }
	                        echo('<option value="0">LATE FEE</option>')
	                        ?>
	                </select>
	            </div>
	        </div>
	        <div class="form-group">
	            <div>
	                <?php
	                    $feetypetypes = sqlgetresult("SELECT * FROM feetypecheck",true);
                    ?>
	                <select name="feetypeselect"  class="feetypeselect form-control ">
	                    <option value="">Fee Type</option>                       
	                </select>
	            </div>
	        </div>
            <div class="form-group">
                <select name="tstatus" id="tstatus"  class="tstatus form-control ">
                    <option value="">-All-</option>
                    <?php 
                    foreach ($status as $key => $value) {
                        
                        if($key=='1'){
                            $sel1="selected='selected'";
                        }else{
                           $sel1=""; 
                        }
                    ?>
                    <option value="<?php echo $key; ?>" <?php echo $sel1; ?>><?php echo $value; ?></option>
                    <?php
                    }
                    ?>                    
                </select>
            </div>
            <div class="form-group">
                <button type="submit" name="receiptexportexcel" id="receiptexportexcel" value="unpaid" class="btn btn-info">Export Fee Type Report</button>
            </div>
            </div>
        </form>
    
</div>
<div style="clear:both">&nbsp;</div>

<!-- </div> -->
</div>
<?php
include_once('..\footer.php');
?>
<script type="text/javascript">
    $(document).on("change", ".feegroupchange", function() {
        // event.preventDefault();
        var feeGrpId = $(this).val();
        if (feeGrpId.length != 0) {
            if(feeGrpId == 0){
                 $(".feetypeselect").html('<option value="">Fee Type</option><option value="">Late Fee</option>');
            }else{
                  $.ajax({
                    url: 'adminactions.php',
                    method: 'post',
                    dataType: 'json',
                    data: {
                        'submit': 'getFeeTypeData',
                        'data': feeGrpId
                    },
                    success: function(response) {
                        console.log(response);
                        var options = '<option value="">Fee Type</option>';
                        $.each(response, function(i, val) {
                            options += '<option value="' + val.value + '">' + val.label + '</option>'
                        });
                        $(".feetypeselect").html(options)
                    }
                });
            }
        } else {
            $(".feetypeselect").html('<option value="">Fee Type</option>')
        }
    });
</script>