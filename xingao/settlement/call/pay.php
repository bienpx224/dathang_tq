<?php
//获取,处理-----------------------------------------------------------------------------------------------
$fromtable=par($_REQUEST['fromtable']);
if(!spr($userid)){$userid=spr($_REQUEST['userid']);}
$lx=par($_POST['lx']);

//验证
if(!$fromtable){exit ('fromtable'.$LG['pptError']);}
if(!$userid){exit ('userid'.$LG['pptError']);}


//查询获取
$mr=FeData('member','userid,username,money,currency,settlement_yundan_bill,settlement_yundan_money,settlement_other_bill,settlement_other_money,settlement_all_money',"userid='{$userid}'");
if(!$mr['userid']){exit ('userid'.$LG['pptError']);}


//运单账单=====================================================
if($fromtable=='yundan'){

	//获取运单账单------
	$settlement_money=spr($mr['settlement_yundan_money']);
	$settlement_bill=$mr['settlement_yundan_bill'];//下面不能加cadd
	
	//初步查询验证------
	if(!$settlement_bill){exit($LG['settlement.Xcall_pay_4']);}
	if(!is_json($settlement_bill)){exit($LG['settlement.Xcall_pay_5']);}
	$_GET = (array) json_decode($settlement_bill,true);//不能加cadd
	
	//重新统计总额------
	$_GET['so']=1;$op2=1;
	require($_SERVER['DOCUMENT_ROOT'].'/xingao/settlement/call/list_yundan_where.php');//条件处理,返回{$select} {$where} {$group} {$order}
	$query="select {$select} from yundan where {$where} {$Xwh} and userid='{$userid}' {$group} {$order}";
	$sql=$xingao->query($query);
	while($rs=$sql->fetch_array())
	{
		require($_SERVER['DOCUMENT_ROOT'].'/xingao/settlement/call/list_yundan_total.php');//总计
		$r_settlement_money=SettlementCharge($fromtable='yundan',$rs['ydid']);
		$r_settlement_money=-spr($r_settlement_money);//转负数(如果原是负数,表示是要退费,会自动转正数)
		
		if($settlement_money!=$r_settlement_money){exit (LGtag($LG['settlement.Xcall_pay_14'],'<tag1>=='.$settlement_money.$XAmc.'||'.'<tag2>=='.$r_settlement_money.$XAmc) );}
		
		
		
		
		
		
		//提交保存-开始----------------------------------------------------------------------
		if($lx=='pay')
		{
			$ts_err='';
			
			//验证------
			if($settlement_money!=$_POST['settlement_money']){exit ("<script>alert('{$LG['settlement.Xcall_pay_6']}');goBack();</script>");}
			
			$fromtable='yundan';$fromid=$rs['ydid'];//$fromid 是多个ID
			
			//扣费/退费------
			if($settlement_money<=0)
			{
				//扣费
				MoneyKF($rs['userid'],$fromtable,'0',$fromMoney=$settlement_money,$fromCurrency='',
				$LG['settlement.Xcall_pay_15'],$LG['settlement.Xcall_pay_16'].$fromid,100,$tally=2);
			}else{
				//充值
				MoneyCZ($rs['userid'],$fromtable,'0',$fromMoney=$settlement_money,$fromCurrency='',
				$LG['settlement.Xcall_pay_17'],$LG['settlement.Xcall_pay_16'].$fromid,100);			
			}
			
			//更新会员表------
			$xingao->query("update member set settlement_yundan_bill='',settlement_yundan_money='0',settlement_all_money=settlement_all_money-{$settlement_money} where userid='{$rs[userid]}'");
			if(mysqli_affected_rows($xingao)<=0){$ts_err.=$LG['settlement.Xcall_pay_18'].'<br>';}
			
			//更新-运单------
			$xingao->query("update yundan set tally='2' where tally='1' and ydid in ({$fromid})");
			if(mysqli_affected_rows($xingao)<=0){$ts_err.=$LG['settlement.Xcall_pay_19'].'<br>';}
			
			//更新-扣费记录表------
			$xingao->query("update money_kfbak set tally='2' where tally='1' and fromtable='{$fromtable}' and  fromid in ({$fromid})");
			if(mysqli_affected_rows($xingao)<=0){$ts_err.=$LG['settlement.Xcall_pay_20'].'<br>';}
			
			//发通知－开始------
			$send_msg=$settlement_msg;$send_mail=$settlement_mail;$send_sms=$settlement_sms;$send_wx=$settlement_wx;
			if($send_msg||$send_mail||$send_sms||$send_wx)
			{
				//获取发送通知内容
				$NoticeTemplate='xingao_settlement_pay_other';	
				require($_SERVER['DOCUMENT_ROOT'].'/public/NoticeTemplate.php');
				
				//发站内信息
				if($send_msg){SendMsg($rs['userid'],$rs['username'],$send_title,$send_content_msg,$send_file,$from_userid='',$from_username='',$new=1,$status=0,$issys=1,$xs=0);}
				//发邮件
				if($send_mail&&$rs['email']){SendMail($rs['email'],$send_title,$send_content_mail,$send_file,$issys=1,$xs=0,$send_userid);}
				//发短信
				if($send_sms&&$rs['mobile']){SendSMS(SMSApiType($rs['mobile_code'],$rs['mobile']),$send_content_sms,$xs=0,$send_userid);}
				//发微信
				if($send_wx){SendWX($send_WxTemId,$send_WxTemName,$send_content_wx,$rs['userid']);}
			}
			//发通知－结束
			
			if(!$ts_err){exit($LG['settlement.Xcall_pay_8']);}
			else{exit ('<strong>'.$LG['settlement.Xcall_pay_9'].'</strong><br>'.$LG['settlement.Xcall_pay_22'].'<br>'.$ts_err);}
			
		}
		//提交保存-结束-----------------------------------------------------------------------
		
		
	}//while($rs=$sql->fetch_array())
	
	
	
	
	
	
	
	
	
	
//其他账单=====================================================
}elseif($fromtable=='other'){
	
	//获取其他账单------
	$settlement_money=spr($mr['settlement_other_money']);
	$settlement_bill=$mr['settlement_other_bill'];//下面不能加cadd
	
	//初步查询验证------
	if(!$settlement_bill){exit($LG['settlement.Xcall_pay_4']);}
	if(!is_json($settlement_bill)){exit($LG['settlement.Xcall_pay_5']);}
	$_GET = (array) json_decode($settlement_bill,true);//不能加cadd
	
	//重新统计总额------
	$_GET['so']=1;$op2=1;
	require($_SERVER['DOCUMENT_ROOT'].'/xingao/settlement/call/list_other_where.php');//条件处理,返回{$select} {$where} {$group} {$order}
	$query="select {$select} from money_kfbak where {$where} and userid='{$userid}' {$group} {$order}";
	$sql=$xingao->query($query);
	while($rs=$sql->fetch_array())
	{
		$sr=FeData('money_kfbak',"count(*) as total,sum(`fromMoney`) as fromMoney","{$where} and userid='{$rs[userid]}'");//总计
		$r_settlement_money=$sr['fromMoney'];
		$r_settlement_money=-spr($r_settlement_money);//转负数(如果原是负数,表示是要退费,会自动转正数)
		
		if($settlement_money!=$r_settlement_money){exit (LGtag($LG['settlement.Xcall_pay_14'],'<tag1>=='.$settlement_money.$XAmc.'||'.'<tag2>=='.$r_settlement_money.$XAmc) );}
	
	
	
	
	
	
		//提交保存-开始----------------------------------------------------------------------
		if($lx=='pay')
		{
			$ts_err='';
			
			//验证------
			if($settlement_money!=$_POST['settlement_money']){exit ("<script>alert('{$LG['settlement.Xcall_pay_6']}');goBack();</script>");}
			
			//扣费/退费------
			if($settlement_money<=0)
			{
				//扣费
				MoneyKF($rs['userid'],'','0',$fromMoney=$settlement_money,$fromCurrency='',
				$LG['settlement.Xcall_pay_23'],'',100,$tally=2);
				
			}else{
				//充值
				MoneyCZ($rs['userid'],'','0',$fromMoney=$settlement_money,$fromCurrency='',
				$LG['settlement.Xcall_pay_24'],'',100);			
			}
			
			//更新会员表------
			$xingao->query("update member set settlement_other_bill='',settlement_other_money='0',settlement_all_money=settlement_all_money-{$settlement_money} where userid='{$rs[userid]}'");
			if(mysqli_affected_rows($xingao)<=0){$ts_err.=$LG['settlement.Xcall_pay_18'].'<br>';}
			
			
			//更新-扣费记录表------
			$xingao->query("update money_kfbak set tally='2' where {$where} and tally='1' and userid='{$rs[userid]}' ");
			if(mysqli_affected_rows($xingao)<=0){$ts_err.=$LG['settlement.Xcall_pay_20'].'<br>';}
			
			//发通知－开始------
			$send_msg=$settlement_msg;$send_mail=$settlement_mail;$send_sms=$settlement_sms;$send_wx=$settlement_wx;
			if($send_msg||$send_mail||$send_sms||$send_wx)
			{
				//获取发送通知内容
				$NoticeTemplate='xingao_settlement_pay_yundan';	
				require($_SERVER['DOCUMENT_ROOT'].'/public/NoticeTemplate.php');
							
				//发站内信息
				if($send_msg){SendMsg($rs['userid'],$rs['username'],$send_title,$send_content_msg,$send_file,$from_userid='',$from_username='',$new=1,$status=0,$issys=1,$xs=0);}
				//发邮件
				if($send_mail&&$rs['email']){SendMail($rs['email'],$send_title,$send_content_mail,$send_file,$issys=1,$xs=0,$send_userid);}
				//发短信
				if($send_sms&&$rs['mobile']){SendSMS(SMSApiType($rs['mobile_code'],$rs['mobile']),$send_content_sms,$xs=0,$send_userid);}
				//发微信
				if($send_wx){SendWX($send_WxTemId,$send_WxTemName,$send_content_wx,$rs['userid']);}
			}
			//发通知－结束
			
			if(!$ts_err){exit($LG['settlement.Xcall_pay_8']);}
			else{exit ('<strong>'.$LG['settlement.Xcall_pay_9'].'</strong><br>'.$LG['settlement.Xcall_pay_22'].'<br>'.$ts_err);}
			
		}
		//提交保存-结束-----------------------------------------------------------------------
		
		
	}//while($rs=$sql->fetch_array())
		
		
		
		
		
		
}else{
	exit ("fromtable{$LG['pptError']}");
}










//输出-----------------------------------------------------------------------------------------------
//显示账单信息:通用
$bill=$LG['settlement.Xcall_pay_12'].cadd($mr['username']).' ('.cadd($mr['userid']).')';

if($fromtable=='yundan')//运单
{
	if($_GET['classid']){$bill.='<br>'.$LG['settlement.Xcall_pay_25'].classify($_GET['classid']);}
	if($_GET['lotno']){$bill.='<br>'.$LG['settlement.Xcall_pay_26'].cadd($_GET['lotno']);}
	if($_GET['sf_name']){$bill.='<br>'.$LG['settlement.Xcall_pay_27'].cadd($_GET['sf_name']);}
}

if($_GET['stime']||$_GET['etime']){$bill.='<br>'.$LG['settlement.Xcall_pay_28'].cadd($_GET['stime']).' - '.cadd($_GET['etime']);}
if($settlement_money){$bill.='<br><strong>'.$LG['settlement.Xcall_pay_29'].$settlement_money.$XAmc.'</strong>';}
?>