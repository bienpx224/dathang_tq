<?php 
//有中文时导出文件名会有乱码

//代替证件
if(stristr(cadd($cd['s_shenfenimg_z']),'/upxingao/'))
{
	$fileNameArr.=','.$_SERVER['DOCUMENT_ROOT'].cadd($cd['s_shenfenimg_z']).'|||'.cadd($cd['s_shenfenhaoma']).'_'.cadd($rs['ydh']).'_Instead1';
	
	if(stristr(cadd($cd['s_shenfenimg_b']),'/upxingao/'))
	{
		$fileNameArr.=','.$_SERVER['DOCUMENT_ROOT'].cadd($cd['s_shenfenimg_b']).'|||'.cadd($cd['s_shenfenhaoma']).'_'.cadd($rs['ydh']).'_Instead2';
	}
}



//原证件
if(stristr(cadd($rs['s_shenfenimg_z']),'/upxingao/'))
{
	$fileNameArr.=','.$_SERVER['DOCUMENT_ROOT'].cadd($rs['s_shenfenimg_z']).'|||'.cadd($rs['s_shenfenhaoma']).'_'.cadd($rs['ydh']).'_1';
	
	if(stristr(cadd($rs['s_shenfenimg_b']),'/upxingao/'))
	{
		$fileNameArr.=','.$_SERVER['DOCUMENT_ROOT'].cadd($rs['s_shenfenimg_b']).'|||'.cadd($rs['s_shenfenhaoma']).'_'.cadd($rs['ydh']).'_2';
	}
}





//有中文时导出文件名会有乱码,有中文的旧方式:
/*

//代替证件-文件
if(stristr(cadd($cd['s_shenfenimg_z']),'/upxingao/'))
{
	$fileNameArr.=','.$_SERVER['DOCUMENT_ROOT'].cadd($cd['s_shenfenimg_z']).'|||'.cadd($cd['s_name']).cadd($rs['ydh']).'_cardInstead1';
	
	if(stristr(cadd($cd['s_shenfenimg_b']),'/upxingao/'))
	{
		$fileNameArr.=','.$_SERVER['DOCUMENT_ROOT'].cadd($cd['s_shenfenimg_b']).'|||'.cadd($cd['s_name']).cadd($rs['ydh']).'_cardInstead2';
	}
}



//原证件
if(stristr(cadd($rs['s_shenfenimg_z']),'/upxingao/'))
{
	$fileNameArr.=','.$_SERVER['DOCUMENT_ROOT'].cadd($rs['s_shenfenimg_z']).'|||'.cadd($rs['s_name']).cadd($rs['ydh']).'_card1';
	
	if(stristr(cadd($rs['s_shenfenimg_b']),'/upxingao/'))
	{
		$fileNameArr.=','.$_SERVER['DOCUMENT_ROOT'].cadd($rs['s_shenfenimg_b']).'|||'.cadd($rs['s_name']).cadd($rs['ydh']).'_card2';
	}
}
*/

//代替证件-文件
?>