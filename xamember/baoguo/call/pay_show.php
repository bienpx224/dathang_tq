<!--
不是必须用到的值:
$ts_money
$total_money
$ts
$ts_hou 后面提示
-->
<!----------------------------------------通用显示-开始------------------------------------------------>
<?php if($ts_money){?>
	<table>
	  <tr>
		<td><img src="/images/payment_48x48.png" /> </td>
		<td>
		<h4><?=$ts_money?></h4>
		</td>
	  </tr>
	</table>
<?php }?>

<!---查询账户是否够支付---->
<?php
$pay_zh="yes";
if($total_money)
{
	$mr=FeData('member','money,integral,groupid,currency',"userid='{$Muserid}'");
	$off_settlement=MemberSettlement('',$mr['groupid']);
	if(!$off_settlement)
	{
		$toExchange=exchange($XAMcurrency,$mr['currency']);	$toMoney=spr($total_money*$toExchange);
		
		$ts.='&raquo; '.$LG['account'].'<strong>'.$mr['money'].'</strong>'.$mr['currency'];
		$ts.=' ('.$LG['deduction'].'<font class="red2">'.$toMoney.$mr['currency'].'</font>)';
		
		if($mr['money']<$toMoney)
		{
			$no_money=spr($toMoney-$mr['money']);
			$ts.= '，'.$LG['baoguo.call_pay_show_4'].$no_money.$mr['currency'].'，<font color="#FF0000">'.$LG['baoguo.call_pay_show_1'].'</font><br>';
			$pay_zh="no";
		}else{
			$ts.= "，{$LG['baoguo.call_pay_show_3']}<br>";
		}
	}
}

$ts.=$ts_hou;
?>

<?php if($ts){?>
	<br>
	<p class="xats">
	<?=$ts?><br/>
	</p>
	<br>
<?php }?>


<?php if($pay_zh=="yes"){?>
	<button type="submit"  class="btn btn-primary"> <i class="icon-money"></i> <?=$LG['baoguo.call_pay_show_2'];//提交操作?> </button>
<?php }elseif($pay_zh=="no"){?>	  

	<a class="btn btn-warning" href="/xamember/money/money_cz.php?lx=1&money=<?=$no_money?>" style="color:#ffffff" target="_blank"><?=$LG['payment.index_12'];//第一步:去充值?></a>
    
	<button type="submit"  class="btn btn-primary"><?=$LG['payment.index_13'];//第二步:已充值开始支付?> </button>
    
    
    <?php if($ON_bankAccount){?>
	<a class="btn btn-default input-xmedium" href="/xamember/transfer/form.php?money=<?=$no_money?><?=$AutoPara?>" style="margin-left:30px;" target="_blank"><i class="icon-money"></i> <?=$LG['payment.index_131'];//也可以用转账充值?></a>
    <?php }?>
<?php }?>
<!----------------------------------------通用显示-结束------------------------------------------------>
