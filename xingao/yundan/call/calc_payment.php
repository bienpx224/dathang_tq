<?php 
/*
	调用:
	$rs
	$sending=0;
	$field='money';//运费
	$field='tax_money';//税费
*/


//获取基本资料及处理------------------------------------------------------------------------------------------------
$ydh=cadd($rs['ydh']);
$userid=$rs['userid'];
$username=$rs['username'];

if($field=='money'){//运费------------
	$prefer=$rs['prefer'];
	$integral_to=$rs['integral_to'];//是否送分
	$integral_use=1;//是否可用积分消费 
	$payment=$rs['payment'];//已付费用
	$pay_money=spr(spr($rs['money'])-$payment);//要付的费用(总费用－已付费用)
}else{//税费------------
	$prefer=0;
	$off_integral=0;//关闭积分功能(关闭显示)
	$integral_to=0;
	$integral_use=0;
	$payment=$rs['tax_payment'];
	$pay_money=spr(spr($rs['tax_money'])-$payment);
}
$pay_total=$pay_money;




if(!$kffs&&$pay_money>0)
{
	$kf=0;//返回扣费失败

//扣费处理------------------------------------------------------------------------------------------
}elseif($kffs&&$pay_money>0){
	
	if($prefer==1||$prefer==2){
		$update_userid=$userid;$usetypes='0,1';require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/coupons/call/update.php');//返回$cp
		$coupons_number=$cp['number'];
	}

	//积分运算,计算真正要付的费用
	$userid=$rs['userid'];$integral_lx='yundan';$coupons_calllx='query';
	require($_SERVER['DOCUMENT_ROOT'].'/xamember/payment/call/calc_coupons.php');//必须排前,优先用优惠券/折扣券,返回$cpc
	require($_SERVER['DOCUMENT_ROOT'].'/xamember/payment/call/calc_integral.php');//积分运算,计算真正要付的费用
	
	
	if($pay_money>0)
	{
		//查询是否可扣费
		$mr=FeData('member','money,groupid,currency',"userid='{$userid}'");
		$off_settlement=MemberSettlement('',$mr['groupid']);
		
		$toExchange=exchange($XAMcurrency,$mr['currency']);	$toMoney=spr($pay_money*$toExchange);

		if(!$off_settlement&&spr($mr['money'])<$toMoney)
		{
			//不是月结并且费用不足
			$ts_pay.=$LG['yundan.Xcall_calc_payment_1'];
			$kf=0;
			//扣费失败发通知 发通知－开始
			$send_msg=$yundan_payfail_msg;
			$send_mail=$yundan_payfail_mail;	
			$send_sms=$yundan_payfail_sms;
			$send_wx=$yundan_payfail_wx;
			if($send_msg||$send_mail||$send_sms||$send_wx)
			{
				$sending=1;//设置已发过通知
				if($field=='money'){//运费
					$tz=$LG['yundan.Xcall_calc_payment_2'];
				}else{//税费
					$tz=$LG['yundan.Xcall_calc_payment_3'];
				}
				
				//获取发送通知内容
				$NoticeTemplate='xingao_calc_payment_1';	
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
			
		}else{
			
			//费用足够,扣费
			if($field=='money'){$type=21;}else{$type=22;}
			$content=$LG['yundan.Xcall_calc_payment_5'].':'.$ydh.'(ID'.$ydid.')';//发信息用
			
			MoneyKF($userid,$fromtable='yundan',$ydid,$fromMoney=$pay_money,$fromCurrency='',
			$ydh,'',$type);
			
			$kf=1;
			if ($integral_jian_hb>0)
			{
				//扣分
				integralKF($userid,$fromtable='yundan',$ydid,$integral_user,$integral_jian_hb,$ydh,'',1);
			}
			
			//优惠券/折扣券 使用
			if($cpc['cpid'])
			{
				couponsKF($cpc,'yundan',$ydid,$coupons_value,$ydh,'');
			}
			
			//开始送积分
			if ($integral_song_hb>0)
			{
				 //会员送分
				 integralCZ($userid,'yundan',$ydid,$integral_song_hb,$ydh,'',1);
				 
				 //推广员加分
				 tuiguang_hqsf($userid,$integral_song_hb,'yundan',$ydid);
			}
		
			//开始更新
			$tally=0;if($off_settlement){$tally=1;}
			//运费-----------------------------------------
			if($field=='money')
			{
				$xingao->query("update yundan set tally='{$tally}',pay=1,payment=payment+{$pay_total},payment_time='".time()."' where ydid='{$ydid}'");
				SQLError('更新运单');
				$ts_pay=LGtag($LG['yundan.Xcall_calc_payment_20'],
						'<tag1>=='.$ydh.'||'.
						'<tag2>=='.$ydid.'||'.
						'<tag3>=='.$pay_money.$XAmc
					 );
			}
			//税费-----------------------------------------
			else
			{
				$xingao->query("update yundan set tally='{$tally}',tax_pay=1,tax_payment=tax_payment+{$pay_total},tax_payment_time='".time()."' where ydid='{$ydid}'");
				SQLError('更新运单');
				$ts_pay=LGtag($LG['yundan.Xcall_calc_payment_21'],
						'<tag1>=='.$ydh.'||'.
						'<tag2>=='.$ydid.'||'.
						'<tag3>=='.$pay_money.$XAmc
						 );
			}
			
			if ($coupons_value>0){$ts_pay.=LGtag($LG['yundan.Xcall_calc_payment_7'],'<tag1>=='.$coupons_use_number.'||<tag2>=='.$coupons_value.$XAmc);}
			if ($integral_jian_hb>0){$ts_pay.=$LG['yundan.Xcall_calc_payment_8'].$integral_user.$LG['useIntegral'].$integral_jian_hb.$XAmc;}
			if ($coupons_value||$integral_jian_hb){$ts_pay.=$LG['yundan.Xcall_calc_payment_9'].$pay_total.$XAmc;}
			if ($integral_song_hb>0){$ts_pay.=$LG['yundan.Xcall_calc_payment_10'].$integral_song_hb.$LG['integral'];}

			//扣费成功发通知
			//不是月结时发通知－开始
			if(!$off_settlement)
			{
				$send_msg=$yundan_paysucc_msg;
				$send_mail=$yundan_paysucc_mail;	
				$send_sms=$yundan_paysucc_sms;
				$send_wx=$yundan_paysucc_wx;
				if($send_msg||$send_mail||$send_sms||$send_wx)
				{
					$sending=1;//设置已发过通知
					if($field=='money'){//运费
						$tz=$LG['yundan.Xcall_calc_payment_11'];
					}else{//税费
						$tz=$LG['yundan.Xcall_calc_payment_12'];
					}
					//获取发送通知内容
					$NoticeTemplate='xingao_calc_payment_2';	
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
			}
			//不是月结时发通知－结束
			
		}//if(spr($mr['money'])<$pay_money)
	}
}//if($kffs)









//算费发通知-------------------------------------------------------------------------------------------

//月结计费通知
if($off_settlement)
{
	if(!$yundan_fee_settlement){$sending=1;}
	elseif($yundan_fee_settlement){$sending=0;}
}


if(!$sending&&$pay_money>0)//如果之前没发过通知时才发
{
	//发通知－开始
	$send_msg=$yundan_fee_msg;
	$send_mail=$yundan_fee_mail;	
	$send_sms=$yundan_fee_sms;
	$send_wx=$yundan_fee_wx;
	if($send_msg||$send_mail||$send_sms||$send_wx)
	{
		if($field=='money'){//运费
			$off_settlement?$tz=$LG['yundan.Xcall_calc_payment_2']:$tz=$LG['yundan.Xcall_calc_payment_14'];
		}else{//税费
			$off_settlement?$tz=$LG['yundan.Xcall_calc_payment_3']:$tz=$LG['yundan.Xcall_calc_payment_16'];
		}

		//获取发送通知内容
		$NoticeTemplate='xingao_calc_payment_3';	
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
}//if(!$sending)
?>