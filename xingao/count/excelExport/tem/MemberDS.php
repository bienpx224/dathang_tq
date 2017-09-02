<?php
$table=$_POST['table'];
$count=$_POST['count'];
$where=$_POST['where'];
$where_count=$_POST['where_count'];

permissions('count_hy_hx',1,'manage','');//验证权限

require_once($_SERVER['DOCUMENT_ROOT'].'/public/PHPExcel/tem_normal.php');

$query="select *,sum(fromMoney) from {$table} where {$where} group by userid order by sum(fromMoney) desc";
$sql=$xingao->query($query);
while($rs=$sql->fetch_array())
{
	$excel_i+=1;
	
	
	
	
	//统计
	$ex_sumdata='';$total=0;
	$arr=ToArr($openCurrency,',');
	if($arr)
	{
		foreach($arr as $arrkey=>$value)
		{
			$fe=FeData($table,"count(*) as total {$count}","{$where_count} and userid='{$rs['userid']}' and fromCurrency='{$value}' ");
			if(spr($fe['sumdata'])){$sumdata[$value]=spr($fe['sumdata']); $ex_sumdata.=$sumdata[$value].$value.' 
';}
			if(spr($fe['total'])){$total+=spr($fe['total']);}
		}
	}





	//账户
	$ex_money='';$integral=0;
	$fe=FeData('member',"count(*) as total,sum(`money`) as money,sum(`money_lock`) as money_lock,sum(`integral`) as integral,currency","userid='{$rs[userid]}'");
	if(spr($fe['money'])||spr($fe['money_lock']))
	{
		$money[$fe['currency']]=spr($fe['money']);	
		$money_lock[$fe['currency']]=spr($fe['money_lock']);
		$ex_money.=$money[$fe['currency']].$fe['currency'];
		if(spr($money_lock[$fe['currency']])){$ex_money.= '(冻结 '.spr($money_lock[$fe['currency']]).$fe['currency'].')';}
		$ex_money.='
';
	}
	if(spr($fe['integral'])){$integral+=spr($fe['integral']);}
	
	
	
	
	
	

	$data[] = array(
	  'list1'=>$excel_i,
	  'list2'=>cadd($rs['username']),
	  'list3'=>$rs['userid'],
	  'list4'=>$ex_sumdata,
	  'list5'=>$total.'条',
	  'list6'=>$ex_money,
	  'list7'=>$integral.'分',
	);		

}//while($rs=$sql->fetch_array())








//总计-------------------------------------------
//所有币种:统计
$ex_sumdata='';$total=0;
$arr=ToArr($openCurrency,',');
if($arr)
{
	foreach($arr as $arrkey=>$value)
	{
		$fe=FeData($table,"count(*) as total {$count}","{$where_count} and fromCurrency='{$value}'");
		if(spr($fe['sumdata'])){$ex_sumdata.= spr($fe['sumdata']).$value.' 
		';}
		if(spr($fe['total'])){$total+=spr($fe['total']);}		
	}
}


//所有币种:账户
$ex_money='';$integral=0;
$arr=ToArr($openCurrency,',');
if($arr)
{
	foreach($arr as $arrkey=>$value)
	{
		$fe=FeData('member',"count(*) as total,sum(`money`) as money,sum(`money_lock`) as money_lock,sum(`integral`) as integral","currency='{$value}'");
		if(spr($fe['money'])||spr($fe['money_lock']))
		{
			$ex_money.= spr($fe['money']).$value;
			if(spr($fe['money_lock'])){$ex_money.= '(冻结 '.spr($fe['money_lock']).$value.')';}
			$ex_money.= '
			';
		}
		if(spr($fe['integral'])){$integral+=spr($fe['integral']);}
		
	}
}


$data[] = array(
  'list1'=>'总计',
  'list2'=>'列表里只显示有数据的会员,总计里则不限',
  'list3'=>'',
  'list4'=>$ex_sumdata,
  'list5'=>$total.'条',
  'list6'=>$ex_money,
  'list7'=>$integral.'分',
);		





if (!$excel_i)
{ 
	exit ("<script>alert('{$LG['NoDataExported']}');goBack('c');</script>");
} 
	
//正式导出---------------------------------------------------------
$enTable = array('list1','list2','list3','list4','list5','list6','list7');//保存内容
$cnTable=array(
	'序号',//list1
	'会员名',//list2
	'会员ID',//list3
	'统计',//list4
	'记录数',//list5
	'账户余额',//list6
	'账户积分',//list7
);//列表名
$excel-> getExcel($data,$cnTable,$enTable,'other',20);
//20单元格宽度，如果设置为‘auto’ 表示自适应宽度，也可是具体的数字

$success=1;
?>
