<?php 
	$bgnum_status_all=CountNum($CN_table='baoguo',$CN_field='',$CN_zhi='',$CN_where="",$CN_userid=$Muserid,$CN_color='default');
	$bgnum_status_kuwai=CountNum($CN_table='baoguo',$CN_field='',$CN_zhi='kuwai',$CN_where="and status in (0,1,1.5) and ware=0",$CN_userid=$Muserid,$CN_color='warning');
	$bgnum_status_ruku=CountNum($CN_table='baoguo',$CN_field='',$CN_zhi='',$CN_where="and status in (2,3) and ware=0",$CN_userid=$Muserid,$CN_color='success');
	$bgnum_status_4=CountNum($CN_table='baoguo',$CN_field='status',$CN_zhi=4,$CN_where="",$CN_userid=$Muserid,$CN_color='important');
	$bgnum_status_6=CountNum($CN_table='baoguo',$CN_field='status',$CN_zhi=6,$CN_where="",$CN_userid=$Muserid,$CN_color='default');
	$bgnum_status_7=CountNum($CN_table='baoguo',$CN_field='status',$CN_zhi=7,$CN_where="",$CN_userid=$Muserid,$CN_color='default');
	$bgnum_status_9=CountNum($CN_table='baoguo',$CN_field='status',$CN_zhi=9,$CN_where="",$CN_userid=$Muserid,$CN_color='default');
	$bgnum_status_10=CountNum($CN_table='baoguo',$CN_field='status',$CN_zhi=10,$CN_where="",$CN_userid=$Muserid,$CN_color='default');
	if($ON_ware){$bgnum_status_ware=CountNum($CN_table='baoguo',$CN_field='ware',$CN_zhi=1,$CN_where="",$CN_userid=$Muserid,$CN_color='default');}
?>
