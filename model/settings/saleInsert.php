<?php
	session_start();
	include '../../connect.php';
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		try {
			$con->beginTransaction();
			$details = $_POST['details'];
			foreach($details as $detail){
				echo $detail['price'];
				$quantity = $detail['quantity'];
				$serviceId = $detail['itemId'];

				$stmt = $con->prepare(
					"UPDATE store_service SET quantity = quantity - $quantity
					 WHERE store_id = ? AND service_id = ?"
				);
				$stmt->execute(array($detail['storeId'] , $serviceId));
				
				$stmt = $con->prepare(
					"INSERT INTO main()VALUES()"
				);
				$stmt->execute();
				$mainId = $con->lastInsertId();

				$stmt = $con->prepare(
					"INSERT INTO move(main_id , account_id , price , statment)
						VALUES(:zmainId , :zaccountId , :zprice , :zstatment)"
				);
				$stmt->execute(array(
					'zmainId'    => $mainId ,
					'zaccountId' => 1 ,
					'zprice'     => $detail['price'],
					'zstatment'  => $detail['statment']
				));
				$moveId = $con->lastInsertId();
				$stmt = $con->prepare(
					"INSERT INTO move_line(creditor , move_id , account_id)
					 VALUES(:zcreditor , :zmove , :zaccount_id)"
				);
				$stmt->execute(array(
					'zcreditor'   => intval($detail['price']),
					'zmove'       => $moveId,
					'zaccount_id' => $detail['type'],
				));
				$stmt = $con->prepare(
					"INSERT INTO move_line(debtor , move_id , account_id)
					 VALUES(:zdebtor , :zmove , :zaccount_id)"
				);
				$stmt->execute(array(
					'zdebtor'     => intval($detail['price']),
					'zmove'       => $moveId,
					'zaccount_id' => 8,
				));
			}
			$con->commit();
		} catch(PDOExecption $e) {
			$con->rollback();
			print "Error!: " . $e->getMessage() . "</br>";
		}
	}
?>