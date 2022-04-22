<?php
	ob_start(); // Output Buffering Start
	session_start();
	if (isset($_SESSION['user_name'])){
		$pageTitle = 'check store';
		$getH3     = 'فحص المحازن';
		include '../../init.php';
		?><script src="<?php echo $controller ?>stores/storeCheck.js"></script><?php
		include $tpl . 'navbar.php';
		$stmt = $con->prepare("SELECT id , name FROM stores ORDER BY id ASC");
		$stmt->execute();
		$rows = $stmt->fetchAll();
		/* Start bocking Page */
	?>
	<form class="row g-3 needs-validation" id = "check" novalidate>
		<div class="col-3 mb-2 mt-4">
			<label for="count" class="form-label">إختار المخزن</label>
			<select class="form-control" id="stores" required>
				<option disabled selected value="">إختار المخزن</option>
				<?php
					foreach($rows as $row){
					?>
					<option value=<?=$row['id']?>><?=$row['name']?></option>
					<?php
					}
				?>
			</select>
			<div class="invalid-feedback">إختار المخزن من فضلك</div>
		</div>
	</form>
	<table id="first" class="table table-striped table-bordered">
		<thead class='tableStyle'>
			<tr>
				<th scope="col-1 mb-2"> الصنف</th>
				<th scope="col-1 mb-2">الكمية</th>
			</tr>
		</thead>
		<tbody>
		</tbody>
	</table>
	<?php
		/* End bocking Page */
		include $tpl . 'footer.php';
	}
	else{
		header('Location:../../index.php');
		exit();
	}
	ob_end_flush();
?>