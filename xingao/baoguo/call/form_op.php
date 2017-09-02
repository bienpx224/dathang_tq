<?php 
$off_baoguo='off_baoguo_'.$field;
$op_name='baoguo_'.$field;
if ($$off_baoguo){ ?>
	<div class="form-group">
	<label class="control-label col-md-2"><?=$name?></label>
	<div class="col-md-10">
	<select name="<?=$field?>" class="form-control input-small select2me" data-placeholder="空">
	 <?php $op_name($rs[$field],1);?>
	 </select>
	
	
	<input name="<?=$field?>_pay"  class="form-control input-xsmall popovers" data-trigger="hover" data-placement="top"  data-content="<?=$pay_ts?>" type="text" value="<?=spr($rs[$field.'_pay'])?>" /><?=$XAmc?>
	
	<input type="button" value="扣费/退费"
	class="btn btn-xs btn-warning"
	onClick="
	document.xingao.tjtype.value='<?=$field?>';
	document.xingao.target='_blank';
	document.xingao.action='money_czkf.php';
	czkf_submit();
	">
	
	<?php if($field=='op_06'){?>
		<span class="help-block">
		<?php 
		//文件上传配置
		$uplx='img';//img,file
		$uploadLimit='10';//允许上传文件个数(如果是单个，此设置无法，默认1)
		$inputname='op_06_img[]';//保存字段名，多个时加[]

		$off_water=0;//水印(不手工设置则按后台设置)
		$off_narrow=1;//是否裁剪
		//$img_w=$certi_w;$img_h=$certi_h;//裁剪尺寸：证件
		$img_w=$other_w;$img_h=$other_h;//裁剪尺寸：通用
		//$img_w=500;$img_h=500;//裁剪尺寸：指定
		include($_SERVER['DOCUMENT_ROOT'].'/public/uploadify/call.php');
		?>
		</span>
	<?php }?>
    
	<?php if($field=='th'){?><span class="help-block"><?=EnlargeImg($rs['th_img'],$rs['bgid'],1)?></span><?php }?>
	<?php if($op_ts){?><span class="help-block"><?=$op_ts?></span><?php }?>
    
    
	</div>
	</div>
<?php }?>
