<?php
	ob_start(); // Output Buffering Start
	session_start();
	if (isset($_SESSION['user_name'])) {
		$pageTitle = 'treasury bank';
		$getH3 = 'الخزنة والبنك';
		include '../../init.php';
		$stmt = $con->prepare(
			"SELECT sum(debtor) as debtor , sum(creditor) as creditor FROM move_line WHERE account_id = 6"
		);
		$stmt1 = $con->prepare(
			"SELECT sum(debtor) as debtor , sum(creditor) as creditor FROM move_line WHERE account_id = 7"
		);
		$stmt->execute();
		$stmt1->execute();
		$total = 0;
		$total1 = 0;
		if($data = $stmt->fetch(PDO::FETCH_ASSOC)){
			$total = $data['creditor'] - $data['debtor'];
		}
		if($data = $stmt1->fetch(PDO::FETCH_ASSOC)){
			$total1 = $data['creditor'] - $data['debtor'];
		}
		/* Start Dashboard Page */
	?>
	<div class="row">
		<div class="col-3">
			<h3 class="text-center">الخزنة</h3>
			<button class="btn btn-primary form-control" style="font-size:30px;height:130px"><?php echo number_format($total); ?></button>
		</div>
		<div class="col-3">
			<h3 class="text-center">البنك</h3>
			<button class="btn btn-success form-control" style="font-size:30px;height:130px"><?php echo number_format($total1); ?></button>
		</div>
		<div class="col-3">
			<h3 class="text-center">مجموع الارصدة</h3>
			<button class="btn btn-warning form-control" style="font-size:30px;height:130px"><?php echo number_format($total+$total1); ?></button>
		</div>
	</div>
	<?php
		/* End Dashboard Page */
		include $tpl . 'footer.php';
		include $tpl . 'footerClose.php';
	}
	else{
		header('Location:../../index.php');
		exit();
	}
	ob_end_flush();
?>