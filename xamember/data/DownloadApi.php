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

/*
	---------------------------------------此页面是完全独立页面---------------------------------------

	注意：此页不能有任何HMTL或空行，否则无法解压
	因此不能指定用编码<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	不能调用如 require_once($_SERVER['DOCUMENT_ROOT'].'/public/function.php');
*/


//显示错误
ini_set('display_errors','On');
error_reporting(E_ALL ^ E_NOTICE^ E_WARNING);//显示错误级别：显示除去 E_NOTICE 之外的所有错误信息 
@session_start();



//----------------生成ZIP压缩包，打包下载-------------------------
$filename=$_SERVER["DOCUMENT_ROOT"].'/upxingao/APIExplain.zip';//保存目录加文件名
@array_map('unlink',glob($filename));//删除原选的文件

$zip = new ZipArchive;// 使用本类，linux需开启zlib，windows需开启php_zip.dll
if($zip->open($filename,ZIPARCHIVE::CREATE) === TRUE)
{
	/*
		注意：
		不能用绝对路径
		不能有中文,空格,特殊符号
	*/
	$zip->addFile('../../doc/APIExplain/AddWaybill.php');
	$zip->addFile('../../doc/APIExplain/QueryWaybill.php');
	$zip->addFile('../../doc/APIExplain/Explain.docx');
	$zip->close();
}





//下面是输出下载;
@header ( "Cache-Control: max-age=0" );
@header ( "Content-Description: File Transfer" );
@header ( 'Content-disposition: attachment; filename=' . basename ( $filename ) ); // 文件名
@header ( "Content-Type: application/zip" ); // zip格式的
@header ( "Content-Transfer-Encoding: binary" ); // 告诉浏览器，这是二进制文件
@header ( 'Content-Length: ' . filesize ( $filename ) ); // 告诉浏览器，文件大小
@readfile ( $filename );//输出文件;
?>