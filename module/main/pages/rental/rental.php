<?php
include_once("./services/rental.service.php");
$rentalService = new RentalService(true);
$getRental = $rentalService->get();
?>
<div class="dashboard-content">
    <div class="row">
        <div class="col-12">
            <h1>รายการเช่ารถ</h1>
        </div>
    </div>
    <div class="row">
        <?php
        for ($i = 0; $i < count($getRental); $i++) {
            $rental = $getRental[$i];
        ?>
            <div class="col-4 mb-2">
                <div class="card">
                    <div class="card-header">
                        <?php echo $rental['brand'] . " " . $rental['model']; ?>
                    </div>
                    <div class="card-body">
                        <blockquote class="blockquote mb-0">
                            <p class="text-center mb-4"><img src="./assets/images/mockup/yaris.jpg"></p>
                            <footer class="blockquote-footer">ทะเบียน : <?php echo $rental['plate']; ?></footer>
                            <footer class="blockquote-footer">ชื่อผู้เช่า : <?php echo $rental['fullName']; ?></footer>
                            <footer class="blockquote-footer">ระยะเวลา : <?php echo date("d/m/Y", strtotime($rental['startDate'])) . " - " . date("d/m/Y", strtotime($rental['endDate'])); ?></footer>
                            <footer class="blockquote-footer">ราคา : <?php echo number_format($rental['price']); ?> ฿</footer>
                            <footer class="text-center">
                                <button type="button" class="btn btn-primary">แก้ไข</button>
                                <button type="button" class="btn btn-warning">คืนรถ</button>
                                <button type="button" class="btn btn-danger">ยกเลิก</button>
                            </footer>
                        </blockquote>
                    </div>
                </div>
            </div>
        <?php
        }
        ?>
    </div>
</div>