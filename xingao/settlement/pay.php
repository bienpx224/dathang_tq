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
$pervar='settlement_ed';require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');
$headtitle="充值销账";$alonepage=1;//单页形式
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/head.php');
?>

<style>html{overflow-x:hidden;}</style>
<!----------------------------------------显示表单-开始------------------------------------------------>
<form action="?" method="post" class="form-horizontal form-bordered" name="xingao" style="width:620px;" onSubmit="return confirm('确认要销账吗？该操作不可恢复！');">
 
 <?php require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/settlement/call/pay.php');//处理?>
 
    <input name="lx" type="hidden" value="pay">
    <input name="fromtable" type="hidden" value="<?=$fromtable?>">
    <input name="userid" autocomplete="off"  type="hidden" value="<?=$userid?>">

    			  
 	<div class="form-group">
    <label class="control-label col-md-3">为防止误操作,请确认销账金额：</label>
    <div class="col-md-9 has-error">
       <input name="settlement_money" type="text" class="form-control input-small input_txt_red" required/><?=$XAmc?>
       <span class="gray2">(请填写“<?=$settlement_money?>”<?=$XAmc?>)</span>
    </div>
    </div>				  


    <div class="form-group">
    <label class="control-label col-md-3">账单信息：</label>
    <div class="col-md-9">
        <span class="gray2">
           <?=$bill?>
        </span>
        <span class="help-block">
        (负数是欠费，正数是退费)
        </span>
    </div>
    </div>
    
    
    <div class="form-group">
    <label class="control-label col-md-3">说明：</label>
    <div class="col-md-9">
       <span class="help-block">
	  
       <a class="btn btn-xs btn-info" href="/xingao/member/money_cz.php?username=<?=$mr['userid']?>&type=52&title=销账款&content=<?=$bill?>" style="color:#ffffff" target="_blank"><i class="icon-money"></i> 如果有收款，请先充值!</a>
       <br>
       <?php 
	   $toExchange=exchange($XAMcurrency,$mr['currency']);	$toMoney=spr(abs($settlement_money)*$toExchange);
	   if( $settlement_money>0||$mr['money']>=$toMoney)
	   {
		   $ok=1;
	   }else{
		   $ok=0;$arrears=$toMoney-$mr['money'];
	   }
	   ?>

        &raquo; 账户有<?=spr($mr['money']).$mr['currency']?> (<?=$ok?'足够支付':'<font class="red">余额不足,还差'.$arrears.$mr['currency'].'</font>'?>)<br>
        &raquo; 销账时将会从账户扣除或增加费用<br>
      </span>
    </div>
    </div>
  	
   <br>
    <button type="submit" class="btn btn-primary"> <i class="icon-credit-card"></i> 确定销账 </button>
</form>
<!----------------------------------------显示表单-结束------------------------------------------------>

<?php require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/foot.php');?>
