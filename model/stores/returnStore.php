<?php
	session_start();
	header('Content-type: application/json; charset=utf-8');
	include '../../connect.php';
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		try {
			$con->beginTransaction();
			$cashingId = $_POST['cashingId'];
			$serviceId = $_POST['serviceId'];
			$parentId  = $_POST['parentId'];
			$quantity  = $_POST['quantity'];
			$old	   = $_POST['old'];
			$stmtS = $con->prepare(
				"UPDATE store_service SET quantity = quantity + $quantity
				 WHERE service_id = $serviceId AND store_id != 4"
			);
			$stmtS->execute();
			if(intval($parentId) != 0){
				$stmtP = $con->prepare(
					"UPDATE store_service SET quantity = quantity + $quantity
					 WHERE service_id = $serviceId AND store_id != 4"
				);
				$stmtP->execute();
			}
			if(intval($quantity) == intval($old)){
				$stmt = $con->prepare("DELETE FROM cashing WHERE id = ?");
				$stmt->execute(array($cashingId));
			}
			else{
				$stmt = $con->prepare(
					"UPDATE cashing SET quantity = quantity - $quantity
					 WHERE id = $cashingId"
				);
				$stmt->execute();
			}
			// echo json_encode($rows);
			$con->commit();
		} catch(PDOExecption $e) {
			$con->rollback();
			print "Error!: " . $e->getMessage() . "</br>";
		}
	}
?>