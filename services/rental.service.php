<?php
class RentalService
{
    public function get()
    {
        global $db, $api, $date;
        $cmd = " SELECT trrental.startDate,";
        $cmd .= " trrental.endDate,";
        $cmd .= " trrental.description,";
        $cmd .= " trrental.price,";
        $cmd .= " mscar.brand,";
        $cmd .= " mscar.model,";
        $cmd .= " mscar.plate,";
        $cmd .= " CONCAT(mscustomer.title,mscustomer.firstname, ' ', mscustomer.lastname) as fullName,";
        $cmd .= " mscustomer.telephone,";
        $cmd .= " mscustomer.idcard,";
        $cmd .= " trpicture.fileName,";
        $cmd .= " trpicture.filePath";
        $cmd .= " FROM trrental ";
        $cmd .= " LEFT JOIN mscar ON mscar.carId = trrental.carId";
        $cmd .= " LEFT JOIN mscustomer ON mscustomer.customerId = trrental.customerId";
        $cmd .= " LEFT JOIN trpicture ON trpicture.pictureId = mscar.carId";
        $cmd .= " WHERE trrental.active = 'Y'";
        $sql = $db->prepare($cmd);
        $sql->execute();
        $result = $sql->fetchAll();
        return $result;
    }

    public function getCar()
    {
        global $db, $api, $date;
        $cmd = " select *, mscar.price as carprice, mscar.carId from mscar";
        $cmd .= " LEFT JOIN trrental ON trrental.carId = mscar.carId";
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
        $cmd .= " LEFT JOIN trrental ON trrental.customerId = mscustomer.customerId";
        $cmd .= " where trrental.customerId is null AND mscustomer.active = 'Y'";
        $sql = $db->prepare($cmd);
        $sql->execute();
        $result = $sql->fetchAll();
        return $result;
    }

    public function insertRental($carId, $customerId, $startDate, $endDate, $info, $price)
    {
        global $db, $api, $date;

        if(!$_POST['carId'] || !$_POST['customerId']) {
            $api->popup("Error", "กรุณากรอกข้อมูลให้ครบทุกช่อง", "error");
            return;
        } 

        if($_POST['startDate'] >= $_POST['endDate']) {
            $api->popup("Error", "กรุณาเลือกวันที่ให้ถูกต้อง", "error");
            return;
        }

        $active = 'Y';
        $sql = $db->prepare("INSERT INTO trrental(customerId, carId, startDate, endDate, description, price, active) VALUES(:customerId, :carId, :startDate, :endDate, :info, :price, :active)");
        $sql->bindParam(":customerId", $customerId);
        $sql->bindParam(":carId", $carId);
        $sql->bindParam(":startDate", $startDate);
        $sql->bindParam(":endDate", $endDate);
        $sql->bindParam(":info", $info);
        $sql->bindParam(":price", $price);
        $sql->bindParam(":active", $active);
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

    function getDate(startDate, endDate) {
        var oneDay = 24 * 60 * 60 * 1000; // hours*minutes*seconds*milliseconds
        var firstDate = new Date(startDate);
        var secondDate = new Date(endDate);
        document.getElementById("startDate").value = startDate;
        document.getElementById("endDate").value = endDate;

        var diffDays = Math.round(Math.abs((firstDate - secondDate) / oneDay));
        document.getElementById("price").value = diffDays;
        console.log(diffDays);
        return diffDays;
    }
</script>