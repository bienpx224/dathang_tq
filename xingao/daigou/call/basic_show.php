<!--基本资料----------------------------------------------------------------------------->
<?php if($call_basic){?>
	<div class="gray modal_border">
		<?php 
		echo '<font class=" tooltips" data-container="body" data-placement="bottom" data-original-title="'.$LG['daigou.types'].'">'.(classify($rs['types'],2)).'</font>';
		
		if($rs['addSource']){echo '<span class="xa_sep"> | </span><font class=" tooltips" data-container="body" data-placement="bottom" data-original-title="'.$LG['source'].'">'.daigou_addSource($rs['addSource']).'</font>';}
		
		if($callFrom=='manage'&&$rs['whcod']){echo '<span class="xa_sep"> | </span><font class=" tooltips" data-container="body" data-placement="bottom" data-original-title="'.$LG['Museric'].'">'.cadd($rs['whcod']).'</font>';}
		
		if($rs['addtime']){echo '<span class="xa_sep"> | </span><font class=" tooltips" data-container="body" data-placement="bottom" data-original-title="'.$LG['yundan.Xcall_basic_show_21'].'">'.DateYmd($rs['addtime'],1).'</font>';}
		
		?>
        
        
		<?php 
		$procurementAddress='';
		if($callFrom=='manage'&&permissions('daigou_ck','','manage',1)&&$rs['procurementAddress']){$procurementAddress=$LG['daigou.103'].':'.cadd($rs['procurementAddress']);}
			
        if( $rs['source']==1 && $rs['address']){
			if(!$procurementAddress){$procurementAddress=$LG['js.4'];}
            echo '<span class="xa_sep"> | </span><font class=" tooltips" data-container="body" data-placement="bottom" data-original-title="'.$procurementAddress.'"><a href="'.cadd($rs['address']).'" target="_blank">'.leng($rs['address'],30).'</a></font>';
        }else{
            if(!$procurementAddress){$procurementAddress=$LG['js.5'];}
			echo '<span class="xa_sep"> | </span><font class=" tooltips" data-container="body" data-placement="bottom" data-original-title="'.$procurementAddress.'">'.cadd($rs['address']).'</font>';
        }
		
		if($callFrom=='manage')
		{
			echo ' <a href="javascript:void(0)" class="btn btn-xs btn-default xacopy" data-clipboard-text="'.cadd($rs['address']).'" onClick="xacopy();">复制</a>
';
		}
        ?> 

    
	<?php
	if($callFrom=='manage'&&permissions('daigou_cg','','manage',1)&&$rs['procurementCost']>0&&have('6,7,8',spr($rs['status']),1))
	{
		$differ=spr(daigou_totalPay($rs)-$rs['procurementCost']);
		
		echo '<span class="xa_sep"> | </span><font class="'.($differ>0?'green':'red2').'"><font class=" tooltips" data-container="body" data-placement="bottom" data-original-title="'.$LG['daigou.151'].'">'.spr($rs['procurementCost']).$rs['priceCurrency'].'</font>';
		echo ' ('.($differ>0?'利润':'亏本').$differ.$rs['priceCurrency'].')</font>';
	}
    ?> 
		

	</div>
<?php }?>












<!--显示备注等文字内容------------------------------------------------------------------------------->
<?php if ($call_content&&$callFrom=='member'){?>
    <div class="gray modal_border"> <strong><?=$LG['daigou.94'];//我的备注?>：</strong>
	<?php 
	$zhi=cadd($rs['content']);
	if($zhi||!$callFrom_show)
	{
		if($callFrom_show)
		{
			echo TextareaToBr($zhi);
		}else{
			echo leng($zhi,100,"...");
			Modals($zhi,$title=$LG['daigou.94'],$time=$rs['addtime'],$nameid='content'.$rs['dgid'],$count=100);
		}
	}
	?>
	<?php if(!$callFrom_show&&$callFrom=='member'&&spr($rs['status'])!=10){?>	
	<a href="op.php?typ=content&field=content&dgid=<?=$rs['dgid']?>" target="iframe<?=$rs['dgid']?>" class="btn btn-xs btn-default"><?=$LG['edit'];//修改?></a>
	<?php }?>
	</div>
<?php }?>










<!--显示备注等文字内容------------------------------------------------------------------------------->
<?php if ($call_memberContent && ($callFrom=='member'||($callFrom=='manage'&&$rs['memberContent'])) ){?>
	<div class="gray modal_border"> <strong><?=$LG['daigou.95'];//会员留言?>：</strong>
	<?php
	$zhi=cadd($rs['memberContent']);
	if($zhi){
		if($callFrom_show)
		{
			echo TextareaToBr($zhi);
		}else{
			echo leng($zhi,100,"...");
			Modals($zhi,$title=$LG['mall_order.form_18'],$time=$rs['memberContentTime'],$nameid='memberContent'.$rs['dgid'],$count=100);
		}
	}?>
    
	<?php if($rs['memberContentNew']&&$callFrom=='manage'&&spr($rs['status'])!=10){?>	
	<a href="op_save.php?typ=New&field=memberContentNew&value=0&dgid=<?=$rs['dgid']?>" target="iframe<?=$rs['dgid']?>" class="btn btn-xs btn-default" style="margin-left:20px;"><i class="icon-check"></i> <?=$LG['daigou.96'];//已读?></a>
	<?php }?>

	<?php if(!$callFrom_show&&$callFrom=='member'&&spr($rs['status'])!=10){?>	
	<a href="op.php?typ=content&field=memberContent&dgid=<?=$rs['dgid']?>" target="iframe<?=$rs['dgid']?>" class="btn btn-xs btn-default"><?=$LG['edit'];//修改?></a>
	<?php }?>
	</div>
<?php }?>










