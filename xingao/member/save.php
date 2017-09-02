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
$pervar='member_ed';require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');
$member_cp=0;if(permissions('member_cp',0,'manage',1)){$member_cp=1;}//是否有会员高级管理权限


//获取,处理=====================================================
$lx=par($_REQUEST['lx']);
$userid=$_REQUEST['userid'];
$useric=par($_POST['useric']);
$groupid=par($_POST['groupid']);
$tokenkey=par($_POST['tokenkey']);
$_POST['username']=$_POST['xa_name'];
$username=par($_POST['username']);
$username_old=par($_POST['username_old']);
$password=add($_POST['password']);//密码不要用postrep过滤
$tixianpassword=par($_POST['tixianpassword']);
$currency=par($_POST['currency']);
$checked=(int)$_POST['checked'];

if (is_array($userid)){$userid=implode(',',$userid);}
$userid=par($userid);


//添加,修改=====================================================
if($lx=='add'||$lx=='edit')
{
	//通用验证------------------------------------
	$token=new Form_token_Core();
	$token->is_token("member{$userid}",$tokenkey); //验证令牌密钥
	
	if(strlen($username)<2){exit ("<script>alert('会员名不能小于2位数！');goBack();</script>");}
	if(!$currency){exit ("<script>alert('请选择币种！');goBack();</script>");}
	if($password==$username){exit ("<script>alert('会员名和密码不能相同！');goBack();</script>");}


	if(add($_POST['nickname']))	{
		$num=NumData('member',"nickname='".add($_POST['nickname'])."' and username<>'".add($_POST['username'])."'  ");
		if($num){exit ( "<script>alert('{$LG['data.save_34']}');goBack();</script>");}
	}

	

	//推广员
	if($_POST['tg_userid']){
		$_POST['tg_username']=FeData('member','username',"userid='".add($_POST['tg_userid'])."'");
		if(!$_POST['tg_username']){exit ("<script>alert('推广员会员ID错误！');goBack();</script>");}
	}
	
	//生成入库码
	$member_ic=(int)$member_ic;
	if($member_ic>0)
	{
		if($useric)
		{
			$num=mysqli_num_rows($xingao->query("select useric from member where useric='{$useric}' and userid<>'{$userid}'"));
			if($num){exit ("<script>alert('该“{$useric}”入库码已重复，请修改！');goBack();</script>");}
		}else{
			$useric=createWhcod('member');
		}
	}
	$_POST['useric']=$useric;
		
		
	if(!$member_cp)//无高级会员管理权限,不能操作以下字段
	{
	  $alone_cp=',api,api_key,api_yd_query,api_yd_add,
	  certification,truename,enname,mobile_code,mobile,email,gender,shenfenhaoma,shenfenimg_z,shenfenimg_b,handCert,
	  qq,wx';
	}else{

		//未通过审核原因 (发站内信)
		if(!$_POST['certification']&&$_POST['why_message'])
		{
			SendMsg($_POST['userid'],$_POST['username'],'实名认证未通过审核',html($_POST['why_message']),'',$Xuserid,$Xusername,1,0,0,0,$popup=1);//$popup=1 自动弹出
		}
		
		if($_POST['certification']==0||$_POST['certification']==1){$_POST['certification_for']=0;}
		if($_POST['certification']==2){$_POST['certification']=0;}

	}
		
	if($_POST['wx_del']){$_POST['wx_openid']='';$_POST['wx_binding_tmp']='';$_POST['wx_binding_time']=0;}


	//添加------------------------------------
	if($lx=='add')
	{
		//验证
		if(!$password){exit ("<script>alert('登录密码未填写！');goBack();</script>");}
		
		if(strlen($_POST['password'])<6){exit ("<script>alert('登录密码不能小于6位数！');goBack();</script>");}

		if($tixianpassword)
		{
			if(strlen($tixianpassword)<6){exit ("<script>alert('提现密码不能小于6位数！');goBack();</script>");}
		}

		RepeatUserName($username);//检查用户名是否重复

		//保存
		$rnd=make_password(20);
		$password=md5($rnd.md5($password));
		if($tixianpassword){$txrnd=make_password(20);$tixianpassword=md5($txrnd.md5($tixianpassword));}
		$regip=GetIP();
		$addtime=time();

		$savelx='add';//调用类型(add,edit,cache)
		$getlx='POST';//获取类型(POST,GET,REQUEST,SQL)
		$alone='wx_del,userid,password,tixianpassword,username_old,my,checked_old,old_img,old_shenfenimg_z,old_shenfenimg_b,old_handCert,why_message'.$alone_cp;//不处理的字段
		$digital='groupid,checked,integral,money,money_lock,loginnum,lasttime,pretime,api,api_yd_query,api_yd_add,tg_userid';//数字字段
		$radio='';//单选、复选、空文本、数组字段
		$textarea='content,company_business';//过滤不安全的HTML代码
		$date='birthday';//日期格式转数字
		$save=XingAoSave($_POST,$getlx,$savelx,$alone,$digital,$radio,$textarea,$html,$date);
		
		$xingao->query("insert into member (".$save['field'].",password,addtime,regip,tixianpassword,rnd,txrnd) values(".$save['value'].",'{$password}','{$addtime}','{$regip}','{$tixianpassword}','{$rnd}','{$txrnd}')");
		SQLError('添加');
		$rc=mysqli_affected_rows($xingao);
		if($rc>0)
		{
			$token->drop_token("member{$userid}"); //处理完后删除密钥
			exit("<script>alert('{$LG['pptAddSucceed']}');goBack('c');</script>");
		}else{
			exit ("<script>alert('{$LG['pptAddFailure']}');goBack();</script>");
		}
	}
	
	//修改------------------------------------
	if($lx=='edit')
	{
		//验证
		if(!$userid){exit ("<script>alert('userid{$LG['pptError']}');goBack();</script>");}
		
		$m=FeData('member','*',"userid='{$userid}'");
		
		if($password)
		{
			if(strlen($_POST['password'])<6){exit ("<script>alert('登录密码不能小于6位数！');goBack();</script>");}
		}
		if($tixianpassword)
		{
			if(strlen($tixianpassword)<6){exit ("<script>alert('提现密码不能小于6位数！');goBack();</script>");}
		}

		if($username!=$username_old){RepeatUserName($username);}//检查用户名是否重复
	
		SendChecked();//状态变更发通知
		
		//有单个文件字段时需要处理(要放在XingAoSave前面)
		DelFile($onefilefield='img','edit');
		DelFile($onefilefield='company_license','edit');
		DelFile('shenfenimg_z','edit');
		DelFile('shenfenimg_b','edit');
		DelFile('handCert','edit');
		

		//更新
		$savelx='edit';//调用类型(add,edit,cache)
		$getlx='POST';//获取类型(POST,GET,REQUEST,SQL)
		$alone='wx_del,userid,password,tixianpassword,username_old,my,checked_old,old_img,old_shenfenimg_z,old_shenfenimg_b,old_handCert,why_message'.$alone_cp;//不处理的字段
		$digital='groupid,checked,api,api_yd_query,api_yd_add,tg_userid,certification';//数字字段
		$radio='';//单选、复选、空文本、数组字段
		$textarea='content,company_business';//过滤不安全的HTML代码
		$date='birthday';//日期格式转数字
		$save=XingAoSave($_POST,$getlx,$savelx,$alone,$digital,$radio,$textarea,$html,$date);
		
		if($groupid!=$m['groupid']){$save.=",max_cz_once='0',max_cz_more='0'";}

		if($password)
		{
			$rnd=make_password(20);
			$password=md5($rnd.md5($password));
			$save.=",password='{$password}',rnd='{$rnd}'";
		}
		if($tixianpassword)
		{
			$txrnd=make_password(20);
			$tixianpassword=md5($txrnd.md5($tixianpassword));
			$save.=",tixianpassword='{$tixianpassword}',txrnd='{$txrnd}'";
		}
		
		
		
		//改变币种
		if($currency!=$m['currency'])
		{
			$exchange=exchange($m['currency'],$currency);
			$money=spr($m['money']*$exchange);
			$money_lock=spr($m['money_lock']*$exchange);
			$save.=",money='{$money}',money_lock='{$money_lock}'";
			$currency_change=1;
			$ts='\\n已变更币种,必须通知会员重新登录';
		}
		
		
		

		$xingao->query("update member set ".$save." where userid='{$userid}'");
		$rc=mysqli_affected_rows($xingao);
		SQLError('修改');
		
		if($rc>0)
		{
			
			//变更币种后操作
			if($currency_change)
			{
			
				//同步更新：提现申请
				$xingao->query("update tixian set money=money*{$exchange},currency='{$currency}' where status='1' and money<>'0' and currency='{$m['currency']}' and userid='{$m['userid']}'");
				SQLError('同步更新：提现申请');

				//同步更新
				$xingao->query("update daigou set freightFeePayTo=freightFeePayTo*{$exchange},toCurrency='{$currency}' where toCurrency='{$m['currency']}' and userid='{$m['userid']}'");
				SQLError('同步更新：代购表');
				
				$xingao->query("update daigou_goods set goodsFeePayTo=goodsFeePayTo*{$exchange} where userid='{$m['userid']}'");
				SQLError('同步更新：代购商品表');

				//发站内信息
				SendMsg($m['userid'],$m['username'],'账户币种已变更',"您的账户已按新币种 {$exchange} 汇率计算，从 {$m['money']}{$m['currency']} 变更到 {$money}{$currency} ",'',$Xuserid,$Xusername,$new=1,$status=0,$issys=1,$xs=0);
			}
			
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
					@$xingao->query("update {$rs[0]} set  tg_username='{$username}' where tg_username='{$username_old}' ");
					//@$xingao->query("update {$rs[0]} set  from_username='{$username}' where from_username='{$username_old}' ");
					//@$xingao->query("update {$rs[0]} set  reply_username='{$username}' where reply_username='{$username_old}' ");
				}
				//SQLError();//后面不能加这个,因为某些表可能没有该字段会出错
			}
	
			$ts=$LG['pptEditSucceed'].$ts;
			
		}else{
			$ts=$LG['pptEditNo'];
		}
		
		$token->drop_token("member{$userid}"); //处理完后删除密钥
		exit("<script>alert('".$ts."');goBack('c');</script>");
	}
	
