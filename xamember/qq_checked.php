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

$headtitle='登录成功';

//专用于QQ快捷登录页面，只为显示头像和昵称就会通过审核，不能显示未登录相关标记
?>
<link href="/bootstrap/css/pages/login.css" rel="stylesheet" type="text/css"/>
<link href="/css/member.css" rel="stylesheet" type="text/css"/>
<link href="/bootstrap/css/themes/<?=$theme_member?>" rel="stylesheet" type="text/css" id="style_color"/>
<?php 
if($ism)
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/m/template/incluce/header.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/m/template/incluce/top.php');
	echo '<link href="/css/xingao_m.css" rel="stylesheet" type="text/css"/>';
}else{
	require_once($_SERVER['DOCUMENT_ROOT'].'/template/incluce/header.php');
	//require_once($_SERVER['DOCUMENT_ROOT'].'/template/incluce/nav.php');
	echo '<style>.gb_member {background-image: url(/images/gb_member_login_'.rand(1,3).'.jpg);}</style>';
}?>


<!--内容开始-->
<div class="gb_member">
<div class="login">
<div class="content fr">
		<!-- BEGIN LOGIN FORM -->
		<form class="login-form" action="login_save.php" method="post">
        <input name="lx" type="hidden" value="<?=$lx?>">
        <input name="tokenkey" type="hidden" value="<?=$tokenkey?>">
			<h3 class="form-title"><?=$headtitle?></h3>
<?php
	if($_SESSION['connect']['img'])
	{
		echo '<h5 class="form-title">';
		echo '<img src='.$_SESSION['connect']['img'].'>';
		echo '<br>'.$_SESSION['connect']['nickname'];
		echo '</h5>';
	}
?>		
			
		</form>
		<!-- END LOGIN FORM -->        
	</div>
</div>
</div>
<div class="clear"></div>

<?php 
require_once($_SERVER['DOCUMENT_ROOT'].'/js/checkJS.php');//通用验证
require_once($_SERVER['DOCUMENT_ROOT'].$m.'/template/incluce/footer.php');?>
