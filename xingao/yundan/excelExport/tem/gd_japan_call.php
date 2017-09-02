<?php
//新表
if($classid!=$rs['classid'])
{
	
	
	//表名
	$val=classify($classid,2);
	array_unshift($table_name,$val);
	
	//航空提单号(二级分类)
	$val=classify($classid,52);
	array_unshift($table_bclassidName,$val);
	
	//物品数量
	array_unshift($table_totalNumber,$totalNumber);
	$totalNumber=0;

	//物品总价
	array_unshift($table_totalPrice,$totalPrice);
	$totalPrice=0;

	//物品/包裹重量
	array_unshift($table_totalWeight,$totalWeight);
	$totalWeight=0;

	//表内容数据
	$table_data[$table_i]=$data;
	if($rs['classid']){unset($data);}//最后一个时不要删除
	
	//用于识别是否是新表
	$classid=$rs['classid'];
	
	$table_i++;
	$serial=0;
}

?>