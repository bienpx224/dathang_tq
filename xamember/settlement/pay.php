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
$pervar='off_settlement';require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/session.php');
$headtitle=$LG['settlement.pay_1'];//充值销账
$alonepage=1;//单页形式
require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/head.php');
?>

<style>html{overflow-x:hidden;}</style>
<!----------------------------------------显示表单-开始------------------------------------------------>
<form action="?" method="post" class="form-horizontal form-bordered" name="xingao" style="width:620px;" onSubmit="return confirm('{$LG['settlement.pay_2']}');">

<?php 
	$userid=$Muserid;$Xwh='';
	require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/settlement/call/pay.php');//处理
?>

    <input name="lx" type="hidden" value="pay">
    <input name="fromtable" type="hidden" value="<?=$fromtable?>">
    <input name="userid" autocomplete="off"  type="hidden" value="<?=$userid?>">
    
    
    
    
<!-- 	<div class="form-group">
    <label class="control-label col-md-3">为防止误操作,请确认销账金额：</label>
    <div class="col-md-9 has-error">
       <input name="settlement_money" type="text" class="form-control input-small input_txt_red" required/><?=$XAmc?>
       <span class="gray2">(请填写“<?=$settlement_money?>”<?=$XAmc?>)</span>
    </div>
    </div>				  
-->
	<input name="settlement_money" type="hidden" value="<?=$settlement_money?>">


    <div class="form-group">
    <label class="control-label col-md-3"><?=$LG['settlement.pay_3'];//账单信息：?></label>
    <div class="col-md-9">
        <span class="gray2">
           <?=$bill?>
        </span>
        <span class="help-block">
        (<?=$LG['settlement.pay_9'];//负数是欠费，正数是退费?>)
        </span>
    </div>
    </div>
    
    
    <div class="form-group">
    <div class="col-md-9">
       <span class="help-block">
       <?php 
	   $toExchange=exchange($XAMcurrency,$mr['currency']);	$toMoney=spr(abs($settlement_money)*$toExchange);
	   if( $settlement_money>0||$mr['money']>=$toMoney)
	   {
		   $ok=1;
	   }else{
		   $ok=0;$arrears=$toMoney-$mr['money'];
	   }
	   ?>
       
       <br>
        &raquo; <?=$LG['account']?><?=spr($mr['money']).$mr['currency']?> (<?=$ok?$LG['settlement.pay_10']:'<font class="red">'.$LG['settlement.pay_4'].$arrears.$mr['currency'].'</font>'?>)<br>
        &raquo; <?=$LG['settlement.pay_5'];//销账时将会从账户扣除或增加费用?><br>
      </span>
    </div>
    </div>
  	
   <br>
   
 <?php if($ok){?>
	<button type="submit"  class="btn btn-primary"> <i class="icon-ok"></i> <?=$LG['settlement.pay_6'];//账户支付?> </button>
<?php }else{?>	  
	<a class="btn btn-warning" href="/xamember/money/money_cz.php?lx=1&money=<?=$arrears?>" style="color:#ffffff" target="_blank"><i class="icon-money"></i> <?=$LG['settlement.pay_7'];//第一步:去充值?></a>
	<button type="submit"  class="btn btn-primary"> <i class="icon-ok"></i> <?=$LG['settlement.pay_8'];//第二步:已充值开始支付?> </button>
<?php }?>

</form>
<!----------------------------------------显示表单-结束------------------------------------------------>
