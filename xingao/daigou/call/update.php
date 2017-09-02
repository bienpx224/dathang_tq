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
require_once($_SERVER['DOCUMENT_ROOT'].'/public/function.php');

//通用设置
$ppt='';

//采购超时更新--------------------------------------------------------------------------
$update_auto=update_time('daigou_timeoutBuy','-10 minutes');//多久更新一次:1 week 3 days 7 hours 30 minutes 5 seconds
if($update_auto)
{
	$xingao->query("update daigou set timeoutBuy='1' where pay in (1,3) and payTime<'".strtotime('-24 hours')."' and status in (3,5)");
	SQLError('代购超时更新');
}
?>