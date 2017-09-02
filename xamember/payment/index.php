<?php
/*
软件著作权：=====================================================
软件名称：兴奥国际物流转运网站管理系统(简称：兴奥转运系统)V7.0
著作权人：广西兴奥网络科技有限责任公司
软件登记号：2016SR041223
网址：www.xingaowl.com
本系统已在中华人民共和国国家版权局注册，著作权受法律及国际公约保护！
版权所有，未购买严禁使用，未经书面许可严禁开发衍生品，违反将追究法律责任！
*/
require_once($_SERVER['DOCUMENT_ROOT'].'/public/config_top.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/public/html.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/public/function.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/session.php');
$headtitle=$LG['payment.index_1'];//支付
if(!CheckEmpty($_GET['alonepage'])){$alonepage=1;}//是否单页形式
require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/head.php');

//获取,处理-----------------------------------------------------------------------------------------------
$lx=par($_REQUEST['lx']);
$fromtable=par($_REQUEST['fromtable']);
$field=par($_REQUEST['field']);
$payid=par(ToStr($_REQUEST['payid']));
$prefer=spr($_REQUEST['prefer']);

$search="lx={$lx}&fromtable={$fromtable}&field={$field}&payid={$payid}&alonepage={$_GET['alonepage']}";

$mr=FeData('member','money,integral,groupid,currency',"userid='{$Muserid}'");
$off_settlement=MemberSettlement('',$mr['groupid']);

if(!$payid)
{
	switch($fromtable)
	{
		case 'mall_order':
			$payid=$_SESSION["odid"];
		break;
		
		case 'yundan':
			$payid=$_SESSION["ydid"];
		break;
		
		case 'daigou':
			$payid=$_SESSION["dgid"];
		break;
	}
}

?>
<style>html{overflow-x:hidden;}</style>
<form action="?" method="post" class="form-horizontal form-bordered" name="XingAoForm" style="width:620px;">
	<input name="lx" type="hidden" value="pay">
	<input name="fromtable" type="hidden" value="<?=$fromtable?>">
	<input name="field" type="hidden" value="<?=$field?>">
	<input name="payid" type="hidden" value="<?=$payid?>">
<?php
//显示表单和保存-----------------------------------------------------------------------------------------------
if(!$payid)
{	
	exit ($LG['payment.index_2']);
}

switch($fromtable)
{
	case 'mall_order':
		require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/payment/mall_order.php');
	break;
	
	case 'yundan':
		require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/payment/yundan.php');
	break;
	
	case 'daigou':
		require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/payment/daigou.php');
		
		//费用不足时，使用在线转账后是否支持自动支付
		$AutoPara="&OnAutoPay=1&fromtable={$fromtable}&fromid={$payid}";//自动支付相关参数
	break;
	
	default:
		exit('fromtable '.$LG['pptError']);
}


//扣费完后提示
if($lx=='pay'&&$ts_pay)
{
	exit('<strong>'.$LG['payment.index_3'].'</strong>');
}
?>
	<h4>
	<strong><?=$LG['payment.index_14']//应付?><font class="red" style="font-size:18px;"><?=$pay_money_total1?></font><?=!$payCurrency?$XAmc:$Mcurrency?></strong>
    <?php 
	if($coupons_value_all>0)
	{
		echo '<br>';
		echo LGtag($LG['payment.index_15'],
			'<tag1>==<font class="red">'.$coupons_value_all.'</font>'.$XAmc.'||'.
			'<tag2>=='.$coupons_use_number_all.'||'.
			'<tag3>==<font color="#FF0000" style="font-size:25px;"><strong>'.$pay_money_total2.'</strong></font>'.$XAmc
		 );
	}
	
	if($integral_jian_hb_all>0)
	{
		echo '<br>';
		echo LGtag($LG['payment.index_16'],
			'<tag1>==<font class="red">'.$integral_jian_hb_all.'</font>'.$XAmc.'||'.
			'<tag2>=='.$integral_user_all.'||'.
			'<tag3>==<font color="#FF0000" style="font-size:25px;"><strong>'.$pay_money_total2.'</strong></font>'.$XAmc
		 );
	}
	?>
    </h4>
<div style="
    margin-top:10px; 
    margin-bottom:10px;
    border-width: 1px;
    border-style: dashed;
    border-color: #cccccc;
"></div>
<?php
//显示支付内容-----------------------------------------------------------------------------------------------

if($pay_money_total1<$pay_money_total2){$pay_money_total2=$pay_money_total1;}

if($prefer==1||$prefer==2)
{
	if((int)$cp_dixiao_yes>0){echo '<font class="gray_prompt">&raquo; '.$LG['payment.index_4'].$cp_dixiao_yes.'</font><br>';}
	if((int)$cp_dixiao_no>0){echo '<font class="gray_prompt">&raquo; '.$LG['payment.index_5'].$cp_dixiao_no.'</font><br>';}
}

