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
$pervar='off_qujian';//权限验证
require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/session.php');


//获取,处理=====================================================
$lx=par($_REQUEST['lx']);
$qjid=par(ToStr($_REQUEST['qjid']));
$tokenkey=par($_POST['tokenkey']);
//添加,修改=====================================================
if($lx=='add'||$lx=='edit')
{
	//通用验证------------------------------------
	$token=new Form_token_Core();
	$token->is_token("qujian_add",$tokenkey); //验证令牌密钥
	
	if($_POST['qjdate']<=date('Y-m-d')){exit ("<script>alert('取件时间要选择明天或以上！');goBack();</script>");}

	if(!$_POST['qjdate']||!$_POST['truename']||!$_POST['mobile']||!$_POST['weight']||!$_POST['address']){exit ("<script>alert('{$LG['qujian.save_1']}');goBack();</script>");}
	

	//添加------------------------------------
	if($lx=='add')
	{
		$addtime=time();
		$savelx='add';//调用类型(add,edit,cache)
		$getlx='POST';//获取类型(POST,GET,REQUEST,SQL)
		$alone='qjid';//不处理的字段
		$digital='status';//数字字段
		$radio='';//单选、复选、空文本、数组字段
		$textarea='content';//过滤不安全的HTML代码
		$date='qjdate';//日期格式转数字
		$save=XingAoSave($_POST,$getlx,$savelx,$alone,$digital,$radio,$textarea,$html,$date);
		
		$xingao->query("insert into qujian (".$save['field'].",addtime,userid,username) values(".$save['value'].",'{$addtime}','{$Muserid}','{$Musername}')");
		SQLError('添加');
		$rc=mysqli_affected_rows($xingao);
		if($rc>0)
		{
			$token->drop_token("qujian_add"); //处理完后删除密钥
			exit("<script>alert('{$LG['pptAddSucceed']}');location='list.php';</script>");
		}else{
			exit ("<script>alert('{$LG['pptAddFailure']}');goBack();</script>");
		}
	}
	
	//修改------------------------------------
	if($lx=='edit')
	{
		if(!$qjid){exit ("<script>alert('qjid{$LG['pptError']}');goBack();</script>");}
		
		//更新
		$savelx='edit';//调用类型(add,edit,cache)
		$getlx='POST';//获取类型(POST,GET,REQUEST,SQL)
		$alone='qjid';//不处理的字段
		$digital='';//数字字段
		$radio='';//单选、复选、空文本、数组字段
		$textarea='content';//过滤不安全的HTML代码
		$date='qjdate';//日期格式转数字
		$save=XingAoSave($_POST,$getlx,$savelx,$alone,$digital,$radio,$textarea,$html,$date);
		$xingao->query("update qujian set ".$save." where qjid='{$qjid}' {$Mmy}");
		SQLError();		
		$rc=mysqli_affected_rows($xingao);

		$token->drop_token("qujian_add"); //处理完后删除密钥
		if($rc>0)
		{
			$ts=$LG['pptEditSucceed'];
		}else{
			$ts=$LG['pptEditNo'];
		}
		exit("<script>alert('".$ts."');location='list.php';</script>");
	}
	
	
	
//删除=====================================================
}elseif($lx=='del'){
	
	if(!$qjid){exit ("<script>alert('qjid{$LG['pptError']}');goBack();</script>");}
	
	$xingao->query("delete from qujian where qjid in ({$qjid}) and status<>'1' {$Mmy}");
	$rc=mysqli_affected_rows($xingao);
	
	if($rc>0){
		exit("<script>alert('{$LG['pptDelSucceed']}{$rc}');location='list.php';</script>");
	}else{
		exit("<script>alert('{$LG['pptDelEmpty']}');location='list.php';</script>");
	}
	
}
?>