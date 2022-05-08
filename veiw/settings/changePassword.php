<?php
	ob_start(); // Output Buffering Start
	session_start();
	// if (isset($_SESSION['user_name'])) {
		$pageTitle = 'change password';
		$getH3     = "تغير كلمة المرور";
		include '../../init.php';
		?><script src="<?php echo $controller ?>settings/changePassword.js"></script><?php
		include $tpl . 'navbar.php';
		$stmt = $con->prepare("SELECT * FROM customers WHERE status = 2 ORDER BY id ASC");
		$stmt->execute();
		$customers = $stmt->fetchAll();
		/* Start Dashboard Page */
	?>
	<form class="row needs-validation" id="changePassword" novalidate style="margin-top:100px;margin-bottom:60px">
		<div class="col-3 mb-3">
			<input type="password" class="form-control" placeholder="إدخل كلمة السر القديمة" required id="oldPassword">
			<div class="invalid-feedback">
			إدخل كلمة السر القديمة من فضلك
			</div>
		</div>
		<div class="col-3 mb-3">
			<input type="password" class="form-control" placeholder="إدخل كلمة السر الجديدة" required  id="newPassword1">
			<div class="invalid-feedback">
				إدخل كلمة السر الجديدة من فضلك
			</div>
		</div>
		<div class="col-3 mb-3">
			<input type="password" class="form-control" placeholder="إدخل كلمة السر مرة ثانية" required  id="newPassword2">
			<div class="invalid-feedback">
			إدخل كلمة السر مرة ثانية من فضلك
			</div>
		</div>
		<div class="col-1 mb-1">
			<button class="form-control btn btn-primary" type="submit">تغير</button>
		</div>
	</form>
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