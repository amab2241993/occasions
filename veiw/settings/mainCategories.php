<?php
	ob_start(); // Output Buffering Start
	session_start();
	if (isset($_SESSION['user_name'])) {
		$pageTitle = 'Dashboard';
		include '../../init.php';
		$stmt = $con->prepare("SELECT id , name FROM stores ORDER BY id");
		$stmt->execute();
		$stores = $stmt->fetchAll();
		$stmt = $con->prepare("SELECT id , name FROM workers");
		$stmt->execute();
		$workers = $stmt->fetchAll();
		$val = null;
		$stmt = $con->prepare(
			"SELECT services.name as category , services.parent_id , store_service.* FROM store_service
			 INNER JOIN services ON store_service.service_id = services.id
			 WHERE services.parent_id <=> NULL ORDER BY id DESC"
		);
		$stmt->execute();
		$services = $stmt->fetchAll();
		/* Start Dashboard Page */
	?>
	<h3>الاصناف الرئيسية</h3>
	<div class="sitting">
		<form class="needs-validation" id="mainCategory" novalidate>
			<div class="form-row">
				<div class="col-md-3 mb-2">
					<label for="name">اسم الصنف</label>
					<input type="text" class="form-control" placeholder="ادخل الصنف" id="name" required />
					<div class="invalid-feedback">
						إختار الاسم من فضلك
					</div>
				</div>
				<div class="col-md-3 mb-2">
					<label for="priceCus">سعر العميل</label>
					<input type="number" class="form-control" placeholder="اكتب سعر العميل" required  id="priceCus">
					<div class="invalid-feedback">
						أدخل سعر العميل من فضلك
					</div>
				</div>
				<div class="col-md-3 mb-2">
					<label for="priceCom">سعر الزميل</label>
					<input type="number" class="form-control" placeholder="اكتب سعر الزميل" required  id="priceCom">
					<div class="invalid-feedback">
						أدخل سعر الزميل من فضلك
					</div>
				</div>
				<div class="col-md-3 mb-2">
					<label for="priceEmp">سعر العامل</label>
					<input type="number" class="form-control" placeholder="اكتب سعر العامل" required  id="priceEmp">
					<div class="invalid-feedback">
						أدخل سعر العامل من فضلك
					</div>
				</div>
				<div class="col-md-3 mb-2">
					<label for="store">المخزن</label>
					<select class="form-control" id="store" required>
						<option selected disabled value="">إختار المخزن</option>
						<?php
							foreach($stores as $store){
							?>
							<option value="<?=$store['id']?>"><?=$store['name']?></option>
							<?php
							}
						?>
					</select>
					<div class="invalid-feedback">
						إختار المخزن من فضلك
					</div>
				</div>
				<div class="col-md-3 mb-2">
					<label for="worker">تصنيف العامل</label>
					<select class="form-control" id="worker" required>
						<option selected disabled value="">إختار تصنيف العامل</option>
						<?php
							foreach($workers as $worker){
							?>
							<option value="<?=$worker['id']?>"><?=$worker['name']?></option>
							<?php
							}
						?>
					</select>
					<div class="invalid-feedback">
						إختار تصنيف العامل من فضلك
					</div>
				</div>
				<div class="col-md-3 mb-2">
					<label for="quantity">الكمية</label>
					<input type="number" class="form-control" value=""placeholder="إختياري" id="quantity">
				</div>
				<div class="col-md-1 mb-2">
					<label style="color:#dcdcdc;">-</label>
					<button class="form-control btn btn-primary" type="submit">اضافة</button>
				</div>
			</div>
		</form>
		<?php
			if(!empty($services)){
			?>
			<table class="table tableStyle1">
				<thead class="tableStyle">
					<tr>
						<th scope="col-1">#</th>
						<th scope="col-2">اسم الصنف</th>
						<th scope="col-2">سعر العميل</th>
						<th scope="col-2">سعر الزميل</th>
						<th scope="col-2">سعر العامل</th>
						<th scope="col-2">الكمية</th>
						<th scope="col-1">التحكم</th>
					</tr>
				</thead>
				<tbody>
					<?php
						$count = 0;
						foreach($services as $service){
						?>
						<tr>
							<th scope="col-1"><?=++$count?></th>
							<th scope="col-2"><?=$service['category']?></th>
							<th scope="col-2"><?=$service['customer_price']?></th>
							<th scope="col-2"><?=$service['companion_price']?></th>
							<th scope="col-2"><?=$service['employee_price']?></th>
							<th scope="col-2"><?=$service['quantity']?></th>
							<th scope="col-1">
								<i class='fa fa-edit edit'></i>
								<i class='fa fa-remove remove'></i>
							</th>
						</tr>
						<?php
						}
					?>
				</tbody>
			</table>
			<?php
			}
		?>

	</div>
	<?php
		/* End Dashboard Page */
		include $tpl . 'footer.php';
	?>
	<script src="<?php echo $controller ?>settings/mainCategories.js"></script>
	<?php
		include $tpl . 'footerClose.php';
	}
	else{
		header('Location:../../index.php');
		exit();
	}
	ob_end_flush();
?>