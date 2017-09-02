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
$pervar='daigou_inStorage';require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');
$headtitle="代购";
$alonepage=1;//单页形式
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/head.php');

if(!$ON_daigou){ exit("<script>alert('{$LG['daigou.45']}');goBack('uc');</script>"); }

//获取,处理
$typ=par($_REQUEST['typ']);
$dgid=spr($_REQUEST['dgid']);
$callFrom='manage';//member

$rs=FeData('daigou','*',"dgid='{$dgid}'");
if(!$rs['dgid']){	exit ("<script>alert('未找到可入库代购单');goBack('c');</script>");	}
if(have(spr($rs['status']),'9,10')){exit ("<script>alert('该代购单处于【'".daigou_Status(spr($rs['status']))."'】,不能再操作！');goBack('c');</script>");}

require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/daigou/call/inStorage_save.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/daigou/call/inStorage_form.php');

require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/foot.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/js/daigouJS.php');
?>
