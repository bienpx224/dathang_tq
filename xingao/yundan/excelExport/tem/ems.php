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
	$rs['weight']=spr($rs['weight'])*$XAwtkg;
	

	$data[] = array(
	  'list1'=>'',
	  'list2'=>$excel_i,
	  'list3'=>cadd($rs['ydh']),
	  
	  'list4'=> spr($rs['weight']),
	  'list5'=> cadd($rs['s_name']),
	  'list6'=> cadd($rs['s_mobile_code']).'-'.cadd($rs['s_mobile']),
	  'list7'=> yundan_add_all($rs),
	);		

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
	'入仓号',//list1
	'箱号',//list2
	'客户单号',//list3
	'重量',//list4
	'收件人姓名',//list5
	'收货人手机',//list6
	'收货人地址',//list7
);//列表名
$excel-> getExcel($data,$cnTable,$enTable,'other',20);
//20单元格宽度，如果设置为‘auto’ 表示自适应宽度，也可是具体的数字

$success=1;
?>
