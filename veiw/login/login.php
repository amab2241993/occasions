<?php
	session_start();
	$noNavbar = '';
	$pageTitle = 'Login';
	$_SESSION['user_name'] = "ali";
	if (isset($_SESSION['user_name'])) {
		header('Location:../dashboard/dashboard.php'); // Redirect To Dashboard Page
	}
	include '../../init.php';
?>
<form class="login">
	<h4 class="text-center">تسجيــل دخول</h4>
	<input class="form-control" type="text" id="user" placeholder="ادخل اسم المستخدم" autocomplete="off" required/>
	<input class="form-control" type="password" id="pass" placeholder="ادخل كلمة السر" required autocomplete="new-password" />
	<button type="submit" class="btn btn-primary" id="add" name="add">تسجيل دخول</button>
</form>
<?php include $tpl . 'footer.php';?>
<script src="<?php echo $controller ?>login.js"></script>
<?php include $tpl . 'footerClose.php';?>