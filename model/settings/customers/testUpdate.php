<?php
	session_start();
	include '../../../connect.php';
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$customerId     = $_POST['customerId'];
		$customerName   = $_POST['customerName'];
		$customerStatus = $_POST['customerStatus'];
		$stmt = $con->prepare("SELECT name FROM customers WHERE name = ? AND ( id != ? AND status = ?)");
		$stmt->execute(array($customerName , $customerId , $customerStatus));
		echo $stmt->rowCount() == 1 ? true : false;
	}
?>