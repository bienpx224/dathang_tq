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
$pervar='off_settlement';require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/session.php');

//---------------------------------------------------------------------------------
$lx=par($_GET['lx']);
$path='/upxingao/export/member/'.$Muserid.'/';//保存目录
if ($lx=='del')
{
	DelAllFile($path);//删除文件
	exit ("<script>goBack('c');</script>");
}

//---------------------------------------------------------------------------------
//获取
$ex_tem=par($_GET['ex_tem']);

//验证
if(!CheckEmpty($_GET['so'])){exit ("<script>alert('{$LG['settlement.excel_export_1']}');goBack('c');</script>");}
if (!$ex_tem){exit ("<script>alert('{$LG['settlement.excel_export_2']}');goBack('c');</script>");}

//处理
if(stristr($ex_tem,'yundan')){
	require($_SERVER['DOCUMENT_ROOT'].'/xingao/settlement/call/list_yundan_where.php');//条件处理,返回{$select} {$where} {$group} {$order}
}elseif(stristr($ex_tem,'other')){
	require($_SERVER['DOCUMENT_ROOT'].'/xingao/settlement/call/list_other_where.php');//条件处理,返回{$select} {$where} {$group} {$order}
}
$where.=$Mmy;


$fileNameArr='';
$success=0;
$excel_i=0;
//---------------------------------------------------------------------------------
require($_SERVER['DOCUMENT_ROOT'].'/xingao/settlement/excel_export/'.$ex_tem.'.php'); 

?>
<meta charset="utf-8">
<link href="/bootstrap/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
<br><br><br><br>

<div class="alert alert-block alert-info fade in alert_cs col-md-9">
  <h4 class="alert-heading"><?=$LG['pptExportSuccess']?><?=$excel_i?></h4>

	<p><br></p>
	<p><a class="btn btn-danger" href="?lx=del"><?=$LG['pptDelExport']?></a></p>
	<p><?=$LG['pptDelExportRemind']?></p>
	
	
</div>
<?php 	
	echo '<script language=javascript>';
	echo 'location.href="'.$path.$xaname.'";';
	echo '</script>';
	XAtsto($path.$xaname);
?>
