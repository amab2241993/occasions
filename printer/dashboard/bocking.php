<!DOCTYPE html>
<html lang="en">
    <?php
        include '../../connect.php';
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $stmt = 0;
            $billId = $_POST["billId"];
            $status = $_POST["status"];
            if($status == 1){
                $stmt = $con->prepare(
                    "SELECT bills.* , customers.name , customers.phone , customers.address FROM bills
                     INNER JOIN customers ON bills.customer_id = customers.id WHERE bills.id = ?
                     AND bills.status = ? LIMIT 1"
                );
            }
            if($status == 2){
                $stmt = $con->prepare(
                    "SELECT bills.* , customers.name , customers.phone , customers.address ,
                     move.price as amount FROM bills
                     INNER JOIN customers ON bills.customer_id = customers.id
                     INNER JOIN main ON bills.main_id = main.id
                     INNER JOIN move ON main.id = move.main_id
                     WHERE bills.id = ? AND bills.status = ? LIMIT 1"
                );
            }
            if($status == 3){
                $stmt = $con->prepare(
                    "SELECT bills.* , customers.name , customers.phone , customers.address ,
                     move_fake.price as amount FROM bills
                     INNER JOIN customers ON bills.customer_id = customers.id
                     INNER JOIN main ON bills.main_id = main.id
                     INNER JOIN move_fake ON main.id = move_fake.main_id
                     WHERE bills.id = ? AND bills.status = ? LIMIT 1"
                );
            }
            $stmt->execute(array($billId  , $status));
            $row = $stmt->fetch();
            $jsons = json_decode($row["details"]);
        ?>
        <head>
            <meta charset="UTF-8" />
            <meta name="viewport" content="width=device-width, initial-scale=1"/>
            <link rel="stylesheet" href="../../layout/css/bootstrap.min.css" />
            <script src="../../layout/js/jquery-3.5.1.min.js"></script>
            <script src="../../layout/js/bootstrap.min.js"></script>
            <link rel="stylesheet" href="../../layout/css/printer.css" />
        </head>
        <body>
            <div class='row'>
                <div class="col-3">
                    <img src="../../layout/img/was.jpg" alt="what" height="200">
                </div>
                <div class="col-6">
                    <h1>الوسامة لخدمات المناسبات</h1>
                    <h2>Alwasama For Event Services</h2>
                </div>
                <div class="col-3">
                    <img src="../../layout/img/was.jpg" alt="what" height="200">
                </div>
                <div class="col-3"></div>
                <div class="col-6">
                    <center>
                        <?php
                            if($row['status'] == 1){
                                echo "<h3 class='form-label'>فاتورة مبدئية</h3>";
                            }
                            if($row['status'] == 2){
                                echo "<h3 class='form-label'>فاتورة نهائي</h3>";
                            }
                            if($row['status'] == 3){
                                echo "<h3 class='form-label'>فاتورة مؤجلة</h3>";
                            }
                        ?>
                        <h4 class="form-label">تاريخ المناسبة:<span><?=$row["bill_date"]?></span></h4>
                    </center>
                </div>
                <div class="col-3"></div>
            </div>
            <div class="date_time">
                <div>
                    <?php
                        echo $row["bill_date"];
                    ?>
                </div>
                <div class=" bill_number">
                    <?php
                        echo "فاتورة رقم".$row["status"];
                    ?>
                </div>
                <div class=" time">
                    <?php
                        echo date("h:i:sa");
                    ?>
                </div>
            </div>
            <div class="customor_info">
                <div><?php echo $row["name"] ?></div>
                <div class="phone"><?php echo $row["phone"] ?></div>
                <div><?php echo $row["address"] ?></div>
            </div>
            <table>
                <thead>
                    <tr class="text-center" style="background:gray">
                        <td>#</td><td>الصنف</td><td>الكمية</td><td>سعر الوحدة</td><td>المجموع</td>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        foreach($jsons as $json){
                        ?>
                        <tr class="text-center">
                            <td><?=$json->id?></td>
                            <td><?=$json->serviceName?></td>
                            <td><?=$json->quantity?></td>
                            <td><?=$json->priceUnit?></td>
                            <td><?=$json->totalUnits?></td>
                        </tr>
                        <?php
                        }
                    ?>
                </tbody>
            </table>
            <br>
            <table>
                <tbody>
                    <?php
                        $dis = intval((intval($row['discount']) * 100) / intval($row['price']));
                        if($row['status'] == 1){
                        ?>
                        <tr>
                            <td><?="اجمالي العفش:".$row['baggage']?></td>
                            <td><?="الخصم: ".$dis."%(".$row['discount'].")"?></td>
                            <td><?="المبلغ :".(intval($row['baggage']) - intval($row['discount']))?></td>
                        </tr>
                        <tr>
                            <td><?="الترحيل:".$row['relay']?></td>
                            <td><?="العمال:".$row['employee_price']?></td>
                            <td><?="الاجمالي:".$row['price']?></td>
                        </tr>
                        <?php
                        }
                        else{
                        ?>
                        <tr>
                            <td><?="اجمالي العفش:".$row['baggage']?></td>
                            <td colspan="2"><?="الخصم: ".$dis."%(".$row['discount'].")"?></td>
                            <td colspan="2"><?="المبلغ :".(intval($row['baggage']) - intval($row['discount']))?></td>
                        </tr>
                        <tr>
                            <td><?="الترحيل:".$row['relay']?></td>
                            <td><?="العمال:".$row['employee_price']?></td>
                            <td><?="الاجمالي:".$row['price']?></td>
                            <td><?="المدفوع: ".$row['amount']?></td>
                            <td><?="المتبقى :".(intval($row['price']) - intval($row['amount']))?></td>
                        </tr>
                        <?php
                        }
                    ?>
                </tbody>
            </table>
            <style>
                table{
                    border-collapse: collapse;
                    width:100%
                }
                td{
                    border:1px solid #b1a4a4;
                    padding: 5px;
                }
                .phone_address{
                    border:1px solid;
                    width:50%;
                    text-align:center;
                    border-radius:10px;
                    margin-top:20px;
                    padding-bottom:30px;
                }
                .user{
                    width:50%;
                    text-align:center;
                    border-radius:10px;
                }

                .date_time{
                    padding:5px;
                    font-size:18px;
                    display:flex;
                }
                .customor_info{
                    border:1px solid #b1a4a4;
                    border-radius:5px;
                    padding:5px;
                    font-size:18px;
                    display:flex
                }
                .date_time .bill_number{
                    text-align:center;
                    font-size:20px;
                    flex:1
                }
                .customor_info .phone{
                    text-align:center;
                    font-size:20px;
                    flex:1
                }
            </style>
        </body>
        <?php
            include "../../printer/footer.inc.php";
        }
    ?>
</html>