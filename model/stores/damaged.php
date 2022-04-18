<?php
	session_start();
	header('Content-type: application/json; charset=utf-8');
	include '../../connect.php';
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		try {
			$storeId = $_POST['storeId'];
			$stmt = $con->prepare(
				"SELECT services.id , services.name , services.parent_id , store_service.quantity , worker_id
				 FROM store_service
				 INNER JOIN services ON store_service.service_id = services.id
				 WHERE store_service.store_id = $storeId AND store_service.quantity != 0"
			);
			$stmt->execute();
			$rows = $stmt->fetchAll();
			foreach($rows as $key => $row){
				$tester = false;
				foreach($rows as $cul){
					if($row['id'] == $cul['parent_id']){
						$tester = true;
					}
				}
				if($tester){unset($rows[$key]);}
			}
			echo json_encode($rows);
		} catch(PDOExecption $e) {
			print "Error!: " . $e->getMessage() . "</br>";
		}
	}
?>