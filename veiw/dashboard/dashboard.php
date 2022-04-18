<?php
	ob_start(); // Output Buffering Start
	session_start();
	if (isset($_SESSION['user_name'])) {
		$pageTitle = 'dashboard';
		$getH3 = "صفحة الحجوزات";
		include '../../init.php';
		include $tpl . 'navbar.php';
		$date = '';
		if(isset($_GET['do'])&& isset($_GET['date'])){
			$dateSent = date($_GET['date']);
			$timestamp  = strtotime($dateSent);
			if($_GET["do"] == 1){
				$prev = date('Y-m-d', mktime(0, 0, 0, date('m', $timestamp)-1, 1, date('Y', $timestamp)));
				$date = date($prev);
			}
			elseif($_GET['do'] == 2){
				$next = date('Y-m', mktime(0, 0, 0, date('m', $timestamp)+1, 1, date('Y', $timestamp)));
				$date = date($next);
			}
		}
		else{
			$date = date('Y-m-d');
		}
		$calendar = new Calendar($date);
		$timestamp = strtotime($date);
		$year = date('Y', $timestamp);
		$month = date('m', $timestamp);
		$stmt = $con->prepare(
			"SELECT DISTINCT bill_date , status FROM bills WHERE month(bill_date) = ? AND year(bill_date) = ?"
		);
		$stmt->execute(array($month , $year));
		$rows = $stmt->fetchAll();
		if (! empty($rows)){
			foreach($rows as $row){
				// $timestamp = strtotime($date);
				if($row['status'] == 1){
					$calendar->add_event("<a href='"."../bockings/bockingFirst.php?date=".$row['bill_date']."'>"."حجز اولى"."</a>" , $row['bill_date'], 1, 'red');
				}
				elseif($row['status'] == 2){
					$calendar->add_event("<a href='"."../bockings/bockingFinal.php?date=".$row['bill_date']."'>"."حجز نهائى"."</a>" , $row['bill_date'], 1, 'green');
				}
				elseif($row['status'] == 3){
					$calendar->add_event("<a href='"."../bockings/bockingLate.php?date=".$row['bill_date']."'>"."حجز مؤجل"."</a>" , $row['bill_date'], 1, 'yellow');
				}
			}
		}
	?>
	<?=$calendar?>
	<?php
		/* End Dashboard Page */
		include $tpl . 'footer.php';
		include $tpl . 'footerClose.php';
	}
	else{
		header('Location: ../../index.php');
		exit();
	}
	ob_end_flush();
?>