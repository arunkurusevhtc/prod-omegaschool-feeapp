<?php
/*error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);*/
date_default_timezone_set('Asia/Calcutta');
if(isset($_POST['paytotal']) && $_POST['paytotal']=='partial'){
   $createdOn = date("Y-m-d h:m:s");
   $uid = $_SESSION['uid'];
   $studentId = isset($_POST['studentId'])?trim($_POST['studentId']):"";
   $chalAcademicYear = isset($_POST['chalAcademicYear'])?trim($_POST['chalAcademicYear']):"";
   /* Student Primary ID */
   $s_id = isset($_POST['s_id'])?trim($_POST['s_id']):"";
   $grand_tot = isset($_POST['grand_tot'])?trim($_POST['grand_tot']):"";
   $challanids = isset($_POST['challanids'])?$_POST['challanids']:"";
   $sfs_amt = isset($_POST['sfs_amt'])?$_POST['sfs_amt']:[];
   $school_amt = isset($_POST['school_amt'])?$_POST['school_amt']:[];
   $feeTotal = isset($_POST['ftot'])?trim($_POST['ftot']):"";
   $tot = isset($_POST['tot'])?trim($_POST['tot']):"";
   $balance = isset($_POST['balamt'])?trim($_POST['balamt']):0;
   $waived_tot = isset($_POST['waived_tot'])?trim($_POST['waived_tot']):0;
   $amount = isset($_POST['partialamt'])?trim($_POST['partialamt']):0;
   $minamt = isset($_POST['minamt'])?trim($_POST['minamt']):0;
   $partialpaidamt = isset($_POST['partialpaidamt'])?trim($_POST['partialpaidamt']):0;
   $payop = isset($_POST['payop'])?trim($_POST['payop']):"";
   $disppart = isset($_POST['disppart'])?trim($_POST['disppart']):1;
   $actualAmt = $amount;
   $cautionamt = 0;

   $caution_deposit_json = isset($_POST['caution_deposit'])?$_POST['caution_deposit']:"";
   if($payop=='caution'){
     $caution_deposit=($caution_deposit_json)?"'".$caution_deposit_json."'":"NULL";
     $cautionamt = isset($_POST['cautionamt'])?trim($_POST['cautionamt']):0;
   }else{
     $caution_deposit="NULL";
   }
   


   $rz_sfs=0;
   $rz_lmes=0;
   $rz_lmos=0;
   $prod_ary=[];

   $payment_method = isset($_POST['payment_method'])?trim($_POST['payment_method']):"atom"; 
   if(($amount >= $minamt && $amount <= $grand_tot) || $disppart==0){
      
       $paygroupval = isset($_POST['paygroup'])?$_POST['paygroup']:"";
       if(!empty($paygroupval)){
        $paygroup=json_decode($paygroupval,true);
       }else{
        $paygroup=[];
       }

      /*if($amount > 50000){
        $amount=50000;
      }*/


        
       if(count($sfs_amt) > 0){
        $jsonsfs_amt=json_encode($sfs_amt);

        foreach($sfs_amt as $sfs){
           $arrdt=explode("-",$sfs);
           $gp=trim($arrdt[6]);
           $amt=trim($arrdt[4])*trim($arrdt[7]);
           $paygroup[$gp]+=$amt;
        }
       }else{
        $jsonsfs_amt="";
       }

       if(count($school_amt) > 0){
        $jsonschool_amt=json_encode($school_amt);
        foreach($school_amt as $sch){
           $arrdt1=explode("-",$sch);
           $gp1=trim($arrdt1[6]);
           $amt1=trim($arrdt1[4]);
           $paygroup[$gp1]+=$amt1;
        }
       }else{
        $jsonschool_amt="";
       }
       

       $accdatails=array('SCHOOL FEE'=>array(1=>'1244172000004389', 2=>'1244172000004365', 3=>'1244172000004389', 4=>'1244172000004365', 6=>'1244172000114886', 5=>'1244172000004389', 7=>'1244172000004860'));

       $parentData = sqlgetresult('SELECT * FROM getparentdata WHERE "studentId" = \''.$studentId.'\' LIMIT 1',true);
       // $parentData[0]['challanNo'] = trim($eventname);
        //$_SESSION['PSLFData'] = $parentData[0];
        //$amount = $amount;   
        $cusName = trim($parentData[0]['userName']);
        $cusEmail = trim($parentData[0]['email']);
        $cusMobile = trim($parentData[0]['mobileNumber']); 
        $cusId = $parentData[0]['id'];
        $academicYear = trim($parentData[0]['academic_yr']);
        $term = trim($parentData[0]['term']);
        $stream = trim($parentData[0]['stream']); 
        $sid = $parentData[0]['sid'];
        $class = trim($parentData[0]['class']);
        $section = trim($parentData[0]['section']);   
        
        //$clientcode = $studentId . '|' . trim(getAcademicyrById($academicYear)) . '|' . $term;
        if($chalAcademicYear){
             $ay_name=trim(getAcademicyrById($chalAcademicYear));
             $clientcode = $studentId . '|' . $ay_name;
         }else{
             $ay_name=trim(getAcademicyrById($academicYear));
             $clientcode = $studentId . '|' . $ay_name;
         }

        $productData = sqlgetresult("SELECT * FROM tbl_accounts WHERE id = '$acc_id' ");

        if($balance > 0){
            if($balance >= $amount){
               //$amount=0;
               $updatebal=$balance-$amount;
               $debitedAmt=$amount;
               $amount=0;
            }else{
              $amount=$amount-$balance;
              $updatebal=0;
              $debitedAmt=$balance;  
            }
        }
        $minus=0;
        /*SCHOOL FEE*/
        if(($payop=='full' || $payop=='caution') && $partialpaidamt == 0 && $balance==0){
            $i = 1;
            $product = ('<products>');
            foreach ($paygroup as $key1 => $value) {
                $key1=trim($key1);
                $key=getFeeGroupbyId($key1);
                $key = trim($key);
                //echo $key;
                //echo $key."__".$stream."-".$value."<br>";
                /*if( $stream == '5' ) {
                    $pname = $productData['1244172000004389'];
                }else{*/

                    switch ($key) {
                        case "SFS UTILITIES FEE":
                            $pname = $productData['1244172000018485'];
                            $rz_sfs+=$value;
                            $prod_ary[]='SFS UTILITIES FEE';
                            break;
                        case "REFUNDABLE DEPOSIT":
                            $pname = $productData['1244172000004353'];
                            if($value >$cautionamt){
                                $rz_lmes+=$value-$cautionamt;
                                $minus=1;
                            }
                            $prod_ary[]='REFUNDABLE DEPOSIT';
                            break;
                        case "SCHOOL UTILITY FEE":
                            $pname = $productData['1244172000004377'];
                            $rz_lmos+=$value;
                            $prod_ary[]='SCHOOL UTILITY FEE';
                            break;    
                        case "TRANSPORT FEE":
                            $pname = $productData['1244172000004377'];
                            $rz_sfs+=$value;
                            $prod_ary[]='TRANSPORT FEE';
                            break;
                        case "LATE FEE":
                            $pname = $productData['1244172000004377'];
                            $rz_lmos+=$value;
                            $prod_ary[]='LATE FEE';
                            break;    
                        case "APPLICATION FEE":
                            $pname = $productData['1244155000122651'];
                            $prod_ary[]='APPLICATION FEE';
                            $rz_lmos+=$value;
                            break;    
                        default:
                            $pdata=isset($accdatails[$key][$stream])?$accdatails[$key][$stream]:$accdatails['SCHOOL FEE'][$stream];
                            $pname = !empty($pdata)?$productData[$pdata]:"";
                            $rz_lmos+=$value;
                            $prod_ary[]='SCHOOL FEE';
                    }

                //} 

                $pname=trim($pname);

                if($value != 0) {
                    if($pname == 'SIX'){
                        if($balance > 0){
                            $value=$value-$balance;
                        }
                        $product .=('<product><id>'.$i.'</id><name>'.$pname.'</name><amount>'.$value.'</amount></product>');

                    }else{
                        if($minus==1){
                            $value = $value-$cautionamt;
                        }
                        $product .=('<product><id>'.$i.'</id><name>'.$pname.'</name><amount>'.$value.'</amount></product>');
                    }
                   
                }
                $i++;
            }
           $product .=('</products>');
           //exit;
        }else{
            if($stream == '1' || $stream == '3' ) {
                $pname = $productData['1244172000004389'];
            } else if($stream == '2' || $stream == '4' ) {
                $pname = $productData['1244172000004365'];
            } else if($stream == '5') {
                $pname = $productData['1244172000004389'];
            } else if($stream == '7') {
                $pname = $productData['1244172000004860'];
            } else {
                /*$stream == '6'*/
                $pname = $productData['1244172000114886'];
            }
            $product = ('<products><product><id>1</id><name>'.trim($pname).'</name><amount>'.$amount.'</amount></product></products>');
            $rz_lmos+=$amount;
            $prod_ary[]='SCHOOL FEE';
        }
  
        
        $run1=sqlgetresult("SELECT * FROM createpartialtransaction('$s_id','$challanids','$jsonsfs_amt','$jsonschool_amt','$grand_tot','$feeTotal','$balance','$waived_tot','$uid','$product','$amount','$minamt','$payop')",true);
        //print_r($run1);
        
        $lastinsert_id = isset($run1[0]['createpartialtransaction'])?$run1[0]['createpartialtransaction']:""; 
        //exit;
        if(!empty($lastinsert_id)){
            $eventname="REF".$lastinsert_id;
            $parentData[0]['challanNo'] = $eventname;
            $_SESSION['PSLFData'] = $parentData[0];
            $datenow = date("d/m/Y h:m:s");
            $transactionDate = str_replace(" ", "%20", $datenow);
            //$transactionId = rand(1, 1000000);
            $transactionId = $eventname;
            $cusChallanNo = $eventname;

            if($amount == 0){
                //if($grand_tot == $amount){
                    $datenow1 = date("Y-m-d");
                    sqlgetresult('UPDATE tbl_partial_payment_log SET "transNum"=\''.$transactionId.'\',payment_url=\'Debited From wallet\',  "transStatus"=\'Ok\',"transDate"=\''.$datenow1.'\', partial_caution_deposit = '.$caution_deposit.',"studentId" = \''.$studentId.'\' WHERE id=\''.$lastinsert_id.'\'');
                    $_SESSION['partial_payment_id'] = $lastinsert_id;
                    /* caution deposit partial */
                    if(!empty($caution_deposit_json) && $payop=='caution'){
                        $chkcaution = toUpdateFeeTypePaidAmt($caution_deposit_json);
                    }

                    //$balance=toGetAvailableBalance($sid);
                    //$tot=$balance-$amount;
                    $wallet = sqlgetresult("SELECT * FROM addAdvanceAmt('".$sid."','$updatebal','".$_SESSION['uid']."')");
                    $resadv=$wallet['addadvanceamt']?$wallet['addadvanceamt']:"";
                    if(!empty($resadv)){
                        $run1=sqlgetresult("SELECT * FROM createadvancetransaction('$s_id','$uid','$product','$debitedAmt','$balance','2','Ok','$lastinsert_id','$eventname','1','$class','$academicYear','$stream','$term','$section')",true);
                    }

                    //$rstAmt=0;

                    partialEwalletPayProcess($challanids, $sid, $uid, $studentId, $term, $academicYear, $actualAmt, $lastinsert_id, $createdOn, $amount);
                    if(!empty($caution_deposit_json) && $payop=='caution'){
                        toCheckFeeTypePartial($caution_deposit_json);
                    }
                    /*$receiptupd=completePartialTransactionById($lastinsert_id);  
                    if($receiptupd > 0){*/
                        $_SESSION['success_msg'] = "<p class='success-msg'>Amount has been debited from the advance payment.</p>";
                    //}
                /*}else{

                }*/
            }
            else{
                if($payop=='full' || $payop=='caution'){
                    $cust=$challanids;
                }else{
                    $cust=$cusChallanNo;
                }

                if($payment_method=='razorpay'){
                    $stream_name="";
                    if($stream){
                      $stream_name=getStreambyId($stream);
                      $stream_name=trim($stream_name);
                    }
                    
                    $ref_chal_ids=toSubstr($challanids,256);
                    $prod_map=implode(",",array_unique($prod_ary));
                    $product_list=toSubstr($prod_map,256);
                    $raz_data=[];
                    $raz_data["receipt"]=$transactionId;
                    $raz_data["amount"]=$amount;
                    $raz_data["key"]=RAZOR_KEY_ID;
                    $raz_data["name"]=$cusName;
                    $raz_data["description"]=$transactionId;
                    $raz_data["image"]="";
                    $raz_data["email"]=$cusEmail;
                    $raz_data["contact"]=$cusMobile;
                    $raz_data["display_currency"]=DF_CURRENCY;
                    $raz_data["notes"]=[
                        "challan_number"=> $ref_chal_ids,
                        "student_id"=> $studentId,
                        "stream"=> $stream_name,
                        "academic_year"=> $ay_name,
                        "merchant_order_id" => $transactionId,
                        "products" => $product_list
                    ];

                    $raz_data["transfers"]=[];
                    if($rz_sfs >0){
                        $sfsnw=$rz_sfs*100;
                        $sfsaray=array('account' => 'acc_KAZSgySPlgemqb','amount' => $sfsnw,'currency' => DF_CURRENCY,'notes' => array('products' => 'SFS-FEES','challan_number' => $ref_chal_ids, "student_id"=>$studentId, 'student_id'=>$studentId,'stream'=> $stream_name,'academic_year'=> $ay_name,'merchant_order_id' => $transactionId),'linked_account_notes' => array('products','challan_number','student_id','stream','academic_year','merchant_order_id'),'on_hold' => 0);
                        $raz_data["transfers"][]=$sfsaray;
                    }

                    if($rz_lmos >0){
                        $pgnw=$rz_lmos*100;
                        $pgaray=array('account' => 'acc_KAZPfxbHVGFncj','amount' => $pgnw,'currency' => DF_CURRENCY,'notes' => array('products' => 'LMOIS','challan_number' => $ref_chal_ids, 'student_id'=>$studentId,'stream'=> $stream_name,'academic_year'=> $ay_name,'merchant_order_id' => $transactionId),'linked_account_notes' => array('products','challan_number','student_id','stream','academic_year','merchant_order_id'),'on_hold' => 0);
                        $raz_data["transfers"][]=$pgaray;
                    }

                    if($rz_lmes >0){
                        $rdamt=$rz_lmes*100;
                        $rdaray=array('account' => 'acc_KAZLPTyX7RArn2','amount' => $rdamt,'currency' => DF_CURRENCY,'notes' => array('products' => 'LMES','challan_number' => $ref_chal_ids, 'student_id'=>$studentId,'stream'=> $stream_name,'academic_year'=> $ay_name,'merchant_order_id' => $transactionId),'linked_account_notes' => array('products','challan_number','student_id','stream','academic_year','merchant_order_id'),'on_hold' => 0);
                        $raz_data["transfers"][]=$rdaray;
                    }

                   include_once('razorpay/tocreateorderid.php');
                   if($razorpayOrderId){
                    $_SESSION['razorpay_order_id']=$razorpayOrderId;
                    $raz_data_json = json_encode($raz_data);
                    $payment_id = sqlgetresult('UPDATE tbl_partial_payment_log SET payment_url = \''.$raz_data_json.'\',"transNum"=\''.$transactionId.'\', paymentmethod = \''.$payment_method.'\', razorpayorderid = \''.$razorpayOrderId.'\', partial_caution_deposit = '.$caution_deposit.',"studentId" = \''.$studentId.'\' WHERE "id" = \''.$lastinsert_id.'\'');

                    $data_ary = [
                     "key"  => $raz_data["key"],
                     "amount"=> $orderData['amount'],
                     "name" => "Omega School FeeAPP",
                     "description"  => $raz_data['description'],
                     "image" => "https://www.omegaschools.org/feeapp/images/razorpay_logo.png",
                     "prefill"  => [
                         "name" => $raz_data['name'],
                         "email" => $raz_data['email'],
                         "contact"  => $raz_data['contact']
                     ],
                     "notes" => $raz_data['notes'],
                     "theme" => ["color" => "#5181f0"],
                     "order_id"  => $razorpayOrderId,
                     "display_currency"  => $raz_data['display_currency'],
                     "display_amount"  => $raz_data['amount']
                    ];

                    $invoice_id=$raz_data["receipt"];
                    $surl="verify_rz_payments.php";
                    $curl="checkout.php?st=c&id=".$transactionId;
                    $ptype="challanpayments";
                    $parnt_id=$uid;
                    $st_id=$studentId;
                    $data_json = json_encode($data_ary);
                    require_once("razorpay/automatic.php");
                    exit;
                   }else{
                      $_SESSION['error_msg']="<p class='error-msg'>Razorpay order id is not generated. Try again later.</p>";
                   }
                }else{

                    //$encoded_data = base64_encode($studentId."_".$lastinsert_id."_".$cusChallanNo."_".$grand_tot."_".$challanids."_".$balance."_".$partialpaidamt);
                    $encoded_data = base64_encode($studentId."_".$lastinsert_id."_".$grand_tot."_".$balance."_".$partialpaidamt); 
                    $returnstudscr = BASEURL.'parse_payment.php?partialPayment='.$encoded_data;
                    require_once 'atompay/TransactionRequest.php';
                    $transactionRequest = new TransactionRequest(); 

                    //Setting all values here
                    $transactionRequest->setMode($paymode);
                    $transactionRequest->setLogin($login);
                    $transactionRequest->setPassword($pass);    
                    $transactionRequest->setProductId($proId);
                    $transactionRequest->setAmount($amount);
                    $transactionRequest->setTransactionCurrency($currency);
                    $transactionRequest->setTransactionAmount($amount);
                    $transactionRequest->setReturnUrl($returnstudscr);
                    $transactionRequest->setClientCode($clientcode);
                    $transactionRequest->setTransactionId($transactionId);
                    $transactionRequest->setTransactionDate($transactionDate);
                    $transactionRequest->setCustomerName($cusName);
                    $transactionRequest->setCustomerEmailId($cusEmail);
                    $transactionRequest->setCustomerChallanNo($cust);
                    $transactionRequest->setCustomerMobile($cusMobile);
                    $transactionRequest->setCustomerBillingAddress("Chennai");
                    $transactionRequest->setProducts($product);
                    $transactionRequest->setCustomerAccount("639827");
                    $transactionRequest->setReqHashKey($ReqHashKey);

                    $url  = $transactionRequest->getPGUrl();
                    // $payment_id = sqlgetresult("INSERT INTO tbl_payments (payment_url) VALUES ('$url') RETURNING id ");
                    sqlgetresult('UPDATE tbl_partial_payment_log SET "transNum"=\''.$transactionId.'\',payment_url=\''.$url.'\', paymentmethod = \''.$payment_method.'\', partial_caution_deposit = '.$caution_deposit.',"studentId" = \''.$studentId.'\' WHERE id=\''.$lastinsert_id.'\'');
                    $_SESSION['partial_payment_id'] = $lastinsert_id;
                    header("Location: ".$url);
                    exit;
               }
            }
        }else{
            $_SESSION['error_msg'] = "<p class='error-msg'> Some Error has occured. Try again later.</p>";
            $errordata = 'Partial Amount of Challans:</br>studentId:' . $studentId . '</br>amount:' . $amount . '</br>' . $_SESSION['error'];
            createErrorlog($errordata);
        }
    }else{
        $_SESSION['error_msg'] = "<p class='error-msg'> Amount should be greater than or equal to minimum due.</p>";
   }
   header("Location: checkout.php");
   exit;
}

if(isset($_GET['st']) && $_GET['st']=='c' && isset($_GET['id']) && !empty($_GET['id'])){
    $invoice_number=trim($_GET['id']);
    if (strpos($invoice_number, 'REF') !== false){
        $lastinsert_id=str_replace("REF","",$invoice_number);
        $datenow1 = date("Y-m-d");
        $remarks='TRANSACTION IS CANCELLED BY USER ON PAYMENT PAGE.';
        sqlgetresult('UPDATE tbl_partial_payment_log SET remarks=\''.$remarks.'\',  "transStatus"=\'C\',"transDate"=\''.$datenow1.'\' WHERE id=\''.$lastinsert_id.'\' AND "transStatus" IS NULL');
    }
    header("Location: checkout.php");
    exit;
}

?>