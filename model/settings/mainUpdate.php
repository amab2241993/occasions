<?php
	session_start();
	include '../../connect.php';
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		try {
			$con->beginTransaction();
			$serviceId      = $_POST['serviceId'];
			$storeServiceId = $_POST['storeServiceId'];
			$name           = $_POST['name'];
			$customerPrice  = $_POST['customerPrice'];
			$companionPrice = $_POST['companionPrice'];
			$employeePrice  = $_POST['employeePrice'];
			$stmt = $con->prepare("UPDATE services SET name = ? WHERE id = ?");
			$stmt->execute(array($name , $serviceId));
			$stmt = $con->prepare(
				"UPDATE store_service SET customer_price = ?  , companion_price = ? ,
				 employee_price = ? WHERE id = ?"
			);
			$stmt->execute(array($customerPrice , $companionPrice , $employeePrice , $storeServiceId));
			if($stmt->rowCount() > 0){
				$st = $con->prepare(
					"SELECT store_service.* FROM services INNER JOIN store_service ON
					 store_service.service_id = services.id WHERE services.parent_id = $serviceId
					 AND store_service.store_id != 4"
				);
				$st->execute();
				$services = $st->fetchAll();
				if(!empty($services)){
					foreach($services as $service){
						$stmt = $con->prepare(
							"UPDATE store_service SET customer_price = :zcup  , companion_price = :zcop ,
							 employee_price = :zemp WHERE id = :zid"
						);
						$stmt->execute(array(
							'zcup' => $customerPrice , 
							'zcop' => $companionPrice , 
							'zemp' => $employeePrice , 
							'zid'  => $service['id']
						));
					}
				}
			}
			$con->commit();
			echo true;
		} catch(PDOExecption $e) {
			$con->rollback();
			print "Error!: " . $e->getMessage() . "</br>";
		}
	}
?>