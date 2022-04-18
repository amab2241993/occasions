<?php
	session_start();
	include '../../connect.php';
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		try {
			$con->beginTransaction();
			$details = $_POST['details'];
			foreach($details as $detail){
				$quantity = $detail['quantity'];
				$serviceId = $detail['itemId'];

				$stmt = $con->prepare(
					"UPDATE store_service SET quantity = quantity - $quantity
					 WHERE store_id = ? AND service_id = ?"
				);
				$stmt->execute(array($detail['storeId'] , $serviceId));
				$stmt = $con->prepare(
					"SELECT quantity FROM store_service WHERE store_id = ? AND service_id = ? LIMIT 1"
				);
				$stmt->execute(array(4 , $serviceId));
				if($stmt->rowCount() > 0){
					$stmt = $con->prepare(
						"UPDATE store_service SET quantity = quantity + $quantity
						 WHERE store_id = ? AND service_id = ?"
					);
					$stmt->execute(array(4 , $serviceId));
				}
				else{
					$stmt = $con->prepare(
						"INSERT INTO store_service(service_id , store_id , quantity , worker_id)
						 VALUES(:zserviceId , :zstoreId , :zquantity , :zworkerId)"
					);
					$stmt->execute(array(
						'zserviceId' => $serviceId ,
						'zstoreId'   => 4 ,
						'zworkerId'  => $detail['workerId'],
						'zquantity'  => $quantity
					));
				}
			}
			$con->commit();
		} catch(PDOExecption $e) {
			$con->rollback();
			print "Error!: " . $e->getMessage() . "</br>";
		}
	}
?>