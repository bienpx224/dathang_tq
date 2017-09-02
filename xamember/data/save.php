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
$lx=par($_POST['lx']);
$tokenkey=par($_POST['tokenkey']);
$code=strtolower(par($_POST['code']));

//通用验证------------------------------------
$token=new Form_token_Core();
$token->is_token('member',$tokenkey); //验证令牌密钥

if($lx=='login_pwd'||$lx=='tixian_pwd'||$lx=='contact'){
	if(!$code){exit ( "<script>alert('{$LG['codeEmpty']}');goBack();</script>");}	
	$vname=xaReturnKeyVarname('safety');$code_se=$_SESSION[$vname];
	if($code!=$_SESSION[$vname]){unset($_SESSION[$vname]);exit ("<script>alert('{$LG['codeOverdue']}');goBack();</script>");}
	unset($_SESSION[$vname]);
}



	if(add($_POST['nickname']))	{
		$num=NumData('member',"nickname='".add($_POST['nickname'])."' and userid<>'{$Muserid}'");
		if($num){exit ( "<script>alert('{$LG['data.save_34']}');goBack();</script>");}	
	}






//基本资料修改=====================================================
if($lx=='basic')
{
	//有单个文件字段时需要处理(要放在XingAoSave前面)
	DelFile($onefilefield='img','edit');
	DelFile($onefilefield='company_license','edit');

	//更新
	$savelx='edit';//调用类型(add,edit,cache)
	$getlx='POST';//获取类型(POST,GET,REQUEST,SQL)
	$alone='old_img,old_company_license';//不处理的字段
	$digital='';//数字字段
	$radio='';//单选、复选、空文本、数组字段
	$textarea='content,company_business';//过滤不安全的HTML代码
	$date='';//日期格式转数字
	$save=XingAoSave($_POST,$getlx,$savelx,$alone,$digital,$radio,$textarea,$html,$date);
	
	$xingao->query("update member set ".$save." where 1=1 {$Mmy}");SQLError('基本修改');
	$rc=mysqli_affected_rows($xingao);
	
	$token->drop_token('member'); //处理完后删除密钥
	if($rc>0)
	{
		$ts=$LG['pptEditSucceed'];
	}else{
		$ts=$LG['pptEditNo'];
	}
	exit("<script>alert('".$ts."');location='form.php';</script>");
	
}








//修改登录密码=====================================================
elseif($lx=='login_pwd')
{
	$password=add($_POST['password']);//密码不要用postrep过滤
	$new_password=add($_POST['new_password']);//密码不要用postrep过滤
	$new_password2=add($_POST['new_password2']);//密码不要用postrep过滤
	

	//验证
	if($password==$Musername){exit ("<script>alert('{$LG['data.save_2']}');goBack();</script>");}
	if($password==$new_password){exit ("<script>alert('{$LG['data.save_1']}');goBack();</script>");}
	if(!$password){exit ("<script>alert('{$LG['data.save_4']}');goBack();</script>");}
	if(!$new_password){exit ("<script>alert('{$LG['data.save_5']}');goBack();</script>");}
	if(strlen($new_password)<6){exit ("<script>alert('{$LG['data.save_6']}');goBack();</script>");}
	if($new_password!=$new_password2){exit ("<script>alert('{$LG['data.save_7']}');goBack();</script>");}
	
	//查询验证
	$fr=mysqli_fetch_array($xingao->query("select rnd,password from member where 1=1 {$Mmy}"));
	$password=md5($fr['rnd'].md5($password));
	if($fr['password']!=$password){exit( "<script>alert('{$LG['data.save_8']}');goBack();</script>");}

	//保存
	$rnd=make_password(20);
	$new_password=md5($rnd.md5($new_password));
	
	$xingao->query("update member set password='{$new_password}',rnd='{$rnd}' where 1=1 {$Mmy}");
	$rc=mysqli_affected_rows($xingao);
	SQLError();
	
	
	$token->drop_token('member'); //处理完后删除密钥
	if($rc>0)
	{
		$ts=$LG['pptEditSucceed'];
		$_SESSION['member']['rnd']=$rnd;
	}else{
		$ts=$LG['pptEditFailure'];
	}
	exit("<script>alert('".$ts."');goBack();</script>");
	
}








