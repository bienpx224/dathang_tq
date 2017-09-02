<?php 
$i_total+=1;
$money_total+=$rs['money'];//总计

$data[] = array(
  'list1'=> cadd($rs['username']).' ('.$rs['userid'].')',
  'list2'=> money_kf($rs['type']),
  'list3'=> $rs['fromtable']?fromtableName($rs['fromtable']).'ID:'.$rs['fromid'].'':'',
  'list4'=> cadd($rs['title']).cadd($rs['content']),
  'list5'=> spr($rs['money']).$XAmc,
  'list6'=> DateYmd($rs['addtime'],1)
);	
?>