<?php
class GetbackService
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
        $cmd .= " trrental.backDate,";
        $cmd .= " trrental.status,";
        $cmd .= " trrental.fines,";
        $cmd .= " trrental.finesDetail,";
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
        $cmd .= " WHERE trrental.active = 'Y' AND trrental.status IN ('back', 'complete') ORDER BY trrental.status IN('complete'), trrental.updateDate DESC";
        $sql = $db->prepare($cmd);
        $sql->execute();
        $result = $sql->fetchAll();
        return $result;
    }

    public function updateRental($delete = false, $rentalId = null, $startDate = null, $endDate = null, $description = '', $price = null, $status = 'rent', $finesDetail = null, $fines = null)
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
            if ($finesDetail) {
                $cmd .= " finesDetail = :finesDetail,";
            }
            if ($fines) {
                $cmd .= " fines = :fines,";
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
            if ($finesDetail) {
                $sql->bindParam(":finesDetail", $finesDetail);
            }
            if ($fines) {
                $sql->bindParam(":fines", $fines);
            }
            if ($description) {
                $sql->bindParam(":info", $description);
            }
            if ($price) {
                $sql->bindParam(":price", $price);
            }
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
    function checkCarModal(id) {
        var x = document.getElementById("checkCar" + id);
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

    function openConfirm(rentalId, finesDetail, fines) {
        Swal.fire({
            title: 'คำเตือน !',
            text: "คุณได้ทำการตรวจสอบรถคันนี้แล้ว ?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'ตกลง',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                var xmlHttp = new XMLHttpRequest();
                xmlHttp.open("GET", "?pages=getback&id=" + rentalId + "&finesDetail=" + finesDetail + "&fines=" + fines, false); // false for synchronous request
                xmlHttp.send(null);
                console.log(xmlHttp);
                if (xmlHttp.status == 200) {
                    if (xmlHttp.responseText.includes("Success") != false) {
                        swal.fire(
                            'Success',
                            'บันทึกสำเร็จ',
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