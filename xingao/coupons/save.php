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
$pervar='coupons';//权限验证
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');

//获取,处理=====================================================
$lx=par($_REQUEST['lx']);
$cpid=$_REQUEST['cpid'];
$status=par($_REQUEST['status']);

$tokenkey=par($_POST['tokenkey']);
$code_number=spr($_POST['code_number']);
$code_digits=spr($_POST['code_digits']);
$number=spr($_POST['number']);
$prevurl = isset($_SERVER['HTTP_REFERER']) ? trim($_SERVER['HTTP_REFERER']) : 'list.php';

if (is_array($cpid)){$cpid=implode(',',$cpid);}
$cpid=par($cpid);

if($lx!='add'&&$lx!='del'&&!$cpid){exit ("<script>alert('请选择要修改的信息！');goBack();</script>");}

//添加,没有修改=====================================================
if($lx=='add')
{
	//通用验证------------------------------------
	$token=new Form_token_Core();
	$token->is_token("coupons",$tokenkey); //验证令牌密钥
	
	if(!CheckEmpty($_POST['types'])){exit ("<script>alert('请选择类型！');goBack();</script>");}
	if(!CheckEmpty($_POST['usetypes'])){exit ("<script>alert('请选择可使用类型！');goBack();</script>");}
	if(!$_POST['value']){exit ("<script>alert('请填写价值！');goBack();</script>");}
	if($_POST['value']<=0){exit ("<script>alert('价值要大于0！');goBack();</script>");}
	if($_POST['types']==2&&$_POST['value']>=10){exit ("<script>alert('折扣券价值要小于10！');goBack();</script>");}
	if(!$_POST['limitmoney']){exit ("<script>alert('请填写最低消费金额！');goBack();</script>");}
	
	if(!$code_number||$code_number>1000){exit ("<script>alert('添加张数请填写在1-1000之间！');goBack();</script>");}
	if($code_digits<8||$code_digits>30){exit ("<script>alert('兑换码位数请填写在8-30之间！');goBack();</script>");}
	if(!$number||$number>100){exit ("<script>alert('使用次数请填写在1-100之间！');goBack();</script>");}
		
	
	//生成优惠券/折扣券参数-------------------------------------
	$duetime=0;if($_POST['duetime1']){$duetime=ToStrtotime($_POST['duetime1'],$_POST['duetime2']);}
	$content=add($_POST['content']);
	$types=spr($_POST['types']);
	$value=spr($_POST['value']);
	$limitmoney=spr($_POST['limitmoney']);
	$usetypes=spr($_POST['usetypes']);
	
	
	$userid=$_POST['userid'];
	$groupid=$_POST['groupid'];
	if($userid||$groupid)
	{
		//添加给会员-开始------------------------------------
		$send_msg=(int)$_POST['send_msg'];
		$send_mail=(int)$_POST['send_mail'];
		$send_sms=(int)$_POST['send_sms'];
		$send_title=trim($_POST['send_title']);
		$send_content=trim($_POST['send_content']);
		
		if (is_array($userid)){$userid=implode(',',$userid);}
		if (is_array($groupid)){$groupid=implode(',',$groupid);}
		if ($send_msg||$send_mail||$send_sms)
		{
			if (!$send_title){exit ("<script>alert('请填写通知标题!');goBack();</script>");}	
			if (!$send_content){exit ("<script>alert('请填写通知内容!');goBack();</script>");}	
		}
	
		//获取添加会员
		$where=" 1=1 ";
		if($groupid){$where.=" and groupid in ({$groupid})";}
		elseif($userid)	{$where.=" and userid in ({$userid})";}
		
		$query="select username,userid,email,mobile_code,mobile from member  where {$where}";
		$sql=$xingao->query($query);
		while($rs=$sql->fetch_array())
		{ 
			$rsuserid=$rs['userid'];$rsusername=$rs['username'];
			if($rs['email']){$rsemail=$rs['email'];}else{$rsemail='';}
			if($rs['mobile']){$rsmobile=SMSApiType($rs['mobile_code'],$rs['mobile']);}else{$rsmobile='';}
			
			//添加优惠券/折扣券
			create_coupons($duetime,$content,$types,$value,$limitmoney,$usetypes,$code_number,$code_digits,$number,$rsuserid,$rsusername,$getSource=2);
	
			//发站内信息
			if($send_msg&&$rsuserid)
			{
				$from_userid=$Xuserid;
				$from_username=$Xusername;
				SendMsg($rsuserid,$rsusername,add($send_title),html($send_content),'',$from_userid,$from_username,$new=1,$status=0,$issys=0,$xs=0);
			}

			//发邮件
			if($send_mail&&$rsemail)
			{
				SendMail($rsemail,$send_title,$send_content,'',$issys=0,$xs=0,'',$notify=1);//$notify=1有批量发送或内容不重要,用异步发送
			}
			
			//发短信
			if($send_sms&&$rsmobile)
			{
				SendSMS($rsmobile,$send_content,$xs=0);
			}
			
			//微信:此类信息为营销类,微信平台禁止推送
		}
		$rc=mysqli_affected_rows($xingao);
		if($rc)
		{
			$token->drop_token("coupons");
			exit("<script>alert('共添加给了{$rc}个会员,每位会员获取了{$code_number}个(共".$code_number*$number."张)');location='list.php';</script>");
		}else{
			exit("<script>alert('没有找到会员,请检查该组是否有会员或所填写会员ID是否正确');goBack();</script>");
		}
		
		//添加给会员-结束------------------------------------
		
	}else{
		
		//不添加给会员
		create_coupons($duetime,$content,$types,$value,$limitmoney,$usetypes,$code_number,$code_digits,$number);
		$token->drop_token("coupons");
		exit("<script>alert('{$LG['pptAddSucceed']}共添加了{$code_number}张');location='list.php';</script>");
	}
	
		
//修改可使用类型=====================================================
}elseif($lx=='usetypes'){
	$usetypes=spr($_POST['usetypes']);
	if(!CheckEmpty($usetypes)){exit ("<script>alert('请选择可使用类型！');goBack();</script>");}

	$xingao->query("update coupons set usetypes='{$usetypes}' where cpid in ({$cpid})");
	SQLError('转移');
	
//修改状态=====================================================
}elseif($lx=='status'){
	if($status!=10){exit ("<script>alert('状态参数{$status}错误！');goBack();</script>");}

	$xingao->query("update coupons set status='".spr($status)."' where cpid in ({$cpid})");
	SQLError('状态');
	
//删除=====================================================
}elseif($lx=='del'){
	if($cpid){
		$where=" and cpid in ({$cpid})";
	}else{
		$date=par($_POST['date']);
		if(!CheckEmpty($date)){exit ("<script>alert('请填写要删除多少月之前 (填0则删除全部)！');goBack();</script>");}
		$start =strtotime('-'.$date.' Month');
		$where.=" and addtime<".$start;
		
		if(CheckEmpty($status)){$where.=" and status=".$status;}
	}

	//开启“长期保存记录”时禁止删除的状态
	if($off_delbak)
	{
		$delbak_status="and status<>'1'";
		$delbak_ts='\\n已开启“长期保存记录”功能，如果是正常记录则不能删除';
	}
	
	$xingao->query("delete from coupons where (userid='0' or status>0) {$delbak_status} {$where}");
	$rc=mysqli_affected_rows($xingao);
	if($rc>0){
		exit("<script>alert('{$LG['pptDelSucceed']}{$rc}');location='{$prevurl}';</script>");
	}else{
		exit ("<script>alert('没有删除的信息：\\n不能删除已分配且未使用信息,如要删除请先设为失效！{$delbak_ts});goBack();</script>");
	}
}

if(mysqli_affected_rows($xingao)<=0){
	exit ("<script>alert('{$LG['pptEditEmpty']}');goBack();</script>");
}else{
	exit("<script>location='{$prevurl}';</script>");
}
?>