<?php
$conn = new mysqli($sqlinfo['host'], $sqlinfo['username'], $sqlinfo['password'], $sqlinfo['dbname']);
$sql = "SELECT * FROM `orders` WHERE `uid` = " . $userinfo['id'];
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) { 
        $orders  = $row;
    }
    $orderdata = '<span>到期时间：'.date('Y-m-d H:i:s', $orders['stoptime']).' </span>';
}else {
    $orderdata = '你还没有创建服务器';
}
$sqldomain = "SELECT * FROM `subdomains` WHERE `uid` = " . $userinfo['id'];
$result = $conn->query($sqldomain);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) { 
        $domains  = $row;
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if($_GET['c'] == 'renew'){
     if ($_POST['authcode'] ==  $_SESSION['authcode']){
        $diff = abs($orders['stoptime'] - time());  
        $days = floor($diff / (60 * 60 * 24));  
        if($days< '3'){ 
        $sql = "UPDATE `orders` SET `stoptime` = '".strtotime('+7 days', time())."' WHERE `orders`.`id` = ".$orders['id'].';';
        $result = $conn->query($sql);
         $ok = '续时成功';
         $orders['stoptime'] = strtotime('+7 days', time());
        }else {
        $error = '请在到期前3天续时';
        }
         
     }else{ 
         $error = '验证码错误';
         
     }
    }
}
if($_GET['c'] == 'domain'){
include 'common/dnsla.php';
$dsql = "SELECT * FROM `subdomains` WHERE `uid` = " . $userinfo['id'];
$result = $conn->query($dsql);
if ($result->num_rows > 0) {
//删除
$data['subdomainid'] = $domains['subdomainid'];
$dnsla = dnsla_ds($data);
    if($dnsla['msg'] == 0){
        $ssql = "DELETE FROM subdomains WHERE `subdomains`.`id` = ".$domains['id'];
        $conn->query($ssql);
         exit("<script language='javascript'>window.location.href='/dashboard';</script>");
}else{
        $error = $dnsla['data'];
    }
}else{
if($_POST['domain']=='qzweb'){
    $domainId = '98420387423196160';
    $domain = '.qzweb.top';
}
switch ($_POST['valuetype']) {
   case "kjaiu":
     $value = 'free1.mcpuls.link';
     break;
   case "wemcs2":
     $value = "s2.wemc.cc";
     break;
   case "lty":
     $value = "cname.lantiangw.fun";
     break;
   case "jhh":
    $value = 'play.simpfun.cn';
     break;
   default:
     $error = '未知的服务商';
     break;
}
switch($_POST['proto']){
   case "cname":
     $dantype = 5;
     $dandata = $value;
     $danhost = $_POST['subdomain'];
     $port = '0';
     break;
   case "srv":
     $dantype = 33;
     $dandata = '5 0 ' . $_POST['port'] .' '. $value. '.';
     $danhost = '_minecraft._tcp.'.$_POST['subdomain'];
     $port = $_POST['port'];
     break;
   default:
     $error = '未知的解析类型';
     break;
}
$esql = "SELECT * FROM `subdomains` WHERE `subdomainText` = '".$danhost."' AND `domain` = '".$domain."'";
$result = $conn->query($esql);
if ($result->num_rows > 0) {
    $error = '前缀已经存在';
}else{
        $data = array('host' => $danhost, 'type' => $dantype , 'domainId' => $domainId , 'data' => $dandata);
        $dnsla = dnsla_ca($data);
        if($dnsla['msg'] == 0){
        $csql = "select * from subdomains order by id desc limit 1;";
            $result = $conn->query($csql);
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $newid = ($row["id"] + 1);
                }
            }
        $ssql = "INSERT INTO `subdomains` (`id`, `uid`, `subdomainid`, `subdomainText`, `value`, `type`, `SrvPort`, `domain`) VALUES ('".$newid."', '".$userinfo['id']."', '".$dnsla['data']."', '".$_POST['subdomain']."', '".$value."', '".$_POST['proto']."', '". $port."', '".$domain."');";
        $conn->query($ssql);
 exit("<script language='javascript'>window.location.href='/dashboard';</script>");
        }else{
        $error = $dnsla['data'];
    }
    }
}
}
?>
<style type="text/css">
							div.row {  
    word-break: break-all;  
}
	@charset "utf-8";
	
	.jqm-round-wrap {
		display: block;
		position: relative;
		width: 100px;
		height: 100px;
		overflow: hidden;
		border-radius: 65px;
		-webkit-border-radius: 65px
	}
	
	.jqm-round-sector {
		position: absolute;
		width: 100px;
		height: 100px
	}
	
	.jqm-round-bg {
		position: absolute;
		width: 100px;
		height: 100px;
		background-color: rgb(103, 119, 239);
		border-radius: 65px;
		-webkit-border-radius: 65px
	}
	
	.jqm-round-circle {
		position: absolute;
		background-color: #fff;
		width: 94px;
		height: 94px;
		left: 2.5px;
		top: 2.5px;
		z-index: 10;
		border-radius: 60px;
		-webkit-border-radius: 60px
	}
	
	.jqm-round-circle p {
		font-size: 14px;
		line-height: 94px;
		margin: 0;
		text-align: center;
		width: 100%;
		font-weight: 700
	}
	.jiaZai{
			display: inline-block;
			height: 130px;
			margin-left: 40px;
	}
