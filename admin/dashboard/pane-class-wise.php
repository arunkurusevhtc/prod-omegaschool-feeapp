<?php
$columns = array('S.No','Stream','Class','Student Count','SFS Demand','LMOIS Demand','LMES Demand','Paid Student Count','SFS Paid Amt','LMOIS Paid Amt','LMES Paid Amt','Waiver Amt');
?>
<div id="pane-class-wise" class="card tab-pane fade in active" role="tabpanel" aria-labelledby="tab-class">
<div class="card-header" role="tab" id="heading-A">
    <h5 class="mb-0">
        <!-- Note: `data-parent` removed from here -->
        <a data-toggle="collapse" href="#collapse-A" aria-expanded="true" aria-controls="collapse-A"><b>Class-Wise Report</b></a>
    </h5>
</div>
<!-- Note: New place of `data-parent` -->
<div id="collapse-A" class="collapse" data-parent="#content" role="tabpanel" aria-labelledby="heading-A">
    <div class="card-body">
<div class="row col-lg-12 m-t-15">
    <div class="form-group col-lg-3">
        <label>Acad.Year</label>
        <select name="dash_yearselect" id="dash_yearselect"  class="yearselect form-control ">
        <option value="">Acad.Year</option>
        <?php
        foreach($yearchecks as $yearcheck) {
        if($yearcheck['status']=='ACTIVE'){
        $sel='selected="selected"';
        }else{
        $sel='';
        }
        echo '<option value="'.$yearcheck['id'].'" '.$sel.' >'.$yearcheck['year'].'</option>';
        }
        ?>
        </select>
    </div>    
    <div class="form-group col-lg-3">
        <label>Term</label>
        <select name="semesterselect" id="semesterselect"  class="form-control ">
        <option value="">Semester</option>
        <?php
        foreach($semestercheck as $semester) {
        echo '<option value="'.$semester['semester'].'" >'.$semester['semester'].'</option>';
        }
        ?>
        </select>
    </div>        
    <div class="form-group col-lg-3">
        <label>Stream</label>
        <select name="dash_streamselect" id="dash_streamselect"  class="streamselect form-control">
        <option value="">All</option>
        <?php
        foreach($streamtypes as $stream) {
        $stream_name=trim($stream['stream']);
        echo '<option value="'.$stream['id'].'">'.$stream_name.'</option>';
        }
        ?>
        </select>
    </div>
    <div class="form-group col-lg-3">
        <label>Status</label>
        <select id="dash_studstatus" name="dash_studstatus"  class="form-control">
        <option value="existing">Existing</option>
        <option value="new">New</option>
        </select>
    </div>   
</div>
<div class="row col-lg-12 m-t-15">
    <div class="form-group col-lg-3">
        <input type="text" name="from" id="from" placeholder="From Date" class="form-control datepicker">
    </div>
    <div class="form-group col-lg-3">
        <input type="text" name="to" id="to" placeholder="To Date" class="form-control datepicker">
    </div>
    <div class="form-group col-lg-4">
        <button type="button" id="class-wiseFlt" name="class-wiseFlt" value="class-wise" class="btn btn-info">Filter</button>
    </div>
</div>
<div class="row col-lg-12 m-t-15">
    <div class="table-responsive">
        <table class="table table-bordered admintab dataTableDashboard">
            <thead>
                <tr>
                    <?php foreach ($columns as $key => $value) { ?>
                    <th><?php echo $value; ?></th>
                    <?php    
                    } 
                    ?>
                </tr>
            </thead>
            <tfoot align="right">
                <tr>
                    <?php for($i=0; $i<count($columns); $i++) { echo '<th></th>'; }  ?>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
    </div>
</div>
</div>