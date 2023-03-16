<?php 
    require_once('admnavbar.php');
?>
<div class="container-fluid nonfeeconfigdata">
    <div class="row col-md-12">
        <div class="col-xs-1 col-sm-2 col-md-3 col-lg-4"></div>
        <div class="col-xs-10 col-sm-8 col-md-6 col-lg-4">  
            <?php
                if(isset($_SESSION['success'])) {
                   echo $_SESSION['success'];
                   unset($_SESSION['success']);
                } elseif(isset($_SESSION['error'])) {
                   echo $_SESSION['error'];
                   unset($_SESSION['error']);
                }
                ?>
        </div>
        <div class="col-xs-1 col-sm-2 col-md-3 col-lg-4"></div>
    </div>
     <div class="col-md-12">
      <div class="m-b-15">
        <h2 class="fer">NON-FEE CONFIGURATION</h2>
        </div>
        </div> 
    <div class="col-md-12 container row">
        <form class="form-inline nonfeetarget" id="addfeedata">
            <p class="msg"></p>
            <div class="col-md-2 form-group">
                <!-- <label for="sel1">Stream:</label> -->
                <select class="form-control ayear" name="academic">
                    <option value="">--SELECT YEAR--</option>
                </select>
            </div>
            <div class="col-md-2 form-group">
                <!-- <label for="sel1">Stream:</label> -->
                <select class="form-control stream" name="stream">
                    <option value="">--SELECT STREAM--</option>
                </select>
            </div>
            <div class="col-md-3 semdiv form-group">
                <!-- <label for="sel1">Semester:</label> -->
                <select class="form-control semester" name="semester">
                    <option value="" class="classlistfee">--SELECT SEMESTER--</option>
                </select>
            </div>
            <div class="col-md-4 form-group">
                <!-- <label for="sel1">Fee Type:</label> -->
                <select class="form-control feetype" name="feetype">
                    <option value="">--SELECT NON-FEE TYPE--</option>
                </select>
            </div>
            <div class="col-md-1 form-group">
                <input type="hidden" name="checknonfeeconfig" value="Add Data"/>
                <input type="submit" class="btn btn-primary btn-md addstudent" name="checknonfeeconfig" value="Add Data"/>
            </div>
        </form>
    </div>
    <div class="" id="other" style="margin-top: 30px;">
        <form method="post" action="adminactions.php">
            <div class="table-responsive col-md-12">
                <table class="table table-bordered" id="mytable" cellspacing="0">
                    <thead>
                        <tr>
                            <th rowspan="2">Academic Year</th>
                            <th rowspan="2">Stream</th>
                            <th rowspan="2">Semester</th>
                            <th rowspan="2">Fee Type</th>
                            <th colspan="21">Amount</th>
                        </tr>
                        <tr>
                        </tr>
                    </thead>
                </table>
                <input type='hidden' name="academic" class="ayear" value="">
                <input type="hidden" name="stream" class="streamtbl">
                <input type="hidden" name="semester" class="semestertbl">
                <input type="hidden" name="feetype" class="feetypetbl">
                <input type='hidden' name="class" class="classlist" value = "">
                <input type='hidden' name[]="classlistarray" class="classlistarray" value = "">
            </div>
            <div class="col-md-12 text-center m-t-15">
                <button type="submit" class="btn btn-primary" name="submit" value="nonfeeconfiguration">Save Fee Details</button>                
            </div>
        </form>
    </div>
    <div style="clear:both;"></div>
    <hr class="feeconfigline" />
    <form id="nonfeeconfigdetails" class="form-inline">
              <div class="row ">
            <div class="row col-md-12">
                <div class="col-md-6 text-left">
                    <h2 class="fer">MANAGE NON-FEE CONFIGURATION</h2>
                </div>
                <div class="col-md-6 p-l-0 text-right">
                    <div class="form-group">
                     <?php
                    $streamtypes = sqlgetresult("SELECT * FROM streamcheck",true);
                    ?>
                    <select id="streamselect" name="streamselect"  class="streamselect form-control streamchange">
                      <option value="">--Select--</option>
                      <?php
                      foreach($streamtypes as $stream) {
                              // print_r($stream);
                      echo '<option value="'.$stream['id'].'" >'.$stream['stream'].'</option>';
                      }
                      ?>
                    </select>
                    </div>
                    <div class="form-group">
                      <?php
                    $classstypes = sqlgetresult("SELECT * FROM classcheck",true);
                    ?>
                     <select id="classselect" name="classselect"  class="classselect  form-control">
                        <option value="">--Select--</option>
                     </select>
                    </div>              
                    <button type="button" id="fltnonfeeconfiguration" name="filter" value="filternonfeeconfiguration" class="btn btn-info">Filter</button>
               </div>
            </div>
                      <div style="clear:both;">
                        </div>
                        </div>
     </form>  
    <div class="col-md-12 row datadiv table-responsive">
        <table class="table table-bordered admintab dataFeeConfiguration">
            <thead>
                <tr>
                    <th>S.No</th>
                    <th>Academic Year</th>
                    <th>FeeType</th>
                    <th>Stream</th>
                    <th>Class</th>
                    <th>Semester</th>
                    <th>Amount</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $data = sqlgetresult("SELECT * FROM nonfeeconfigdata ORDER BY id ASC",true);
                    // print_r($data);
                    $num = ($data!= null) ? count($data) : 0;
                    $count = 1;
                    if($num > 0)
                    {
                        foreach ($data as $par)
                        {
                        ?>
                <tr>
                    <td><?php echo $count; ?></td>
                    <td><?php echo trim($par["academicYear"]); ?></td>
                    <td><?php echo trim($par["feeType"]); ?></td>
                    <td><?php echo trim($par["stream"]); ?></td>
                    <td><?php echo trim($par["class_list"]); ?></td>
                    <td><?php echo trim($par["semester"]); ?></td>
                    <td class="text-right"><?php echo trim($par["amount"]); ?></td>
                    <td class="fafa">                                     
                        <a href="editnonfeeconfig.php?id=<?php echo trim($par["id"]); ?>"><i class="fa fa-edit"></i></a> 
                        <a href="adminactions.php?action=delete&page=nonfeeconfig&id=<?php echo $par["id"]; ?>"><i class="fa fa-trash-o"></i></a>
                    </td>
                </tr>
                <?php $count++;
                    } 
                    }
                    ?>
            </tbody>
        </table>
    </div>
</div>

<div class="row comment"></div>

<?php
    include_once(BASEPATH.'footer.php');
?>