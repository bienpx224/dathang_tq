<?php 
$off_settlement=0;//不支持月结

$where="and status not in (0,1,10) and pay in (0,2)";

//获取信息
$ts_pay='';
$query="select * from daigou where dgid in ({$payid}) {$where} {$Mmy}";
$sql=$xingao->query($query);
while($rs=$sql->fetch_array())
{
	//币种变更提示:在变更币种时已经修改过金额,以防万一还是再次验证
	if($rs['toCurrency']&&$Mcurrency!=$rs['toCurrency']){ exit("ID{$dgid}:".$LG['daigou.3']); }

	//通用获取资料---------------------------------------------------------------------------
	$dgdh=cadd($rs['dgdh']);
	$userid=$rs['userid'];
	$username=$rs['username'];
	$dgid=$rs['dgid'];
	
	$totalFee=daigou_totalFee($rs);//此单全部费用:网站主币
	$totalPay=daigou_totalPay($rs);//已支付:网站主币
	$totalPayTo=daigou_totalPay($rs,1);//已支付:支付币
	$pay_money=$totalFee-$totalPay;//要付的费用(总费用－已付费用)
	if($pay_money<=0)
	{
		exit("ID{$dgid}:{$LG['daigou.4']}");
	}elseif($pay_money>0){
		$pay_money*=exchange($rs['priceCurrency'],$Mcurrency);//转支付币:不加spr,因为费用过小时,汇率后会小于2位小数,会显示0元
		if($pay_money>0&&$pay_money<0.01){$pay_money=0.01;}else{$pay_money=spr($pay_money);}//最小扣0.01
	}
	
	
	//另页面用到
	$pay_total=$pay_money;
	$pay_money_total1+=$pay_money;//未算优惠券/折扣券和积分前要付的费用
	$pay_money_total2+=$pay_money;//总共要付的费用
	$payCurrency=1;//已是会员支付币,不再兑换

	//提交保存-开始-------------------------------------------------------------------------------------------------
	if($lx=='pay')
	{
		unset($kf);
		if($pay_money>0)
		{
			//扣费
			$content=$dgdh.'(ID'.$dgid.')';//发信息可能用到
			
			$kf=MoneyKF($userid,'daigou',$dgid,$fromMoney=$pay_money,$fromCurrency=$Mcurrency,$dgdh,'',$type=3);
			
			//初次支付时:开始送积分
			if($off_integral&&$integral_daigou>0&&!$rs['PayFirstTime'])
			{
				 //会员加分
				 integralCZ($userid,'daigou',$dgid,$integral_daigou,$dgdh,'',4);
				 
				 //推广员加分
				 tuiguang_hqsf($userid,$integral_daigou,'daigou',$dgid);
			}
		}
		
		
		
		
		
		
		
		//0元补款时要用,因此不放if($pay_money>0)里面----------
		$save=daigou_savePay($rs,$kf,1);//支付后保存相关费用数据
		
		
		$xingao->query("update daigou set {$save} where dgid='{$dgid}' {$Mmy}");
		SQLError('更新代购单');
	
		//更新代购单状态
		$status=0;if(spr($rs['status'])<3){$status=3;}elseif(spr($rs['status'])>3){$status=5;}
		if($status){daigou_upStatus($rs,$status,1,0,0,'member');}
		
		//添加日志
		if(!$rs['PayFirstTime'])
		{
			$logContent='$LG[daigou.1]'; //初次支付
		}else{
			$logContent='$LG[daigou.2]';//后期补款
		}
			$logContent.="({$pay_money}{$Mcurrency})";//后期补款
		if(mysqli_affected_rows($xingao)){opLog('daigou',$rs['dgid'],1,$logContent,0);}
		
		
		
		
		
		$ts_pay=$dgdh."(ID{$dgid}):".$LG['havePay'].$pay_money.$Mcurrency;//."({$LG['deduct']}{$kf['toMoney']}{$kf['toCurrency']})"
		if ($integral_song_hb>0){$ts_pay.=LGtag($LG['payment.mall_order_9'],'<tag1>=='.$integral_song_hb );}
		echo '<font class="gray_prompt">&raquo;  '.$ts_pay."<br></font>";
		
		
		
	}
	//提交保存-结束-------------------------------------------------------------------------------------------------

}//while($rs=$sql->fetch_array())

if(!$dgid){exit($LG['payment.mall_order_24']);}


//完成提示-------------------------------------------------------------------------------------------------
if($lx=="pay"&&$ts_pay)
{
	$_SESSION["dgid"]='';//支付成功,清空所保存的勾选
}


$off_integral=0;//关闭积分系统:目的是隐藏"使用优惠"
?>