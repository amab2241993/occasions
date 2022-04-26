<?php
	session_start();
	include '../../../connect.php';
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$customerName  = $_POST['customerName'];
		$customerPhone = $_POST['customerPhone'];
		$customerId    = $_POST['customerId'];
		$stmt = $con->prepare("UPDATE customers SET name = ? , phone = ? WHERE id = ?");
		$stmt->execute(array($customerName , $customerPhone , $customerId));
		$count = $stmt->rowCount();
		if($count > 0){
			echo true;
		}
		else{
			echo false;
		}
	}
?>