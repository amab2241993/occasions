<?php
	session_start();
	header('Content-type: application/json; charset=utf-8');
	include '../../connect.php';
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		try {
			$con->beginTransaction();
			$details = $_POST['details'];
			foreach($details as $detail){
				$ssId	   = $detail['ssId'];
				$serviceId = $detail['serviceId'];
				$storeId   = $detail['storeId'];
				$parentId  = $detail['parentId'];
				$billId	   = $detail['billId'];
				$quIn      = $detail['quIn'];
				$quOut     = $detail['quOut'];
				$stmt = $con->prepare(
					"SELECT id FROM cashing WHERE bill_id = $billId AND service_id = $serviceId LIMIT 1"
				);
				$stmt->execute();
				$row = $stmt->fetch();
				$count = $stmt->rowCount();
				if ($count > 0) {
					$thisId   = $row['id'];
					$stmt = $con->prepare(
						"UPDATE cashing SET quantity = quantity + $quIn WHERE id = $thisId"
					);
					$stmt->execute();
					foreach($quOut as $value){
						$cId = intval($value['cId']);
						if(intval($value['cId']) != 0){
							$st = $con->prepare(
								"SELECT id , quantity FROM cashing_out WHERE cashing_id = $thisId
								 AND customer_id = $cId LIMIT 1"
							);
							$st->execute();
							$ro   = $st->fetch();
							$ct   = $st->rowCount();
							$qu   = $value['pieces'];
							if($ct > 0){
								$thId = $ro['id'];
								$stt = $con->prepare(
									"UPDATE cashing_out SET quantity = quantity + $qu WHERE id = $thId"
								);
								$stt->execute();
							}
							else{
								$stmt = $con->prepare(
									"INSERT INTO cashing_out(cashing_id , customer_id , quantity)
									 VALUES(:zcashingId , :zcustomerId , :zquantity)"
								);
								$stmt->execute(array(
									'zcashingId'  => $thisId,
									'zcustomerId' => $value['cId'],
									'zquantity'	  => $value['pieces']
								));
							}
						}
					}
				}
				else{
					// if($parentId <=> "" || $parentId <=> null){
					// 	$parentId = 0;
					// }
					$stmt = $con->prepare(
						"INSERT INTO cashing(store_service_id , service_id , store_id , parent_id , 
						 bill_id , quantity) VALUES(:zssId , :zserviceId , :zstoreId , :zparentId ,
						 :zbillId , :zquantity)"
					);
					$stmt->execute(array(
						'zssId'		 => $ssId,
						'zserviceId' => $serviceId,
						'zstoreId'	 => $storeId,
						'zparentId'	 => $parentId,
						'zbillId'	 => $billId,
						'zquantity'	 => $quIn
					));
					$id = $con->lastInsertId();
					foreach($quOut as $value){
						if(intval($value['cId']) != 0){
							$stmt = $con->prepare(
								"INSERT INTO cashing_out(cashing_id , customer_id , quantity)
								 VALUES(:zcashingId , :zcustomerId , :zquantity)"
							);
							$stmt->execute(array(
								'zcashingId'  => $id,
								'zcustomerId' => $value['cId'],
								'zquantity'	  => $value['pieces']
							));
						}
					}
				}
				
				$stmtS = $con->prepare(
					"UPDATE store_service SET quantity = quantity - $quIn WHERE id = $ssId"
				);
				$stmtS->execute();

				$stmtP = $con->prepare(
					"UPDATE store_service SET quantity = quantity - $quIn
					 WHERE service_id = $parentId AND store_id != 4"
				);
				$stmtP->execute();
			}
			// echo json_encode($rows);
			$con->commit();
		} catch(PDOExecption $e) {
			$con->rollback();
			print "Error!: " . $e->getMessage() . "</br>";
		}
	}
?>