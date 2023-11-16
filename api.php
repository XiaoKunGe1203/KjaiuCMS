<?php
include 'config.php';
$conn = new mysqli($sqlinfo['host'], $sqlinfo['username'], $sqlinfo['password'], $sqlinfo['dbname']);
switch ($_GET['rp'])
{
case 'datalist':
$sql = "select * from dashboard_users order by id desc limit 1;";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $usermax = $row['id'];
    }
}
$sql = "select * from orders order by id desc limit 1;";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $servermax = $row['sid'];
    }
}
$result = array(
    'code' => 0,
    'msg' => 'ok',
    'maxuser' => $usermax,
    'maxserver' => $servermax
    );
break;  
default:
$result = array(
    'code' => -1,
    'msg' => 'error'
    );
}
$conn->close();
header('Content-Type: application/json');
echo json_encode($result);
?>