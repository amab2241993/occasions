<?php
	session_start();
	// header('Content-type: application/json; charset=utf-8');
	include 'connect.php';
	$stmt = $con->prepare(
		"SELECT services.id , services.name , services.parent_id , store_service.quantity , worker_id
			FROM store_service
			INNER JOIN services ON store_service.service_id = services.id"
	);
	$stmt->execute();
	$rows = $stmt->fetchAll();
	$counter = 0;
	foreach($rows as $row){
		$index = 0;
		echo "counter : " . ++$counter."<br>";
		foreach($rows as $key => $cul){
			echo "counter : " . ++$index."  ,  name : ".$cul['name']."<br>";
			unset($rows[$key]);
		}
		echo "_______________________________________________"."<br>";
	}
	// echo json_encode($rows);
?>