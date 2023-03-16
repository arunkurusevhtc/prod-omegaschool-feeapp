<?php

include_once('admnavbar.php');
// $studentId = '6135';
// $DemandData = sqlgetresult('SELECT * FROM getdemanddata WHERE "studentId" = \''.$studentId.'\' AND "term" = \'II\'',true);
// print_r($DemandData);
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
</div>

<div class="col-md-12 ">
    <div class="col-md-2 p-l-0">
        <h2 class="top">DEMAND REPORT</h2>
    </div>
    <div class="col-md-10 p-r-0 text-right">
        <form  id="filterdemandreport" class="form-inline">
            <div class="col-md-2 form-group">
                <div>
                    <?php
                        $yearchecks = sqlgetresult("SELECT * FROM yearcheck",true);
                        ?>
                    <select name="yearselect"  class="yearselect form-control ">
                        <option value="">Acad.Year</option>
                        <?php
                            foreach($yearchecks as $yearcheck) {
                            echo '<option value="'.$yearcheck['id'].'" >'.$yearcheck['year'].'</option>';
                            }
                            ?>
                    </select>
                </div>
            </div>
            <div class="col-md-1 form-group">
                <div>
                    <?php
                        $paidarray = array("Paid","Not Paid");
                        ?>
                    <select name="paidselect"  class="paidselect form-control ">
                        <option value="">Status</option>
                        <?php
                            foreach($paidarray as $paid) {
                            if($paid == "Paid"){
                            echo '<option value="1" >'.$paid.'</option>';
                            }
                            else{
                            echo '<option value="0" >'.$paid.'</option>';  
                            }
                            }
                            ?>
                    </select>
                </div>
            </div>
            <div class="col-md-3 form-group">
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
            <div class="col-md-3 form-group">
                <div>
                    <?php
                        $feetypetypes = sqlgetresult("SELECT * FROM feetypecheck",true);
                        ?>
                    <select name="feetypeselect"  class="feetypeselect form-control ">
                        <option value="">Fee Type</option>                       
                    </select>
                </div>
            </div>
            <div class="col-md-2 form-group">
                <div>
                    <?php
                        $streamtypes = sqlgetresult("SELECT * FROM streamcheck",true);
                        ?>
                    <select name="streamselect"  class="streamselect form-control ">
                        <option value="">Stream</option>
                        <?php
                            foreach($streamtypes as $stream) {
                            echo '<option value="'.$stream['id'].'" >'.$stream['stream'].'</option>';
                            }
                            ?>
                    </select>
                </div>
            </div>
            <div class="col-md-1 form-group">
                <button type="submit" name="filter" value="filterdemandreport" class="btn btn-info">Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="col-md-12 m-t-15" >
    <div class="table-responsive">
        <form>
            <table class="table table-bordered admintab dataTableDemandReport">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Action</th>
                        <th>Academic Year</th>
                        <th>Student Id</th>
                        <th>Student Name</th>
                        <th>Challan Number</th>
                        <th>Stream</th>
                        <th>Challan Status</th>
                        <th>Fee Group</th>
                        <th>Fee Type</th>
                        <th>Total</th>
                        <th>Created On</th>
                        <th>Remarks</th>
                        <th>Due Date</th>
                    </tr>
                </thead>
                <tbody>
                    
                </tbody>
            </table>
        </form>
    </div>
</div>
</div>

<div class="modal fade" id="demandreportviewmodal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Demand Report</h4>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <form method="post" id="studDataModal" action="sql_actions.php">
                        <div id="challanData"></div>                                     
                        <div class="text-center">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row comment">
</div>

<script type="text/javascript">
    $(document).on("change", ".feegroupchange", function() {
        // event.preventDefault();
        var feeGrpId = $(this).val();
        // alert(feeGrpId);
        if (feeGrpId.length != 0) {
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
            })
        } else {
            $(".feetypeselect").html('<option value="">Fee Type</option>')
        }
    });

    $(document).on("click", ".demandreportview", function() {
        // event.preventDefault();
        var studId = $(this).data('id');
        var id = this.id;
        // var feegroup = $(this).data('feegroup');
        $.ajax({
            url: 'adminactions.php',
            method: 'post',
            dataType: 'json',
            data: {
                'submit': 'viewdemandreport',
                'studId': studId,
                'cid': id
            },
            success: function(response) {
                console.log(response);
                $("#challanData").html('');
                var html = '<table class="table report_tbl" border="1"><tr><td><label>Name: </label> ' + response.studentName + '</td><td><label>Id: </label> ' + response.studentId + '</td></tr><tr class="innerborder1"><td><label>Challan Number: </label> ' + response.challanNo + '</td><td><label>Academic Year: </label> ' + response.academicYear + '</td></tr><tr><td colspan="2">';                
                html += '<table class="report_tbl table" >';
                var amount = 0;
                html += '<tr><td><label>Fee Group</label></td><td><label>Fee Type</label></td><td><label>Amount</label></td></tr>';
                $.each(response.feeData, function(i, row) {                    
                    var pamt = 0;
                    $.each(row, function(index, el) {
                            if ($.trim(el[0]) != 0) {
                                html += '<tr><td>' + i + '</td><td>' + el[1] + '</td><td>' + el[0] + '</td></tr>';
                                pamt += parseInt(el[0])
                            }
                    });
                    amount += pamt;                    
                });
               
                
                html += '<tr><td colspan="2"></td><td><p class="tot" style="border-top:1px solid #000;text-align:right;padding-top: 15px;"><span id="grand_tot">' + amount + '</span></p></td></tr></table></table>';
                $("#challanData").append(html)
            }
        })
    });
</script>