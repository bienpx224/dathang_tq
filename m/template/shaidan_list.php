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

//http://zy/m/template/shaidan_list.php?classid=25

//获取,处理

$lx=par($_GET['lx']);

$classid=(int)$_GET['classid'];

//验证,查询
if(!$_SESSION['manage']['userid']&&$lx!='html'){XAts('temp');}
if(!$classid)
{
	echo ("<script>alert('classid{$LG['pptError']}');goBack('uc');</script>");  goto checked;
}

$cr=ClassData($classid);
$headtitle=$cr['seotitle'.$LT]?cadd($cr['seotitle'.$LT]):cadd($cr['name'.$LT]);
$search.="&classid={$classid}";

require_once($_SERVER['DOCUMENT_ROOT'].'/m/template/incluce/header.php');//放查询的后面
require_once($_SERVER['DOCUMENT_ROOT'].'/m/template/incluce/top.php');//放查询的后面
//图片扩大插件
require_once($_SERVER['DOCUMENT_ROOT'].'/public/enlarge/call.html');
?>
<!--内容开始-->
<div class="article_ny">
	<span class="title"><?=cadd($cr['name'.$LT])?> </span>
	<div>
<?php 
$where="checked='1' and types='0' ";
if($ON_shaidan_language&&$LT!='CN'){$where.=" and language='{$LT}'";}
elseif($ON_shaidan_language&&$LT=='CN'){$where.=" and (language='{$LT}' or language='')";}

$order=' order by sdid desc';//默认排序
$query="select * from shaidan where {$where} {$order}";
$line=8;$page_line=3;//分页处理,不设置则默认
include($_SERVER['DOCUMENT_ROOT'].'/public/page.php');
while($rs=$sql->fetch_array())
{
	$img=$rs['img'];if(!is_array($img)&&$img){$img=explode(",",$img);}
	$img=$img[0];
?>
		<div class="List clearfix"> <a href="/m<?=pathLT($rs['path'])?>"  target="_blank" ><img class="img" src="<?=ImgAdd($img)?>"></a>    
			<div class="article">
				<a href="/m<?=pathLT($rs['path'])?>" target="_blank"  style="color:<?=cadd($rs['titlecolor'])?>"><?=substr_cut($rs['username'],$length=3)?></a>
				
				<p><?=leng($rs['content'],100,"...")?></p>
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