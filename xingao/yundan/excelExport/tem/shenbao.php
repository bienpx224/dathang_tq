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
	$rs['weightRepeat']=spr($rs['weightRepeat'])*$XAwtkg;
	$rs['declarevalue']=spr($rs['declarevalue']*exchange($XAScurrency,$XAMcurrency));//转主币种


	//读取物品-开始
	$wupin_number=0;$wupin_total=0;$category='';
	
	$fromtable='yundan'; $fromid=$rs['ydid'];
	$query_wp="select * from wupin where fromtable='{$fromtable}' and fromid='{$fromid}' order by wupin_name desc";
	$sql_wp=$xingao->query($query_wp);
	while($wp=$sql_wp->fetch_array())
	{
		$wupin_number+=$wp['wupin_number'];//总计数量
		$wupin_total+=$wp['wupin_total'];//总计价格
		
		$so=cadd(is_numeric($wp['wupin_type'])?classify($wp['wupin_type'],2):cadd($wp['wupin_type']).' '.$wp['wupin_name'].' '.$wp['wupin_brand']).' * '.$wp['wupin_number'];
		$category=editContent($category,$so,1,' * ','；');
	}
	//读取物品－结束
	
	//代替证件-导出
	unset($cd);
	if($ON_cardInstead&&$rs['cardYdid'])
	{
		$cd=FeData('yundan','s_name,s_shenfenhaoma,s_shenfenimg_z,s_shenfenimg_b',"ydid='{$rs['cardYdid']}'");
	}
	
	$data[] = array(
	'list1'=>'',
	'list2'=>$excel_i,
	'list3'=>cadd($rs['ydh']),
	'list4'=>$category,//货物品名:包含物品名称、规格、型号
	'list5'=>'',
	'list6'=>'',
	'list7'=>$wupin_number,//总计数量
	'list8'=>'',
	'list9'=>spr($rs['weight']*$XAwtkg),
	'list10'=>yundan_calcWeight($rs)*$XAwtkg,//要先取整，再转KG，否则不准确
	'list11'=>spr($rs['weightRepeat']),
	'list12'=>'',
	'list13'=>'',
	'list14'=>$wupin_total,//货物价格
	'list15'=>'',
	'list16'=>'',
	'list17'=>'',
	'list18'=>cadd($rs['s_name']),//收件人姓名
	'list19'=>cadd($rs['s_shenfenhaoma']),
	'list20'=>cadd($rs['s_mobile_code']).'-'.cadd($rs['s_mobile']),
	'list21'=>yundan_add_all($rs),
	'list22'=>cadd($rs['f_name']),//发件人姓名
	'list23'=>cadd($rs['f_mobile_code']).'-'.cadd($rs['f_mobile']),
	'list24'=>yundan_add_all($rs,'f'),
	'list25'=>cadd($rs['hscode']),
	'list26'=>'',
	'list27'=>status_name(spr($rs['status'])),
	'list28'=>spr($rs['money']),
	'list29'=>cadd($cd['s_name']),
	'list30'=>cadd($cd['s_shenfenhaoma']),
	'list31'=>cadd($rs['lotno']),
	);		



	//读取身份证文件
	require($_SERVER['DOCUMENT_ROOT'].'/xingao/yundan/excelExport/card_file.php'); 
}//while($rs=$sql->fetch_array())

if (!$excel_i)
{ 
	exit ("<script>alert('{$LG['NoDataExported']}');goBack('c');</script>");
} 

//正式导出---------------------------------------------------------
$enTable = array('list1','list2','list3','list31','list4','list5','list6','list7','list8','list9','list10','list11','list12','list13','list14','list15','list16','list17','list18','list19','list20','list21','list22','list23','list24','list25','list26','list27','list28','list29','list30');//保存内容

$cnTable=array(
	'袋号',//list1
	'序号',//list2
	'运单号',//list3
	'批次号',//list3
	'货物品名',//list4
	'税号',//list5
	'件数',//list6
	'数量(盒)',//list7
	'单位',//list8
	'实际重量(Kg)',//list9
	'计费重量(Kg)',//list10
	'出库复称(Kg)',//list10
	'体积(M3)',//list11
	'完税价格',//list12
	'货物价格',//list13
	'认定价格',//list14
	'税率',//list15
	'应征税额(CNY)',//list16
	'收件人姓名',//list17
	'收件人身份证',//list18
	'收件人电话',//list19
	'收件人地址',//list20
	'发件人姓名',//list21
	'发件人电话',//list22
	'发件人地址',//list23
	'HG/HS编码',//list24
	'验放指令',//list25
	'运单状态',//list26
	'运单总费('.$XAmc.')',//list27
	'替代证件姓名',//list27
	'替代证件号'//list27
);//列表名


$excel-> getExcel($data,$cnTable,$enTable,'other',20);
//20单元格宽度，如果设置为‘auto’ 表示自适应宽度，也可是具体的数字

$success=1;
?>
