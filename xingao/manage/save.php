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
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');

//获取,处理=====================================================
$my=par($_REQUEST['my']);
$lx=par($_REQUEST['lx']);
$userid=$_REQUEST['userid'];
$groupid=par($_POST['groupid']);
$tokenkey=par($_POST['tokenkey']);
$_POST['username']=$_POST['xa_name'];
$username=par($_POST['username']);
$username_old=par($_POST['username_old']);
$password=add($_POST['password']);//密码不要用postrep过滤
$password2=add($_POST['password2']);//密码不要用postrep过滤

if(!permissions('manage_ma','','manage',1))
{
	$groupid=$_SESSION['manage']['groupid'];
}

if($my)
{
	$lx='edit';
	$userid=$Xuserid;
}else{
	permissions('manage_ma','','manage','');
}

if (is_array($userid)){$userid=implode(',',$userid);}
$userid=par($userid);


//添加,修改=====================================================
if($lx=='add'||$lx=='edit')
{
	//通用验证------------------------------------
	$token=new Form_token_Core();
	$token->is_token("manage",$tokenkey); //验证令牌密钥
	
	if(strlen($username)<2){exit ("<script>alert('用户名不能小于2位数！');goBack();</script>");}
	if($password==$username){exit ("<script>alert('用户名和密码不能相同！');goBack();</script>");}

	//添加------------------------------------
	if($lx=='add')
	{
		//验证
		if(!$password||$password!=$password2){exit ("<script>alert('密码未填写或所填写2次密码不相同！');goBack();</script>");}
		
		if(strlen($_POST['password'])<6){exit ("<script>alert('密码不能小于6位数！');goBack();</script>");}

		RepeatUserName($username);//检查用户名是否重复
		
		//保存
		$rnd=make_password(20);
		$password=md5($rnd.md5($password));
		$addip=GetIP();
		$addtime=time();

		$savelx='add';//调用类型(add,edit,cache)
		$getlx='POST';//获取类型(POST,GET,REQUEST,SQL)
		$alone='userid,password,password2,username_old,my';//不处理的字段
		$digital='groupid,checked,loginnum,lasttime,pretime';//数字字段
		$radio='userprikey';//单选、复选、空文本、数组字段
		$textarea='content';//过滤不安全的HTML代码
		$date='';//日期格式转数字
		$save=XingAoSave($_POST,$getlx,$savelx,$alone,$digital,$radio,$textarea,$html,$date);
		
		$xingao->query("insert into manage (".$save['field'].",password,addtime,addip,rnd) values(".$save['value'].",'{$password}','{$addtime}','{$addip}','{$rnd}')");
		SQLError();
		$rc=mysqli_affected_rows($xingao);
		if($rc>0)
		{
			$token->drop_token("manage"); //处理完后删除密钥
			exit("<script>alert('{$LG['pptAddSucceed']}');location='list.php';</script>");
		}else{
			exit ("<script>alert('{$LG['pptAddFailure']}');goBack();</script>");
		}
	}
	
	//修改------------------------------------
	if($lx=='edit')
	{
		//验证
		if($password)
		{
			if($password!=$password2){exit ("<script>alert('所填写2次密码不相同！');goBack();</script>");}
			if(strlen($_POST['password'])<6){exit ("<script>alert('密码不能小于6位数！');goBack();</script>");}
		}
		if(!$userid){exit ("<script>alert('userid{$LG['pptError']}');goBack();</script>");}
		if($username!=$username_old)
		{
			RepeatUserName($username);//检查用户名是否重复
		}
		
		//更新
		$savelx='edit';//调用类型(add,edit,cache)
		$getlx='POST';//获取类型(POST,GET,REQUEST,SQL)
		$alone='userid,password,password2,username_old,my';//不处理的字段
		$digital='groupid,checked';//数字字段
		$radio='';//单选、复选、空文本、数组字段
		$textarea='content';//过滤不安全的HTML代码
		$date='';//日期格式转数字
		$save=XingAoSave($_POST,$getlx,$savelx,$alone,$digital,$radio,$textarea,$html,$date);

		if($password)
		{
			$rnd=make_password(20);
			$password=md5($rnd.md5($password));
			$save.=",password='{$password}',rnd='{$rnd}'";
		}

		$xingao->query("update manage set ".$save." where userid='{$userid}'");
		SQLError();
		$rc=mysqli_affected_rows($xingao);
		
		if($rc>0)
		{
			//修改用户名时,修改所有表的旧用户名
			if($username!=$username_old)
			{
				//更新数据库中所有表的字段值
				$query="show tables";//查询数据库的所有表
				$sql=$xingao->query($query);
				while($rs=$sql->fetch_array())
				{
					//echo '数据表:'.$rs[0].'<br>';
					//更新,要加@
					@$xingao->query("update {$rs[0]} set  username='{$username}' where username='{$username_old}' ");
					@$xingao->query("update {$rs[0]} set  from_username='{$username}' where from_username='{$username_old}' ");
					@$xingao->query("update {$rs[0]} set  reply_username='{$username}' where reply_username='{$username_old}' ");
					
				}
				//SQLError();//后面不能加这个,因为某些表可能没有该字段会出错
			}
	
			$ts=$LG['pptEditSucceed'];
			
		}else{
			$ts='未有任何修改';
		}

		$token->drop_token("manage"); //处理完后删除密钥
		exit("<script>alert('".$ts."');location='list.php';</script>");
	}
	
	
	
//删除=====================================================
}elseif($lx=='del'){
	
	if(!$userid){exit ("<script>alert('userid{$LG['pptError']}');goBack();</script>");}

	//删除用户相关记录
	$query="select username from manage where userid in ({$userid})";
	$sql=$xingao->query($query);
	while($rs=$sql->fetch_array())
	{
		$xingao->query("delete from manage_log where username='{$rs[username]}'");//删除用户日志
	}
	
	//删除用户
	$xingao->query("delete from manage where userid in ({$userid})");
	$rc=mysqli_affected_rows($xingao);
	
	if($rc>0){
		exit("<script>alert('{$LG['pptDelSucceed']}{$rc}');location='list.php';</script>");
	}else{
		exit("<script>alert('{$LG['pptDelEmpty']}');location='list.php';</script>");
	}
	
}
?>