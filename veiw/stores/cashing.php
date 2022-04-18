<?php
	ob_start(); // Output Buffering Start
	session_start();
	// if (isset($_SESSION['user_name'])){
		$pageTitle = 'cashing';
		$getH3     = 'إذن صرف';
		include '../../init.php';
		$stmt = $con->prepare(
			"SELECT bills.* , customers.name AS customer_name FROM bills
			 INNER JOIN customers ON bills.customer_id = customers.id
			 WHERE bills.status = 2 ORDER BY id DESC"
		);
		$stmt->execute();
		$rows = $stmt->fetchAll();
		/* Start bocking Page */
	?>
	<form class="row g-3 needs-validation" id="cashing" novalidate>
		<div class="col-3 mb-4 mt-4">
			<select class="form-control js-example-basic-single" id="change" required>
				<option disabled selected value="">إختار الخدمة</option>
				<?php
					foreach($rows as $row){
						echo "<option value='".$row['id']."'>".$row['code'].' : '.$row['customer_name']."</option>";
					}
				?>
			</select>
			<div class="invalid-feedback">إختار الخدمة من فضلك</div>
		</div>
		<div class="col-1 mb-4 mt-4">
			<button type="submit" class="form-control btn btn-primary">إختيار</button>
		</div>
		<div class="col-5 mb-4 mt-4"></div>
	</form>
	<center class="row" id='center'></center>
	<?php
		/* End bocking Page */
		include $tpl . 'footer.php';
	?>
	<script src="<?php echo $controller ?>stores/cashing.js"></script>
	<?php
		include $tpl . 'footerClose.php';
	// }
	// else{
	// 	header('Location:../../index.php');
	// 	exit();
	// }
	ob_end_flush();
?>