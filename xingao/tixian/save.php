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
$pervar='tixian';//权限验证
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');


//获取,处理=====================================================
$lx=par($_REQUEST['lx']);
$txid=$_REQUEST['txid'];
$tokenkey=par($_POST['tokenkey']);
$status=par($_POST['status']);

if (is_array($txid)){$txid=implode(',',$txid);}
$txid=par($txid);

//添加,修改=====================================================
if($lx=='add'||$lx=='edit')
{
	//通用验证------------------------------------
	$token=new Form_token_Core();
	$token->is_token("tixian".$txid,$tokenkey); //验证令牌密钥
	
	if(!$status){exit ("<script>alert('请选择状态！');goBack();</script>");}

	
	//修改------------------------------------
	if($lx=='edit')
	{
		$rs=FeData('tixian','*',"txid={$txid}");
		$mr=FeData('member','money,money_lock,currency',"userid='{$rs[userid]}'");
		
		if($mr['currency']!=$rs['currency']){exit( "<script>alert('提现币种与账户币种不相同，无法处理！只能从数据库修复。');goBack();</script>");}
			
		
		
		//提现表保存字段
		$save=" status='{$status}' ";
		if($_POST['old_reply']!=$_POST['reply'])
		{
			$reply=html($_POST['reply']);
			$save.=",reply='{$reply}',replytime='".time()."'";
		}
		
		if($_POST['status']!=spr($rs['status']))
		{
			$save.=",withtime='".time()."' ";
		}
		
		//只修改回复*****************************************************
		if($status==1)
		{
			//更新提现表
			$xingao->query("update tixian set {$save} where txid='{$txid}'");				
			$rc=mysqli_affected_rows($xingao);
			if($rc>0)
			{
				$ts=$LG['pptEditSucceed'].'\\n如果想看到最新修改,请刷新列表';
			}else{
				$ts='未有任何修改!';
			}
			
			$token->drop_token("tixian".$txid); //处理完后删除密钥
			
			exit ("<script>alert('".$ts."');goBack('c');</script>");
		}


		//提现完成*****************************************************
		elseif($status==2)
		{
			if($mr['money_lock']<$rs['money']){exit( "<script>alert('冻结中金额错误,无法处理 (冻结只有".$mr['money_lock'].$mr['currency'].",而申请提现需要".$rs['money'].$rs['currency'].")！');goBack();</script>");}
			
			//更新会员表
			$xingao->query("update member set money_lock=money_lock-$rs[money] where userid='{$rs[userid]}'");
			$rc=mysqli_affected_rows($xingao);
			if($rc>0)
			{
				//更新提现表
				$xingao->query("update tixian set {$save} where txid='{$txid}'");	
				SQLError('更新提现表');
					
				//添加扣费记录
				$xingao->query("insert into money_kfbak(userid,username,fromtable,fromid,toMoney,toCurrency,title,content,addtime,type,remain,operator) values('{$rs[userid]}','{$rs[username]}','tixian','{$txid}','{$rs[money]}','{$rs[currency]}','','','".time()."','100','{$mr['money']}','{$Xuserid}');");
				SQLError('添加扣费记录');

				$ts=$LG['pptEditSucceed'].'\\n如果想看到最新修改,请刷新列表';
			}else{
				$ts=$LG['pptEditFailure'].'\\n更新会员表错误!';
			}
			
			$token->drop_token("tixian".$txid); //处理完后删除密钥
			
			exit ("<script>alert('".$ts."');goBack('c');</script>");
		}

		//拒绝提现*****************************************************
		elseif($status==3)
		{
			$money=spr($mr['money']+$rs['money']);
			$money_lock=RepPIntvar(spr($mr['money_lock']-$rs['money']));//负数时转0
				
			//更新会员表
			$xingao->query("update member set money={$money},money_lock={$money_lock} where userid='{$rs[userid]}'");
			$rc=mysqli_affected_rows($xingao);
			if($rc>0)
			{
				//更新提现表
				$xingao->query("update tixian set {$save} where txid='{$txid}'");				
				
				$ts=$LG['pptEditSucceed'].'\\n如果想看到最新修改,请刷新列表';
			}else{
				$ts=$LG['pptEditFailure'].'\\n更新会员表错误!';
			}
			
			$token->drop_token("tixian".$txid); //处理完后删除密钥
			
			exit ("<script>alert('".$ts."');goBack('c');</script>");
		}


	}//if($lx=='edit')
}
//删除在列表页,按搜索结果删除=====================================================
?>