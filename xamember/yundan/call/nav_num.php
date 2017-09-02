<?php 
//预先支付才处理
if($ON_yundan_prepay)
{
	$ydnum_status_02=CountNum($CN_table='yundan',$CN_field='status',$CN_zhi=-2,$CN_where="",$CN_userid=$Muserid,$CN_color='default');
}

$ydnum_status_all=CountNum($CN_table='yundan',$CN_field='',$CN_zhi='',$CN_where="",$CN_userid=$Muserid,$CN_color='default');
$ydnum_status_01=CountNum($CN_table='yundan',$CN_field='status',$CN_zhi=-1,$CN_where="",$CN_userid=$Muserid,$CN_color='default');

$ydnum_status_0=CountNum($CN_table='yundan',$CN_field='status',$CN_zhi=0,$CN_where="",$CN_userid=$Muserid,$CN_color='default');
$ydnum_status_1=CountNum($CN_table='yundan',$CN_field='status',$CN_zhi=1,$CN_where="",$CN_userid=$Muserid,$CN_color='important');
$ydnum_status_2=CountNum($CN_table='yundan',$CN_field='status',$CN_zhi=2,$CN_where="",$CN_userid=$Muserid,$CN_color='warning');
$ydnum_status_3=CountNum($CN_table='yundan',$CN_field='status',$CN_zhi=3,$CN_where="",$CN_userid=$Muserid,$CN_color='warning');
$ydnum_status_4=CountNum($CN_table='yundan',$CN_field='status',$CN_zhi=4,$CN_where="",$CN_userid=$Muserid,$CN_color='default');
$ydnum_status_chuku=CountNum($CN_table='yundan',$CN_field='',$CN_zhi='chuku',$CN_where="and status>4 and status<30",$CN_userid=$Muserid,$CN_color='success');
?>

