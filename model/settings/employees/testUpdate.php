<?php
	session_start();
	include '../../../connect.php';
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$employeeId = $_POST['employeeId'];
		$employeeN  = $_POST['employeeN'];
		$stmt = $con->prepare("SELECT name FROM employees WHERE name = ? AND  id != ?");
		$stmt->execute(array($employeeN , $employeeId));
		$count = $stmt->rowCount();
		if($count > 0) echo true;
		else echo false;
	}
?>