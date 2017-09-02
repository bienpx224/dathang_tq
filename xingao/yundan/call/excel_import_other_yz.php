<?php
//通用查询验证
//无法使用function，因为有 [ 符号都会错误

$error_now=0;
if (spr($rs['status'])==30&&$op_status_30)
{
	echo $strs_zhi .$strs_name.' 已处“完成”状态。此行没导入！<br>';
	$ok=0;$error_result+=1;
}
elseif (spr($rs['status'])>=20&&$op_status_20)
{
	echo $strs_zhi .$strs_name.' 超过或已是“快递发货”状态。此行没导入！<br>';
	$ok=0;$error_result+=1;
}
elseif (spr($rs['status'])<=4&&$op_status_4)
{
	echo $strs_zhi .$strs_name.' 未出库状态。此行没导入！<br>';
	$ok=0;$error_result+=1;
}
elseif ($rs['lotno']!=$lotno&&$lotno)
{
	echo $strs_zhi .$strs_name.' 批次号不对。此行没导入！<br>';
	$ok=0;$error_result+=1;
}

						
if($ok) 
{
	$warehouse=warehouse_per('ts',par($rs['warehouse']),1);
	if($warehouse)
	{
		$ok=0;$error_result+=1;
		echo '<strong>'.$strs[0].'</strong>：您无权管理该仓库的运单。此行没导入！<br>';
	}
}
?>