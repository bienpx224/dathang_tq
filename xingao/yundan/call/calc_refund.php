<?php 
//获取基本资料及处理------------------------------------------------------------------------------------------------
if($field=='money'){//运费------------
	$re_money=spr($rs['payment']-$rs['money']);//要退的费用(已付费用-总费用)
	$re_content=cadd($rs['ydh']).$LG['yundan.Xcall_calc_payment_22'];
	$re_save="payment=money";
}else{//税费------------
	$re_money=spr($rs['tax_payment']-$rs['tax_money']);
	$re_content=cadd($rs['ydh']).$LG['yundan.Xcall_calc_payment_23'];
	$re_save="tax_payment=tax_money";
}

//扣费处理--------------------------------------------------------------------------------------------------------------
if($re_money>0)
{
	//退费
	MoneyCZ($rs['userid'],'yundan',$rs['ydid'],$fromMoney=$re_money,$fromCurrency='',
	$rs['ydh'],'',51);
	
	//更新主表
	$xingao->query("update yundan set ".$re_save." where ydid='{$ydid}'");
	
	//按扣费成功发通知设置发送
	//发通知－开始
	$send_msg=$yundan_paysucc_msg;
	$send_mail=$yundan_paysucc_mail;	
	$send_sms=$yundan_paysucc_sms;
	$send_wx=$yundan_paysucc_wx;
	if($send_msg||$send_mail||$send_sms||$send_wx)
	{
		//获取发送通知内容
		$NoticeTemplate='xingao_calc_refund';	
		require($_SERVER['DOCUMENT_ROOT'].'/public/NoticeTemplate.php');

		$send_file='';
	
		$send_userid=$rs['userid'];
		$send_username=$rs['username'];
	
		//发站内信息
		if($send_msg){SendMsg($send_userid,$send_username,$send_title,$send_content_msg,$send_file,$from_userid='',$from_username='',$new=1,$status=0,$issys=1,$xs=0);}
		//发邮件
		if($send_mail){SendMail($rsemail='',$send_title,$send_content_mail,$send_file,$issys=1,$xs=0,$send_userid);}
		//发短信
		if($send_sms){SendSMS($rsmobile='',$send_content_sms,$xs=0,$send_userid);}
		//发微信
		if($send_wx){SendWX($send_WxTemId,$send_WxTemName,$send_content_wx,$send_userid);}
	}
	//发通知－结束
	
}
?>