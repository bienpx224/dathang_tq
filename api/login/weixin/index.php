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

if($ON_demo)
{
	exit("<script>alert('当前是演示模式，未开通该登录方式！');goBack('c');</script>");
}

//微信登录
$_SESSION['api_weixin']=md5(uniqid(rand(), TRUE));//生成唯一随机串防CSRF攻击
$redirect_uri=urlencode($siteurl.'api/login/weixin/return.php');

$url = "https://open.weixin.qq.com/connect/qrconnect?appid={$connect_weixinid}&redirect_uri={$redirect_uri}&response_type=code&scope=snsapi_login&state={$_SESSION['api_weixin']}#wechat_redirect";

echo '<script language=javascript>';
echo 'location.href="'.$url.'";';
echo '</script>';
XAtsto($url);
?>
