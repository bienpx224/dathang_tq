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
$alonepage=1;require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/head.php');
if(!$ON_daigou){ exit("<script>alert('{$LG['daigou.45']}');goBack('uc');</script>"); }
?>

<style>
html{overflow-x:hidden;}
body{margin:0px; color:#FF6E00}/*有错误时,红色提示文字*/
.alert{margin:0px;}
</style>


<?php 
//获取,处理-----------------------------------------------------------------------------------------------
$typ=par($_REQUEST['typ']);
$field=par($_POST['field']);
$value=par($_POST['value']);
$goid=spr($_POST['goid']);
$dgid=par(ToStr($_POST['dgid']));
$tokenkey=par($_POST['tokenkey']);


if($dgid){$rs=FeData('daigou','*',"dgid='{$dgid}' {$Xwh}");}
if($goid){$gd=FeData('daigou_goods','*',"dgid='{$rs['dgid']}' and goid='{$goid}'");}


//处理会员申请=====================================================
if($typ=='op')
{
	//通用验证------------------------------------
	$token=new Form_token_Core();
	$token->is_token("daigouOP{$dgid}",$tokenkey); //验证令牌密钥
	$XAalert=1;//显示提示方式
	
	//获取,处理,验证---
	if(!$dgid){exit("<script>alert('dgid{$LG['pptEmpty']}');goBack();</script>");}
	if(!$goid){exit("<script>alert('goid{$LG['pptEmpty']}');goBack();</script>");}
	if(!CheckEmpty($_POST['manageStatus'])){exit ("<script>alert('请选择处理状态！');goBack();</script>");}
	$manageStatus=spr($_POST['manageStatus']);


	//查询处理,验证---
	if(!$gd['memberStatus']){exit("<script>alert('无需处理！ (可能已处理过或会员取消了申请)');goBack();</script>");}
	if(!daigou_per_op($gd['memberStatus'])){exit("<script>alert('无操作权限！');goBack();</script>");}


	//通用保存-开始
	$save="dgid=dgid";
	$save_gd="manageStatus='{$manageStatus}'";
	$memberContentReply=add($_POST['memberContentReply']);
	if($memberContentReply!=$rs['memberContentReply'])
	{
		$save.=",memberContentReply='{$memberContentReply}',memberContentReplyNew='1',memberContentReplyTime='".time()."'";
	}
	//非'处理中'时,就更新申请状态
	if($manageStatus!=1){$save_gd.=",memberLastStatus=memberStatus,memberStatus=0,manageStatusTime='".time()."'";}
	//通用保存-结束
	
	
	//申请查货-----------------------------------------
	if($gd['memberStatus']==1)
	{
		$chk=1;
		//$where.=" and status in (3,5,6,7)";
	}
	
	//申请换货-----------------------------------------
	elseif($gd['memberStatus']==2)
	{
		$chk=1;
		$where.=" and status in (3,4,5,6,7,8,9)";
		
		//已处理:费用不变
		if($manageStatus==3)
		{
			$save_gd.=",inventoryNumber=inventoryNumber-'".spr($_POST['inventoryNumber'])."',color='".spr($_POST['color'])."',colorOther='".add($_POST['colorOther'])."',size='".spr($_POST['size'])."',sizeOther='".add($_POST['sizeOther'])."'";
		}
	}
	
	//申请增购数量-----------------------------------------
	elseif($gd['memberStatus']==3)
	{
		$chk=1;
		$where.=" and status in (3,4,5)";
		
		//已处理
		if($manageStatus==3)
		{
			if(!$_POST['addNumber']){exit ("<script>alert('请填写新增数量！');goBack();</script>");}
			if(have('6,7,8',spr($rs['status']),1))
			{
				if($_POST['procurementCost']<=0){exit ("<script>alert('请填写采购成本！');goBack();</script>");}
				$save.=",procurementCost=".spr($_POST['procurementCost']);
			}
				
			$number=spr($_POST['addNumber']+$gd['number']);
			$save_gd.=",number='{$number}'";
			//$ppt='<br>可进行支付/补款操作';

			
			$pay=1;//执行扣费/退费操作
			$refund=0;//可退费
			$per=1;//验证权限
			
			if($_POST['pay'])
			{
				$per=0;//立即扣费时:不验证权限,不验证仓库,不验证自动扣费额度
				//$ppt='';
			}
			$logContent="(增购数量{$_POST['addNumber']})";
		}
			
		
	}
	
	//申请退货退款-----------------------------------------
	elseif($gd['memberStatus']==4)
	{
		$chk=1;
		$where.=" and status in (6,7,8,9)";
		
		//已处理
		if($manageStatus==3)
		{
			$numberRet=spr($_POST['numberRet']);
			if(!$numberRet){exit ("<script>alert('请填写退货数量！');goBack();</script>");}
			if(have('6,7,8',spr($rs['status']),1))
			{
				if($_POST['procurementCost']<=0){exit ("<script>alert('请填写采购成本！');goBack();</script>");}
				$save.=",procurementCost=".spr($_POST['procurementCost']);
			}
				
			
			$number=spr($gd['number']-$numberRet);
			if($number<0){exit ("<script>alert('所填退货数量已超过现货数量！');goBack();</script>");}
			//退货处理:已含保存退款相关数据
			$ppt='<br>'.daigou_numberRet($gd['goid'],$numberRet);
			$logContent="(退货数量{$numberRet})";
		}
	}

	
	
	//正式处理-----------------------------------------
	if($chk)
	{
		//无法处理时自动退费-开始
		if(have($manageStatus,'2,4'))
		{
			$r = daigou_memberStatus_refund($rs['dgid'],"goid='{$gd['goid']}'");
			$ppt.=$r['ppt'];
			$showTime=$r['showTime'];
			$logContent.=$r['logContent'];
		}
		//无法处理时自动退费-结束

		$xingao->query("update daigou set {$save} where dgid='{$rs['dgid']}'"); SQLError('更新daigou');
		$xingao->query("update daigou_goods set {$save_gd} where goid='{$gd['goid']}'"); SQLError('更新daigou_goods');
		
		//更新商品goodsFee总价,代购单支付情况
		daigou_Update($rs['dgid']);
		
		$rc=mysqli_affected_rows($xingao);
		if($rc>0)
		{
			//添加日志
			$logContent="商品单号{$gd['godh']}:".'$LG[daigou.memberStatus'.$gd['memberStatus'].'] → $LG[daigou.manageStatus'.$manageStatus.']'.$logContent;
			opLog('daigou',$rs['dgid'],1,$logContent,1);

			//扣费:需要先保存再重新读取,因此放最后
			if($pay){daigou_PayRef($rs['dgid'],$refund);}//扣费/退费处理
		}
	}
	

	//操作完后提示
	if($rc>0){
		$ppt=$LG['daigou.81'].$ppt;
	}else{
		$ppt='无任何修改:'.$ppt;
	}
	$token->drop_token("daigouOP{$dgid}"); //处理完后删除密钥

}

























//采购处理=====================================================
elseif($typ=='procurement'){
	
	//通用验证------------------------------------
	$token=new Form_token_Core();
	$token->is_token("daigouOP{$dgid}",$tokenkey); //验证令牌密钥
	
	$XAalert=1;//显示提示方式
	permissions('daigou_cg',1,'manage','');//验证权限
	
	//查询处理,验证---
	if(!spr($rs['status'])==10){exit("<script>alert('此单为【".daigou_Status(10)."】,不能再操作,如要操作请先修改为其他状态');goBack();</script>");}
	if(!$rs['pay']){exit("<script>alert('此单未支付,不能操作');goBack();</script>");}




	//通用保存-开始
	$save="dgid=dgid";
	$save_gd="goid=goid";
	$memberContentReply=add($_POST['memberContentReply']);
	if($memberContentReply!=$rs['memberContentReply'])
	{
		$save.=",memberContentReply='{$memberContentReply}',memberContentReplyNew='1',memberContentReplyTime='".time()."'";
	}
	if($_POST['read']){$save.=",memberContentNew='0'";}
	//通用保存-结束
	
	
	//采购-----------------------------------------
	if($value==1)
	{
		$chk=1;
		$where.=" and status in (3,5,6)";
		$save.=",procurementCost='".spr($_POST['procurementCost'])."',procurementTime='".spr($_POST['procurementTime'])."',procurementAddress='".add($_POST['procurementAddress'])."'";
		$status=6;//更新的主状态
		$exceed=0;//现状态超过时是否更新
		$logContent='采购';//日志内容
	}
	
	//补款/退款---------------------------------------------
	elseif($value==2)
	{
		if(!$goid){exit("<script>alert('goid{$LG['pptEmpty']}');goBack();</script>");}
		if($_POST['priceCurrency']!=$rs['priceCurrency']&&$rs['pay']){exit ("<script>alert('该单已经支付过,无法再变更币种！');goBack();</script>");}
		if($_POST['price']<=0){exit ("<script>alert('请输入正确新单价！');goBack();</script>");}
		if(have('6,7,8',spr($rs['status']),1))
		{
			if($_POST['procurementCost']<=0){exit ("<script>alert('请填写采购成本！');goBack();</script>");}
			$save.=",procurementCost=".spr($_POST['procurementCost']);
		}

		$chk=1;
		$where.=" and status in (0,1,2,3,4,5)";

		//变更处理
		if(spr($_POST['price'])!=spr($gd['price'])||$_POST['priceCurrency']!=$rs['priceCurrency'])
		{
			$save.=",priceCurrency='".add($_POST['priceCurrency'])."'";
			$save_gd.=",price='".spr($_POST['price'])."'";

			
			$pay=1;//执行扣费/退费操作
			$refund=1;//可退费
			$per=1;//验证权限
			$logContent="需要补款/退款(单价从".spr($gd['price']).$rs['priceCurrency']."变更到{$_POST['price']}{$_POST['priceCurrency']})";//日志内容
		}
			
		if($_POST['pay'])
		{
			$pay=1;//执行扣费/退费操作
			$refund=1;//可退费
			$per=0;//立即扣费时:不验证权限,不验证仓库,不验证自动扣费额度
			$logContent="执行补款/退款";//日志内容
		}
		
	}

	//断货退款-----------------------------------------
	elseif($value==3)
	{
		if(!$goid){exit("<script>alert('goid{$LG['pptEmpty']}');goBack();</script>");}
		$chk=1;
		$where.=" and status in (0,1,2,3,4,5,6)";
		
		$numberRet=spr($_POST['numberRet']);
		if(!$numberRet){exit ("<script>alert('请填写退货数量！');goBack();</script>");}
		
		$number=spr($gd['number']-$numberRet);
		if($number<0){exit ("<script>alert('所填退货数量已超过现货数量！');goBack();</script>");}

		if(have('6,7,8',spr($rs['status']),1))
		{
			if($_POST['procurementCost']<=0){exit ("<script>alert('请填写采购成本！');goBack();</script>");}
			$save.=",procurementCost=".spr($_POST['procurementCost']);
		}

		
		//退货处理:已含保存退款相关数据
		$ppt='<br>'.daigou_numberRet($gd['goid'],$numberRet);
		$logContent="断货退款(断货数量{$numberRet})";//日志内容
	}





	
	//正式处理-----------------------------------------
	if($chk)
	{
		$xingao->query("update daigou set {$save} where dgid='{$rs['dgid']}'"); SQLError('更新daigou');
		$xingao->query("update daigou_goods set {$save_gd} where goid='{$gd['goid']}'"); SQLError('更新daigou_goods');
		
		//更新商品goodsFee总价,代购单支付情况
		daigou_Update($rs['dgid']);
		
		$rc=mysqli_affected_rows($xingao);
		if($rc>0)
		{
			//添加日志
			opLog('daigou',$rs['dgid'],1,$logContent,1);
		}

		//扣费/退费:需要先保存再重新读取,因此放最后
		if($pay){daigou_PayRef($rs['dgid'],$refund,$per);}//扣费/退费处理
		
		//更新主状态:$notify=1 因为处理完后,一般会处理另一个单,此单会一直留在框架中
		if($status){daigou_upStatus($rs['dgid'],$status,$exceed,$send=1,$pop=0,$callFrom='manage',$notify=1);}
		
		$ppt=$LG['daigou.81'].$ppt;
	}else{
		$ppt='操作失败:'.$ppt;
	}
	
	$token->drop_token("daigouOP{$dgid}"); //处理完后删除密钥
}


















//修改各种备注=====================================================
elseif($typ=='content'){
	//通用验证------------------------------------
	$token=new Form_token_Core();
	$token->is_token("daigouOP{$dgid}",$tokenkey); //验证令牌密钥
	
	//验证权限
	permissions('daigou_ed,daigou_cg,daigou_hh,daigou_ch,daigou_th,daigou_zg',1,'manage','');
	
	$chk=0;	$XAalert=1;//显示提示方式
	
	//更新和条件
	$where="dgid in ({$dgid}) {$Xwh}";
	if($field=='memberContentReply')
	{
		$chk=1;$save="{$field}='{$value}',memberContentNew=0";
		$content=FeData('daigou',$field,$where);
		if($content!=$value){$save.=",memberContentReplyNew=1,memberContentReplyTime=".time();}
	}elseif($field=='sellerContentReply'){
		$chk=1;$save="{$field}='{$value}',sellerContentNew=0";
		$content=FeData('daigou',$field,$where);
		if($content!=$value){$save.=",sellerContentReplyNew=1,sellerContentReplyTime=".time();}
	}



	//保存
	if($chk){
		$xingao->query("update daigou set {$save} where {$where}");SQLError('更新daigou');
		if(mysqli_affected_rows($xingao)>0){$ppt=$LG['daigou.81'];}else{$err=1;$ppt='无修改!';}
	}else{
		$err=1;$ppt='参数错误!';
	}
	$token->drop_token("daigouOP{$dgid}"); //处理完后删除密钥
}	


















	
	
	
	
	
//留言最新状态设置=====================================================
elseif($typ=='New'){
	//验证权限
	permissions('daigou_ed,daigou_cg,daigou_hh,daigou_ch,daigou_th,daigou_zg',1,'manage','');

	//获取处理
	$field=par($_GET['field']);
	$value=par($_GET['value']);
	$dgid=par(ToStr($_REQUEST['dgid'])); 
	$chk=0;
	$XAalert=1;//显示提示方式
	
	//更新和条件
	$where="dgid in ({$dgid}) {$Xwh}";
	if($field=='memberContentNew'){$chk=1;$save="memberContentNew='".spr($value)."'";}
	if($field=='sellerContentNew'){$chk=1;$save="sellerContentNew='".spr($value)."'";}
	
	//保存
	if($chk){
		$xingao->query("update daigou set {$save} where {$where}");SQLError('更新daigou');
		if(mysqli_affected_rows($xingao)>0){$ppt=$LG['daigou.81'];}else{$err=1;$ppt='无需修改!';}
	}else{
		$err=1;$ppt='参数错误!';
	}
	
}























//通用提示=====================================================
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
body{color:#444444;}
</style>