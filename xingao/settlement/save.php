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
$pervar='settlement_ed';require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');

//获取=====================================================
$lx=par($_REQUEST['lx']);
$fromtable=par($_REQUEST['fromtable']);
$save_lx=par($_REQUEST['save_lx']);
$save_lx_alone=par($_REQUEST['save_lx_alone']);
$userid=par($_REQUEST['userid']);

$search.="&lx={$lx}&fromtable={$fromtable}&userid={$userid}&save_lx_alone={$save_lx_alone}";

//验证
if(!CheckEmpty($_GET['so'])){exit ("<script>alert('请先搜索！');goBack('c');</script>");}

if($_GET['tally']!=1){exit ("<script>alert('月结状态 必须选择“".striptags(Tally(1))."”，请重选再搜索！');goBack('c');</script>");}

if(!$_GET['stime']){exit ("<script>alert('时间-起 必须选择，请重选再搜索！');goBack('c');</script>");}
if(!$_GET['etime']){exit ("<script>alert('时间-止 必须选择，请重选再搜索！');goBack('c');</script>");}
if(DateDiff(date("Y-m-d",time()),$_GET['etime'],'d')<=0){exit ("<script>alert('时间-止 不能是今天或以后，请重选再搜索！');goBack('c');</script>");}

if(!$save_lx&&!$save_lx_alone){exit ("<script>alert('请选择生成条件！');goBack('c');</script>");}

