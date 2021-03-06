<?php
class RentalService
{
    public function get()
    {
        global $db, $api, $date;
        $cmd = " SELECT trrental.startDate,";
        $cmd .= " trrental.endDate,";
        $cmd .= " trrental.rentId,";
        $cmd .= " trrental.description,";
        $cmd .= " trrental.customerId,";
        $cmd .= " trrental.carId,";
        $cmd .= " trrental.price,";
        $cmd .= " mscar.brand,";
        $cmd .= " mscar.model,";
        $cmd .= " mscar.plate,";
        $cmd .= " CONCAT(mscustomer.title,mscustomer.firstname, ' ', mscustomer.lastname) as fullName,";
        $cmd .= " mscustomer.telephone,";
        $cmd .= " mscustomer.idcard,";
        $cmd .= " trpicture.fileName,";
        $cmd .= " trpicture.filePath,";
        $cmd .= " mscar.price as carprice";
        $cmd .= " FROM trrental ";
        $cmd .= " LEFT JOIN mscar ON mscar.carId = trrental.carId";
        $cmd .= " LEFT JOIN mscustomer ON mscustomer.customerId = trrental.customerId";
        $cmd .= " LEFT JOIN trpicture ON trpicture.pictureId = mscar.pictureId";
        $cmd .= " WHERE trrental.active = 'Y' AND trrental.status = 'rent'";
        $sql = $db->prepare($cmd);
        $sql->execute();
        $result = $sql->fetchAll();
        return $result;
    }

    public function getCar()
    {
        global $db, $api, $date;
        $cmd = " select *, mscar.price as carprice, mscar.carId from mscar";
        $cmd .= " LEFT JOIN trrental ON trrental.carId = mscar.carId AND trrental.active = 'Y' AND trrental.status = 'rent'";
        $cmd .= " where trrental.carId is null";
        $sql = $db->prepare($cmd);
        $sql->execute();
        $result = $sql->fetchAll();
        return $result;
    }

    public function getCustomer()
    {
        global $db, $api, $date;
        $cmd = " select *, mscustomer.customerId from mscustomer";
        $cmd .= " LEFT JOIN trrental ON trrental.customerId = mscustomer.customerId AND trrental.active = 'Y' AND trrental.status = 'rent'";
        $cmd .= " where trrental.customerId is null AND mscustomer.active = 'Y'";
        $sql = $db->prepare($cmd);
        $sql->execute();
        $result = $sql->fetchAll();
        return $result;
    }

    public function insertRental($carId, $customerId, $startDate, $endDate, $info, $price)
    {
        global $db, $api, $date, $empId;

        if (!$_POST['carId'] || !$_POST['customerId']) {
            $api->popup("Error", "กรุณากรอกข้อมูลให้ครบทุกช่อง", "error");
            return;
        }

        if ($_POST['startDate'] >= $_POST['endDate']) {
            $api->popup("Error", "กรุณาเลือกวันที่ให้ถูกต้อง", "error");
            return;
        }

        $active = 'Y';
        $status = 'rent';
        $sql = $db->prepare("INSERT INTO trrental(customerId, carId, startDate, endDate, description, price, status, active, createBy, createDate, updateBy, updateDate) VALUES(:customerId, :carId, :startDate, :endDate, :info, :price, :status, :active, :createBy, :createDate, :updateBy, :updateDate)");
        $sql->bindParam(":customerId", $customerId);
        $sql->bindParam(":carId", $carId);
        $sql->bindParam(":startDate", $startDate);
        $sql->bindParam(":endDate", $endDate);
        $sql->bindParam(":info", $info);
        $sql->bindParam(":price", $price);
        $sql->bindParam(":status", $status);
        $sql->bindParam(":active", $active);
        $sql->bindParam(":createBy", $empId);
        $sql->bindParam(":createDate", $date);
        $sql->bindParam(":updateBy", $empId);
        $sql->bindParam(":updateDate", $date);
        $sql->execute();
        if (!$sql) {
            $api->log($api->jsonParse($sql));
            return false;
        } else {
            $api->log($api->jsonParse($sql));
            return true;
        }
    }

