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

//获取,处理
$dgid=par($_GET['dgid']);
$callFrom=par($_GET['callFrom']);
if(!$dgid){exit ("dgid{$LG['pptError']}");}

if($callFrom=='manage'){$pervar='daigou_se';require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');}
elseif($callFrom=='member'){require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/session.php');}
else{exit ($LG['yundan.Xcall_money_modal_1']);}

$rs=FeData('daigou','*',"dgid={$dgid} {$Mmy} {$Xwh}");//查询
if(!$rs['dgid']){exit ("dgid{$LG['pptError']}");}
?>
<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
			<h4 class="modal-title"><?=$LG['yundan.Xcall_money_modal_2'];//费用明细?></h4>
		</div>
		<div class="modal-body">
			<div class="row">
				<div class="col-md-12" align="left">
                
                
                
                
                
 <table class="table table-striped table-bordered table-hover" >
  <thead>
    <tr>
      <th align="center"><?=$LG['daigou.godh'];//商品单号?></th>
      <th align="center"><?=$LG['daigou.119'];//单价*数量*品牌折扣*手续费?></th>
      <th align="center"><?=$LG['front.86'];//颜色?></th>
      <th align="center"><?=$LG['front.85'];//尺寸?></th>
    </tr>
  </thead>
  
  <tbody>
<?php 
$gd_i=0;
$query_gd="select * from daigou_goods where dgid='{$rs['dgid']}' order by godh asc";
$sql_gd=$xingao->query($query_gd);
while($gd=$sql_gd->fetch_array())
{
	$gd_i++;
?>

    <tr class="odd gradeX">
      <td align="center" valign="middle"><?=cadd($gd['godh'])?></td>
      <td align="center" valign="middle">
      <font title="<?=$LG['mall_order.Xcall_money_payment_9'];//单价?>"> <?=spr($gd['price']).$rs['priceCurrency']?> </font>
      *
      <font title="<?=$LG['number'];//数量?>"> <?=spr($gd['number'])?></font> 
     
      <?php if($rs["brandDiscount"]>0){?>
      *
      <font title="<?=$LG['daigou.135'];//品牌折扣?>"> <?=spr($rs["brandDiscount"])?><?=$LG['fold']?></font>
      <?php }?>
      
      <?php if($rs["serviceRate"]>0){?>
      *
      <font title="<?=$LG['daigou.130'];//手续服务费?>"> <?=spr($rs["serviceRate"])?>%</font>
      <?php }?>
      
     = 
	  <?php 
	  echo $goodsFee=spr($gd['goodsFee']);	  $total_goodsFee+=$goodsFee;
	  ?>
      <?=$rs['priceCurrency']?>
      </td>
      
      <td align="center" valign="middle"><?=$gd['color']>0?classify($gd['color'],2):cadd($gd['colorOther'])?></td>
      <td align="center" valign="middle"><?=$gd['size']>0?classify($gd['size'],2):cadd($gd['sizeOther'])?></td>
    </tr>
   
<?php }?>


  </tbody>
  
  <tr class="odd gradeX">
    <td align="center" valign="middle"><?=$LG['yundan.XexcelExport_simple_2']?>:<?=$gd_i?></td>
    <td align="center" valign="middle "> 
	  <?=$LG['total'];//总计?>:<?=spr($total_goodsFee).$rs['priceCurrency']?>
      <?=$rs['freightFee']>0?'+'.$rs['freightFee']."({$LG['daigou.50']})":''?>
      =<strong><?=spr($total_goodsFee+$rs['freightFee']).$rs['priceCurrency']?></strong>
    </td>
    <td align="center" valign="middle"></td>
    <td align="center" valign="middle"></td>
    </tr>
</table>










<?php 
	$totalFee=daigou_totalFee($rs);//此单全部费用:价格币
	$totalPayTo=daigou_totalPay($rs,1);//已支付:支付币


	//是会员时,显示兑换会员账户币种
	if($rs['priceCurrency']!=$Mcurrency&&$Mcurrency&&!$totalPayTo)
	{
		echo ' <font class="gray_prompt2">('.$LG['daigou.137'].'<font class="red2">'.spr($totalFee*daigou_exchangeBase(exchange($rs['priceCurrency'],$Mcurrency),$Muserid)).$Mcurrency.'</font> )</font>';//换成主币
	}
	
	if($totalPayTo)
	{
	    echo '<br><br>';
		echo $LG['daigou.131'].$totalPayTo.$rs['toCurrency'].'';
		echo ' <font class="gray2">'.$LG['daigou.132'].'</font><br>';
		if($rs['pay']>0){echo daigou_payStatus($rs['pay']).':'.DateYmd($rs['payTime']);}
	}
	
?>

				</div>
			</div>
		</div>
		<div class="modal-footer">
			 <button type="button" class="btn btn-danger" data-dismiss="modal"> <?=$LG['close']?> </button>
		</div>
	</div>
	<!-- /.modal-content --> 
</div>
<!-- /.modal-dialog -->