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

//获取,处理=====================================================
$lx=par($_REQUEST['lx']);
$cpid=par(ToStr($_REQUEST['cpid']));

$status=par($_REQUEST['status']);
$codes=par($_POST['codes']);//兑换码
$code=strtolower(par($_POST['code']));//验证码
$tokenkey=par($_POST['tokenkey']);
$prevurl = isset($_SERVER['HTTP_REFERER']) ? trim($_SERVER['HTTP_REFERER']) : 'list.php';

if($lx!='add'&&$lx!='del'&&!$cpid){exit ("<script>alert('{$LG['coupons.save_1']}');goBack();</script>");}

//添加,没有修改=====================================================
if($lx=='add')
{
	//通用验证------------------------------------
	$token=new Form_token_Core();
	$token->is_token("coupons",$tokenkey); //验证令牌密钥

	if(strlen($codes)<6||strlen($codes)>30){exit ( "<script>alert('{$LG['coupons.save_3']}');goBack();</script>");}
	
	if(!$code){exit ( "<script>alert('{$LG['codeEmpty']}');goBack();</script>");}
	$vname=xaReturnKeyVarname('cp');$code_se=$_SESSION[$vname];
	if($code!=$_SESSION[$vname]){unset($_SESSION[$vname]);exit ( "<script>alert('{$LG['coupons.save_4']}');goBack();</script>");}
	unset($_SESSION[$vname]);
	
	//获取
	$rs=FeData('coupons','*',"codes='{$codes}'");
	if(!$rs['cpid']){exit ( "<script>alert('{$LG['coupons.save_5']}');goBack();</script>");}
	if($rs['userid']){exit ( "<script>alert('{$LG['coupons.save_6']}');goBack();</script>");}
	if(spr($rs['status'])){exit ( "<script>alert('{$LG['coupons.save_7']}');goBack();</script>");}
	if($rs['duetime']&&$rs['duetime']<time()){exit ( "<script>alert('{$LG['coupons.save_8']}');goBack();</script>");}
	if(!$rs['number']){exit ( "<script>alert('{$LG['coupons.save_9']}');goBack();</script>");}
	
	$xingao->query("update coupons set userid='{$Muserid}',username='{$Musername}',getSource='1',getTime='".time()."' where cpid='{$rs[cpid]}' ");
	SQLError();
	$rc=mysqli_affected_rows($xingao);
	
	if($rc>0){
		exit("<script>alert('".LGtag($LG['coupons.save_10'],'<tag1>=='.$rs['number'] ).Coupons_Types($rs['types'])."！');location='list.php?status=0';</script>");
	}else{
		exit ("<script>alert('{$LG['coupons.save_11']}');goBack();</script>");
	}

	
//删除=====================================================
}elseif($lx=='del'){
	if($cpid){
		$where=" and cpid in ({$cpid})";
	}else{
		$date=par($_POST['date']);
		if(!CheckEmpty($date)){exit ("<script>alert('{$LG['coupons.save_12']}');goBack();</script>");}
		$start =strtotime('-'.$date.' Month');
		$where.=" and getTime<".$start;
		
		if(CheckEmpty($status)){$where.=" and status>0 and status=".$status;}
	}
	
	$xingao->query("delete from coupons where status<>1 {$where} {$Mmy}");
	$rc=mysqli_affected_rows($xingao);
	if($rc>0){
		exit("<script>alert('{$LG['pptDelSucceed']}{$rc}');location='{$prevurl}';</script>");
	}else{
		exit ("<script>alert('{$LG['coupons.save_13']}');goBack();</script>");
	}
}

if(mysqli_affected_rows($xingao)<=0){
	exit ("<script>alert('{$LG['pptEditEmpty']}');goBack();</script>");
}else{
	exit("<script>location='{$prevurl}';</script>");
}
?>