    public function updateRental($delete = false, $rentalId = null, $startDate = null, $endDate = null, $description = '', $price = null, $status = 'rent')
    {
        global $db, $api, $date, $empId;

        if ($delete) {
            $active = 'N';
        } else {
            $active = 'Y';
        }

        $cmd = "UPDATE trrental SET ";
        if (!$delete) {
            if ($startDate) {
                $cmd .= " startDate = :startDate,";
            }
            if ($endDate) {
                $cmd .= " endDate = :endDate,";
            }
            if ($description != '') {
                $cmd .= " description = :info,";
            }
            if ($price) {
                $cmd .= " price = :price,";
            }
            if ($status == 'back') {
                $cmd .= " backDate = :dateBack,";
            }
        }
        $cmd .= "status = :status, active = :active, updateBy = :updateBy, updateDate = :updateDate WHERE rentId = :rentalId";


        $sql = $db->prepare($cmd);
        if (!$delete) {
            if ($startDate) {
                $sql->bindParam(":startDate", $startDate);
            }
            if ($endDate) {
                $sql->bindParam(":endDate", $endDate);
            }
            if ($description) {
                $sql->bindParam(":info", $description);
            }
            if ($price) {
                $sql->bindParam(":price", $price);
            }
        }
        if ($status == 'back') {
            $sql->bindParam(":dateBack", $date);
        }
        $sql->bindParam(":status", $status);
        $sql->bindParam(":active", $active);
        $sql->bindParam(":updateBy", $empId);
        $sql->bindParam(":updateDate", $date);
        $sql->bindParam(":rentalId", $rentalId);
        $sql->execute();
        if (!$sql) {
            $api->log($api->jsonParse($sql));
            return false;
        } else {
            $api->log($api->jsonParse($sql));
            return true;
        }
    }
}
?>

<script>
    function addRentals() {
        var x = document.getElementById("addRental");
        if (x.style.display == "block") {
            x.style.display = "none";
        } else if (x.style.display = "none") {
            x.style.display = "block";
        }
    }

    function updateRentalModal(id) {
        var x = document.getElementById("updateRental" + id);
        if (x.style.display == "block") {
            x.style.display = "none";
        } else if (x.style.display = "none") {
            x.style.display = "block";
        }
    }

    function getDate(startDate, endDate, id = null) {
        var oneDay = 24 * 60 * 60 * 1000; // hours*minutes*seconds*milliseconds
        var firstDate = new Date(startDate);
        var secondDate = new Date(endDate);
        document.getElementById("startDate").value = startDate;
        document.getElementById("endDate").value = endDate;

        var diffDays = Math.round(Math.abs((firstDate - secondDate) / oneDay));
        if (id) {
            document.getElementById("price" + id).value = diffDays;
        } else {
            document.getElementById("price").value = diffDays;
        }
        console.log(diffDays);
        return diffDays;
    }

    function openDelete(rentalId) {
        Swal.fire({
            title: 'คำเตือน !',
            text: "คุณต้องการที่จะลบข้อมูลหรือไม่",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'ตกลง',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                var xmlHttp = new XMLHttpRequest();
                xmlHttp.open("GET", "?pages=rental&id=" + rentalId + "", false); // false for synchronous request
                xmlHttp.send(null);
                if (xmlHttp.status == 200) {
                    if (xmlHttp.responseText.includes("Delete Success") != false) {
                        swal.fire(
                            'Success',
                            'ลบข้อมูลสำเร็จ',
                            'success'
                        ).then(result => {
                            if (result) {
                                $("#rentalList").load(" #rentalList > *");
                            }
                        })
                    }
                }
            }
        })
    }

    function openBack(rentalId) {
        Swal.fire({
            title: 'คำเตือน !',
            text: "คุณรับคืนรถคันนี้แล้วใช่หรือไม่ ?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'ใช่',
            cancelButtonText: 'ไม่ใช่'
        }).then((result) => {
            if (result.isConfirmed) {
                var xmlHttp = new XMLHttpRequest();
                xmlHttp.open("GET", "?pages=rental&back=" + rentalId + "", false); // false for synchronous request
                xmlHttp.send(null);
                console.log(xmlHttp);
                if (xmlHttp.status == 200) {
                    if (xmlHttp.responseText.includes("Success") != false) {
                        swal.fire(
                            'Success',
                            'รับคืนรถสำเร็จ',
                            'success'
                        ).then(result => {
                            if (result) {
                                $("#rentalList").load(" #rentalList > *");
                            }
                        })
                    }
                }
            }
        })
    }
</script>