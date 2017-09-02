<?php 
require_once($_SERVER['DOCUMENT_ROOT'].'/public/config_top.php');//需要放外面,否则_SESSION无效

ob_start();//开始缓冲------------------------------------------------
	//以下内容必须放在缓冲里面,才能获取全部内容来生成静态页
	require_once($_SERVER['DOCUMENT_ROOT'].'/public/html.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/public/function.php');

	if(!$_SESSION['manage']['userid']){exit('非法注入');}

	$htmlAdd=urldecode($_GET['htmlAdd']);//在放第一位
	$_GET=GetUrlPar(urldecode($_GET['fileAdd']));//$_GET 传给require内部文件
	//echo $htmlAdd;exit;
	require($_SERVER['DOCUMENT_ROOT'].$_GET['fileAdd']);//该文件内不能有转向,exit,等影响到外部的代码
	file_put_contents($htmlAdd,ob_get_contents());
	
ob_end_clean();//结束缓存：清除并关闭缓冲区--------------------------
?>