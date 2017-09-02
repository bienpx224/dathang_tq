<?php
/*
软件著作权：=====================================================
软件名称：兴奥国际物流转运网站管理系统(简称：兴奥转运系统)V7.0
著作权人：广西兴奥网络科技有限责任公司
软件登记号：2016SR041223
网址：www.xingaowl.com
本系统已在中华人民共和国国家版权局注册，著作权受法律及国际公约保护！
版权所有，未购买严禁使用，未经书面许可严禁开发衍生品，违反将追究法律责任！
*/
require_once($_SERVER['DOCUMENT_ROOT'].'/public/config_top.php');

require_once($_SERVER['DOCUMENT_ROOT'].'/public/function.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/session.php');

$lx=par($_GET['lx']);


if($lx=='msg_number')
{
	$query="select id from msg where new=1 {$Mmy}";
	$num=mysqli_num_rows($xingao->query($query));
	if($num){echo '<span class="badge badge-info">'.$num.'</span>';}
}

elseif($lx=='msg_list')
{
	$query="select id,userid,username,edittime,title from msg where new=1 {$Mmy} order by edittime desc limit 10";
	$sql=$xingao->query($query);
	while($rs=$sql->fetch_array())
	{
	?>
		 <li><a href="/xamember/msg/show.php?id=<?=$rs['id']?>" target="_blank">  <span class="message"> <?=leng($rs['title'],55,"...")?></span> 
		 <span class="time"><?=DateYmd($rs['edittime'])?></span>

		 </a> </li>
 	<?php
	}
	$sql->free(); //释放资源
}
?>

                 
             