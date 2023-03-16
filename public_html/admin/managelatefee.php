<?php
    require_once('admnavbar.php');
    ?>
<div class="container-fluid">
    <div class=" col-md-12">
        <div class="col-xs-1 col-sm-2 col-md-3 col-lg-4"></div>
        <div class="col-xs-10 col-sm-8 col-md-6 col-lg-4">
            <?php
                if(isset($_SESSION['successlatefee'])) {
                echo $_SESSION['successlatefee'];
                unset($_SESSION['successlatefee']);
                    }
                if(isset($_SESSION['errorlatefee'])) {
                echo $_SESSION['errorlatefee'];
                unset($_SESSION['errorlatefee']);
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
        <h2 class="top">LATE FEE </h2>
    </div>   
    <!-- <a href="addlatefee.php"><button class="btn btn-info butt">Add Late Fee</button></a> -->
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-bordered admintab dataTableLateFee">
                <!-- <caption class="title">Sales Data of Electronic Division</caption> -->
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>No Of Days</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $count=1;
                        $sql = 'SELECT * FROM latefeecheck';
                        $res = sqlgetresult($sql,true);
                        $num = ($res!= null) ? count($res) : 0;
                        if($num > 0)
                        {
                        foreach ($res as $late)
                        {
                        ?>
                    <tr>
                        <td><?php echo $count; ?></td>
                        <td><?php echo trim($late["noOfDays"]); ?></td>
                        <td class="text-right"><?php echo trim($late["amount"]); ?></td>
                        <td><?php echo trim($late["status"]); ?></td>
                        <td class="fafa">
                            <?php if($late["status"] =="ACTIVE"){?>
                            <a href="adminactions.php?status=ACTIVE&id=<?php echo $late["id"];?>&page=l"><i class="fa fa-check fafaactive"></i></a>
                            <?php }else{?>
                            <a href="adminactions.php?status=INACTIVE&id=<?php echo
                                $late["id"];?>&page=l"><i class="fa fa-close fafainactive"></i></a>
                            <?php } ?>
                            <a href="editlatefee.php?id=<?php echo $late["id"]; ?>"><i class="fa fa-edit"></i></a> 
                            <a href="adminactions.php?action=delete&id=<?php echo $late["id"]; ?>&page=l"><i class="fa fa-trash-o"></i></a>
                        </td>
                    </tr>
                    <?php $count++;
                        } 
                        }
                        // else {
                        //            echo "<tr><td colspan='5'>No Data Avaiable.</td></tr>";
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