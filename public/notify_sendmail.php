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
if(!$Xuserid){exit('非法注入提示:后台人员才能使用异步发送');}
if(par($_GET['key'])!=md5(DateYmd(time(),'Y-m-d H:'))){exit('非法注入提示:KEY超时');}//不能用分,秒验证,时间变换时会验证不对


$email=urldecode($_GET['email']);
$title=urldecode($_GET['title']);
$content=urldecode($_GET['content']);
$file=urldecode($_GET['file']);
$issys=urldecode($_GET['issys']);
$xs=urldecode($_GET['xs']);
$userid=urldecode($_GET['userid']);


echo SendMail($email,$title,$content,$file,$issys,$xs,$userid,$notify=0);//$notify此页必须0
?>