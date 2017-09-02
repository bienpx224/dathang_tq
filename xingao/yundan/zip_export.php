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
	
	简单版见:/xamember/data/DownloadApi.php
*/

//显示错误
ini_set('display_errors','On');
error_reporting(E_ALL ^ E_NOTICE^ E_WARNING);//显示错误级别：显示除去 E_NOTICE 之外的所有错误信息 
@session_start();
@date_default_timezone_set("PRC");//设置中国时区 

$fileNameArr=$_POST["fileNameArr"];//要打包的文件

//$fileNameArr= "../upxingao/404.gif|||要保存的文件名,../images/app_btn.png|||要保存的文件名";
//注意：不能用绝对路径


//----------------生成ZIP压缩包，打包下载-------------------------
if($fileNameArr)
{
	//可修改－开始
	$path=$_SERVER["DOCUMENT_ROOT"].'/upxingao/export/manage/'.$_SESSION['manage']['userid'].'/';//保存目录
	if(!is_dir($path)){mkdir($path, 0700);}//目录是否存在，不存在就创建
	@array_map('unlink',glob($path.'*'));//删除该目录下所有文件再生成
	$filename=date("Y-m-d H-i-s").".zip";//文件名
	$filename=$path.$filename;//目录加文件名
	//可修改－结束

	
	// 生成文件
	$zip = new ZipArchive (); // 使用本类，linux需开启zlib，windows需开启php_zip.dll
	if ($zip->open ( $filename, ZIPARCHIVE::CREATE ) !== TRUE) {
		exit ( '无法打开文件，或者文件创建失败' );
	}
	
	$fileNameArr=explode(",",$fileNameArr);
	foreach ( $fileNameArr as $val ) 
	{
		//只能打包/upxingao/目录下的文件,防止入侵
		if (stristr($val,'/upxingao/'))
		{
			$name=explode("|||",$val);//获取姓名
			$sfr=basename ($name[0]);//文件地址
			$sfr=pathinfo($sfr, PATHINFO_EXTENSION); 
			
			//文件名:由于页面不能指定用编码，只能用浏览器默认编码，因此必须转为GB2312输出 
			//防止某种函数未开通,所以用2种
/*
			不能转,压缩包经常打不开,如还不行修改excel_export\card_file.php,不要有中文
			$sfr2=mb_convert_encoding($name[1], 'GBK','GBK,GB2312,UTF-8');
			$sfr2=iconv('UTF-8', 'GB2312',$sfr2);
			$sfr=$sfr2.".".$sfr;	
*/
			$sfr=$name[1].'.'.$sfr;	
			
			
			$zip->addFile ($name[0],$sfr); // 第二个参数是放在压缩包中的文件名称，如果文件可能会有重复，就需要注意一下
		}
	}
	$zip->close (); // 关闭
	
	//下面是输出下载;
	@header ( "Cache-Control: max-age=0" );
	@header ( "Content-Description: File Transfer" );
	@header ( 'Content-disposition: attachment; filename=' . basename ( $filename ) ); // 文件名
	@header ( "Content-Type: application/zip" ); // zip格式的
	@header ( "Content-Transfer-Encoding: binary" ); // 告诉浏览器，这是二进制文件
	@header ( 'Content-Length: ' . filesize ( $filename ) ); // 告诉浏览器，文件大小
	@readfile ( $filename );//输出文件;
}//if($fileNameArr){
?>