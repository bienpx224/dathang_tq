<?php if($callFrom_op){//在列表中调用?>

	<!--************************************未入库时的-操作菜单**********************************-->	
	<?php if(spr($rs['status'])<=1  && !$rs['ware'] &&$rs['addSource']!=3&&$rs['addSource']!=4){	?>
	<a href="edit_form.php?lx=edit&bgid=<?=$rs['bgid']?>" class="btn btn-xs btn-info"><i class="icon-edit"></i> <?=$LG['edit']?></a>
	<?php } ?>
	
	<?php if($off_baoguo_zxyd&&spr($rs['status'])==0&&!$rs['ware']&&$rs['addSource']!=3&&$rs['addSource']!=4){?>
	<a href="/xamember/baoguo/delivery.php?typ=0&bg_zxyd=1&bgid=<?=$rs['bgid']?>" target="_blank" class="btn btn-xs btn-info">
    <i class="icon-plane"></i> <?=$LG['function.26'];//下单发货?></a> <!--spr($rs['status'])==1待入库,已全下单,不可改<=1 状态可以下单-->
	<?php } ?>

	<?php if( ((spr($rs['status'])<=1&&$rs['addSource']!=3) ||(spr($rs['status'])==9&&!$off_delbak)||spr($rs['status'])==10)  && !$rs['ware']){?>
	<a href="save.php?lx=del&bgid=<?=$rs['bgid']?>" class="btn btn-xs btn-danger" onClick="return confirm('<?=$LG['pptDelConfirm'];//确认要删除所选吗?此操作不可恢复!?>');"><i class="icon-remove"></i> <?=$LG['del']?></a>
	<?php } ?>
	
		
	<!--************************************未确认包裹的-操作菜单**********************************-->
	<?php if(spr($rs['status'])==2){?>
	<a href="op.php?bgid=<?=$rs['bgid']?>&field=status&value=3"  target="XingAobox" class="btn btn-xs btn-warning showdiv" title="<?=$LG['baoguo.call_op_menu_2'];//确认该包裹正确,确认后就可以对其操作?>"><i class="icon-ok"></i> <?=$LG['baoguo.call_op_menu_3'];//确认包裹?></a>
	<?php }?>

	
	<!--************************************待下运单/已确认包裹的-操作菜单**********************************-->
	<?php if(spr($rs['status'])==3 && $rs['th']!=2 && !$rs['ware']){//已确认包裹,并且不是已退货?>
	
	<div class="btn-group"> <a class="btn  btn-info dropdown-toggle" href="#" data-toggle="dropdown"> <?=$LG['op']?> <i class="icon-angle-down"></i> </a>
	<ul class="dropdown-menu dropdown-menu_baoguo" style="min-width:120px;">
		<?php 
		baoguo_op('fahuo',$rs);//下单发货
		baoguo_op('edit_wupin',$rs);//编辑物品
		baoguo_op('hx',$rs); //合箱
		baoguo_op('fx',$rs); //分箱
		
		echo '<li style="height:10px"></li>';
		baoguo_op('02',$rs); //验货
		baoguo_op('09',$rs);//清点
		baoguo_op('06',$rs);//拍照
		baoguo_op('07',$rs);//减重
		baoguo_op('10',$rs);//复称
		baoguo_op('11',$rs);//空隙
		baoguo_op('04',$rs);//转移仓库
		baoguo_op('th',$rs);//退货
		echo '<li style="height:10px"></li>';
		
		baoguo_op('ware',$rs);//仓储
		baoguo_op('tra_user',$rs);//转移会员
		?>
	
	</ul>
	</div>
	<?php }?>
	
	<!--************************************仓储的-操作菜单**********************************-->
	<?php if($ON_ware){?>
	<?php if($rs['ware']){?>
	<a href="op.php?bgid=<?=$rs['bgid']?>&field=ware&value=0"  target="XingAobox" class="btn btn-xs btn-info showdiv" ><i class="icon-eject"></i> <?=$LG['baoguo.call_op_menu_4'];//取出?></a>
	<?php }?>
	<?php }?>
	
	
	
	<!--操作-结束-->

<?php }else{//在底部调用?>

	<?php if(!$baoguo_qr){?>
	<option value="status,3"><?=$LG['baoguo.call_op_menu_3'];//确认包裹?></option>    
	<?php }?> 
	
	<?php if($off_baoguo_op_02){?>
	<option value="op_02,<?=$zhi=1?>" ><?=baoguo_op_02($zhi)?></option>                    
	<option value="op_02,<?=$zhi=10?>" ><?=baoguo_op_02($zhi)?></option>                    
	<?php }?> 
	
	<?php if($off_baoguo_op_04){?>
	<option value="op_04,<?=$zhi=1?>" ><?=baoguo_op_04($zhi)?></option>                    
	<option value="op_04,<?=$zhi=10?>" ><?=baoguo_op_04($zhi)?></option>                    
	<?php }?> 
	
	<?php if($off_baoguo_op_06){?>
	<option value="op_06,<?=$zhi=1?>" ><?=baoguo_op_06($zhi)?></option>                    
	<option value="op_06,<?=$zhi=10?>" ><?=baoguo_op_06($zhi)?></option>                    
	<?php }?> 
	
	<?php if($off_baoguo_op_07){?>
	<option value="op_07,<?=$zhi=1?>" ><?=baoguo_op_07($zhi)?></option>                    
	<option value="op_07,<?=$zhi=10?>" ><?=baoguo_op_07($zhi)?></option>                    
	<?php }?> 
	
	<?php if($off_baoguo_th){?>
	<option value="th,<?=$zhi=1?>" ><?=baoguo_th($zhi)?></option>                    
	<option value="th,<?=$zhi=10?>" ><?=baoguo_th($zhi)?></option>                    
	<?php }?> 
	
	<?php if($off_baoguo_op_11){?>
	<option value="op_09,<?=$zhi=1?>" ><?=baoguo_op_09($zhi)?></option>                    
	<option value="op_09,<?=$zhi=10?>" ><?=baoguo_op_09($zhi)?></option>                    
	<?php }?> 
	
	<?php if($off_baoguo_op_10){?>
	<option value="op_10,<?=$zhi=1?>" ><?=baoguo_op_10($zhi)?></option>                    
	<option value="op_10,<?=$zhi=10?>" ><?=baoguo_op_10($zhi)?></option> 
	<?php }?> 
	
	<?php if($off_baoguo_op_11){?>
	<option value="op_11,<?=$zhi=1?>" ><?=baoguo_op_11($zhi)?></option>                    
	<option value="op_11,<?=$zhi=10?>" ><?=baoguo_op_11($zhi)?></option> 
	<?php }?> 
	
	<?php if($ON_ware){?>
	<option value="ware,1"><?=$LG['baoguo.call_op_menu_5'];//仓储?></option>
	<?php }?>

	<?php if($off_tra_user){?>
	<option value="tra_user"><?=$LG['baoguo.call_op_menu_6'];//转移会员?></option>
	<?php }?>

<?php }?>