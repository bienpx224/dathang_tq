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
$tokenkey=par($_POST['tokenkey']);

$truename=par($_POST['truename']);
$mobile_code=par($_POST['mobile_code']);
$mobile=par($_POST['mobile']);
$addid=par(ToStr($_REQUEST['addid']));

//添加,修改=====================================================
if($lx=='add'||$lx=='edit')
{
	//通用验证------------------------------------
	$token=new Form_token_Core();
	$token->is_token("address_add",$tokenkey); //验证令牌密钥
	
	if(!$truename||!$mobile_code||!$mobile){exit ("<script>alert('{$LG['address.pptSave1']}');goBack();</script>");}//请填写/选择姓名、手机地区、手机号码！
	    
	if(!$_POST['add_shengfen']||!$_POST['add_chengshi']||!$_POST['add_quzhen']||!$_POST['add_dizhi']){exit ("<script>alert('{$LG['address.pptSave2']}');goBack();</script>");}//请填写完整地址资料！
	
	//验证地址资料
	$Receive=CheckReceive('address');
	if($Receive){exit ("<script>alert('{$Receive}');goBack();</script>");}
	
	//添加------------------------------------
	if($lx=='add')
	{
		$_POST['addtime']=time();
		$_POST['edittime']=time();

		$savelx='add';//调用类型(add,edit,cache)
		$getlx='POST';//获取类型(POST,GET,REQUEST,SQL)
		$alone='addid,old_shenfenimg_z,old_shenfenimg_b';//不处理的字段
		$digital='addclass,checked,mrf,mrs';//数字字段
		$radio='';//单选、复选、空文本、数组字段
		$textarea='content';//过滤不安全的HTML代码
		$date='';//日期格式转数字
		$save=XingAoSave($_POST,$getlx,$savelx,$alone,$digital,$radio,$textarea,$html,$date);
		
		$xingao->query("insert into member_address (".$save['field'].",userid,username) values(".$save['value'].",'{$Muserid}','{$Musername}')");
		SQLError('添加');
		$rc=mysqli_affected_rows($xingao);
		if($rc>0)
		{
			$token->drop_token("address_add"); //处理完后删除密钥
			exit("<script>alert('{$LG['pptAddSucceed']}');location='list.php';</script>");
		}else{
			exit ("<script>alert('{$LG['pptAddFailure']}');goBack();</script>");
		}
	}
	
	//修改------------------------------------
	if($lx=='edit')
	{
		if(!$addid){exit ("<script>alert('addid{$LG['pptError']}');goBack();</script>");}
		
		//有单个文件字段时需要处理(要放在XingAoSave前面)
		DelFile($onefilefield='shenfenimg_z','edit');
		DelFile($onefilefield='shenfenimg_b','edit');
		
		$_POST['edittime']=time();

		//更新
		$savelx='edit';//调用类型(add,edit,cache)
		$getlx='POST';//获取类型(POST,GET,REQUEST,SQL)
		$alone='addid,old_shenfenimg_z,old_shenfenimg_b';//不处理的字段
		$digital='addclass,checked';//数字字段
		$radio='';//单选、复选、空文本、数组字段
		$textarea='content';//过滤不安全的HTML代码
		$date='';//日期格式转数字
		$save=XingAoSave($_POST,$getlx,$savelx,$alone,$digital,$radio,$textarea,$html,$date);
		$xingao->query("update member_address set ".$save." where addid='{$addid}' {$Mmy}");
		SQLError();		
		$rc=mysqli_affected_rows($xingao);

		$token->drop_token("address_add"); //处理完后删除密钥
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
	
	if(!$addid){exit ("<script>alert('addid{$LG['pptError']}');goBack();</script>");}
	
	//删除文件
	$query="select shenfenimg_z,shenfenimg_b from member_address where addid in ({$addid}) and (shenfenimg_z<>'' or shenfenimg_b<>'')  {$Mmy}";
	$sql=$xingao->query($query);
	while($rs=$sql->fetch_array())
	{
		DelFile($rs['shenfenimg_z']);
		DelFile($rs['shenfenimg_b']);
	}
	
	$xingao->query("delete from member_address where addid in ({$addid})  {$Mmy}");
	$rc=mysqli_affected_rows($xingao);
	
	if($rc>0){
		exit("<script>alert('{$LG['pptDelSucceed']}{$rc}');location='list.php';</script>");
	}else{
		exit("<script>alert('{$LG['pptDelEmpty']}');location='list.php';</script>");
	}
	
}
//修改默认发货,收货地址=====================================================
elseif($lx=='mr'){
	if(!$addid){exit ("<script>alert('addid{$LG['pptError']}');goBack();</script>");}
	$mrf=(int)$_GET["mrf"];
	$mrs=(int)$_GET["mrs"];

	if($mrf==1)
	{
		$xingao->query("update member_address set mrf='0' where mrf='{$mrf}'{$Mmy}");SQLError();
		$xingao->query("update member_address set mrf='{$mrf}' where addid={$addid} {$Mmy}");SQLError();
	}
	elseif($mrf==2)
	{
		$xingao->query("update member_address set mrf='0' where addid={$addid} {$Mmy}");SQLError();
	}
	elseif($mrs==1)
	{
		$xingao->query("update member_address set mrs='0' where mrs='{$mrs}'{$Mmy}");SQLError();
		$xingao->query("update member_address set mrs='{$mrs}' where addid={$addid} {$Mmy}");SQLError();
	}
	elseif($mrs==2)
	{
		$xingao->query("update member_address set mrs='0' where addid={$addid} {$Mmy}");SQLError();
	}
	
	exit("<script>location='list.php';</script>");
}
//修改属性=====================================================
elseif($lx=='addclass'){
	$addclass=(int)$_POST["addclass"];
	
	if(!CheckEmpty($addclass)){exit ("<script>alert('{$LG['address.pptSave3']}');goBack();</script>");}//请选择类型！
	//更新主信息状态
	$xingao->query("update member_address set addclass='{$addclass}' where addid in ({$addid}) {$Mmy}");
	SQLError();
	$rc=mysqli_affected_rows($xingao);
	if($rc>0){
		exit("<script>location='list.php';</script>");
	}else{
		exit("<script>alert('{$LG['pptEditEmpty']}');goBack();</script>");
	}
}

?>