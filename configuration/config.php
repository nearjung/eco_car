<?php
ini_set('display_errors', '1');
$mysql_connection_setting = true;
$mysql_host =   "sql12.freemysqlhosting.net";
$mysql_user =   "sql12390926";
$mysql_pass =   "2zDWCcRgaD";
$mysql_db   =   "sql12390926";

// Site config.
$site_charset   = "utf-8";
$site_url       = "http://localhost/";

if ($mysql_connection_setting) {
    $db = new PDO("mysql:dbname=" . $mysql_db . ";host=" . $mysql_host . "", $mysql_user, $mysql_pass);
    $db->exec("set names utf8");
}
include_once("function.php");
$api = new API(true);
$ip = $_SERVER['REMOTE_ADDR'];
date_default_timezone_set('Asia/Bangkok');
$dateNow = date("d/m/Y H:i:s");
$date = date("Y-m-d H:i:s");
$empId = @$_COOKIE['empId'];
?>