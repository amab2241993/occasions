<?php
	session_start();
	include '../../../connect.php';
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		try {
			$newCode = null;
			$con->beginTransaction();
			$parent = $_POST['parent'];
			$name 	= $_POST['name'];
			$stmt = $con->prepare("SELECT COUNT(*) FROM accounts WHERE parent_id = $parent");
			$stmt->execute();
			$number_of_rows = $stmt->fetchColumn();
			$stmt = $con->prepare("SELECT code FROM accounts WHERE id = $parent LIMIT 1");
			$stmt->execute();
			$code = $stmt->fetch();
			if(strlen((string)($number_of_rows + 1)) == 1)
			$newCode = $code['code']."/0".($number_of_rows + 1);
			else
			$newCode = $code['code']."/".($number_of_rows + 1);
			$stmt = $con->prepare(
				"INSERT INTO accounts(name , parent_id , code) VALUES(:zname , :zparent , :zcode)"
			);
			$stmt->execute(array(
				'zname'   => $name,
				'zparent' => $parent,
				'zcode'   => $newCode,
			));
			$con->commit();
		} catch(PDOExecption $e) {
			$con->rollback();
			print "Error!: " . $e->getMessage() . "</br>";
		}
	}
?>