<?php
	require_once('config.php');
	$getparentmailid = sqlgetresult('SELECT p."email" AS mail1 , p."secondaryEmail" AS mail2 FROM tbl_student s LEFT JOIN tbl_parents p ON s."parentId" = p."id" LEFT JOIN tbl_challans c ON c."studentId" = s."studentId" WHERE c."challanNo" IS NOT NULL AND p."email" IS NOT NULL AND s."stream" = \'2\' AND s.class= \'19\' GROUP BY  p."email",p."secondaryEmail" ');

	// echo count($getparentmailid);

	// echo 'SELECT p.* FROM tbl_challans c LEFT JOIN tbl_student s ON c."studentId" = s."studentId" LEFT JOIN tbl_parents p ON s."parentId" = p."id" WHERE p.email IS NOT NULL GROUP BY p.id';
	// $getparentmailid = array_values($getparentmailid);
	// print_r($getparentmailid);
	foreach ($getparentmailid as $v) {
		$data = 'Dear Parents,<br/>Please login to the fee app and check the correct amount of challans, ignore the challan mailed. Please proceed to pay from website. Optional fees will be available only at the fee app portal.';
		$subject = 'Challan Generation Error';
		// print_r($v['mail1']);
		// print_r($v['mail2']);
		// $send = SendMailId($v['mail1'], $subject, $data );
		if($send) {
			echo "email sent to M1- ".$v['mail1'];echo "<br/>";
		}
		
		if($v['mail2'] !== 0) {
			// $send = SendMailId($v['mail2'], $subject, $data );
			if($send) {
				echo "email sent to M2- ".$v['mail2'];echo "<br/>";
			}			
		}
		
	}

?>