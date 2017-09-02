<?php 
$i_total+=1;

//重量
//$rs['weight']=spr($rs['weight'])*$XAwtkg;
$weight_total+=$rs['weight'];//总计

//总费用
$all_fee=spr($rs['money']+$rs['tax_money']);
$all_fee_total+=$all_fee;//总计

//优惠
$sp=SettlementPreferential($fromtable='yundan',$rs['ydid']);
$reduce=spr($sp['money_co']+$sp['money_jf']);
$sp_show=$reduce.$XAmc;
if($reduce>0){
	$sp_show1='';if($sp['money_co']>0){$sp_show1=$LG['settlement.Xcall_other_total_2'].spr($sp['money_co']).$XAmc.'；';}
	$sp_show2='';if($sp['money_jf']>0){$sp_show2=$LG['settlement.Xcall_other_total_3'].spr($sp['money_jf']).$XAmc.'；';}
	$sp_show.="({$sp_show1}{$sp_show2})";
}
$reduce_total+=$reduce;//总计

//退费
$sr=SettlementRefund($fromtable='yundan',$rs['ydid']);
$sr_show='';
if($sr>0){$sr_show.=$sr.$XAmc.' ('.$LG['settlement.Xcall_other_total_4'].')';}
$sr_total+=$sr;//总计

//收费
$sc=SettlementCharge($fromtable='yundan',$rs['ydid']);
$sc_show=spr($sc-$sr).$XAmc;
if($sr>0){$sc_show.=LGtag($LG['settlement.Xcall_other_total_5'],'<tag1>=='.spr($sr).$XAmc);}
$sc_total+=$sc;//总计

$data[] = array(
  'list1'=> cadd($rs['username']).' ('.$rs['userid'].')',
  'list2'=> cadd($rs['ydh']),
  'list3'=> cadd($rs['f_name']),
  'list4'=> cadd($rs['s_name']),
  'list5'=> spr($rs['weight']).$XAwt,
  'list6'=> $all_fee.$XAmc,
  'list7'=> $sp_show,
  'list8'=> $sr_show,
  'list9'=> $sc_show,
  'list10'=> striptags(Tally($rs['tally'])),
  'list11'=> DateYmd($rs['chukutime']),
);	
?>