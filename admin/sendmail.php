<?php
	require_once('config.php');
	$getparentmailid = sqlgetresult('SELECT p."email" AS mail1 , p."secondaryEmail" AS mail2 FROM tbl_student s LEFT JOIN tbl_parents p ON s."parentId" = p."id" LEFT JOIN tbl_challans c ON c."studentId" = s."studentId" WHERE c."challanNo" IS NOT NULL AND p."email" IS NOT NULL GROUP BY  p."email",p."secondaryEmail" ');

	// echo 'SELECT p.* FROM tbl_challans c LEFT JOIN tbl_student s ON c."studentId" = s."studentId" LEFT JOIN tbl_parents p ON s."parentId" = p."id" WHERE p.email IS NOT NULL GROUP BY p.id';
	$getparentmailid = array_values($getparentmailid);
	foreach ($getparentmailid as $v) {
		$data = 'Dear Parents,<br/>Please ignore challans generated and mailed. Login to the website where these have been re-generated and placed. Please proceed to pay from website. Optional fees will be available only at the fee app portal.';
		$subject = 'Challan Generation Error';
		// echo $v;
		$send = SendMailId($v, $subject, $data );
		echo "email sent to - ".$v;echo "<br/>";
	}

?>