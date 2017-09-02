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
	$wupin_number=0;$wupin_total=0;$wupin_name='';
	
	$fromtable='yundan'; $fromid=$rs['ydid'];
	$query_wp="select * from wupin where fromtable='{$fromtable}' and fromid='{$fromid}' order by wupin_name desc";
	$sql_wp=$xingao->query($query_wp);
	while($wp=$sql_wp->fetch_array())
	{
		$wupin_number+=$wp['wupin_number'];//总计数量
		$wupin_total+=$wp['wupin_total'];//总计价格
		
		//$so=cadd($wp['wupin_name']).' * '.$wp['wupin_number'];
		$so=is_numeric($wp['wupin_type'])?classify($wp['wupin_type'],2):cadd($wp['wupin_type']).' * '.$wp['wupin_number'];
		$wupin_name=editContent($wupin_name,$so,1,' * ','；');
	}
	
	//获取包裹表-开始
	$bgid=$rs['bgid'];
	$bgydh='';
	if($bgid)
	{
		$bg_i=0;
		$query_bg="select bgydh from baoguo where bgid in ({$bgid})  order by bgydh asc";
		$sql_bg=$xingao->query($query_bg);
		while($bg=$sql_bg->fetch_array())
		{
			$bg_i++;
			$bgydh.=cadd($bg['bgydh']).',';
		}
	}
	$bgydh=DelStr($bgydh);
	//获取包裹表-结束
	//读取多值字段－结束
	
	
		
	//发件人资料:会员下单时候有的话就用会员所填,否则用公司
	if($rs['f_name']&&$rs['f_mobile']&&yundan_add_all($rs,'f'))
	{
		$f_name=cadd($rs['f_name']);
		$f_mobile=cadd('+'.$rs['f_mobile_code'].' '.$rs['f_mobile']);
		$f_tel=cadd($rs['f_tel']);
		$f_add=yundan_add_all($rs,'f');
	}else{
		$f_name=CompanySend('sendName');
		$f_mobile='';
		$f_tel=CompanySend('sendTel');
		$f_add=CompanySend('sendAdd');
	}


	$data[] = array(
	  'list1'=>cadd($rs['ydh']),
	  'list2'=>$bgydh,
	  
	  'list3'=>$f_name,
	  'list4'=>$f_mobile,
	  'list5'=>$f_tel,
	  'list6'=>$f_add,
	  
	  'list7'=>cadd($rs['s_name']),
	  'list8'=>cadd('+'.$rs['s_mobile_code'].' '.$rs['s_mobile']),
	  'list9'=>cadd($rs['s_tel']),
	  'list10'=>cadd($rs['s_add_shengfen']),
	  'list11'=>cadd($rs['s_add_chengshi']),
	  'list12'=>cadd($rs['s_add_quzhen']),
	  'list13'=>yundan_add_all($rs),
	  'list14'=>cadd($rs['s_shenfenhaoma']),
	  
	  'list15'=>'',//货物分类(留空)
	  'list16'=>cadd($wupin_name),//品名(实际用分类)
	  'list17'=>$wupin_number,//总计数量
	  'list18'=>spr($rs['weight']*$XAwtkg),
	  'list19'=>spr($rs['declarevalue']*exchange($XAScurrency,'CNY')),
	  'list20'=>0,//保险价值(0)
	);	
		
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
	'运单号',//list1
	'包裹单号',//list2
	
	'发货人',//list3
	'发货人手机',//list4
	'发货人固话',//list5
	'发货人地址',//list6
	
	'收件人姓名',//list7
	'收货人手机',//list8
	'收货人固话',//list9
	'收件人省份',//list10
	'收件人城市',//list11
	'收件人区镇',//list12
	'收货人地址',//list13
	'收件人身份证号码',//list14
	
	'货物分类',//list15
	'品名',//list16
	'件数',//list17
	'货物重量(KG)',//list18
	'货物价值(RMB)',//list19
	'保险价值',//list20
);//列表名

$excel-> getExcel($data,$cnTable,$enTable,'other',20);//20单元格宽度，如果设置为‘auto’ 表示自适应宽度，也可是具体的数字

$success=1;
?>
