<?php
	require_once('admnavbar.php');
	$data = sqlgetresult("SELECT * FROM nonfeetypecheck",true);
?>

<div class="container-fluid">
    <div class="row col-md-12">
        <div class="col-xs-1 col-sm-2 col-md-3 col-lg-2"></div>
        <div class="col-xs-10 col-sm-8 col-md-6 col-lg-8">  
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
        <div class="col-xs-1 col-sm-2 col-md-3 col-lg-2"></div>
    </div>
</div>
<div class="col-md-12 ">
    <div class="col-md-6 p-l-0">
        <h2 class="top">NON-FEE CHALLANS</h2>
    </div>
    <div class="col-md-6 p-r-0 text-right">
        <a href="addnonfeetype.php" class=" btn btn-info">Add Non Fee Type</a>
    </div>
</div>
<div class="col-md-12 m-t-15" >
    <div class="table-responsive">
        <form>
            <table class="table table-bordered admintab ">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Non Fee Type</th>
                        <th>Description</th>
                        <th>Fee Group</th>
                        <th>AccountNo</th>                       
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $i=1;
                      
                        $num = ($data!= null) ? count($data) : 0;
                        if($num > 0)
                        {      

                        // print_r($data);   
	                        foreach ($data as $data) {	                         
	                         ?>
	                          <!-- date("d-m-Y", strtotime($data['createdOn'])) -->
		                    	<tr class="filterrowchallan">
			                        <td><?php echo $i;?></td>
			                        <td><?php echo $data['feeType'];?></td>
			                        <td><?php echo $data['description'];?></td>
			                        <td><?php echo trim($data['feeGroup']);?></td>
                                    <td><?php echo trim($data['acc_no']);?></td>
			                        <td><?php echo trim($data["status"]); ?></td>
			                        <td class="fafa">
			                            <?php if($data["status"] =="ACTIVE"){?>
			                            <a href="adminactions.php?status=ACTIVE&id=<?php echo $data["id"];?>&page=nf"><i class="fa fa-check fafaactive"></i></a>
			                            <?php }else{?>
			                            <a href="adminactions.php?status=INACTIVE&id=<?php echo
			                                $data["id"];?>&page=nf"><i class="fa fa-close fafainactive"></i></a>
			                            <?php } ?>
			                            <a href="editnonfeetype.php?id=<?php echo $data["id"]; ?>"><i class="fa fa-edit"></i></a> 
			                            <a href="adminactions.php?action=delete&id=<?php echo $data["id"]; ?>&page=nf"><i class="fa fa-trash-o"></i></a>
			                        </td>
			                    </tr>
	                    <?php 
	                        	$i++;
	                        }
                        }                         
                        ?>
                </tbody>
            </table>
        </form>
    </div>
</div>
</div>


<div class="row comment"></div>
<?php
	include_once(BASEPATH.'footer.php');
?>