<?php
//积分消费功能------------------------------------------------------------------
/*
调用:
	$pay_money=500;
	$userid=$rs['userid'];
	$integral_lx="yundan";	
	$prefer=3//是否使用优惠
	require($_SERVER['DOCUMENT_ROOT'].'/xamember/payment/call/calc_integral.php');//输出$pay_money

*/

$integral_jian_hb=0;//本次要减的积分元
$integral_reduce+=$integral_user;//累积要减积分
$integral_user=0;//本次要使用的积分
if ($off_integral)
{ 
	//-------------------------------------------------计算积分消费-------------------------------------------
	if ($integral_use&&$prefer==3)//$integral_use从订单获取
	{
		$pay_ts="({$LG['payment.calc_integral_1']})";//默认提示
		
		if ($pay_money>$integral_5&&$pay_money>0)//大于抵消条件
		{
			//第一次执行时,获取会员账号的积分
			if(!CheckEmpty($integral_user_you))
			{
				if(!$userid){$userid=$Muserid;}
				$integral_user_you=FeData('member','integral',"userid='{$userid}'");
			}else{
				//不是第一次
				if($integral_song_hb>0)//加上要送的分
				{
					$integral_user_you+=$integral_song_hb;
					$integral_song_hb=0;
				}
				
				if($integral_reduce)//减去要减的分
				{
					$integral_user_you-=$integral_reduce;
				}
			}
			
			
			$integral_user=$integral_user_you;//下面计算用：可以用的分
			
			//积分兑换元
			if($integral_bili>0){$integral_jian_hb=spr($integral_user/$integral_bili);}else{$integral_jian_hb=0;}//$integral_bili从配置获取
		
			//如果有兑换元可消费
			if($integral_jian_hb>=0.01)
			{
				//要扣的金额 最大可以抵消的兑换元
				$integral_pay_money=$pay_money/$integral_1*$integral_2;//$integral_1,integral_2,integral_3从配置获取
				if ($integral_pay_money>$integral_3){$integral_pay_money=$integral_3;}
				
				//拥有的积分 最大可以抵消的兑换元
				if($integral_pay_money>0)
				{
					//如果兑换元大于可消费，就用最大可消费兑换元，并计算
					if($integral_jian_hb>$integral_pay_money){
						$integral_user=$integral_bili*$integral_pay_money;//要减的分
						$integral_jian_hb=$integral_pay_money;//要抵消的兑换元
					}else{
						$integral_user_now=$integral_bili*$integral_jian_hb;//要减的分(要反计算，不然会扣如1010分，实际只扣1000就可以)
						if ($integral_user>$integral_user_now){$integral_user=$integral_user_now;}
					}
					$pay_money-=$integral_jian_hb;
				}
				
				$pay_ts="";//可以消费,清空提示
			}else{
				$integral_user=0;
			}//if($integral_jian_hb>0.01)
			
		}//if ($pay_money>$integral_5){
	}//if ($integral_use){
	//--------------------------计算积分消费-结束------------------------------------
	
	
	
	
	
	

	
	
	//-------------------------------------------------计算送积分----------------------------------------------
	//获取增送比例 
	/*	
		$integral_4获取config.php：消费积分后是否还送分
	*/

	if($integral_lx=='yundan')
	{
		/*运单:$integral_to 只有0,1是否送分,没有2*/
		$integral_sbl=$integral_yundan;//送分比例
		if ($integral_to&&$integral_sbl>0)
		{
			if($integral_to&&($integral_4||(!$integral_4&&!$integral_jian_hb)))
			{
				$integral_song_hb=intval($pay_money)*$integral_sbl;
			}
		}
	}elseif($integral_lx=='mall'){
		/*商城:是否送分（$integral_to=1：无消费积分时赠送积分；2：都送分；0：不送分；）*/
		$integral_sbl=$integral_mall;//送分比例
		if ($integral_to&&$integral_sbl>0)
		{
			if(($integral_to==1&&$integral_4&&!$integral_jian_hb)||$integral_to==2)
			{
				$integral_song_hb=intval($pay_money)*$integral_sbl;
			}
		}
	}
	

	//echo $integral_song_hb.'<br>';
	//-------------------------------------------------计算送积分-结束--------------------------------

}//if ($off_integral==1){ 

if(!$integral_jian_hb){$integral_user=0;}
$pay_money=spr($pay_money);//输出
?>