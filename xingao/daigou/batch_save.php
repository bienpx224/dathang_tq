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
$pervar='daigou_ed';require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');
if(!$ON_daigou){ exit("<script>alert('{$LG['daigou.45']}');goBack('uc');</script>"); }

set_time_limit(0);//批量处理时,速度慢,设为永不超时
//显示表单获取,处理
$lx=par($_POST['lx']);

//查询数量------------------------------------------------------------------------------------------------
if($lx=='num')
{
	//获取及验证修改条件---------------------------------
	require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/daigou/call/where_save.php');//输出:$where
	$num=mysqli_num_rows($xingao->query("select dgid from daigou where 1=1 {$where}"));
	exit ('<div align="center" style="color:#FF0000">按选择条件,共有'.$num.'个代购单</div>');
}	


//修改-开始------------------------------------------------------------------------------------------------
if($lx=='tj')
{
	//获取及验证修改条件---------------------------------
	require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/daigou/call/where_save.php');//输出:$where


	//获取及验证修改内容---------------------------------
	$status=par($_POST['status']);
	$options1=par($_POST['options1']);
	$options2=par($_POST['options2']);
	$options3=par($_POST['options3']);
	$manageStatus=par($_POST['manageStatus']);
	$warehouse=par($_POST['warehouse']);
	$memberContentReply=html($_POST['memberContentReply']);
	$memberContentReply_lx=par($_POST['memberContentReply_lx']);
	$sellerContentReply=html($_POST['sellerContentReply']);
	$sellerContentReply_lx=par($_POST['sellerContentReply_lx']);
	
	
	
	
	
	
	//批量修改-开始---------------------------------------
	$save='addtime=addtime';
	if (CheckEmpty($warehouse)){$save.=",warehouse='{$warehouse}'";}
	if (CheckEmpty($memberContentReply))
	{
		if($memberContentReply_lx){$save.=",memberContentReply=concat('{$memberContentReply}； 
		',memberContentReply)";}else{$save.=",memberContentReply='{$memberContentReply}'";}
		$save.=",memberContentReplyNew=1,memberContentReplyTime='".time()."'";
	}
	if (CheckEmpty($sellerContentReply))
	{
		if($sellerContentReply_lx){$save.=",sellerContentReply=concat('{$sellerContentReply}； 
		',sellerContentReply)";}else{$save.=",sellerContentReply='{$sellerContentReply}'";}
		$save.=",sellerContentReplyNew=1,sellerContentReplyTime='".time()."'";
	}

	$xingao->query("update daigou set {$save} where 1=1 {$where} ");SQLError('修改');
	$rc=mysqli_affected_rows($xingao);
	//批量修改-结束---------------------------------------
	
	
	
	
	//单个修改:处理会员申请---------------------------------------
	if(CheckEmpty($manageStatus))
	{
		if(CheckEmpty($wh_memberStatus)){$where_gd=" and memberStatus='{$wh_memberStatus}' ";}

		
		$i=0;$err_i=0;
		$query="select * from daigou where 1=1 {$where}";
		$sql=$xingao->query($query);
		while($rs=$sql->fetch_array())
		{
			$query_gd="select * from daigou_goods where number>0 and dgid='{$rs['dgid']}' {$where_gd}";
			$sql_gd=$xingao->query($query_gd);
			while($gd=$sql_gd->fetch_array())
			{
				$err=0;
				if(!$err&&($gd['manageStatus']==$manageStatus||$gd['memberStatus']==0||spr($rs['status'])==10)){$err=1;}
				
				if(!$err)
				{
					//无法处理时自动退费-开始
					$logContent_refund='';
					if(have($manageStatus,'2,4'))
					{
						$r = daigou_memberStatus_refund($rs['dgid'],"goid='{$gd['goid']}'");
						$logContent_refund=$r['logContent'];
						$ppt='\\n如之前申请服务时有扣费,已自动退回';
					}
					//无法处理时自动退费-结束
					
					$save="manageStatus='{$manageStatus}'";
					//非'处理中'时,就更新申请状态
					if($manageStatus!=1){$save.=",memberLastStatus=memberStatus,memberStatus=0,manageStatusTime='".time()."'";}
					$xingao->query("update daigou_goods set {$save} where goid='{$gd['goid']}'");SQLError('修改商品表');
					
					//添加日志
					$logContent="商品单号{$gd['godh']}:".'$LG[daigou.memberStatus'.$gd['memberStatus'].'] → $LG[daigou.manageStatus'.$manageStatus.']'.$logContent_refund;
					opLog('daigou',$rs['dgid'],1,$logContent,1);
					$i+=1;
				}else{
					$err_i++;
				}
			}
		}
		$ts.='\\n共处理'.$i.'个会员申请'.$ppt;
		if($err_i){$ts.='(另有'.$err_i.'个商品没符合更新要求)';}
	}
	
	
	
	//单个修改:修改状态---------------------------------------
	if(CheckEmpty($status))
	{
		$i=0;$err_i=0;
		$query="select * from daigou where 1=1 {$where} ";
		$sql=$xingao->query($query);
		while($rs=$sql->fetch_array())
		{
			$err=0;
			if(!$err&&$options1&&spr($rs['status'])>=$status){$err=1;}
			if(!$err&&$options2&&spr($rs['status'])==2){$err=1;}
			if(!$err&&$options3&&$rs['memberStatus']){$err=1;}
			
			if(!$err)
			{
				daigou_upStatus($rs,$status,$exceed=1,$send=1,$pop=0,$callFrom='manage',$notify=1);
				$i+=1;
			}else{
				$err_i++;
			}
		}
		$ts.='\\n共更新'.$i.'个代购单状态';
		if($err_i){$ts.='(另有'.$err_i.'单没符合更新要求)';}
	}
	
	
	
	
	
	
	if($rc>0){$ts='共修改'.$rc.'个代购单！'.$ts;}
	if($ts)
	{
		exit("<script>alert('".$ts."');goBack('c');</script>");
	}else{
		exit("<script>alert('没有符合的代购单或没选择要修改的内容！');goBack('c');</script>");
	}
}

exit("<script>alert('无效操作！');goBack('c');</script>");
//修改-结束------------------------------------------------------------------------------------------------
?>