</style>
<script>
function pt(){
window.open(
 "http://pt.aikeln.tk:48100/",
 "_blank",
 "top=300,left=300,width=800,height=500,menubar=no,toolbar=no,status=no,scrollbars=yes"
);
}
function vts() {    
  // 获取选中的选项    
  var selectedOption = document.getElementById("valuetype").options[document.getElementById("valuetype").selectedIndex];    
  var selectedOptiontype = document.getElementById("proto").options[document.getElementById("proto").selectedIndex];    
      
  // 获取选项的value值    
  var proto = selectedOptiontype.value;  
  var value = selectedOption.value;   
 va = ''; 
  switch (value) {  
          case 'jhh':  
        va = 'play.simpfun.cn';  
      document.getElementById('jxz').innerHTML = '<input type="text" id="valuedata" name="valuedata" class="form-control" value="'+ va +'" readonly="readonly">';  
      break;    
    case 'lty':  
      va = 'cname.lantiangw.fun';  
      document.getElementById('jxz').innerHTML = '<input type="text" id="valuedata" class="form-control" value="'+ va +'" name="valuedata" readonly="readonly">';  
      break;    
    case 'wemcs2':  
      va = 's2.wemc.cc';  
      document.getElementById('jxz').innerHTML = '<input type="text" id="valuedata" class="form-control" value="'+ va +'" name="valuedata" readonly="readonly">';  
      break;    
    case 'kjaiu':  
      va = 'free1.mcpuls.link';  
      document.getElementById('jxz').innerHTML = '<input type="text" id="valuedata" class="form-control" value="'+ va +'" name="valuedata" readonly="readonly">';  
      break;    
    case 'qing': 
      va = '';  
      document.getElementById('jxz').innerHTML = '<input type="text" id="valuedata" class="form-control" value="'+ va +'" name="valuedata" readonly="readonly" placeholder="如：1.2.3.4">';  
      break;    
    default:  
      va = '';  
      document.getElementById('jxz').innerHTML = '<input type=\'text\' class="form-control" value=\''+va+'\' readonly=\'readonly\' placeholder=\'请选择...\'>';  
      break;    
  }  
  if(proto =='srv'){  
    document.getElementById('Vtype').innerHTML = '<label for="inputEmail3" class="col-sm-3 form-control-label">端口号</label><div class="col-sm-9"><input type="number" name="port" type=\'text\' size=\'40\' class="form-control" name=\'port\' id=\'port\' required placeholder="1-65535"/><div class="invalid-feedback">请输入端口号！</div></div>';    
}else{  
    document.getElementById('Vtype').innerHTML = '';    
}
}
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
        title: "服务请求已发送",
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

      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1><?php echo $pageinfo['pagename'];?></h1>
            <div class="section-header-breadcrumb">
            </div>
          </div>
		<?php if(isset($error)){?>
          <div class="alert alert-danger">
            <strong>错误!</strong> <?php echo $error;?>
            </div>
        <?php } 
        if(isset($ok)){?>
          <div class="alert alert-success">
            <strong>成功!</strong> <?php echo $ok;?>
            </div>
        <?php } ?>
                <div class="row">

                       <div class="col-xs-12 col-md-6 col-lg-6 col-xl-3">
                        <div class="card-box tilebox-two">
                            <h4 class="text-muted text-uppercase m-b-15">个人信息</h4>
                            UID <?php echo $userinfo['id']?>
                            <hr>
                                                    <h4 class="text-muted text-uppercase m-b-15">服务器信息</h4>
<?php echo $orderdata;?>
                        </div>
                        <div class="card-box tilebox-two">
                            

                            <h6 class="text-muted text-uppercase m-b-15">实例控制台登录信息
