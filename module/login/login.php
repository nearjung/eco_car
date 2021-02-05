<?php
// Component
include_once("./services/login.service.php");
$loginService = new LoginService(true);

?>
<div class="login-page">
    <div class="login_div animate__animated animate__bounceInLeft">
        <div class="login_tbl col-5">
            <div class="row">
                <div class="col-12 mt-3">เข้าสู่ระบบ</div>
                <div class="col-12">
                    <hr>
                </div>
                <div class="col-12">
                    <form name="login" method="post">
                        <div class="col-10 mb-2" style="margin: auto;"><input class="form-control" autocomplete="off" placeholder="E-mail" name="email" type="email" value=""></div>
                        <div class="col-10 mb-2" style="margin: auto;"><input class="form-control" autocomplete="off" placeholder="Password" name="password" type="password" value=""></div>
                        <div class="col-12"><button name="submit" type="submit" class="btn btn-primary"><i class="fas fa-sign-in-alt"></i> เข้าสู่ระบบ</button> <button type="button" class="btn btn-warning"><i class="fas fa-unlock-alt"></i> ลืมรหัสผ่าน</button></div>
                    </form>
                    <?php
                    if (isset($_POST['email'])) {
                        $login = $loginService->login($_POST['email'], $_POST['password']);
                        if ($login) {
                            $api->popup("สำเร็จ!", "เข้าสู่ระบบสำเร็จ", "success", '/');
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>