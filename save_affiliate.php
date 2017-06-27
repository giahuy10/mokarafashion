<?php
$hash = $_GET['q'];
$cookie_name = "affiliate_id";
setcookie($cookie_name, $hash, time() + (86400 * 30), "/"); // 86400 = 1 day
echo "hello ".$_COOKIE[$cookie_name];
?>
