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
	$wupin_number=0;$wupin_total=0;$category='';
	
	$fromtable='yundan'; $fromid=$rs['ydid'];
	$query_wp="select * from wupin where fromtable='{$fromtable}' and fromid='{$fromid}' order by wupin_name desc";
	$sql_wp=$xingao->query($query_wp);
	while($wp=$sql_wp->fetch_array())
	{
		$wupin_number+=$wp['wupin_number'];//总计数量
		$wupin_total+=$wp['wupin_total'];//总计价格
		
		$so=cadd($wp['wupin_brand'].$wp['wupin_name']).'*'.$wp['wupin_number'];
		$category=editContent($category,$so,1,'*','；');
	}
	//读取多值字段－结束
	$data[] = array(
		'list1'=>'EFSEASON',//发货人
		'list2'=>'',//发货人地址
		'list3'=>'',//发货人手机
		'list4'=>'',//发货人固定电话
		'list5'=>cadd($rs['s_name']),//收件人姓名
		'list6'=>cadd($rs['s_mobile']),//收货人手机
		'list7'=>'',//收货人固话
		'list8'=>yundan_add_all($rs),//收货人地址
		'list9'=>cadd($rs['s_shenfenhaoma']),//收件人身份证号码
		'list10'=>$category,//品名1
		'list11'=>$wupin_number,//件数1
		'list12'=>spr($rs['weight']*$XAwtkg),//货物重量
		'list13'=>spr($rs['declarevalue']),//货物价值
		'list14'=>spr($rs['insureamount']),//保险价值
		'list15'=>'',//货物分类
		'list16'=>'',
		'list17'=>'',
		'list18'=>'',
		'list19'=>'',
		'list20'=>'',
		'list21'=>'',
		'list22'=>'',
		'list23'=>'',
		'list24'=>'',
		'list25'=>'',
		'list26'=>'',
		'list27'=>'',
		'list28'=>cadd($rs['ydh']),//我/三方单号
	);	
		
	//读取身份证文件
	require($_SERVER['DOCUMENT_ROOT'].'/xingao/yundan/excelExport/card_file.php'); 
	
}//while($rs=$sql->fetch_array())

if (!$excel_i)
{ 
	exit ("<script>alert('{$LG['NoDataExported']}');goBack('c');</script>");
} 

//正式导出---------------------------------------------------------
$enTable = array('list1','list2','list3','list4','list5','list6','list7','list8','list9','list10','list11','list12','list13','list14','list15','list16','list17','list18','list19','list20','list21','list22','list23','list24','list25','list26','list27','list28');//保存内容
$cnTable=array(
	'发货人',//list1
	'发货人地址',//list2
	'发货人手机',//list3
	'发货人固定电话',//list4
	'收件人姓名',//list5
	'收货人手机',//list6
	'收货人固话',//list7
	'收货人地址',//list8
	'收件人身份证号码',//list9
	'品名1',//list10
	'件数1',//list11
	'货物重量(KG)',//list12
	'货物价值',//list13
	'保险价值',//list14
	'货物分类',//list15
	
	'',//list16
	'',//list17
	'',//list18
	'',//list19
	'',//list20
	'',//list21
	'',//list22
	'',//list23
	'',//list24
	'',//list25
	'',//list26
	'',//list27
	'我/三方单号',//list28
);//列表名

$excel-> getExcel($data,$cnTable,$enTable,'other','15');//20单元格宽度，如果设置为‘auto’ 表示自适应宽度，也可是具体的数字
$success=1;
?>
