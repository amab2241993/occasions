<?php
	session_start();
	header('Content-type: application/json; charset=utf-8');
	include '../../connect.php';
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		try {
			$id = $_POST['id'];
			$stmt = $con->prepare(
				"SELECT services.name , services.id AS count , store_service.* FROM store_service
				 INNER JOIN services ON store_service.service_id = services.id
				 WHERE services.id = $id AND store_service.store_id != 4 LIMIT 1"
			);
			$stmt->execute();
			$row = $stmt->fetch();
			echo json_encode($row);
		} catch(PDOExecption $e) {
			print "Error!: " . $e->getMessage() . "</br>";
		}
	}
?>