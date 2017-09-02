<?php 
//全部运单ID
$rs['ydid']='';$rs['weight']='';$rs['money']='';$rs['tax_money']='';
$query_total="select ydid,weight,money,tax_money from yundan where  {$where} and userid='{$rs[userid]}'";
$sql_total=$xingao->query($query_total);
while($ta=$sql_total->fetch_array())
{
	$rs['ydid'].=$ta['ydid'].',';//总运单ID
	$rs['weight']+=$ta['weight'];//总重量
	$rs['money']+=$ta['money'];//总费用
	$rs['tax_money']+=$ta['tax_money'];//总额外税费
}
$rs['ydid']=DelStr($rs['ydid']);
$rs['ydh']=arrcount($rs['ydid']).$LG['settlement.Xlist_yundan_total'];//总运单数量
?>