<?php
class CarService
{
    public function get()
    {
        global $db, $api, $date, $empId;
        $active = 'Y';
        $sql = $db->prepare("SELECT * FROM mscar LEFT JOIN trpicture ON trpicture.pictureId = mscar.pictureId WHERE mscar.active = :active ORDER BY mscar.createDate DESC");
        $sql->bindParam(":active", $active);
        $sql->execute();;
        $result = $sql->fetchAll();
        return $result;
    }

    public function getLastPicture() {
        global $db, $api, $date, $empId;
    }
}
?>

<script>
    function addCar() {
        var x = document.getElementById("addCar");
        if (x.style.display == "block") {
            x.style.display = "none";
        } else if (x.style.display = "none") {
            x.style.display = "block";
        }
    }

</script>