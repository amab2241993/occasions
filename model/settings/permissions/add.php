<?php
	session_start();
	include '../../../connect.php';
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$userId       = $_POST['userId'];
		$permissionId = $_POST['permissionId'];
		$stmt = $con->prepare(
			"INSERT INTO user_permission(user_id , permission_id) VALUES(:zuserId , :zpermissionId)"
		);
		$stmt->execute(array(
			'zuserId'       => $userId ,
			'zpermissionId' => $permissionId
		));
	}
?>