//开通或关闭账号=====================================================
}elseif($lx=='checked'){
	if(!$userid){exit ("<script>alert('userid{$LG['pptError']}');goBack();</script>");}
	
	$Send=SendChecked();//状态变更发通知
	
	$xingao->query("update member set checked='{$checked}' where userid in ({$userid})");
	SQLError();
	
	$rc=mysqli_affected_rows($xingao);
	if($rc>0){
	   //返回
	   if($Send)
	   {
		  $ts="\\n已经发送通知(如有发邮件和短信一会才能收到)";
	   }else{
		  $ts="\\n没有发送通知，可能是没开通发通知功能或没有符合发送条件！(邮箱、电话未填写正确)";
	   }
		exit("<script>alert('操作成功,共更新了{$rc}个会员！".$ts."');location='list.php';</script>");
	}else{
		exit("<script>alert('没有符合条件可更新的会员！".$ts."');location='list.php';</script>");
	}
	
//移动=====================================================
}elseif($lx=='mobile'){
	$groupid_new=par($_POST['groupid_new']);
	if(!$groupid_new){exit ("<script>alert('请选择新分类！');goBack();</script>");}

	$xingao->query("update member set max_cz_once='0',max_cz_more='0' where groupid<>'{$groupid_new}' and userid in ({$userid})");SQLError('更新最大充值记录');
	$xingao->query("update member set groupid='{$groupid_new}' where userid in ({$userid})");SQLError('转移');
	$rc=mysqli_affected_rows($xingao);

	exit("<script>alert('转移完成,共转移{$rc}个会员！');location='list.php';</script>");

//删除=====================================================
}elseif($lx=='del'){
	
	if(!$userid){exit ("<script>alert('userid{$LG['pptError']}');goBack();</script>");}

	//删除会员相关记录
	$query="select username,userid,img,company_license from member where userid in ({$userid}) {$myMember}";
	$sql=$xingao->query($query);
	while($rs=$sql->fetch_array())
	{
		//删除文件
		DelFile($rs['img']);
		DelFile($rs['company_license']);
		$xingao->query("delete from member_log where username='{$rs[username]}'");
	}
	
	//删除会员相关记录
	//账户记录不删除，要用于数据统计
	//$xingao->query("delete from money_czbak where userid in ({$userid}) ");
	//$xingao->query("delete from money_kfbak where userid in ({$userid}) ");
	
	
	$xingao->query("delete from integral_czbak where userid in ({$userid}) ");
	$xingao->query("delete from integral_kfbak where userid in ({$userid}) ");
	
	//删除站内信息
	$query="select id,file from msg where userid in ({$userid})";
	$sql=$xingao->query($query);
	while($rs=$sql->fetch_array())
	{
		//删除站内信息文件
		DelFile($rs['file']);
		
		//删除站内信息回复
		$query2="select file from msg_reply where msgid in ({$rs[id]}) ";
		$sql2=$xingao->query($query2);
		while($rs2=$sql2->fetch_array())
		{
			//删除站内信息回复文件
			DelFile($rs2['file']);
		}
		$xingao->query("delete from msg_reply where msgid in ({$rs[id]}) ");
		
	}
	$xingao->query("delete from msg where userid in ({$userid}) ");

	//删除会员
	$xingao->query("delete from member where userid in ({$userid}) {$myMember}");
	$rc=mysqli_affected_rows($xingao);
	
	if($rc>0){
		exit("<script>alert('{$LG['pptDelSucceed']}{$rc}');location='list.php';</script>");
	}else{
		exit("<script>alert('{$LG['pptDelEmpty']}');location='list.php';</script>");
	}
	
}
?>


