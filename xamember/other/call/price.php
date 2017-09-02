<div>
  
 <?php if($member_per[$Mgroupid]['ON_Mbaoguo']){?>
  <div class="portlet">
	<div class="portlet-title">
	  <div class="caption"><i class="icon-reorder"></i><strong><?=$LG['other.call_price_1'];//包裹服务?></strong></div>
	  <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
	</div>
	<div class="portlet-body form" style="display: block;"> 
	  <!--表单内容-->
	  <table class="table table-striped table-bordered table-hover"  style="margin-bottom:0px;">
		<tbody>
	   <?php if ($off_hx){ ?>
	   <tr>
		<td align="center"><?=$LG['other.call_price_2'];//合箱?></td>
		<td align="left">
<?php
if($member_per[$Mgroupid]['Price_hxsl']==0){
	echo '<font class="red">免费</font>';
}else{
	echo LGtag($LG['other.call_price_20_1'],
	'<tag1>=='.$member_per[$Mgroupid]['Price_hxsl'].'||'.
	'<tag2>==<font class="red">'.$member_per[$Mgroupid]['Price_hx1'].'</font>'.$XAmc.'||'.
	'<tag3>==<font class="red">'.$member_per[$Mgroupid]['Price_hx2'].'</font>'.$XAmc
 );
}
	  ?>
	  <!--
<?=LGtag($LG['other.call_price_20_1'],
	'<tag1>=='.$member_per[$Mgroupid]['Price_hxsl'].'||'.
	'<tag2>==<font class="red">'.$member_per[$Mgroupid]['Price_hx1'].'</font>'.$XAmc.'||'.
	'<tag3>==<font class="red">'.$member_per[$Mgroupid]['Price_hx2'].'</font>'.$XAmc
 );?>-->

          </td>
</tr>
	<?php }?>
	<?php if ($off_fx){ ?>
	  <tr>
		<td align="center"><?=$LG['other.call_price_4'];//分箱?></td>
		<td align="left">
<?php
if($member_per[$Mgroupid]['Price_fxsl']==0){
	echo '<font class="red">免费</font>';
}else{
	echo LGtag($LG['other.call_price_20_1'],
	'<tag1>=='.$member_per[$Mgroupid]['Price_fxsl'].'||'.
	'<tag2>==<font class="red">'.$member_per[$Mgroupid]['Price_fx1'].'</font>'.$XAmc.'||'.
	'<tag3>==<font class="red">'.$member_per[$Mgroupid]['Price_fx2'].'</font>'.$XAmc
 );
}
?>
<!--
<?=LGtag($LG['other.call_price_20_1'],
	'<tag1>=='.$member_per[$Mgroupid]['Price_fxsl'].'||'.
	'<tag2>==<font class="red">'.$member_per[$Mgroupid]['Price_fx1'].'</font>'.$XAmc.'||'.
	'<tag3>==<font class="red">'.$member_per[$Mgroupid]['Price_fx2'].'</font>'.$XAmc
 );?>-->

</td>
</tr>
	  <?php }?>
	  <?php if ($ON_ware){ ?>
	  <tr>
		<td align="center"><?=$LG['other.call_price_5'];//仓储?></td>
		<td align="left">
		<?php
if($member_per[$Mgroupid]['bg_ware_freeDays']==3650){
	echo '<font class="red">免费</font>';
}else{
?>
<?=$LG['other.call_price_21'];//免费?>
<font class="red">
<?=$member_per[$Mgroupid]['bg_ware_freeDays']?>
</font><?=$LG['other.call_price_22'];//天?>
<?php }?>
<div class="xa_border"></div>

<?=$LG['other.call_price_6'];//仓储价格?>
<?php
if($member_per[$Mgroupid]['bg_ware_price']==0){
	echo '<font class="red">免费</font>';
}else{
	?>
	<font class="red">
	<?=$member_per[$Mgroupid]['bg_ware_price']?>
	</font>
	<?=$XAmc?>
	<?=$LG['other.call_price_23'];///天?>
	<?php
}
?>
          </td>
</tr>
	  <?php } ?>
      
	  
	  <tr>
		<td align="center"><?=$LG['daigou.175'];//其他服务收费?></td>
		<td align="left">
        
<?php if ($off_baoguo_th){ ?>

<?=str_ireplace($LG['other.call_price_25'],'',baoguo_th(1))?>
<?php if($member_per[$Mgroupid]['Price_th']==0){
echo '<font class="red">免费</font>';
}else{
?>
<font class="red"><?=$member_per[$Mgroupid]['Price_th']?></font><?=$XAmc?><?=$LG['other.call_price_24'];//包裹?>
<?php
}
?>
<span class="xa_sep"> | </span>
<?php } ?>

<?php if ($off_baoguo_op_02){ ?>
<?=str_ireplace($LG['other.call_price_25'],'',baoguo_op_02(1))?>
<?php if($member_per[$Mgroupid]['Price_02']==0){
echo '<font class="red">免费</font>';
}else{
?>
<font class="red"><?=$member_per[$Mgroupid]['Price_02']?></font><?=$XAmc?><?=$LG['other.call_price_24'];//包裹?>
<?php
}
?>
<span class="xa_sep"> | </span>
<?php } ?>

<?php if ($off_baoguo_op_04){ ?>
<?=str_ireplace($LG['other.call_price_25'],'',baoguo_op_04(1))?>
<?php if($member_per[$Mgroupid]['Price_04']==0){
echo '<font class="red">免费</font>';
}else{
?>
<font class="red"><?=$member_per[$Mgroupid]['Price_04']?></font><?=$XAmc?><?=$LG['other.call_price_24'];//包裹?>
<?php
}
?>
<span class="xa_sep"> | </span>
<?php } ?>

<?php if ($off_baoguo_op_06){ ?>
<?=str_ireplace($LG['other.call_price_25'],'',baoguo_op_06(1))?>
<?php if($member_per[$Mgroupid]['Price_06']==0){
echo '<font class="red">免费</font>';
}else{
?>
<font class="red"><?=$member_per[$Mgroupid]['Price_06']?></font><?=$XAmc?><?=$LG['other.call_price_24'];//包裹?>
<?php
}
?>
<span class="xa_sep"> | </span>
<?php } ?>


<?php if ($off_baoguo_op_09){ ?>
<?=str_ireplace($LG['other.call_price_25'],'',baoguo_op_09(1))?>
<?php if($member_per[$Mgroupid]['Price_09']==0){
echo '<font class="red">免费</font>';
}else{
?>
<font class="red"><?=$member_per[$Mgroupid]['Price_09']?></font><?=$XAmc?><?=$LG['other.call_price_24'];//包裹?>
<?php
}
?>
<span class="xa_sep"> | </span>
<?php } ?>

<?php if ($off_baoguo_op_10){ ?>
<?=str_ireplace($LG['other.call_price_25'],'',baoguo_op_10(1))?>
<?php if($member_per[$Mgroupid]['Price_10']==0){
echo '<font class="red">免费</font>';
}else{
?>
<font class="red"><?=$member_per[$Mgroupid]['Price_10']?></font><?=$XAmc?><?=$LG['other.call_price_24'];//包裹?>
<?php
}
?>
<span class="xa_sep"> | </span>
<?php } ?>

<?php if ($off_baoguo_op_11){ ?>
<?=str_ireplace($LG['other.call_price_25'],'',baoguo_op_11(1))?>
<?php if($member_per[$Mgroupid]['Price_11']==0){
echo '<font class="red">免费</font>';
}else{
?>
<font class="red"><?=$member_per[$Mgroupid]['Price_11']?></font><?=$XAmc?><?=$LG['other.call_price_24'];//包裹?>
<?php
}
?>
<span class="xa_sep"> | </span>
<?php } ?>



        </td>
        </tr>
	 
	  
	  
	  </tbody>
	  </table>
	</div>
  </div>
  <!---->
 <?php }?>
 
  
  <div class="portlet">
	<div class="portlet-title">
	  <div class="caption"><i class="icon-reorder"></i><strong><?=$LG['other.call_price_7'];//运单服务?></strong>
