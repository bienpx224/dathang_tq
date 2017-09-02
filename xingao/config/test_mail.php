<?php
/*
软件著作权：=====================================================
软件名称：兴奥国际物流转运网站管理系统(简称：兴奥转运系统)V7.0
著作权人：广西兴奥网络科技有限责任公司
软件登记号：2016SR041223
网址：www.xingaowl.com
本系统已在中华人民共和国国家版权局注册，著作权受法律及国际公约保护！
版权所有，未购买严禁使用，未经书面许可严禁开发衍生品，违反将追究法律责任！
*/
require_once($_SERVER['DOCUMENT_ROOT'].'/public/config_top.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/public/html.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/public/function.php');
$pervar='manage_sy';require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');
$headtitle="系统配置";$alonepage=1;
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/head.php');
?>
<br><br><br><br>

<div class="alert alert-block alert-info fade in alert_cs col-md-9">
  <h4 class="alert-heading">邮件发送测试</h4>
  <p>
  	<?php 
	//邮箱接口配置
	$smtp_server=add($_GET['smtp_server']);
	$smtp_secure=add($_GET['smtp_secure']);
	$smtp_port=add($_GET['smtp_port']);
	$smtp_name=add($_GET['smtp_name']);
	$smtp_mail=add($_GET['smtp_mail']);
	$smtp_password=add($_GET['smtp_password']);
	$test_mail=par($_GET['test_mail']);
	
	if($test_mail)
	{
		SendMail($test_mail,$title='恭喜:您的邮件设置已正确',$content='此邮件为系统设置发送测试',$file='',$issys=1,$xs=1);
		echo '<br><br>如果没有提示错误说明已发送过去<br>请登录'.$test_mail.'查看是否收到邮件,如收到说明设置成功';
	}
	else
	{
		echo '请填写接收的邮箱账号';
	}
	
	?>
  </p>
  <p><br>
 <a class="btn btn-danger" href="#" onClick="window.opener=null;window.open('','_self');window.close();" >关闭页面</a></p>
</div>
