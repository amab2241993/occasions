<?php
	session_start();
	include '../../connect.php';
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		try {
			$con->beginTransaction();
			$name     = $_POST['name'];
			$quantity = $_POST['quantity'];
			$parent	  = $_POST['parent'];
			$row	  = '';
			$priceCus = '';
			$priceCom = '';
			$priceEmp = '';
			$store    = '';
			$worker	  = '';
			$stmt = $con->prepare("INSERT INTO services(name , parent_id) VALUES(:zname , :zparent)");
			$stmt->execute(array('zname'   => $name , 'zparent' => $parent));
			if($stmt->rowCount() === 1){
				$id = $con->lastInsertId();
				$stmt = $con->prepare(
					"SELECT services.name as category , services.parent_id , store_service.* FROM store_service
					 INNER JOIN services ON store_service.service_id = services.id
					 WHERE (services.id = $parent AND store_service.store_id != 4)
					 ORDER BY id DESC LIMIT 1"
				);
				$stmt->execute();
				$row = $stmt->fetch();
				if(empty($row)){
					$stmt = $con->prepare(
						"SELECT services.name as category , services.parent_id , store_service.* FROM store_service
						 INNER JOIN services ON store_service.service_id = services.id
						 WHERE (services.id = $parent AND store_service.store_id = 4)
						 ORDER BY id DESC LIMIT 1"
					);
					$stmt->execute();
					$row = $stmt->fetch();
					$store = 1;
				}
				else{
					$store = $row['store_id'];
				}
				$priceCus = $row['customer_price'];
				$priceCom = $row['companion_price'];
				$priceEmp = $row['employee_price'];
				$store	  = $row['store_id'];
				$worker	  = $row['worker_id'];

				$stmt = $con->prepare(
					"INSERT INTO store_service(store_id , service_id , worker_id , customer_price ,
					 companion_price , employee_price , quantity)
					 VALUES(:zstore , :zservice , :zworker , :zcustomer , :zcompanion , :zemployee , :zquantity)"
				);
				$stmt->execute(array(
					'zstore'     => $store,
					'zservice'   => $id,
					'zworker'    => $worker,
					'zcustomer'  => $priceCus,
					'zcompanion' => $priceCom,
					'zemployee'  => $priceEmp,
					'zquantity'  => $quantity,
				));
			}
			else{$con->rollback();}
			$con->commit();
		} catch(PDOExecption $e) {
			$con->rollback();
			print "Error!: " . $e->getMessage() . "</br>";
		}
	}
?>