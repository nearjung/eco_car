<?php
$pages = @$_GET['pages'];
if ($pages == 'member') {
    include_once("./module/main/pages/memberinfo/memberinfo.php");
} else if ($pages == 'rental') {
    include_once("./module/main/pages/rental/rental.php");
} else if ($pages == 'getback') {
    include_once("./module/main/pages/getback/getback.php");
} else if ($pages == 'carinfo') {
    include_once("./module/main/pages/carinfo/carinfo.php");
} else if ($pages == 'agreement') {
    include_once("./module/main/pages/agreement/agreement.php");
} else if ($pages == 'memberform') {
    include_once("./module/main/pages/memberinfo/memberform.php");
} else if ($pages == 'logout') {
    setcookie("email", "", time() - 3600);
    setcookie("password", "", time() - 3600);
    $api->go("/");
} else {
    include_once("./module/main/pages/dashboard/dashboard.php");
}
