<?php
$conn = new mysqli($sqlinfo['host'], $sqlinfo['username'], $sqlinfo['password'], $sqlinfo['dbname']);
// API地址
$Oauth_config['apiurl'] = 'None';
// APPID
$Oauth_config['appid'] = 'None';
// APPKEY
$Oauth_config['appkey'] = 'None';
// 登录成功返回地址
$Oauth_config['callback'] = 'https://www.kjaiu.link/connect';
function get_curl($url){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.132 Safari/537.36");
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$ret = curl_exec($ch);
		curl_close($ch);
		return $ret;
		
	}
function ca($data){
global $conn;
//获取ID
$sql = "select * from dashboard_users order by id desc limit 1;";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $newid = ($row["id"] + 1);
    }
}
//创建用户
$authentication = md5(time() . $_SERVER['HTTP_USER_AGENT'] . $data['social_uid'] . $data['access_token'] . $newid . $data['nickname']);
$sql = "INSERT INTO `dashboard_users` (`id`, `username`, `password`, `nickname`, `userimg`, `group`, `qq`, `authentication`, `credit`) VALUES ('".$newid."', '" . $data['social_uid'] . "', '" . $data['access_token'] . "', '" . $data['nickname'] . "', '".$data['faceimg']."', '".$data['type']."', '0', '".$authentication."', '0');
";
$result = $conn->query($sql);
if ($result->num_rows > 0) {}else{
setcookie("uid",$newid);
setcookie("authentication",$authentication);
header("Location: /dashboard");
exit;
}
}
switch ($pages)
{
case '/index':
//主页
if($_GET['c'] == 'logout'){setcookie("uid",""); setcookie("authentication",""); header("Location: /login"); exit;}
?>
<!DOCTYPE html>
<html  lang='zh-CN'>
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1">
  <link rel="shortcut icon" href="assets/images/h-1.svg" type="image/x-icon">
  <meta name="description" content="幻心互联 - 让每个人感受到开服的乐趣,Kjaiu,Minecraft服务器。免费。永久。您自己的Minecraft服务器，唯一一个永远免费的服务器。">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-touch-fullscreen" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black">
  <meta name="full-screen" content="yes">
  <meta name="browsermode" content="application">
  <meta name="x5-fullscreen" content="true">
  <meta name="x5-page-mode" content="app">
  
  <title>幻心互联 - 让每个人感受到开服的乐趣</title>
  <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/bootstrap/css/bootstrap-grid.min.css">
  <link rel="stylesheet" href="assets/bootstrap/css/bootstrap-reboot.min.css">
  <link rel="stylesheet" href="assets/parallax/jarallax.css">
  <link rel="stylesheet" href="assets/web/assets/gdpr-plugin/gdpr-styles.css">
  <link rel="stylesheet" href="assets/dropdown/css/style.css">
  <link rel="stylesheet" href="assets/socicon/css/styles.css">
  <link rel="stylesheet" href="assets/theme/css/style.css">
  <link rel="preload" as="style" href="assets/mobirise/css/mbr-additional.css">
  <link rel="stylesheet" href="assets/mobirise/css/mbr-additional.css" type="text/css">

</head>
<body>
  
  <section data-bs-version="5.1" class="menu menu3 cid-sFAA5oUu2Y" once="menu" id="menu3-1">
    
    <nav class="navbar navbar-dropdown navbar-expand-lg">
        <div class="container-fluid">
            <div class="navbar-brand">
                
                <span class="navbar-caption-wrap"><a class="navbar-caption text-primary display-5" href="http://go.mcpuls.link/?go=home">幻心互联</a></span>
            </div>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-bs-toggle="collapse" data-target="#navbarSupportedContent" data-bs-target="#navbarSupportedContent" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <div class="hamburger">
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav nav-dropdown" data-app-modern-menu="true"><li class="nav-item"><a class="nav-link link text-primary display-7" href="index.html#header1-i">主页</a></li>
                    
                    <li class="nav-item"><a class="nav-link link text-primary show display-7" href="/create" aria-expanded="false">订购产品</a>
                    </li><li class="nav-item"><a class="nav-link link text-primary display-7" href="//qxsh.tk/?go=tos" target="_blank">TOS</a></li>
                    </ul>
                
                <div class="navbar-buttons mbr-section-btn"><a class="btn btn-primary display-4" href="/dashboard">控制台</a></div>
            </div>
        </div>
    </nav>
</section>

<section data-bs-version="5.1" class="header1 cid-sFCAOqBTxa mbr-fullscreen mbr-parallax-background" id="header1-i">

    

    <div class="mbr-overlay" style="opacity: 0.6; background-color: rgb(237, 245, 225);"></div>

    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-11">
                <h1 class="mbr-section-title mbr-fonts-style mb-3 display-1"><strong>幻心互联</strong></h1>
                
                <p class="mbr-text mbr-fonts-style display-7"><strong>
                    让每个人感受到开服的欢乐</strong><strong><br></strong></p>
                    
                
            </div>
        </div>
    </div>
</section>
<script src="assets/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/parallax/jarallax.js"></script> 
   <script src="assets/smoothscroll/smooth-scroll.js"></script> 
    <script src="assets/ytplayer/index.js"></script> 
     <script src="assets/dropdown/js/navbar-dropdown.js"></script> 
      <script src="assets/theme/js/script.js"></script>  
</body>
</html>
<?php
break;  
case '/oauth':
//第三方登录
$url = $Oauth_config['apiurl'] .'connect.php?act=login&appid='.$Oauth_config['appid'].'&appkey='.$Oauth_config['appkey'].'&type='.$_GET['type'].'&redirect_uri='.$Oauth_config['callback'];
if($_GET['type'] == 'lty'){
header("Location: https://lantian.pro/loginAccessToken?redirect_url=".$Oauth_config['callback']."?type=lty");
}
if($_GET['type']  == 'qq'){
header("Location: ".json_decode(get_curl($url),true)['url']);
}
break;
case '/connect':
if($_GET['type']  == 'lty'){
echo '蓝天云登录已停用';
}
$url = $Oauth_config['apiurl'] .'connect.php?act=callback&appid='.$Oauth_config['appid'].'&appkey='.$Oauth_config['appkey'].'&type='.$_GET['type'].'&code='.$_GET['code'];
//第三方登录回调
if($_GET['type']  == 'qq'){
$retjson = json_decode(get_curl($url),true);
$sql = "SELECT * FROM `dashboard_users` WHERE `username` = '" . $retjson['social_uid']. "'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
       setcookie("uid",$row['id']);
       setcookie("authentication",$row['authentication']);
       header("Location: /dashboard");
    }
}else {
if ($retjson['code'] == 0){
ca($retjson);}else{echo '凭据已过期请重新授权';}
    }
}
break;
default:
  //表达式的值不等于任何时执行的代码;
}
