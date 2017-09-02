<!--显示操作状态-->
<?php if(spr($rs['status'])>1){?>
<div class="gray modal_border">
<?php 
	baoguo_op_show('ware',$rs);//仓储
	baoguo_op_show('hx',$rs);//合货
	baoguo_op_show('fx',$rs);//分货
	baoguo_op_show('th',$rs);//退货
	baoguo_op_show('04',$rs);//转移仓库
	$zhi=cadd($rs['op_04_warehouse']);
	if($zhi&&$rs['op_04']!=2){echo '('.$LG['baoguo.call_basic_show_2'].warehouse($zhi).')';}
	baoguo_op_show('02',$rs);//验货
	baoguo_op_show('06',$rs);//拍照
	baoguo_op_show('07',$rs);//减重
	baoguo_op_show('09',$rs);//清点
	baoguo_op_show('10',$rs);//复称
	baoguo_op_show('11',$rs);//填空隙
?>
</div>
<?php }?>


<!--物品概况-->
<?php 
$zhi=goodsdescribe2('baoguo',$rs['bgid']);
if($zhi){
?>
<div class="gray modal_border"> <strong><?=$LG['baoguo.call_basic_show_4']?>：</strong>
	<?php 
	if($callFrom_show)
	{
		echo TextareaToBr($zhi);
	}elseif($rs['hx']!=2){
		echo leng($zhi,100,"...");
		Modals($zhi,$title=$LG['baoguo.call_basic_show_4'],$time='',$nameid='wp'.$rs['bgid'],$count=100);
	}?>
</div>
<?php }?>

<!--显示备注等文字内容-->
<?php if($rs['old_username']||$rs['odid']){?><!--2017.06.17:删除dgid-->
<div class="gray modal_border"> <strong><?=$LG['baoguo.call_basic_show_5'];//系统备注：?></strong>
	<?php 
	if($rs['old_username'])
	{
		echo '<a class="tooltips" data-container="body" data-placement="top" data-original-title="'.$LG['baoguo.call_basic_show_17'].DateYmd($rs['tra_user_time']).'">';
		if($rs['tra_user_type']){
			echo $LG['baoguo.call_basic_show_6'].cadd($rs['old_username']).' ('.$rs['old_userid'].')';
		}else{
			echo $LG['baoguo.call_basic_show_7'].cadd($rs['old_username']).' ('.$rs['old_userid'].') '.$LG['baoguo.call_basic_show_18'];
		}
		echo '</font>';
	}
	?>

	<?php if($rs['odid']&&$off_mall){?>
	<span class="xa_sep"> | </span>
	<?=$LG['baoguo.call_basic_show_19']?>
	<?=$rs['odid']?>
	<?php }?>
	
	<!--2017.06.17:删除dgid-->
</div>
<?php }?>
<!---->

<?php if($rs['content']){?>	
    <div class="gray modal_border"> <strong><?=$LG['baoguo.call_basic_show_8'];//会员备注：?></strong>
    <?php 
    $zhi=cadd($rs['content']);
    if($zhi)
    {
        if($callFrom_show)
        {
            echo TextareaToBr($zhi);
        }else{
            echo leng($zhi,100,"...");
            Modals($zhi,$title=$LG['baoguo.call_basic_show_9'],$time=$rs['addtime'],$nameid='content'.$rs['bgid'],$count=100);
        }
    }
    ?>
    
    <?php if(!$callFrom_show&&$callFrom=='member'){?>	
        <a href="../Tool/edit.php?typ=1&table=baoguo&fieldEdit=content&fieldId=bgid&id=<?=$rs['bgid']?>" target="XingAobox" class="btn btn-xs btn-default showdiv"><?=$LG['edit'];//修改?></a>
    <?php }?>
    </div>
<?php }?>

<!---->

<?php
$zhi=cadd($rs['hx_requ']);
if($zhi){
?>
<div class="gray modal_border"> <strong><?=$LG['baoguo.call_basic_show_11']?>：</strong>
	<?php 
	if($callFrom_show)
	{
		echo TextareaToBr($zhi);
	}elseif($rs['hx']!=2){
		echo leng($zhi,100,"...");
		Modals($zhi,$title=$LG['baoguo.call_basic_show_11'],$time='',$nameid='hx_requ'.$rs['bgid'],$count=100);
	}?>
</div>
<?php }?>

<!---->

<?php
$zhi=cadd($rs['fx_requ']);
$num=arrcount(cadd($rs['fx_wupin']));
if($rs['fx']&&($zhi||$num)){
?>
<div class="gray modal_border"> <strong><?=$LG['baoguo.call_basic_show_12'];//分箱要求：?></strong>
	<?php 
	
	if($callFrom_show)
	{
		echo TextareaToBr($zhi);
		if($num){echo '<br>'.$LG['baoguo.call_basic_show_22'].$num;}
	}elseif($rs['fx']!=2){
		echo leng($zhi,100,"...");
		Modals($zhi,$title=$LG['baoguo.call_basic_show_23'],$time='',$nameid='fx_requ'.$rs['bgid'],$count=100);
		if($num){echo '<br>'.$LG['baoguo.call_basic_show_22'].$num;}
	}
	?>
</div>
<?php }?>

<!---->

<?php
$zhi=cadd($rs['th_requ']);
if($zhi){
?>
<div class="gray modal_border"> <strong><?=$LG['baoguo.call_basic_show_14']?>：</strong>
	<?php 
	if($callFrom_show)
	{
		echo TextareaToBr($zhi);
		ShowImg($rs['th_img']);
	}elseif($rs['th']!=2){
		echo leng($zhi,100,"...");
		Modals($zhi,$title=$LG['baoguo.call_basic_show_14'],$time='',$nameid='th_requ'.$rs['bgid'],$count=100);
	}?>
</div>
<?php }?>
<!---->


<?php
$zhi=cadd($rs['reply']);
if($zhi){
?>
<div class="gray modal_border"> <strong><?=$LG['baoguo.call_basic_show_15'];//仓库留言：?></strong>
	<?php 
	if($callFrom_show)
	{
		echo TextareaToBr($zhi);
	}else{
		echo leng($zhi,100,"...");
		Modals($zhi,$title=$LG['baoguo.call_basic_show_16'],$time=$rs['replytime'],$nameid='reply'.$rs['bgid'],$count=100);
	}?>
</div>
<?php }?>
