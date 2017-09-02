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
ob_end_clean();ob_implicit_flush(1);//实时输出:要放最前面,因为内容从此输出(之前的内容不输出)
require_once($_SERVER['DOCUMENT_ROOT'].'/public/config_top.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/public/html.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/public/function.php');

require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/session.php');
if(!$member_per[$Mgroupid]['off_print']){exit ("<script>alert('{$LG['yundan.op_9']}');goBack('c');</script>");}
	
$callFrom='member';require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/yundan/print/index.php');
?>