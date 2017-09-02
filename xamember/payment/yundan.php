<?php 
//立即更新coupons和查询数量
$update_userid=$Muserid;$usetypes='0,1';require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/coupons/call/update.php');//返回$cp
$coupons_number=$cp['number'];

$field=par($_REQUEST['field']);//费用类型(运费或税费,从运单列表传值)
if($field=='money'){//运费------------
	$where="and status not in (0,1) and pay='0'";
}elseif($field=='tax_money'){//税费------------
	$where="and status>1 and tax_pay='0'";
}else{
	exit ("<script>alert('{$LG['payment.yundan_1']}');goBack();</script>");
}

//获取运单信息
$ts_pay='';
$query="select * from yundan where ydid in ({$payid}) {$where} {$Mmy}";
$sql=$xingao->query($query);
while($rs=$sql->fetch_array())
{
	//通用获取资料---------------------------------------------------------------------------
	$ydh=cadd($rs['ydh']);
	$userid=$rs['userid'];
	$username=$rs['username'];
	$ydid=$rs['ydid'];
	if($rs['tally']==2){exit("<script>alert('".LGtag($LG['payment.yundan_2'],'<tag1>=='.$ydh.'')."');goBack('c');</script>");}
	
	if($field=='money'){//运费------------
		$integral_to=$rs['integral_to'];//是否送分
		$integral_use=1;//是否可用积分消费 
		$payment=$rs['payment'];//已付费用
		$pay_money=spr(spr($rs['money'])-$payment);//要付的费用(总费用－已付费用)
	}elseif($field=='tax_money'){//税费------------
		$prefer=0;
		$off_integral=0;//关闭积分功能(关闭显示)
		$integral_to=0;
		$integral_use=0;
		$payment=$rs['tax_payment'];
		$pay_money=spr(spr($rs['tax_money'])-$payment);
	}
	if($pay_money<=0){exit("ID{$ydid}:".LGtag($LG['payment.yundan_2']) );}
	$pay_total=$pay_money;
	
	
	$pay_money_total1=$pay_money_total1+$pay_money;//未算优惠券/折扣券和积分前要付的费用
	$integral_lx='yundan';$coupons_calllx='query';
	require($_SERVER['DOCUMENT_ROOT'].'/xamember/payment/call/calc_coupons.php');//必须排前,优先用优惠券/折扣券
	require($_SERVER['DOCUMENT_ROOT'].'/xamember/payment/call/calc_integral.php');//积分运算,计算真正要付的费用

	//总计并用于显示
	$pay_money_total2+=$pay_money;//总共要付的费用
	$coupons_value_all+=$coupons_value;//总共可用优惠券/折扣券的元
	$coupons_use_number_all+=$coupons_use_number;//总共可用优惠券/折扣券的数量
	if($coupons_value>0)
	{
		if($cp_dixiao_yes){$cp_dixiao_yes.='、'.$ydid;}else{$cp_dixiao_yes=$ydid;}//可以使用的优惠券/折扣券
	}else{
		if($cp_dixiao_no){$cp_dixiao_no.='、'.$ydid;}else{$cp_dixiao_no=$ydid;}//不可以使用的优惠券/折扣券
	}
	
	
	$integral_jian_hb_all+=$integral_jian_hb;//总共可用的积分元
	$integral_user_all+=$integral_user;//总共可用的积分
		
	if($integral_jian_hb>0)
	{
		if($dixiao_yes){$dixiao_yes.='、'.$ydid;}else{$dixiao_yes=$ydid;}//可以使用的积分信息ID
	}else{
		if($dixiao_no){$dixiao_no.='、'.$ydid;}else{$dixiao_no=$ydid;}//不可以使用的积分信息ID
	}

	//提交保存-开始-------------------------------------------------------------------------------------------------
	if($lx=='pay')
	{
		if($pay_money>0)
		{
			//扣费
			if($field=='money'){$type=21;}else{$type=22;}
			$content=$ydh.'(ID'.$ydid.')';//发信息可能用到
			
			MoneyKF($userid,'yundan',$ydid,$fromMoney=$pay_money,$fromCurrency='',
			$ydh,'',$type);
			
			if ($integral_jian_hb>0)
			{
				//扣分
				integralKF($userid,'yundan',$ydid,$integral_user,$integral_jian_hb,$ydh,'',1);
			}
			
			//优惠券/折扣券 使用
			if($cpc['cpid'])
			{
				couponsKF($cpc,'yundan',$ydid,$coupons_value,$ydh,'');
			}
			
			//开始送积分
			if ($integral_song_hb>0)
			{
				 //会员加分
				 integralCZ($userid,'yundan',$ydid,$integral_song_hb,$ydh,'',1);
				 
				 //推广员加分
				 tuiguang_hqsf($userid,$integral_song_hb,'yundan',$ydid);
			}
		}

		//开始更新
		$tally=0;if($off_settlement){$tally=1;}
		//运费-----------------------------------------
		if($field=='money')
		{
			
			if($ON_yundan_prepay)
			{
				if(spr($rs['status'])==-2)
				{
					//包裹下单时处理-开始
					$num=NumData('yundan',"calc=2 and payment=0 and money>0 and ydid<>'{$ydid}' and bgid<>'' and bgid='{$rs['bgid']}'");
					if($num){
						//有待预付运单
						$save_prepay=",status=-2";
					}else{
						//没有待预付运单
						if($rs['notStorage']==1){$save_prepay=",status=-1";}else{$save_prepay=",status=0";}
						
						//全部更新分包
						$xingao->query("update yundan set ydid=ydid {$save_prepay} where ydid<>'{$ydid}' and bgid<>'' and bgid='{$rs['bgid']}' {$Mmy}");
					}
					//包裹下单时处理-结束
				}
			}
			
			
			$xingao->query("update yundan set tally='{$tally}',pay=1,memberpay=1,payment=payment+$pay_total,payment_time='".time()."' {$save_prepay} where ydid='{$ydid}' {$Mmy}");
			SQLError('更新运单');
			
			baoguoInStorage($rs['bgid']);//按包裹入库情况更新运单
			
			
			//更新运单状态
			if(spr($rs['status'])<4&&spr($rs['status'])!=-1&&spr($rs['status'])!=-2)
			{
				yundan_updateStatus($rs,$update_status=4,0,0);
			}
			$ts_pay=$ydh.'(ID'.$ydid.'):'.$LG['havePay'].$pay_money.$XAmc;
		}
		//税费-----------------------------------------
		else
		{
			$xingao->query("update yundan set tally='{$tally}',tax_pay=1,memberpay=1,tax_payment=tax_payment+$pay_total,tax_payment_time='".time()."' where ydid='{$ydid}' {$Mmy}");
			SQLError('更新订单');
			//更新运单状态
			if($status_on_15&&spr($rs['status'])<15)
			{
				yundan_updateStatus($rs,$update_status=15,0,0);
			}
			$ts_pay=$ydh.'(ID'.$ydid.'):'.$LG['havePay'].$pay_money.$XAmc;
		}
		
		if ($coupons_value>0){$ts_pay.=LGtag($LG['payment.mall_order_6'],'<tag1>=='.$coupons_use_number ).$coupons_value.$XAmc;}
		if ($integral_jian_hb>0){$ts_pay.=LGtag($LG['payment.mall_order_7'],'<tag1>=='.$integral_user ).$integral_jian_hb.$XAmc;}
		if ($coupons_value||$integral_jian_hb){$ts_pay.=$LG['payment.mall_order_8'].$pay_total.$XAmc;}
		if ($integral_song_hb>0){$ts_pay.=LGtag($LG['payment.mall_order_9'],'<tag1>=='.$integral_song_hb );}

		echo '<font class="gray_prompt">&raquo;  '.$ts_pay."<br></font>";
	}
	//提交保存-结束-------------------------------------------------------------------------------------------------

}//while($rs=$sql->fetch_array())
if(!$ydid)
{
	exit($LG['payment.mall_order_24']);
}


//完成提示-------------------------------------------------------------------------------------------------
if($lx=="pay"&&$ts_pay)
{
	$_SESSION["ydid"]='';//支付成功,清空所保存的勾选
}
?>