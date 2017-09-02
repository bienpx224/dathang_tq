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
	//物品输出－开始
	$first=0;$ii=12;
	$fromtable='yundan'; $fromid=$rs['ydid'];
	$query_wp="select *,count(*) as total,sum(`wupin_number`) as total_number from wupin where fromtable='{$fromtable}' and fromid='{$fromid}' order by wupin_price desc limit 10 ";
	$sql_wp=$xingao->query($query_wp);
	while($wp=$sql_wp->fetch_array())
	{
		$ii++;$list='list'.$ii;	$$list=leng(ReturnPinyinFun($wp['wupin_name']),35);
		$ii++;$list='list'.$ii;	$$list=spr($wp['wupin_number']);
		$ii++;$list='list'.$ii;	$$list=spr($wp['wupin_price']);
		
		//特殊计算:(计费重量-1.5)/总数量数量
		$wp['wupin_weight']=$rs['weight']-1.5;
		$wp['wupin_weight']=@($wp['wupin_weight']/$wp['total_number']);
		if($wp['wupin_weight']<=0){$wp['wupin_weight']=0.01;}
		$ii++;$list='list'.$ii;	$$list=spr($wp['wupin_weight']*$XAwtkg);
	}
	//物品输出－结束
	
	$rs['declarevalue']=spr($rs['declarevalue']*exchange($XAScurrency,$XAMcurrency));//转主币种
	
	//生成国家代码
	if($rs['country']==142){$country='CHN';}
	elseif($rs['country']==110){$country='HKG';}
	elseif($rs['country']==143){$country='TWN';}
	elseif($rs['country']==121){$country='MAC';}
	else{$country='';}

	//获取渠道名称
	$groupid=FeData('member','groupid',"userid='{$rs['userid']}'");
	$channel=channel_name($groupid,$rs['warehouse'],$rs['country'],$rs['channel']);


	
	
	$data[] = array(
	'list1'=>cadd($rs['ydh']),//Sendungsreferenz 订单号
	'list2'=>'nana001',//Absenderreferenz 发件人代码:固定
	'list3'=>cadd(ReturnPinyinFun($rs['s_name'])),//Name 1 (Empfänger) 收件人姓名
	'list4'=>leng(ReturnPinyinFun($rs['s_add_quzhen'].$rs['s_add_dizhi']),35),//Straße (Empfänger) 收件人街道
	'list5'=>cadd($rs['s_zip']),//PLZ (Empfänger) 收件人邮编
	'list6'=>leng(ReturnPinyinFun($rs['s_add_chengshi']),35),//Ort (Empfänger) 收件人城市
	'list7'=>$country,//Land (Empfänger) 收件人国家
	'list8'=>cadd($rs['s_mobile']),//Telefonnummer (Empfänger) 收件人电话
	'list9'=>spr($rs['weight']),//Gewicht 包裹总重kg
	'list10'=>$channel,//Produkt- und Servicekennzeichnung 经济包 或 优先包 (显示渠道名,再手工修改)
	'list11'=>'62989508615301',//Abrechnungsnummer 固定填写
	'list12'=>'PRESENT',//Sendungsart 固定填写
	
	'list13'=>$list13,//Beschreibung (Warenposition 1) 产品1 名称 拼音
	'list14'=>$list14,//Menge (Warenposition 1) 产品1 总数量
	'list15'=>$list15,//Warenwert / Einheit (Warenposition 1) 产品1 单品价值€
	'list16'=>$list16,//Gewicht (Warenposition 1) 产品1 单品净重 kg
	
	'list17'=>$list17,//Beschreibung (Warenposition 2) 产品2 名称 英文
	'list18'=>$list18,//Menge (Warenposition 2) 产品2 总数量
	'list19'=>$list19,//Warenwert / Einheit (Warenposition 2) 产品2 单品价值€
	'list20'=>$list20,//Gewicht (Warenposition 2) 产品2 单品净重 kg
	'list21'=>$list21,//Beschreibung (Warenposition 3) 产品3 名称 英文
	'list22'=>$list22,//Menge (Warenposition 3) 产品3 总数量
	'list23'=>$list23,//Warenwert / Einheit (Warenposition 3) 产品3 单品价值€
	'list24'=>$list24,//Gewicht (Warenposition 3) 产品3 单品净重 kg
	'list25'=>$list25,//Beschreibung (Warenposition 4) 产品4 名称 英文
	'list26'=>$list26,//Menge (Warenposition 4) 产品4 总数量
	'list27'=>$list27,//Warenwert / Einheit (Warenposition 4) 产品4 单品价值€
	'list28'=>$list28,//Gewicht (Warenposition 4) 产品4 单品净重 kg
	'list29'=>$list29,//Beschreibung (Warenposition 5) 产品5 名称 英文
	'list30'=>$list30,//Menge (Warenposition 5) 产品5 总数量
	'list31'=>$list31,//Warenwert / Einheit (Warenposition 5) 产品5 单品价值€
	'list32'=>$list32,//Gewicht (Warenposition 5) 产品5 单品净重 kg
	);	
		
	$list13='';$list14='';$list15='';$list16='';$list17='';$list18='';$list19='';$list20='';$list21='';$list22='';$list23='';$list24='';$list25='';$list26='';$list27='';$list28='';$list29='';$list30='';$list31='';$list32='';

	//读取身份证文件
	require($_SERVER['DOCUMENT_ROOT'].'/xingao/yundan/excelExport/card_file.php'); 
	
}//while($rs=$sql->fetch_array())

