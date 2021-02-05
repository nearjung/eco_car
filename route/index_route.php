<?php
session_start();
$email = @$_COOKIE['email'];
$password = @$_COOKIE['password'];

// Import SQL Config
include_once("./configuration/config.php");

if (!$email || !$password) {
    include_once("login_route.php");
} else {
    include_once("main_route.php");
}
?>