<?php
	session_start();
	include '../../../connect.php';
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$loanId = $_POST['loanId'];
		$loanA  = $_POST['loanA'];
		$loanP  = $_POST['loanP'];
		$loanN  = $_POST['loanN'];
		$stmt = $con->prepare(
			"UPDATE loans SET name = ? , phone = ?  , address = ? WHERE id = ?"
		);
		$stmt->execute(array($loanN , $loanP , $loanA , $loanId));
		$count = $stmt->rowCount();
		if($count > 0){
			echo true;
		}
		else{
			echo false;
		}
	}
?>