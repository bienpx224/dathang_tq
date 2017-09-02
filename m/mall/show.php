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
//运费自定义计算公式

if(!$off_mall){	exit ("<script>alert('{$LG['front.136']}');goBack('uc');</script>");}

//处理:1125

//获取,处理
$lx=par($_GET['lx']);
$mlid=(int)$_GET['mlid'];

if(!$mlid)
{
	exit ("<script>alert('id{$LG['pptError']}');goBack('uc');</script>");
}

$rs=FeData('mall','*',"mlid='{$mlid}'");

$classid=$rs['classid'];//每页必须有$classid
$cr=ClassData($classid);

$headtitle=$rs['seotitle'.$LT]?cadd($rs['seotitle'.$LT]):cadd($rs['title'.$LT]);
$headtitle.='-'.cadd($cr['name'.$LT]);

require_once($_SERVER['DOCUMENT_ROOT'].'/m/template/incluce/header.php');//放查询的后面
require_once($_SERVER['DOCUMENT_ROOT'].'/m/template/incluce/top.php');

if(!$rs['checked'])
{
	XAts('mall_checked');
	exit();
}

if($rs['url'.$LT])
{
	echo '<script language=javascript>';
	echo 'location.href="'.$rs['url'.$LT].'";';
	echo '</script>';
	XAtsto($rs['url'.$LT]);
	exit();
}

?>
<!--商城样式开始-->
<link href="/css/mall.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/js/cloud-zoom.1.0.2.js"></script>
<!--商城样式结束-->

<!--内容开始-->
<!--图片自动缩小--> 
<script type="text/javascript" src="/js/jQuery.autoIMG.min.js"></script> 
<?php echo '<script type="text/javascript">$(function(){	$("#autoimg").autoIMG();});</script>';//用PHP输出不然DW里提示JS错误?>

<div class="article_ny" id="autoimg">
	<span class="title"><?=cadd($rs['title'.$LT])?></span>
	<span class="intr">
		<a href="#pl"  onClick="changeTabs(3)">
		<i class="icon-thumbs-up"></i> <?=$LG['comments']?><script src="/public/onclick.php?table=mall&field=plclick&id=<?=$rs['mlid']?>"></script>
		</a>
		<i class="icon-eye-open" style="margin-left:20px;"></i> <?=$LG['browse']?><script src="/public/onclick.php?table=mall&id=<?=cadd($rs['mlid'])?>"></script>
		<i class="icon-calendar" style="margin-left:20px;"></i> <?=DateYmd($rs['edittime'],3,$rs['addtime'])?> 
	</span> 
	
      <!--订购表单-开始-->
      <?php $callFrom_m='m';require_once($_SERVER['DOCUMENT_ROOT'].'/mall/call/show_cartform.php');?>
      <!--订购表单-结束--> 
<br>
<br>

      <!--下部商品-开始-->
      <?php require_once($_SERVER['DOCUMENT_ROOT'].'/mall/call/show_content.php');?>
      <!--下部商品-结束--> 

</div>
<!--内容结束-->
<?php require_once($_SERVER['DOCUMENT_ROOT'].'/m/template/incluce/footer.php');?>
