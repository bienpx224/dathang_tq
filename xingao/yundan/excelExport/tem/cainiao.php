<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/public/PHPExcel/tem_normal.php');

/*
注意：
》生成时不能打开已生成的文件，无法报错。
》保存中文时必须转码，用 '中文内容部分')
*/

$query="select * from yundan where 1=1 {$where} ".whereCS()."   {$Xwh} {$order}";
$sql=$xingao->query($query);
while($rs=$sql->fetch_array())
{
	$excel_i+=1;
	//读取多值字段-开始
	$wupin_type='';
	$fromtable='yundan'; $fromid=$rs['ydid'];
	$query_wp="select * from wupin where fromtable='{$fromtable}' and fromid='{$fromid}' order by wupin_name desc";
	$sql_wp=$xingao->query($query_wp);
	while($wp=$sql_wp->fetch_array())
	{
		$wupin_type.=is_numeric($wp['wupin_type'])?classify($wp['wupin_type'],2):cadd($wp['wupin_type']).',';
	}
	$wupin_type=$wupin_type?DelStr($wupin_type):'日用品';
	
	//读取多值字段－结束
	$data[] = array(
		'list1'=>CompanySend('sendName').'('.substr($rs['ydh'],-10).')',//寄件人姓名
		'list2'=>CompanySend('sendMobile'),//寄件人手机
		'list3'=>CompanySend('sendTel'),//寄件人座机
		
		'list4'=>cadd($rs['s_name']),//收件人姓名
		'list5'=>cadd($rs['s_mobile']),//收件人手机
		'list6'=>cadd($rs['s_tel']),//收件人座机
		'list7'=>cadd($rs['s_add_shengfen']),//收件地址_省
		'list8'=>cadd($rs['s_add_chengshi']),//收件地址_市
		'list9'=>cadd($rs['s_add_quzhen']),//收件地址_区
		'list10'=>cadd($rs['s_add_dizhi']),//收件地址_详细地址
		'list11'=>$wupin_type,//物品信息
	);	
		
	//读取身份证文件
	require($_SERVER['DOCUMENT_ROOT'].'/xingao/yundan/excelExport/card_file.php'); 
	
}//while($rs=$sql->fetch_array())

if (!$excel_i)
{ 
	exit ("<script>alert('{$LG['NoDataExported']}');goBack('c');</script>");
} 

//正式导出---------------------------------------------------------
$enTable = array('list1','list2','list3','list4','list5','list6','list7','list8','list9','list10','list11');//保存内容
$cnTable=array(
	  '寄件人姓名(运单号后10位)',//list1
	  '寄件人手机',//list2
	  '寄件人座机',//list3
	  '收件人姓名',//list4
	  '收件人手机',//list5
	  '收件人座机',//list6
	  '收件地址_省',//list7
	  '收件地址_市',//list8
	  '收件地址_区',//list9
	  '收件地址_详细地址',//list10
	  '物品信息',//list11
);//列表名

$excel-> getExcel($data,$cnTable,$enTable,'other','15');//20单元格宽度，如果设置为‘auto’ 表示自适应宽度，也可是具体的数字
$success=1;
?>
