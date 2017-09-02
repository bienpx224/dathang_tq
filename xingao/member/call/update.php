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
require_once($_SERVER['DOCUMENT_ROOT'].'/public/function.php');


//时更新
if(update_time('member_data','-1 hours'))//多久更新一次:1 week 3 days 7 hours 30 minutes 5 seconds
{
	//更新-----------------------------------------------------------------------------------
	$updatetime=time();
	
	$query="select userid,username,updatetime from member ";
	$sql=$xingao->query($query);
	while($rs=$sql->fetch_array())
	{
		$save="updatetime='{$updatetime}'";
		
		//统计全部=====================================
		$where="userid='{$rs[userid]}' and addtime>='{$rs[updatetime]}'";

		$yd=FeData('yundan',"count(*) as total,sum(`weight`) as weight",$where." and status>4");
		$kf=FeData('money_kfbak',"count(*) as total,sum(`fromMoney`) as fromMoney",$where);
		
		$total=abs(spr($yd['total']));
		$weight=abs(spr($yd['weight']));
		$xiaofei=abs(spr($kf['fromMoney']));
		
		if($total){$save.=",yundan_tsl=yundan_tsl+{$total}";}//全部转运单量
		if($weight){$save.=",yundan_tzl=yundan_tzl+{$weight}";}//全部转运重量
		if($xiaofei){$save.=",xiaofei_t=xiaofei_t+{$xiaofei}";}//全部消费
		
		//统计本月=====================================
		$where="userid='{$rs[userid]}' and addtime>='".strtotime(date('Y-m'))."'";

		$yd=FeData('yundan',"count(*) as total,sum(`weight`) as weight",$where." and status>4");
		$kf=FeData('money_kfbak',"count(*) as total,sum(`fromMoney`) as fromMoney",$where);
		
		$total=abs(spr($yd['total']));
		$weight=abs(spr($yd['weight']));
		$xiaofei=abs(spr($kf['fromMoney']));
		
		$save.=",yundan_msl={$total}";//全部转运单量
		$save.=",yundan_mzl={$weight}";//全部转运重量
		$save.=",xiaofei_m={$xiaofei}";//全部消费
		
		//更新=====================================
		$xingao->query("update member set {$save} where userid='{$rs[userid]}'");SQLError();
	}//while($rs=$sql->fetch_array())
	
}











//天更新
if (update_time('member_birthday','-1 days'))//多久更新一次:1 week 3 days 7 hours 30 minutes 5 seconds
{
	//生日送分-开始
	$integral_MemberBirthday=spr($integral_MemberBirthday);

	$send_msg=$member_birthday_msg;
	$send_mail=$member_birthday_mail;	
	$send_sms=$member_birthday_sms;
	$send_wx=$member_birthday_wx;
	$ON_member_birthday_send=0;if($send_msg||$send_mail||$send_sms||$send_wx){$ON_member_birthday_send=1;}

	if($integral_MemberBirthday>0||$ON_member_birthday_send)
	{
		
		$where="1=1";
		$where.=" and birthday>0";//有填写生日的
		$where.=" and birthday_integral_time<'".strtotime(date('Y').'-1-1')."'";//今年未送过的
		
		$query="select userid,username,birthday,birthday_integral_time from member where {$where}";
		$sql=$xingao->query($query);
		while($rs=$sql->fetch_array())
		{
			//查询生日
			if( DateYmd($rs['birthday'],3)==DateYmd(time(),3) )
			{
				//送分---------------
				if($integral_MemberBirthday>0){integralCZ($rs['userid'],'','0',$integral_MemberBirthday,'生日送分','',100);}
				
				//发通知---------------
				if($ON_member_birthday_send)
				{
					$MLG=memberLT($se['userid']);
					if(!$send_content){$send_content=Label(TextareaToBr(memberLT($rs['userid'],'member_birthday_content'),1));}
					
					//获取发送通知内容
					$NoticeTemplate='xingao_member_birthday';	
					require($_SERVER['DOCUMENT_ROOT'].'/public/NoticeTemplate.php');
					$send_file='';
				
					$send_userid=$rs['userid'];
					$send_username=$rs['username'];
				
					//发站内信息
					if($send_msg){SendMsg($send_userid,$send_username,$send_title,$send_content_msg,$send_file,$from_userid='',$from_username='',$new=1,$status=0,$issys=1,$xs=0);}
					//发邮件
					if($send_mail){SendMail($rsemail='',$send_title,$send_content_mail,$send_file,$issys=1,$xs=0,$send_userid);}
					//发短信
					if($send_sms){SendSMS($rsmobile='',$send_content_sms,$xs=0,$send_userid);}
					//发微信:不支持
					//if($send_wx){SendWX($send_WxTemId,$send_WxTemName,$send_content_wx,$send_userid);}
				}


				//更新---------------
				$xingao->query("update member set birthday_integral_time='".time()."' where userid='{$rs[userid]}'");SQLError();
				
			}//if( DateYmd($rs['birthday'],3)==DateYmd(time(),3) )
			
		}//while($rs=$sql->fetch_array())
	}
	//生日送分-结束
	
}
?>