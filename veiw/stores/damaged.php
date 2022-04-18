<?php
	ob_start(); // Output Buffering Start
	session_start();
	if (isset($_SESSION['user_name'])){
		$pageTitle = 'damaged';
		$getH3     = 'التحويل الى المخزن التألف';
		include '../../init.php';
		$stmt = $con->prepare("SELECT id , name FROM stores WHERE id != 4");
		$stmt->execute();
		$rows = $stmt->fetchAll();
		/* Start bocking Page */
	?>
	<form class="row g-3 needs-validation" id = "damaged" novalidate>
		<div class="col-3 mb-2 mt-4">
			<label for="count" class="form-label">المخزن المرحل منه</label>
			<select class="form-control" id="stores" required>
				<option disabled selected value="">إختار المخزن</option>
				<?php
					foreach($rows as $row){
					?>
					<option value=<?=$row['id']?>><?=$row['name']?></option>
					<?php
					}
				?>
			</select>
			<div class="invalid-feedback">إختار المخزن من فضلك</div>
		</div>
		<div class="col-2 mb-2 mt-4">
			<label for="count" class="form-label">المخزن المرحل اليه</label>
			<input type="text" class="form-control" disabled id="storeDamaged" value="المخزن التألف"required>
		</div>
		<div class="col-2 mb-2 mt-4">
			<label for="count" class="form-label">الصنف التألف</label>
			<select class="form-control" disabled id="item" required></select>
			<div class="invalid-feedback">إختار النوع من فضلك</div>
		</div>
		<div class="col-3 mb-2 mt-4">
			<label for="quantity" class="form-label">الكمية</label>
			<input type="number" disabled class="form-control" value="" id="quantity" required min=1>
			<div class="invalid-feedback">إدخل الكمية من فضلك</div>
		</div>
		<div class="col-1 mb-2 mt-4">
			<label style="color:#dcdcdc;"class="form-label">-</label>
			<button type="submit" class="form-control btn btn-primary">ترحيل</button>
		</div>
	</form>
	
	<table class='table' style="width:96%;">
		<thead class='tableStyle'>
			<tr>
				<th scope="col-1 mb-2"> الصنف</th>
				<th scope="col-1 mb-2">الكميةالمحولة</th>
				<th scope="col-1 mb-2">الكمية الكلية</th>
				<th scope="col-1 mb-2">خذف</th>
			</tr>
		</thead>
		<tbody>
		</tbody>
	</table>
	<form class="row" id="dataTable">
		<div id="save" class="col-1 mb-2 mt-4">
			<label style="color:#dcdcdc;"class="form-label">-</label>
			<button type="submit" class="form-control btn btn-primary">حفظ</button>
		</div>
	</form>
	<?php
		/* End bocking Page */
		include $tpl . 'footer.php';
	?>
	<script src="<?php echo $controller ?>stores/damaged.js"></script>
	<?php
		include $tpl . 'footerClose.php';
	}
	else{
		header('Location:../../index.php');
		exit();
	}
	ob_end_flush();
?>