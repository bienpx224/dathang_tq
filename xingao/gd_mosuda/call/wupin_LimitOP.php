<!--限制操作的列表:比如商城,代购-->

<table width="100%" class="table table-striped  table-hover" id="tableProduct">
<!--自动计算要加这个 id="tableProduct"-->
<thead>
<tr>
     <?=wupin_header_general()?>
     <th align="center" class="title"></th>
</tr>
</thead>
<?php
$j=0;
if($fromtable&&$fromid)
{	
	
	//下单时
	if($lx=='add')
	{
		global $weightEstimate;
		$weightEstimate=0;
		$checkbox_where=whereCHK(daigou_deliveryAsk('','',1),'and');
		
		$query_wp="select * from daigou_goods where goid in ({$fromid}) {$Mmy} {$checkbox_where} order by dgid asc,barcode asc";
		$sql_wp=$xingao->query($query_wp);
		while($gd=$sql_wp->fetch_array())
		{
			$j+=1;
			if($barcode!=$gd['barcode']){$wp=FeData('gd_mosuda','*',"record in (0,2) and barcode='{$gd['barcode']}'");$barcode=$gd['barcode'];}
			if(!$wp['gdid'])		{$notRecord.=cadd($gd['godh']).',';}
			if(!$gd['barcode'])		{$notBarcode.=cadd($gd['godh']).',';}
			if($tag){$_SESSION[$tag].=','.$wp['gdid'];}
			if($dgid!=$gd['dgid']){$rs=FeData('daigou','*',"dgid='{$gd['dgid']}'");$dgid=$gd['dgid'];}
			
			//赋值给wupin_form_gd
			$gd['price']*=exchange($rs['priceCurrency'],$XAScurrency);
			$wp['wupin_price']	= $gd['price'];
			$wp['wupin_number']	= $gd['inventoryNumber'];
			$wp['wupin_total']	= $gd['price']*$gd['inventoryNumber'];
			$wp['wupin_number_max']	= $gd['inventoryNumber'];//自定:数量最大限制
			$wp['wupin_dh']		= $gd['godh'];//自定:显示单号
			$wp['wupin_weight']	= $wp['weightGross']/$XAwtkg;//KG转网站单位
			
			$weightEstimate+=$wp['wupin_weight']*$gd['inventoryNumber'];
			?>
			<tr class="odd gradeX <?=$wp['record']==1?'red2':''?>" id="line" style="height:35px;">
			<input type="hidden" name="goid[]" value="<?=$gd['goid']?>">
			<?=wupin_form_gd($wp,$lx,$LimitOP=1)?>
			</tr>
			<?php
		}
		
		//未备案商品:停止并提示
		if($notRecord){$err_ppt.='\\n'.$LG['yundan.form_70'].$notRecord;}
		if($notBarcode){$err_ppt.='\\n'.$LG['yundan.form_71'].$notBarcode;}
		if($err_ppt){
			$err_ppt=$LG['yundan.form_72'].$err_ppt;
			exit ("<script>alert('{$err_ppt}');goBack();</script>");
		}
		
		
		
	
	//修改时
	}elseif($lx=='edit'){
	
		$query_wp="select * from wupin where fromtable='{$fromtable}' and fromid in ({$fromid}) and gdid<>'0' order by wupin_brand asc,wupin_name asc";
		$sql_wp=$xingao->query($query_wp);
		while($wp=$sql_wp->fetch_array())
		{
			$j+=1;
			if($tag){$_SESSION[$tag].=','.$wp['gdid'];}
			?>
			<tr class="odd gradeX <?=$wp['record']==1?'red2':''?>" id="line" style="height:35px;">
			<?=wupin_form_gd($wp,$lx,$LimitOP=2)?>
			</tr>
			<?php		
		}
		if($tag){$_SESSION[$tag]=DelStr($_SESSION[$tag],',',1);}
	}
}
?>
</table>

<span class="help-block" style="padding:20px;">
	<?php if($callFrom=='member'){?>
       &raquo; <?=$LG['gd.13'];//税收是按商品备案价格收取，而非您所填写的单价 (所填单价是用于物品保价)?><br>
    <?php }?>
    
	<?php if($LimitOP==2){?>
       &raquo; <?=$LG['yundan.29'];//为了方便与库存数量同步更新,禁止修改物品资料,如需修改,请删除该运单并重新下单?><br>
    <?php }?>
</span>
