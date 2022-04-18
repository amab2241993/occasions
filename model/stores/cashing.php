<?php
	session_start();
	header('Content-type: application/json; charset=utf-8');
	include '../../connect.php';
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		try {
			$billId = $_POST['billId'];
			$stmt = $con->prepare("SELECT details , bill_type FROM bills WHERE id = $billId LIMIT 1");
			$stmt->execute();
			$rows = $stmt->fetch();
			echo json_encode($rows);
		} catch(PDOExecption $e) {
			print "Error!: " . $e->getMessage() . "</br>";
		}
	}
?>