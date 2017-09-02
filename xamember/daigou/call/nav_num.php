<?php 
$dgnum_status_all=CountNum($CN_table='daigou',$CN_field='',$CN_zhi='',$CN_where="",$CN_userid=$Muserid,$CN_color='default');

$dgnum_memberContentReplyNew=CountNum($CN_table='daigou',$CN_field='',$CN_zhi='',$CN_where=" and status not in (10) and memberContentReplyNew=1",$CN_userid=$Muserid,$CN_color='warning');

$dgnum_status_pay=CountNum($CN_table='daigou',$CN_field='',$CN_zhi='',$CN_where=" and status in (2,4)",$CN_userid=$Muserid,$CN_color='warning');

$dgnum_status_storage=CountNum($CN_table='daigou',$CN_field='',$CN_zhi='',$CN_where=" and status in (3,5,6,7)",$CN_userid=$Muserid,$CN_color='default');

if($dg_checked)
{
	$dgnum_status_0=CountNum($CN_table='daigou',$CN_field='status',$CN_zhi=0,$CN_where="",$CN_userid=$Muserid,$CN_color='default');
}

$dgnum_status_1=CountNum($CN_table='daigou',$CN_field='status',$CN_zhi=1,$CN_where="",$CN_userid=$Muserid,$CN_color='important');

$dgnum_status_2=CountNum($CN_table='daigou',$CN_field='status',$CN_zhi=2,$CN_where="",$CN_userid=$Muserid,$CN_color='default');
$dgnum_status_4=CountNum($CN_table='daigou',$CN_field='status',$CN_zhi=4,$CN_where="",$CN_userid=$Muserid,$CN_color='warning');

$dgnum_status_inStorage=CountNum($CN_table='daigou',$CN_field='',$CN_zhi=0,$CN_where=" and status in (8,9)",$CN_userid=$Muserid,$CN_color='default');

$dgnum_status_delivery=CountNum($CN_table='daigou',$CN_field='',$CN_zhi=0,$CN_where=" and status in (8,9) and dgid in (select dgid from daigou_goods where inventoryNumber>0) ",$CN_userid=$Muserid,$CN_color='danger');

$dgnum_status_9_5=CountNum($CN_table='daigou',$CN_field='status',$CN_zhi=9.5,$CN_where="",$CN_userid=$Muserid,$CN_color='default');

$dgnum_status_10=CountNum($CN_table='daigou',$CN_field='status',$CN_zhi=10,$CN_where="",$CN_userid=$Muserid,$CN_color='default');

?>

