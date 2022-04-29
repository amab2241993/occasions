<?php
	ob_start(); // Output Buffering Start
	session_start();
	// if (isset($_SESSION['user_name'])) {
		$pageTitle = 'management loans';
		$getH3     = "إداراة السلفيات";
		include '../../init.php';
		?><script src="<?php echo $controller ?>settings/managementLoan.js"></script><?php
		include $tpl . 'navbar.php';
		$stmt = $con->prepare("SELECT * FROM loans ORDER BY id DESC");
		$stmt->execute();
		$loans = $stmt->fetchAll();
		/* Start Dashboard Page */
	?>
	<form class="row needs-validation" id="loans" novalidate>
		<div class="col-3 mb-3">
			<label for="name">اسم المستلف</label>
			<input type="text" class="form-control" placeholder="اسم المستلف" required id="name">
			<div class="invalid-feedback">
				اكتب الاسم من فضلك
			</div>
		</div>
		<div class="col-3 mb-3">
			<label for="phone">رقم الهاتف</label>
			<input type="text" class="form-control" placeholder="اكتب رقم الهاتف" required  id="phone">
			<div class="invalid-feedback">
				أدخل رقم الهاتف من فضلك
			</div>
		</div>
		<div class="col-3 mb-3">
			<label for="address">العنوان</label>
			<input type="text" class="form-control" placeholder="إدخل العنوان" required  id="address">
			<div class="invalid-feedback">
				أدخل العنوان من فضلك
			</div>
		</div>
		<div class="col-1 mb-1">
			<label style="color:#dcdcdc;">-</label>
			<button class="form-control btn btn-primary" type="submit">اضافة</button>
		</div>
	</form>
	<table class="table">
		<thead class="tableStyle">
			<tr>
				<th scope="col-1">#</th>
				<th scope="col-2">الإسم</th>
				<th scope="col-2">الهاتف</th>
				<th scope="col-2">العنوان</th>
				<th scope="col-2">تحكم</th>
			</tr>
		</thead>
		<tbody>
			<?php
				if (! empty($loans)){
					$count= 0;
					foreach($loans as $loan){
					?>
					<tr loan="<?=$loan['name']?>" phone="<?=$loan['phone']?>">
						<td scope="row"><?=++$count?></td>
						<td><?= $loan['name']; ?></td>
						<td><?= $loan['phone']; ?></td>
						<td><?= $loan['address']; ?></td>
						<td class="col-2" style="font-size:20px" address="<?=$loan['address']?>">
							<i class='fa fa-edit edit pl-2'id="<?=$loan['id']?>"></i>
							<i class='fa fa-remove remove pl-2' id="<?=$loan['id']?>"></i>
						</td>
					</tr>
					<?php
					}
				}
			?>
		</tbody>
	</table>
	<!-- ******************** model **************************** -->
	<div class="modal fade" id='update' tabindex="-1" role="dialog" aria-labelledby="updateLabel" aria-hidden=true>
		<div class="modal-dialog"  role="document">
			<div class="modal-content">
				<div class="modal-body row">
					<form class="row g-3 needs-validation" id='updateForm' novalidate>
						<input type="hidden" id="loanId">
						<div class="col-5 mt-2"><h3>تعديل البيانات</h3></div>
						<div class="col-5 mt-2"></div>
						<div class="col-1 mt-2">
							<button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="col-1 mt-2"></div>
						<div class="col-10 mt-2">
							<label for="loanN">اسم الموظف</label>
							<input type="text" class="form-control" required id="loanN">
							<div class="invalid-feedback">
								اكتب الاسم من فضلك
							</div>
						</div>
						<div class="col-2 mt-2"></div>
						<div class="col-5 mt-2">
							<label for="loanP">اكتب رقم الهاتف</label>
							<input type="text" class="form-control" required  id="loanP">
							<div class="invalid-feedback">
								أدخل رقم الهاتف من فضلك
							</div>
						</div>
						<div class="col-5 mt-2">
							<label for="loanA">اكتب العنوان </label>
							<input type="text" class="form-control" required  id="loanA">
							<div class="invalid-feedback">
								أدخل العنوان من فضلك
							</div>
						</div>
						<div class="col-2 mt-2"></div>
						<div class="col-4 mt-2 mb-2">
							<button type="submit" class="btn btn-primary">تعديل</button>
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
	<!-- ******************** model **************************** -->
	<div class="modal fade" id='passwordInter' tabindex="-1" role="dialog" aria-labelledby="passwordInterLabel" aria-hidden=true>
		<div class="modal-dialog"  role="document">
			<div class="modal-content">
				<div class="modal-body row">
					<form class="row g-3 needs-validation" id='passowrdForm' novalidate>
						<input type="hidden" id="loan_id">
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