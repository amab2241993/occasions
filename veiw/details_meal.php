<?php
	ob_start(); // Output Buffering Start
	session_start();
	if (isset($_SESSION['user_name'])) {
		$pageTitle = 'Dashboard';
		include 'init.php';
		/* Start Dashboard Page */
		if(isset($_GET['meal']) && isset($_GET['date'])){
			$stmt = $con->prepare(
				"SELECT reservations.* , customers.name as customer_name FROM reservations INNER JOIN customers
				 ON reservations.customer_id =customers.id WHERE reservation_date = ? AND meal_type = ? LIMIT 1"
			);
			$stmt->execute(array($_GET['date'] , $_GET['meal']));
$row = $stmt->fetch();
?>
<h3>التفاصيل الحجز</h3>
<table class="table tableStyle1">
<thead class="tableStyle">
<tr>
<th scope="col">تاريخ الحجز</th>
<th scope="col">وقت الحجز</th>
<th scope="col">اسم العميل</th>
<th scope="col">عدد الافراد</th>
<th scope="col">المبلغ الكلي</th>
<th scope="col">الخصم</th>
<th scope="col">المبلغ النهائى</th>
<th scope="col">التحكم</th>
</tr>
</thead>
<tbody>
<tr>
<td><?= $row['reservation_date']; ?></td>
<td><?= $row['meal_type']; ?></td>
<td><?= $row['customer_name']; ?></td>
<td><?= $row['number_of_people']; ?></td>
<td><?= $row['price']; ?></td>
<td><?= $row['discount']; ?></td>
<td><?= $row['total_price']; ?></td>
<td><button type='button' data-bs-toggle='modal' data-bs-target='<?="#exampleModal".$count?>'>تأكيد</button></td>
</tr>
</tbody>
</table>
<?php
}
/* End Dashboard Page */
include $tpl . 'footer.php';
}
else{
header('Location: index.php');
exit();
}
ob_end_flush();
?>