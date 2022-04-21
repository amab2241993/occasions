<?php
	session_start();
	include '../../connect.php';
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		try {
			$con->beginTransaction();
			$billId    = $_POST['billId'];
			$move      = $_POST['move'];
			$type 	   = $_POST['type'];
			$price	   = $_POST['price'];
			$money	   = $_POST['money'];
			$accountId = 12;
			$stmt = $con->prepare("UPDATE move_fake SET price = ? WHERE id = ?");
			$stmt->execute(array($money , $move));
			$stmt = $con->prepare(
				"INSERT INTO move_line_fake(creditor , move_fake_id , account_id)
				 VALUES(:zcreditor , :zmove , :zaccount_id)"
			);
			$stmt->execute(array(
				'zcreditor'   => $price,
				'zmove'       => $move,
				'zaccount_id' => $type,
			));
			$stmt = $con->prepare(
				"INSERT INTO move_line_fake(debtor , move_fake_id , account_id)
				 VALUES(:zdebtor , :zmove , :zaccount_id)"
			);
			$stmt->execute(array(
				'zdebtor'     => $price,
				'zmove'       => $move,
				'zaccount_id' => $accountId,
			));
			$result = array(
				["message"=>"success" ,
				"billId" => $billId,
				"status"=>100]
			);
			echo json_encode($result);
			$con->commit();
		} catch(PDOExecption $e) {
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