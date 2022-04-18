<?php
	session_start();
	include '../../connect.php';
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		try {
			$con->beginTransaction();
			$details = $_POST['details'];
			foreach($details as $detail){
				$newQuantity = $detail['newQuantity'];
				$serviceId   = $detail['itemId'];

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