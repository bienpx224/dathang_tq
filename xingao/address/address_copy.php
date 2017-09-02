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

require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');
$headtitle=$LG['yundan.address_copy_1'];
$alonepage=1;
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/head.php');

$userid=par($_GET['userid']);
$username=par($_GET['username']);
if($userid||$username){$Mmy=" and (userid='{$userid}' or username='{$username}')";}
else{exit ("<script>alert('请先填写会员ID或会员名');goBack('c');</script>");}

require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/address/call/out_list.php');
?>