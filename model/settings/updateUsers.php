<?php
	session_start();
	include '../../connect.php';
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$name = $_POST['name'];
		$full = $_POST['full'];
		$userId = $_POST['userId'];
		$stmt = $con->prepare(
			"UPDATE users SET user_name = ? , full_name = ? WHERE id = ?"
		);
		$stmt->execute(array($name , $full , $userId));
		echo 1;
	}
?>