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
if(!$off_mall)
{
	exit ("<script>alert('{$LG['mall_order.form_2']}');goBack('uc');</script>");
}
$lx=par($_GET['lx']);


if($lx=='cart_number')
{
	$query="select mlid from mall_order where pay='0' and status<>'3' {$Mmy}";
	$num=mysqli_num_rows($xingao->query($query));
	if($num){echo '<span class="badge badge-warning">'.$num.'</span>';}
}

elseif($lx=='cart_list')
{
	$query="select url,mlid,titleimg,title from mall_order where pay='0' and status<>'3' {$Mmy} order by odid desc";
	$sql=$xingao->query($query);
	while($rs=$sql->fetch_array())
	{
	?>
		<li><a href="<?=$rs['url']?cadd($rs['url']):'/mall/show.php?mlid='.$rs['mlid'];?>" target="_blank"><span class="photo"><img src="<?=ImgAdd($rs['titleimg'])?>"/></span> <span class="message"><?=leng($rs['title'],50,"...")?></span> </a> </li>	
	<?php
	}
	$sql->free(); //释放资源
}
?>

                 
             