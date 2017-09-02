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
$headtitle=$LG['ShowImg.1'];
require_once($_SERVER['DOCUMENT_ROOT'].'/template/incluce/header.php');
$img=cadd(urldecode($_GET['img']));
?>


<div align="center"  id="autoimg">
<?php
$arr=$img;
if(!is_array($arr)&&$arr){$arr=explode(",",$arr);}//转数组
foreach($arr as $arrkey=>$value)
{ 
	$value=par($value,0,1);
	if (CheckSaveTranFiletype($value)){echo '<br>'.$LG['ShowImg.2'].$value;continue;}
	if (!stristr($value,'/upxingao/')&&!stristr($value,'/images/')&&!stristr($value,'/img/') )	{echo '<br>'.$LG['ShowImg.3'].$value;continue;}
	echo '<img src="'.$value.'"/>';
}
?>
</div>



<!--图片自动缩小-->
<script type="text/javascript" src="/js/jQuery.autoIMG.min.js"></script>
<?php echo '<script type="text/javascript">$(function(){	$("#autoimg").autoIMG();});</script>';//用PHP输出不然DW里提示JS错误?>

<style>
img{ padding: 2px; border: 1px solid #CCCCCC; margin-bottom:20px; }
</style>




<div align="center" class="fixed_btn">
<style>body{margin-bottom:50px !important;}</style><!--增高底部高度-->

   <button type="button" class="btn btn-danger" onClick="goBack('c');"  style="margin-left:30px;"><i class="icon-remove"></i> <?=$LG['ShowImg.4']?> </button>
</div>
         
