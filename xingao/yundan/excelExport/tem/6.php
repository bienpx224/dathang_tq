<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/public/PHPExcel/tem_normal.php');

/*
注意：
》生成时不能打开已生成的文件，无法报错。
》保存中文时必须转码，用 '中文内容部分')
*/

$query="select * from yundan where 1=1 {$where} ".whereCS()."   {$my_warehouse} {$order}";
$sql=$xingao->query($query);
while($rs=$sql->fetch_array())
{
	$excel_i+=1;
	
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
	
	$s_add=yundan_add_all($rs);


	//读取多值字段-开始
	$fromtable='yundan'; $fromid=$rs['ydid'];
	$query_wp="select * from wupin where fromtable='{$fromtable}' and fromid='{$fromid}' order by wupin_name desc";
	$sql_wp=$xingao->query($query_wp);
	while($wp=$sql_wp->fetch_array())
	{
		$data[] = array(
		  'list1'=>cadd($rs['ydh']),
		  'list2'=>$bgydh,
		  
		  'list3'=>$f_name,
		  'list4'=>$f_mobile,
		  'list5'=>$f_tel,
		  'list6'=>$f_add,
		  
		  'list7'=>cadd($rs['s_name']),
		  'list8'=>cadd($rs['s_mobile']?'+'.$rs['s_mobile_code'].' '.$rs['s_mobile']:''),
		  'list9'=>cadd($rs['s_tel']),
		  'list10'=>cadd($rs['s_add_shengfen']),
		  'list11'=>cadd($rs['s_add_chengshi']),
		  'list12'=>cadd($rs['s_add_quzhen']),
		  'list13'=>$s_add,
		  'list14'=>cadd($rs['s_shenfenhaoma']),
		  
		  'list15'=>is_numeric($wp['wupin_type'])?classify($wp['wupin_type'],2):cadd($wp['wupin_type']),
		  'list16'=>cadd($wp['wupin_name']),
		  'list17'=>cadd($wp['wupin_brand']),
		  'list18'=>spr($wp['wupin_number']),
		  'list19'=>spr($wp['wupin_price']),
		  'list20'=>spr($wp['wupin_weight']*$XAwtkg),
		  'list21'=>spr($wp['wupin_total']),
		  
		  'list22'=>spr($rs['declarevalue']),
		  'list23'=>spr($rs['insureamount']),
		);	
		
		//主单基本数据留空
		$rs['ydh']='';
		$bgydh='';
		$f_name='';
		$f_mobile='';
		$f_tel='';
		$f_add='';
		$s_add='';
		$rs['s_name']='';
		$rs['s_mobile']='';
		$rs['s_tel']='';
		$rs['s_add_shengfen']='';
		$rs['s_add_chengshi']='';
		$rs['s_add_quzhen']='';
		$rs['s_shenfenhaoma']='';
		$rs['declarevalue']='';
		$rs['insureamount']='';
	}
	//读取多值字段－结束
	
	
	
	//没物品数据时:下面的$data[]代码跟上面的$data[]完全一样
	if(!mysqli_num_rows($sql_wp))
	{
		unset($wp);
		$data[] = array(
		  'list1'=>cadd($rs['ydh']),
		  'list2'=>$bgydh,
		  
		  'list3'=>$f_name,
		  'list4'=>$f_mobile,
		  'list5'=>$f_tel,
		  'list6'=>$f_add,
		  
		  'list7'=>cadd($rs['s_name']),
		  'list8'=>cadd($rs['s_mobile']?'+'.$rs['s_mobile_code'].' '.$rs['s_mobile']:''),
		  'list9'=>cadd($rs['s_tel']),
		  'list10'=>cadd($rs['s_add_shengfen']),
		  'list11'=>cadd($rs['s_add_chengshi']),
		  'list12'=>cadd($rs['s_add_quzhen']),
		  'list13'=>$s_add,
		  'list14'=>cadd($rs['s_shenfenhaoma']),
		  
		  'list15'=>is_numeric($wp['wupin_type'])?classify($wp['wupin_type'],2):cadd($wp['wupin_type']),
		  'list16'=>cadd($wp['wupin_name']),
		  'list17'=>cadd($wp['wupin_brand']),
		  'list18'=>spr($wp['wupin_number']),
		  'list19'=>spr($wp['wupin_price']),
		  'list20'=>spr($wp['wupin_weight']*$XAwtkg),
		  'list21'=>spr($wp['wupin_total']),
		  
		  'list22'=>spr($rs['declarevalue']),
		  'list23'=>spr($rs['insureamount']),
		);	
	}


			
	//读取身份证文件
	require($_SERVER['DOCUMENT_ROOT'].'/xingao/yundan/excelExport/card_file.php'); 
	
}//while($rs=$sql->fetch_array())

if (!$excel_i)
{ 
	exit ("<script>alert('没有数据可导出!');goBack('c');</script>");
} 

//正式导出---------------------------------------------------------
$enTable = array('list1','list2','list3','list4','list5','list6','list7','list8','list9','list10','list11','list12','list13','list14','list15','list16','list17','list18','list19','list20','list21','list22','list23');//保存内容
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
	
	'货物类别',//list15
	'货物品名',//list16
	'货物品牌',//list17
	'货物件数',//list18
	'货物单价('.$XAsc.')',//list19
	'货物重量(kg)',//list20
	'货物总价('.$XAsc.')',//list21
	
	'运单价值('.$XAsc.')',//list22
	'运单保险('.$XAsc.')',//list23
);//列表名

$excel-> getExcel($data,$cnTable,$enTable,'other',20);//20单元格宽度，如果设置为‘auto’ 表示自适应宽度，也可是具体的数字
$success=1;
?>
