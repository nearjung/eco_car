<?php
include_once("./services/getback.service.php");
$getbackService = new GetbackService(true);
$getRental = $getbackService->get();
if (@$_GET['id']) {
    if ($getbackService->updateRental(false, @$_GET['id'], null, null, '', null, 'complete', @$_GET['finesDetail'], @$_GET['fines']) === true) {
        echo "Success";
        exit();
    }
}
?>
<div class="dashboard-content">
    <div class="row">
        <div class="col-6">
            <h1>รายการรับคืนรถ</h1>
        </div>
    </div>
    <div class="row" id="rentalList">
        <?php
        for ($i = 0; $i < count($getRental); $i++) {
            $rental = $getRental[$i];
        ?>

            <div class="modal-main" id="checkCar<?php echo $rental['rentId']; ?>">
                <div class="modal-content col-6">
                    <div class="row">
                        <div class="col-12 ms-3 mt-3"><span style="font-size: 20px;">ตรวจสอบสภาพรถ : <?php echo $rental['brand'] . " " . $rental['model'] . " " . $rental['plate']; ?></span></div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <hr>
                        </div>
                    </div>
                    <form name="form" action="" method="post">
                        <div class="row">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-11 m-2">
                                        <div class="form-group">
                                            <label for="finesDetail<?php echo $rental['rentId']; ?>">สภาพรถหลังคืน</label>
                                            <textarea name="finesDetail<?php echo $rental['rentId']; ?>" class="form-control" id="finesDetail<?php echo $rental['rentId']; ?>" rows="3" value=""></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-11 m-2">
                                        <div class="form-group">
                                            <label for="fines<?php echo $rental['rentId']; ?>">ค่าปรับ</label>
                                            <input type="number" class="form-control" name="fines<?php echo $rental['rentId']; ?>" id="fines<?php echo $rental['rentId']; ?>" value="">
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>


                        <div class="row mb-2 m-2">
                            <div class="col-12" style="text-align: right;">
                                <button type="button" onclick="openConfirm(<?php echo $rental['rentId']; ?>, $('#finesDetail<?php echo $rental['rentId']; ?>').val(), $('#fines<?php echo $rental['rentId']; ?>').val())" name="submitAdd" class="btn btn-success">Save</button>
                                <button type="button" onclick="checkCarModal(<?php echo $rental['rentId']; ?>)" class="btn btn-danger">Close</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-4 mb-2">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-7"><?php echo $rental['brand'] . " " . $rental['model']; ?></div>
                        </div>

                    </div>
                    <div class="card-body">
                        <blockquote class="blockquote mb-0">
                            <?php
                            if ($rental['status'] == "complete") {
                                echo '<div class="striked-out">';
                            } else {
                                echo '<div>';
                            }
                            ?>
                            <p class="text-center mb-4"><img src="./assets/images/upload/<?php echo $rental['fileName']; ?>"></p>
                            <footer class="blockquote-footer">ทะเบียน : <?php echo $rental['plate']; ?></footer>
                            <footer class="blockquote-footer">ชื่อผู้เช่า : <?php echo $rental['fullName']; ?></footer>
                            <footer class="blockquote-footer">เบอร์โทรศัพท์ : <?php echo $rental['telephone']; ?></footer>
                            <footer class="blockquote-footer">รหัสบัตรประชาชน : <?php echo $rental['idcard']; ?></footer>
                            <footer class="blockquote-footer">รายละเอียด : <?php echo $rental['description']; ?></footer>
                            <footer class="blockquote-footer">ระยะเวลา : <?php echo date("d/m/Y", strtotime($rental['startDate'])) . " - " . date("d/m/Y", strtotime($rental['endDate'])); ?></footer>
                            <footer class="blockquote-footer">ค่าเช่าต่อวัน : <?php echo number_format($rental['carprice']); ?> ฿</footer>
                            <footer class="blockquote-footer">ค่าเช่าทั้งหมด : <?php echo number_format($rental['price']); ?> ฿</footer>
                            <footer class="blockquote-footer" style="color: red;">วันคืนรถ : <?php echo date("d/m/Y H:i:s", strtotime($rental['backDate'])) ?></footer>
                            <footer class="blockquote-footer" style="color: red;">สภาพรถ : <?php echo $rental['finesDetail']; ?></footer>
                            <footer class="blockquote-footer" style="color: red;">ค่าปรับ : <?php echo number_format($rental['fines']); ?></footer>
                            <footer class="blockquote-footer" style="color: red; font-weight:bold;">ยอดรวม : <?php echo number_format($rental['fines'] + $rental['price']); ?></footer>
                    </div>
                    </blockquote>
                    <div class="row m-2">
                        <?php
                        if ($rental['status'] == "complete") {
                        ?>
                            <div class="col-12 text-center" style="color:green;">คืนรถสำเร็จ</div>
                        <?php } else { ?>
                            <div class="col-12 text-center"><button onclick="checkCarModal(<?php echo $rental['rentId']; ?>)" type="button" class="btn btn-success">ตรวจสอบสภาพรถ</button></div>
                        <?php } ?>
                    </div>
                </div>
            </div>
    </div>

<?php
        }
?>
</div>

</div>