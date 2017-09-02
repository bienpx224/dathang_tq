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
$alonepage=1;require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/head.php');
if(!$ON_daigou){ exit("<script>alert('{$LG['daigou.45']}');goBack('uc');</script>"); }

//获取,处理
$typ=par($_REQUEST['typ']);
$tmp=par($_REQUEST['tmp']);
$smt=spr($_REQUEST['smt']);
$dgid=spr($_REQUEST['dgid']);
$goid=par(ToStr($_REQUEST['goid']));
//前/后配置
$groupid=$Mgroupid;
$userid=$Muserid;
$username=$Musername;
$Mmy=" and userid='{$userid}'";//后台时必须有

$callFrom='member';
$urlpar="&tmp={$tmp}&groupid={$groupid}&userid={$userid}&username={$username}&dgid={$dgid}";
?>
<style>
body{min-width:0px;}
html{overflow-x:hidden;}
</style>


<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/daigou/call/goods_save.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/daigou/call/goods_form_list.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/foot.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/js/daigouJS.php');//要放foot.php的后面
?>
