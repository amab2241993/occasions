<?php
	ob_start(); // Output Buffering Start
	session_start();
	if (isset($_SESSION['user_name'])){
		$pageTitle = 'expenses';
		$getH3     = 'المصروفات';
		include '../../init.php';
		$stmt = $con->prepare("SELECT id,name FROM accounts WHERE parent_id = 4 AND id != 20 AND id != 21");
		$stmt->execute();
		$rows = $stmt->fetchAll();
		$stmt = $con->prepare("SELECT id , name FROM accounts WHERE parent_id = 5");
		$stmt->execute();
		$accounts = $stmt->fetchAll();
		/* Start bocking Page */
	?>
	<form class="row g-3 needs-validation" id="expense" novalidate>
		<div class="col-2">
			<label for="mainItem" class="form-label">البند الرئيسي</label>
			<select class="form-control" id="mainItem" required>
				<option selected disabled>إختار نوع المصروف</option>
				<?php 
					foreach($rows as $row){
					?>
					<option value="<?=$row['id']?>"><?=$row['name']?></option>
					<?php
					}
				?>
			</select>
		</div>
		<div class="col-2">
			<label for="subItem" class="form-label">البند الفرعي</label>
			<select class="form-control"  id="subItem" required>
				<option selected disabled></option>
			</select>
		</div>
		<div class="col-2">
			<label for="type" class="form-label">طريقة الدفع</label>
			<select class="form-control"  id="type" required>
				<option selected disabled value="">إختار طريقة الدفع</option>
				<?php
					foreach($accounts as $account){
					?>
					<option value=<?=$account['id']?>><?=$account['name']?></option>
					<?php
					}
				?>
			</select>
			<div class="invalid-feedback">إختار طريقة الدفع من فصلك</div>
		</div>
		<div class="col-2">
			<label for="statement" class="form-label">البيان</label>
			<input type="text" class="form-control" id="statement">
		</div>
		<div class="col-2 label">
			<label for="amount" class="form-label">المبلغ</label>
			<input type="number" class="form-control" id="amount">
		</div>
		<div class="col-md-1">
			<label class="form-label" style="color:#dcdcdc">-</label>
			<button type="submit" class="form-control btn btn-primary">حفظ</button>
		</div>
		<div class="col-md-12" style="color:#dcdcdc">-</div>
	</form>
	<?php
		/* End bocking Page */
		include $tpl . 'footer.php';
	?>
	<script src="<?php echo $controller ?>expenses/expense.js"></script>
	<?php
		include $tpl . 'footerClose.php';
	}
	else{
		header('Location:../../index.php');
		exit();
	}
	ob_end_flush();
?>