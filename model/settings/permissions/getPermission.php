<?php
	session_start();
	header('Content-type: application/json; charset=utf-8');
	include '../../../connect.php';
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$userId = $_POST['userId'];
		$st = $con->prepare(
			"SELECT name , id FROM permissions ORDER BY id ASC"
		);
		$st->execute();
		$permissions = $st->fetchAll();
		$stmt = $con->prepare(
			"SELECT permissions.name , user_permission.id FROM permissions
			 INNER JOIN user_permission ON user_permission.permission_id = permissions.id
			 WHERE user_permission.user_id = $userId"
		);
		$stmt->execute();
		$user_permission = $stmt->fetchAll();
		$count = $stmt->rowCount();
		if($count > 0){
			foreach ($permissions as $key=>$permission){
				$status = false;
				$id = 0;
				foreach ($user_permission as $userPermission){
					if($userPermission['name'] == $permission['name']){
						$status = true;
						$id = $userPermission['id'];
					}
				}
				$permissions[$key]['userPermission'] = $id;
			}
		}
		else{
			foreach ($permissions as $key=>$permission){
				$permissions[$key]['userPermission'] = 0;
			}
		}
		echo json_encode($permissions);
	}
?>