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
$pervar='daigou';require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/session.php');
$alonepage=1;require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/head.php');
if(!$ON_daigou){ exit("<script>alert('{$LG['daigou.45']}');goBack('uc');</script>"); }

//获取,处理-----------------------------------------------------------------------------------------------
$typ=par($_REQUEST['typ']);
$field=par($_POST['field']);
$value=add($_POST['value']);
$dgid=par(ToStr($_POST['dgid']));
$goid=par(ToStr($_POST['goid']));
//操作处理=====================================================
if($typ=='op')
{
	$XAalert=1;//显示提示方式
	
	//验证
	if(!$field){exit("<script>alert('field{$LG['pptEmpty']}');goBack();</script>");}
	if(!$dgid){exit("<script>alert('dgid{$LG['pptEmpty']}');goBack();</script>");}
	if(!$goid){exit("<script>alert('{$LG['daigou.72_3']}');goBack();</script>");}

	//通用保存
	$where_dg="dgid in ({$dgid}) {$Mmy}";
	$where="goid in ({$goid}) and number>0 and manageStatus<>'1' {$Mmy}";
	$save="manageStatus=0,manageStatusTime=0";
	$memberStatusRequ=add($_POST['memberStatusRequ']);
	if($memberStatusRequ){$save.=",memberStatusRequ='{$memberStatusRequ}'";}
	
	
	if($field=='memberStatus')
	{ 
		//注意:此部分不支持多dgid
		
		$value=spr($value);
		//取消申请-----------------------------------------
		if($value==0)
		{
			$chk=1;
			$where.=" and memberStatus<>'0'";
			$save.=",{$field}='{$value}',memberLastStatus=0,memberStatusTime=0";
		}
		
		
		//下面通用保存
		$save.=",memberStatusTime=".time();
		
		//申请查货-----------------------------------------
		if($value==1)
		{
			$chk=1;
			$where_dg.=" and status in (3,5,6,7)";
			$save.=",{$field}='{$value}'";
		}
		//申请换货-----------------------------------------
		elseif($value==2)
		{
			$chk=1;
			$where_dg.=" and status in (3,4,5,6,7,8,9)";
			$save.=",{$field}='{$value}'";
		}
		//申请增购数量-----------------------------------------
		elseif($value==3)
		{
			$chk=1;
			$where_dg.=" and status in (3,4,5)";
			$save.=",{$field}='{$value}'";
		}
		//申请退货退款-----------------------------------------
		elseif($value==4)
		{
			$chk=1;
			$where_dg.=" and status in (3,4,5,6,7,8,9)";
			$save.=",{$field}='{$value}'";
		}
	}
	
	
	
	
	
	
	
	//保存修改
	if($chk)
	{
		
		//保存
		$query="select dgid from daigou where {$where_dg}";
		$sql=$xingao->query($query);
		while($rs=$sql->fetch_array())
		{
			//申请服务时通用自动扣费-开始---------------------------------
			if($value>0)
			{
				$serviceFee=$member_per[$Mgroupid]['dg_serviceFee_'.$value];
				if($serviceFee)
				{
					//查询申请多少种商品
					$godh_all='';
					$query_gd="select godh from daigou_goods where {$where} and dgid='{$rs['dgid']}' and memberStatus<>'{$value}'";
					$sql_gd=$xingao->query($query_gd);
					while($gd=$sql_gd->fetch_array())
					{
						$godh_all.=cadd($gd['godh']).',';
					}
					
					$godh_all=DelStr($godh_all);
					if($godh_all)
					{
						$num=arrcount($godh_all);
						$pay_money=$serviceFee*$num;//总费
						
						//查询费用是否够支付
						$mr=FeData('member','money,currency',"userid='{$Muserid}'");
						$money=$mr['money']*daigou_exchangeBase(exchange($mr['currency'],$XAMcurrency),$Muserid);
						if($money>=$pay_money)
						{
							//扣费
							MoneyKF($Muserid,$fromtable='daigou',$fromid=$rs['dgid'],$fromMoney=$pay_money,$fromCurrency='',$title=$rs['dgdh'],$godh_all,$type=3);
							
							$ppt="{$LG['baoguo.fx_save_5']}<strong>".$pay_money.$XAmc.'</strong>';
							$save_pay=",memberStatus_pay='-{$serviceFee}'";//主币
							$showTime=3000;
							$logContent="({$ppt},商品:{$godh_all})";
						}else{
							$err=1;
							$differ=spr($money-$pay_money*daigou_exchangeBase(exchange($XAMcurrency,$mr['currency']),$Muserid));
							$ppt=$LG['pptNotPayService'].'<font class="red">'.$LG['pptWorse'].$differ.$mr['currency'].'</font>';
							$showTime=3000;
							goto a;
						}
					}
				}
			}
			//申请服务时通用自动扣费-结束
			
			
			
			
			//取消服务时通用自动退费-开始---------------------------------
			else{
				$r = daigou_memberStatus_refund($rs['dgid'],$where);
				$ppt=$r['ppt'];
				$showTime=$r['showTime'];
				$logContent=$r['logContent'];
			}
			//取消服务时通用自动退费-结束
			
			
			
			
			//正式保存---------------------------------
			$xingao->query("update daigou_goods set {$save}{$save_pay} where {$where} and dgid='{$rs['dgid']}' "); 
			SQLError('更新daigou_goods');
			$rc+=mysqli_affected_rows($xingao);
		}
		if($rc)
		{
			opLog('daigou',$rs['dgid'],1,daigou_memberStatus($value).$logContent,0);//添加日志
		}
		
	}
	

	//操作完后提示
	if($rc){
		$ppt=LGtag($LG['daigou.80_1'],'<tag1>=='.$rc).$ppt;
	}else{
		$ppt=$LG['pptEditNo'].$ppt;
	}
	




//修改各种备注=====================================================
}elseif($typ=='content'){
	$chk=0;	$XAalert=1;//显示提示方式
	
	//更新和条件
	$where="dgid in ({$dgid}) {$Mmy}";
	if($field=='content'){$chk=1;$save="{$field}='{$value}'";}
	if($field=='memberContent')
	{
		$chk=1;$save="{$field}='{$value}',memberContentReplyNew=0";
		$content=FeData('daigou',$field,$where);
		if($content!=$value){$save.=",memberContentNew=1,memberContentTime=".time();}
	}

	//保存
	if($chk){
		$xingao->query("update daigou set {$save} where {$where}");SQLError('更新daigou');
		if(mysqli_affected_rows($xingao)>0){$ppt=$LG['daigou.81'];}else{$err=1;$ppt=$LG['pptEditNo'];}
	}else{
		$err=1;$ppt=$LG['pptError'];
	}
	
	
	
	
	
	
//留言最新状态设置=====================================================
}elseif($typ=='New'){
	//获取处理
	$field=par($_GET['field']);
	$value=par($_GET['value']);
	$dgid=par(ToStr($_REQUEST['dgid']));
	$chk=0;	$XAalert=1;//显示提示方式
	
	//更新和条件
	$where="dgid in ({$dgid}) {$Mmy}";
	if($field=='memberContentReplyNew'){$chk=1;$save="memberContentReplyNew='".spr($value)."'";}
	if($field=='sellerContentReplyNew'){$chk=1;$save="sellerContentReplyNew='".spr($value)."'";}
	
	//保存
	if($chk){
		$xingao->query("update daigou set {$save} where {$where}");SQLError('更新daigou');
		if(mysqli_affected_rows($xingao)>0){$ppt=$LG['daigou.81'];}else{$err=1;$ppt=$LG['pptEditNo'];}
	}else{
		$err=1;$ppt=$LG['pptError'];
	}
	
}







//通用提示=====================================================
a:
if($XAalert)
{
	$pptTyp='success';	if($err){$pptTyp='warning';}
	XAalert($ppt,$pptTyp);
}else{
	echo "<strong>{$ppt}</strong>";
}


if(!$showTime){$showTime=1000;}
echo "<script>setTimeout(\"goBack('op.php?dgid={$dgid}')\",'{$showTime}');</script>"
?>
<style>
html{overflow-x:hidden;}
body{margin:0px;}
.alert{margin:0px;}
</style>