if($off_integral&&$prefer==3)
{
	if((int)$dixiao_yes>0){echo '<font class="gray_prompt">&raquo; '.$LG['payment.index_6'].$dixiao_yes.'</font><br>';}
	if((int)$dixiao_no>0){echo '<font class="gray_prompt">&raquo; '.$LG['payment.index_7'].$dixiao_no.'</font><br>';}
?>
  <a href="javascript:void(0)" onMouseOver="document.getElementById('hide').style.display='block'" onMouseOut="document.getElementById('hide').style.display='none'">&raquo; <?=$LG['payment.index_8']?></a>
  <div id="hide" style="display:none;" class="gray_prompt"> 
	<?=LGtag($LG['payment.index_17'],
        '<tag1>=='.$integral_bili.'||'.
        '<tag2>=='.$XAmc.'||'.
        '<tag3>=='.$integral_1.$XAmc.'||'.
        '<tag4>=='.$integral_2.'||'.
        '<tag5>=='.$integral_3.'||'.
        '<tag6>=='.$integral_5.$XAmc
     );?>
  <br>
 </div>
<?php }?>


<?php
//查询账户是否够支付-----------------------------------------------------------------------------------------------
$pay_zh='yes';
if(!$off_settlement)
{
	//计算的是否已是会员支付币:不是时再兑换
	if(!$payCurrency)
	{
		$toExchange=exchange($XAMcurrency,$mr['currency']);	$toMoney=spr($pay_money_total2*$toExchange);
	}else{
		$toMoney=$pay_money_total2;
	}
	
	$ts.='&raquo; '.$LG['account'].'<strong>'.$mr['money'].'</strong>'.$mr['currency'];
	$ts.=' ('.$LG['deduction'].'<font class="red2">'.$toMoney.$mr['currency'].'</font>)';
	
	if($mr['money']<$toMoney)
	{
		$no_money=spr($toMoney-$mr['money']);
		$ts.= ",{$LG['pptWorse']}".$no_money.$mr['currency'].'，<font color="#FF0000">'.$LG['pptNotPay'].'</font>';
		$pay_zh='no';	
	}else{
		$ts.= ",{$LG['pptCanPay']}";
	}
}


?>

<p>
<?=$ts?>
<br/>
<br/>
<?php if($field!='tax_money'&&($cp1['number']||$cp2['number']||($off_integral&&$mr['integral'])  )){?>
<div class="radio-list">
    <?=$LG['payment.index_18']?><br>
    
    <?php if($cp1['number']){?>
    <label>
    <input type="radio" name="prefer" value="1" <?=$prefer==1?'checked':''?> onClick="location.href='?<?=$search?>&prefer=1';"> <?=LGtag($LG['payment.index_19'],'<tag1>=='.$cp1['number']);?><br>
    </label>
    <?php }?>
    

    <?php if($cp2['number']){?>
    <label>
    <input type="radio" name="prefer" value="2" <?=$prefer==2?'checked':''?> onClick="location.href='?<?=$search?>&prefer=2';"> <?=LGtag($LG['payment.index_20'],'<tag1>=='.$cp2['number']);?><br>
    </label>
    <?php }?>
    
    <?php if($off_integral){?>
    <label>
    <input type="radio" name="prefer" value="3" <?=$prefer==3?'checked':''?> onClick="location.href='?<?=$search?>&prefer=3';"> <?=LGtag($LG['payment.index_21'],'<tag1>=='.$mr['integral']);?><br>
    </label>
    <?php }?>
    
    <label>
    <input type="radio" name="prefer" value="0" <?=!$prefer?'checked':''?> onClick="location.href='?<?=$search?>&prefer=0';"> <?=$LG['useNot']?>
    </label>
    <br/>
</div>
<?php }?>
<br/>
</p>

<!--------------------------------------------------------------------------------------------------->


<?php if($pay_zh=="yes"){?>
	<button type="submit"  class="btn btn-primary"> <i class="icon-ok"></i> <?=$LG['payment.index_11'];//账户支付?> </button>
    
<?php }elseif($pay_zh=="no"){?>	  

	<a class="btn btn-warning" href="/xamember/money/money_cz.php?lx=1&money=<?=$no_money?>" style="color:#ffffff" target="_blank"><?=$LG['payment.index_12'];//第一步:去充值?></a>
    
	<button type="submit"  class="btn btn-primary"><?=$LG['payment.index_13'];//第二步:已充值开始支付?> </button>
    
    
    <?php if($ON_bankAccount){?>
	<a class="btn btn-default input-xmedium" href="/xamember/transfer/form.php?money=<?=$no_money?><?=$AutoPara?>" style="margin-left:30px;" target="_blank"><i class="icon-money"></i> <?=$LG['payment.index_131'];//也可以用转账充值?></a>
    <?php }?>
    
<?php }?>

</form>

<?php require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/foot.php');?>
