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
$headtitle=$LG['excelExport'];


//获取,处理
$lx=par($_POST['lx']);
$table=par($_POST['table']);
$where=$_POST['where'];
if(!$where){$where='1=1';}
$where.=$Mmy;

$path='/upxingao/export/member/'.$Muserid.'/';//保存目录,后面要有/

if ($_GET['lx']=='del')
{
	DelAllFile($path);//删除文件
	exit("<script>goBack('c');</script>");
}

if ($lx=='export'&&$table)
{ 
	//导出-开始______________________________________________________________________
	require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/member/call/excel_export_bak.php');
	//导出-结束______________________________________________________________________
}
?>
