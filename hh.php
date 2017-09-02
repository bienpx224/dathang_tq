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
$noper=1;require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');


//后台安全入口,可修改此文件名,越复杂越好,防止他人知道

//已登录
if($Xuserid&&$_COOKIE['manage_cookie']&&$_SESSION['entrance']=='xazy'){$url='/xingao/main.php';}else{$url='/xingao/index.php';}

if($_SESSION['entrance']=='xazy')
{
	echo '<script language=javascript>';
	echo 'location.href="'.$url.'";';
	echo '</script>';
	XAtsto($url);
}
$_SESSION['entrance']='xazy';
?>

<script>location.href='?';</script><!--刷新一次本页面,以清空缓存-->