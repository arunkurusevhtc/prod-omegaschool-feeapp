<?php
    include_once('admnavbar.php');
    ?>
<div class="container-fluid">
    <div class=" col-md-12">
        <div class="col-xs-1 col-sm-2 col-md-3 col-lg-4"></div>
        <div class="col-xs-10 col-sm-8 col-md-6 col-lg-4">
            <?php
                if(isset($_SESSION['successtax'])) {
                echo $_SESSION['successtax'];
                unset($_SESSION['successtax']);
                    }
                if(isset($_SESSION['errortax'])) {
                echo $_SESSION['errortax'];
                unset($_SESSION['errortax']);
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
    <div class="col-md-12 p-r-0">
       <h2 class="top">TAX </h2>
    </div>    
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-bordered admintab dataTableTax">
                <!-- <caption class="title">Sales Data of Electronic Division</caption> -->
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Effective Date</th>
                        <th>Tax Type</th>
                        <th>Central Tax</th>
                        <th>State Tax</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $count=1;
                        $sql = 'SELECT * FROM taxcheck';
                        $res = sqlgetresult($sql,true);
                        $num = ($res!= null) ? count($res) : 0;
                        if($num > 0)
                        {
                        foreach ($res as $tax)
                        {
                        ?>
                    <tr>
                        <td><?php echo $count; ?></td>
                        <td><?php echo date('d-m-y',strtotime(trim($tax["effectiveDate"]))); ?></td>
                        <td><?php echo trim($tax["taxType"]); ?></td>
                        <td><?php echo trim($tax["centralTax"]); ?></td>
                        <td><?php echo trim($tax["stateTax"]); ?></td>
                        <td><?php echo trim($tax["status"]); ?></td>
                        <td class="fafa">
                            <?php if($tax["status"] =="ACTIVE"){?>
                            <a href="adminactions.php?status=ACTIVE&id=<?php echo $tax["id"];?>&page=ta"><i class="fa fa-check fafaactive"></i></a>
                            <?php }else{?>
                            <a href="adminactions.php?status=INACTIVE&id=<?php echo
                                $tax["id"];?>&page=ta"><i class="fa fa-close fafainactive"></i></a>
                            <?php } ?>
                            <a href="edittax.php?id=<?php echo $tax["id"]; ?>"><i class="fa fa-edit"></i></a> 
                            <a href="adminactions.php?action=delete&id=<?php echo $tax["id"]; ?>&page=ta"><i class="fa fa-trash-o"></i></a>
                        </td>
                    </tr>
                    <?php $count++;
                        } 
                        }
                        // else {
                        //            echo "<tr><td colspan='7' style='text-align:center;'>No Data Avaiable.</td></tr>";
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