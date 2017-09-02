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

//http://zy/m/template/article_content_intro.php?classid=29&id=74

//获取,处理

$lx=par($_GET['lx']);
$id=(int)$_GET['id'];
//验证,查询
if(!$_SESSION['manage']['userid']&&$lx!='html'){XAts('temp');}
if(!$id){echo ("<script>alert('id{$LG['pptError']}');goBack('uc');</script>");  goto checked; }
$rs=FeData('article','*',"id='{$id}'");

$classid=$rs['classid'];//每页必须有$classid
$cr=ClassData($classid);

$headtitle=$rs['seotitle'.$LT]?cadd($rs['seotitle'.$LT]):cadd($rs['title'.$LT]);
$headtitle.='-'.cadd($cr['name'.$LT]);

require_once($_SERVER['DOCUMENT_ROOT'].'/m/template/incluce/header.php');//放查询的后面

if(!$rs['checked']){echo XAts('checked');  goto checked;}

require_once($_SERVER['DOCUMENT_ROOT'].'/m/template/incluce/top.php');
?>

<!--内容开始-->

<!--图片自动缩小--> 
<script type="text/javascript" src="/js/jQuery.autoIMG.min.js"></script> 
<?php echo '<script type="text/javascript">$(function(){	$("#autoimg").autoIMG();});</script>';//用PHP输出不然DW里提示JS错误?>

<div class="article_ny" id="autoimg">
	<span class="title"><?=cadd($rs['title'.$LT])?></span>
	
	<span class="intr">
	<i class="icon-eye-open"></i> <?=$LG['browse']?>：<script src="/public/onclick.php?table=article&id=<?=cadd($rs['id'])?>"></script> <?=$LG['main.58']?>
	<i class="icon-calendar" style="margin-left:20px;"></i> <?=$LG['release']?>：<?=DateYmd($rs['edittime'],2,$rs['addtime'])?>
	</span> 

	<br>
	<a href="<?=cadd($rs['seokey'.$LT])?>" class="merchantlogo-pic" target="_blank">
	<img src="<?=ImgAdd($rs['img'.$LT])?>" class="verCenter" onload="AutoResizeImage(248,175,this);"/>
	</a>
	
	<table>
		<tr>
			<td valign="top" style="width:75px"><?=$LG['front.149']//网址:?></td>
			<td><a href="<?=cadd($rs['seokey'.$LT])?>"  target="_blank"><?=cadd($rs['seokey'.$LT])?></a></td>
		</tr>
		<tr>
			<td valign="top" style="width:75px"><?=$LG['front.150']//简介:?></td>
			<td><?=cadd($rs['intro'.$LT])?></td>
		</tr>
	</table>

	<p><?=caddhtml($rs['content'.$LT])?></p>	

	<?php if($rs['dow'.$LT]){?>
	<p align="center"><a href="<?=cadd($rs['dow'.$LT])?>" target="_blank" class="btn btn-info input-medium"> <i class="icon-arrow-down"></i> <?=$LG['download']?> </a></p>
	<?php }?>

</div>
<!--内容结束-->
<?php require_once($_SERVER['DOCUMENT_ROOT'].'/m/template/incluce/footer.php');  checked: ?>