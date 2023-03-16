<?php
include_once('admnavbar.php');

$id=$_REQUEST['id'];
if($id != ''){
    getreceiptreport($id);
    $response = getreceiptreport($id);  
    $receiptresponse = $response['receipt'];
    $demandresponse = $response['demand'];
    $commonresponse = $response['common'];

}

?>
<div class="col-md-12 ">
    <div class="col-md-6 p-l-0">
        <h2 class="top">STUDENT LEDGER</h2>
    </div>
    <div class="col-md-6 p-r-0 text-right">
       <form  id="filterreceiptreportview" class="form-inline">
            <div class="col-md-3 form-group">
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
            <div class="col-md-3 form-group">
                <div>
                    <?php
                        $semestercheck = sqlgetresult('SELECT * FROM tbl_semester',true);
                        ?>
                    <select name="semesterselect"  class="semesterselect form-control ">
                        <option value="">Semester</option>
                        <?php
                        
                            foreach($semestercheck as $semester) {
                            echo '<option value="'.$semester['semester'].'" >'.$semester['semester'].'</option>';
                            }
                            ?>
                    </select>
                </div>
            </div>
            <div class="col-md-3 form-group">
                <div>
                    <input type="text" name="studentid" id="studentid" class="studentid form-control" required>
                </div>
            </div>   

            <div class="col-md-3 form-group">
                <button type="submit" name="filter" value="filterreceiptreportview" class="btn btn-info">Filter</button>
            </div>
        </form>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="contentreport col-sm-12">
            <div class="col">
                <table class="table report_tbl">
                    <tr>
                        <td>
                            <label>Name: </label>
                            <span><?php echo($commonresponse['studentName']); ?></span>
                        </td>
                        <td>
                            <label>Id:</label>
                            <span><?php echo($commonresponse['studentId']); ?></span>
                        </td>
                        </tr>
                        <tr class="innerborder1">
                        <td>
                            <label>Gender: </label>
                            <span><?php 
                            if($commonresponse['gender'] == 'M'){
                                echo("Male");
                            }
                            else if($commonresponse['gender'] == 'F'){
                                echo("Female");
                            }
                            else{
                                echo("Nil");
                            }
                            ?></span>
                        </td>
                        <td>
                            <label>Stream: </label>
                            <span><?php echo($commonresponse ['stream']); ?></span>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="col-sm-12">
                    <?php 
                        foreach($demandresponse AS $acayear => $fulldata){
                            foreach($fulldata AS $term => $fulldata2){
                    ?>
                <div class="col-sm-2">
                    <label>Academic Year: </label>
                    <div style="clear:both;">&nbsp</div>
                    <span><?php echo($acayear); ?></span>
                    <div style="clear:both;">&nbsp</div>
                    <label>Semester: </label>
                    <div style="clear:both;">&nbsp</div>
                    <span><?php echo($term); ?></span>
                </div>
                <div class = "col-sm-10">

                    <table class="report_tbl table" >
                            <tr>
                            <!-- <td colspan = 3 style="text-align:center"> -->
                            <h3 class="top">Demand Report for <?php echo($acayear); ?><h3>
                            <!-- </td> -->
                            </tr>
                            <tr>
                            <td>
                            <label>Fee Group</label>
                            </td>
                            <td>
                            <label>Fee Type</label>
                            </td>
                            <td>
                            <label>Amount</label>
                            </td>
                            </tr>

                            <?php
                            $pamt = 0;
                            $amount = 0;
                            foreach($fulldata2['feeGroup'] as $i => $row){
                                // foreach ($fulldata2['feeType'] as $j => $el) {
                                    // foreach ($fulldata2['total'] as $k => $el) {
                                    if($fulldata2['total'][$i] != 0){
                                        echo('<tr><td>' . $row . '</td><td>' . $fulldata2['feeType'][$i] . '</td><td>' . $fulldata2['total'][$i] . '</td></tr>');
                                         $pamt += $fulldata2['total'][$i];
                                    }
                                    // }
                                // }
                            }
                            $amount += $pamt;
                            ?>

                            <tr>
                            <td colspan="2"></td>
                            <td>
                            <p class="tot" style="border-top:1px solid #000;text-align:right;padding-top: 15px;">
                                <span id="grand_tot"><?php echo($amount); ?></span>
                            </p>
                            </td>
                            </tr>
                    </table>
                </div>
            <?php
                }
            }
            ?>
            </div>

            <div class="col-sm-12">
                    <?php 
                        foreach($receiptresponse AS $acayear => $fulldata){
                            foreach($fulldata AS $term => $fulldata2){
                    ?>
                <div class="col-sm-2">
                    <label>Academic Year: </label>
                    <div style="clear:both;">&nbsp</div>
                    <span><?php echo($acayear); ?></span>
                    <div style="clear:both;">&nbsp</div>
                    <label>Semester: </label>
                    <div style="clear:both;">&nbsp</div>
                    <span><?php echo($term); ?></span>
                </div>
                <div class = "col-sm-10">

                    <table class="report_tbl table" >
                            <tr>
                            <h3 class="top">Receipt Report for <?php echo($acayear); ?><h3>
                            </tr>
                            <tr>
                            <td>
                            <b>Particulars</b>
                            </td>
                            <td style="text-align:right">
                            <b>Paid Date</b>
                            </td>
                            <td style="text-align:right">
                            <b>Pay Type</b>
                            </td>
                            <td style="text-align:right">
                            <b>Demand</b>
                            </td>
                            <td style="text-align:right">
                            <b>Waived</b>
                            </td>
                            <td style="text-align:right">
                            <b>Receipt</b>
                            </td>
                            </tr>

                            <?php
                            // foreach($receiptresponse AS $receiptdata1){
                                // foreach($receiptdata1 AS $receiptdata2){

                                        $groupamountdemand = 0;
                                        $groupamountwaived = 0;
                                        $groupamountreceipt = 0;
                                        $demandamount = 0;
                                        $waivedamount = 0;
                                        $receiptamount = 0;

                                    foreach($fulldata2 AS $feegroup => $receiptdata3){
                                        // echo('<hr/>');
                                        // print_r($receiptdata3);
                                        $grouptotaldemand = array_sum($receiptdata3['total']);

                                        $paiddate = array_unique($receiptdata3['paid_date']);
                                        $paiddate= $paiddate[0];
                                        if($paiddate != ''){
                                            $paiddate= $paiddate;
                                        }
                                        else {
                                            $paiddate = array_unique($receiptdata3['updatedOn']);
                                            $paiddate= $paiddate[0];
                                        }

                                        $pay_type = array_unique($receiptdata3['pay_type']);
                                        $pay_type= $pay_type[0];
                                        // print_r($grouptotal);
                                        // print_r($paiddate);
                                        // print_r($pay_type);
                                        if($pay_type == ''){
                                            $pay_type = "Online";
                                        } else if($pay_type == 'Online'){
                                            $pay_type = "Online - FS";
                                        } else{
                                            $pay_type = $pay_type;
                                        }

                                        if($receiptdata3['waivedarray'] == 0){
                                            $waivedtotal = 0;
                                        }
                                        else{
                                            foreach($receiptdata3['waivedarray'] AS $waiveddata){
                                                $waivedtotal = $waiveddata['waiver_total'];
                                            }
                                        }
                                        $receipttotal = $grouptotaldemand - $waivedtotal;

                                        echo('<tr><td>' . $feegroup . '</td><td style="text-align:right">'.date("d-m-Y", strtotime($paiddate)).'</td><td style="text-align:right">'. $pay_type .'</td><td style="text-align:right">'.$grouptotaldemand.'</td>
                                            <td style="text-align:right">'.$waivedtotal.'</td><td style="text-align:right">'.$receipttotal.'</td></tr>');
                                        $groupamountdemand += $grouptotaldemand;
                                        $groupamountwaived += $waivedtotal;
                                        $groupamountreceipt += $receipttotal;


                                    }
                                    $demandamount += $groupamountdemand;
                                    $waivedamount += $groupamountwaived;
                                    $receiptamount += $groupamountreceipt;

                                // }
                            // }
                            ?>

                            <tr>
                            <td colspan="3"></td>
                            <td>
                            <p class="tot" style="border-top:1px solid #000;text-align:right;padding-top: 15px;">
                                <span id="grand_tot"><?php echo($demandamount); ?></span>
                            </p>
                            </td>
                            <td>
                            <p class="tot" style="border-top:1px solid #000;text-align:right;padding-top: 15px;">
                                <span id="grand_tot"><?php echo($waivedamount); ?></span>
                            </p>
                            </td>
                            <td>
                            <p class="tot" style="border-top:1px solid #000;text-align:right;padding-top: 15px;">
                                <span id="grand_tot"><?php echo($receiptamount); ?></span>
                            </p>
                            </td>
                            </tr>
                    </table>
                </div>
            <?php
                }
            }
            ?>
            </div>
 
                <div class="text-center">
                    <button class="btn btn-warning text-center" onclick="location.href='receiptreport.php';" >Back</button>
                </div>
        </div>
    </div>
</div>