<?=LGtag($LG['other.call_price_26'],
'<tag1>==<a href="/yundan/price.php" target="_blank">'
 );?>

      </div>
	  <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
	</div>
	<div class="portlet-body form" style="display: block;"> 
	  <!--表单内容-->
	  <table class="table table-striped table-bordered table-hover"  style="margin-bottom:0px;">
			<tbody>
	
		<?php if ($member_per[$Mgroupid]['baoguo_fx']){ ?>
	   <tr>
		<td align="center"><?=$LG['other.call_price_8'];//包裹分箱发货数量限制?></td>
		<td align="left">
		<font class="red"><?=$member_per[$Mgroupid]['baoguo_fx']?></font>
		<?=$LG['other.call_price_27']?>&nbsp;&nbsp; 
        <span class="gray2"><?=$LG['other.call_price_9'];//每个包裹最多可以分多少个包发货?></span>
		</td>
		</tr>
	<?php }?>
		<?php if ($member_per[$Mgroupid]['baoguo_fh']){ ?>
	   <tr>
		<td align="center"><?=$LG['other.call_price_10'];//包裹合箱发货数量限制?></td>
		<td align="left">
		<font class="red"><?=$member_per[$Mgroupid]['baoguo_fh']?></font>
		 <?=$LG['other.call_price_27']?>&nbsp;&nbsp; <span class="gray2"><?=$LG['other.call_price_11'];//一次最多可以多少个包裹合箱发货?></span>
		</td>
		</tr>
	<?php }?>
	<?php if ($member_per[$Mgroupid]['baoguo_fh2']){ ?>
	   <tr>
		<td align="center"><?=$LG['other.call_price_12'];//包裹发货重量限制?></td>
		<td align="left">
		<font class="red"><?=$member_per[$Mgroupid]['baoguo_fh2']?></font>
		<?=$XAwt?>&nbsp;&nbsp; <span class="gray2"><?=$LG['other.call_price_13'];//包裹发货最大重量?></span>
		</td>
		</tr>
	<?php }?>
    
	   <tr>
		<td align="center"><?=$LG['other.call_price_14'];//服务手续费?></td>
		<td align="left">
