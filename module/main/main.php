<?php
// Component
include_once("./services/main.service.php");
$mainService = new MainService(true);

$member = $mainService->getMember($_COOKIE['email'], $_COOKIE['password']);
?>
<div class="row" style="height: 100%;">
    <div class="col-2 left-panel" style="padding-right: 0px;">
        <div class="row">
            <div class="col mb-1 text-center"><img class="avatar" src="./assets/images/avatar.png"></div>
        </div>
        <div class="row">
            <div class="col text-center"><?php echo $member['title'] . "" . $member['firstname'] . " " . $member['lastname']; ?></div>
        </div>
        <div class="row">
            <div class="col text-center">
                <hr>
            </div>
        </div>
        <!-- Menu -->
        <div class="row">
            <div class="col">
                <div onclick="go('/')" class="col-12 menu-left"><label><i class="fas fa-home"></i> &nbsp;แดชบอร์ด</label></div>
                <div onclick="go('?pages=member')" class="col-12 menu-left"><label><i class="fas fa-user"></i> &nbsp;&nbsp;ข้อมูลลูกค้า</label></div>
                <div onclick="go('?pages=rental')" class="col-12 menu-left"><label><i class="fas fa-clipboard-list"></i> &nbsp;&nbsp;&nbsp;รายการเช่า</label></div>
                <div onclick="go('?pages=getback')" class="col-12 menu-left"><label><i class="fas fa-receipt"></i> &nbsp;&nbsp;&nbsp;รายการรับคืน</label></div>
                <?php
                if ($member['authority'] == 1) {
                ?>
                <div onclick="go('?pages=carinfo')" class="col-12 menu-left"><label><i class="fas fa-car"></i> &nbsp;&nbsp;ข้อมูลรถยนต์</label></div>
                <div onclick="go('?pages=agreement')" class="col-12 menu-left"><label><i class="fas fa-handshake"></i> &nbsp;สัญญาเช่า</label></div>
                <?php
                }
                ?>
                <div onclick="go('?pages=logout')" class="col-12 menu-left"><label><i class="fas fa-sign-out-alt"></i> &nbsp;&nbsp;ออกจากระบบ</label></div>
            </div>
        </div>
    </div>
    <div class="col-10"><?php
        include_once("./module/main/main.route.php");
    ?></div>
</div>