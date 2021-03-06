<?php
	ob_start(); // Output Buffering Start
	session_start();
	// if (isset($_SESSION['user_name'])) {
		$pageTitle = 'users';
		$getH3     = "المستخدمين";
		include '../../init.php';
		?><script src="<?php echo $controller ?>settings/users.js"></script><?php
		include $tpl . 'navbar.php';
		$stmt = $con->prepare("SELECT * FROM users ORDER BY id DESC");
		$stmt->execute();
		$users = $stmt->fetchAll();
		/* Start Dashboard Page */
	?>
	<form class="row needs-validation" id="users" novalidate>
		<div class="col-2 mb-3">
			<label for="name">اسم المستخدم</label>
			<input type="text" class="form-control" placeholder="اسم المستخدم" required id="name">
			<div class="invalid-feedback">
				اكتب الاسم من فضلك
			</div>
		</div>
		<div class="col-2 mb-2">
			<label for="pass">كلمة السر</label>
			<input type="password" class="form-control" placeholder="كلمة السر" required  id="pass">
			<div class="invalid-feedback">
				اكتب كلمة السر من فضلك
			</div>
		</div>
		<div class="col-2 mb-3">
			<label for="full">اكتب الاسم بالكامل</label>
			<input type="text" class="form-control" placeholder="اكتب الاسم بالكامل" required  id="full">
			<div class="invalid-feedback">
				أدخل اسم المستخدم كامل من فضلك
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
				<th scope="col-2">اسم المستخدم</th>
				<th scope="col-2">الاسم كامل</th>
				<th scope="col-2">تحكم</th>
			</tr>
		</thead>
		<tbody>
			<?php
				if (! empty($users)){
					$count= 0;
					foreach($users as $user){
					?>
					<tr user="<?=$user['user_name']?>" full="<?=$user['full_name']?>">
						<td scope="row"><?=++$count?></td>
						<td><?= $user['user_name']; ?></td>
						<td><?= $user['full_name']; ?></td>
						<td class="col-2" style="font-size:20px">
							<i class='fa fa-edit edit pl-2' id="<?=$user['id']?>"></i>
							<?php if($_SESSION['user_name'] != $user['user_name']){?>
							<i class='fa fa-remove remove pl-2' id="<?=$user['id']?>"></i>
							<?php } ?>
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
						<input type="hidden" id="userId">
						<input type="hidden" id="thisName">
						<div class="col-5 mt-2"><h3>تعديل البيانات</h3></div>
						<div class="col-5 mt-2"></div>
						<div class="col-1 mt-2">
							<button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="col-1 mt-2"></div>
						<div class="col-10 mt-2">
							<label for="userName">اسم المستخدم</label>
							<input type="text" class="form-control" required id="userName">
							<div class="invalid-feedback">
								اكتب الاسم من فضلك
							</div>
						</div>
						<div class="col-2 mt-2"></div>
						<div class="col-10 mt-2">
							<label for="fullName">اكتب الاسم بالكامل</label>
							<input type="text" class="form-control" required  id="fullName">
							<div class="invalid-feedback">
								أدخل اسم المستخدم كامل من فضلك
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
						<input type="hidden" id="user_id">
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