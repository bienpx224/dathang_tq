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
require_once($_SERVER['DOCUMENT_ROOT'].'/api/login/qq/qqConnectAPI.php');

//echo '1';
$qc = new QC();
$token= $qc->qq_callback();//该会员的access_token
//echo '2';
$openid= $qc->get_openid();//该会员的openid (主要验证这个来登录)
//echo '3';
$qc = new QC($token,$openid);//必须重新定义：用于保证正确传输access_token和openid以保证api的使用
$arr = $qc->get_user_info();//该会员的资料
//echo '4';


//绑定
if($Mgroupid&&$Muserid)//特别情况必须2个
{
	member_connect_into($token,$openid,'qq');
//登录
}else{
	$_SESSION['connect']['bindtoken']=$token;
	$_SESSION['connect']['bindkey']=$openid;
	$_SESSION['connect']['apptype']='qq';
	$_SESSION['connect']['img']=$arr['figureurl_2'];
	$_SESSION['connect']['nickname']=$arr["nickname"];
	
	if($off_connect_qq_checked)
	{
		$_SESSION['member']['groupid']=1;
		echo '<script language=javascript>';
		echo 'location.href="/xamember/qq_checked.php";';
		echo '</script>';
		XAtsto('/xamember/qq_checked.php');
	}
	else
	{
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
			echo 'location.href="/xamember/index.php?apptype=qq";';
			echo '</script>';
			XAtsto('/xamember/index.php?apptype=qq');
		}
	}
}






/*
foreach($arr as $k => $v){//输出全部资料(无法获取QQ号)
	echo '<li>'.$k.': &nbsp; '.$v.'</li>';
}

	echo "<img src=\"".$arr['figureurl']."\">";
	echo " 您好:".$arr["nickname"]."，您已通过QQ成功登录";


echo "<p>";
echo "性别:".$arr["gender"];
echo "</p>";
echo "<p>";
echo "昵称:".$arr["nickname"];
echo "</p>";
echo "<p>";
echo "小头像<img src=\"".$arr['figureurl']."\">";
echo "<p>";
echo "<p>";
echo "中头像<img src=\"".$arr['figureurl_1']."\">";
echo "<p>";
echo "<p>";
echo "大头像<img src=\"".$arr['figureurl_2']."\">";
echo "<p>";
echo "vip:".$arr["vip"];
echo "</p>";
echo "level:".$arr["level"];
echo "</p>";
echo "is_yellow_year_vip:".$arr["is_yellow_year_vip"];
echo "</p>";
*/
?>