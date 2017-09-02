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
$noper=1;require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');

//获取,处理=====================================================
$lx=par($_REQUEST['lx']);
$yz=par($_POST['yz']);
$code=strtolower(par($_POST['code']));
$tokenkey=par($_POST['tokenkey']);
$username=par($_POST['username']);
$password=add($_POST['password']);//密码不要用postrep过滤

//退出,不要全部清空,会把会员也删除
if($lx=='logout'){
  //账号资料
  unset($_SESSION['manage']['groupid']);
  unset($_SESSION['manage']['userid']);
  unset($_SESSION['manage']['username']);
  unset($_SESSION['manage']['truename']);
  unset($_SESSION['manage']['rnd']);
  //unset($_SESSION['language']);//前后,后台共用,不删除
  
  //安全入口
  unset($_SESSION['entrance']);
  
  //菜单缓存
  unset($_SESSION['cache_manage']);
  unset($_SESSION['cache_manage_time']);

  XAts($tslx='',$color='warning',$title='已退出',$content='如要登录请从安全入口进入！',$button='',$exit='1',0);
  
  echo '<script language=javascript>';
  echo 'goBack("c");';//某些浏览器直接转到本页时无法直接关闭网页
  echo '</script>';
}

//已登录
if($Xuserid)
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
	//关闭后台
	if($off_site_manage&&$off_site_manage!=$username)
	{
		XAts($tslx='',$color='info',$title='信息提示',$content=$site_manage_ts,$button='return',$exit='1');
	}
	
	$status=0;
	//基本验证
	$token=new Form_token_Core();
	$token->is_token("managelogin",$tokenkey); //验证令牌密钥
	
	if((!$password || !$username) && !$status){$status=100;echo "<script>alert('请输入用户名和密码！');goBack();</script>";}

	//查询数据验证
	if(!$status)
	{
		$fr=mysqli_fetch_array($xingao->query("select * from manage where username='{$username}'"));
		
		if(!$fr['checked'] && !$status){$status=100;echo "<script>alert('没有该账号或已被关闭！');goBack();</script>";}
		
		if($_SESSION['manage_codeshow']||($fr['fainum']>2 && $fr['pretime']>=strtotime('-60 minutes') )){$manage_codeshow=1;$_SESSION['manage_codeshow']=1;}
		if($off_code_managelogin && $manage_codeshow && !$status)
		{
			if(!$code && !$status){$status=100;echo "<script>alert('请输入验证码！');goBack();</script>";}
			
			$vname=xaReturnKeyVarname('login');
			if($code!=$_SESSION[$vname] && !$status){$status=100;unset($_SESSION[$vname]);echo "<script>alert('验证码错误或已过期请点击验证码以更新！');goBack();</script>";}
			unset($_SESSION[$vname]);
		}
		if(!$_SESSION['manage_codeshow']&&$fr['fainum']>=2 && $fr['pretime']>=strtotime('-60 minutes')){$_SESSION['manage_codeshow']=1;}


		if($fr['fainum']>$manage_login_limit && $fr['pretime']>=strtotime('-'.$manage_limit_time.' minutes') && !$status){$status=100;echo "<script>alert('登录失败次数太多,已禁止登录！');goBack();</script>";}

		if($manage_login_yz && !$status)
		{
			if(!$yz && !$status){$status=100;echo "<script>alert('请输入认证码！');goBack();</script>";}
			if($yz!=$manage_login_yz && !$status){$status=1;echo "<script>alert('认证码错误！');goBack();</script>";}
		}
		
		if(!$fr['userid'] && !$status){$status=2;echo "<script>alert('用户名或密码错误！');goBack();</script>";}//用户名错误,不直接显示,不安全
	
		$password=md5($fr['rnd'].md5($password));
		if($fr['password']!=$password && !$status){$status=3;echo "<script>alert('用户名或密码错误！');goBack();</script>";}//密码错误,不直接显示,不安全
		
	}

	
	//---------------------------------------------------------------------------------------------
	//登录成功
	if(!$status)
	{
		$_SESSION['manage_codeshow']=0;
		
		//更新主表
		$ip=GetIP();
		$loginadd=convertIP($ip);
		$time=time();
		$xingao->query("update manage set 
		loginnum=loginnum+1,
		fainum=0,
		lasttime='".$fr['pretime']."',
		lastip='".$fr['preip']."',
		pretime='".$time."',
		preip='".$ip."'
		where userid='{$fr[userid]}'"); //可换行
		SQLError();
		
		//添加登录成功记录
		$xingao->query("insert into manage_log (userid,username,logintime,loginip,loginadd,status,password,loginauth) 
		values
		(
		'".add($fr['userid'])."',
		'".add($fr['username'])."',
		'".$time."',
		'".$ip."',
		'".$loginadd."',
		'21',
		'',
		'0'
		)");
		SQLError();
		
		//保存
		setcookie("manage_cookie",time(), time()+$manage_cookie,"/");//过期时间
		$_SESSION['manage']['groupid']=(int)$fr['groupid'];
		$_SESSION['manage']['userid']=(int)$fr['userid'];
		$_SESSION['manage']['username']=cadd($fr['username']);
		$_SESSION['manage']['truename']=cadd($fr['truename']);
		$_SESSION['manage']['rnd']=cadd($fr['rnd']);
		$_SESSION['manage']['popuptime']=spr($fr['popuptime']);
		$_SESSION['language']=cadd($fr['language']);
		
		$token->drop_token("managelogin"); //处理完后删除密钥
		
		
		
		//登录成功转向:如有上一页则转向上一页
		$url='main.php';
		if($_SESSION['manage']['prevurl']){$url=$_SESSION['manage']['prevurl'];unset($_SESSION['manage']['prevurl']);}
		echo '<script language=javascript>';
		echo 'location.href="'.$url.'";';
		echo '</script>';
		XAtsto($url);
		exit();
	}

	//登录失败
	if($status && $status!=100)
	{
		if($off_code_managelogin && $fr['fainum']>1)//1是登录失败3次显示验证码，2是失败4次，如此叠加
		{
			$_SESSION['manage_codeshow']=1;
		}
		
		//更新主表
		$ip=GetIP();
		$loginadd=convertIP($ip);
		$time=time();
		$xingao->query("update manage set 
		fainum=fainum+1,
		pretime='".$time."',
		preip='".$ip."'
		where userid='{$fr[userid]}'"); //可换行
		SQLError();
		
		//添加登录失败记录
		if($status==1||$status==2||$status==3){$loginauth=1;}else{$loginauth=0;}
		$xingao->query("insert into manage_log (userid,username,logintime,loginip,loginadd,status,password,loginauth) 
		values
		(
		'".add($fr['userid'])."',
		'".add($fr['username'])."',
		'".$time."',
		'".$ip."',
		'".$loginadd."',
		'".$status."',
		'".add($_POST['password'])."',
		'".$loginauth."'
		)");
		SQLError();
	}
	
}
?>