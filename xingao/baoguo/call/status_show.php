<?php
if(spr($rs['status'])==3&&!$fahuo){echo baoguo_Status(5,2);}else{echo baoguo_Status(spr($rs['status']),2);}
?>

<br>
<font class="gray2">
<?php 
//显示已生成的运单号
$query_yd="select ydid,ydh,status,statustime,statusauto from yundan where find_in_set('{$rs[bgid]}',bgid) order by ydh asc";
$sql_yd=$xingao->query($query_yd);
while($yd=$sql_yd->fetch_array())
{
	echo ' <a href="../yundan/show.php?ydid='.$yd['ydid'].'" target="_blank">'.$yd['ydh'].'</a>';
	echo '(<a href="/yundan/status.php?ydh='.$yd['ydh'].'" target="_blank">';
	echo status_name(spr($yd['status']),$yd['statustime'],$yd['statusauto']);
	echo '</a>) <br>';
}
?>
</font>