<?php if ($member_per[$Mgroupid]['Price_fh_hx_fee1']||$member_per[$Mgroupid]['Price_fh_hx_fee2']){ ?>
    <strong><?=$LG['other.call_price_28'];//从包裹下单才收费：?></strong> 
    
    <?php 
	if(!$member_per[$Mgroupid]['Price_fh_hx_fee1_way']){$fee_unit1=$LG['other.call_price_24'];}
	if(!$member_per[$Mgroupid]['Price_fh_hx_fee2_way']){$fee_unit2=$LG['other.call_price_24'];}
	?>
    <?=LGtag($LG['other.call_price_20'],
    '<tag1>=='.$member_per[$Mgroupid]['Price_fh_hxsl'].'||'.
    '<tag2>==<font class="red">'.$member_per[$Mgroupid]['Price_fh_hx_fee1'].'</font>'.$XAmc.$fee_unit1.'||'.
    '<tag3>==<font class="red">'.$member_per[$Mgroupid]['Price_fh_hx_fee2'].'</font>'.$XAmc.$fee_unit2.'||'.
    '<tag4>=='.$member_per[$Mgroupid]['Price_fh_feesl']
    );?>
   
    <span class="xa_border"></span>   
<?php }?>
   
   <?php 
   $Price_fh_wg_formula=$member_per[$Mgroupid]['Price_fh_wg_formula'];
   if ($Price_fh_wg_formula){
   ?>   
		<strong><?=$LG['other.call_price_16'];//按重量收费?></strong>
        (
		<?php 
		if($member_per[$Mgroupid]['Price_fh_wg_type']==1){echo $LG['other.call_price_17'];}
		elseif($member_per[$Mgroupid]['Price_fh_wg_type']==2){echo $LG['other.call_price_18'];}
		?>
        )
        ：
        
       <?php  
	   if($Price_fh_wg_formula==1){?>
            <font class="red"><?=$member_per[$Mgroupid]['Price_fh_wg_fee']?></font>
            <?=$XAmc?>/<?=$member_per[$Mgroupid]['Price_fh_wg']?><?=$XAwt?>
        <?php }elseif($Price_fh_wg_formula==2){
				echo '<br>';
				$Price_fh_wg_fee2=$member_per[$Mgroupid]['Price_fh_wg_fee2'];
				$Price_fh_wg_fee2=str_ireplace('=',$XAwt.'<=',$Price_fh_wg_fee2);
				echo TextareaToBr($Price_fh_wg_fee2);
		}?>
        
        
  <?php }?>
		</td>
		</tr>
	



