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
echo '<meta charset="utf-8">';
require_once($_SERVER['DOCUMENT_ROOT'].'/public/function.php');
@set_time_limit(0);//批量处理时,速度慢,设为永不超时
@header('X-Frame-Options:');//此页面设置为可以嵌入到iframe或者frame中
//自动更新处理
/*
	不可放站内框架,因为用户会打开新页面,导致本页可能执行一半就停止,数据就会更新不全
*/
if($off_baoguo){ require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/baoguo/call/update.php'); }
if($ON_daigou){ require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/daigou/call/update.php'); }
if($off_mall){ require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/mall_order/call/update.php'); }
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/yundan/call/update.php');//echo 'yundan-update提示:'.$ppt_update;
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/member/call/update.php');
?>