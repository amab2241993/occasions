<?php
	session_start();
	include '../../connect.php';
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		try {
			$con->beginTransaction();
			$billId    = $_POST['billId'];
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
			if($stmt->rowCount() == 1){
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
				$stmt =$con->prepare("SELECT id , details , code , price FROM bills WHERE id = $billId LIMIT 1");
				$stmt->execute();
				$bill = $stmt->fetch();
				$billCode = $bill['code'];

				$stmt = $con->prepare("SELECT COUNT(*) FROM bills WHERE status = 3");
				$stmt->execute();
				$numRow = $stmt->fetchColumn() + 1;
				$stmt = $con->prepare("UPDATE bills SET status = 3 , code = $numRow WHERE id = $billId");
				$stmt->execute();
				
				$stmt = $con->prepare("SELECT id , code FROM bills WHERE code > $billCode AND status = 1 ORDER BY code ASC");
				$stmt->execute();
				$codes = $stmt->fetchAll();
				if($stmt->rowCount() > 0){
					foreach($codes as $x){
						$coder = ($x['code'] - 1);
						$counter = $x['id'];
						$stmt = $con->prepare("UPDATE bills SET code = $coder WHERE id = $counter");
						$stmt->execute();
					}
				}
				$result = array(
					["message"=>"success" ,
					"billId" => $billId,
					"status"=>100]
				);
				echo json_encode($result);
			}
			else{
				$con->rollback();
				$result = array(
					["message"=>"خطأ فى البيانات" ,
					"billId" => $billId,
					"status"=>101]
				);
				echo json_encode($result);
			}
			$con->commit();
		}catch(PDOExecption $e) {
			$con->rollback();
			$result = array(
				["message"=>"خطأ فى البيانات" ,
				"billId" => $billId,
				"status"=>101]
			);
			echo json_encode($result);
		}
	}
?>