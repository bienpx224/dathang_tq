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
$lx=par($_REQUEST['lx']);
$bgid=par($_REQUEST['bgid']);

//验证
if(!$bgid){exit ("bgid{$LG['pptError']}");}

//通用读取包裹资料-----------------------------------------------------------------------------------------------
$where=baoguo_fahuo(2)." and status in (2,3) and ware=0 ";//可以发货并且状态已入库的
$min=mysqli_fetch_array($xingao->query("select bgydh,bgid,fx_wupin,fx_requ from baoguo where fx<>1 and fx_suo=0  and bgid='{$bgid}' {$where} {$Mmy}"));

$bgid=$min['bgid'];
if (!$bgid){exit("{$LG['baoguo.fx_save_1']}");} 



//保存子分箱开始----------------------------------------------------------------------------------------------
if ($lx=='fx')
{
	$wupin_type=$_POST['wupin_type'];
	$wupin_name=$_POST['wupin_name'];
	$wupin_brand=$_POST['wupin_brand'];
	$wupin_spec=$_POST['wupin_spec'];
	$wupin_price=$_POST['wupin_price'];
	$wupin_unit=$_POST['wupin_unit'];
	$wupin_weight=$_POST['wupin_weight'];
	$wupin_number=$_POST['wupin_number'];
	$wupin_total=$_POST['wupin_total'];
	


	//保存分箱物品
	$fromtable_fx=$bgid.'_'.make_letter(10);//临时保存
	$fromid_fx=$bgid;//临时保存
	foreach($wupin_number as $key_w=>$value_w)
	{
		if(trim($wupin_number[$key_w])||trim($wupin_weight[$key_w]))
		{
			$xingao->query("insert into wupin (
				fromtable,fromid,
				wupin_type,wupin_name,wupin_brand,wupin_spec,wupin_price,wupin_unit,wupin_weight,wupin_number,wupin_total
			) values (
				'".add($fromtable_fx)."','".spr($fromid_fx)."',
				'".add($wupin_type[$key_w])."','".add($wupin_name[$key_w])."','".add($wupin_brand[$key_w])."','".add($wupin_spec[$key_w])."','".spr($wupin_price[$key_w])."','".add($wupin_unit[$key_w])."','".spr($wupin_weight[$key_w])."','".spr($wupin_number[$key_w])."','".spr($wupin_total[$key_w])."'
			)");
			SQLError('保存物品');
			$ok=1;
		}
	}
	

	//更新主箱
	if($min['fx_wupin']){$fx_wupin=add($min['fx_wupin'].','.$fromtable_fx);}else{$fx_wupin=add($fromtable_fx);}
	if($ok)
	{
		$xingao->query("update baoguo set fx_wupin='{$fx_wupin}' where bgid='{$bgid}' {$Mmy}");
		SQLError('分箱-更新主箱');
	}
	
}


//删除分箱----------------------------------------------------------------------------------------------
elseif($lx=='del')
{
	$fx_wupin=cadd($_GET['fx_wupin']);
	if(!$fx_wupin){exit ("<script>alert('{$LG['baoguo.fx_save_2']}');goBack();</script>"); }
	
	//更新主箱
	$fx_wupin_save=str_ireplace(','.$fx_wupin,'',cadd($min['fx_wupin']));//删除:,标记
	$fx_wupin_save=str_ireplace($fx_wupin.',','',cadd($min['fx_wupin']));//删除:标记,
	$fx_wupin_save=str_ireplace($fx_wupin,'',cadd($min['fx_wupin']));//删除:标记
	
	$xingao->query("update baoguo set fx_wupin='{$fx_wupin_save}' where bgid='{$bgid}' {$Mmy}");
	SQLError('删除-更新主箱');

	//删除分箱
	$xingao->query("delete from wupin where fromtable='{$fx_wupin}' ");
}



//整合主箱物品并删除所有分箱---------------------------------------------------------------------------
elseif($lx=='del_all')
{
	//把重复的物品整合一起，只加数量和总价
	wupin_run($zheng=4,$fromtable='baoguo',$fromid=$bgid,$fromtable_new='',$fromid_new='');
	
	//删除全部分箱
	$xingao->query("delete from wupin where fromtable like '%{$bgid}%'  ");
	SQLError('全删除-删除未提交临时分箱申请的物品');
	
	//更新主箱
	$xingao->query("update baoguo set fx_wupin='' where bgid='{$bgid}' {$Mmy}");
	SQLError('全删除-更新主箱');
}


//确定保存付款----------------------------------------------------------------------------------------------
elseif ($lx=='pay')
{ 
	$fx_requ=html($_POST['hx_requ']);
	$num=arrcount(cadd($min['fx_wupin']));
	$money=baoguo_fx_fee($num,$Muserid);
	
	
	if($money>0)
	{
		$content=$LG['baoguo.fx_save_3'].cadd($min['bgydh']).' (ID:'.$min['bgid'].')';//发信息可能用到
		MoneyKF($Muserid,$fromtable='baoguo',$fromid=$min['bgid'],$fromMoney=$money,$fromCurrency='',
		$title=$min['bgydh'],'',$type=op_money_type('fx',1));
		$ts= ",{$LG['baoguo.fx_save_5']}<strong>".$money.$XAmc.'</strong>';
		$set.=",fx_pay='-{$money}'";
	}
	
	echo '&raquo; '.$min_bgydh.$LG['baoguo.fx_save_4'].$ts.'<br>';

	//更新主表
	$set="fx_requ='{$fx_requ}',fx='1',fx_suo='1',fx_pay='-{$money}'";
	$xingao->query("update baoguo set {$set} where bgid='{$bgid}' {$Mmy}");
	SQLError('更新主表');
	$rc=mysqli_affected_rows($xingao);
	
	//操作完后提示
	if($rc){echo '<br><strong>'.LGtag($LG['baoguo.fx_save_6'],'<tag1>=='.$num).'</strong>';}else{echo '<br><strong>'.$LG['baoguo.fx_save_7'].'</strong>';}
	exit();
}

exit('<script language=javascript>location.href="fx.php?bgid='.$bgid.'";</script>');
?>