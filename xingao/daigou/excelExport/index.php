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
if(!$ON_daigou){ exit("<script>alert('{$LG['daigou.45']}');goBack('uc');</script>"); }

$lx=par($_REQUEST['lx']);
$callFrom=par($_POST['call_lx']);
$ex_tem=par($_POST['ex_tem']);
$path='/upxingao/export/manage/'.$Xuserid.'/';//保存目录

if (!$ex_tem){exit ("<script>alert('{$LG['daigou.89']}');goBack('c');</script>");}

//此页面支持会员操作
if($callFrom=='member'){
	require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/session.php');
	$id_name='dgid';
}else{
	$callFrom='manage';
	$pervar='daigou_ex';require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');
	$id_name='Xdgid';
}

$dgid=par(ToStr($_GET['dgid']));
if(!$dgid||is_array($_GET['dgid'])){$dgid=$_SESSION[$id_name];}//如果是数组,说明是从底部点击的按钮,要用_SESSION才能获取分页里的勾选


if ($lx=='del')
{
	DelAllFile($path);//删除文件
	exit("<script>goBack('c');</script>");
}

//获取及验证条件---------------------------------
if($lx=='tj')
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/daigou/call/where_save.php');//输出:$where
}else{
	if (!$dgid){exit ("<script>alert('{$LG['daigou.90']}');goBack('c');</script>");}
	$where=" and dgid in ({$dgid})";
}

$fileNameArr='';
$success=0;
$excel_i=0;

if(!$order){$order='order by dgdh asc';}
require($_SERVER['DOCUMENT_ROOT'].'/xingao/daigou/excelExport/tem/'.$ex_tem.'.php'); 

if($success)
{
?>
<meta charset="utf-8">
<link href="/bootstrap/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
<br><br><br><br>

<div class="alert alert-block alert-info fade in alert_cs col-md-9">
  <h4 class="alert-heading"><?=$LG['pptExportSuccess'].$excel_i//导出成功: 共导出约有?></h4>
	<p><br></p>
	<p><a class="btn btn-danger" href="?lx=del"><?=$LG['pptDelExport'];//删除服务器上文件并关闭页面 (防止他人下载,下载完后请删除)?></a></p>
	<p><?=$LG['pptDelExportRemind'];//注意:导出时会删除之前的文件，因此如果之前文件没下载完，请下载完本次导出文件后再重新导出之前的信息!?></p>
	
	
</div>
<?php
	echo '<script language=javascript>';
	echo 'location.href="'.$path.$xaname.'";';
	echo '</script>';
	XAtsto($path.$xaname);

}else{
	exit ("<script>alert('{$LG['daigou.92']}');goBack('c');</script>");//没有符合条件的代购单可导出,请检查是否选择正确!
}
//$_SESSION[$id_name]='';
?>

