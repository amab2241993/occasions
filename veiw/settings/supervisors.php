<?php
	ob_start(); // Output Buffering Start
	session_start();
	// if (isset($_SESSION['user_name'])){
		$pageTitle = 'Supervisors and administrative';
		$getH3     = 'المشرفين والادارية';
		include '../../init.php';
		?>
		<script src="<?php echo $controller ?>settings/supervisors.js"></script>
		<?php
		include $tpl . 'navbar.php';
		$stmt = $con->prepare("SELECT * FROM management");
		$stmt->execute();
		$rows = $stmt->fetchAll();
		/* Start bocking Page */
	?>
	<table class='table table-bordered'>
		<thead class='tableStyle'>
			<tr>
				<th scope="col-1 mb-2">الأسم</th>
				<th scope="col-1 mb-2">القيمة</th>
				<th scope="col-1 mb-2">تعديل</th>
			</tr>
		</thead>
		<tbody>
			<?php
				foreach ($rows as $row){
				?>
				<tr>
					<td scope="col-1 mb-2"><?=$row['name']?></td>
					<td scope="col-1 mb-2">
						<?php
							if($row['percent'] == 1){
							?>
							<input type="text" class="form-control cost" name="data[]" id="<?=$row['id']?>" value="<?="%".$row['cost']?>">
							<?php
							}
							else{
							?>
							<input type="text" class="form-control" name="data[]" id="<?=$row['id']?>" value="<?=$row['cost']?>">
							<?php
							}
						?>
					</td>
					<td scope="col-1 mb-2"><button class="btn btn-primary click">تعديل</button></td>
				</tr>
				<?php
				}
			?>
		</tbody>
	</table>
	<?php
		/* End bocking Page */
		include $tpl . 'footer.php';
	// }
	// else{
	// 	header('Location:../../index.php');
	// 	exit();
	// }
	ob_end_flush();
?>