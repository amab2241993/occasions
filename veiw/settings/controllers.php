<?php
	ob_start(); // Output Buffering Start
	session_start();
	if (isset($_SESSION['user_name'])) {
		$pageTitle = 'controllers';
		$getH3     = 'البنود';
		include '../../init.php';
		?><script src="<?php echo $controller ?>settings/controllers.js"></script><?php
		include $tpl . 'navbar.php';
		$stmt = $con->prepare("SELECT id , name FROM accounts WHERE parent_id = ?");
		$stmt->execute(array(4));
		$rows = $stmt->fetchAll();
		/* Start Dashboard Page */
	?>
	<form class="needs-validation" id="controllers" novalidate method="post">
		<div class="form-row">
			<div class="col-md-4 mb-4">
				<label for="parent">البند</label>
				<select class="form-control" id="parent" required>
					<option selected disabled value="">إختار نوع البند</option>
					<?php
						foreach($rows as $row){
							if($row['id'] != 19){
							?>
							<option value="<?=$row['id']?>"><?=$row['name']?></option>
							<?php
							}
						}
					?>
				</select>
				<div class="invalid-feedback">
					إختار بند من فضلك
				</div>
			</div>
			<div class="col-md-4 mb-4">
				<label for="name">اكتب البند</label>
				<input type="text" class="form-control" placeholder="اكتب البند بالكامل" required  id="name">
				<div class="invalid-feedback">
					أدخل اسم البند من فضلك
				</div>
			</div>
			<div class="col-md-1 mb-4">
				<label style="color:white;">-</label>
				<button class="form-control btn btn-primary" type="submit">اضافة</button>
			</div>
		</div>
	</form>
	<table class="table tableStyle1">
		<thead class="tableStyle">
			<tr>
				<th scope="col-1">الرقم المتسلسل</th>
				<th scope="col-3">اسم المستخدم</th>
				<th scope="col-3">التحكم</th>
			</tr>
		</thead>
		<tbody>
		</tbody>
	</table>
	<?php
		/* End Dashboard Page */
		include $tpl . 'footer.php';
	}
	else{
		header('Location:../../index.php');
		exit();
	}
	ob_end_flush();
?>