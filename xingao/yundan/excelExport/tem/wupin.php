<?php
/*
注意：
》生成时不能打开已生成的文件，无法报错。
》保存中文时必须转码，用 '中文内容部分')
*/

//不类报表类型配置ToExcel_call.php所在路径
require_once($_SERVER['DOCUMENT_ROOT'].'/public/PHPExcel/tem_normal.php');


$query="select * from yundan where 1=1 {$where} ".whereCS()."   {$Xwh} {$order}";
$sql=$xingao->query($query);
while($rs=$sql->fetch_array())
{
	$excel_i+=1;
	$weight=spr(spr($rs['weight'])*$XAwtkg);
	
	//子表查询-开始
	$fromtable='yundan'; $fromid=$rs['ydid'];
	$query_wp="select * from wupin where fromtable='{$fromtable}' and fromid='{$fromid}' order by wupin_name desc";
	$sql_wp=$xingao->query($query_wp);
	while($wp=$sql_wp->fetch_array())
	{
		$data[] = array(
		   'list1'=>cadd($rs['ydh']),
		   'list2'=>cadd($wp['wupin_name']),
		   'list3'=>cadd($wp['wupin_spec']),
		   'list4'=>cadd($wp['wupin_number']),
		   'list5'=>cadd($wp['wupin_price']),
		   'list6'=>cadd($wp['wupin_total']),
		   'list7'=>$weight
		);	
			
		//主单基本数据留空
		$rs['ydh']='';
		$weight='';
	}
	//子表查询－结束
	
	
	//没物品数据时:下面的$data[]代码跟上面的$data[]完全一样
	if(!mysqli_num_rows($sql_wp))
	{
		unset($wp);
		$data[] = array(
		   'list1'=>cadd($rs['ydh']),
		   'list2'=>cadd($wp['wupin_name']),
		   'list3'=>cadd($wp['wupin_spec']),
		   'list4'=>cadd($wp['wupin_number']),
		   'list5'=>cadd($wp['wupin_price']),
		   'list6'=>cadd($wp['wupin_total']),
		   'list7'=>$weight
		);	
	}


	

	
	
	
	//读取身份证文件
	require($_SERVER['DOCUMENT_ROOT'].'/xingao/yundan/excelExport/card_file.php'); 
}//while($rs=$sql->fetch_array())

if (!$excel_i)
{ 
	exit ("<script>alert('{$LG['NoDataExported']}');goBack('c');</script>");
} 

//正式导出---------------------------------------------------------
$enTable = array('list1','list2','list3','list4','list5','list6','list7');//保存内容
$cnTable=array(
	'运单号',//list1
	'品名',//list2
	'规格',//list3
	'数量',//list4
	'单价',//list5
	'总金额',//list6
	'包裹重量(kg)',//list7
);//列表名
$excel-> getExcel($data,$cnTable,$enTable,'other',20);
//20单元格宽度，如果设置为‘auto’ 表示自适应宽度，也可是具体的数字

$success=1;
?>
