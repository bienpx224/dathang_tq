<?php 
if($update_userid)
{
	$cp_where=" and userid='{$update_userid}'";
	$update_cp=1;
	
	
	//查询
	if(!$usetypes){$usetypes=0;}
	$cp=FeData('coupons',' count(*) as total,sum(`number`) as number',"status=0 and usetypes in ({$usetypes}) {$cp_where}");//优惠券/折扣券
	$cp1=FeData('coupons',' count(*) as total,sum(`number`) as number',"status=0 and usetypes in ({$usetypes}) and types=1 {$cp_where}");//优惠券
	$cp2=FeData('coupons',' count(*) as total,sum(`number`) as number',"status=0 and usetypes in ({$usetypes}) and types=2 {$cp_where}");//折扣券
	
}

if (update_time('coupons','-30 minutes'))//多久更新一次:1 week 3 days 7 hours 30 minutes 5 seconds
{
	$cp_where="";
	$update_cp=1;
}

if ($update_cp)
{
	$xingao->query("update coupons set status=2 where status=0 and duetime>0 and duetime<".time()." {$cp_where}");//过期更新
	$xingao->query("update coupons set status=10 where number<=0 {$cp_where}");//数量无效更新
	$xingao->query("update coupons set status=10 where value<=0 {$cp_where}");//价值无效更新
}
?>