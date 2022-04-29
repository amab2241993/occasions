<?php
	session_start();
	include '../../../connect.php';
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$name   = $_POST['name'];
		$stmt = $con->prepare("SELECT name FROM loans WHERE name = ?");
		$stmt->execute(array($name));
		$count = $stmt->rowCount();
		if($count > 0) echo true;
		else echo false;
	}
?>