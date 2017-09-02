<?php
/*
注意：
》生成时不能打开已生成的文件，无法报错。
》保存中文时必须转码，用 '中文内容部分')
*/

//不类报表类型配置ToExcel_call.php所在路径
require_once($_SERVER['DOCUMENT_ROOT'].'/public/PHPExcel/tem_normal.php');

$excel_i_now=0;
$query="select * from yundan where 1=1 {$where} ".whereCS()."   {$Xwh} {$order}";
$sql=$xingao->query($query);
while($rs=$sql->fetch_array())
{
	$excel_i+=1;

	//子表查询-开始
	$excel_i_now+=1;
	$rs['gnkd']= cadd($rs['gnkd']);
	$rs['ydh']=cadd($rs['ydh']);
	$weight=spr(spr($rs['weight'])*$XAwtkg);
	$rs['s_name']= cadd($rs['s_name']);
	$mobile= cadd($rs['s_mobile_code']).'-'.cadd($rs['s_mobile']);
	$add= yundan_add_all($rs);

	$ydh=cadd($rs['ydh']);//用于判断是否有数据
	$category='';

	$fromtable='yundan'; $fromid=$rs['ydid'];
	$query_wp="select * from wupin where fromtable='{$fromtable}' and fromid='{$fromid}' order by wupin_name desc";
	$sql_wp=$xingao->query($query_wp);
	while($wp=$sql_wp->fetch_array())
	{
		$data[] = array(
		   'list1'=>$excel_i_now,
		   'list2'=>cadd($rs['gnkd']),
		   'list3'=>cadd($rs['ydh']),
		   'list4'=>cadd($wp['wupin_name']),
		   'list5'=>cadd($wp['wupin_number']),
		   'list6'=>'个',
		   'list7'=>$weight,
		   
		  'list8'=>cadd($rs['s_name']) ,
		  'list9'=>$mobile,
		  'list10'=>$add,
		);	
			
		//主单基本数据留空
		$excel_i_now='';
		$rs['gnkd']='';
		$rs['ydh']='';
		$weight='';
		$rs['s_name']='';
		$mobile='';
		$add='';
	}
	//子表查询－结束
	
	//没物品数据时:下面的$data[]代码跟上面的$data[]完全一样
	if(!mysqli_num_rows($sql_wp))
	{
		unset($wp);
		$data[] = array(
		   'list1'=>$excel_i_now,
		   'list2'=>cadd($rs['gnkd']),
		   'list3'=>cadd($rs['ydh']),
		   'list4'=>cadd($wp['wupin_name']),
		   'list5'=>cadd($wp['wupin_number']),
		   'list6'=>'个',
		   'list7'=>$weight,
		   
		  'list8'=>cadd($rs['s_name']) ,
		  'list9'=>$mobile,
		  'list10'=>$add,
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
$enTable = array('list1','list2','list3','list4','list5','list6','list7','list8','list9','list10');//保存内容
$cnTable=array(
	'序号',//list1
	'分运单号',//list2
	'客户单号',//list3
	'中文货物品名',//list4
	'数量',//list5
	'单位',//list6
	'重量(kg)',//list7
	'收件人姓名',//list8
	'收件人电话',//list9
	'收件人地址',//list10
);//列表名

$excel-> getExcel($data,$cnTable,$enTable,'other',20);
//20单元格宽度，如果设置为‘auto’ 表示自适应宽度，也可是具体的数字

$success=1;
?>
