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

//获取
$lx=par($_POST['lx']);

//发短信-------------------------------------------
if($lx=='mobile')
{
	$mobile_code=par($_POST['mobile_code']);
	$mobile=par($_POST['mobile']);
	
	//判断
	if(!$off_reg_mobile){exit("<font class=red>{$LG['reg.yz_closeMobile']}</font>");}
	if(!$off_sms){exit("<font class=red>{$LG['reg.yz_closeSMS']}</font>");}
	if(!$mobile){exit("<font class=red>{$LG['reg.yz_MobileIn']}</font>");}
	if ($_SESSION['mobile_time']>strtotime('-1 minutes'))//多久（分）可以发送一次
	{
		$time2=date('Y-m-d H:i:s',strtotime('-1 minutes'));
		$time1=date('Y-m-d H:i:s',$_SESSION['mobile_time']);
		$time_s=DateDiff($time1,$time2,'s');;
		exit($time_s."<font class=red>{$LG['reg.yz_TimeLimit']}</font>");
	}
	if($mobile_code==86)
	{
		if (!CheckTelephone($mobile)){exit("<font class=red>{$LG['reg.yz_MobileError']}</font>");}
	}
	
	//查询判断
	$num=mysqli_num_rows($xingao->query("select username from member where mobile='{$mobile}' and  mobile_code='{$mobile_code}'"));
	if ($num){exit ("<font class=red>{$LG['reg.yz_MobileRepeat']}</font>");}	
	
	
	
	//生成验证码
	$_SESSION['mobile_code']=$mobile_code;//防止用户修改
	$_SESSION['mobile']=$mobile;//防止用户修改
	$_SESSION['mobile_yz']=no_make_password(6);
	$_SESSION['mobile_time']=time();
	
	//发短信
	$mobile=SMSApiType($mobile_code,$mobile);
	$content=$LG['reg.yz_MobileCode'].$_SESSION['mobile_yz'].'';
	SendSMS($mobile,$content,$xs=0);
	
	exit($LG['reg.yz_SendMobileCode']);
	
}
//发邮件-------------------------------------------
elseif($lx=='email')
{
	$email=par($_POST['email']);
	
	//判断
	if(!chemail($email)){exit("<font class=red>{$LG['reg.yz_EmailError']}</font>");}
	if ($_SESSION['email_time']>strtotime('-30 seconds'))//多久（秒）可以发送一次
	{
		$time2=date('Y-m-d H:i:s',strtotime('-30 seconds'));
		$time1=date('Y-m-d H:i:s',$_SESSION['email_time']);
		$time_s=DateDiff($time1,$time2,'s');;
		exit($time_s.'<font class=red>秒后才能再次发送</font>');
	}

	//查询判断
	$num=mysqli_num_rows($xingao->query("select username from member where email='{$email}'"));
	if ($num){exit ("<font class=red>{$LG['reg.yz_EmailRepeat']}</font>");}	
	
	//生成验证码
	$_SESSION['email']=$email;//防止用户修改
	$_SESSION['email_yz']=make_NoAndPa(6);
	$_SESSION['email_time']=time();
	
	//获取发送通知内容
	$NoticeTemplate='member_reg_yz';	
	require($_SERVER['DOCUMENT_ROOT'].'/public/NoticeTemplate.php');
	
	//发邮件
 	SendMail($email,$title,$content,$file,$issys=1,$xs=0);
	
	//发微信:未有账号前无法绑定微信,因此无法用微信验证
	exit($LG['reg.yz_SendEmailCode']);
}
?>
