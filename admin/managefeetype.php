<?php
    require_once('admnavbar.php');
    ?>
<div class="container-fluid">
    <div class=" col-md-12">
        <div class="col-xs-1 col-sm-2 col-md-3 col-lg-4"></div>
        <div class="col-xs-10 col-sm-8 col-md-6 col-lg-4">
            <?php
                if(isset($_SESSION['successftype'])) {
                echo $_SESSION['successftype'];
                unset($_SESSION['successftype']);
                    }
                if(isset($_SESSION['errorftype'])) {
                echo $_SESSION['errorftype'];
                unset($_SESSION['errorftype']);
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
    <div class="col-md-12 ">
        <div class="col-md-6 p-l-0">
            <h2 class="top">FEE TYPES </h2>   
        </div>   
        <div class="col-md-6 p-r-0 text-right">
            <?php
                $sql = 'SELECT * FROM feetypecheck';
                $res = sqlgetresult($sql,true);        
                $num = ($res!= null) ? count($res) : 0;
                if($num == 0) {
            ?>
            <a href="addfeetype.php"><button class="btn btn-info">Add Fee Type</button></a>
            <?php
                }
            ?>
        </div>  
    </div>
    
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-bordered admintab dataTableFeeType">
                <!-- <caption class="title">Sales Data of Electronic Division</caption> -->
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Fee Type</th>
                        <th>Description</th>
                       <th>Group</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $count=1;
                       
                        if($num > 0)
                        {
                        
                        foreach ($res as $key=>$fee)
                        {
                            if(($fee['tax'])!=""){
                               $sql = 'SELECT * FROM taxcheck';
                               $result = sqlgetresult($sql,true);
                               $split=explode(",",$fee["tax"]);
                              // $feedata='';
                               foreach($split as $t){
                                 foreach($result as $num=>$tax){
                                    // print_r(trim($t));
                                    if(in_array($t, $tax)){
                                     $feedata[$t]=$tax['taxType'];
                                    }
                                 }
                                 
                               }
                               // print_r($feedata);
                        
                           }
                        ?>
                    <tr>
                        <td><?php echo $count; ?></td>
                        <td><?php echo trim($fee["feeType"]); ?></td>
                        <td><?php echo trim($fee["description"]); ?></td>
                    
                        <td><?php echo trim($fee["feeGroup"]); ?></td>
                        <td><?php echo trim($fee["status"]); ?></td>
                        <td class="fafa">
                            <?php if($fee["status"] =="ACTIVE"){?>
                            <a href="adminactions.php?status=ACTIVE&id=<?php echo $fee["id"];?>&page=f"><i class="fa fa-check fafaactive"></i></a>
                            <?php }else{?>
                            <a href="adminactions.php?status=INACTIVE&id=<?php echo
                                $fee["id"];?>&page=f"><i class="fa fa-close fafainactive"></i></a>
                            <?php } ?>
                            <a href="editfeetype.php?id=<?php echo $fee["id"]; ?>"><i class="fa fa-edit"></i></a> 
                            <a href="adminactions.php?action=delete&id=<?php echo $fee["id"]; ?>&page=f"><i class="fa fa-trash-o"></i></a>
                        </td>
                    </tr>
                    <?php $count++;
                        } 
                        }
                        // else {
                        //            echo "<tr><td colspan='7' class='text-center'>No Data Avaiable.</td></tr>";
                        //           }
                        ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>
<div class="row comment">
</div>
<?php
    
include_once(BASEPATH.'footer.php');

?>