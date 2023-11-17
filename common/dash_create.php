<?php
$conn = new mysqli($sqlinfo['host'], $sqlinfo['username'], $sqlinfo['password'], $sqlinfo['dbname']);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['authcode'] ==  $_SESSION['authcode']){
        $cdata['uid']  = $userinfo['id'];
        $cdata['nickname']  = $userinfo['nickname'];
        include 'common/pt.php';
        $pt = pt_ca($cdata);
        if($pt['msg'] == '-1'){
            $error = $pt['data'];
        }elseif ($pt['msg'] == '0') {
            $newid = '0';
            $sql = "select * from orders order by id desc limit 1;";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $newid = ($row["id"] + 1);
                }
            }
            $ssql = "INSERT INTO `orders` (`id`, `uid`, `stoptime`, `sid`) VALUES ('" . $newid . "', '" . $userinfo['id'] ."', '" . strtotime('+1 days', time()) . "' , '".$pt['sid']."' );";
            $conn->query($ssql);
            $ok = '服务器创建成功';

            
        }else {
            $error = '未知错误';
        }
    }else {
        $error = '验证码错误';
    }
    }
?>
<div class="main-content">
	<section class="section">
		<div class="section-header">
			<h1> 创建服务器</h1>
			<div class="section-header-breadcrumb">
			</div>
		</div>
		<?php if(isset($error)){?>
          <div class="alert alert-danger alert-dismissible">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <strong>错误!</strong> <?php echo $error;?>
  </div>
<?php } ?>
		<?php if(isset($ok)){?>
          <div class="alert alert-success">
    <strong>成功!</strong> <?php echo $ok;?>
  </div>
<?php } ?>
		<div class="row">
			<div class="col-xs-12">
			<form  action="/create" method=post name="form1" class="needs-validation" novalidate >
				<div class="card-box">
					<div class="section-title" style="margin-top: 0;">配置信息</div>
					<div class="row">

            <!--<label><input type="radio" name="optradio">Option 1</label> -->
            <ul>
                <li>2 核 4GB</li>
                <li>6Mbps</li>
                <li>免费存储容量 4GiB</li>
                <li>建议 10 人游玩</li>
            </ul>
					</div>
					<div class="section-title" style="margin-top: 0;">验证信息</div>
					<div class="row">
            <ul>
            <div class="form-group mb-3">
            <p>验证码图片：
            <img src="style/captcha.php" class="pull-right" id="captcha" style="cursor: pointer;" onclick="this.src=this.src+'?d='+Math.random();" title="点击刷新" alt="captcha">
            </p>
            <input class="form-control" id="authcode" name="authcode" required placeholder="验证码">
            <div class="invalid-feedback">请输入验证码！</div>
            </div>
		  <div class="form-group mb-0 text-center">
            <input type="submit" value="立即创建" class="btn btn-primary btn-block"/>
          </div>
            </ul>
            
            
                   
					</div>
				</div>
				  </form>
			</div>
		</div>


		<script>
			nodeform.domain.focus();
		</script>
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
        title: "创建服务器请求已发送",
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
