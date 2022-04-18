<?php
	session_start();
	include '../../connect.php';
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		try {
			$con->beginTransaction();
			$move      = $_POST['move'];
			$type 	   = $_POST['type'];
			$price	   = $_POST['price'];
			$money	   = $_POST['money'];
			$accountId = 12;
			$stmt = $con->prepare("UPDATE move SET price = ? WHERE id = ?");
			$stmt->execute(array($money , $move));
			$stmt = $con->prepare(
				"INSERT INTO move_line(creditor , move_id , account_id)
				 VALUES(:zcreditor , :zmove , :zaccount_id)"
			);
			$stmt->execute(array(
				'zcreditor'   => $price,
				'zmove'       => $move,
				'zaccount_id' => $type,
			));
			$stmt = $con->prepare(
				"INSERT INTO move_line(debtor , move_id , account_id)
				 VALUES(:zdebtor , :zmove , :zaccount_id)"
			);
			$stmt->execute(array(
				'zdebtor'     => $price,
				'zmove'       => $move,
				'zaccount_id' => $accountId,
			));
			$con->commit();
		} catch(PDOExecption $e) {
			$con->rollback();
			print "Error!: " . $e->getMessage() . "</br>";
		}
	}
?>