<?php
	ob_start();

	session_start(); // Start The Session

	session_unset(); // Unset The Data

	session_destroy(); // Destory The Session

	header('Location: ../../index.php');
	
	ob_flush();

	exit();
?>