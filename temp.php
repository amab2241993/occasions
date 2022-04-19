
<table>
<thead>
<tr class="text-center" style="background:gray">
<td>#</td><td>الصنف</td><td>الكمية</td><td>سعر الوحدة</td><td>المجموع</td>
</tr>
</thead>
<tbody>
<?php
$i=1;
$select="select products.*,service_name,price from products inner join services on service_id=product_id 
where bill_id='$bill_id'";
$result= $connection->query($select);
if ($result->num_rows > 0) {
while($rows = $result->fetch_assoc()) {
$total=$rows["quantity"]*$rows["price"];
echo "<tr id=".$rows["id"]." class='text-center'><td>".$i."</td><td>".$rows["service_name"]."</td><td>".$rows["quantity"]."</td><td>".$rows["price"]."</td>
<td class='total_php'>".$total."</td></tr>";
$i++; 
// $customor_id=$rows["max(customor_id)"];
}
}else {
echo "0 results";
}
?>
</tbody>
</table>
<br>
<table>
<thead>

</thead>
<tbody>
<?php
$select="select * from bills where bill_id='$bill_id'";
$result= $connection->query($select);
if ($result->num_rows > 0) {
while($rows = $result->fetch_assoc()) {?>
<tr class=""><td colspan="">اجمالي العفش: <?php echo $rows["total_afash"];?></td>
<td colspan="">الخصم: <?php echo $rows["discount"]."% (".$rows["total_afash"]*$rows["discount"]*.01.")" ;?></td>
<td colspan="" class=""> المبلغ : <?php echo $rows["total_afash"]-($rows["total_afash"]*$rows["discount"]*.01);?></td>
</tr>
<tr class=""><td colspan=""> الترحيل: <?php echo $rows["relay"] ?></td>
<td colspan="" class=""> العمال: <?php echo $rows["empolyee_price"];?></td>
<td colspan="" class=""> الاجمالي: <?php echo $rows["mota"];?></td>
</tr>



<?php
}
}
?>
</tbody>
</table>