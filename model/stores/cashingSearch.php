<?php
	session_start();
	header('Content-type: application/json; charset=utf-8');
	include '../../connect.php';
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		try {
			$serviceId = $_POST['serviceId'];
			$stmt = $con->prepare("SELECT id , name FROM services WHERE parent_id = $serviceId");
			$stmt->execute();
			$rows = $stmt->fetchAll();
			echo json_encode($rows);
		} catch(PDOExecption $e) {
			print "Error!: " . $e->getMessage() . "</br>";
		}
	}
?>