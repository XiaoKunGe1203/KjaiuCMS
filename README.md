# KjaiuSystem http://www.kjaiu.link
这个东西准备年初开源的 但是这几天说我系统怎么怎么样 那么就提前开源了

***

## 如何安装
### 注意：
目前没有后台管理界面，如果要求高请自行前往www.idcsmart.com或www.apayun.com！！！<br>
### 安装步骤
运行环境要求：PHP7.2或以上  Mysql5.6或5.7<br>
所有代码传到网站根目录<br>
导入根目录的app.sql文件<br>
### 伪静态设置
nginx用户：
```   
location / {
	if (!-e $request_filename){
		rewrite  ^(.*)$  /index.php?s=$1  last;   break;
	}
}
```

### 配置数据库
修改数据库配置文件config.php<br>

***

## License
This project is licensed under the Mozilla public license - see the  [LICENSE](https://github.com/XiaoKunGe1203/KjaiuSystem/blob/main/LICENSE)[LICENSE_Chinese_Reference](https://github.com/XiaoKunGe1203/KjaiuSystem/blob/main/LICENSE_Chinese_Reference) file for details.<br>
除非适用法律要求或书面同意，否则根据许可分发的软件将按“原样”分发，没有任何明示或暗示的保证或条件。有关许可下的特定语言管理权限和限制，请参阅许可。
