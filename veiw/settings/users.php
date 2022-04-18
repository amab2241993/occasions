<?php
	ob_start(); // Output Buffering Start
	session_start();
	$index = 1;
	$update[]='';
	if (isset($_SESSION['user_name'])) {
		$pageTitle = 'Dashboard';
		include '../../init.php';
		$stmt = $con->prepare("SELECT * FROM users ORDER BY id DESC");
		$stmt->execute();
		$rows = $stmt->fetchAll();
		/* Start Dashboard Page */
	?>
	<h3>المستخدمين</h3>
	<form class="needs-validation users" novalidate style="padding-right:10px;padding-left:10px;">
		<div class="form-row">
			<div class="col-md-2 mb-3">
				<label for="name" style="padding-right:10px;">اسم المستخدم</label>
				<input type="text" class="form-control" placeholder="اسم المستخدم" required id="name">
				<div class="invalid-feedback">
					اكتب الاسم من فضلك
				</div>
			</div>
			<div class="col-md-2 mb-2">
				<label for="pass" style="padding-right:10px;">كلمة السر</label>
				<input type="password" class="form-control" placeholder="كلمة السر" required  id="pass">
				<div class="invalid-feedback">
					اكتب كلمة السر من فضلك
				</div>
			</div>
			<div class="col-md-2 mb-3">
				<label for="full" style="padding-right:10px;">اكتب الاسم بالكامل</label>
				<input type="text" class="form-control" placeholder="اكتب الاسم بالكامل" required  id="full">
				<div class="invalid-feedback">
					أدخل اسم المستخدم كامل من فضلك
				</div>
			</div>
			<div class="col-md-1 mb-1">
				<label style="color:white;">-</label>
				<button class="form-control btn btn-primary" type="submit">اضافة</button>
			</div>
		</div>
	</form>
	<table class="table tableStyle1">
		<thead class="tableStyle">
			<tr>
				<th scope="col-md-1">#</th>
				<th scope="col">اسم المستخدم</th>
				<th scope="col">الاسم كامل</th>
				<th scope="col">تعديل</th>
			</tr>
		</thead>
		<tbody>
			<?php
				if (! empty($rows)){
					$count= 0;
					foreach($rows as $row){
					?>
					<tr>
						<th scope="row"><?=++$count?></th>
						<td><?= $row['user_name']; ?></td>
						<td><?= $row['full_name']; ?></td>
						<td><button type='button' data-toggle='modal' data-target='<?="#exampleModal".$count?>'>تعديل</button></td>
					</tr>
					<?php
					}
				}
			?>
		</tbody>
	</table>
	<?php
		foreach($rows as $row){
			$stmt = $con->prepare("SELECT * FROM users WHERE id=? LIMIT 1");
			$stmt->execute(array($row['id']));
			$update[$index] = $stmt->fetch();
		?>
		<div class="modal fade" id='<?="exampleModal".$index?>' role="dialog" tabindex="-1" aria-labelledby='<?="exampleModal"."$index"."Label"?>' aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content" style="padding-right:10px;padding-left:10px;">
					<form class="row g-3 needs-validation update" novalidate>
						<input type="hidden" id="<?='user'.$update[$index]['id'];?>" value="<?=$update[$index]['id']?>">
						<div class="col-md-12" style="color:white;">-</div>
						<div class="col-md-4"><h4>تعديل البيانات</h4></div>
						<div class="col-md-6"></div>
						<div class="col-md-1">
							<button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="col-md-12" style="color:white;">-</div>
						<div class="col-md-12">
							<label for="<?='name'.$update[$index]['id']?>">اسم المستخدم</label>
							<input type="text" class="form-control" value="<?=$update[$index]['user_name']?>" required id="<?='name'.$update[$index]['id'];?>">
							<div class="invalid-feedback">
								اكتب الاسم من فضلك
							</div>
						</div>
						<div class="col-md-12" style="color:white;">-</div>
						<div class="col-md-12">
							<label for="<?='full'.$update[$index]['id'];?>">اكتب الاسم بالكامل</label>
							<input type="text" class="form-control" value="<?=$update[$index]['full_name']?>" required  id="<?='full'.$update[$index]['id']?>">
							<div class="invalid-feedback">
								أدخل اسم المستخدم كامل من فضلك
							</div>
						</div>
						<div class="col-md-12" style="color:white;">-</div>
						<div class="col-md-4">
							<button class="form-control btn btn-secondary" data-dismiss="modal">قفل الصفحة</button>
						</div>
						<div class="col-md-6"></div>
						<div class="col-md-1">
							<button type="submit" id="click[]" value = "<?=$update[$index]['id']?>" class="btn btn-primary">حفظ</button>
						</div>
						<div class="col-md-12" style="color:white;">-</div>
					</form>
				</div>
			</div>
		</div>
		<?php
			$index++;
		}
		/* End Dashboard Page */
		include $tpl . 'footer.php';
	?>
	<script src="<?php echo $controller ?>settings/users.js"></script>
	<?php
		include $tpl . 'footerClose.php';
	}
	else{
		header('Location:../../index.php');
		exit();
	}
	ob_end_flush();
?>