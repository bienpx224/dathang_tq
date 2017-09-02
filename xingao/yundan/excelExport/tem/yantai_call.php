<?php
//新表
if($classid!=$rs['classid'])
{
	
	
	//表名
	$val=classify($classid,2);
	array_unshift($table_name,$val);
	
	//仓库所在国家,用于获取国家代码
	$val=FeData('warehouse','country',"whid='{$rs['warehouse']}'");
	array_unshift($table_whCountry,$val);
	
	//物品数量
	array_unshift($table_totalNumber,$totalNumber);
	$totalNumber=0;

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