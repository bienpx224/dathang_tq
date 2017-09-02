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
require_once($_SERVER['DOCUMENT_ROOT'].'/public/html.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/public/function.php');
$noper=1;require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/session.php');

if($_COOKIE['client']=='xazy'){$client=1;}//是否来自会员的客户端
$headtitle=$LG['front.2'];
require_once($_SERVER['DOCUMENT_ROOT'].'/m/template/incluce/header.php');//放查询的后面

if($client)
{
	$ac2='xa_active';require_once($_SERVER['DOCUMENT_ROOT'].'/client/nav_m.php');
}else{
	require_once($_SERVER['DOCUMENT_ROOT'].'/m/template/incluce/top.php');
}

if(!$off_shenfenzheng){exit ("<script>alert('{$LG['front.4']}');goBack();</script>");}
if(!$off_upload_cert){exit ("<script>alert('{$LG['front.5']}');goBack();</script>");}
?>
<!--内容开始-->
<div class="article_ny" id="autoimg">
	<span class="title"><?=$headtitle?></span>
	<span class="intr">
	<?php $m='/m';require_once($_SERVER['DOCUMENT_ROOT'].'/yundan/call/upload_form.php');?>
	</span> 
</div>
<!--内容结束--> 
<?php 
if($client)
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/client/footer.php');
}else{
	require_once($_SERVER['DOCUMENT_ROOT'].'/m/template/incluce/footer.php');
}
?>