<?php $name=yundan_service('op_bgfee1','name');	if($name){?>
	  <tr>
        <td align="center"><?=$name?></td>
		<td align="left">
       <?php config_yundan_serviceVal('op_bgfee1_val_fee');?>
		</td>
	  </tr>
<?php }?>	  
	  
<?php $name=yundan_service('op_bgfee2','name');	if($name){?>
	  <tr>
        <td align="center"><?=$name?></td>
		<td align="left">
       <?php config_yundan_serviceVal('op_bgfee2_val_fee');?>
		</td>
	  </tr>
<?php }?>	  


<?php $name=yundan_service('op_wpfee1','name');	if($name){?>
	  <tr>
        <td align="center"><?=$name?></td>
		<td align="left">
       <?php config_yundan_serviceVal('op_wpfee1_val_fee');?>
		</td>
	  </tr>
<?php }?>	  
	  
<?php $name=yundan_service('op_wpfee2','name');	if($name){?>
	  <tr>
        <td align="center"><?=$name?></td>
		<td align="left">
       <?php config_yundan_serviceVal('op_wpfee2_val_fee');?>
		</td>
	  </tr>
<?php }?>	  


	  
<?php $name=yundan_service('op_ydfee1','name');	if($name){?>
	  <tr>
        <td align="center"><?=$name?></td>
		<td align="left">
       <?php config_yundan_serviceVal('op_ydfee1_val_fee');?>
		</td>
	  </tr>
<?php }?>	  
	  
<?php $name=yundan_service('op_ydfee2','name');	if($name){?>
	  <tr>
        <td align="center"><?=$name?></td>
		<td align="left">
       <?php config_yundan_serviceVal('op_ydfee2_val_fee');?>
		</td>
	  </tr>
<?php }?>	  


