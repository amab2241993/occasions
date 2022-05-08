<?php
	session_start();
	header('Content-type: application/json; charset=utf-8');
	include '../../connect.php';
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		try {
			$stmt = '';
			$serviceId = $_POST['serviceId'];
			$stmt = $con->prepare(
				"SELECT services.id , services.name , services.parent_id ,
				 store_service.id AS ssId , store_service.store_id , store_service.quantity
				 FROM services INNER JOIN store_service ON store_service.service_id = services.id
				 WHERE services.parent_id = $serviceId AND store_service.store_id != 4"
			);
			$stmt->execute();
			$count = $stmt->rowCount();
			if ($count == 0) {
				$stmt = $con->prepare(
					"SELECT services.id , services.name , services.parent_id,
					 store_service.id AS ssId , store_service.store_id , store_service.quantity
					 FROM services INNER JOIN store_service ON store_service.service_id = services.id
					 WHERE services.id = $serviceId AND store_service.store_id != 4"
				);
				$stmt->execute();
			}
			$rows = $stmt->fetchAll();
			echo json_encode($rows);
		} catch(PDOExecption $e) {
			print "Error!: " . $e->getMessage() . "</br>";
		}
	}
?>