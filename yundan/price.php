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

$headtitle=$LG['front.3'];
require_once($_SERVER['DOCUMENT_ROOT'].'/template/incluce/header.php');//放查询的后面
require_once($_SERVER['DOCUMENT_ROOT'].'/template/incluce/service.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/template/incluce/nav.php');


?>

<!--内容开始-->
<style>
.class_banner{background:url(<?=ClassImg($classid)?>) no-repeat center top;}
</style>
<div class="class_banner"></div>
<div class="center">
	<div class="article_left fl">
		<div class="search-container">
			<?php require_once($_SERVER['DOCUMENT_ROOT'].'/yundan/call/price_form.php');?>
		</div>
	</div>
	<div class="article_right fr">
      <div class="add_fr"><a href="/"><?=$LG['name.nav_0'];//首页?></a> > <?=$headtitle?></div>
	  <div class="right_tit"><i class="icon-th-large"></i> <?=$headtitle?></div>
		<div class="article_content" >
			<div class="clear"></div>
			<div class="article_ny">
				<p> 
				<!--内容开始--> 
				<?php require_once($_SERVER['DOCUMENT_ROOT'].'/yundan/call/price_show.php');?>
				<!--内容结束--> 
				</p>
			</div>
		</div>
	</div>
	<div class="clear"></div>
</div>
<div class="clear"></div>

<!--内容结束--> 
<?php require_once($_SERVER['DOCUMENT_ROOT'].'/template/incluce/footer.php');?>