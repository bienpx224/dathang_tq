<?php 
require_once($_SERVER["DOCUMENT_ROOT"]."/public/PHPExcel/PHPExcel.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/public/PHPExcel/PHPExcel/Writer/Excel5.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/public/PHPExcel/PHPExcel/Writer/Excel2007.php");

DelAllFile($path);//删除该目录所有文件
DoMkdir($path);//重新生成目录
if(!$excelname)
{
	$xaname=date('Y-m-d H-i-s').'.xls';//文件名,外部用到$xaname
	$excelname=$_SERVER['DOCUMENT_ROOT'].$path.$xaname;//目录加文件名
}
$excel = new ChangeArrayToExcel($excelname);




/*
根据给定的数字生成至多两位对应EXCEL文件列的字母
*/
function numToEn($pColumnIndex = 0)
{
	static $_indexCache = array();

	if (!isset($_indexCache[$pColumnIndex])) {
		if ($pColumnIndex < 26) {
			$_indexCache[$pColumnIndex] = chr(65 + $pColumnIndex);
		} elseif ($pColumnIndex < 702) {
			$_indexCache[$pColumnIndex] = chr(64 + ($pColumnIndex / 26)) .
										  chr(65 + $pColumnIndex % 26);
		} else {
			$_indexCache[$pColumnIndex] = chr(64 + (($pColumnIndex - 26) / 676)) .
										  chr(65 + ((($pColumnIndex - 26) % 676) / 26)) .
										  chr(65 + $pColumnIndex % 26);
		}
	}
	return $_indexCache[$pColumnIndex];
}
?>