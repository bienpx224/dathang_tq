<?php 
if(!$bg_number){$bg_number=arrcount($rs['bgid']);}
if(!$wp_number){
	$sum=FeData('wupin',"count(*) as total,sum(`wupin_number`) as wupin_number","fromtable='yundan' and fromid='{$rs['ydid']}'");
	$wp_number=$sum['wupin_number'];
}
if($bg_number>0)
{
	echo '<b>'.LGtag($LG['yundan.Xcall_value_show_1'],'<tag1>=='.$bg_number).'</b>';
}
if($wp_number>0)
{
	echo LGtag($LG['yundan.Xcall_value_show_5'],'<tag1>=='.$wp_number);
}

if($baoguo_hx_fee>0)
{
	echo '<span class="xa_sep"> | </span>'.$LG['yundan.Xcall_value_show_2'];
	echo ' '.$baoguo_hx_fee.$XAmc; 
}


if($rs['op_ydfee2']>0)
{
	echo '<span class="xa_sep"> | </span>';
	echo yundan_service('op_ydfee2','name'); 
	echo ':';
	$fee=yundan_service('op_ydfee2',$rs['op_ydfee2'],2);//有直接显示
	echo ' '.spr($fee).$XAmc; 
}

if($rs['op_ydfee1']>0)
{
	echo '<span class="xa_sep"> | </span>';
	echo yundan_service('op_ydfee1','name'); 
	echo ':';
	$fee=yundan_service('op_ydfee1',$rs['op_ydfee1'],2);//有直接显示
	echo ' '.spr($fee).$XAmc; 
}

if($rs['op_bgfee1']>0)
{
	echo '<span class="xa_sep"> | </span>';
	echo yundan_service('op_bgfee1','name'); 
	echo ':';
	$fee=yundan_service('op_bgfee1',$rs['op_bgfee1'],2);//有直接显示
	echo ' '.$fee.$XAmc.$LG['yundan.Xcall_value_show_3']; 
	echo '('.$LG['total'].spr($bg_number*$fee).$XAmc.')';
}

if($rs['op_bgfee2']>0)
{
	echo '<span class="xa_sep"> | </span>';
	echo yundan_service('op_bgfee2','name'); 
	echo ':';
	$fee=yundan_service('op_bgfee2',$rs['op_bgfee2'],2);//有直接显示
	echo ' '.$fee.$XAmc.$LG['yundan.Xcall_value_show_3']; 
	echo '('.$LG['total'].spr($bg_number*$fee).$XAmc.')';
}

if($rs['op_wpfee1']>0)
{
	echo '<span class="xa_sep"> | </span>';
	echo yundan_service('op_wpfee1','name'); 
	echo ':';
	$fee=yundan_service('op_wpfee1',$rs['op_wpfee1'],2);//有直接显示
	echo ' '.$fee.$XAmc.$LG['yundan.Xcall_value_show_4']; 
	echo '('.$LG['total'].spr($wp_number*$fee).$XAmc.')';
}

if($rs['op_wpfee2']>0)
{
	echo '<span class="xa_sep"> | </span>';
	echo yundan_service('op_wpfee2','name'); 
	echo ':';
	$fee=yundan_service('op_wpfee2',$rs['op_wpfee2'],2);//有直接显示
	echo ' '.$fee.$XAmc.$LG['yundan.Xcall_value_show_4']; 
	echo '('.$LG['total'].spr($wp_number*$fee).$XAmc.')';
}

?>
