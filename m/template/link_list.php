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

$class=(int)$_GET['class'];
if(!$class)
{
	echo ("<script>alert('class{$LG['pptError']}');goBack('uc');</script>");  goto checked;
}
$search.="&class={$class}";
$headtitle=LinkClass($class);
require_once($_SERVER['DOCUMENT_ROOT'].'/m/template/incluce/header.php');//放查询的后面
require_once($_SERVER['DOCUMENT_ROOT'].'/m/template/incluce/top.php');//放查询的后面
?>

<!--内容开始-->
<div class="article_ny">
	<span class="title"><?=LinkClass($class)?></span>
	<div>
<?php 
$order=' order by myorder desc,id desc';//默认排序
$query="select * from link where checked=1 and class='{$class}' {$order}";
$line=15;$page_line=3;//分页处理,不设置则默认
include($_SERVER['DOCUMENT_ROOT'].'/public/page.php');
while($rs=$sql->fetch_array())
{
?>
	<div class="imgw_list"> <a href="<?=cadd($rs['url'])?>" target="_blank"><img src="<?=ImgAdd($rs['img'])?>" /></a>
	  
		  <div class="title">
		  <a href="<?=cadd($rs['url'])?>" target="_blank"><?=leng($rs['name'],200,"...");?></a>
		 </div>
		</div>
	<?php 
	}
	if (!mysqli_num_rows($sql)){ echo $LG['pptNot'];} 
	?>
	</div>
	<div class="row">
	<?=$listpage?>
	</div>
</div>
<!--内容结束-->
<?php require_once($_SERVER['DOCUMENT_ROOT'].'/m/template/incluce/footer.php');  checked: ?>