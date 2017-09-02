<?php if($callFrom_op){//在列表中调用?>

    <!--************************************通用-操作菜单**********************************-->	
    <?php if(permissions('baoguo_ed','','manage',1) ){?>
        <a href="form.php?lx=edit&bgid=<?=$rs['bgid']?>" class="btn btn-xs btn-info"  target="_blank"><i class="icon-edit"></i> <?=$LG['showedit']?></a>
        
        <?php if( (spr($rs['status'])<=1 ||(spr($rs['status'])==9&&!$off_delbak)|| spr($rs['status'])==10)  && !$rs['ware']){?>
        <a href="save.php?lx=del&bgid=<?=$rs['bgid']?>" class="btn btn-xs btn-danger"  onClick="return confirm('<?=$LG['pptDelConfirm']//确认要删除所选吗?此操作不可恢复!?>');"><i class="icon-remove"></i> <?=$LG['del']?></a> 
        <?php }?>
          <br>
    <?php }?>
              
    <?php if(permissions('baoguo_ot','','manage',1) ){?>
        <div class="btn-group"> 
            <a class="btn btn-xs btn-default dropdown-toggle" href="#" data-toggle="dropdown"> <?=$LG['print']?> <i class="icon-angle-down"></i> </a>
            <ul class="dropdown-menu" style="min-width:110px;">
                <?php baoguo_print('',2,$rs['bgid'])?>
            </ul>
        </div>
    <?php }?>
    
    <?php memberMenu($rs['username'],$rs['userid'],'baoguo',$rs['bgid'],$rs['bgydh']);//会员账户操作菜单 ?>
        
    <!--操作-结束-->
    
    
    
    
    
    
    
    

<?php }else{//在底部调用?>

	<?php if(!$baoguo_qr){?>
	<option value="status,3" ><?=$LG['baoguo.Xcall_op_menu_1']?></option>    
	<?php }?> 
	
	<?php if($off_baoguo_op_02){?>
	<option value="op_02,<?=$zhi=2?>" ><?=baoguo_op_02($zhi)?></option>                    
	<option value="op_02,<?=$zhi=3?>" ><?=baoguo_op_02($zhi)?></option>                    
	<?php }?> 
	
	<?php if($off_baoguo_op_04){?>
	<option value="op_04,<?=$zhi=2?>" ><?=baoguo_op_04($zhi)?></option>                    
	<option value="op_04,<?=$zhi=3?>" ><?=baoguo_op_04($zhi)?></option>                    
	<?php }?> 
	
	<?php if($off_baoguo_op_06){?>
	<option value="op_06,<?=$zhi=2?>" ><?=baoguo_op_06($zhi)?> (<?=$LG['baoguo.Xcall_op_menu_2']?>)</option>
	<option value="op_06,<?=$zhi=3?>" ><?=baoguo_op_06($zhi)?></option>                    
	<?php }?> 
	
	<?php if($off_baoguo_op_07){?>
	<option value="op_07,<?=$zhi=2?>" ><?=baoguo_op_07($zhi)?></option>                    
	<option value="op_07,<?=$zhi=3?>" ><?=baoguo_op_07($zhi)?></option>                    
	<?php }?> 
	
	<?php if($off_baoguo_op_09){?>
	<option value="op_09,<?=$zhi=2?>" ><?=baoguo_op_09($zhi)?></option>                    
	<option value="op_09,<?=$zhi=3?>" ><?=baoguo_op_09($zhi)?></option>                    
	<?php }?> 
	
	<?php if($off_baoguo_op_10){?>
	<option value="op_10,<?=$zhi=2?>" ><?=baoguo_op_10($zhi)?></option>                    
	<option value="op_10,<?=$zhi=3?>" ><?=baoguo_op_10($zhi)?></option> 
	<?php }?> 
	
	<?php if($off_baoguo_op_11){?>
	<option value="op_11,<?=$zhi=2?>" ><?=baoguo_op_11($zhi)?></option>                    
	<option value="op_11,<?=$zhi=3?>" ><?=baoguo_op_11($zhi)?></option> 
	<?php }?> 
	
	<?php if($off_baoguo_th){?>
	<option value="th,<?=$zhi=2?>" ><?=baoguo_th($zhi)?></option>                    
	<option value="th,<?=$zhi=3?>" ><?=baoguo_th($zhi)?></option>                    
	<?php }?> 
	
	<?php if($ON_ware){?>
	<option value="ware,1" ><?=$LG['baoguo.Xcall_op_menu_3']?></option>
	<?php }?>

<?php }?>