if (!$excel_i)
{ 
	exit ("<script>alert('{$LG['NoDataExported']}');goBack('c');</script>");
} 
//正式导出---------------------------------------------------------
$enTable = array('list1','list2','list3','list4','list5','list6','list7','list8','list9','list10','list11','list12','list13','list14','list15','list16','list17','list18','list19','list20','list21','list22','list23','list24','list25','list26','list27','list28','list29','list30','list31','list32');//保存内容
$cnTable=array(
	'Sendungsreferenz 订单号',//list1
	'Absenderreferenz 发件人代码',//list2
	'Name 1 (Empfänger) 收件人姓名',//list3
	'Straße (Empfänger) 收件人街道',//list4
	'PLZ (Empfänger) 收件人邮编',//list5
	'Ort (Empfänger) 收件人城市',//list6
	'Land (Empfänger) 收件人国家',//list7
	'Telefonnummer (Empfänger) 收件人电话',//list8
	'Gewicht 包裹总重kg',//list9
	'Produkt- und Servicekennzeichnung 经济包 或 优先包',//list10
	'Abrechnungsnummer 固定填写',//list11
	'Sendungsart 固定填写',//list12
	'Beschreibung (Warenposition 1) 产品1 名称 英文',//list13
	'Menge (Warenposition 1) 产品1 总数量',//list14
	'Warenwert / Einheit (Warenposition 1) 产品1 单品价值€',//list15
	'Gewicht (Warenposition 1) 产品1 单品净重 kg',//list16
	'Beschreibung (Warenposition 2) 产品2 名称 英文',//list17
	'Menge (Warenposition 2) 产品2 总数量',//list18
	'Warenwert / Einheit (Warenposition 2) 产品2 单品价值€',//list19
	'Gewicht (Warenposition 2) 产品2 单品净重 kg',//list20
	'Beschreibung (Warenposition 3) 产品3 名称 英文',//list21
	'Menge (Warenposition 3) 产品3 总数量',//list22
	'Warenwert / Einheit (Warenposition 3) 产品3 单品价值€',//list23
	'Gewicht (Warenposition 3) 产品3 单品净重 kg',//list24
	'Beschreibung (Warenposition 4) 产品4 名称 英文',//list25
	'Menge (Warenposition 4) 产品4 总数量',//list26
	'Warenwert / Einheit (Warenposition 4) 产品4 单品价值€',//list27
	'Gewicht (Warenposition 4) 产品4 单品净重 kg',//list28
	'Beschreibung (Warenposition 5) 产品5 名称 英文',//list29
	'Menge (Warenposition 5) 产品5 总数量',//list30
	'Warenwert / Einheit (Warenposition 5) 产品5 单品价值€',//list31
	'Gewicht (Warenposition 5) 产品5 单品净重 kg',//list32

);//列表名
$excel-> getExcel($data,$cnTable,$enTable,'other',20);
//20单元格宽度，如果设置为‘auto’ 表示自适应宽度，也可是具体的数字

$success=1;
?>
