<?php
function pt_fz($cdata){
$api_key = 'None';
    // 构造请求数据
    $data = array(
        'status' => 'suspended'
    );
    // 发送请求
    $ch = curl_init('http://pt.aikeln.tk:48100/api/application/servers/' . $cdata['sid'] . '/suspend');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Authorization: Bearer ' . $api_key,
        'Content-Type: application/json',
        'Accept: Application/vnd.pterodactyl.v1+json'
    ));
    $response = curl_exec($ch);
    curl_close($ch);
    
    // 处理响应
    $response_data = json_decode($response, true);
    if (isset($response_data['errors'])) {
       $fanhui = array('msg' => '-1', 'data' => '冻结服务器失败：' . $response_data['errors'][0]['detail']);
    } else {
       $fanhui = array('msg' => '0', 'data' => '冻结服务器成功');
    }
    return $fanhui;
}
include 'config.php';
$conn = new mysqli($sqlinfo['host'], $sqlinfo['username'], $sqlinfo['password'], $sqlinfo['dbname']);
//执行语句
$sql = "SELECT * FROM `orders`";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
    if (time() > $row['stoptime']) {  
      $data['sid'] = $row['sid'];
      $pt = pt_fz($data);
            if($pt['msg'] == '0'){
        $ssql = "DELETE FROM orders WHERE `orders`.`id` = " . $row['id'];
        $conn->query($ssql);
        echo "服务器".$row['id'] ."已冻结";
        echo "\r\n";
            }elseif ($pt['msg'] == '-1') {
        echo $pt['data'];
      }
        } else {  
        echo "服务器" .$row['id']."未过期";  
        echo "\r\n";
        }  
    }
} 
?>

