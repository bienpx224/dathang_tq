<?php
/*
注意：
》生成时不能打开已生成的文件，无法报错。
》保存中文时必须转码，用 '中文内容部分')
*/

//不类报表类型配置ToExcel_call.php所在路径
require_once($_SERVER['DOCUMENT_ROOT'].'/public/PHPExcel/tem_normal.php');

if($callFrom=='manage')
{
	permissions('count_dg',1,'manage','');//验证权限
}else{
	//会员不可导出
	exit ("<script>alert('{$LG['pptOpPer']}');goBack('c');</script>");
}

$query="select * from daigou where 1=1 {$where} {$Mmy} {$Xwh}  and status in (6,7,8) order by addtime asc";
$sql=$xingao->query($query);
while($rs=$sql->fetch_array())
{
	$excel_i+=1;
	
	
	//统计-------------------------------------------------------------
	$totalTyp='small';require($_SERVER['DOCUMENT_ROOT'].'/xingao/daigou/excelExport/tem/count_total_call.php');//当天
	
	
	//正常输出列表-------------------------------------------------------------
	$gd=daigou_GetTotal_goodsFee($rs['dgid']);
	$money_0[$rs['priceCurrency']]=spr($gd['goodsFeePay']+$rs['freightFeePay']);
	$money_1[$rs['priceCurrency']]=spr($rs['procurementCost']);
	$money_2[$rs['priceCurrency']]=spr($rs['procurementCost']-$gd['goodsFeePay']-$rs['freightFeePay']);
	$money_3[$rs['toCurrency']]=spr($gd['goodsFeePayTo']+$rs['freightFeePayTo']);
	
	
	$data[] = array(
		'list1'=>cadd($rs['username'])." ({$rs['userid']})",//会员
		'list2'=>DateYmd($rs['addtime']),//下单时间
		'list3'=>DateYmd($rs['procurementTime']),//采购时间
		'list4'=>$money_0[$rs['priceCurrency']].$rs['priceCurrency'],//代购价格
		'list5'=>$money_1[$rs['priceCurrency']].$rs['priceCurrency'],//采购成本
		'list6'=>$money_2[$rs['priceCurrency']].$rs['priceCurrency'],//利润
		'list7'=>$money_3[$rs['toCurrency']].$rs['toCurrency'],//扣费
	);	
	
	
	//记录总计-------------------------------------------------------------
	//所有币种	
	$arr=ToArr($openCurrency,',');
	if($arr)
	{
		foreach($arr as $arrkey=>$value)
		{
			//小计
			$small_money_0[$value]+=$money_0[$value]; 
			$small_money_1[$value]+=$money_1[$value]; 
			$small_money_2[$value]+=$money_2[$value]; 
			$small_money_3[$value]+=$money_3[$value];
			 
			//大总计
			$total_money_0[$value]+=$money_0[$value]; 
			$total_money_1[$value]+=$money_1[$value]; 
			$total_money_2[$value]+=$money_2[$value]; 
			$total_money_3[$value]+=$money_3[$value]; 
		}
	}
	$num+=1;
	$total_num+=1;
	
	
}//while($rs=$sql->fetch_array())



//统计-------------------------------------------------------------
$totalTyp='small';require($_SERVER['DOCUMENT_ROOT'].'/xingao/daigou/excelExport/tem/count_total_call.php');//当天
$totalTyp='all';require($_SERVER['DOCUMENT_ROOT'].'/xingao/daigou/excelExport/tem/count_total_call.php');//全部



if (!$excel_i)
{ 
	exit ("<script>alert('{$LG['NoDataExported']}');goBack('c');</script>");
} 

//正式导出---------------------------------------------------------
$enTable = array('list1','list2','list3','list4','list5','list6','list7');//保存内容
$cnTable=array(
	'会员',//list1
	'下单时间',//list2
	'采购时间',//list3
	'代购价格',//list4
	'采购成本',//list5
	'利润',//list6
	'扣费',//list7
);//列表名
$excel-> getExcel($data,$cnTable,$enTable,'other',20);
//20单元格宽度，如果设置为‘auto’ 表示自适应宽度，也可是具体的数字

$success=1;
?>