<!--显示备注等文字内容------------------------------------------------------------------------------->
<?php if ($call_memberContentReply && ($callFrom=='manage'||($callFrom=='member'&&$rs['memberContentReply'])) ){?>
	<div class="gray modal_border"> <strong><?=$LG['daigou.98'];//回复会员?>：</strong>
	<?php
	$zhi=cadd($rs['memberContentReply']);
	if($zhi){
		if($callFrom_show)
		{
			echo TextareaToBr($zhi);
		}else{
			echo leng($zhi,100,"...");
			Modals($zhi,$title=$LG['daigou.98'],$time=$rs['memberContentReplyTime'],$nameid='memberContentReply'.$rs['dgid'],$count=100);
		}
	}?>
    
	<?php if($rs['memberContentReplyNew']&&$callFrom=='member'&&spr($rs['status'])!=10){?>	
	<a href="op_save.php?typ=New&field=memberContentReplyNew&value=0&dgid=<?=$rs['dgid']?>" target="iframe<?=$rs['dgid']?>" class="btn btn-xs btn-default" style="margin-left:20px;"><i class="icon-check"></i> <?=$LG['daigou.96'];//已读?></a>
	<?php }?>

	<?php if(!$callFrom_show&&$callFrom=='manage'&&spr($rs['status'])!=10){?>
	<a href="op.php?typ=content&field=memberContentReply&dgid=<?=$rs['dgid']?>" target="iframe<?=$rs['dgid']?>" class="btn btn-xs btn-default"><?=$LG['edit'];//修改?></a>
	<?php }?>
	</div>
<?php }?>











<!--显示备注等文字内容------------------------------------------------------------------------------->
<?php if ($call_sellerContent && ($callFrom=='seller'||($callFrom=='manage'&&$rs['sellerContent'])) ){?>
	<div class="gray modal_border"> <strong><?=$LG['daigou.100'];//供应商留言?>：</strong>
	<?php
	$zhi=cadd($rs['sellerContent']);
	if($zhi){
		if($callFrom_show)
		{
			echo TextareaToBr($zhi);
		}else{
			echo leng($zhi,100,"...");
			Modals($zhi,$title=$LG['daigou.100'],$time=$rs['sellerContentTime'],$nameid='sellerContent'.$rs['dgid'],$count=100);
		}
	}?>
    
	<?php if($rs['sellerContentNew']&&$callFrom=='manage'&&spr($rs['status'])!=10){?>	
	<a href="op_save.php?typ=New&field=sellerContentNew&value=0&dgid=<?=$rs['dgid']?>" target="iframe<?=$rs['dgid']?>" class="btn btn-xs btn-default" style="margin-left:20px;"><i class="icon-check"></i> <?=$LG['daigou.96'];//已读?></a>
	<?php }?>

	<?php if(!$callFrom_show&&$callFrom=='seller'&&spr($rs['status'])!=10){?>
	<a href="op.php?typ=content&field=sellerContent&dgid=<?=$rs['dgid']?>" target="iframe<?=$rs['dgid']?>" class="btn btn-xs btn-default"><?=$LG['edit'];//修改?></a>
	<?php }?>
	</div>
<?php }?>









<!--显示备注等文字内容------------------------------------------------------------------------------->
<?php if ($call_sellerContentReply && ($callFrom=='manage'||($callFrom=='seller'&&$rs['sellerContentReply'])) ){?>
	<div class="gray modal_border"> <strong><?=$LG['daigou.102'];//回复供应商?>：</strong>
	<?php
	$zhi=cadd($rs['sellerContentReply']);
	if($zhi){
		if($callFrom_show)
		{
			echo TextareaToBr($zhi);
		}else{
			echo leng($zhi,100,"...");
			Modals($zhi,$title=$LG['daigou.102'],$time=$rs['sellerContentReplyTime'],$nameid='sellerContentReply'.$rs['dgid'],$count=100);
		}
	}?>
    
	<?php if($rs['sellerContentReplyNew']&&$callFrom=='seller'&&spr($rs['status'])!=10){?>	
	<a href="op_save.php?typ=New&field=sellerContentReplyNew&value=0&dgid=<?=$rs['dgid']?>" target="iframe<?=$rs['dgid']?>" class="btn btn-xs btn-default" style="margin-left:20px;"><i class="icon-check"></i> <?=$LG['daigou.96'];//已读?></a>
	<?php }?>

	<?php if(!$callFrom_show&&$callFrom=='manage'&&spr($rs['status'])!=10){?>
	<a href="op.php?typ=content&field=sellerContentReply&dgid=<?=$rs['dgid']?>" target="iframe<?=$rs['dgid']?>" class="btn btn-xs btn-default"><?=$LG['edit'];//修改?></a>
	<?php }?>
	</div>
<?php }?>