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
$bgid=par(ToStr($_REQUEST['bgid']));
$tokenkey=par($_POST['tokenkey']);

if(!$bgid){exit ("<script>alert('bgid{$LG['pptError']}');goBack();</script>");}

//修改=====================================================
if($lx=='edit')
{
	//验证
	$fe=FeData('baoguo','status,addSource',"bgid=$bgid");
	if($fe['status']>1)
	{
		exit ("<script>alert('{$LG['baoguo.edit_form_2']}');goBack();</script>");
	}
	
	if($fe['addSource']==3)
	{
		exit ("<script>alert('{$LG['baoguo.edit_form_3']}');goBack();</script>");
	}

	$bgydh=par($_POST['bgydh']);
	$old_bgydh=par($_POST['old_bgydh']);
	
	//验证------------------------------------
	$token=new Form_token_Core();
	$token->is_token("baoguo".$bgid,$tokenkey); //验证令牌密钥

	if(!$_POST['bgydh']){exit ("<script>alert('{$LG['baoguo.edit_save_1']}');goBack();</script>");}
	if(!$_POST['warehouse']){exit ("<script>alert('{$LG['baoguo.edit_save_2']}');goBack();</script>");}
	
	//验证运单号
	if($old_bgydh!=$bgydh)
	{
		$num=mysqli_num_rows($xingao->query("select bgydh from baoguo where bgydh='{$bgydh}' "));
		if($num){exit ("<script>alert('{$LG['baoguo.edit_save_3']}');goBack();</script>");}
	}
		
	//更新------------------------------------
	$savelx='edit';//调用类型(add,edit,cache)
	$getlx='POST';//获取类型(POST,GET,REQUEST,SQL)
	$alone='bgid,old_bgydh';//不处理的字段
	$digital='';//数字字段
	$radio='';//单选、复选、空文本、数组字段
	$textarea='content';//过滤不安全的HTML代码
	$date='fahuotime';//日期格式转数字
	$save=XingAoSave($_POST,$getlx,$savelx,$alone,$digital,$radio,$textarea,$html,$date);
	$save.=",edittime=".time();
	
	$xingao->query("update baoguo set ".$save." where bgid='{$bgid}' {$Mmy}");SQLError('修改');		
	$rc=mysqli_affected_rows($xingao);
	wupin_save('baoguo',$bgid);
	
	if($rc>0)
	{
		$ts=$LG['pptEditSucceed'];
	}else{
		$ts=$LG['pptEditNo'];
	}
	$token->drop_token("baoguo".$bgid); //处理完后删除密钥
	exit("<script>location='list.php?status=kuwai';</script>");//alert('".$ts."');
	
}
?>