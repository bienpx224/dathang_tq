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
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');

$lx=par($_GET['lx']);


if($lx=='msg_number')
{
	$tnum=mysqli_num_rows($xingao->query("select id from msg where status>10"));
	$mynum=mysqli_num_rows($xingao->query("select id from msg where status>10 and from_userid='{$Xuserid}'"));
	if($tnum||$mynum){echo '<span class="badge badge-info" title="我的有'.$mynum.'条/共'.$tnum.'条">'.$mynum.'/'.$tnum.'</span>';}
}

elseif($lx=='msg_list')
{
	$query="select id,userid,username,edittime,title from msg where status>10 and from_userid='{$Xuserid}' order by edittime desc limit 10";
	$sql=$xingao->query($query);
	while($rs=$sql->fetch_array())
	{
	?>
      <li><a href="/xingao/msg/show.php?id=<?=$rs['id']?>" target="_blank"> <span class="photo"><img src="<?=FeData('member','img',"userid='{$rs['userid']}'")?>" width="45" height="45"/></span> <span class="subject"> <span class="from" title="<?=$rs['username']?>"><?=leng($rs['username'],10,"...")?></span> <span class="time"><?=DateYmd($rs['edittime'],'m-d H:i')?></span> </span> <span class="message"> <?=leng($rs['title'],55,"...")?> </span> </a> </li>
 	<?php
	}
	$sql->free(); //释放资源
}
?>

                 
             