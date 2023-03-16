<?php
$columns = array('Particulars','Existing Student','New Student','Total');
?>
<div id="pane-summary" class="card tab-pane fade" role="tabpanel" aria-labelledby="tab-summary">
    <div class="card-header" role="tab" id="heading-B">
        <h5 class="mb-0">
            <a class="collapsed" data-toggle="collapse" href="#collapse-B" aria-expanded="false" aria-controls="collapse-B"><b>Summary Report</b></a>
        </h5>
    </div>
    <div id="collapse-B" class="collapse" data-parent="#content" role="tabpanel" aria-labelledby="heading-B">
        <div class="card-body">
            <div class="row col-lg-12 m-t-15">
                <div class="form-group col-lg-3">
                    <label>Acad.Year</label>
                    <select name="summary_yearselect" id="summary_yearselect"  class="yearselect form-control ">
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
                    <select name="summary_semesterselect" id="summary_semesterselect"  class="form-control ">
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
                    <select name="summary_streamselect" id="summary_streamselect"  class="streamselect form-control">
                    <option value="">All</option>
                    <?php
                    foreach($streamtypes as $stream) {
                    $stream_name=trim($stream['stream']);
                    echo '<option value="'.$stream['id'].'">'.$stream_name.'</option>';
                    }
                    ?>
                    </select>
                </div>
                <!--<div class="form-group col-lg-3">
                    <label>Status</label>
                    <select id="dash_studstatus" name="dash_studstatus"  class="form-control">
                    <option value="existing">Existing</option>
                    <option value="new">New</option>
                    </select>
                </div>-->
            </div>
            <div class="row col-lg-12 m-t-15">
                <div class="form-group col-lg-3">
                    <input type="text" name="summary_from" id="summary_from" placeholder="From Date" class="form-control datepicker">
                </div>
                <div class="form-group col-lg-3">
                    <input type="text" name="summary_to" id="summary_to" placeholder="To Date" class="form-control datepicker">
                </div>
                <div class="form-group col-lg-4">
                    <button type="button" id="summary-wiseFlt" name="summary-wiseFlt" value="summary-wise" class="btn btn-info">Filter</button>
                </div>
            </div>
            <div class="row col-lg-12 m-t-15">
                <div class="table-responsive">
                    <table class="table table-bordered admintab dataTableSummary">
                        <thead>
                            <tr>
                                <th style="width: 40%">Particulars</th>
                                <th style="width: 20%">Existing Student</th>
                                <th style="width: 20%">New Student</th>
                                <th style="width: 20%">Total</th>
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