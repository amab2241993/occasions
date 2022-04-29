<?php
	session_start();
	include '../../../connect.php';
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$loanId = $_POST['loanId'];
		$loanN  = $_POST['loanN'];
		$stmt = $con->prepare("SELECT name FROM loans WHERE name = ? AND  id != ?");
		$stmt->execute(array($loanN , $loanId));
		$count = $stmt->rowCount();
		if($count > 0) echo true;
		else echo false;
	}
?>