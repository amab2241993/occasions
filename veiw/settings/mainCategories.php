<?php
	ob_start(); // Output Buffering Start
	session_start();
	// if (isset($_SESSION['user_name'])) {
		$pageTitle = 'main categories';
		$getH3     = 'الاصناف الرئيسية';
		include '../../init.php';
		?><script src="<?php echo $controller ?>settings/mainCategories.js"></script><?php
		include $tpl . 'navbar.php';
		$stmt = $con->prepare("SELECT id , name FROM stores ORDER BY id");
		$stmt->execute();
		$stores = $stmt->fetchAll();
		$stmt = $con->prepare("SELECT id , name FROM workers");
		$stmt->execute();
		$workers = $stmt->fetchAll();
		$val = null;
		$stmt = $con->prepare(
			"SELECT s1.name AS category , s1.parent_id , s1.id AS serviceId ,
			 count(s2.parent_id) AS counter , store_service.* FROM services s1
			 INNER JOIN store_service ON store_service.service_id = s1.id
			 LEFT OUTER JOIN services s2 ON s1.id = s2.parent_id
			 WHERE s1.parent_id <=> NULL AND store_service.store_id != 4
			 GROUP BY s1.id ORDER BY s1.id DESC"
		);
		$stmt->execute();
		$services = $stmt->fetchAll();
		/* Start Dashboard Page */
	?>
	<form class="row needs-validation" id="mainCategory" novalidate>
		<div class="col-3 mb-2">
			<input type="text" class="form-control" placeholder="ادخل الصنف" id="name" required />
			<div class="invalid-feedback">
				إختار الاسم من فضلك
			</div>
		</div>
		<div class="col-3 mb-2">
			<input type="number" class="form-control" placeholder="اكتب سعر العميل" required id="priceCus" min=1 />
			<div class="invalid-feedback">
				أدخل سعر العميل من فضلك
			</div>
		</div>
		<div class="col-3 mb-2">
			<input type="number" class="form-control" placeholder="اكتب سعر الزميل" required  id="priceCom" min=1 />
			<div class="invalid-feedback">
				أدخل سعر الزميل من فضلك
			</div>
		</div>
		<div class="col-3 mb-2">
			<input type="number" class="form-control" placeholder="اكتب سعر العامل" required  id="priceEmp" min=1 />
			<div class="invalid-feedback">
				أدخل سعر العامل من فضلك
			</div>
		</div>
		<div class="col-3 mb-2">
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
		<div class="col-3 mb-2">
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
		<div class="col-3 mb-2">
			<input type="number" class="form-control" value=""placeholder="الكمية (إختياري)" id="quantity" />
		</div>
		<div class="col-1 mb-2">
			<button class="form-control btn btn-primary" type="submit">اضافة</button>
		</div>
	</form>
	<?php
		if(!empty($services)){
		?>
		<table id="first" class="table table-bordered text-center">
			<thead class="tableStyle">
				<tr>
					<th class="col-2">إسم الصنف</th>
					<th class="col-2">عدد الفروع</th>
					<th class="col-2">سعر العميل</th>
					<th class="col-2">سعر الزميل</th>
					<th class="col-2">سعر العامل</th>
					<th class="col-1">الكمية</th>
					<th class="col-1">التحكم</th>
				</tr>
			</thead>
			<tbody>
				<?php
					foreach($services as $service){
					?>
					<tr name="<?=$service['category']?>" priceCu=<?=$service['customer_price']?>>
						<td class="col-2"><?=$service['category']?></td>
						<td class="col-2"><?=$service['counter']?></td>
						<td class="col-2"><?=$service['customer_price']?></td>
						<td class="col-2"><?=$service['companion_price']?></td>
						<td class="col-2"><?=$service['employee_price']?></td>
						<td class="col-1"><?=$service['quantity']?></td>
						<td class="col-1" style="font-size:20px" storeServiceId=<?=$service['id']?> priceCo=<?=$service['companion_price']?>>
							<i class='fa fa-edit edit pl-2' serviceId=<?=$service['serviceId']?> priceEm=<?=$service['employee_price']?>></i>
							<i class='fa fa-remove remove pl-2' serviceId=<?=$service['serviceId']?>></i>
						</td>
					</tr>
					<?php
					}
				?>
			</tbody>
		</table>
		<?php
		}
	?>
	<!-- ******************** model **************************** -->
	<div class="modal fade" id='update' tabindex="-1" role="dialog" aria-labelledby="updateLabel" aria-hidden=true>
		<div class="modal-dialog"  role="document">
			<div class="modal-content">
				<div class="modal-body row">
					<form class="row g-3 needs-validation" id='updateForm' novalidate>
						<input type="hidden" id="serviceId">
						<input type="hidden" id="storeServiceId">
						<div class="col-5 mt-2"><h3>تعديل البيانات</h3></div>
						<div class="col-5 mt-2"></div>
						<div class="col-1 mt-2">
							<button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="col-6 mt-2">
							<label for="nameU">اسم الصنف</label>
							<input type="text"class="form-control" id="nameU" placeholder="إدخل الصنف" required>
							<div class="invalid-feedback">إختار  اسم الصنف من فضلك</div>
						</div>
						<div class="col-6 mt-2">
							<label for="priceCusU">سعرالعميل</label>
							<input type="number" class="form-control" id="priceCusU" placeholder="إدخل سعر العميل" required  min=1 />
							<div class="invalid-feedback">إختار  سعرالعميل من فضلك</div>
						</div>
						<div class="col-6 mt-2">
							<label for="priceComU">سعر الزميل</label>
							<input type="number" class="form-control" id="priceComU" placeholder="إدخل سعر الزميل" required  min=1 />
							<div class="invalid-feedback">إختار سعر الزميل من فضلك</div>
						</div>
						<div class="col-6 mt-2">
							<label for="priceEmpU">سعر العامل</label>
							<input type="number" class="form-control" id="priceEmpU" placeholder="إدخل سعر العامل" required  min=1 />
							<div class="invalid-feedback">إختار سعر العامل من فضلك</div>
						</div>
						<div class="col-4 mt-2 mb-2">
							<button type="submit" class="btn btn-primary">تعديل</button>
						</div>
						<div class="col-4 mt-2 mb-2"></div>
						<div class="col-4 mt-2 mb-2">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">قفل الصفحة</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<!-- ******************** model **************************** -->
	<div class="modal fade" id='passwordInter' tabindex="-1" role="dialog" aria-labelledby="passwordInterLabel" aria-hidden=true>
		<div class="modal-dialog"  role="document">
			<div class="modal-content">
				<div class="modal-body row">
					<form class="row g-3 needs-validation" id='passowrdForm' novalidate>
						<input type="hidden" id="service_id" value="">
						<div class="col-5 mt-2"><h3>إدخل كلمة السر</h3></div>
						<div class="col-5 mt-2"></div>
						<div class="col-1 mt-2">
							<button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="col-10 mt-2">
							<label for='password' class="form-label">كلمة المرور</label>
							<input type="password" class="form-control" id="password" required>
							<div class="invalid-feedback">كلمة المرور غير صحيحة</div>
						</div>
						<div class="col-2 mt-2"></div>
						<div class="col-4 mt-2 mb-2">
							<button type="submit" class="btn btn-primary">إرسال</button>
						</div>
						<div class="col-3 mt-2 mb-2"></div>
						<div class="col-4 mt-2 mb-2">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">قفل الصفحة</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<?php
		/* End Dashboard Page */
		include $tpl . 'footer.php';
	// }
	// else{
		// 	header('Location:../../index.php');
		// 	exit();
	// }
	ob_end_flush();
?>