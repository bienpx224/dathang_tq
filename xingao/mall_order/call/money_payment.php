<?php 
//后台,会员中心通用
$total_price=spr( spr($rs['price'])*$rs['number'] + spr($rs['price_other']));
$payment=spr($rs['payment']);
$pay=$rs['pay'];
$payment_time=$rs['payment_time'];

if(!$total_price)
{
	$pay_status= '<span class="label label-sm label-default">'.$LG['mall_order.Xcall_money_payment_1'].'</span>';
}
elseif(!$rs['pay'])
{
	$pay_status= '<span class="label label-sm label-default">'.$LG['mall_order.Xcall_money_payment_2'].'</span>';
}
elseif($pay&&$total_price>$payment)
{
	$pay_status= '<span class="label label-sm label-warning" title="'.$LG['mall_order.Xcall_money_payment_3'].($total_price-$payment).$XAmc.'">'.$LG['mall_order.Xcall_money_payment_4'].'</span>';
}
elseif($pay&&$total_price<$payment)
{
	$pay_status= '<span class="label label-sm label-danger" title="'.$LG['mall_order.Xcall_money_payment_5'].($payment-$total_price).$XAmc.'">'.$LG['mall_order.Xcall_money_payment_6'].'</span>';
}
elseif($pay&&$total_price==$payment)
{
	$pay_status= '<span class="label label-sm label-success" title="'.$LG['mall_order.Xcall_money_payment_7'].DateYmd($payment_time,1).'">'.$LG['mall_order.Xcall_money_payment_8'].'</span>';
}
?>


<font title="<?=$LG['mall_order.Xcall_money_payment_9'];//单价?>"><?=spr($rs['price']).$XAmc?></font>
 * 
<font title="<?=$LG['number'];//数量?>"><?=$rs['number'].classify($rs['unit'],2)?></font>
<?php if(spr($rs['price_other'])>0){?>
+ 
<font title="<?=$LG['mall_order.Xcall_money_payment_17'].cadd($rs['price_otherwhy'])?>"><?=spr($rs['price_other']).$XAmc?></font>
<?php }?>
= 
<font class="show_price" title="<?=$LG['mall_order.Xcall_money_payment_10'];//共需付?>"><?=$total_price?></font><?=$XAmc?>


<br>
		
<font class="gray">
	<font title="<?=$LG['mall_order.list_6'];//订购时间?>"><?=DateYmd($rs['addtime'],1)?></font>

<?php if($off_integral){?>
	<!--可消费积分情况-->
	<?php if($rs['integral_use']){echo $LG['mall_order.Xcall_money_payment_11'];}else{echo $LG['mall_order.Xcall_money_payment_18'];}?>
	
	<!--送积分情况-->
	<?php if($integral_mall>0){?>
		<span title="<?php if($rs['integral_to']==1&&$rs['integral_use']==1){ echo $LG['mall_order.Xcall_money_payment_13'].$integral_mall;}
		elseif($rs['integral_to']==1||$rs['integral_to']==2){ echo $LG['mall_order.Xcall_money_payment_15'].$integral_mall;}
		elseif(!$rs['integral_to']){ echo $LG['mall_order.Xcall_money_payment_16'];}
		?>">(<?php if(!$rs['integral_to']){ echo $LG['mall_order.Xcall_money_payment_16'];}else{echo $LG['mall_order.Xcall_money_payment_19'];}?>)</span>
	<?php }?>
	
<?php }?>
</font>
