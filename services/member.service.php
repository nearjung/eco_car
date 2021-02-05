<?php
class MemberService
{
    public function get()
    {
        global $db, $api, $date;
        $sql = $db->prepare("SELECT * FROM mscustomer WHERE active = 'Y' ORDER BY customerId DESC");
        $sql->execute();
        $result = $sql->fetchAll();
        return $result;
    }

    public function register($title, $firstname, $lastname, $idcard, $telephone)
    {
        global $db, $api, $date, $site_url;
        if (!$title || !$firstname || !$lastname || $idcard || $telephone) {
            $api->popup("Error", "กรุณากรอกข้อมูลให้ครบทุกช่อง");
        }
    }
}
?>

<script>
    function addMember() {
        var x = document.getElementById("addMember");
        if (x.style.display == "block") {
            x.style.display = "none";
        } else if (x.style.display = "none") {
            x.style.display = "block";
        }
    }

    function openModal(id) {
        var x = document.getElementById("myDIV" + id);
        if (x.style.display == "block") {
            x.style.display = "none";
        } else if (x.style.display = "none") {
            x.style.display = "block";
        }
    }
</script>