//修改提现密码=====================================================
elseif($lx=='tixian_pwd'){

	$tixianpassword=par($_POST['tixianpassword']);
	$new_tixianpassword=par($_POST['new_tixianpassword']);
	$new_tixianpassword2=par($_POST['new_tixianpassword2']);
	
	//验证
	if($new_tixianpassword==$Musername){exit ("<script>alert('{$LG['data.save_2']}');goBack();</script>");}
	if($tixianpassword==$new_tixianpassword){exit ("<script>alert('{$LG['data.save_1']}');goBack();</script>");}
	if(!$new_tixianpassword){exit ("<script>alert('{$LG['data.save_5']}');goBack();</script>");}
	if(strlen($new_tixianpassword)<6){exit ("<script>alert('{$LG['data.save_6']}');goBack();</script>");}
	if($new_tixianpassword!=$new_tixianpassword2){exit ("<script>alert('{$LG['data.save_7']}');goBack();</script>");}
	
	//查询验证
	$fr=mysqli_fetch_array($xingao->query("select txrnd,tixianpassword from member where 1=1 {$Mmy}"));
	if($fr['tixianpassword'])
	{
		if(!$tixianpassword){exit ("<script>alert('{$LG['data.save_9']}');goBack();</script>");}
		
		$tixianpassword=md5($fr['txrnd'].md5($tixianpassword));
		if($fr['tixianpassword']!=$tixianpassword){exit( "<script>alert('{$LG['data.save_10']}');goBack();</script>");}
	}

	//保存
	$txrnd=make_password(20);
	$new_tixianpassword=md5($txrnd.md5($new_tixianpassword));
	
	$xingao->query("update member set tixianpassword='{$new_tixianpassword}',txrnd='{$txrnd}' where 1=1 {$Mmy}");
	$rc=mysqli_affected_rows($xingao);
	SQLError();
	
	
	$token->drop_token('member'); //处理完后删除密钥
	if($rc>0)
	{
		$ts=$LG['pptEditSucceed'];
	}else{
		$ts=$LG['pptEditFailure'];
	}
	exit("<script>alert('".$ts."');goBack();</script>");
}








//修改手机/邮箱=====================================================
elseif($lx=='contact'){

	$mobile_code=par($_POST['mobile_code']);
	$mobile=par($_POST['mobile']);
	$mobile_yz=par($_POST['mobile_yz']);
	$email=par($_POST['email']);
	$email_yz=par($_POST['email_yz']);
	
	//查询验证
	$fr=mysqli_fetch_array($xingao->query("select mobile_code,mobile,email from member where 1=1 {$Mmy}"));
	
	//修改手机------------------------------------
	if($mobile)
	{
		//判断
		if($mobile_code==86)
		{
			if (!CheckTelephone($mobile)){exit ("<script>alert('{$LG['data.save_11']}');goBack();</script>");}
		}
		
		//是否要验证原手机
		$yz=1;
		if($fr['mobile'])
		{
			if ($fr['mobile_code']==86&&!CheckTelephone($fr['mobile'])){$yz=0;}
		}else{
			$yz=0;
		}

		if($yz)
		{
			if(!$mobile_yz){exit ("<script>alert('{$LG['data.save_12']}');goBack();</script>");}
			if(!$_SESSION['mobile_yz']){exit("<script>alert('{$LG['data.save_13']}');goBack();</script>");}
			if($fr['mobile_code']!=$_SESSION['mobile_code']){exit ("<script>alert('{$LG['data.save_14']}');goBack();</script>");}
			if($fr['mobile']!=$_SESSION['mobile']){exit ("<script>alert('{$LG['data.save_15']}');goBack();</script>");}
			
			if($mobile_yz!=$_SESSION['mobile_yz'])
			{
				$_SESSION['sf_myz_number']+=1;
				if($_SESSION['sf_myz_number']>=5)
				{
					 unset($_SESSION['mobile_code']);
					 unset($_SESSION['mobile']);
					 unset($_SESSION['mobile_yz']);
					 unset($_SESSION['mobile_time']);
					 unset($_SESSION['sf_myz_number']);
				     exit("<script>alert('{$LG['data.save_16']}');goBack();</script>");
				}
				exit ("<script>alert('{$LG['data.save_19']}');goBack();</script>");
			}		
		}
		
		//保存字段
		$save="mobile_code='{$mobile_code}',mobile='{$mobile}'";
	}

	//修改邮箱------------------------------------
	if($email)
	{
		//判断
		if (!chemail($email)){exit ("<script>alert('{$LG['data.save_20']}');goBack();</script>");}
		
		//是否要验证原邮箱
		$yz=1;
		if(!$fr['email']||!chemail($fr['email']) )
		{
			$yz=0;
		}

		if($yz)
		{
			if(!$email_yz){exit ("<script>alert('{$LG['data.save_21']}');goBack();</script>");}
			if(!$_SESSION['email_yz']){exit("<script>alert('{$LG['data.save_13']}');goBack();</script>");}
			if($fr['email']!=$_SESSION['email']){exit ("<script>alert('{$LG['data.save_22']}');goBack();</script>");}
			if($email_yz!=$_SESSION['email_yz'])
			{
				$_SESSION['sf_eyz_number']+=1;
				if($_SESSION['sf_eyz_number']>=5)
				{
					 unset($_SESSION['email']);
					 unset($_SESSION['email_yz']);
					 unset($_SESSION['email_time']);
					 unset($_SESSION['sf_eyz_number']);
				     exit("<script>alert('{$LG['data.save_16']}');goBack();</script>");
				}
				exit ("<script>alert('{$LG['data.save_26']}');goBack();</script>");
			}		
		}
		
		//保存字段
		if($save){$save.=",email='{$email}'";}else{$save="email='{$email}'";}
		
	}
	

	//保存
	$xingao->query("update member set {$save} where 1=1 {$Mmy}");
	$rc=mysqli_affected_rows($xingao);
	SQLError();
	
	$token->drop_token('member'); //处理完后删除密钥
	if($rc>0)
	{
	   unset($_SESSION['mobile_code']);
	   unset($_SESSION['mobile']);
	   unset($_SESSION['mobile_yz']);
	   unset($_SESSION['mobile_time']);
	   unset($_SESSION['email']);
	   unset($_SESSION['email_yz']);
	   unset($_SESSION['email_time']);
	   unset($_SESSION['sf_myz_number']);
	   unset($_SESSION['sf_eyz_number']);
		$ts=$LG['pptEditSucceed'];
	}else{
		$ts=$LG['pptEditFailure'];
	}
	exit("<script>alert('".$ts."');goBack();</script>");
}








