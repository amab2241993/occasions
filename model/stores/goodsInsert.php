<?php
	session_start();
	include '../../connect.php';
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		try {
			$con->beginTransaction();
			$details = $_POST['details'];
			$key = 0;
			foreach($details as $detail){
				$newQuantity = $detail['newQuantity'];
				$oldQuantity = $detail['oldQuantity'];
				$quantity    = intval($newQuantity) - intval($oldQuantity);
				$serviceId   = $detail['itemId'];
				$parentId    = $detail['parentId'];
				if($detail['parentId'] > 0){
					$stmt = $con->prepare(
						"UPDATE store_service SET quantity = quantity + $quantity
						 WHERE store_id = ? AND service_id = ?"
					);
					$stmt->execute(array($detail['storeId'] , $parentId));
				}

				$stmt = $con->prepare(
					"UPDATE store_service SET quantity = ? WHERE store_id = ? AND service_id = ?"
				);
				$stmt->execute(array($newQuantity , $detail['storeId'] , $serviceId));
			}
			$con->commit();
		} catch(PDOExecption $e) {
			$con->rollback();
			print "Error!: " . $e->getMessage() . "</br>";
		}
	}
?>