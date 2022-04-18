<?php
	session_start();
	include '../../connect.php';
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		try {
			$con->beginTransaction();
			$name     = $_POST['name'];
			$priceCus = $_POST['priceCus'];
			$priceCom = $_POST['priceCom'];
			$priceEmp = $_POST['priceEmp'];
			$quantity = $_POST['quantity'];
			$store    = $_POST['store'];
			$worker   = $_POST['worker'];

			$stmt = $con->prepare("INSERT INTO services(name) VALUES(:zname)");
			$stmt->execute(array('zname'   => $name));
			if($stmt->rowCount() === 1){
				$id = $con->lastInsertId();
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