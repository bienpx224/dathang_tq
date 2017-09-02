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
$txzhid=$_REQUEST['txzhid'];
$tokenkey=par($_POST['tokenkey']);
$tixianpassword=par($_POST['tixianpassword']);

if (is_array($txzhid)){$txzhid=implode(',',$txzhid);}
$txzhid=par($txzhid);

//添加,修改=====================================================
if($lx=='add'||$lx=='edit')
{
	//通用验证------------------------------------
	$token=new Form_token_Core();
	$token->is_token("tixian_zh",$tokenkey); //验证令牌密钥
	
	if(!$_POST['bank']||!$_POST['name']||!$_POST['account']){exit ("<script>alert('{$LG['tixian_zh.save_1']}');goBack();</script>");}
	
	//查询验证
	$fr=mysqli_fetch_array($xingao->query("select txrnd,tixianpassword from member where 1=1 {$Mmy}"));
	$tixianpassword=md5($fr['txrnd'].md5($tixianpassword));
	if($fr['tixianpassword']!=$tixianpassword){exit( "<script>alert('{$LG['tixian_zh.save_2']}');goBack();</script>");}

	
	//添加------------------------------------
	if($lx=='add')
	{
		$addtime=time();

		$savelx='add';//调用类型(add,edit,cache)
		$getlx='POST';//获取类型(POST,GET,REQUEST,SQL)
		$alone='txzhid,tixianpassword';//不处理的字段
		$digital='checked';//数字字段
		$radio='';//单选、复选、空文本、数组字段
		$textarea='content';//过滤不安全的HTML代码
		$date='';//日期格式转数字
		$save=XingAoSave($_POST,$getlx,$savelx,$alone,$digital,$radio,$textarea,$html,$date);
		
		$xingao->query("insert into tixian_zh (".$save['field'].",addtime,userid,username) values(".$save['value'].",'{$addtime}','{$Muserid}','{$Musername}')");
		SQLError('添加');
		$rc=mysqli_affected_rows($xingao);
		if($rc>0)
		{
			$token->drop_token("tixian_zh"); //处理完后删除密钥
			exit("<script>alert('{$LG['pptAddSucceed']}');location='list.php';</script>");
		}else{
			exit ("<script>alert('{$LG['pptAddFailure']}');goBack();</script>");
		}
	}
	
	//修改------------------------------------
	if($lx=='edit')
	{
		if(!$txzhid){exit ("<script>alert('txzhid{$LG['pptError']}');goBack();</script>");}
		
		//更新
		$savelx='edit';//调用类型(add,edit,cache)
		$getlx='POST';//获取类型(POST,GET,REQUEST,SQL)
		$alone='txzhid,tixianpassword';//不处理的字段
		$digital='checked';//数字字段
		$radio='';//单选、复选、空文本、数组字段
		$textarea='content';//过滤不安全的HTML代码
		$date='';//日期格式转数字
		$save=XingAoSave($_POST,$getlx,$savelx,$alone,$digital,$radio,$textarea,$html,$date);
		$xingao->query("update tixian_zh set ".$save." where txzhid='{$txzhid}' {$Mmy}");
		SQLError();		
		$rc=mysqli_affected_rows($xingao);

		$token->drop_token("tixian_zh"); //处理完后删除密钥
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
	
	if(!$txzhid){exit ("<script>alert('txzhid{$LG['pptError']}');goBack();</script>");}
	
	$xingao->query("delete from tixian_zh where txzhid in ({$txzhid})  {$Mmy}");
	$rc=mysqli_affected_rows($xingao);
	
	if($rc>0){
		exit("<script>alert('{$LG['pptDelSucceed']}{$rc}');location='list.php';</script>");
	}else{
		exit("<script>alert('{$LG['pptDelEmpty']}');location='list.php';</script>");
	}
	
}

?>