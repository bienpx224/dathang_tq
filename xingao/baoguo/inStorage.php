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
$pervar='baoguo_ad';require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');
$headtitle="扫描入库";
$alonepage=1;//1单页形式;2框架形式
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/head.php');


$lx=par($_POST['lx']);
$bgid=par($_REQUEST['bgid']);
$alert_color='info';

$rs=FeData('baoguo','*',"bgid='{$bgid}'");
if(!$rs['bgid']){exit ("<script>alert('未找到可入库包裹');goBack('c');</script>");}
if(have(spr($rs['status']),'2,3,4,10')){exit ("<script>alert('该包裹处于【'".baoguo_Status(spr($rs['status']))."'】,不能再操作！');goBack('c');</script>");}


require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/baoguo/call/inStorage_save.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/baoguo/call/inStorage_form.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/foot.php');
?>
