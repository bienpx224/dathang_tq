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
	$rs['ydh']=cadd($rs['ydh']);
	$excel_3="1";
	$excel_i_now+=1;
	$weight=spr($rs['weight']*$XAwtkg);
	$rs['s_name']= cadd($rs['s_name']);
	$mobile= cadd($rs['s_mobile_code']).'-'.cadd($rs['s_mobile']);
	$rs['s_tel']=cadd($rs['s_tel']);
	$add=yundan_add_all($rs);
	$rs['hscode']=cadd($rs['hscode']);
	$rs['s_shenfenhaoma']=cadd($rs['s_shenfenhaoma']);
	$rs['s_add_chengshi']=$rs['s_add_chengshi'];

	$ydh=cadd($rs['ydh']);//用于判断是否有数据
	$category='';
	
	$fromtable='yundan'; $fromid=$rs['ydid'];
	$query_wp="select * from wupin where fromtable='{$fromtable}' and fromid='{$fromid}' order by wupin_name desc";
	$sql_wp=$xingao->query($query_wp);
	while($wp=$sql_wp->fetch_array())
	{
		$data[] = array(
			'list1'=>'',
			'list2'=>$excel_i_now,
			'list3'=>$excel_3,
			'list4'=>cadd($rs['ydh']),
			
			'list5'=>cadd($wp['wupin_brand']),
			'list6'=>cadd($wp['wupin_name']),
			'list7'=>cadd($wp['wupin_number']),
			
			'list8'=>$weight,
			'list9'=>cadd($rs['s_name']),
			'list10'=>$mobile,
			'list11'=>$add,
			'list12'=>cadd($rs['s_add_chengshi']),
			'list13'=>cadd($rs['hscode']), 
			'list14'=>cadd($rs['s_shenfenhaoma']),
		);	
			
		//主单基本数据留空
		$excel_i_now='';
		$excel_3='';
		$rs['ydh']='';
		$weight='';
		$rs['s_name']='';
		$mobile='';
		$rs['s_tel']='';
		$add='';
		$rs['hscode']='';
		$rs['s_shenfenhaoma']='';
		$rs['s_add_chengshi']='';
	}
	//子表查询－结束
	
	
	//没物品数据时:下面的$data[]代码跟上面的$data[]完全一样
	if(!mysqli_num_rows($sql_wp))
	{
		unset($wp);
		$data[] = array(
			'list1'=>'',
			'list2'=>$excel_i_now,
			'list3'=>$excel_3,
			'list4'=>cadd($rs['ydh']),
			
			'list5'=>cadd($wp['wupin_brand']),
			'list6'=>cadd($wp['wupin_name']),
			'list7'=>cadd($wp['wupin_number']),
			
			'list8'=>$weight,
			'list9'=>cadd($rs['s_name']),
			'list10'=>$mobile,
			'list11'=>$add,
			'list12'=>cadd($rs['s_add_chengshi']),
			'list13'=>cadd($rs['hscode']), 
			'list14'=>cadd($rs['s_shenfenhaoma']),
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
$enTable = array('list1','list2','list3','list4','list5','list6','list7','list8','list9','list10','list11','list12','list13','list14');//保存内容
$cnTable=array(
	'入仓号',//list1
	'箱号',//list2
	'卡板号',//list3
	'客户单号',//list4
	'英文品牌',//list5
	'中文货物名称',//list6
	'数量',//list7
	'重量(kg)',//list8
	'收件人姓名',//list9
	'收件人电话',//list10
	'收件人地址',//list11
	'收件城市',//list12
	'分运单号',//list13
	'身份证',//list14
);//列表名
$excel-> getExcel($data,$cnTable,$enTable,'other',20);
//20单元格宽度，如果设置为‘auto’ 表示自适应宽度，也可是具体的数字

$success=1;
?>
