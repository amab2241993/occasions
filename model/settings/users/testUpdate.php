<?php
	session_start();
	include '../../../connect.php';
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$userId   = $_POST['userId'];
		$userName = $_POST['userName'];
		$stmt = $con->prepare("SELECT user_name FROM users WHERE user_name = ? AND id != ? LIMIT 1");
		$stmt->execute(array($userName , $userId));
		$count = $stmt->rowCount();
		if($count > 0) echo true;
		else echo false;
	}
?>