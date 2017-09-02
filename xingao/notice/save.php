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
$pervar='notice';//权限验证
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');

$Xgr=" and groupid='{$Xgroupid}' ";


//获取,处理=====================================================
$lx=par($_REQUEST['lx']);
$noid=par(ToStr($_REQUEST['noid']));
$tokenkey=par($_POST['tokenkey']);
$status=par($_POST['status']);
$prevurl = isset($_SERVER['HTTP_REFERER']) ? trim($_SERVER['HTTP_REFERER']) : 'list.php';

if($lx!='add'&&$lx!='del'&&!$noid){exit ("<script>alert('请选择要修改的信息！');goBack();</script>");}

//添加,修改=====================================================
if($lx=='add'||$lx=='edit')
{
	//通用验证------------------------------------
	$token=new Form_token_Core();
	$token->is_token("notice",$tokenkey); //验证令牌密钥
	
	$_POST['duetime']=ToStrtotime($_POST['duetime1'],$_POST['duetime2']);
	if($_POST['popup']&&$_POST['popuptime']<30){exit ("<script>alert('弹出相隔时间不能小于30分钟！');goBack();</script>");}
	
	//添加------------------------------------
	if($lx=='add')
	{
		$_POST['groupid']=$Xgroupid;
		$_POST['userid']=$Xuserid;
		$_POST['username']=$Xusername;
		
		$_POST['edittime']=time();
		$_POST['addtime']=time();
		
		
		$savelx='add';//调用类型(add,edit,cache)
		$getlx='POST';//获取类型(POST,GET,REQUEST,SQL)
		$alone='noid,duetime1,duetime2';//不处理的字段
		$digital='to_groupid,status,checked,duetime,level,popup,popuptime';//数字字段
		$radio='';//单选、复选、空文本、数组字段
		$textarea='content';//过滤不安全的HTML代码
		$date='';//日期格式转数字
		$save=XingAoSave($_POST,$getlx,$savelx,$alone,$digital,$radio,$textarea,$html,$date);
		
		$xingao->query("insert into notice (".$save['field'].") values(".$save['value'].")");
		SQLError('添加');
		$token->drop_token("notice"); //处理完后删除密钥
		exit("<script>alert('{$LG['pptAddSucceed']}');location='form.php';</script>");
	}
	
	//修改------------------------------------
	if($lx=='edit')
	{
		$_POST['edittime']=time();
		
		$savelx='edit';//调用类型(add,edit,cache)
		$getlx='POST';//获取类型(POST,GET,REQUEST,SQL)
		$alone='noid,duetime1,duetime2';//不处理的字段
		$digital='to_groupid,status,checked,duetime,level,popup,popuptime';//数字字段
		$radio='';//单选、复选、空文本、数组字段
		$textarea='content';//过滤不安全的HTML代码
		$date='';//日期格式转数字

		$save=XingAoSave($_POST,$getlx,$savelx,$alone,$digital,$radio,$textarea,$html,$date);
		$xingao->query("update notice set ".$save." where noid='{$noid}' {$Xgr}");
		SQLError('修改');		
		$token->drop_token("notice"); //处理完后删除密钥
		
		exit("<script>location='list.php';</script>");
	}

//移动=====================================================
}elseif($lx=='mobile'){
	$groupid_new=par($_POST['groupid_new']);
	if(!$groupid_new){exit ("<script>alert('请选择新所属！');goBack();</script>");}

	$xingao->query("update notice set to_groupid='{$groupid_new}' where noid in ({$noid}) {$Xgr}");
	SQLError('转移');
	
//修改状态=====================================================
}elseif($lx=='status'){
	if(!CheckEmpty($_POST['status_new'])){exit ("<script>alert('请选择要修改的状态！');goBack();</script>");}
	$status_new=par($_POST['status_new']);

	$xingao->query("update notice set status='{$status_new}' where noid in ({$noid}) {$Xgr}");
	SQLError('状态');
	
//过期设置=====================================================
}elseif($lx=='checked'){
	$xingao->query("update notice set checked='{$checked}' where noid in ({$noid}) {$Xgr}");
	SQLError('过期设置');
	

//删除=====================================================
}elseif($lx=='del'){
	if($noid){$where="noid in ({$noid})";}else{$where="checked='0'";}
	$xingao->query("delete from notice  where {$where} {$Xgr}");
}

if(mysqli_affected_rows($xingao)<=0){
	exit ("<script>alert('修改或删除失败：\\n可能没有可修改的信息！\\n可能您与发布者不是同一个分组里,无权修改！');goBack();</script>");
}else{
	exit("<script>location='{$prevurl}';</script>");
}
?>