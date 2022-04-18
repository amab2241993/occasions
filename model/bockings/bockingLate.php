<?php
	session_start();
	include '../../connect.php';
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		try {
			$con->beginTransaction();
			$res   	   = $_POST['res'];
			$main      = $_POST['main'];
			$type 	   = $_POST['type'];
			$price	   = $_POST['price'];
			$idA       = 3;
			$idAA      = 12;
			$stmt  = $con->prepare(
				"INSERT INTO move_fake (main_id , account_id , price)
				 VALUES(:zmain , :zaccount , :zprice)"
			);
			$stmt->execute(array(
				'zmain'    => $main,
				'zaccount' => $idA,
				'zprice'   => $price,
			));
			$lastId = $con->lastInsertId();
			$stmt = $con->prepare(
				"INSERT INTO move_line_fake(creditor , move_fake_id , account_id)
				 VALUES(:zcreditor , :zmove , :zaccount_id)"
			);
			$stmt->execute(array(
				'zcreditor'   => $price,
				'zmove'       => $lastId,
				'zaccount_id' => $type,
			));
			$stmt = $con->prepare(
				"INSERT INTO move_line_fake(debtor , move_fake_id , account_id)
				 VALUES(:zdebtor , :zmove , :zaccount_id)"
			);
			$stmt->execute(array(
				'zdebtor'     => $price,
				'zmove'       => $lastId,
				'zaccount_id' => $idAA,
			));
			$stmt =$con->prepare("SELECT id , details , code , price FROM bills WHERE id = $res LIMIT 1");
			$stmt->execute();
			$bill = $stmt->fetch();
			$billCode = $bill['code'];

			$stmt = $con->prepare("SELECT COUNT(*) FROM bills WHERE status = 3");
			$stmt->execute();
			$numRow = $stmt->fetchColumn() + 1;
			$stmt = $con->prepare("UPDATE bills SET status = 3 , code = $numRow WHERE id = $res");
			$stmt->execute();
			
			$stmt = $con->prepare("SELECT id , code FROM bills WHERE code > $billCode AND status = 1 ORDER BY code ASC");
			$stmt->execute();
			$codes = $stmt->fetchAll();
			echo $stmt->rowCount(). " : " . $billCode; 
			if($stmt->rowCount() > 0){
				foreach($codes as $x){
					$coder = ($x['code'] - 1);
					$counter = $x['id'];
					$stmt = $con->prepare("UPDATE bills SET code = $coder WHERE id = $counter");
					$stmt->execute();
				}
			}
			$con->commit();
		}catch(PDOExecption $e) {
			$con->rollback();
			print "Error!: " . $e->getMessage() . "</br>";
		}
	}
?>