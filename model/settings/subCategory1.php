<?php
	session_start();
	header('Content-type: application/json; charset=utf-8');
	include '../../connect.php';
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		try {
			$con->beginTransaction();
			$quantity = null;
			$quantity1 = null;
			$quantity2 = null;
			$parent = $_POST['parent'];
			$stmt = $con->prepare(
				"SELECT sum(store_service.quantity) as quantity FROM store_service INNER JOIN services ON
				 store_service.service_id = services.id WHERE store_service.service_id = $parent
				 AND store_service.store_id != 4"
			);
			$stmt->execute();
			if($data = $stmt->fetch(PDO::FETCH_ASSOC)){
				$quantity1 = $data['quantity'];
			}
			$stmt1 = $con->prepare(
				"SELECT sum(store_service.quantity) as quantity FROM store_service INNER JOIN services ON
				 store_service.service_id = services.id WHERE services.parent_id = $parent
				 AND store_service.store_id != 4"
			);
			$stmt1->execute();
			if($data = $stmt1->fetch(PDO::FETCH_ASSOC)){
				$quantity2 = $data['quantity'];
			}
			$con->commit();
			echo json_encode(intval($quantity1) - intval($quantity2));
		} catch(PDOExecption $e) {
			$con->rollback();
			print "Error!: " . $e->getMessage() . "</br>";
		}
	}
?>