<?php 
//此页配置调用
function config_yundan_serviceVal($field)
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	global $member_per,$Mgroupid;
	for ($i_val=1; $i_val<=10; $i_val++)
	{
		if($field=='op_bgfee1_val_fee'){$name=yundan_service('op_bgfee1',$i_val);		$unit="{$XAmc}{$LG['other.call_price_24']}；";}
		elseif($field=='op_bgfee2_val_fee'){$name=yundan_service('op_bgfee2',$i_val);	$unit="{$XAmc}{$LG['other.call_price_24']}；";}
		elseif($field=='op_wpfee1_val_fee'){$name=yundan_service('op_wpfee1',$i_val);	$unit="{$XAmc}{$LG['other.call_price_24']}；";}
		elseif($field=='op_wpfee2_val_fee'){$name=yundan_service('op_wpfee2',$i_val);	$unit="{$XAmc}{$LG['other.call_price_24']}；";}
		elseif($field=='op_ydfee1_val_fee'){$name=yundan_service('op_ydfee1',$i_val);	$unit="{$XAmc}；";}
		elseif($field=='op_ydfee2_val_fee'){$name=yundan_service('op_ydfee2',$i_val);	$unit="{$XAmc}；";}
		
		if(!$name){continue;}
		?>
		<?=$name?>
		<?php
		if($member_per[$Mgroupid][$field.$i_val]==0){
		echo '<font class="red">免费</font>';
		}else{
		?>
		<font class="red"><?=$member_per[$Mgroupid][$field.$i_val]?></font>
		<?=$unit?>
		<?php
		}
		?>
		<?php 
	}
}
?>	   
  
	  </tbody>

	  </table>
	</div>
  </div>
  


 <?php if($member_per[$Mgroupid]['daigou']){?>
  <div class="portlet">
	<div class="portlet-title">
	  <div class="caption"><i class="icon-reorder"></i><strong><?=$LG['daigou.167'];//代购服务?></strong></div>
	  <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
	</div>
	<div class="portlet-body form" style="display: block;"> 
	  <!--表单内容-->
	  <table class="table table-striped table-bordered table-hover"  style="margin-bottom:0px;">
		<tbody>

	  <tr>
		<td align="center"><?=$LG['daigou.172'];//代购手续费率?></td>
		<td align="left">
<?php
if($member_per[$Mgroupid]['dg_serviceRateWeb']==0){
?>
<font class="red">免费</font><?=$LG['daigou.173'];//线上电商?>
<?php
	  }else{
	?>
	<?=$LG['daigou.173'];//线上电商?><font class="red"><?=$member_per[$Mgroupid]['dg_serviceRateWeb']?></font>%
	<?php
	  }
	  ?>
        
        <span class="xa_sep"> | </span>
<?php
if($member_per[$Mgroupid]['dg_serviceRateShop']==0){
?>
<font class="red">免费</font><?=$LG['daigou.174'];//线下专柜?>
<?php }else{?>
<?=$LG['daigou.174'];//线下专柜?><font class="red"><?=$member_per[$Mgroupid]['dg_serviceRateShop']?></font>%
<?php }?>
        </td>
	  </tr>


	  <tr>
		<td align="center"><?=$LG['daigou.175'];//其他服务收费?></td>
		<td align="left">
<?php for ($i=1; $i<=4; $i++){?>
<?php if($member_per[$Mgroupid]['dg_serviceFee_'.$i]==0){
?>
<font class="red">免费</font><?=daigou_memberStatus($i)?>
<?php
}else{
	?>
	<?=daigou_memberStatus($i)?><font class="red"><?=$member_per[$Mgroupid]['dg_serviceFee_'.$i]?></font>
    <?=$XAmc?>
	<?php
}?>
<span class="xa_sep"> | </span>
<?php }?>                       
        </td>
	  </tr>



	  <?php if ($ON_ware){ ?>
	  <tr>
		<td align="center"><?=$LG['other.call_price_5'];//仓储?></td>
		<td align="left">
<?php if($member_per[$Mgroupid]['dg_ware_freeDays']==3650){
?>
<font class="red">免费</font>
<?php
}else{
?>
        <?=LGtag($LG['daigou.168'],'<tag1>==<font class="red">'.$member_per[$Mgroupid]['dg_ware_freeDays'].'</font>')?>
        
        <?php if($member_per[$Mgroupid]['dg_ware_volumePrice']){?>
        <div class="xa_border"></div>
        <?=LGtag($LG['daigou.169'],'<tag1>==<font class="red">'.$member_per[$Mgroupid]['dg_ware_volumeLimit'].$XAsz.'</font>||<tag2>==<font class="red">'.$member_per[$Mgroupid]['dg_ware_volumePrice'].$XAmc.'</font>/1'.$XAsz)?>
        <?php }?>
        
        <?php if($member_per[$Mgroupid]['dg_ware_weightPrice']){?>
        <div class="xa_border"></div>
        <?=LGtag($LG['daigou.170'],'<tag1>==<font class="red">'.$member_per[$Mgroupid]['dg_ware_weightLimit'].$XAwt.'</font>||<tag2>==<font class="red">'.$member_per[$Mgroupid]['dg_ware_weightPrice'].$XAmc.'</font>/1'.$XAwt)?>
        <?php }?>
        
        <?php if($member_per[$Mgroupid]['dg_ware_numberPrice']){?>
        <div class="xa_border"></div>
        <?=LGtag($LG['daigou.171'],'<tag1>==<font class="red">'.$member_per[$Mgroupid]['dg_ware_numberPrice'].'</font>')?>
        <?php }?>

<?php
}?>     
        </td>
	</tr>
	  <?php } ?>
      
      
	  
	  </tbody>
	  </table>
	</div>
  </div>
 <?php }?>
  <!----> 
</div>