</h6>
							  <div style="height:10px;"></div>
							  <span>
							    面板地址	<a href=""onclick="pt()">http://pt.aikeln.tk:48100/</a><br>
                                用户名	<?php echo $userinfo['id'];?>@kjaiu.link<br>
                                初始密码	Kjaiu#<?php echo $userinfo['id'];?><br>
                                </span>

							
                        </div>
                        <?php if(isset($orders)){?>
                        <form  action="/dashboard?c=renew" method=post name="form1" class="needs-validation" novalidate >
                        <div class="card-box tilebox-two">
                            <h4 class="text-muted text-uppercase m-b-15">续时服务器</h4>
                                    <div class="form-group mb-3">
            <p>验证码图片：
            <img src="style/captcha.php" class="pull-right" id="captcha" style="cursor: pointer;" onclick="this.src=this.src+'?d='+Math.random();" title="点击刷新" alt="captcha">
            </p>
            <input class="form-control" id="authcode" name="authcode" required placeholder="验证码">
            <div class="invalid-feedback">请输入验证码！</div>
            </div>
		  <div class="form-group mb-0 text-center">
            <input type="submit" value="立即续时" class="btn btn-primary btn-block"/>
          </div>
                        </div>
						</form>
						<?php }?>

						</div>
						
						<div class="col-xs-12 col-md-6 col-lg-6 col-xl-9">
					   <div>
					   
                        <div class="card-box">
                         <section class="icon-list-demo">
						 
						<div class="section-title" style="margin-top: 0;"> <h2>欢迎使用 幻心互联</h2></div>
						<div class="row"  style="word-break: break-all;">
	<div class="col-sm-6 col-md-4 col-lg-4 col-xl-3 col-xs-6">
<p>幻心互联 已正式开启公测，如果在公测过程中发现任何BUG请立即联系群内管理员。<br>幻心互联官方QQ讨论交流群<code>872939133</code>欢迎加入。</p>
具体请前往<a href="//qxsh.tk/?go=wiki"  target="_blank">Wiki</a></li>
						 </div>
						 </div>

						 </section>
						</div>
						                        <div class="card-box">
                         <section class="icon-list-demo">
						 
						<div class="section-title" style="margin-top: 0;"> <h2>域名管理</h2></div>
						<div class="row">
						<div class="col-sm-6 col-md-4 col-lg-4 col-xl-6 col-xs-6">
						<?php if(isset($domains['subdomainText'])){?>
						你的域名：<?php echo $domains['subdomainText'].$domains['domain'];?><br>
						记录类型：<?php echo $domains['type'];?><br>
						记录值：<?php echo $domains['value'];?><br>
						SRV端口：<?php if($domains['type'] == 'srv'){ echo $domains['SrvPort']; } ?><br>
                        删除当前的域名后，该域名将无法继续使用，不过你可重新创建一个域名。
                        <a href="?c=domain"><button class="btn btn-danger">删除</button></a>
                        <?php } else{?>
								<form name='nodeform' action="dashboard?c=domain" method="post" class="needs-validation" novalidate>
									<div class="form-group row">
										<label for="inputEmail3" class="col-sm-3 form-control-label">域名</label>
										<div class="col-sm-9">
											<input type='text' size='40' class="form-control" name='subdomain' id='subdomain' 
											 placeholder="前缀 如：mc" required />
											 <div class="invalid-feedback">请输入前缀！</div>
											 <select name="domain" id="domain"  class="form-control">
												<option value="qzweb">.qzweb.top</option>
												</select>
                                        </div>
									</div>
									<div class="form-group row">
										<label for="inputPassword3" class="col-sm-3 form-control-label">解析服务商</label>
										<div class="col-sm-9">
								        <select onchange="vts()" name="valuetype" id="valuetype"  class="form-control">
												<option value="qing">请选择</option>
												<option value="lty">蓝天云</option>
												<option value="jhh">简幻欢</option>
												<option value="wemcs2">WEMC-S2</option>
												<option value="kjaiu">幻心互联</option>
												</select>
										</div>
									</div>
									<div class="form-group row">
										<label for="inputPassword4" class="col-sm-3 form-control-label">解析类型</label>
										<div class="col-sm-9">
								        <select onchange="vts()" name="proto" id="proto"  class="form-control">
												<option value="cname">CNAME</option>
												<option value="srv">SRV</option>
												</select>
										</div>
									</div>
									<div class="form-group row">
										<label for="inputEmail3" class="col-sm-3 form-control-label">解析值</label>
										<div id="jxz" class="col-sm-9">
											<input type='text' size='40' class="form-control" name='value' id='value' placeholder="如：1.2.3.4" readonly="readonly"required />
                                        </div>
									</div>
									<div id="Vtype" class="form-group row">
									</div>
									<div class="form-group row">
										<label for="inputPassword3" class="col-sm-2 form-control-label">&nbsp;</label>
										<div class="col-sm-10">
											<input type="submit" id='button' class="btn btn-primary" value="确定" />
										</div>
									</div>
								</form>
                        <?php }?>
                        </div>
						 </div>

						 </section>
						</div>
					   </div>	
					   <div>
					   </div>
					   </div>
					
				</div>
<div style="height:50px;"></div>
