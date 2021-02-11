<?php
class LoginService
{
    public function login($email, $password)
    {
        global $db, $api, $date;

        if (!$email || !$password) {
            $api->popup("Error", "กรุณากรอกข้อมูลให้ครบทุกช่อง", "error");
        }

        $sql = $db->prepare("SELECT * FROM msemployee WHERE email = :email AND password = :password");
        $sql->BindParam(":email", $email);
        $sql->BindParam(":password", $password);
        $sql->execute();
        $result = $sql->fetch();
        $api->log($api->jsonParse($result));
        if (!$result) {
            $api->popup("Error", "อีเมลหรือรหัสผ่านไม่ถูกต้อง", "error");
        } else {
            setcookie("email", $result['email'], 0, "/");
            setcookie("empId", $result['empId'], 0, "/");
            setcookie("password", $result['password'], 0, "/");
            return $result;
        }
    }
}
