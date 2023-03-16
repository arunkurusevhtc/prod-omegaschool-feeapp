<?php

include_once('admnavbar.php');
// $ReceiptData = sqlgetresult('SELECT * FROM topupdata WHERE "stream" =\'' . 1 . '\' AND "clid" = \'' . 1 . '\'',true);
$ReceiptData = sqlgetresult('SELECT * FROM topupdata',true);
?>
<div class="container-fluid">
    


<div class="col-md-12 ">
    <div class="col-md-3 p-l-0">
        <h2 class="top">MANAGE TOP-UP</h2>
    </div>
    <div class="col-md-7 p-r-0 text-right">
        <form  id="filtertopupchallans" class="form-inline">
            <div class="form-group">
                <div>
                    <?php
                        $streamtypes = sqlgetresult("SELECT * FROM streamcheck",true);
                        ?>
                    <select id="streamselect" name="streamselect"  class="streamselect form-control streamchange">
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
                    <select id="classselect" name="classselect"  class="classselect  form-control classchange">
                        <option value="">Class</option>
                       
                    </select>
                </div>
            </div>
            <div class="form-group">
           
                <div>
                    <?php
                        $sectiontypes = sqlgetresult("SELECT DISTINCT section FROM tbl_student ORDER BY section ASC",true);
                        ?>
                    <select id="sectionselect" name="sectionselect"  class="sectionselect  form-control">
                        <option value="">Section</option>                        
                    </select>
                </div>
            </div>
            <button type="button" id="flttopupchallans" name="filter" value="filtertopupchallans" class="btn btn-info">Filter</button>
        </form>
    </div>
    <div class="col-md-2 text-right p-r-0">
        <a href="adminactions.php?excel=topup" class="btn btn-primary">Download Excel</a>
    </div>
</div>

<div class="col-md-12 m-t-15" >
    <div class="table-responsive">
        <form>
            <table class="table table-bordered admintab dataTableTopup">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Student Id</th>
                        <th>Student Name</th>
                        <th>Academic Year</th>
                        <th>Stream</th>
                        <th>Class</th>
                        <th>Section</th>
                        <th>Challan Number</th>                                   
                        <th>Total</th>
                        <th>Paid On</th>
                        <th>Paid By</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $i=1;
                        
                        $num = ($ReceiptData != null) ? count($ReceiptData) : 0;
                        if($num > 0)
                        {      

                        foreach ($ReceiptData as $data) {
                          ?>
                        <tr class="filterrowreport">
                        <td><?php echo $i;?></td>
                        <td><?php echo $data['studentId'];?></td>
                        <td><?php echo $data['studentName'];?></td>
                        <td><?php echo $data['academic_yr'];?></td>
                        <td><?php echo $data['steamname'];?></td>
                        <td><?php echo $data['class_list'];?></td>
                        <td><?php echo $data['section'];?></td>
                        <td><?php echo 'CARDTOPUP'.$data['tpid'].'/'.$data['studentId'];?></td>
                        <td class="text-right"><?php echo $data['amount'];?></td>
                        <td><?php echo date("d-m-Y", strtotime($data['createdOn']));?></td>
                        <td><?php echo $data['adminName'];?></td>                        
                    </tr>
                    <?php 
                        $i++;
                        }
                        } 
                        /*else {
                                   echo "<tr><td colspan='11' style='text-align:center;'>No Data Avaiable.</td></tr>";
                                  }*/
                        ?>
                </tbody>
            </table>
        </form>
    </div>
</div>

</div>

<div class="modal fade" id="receiptreportviewmodal" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Receipt</h4>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <form method="post" id="studDataModal" action="sql_actions.php">
                        <div id="challanData"></div>                                     
                        <div class="text-center">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <!-- <button type="submit" name='pay' value="confirm" class="btn btn-primary" >Confirm Payment</button> -->
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row comment"></div>

<?php
    include_once(BASEPATH.'footer.php');
?>