<?php
	ob_start(); // Output Buffering Start
	session_start();
	// if (isset($_SESSION['user_name'])) {
		$pageTitle = 'sub categories';
		$getH3     = 'الأصناف الفرعية';
		include '../../init.php';
		?><script src="<?php echo $controller ?>settings/subCategories.js"></script><?php
		include $tpl . 'navbar.php';
		$stmt = $con->prepare("SELECT id , parent_id , name FROM services WHERE parent_id <=> NULL");
		$stmt->execute();
		$rows = $stmt->fetchAll();
		/* Start Dashboard Page */
	?>
	<form class="row needs-validation" id="subCategory" novalidate>
		<div class="col-2 mt-2 mb-2">
			<select class="form-control" id="serId" required>
				<option selected disabled value="">نوع الصنف</option>
				<?php
					foreach($rows as $row){
					?>
					<option value="<?=$row['id']?>" parent="<?=$row['parent_id']?>">
						<?=$row['name']?>
					</option>
					<?php
					}
				?>
			</select>
			<div class="invalid-feedback">إختار الصنف من فضلك</div>
		</div>
		<div class="col-2 mt-2 mb-2">
			<input type="text" class="form-control" placeholder="اكتب اسم الصنف" required  id="name">
			<div class="invalid-feedback">أدخل اسم الصنف من فضلك</div>
		</div>
		<div class="col-3 mt-2 mb-2">
			<input type="number" class="form-control" value=""placeholder="الكمية (إختياري)" id="quantity" min=0>
		</div>
		<div class="col-1 mt-2 mb-2">
			<button class="form-control btn btn-primary" type="submit">اضافة</button>
		</div>
	</form>
	<table class="table table-bordered text-center">
		<thead class="tableStyle">
			<tr>
				<th class="col-1">#</th>
				<th class="col-2">اسم الصنف</th>
				<th class="col-2">سعر العميل</th>
				<th class="col-2">سعر الزميل</th>
				<th class="col-2">سعر العامل</th>
				<th class="col-2">الكمية</th>
				<th class="col-1">التحكم</th>
			</tr>
		</thead>
		<tbody>
		</tbody>
	</table>
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
						<div class="col-9 mt-2">
							<label for="nameU">اسم الصنف</label>
							<input type="text"class="form-control" id="nameU" placeholder="إدخل الصنف" required>
							<div class="invalid-feedback">إختار  اسم الصنف من فضلك</div>
						</div>
						<div class="col-3 mt-2"></div>
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