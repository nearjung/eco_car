<?php
class MemberService
{
    public function get($searchTxt = null)
    {
        global $db, $api, $date;
        $query = "SELECT * FROM mscustomer WHERE ";
        $query .= " active = 'Y'";
        if ($searchTxt) {
            $query .= " AND CONCAT(title,firstname,' ',lastname) LIKE '%" . $searchTxt . "%' ";
        }
        $sql = $db->prepare($query);
        $sql->execute();
        $result = $sql->fetchAll();
        return $result;
    }

    public function register($title, $firstname, $lastname, $idcard, $telephone)
    {
        global $db, $api, $date, $site_url, $empId;
        if (!$title || !$firstname || !$lastname) {
            $api->popup("Error", "กรุณากรอกชื่อให้ถูกต้อง", "error");
        } else {
            $active = 'Y';
            $sql = $db->prepare("INSERT INTO mscustomer(title, firstname, lastname, telephone, idcard, active, createBy, createDate, updateBy, updateDate) VALUES(:title, :firstname, :lastname, :telephone, :idcard, :active, :createBy, :createDate, :updateBy, :updateDate)");
            $sql->bindParam(":title", $title);
            $sql->bindParam(":firstname", $firstname);
            $sql->bindParam(":lastname", $lastname);
            $sql->bindParam(":telephone", $telephone);
            $sql->bindParam(":idcard", $idcard);
            $sql->bindParam(":active", $active);
            $sql->bindParam(":createBy", $empId);
            $sql->bindParam(":createDate", $date);
            $sql->bindParam(":updateBy", $empId);
            $sql->bindParam(":updateDate", $date);
            $sql->execute();
            if (!$sql) {
                $api->popup("Error", "เกิดข้อผิดพลาดขณะส่งข้อมูล", "error");
            } else {
                $api->popup("Success", "บันทึกข้อมูลสำเร็จ", "success", "?pages=member");
            }
            $api->log($api->jsonParse($sql));
        }
    }

    public function editMember($delete = false, $customerId = null, $title = null, $firstname = null, $lastname = null, $idcard = null, $telephone = null)
    {
        global $db, $api, $date;
        if ((!$delete) && (!$title || !$firstname || !$lastname)) {
            $api->popup("Error", "กรุณากรอกชื่อให้ถูกต้อง", "error");
            return;
        }

        if (!$delete) {
            $active = 'Y';
            $sql = $db->prepare("UPDATE mscustomer SET title = :title, firstname = :firstname, lastname = :lastname, telephone = :telephone, idcard = :idcard, active = :active, updateBy = :updateBy, updateDate = :updateDate WHERE customerId = :customerId");
            $sql->bindParam(":title", $title);
            $sql->bindParam(":firstname", $firstname);
            $sql->bindParam(":lastname", $lastname);
            $sql->bindParam(":telephone", $telephone);
            $sql->bindParam(":idcard", $idcard);
        } else {
            $active = 'N';
            $sql = $db->prepare("UPDATE mscustomer SET active = :active, updateBy = :updateBy, updateDate = :updateDate WHERE customerId = :customerId");
        }
        $sql->bindParam(":active", $active);
        $sql->bindParam(":customerId", $customerId);
        $sql->bindParam(":updateBy", $empId);
        $sql->bindParam(":updateDate", $date);
        $sql->execute();
        if (!$sql) {
            $api->popup("Error", "เกิดข้อผิดพลาดขณะส่งข้อมูล", "error");
        } else {
            if (!$delete) {
                echo "Update Success";
            } else {
                echo "Delete Success";
            }
        }
        $api->log($api->jsonParse($sql));
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

    function onSearchBtn() {
        var x = document.getElementById("nosearch");
        var y = document.getElementById("search");

        if (x.style.display != "none") {
            y.style.display = "revert";
            x.style.display = "none";
        } else if (x.style.display == "none") {
            y.style.display = "none";
            x.style.display = "revert";
        }

    }

    function onSearch(txt) {
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if (keycode == '13') {
            window.top.location = "?pages=member&update=3&searchTxt=" + txt + "";
        }
    }


    function onUpdate(deletet = false, customerId = null, title = null, firstname = null, lastname = null, idcard = null, telephone = null) {
        var xmlHttp = new XMLHttpRequest();
        xmlHttp.open("GET", "?pages=member&update=1&customerId=" + customerId + "&title=" + title + "&firstname=" + firstname + "&lastname=" + lastname + "&idcard=" + idcard + "&telephone=" + telephone + "", false); // false for synchronous request
        xmlHttp.send(null);
        if (xmlHttp.status == 200) {
            if (xmlHttp.responseText.includes("Update Success") != false) {
                swal.fire(
                    'Success',
                    'บันทึกข้อมูลสำเร็จ',
                    'success'
                ).then(result => {
                    if (result) {
                        $("#memberList").load(" #memberList > *");
                    }
                })
            }
        }
    }

    function openDelete(customerId) {
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
                xmlHttp.open("GET", "?pages=member&update=2&customerId=" + customerId + "", false); // false for synchronous request
                xmlHttp.send(null);
                if (xmlHttp.status == 200) {
                    if (xmlHttp.responseText.includes("Delete Success") != false) {
                        swal.fire(
                            'Success',
                            'ลบข้อมูลสำเร็จ',
                            'success'
                        ).then(result => {
                            if (result) {
                                $("#memberList").load(" #memberList > *");
                            }
                        })
                    }
                }
            }
        })
    }
</script>