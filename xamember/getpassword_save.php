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

//获取,处理=====================================================
$lx=par($_REQUEST['lx']);
$code=strtolower(par($_POST['code']));
$tokenkey=par($_POST['tokenkey']);
$username=par($_POST['username']);
$getPwdTyp=par($_POST['getPwdTyp']);
$mobile=par($_POST['mobile']);
$email=par($_POST['email']);
$getpassword_yz=par($_POST['getpassword_yz']);
$password=add($_POST['password']);//密码不要用postrep过滤
$password2=add($_POST['password2']);//密码不要用postrep过滤

//已登录
$userid=$_SESSION['member']['userid'];
if($userid)
{
	echo '<script language=javascript>';
	echo 'location.href="main.php";';
	echo '</script>';
	XAtsto('main.php');

}



if($lx=='steps1')//第一步=====================================================
{
	if ($_SESSION['getpassword_time']>strtotime('-1 minutes'))//多久（分）可以发送一次
	{
		$time2=date("Y-m-d H:i:s",strtotime('-1 minutes'));
		$time1=date("Y-m-d H:i:s",$_SESSION['getpassword_time']);
		$time_s=DateDiff($time1,$time2,'s');;
		exit("<script>alert('{$time_s}{$LG['getpassword.timeLimit']}');goBack();</script>");
	}

	//基本验证
	$token=new Form_token_Core();
	$token->is_token("getpassword",$tokenkey); //验证令牌密钥
	
	if(!$username){exit("<script>alert('{$LG['getpassword.usernameEmpty']}');goBack();</script>");}
	if($getPwdTyp!='wx'&&!$mobile&&!$email){exit("<script>alert('{$LG['getpassword.typeEmpty']}');goBack();</script>");}

	if(!$code){exit("<script>alert('{$LG['codeEmpty']}');goBack();</script>");}
	$vname=xaReturnKeyVarname('getpassword');
	if($code!=$_SESSION[$vname]){unset($_SESSION[$vname]);exit("<script>alert('{$LG['codeOverdue']}');goBack();</script>");}
	unset($_SESSION[$vname]);

	//查询数据验证
	$fr=mysqli_fetch_array($xingao->query("select * from member where username='{$username}'"));

	if(!$fr['checked']){exit("<script>alert('{$LG['getpassword.usernameClose']}');goBack();</script>");}
	
	//发送验证码---------------------------------------------------------------------------------------------
	$yz=make_NoAndPa(10);
	
	//获取发送通知内容
	$NoticeTemplate='member_getpwd';
	require($_SERVER['DOCUMENT_ROOT'].'/public/NoticeTemplate.php');
	
	if($off_sms&&$mobile&&$mobile==$fr['mobile'])
	{
		$mobile=SMSApiType($fr['mobile_code'],$fr['mobile']);
		SendSMS($mobile,$content,$xs=0);
		$Send='mobile';
		$ts=$LG['getpassword.getCheckcodeMobile'].'('.$fr['mobile'].')';
	}elseif($email&&$email==$fr['email']){
		SendMail($fr['email'],$title,$content,$file,$xs=0);;
		$Send='email';
		$ts=$LG['getpassword.getCheckcodeEmail'].'('.$fr['email'].')';
	}elseif($ON_WX&&$getPwdTyp=='wx'&&$fr['wx_openid']){
		SendWX($send_WxTemId,$send_WxTemName,$send_content_wx,'',$fr['wx_openid']);
		$Send='wx';
		$ts=$LG['getpassword.getCheckcodeWX'];
	}
	
	if($Send)
	{
		$_SESSION['getpassword_username']=$fr['username'];
		$_SESSION['getpassword_userid']=$fr['userid'];
		$_SESSION['getpassword_yz']=$yz;
		$_SESSION['getpassword_time']=time();
		//处理完后删除密钥
		$token->drop_token("getpassword"); 
		exit("<script>location='getpassword.php?lx=steps2&ts=".$ts."';</script>");
	}elseif($getPwdTyp=='wx'){
		exit("<script>alert('{$LG['getpassword.errorWX']}');goBack();</script>");
	}else{
		exit("<script>alert('{$LG['getpassword.errorMobileOrEmail']}\\n {$LG['getpassword.correctMobile']}:".substr_cut($fr['mobile'])." \\n {$LG['getpassword.correctEmail']}:".substr_cut($fr['email'])."');goBack();</script>");
	}
	
}
//第二步=====================================================
elseif($lx=='steps2'){
	//基本验证
	$token=new Form_token_Core();
	$token->is_token("getpassword",$tokenkey); //验证令牌密钥

	if(!$code){exit("<script>alert('{$LG['codeEmpty']}');goBack();</script>");}
	$vname=xaReturnKeyVarname('getpassword');
	if($code!=$_SESSION[$vname]){unset($_SESSION[$vname]);exit("<script>alert('{$LG['codeOverdue']}');goBack();</script>");}
	unset($_SESSION[$vname]);

	if(!$getpassword_yz){exit("<script>alert('{$LG['getpassword.inCheckcode']}');goBack();</script>");}
	
	if(!$_SESSION['getpassword_userid']){exit("<script>alert('{$LG['getpassword.checkcodeFailure']}');goBack();</script>");}
	
	if($getpassword_yz!=$_SESSION['getpassword_yz'])
	{
		$_SESSION['getpwd_yz_number']+=1;
		if($member_getpw_number<=0){$member_getpw_number=1;}
		if($member_getpw_number>10){$member_getpw_number=10;}
		if($_SESSION['getpwd_yz_number']>=$member_getpw_number)
		{
		  unset($_SESSION['getpassword_username']);
		  unset($_SESSION['getpassword_userid']);
		  unset($_SESSION['getpassword_yz']);
		  unset($_SESSION['getpwd_yz_number']);
		  unset($_SESSION['getpassword_time']);
	      exit("<script>alert('{$LG['getpassword.checkcodeErrorLimit']}');goBack();</script>");
		}
		exit("<script>alert('{$LG['getpassword.checkcodeError']}');goBack();</script>");
	}
	
	if($password==$_SESSION['getpassword_username']){exit ("<script>alert('{$LG['getpassword.usernamePasswordRepeat']}');goBack();</script>");}
	if(!$password){exit ("<script>alert('{$LG['getpassword.inPasswordReset']}');goBack();</script>");}
	if($password!=$password2){exit ("<script>alert('{$LG['passwordRepeatError']}');goBack();</script>");}
	if(strlen($_POST['password'])<6){exit ("<script>alert('{$LG['passwordLengthError']}');goBack();</script>");}
	
	//修改密码
	//更新主表
	$rnd=make_password(20);
	$password=md5($rnd.md5($password));
	$ip=GetIP();
	$time=time();
	
	$xingao->query("update member set 
	password='".$password."',
	rnd='".$rnd."'
	where userid='".$_SESSION['getpassword_userid']."'");
	SQLError();
	
	//添加记录到登录日志
	$status=41;//41=使用找回密码重设  搜索：LoginState
	$xingao->query("insert into member_log (userid,username,logintime,loginip,status,password,loginauth) 
	values
	(
	'".add($_SESSION['getpassword_userid'])."',
	'".add($_SESSION['getpassword_username'])."',
	'".$time."',
	'".$ip."',
	'".$status."',
	'--',
	'1'
	)");
	SQLError();
	
	unset($_SESSION['getpassword_username']);
	unset($_SESSION['getpassword_userid']);
	unset($_SESSION['getpassword_yz']);
	unset($_SESSION['getpwd_yz_number']);
	unset($_SESSION['getpassword_time']);
	//处理完后删除密钥
	$token->drop_token("getpassword"); 
	exit("<script>alert('{$LG['getpassword.passwordResetSuccess']}');location='/xamember/';</script>");


}
?>