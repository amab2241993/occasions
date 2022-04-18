<?php
	session_start();
	include '../connect.php';
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$user_name = $_POST['user'];
		$password  = $_POST['pass'];
		$hashedPass = sha1($password);
		// Check If The User Exist In Database
		$stmt = $con->prepare("SELECT id,user_name,password FROM users WHERE user_name = ? AND password = ? LIMIT 1");
		$stmt->execute(array($user_name, $hashedPass));
		$row = $stmt->fetch();
		$count = $stmt->rowCount();
		// If Count > 0 This Mean The Database Contain Record About This Username
		if ($count > 0) {
			$_SESSION['user_name'] = $user_name; // Register Session Name
			$_SESSION['id'] = $row['id']; // Register Session ID
		}
	}
?>