<?php
//状态变更发通知
function SendChecked()
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	global $sitename,$siteurl;
	global $checked,$userid;
	global $member_sh_msg,$member_sh_mail,$member_sh_sms,$member_sh_wx;
	global $Xgroupid,$Xuserid,$Xusername,$Xtruename,$Xrnd;

	if($member_sh_msg||$member_sh_mail||$member_sh_sms||$member_sh_wx)
	{
		$query="select username,userid,truename,email,mobile_code,mobile,addtime from member where checked<>'{$checked}' and userid in ({$userid}) {$myMember}";
		$sql=$xingao->query($query);
		while($rs=$sql->fetch_array())
		{
			if($checked)
			{
				//获取发送通知内容
				$NoticeTemplate='xingao_member_checked_1';	
				require($_SERVER['DOCUMENT_ROOT'].'/public/NoticeTemplate.php');
			}else{
				//获取发送通知内容
				$NoticeTemplate='xingao_member_checked_0';	
				require($_SERVER['DOCUMENT_ROOT'].'/public/NoticeTemplate.php');
			}

			//发站内信息
			if($member_sh_msg)
			{
				$from_userid=$Xuserid;
				$from_username=$Xusername;
				SendMsg($rs['userid'],$rs['username'],$send_title,$send_content_msg,$file,$from_userid,$from_username,$new=1,$status=0,$issys=1,$xs=0);
				$Send=1;
			}
			
			//发邮件
			if($member_sh_mail&&$rs['email'])
			{
				SendMail($rs['email'],$send_title,$send_content_mail,$file,$issys,$xs=0);
				$Send=1;
			}
			
			//发短信
			$mobile=SMSApiType($rs['mobile_code'],$rs['mobile']);
			if($member_sh_sms&&$mobile)
			{
				SendSMS($mobile,$send_content_sms,$xs=0);
				$Send=1;
			}
			
			//发微信
			if($member_sh_wx)
			{
				SendWX($send_WxTemId,$send_WxTemName,$send_content_wx,$rs['userid']);
				$Send=1;
			}
			
		}
	return $Send;
	}
}
?>