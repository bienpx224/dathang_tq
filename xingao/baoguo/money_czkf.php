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


if(!$off_baoguo){exit ("<script>alert('包裹系统已关闭！');goBack();</script>");}

$bgid=par($_POST["bgid"]);
$bgydh=par($_POST["bgydh"]);
$type=par($_POST["tjtype"]);
$userid=par($_POST["userid"]);
$money=spr($_POST[$type.'_pay']);

if (!$type){exit ("<script>alert('tjtype错误！');goBack('c');</script>");}	
if (!$money){exit ("<script>alert('金额不能为0！');goBack('c');</script>");}

//获取用户名和用户ID
$member=MemberOK('','',$userid,'','','');
if(!$member){exit ("<script>alert('会员错误！');goBack('c');</script>");}


$type=op_money_type($type,1);
$type_name=op_money_type($type);

if ($money<0)
{
	//扣费
	$pervar='member_re';require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');//费用权限
	
	$content='包裹单号:'.$bgydh.' (ID:'.$bgid.')';//发信息可能用到
	
	MoneyKF($userid,'baoguo',$bgid,$fromMoney=$money,$fromCurrency='',
	$bgydh,'',$type);
	
	exit ("<script>alert('{$type_name} 扣费成功,已扣{$money}{$XAmc}！');goBack('c');</script>");
}else{
	//退费
	$pervar='member_de';require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');//费用权限

	MoneyCZ($userid,$fromtable='baoguo',$fromid=$bgid,$fromMoney=$money,$fromCurrency='',$title=$bgydh,$content='',$type);
	
	exit ("<script>alert('{$type_name} 退费成功,已退{$money}{$XAmc}！');goBack('c');</script>");
}



?>
