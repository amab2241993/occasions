<?php
	session_start();
	include '../../../connect.php';
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$accountId	 = $_POST['accountId'];
		$accountName = $_POST['accountName'];
		$stmt = $con->prepare(
			"UPDATE accounts SET name = ? WHERE id = ?"
		);
		$stmt->execute(array($accountName , $accountId));
		$count = $stmt->rowCount();
		if($count > 0){
			echo true;
		}
		else{
			echo false;
		}
	}
?>