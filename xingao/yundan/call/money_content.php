<?php 
if(spr($rs['money'])>0){
	//会员资料
	$groupid=FeData('member','groupid',"userid='{$rs['userid']}'");
	$channel=$rs['channel'];
	$warehouse=$rs['warehouse'];
	require($_SERVER['DOCUMENT_ROOT'].'/xingao/yundan/call/get_price.php');//获取价格
	

	//增值服务
	if($rs['bgid']){
		echo '<br><b>'.$LG['yundan.Xcall_money_content_10'].' </b> <br>
		&raquo; '.$LG['yundan.Xcall_money_content_1'].'<br>'; 

		//获取合箱发货费用($baoguo_hx_fee)
		$bgid=$rs['bgid'];
		require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/yundan/call/bg_hx_fh.php');//$baoguo_hx_fee
		if(!$show_small){echo '<br>';}elseif($baoguo_hx_fee){echo $LG['yundan.Xcall_money_content_11'].$baoguo_hx_fee.$XAmc;}

		//输出增值服务(用到$baoguo_hx_fee)
		require($_SERVER['DOCUMENT_ROOT'].'/xingao/yundan/call/value_show.php');
	}
	
	
	echo '<br><br><b>'.$LG['yundan.Xcall_money_content_12'].'</b><br>'; 
	echo $LG['yundan.Xcall_money_content_2'].spr($rs['fee_transport']).$XAmc;
	
	if($rs['discount']>0)
	{
		echo $LG['yundan.Xcall_money_content_3'].spr($rs['discount']).$LG['yundan.Xcall_money_content_13'].' ('.$LG['yundan.Xcall_money_content_14'].(spr($rs['fee_transport']*$rs['discount']/10)).$XAmc.')';
	}
	
	if($rs['insurevalue']>0)
	{
		echo '<span class="xa_sep"> | </span>'.$LG['yundan.Xcall_money_content_4'].spr($rs['insurevalue']).$XAmc.'';
	}
	
	//其他
	if($rs['fee_tax']>0)
	{
		echo '<span class="xa_sep"> | </span>'.$LG['yundan.Xcall_money_content_5'].spr($rs['fee_tax']).$XAmc;
	}
	
	if($rs['fee_cc']>0)
	{
		echo '<span class="xa_sep"> | </span>'.$LG['yundan.Xcall_money_content_6'].spr($rs['fee_cc']).$XAmc;
	}
	if($rs['fee_ware']>0)
	{
		echo '<span class="xa_sep"> | </span>'.$LG['yundan.28'].spr($rs['fee_ware']).$XAmc;
	}
	if($rs['fee_service']>0)
	{
		echo '<span class="xa_sep"> | </span>'.$LG['yundan.Xcall_money_content_7'].spr($rs['fee_service']).$XAmc.'('.$LG['yundan.Xcall_money_content_15'].')';
	}
	if($rs['fee_other']>0)
	{
		echo '<span class="xa_sep"> | </span>'.$LG['yundan.Xcall_money_content_8'].spr($rs['fee_other']).$XAmc;
	}
	
	if(!$show_small){echo '<br>'.$LG['total'].'<font class="show_price">'.spr($rs['money']).'</font>'.$XAmc;}
	
	//算费备注
	if($rs['money_content'])
	{
		echo '<hr size="1" width="100%">';
		echo TextareaToBr($rs['money_content']);
	}
	
}elseif($rs['ydid']){
	echo $LG['yundan.Xcall_money_content_9'];
}
?>
