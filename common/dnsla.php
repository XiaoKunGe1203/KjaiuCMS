<?php
function dnsla_ca($data){
    // 生成Basic令牌  
$token = base64_encode('None:None');  
// 要添加解析记录的域名ID和其他参数  
$domainId = $data['domainId'];  
$type = $data['type']; 
$host = $data['host'];  
$domaindata = $data['data'];  
// 构建请求数据  
$recordData = array(  
    'domainId' => $domainId,  
    'type' => $type,  
    'host' => $host,  
    'data' => $domaindata,  
    'ttl' => 600
);  
$jsonData = json_encode($recordData);  
  
// 发起POST请求  
$url = 'https://api.dns.la/api/record';  
$ch = curl_init();  
curl_setopt($ch, CURLOPT_URL, $url);  
curl_setopt($ch, CURLOPT_HTTPHEADER, array(  
    'Authorization: Basic ' . $token,  
    'Content-Type: application/json; charset=utf-8'  
));  
curl_setopt($ch, CURLOPT_POST, 1);  
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);  
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
$response = curl_exec($ch);  
curl_close($ch);  
  
// 处理响应结果  
$result = json_decode($response, true);  
if ($result['code'] == 200) {  
    $return = array('msg' => 0, 'data' => $result['data']['id'], 'response' => $response);
} else {  
        $return = array('msg' => -1, 'data' => $data['msg'], 'response' => $response);
}  
    return $return;
}
function dnsla_ds($data){
    // 生成Basic令牌  
$token = base64_encode('None:None');  
// 设置请求URL和请求选项  
$url = 'https://api.dns.la/api/record?id='.$data['subdomainid'];  
$options = array(  
    CURLOPT_URL => $url,  
    CURLOPT_HTTPHEADER => array(  
        'Authorization: Basic ' . $token,  
        'Content-Type: application/json'  
    ),  
    CURLOPT_RETURNTRANSFER => true,  
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,  
    CURLOPT_CUSTOMREQUEST => 'DELETE'  
);  
  
// 创建CURL资源并发送请求  
$ch = curl_init();  
curl_setopt_array($ch, $options);  
$response = curl_exec($ch);  
// 关闭CURL资源  
curl_close($ch);  
// 处理响应结果  
$result = json_decode($response, true);  
if ($result['code'] == 200) {  
    $return = array('msg' => '0', 'data' => $result['data']['id'], 'response' => $response);
} else {  
    $return = array('msg' => '-1', 'data' => $result['msg'], 'response' => $response);
}  
    return $return;
}
?>
