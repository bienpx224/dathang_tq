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
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');
$headtitle="导出记录";


//获取,处理
$lx=par($_POST['lx']);
$ex_tem=par($_POST['ex_tem']);

$path='/upxingao/export/manage/'.$Xuserid.'/';//保存目录,后面要有/
if($_GET['lx']=='del')
{
	DelAllFile($path);//删除文件
	exit("<script>goBack('c');</script>");
}

if ($lx=='export'&&$ex_tem)
{ 
	//导出-开始______________________________________________________________________
	require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/count/excelExport/tem/'.$ex_tem.'.php');
	//导出-结束______________________________________________________________________
}

if ($success){
?>
<meta charset="utf-8">
<link href="/bootstrap/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
<br><br><br><br>

<div class="alert alert-block alert-info fade in alert_cs col-md-9">
  <h4 class="alert-heading">导出成功: 共导出约有<?=$excel_i?>条</h4>
	<p><br></p>
	<p><a class="btn btn-danger" href="?lx=del">删除服务器上文件并关闭页面 (防止他人下载,下载完后请删除)</a></p>
	<p>注意:导出时会删除之前的文件，因此如果之前文件没下载完，请下载完本次导出文件后再重新导出之前的信息!</p>
</div>
<?php
	echo '<script language=javascript>';
	echo 'location.href="'.$path.$xaname.'";';
	echo '</script>';
	XAtsto($path.$xaname);
}else{
	exit ("<script>alert('{$LG['NoDataExported']}');goBack('uc');</script>");
}
?>