//基本资料修改=====================================================
elseif($lx=='certification')
{
	//验证
	if($certification_ct2&&!$_POST['shenfenhaoma']){exit ("<script>alert('{$LG['data.save_27']}');goBack();</script>");}
	if($certification_ct3&&!$_POST['shenfenimg_z']&&!$_POST['old_shenfenimg_z']){exit ("<script>alert('{$LG['data.save_28']}');goBack();</script>");}
	if($certification_ct3&&!$_POST['shenfenimg_b']&&!$_POST['old_shenfenimg_b']){exit ("<script>alert('{$LG['data.save_29']}');goBack();</script>");}
	if($certification_ct3&&!$_POST['handCert']&&!$_POST['old_handCert']){exit ("<script>alert('{$LG['data.save_30']}');goBack();</script>");}

	//有单个文件字段时需要处理(要放在XingAoSave前面)
	DelFile('shenfenimg_z','edit');
	DelFile('shenfenimg_b','edit');
	DelFile('handCert','edit');

	//更新
	$savelx='edit';//调用类型(add,edit,cache)
	$getlx='POST';//获取类型(POST,GET,REQUEST,SQL)
	$alone='old_shenfenimg_z,old_shenfenimg_b,old_handCert';//不处理的字段
	$digital='certification_for';//数字字段
	$radio='';//单选、复选、空文本、数组字段
	$textarea='';//过滤不安全的HTML代码
	$date='birthday';//日期格式转数字
	$save=XingAoSave($_POST,$getlx,$savelx,$alone,$digital,$radio,$textarea,$html,$date);
	
	$xingao->query("update member set ".$save." where 1=1 {$Mmy}");SQLError('认证修改');
	$rc=mysqli_affected_rows($xingao);
	
	$token->drop_token('member'); //处理完后删除密钥
	if($rc>0)
	{
		$_SESSION['member']['enname']=add($_POST['enname']);//用于生成运单或代购单号格式,所以需要修改后就可用
		$ts=$LG['pptEditSucceed'];
		if(!$_POST['certification_for']){$ts.=$LG['data.save_32'];}//，但未提交审核申请，如确认无误请提交审核！
	}else{
		$ts=$LG['pptEditNo'];
	}
	exit("<script>alert('".$ts."');location='form.php?tab=4';</script>");
	
}

else
{
	exit("<script>alert('{$LG['data.save_33']}');goBack();</script>");
}
?>