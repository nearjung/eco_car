<?php
class MainService
{
    public function getMember($email, $password)
    {
        global $db, $api, $date;
        $sql = $db->prepare("SELECT * FROM msemployee WHERE email = :email AND password = :password");
        $sql->BindParam(":email", $email);
        $sql->BindParam(":password", $password);
        $sql->execute();
        $result = $sql->fetch();
        if (!$result) {
            $api->popup("Error", "อีเมลหรือรหัสผ่านไม่ถูกต้อง", "error");
        } else {
            return $result;
        }
    }
}
