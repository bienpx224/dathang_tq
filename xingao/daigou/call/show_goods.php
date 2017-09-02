        <div class="portlet">
          <div class="portlet-title">
            <div class="caption"><i class="icon-reorder"></i><?=$LG['daigou.129'];//商品资料?></div>
            <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
          </div>
          <div class="portlet-body form">
            <!--表单内容-开始-->
 
 <table class="table table-striped table-bordered table-hover" >
  <thead>
    <tr>
      <th align="center"><?=$LG['daigou.godh'];//商品单号?></th>
      <th align="center"><?=$LG['status'];//状态?></th>
      <th align="center"><?=$LG['daigou.119'];//单价*数量*品牌折扣*手续费?></th>
      <th align="center"><?=$LG['front.86'];//颜色?></th>
      <th align="center"><?=$LG['front.85'];//尺寸?></th>
      <th align="center"><?=$LG['weight'];//重量?></th>
      <th align="center"><?=$LG['daigou.165'];//入库?></th>
      <th align="center"><?=$LG['daigou.179'];//发货?></th>
      <th align="center"><?=$LG['daigou.70_1'];//已退货/断退?></th>
      <th align="center"><?=$LG['barcode'];//商品条码?></th>
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

    <tr class="odd gradeX <?=!spr($gd['number'])?'gray2':''?>">
      <td align="center" valign="middle"><?=cadd($gd['godh'])?></td>
      <td align="center" valign="middle"><?=$gd['number']>0?daigou_memberStatus('',2,$gd):daigou_Status(10,2)?></td>
      <td align="center" valign="middle">
      <font title="<?=$LG['mall_order.Xcall_money_payment_9'];//单价?>"> <?=spr($gd['price']).$rs['priceCurrency']?> </font>
      *
      <font title="<?=$LG['number'];//数量?>"> <?=spr($gd['number'])?></font> 
      *
      <font title="<?=$LG['daigou.135'];//品牌折扣?>"> <?=spr($rs['brandDiscount'])?><?=$LG['fold']?></font>
      *
      <font title="<?=$LG['daigou.130'];//手续服务费?>"> <?=spr($rs['serviceRate'])?>%</font>
      = 
	  <?php 
	  echo $goodsFee=spr($gd['goodsFee']);	  $total_goodsFee+=$goodsFee;
	  ?>
      <?=$rs['priceCurrency']?>
      </td>
      
      <td align="center" valign="middle"><?=$gd['color']>0?classify($gd['color'],2):cadd($gd['colorOther'])?></td>
      <td align="center" valign="middle"><?=$gd['size']>0?classify($gd['size'],2):cadd($gd['sizeOther'])?></td>
      <td align="center" valign="middle"><?=$gd['weight']>0?spr($gd['weight']).$XAwt:''?></td>
      <td align="center" valign="middle"><?=spr($gd['inventoryNumber'])?></td>
      <td align="center" valign="middle"><?=spr($gd['deliveryNumber'])?></td>
      <td align="center" valign="middle"> 
		  <font class=" tooltips" data-container="body" data-placement="top" data-original-title="<?=$LG['daigou.69'].DateYmd($gd['lackStatusTime'],1)?><br><?=$LG['daigou.70'].$gd['numberRet']?>"> <?=daigou_lackStatus($gd['lackStatus'],2,$gd['numberRet'])?></font> 
      </td>
      <td align="center" valign="middle"><?=cadd($gd['barcode'])?></td>
    </tr>
    <tr>
      <td colspan="20" align="left" class="gray2">
        <?php if($gd['addid']){echo addressShow($gd['addid']);}?> 
        </td>
    </tr>
<?php }?>


  </tbody>
  
  <thead>
  <tr class="odd gradeX">
    <th align="center" valign="middle"><?=$LG['yundan.XexcelExport_simple_2']?>:<?=$gd_i?></th>
    <th align="center" valign="middle"></th>
    <th align="center" valign="middle "> 
	  <?=$LG['total'];//总计?>:<?=spr($total_goodsFee).$rs['priceCurrency']?>
      <?=$rs['freightFee']>0?'+'.spr($rs['freightFee']).$rs['priceCurrency']."({$LG['daigou.50']})":''?>
      =<strong><?=spr($total_goodsFee+$rs['freightFee']).$rs['priceCurrency']?></strong>
    </th>
    <th align="center" valign="middle"></th>
    <th align="center" valign="middle"></th>
    <th align="center" valign="middle"></th>
    <th align="center" valign="middle"></th>
    <th align="center" valign="middle"></th>
    <th align="center" valign="middle"></th>
    </tr>
  </thead>
</table>

           
            
          </div>
        </div>