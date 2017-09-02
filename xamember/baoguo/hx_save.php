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
require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/session.php');


//获取,处理-----------------------------------------------------------------------------------------------
$lx=par($_POST['lx']);
$min_bgid=par($_POST['min_bgid']);
$min_bgydh=par($_POST['min_bgydh']);
$hx_requ=html($_POST['hx_requ']);

$bgid=par(ToStr($_POST['bgid']));
//验证
if(!$min_bgid){exit ("min_bgid{$LG['pptError']}");}
if(!$bgid){exit ("<script>alert('{$LG['baoguo.hx_save_1']}');goBack();</script>"); }

if($lx=='pay')
{
	//计算包裹合箱费用
	$num=arrcount($bgid);
	$money=baoguo_hx_fee($num,$Muserid);
	$set="hx_id='{$bgid}',hx='1',hx_suo='1',hx_requ='{$hx_requ}'";
	
	if($money>0)
	{
		$content=$LG['baoguo.fx_save_3'].$min_bgydh.' (ID:'.$min_bgid.')';//发信息可能用到
		
		MoneyKF($Muserid,$fromtable='baoguo',$fromid=$min_bgid,$fromMoney=$money,$fromCurrency='',
		$title=$min_bgydh,'',$type=op_money_type('hx',1));
		
		$ts= ",{$LG['baoguo.fx_save_5']}<strong>".$money.$XAmc.'</strong>';
		$set.=",hx_pay='-{$money}'";
	}
	
	echo '&raquo; '.$min_bgydh.$min_bgydh.$LG['baoguo.fx_save_4'].$ts.'<br>';
	
	//更新主表
	$xingao->query("update baoguo set {$set} where bgid='{$min_bgid}' {$Mmy}");
	SQLError('更新主表');
	$rc=mysqli_affected_rows($xingao);
	
	//更新次包裹为记录包裹 
	if($rc)
	{
		$xingao->query("update baoguo set status='10',content=concat('".date('Y-m-d H:i:s')."{$LG['baoguo.hx_save_3']}{$min_bgydh}
		-------------
		',content),edittime='".time()."' where bgid in ({$bgid}) {$Mmy}");
		SQLError('更新次包裹');
	}
	
	//操作完后提示
	if($rc){echo '<br><strong>'.LGtag($LG['baoguo.hx_save_4'],'<tag1>=='.$num).'</strong>';}else{echo '<br><strong>'.$LG['baoguo.fx_save_7'].'</strong>';}

	exit();
}

?>