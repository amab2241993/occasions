<?php
	session_start();
	include '../../../connect.php';
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$userName = $_POST['userName'];
		$fullName = $_POST['fullName'];
		$thisName = $_POST['thisName'];
		$userId   = $_POST['userId'];
		$stmt = $con->prepare("UPDATE users SET user_name = ? , full_name = ? WHERE id = ?");
		$stmt->execute(array($userName , $fullName , $userId));
		$count = $stmt->rowCount();
		if($count > 0){
			if($_SESSION['user_name'] == $thisName){
				$_SESSION['user_name'] = $userName;
			}
			echo true;
		}
		else{
			echo false;
		}
	}
?>