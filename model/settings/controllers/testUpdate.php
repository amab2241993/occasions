<?php
	session_start();
	include '../../../connect.php';
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$accountId	 = $_POST['accountId'];
		$parentId	 = $_POST['parentId'];
		$accountName = $_POST['accountName'];
		$stmt = $con->prepare("SELECT name FROM accounts WHERE name = ? AND  id != ? AND parent_id = ?");
		$stmt->execute(array($accountName , $accountId , $parentId));
		$count = $stmt->rowCount();
		if($count > 0) echo true;
		else echo false;
	}
?>