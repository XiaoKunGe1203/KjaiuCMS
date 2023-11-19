<?php
$conn = new mysqli($sqlinfo['host'], $sqlinfo['username'], $sqlinfo['password'], $sqlinfo['dbname']);
if ($_GET['s'] == '/login') { 
    //页面输出
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $sql = "SELECT * FROM `dashboard_users` WHERE `username` = '".$_POST['username']."' AND `password` = '".$_POST['passwd']."'";
        $result = $conn->query($sql);
$conn->close();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
//密码正确
setcookie("uid",$row['id']);
setcookie("authentication",$row['authentication']);
header("Location: /dashboard");
exit;
}
}else {
    $error = '账户或密码错误';
}
    }
?>
<html lang="zh">
<head>
<meta charset="utf-8" />
<title>用户登录 - 控制面板</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
<meta content="Coderthemes" name="author" />
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-touch-fullscreen" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<meta name="full-screen" content="yes">
		<meta name="browsermode" content="application">
		<meta name="x5-fullscreen" content="true">
		<meta name="x5-page-mode" content="app">
<link href="style/login/css/icons.min.css" rel="stylesheet" type="text/css" />
<link href="style/login/css/app.min.css" rel="stylesheet" type="text/css" />
</head>

<body class="auth-fluid-pages pb-0">
<div class="auth-fluid"> 
  <!--Auth fluid left content -->
  <div class="auth-fluid-form-box">
    <div class="align-items-center d-flex h-100">
      <div class="card-body"> 
        
        <!-- Logo -->
        <div class="auth-brand text-center text-lg-left">
          <h3> 用户登录 - 控制面板 </h3>
        </div>
        <?php if(isset($error)){?>
          <div class="alert alert-danger alert-dismissible">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <strong>错误!</strong> <?php echo $error;?>
  </div>
<?php } ?>
        <!-- title-->
        <h4 class="mt-0">账户登录</h4>
        
        <!-- form -->
        <form action="login" method=post name="form1" class="needs-validation" novalidate>
          <div class="form-group">
            <label for="emailaddress">账号</label>
            <div class="invalid-feedback">请输入用户名！</div>
            <input class="form-control"  name="username" id="username"  required="" placeholder="账号">
          </div>
          <div class="form-group">
            <label for="password">密码</label>
            <div class="invalid-feedback">请输入密码！</div>
            <input class="form-control" type="password" name="passwd" required="" id="password" placeholder="密码">
          </div>
          <div class="form-group mb-3">
            <div>
             <a href="/register" ><label>注册</label></a>
              &nbsp;&nbsp;&nbsp;&nbsp;
              <a href=""><label>找回密码</label></a>
            </div>
          </div>
          <div class="form-group mb-0 text-center">
            <input type="submit" value="登陆" class="btn btn-primary btn-block"/>
          </div>
          <hr>
            <a href="/oauth?type=lty" class="btn btn-info" role="button">通过蓝天云登录</a>
            <a href="/oauth?type=qq" class="btn btn-info" role="button">通过QQ登录</a>
          <!-- social-->
        </form>
        <!-- end form--> 
        
      </div>
      <!-- end .card-body --> 
    </div>
    <!-- end .align-items-center.d-flex.h-100--> 
  </div>
  <!-- end auth-fluid-form-box--> 
  
  <!-- Auth fluid right content -->
  <div class="auth-fluid-right text-center">
    <div class="auth-user-testimonial">
      <h2 class="mb-3"> 用户登录 控制面板</h2>
    </div>
    <!-- end auth-user-testimonial--> 
  </div>
  <!-- end Auth fluid right content --> 
</div>
<!-- App js -->
<script>
// 如果验证不通过禁止提交表单
(function() {
  'use strict';
  window.addEventListener('load', function() {
    // 获取表单验证样式
    var forms = document.getElementsByClassName('needs-validation');
    // 循环并禁止提交
    var validation = Array.prototype.filter.call(forms, function(form) {
      form.addEventListener('submit', function(event) {
        if (form.checkValidity() === false) {
          event.preventDefault();
          event.stopPropagation();
        }else{
        swal({
        title: "登录请求已发送",
        text: '请等待刷新，如果超过1分钟没有结束请联系管理员',
        type: "success",
        showConfirmButton: false,
        timer: 3000
        });
        }
        form.classList.add('was-validated');
      }, false);
    });
  }, false);
})();
</script>
<script src="style/login/js/app.min.js"></script>
</body>
</html>
<?php
}
//页面输出结束

