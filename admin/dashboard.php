<?php
include_once('admnavbar.php');
$yearchecks = sqlgetresult("SELECT * FROM yearcheck ORDER BY id DESC",true);
$semestercheck = sqlgetresult('SELECT * FROM tbl_semester',true);
$streamtypes = sqlgetresult("SELECT * FROM streamcheck",true);
?>
<div class="container-fluid feeappdash" id="topup-container">
	<div class="row col-lg-12">
		<h2 class="top">Dashboard</h2>
	</div>
    <div class="row col-lg-12">
       <ul id="tabs" class="nav nav-tabs" role="tablist">
        <li class="nav-item active">
            <a id="tab-class" href="#pane-class-wise" class="nav-link active" data-toggle="tab" role="tab"><b>Class-Wise Report</b></a>
        </li>
        <li class="nav-item">
            <a id="tab-summary" href="#pane-summary" class="nav-link" data-toggle="tab" role="tab"><b>Summary Report</b></a>
        </li>
        <li class="nav-item">
            <a id="tab-date" href="#pane-date-wise" class="nav-link" data-toggle="tab" role="tab"><b>Date-Wise Report</b></a>
        </li>
    </ul>
    <div id="content" class="tab-content" role="tablist">
        <!-- Class-Wise Report -->
        <?php include_once("dashboard/pane-class-wise.php"); ?>
        <!-- Summary Report -->
        <?php include_once("dashboard/pane-summary.php"); ?>
        <!-- Date-Wise Report -->
        <?php include_once("dashboard/pane-date-wise.php"); ?>
    </div> 
    </div>
</div>
<div class="row comment">
</div>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
<style type="text/css">
  #DataTables_Table_1, #DataTables_Table_2{
      width: 85% !important;
  }  
  tfoot td {
    font-weight:bold;
  }
.nav-tabs {
    display:none;
}

@media(min-width:768px) {
    .nav-tabs {
        display: flex;
    }
    
    .card {
        border: none;
    }

    .card .card-header {
        display:none;
    }  

    .card .collapse{
        display:block;
    }
}

@media(max-width:767px){
    .tab-content > .tab-pane {
        display: block !important;
        opacity: 1;
    }
}
</style>
<?php
include_once(BASEPATH.'footer.php');
?>