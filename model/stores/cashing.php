<?php
	session_start();
	header('Content-type: application/json; charset=utf-8');
	include '../../connect.php';
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		try {
			$billId = $_POST['billId'];
			$stmt = $con->prepare(
				"SELECT bills.id , bills.details , bills.bill_type , cashing.quantity FROM bills
				 LEFT OUTER JOIN cashing ON cashing.bill_id = bills.id
				 WHERE bills.id = $billId GROUP BY bills.id LIMIT 1"
			);
			$stmt->execute();
			$rows = $stmt->fetch();
			echo json_encode($rows);
		} catch(PDOExecption $e) {
			print "Error!: " . $e->getMessage() . "</br>";
		}
	}
?>