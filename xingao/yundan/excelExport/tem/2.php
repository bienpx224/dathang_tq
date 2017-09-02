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
	$groupid=FeData('member','groupid',"userid='{$rs['userid']}'");
	
	$rs['ydh']=cadd($rs['ydh']);
	$channel=channel_name($groupid,$rs['warehouse'],$rs['country'],$rs['channel']);
	$weight=spr(spr($rs['weight'])*$XAwtkg);
	$rs['s_name']= cadd($rs['s_name']);
	$mobile= cadd($rs['s_mobile_code']).'-'.cadd($rs['s_mobile']);
	$rs['s_tel']=cadd($rs['s_tel']);
	$add=yundan_add_all($rs);
	
	//子表查询-开始
	$category='';
	$fromtable='yundan'; $fromid=$rs['ydid'];
	$query_wp="select * from wupin where fromtable='{$fromtable}' and fromid='{$fromid}' order by wupin_name desc";
	$sql_wp=$xingao->query($query_wp);
	while($wp=$sql_wp->fetch_array())
	{
		$data[] = array(
			'list1'=>cadd($rs['ydh']),//物品品牌
			'list2'=>$channel,//渠道
			'list3'=>$weight,//重量
			
			'list4'=>cadd($wp['wupin_brand']),//物品品牌
			'list5'=>cadd($wp['wupin_name']),//物品名称
			'list6'=>cadd($wp['wupin_number']),//数量
			'list7'=>cadd($wp['wupin_total']),//某类物品总价
			
			'list8'=>cadd($rs['s_name']),
			'list9'=>$mobile,
			'list10'=>cadd($rs['s_tel']),
			'list11'=>$add,
			'list12'=>cadd($rs['s_shenfenhaoma']),
		);	
		
		//主单基本数据留空
		$rs['ydh']='';
		$channel='';
		$weight='';
		$rs['s_name']='';
		$mobile='';
		$rs['s_tel']='';
		$add='';
	}
	//子表查询－结束
	
	
	
	//没物品数据时:下面的$data[]代码跟上面的$data[]完全一样
	if(!mysqli_num_rows($sql_wp))
	{
		unset($wp);
		$data[] = array(
			'list1'=>cadd($rs['ydh']),//物品品牌
			'list2'=>$channel,//渠道
			'list3'=>$weight,//重量
			
			'list4'=>cadd($wp['wupin_brand']),//物品品牌
			'list5'=>cadd($wp['wupin_name']),//物品名称
			'list6'=>cadd($wp['wupin_number']),//数量
			'list7'=>cadd($wp['wupin_total']),//某类物品总价
			
			'list8'=>cadd($rs['s_name']),
			'list9'=>$mobile,
			'list10'=>cadd($rs['s_tel']),
			'list11'=>$add,
			'list12'=>cadd($rs['s_shenfenhaoma']),
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
$enTable = array('list1','list2','list3','list4','list5','list6','list7','list8','list9','list10','list11','list12');//保存内容
$cnTable=array(
	$LG['yundan.list_6'],//运单号 list1
	$LG['yundan.list_7'],//渠道 list2
	$LG['yundan.XexcelExport_2_1'],//重量(kg) list3
	$LG['yundan.XexcelExport_2_2'],//物品品牌 list4
	$LG['yundan.XexcelExport_2_3'],//物品名称 list5
	$LG['yundan.XexcelExport_2_4'],//物品数量 list6
	$LG['yundan.declarevalue'],//物品价值 list7
	$LG['yundan.XexcelExport_2_5'],//收货人 list8
	$LG['yundan.XexcelExport_2_6'],//收货人电话 list9
	$LG['yundan.XexcelExport_2_7'],//备用电话 list10
	$LG['yundan.XexcelExport_2_8'],//收货地址 list11
	$LG['yundan.s_shenfenhaoma'],//身份证号码 list12
);//列表名
$excel-> getExcel($data,$cnTable,$enTable,'other',20);
//20单元格宽度，如果设置为‘auto’ 表示自适应宽度，也可是具体的数字
	
$success=1;
?>
