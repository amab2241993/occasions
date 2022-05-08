<?php
	ob_start(); // Output Buffering Start
	session_start();
	// if (isset($_SESSION['user_name'])){
		$pageTitle = 'cashing';
		$getH3     = 'إذن صرف';
		include '../../init.php';
		?><script src="<?php echo $controller ?>stores/cashing.js"></script><?php
		include $tpl . 'navbar.php';
		$stmt = $con->prepare(
			"SELECT bills.* , customers.name AS customer_name FROM bills
			 INNER JOIN customers ON bills.customer_id = customers.id
			 WHERE bills.status = 2 ORDER BY id DESC"
		);
		$stmt->execute();
		$rows = $stmt->fetchAll();

		$stmt = $con->prepare(
			"SELECT * FROM customers WHERE status = 2 ORDER BY id DESC"
		);
		$stmt->execute();
		$customers = $stmt->fetchAll();
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
	<form id='ssss'>
		<div id="all"></div>
		<div><center id="save"></center></div>
	</form>
	<form id="lastForm"></form>
	<!-- ******************** model **************************** -->
	<div class="modal fade" id='out' tabindex="-1" role="dialog" aria-labelledby="outLabel" aria-hidden=true>
		<div class="modal-dialog"  role="document">
			<div class="modal-content">
				<div class="modal-body row">
					<form class="row g-3 needs-validation" id='saveForm' novalidate>
						<input type="hidden" id="ids">
						<input type="hidden" id="qus">
						<div class="col-5 mt-2"><h3>إستلاف</h3></div>
						<div class="col-5 mt-2"></div>
						<div class="col-1 mt-2">
							<button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="col-5 mb-2 mt-4">
							<label for="name" class="form-label">اسم الزميل</label>
							<select class="form-control" id="name" required>
								<option disabled selected value="">إختار الزميل</option>
								<?php
									foreach($customers as $customer){
									?>
									<option value=<?=$customer['id']?>><?=$customer['name']?></option>
									<?php
									}
								?>
							</select>
							<div class="invalid-feedback">إختار الزميل من فضلك</div>
						</div>
						<div class="col-5 mb-2 mt-4">
							<label for="piece" class="form-label">عدد القطع</label>
							<input type="number" class="form-control" value="" id="piece" required min=1>
							<div class="invalid-feedback invalids"> </div>
						</div>
						<div class="col-1 mb-2 mt-4">
							<label style="color:white;"class="form-label">-</label>
							<button type="submit" class="form-control btn btn-primary">+</button>
						</div>
					</form>
					<table class='table' style="width:96%;">
						<thead class='tableStyle'>
							<tr>
								<th scope="col-1 mb-2">المكتب</th>
								<th scope="col-1 mb-2">الكمية</th>
								<th scope="col-1 mb-2">خذف</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
					<form id="dataTable">
						<div class="col-4 mt-2 mb-2" id="lastSave">
							<button type="submit" class="btn btn-primary">حفظ</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<?php
		/* End bocking Page */
		include $tpl . 'footer.php';
	// }
	// else{
	// 	header('Location:../../index.php');
	// 	exit();
	// }
	ob_end_flush();
?>