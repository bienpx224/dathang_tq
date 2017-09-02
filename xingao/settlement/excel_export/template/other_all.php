<?php
/*
注意：
》生成时不能打开已生成的文件，无法报错。
》保存中文时必须转码，用 '中文内容部分')
*/

//另类报表类型配置ToExcel_call.php所在路径
require_once($_SERVER['DOCUMENT_ROOT'].'/public/PHPExcel/tem_normal.php');


$query="select * from money_kfbak where {$where} {$order}";
$sql=$xingao->query($query);
while($rs=$sql->fetch_array())
{
	$excel_i+=1;//总共导出数量
	
	//运单列表
	require($_SERVER['DOCUMENT_ROOT'].'/xingao/settlement/excel_export/call/other_list.php');

}//while($rs=$sql->fetch_array())



//最后一个运单总计
require($_SERVER['DOCUMENT_ROOT'].'/xingao/settlement/excel_export/call/other_total.php');



if (!$excel_i)
{ 
	exit ("<script>alert('{$LG['NoDataExported']}');goBack('c');</script>");
} 
	
	
	
	
//正式导出---------------------------------------------------------
$enTable = array('list1','list2','list3','list4','list5','list6');//保存内容
$cnTable=array(
	$LG['settlement.Xtemplate_other_all_1'],//list1
	$LG['settlement.Xtemplate_other_all_2'],//list2
	$LG['settlement.Xtemplate_other_all_3'],//list3
	$LG['settlement.Xtemplate_other_all_4'],//list4
	$LG['settlement.Xtemplate_other_all_5'],//list5
	$LG['settlement.Xtemplate_other_all_6']//list6
);//列表名
$excel-> getExcel($data,$cnTable,$enTable,'other',20);
//20单元格宽度，如果设置为‘auto’ 表示自适应宽度，也可是具体的数字
?>