//保存账单=====================================================
if($lx=='save')
{
	//运单账单处理------------------------------------------------------------------
	if($fromtable=='yundan')
	{
		//生成JONS账单
		$bill='{"classid":"'.spr($_GET['classid']).'","lotno":"'.striptags($_GET['lotno']).'","sf_name":"'.striptags($_GET['sf_name']).'","tally":"'.spr($_GET['tally']).'","stime":"'.par($_GET['stime']).'","etime":"'.par($_GET['etime']).'"}'; 
		
		//总计费用
		$_GET['so']=1;$op2=1;
		require($_SERVER['DOCUMENT_ROOT'].'/xingao/settlement/call/list_yundan_where.php');//条件处理,返回{$select} {$where} {$group} {$order}
		
		$query="select {$select} from yundan where {$where} {$Xwh} {$group} {$order}";
		$sql=$xingao->query($query);
		while($rs=$sql->fetch_array())
		{
			$ok=0;
			//查询验证
			$mr=FeData('member','settlement_yundan_bill,settlement_yundan_money',"userid='{$rs['userid']}'");
			
			if($save_lx==1&&!$mr['settlement_yundan_bill']){$ok=1;}
			elseif($save_lx==2&&$mr['settlement_yundan_bill']){$ok=1;}
			elseif($save_lx==3){$ok=1;}
			elseif($save_lx_alone)//单个会员生成时
			{
				if($mr['settlement_yundan_bill'])
				{
					exit ("<script>if(confirm('该会员已有账单未销账,确定重新生成吗?\\n将会覆盖之前的账单！'))location='?{$search}&save_lx=3';</script>");
				}
				$ok=1;
			}


			//处理保存
			if($ok)
			{
				require($_SERVER['DOCUMENT_ROOT'].'/xingao/settlement/call/list_yundan_total.php');//总计
				$settlement_money=SettlementCharge($fromtable='yundan',$rs['ydid']);
				$settlement_money=-$settlement_money;//转负数(如果原是负数,表示是要退费,会自动转正数)
			
				//保存
				$xingao->query("update member set settlement_yundan_bill='".add($bill)."',settlement_yundan_money='".spr($settlement_money)."' where userid='{$rs[userid]}'");
				SQLError('保存member yundan');
				if(mysqli_affected_rows($xingao)>0)
				{
					$rc+=1;

					//发通知－开始
					$send_msg=$settlement_msg;$send_mail=$settlement_mail;$send_sms=$settlement_sms;$send_wx=$settlement_wx;
					if($send_msg||$send_mail||$send_sms||$send_wx)
					{
						$MLG=memberLT($rs['userid']);
						
						//获取发送通知内容
						$NoticeTemplate='xingao_settlement_save_yundan';	
						require($_SERVER['DOCUMENT_ROOT'].'/public/NoticeTemplate.php');
						
						//发站内信息
						if($send_msg){SendMsg($rs['userid'],$rs['username'],$send_title,$send_content_msg,$send_file,$from_userid='',$from_username='',$new=1,$status=0,$issys=1,$xs=0);}
						//发邮件
						if($send_mail&&$rs['email']){SendMail($rs['email'],$send_title,$send_content_mail,$send_file,$issys=1,$xs=0,$send_userid);}
						//发短信
						if($send_sms&&$rs['mobile']){SendSMS(SMSApiType($rs['mobile_code'],$rs['mobile']),$send_content_sms,$xs=0,$send_userid);}
						//发微信
						if($send_wx){SendWX($send_WxTemId,$send_WxTemName,$send_content_wx,$rs['userid']);}
					}
					//发通知－结束
					
				}//if(mysqli_affected_rows($xingao)>0)
				
				
				
				
			}
		}//while($rs=$sql->fetch_array())

	}
	
	
	
	
	
	
	
	
	
	//其他账单处理------------------------------------------------------------------
	elseif($fromtable=='other')
	{
		//生成JONS账单
		$bill='{"tally":"'.$_GET['tally'].'","stime":"'.$_GET['stime'].'","etime":"'.$_GET['etime'].'"}'; 
		
		//总计费用
		$_GET['so']=1;$op2=1;
		require($_SERVER['DOCUMENT_ROOT'].'/xingao/settlement/call/list_other_where.php');//条件处理,返回{$select} {$where} {$group} {$order}
		
		$query="select {$select} from money_kfbak where {$where} {$group} {$order}";
		$sql=$xingao->query($query);
		while($rs=$sql->fetch_array())
		{
			$ok=0;
			//查询验证
			$mr=FeData('member','settlement_other_bill,settlement_other_money',"userid='{$rs['userid']}'");
			
			if($save_lx==1&&!$mr['settlement_other_bill']){$ok=1;}
			elseif($save_lx==2&&$mr['settlement_other_bill']){$ok=1;}
			elseif($save_lx==3){$ok=1;}
			elseif($save_lx_alone)//单个会员生成时
			{
				if($mr['settlement_other_bill'])
				{
					exit ("<script>if(confirm('该会员已有账单未销账,确定重新生成吗?\\n将会覆盖之前的账单！'))location='?{$search}&save_lx=3';</script>");
				}
				$ok=1;
			}
			
			//处理保存
			if($ok)
			{
				$sr=FeData('money_kfbak',"count(*) as total,sum(`fromMoney`) as fromMoney","{$where} and userid='{$rs[userid]}'");//总计
				$settlement_money=$sr['fromMoney'];
				$settlement_money=-$settlement_money;//转负数(如果原是负数,表示是要退费,会自动转正数)
				
				//保存
				$xingao->query("update member set settlement_other_bill='".add($bill)."',settlement_other_money='".spr($settlement_money)."' where userid='{$rs[userid]}'");
				SQLError('保存member other');
				if(mysqli_affected_rows($xingao)>0)
				{
					$rc+=1;

					//发通知－开始
					$send_msg=$settlement_msg;$send_mail=$settlement_mail;$send_sms=$settlement_sms;$send_wx=$settlement_wx;
					if($send_msg||$send_mail||$send_sms||$send_wx)
					{
						$MLG=memberLT($rs['userid']);
						
						//获取发送通知内容
						$NoticeTemplate='xingao_settlement_save_other';	
						require($_SERVER['DOCUMENT_ROOT'].'/public/NoticeTemplate.php');
						

						//发站内信息
						if($send_msg){SendMsg($rs['userid'],$rs['username'],$send_title,$send_content_msg,$send_file,$from_userid='',$from_username='',$new=1,$status=0,$issys=1,$xs=0);}
						//发邮件
						if($send_mail&&$rs['email']){SendMail($rs['email'],$send_title,$send_content_mail,$send_file,$issys=1,$xs=0,$send_userid);}
						//发短信
						if($send_sms&&$rs['mobile']){SendSMS(SMSApiType($rs['mobile_code'],$rs['mobile']),$send_content_sms,$xs=0,$send_userid);}
						//发微信
						if($send_wx){SendWX($send_WxTemId,$send_WxTemName,$send_content_wx,$rs['userid']);}
					}
					//发通知－结束
					
				}
				
				
			}
		}//while($rs=$sql->fetch_array())


	}
	
	if($rc>0){exit ("<script>alert('共生成{$rc}个账单！\\n返回刷新可看到最新结果');goBack('c');</script>");}
	else{exit ("<script>alert('没有新账单可生成！');goBack('c');</script>");}
}