<?php
require_once('../header.php');
// $_SESSION["user_id"] = "";
	session_destroy();
	// print_r($_SESSION);
	header("Location:login.php");
?>