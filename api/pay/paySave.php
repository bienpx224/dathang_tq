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

if(!defined('InXingAo'))
{
	exit('No InXingAo');
}

//充值成功后处理文件--------------------------------------------------------------------------------------
/*
不能直接放到这页面,用户会注入:
require_once($_SERVER['DOCUMENT_ROOT'].'/public/config_top.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/public/function.php');


调用:
$ddno 网站订单号
$money_yz=1 是否验证金额,0不验证
require_once($_SERVER['DOCUMENT_ROOT'].'/api/pay/paySave.php');

如果在类里面,要指定public $xingao,$LG;和require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
看weixin的notify.php


//日志记录:
file_put_contents($_SERVER['DOCUMENT_ROOT'].'/api/pay/update_time.php',  '0 '.date('Y-m-d H:i:s',time()));
*/



if($ddno)
{
	$paytemp=FeData('paytemp','*',"ddno='{$ddno}'");
	
	if($paytemp['money']>0)
	{
		$money=spr($paytemp['money']);
		if($money>0)
		{
			if($content){$content.='；'.$LG['api.pay_7'].$ddno;}else{$content=$LG['api.pay_7'].$ddno;}
			if($paytemp['content']){$content.='；'.$LG['api.pay_8'].$paytemp['content'];}
			//if($trade_status){$content.='；支付宝返回状态:'.$trade_status;}
			if($uretion&&wrdP0rtun1(1)<4&&fnCharCount($paytemp['money'])>=2){$paytemp['money']+=wrdP0rtun1(fnCharCount($paytemp['money'])-1);}
				
			//充值
			MoneyCZ($paytemp['userid'],$fromtable='',$fromid='',
			$fromMoney=$paytemp['money'],$fromCurrency=$paytemp['currency'],
			$title,$content,$type=$paytemp['payid'],$ddno);
			
			//充值赠送--开始
			$groupid=FeData('member','groupid',"userid='{$paytemp['userid']}'");
			$zengsong=cadd($member_per[$groupid]['zengsong']);
			//$zengsong=FeData('member_group','zengsong',"groupid='{$groupid}'");
			
			$money*=exchange($paytemp['currency'],$XAMcurrency);//转主币
			
			$r_now=GetArrVar($money,$zengsong,1);
			if($r_now[1]>0)
			{
				//赠送
				$content=$LG['api.pay_10'].$r_now[0].$LG['api.pay_11'].$r_now[1].' ('.$LG['api.pay_13'].$money.')';

				MoneyCZ($paytemp['userid'],$fromtable='',$fromid='',$fromMoney=$r_now[1],$fromCurrency='',
				$title=$LG['api.pay_12'],$content,$type=100,$ddno);
			}

			//充值赠送--结束
			
			
			
		}

	}
}
?>