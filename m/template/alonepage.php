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

//http://zy/m/template/alonepage.php?classid=2

//获取,处理

$lx=par($_GET['lx']);

$classid=(int)$_GET['classid'];//每页必须有$classid

//验证,查询
if(!$_SESSION['manage']['userid']&&$lx!='html'){XAts('temp');}
if(!$classid)
{
	echo ("<script>alert('classid{$LG['pptError']}');goBack('uc');</script>");  goto checked;
}

$rs=FeData('class','*',"classid='{$classid}'");
$headtitle=$rs['seotitle'.$LT]?cadd($rs['seotitle'.$LT]):cadd($rs['name'.$LT]);
require_once($_SERVER['DOCUMENT_ROOT'].'/m/template/incluce/header.php');//放查询的后面

if(!$rs['checked']){echo XAts('checked');  goto checked;}

require_once($_SERVER['DOCUMENT_ROOT'].'/m/template/incluce/top.php');
?>

<!--内容开始-->

<!--图片自动缩小--> 
<script type="text/javascript" src="/js/jQuery.autoIMG.min.js"></script> 
<?php echo '<script type="text/javascript">$(function(){	$("#autoimg").autoIMG();});</script>';//用PHP输出不然DW里提示JS错误?>

<div class="article_ny" id="autoimg">
	<span class="title"><?=cadd($rs['name'.$LT])?></span>
	
	<span class="intr">
		<i class="icon-eye-open"></i> <?=$LG['browse']?>：<script src="/public/onclick.php?table=class&classid=<?=cadd($rs['classid'])?>&id="></script> <?=$LG['main.58']?>
		<i class="icon-calendar" style="margin-left:20px;"></i> <?=$LG['release']?>：<?=DateYmd($rs['edittime'],2,$rs['addtime'])?> 
	</span> 
	
	<?=caddhtml($rs['content'.$LT])?>
</div>
<!--内容结束-->
<?php require_once($_SERVER['DOCUMENT_ROOT'].'/m/template/incluce/footer.php');  checked: ?>