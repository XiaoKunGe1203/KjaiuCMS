<?php
$pageinfo = $row;
$conn = new mysqli($sqlinfo['host'], $sqlinfo['username'], $sqlinfo['password'], $sqlinfo['dbname']);
$sql = "SELECT * FROM `dashboard_users` WHERE `id` = ".$_COOKIE['uid'] ." AND `authentication` = '".$_COOKIE['authentication']."'";
$result = $conn->query($sql);
$conn->close();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
//密码正确
$userinfo = $row;
session_start();
include 'common/dash_head.html';
include 'common/dash_top.html';
}
}else {
header("Location: /login");
exit;
}
switch ($pages) {
   case "/dashboard":
    include 'common/dash.php';
     break;
   case "/create":
    include 'common/dash_create.php';
     break;
   default:
}
include 'common/dash_foot.html';

