<?php
//自动登录
if((!$_SESSION['member']['userid']||!$_COOKIE['member_cookie'])&&$ON_MemberAutoLogin)
{
	$ALVal=$_COOKIE["MemberAutoLogin"];
	if($ALVal)
	{
		if(!is_array($ALVal)&&$ALVal){$ALVal=explode(':::',$ALVal);}
		$ALuserid=$ALVal[0];$ALpassword=$ALVal[1];
		
		$fr=FeData('member','*',"userid='{$ALuserid}' and password='{$ALpassword}'");//必须用$fr
		if($fr['userid']){
			MemberLoginSuccess(23);//登录成功
		}else{
			@setcookie('MemberAutoLogin','',time()+60*60*24*7,"/");//登录失败时清空值防止每次都查询
		}
	}
}

//通用赋值
$Mgroupid=$_SESSION['member']['groupid'];
$Muserid=$_SESSION['member']['userid'];
$Museric=strtoupper($_SESSION['member']['useric']);//将字符串转换为大写形式
$Musername=$_SESSION['member']['username'];
$Mtruename=$_SESSION['member']['truename'];
$Menname=$_SESSION['member']['enname'];
$Mrnd=$_SESSION['member']['rnd'];
$Mcurrency=$_SESSION['member']['currency'];//币种(后台更换币种时,会强行退出重新登录)
$Mcertification=$_SESSION['member']['certification'];//实名认证状态
//$Mlanguage=$_SESSION['language'];//语种文件 

//$member_per[$Mgroupid]['groupname'];//获取组资料


$Mmy=" and userid='{$Muserid}' ";//固定查询

//验证登录/权限
/*
$noper
$pervar
*/
if(!$noper)
{
	//关闭会员中心
	if($off_site_member)
	{
		echo '<script language=javascript>';
		echo 'location.href="/xamember/login_save.php?lx=logout";';
		echo '</script>';
		exit();
	}
	
	//权限验证和是否登录
	if($pervar)
	{
		permissions($pervar,1,'member','');//验证权限和是否登录
	}else{
		permissions(0,1,'member','2');//默认只验证是否登录
	}
	

	//未通过认证禁用的系统
	if($off_certification&&!$Mcertification)
	{
		if($certification_baoguo&&Act('/baoguo/','cf')){exit("<script>alert('{$LG['incluce.session_1']}');location='/xamember/main.php';</script>");}
		elseif($certification_yundan&&Act('/yundan/','cf')){exit("<script>alert('{$LG['incluce.session_2']}');location='/xamember/main.php';</script>");}
		elseif($certification_mall_order&&Act('/mall_order/','cf')){exit("<script>alert('{$LG['incluce.session_3']}');location='/xamember/main.php';</script>");}
		elseif($certification_daigou&&Act('/daigou/','cf')){exit("<script>alert('{$LG['incluce.session_4']}');location='/xamember/main.php';</script>");}
		elseif($certification_qujian&&Act('/qujian/','cf')){exit("<script>alert('{$LG['incluce.session_5']}');location='/xamember/main.php';</script>");}
	}
	
	//下单时,视生成的单号要求 验证会员资料是否符合
	/*
		==9 生成格式需要有会员英文名
	*/
	if($ydh_typ==9&&!$Menname&&Act('/yundan/form.php','cf')){exit("<script>alert('{$LG['incluce.session_1_1']}');location='/xamember/main.php';</script>");}
	elseif($dg_typ==9&&!$Menname&&Act('/daigou/form.php','cf')){exit("<script>alert('{$LG['incluce.session_1_1']}');location='/xamember/main.php';</script>");}
	
	
	//防止以上函数执行错误而无提示，最后做验证
	if(!$Muserid){exit('登录超时！');}
}
?>