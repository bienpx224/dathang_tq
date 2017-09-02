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
$pervar='daigou';require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/session.php');
if(!$ON_daigou){ exit("<script>alert('{$LG['daigou.45']}');goBack('c');</script>"); }

//----------------处理数据下运单----------------

//获取,处理
$typ=par($_REQUEST['typ']);//1多个发货;0单个发货
if($typ){$goid=par($_SESSION['goid']);}else{$goid=par(ToStr($_REQUEST['goid']));}
if(!$goid){exit("<script>alert('{$LG['daigou.187']}');goBack('c');</script>");}

if($typ){
	//多个发货
	daigou_deliveryCHK($goid);//验证可发货
	daigou_deliveryLimit($goid,1);
}else{
	//单个发货
	daigou_getGdid($goid);//自动获取同收件人所有商品
}


if($goid)
{
	$url="/xamember/yundan/form.php?addSource=7&goid={$goid}";
	echo '<script language=javascript>';
	echo 'location.href="'.$url.'";';
	echo '</script>';
	XAtsto($url);
}