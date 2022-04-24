<?php
	session_start();
	include '../../connect.php';
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		try {
			$con->beginTransaction();
			$serviceId = $_POST['serviceId'];
			$stmt = $con->prepare(
				"SELECT store_service.quantity , services.parent_id FROM store_service
				 INNER JOIN services ON store_service.service_id = services.id
				 WHERE services.id = $serviceId AND store_service.store_id != 4 LIMIT 1"
			);
			$stmt->execute();
			$service = $stmt->fetch();
			$parentId = $service['parent_id'];
			$quantity = $service['quantity'];
			echo $service['parent_id'];
			$stm = $con->prepare(
				"UPDATE store_service SET quantity = quantity - $quantity
				 WHERE service_id = $parentId AND store_service.store_id != 4"
			);
			$stm->execute();
			$stmt = $con->prepare("DELETE FROM services WHERE id = ?");
			$stmt->execute(array($serviceId));
			$count = $stmt->rowCount();
			if ($count > 0) {
				$result = true;
			}
			else{
				$result = false;
			}
			echo $result;
			$con->commit();
		} catch(PDOExecption $e) {
			$con->rollback();
			print "Error!: " . $e->getMessage() . "</br>";
		}
	}
?>