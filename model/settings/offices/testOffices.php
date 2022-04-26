<?php
	session_start();
	include '../../../connect.php';
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$name   = $_POST['name'];
		$status = $_POST['status'];
		$stmt = $con->prepare("SELECT name FROM customers WHERE name = ? AND status = ?");
		$stmt->execute(array($name , $status));
		$count = $stmt->rowCount();
		if($count > 0) echo true;
		else echo false;
	}
?>