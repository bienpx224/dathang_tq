<?php
/*
注意：
》生成时不能打开已生成的文件，无法报错。
》保存中文时必须转码，用 '中文内容部分')
*/

//不类报表类型配置ToExcel_call.php所在路径
require_once($_SERVER['DOCUMENT_ROOT'].'/public/PHPExcel/tem_normal.php');

$excel_i_now=0;
$query="select * from yundan where 1=1 {$where} ".whereCS()."   {$Mmy} {$Xwh} {$order}";
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
		
		$so=is_numeric($wp['wupin_type'])?classify($wp['wupin_type'],2):cadd($wp['wupin_type']);
		$so.=' '.cadd($wp['wupin_name']);
		$so.=is_numeric($wp['wupin_brand'])?classify($wp['wupin_brand'],2):cadd($wp['wupin_brand']);
		$so.=' * '.$wp['wupin_number'];
		$category=editContent($category,$so,1,' * ','；');
	}
	//读取多值字段－结束


	$data[] = array(
		'list1'=>$excel_i,//序号
		'list2'=>cadd($rs['ydh']),//运单号
		'list3'=>$category,//货物品名
		'list4'=>$wupin_number,//数量
		'list5'=>cadd($rs['s_name']),//收件人姓名
		'list6'=>cadd($rs['s_shenfenhaoma']),//收件人身份证
		'list7'=>cadd($rs['s_mobile']),//收件人电话
		'list8'=>yundan_add_all($rs),//收件人地址
		'list9'=>spr($rs['weight']),//计重
		'list10'=>spr($rs['money']),//运单总费
		'list11'=>DateYmd($rs['addtime']),//下单日期
	);	
		
	
	//读取身份证文件:会员导出时不导出证件
	//require($_SERVER['DOCUMENT_ROOT'].'/xingao/yundan/excelExport/card_file.php'); 

}//while($rs=$sql->fetch_array())

if (!$excel_i)
{ 
	exit ("<script>alert('{$LG['NoDataExported']}');goBack('c');</script>");
} 
	
//正式导出---------------------------------------------------------
$enTable = array('list1','list2','list3','list4','list5','list6','list7','list8','list9','list10','list11');//保存内容
$cnTable=array(
	$LG['yundan.XexcelExport_4_1'],//list1
	$LG['awb'],//list2
	$LG['yundan.XexcelExport_simple_1'],//list3
	$LG['yundan.XexcelExport_simple_2'],//list4
	$LG['yundan.s_name'],//list5
	$LG['yundan.XexcelExport_simple_3'],//list6
	$LG['yundan.XexcelExport_4_11'],//list7
	$LG['yundan.XexcelExport_3_9'],//list8
	$LG['yundan.XexcelExport_simple_4'],//list9
	$LG['yundan.XexcelExport_simple_5'],//list10
	$LG['yundan.XexcelExport_simple_6'],//list11

/*	'序号',//list1
	'运单号',//list2
	'货物品名',//list3
	'数量（盒）',//list4
	'收件人姓名',//list5
	'收件人身份证',//list6
	'收件人电话',//list7
	'收件人地址',//list8
	'计重（磅）',//list9
	'运单总费(美元)',//list10
	'下单日期',//list11
*/

);//列表名

$excel-> getExcel($data,$cnTable,$enTable,'other',20);
//20单元格宽度，如果设置为‘auto’ 表示自适应宽度，也可是具体的数字

$success=1;
?>
