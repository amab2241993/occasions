<?php
	session_start();
	include '../../../connect.php';
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$customerName    = $_POST['customerName'];
		$customerPhone   = $_POST['customerPhone'];
		$customerAddress = $_POST['customerAddress'];
		$customerId      = $_POST['customerId'];
		$stmt = $con->prepare("UPDATE customers SET name = ? , phone = ? , address = ? WHERE id = ?");
		$stmt->execute(array($customerName , $customerPhone , $customerAddress , $customerId));
		echo $stmt->rowCount() == 1 ? true : false;
	}
?>