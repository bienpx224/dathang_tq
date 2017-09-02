<?php 
$sr_total_show='';if($sr_total>0){$sr_total_show='('.$LG['settlement.Xcall_other_total_1'].$sr_total.$XAmc.')';}
$data[] = array(
  'list1'=> $LG['total'],
  'list2'=> $i_total,
  'list3'=> '',
  'list4'=> '',
  'list5'=> spr($weight_total).$XAwt,
  'list6'=> spr($all_fee_total).$XAmc,
  'list7'=> spr($reduce_total).$XAmc,
  'list8'=> spr($sr_total).$XAmc,
  'list9'=> spr($sc_total).$XAmc.$sr_total_show,
  'list10'=> '',
  'list11'=> '',
);	
$weight_total=0;$all_fee_total=0;$reduce_total=0;$sr_total=0;$sc_total=0;$i_total=0;



//留空一行
$data[] = array(
  'list1'=> '',
  'list2'=> '',
  'list3'=> '',
  'list4'=> '',
  'list5'=> '',
  'list6'=> '',
  'list7'=> '',
  'list8'=> '',
  'list9'=> '',
  'list10'=> '',
  'list11'=> '',
);
?>