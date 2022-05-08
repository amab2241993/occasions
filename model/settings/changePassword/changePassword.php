<?php
	session_start();
	include '../../../connect.php';
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		try {
			$password = $_POST['password'];
			$hashedPass = sha1($password);
			// Check If The User Exist In Database
			$stmt = $con->prepare("UPDATE users SET password = ? WHERE user_name = ?");
			$stmt->execute(array($hashedPass , $_SESSION['user_name']));
			echo $stmt->rowCount() == 1 ? 1 : 0;
		} catch(PDOExecption $e) {
			print "Error!: " . $e->getMessage() . "</br>";
		}
	}
?>