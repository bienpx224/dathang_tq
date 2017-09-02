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

//已登录
$userid=$_SESSION['member']['userid'];
if($userid)
{
	echo '<script language=javascript>';
	echo 'location.href="main.php";';
	echo '</script>';
	XAtsto('main.php');
}


//获取,处理=====================================================
$lx=par($_REQUEST['lx']);




//添加,修改=====================================================
if($lx=='add')
{
	$code=strtolower(par($_POST['code']));
	$tokenkey=par($_POST['tokenkey']);
	$username=par($_POST['username']);
	//$email=par($_POST['email']);
	$email=par($_POST['username']);
	$password=add($_POST['password']);//密码不要用postrep过滤
	$password2=add($_POST['password2']);//密码不要用postrep过滤
	$groupid=par($_POST['groupid']);
	$email_yz=par($_POST['email_yz']);
	$mobile_code=par($_POST['mobile_code']);
	$mobile=par($_POST['mobile']);
	$mobile_yz=par($_POST['mobile_yz']);
	$tuiguang_userid=spr($_POST['tuiguang_userid']);
	$currency=par($_POST['currency']);

	//通用验证------------------------------------
	if(!$off_member_reg)
	{
		exit("<script>alert('{$LG['reg.CloseReg']}');goBack('uc');</script>");
	}
	
	$token=new Form_token_Core();
	$token->is_token("memberreg",$tokenkey); //验证令牌密钥
	
	if($off_code_reg)
	{
		if(!$code){exit("<script>alert('{$LG['codeEmpty']}');goBack();</script>");}
		
		$vname=xaReturnKeyVarname('reg');$code_se=$_SESSION[$vname];
		if($code!=$_SESSION[$vname]){unset($_SESSION[$vname]);exit ( "<script>alert('{$LG['codeOverdue']}');goBack();</script>");}
		unset($_SESSION[$vname]);
	}
	//验证------------------------------------
	
	//登录账号验证
	if(strlen($username)<2){exit ("<script>alert('{$LG['reg.save_1']}');goBack();</script>");}

	if (have($username,$member_reg_key,0)){exit ("<script>alert('{$username} {$LG['reg.save_2']}');goBack();</script>");}
	
	if($member_reg_lx==1)
	{
		if(!chemail($username)){exit ("<script>alert('{$username} {$LG['reg.save_3']}');goBack();</script>");}
		if (1==2&&trim($username)!=trim($email))
		{
			exit ("<script>alert('{$LG['reg.save_4']}');goBack();</script>");
		}
	}
	elseif($member_reg_lx==2)
	{
		if (!preg_match_all("/^[_a-zA-Z0-9]*$/",$username)){exit ("<script>alert('{$LG['reg.save_5']}');goBack();</script>");}//[可以用的内容]
	}
	elseif($member_reg_lx==3)
	{
		if (!preg_match_all("/^[a-zA-Z]*$/",$username)){exit ("<script>alert('{$LG['reg.save_6']}');goBack();</script>");}//[可以用的内容]
	}
	if(!$currency){exit ("<script>alert('{$LG['reg.save_34']}');goBack();</script>");}
	
	//邮箱验证
	if(!chemail($email)){exit ("<script>alert('{$LG['reg.save_7']}');goBack();</script>");}
	

	//昵称验证
	if($ON_nickname)/*1==2&&$ON_nickname*/
	{
		if(!add($_POST['nickname'])){exit ("<script>alert('{$LG['reg.save_35']}');goBack();</script>");}
		$num=NumData('member',"nickname='".add($_POST['nickname'])."' and username<>'".add($_POST['username'])."'  ");
		if($num){exit ( "<script>alert('{$LG['data.save_34']}');goBack();</script>");}
	}
	
	//密码验证
	if($password==$username){exit ("<script>alert('{$LG['reg.save_8']}');goBack();</script>");}
	if(!$password){exit ("<script>alert('{$LG['reg.save_9']}');goBack();</script>");}
	if(1==2&&$password!=$password2){exit ("<script>alert('{$LG['reg.save_10']}');goBack();</script>");}
	if(strlen($_POST['password'])<6){exit ("<script>alert('{$LG['reg.save_11']}');goBack();</script>");}

	if($off_reg_email)
	{
		if(!$email_yz){exit ("<script>alert('{$LG['reg.save_12']}');goBack();</script>");}
		if($email!=$_SESSION['email']){exit ("<script>alert('{$LG['reg.save_13']}');goBack();</script>");}
		if($email_yz!=$_SESSION['email_yz']){exit ("<script>alert('{$LG['reg.save_14']}');goBack();</script>");}		
	}

	if($off_reg_mobile==1&&$off_sms==1)
	{
		if(!$mobile||!$mobile_yz){exit ("<script>alert('{$LG['reg.save_15']}');goBack();</script>");}
		if($mobile_yz!=$_SESSION['mobile_yz']){exit ("<script>alert('{$LG['reg.save_18']}');goBack();</script>");}		
		if($mobile_code!=$_SESSION['mobile_code']){exit ("<script>alert('{$LG['reg.save_16']}');goBack();</script>");}
		if($mobile!=$_SESSION['mobile']){exit ("<script>alert('{$LG['reg.save_17']}');goBack();</script>");}
	}

	
	//查询数据验证
	RepeatUserName($username,$reg=1);//检查用户名是否重复

	$num=mysqli_num_rows($xingao->query("select username from member where email='{$email}'"));
	if($num){exit ("<script>alert('{$email} {$LG['reg.save_19']}');goBack();</script>");}
	
	$num=mysqli_num_rows($xingao->query("select groupid from member_group where checked=1 and regchecked=1 and groupid='{$groupid}'"));
	if(!$num){exit ("<script>alert('{$LG['reg.save_20']}');goBack();</script>");}


	//推广验证--开始－－－－－－－－－－－－－－－－－－
	if($off_integral&&$off_tuiguang&&$tuiguang_userid)
	{
		//防作弊－开始
		//获取
		$status=1;//全部默认有效
		$tgy_yx=1;//推广员默认有效
		$xhy_yx=1;//新会员默认有效
		$ip_now=GetIP(); //新注册会员的IP

		//推广员资料
		$rs_tgy=mysqli_fetch_array($xingao->query("select * from member where userid='{$tuiguang_userid}'"));
		if(!$rs_tgy['userid']){exit ("<script>alert('{$tuiguang_userid} {$LG['reg.save_21']}');goBack();</script>");}	
		
		//推广员最后一个注册的会员ID
		$rs_tgyx_id=mysqli_fetch_array($xingao->query("select * from tuiguang_bak where userid='{$tuiguang_userid}' order by addtime desc "));
		

		//推广员最后注册的会员资料
		$rs_tgyxhy=mysqli_fetch_array($xingao->query("select * from member where userid='{$rs_tgyx_id[userid]}' "));

		//新注册会员与推广员的注册/最后登录IP相同无效
		if($tuiguang_tgyip&&$status&&$rs_tgy['regip'])
		{
			if($ip_now==$rs_tgy['lastip']||$ip_now==$rs_tgy['regip']){$status=0;$invalid_content=$LG['reg.save_22'];}//新注册会员与推广员的注册或最后登录IP相同
		}
		
		//新注册会员与 最后注册的会员 的注册/最后登录IP相同无效 
		if($tuiguang_xhyip&&$status&&$rs_tgyxhy['regip'])
		{
			if($ip_now==$rs_tgyxhy['lastip']||$ip_now==$rs_tgyxhy['regip']){$status=0;$invalid_content=$LG['reg.save_23'];}//新注册会员与最后注册的会员的注册或最后登录IP相同
		}
		
		//新注册会员与 最后注册的会员 间隔 分种以内无效 
		if($tuiguang_xhysj>0&&$status&&$rs_tgyxhy['addtime'])
		{
			$time_now=date("Y-m-d H:i:s",time());
			$time_user=date("Y-m-d H:i:s",$rs_tgyxhy['addtime']);
			if((int)DateDiff($time_now,$time_user,'i')<$tuiguang_xhysj){$status=0;$invalid_content=$LG['reg.save_24'];}//新注册会员与最后注册的会员间隔太短
		}
		
		//新注册会员名与 最后注册的会员 名相似度 %无效
		if($tuiguang_xhymc>0&&$status&&$rs_tgyxhy['username'])
		{
			similar_text($username,$rs_tgyxhy['username'],$percent);
			if($percent>=$tuiguang_xhymc){$status=0;$invalid_content=$LG['reg.save_25'];}//新注册会员名与最后注册的会员名相似度太接近
		}
		
		//推广员每天最多有效获分 次，超过有效后 还送分给新注册会员
		if($tuiguang_tgyhdcs>0&&$status)
		{
			//推广员 今天注册的会员数量
			$start =strtotime(date('Y-m-d')." 00:00:00");
			$num=mysqli_num_rows($xingao->query("select userid from tuiguang_bak where userid='{$tuiguang_userid}' and addtime>='{$start}'"));

			if($num>$tuiguang_tgyhdcs)
			{
				$tgy_yx=0;
				if($tuiguang_xhyhdcs!=1){$xhy_yx=0;}
			}
			
		}
		
		//防作弊－结束
		
		//计算是否送分(
		$tuiguang_tgy_integral=$tuiguang_tgy;
		$tuiguang_xhy_integral=$tuiguang_xhy;
		if($status)
		{
			if(!$tgy_yx){$tuiguang_tgy_integral=0;$invalid_content=$LG['reg.save_26'];}//超过了每天最多获分次数
			if(!$xhy_yx){$tuiguang_xhy_integral=0;}
		}else{
			$tuiguang_tgy_integral=0;
			$tuiguang_xhy_integral=0;
		}
	}
	//推广验证--结束－－－－－－－－－－－－－－－－－－


	//保存------------------------------------
	$rnd=make_password(20);
	$password=md5($rnd.md5($password));
	$regip=GetIP();
	$addtime=time();
	$preip=$regip;
	$pretime=$addtime;
	
	if($integral_xhysf<0){$integral_xhysf=0;}//新注册送分
	$integral=$integral_xhysf+$tuiguang_xhy_integral;//推广送分
	$integral=(int)$integral;
	
	//是否需要审核
	$checked=1;$loginnum=1;
	if($member_reg_sh){$checked=0;$loginnum=0;}
	
	//生成入库码
	$useric=createWhcod('member');
	
	//获取推广员
	$tg_userid=spr($tuiguang_userid);
	if($tg_userid)
	{
		$tg_username=FeData('member','username',"userid='{$tg_userid}'");
		if(!$tg_username){$tg_userid=0;}
	}

	$xingao->query("insert into member 
	(
		username,useric,email,password,groupid,currency,mobile_code,mobile,addtime,regip,rnd,preip,pretime,loginnum,checked,tg_userid,tg_username,CustomerService,nickname
	)values(
		'{$username}','{$useric}','{$email}','{$password}','{$groupid}','{$currency}','{$mobile_code}','{$mobile}'
		,'{$addtime}','{$regip}','{$rnd}','{$preip}','{$pretime}','{$loginnum}','{$checked}','{$tg_userid}','{$tg_username}','".par($_POST['CustomerService'])."','".par($_POST['nickname'])."'
	)");
	SQLError('添加会员');

	$rc=mysqli_affected_rows($xingao);
	$userid=mysqli_insert_id($xingao);
	if($rc>0)
	{
		$token->drop_token("memberreg"); //处理完后删除密钥
		
		//保存,以用发邮件及登录
		setcookie("member_cookie",time(), time()+$member_cookie,"/");//过期时间
		$_SESSION['member']['groupid']=$groupid;
		$_SESSION['member']['userid']=$userid;
		$_SESSION['member']['useric']=$useric;
		$_SESSION['member']['username']=$username;
		$_SESSION['member']['truename']=$truename;
		$_SESSION['member']['enname']=$enname;
		$_SESSION['member']['rnd']=$rnd;
		$_SESSION['member']['certification']=0;
		
		
		
		
					
		//新会员注册送优惠券/折扣券
		for($i=1; $i<=3; $i++)
		{
			$tag='reg';
			$joint="{$tag}cp_number{$i}"; 						$cp_number=$$joint;
			$joint="{$tag}cp_types{$i}"; 						$cp_types=$$joint;
			$joint="{$tag}cp_usetypes{$i}"; 					$cp_usetypes=$$joint;
			$joint="{$tag}cp_value{$i}"; 						$cp_value=$$joint;
			$joint="{$tag}cp_limitmoney{$i}"; 					$cp_limitmoney=$$joint;
			$joint="{$tag}cp_code_digits{$i}"; 					$cp_code_digits=$$joint;
			$joint="{$tag}cp_overdue$i}"; 						$cp_overdue=$$joint;
			if($cp_number)
			{	
				if($cp_overdue){$duetime=strtotime('+'.$cp_overdue.' days');}else{$duetime=0;}
				create_coupons($duetime,'',$cp_types,$cp_value,$cp_limitmoney,$cp_usetypes,$code_number=1,$cp_code_digits,$cp_number,$userid,$username,$getSource='3');
			}
		}



		if($off_integral)
		{
			//加积分(新会员注册送分)	
			if ($integral_xhysf>0)
			{
				integralCZ($userid,'','',$integral_xhysf,$LG['reg.save_28'],'',$type=100);
			}
	
			//推广送分/优惠券/折扣券--开始－－－－－－－－－－－－－－－－－－
			if($off_tuiguang&&$tuiguang_userid)
			{
				//推广员=====================
				if($tuiguang_tgy_integral)//有效的
				{
			
					//推广员 加积分	
					integralCZ($tuiguang_userid,'','',$tuiguang_tgy_integral,$LG['reg.save_31'],'',$type=6);
					
					//推广员 加优惠券/折扣券
					$cp_number_total=0;
					for($i=1; $i<=3; $i++)
					{
						$tag='tgy';
						$joint="{$tag}cp_number{$i}"; 						$cp_number=$$joint;
						$joint="{$tag}cp_types{$i}"; 						$cp_types=$$joint;
						$joint="{$tag}cp_usetypes{$i}"; 					$cp_usetypes=$$joint;
						$joint="{$tag}cp_value{$i}"; 						$cp_value=$$joint;
						$joint="{$tag}cp_limitmoney{$i}"; 					$cp_limitmoney=$$joint;
						$joint="{$tag}cp_code_digits{$i}"; 					$cp_code_digits=$$joint;
						$joint="{$tag}cp_overdue$i}"; 						$cp_overdue=$$joint;
						if($cp_number)
						{	
							if($cp_overdue){$duetime=strtotime('+'.$cp_overdue.' days');}else{$duetime=0;}
							create_coupons($duetime,'',$cp_types,$cp_value,$cp_limitmoney,$cp_usetypes,$code_number=1,$cp_code_digits,$cp_number,$tuiguang_userid,$rs_tgy['username'],$getSource='4');
							$cp_number_total+=$cp_number;
						}
					}
					
					TuiGuangBak($tuiguang_userid,$rs_tgy['username'],$userid,$username,$status=1,$integral=$tuiguang_tgy_integral,$cp_number_total,$addtime=0);
				
				}else{//无效的
					if($tuiguang_tgy>0)
					{
						//推广员 添加无效推广记录
						TuiGuangBak($tuiguang_userid,$rs_tgy['username'],$userid,$username,$status=0,$integral=0,0,$addtime=0,$invalid_content);
						//推广员 加积分无效推广送0分记录
						integralCZ($tuiguang_userid,'','',0,$LG['reg.save_33'],'',$type=6);
					}
					
				}
				
				
			
				//新会员推广送分=====================
				if($tuiguang_xhy_integral)
				{
					//加积分(被邀请获得)	
					integralCZ($userid,'','',$tuiguang_xhy_integral,$LG['reg.save_32'],'',$type=6);
					
					//新会员 加优惠券/折扣券
					for($i=1; $i<=3; $i++)
					{
						$tag='xhy';
						$joint="{$tag}cp_number{$i}"; 						$cp_number=$$joint;
						$joint="{$tag}cp_types{$i}"; 						$cp_types=$$joint;
						$joint="{$tag}cp_usetypes{$i}"; 					$cp_usetypes=$$joint;
						$joint="{$tag}cp_value{$i}"; 						$cp_value=$$joint;
						$joint="{$tag}cp_limitmoney{$i}"; 					$cp_limitmoney=$$joint;
						$joint="{$tag}cp_code_digits{$i}"; 					$cp_code_digits=$$joint;
						$joint="{$tag}cp_overdue$i}"; 						$cp_overdue=$$joint;
						if($cp_number)
						{	
							if($cp_overdue){$duetime=strtotime('+'.$cp_overdue.' days');}else{$duetime=0;}
							create_coupons($duetime,'',$cp_types,$cp_value,$cp_limitmoney,$cp_usetypes,$code_number=1,$cp_code_digits,$cp_number,$userid,$username,$getSource='3');
						}
					}

				}else{
					//添加无效推广送0分记录
					if($tuiguang_xhy>0){
						integralCZ($userid,'','',0,$LG['reg.save_33'],'',$type=6);
					}
				}
			}
			//新会员推广送分-结束
			//推广送分/优惠券/折扣券--结束－－－－－－－－－－－－－－－－－－
		}
		
		if($member_reg_sh)
		{
			$ts=$LG['reg.save_27'];//注册成功，我们将会尽快审核您的申请，通过审核后才能登录！
		}
		
		//新注册发邮件
		if($off_member_reg_sendmail&&$email)
		{
			//获取发送通知内容
			$NoticeTemplate='member_reg';	
			require($_SERVER['DOCUMENT_ROOT'].'/public/NoticeTemplate.php');

			//发邮件
			SendMail($email,$title,$content,$file='',$issys=1,$xs=0);
		}
		
		//是否需要审核
		if($member_reg_sh)
		{
			//需要审核时清空
			setcookie("member_cookie",0, time()+$member_cookie,"/");//过期时间
			$_SESSION['member']['groupid']='';
			$_SESSION['member']['userid']='';
			$_SESSION['member']['useric']='';
			$_SESSION['member']['username']='';
			$_SESSION['member']['truename']='';
			$_SESSION['member']['rnd']='';
			$_SESSION['member']['certification']='';
			exit("<script>alert('".$ts."');location='/';</script>");
		}else{
			
			//不需要审核，成功处理
			
			$fr=FeData('member','*',"userid='{$userid}'");
			MemberLoginSuccess(21);//有多个地方使用
			
			//快捷登录绑定
			if($_SESSION['connect']['bindtoken'])
			{
			   member_connect_into($_SESSION['connect']['bindtoken'],$_SESSION['connect']['bindkey'],$_SESSION['connect']['apptype']);
			}
			 
			//转向:如有上一页则转向上一页
			$url='main.php';
			if($_SESSION['member']['prevurl']){$url=$_SESSION['member']['prevurl'];unset($_SESSION['member']['prevurl']);}
			echo '<script language=javascript>';
			echo 'alert("'.$LG['reg.save_29'].'");location.href="'.$url.'";';
			echo '</script>';
			XAtsto($url);
			exit();
		}


	}else{
		exit ("<script>alert('{$LG['reg.save_30']}');goBack();</script>");
	}
	
}
?>