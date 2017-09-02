<?php
/*
注意：
》生成时不能打开已生成的文件，无法报错。
》保存中文时必须转码，用 '中文内容部分')
*/

//另类报表类型配置ToExcel_call.php所在路径
require_once($_SERVER['DOCUMENT_ROOT'].'/public/PHPExcel/tem_normal.php');


$query="select * from yundan where {$where}  {$order}";
$sql=$xingao->query($query);
while($rs=$sql->fetch_array())
{
	$excel_i+=1;//总共导出数量
	
	//是否是另一个会员
	$userid_nwe=0;
	if($rs['userid']!=$userid_pre&&$userid_pre){
		$userid_pre=$rs['userid'];$userid_nwe=2;//是另一个会员
	}elseif($rs['userid']!=$userid_pre&&!$userid_pre){
		$userid_pre=$rs['userid'];$userid_nwe=1;//刚开始
	}

	
	if($userid_nwe==2)
	{
		//运单总计
		require($_SERVER['DOCUMENT_ROOT'].'/xingao/settlement/excel_export/call/yundan_total.php');
	}
	
	//运单列表
	require($_SERVER['DOCUMENT_ROOT'].'/xingao/settlement/excel_export/call/yundan_list.php');

	
		
}//while($rs=$sql->fetch_array())



//最后一个运单总计
require($_SERVER['DOCUMENT_ROOT'].'/xingao/settlement/excel_export/call/yundan_total.php');



if (!$excel_i)
{ 
	exit ("<script>alert('{$LG['NoDataExported']}');goBack('c');</script>");
} 
	
	
	
	
//正式导出---------------------------------------------------------
$enTable = array('list1','list2','list3','list4','list5','list6','list7','list8','list9','list10','list11');//保存内容
$cnTable=array(
	$LG['settlement.Xtemplate_other_all_1'],//list1
	$LG['settlement.Xtemplate_other_all_7'],//list2
	$LG['settlement.Xtemplate_other_all_8'],//list3
	$LG['settlement.Xtemplate_other_all_9'],//list4
	$LG['settlement.Xtemplate_other_all_10'],//list5
	$LG['settlement.Xtemplate_other_all_11'],//list6
	$LG['settlement.Xtemplate_other_all_12'],//list7
	$LG['settlement.Xtemplate_other_all_13'],//list8
	$LG['settlement.Xtemplate_other_all_14'],//list9
	$LG['settlement.Xtemplate_other_all_15'],//list10
	$LG['settlement.Xtemplate_other_all_16']//list11
);//列表名
$excel-> getExcel($data,$cnTable,$enTable,'other',20);
//20单元格宽度，如果设置为‘auto’ 表示自适应宽度，也可是具体的数字
?>
