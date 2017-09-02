<?php
	if (!HaveFile($file)){exit("<script>alert('{$LG['PHPExcel.2']}');goBack('c');</script>");}
	
	$ftype=GetFiletype($file);
	if ($ftype!='.xls'&&$ftype!='.xlsx'){DelFile($file);exit ("<script>alert('{$LG['PHPExcel.3']}');goBack('c');</script>");}	

	require_once($_SERVER['DOCUMENT_ROOT'].'/public/PHPExcel/PHPExcel.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/public/PHPExcel/PHPExcel/IOFactory.php');
	echo '<script src="/public/PHPExcel/PHPExcel/CachedObjectStorage/SQLiti.php" type="text/javascript"></script>';

	if ($ftype=='.xls')
	{
	  require_once ($_SERVER['DOCUMENT_ROOT'].'/public/PHPExcel/PHPExcel/Reader/Excel5.php');
	  $objReader = PHPExcel_IOFactory::createReader('Excel5');//use excel2007 for 2007 format 
	}
	elseif($ftype=='.xlsx')
	{
	  require_once ($_SERVER['DOCUMENT_ROOT'].'/public/PHPExcel/PHPExcel/Reader/Excel2007.php');
	  $objReader = PHPExcel_IOFactory::createReader('Excel2007');//use excel2007 for 2007 format 
	}
 
	$objPHPExcel = $objReader->load($_SERVER['DOCUMENT_ROOT'].$file); 
	$sheet = $objPHPExcel->getSheet(0); 
	$highestRow = $sheet->getHighestRow();           //取得总行数 
	$highestColumn = $sheet->getHighestColumn(); //取得总列数
	
	$objWorksheet = $objPHPExcel->getActiveSheet();
	$highestRow = $objWorksheet->getHighestRow(); 
	$excel_zc=$highestRow-1;
	$highestColumn = $objWorksheet->getHighestColumn();
	$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);//总列数
?>
