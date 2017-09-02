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
$noper=1;
require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/session.php');

//由接口GET返回
$state=par($_GET['state']);
$code=par($_GET['code']);

//验证
if(!$state){
      exit('获取的state是空的!');
}elseif($state!=$_SESSION['api_weixin']){
      exit('获取的state与本站的state不相同!');
}

//获取access_token 与 openid数据
$url="https://api.weixin.qq.com/sns/oauth2/access_token?appid={$connect_weixinid}&secret={$connect_weixinkey}&code={$code}&grant_type=authorization_code";
$arr=send_get($url);
$token=$arr['access_token'];
$openid=$arr['openid'];



//绑定-------------------------------------------------------------------------------------
if($Mgroupid&&$Muserid)//特别情况必须2个
{
	member_connect_into($token,$openid,'weixin');

//登录-------------------------------------------------------------------------------------
}else{
	//获取微信的资料
	$url='https://api.weixin.qq.com/sns/userinfo?access_token='.$token.'&openid='.$openid.'&lang=zh_CN';
	$user=send_get($url);
	/*
	资料参数:
	$user['nickname']:昵称
	$user['headimgurl']:头像
	http://www.thinkphp.cn/Uploads/editor/2015-10-28/562fa3238875d.JPG
	*/

	$_SESSION['connect']['bindtoken']=$token;
	$_SESSION['connect']['bindkey']=$openid;
	$_SESSION['connect']['apptype']='weixin';
	$_SESSION['connect']['img']=$user['headimgurl'];
	$_SESSION['connect']['nickname']=$user['nickname'];
	
	$num=mysqli_num_rows($xingao->query("select userid from member_connect where bindkey='".$_SESSION['connect']['bindkey']."' "));
	if($num)
	{
		echo '<script language=javascript>';
		echo 'location.href="/xamember/login_save.php?lx=login&connect=1";';
		echo '</script>';
		XAtsto('/xamember/login_save.php?lx=login&connect=1');
	}
	else
	{
		echo '<script language=javascript>';
		echo 'location.href="/xamember/index.php?apptype=weixin";';
		echo '</script>';
		XAtsto('/xamember/index.php?apptype=weixin');
	}
}
?>