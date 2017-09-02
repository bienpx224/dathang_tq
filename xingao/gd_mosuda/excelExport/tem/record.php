<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/public/PHPExcel/tem_normal.php');

/*
注意：
》生成时不能打开已生成的文件，无法报错。
》保存中文时必须转码，用 '中文内容部分')
*/

$query="select * from gd_mosuda where 1=1 {$where} {$order}";
$sql=$xingao->query($query);
while($rs=$sql->fetch_array())
{
	$excel_i+=1;
	$data[] = array(
		'list1'=>cadd($rs['itemNo']),//商品货号
		'list2'=>cadd($rs['HYG']),//HYG备案号
		'list3'=>cadd($rs['taxCode']),//新行邮税号
		'list4'=>spr($rs['taxes'],2,0).'%',//4月8日后实施海关行邮税税率
		'list5'=>spr($rs['consumptionTax'],2,0).'%',//消费税
		'list6'=>spr($rs['valueTax'],2,0).'%',//增值税
		'list7'=>spr($rs['comprehensiveTax'],2,0).'%',//4月8日实施海关跨境电商综合税率
		'list8'=>cadd($rs['recordCode']),//检验检疫商品备案编号
		'list9'=>cadd($rs['HSCode']),//商检HS CODE
		'list10'=>cadd($rs['itemsTaxCode']),//海关物品税号
		'list11'=>cadd($rs['name']),//商品名称
		'list12'=>cadd($rs['spec']),//规格型号
		'list13'=>cadd($rs['unit']),//计量单位
		'list14'=>cadd($rs['barcode']),//商品条形码
		'list15'=>cadd($rs['types']),//商品描述
		'list16'=>cadd($rs['content']),//备注
		'list17'=>cadd($rs['merchants']),//生产企业名称
		'list18'=>cadd($rs['brand']),//品牌
		'list19'=>cadd($rs['producer']),//原产国/地区
		'list20'=>spr($rs['weightGross'],2,0),//毛重(KG)
		'list21'=>spr($rs['weightSuttle'],2,0),//净重(KG)
		'list22'=>cadd($rs['composition']),//成分
		'list23'=>cadd($rs['foodAdditives']),//超范围使用食品添加剂
		'list24'=>cadd($rs['harmful']),//含有毒害物质
		'list25'=>spr($rs['recordPrice'],2,0),//备案价格
		'list26'=>spr($rs['price'],2,0),//商品单价
		'list27'=>cadd($rs['url']),//商品网址
		'list28'=>cadd($rs['gdid']),//导入时更新的ID
	);	
		
	
}//while($rs=$sql->fetch_array())

if (!$excel_i)
{ 
	exit ("<script>alert('{$LG['NoDataExported']}');goBack('c');</script>");
} 

//正式导出---------------------------------------------------------
$enTable = array('list1','list2','list3','list4','list5','list6','list7','list8','list9','list10','list11','list12','list13','list14','list15','list16','list17','list18','list19','list20','list21','list22','list23','list24','list25','list26','list27','list28');

//保存内容
$cnTable=array(
	'商品货号',//list1
	'HYG备案号',//list2
	'新行邮税号',//list3
	'行邮税率',//list4
	'消费税',//list5
	'增值税',//list6
	'电商综合税率',//list7
	'备案编号',//list8
	'商检HS CODE',//list9
	'海关物品税号',//list10
	'商品名称',//list11
	'规格型号',//list12
	'计量单位',//list13
	'商品条形码',//list14
	'商品描述',//list15
	'备注',//list16
	'生产企业名称',//list17
	'品牌',//list18
	'原产国/地区',//list19
	'毛重(KG)',//list20
	'净重(KG)',//list21
	'成分',//list22
	'超范围使用食品添加剂',//list23
	'含有毒害物质',//list24
	'备案价格',//list25
	'商品单价',//list26
	'商品网址',//list27
	'资料ID',//list28
);//列表名

$excel-> getExcel($data,$cnTable,$enTable,'other',20);//20单元格宽度，如果设置为‘auto’ 表示自适应宽度，也可是具体的数字
$success=1;
?>
