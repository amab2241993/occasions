<?php
	session_start();
	include '../../connect.php';
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		try {
			$con->beginTransaction();
			$moveId     = $_POST['moveId'];
			$moveLineId = $_POST['moveLineId'];
			$type 	    = $_POST['type'];
			$price	    = $_POST['price'];
			$state	    = $_POST['state'];
			
			$stmt = $con->prepare(
				"INSERT INTO main()VALUES()"
			);
			$stmt->execute();
			
			$id = $con->lastInsertId();

			$stmt  = $con->prepare(
				"INSERT INTO move (main_id , statment , account_id , price)
				 VALUES(:zmain , :zstatment , :zaccount , :zprice)"
			);
			
			$stmt->execute(array(
				'zmain'     => $id,
				'zstatment' => $state,
				'zaccount'  => $moveId,
				'zprice'    => $price,
			));
			
			$lastId = $con->lastInsertId();
			
			$stmt = $con->prepare(
				"INSERT INTO move_line(debtor , move_id , account_id)
				 VALUES(:zdebtor , :zmove , :zaccount_id)"
			);
			$stmt->execute(array(
				'zdebtor'     => $price,
				'zmove'       => $lastId,
				'zaccount_id' => $type,
			));
			$stmt = $con->prepare(
				"INSERT INTO move_line(creditor , move_id , account_id)
				 VALUES(:zcreditor , :zmove , :zaccount_id)"
			);
			$stmt->execute(array(
				'zcreditor'   => $price,
				'zmove'       => $lastId,
				'zaccount_id' => $moveLineId,
			));
			$con->commit();
		} catch(PDOExecption $e) {
			$con->rollback();
			print "Error!: " . $e->getMessage() . "</br>";
		}
	}
?>