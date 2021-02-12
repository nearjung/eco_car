<?php
$a = @$_GET['a'];
$data = @$_GET['data'];

if ($a == "convert") {
    $img = file_get_contents($data);
    $data = base64_encode($img);
    echo $data;
    exit();
}
?>