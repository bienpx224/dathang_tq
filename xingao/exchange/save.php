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
$pervar='manage_ex';require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');

//获取,处理=====================================================
$lx=par($_POST['lx']);
$exid=$_POST['exid'];
$exchange=$_POST['exchange'];
//$autoGet=$_POST['autoGet'];//复选框 不可用数组功能
$autoScopeStart=$_POST['autoScopeStart'];
$autoScopeEed=$_POST['autoScopeEed'];
$autoInRe=$_POST['autoInRe'];
if($lx=='edit')
{
	$arr=ToArr($exid,',');
	if($arr)
	{
		foreach($arr as $arrkey=>$value)
		{
			
			$autoGet=spr($_POST['autoGet'.$exid[$arrkey]]);
			if((!$ON_exchange||!$autoGet)&&$exchange[$arrkey]<=0){exit ("<script>alert('ID:{$exid[$arrkey]} 请填写汇率!');goBack();</script>");}

			$xingao->query("update exchange set 
			exchange='".spr($exchange[$arrkey],5)."' ,
			autoGet='{$autoGet}' ,
			autoScopeStart='".spr($autoScopeStart[$arrkey],5)."' ,
			autoScopeEed='".spr($autoScopeEed[$arrkey],5)."'  ,
			autoInRe='".spr($autoInRe[$arrkey],5)."' 
			where exid='{$exid[$arrkey]}'");
			SQLError('更新资料');
			
			if(mysqli_affected_rows($xingao)>0)
			{
				$xingao->query("update exchange set edittime='".time()."' where exid='{$exid[$arrkey]}'");
				SQLError('更新时间');
			}
		}
	}
	
	exit("<script>location='list.php';</script>");
}
?>