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
//require_once($_SERVER['DOCUMENT_ROOT'].'/public/html.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/public/function.php');

$lx=par($_POST['lx']);
$id=$_POST['id'];
$id_name=par($_POST['id_name']);
$page=$_POST['page'];if(!$page){$page=0;}

$se=$id_name.'_id_'.$page;

if($lx=='bc')
{
	//保存单页
	$_SESSION[$se]=$id;	//if($id){}//不能加这个，否则无法删除全部ID

	
	//保存全部
	for ($i=0; $i<=200; $i++) //200页
	{
		  $se=$id_name.'_id_'.$i;
		  if($_SESSION[$se])
		  {
			  if($id_all)
			  {
				 $id_all=$id_all.','.$_SESSION[$se];
			  }else{
				 $id_all=$_SESSION[$se];
			  }
		  }
	}
	$_SESSION[$id_name]=par($id_all);
	echo arrcount($id_all);
	
}elseif(par($_GET['lx'])=='sc'){
	
	$id_name=par($_GET['id_name']);
	
	//清除全部
	for ($i=0; $i<=200; $i++) //200页
	{
	  $se=$id_name.'_id_'.$i;
	  $_SESSION[$se]='';
	}
	
	//清除
	$_SESSION[$id_name]='';
	$prevurl = isset($_SERVER['HTTP_REFERER']) ? trim($_SERVER['HTTP_REFERER']) : '';
	exit("<script>location='".$prevurl."';</script>");

}
?>