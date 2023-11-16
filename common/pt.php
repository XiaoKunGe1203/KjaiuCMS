<?php 
function pt_ca($cdata){

// Pterodactyl API 基本信息
$api_url = 'http://pt.aikeln.tk:48100/api/application';
$api_key = 'None';

$nickname = $cdata['nickname'];
$username = 'uid'.$cdata['uid'];
$node = '1';

// 构造请求数据
$data = array(
    'username' => 'UID'.$cdata['uid'],
    'first_name'=> $nickname .'first',
    'last_name'=> $nickname .'last',
    'email' => $cdata['uid'] . '@kjaiu.link',
    'password' => 'Kjaiu#'.$cdata['uid'],
    'language' => 'zh',
    'root_admin' => false
);

// 发送请求
$ch = curl_init($api_url . '/users');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
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
    $result = array('msg' => '-1', 'data' => '创建账户失败：' . $response_data['errors'][0]['detail']);
} else {
    $result = array('msg' => '0', 'data' => '创建账户成功');
}



//获取用户id
// 发送请求
$ch = curl_init($api_url . '/users?page=2');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Authorization: Bearer ' . $api_key,
    'Content-Type: application/json',
    'Accept: Application/vnd.pterodactyl.v1+json'
));
$response = curl_exec($ch);
curl_close($ch);

// 处理响应
$userid = '0';
$response_data = json_decode($response, true);
if (isset($response_data['data'])) {
    foreach ($response_data['data'] as $user) {
        if ($user['attributes']['username'] == $username) {
            $userid = $user['attributes']['id'];
            break;
        }
    }
}

if ($userid > 0) {

} elseif ($userid == 0) {

//获取用户id
// 发送请求
$ch = curl_init($api_url . '/users?page=1');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Authorization: Bearer ' . $api_key,
    'Content-Type: application/json',
    'Accept: Application/vnd.pterodactyl.v1+json'
));
$response = curl_exec($ch);
curl_close($ch);

// 处理响应
$userid = '0';
$response_data = json_decode($response, true);
if (isset($response_data['data'])) {
    foreach ($response_data['data'] as $user) {
        if ($user['attributes']['username'] == $username) {
            $userid = $user['attributes']['id'];
            break;
        }
    }
}
} else {
    die('Unknown status');
}

// 获取可用端口范围
$page = 1;

$url = "{$api_url}/nodes/{$node}/allocations?page={$page}";
$headers = [
    'Accept: application/json',
    'Content-Type: application/json',
    "Authorization: Bearer {$api_key}",
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_COOKIEFILE, 'pterodactyl_session=your_session_cookie');
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$data = json_decode($response, true);
$allocations = $data['data'];

$unassigned_allocation_ids = [];
foreach ($allocations as $allocation) {
    if (!$allocation['attributes']['assigned']) {
        $unassigned_allocation_ids[] = $allocation['attributes']['id'];
    }
}

while (empty($unassigned_allocation_ids)) {
    $page++;
    $url = "{$api_url}/nodes/{$node}/allocations?page={$page}";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_COOKIEFILE, 'pterodactyl_session=your_session_cookie');
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $data = json_decode($response, true);
    $allocations = $data['data'];

    foreach ($allocations as $allocation) {
        if (!$allocation['attributes']['assigned']) {
            $unassigned_allocation_ids[] = $allocation['attributes']['id'];
        }
    }
}

$selected_allocation_id = $unassigned_allocation_ids[0];
// 构造请求数据
$data = array(
    'name' => 'KjaiuServer',
    'user' => $userid,
    'external_id' => $cdata['uid'],
    'egg' => '1',
    'docker_image' => 'kjaiu:8j9',
    'startup' => 'Kjaiu.sh',
    'limits' => array(
    'cpu' => '200',
    'memory' => '4096',
    'disk' => '4096',
    'io' => 500,
    'swap' => '4096',
    ),
    'threads' => null,
    'environment' => array(
    'SERVER_JARFILE' => 'server.jar',
    ),
    'feature_limits' => array(
        'databases' => '0',
        'allocations' => '1',
        'backups' => '0',
    ),
    'allocation' => array(
        'default' => $selected_allocation_id,
    ),
    'skip_scripts' => false,
    'oom_disabled' => false,
    'swap_disabled' => false
);

// 发送请求
$ch = curl_init($api_url . '/servers');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
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
echo $response_data['attributes'][0]['id'];
if (isset($response_data['errors'])) {
    $result = array('msg' => '-1', 'data' => '创建服务器失败：' . $response_data['errors'][0]['detail']);
} else {
    $result = array('msg' => '0', 'sid' => $response_data['attributes']['id'] , 'data' => '创建服务器成功' , 'response' => $response);
}
// 返回 JSON 数据
return $result;
}
?>