if ($_GET['s'] == '/register') { 
session_start();
//页面加载
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    if ($_POST['authcode'] ==  $_SESSION['authcode']){
//用户名重复检查
$sql = "SELECT * FROM `dashboard_users` WHERE `username` = '" . $_POST['username']. "'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
       $error = '用户名已存在';
    }
}else{

                        //获取ID
$sql = "select * from dashboard_users order by id desc limit 1;";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $newid = ($row["id"] + 1);
    }
}
//创建用户
$authentication = md5(time() . $_SERVER['HTTP_USER_AGENT'] . $_POST['username'] . $_POST['passwd'] . $newid . $_POST['authcode']);
$sql = "INSERT INTO `dashboard_users` (`id`, `username`, `password`, `nickname`, `userimg`, `group`, `qq`, `authentication`, `credit`) VALUES ('".$newid."', '" . $_POST['username'] . "', '" . $_POST['passwd'] . "', '" . $_POST['username'] . "', '/style/static/images/avatar.svg', '0', '0', '".$authentication."', '0');
";
$result = $conn->query($sql);
if ($result->num_rows > 0) {}else{
setcookie("uid",$newid);
setcookie("authentication",$authentication);
header("Location: /dashboard");
exit;
}

}
    }else{
        $error = '验证码错误';
    }
}
?>
<html lang="zh">
<head>
<meta charset="utf-8" />
<title>用户注册 - 控制面板</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
<meta content="Coderthemes" name="author" />
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-touch-fullscreen" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<meta name="full-screen" content="yes">
		<meta name="browsermode" content="application">
		<meta name="x5-fullscreen" content="true">
		<meta name="x5-page-mode" content="app">
<link href="style/login/css/icons.min.css" rel="stylesheet" type="text/css" />
<link href="style/login/css/app.min.css" rel="stylesheet" type="text/css" />
</head>

<body class="auth-fluid-pages pb-0">
<div class="auth-fluid"> 
  <!--Auth fluid left content -->
  <div class="auth-fluid-form-box">
    <div class="align-items-center d-flex h-100">
      <div class="card-body"> 
        
        <!-- Logo -->
        <div class="auth-brand text-center text-lg-left">
          <h3> 用户注册 - 控制面板 </h3>
        </div><?php if(isset($error)){?>
          <div class="alert alert-danger alert-dismissible">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <strong>错误!</strong> <?php echo $error;?>
  </div>
<?php } ?>
        <!-- form -->
        
        <form action="/register" method=post name="form1" class="needs-validation" novalidate>
          <div class="form-group">
            <label for="emailaddress">账号</label>
            <input class="form-control"  name="username" id="username"  required placeholder="账号">
            <div class="invalid-feedback">请输入用户名！</div>
          </div>
          <div class="form-group">
            <label for="password">密码</label>
            <input class="form-control" type="password" name="passwd" required id="password" placeholder="密码" >
            <div class="invalid-feedback">请输入密码！</div>
          </div>
          
          <div class="form-group mb-3">
            <label for="password">验证码</label>
            <input class="form-control" id="authcode" name="authcode" required placeholder="验证码"> 
            <div class="invalid-feedback">请输入验证码！</div><img src="style/captcha.php" class="pull-right" id="captcha" style="cursor: pointer;" onclick="this.src=this.src+'?d='+Math.random();" title="点击刷新" alt="captcha">
          </div>
           <div class="form-group form-check">
		<label class="form-check-label">
		  <input class="form-check-input" type="checkbox" name="tos" required> 同意<a href="//qxsh.tk?go=tos" >《幻心网络用户协议》</a>
		  <div class="valid-feedback">验证成功！</div>
		  <div class="invalid-feedback">同意协议才能提交。</div>
		</label>
	  </div>
          <div class="form-group mb-3">
            <div>
             <a href="/login" ><label>登录</label></a>
              &nbsp;&nbsp;&nbsp;&nbsp;
              <a href=""><label>找回密码</label></a>
            </div>
          </div>
          <div class="form-group mb-0 text-center">
            <input type="submit" value="注册" class="btn btn-primary btn-block"/>
          </div>
          
          <!-- social-->
        </form>
        <!-- end form--> 
        
      </div>
      <!-- end .card-body --> 
    </div>
    <!-- end .align-items-center.d-flex.h-100--> 
  </div>
  <!-- end auth-fluid-form-box--> 
  
  <!-- Auth fluid right content -->
  <div class="auth-fluid-right text-center">
    <div class="auth-user-testimonial">
      <h2 class="mb-3"> 用户注册 控制面板</h2>
    </div>
    <!-- end auth-user-testimonial--> 
  </div>
  <!-- end Auth fluid right content --> 
</div>
<!-- App js --> 
<script>
// 如果验证不通过禁止提交表单
(function() {
  'use strict';
  window.addEventListener('load', function() {
    // 获取表单验证样式
    var forms = document.getElementsByClassName('needs-validation');
    // 循环并禁止提交
    var validation = Array.prototype.filter.call(forms, function(form) {
      form.addEventListener('submit', function(event) {
        if (form.checkValidity() === false) {
          event.preventDefault();
          event.stopPropagation();
        }else{
        swal({
        title: "注册请求已发送",
        text: '请等待刷新，如果超过1分钟没有结束请联系管理员',
        type: "success",
        showConfirmButton: false,
        timer: 3000
        });
        }
        form.classList.add('was-validated');
      }, false);
    });
  }, false);
})();
</script>
<script src="style/login/js/app.min.js"></script>
</body>
</html>
<?php
}
