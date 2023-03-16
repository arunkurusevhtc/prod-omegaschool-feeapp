<?php
$columns = array('Date','Existing',' New','Total');
?>
<div id="pane-date-wise" class="card tab-pane fade" role="tabpanel" aria-labelledby="tab-date">
    <div class="card-header" role="tab" id="heading-C">
        <h5 class="mb-0">
            <a class="collapsed" data-toggle="collapse" href="#collapse-C" aria-expanded="false" aria-controls="collapse-C"><b>Date-wise Report</b></a>
        </h5>
    </div>
    <div id="collapse-C" class="collapse" role="tabpanel" data-parent="#content" aria-labelledby="heading-C">
        <div class="card-body">
<div class="row col-lg-12 m-t-15">
    <div class="form-group col-lg-3">
        <label>Acad.Year</label>
        <select name="dtwise_yearselect" id="dtwise_yearselect"  class="yearselect form-control ">
        <option value="">All</option>
        <?php
        foreach($yearchecks as $yearcheck) {
        /*if($yearcheck['status']=='ACTIVE'){
        $sel='selected="selected"';
        }else{
        $sel='';
        }*/
        echo '<option value="'.$yearcheck['id'].'">'.$yearcheck['year'].'</option>';
        }
        ?>
        </select>
    </div>    
    <div class="form-group col-lg-3">
        <label>Term</label>
        <select name="dtwise_semesterselect" id="dtwise_semesterselect"  class="form-control ">
        <option value="">All</option>
        <?php
        foreach($semestercheck as $semester) {
        echo '<option value="'.$semester['semester'].'" >'.$semester['semester'].'</option>';
        }
        ?>
        </select>
    </div>        
    <div class="form-group col-lg-3">
        <label>Stream</label>
        <select name="dtwise_streamselect" id="dtwise_streamselect"  class="streamselect form-control">
        <option value="">All</option>
        <?php
        foreach($streamtypes as $stream) {
        $stream_name=trim($stream['stream']);
        echo '<option value="'.$stream['id'].'">'.$stream_name.'</option>';
        }
        ?>
        </select>
    </div>   
</div>
<div class="row col-lg-12 m-t-15">
    <div class="form-group col-lg-3">
        <input type="text" name="dtwise_from" id="dtwise_from" placeholder="From Date" class="form-control datepicker">
    </div>
    <div class="form-group col-lg-3">
        <input type="text" name="dtwise_to" id="dtwise_to" placeholder="To Date" class="form-control datepicker">
    </div>
    <div class="form-group col-lg-4">
        <button type="button" id="dtwise_wiseFlt" name="dtwise_wiseFlt" value="date-wise" class="btn btn-info">Filter</button>
    </div>
</div>
<div class="row col-lg-12 m-t-15">
    <div class="table-responsive">
        <table class="table table-bordered admintab dataTableDatewise">
            <thead>
                <tr>
                    <th style="width: 40%">Date</th>
                    <th style="width: 20%">Existing</th>
                    <th style="width: 20%">New</th>
                    <th style="width: 20%">Total</th>
                </tr>
            </thead>
            <tfoot align="right">
                <tr>
                    <th></th>
                    <th></th>
                    <th>Grand Total</th>
                    <th class="text-right"></th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
    </div>
    </div>
</div>