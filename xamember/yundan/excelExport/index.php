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
require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/session.php');

$lx=par($_REQUEST['lx']);
$callFrom=par($_POST['call_lx']);
$ex_tem=par($_POST['ex_tem']);
$use_where=par($_POST['use_where']);
$path='/upxingao/export/member/'.$Muserid.'/';//保存目录

if ($lx=='del')
{
	DelAllFile($path);//删除文件
	exit("<script>goBack('c');</script>");
}

if (!$ex_tem){exit ("<script>alert('{$LG['daigou.89']}');goBack('c');</script>");}
if($ex_tem!='simple'){exit ("<script>alert('{$LG['yundan.3']}');goBack('c');</script>");}


$id_name='ydid';
$ydid=par(ToStr($_GET['ydid']));
if(!$ydid||is_array($_GET['ydid'])){$ydid=$_SESSION[$id_name];}//如果是数组,说明是从底部点击的按钮,要用_SESSION才能获取分页里的勾选

//获取及验证条件---------------------------------
if($use_where)
{
	$where=" and {$_SESSION['Mexport_yundan']}";
}else{
	if(!$ydid){exit ("<script>alert('{$LG['yundan.4']}');goBack('c');</script>");}
	$where=" and ydid in ({$ydid})";
}


$fileNameArr='';
$success=0;
$excel_i=0;

if(!$order){$order='order by ydh asc';}
require($_SERVER['DOCUMENT_ROOT'].'/xingao/yundan/excelExport/tem/'.$ex_tem.'.php'); 

if($success)
{
?>
<meta charset="utf-8">
<link href="/bootstrap/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
<br><br><br><br>

<div class="alert alert-block alert-info fade in alert_cs col-md-9">
  <h4 class="alert-heading"><?=$LG['pptExportSuccess'].$excel_i?></h4>
	<?php if($fileNameArr){?>
	<p>
	<form action="/xingao/yundan/zip_export.php" method="post">
	<input type="hidden" name="fileNameArr" value="<?=DelStr($fileNameArr,',',1)?>">
	<button type="submit"  class="btn btn-primary"> <i class="icon-ok"></i> <?=$LG['yundan.5'];//下载身份证 (Excel文件下载完后再点击)?> </button>
	</form>
	</p>
	<p><?=$LG['yundan.9'];//如果身份证文件名有乱码,请把浏览器默认编码改为GBK?></p>
	<?php }?>
	
	<p><br></p>
	<p><a class="btn btn-danger" href="?lx=del"><?=$LG['pptDelExport'];//删除服务器上文件并关闭页面 (防止他人下载,下载完后请删除)?></a></p>
	<p><?=$LG['yundan.8'];//注意:导出时会删除之前的文件，因此如果之前文件没下载完，请下载完本次导出文件后再重新导出之前的信息!?></p>
	
	
</div>
<?php
	echo '<script language=javascript>';
	echo 'location.href="'.$path.$xaname.'";';
	echo '</script>';
	XAtsto($path.$xaname);

}else{
	exit ("<script>alert('{$LG['yundan.7']}');goBack('c');</script>");
}
//$_SESSION[$id_name]='';
?>

