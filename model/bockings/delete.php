<?php
	session_start();
	include '../../connect.php';
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		try {
			$con->beginTransaction();
			$mainId = $_POST['mainId'];
			$code   = $_POST['code'];
			$status = $_POST['status'];
			$result = false;
			// Check If The User Exist In Database
			if($status == 2){
				$stmt = $con->prepare(
					"SELECT bill_expense.main_id FROM bills INNER JOIN bill_expense ON
						bills.id = bill_expense.bill_id WHERE bills.main_id = ? LIMIT 1"
				);
				$stmt->execute(array($mainId));
				$bill_expense = $stmt->fetch();
				if($stmt->rowCount() > 0){
					$stmt = $con->prepare("DELETE FROM main WHERE id = ?");
					$stmt->execute(array($bill_expense['main_id']));
				}
			}
			$stmt = $con->prepare("DELETE FROM main WHERE id = ?");
			$stmt->execute(array($mainId));
			$count = $stmt->rowCount();
			if ($count > 0) {
				$stmt = $con->prepare(
					"SELECT id , code FROM bills WHERE code > ? AND status = ?  ORDER BY code ASC"
				);
				$stmt->execute(array($code , $status));
				$allCodes = $stmt->fetchAll(); 
				if($stmt->rowCount() > 0){
					foreach($allCodes as $allCode){
						$coder = ($allCode['code'] - 1);
						$counter = $allCode['id'];
						$stmt = $con->prepare("UPDATE bills SET code = $coder WHERE id = $counter");
						$stmt->execute();
					}
				}
				$result = true;
			}
			else{
				$result = false;
			}
			echo $result;
			$con->commit();
		} catch(PDOExecption $e) {
			$con->rollback();
			print "Error!: " . $e->getMessage() . "</br>";
		}
	}
?>