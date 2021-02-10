<?php
include_once("./services/rental.service.php");
$rentalService = new RentalService(true);
$getRental = $rentalService->get();
$getCar = $rentalService->getCar();
$getCustomer = $rentalService->getCustomer();
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
    <div class="row">
        <?php
        for ($i = 0; $i < count($getRental); $i++) {
            $rental = $getRental[$i];
        ?>
            <div class="col-4 mb-2">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-11"><?php echo $rental['brand'] . " " . $rental['model']; ?></div>
                            <div class="col-1">
                                <div class="dropdownStyle">
                                    <i class="fas fa-bars"></i>
                                    <div class="dropdownStyle-content">
                                        <a>แก้ไข</a>
                                        <a>รับรถคืน</a>
                                        <a>ยกเลิกการเช่า</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="card-body">
                        <blockquote class="blockquote mb-0">
                            <p class="text-center mb-4"><img src="./assets/images/mockup/yaris.jpg"></p>
                            <footer class="blockquote-footer">ทะเบียน : <?php echo $rental['plate']; ?></footer>
                            <footer class="blockquote-footer">ชื่อผู้เช่า : <?php echo $rental['fullName']; ?></footer>
                            <footer class="blockquote-footer">เบอร์โทรศัพท์ : <?php echo $rental['telephone']; ?></footer>
                            <footer class="blockquote-footer">รหัสบัตรประชาชน : <?php echo $rental['idcard']; ?></footer>
                            <footer class="blockquote-footer">ระยะเวลา : <?php echo date("d/m/Y", strtotime($rental['startDate'])) . " - " . date("d/m/Y", strtotime($rental['endDate'])); ?></footer>
                            <footer class="blockquote-footer">ราคา : <?php echo number_format($rental['price']); ?> ฿</footer>

                        </blockquote>
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
                                    <input name="startDate" type="date" onchange="getDate($('#startDate').val(), $('#endDate').val())" class="form-control" id="startDate" value="">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-11 m-2">
                                <div class="form-group">
                                    <label for="title">วันที่สิ้นสุด</label>
                                    <input name="endDate" id="endDate" type="date" class="form-control" onchange="getDate($('#startDate').val(), $('#endDate').val())" value="">
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
                        <button type="button" onclick="addRental()" class="btn btn-danger">Close</button>
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