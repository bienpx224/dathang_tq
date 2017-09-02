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

if(!defined('InXingAo')){exit('No InXingAo');}

//计算优惠
function SettlementPreferential($fromtable='yundan',$fromid) 
{  
	if(!$fromid){return'';}
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	
	$cp=FeData('coupons',"count(*) as total,sum(`money`) as money","fromtable='{$fromtable}' and fromid in ({$fromid})");//优惠券/折扣券
	$jf=FeData('integral_kfbak',"count(*) as total,sum(`money`) as money","fromtable='{$fromtable}' and fromid in ({$fromid})");//积分
	
	$r['money_co']=$co['money'];
	$r['money_jf']=$jf['money'];
	return $r;
}

//计算退费
function SettlementRefund($fromtable='yundan',$fromid) 
{  
	if(!$fromid){return'';}
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	
	$cz=FeData('money_czbak',"count(*) as total,sum(`fromMoney`) as fromMoney","fromtable='{$fromtable}' and  fromid in ({$fromid})");//充值退费(退费时已退到账号,所以不能再用作统计)
	return spr($cz['fromMoney']);
}

//计算收费
function SettlementCharge($fromtable='yundan',$fromid) 
{  
	if(!$fromid){return'';}
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	
	$kf=FeData('money_kfbak',"count(*) as total,sum(`fromMoney`) as fromMoney","fromtable='{$fromtable}' and  fromid in ({$fromid})");//按扣费记录算(因为可能特殊情况直接在账户里扣)
	//不能加tally='1' ,否则无法正常查看已销账记录
	
	return spr($kf['fromMoney']);
}


?>