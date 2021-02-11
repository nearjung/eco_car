<?php
include_once("./services/rental.service.php");
$rentalService = new RentalService(true);
$getRental = $rentalService->get();
$getCar = $rentalService->getCar();
$getCustomer = $rentalService->getCustomer();

if(@$_GET['id']) {
    if($rentalService->updateRental(true, @$_GET['id']) === true) {
        echo "Delete Success";
        exit();
    }
}
if(@$_GET['back']) {
    if($rentalService->updateRental(false, @$_GET['back'], null, null, '', null, 'back') === true) {
        echo "Success";
        exit();
    }
}
?>
<div class="dashboard-content">
    <div class="row">
        <div class="col-6">
            <h1>รายการเช่ารถ</h1>
        </div>
        <div class="col-6" style="text-align: end; margin: auto; color:green;">
            <h5>เพิ่มรายการเช่า&nbsp; <i style="cursor: pointer;" onclick="addRentals()" class="fas fa-plus"></i></h5>
        </div>
    </div>
    <div class="row" id="rentalList">
        <?php
        for ($i = 0; $i < count($getRental); $i++) {
            $rental = $getRental[$i];
        ?>
            <!-- Update Modal -->
            <div class="modal-main" id="updateRental<?php echo $rental['rentId']; ?>">
                <div class="modal-content col-6">
                    <div class="row">
                        <div class="col-12 ms-3 mt-3"><span style="font-size: 20px;">แก้ไขรายการเช่า</span></div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <hr>
                        </div>
                    </div>
                    <form name="addRental" action="" method="post">
                        <div class="row">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-11 m-2">
                                        <div class="form-group">
                                            <label for="carselect">กรุณาเลือกรถ</label>
                                            <select name="carId<?php echo $rental['rentId']; ?>" class="form-control" id="carselect" readonly>
                                                <option value="<?php echo $rental['carId'] . "|" . $rental['carprice']; ?>"><?php echo $rental['plate']; ?></option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-11 m-2">
                                        <div class="form-group">
                                            <label for="customerselect">ชื่อลูกค้า</label>
                                            <select name="customerId<?php echo $rental['rentId']; ?>" class="form-control" id="customerselect" readonly>
                                                <option value=""><?php echo $rental['fullName']; ?></option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-11 m-2">
                                        <div class="form-group">
                                            <label for="title">วันที่เริ่มเช่า</label>
                                            <input name="startDate<?php echo $rental['rentId']; ?>" type="date" onchange="getDate($('#startDate<?php echo $rental['rentId']; ?>').val(), $('#endDate<?php echo $rental['rentId']; ?>').val(), <?php echo $rental['rentId']; ?>)" class="form-control" id="startDate<?php echo $rental['rentId']; ?>" value="<?php echo date("Y-m-d", strtotime($rental['startDate'])); ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-11 m-2">
                                        <div class="form-group">
                                            <label for="title">วันที่สิ้นสุด</label>
                                            <input name="endDate<?php echo $rental['rentId']; ?>" id="endDate<?php echo $rental['rentId']; ?>" type="date" class="form-control" onchange="getDate($('#startDate<?php echo $rental['rentId']; ?>').val(), $('#endDate<?php echo $rental['rentId']; ?>').val(), <?php echo $rental['rentId']; ?>)" value="<?php echo date("Y-m-d", strtotime($rental['endDate'])); ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-11 m-2">
                                        <div class="form-group">
                                            <label for="description">รายละเอียดอื่นๆ</label>
                                            <textarea name="description<?php echo $rental['rentId']; ?>" class="form-control" id="description" rows="2"><?php echo $rental['description']; ?></textarea>
                                            <input type="hidden" name="price<?php echo $rental['rentId']; ?>" id="price<?php echo $rental['rentId']; ?>" value="0">
                                            <input type="hidden" name="rentId<?php echo $rental['rentId']; ?>" id="rentId" value="<?php echo $rental['rentId']; ?>">
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>


                        <div class="row mb-2 m-2">
                            <div class="col-12" style="text-align: right;">
                                <button type="submit" name="submitUpdate" class="btn btn-success">Save</button>
                                <button type="button" onclick="updateRentalModal(<?php echo $rental['rentId']; ?>)" class="btn btn-danger">Close</button>
                            </div>
                        </div>
                    </form>

                    <?php
                    if (isset($_POST['submitUpdate'])) {
                        if ($rentalService->updateRental(false, $_POST['rentId' . $rental['rentId']], @$_POST['startDate' . $rental['rentId']], @$_POST['endDate' . $rental['rentId']], @$_POST['description' . $rental['rentId']], (@$_POST['price' . $rental['rentId']]) ? @$_POST['price' . $rental['rentId']] * explode("|", @$_POST['carId' . $rental['rentId']])[1] : null) === true) {
                            $api->popup("Success", "บันทึกสำเร็จ", "success", '?pages=rental');
                        } else {
                            $api->popup("Error", "เกิดข้อผิดพลาด !", "error", "?pages=rental");
                        }
                    }
                    ?>
                </div>
            </div>

            <div class="col-4 mb-2">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-7"><?php echo $rental['brand'] . " " . $rental['model']; ?></div>
                            <div class="col-4">
                                <div class="row">
                                    <div class="col-4"><button type="button" class="btn btn-primary"><i class="fas fa-print"></i></button></div>
                                    <div class="col-4"><button type="button" class="btn btn-warning" onclick="updateRentalModal(<?php echo $rental['rentId']; ?>)"><i class="fas fa-edit"></i></button></div>
                                    <div class="col-4"><button type="button" class="btn btn-danger" onclick="openDelete(<?php echo $rental['rentId']; ?>)"><i class="fas fa-trash-alt"></i></button></div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="card-body">
                        <blockquote class="blockquote mb-0">
                            <p class="text-center mb-4"><img src="./assets/images/upload/<?php echo $rental['fileName']; ?>"></p>
                            <footer class="blockquote-footer">ทะเบียน : <?php echo $rental['plate']; ?></footer>
                            <footer class="blockquote-footer">ชื่อผู้เช่า : <?php echo $rental['fullName']; ?></footer>
                            <footer class="blockquote-footer">เบอร์โทรศัพท์ : <?php echo $rental['telephone']; ?></footer>
                            <footer class="blockquote-footer">รหัสบัตรประชาชน : <?php echo $rental['idcard']; ?></footer>
                            <footer class="blockquote-footer">รายละเอียด : <?php echo $rental['description']; ?></footer>
                            <footer class="blockquote-footer">ระยะเวลา : <?php echo date("d/m/Y", strtotime($rental['startDate'])) . " - " . date("d/m/Y", strtotime($rental['endDate'])); ?></footer>
                            <footer class="blockquote-footer">ค่าเช่าต่อวัน : <?php echo number_format($rental['carprice']); ?> ฿</footer>
                            <footer class="blockquote-footer">ค่าเช่าทั้งหมด : <?php echo number_format($rental['price']); ?> ฿</footer>
                        </blockquote>
                        <div class="row m-2">
                            <div class="col-12 text-center"><button onclick="openBack(<?php echo $rental['rentId']; ?>)" type="button" class="btn btn-success">รับรถคืน</button></div>
                        </div>
                    </div>
                </div>
            </div>

        <?php
        }
        ?>
    </div>

    <!-- Add Modal -->
    <div class="modal-main" id="addRental">
        <div class="modal-content col-6">
            <div class="row">
                <div class="col-12 ms-3 mt-3"><span style="font-size: 20px;">เพิ่มรายการเช่า</span></div>
            </div>
            <div class="row">
                <div class="col-12">
                    <hr>
                </div>
            </div>
            <form name="addRental" action="" method="post">
                <div class="row">
                    <div class="col-12">
                        <div class="row">
                            <div class="col-11 m-2">
                                <div class="form-group">
                                    <label for="carselect">กรุณาเลือกรถ</label>
                                    <select name="carId" class="form-control" id="carselect">
                                        <option value="">- กรุณาเลือกรถ -</option>
                                        <?php
                                        for ($i = 0; $i < count($getCar); $i++) {
                                            $car = $getCar[$i];
                                        ?>
                                            <option value="<?php echo $car['carId'] . "|" . $car['carprice']; ?>"><?php echo $car['brand'] . " " . $car['model'] . " " . $car['plate']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-11 m-2">
                                <div class="form-group">
                                    <label for="customerselect">ชื่อลูกค้า</label>
                                    <select name="customerId" class="form-control" id="customerselect">
                                        <option value="">- กรุณาเลือกชื่อลูกค้า -</option>
                                        <?php
                                        for ($i = 0; $i < count($getCustomer); $i++) {
                                            $customer = $getCustomer[$i];
                                        ?>
                                            <option value="<?php echo $customer['customerId']; ?>"><?php echo $customer['title'] . $customer['firstname'] . " " . $customer['lastname']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-11 m-2">
                                <div class="form-group">
                                    <label for="title">วันที่เริ่มเช่า</label>
                                    <input name="startDate" type="date" onchange="getDate($('#startDate').val(), $('#endDate').val(), null)" class="form-control" id="startDate" value="">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-11 m-2">
                                <div class="form-group">
                                    <label for="title">วันที่สิ้นสุด</label>
                                    <input name="endDate" id="endDate" type="date" class="form-control" onchange="getDate($('#startDate').val(), $('#endDate').val(), null)" value="">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-11 m-2">
                                <div class="form-group">
                                    <label for="description">รายละเอียดอื่นๆ</label>
                                    <textarea name="description" class="form-control" id="description" rows="2" value=""></textarea>
                                    <input type="hidden" name="price" id="price" value="0">
                                </div>
                            </div>
                        </div>

                    </div>
                </div>


                <div class="row mb-2 m-2">
                    <div class="col-12" style="text-align: right;">
                        <button type="submit" name="submitAdd" class="btn btn-success">Save</button>
                        <button type="button" onclick="addRentals()" class="btn btn-danger">Close</button>
                    </div>
                </div>
            </form>

            <?php
            if (isset($_POST['submitAdd'])) {
                if ($rentalService->insertRental(explode("|", @$_POST['carId'])[0], $_POST['customerId'], @$_POST['startDate'], @$_POST['endDate'], @$_POST['description'], @$_POST['price'] * explode("|", @$_POST['carId'])[1]) === true) {
                    $api->popup("Success", "บันทึกสำเร็จ", "success", '?pages=rental');
                } else {
                    $api->popup("Error", "เกิดข้อผิดพลาด !", "error", "?pages=rental");
                }
            }
            ?>
        </div>
    </div>
</div>