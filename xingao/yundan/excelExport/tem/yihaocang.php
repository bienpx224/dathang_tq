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
	$fromtable='yundan'; $fromid=$rs['ydid'];
	$query_wp="select * from wupin where fromtable='{$fromtable}' and fromid='{$fromid}' order by wupin_name desc";
	$sql_wp=$xingao->query($query_wp);
	while($wp=$sql_wp->fetch_array())
	{
		$data[] = array(
			'list1'=>'',
			'list2'=>'',
			'list3'=>cadd($rs['ydh']),
			'list4'=>'',
			
			'list5'=>cadd($wp['wupin_brand']),//品牌
			'list6'=>cadd($wp['wupin_name']),//品名
			'list7'=>$wp['wupin_number']?$wp['wupin_number']:1,//件数
			'list8'=>spr($wp['wupin_price']*exchange($XAScurrency,$XAMcurrency)),//转主币种
			'list9'=>'',

			'list10'=>cadd($rs['s_name']),//收件人	
			'list11'=>cadd($rs['s_add_shengfen']),
			'list12'=>cadd($rs['s_add_chengshi']),
			
			'list13'=>cadd($rs['s_add_dizhi']),
			'list14'=>cadd($rs['s_zip']),
			'list15'=>$rs['s_mobile']?cadd($rs['s_mobile_code']).'-'.cadd($rs['s_mobile']):'',
			'list16'=>cadd($rs['s_shenfenhaoma']),
			'list17'=>'',
			'list18'=>'',
			'list19'=>'',
			'list20'=>'',
		);	
			
		//主单基本数据留空
		$rs['s_name']='';
		$rs['s_add_shengfen']='';
		$rs['s_add_chengshi']='';
		$rs['s_add_dizhi']='';
		$rs['s_zip']='';
		$rs['s_mobile']='';
		$rs['declarevalue']='';
		$rs['s_shenfenhaoma']='';
	
	}
	//子表查询－结束
	
	
	//没物品数据时:下面的$data[]代码跟上面的$data[]完全一样
	if(!mysqli_num_rows($sql_wp))
	{
		unset($wp);
		$data[] = array(
			'list1'=>'',
			'list2'=>'',
			'list3'=>cadd($rs['ydh']),
			'list4'=>'',
			
			'list5'=>cadd($wp['wupin_brand']),//品牌
			'list6'=>cadd($wp['wupin_name']),//品名
			'list7'=>$wp['wupin_number']?$wp['wupin_number']:1,//件数
			'list8'=>spr($wp['wupin_price']*exchange($XAScurrency,$XAMcurrency)),//转主币种
			'list9'=>'',

			'list10'=>cadd($rs['s_name']),//收件人	
			'list11'=>cadd($rs['s_add_shengfen']),
			'list12'=>cadd($rs['s_add_chengshi']),
			
			'list13'=>cadd($rs['s_add_dizhi']),
			'list14'=>cadd($rs['s_zip']),
			'list15'=>$rs['s_mobile']?cadd($rs['s_mobile_code']).'-'.cadd($rs['s_mobile']):'',
			'list16'=>cadd($rs['s_shenfenhaoma']),
			'list17'=>'',
			'list18'=>'',
			'list19'=>'',
			'list20'=>'',
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
$enTable = array('list1','list2','list3','list4','list5','list6','list7','list8','list9','list10','list11','list12','list13','list14','list15','list16','list17','list18','list19','list20');//保存内容
$cnTable=array(
	'仓库编码',//list1
	'服务类型编码',//list2
	'包裹追踪号',//list3
	'用户关联单号',//list4
	'品牌',//list5
	'货物名称',//list6
	'数量',//list7
	'单价RMB',//list8
	'备注信息',//list9
	'收件人',//list10
	'收件省份',//list11
	'收件城市',//list12
	'收件地址',//list13
	'收件邮编',//list14
	'收件电话',//list15
	'身份证号',//list16
	'是否清点数量',//list17
	'是否抽取发票',//list18
	'是否购买保险',//list19
	'是否加固包装'//list20
);//列表名
$excel-> getExcel($data,$cnTable,$enTable,'other',20);
//20单元格宽度，如果设置为‘auto’ 表示自适应宽度，也可是具体的数字

$success=1;
?>
