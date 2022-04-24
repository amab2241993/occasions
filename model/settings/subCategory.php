<?php
	session_start();
	header('Content-type: application/json; charset=utf-8');
	include '../../connect.php';
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		try {
			$parent = $_POST['parent'];
			$stmt = $con->prepare(
				"SELECT services.id as serviceId , services.name as category , services.parent_id , 
				 store_service.* FROM store_service
				 INNER JOIN services ON store_service.service_id = services.id
				 WHERE (services.parent_id = $parent AND store_service.store_id != 4) ORDER BY id DESC"
			);
			$stmt->execute();
			$rows = $stmt->fetchAll();
			echo json_encode($rows);
		} catch(PDOExecption $e) {
			print "Error!: " . $e->getMessage() . "</br>";
		}
	}
?>