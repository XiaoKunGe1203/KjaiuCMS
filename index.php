<?php
/*
nginx伪静态
location / {
	if (!-e $request_filename){
		rewrite  ^(.*)$  /index.php?s=$1  last;   break;
	}
}
*/
include 'config.php';

$conn = new mysqli($sqlinfo['host'], $sqlinfo['username'], $sqlinfo['password'], $sqlinfo['dbname']);
    // SQL链接测试
if ($conn->connect_error) {
    $error = "无法链接到数据库";
    exit;
}

if(isset($_GET['s'])){
    $pages = $_GET['s'];
}else{
    $pages = '/index';
}
$sql = "SELECT * FROM `HomePages` WHERE `s` LIKE '". $pages ."'";
$result = $conn->query($sql);
$conn->close();
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();  
    require $row['www'] .'/'. $row['filename'];
} else {
      // 如果文件不存在，输出错误消息
      $error = '文件不存在';
      $ecode = '404';
      require 'error.php';
} 
/*
$sql = "UPDATE `dashboard_users` SET `authentication` = '". $authentication ."' WHERE `dashboard_users`.`id` = 0;
";
phpinfo(); */
