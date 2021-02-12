<?php
include_once("./services/carinfo.service.php");
$carService = new CarService(true);
$getCar = $carService->get();
?>
<div class="dashboard-content">
    <div class="row">
        <div class="col-6">
            <h1>รายการรถยนต์</h1>
        </div>
        <div class="col-6" style="text-align: end; margin: auto; color:green;">
            <h5>เพิ่มรถยนต์&nbsp; <i style="cursor: pointer;" onclick="addCar()" class="fas fa-plus"></i></h5>
        </div>
    </div>
    <div class="row" id="rentalList">

        <?php
        for ($i = 0; $i < count($getCar); $i++) {
            $car = $getCar[$i];
        ?>
            <div class="col-4 mb-3">
                <div class="card">
                    <div class="card-header">
                        <?php echo $car['brand'] . " " . $car['model'] . " " . $car['plate']; ?>
                    </div>
                    <div class="card-body">
                        <p class="text-center mb-4"><img src="./assets/images/upload/<?php echo $car['fileName']; ?>"></p>
                        <p class="card-text text-center">ค่าเช่า : <?php echo number_format($car['price']) . " / วัน"; ?></p>
                        <p class="card-text text-center"><a class="btn btn-warning"><i class="far fa-edit"></i></a> <a class="btn btn-danger"><i class="far fa-trash-alt"></i></a></p>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>

    <div class="modal-main" id="addCar">
        <div class="modal-content col-6">
            <div class="row">
                <div class="col-12 ms-3 mt-3"><span style="font-size: 20px;">เพิ่มรถยนต์</span></div>
            </div>
            <div class="row">
                <div class="col-12">
                    <hr>
                </div>
            </div>
            <form name="form" action="" method="post" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-12">
                        <div class="row">
                            <div class="col-11 m-2">
                                <div class="form-group">
                                    <label for="brand">ยี่ห้อรถ</label>
                                    <input type="text" class="form-control" name="brand" id="brand" placeholder="Toyota, Honda, Mazda" value="">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-11 m-2">
                                <div class="form-group">
                                    <label for="model">รุ่นรถ</label>
                                    <input type="text" class="form-control" name="model" id="model" placeholder="Yaris, Jazz, Camry" value="">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-11 m-2">
                                <div class="form-group">
                                    <label for="plate">ทะเบียนรถ</label>
                                    <input type="text" class="form-control" name="plate" id="plate" placeholder="กข1234 ชลบุรี" value="">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-11 m-2">
                                <div class="form-group">
                                    <label for="price">ค่าเช่าต่อวัน</label>
                                    <input type="text" class="form-control" name="price" id="price" placeholder="0" value="">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-11 m-2">
                                <div class="form-group">
                                    <label for="picture">รูปภาพรถ</label>
                                    <input type="file" class="form-control" name="picture" id="picture" value="">
                                    <!-- <img id="imageCar" style="display: hidden;" src="" style="height: 250px;"> -->
                                </div>
                            </div>
                        </div>

                    </div>
                </div>


                <div class="row mb-2 m-2">
                    <div class="col-12" style="text-align: right;">
                        <button type="button" name="submitAdd" class="btn btn-success">Save</button>
                        <button type="button" onclick="addCar()" class="btn btn-danger">Close</button>
                    </div>
                </div>
            </form>
            <?php
            if (isset($_POST['submitAdd'])) {
                $target_dir = "./assets/images/upload/";
                $fileName = basename(md5($_FILES["picture"]["name"] . rand(0, 500)));
                $target_file = $target_dir . $fileName;
                $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
                $check = getimagesize($_FILES["picture"]["tmp_name"]);
                if ($check !== true) {
                    $api->popup("Error", "กรุณาเลือกไฟล์รูปภาพ", "error");
                    return;
                }

                if ($_FILES["picture"]["size"] > 2 * 1024 * 1024) {
                    $api->popup("Error", "ขนาดไฟล์ไม่ควรเกิน 2MB", "error");
                    return;
                }

                if (move_uploaded_file($_FILES["picture"]["tmp_name"], $target_file)) {
                    // Insert after upload
                    $active = 'Y';
                    $sql = $db->prepare("INSERT INTO trpicture(fileName, filePath, active, createBy, createDate, updateBy, updateDate) VALUES(:fileName, :filePath, :active, :createBy, :createDate, :updateBy, :updateDate)");
                    $sql->bindParam(":fileName", $fileName);
                    $sql->bindParam(":filePath", $target_file);
                    $sql->bindParam(":active", $active);
                    $sql->bindParam(":createBy", $empId);
                    $sql->bindParam(":createDate", $date);
                    $sql->bindParam(":updateBy", $empId);
                    $sql->bindParam(":updateDate", $date);
                    $sql->execute();
                    if(!$sql) {
                        $api->popup("Error", "เกิดข้อผิดพลาดขณะรันข้อมูล", "error");
                    } else {
                        
                        $api->popup("Success", "เพิ่มรถยนต์สำเร็จ", "?pages=carinfo");
                    }
                } else {
                    $api->popup("Error", "ไม่สามารถอัพโหลดรูปได้", "error");
                }
            }
            ?>
        </div>
    </div>
</div>