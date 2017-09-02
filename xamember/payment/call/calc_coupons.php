<?php 
/*
优惠券/折扣券查询----------------------------------------------------------------------------
调用:
	$coupons_calllx='query';
	$prefer=1/2
	$coupons_number
	$pay_money=500;
	$userid=$rs['userid'];
	$integral_lx='yundan';	
	require($_SERVER['DOCUMENT_ROOT'].'/xamember/payment/calc_coupons.php');//返回:$pay_money,$cpc['cpid'],$cpc['number']
*/
if($coupons_calllx=='query')
{
	//不能放外面
	$cpc['cpid']=0;
	$cpc['number']=0;
	$coupons_value=0;//计算好的可以用的元
	$coupons_use_number=0;//计算好的可以用的数量

	if(($prefer==1||$prefer==2)&&$coupons_number&&$pay_money>0)
	{
		if($integral_lx=='yundan')	{$usetypes='0,1';}
		elseif($integral_lx=='mall'){$usetypes='0,2';}
		
		if(!$coupons_cpid){$coupons_cpid=0;}
		if($prefer==1){$cp_order=" order by types asc,limitmoney asc,value desc";}
		elseif($prefer==2){$cp_order=" order by types desc,limitmoney asc,value asc";}
		
		$cpc=FeData('coupons','*',"limitmoney<={$pay_money} and cpid not in ({$coupons_cpid}) and status=0 and usetypes in ({$usetypes}) and userid='{$userid}' {$cp_order} ,number asc,getTime asc");
	
		if($cpc['cpid'])
		{
			if($cpc['types']==1){
				$pay_money-=spr($cpc['value']); 
				$coupons_value=spr($cpc['value']);//可用的元
				$coupons_use_number=1;//可用的数量
			}elseif($cpc['types']==2){
				$pay_money_now=$pay_money;
				$pay_money*=spr($cpc['value']/10); 
				$coupons_value=spr($pay_money_now-$pay_money);//可用的元
				$coupons_use_number=1;//可用的数量
			}
			
			//记录已使用,如果已使用不再用来计算
			$coupons_number_cpid='coupons_number_'.$cpc['cpid'];
			if(!CheckEmpty($$coupons_number_cpid)){$$coupons_number_cpid=$cpc['number'];}
			else{$$coupons_number_cpid--;}
			
			if($$coupons_number_cpid<=0){
				if($coupons_cpid){$coupons_cpid.=','.$cpc['cpid'];}else{$coupons_cpid=$cpc['cpid'];}
			}
			
			//减总共数量,当0时不再计算
			$coupons_number--;
		}
	}
}
?>