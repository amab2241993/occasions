<?php
	session_start();
	header('Content-type: application/json; charset=utf-8');
	include '../../connect.php';
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		try {
			$customerId = $_POST['customerId'] != 1 ? 2 : 1;
			$stmt = $con->prepare("SELECT id , name FROM customers WHERE status = $customerId");
			$stmt->execute();
			$rows = $stmt->fetchAll();
			echo json_encode($rows);
		} catch(PDOExecption $e) {
			print "Error!: " . $e->getMessage() . "</br>";
		}
	}
?>