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
if(!defined('InXingAo')){exit('No InXingAo');}

//不需要处理的字段-------------------------------------------------------------------------
function daigou_with_field($typ='alone')
{
	if($typ=='alone')
	{
		return'dgid,goid,s_name,address_save_s,s_mobile,s_tel,s_zip,s_add_shengfen,s_add_chengshi,s_add_quzhen,s_add_dizhi,s_shenfenhaoma,s_shenfenimg_z_add,s_shenfenimg_b_add,s_shenfenimg_z,s_shenfenimg_b,s_mobile_code,old_img,groupid';//代购单和商品不处理的字段
	}elseif($typ=='dg_digital'){
		return 'warehouse,source,autoAddPay,freightFee,brand,brandDiscount,types,sellerStatus';//代购单数字字段
		//不能加status,会员修改时,默认没有这个字段,加上会强行改为0
	}elseif($typ=='digital'){
		return 'number,price,color,size,addid,goodsFee,weight';//商品数字字段
	}
}


//提交验证,处理,返回全局-------------------------------------------------------------------------
function daigou_chk()
{
 	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');	
	global $_POST,$member_per;
	if($_POST['source']==1)
	{
		//线上网站==========================
		if(!$_POST['address']){exit ("<script>alert('{$LG['daigou.81_1']}');goBack();</script>");}
		$_POST['address']=addhttp($_POST['address']);
		$_POST['serviceRate']=$member_per[$_POST['groupid']]['dg_serviceRateWeb'];//服务费率
		$_POST['brandDiscount']=0;
		$_POST['brand']=0;
		
	}elseif($_POST['source']==2){
		//线下专柜==========================
		if(!$_POST['brand']){exit ("<script>alert('{$LG['daigou.82']}');goBack();</script>");}
		$_POST['serviceRate']=$member_per[$_POST['groupid']]['dg_serviceRateShop'];//服务费率

		//再次安全验证
		$_POST['brandDiscount']=daigou_brandDiscount($_POST['groupid'],$_POST['brand']);//品牌折扣
		if($_POST['brandDiscount']==0){exit ("<script>alert('{$LG['daigou.83']}');goBack();</script>");}

	}
}



//变更状态,更新状态,修改状态 -------------------------------------------------------------------------
/*
	含有:日志
	
	$rs 可以是数组或代购ID (代购ID时自动SQL)
	$status 要更新的状态
	$exceed=0 现状态超过时是否更新
	$send 是否发通知
	$pop=0 是否弹出提示:弹出时执行exit
	$callFrom=='manage' member
	$notify;//发邮件时是否用框架发送,减少等待时间
*/


function daigou_upStatus($rs,$status,$exceed=0,$send=1,$pop=1,$callFrom='manage',$notify=0)
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	
	//开发错误弹出提示
	if(!$rs){exit ("<script>alert('rs{$LG['pptEmpty']} (DG001)');goBack();</script>");}
	if(!CheckEmpty($status)){exit ("<script>alert('status{$LG['pptEmpty']} (DG002)');goBack();</script>");}
	
	if(!$statusTime){$statusTime=time();}
	if($callFrom=='manage'){global $Xwh;}elseif($callFrom=='member'){global $Mmy;}
	
	if(is_numeric($rs)){$rs=FeData('daigou','*',"dgid='{$rs}' {$Mmy} {$Xwh}");}
	
	if(spr($rs['status'])==$status){return '不更新:现状态与要更新的状态一样 (DG003)';}
	if(!$exceed&&spr($rs['status'])>$status){return '不更新:现状态超过要更新的状态 (DG004)';}
	
	//关联更新------------------------------------
	$save="status='{$status}',statusTime='{$statusTime}'";
	$save_gd="dgid=dgid";
	if(have('0,1,10',$status,1)){$save_gd.=",memberStatus=0,manageStatus=0";}
	if($status==9){$save.=",inStorageTime=".time();}//入库时间
	
	//状态为:10 无效时------------------------------------
	if($status==10)
	{
		if($rs['pay'])
		{
			$ppt="已支付的代购单不可变更为【".daigou_Status(10)."】,请处理为【".daigou_lackStatus(2)."】会自动变更";
			if($pop)
			{
				exit ("<script>alert('{$ppt}');goBack();</script>");
			}else{
				return $ppt;
			}
		}
		
		//退回申请服务时所扣费用-开始
		daigou_memberStatus_refund($rs['dgid'],"dgid='{$rs['dgid']}'");
		//退回申请服务时所扣费用-结束
		
	}
	
	
	
	
	//更新主表------------------------------------
	$xingao->query("update daigou set {$save} where dgid='{$rs['dgid']}'");SQLError('更新主表 (DG005)');
	//更新商品表------------------------------------
	$xingao->query("update daigou_goods set {$save_gd} where dgid='{$rs['dgid']}'");SQLError('更新商品表 (DG005_1)');





	//同步更新------------------------------------
	if(mysqli_affected_rows($xingao)>0)
	{
		//无效时退款:不启用,必须先退款才能设为无效
		
		//添加日志
		$logContent='$LG[daigou.status'.spr($rs['status']).'] → $LG[daigou.status'.$status.']';
		opLog('daigou',$rs['dgid'],1,$logContent,1);
		
		//发通知－开始
		if($send)
		{
			$joint='daigou_status_msg'.$status; global $$joint; $send_msg=$$joint;
			$joint='daigou_status_mail'.$status; global $$joint; $send_mail=$$joint;
			$joint='daigou_status_sms'.$status;  global $$joint; $send_sms=$$joint;
			$joint='daigou_status_wx'.$status;  global $$joint; $send_wx=$$joint;
	
			if($send_msg||$send_mail||$send_sms||$send_wx)
			{
				//获取发送通知内容
				$NoticeTemplate='daigou_upStatus';
				$status_name=daigou_Status($status);
				require($_SERVER['DOCUMENT_ROOT'].'/public/NoticeTemplate.php');

				
				//发站内信息
				if($send_msg){SendMsg($rs['userid'],$rs['username'],$send_title,$send_content_msg,$send_file,'','',1,0,1,0);}
				//发邮件
				if($send_mail){SendMail('',$send_title,$send_content_mail,$send_file,1,0,$rs['userid'],$notify);}
				//发短信
				if($send_sms){SendSMS($rsmobile='',$send_content_sms,0,$rs['userid']);}//$rsmobile空时,自动获取会员资料的手机号
				//发微信
				if($send_wx){SendWX($send_WxTemId,$send_WxTemName,$send_content_wx,$rs['userid']);}
			}
		}
		//发通知－结束
	}
}




//按品牌获取品牌折扣-------------------------------------------------------------------------
function daigou_brandDiscount($groupid,$brand)
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	global $member_per;
	$r=GetArrVar($brand,$member_per[$groupid]['dg_brandDiscount']);
	if($r[2]==0){return $LG['js.6'];}//不支持该品牌
	return $r[2];
}


//会员显示操作菜单-------------------------------------------------------------------------
function daigou_op($value,$rs)
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	
	
	$manageStatus=NumData('daigou_goods',"dgid='{$rs['dgid']}' and manageStatus<>1");
	//全部在处理中,则不显示
	if($manageStatus)
	{
		$name=daigou_memberStatus($value);
		if($value){$icon='icon-share';}else{$icon='icon-remove';$color='#FF6600';}
		
		return '<li><a href="op.php?typ=op&edit='.$edit.'&field=memberStatus&value='.$value.'&dgid='.$rs['dgid'].'" target="iframe'.$rs['dgid'].'"><font style="color:'.$color.'"> <i class="'.$icon.'"></i> '.$name.'</font></a></li>';
	}
}


//输出该组支持的品牌-------------------------------------------------------------------------
/*
	后台输出全部,则用: ClassifyAll(6,$val);
*/
function daigou_brand($val,$groupid=0,$typ=0)
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	global $member_per;
	
	if($typ==1)
	{
		$dg_brandDiscount=$member_per[$groupid]['dg_brandDiscount'];
		$query_cf="select classid,name{$LT} from classify where classtype='6' and checked=1 and bclassid<>0";
		$sql_cf=$xingao->query($query_cf);
		while($cf=$sql_cf->fetch_array())
		{
			$r=GetArrVar($cf['classid'],$dg_brandDiscount);
			if($r[2]>0){$selected=$val==$cf['classid']?'selected':''; echo '<option value="'.$cf['classid'].'" '.$selected.'>'.cadd($cf['name'.$LT]).'</option>';}
			
		}
	}else{
		return classify($val,2);
	}
}







//筛选菜单----------------------------------------------------------------------------------------
function daigou_Options($field,$zhi='',$name='')
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	global $_GET,$callFrom,$Muserid,$search;
	
	if(!$name){return;}
	if($callFrom=='member'){$CN_userid=$Muserid;}else{$CN_userid='';}

	$scr_act='default';	if($field==par($_GET['field'])&&$zhi==par($_GET['zhi'])){$scr_act='info';}
	
	echo  ' <button type="button" class="btn btn-'.$scr_act.'" onClick="location.href=\'?'.$search.'&so=1&field='.$field.'&zhi='.$zhi.'\';" style="margin-bottom:5px;">';//按钮
	echo $name;//显示名称
	
	if(have($field,'memberStatus,manageStatus,lackStatus'))
	{
		$CN_where=" and dgid in (select dgid from daigou_goods where {$field}='{$zhi}')";
		$field='';$zhi='';
	}
	echo CountNum('daigou',$field,$zhi,$CN_where,$CN_userid,'default');//统计数量
	echo '</button> ';//结束按钮
}
 
 


//处理操作权限 -------------------------------------------------------------------------
function daigou_per_op($memberStatus)
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	global $manage_per;
	
	$chk=0;
	if($memberStatus==1&&permissions('daigou_ed,daigou_cg,daigou_th,daigou_zg,daigou_hh,daigou_ch','','manage',1)){$chk=1;}	
	elseif($memberStatus==2&&permissions('daigou_cg,daigou_hh','','manage',1)){$chk=1;}	
	elseif($memberStatus==3&&permissions('daigou_cg,daigou_zg','','manage',1)){$chk=1;}	
	elseif($memberStatus==4&&permissions('daigou_cg,daigou_th','','manage',1)){$chk=1;}	
	
	return $chk;
}













//---------------------------------------------------------------------------------------------
//------------------------------------------仓储相关-------------------------------------------
//---------------------------------------------------------------------------------------------

//仓储计算费用------------------------------------------------------------------------------
/*
	注意:体积/重量/数量按下单发货数值的来计算,不是仓库中的数量 (体积/重量都是按单件算,因此是不变的,只有数量是变动的)
*/
function dg_wareFee($goid,$number)
{ 
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	global $ON_ware,$member_per;
	if(!$ON_ware||!$goid||!$number){return 0;}

	$arr=ToArr($goid);
	$number=ToArr($number);
	if($arr)
	{
		foreach($arr as $key=>$value)
		{
			
			//计算-开始
			$gd=FeData('daigou_goods','*',"goid='{$value}'");
			if($dgid!=$gd['dgid']){$rs=FeData('daigou','userid,dgid,inStorageTime',"dgid='{$gd['dgid']}'");$dgid=$gd['dgid'];}//必须有:userid,inStorageTime
			if($rs['inStorageTime']<=0){continue;}
			
			$free_days_have=dg_ware_days(1,$rs);//负数表示已超过免费天数
			if($free_days_have>0){continue;}//还有免费仓储天数
			
			$days=abs($free_days_have);//仓储天数
			$groupid=FeData('member','groupid',"userid='{$gd['userid']}'");
			
			//尺寸:单件
			$volume=spr($gd['sizeLength']*$gd['sizeWidth']*$gd['sizeHeight']);
			if($volume>$member_per[$groupid]['dg_ware_volumeLimit'])	{
				$fee+=$member_per[$groupid]['dg_ware_volumePrice']*$volume*$number[$key]*$days;continue;
			}
			
			//重量:单件
			if($gd['weight']>$member_per[$groupid]['dg_ware_weightLimit'])	{
				$fee+=$member_per[$groupid]['dg_ware_weightPrice']*$gd['weight']*$number[$key]*$days;continue;
			}
		
			//数量:单件
			if($member_per[$groupid]['dg_ware_numberPrice'])	{
				$fee+=$member_per[$groupid]['dg_ware_numberPrice']*$number[$key]*$days;continue;
			}
			//计算-结束
			
		}
	}

	return spr($fee);
} 






//显示存放时间
/*
	$lx=0;显示 $lx=1 计算还有免费仓储天数
	
	要有:userid,inStorageTime
*/
//----------------------------------------------------------------------------------------
function dg_ware_days($lx='',$rs='')
{
	global $ON_ware,$member_per;
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	if(!$rs){global $rs;}

	$ts= LGtag($LG['function.45'],'<tag1>=='.DateDiff(time(),$rs['inStorageTime'],'d') ).'<br>';
	
	if($rs['inStorageTime']>0)
	{
		if ($ON_ware)
		{
			
			$groupid=FeData('member','groupid',"userid='{$rs['userid']}'");
			$free_days=$member_per[$groupid]['dg_ware_freeDays'];//会员组免费存放天数
			$free_days_have=(int)($free_days-DateDiff(time(),$rs['inStorageTime'],'d'));//会员还有免费存放天数
			
			if($free_days_have>=0)
			{
				$ts= '<font class="gray2">'.LGtag($LG['function.46'],'<tag1>=='.$free_days_have).'</font>';
			}else{
				$ts= '<font class="gray2">'.LGtag($LG['function.46_1'],'<tag1>=='.abs($free_days_have)).'</font>';
			}
		} 
		
		if(!$lx){echo $ts;}elseif($lx==1){return $free_days_have;}
	}
}





//---------------------------------------------------------------------------------------------
//------------------------------------------费用相关-------------------------------------------
//---------------------------------------------------------------------------------------------





//取消申请服务退费-------------------------------------------------------------------------
/*单独退款:无安全,其他验证*/
function daigou_memberStatus_refund($dgid,$where)
{
	if(!$dgid){return;}
	if(!$where){exit('开发错误:未指定商品');}
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	
	$rs=FeData('daigou','dgdh,dgid',"dgid={$dgid}");
	
	$query_gd="select userid,goid,godh,memberStatus_pay from daigou_goods where  {$where} and dgid='{$rs['dgid']}' and number>0 and memberStatus>0 and memberStatus_pay<>0";
	
	$sql_gd=$xingao->query($query_gd);
	while($gd=$sql_gd->fetch_array())
	{
		$userid=$gd['userid'];
		$godh_all.=cadd($gd['godh']).',';
		$goid_all.=cadd($gd['goid']).',';
		$refund_money+=$gd["memberStatus_pay"];//主币
	}
	
	//退费
	if($refund_money)
	{
		$godh_all=DelStr($godh_all);
		$goid_all=DelStr($goid_all);
		$refund_money=abs($refund_money);
		
		MoneyCZ($userid,$fromtable='daigou',$fromid=$dgid,$fromMoney=$refund_money,$fromCurrency='',$title=$rs['dgdh'],$godh_all,$type=3);
		
		//更新
		$xingao->query("update daigou_goods set memberStatus_pay='0' where goid in ({$goid_all})"); 
		
		$r['ppt']=" &nbsp; {$LG['daigou.24']}<strong>".$refund_money.$XAmc.'</strong>';
		$r['showTime']=3000;
		$r['logContent']="({$r['ppt']},商品:{$godh_all})";
		
		return $r;
	}
}


//付款情况-------------------------------------------------------------------------
function daigou_showFee($rs)
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	global $callFrom;
	
	if(have('0,1,10',spr($rs['status']),1)){return;}
	
	$totalFee=daigou_totalFee($rs);//此单全部费用:价格币
	$totalPay=daigou_totalPay($rs);//已支付:价格币
	$totalPayTo=daigou_totalPay($rs,1);//已支付:支付币

	if(!$totalFee)//错误
	{
		$pay_status='<span class="label label-sm label-default">'.$LG['daigou.14'].'</span>';
	}
	elseif(!$rs['pay'])//未支付
	{
		$pay_status= '<span class="label label-sm label-default">'.daigou_payStatus($rs['pay']).'</span>';
	}
	elseif($rs['pay']==1)//付清
	{
		$payppt=$LG['yundan.Xcall_basic_show_29'];if($rs['memberpay']){$payppt=$LG['yundan.Xcall_basic_show_30'];}
		$pay_ppt=LGtag($LG['daigou.9'],'<tag1>=='.$payppt).DateYmd($rs['payTime'],1);

		$pay_status= '<span class="label label-sm label-success">'.daigou_payStatus($rs['pay']).'</span>';
	}
	elseif($rs['pay']==2)//补款
	{
		$pay_status= '<span class="label label-sm label-warning">'.daigou_payStatus($rs['pay']).(abs(spr($rs['payDiffer'])).$rs['priceCurrency']).'</span>';
	}
	elseif($rs['pay']==3)//多付
	{
		$pay_ppt=$LG['daigou.16'].abs(spr($rs['payDiffer'])).$rs['priceCurrency'];
		$pay_status= '<span class="label label-sm label-danger">'.daigou_payStatus($rs['pay']).'</span>';
	}
	?>
    
       
	<a data-target="#ajax<?=$rs['dgid']?>" data-toggle="modal" href="/xingao/daigou/call/money_modal.php?dgid=<?=$rs['dgid']?>&callFrom=<?=$callFrom?>">
	   <font class="tooltips" data-container="body" data-placement="top" data-original-title="<?=$LG['settlement.list_yundan_13'];//总费用?>">
	   <?=$totalFee.$rs['priceCurrency']?>
       </font>
	</a>
	
	<div class="modal fade" id="ajax<?=$rs['dgid']?>" tabindex="-1" role="basic" aria-hidden="true">
		<img src="/images/ajax-modal-loading.gif" alt="" class="loading">
	</div>
    
    <!--支付状态-->
     <font class="show_price popovers" data-trigger="hover" data-placement="top"  data-content="
         <?=$totalPay>0?$LG['daigou.10'].$totalPay.$rs['priceCurrency']:''?>
         <?=$totalPayTo>0?'('.$LG['deduct'].$totalPayTo.$rs['toCurrency'].')<br>':''?>
         <?=$LG['daigou.11']?><?=$rs['autoAddPay']>0?spr($rs['autoAddPay']).$rs['priceCurrency']:$LG['daigou.17']?> 
        <?=$pay_ppt?'<br>'.$pay_ppt:''?>

     ">
     <?=$pay_status?>
     </font>
     
	<?php
}


//支付状态
function daigou_payStatus($pay)
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	switch($pay)
	{
		case 0:return $LG['notPay'];
		case 1:return $LG['havePay'];
		case 2:return $LG['daigou.8'];
		case 3:return $LG['daigou.16'];
	}
}





//批量支付------------------------------------------------------------------------
/*
	只能是同个会员的代购单
	用于转账后自动支付和其他批量自动支付
	
	$dgid 支持多ID
	$refund=0 不可退费;
	$refund=1 可退费
	
	$per=0 不验证权限,不验证仓库

	$allPay=0 费用不够全部支付时,也支付部分代购单;
	$allPay=1 费用够支付全部时才支付全部 
	
	返回:0 没支付 ;1已全部支付;2部分支付
*/
function daigou_batchPay($dgid,$refund=0,$per=0,$allPay=0)
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	global $manage_per,$Xwh;

	if(!$dgid){exit("<script>alert('dgid{$LG['pptEmpty']} (DG018)');goBack('uc');</script>");}
	
	$dgid=ToStr($dgid);
	$userid='';$ret=0;

	//获取总共要支付的费用
	/*pay 0=未支付;1=已付清;2=未付清;3=已多付*/
	$query="select * from daigou where dgid in ({$dgid}) and pay in (0,2,3) order by userid desc";
	$sql=$xingao->query($query);
	while($rs=$sql->fetch_array())
	{
		$pay_money=0;
		$totalFee=daigou_totalFee($rs);//此单全部费用:价格币
		$totalPay=daigou_totalPay($rs);//已支付:价格币
		$pay_money=$totalFee-$totalPay;//要付的费用(总费用-已付费用)
		if($pay_money>0)
		{
			if($userid&&$userid!=$rs['userid']){$userid=$rs['userid'];exit("<script>alert('只能是同个会员的代购单 (DG006)');goBack('uc');</script>");}
			
			if(!$mr['currency']){$mr=FeData('member','money,currency',"userid='{$rs['userid']}'");}//获取会员资料
			$pay_money*=daigou_exchangeBase(exchange($rs['priceCurrency'],$mr['currency']),$rs['userid']);//转支付币:不加spr,因为费用过小时,汇率后会小于2位小数,会显示0元
			if($pay_money>0&&$pay_money<0.01){$pay_money=0.01;}else{$pay_money=spr($pay_money);}
			$totalpay_money+=$pay_money;
			
			//$allPay=0:支付部分代购单
			if(!$allPay&&$mr['money']>=spr($totalpay_money))
			{
				daigou_PayRef($rs['dgid'],$refund,$per);//支付处理
				$totalpay_money=0;
				$ret=1;
			}elseif(!$allPay&&$mr['money']<$totalpay_money){
				if($ret){return 2;}else{return 0;}
			}

			$dgidPay.=$rs['dgid'].',';
		}
	}
	
	if(!$allPay){return $ret;}
		
	//$allPay=1:支付全部代购单
	/*获取账户金额:够支付*/
	$dgidPay=DelStr($dgidPay);
	if($allPay&&$dgidPay&&$mr['money']>=spr($totalpay_money))
	{
		//多ID时:分开执行
		$arr=ToArr($dgidPay);
		if($arr)
		{
			foreach($arr as $arrkey=>$value)
			{
				daigou_PayRef($value,$refund,$per);
			}
		}
		return 1;
	}else{
		return 0;
	}
}


						



//后台普通的扣费&退费: 已含更新状态,有日志,发通知,自动识别自动扣费额度--------------------------
/*
	按代购单本身的相差费用扣&退
    按当天汇率计算差额(不是总额)
	
	$dgid 只支持一个ID
	$refund=0 不可退费;$refund=1 可退费
	$per=0 不验证权限(在外部先行验证),不验证仓库,不验证自动扣费额度:转账自动支付时用到或强制扣费用到
*/
function daigou_PayRef($dgid,$refund=0,$per=1)
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	global $manage_per,$Xwh;	
	if(!$dgid){exit("<script>alert('dgid{$LG['pptEmpty']} (DG007)');goBack('uc');</script>");}
	if(!$per){$Xwh='';}
	
	
	
	
	//处理
	if(!$per||permissions('daigou_cg,daigou_zg','','manage',1))
	{
		$rs=FeData('daigou','*',"dgid='{$dgid}' {$Xwh}");
		if(!$rs['dgid']){exit("<script>alert('找不到此单或无权限管理该仓库的代购单！ (DG008)');goBack('uc');</script>");}
		if($rs['pay']==1){exit("<script>alert('(ID{$rs['dgid']}) 已支付状态不可再操作 (DG009)');goBack('uc');</script>");}
		
		//费用处理----------------------------------------------------------------------------------	
		$totalFee=daigou_totalFee($rs);//此单全部费用:价格币
		$totalPay=daigou_totalPay($rs);//已支付:价格币
		$totalPayTo=daigou_totalPay($rs,1);//已支付:支付币
		$pay_money=$totalFee-$totalPay;//要付的费用(总费用－已付费用)
		
		//扣费==============================================================================
		if($pay_money>0)
		{
			
			global $daigou_managePay_msg,$daigou_managePay_mail,$daigou_managePay_sms,$daigou_managePay_wx; 
			$send_msg=$daigou_managePay_msg;$send_mail=$daigou_managePay_mail;$send_sms=$daigou_managePay_sms;$send_wx=$daigou_managePay_wx;
			
			//查询是否可扣费
			$mr=FeData('member','money,groupid,currency',"userid='{$rs['userid']}'");
			$toExchange=daigou_exchangeBase(exchange($rs['priceCurrency'],$mr['currency']),$rs['userid']);	
			$toMoney=$pay_money*$toExchange;
			
			if($mr['money']>=spr($toMoney))
			{
				//金额足够:扣费--------------
				
				//可自动扣---
				if(!$per||$rs['autoAddPay']>=spr($pay_money))
				{
					$kf=MoneyKF($rs['userid'],'daigou',$rs['dgid'],$pay_money,$rs['priceCurrency'],$rs['dgdh'],'',$type=3);
					//初次支付时:开始送积分
					global $off_integral,$integral_daigou;
					if($off_integral&&$integral_daigou>0&&!$rs['PayFirstTime'])
					{
						 //会员加分
						 integralCZ($rs['userid'],'daigou',$rs['dgid'],$integral_daigou,$rs['dgdh'],'',4);
						 
						 //推广员加分
						 tuiguang_hqsf($rs['userid'],$integral_daigou,'daigou',$rs['dgid']);
					}
				
					//更新主表内容
					$save=daigou_savePay($rs,$kf,0);//支付后保存相关费用数据
					if($per){$save.=",autoAddPay=autoAddPay-{$pay_money}";}//减去可自动扣费额度
					
					
					//获取发送通知内容
					$NoticeTemplate='daigou_PayRef_3';
					require($_SERVER['DOCUMENT_ROOT'].'/public/NoticeTemplate.php');
					
					//添加日志内容
					
					$logContent=$LG['havePay'].$pay_money.$rs['priceCurrency'].'('.$LG['deduct'].$toMoney.$mr['currency'].')';
					if($gd['godh']){$logContent="商品单号{$gd['godh']}:".$logContent;}
					
					$ret=1;//处理结果
				}
				
				//不可自动扣---
				else{
					//更新主表内容
					if(!$rs['pay']){$save="pay=0";}elseif($rs['pay']){$save="pay=2";}
					
					
					//发通知内容
					if($rs['autoAddPay']>0){$send_title=$LG['daigou.22'];}
					else{$send_title=$LG['daigou.23'];}
					
					//获取发送通知内容
					$NoticeTemplate='daigou_PayRef_1';
					require($_SERVER['DOCUMENT_ROOT'].'/public/NoticeTemplate.php');
					
					$ret=0;//处理结果
				}
				
				
				
				
				
				
				
			}else{
				//金额不足:没扣费--------------
				
				//更新主表内容
				if(!$rs['pay']){$save="pay=0";}elseif($rs['pay']){$save="pay=2";}
				
				//获取发送通知内容
				$NoticeTemplate='daigou_PayRef_2';
				require($_SERVER['DOCUMENT_ROOT'].'/public/NoticeTemplate.php');

				
				$ret=0;//处理结果
			}
			
		}
		
		
		
		
		
		
		
		
		
		//退费==============================================================================
		elseif($pay_money<0){
			$pay_money=abs($pay_money);
			if($refund)
			{
				global $daigou_manageRef_msg,$daigou_manageRef_mail,$daigou_manageRef_sms,$daigou_manageRef_wx; 
				$send_msg=$daigou_manageRef_msg;$send_mail=$daigou_manageRef_mail;$send_sms=$daigou_manageRef_sms;$send_wx=$daigou_manageRef_wx;

				//查询账户币种资料
				$mr=FeData('member','money,groupid,currency',"userid='{$rs['userid']}'");
				$toExchange=daigou_exchangeBase(exchange($rs['priceCurrency'],$mr['currency']),$rs['userid']);	$toMoney=spr($pay_money*$toExchange);
			
				if($toMoney)
				{
					$cz=MoneyCZ($rs['userid'],'daigou',$rs['dgid'],$pay_money,$rs['priceCurrency'],$rs['dgdh'],'',$type=51);
					
					//获取发送通知内容
					$NoticeTemplate='daigou_PayRef_4';
					require($_SERVER['DOCUMENT_ROOT'].'/public/NoticeTemplate.php');
					
					//添加日志内容
					$logContent=$LG['daigou.24'].$pay_money.$rs['priceCurrency'].'('.$LG['daigou.37'].$cz['toMoney'].$cz['toCurrency'].')';
					if($gd['godh']){$logContent="商品单号{$gd['godh']}:".$logContent;}
				}else{
					//添加日志内容
					$logContent=LGtag($LG['daigou.38'],'<tag1>=='.$mr['currency']);//"由于退费金额小于0.01{$mr['currency']},因此本次未退款";
					if($gd['godh']){$logContent="商品单号{$gd['godh']}:".$logContent;}
				}
				
				//更新主表内容
				$save=daigou_savePay($rs,$cz,0);

			}
			$ret=1;//处理结果

		}
		
		
		
		//无需操作==============================================================================
		elseif($pay_money==0){
			$NOsend=1;
			$ret=1;//处理结果
		}//if($pay_money>0)
		
		
		
		
		
		
		
		
		
		
		
		
		
		//返回处理==============================================================================
		if(!CheckEmpty($ret)){exit("<script>alert('开发错误: ret空 (DG010)');goBack('uc');</script>");}

		//更新主表----
		if($save)
		{
			$xingao->query("update daigou set {$save} where dgid='{$rs['dgid']}'");
			SQLError('更新主表 (DG011)');
		}



		//更新主状态----
		if($ret)
		{
			//更新为已支付
			$status=0;if(spr($rs['status'])<3){$status=3;}elseif(spr($rs['status'])<5){$status=5;}
			if($status){daigou_upStatus($rs,$status,$exceed=1,$send=1,$pop=1,$callFrom='manage');}
			
		}else{
			//更新为待支付
			$status=0;if(spr($rs['status'])<=2){$status=2;}elseif(spr($rs['status'])<=5){$status=4;}
			if($status){daigou_upStatus($rs,$status,$exceed=1,$send=1,$pop=1,$callFrom='manage');}
		}
		
		//添加日志
		if($logContent){opLog('daigou',$rs['dgid'],1,$logContent,1);}
		
		
		//发通知----
		if(!$NOsend&&($send_msg||$send_mail||$send_sms||$send_wx))
		{
			//发站内信息
			if($send_msg){SendMsg($rs['userid'],$rs['username'],$send_title,$send_content_msg,$send_file,'','',1,0,1,0);}
			//发邮件
			if($send_mail){SendMail('',$send_title,$send_content_mail,$send_file,1,0,$rs['userid'],$notify);}
			//发短信
			if($send_sms){SendSMS($rsmobile='',$send_content_sms,0,$rs['userid']);}//$rsmobile空时,自动获取会员资料的手机号
			//发微信
			if($send_wx){SendWX($send_WxTemId,$send_WxTemName,$send_content_wx,$rs['userid']);}
		}
		
		//返回
		return $ret;
	}//if(permissions('daigou_cg,daigou_zg','','manage',1))
}
			





//退货退款 断货退款: 有日志,已含更新状态,发通知
/*
	$goid 只能一个值
	$numberRet 退货数量
*/
function daigou_numberRet($goid,$numberRet)
{
	$numberRet=spr($numberRet,0);
	if(!$goid){exit("<script>alert('goid参数空！ (DG012)');goBack('uc');</script>");}
	if(!$numberRet){exit("<script>alert('numberRet参数空！ (DG013)');goBack('uc');</script>");}
	
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	global $manage_per,$Xwh,$Xuserid;
	
	
	
	if(permissions('daigou_cg,daigou_th','','manage',1))
	{
		//获取,验证,处理--------------------------------------------------------------------
		$gd=FeData('daigou_goods','*',"goid='{$goid}'");
		$rs=FeData('daigou','*',"dgid='{$gd['dgid']}' {$Xwh}");
		
		if(!$rs['dgid']){exit("<script>alert('找不到此单或无权限管理该仓库的代购单！ (DG014)');goBack('uc');</script>");}
		if($gd['number']<=0){exit("<script>alert('现数量已经为0,无法再操作！ (DG015)');goBack('uc');</script>");}
		
		$mr=FeData('member','integral,currency',"userid='{$rs['userid']}'");
		
		if($rs['toCurrency']!=$mr['currency']){exit("<script>alert('会员账户币种{$mr['currency']}与此单的币种{$rs['toCurrency']}不一致,无法再退款！ (DG016)');goBack('uc');</script>");}

		if($numberRet>$gd['number']){$numberRet=$gd['number'];}
		
		$totalFee=daigou_totalFee($rs);//此单全部费用:价格币
		$totalPay=daigou_totalPay($rs);//已支付:价格币
		$totalPayTo=daigou_totalPay($rs,1);//已支付:支付币
		$pay_money=spr($totalFee-$totalPay);//要付的费用(总费用－已付费用)
		
		
		//默认--------------------------------------------------------------------
		$refundMoney=0;	$refundMoneyTo=0;
		$save="dgid=dgid";
		$save_gd="dgid=dgid";

		//更新主表内容:通用保存--------------------------------------------------------------------
		$number=spr($gd['number']-$numberRet);
		$goodsFee=daigou_goodsFee_calc($rs,'','',$gd,$number);
		$save_gd.=",number=number-{$numberRet},inventoryNumber=inventoryNumber-{$numberRet},numberRet=numberRet+{$numberRet},goodsFee='{$goodsFee}'";
		
		if($numberRet>=$gd['number']){$save_gd.=",lackStatus='2',lackStatusTime=".time();}else{$save_gd.=",lackStatus='1',lackStatusTime=".time();}//断货退款状态
		


		//处理退款-开始-------------------------------------------------------------------
		if($totalPayTo>0)
		{
			//退款公式:(商品总费/总商品数*退数)+运费============
			
			/*退商品费和手续费*/
			$refundGoods=$gd['price']*$numberRet;//单价币种
			if($rs['serviceRate']>0){$refundGoods+=$refundGoods*($rs['serviceRate']/100);}//服务费也要退
			
			$refundGoodsTo=($gd['goodsFeePayTo']/$gd['number'])*$numberRet;//支付币
			$refundMoney=$refundGoods;		
			$refundMoneyTo=$refundGoodsTo;

			/*退运费:全退并且所有商品已全退时才退*/
			if($numberRet>=$gd['number'])//全退
			{
				$num=mysqli_num_rows($xingao->query("select goid from daigou_goods where dgid='{$rs['dgid']}' and number>0 and goid<>'{$gd['goid']}'"));
				if(!$num)//没有商品
				{
					//退运费
					$refundMoney+=$rs['freightFeePay'];//价格币
					$refundMoneyTo+=$rs['freightFeePayTo'];//支付币
					
					$refundMoney_freight=$rs['freightFeePay'];//不用于计算,单独保存
					$refundMoneyTo_freight=$rs['freightFeePayTo'];//不用于计算,单独保存

					//更新主表内容
					$save.=",freightFeePay=0,freightFeePayTo=0";
					
					//要变更的主状态
					$status=10;
					$ppt='此单已全退,状态变更为【'.daigou_Status(10).'】；'.$ppt;
				}
				
				//全退时,扣已送的积分:查询当初所送的分数
				global $off_integral;
				if($off_integral)
				{
					$integral=FeData('integral_kfbak','integral',"fromtable='daigou' and fromid='{$rs['dgid']}'");
					
					if($integral>0)
					{
						//查询是否可扣积分
						if($mr['integral']>=$integral)
						{
							integralKF($rs['userid'],$fromtable='daigou',$fromid=$rs['dgid'],$integral,0,'退货扣分','代购单全部退货,扣除当初此单所送的分',$type=100,$operator=$Xuserid);
							
							$ppt='扣除当初所送的'.$integral.'积分';
						}
					}
				}
				
				
				
			}
			
			



			
			//减掉未支付的欠费============
			//欠费:计算未支付的欠费
			/*取消原因:很难处理,不知道是哪个项目(商品或运费)欠费,减掉后并没有更新保存已减的欠费*/
/*			$owe=$totalFee-$totalPay;//返回是价格币:只能计算原币
			if($owe>0)
			{
				$refundMoney-=$owe;
				if($refundMoney>0)
				{
					$refundMoneyTo-=$owe*daigou_exchangeBase(exchange($rs['priceCurrency'],$rs['toCurrency']),$rs['userid']);//减掉欠费(欠费换成支付币)
				}
			}
*/


			//退款============
			if($refundMoneyTo>0&&!spr($refundMoneyTo)){$ppt="由于退费金额小于0.01{$rs['toCurrency']},因此未退款；".$ppt;}
			
			$refundMoneyTo=spr($refundMoneyTo);
			if($refundMoneyTo>0)
			{
				global $daigou_manageRef_msg,$daigou_manageRef_mail,$daigou_manageRef_sms,$daigou_manageRef_wx; 
				$send_msg=$daigou_manageRef_msg;$send_mail=$daigou_manageRef_mail;$send_sms=$daigou_manageRef_sms;$send_wx=$daigou_manageRef_wx;

				$cz=MoneyCZ($rs['userid'],'daigou',$rs['dgid'],$refundMoneyTo,$rs['toCurrency'],$rs['dgdh'],'',$type=51);
				$ppt="已退{$refundMoney}{$rs['priceCurrency']} (到账{$cz['toMoney']}{$cz['toCurrency']})；".$ppt;
				
				//获取发送通知内容
				$NoticeTemplate='daigou_numberRet';
				require($_SERVER['DOCUMENT_ROOT'].'/public/NoticeTemplate.php');
				
				$save_gd.=",goodsFeePay=goodsFeePay-".spr($refundGoods-$refundMoney_freight).",goodsFeePayTo=goodsFeePayTo-".spr($refundGoodsTo-$refundMoneyTo_freight);
			}
			
		
			
		}else{
			$ppt='此单未支付,因此没退款';
		}
		//处理退款-结束-------------------------------------------------------------------






		//更新主表--------------------------------------------------------------------
		if($save)
		{
			$xingao->query("update daigou set {$save} where dgid='{$rs['dgid']}'");
			SQLError('更新主表 (DG017)');
		}
		
		if($save_gd)
		{
			$xingao->query("update daigou_goods set {$save_gd} where goid='{$gd['goid']}'");
			SQLError('更新商品表 (DG017_1)');
		}

		//更新商品goodsFee总价,代购单支付情况
		daigou_Update($rs['dgid']);//状态更新为10时,需要判断pay情况,因此先更新

		//更新主状态--------------------------------------------------------------------
		if($status){daigou_upStatus($rs['dgid'],$status,$exceed=1,$send=1,$pop=1,$callFrom='manage');}//要重新读取

		
		
		
		//发通知--------------------------------------------------------------------
		if($send_msg||$send_mail||$send_sms||$send_wx)
		{
			//发站内信息
			if($send_msg){SendMsg($rs['userid'],$rs['username'],$send_title,$send_content_msg,$send_file,'','',1,0,1,0);}
			//发邮件
			if($send_mail){SendMail('',$send_title,$send_content_mail,$send_file,1,0,$rs['userid'],$notify);}
			//发短信
			if($send_sms){SendSMS($rsmobile='',$send_content_sms,0,$rs['userid']);}//$rsmobile空时,自动获取会员资料的手机号
			//发微信
			if($send_wx){SendWX($send_WxTemId,$send_WxTemName,$send_content_wx,$rs['userid']);}
		}
		
	
	}
	
				
	//添加日志
	if($ppt){opLog('daigou',$dgid,1,$ppt,1);}
	
	return $ppt;	
}







//取消订购/拒绝采购: 已含更新状态,发通知,全退款
/*
	是通用处理,日志内容不一样,因此不添加日志,需要在外部另行添加
*/
function daigou_cancel($dgid,$callFrom)
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	if(!$dgid){exit("<script>alert('dgid{$LG['pptEmpty']} (DG020)');goBack('uc');</script>");}
	
	//支持会员操作
	if($callFrom=='member'){
		global $Mmy;
		if(!$Mmy){return;}
		$send=0;//不发通知
		$logContent='会员取消采购';//日志内容
		$where=" dgid='{$dgid}' and status in (2,4) {$Mmy}";
		
	}elseif($callFrom=='manage'){
		global $manage_per,$Xwh,$Xuserid;
		if(!$Xuserid){return;}
		permissions('daigou_cg',1,'manage','');//验证权限
		$send=1;//发通知
		$logContent='后台拒绝采购';//日志内容
		$where="dgid='{$dgid}'  and status<6 {$Xwh}";
		
	}else{
		return;
	}
	
	//获取,验证,处理--------------------------------------------------------------------
	$rs=FeData('daigou','*',"{$where}");
	if(!$rs['dgid']){exit("<script>alert('找不到此单或无权限管理该仓库的代购单！ (DG021)');goBack('uc');</script>");}
	
	$mr=FeData('member','integral,currency',"userid='{$rs['userid']}'");
	
	$totalPay=daigou_totalPay($rs);//已支付:价格币
	$totalPayTo=daigou_totalPay($rs,1);//已支付:支付币
	
	if($totalPayTo>0&&$rs['toCurrency']!=$mr['currency']){exit("<script>alert('会员账户币种{$mr['currency']}与此单的币种{$mr['toCurrency']}不一致,无法操作！ (DG023)');goBack('uc');</script>");}

	
	
	//默认--------------------------------------------------------------------
	$status=10;//要更新的状态
	$ppt=$LG['daigou.56'].'【'.daigou_Status(10).'】；';

	//更新主表内容:通用保存--------------------------------------------------------------------
	$save="pay=0,freightFeePay='0',freightFeePayTo='0',toCurrency='',PayFirstTime='0'";
	$save_gd="goodsFeePay='0',goodsFeePayTo='0'";
	
	

	//处理退款-开始-------------------------------------------------------------------
	if($totalPayTo>0)
	{
		//扣已送的积分:查询当初所送的分数
		global $off_integral;
		if($off_integral)
		{
			$integral=FeData('integral_kfbak','integral',"fromtable='daigou' and fromid='{$rs['dgid']}'");
			
			if($integral>0)
			{
				//查询是否可扣积分
				if($mr['integral']>=$integral)
				{
					integralKF($rs['userid'],$fromtable='daigou',$fromid=$rs['dgid'],$integral,0,'取消订购扣分','代购单全部取消订购,扣除当初此单所送的分',$type=100,$operator=$Xuserid);
					$ppt.=LGtag($LG['daigou.57'],'<tag1>=='.$integral);
				}
			}
		}


		//退款============
		if($totalPayTo>0&&!spr($totalPayTo)){$ppt.=LGtag($LG['daigou.38'],'<tag1>=='.$rs['toCurrency']);}
		
		$totalPayTo=spr($totalPayTo);
		if($totalPayTo>0)
		{
			global $daigou_manageRef_msg,$daigou_manageRef_mail,$daigou_manageRef_sms,$daigou_manageRef_wx; 
			$send_msg=$daigou_manageRef_msg;$send_mail=$daigou_manageRef_mail;$send_sms=$daigou_manageRef_sms;$send_wx=$daigou_manageRef_wx;

			$cz=MoneyCZ($rs['userid'],'daigou',$rs['dgid'],$totalPayTo,$rs['toCurrency'],$rs['dgdh'],'',$type=51);
			$ppt.="{$LG['daigou.24']}{$totalPay}{$rs['priceCurrency']} ({$LG['daigou.37']}{$cz['toMoney']}{$cz['toCurrency']})；";
			
			//获取发送通知内容
			$NoticeTemplate='daigou_cancel';
			require($_SERVER['DOCUMENT_ROOT'].'/public/NoticeTemplate.php');

		}
	}else{
		$ppt=$LG['daigou.58'];//此单未支付,因此没退款
	}
	//处理退款-结束-------------------------------------------------------------------




	//更新主表--------------------------------------------------------------------
	if($save)
	{
		$xingao->query("update daigou set {$save} where dgid='{$rs['dgid']}'");
		SQLError('更新主表 (DG024)');
		
		if(mysqli_affected_rows($xingao)>0)
		{
			//添加日志
			if($callFrom=='member'){$types=0;}else{$types=1;}
			opLog('daigou',$rs['dgid'],1,$logContent,$types);
		
		}
	}

	if($save_gd)
	{
		$xingao->query("update daigou_goods set {$save_gd} where dgid='{$rs['dgid']}'");
		SQLError('更新商品表 (DG024_1)');
	}


	//更新主状态--------------------------------------------------------------------
	if($status){daigou_upStatus($rs['dgid'],$status,$exceed=1,$send,$pop=0,$callFrom);}//要重新读取

	
	
	
	//发通知--------------------------------------------------------------------
	if($send_msg||$send_mail||$send_sms||$send_wx)
	{
		//发站内信息
		if($send_msg){SendMsg($rs['userid'],$rs['username'],$send_title,$send_content_msg,$send_file,'','',1,0,1,0);}
		
		//会员自行操作时不发以下2项通知
		if($send)
		{
			//发邮件
			if($send_mail){SendMail('',$send_title,$send_content_mail,$send_file,1,0,$rs['userid'],$notify);}
			//发短信
			if($send_sms){SendSMS($rsmobile='',$send_content_sms,0,$rs['userid']);}//$rsmobile空时,自动获取会员资料的手机号
			//发微信
			if($send_wx){SendWX($send_WxTemId,$send_WxTemName,$send_content_wx,$rs['userid']);}
		}
	}
	
	
	
	return $ppt;	
}





//支付/退费后保存相关费用数据-------------------------------------------------------------------------
/*
	$memberpay 是否是会员自行支付
*/
function daigou_savePay($rs,$kf,$memberpay=0)
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	global $XAMcurrency;
	
	$save="pay=1,payTime='".time()."',memberpay='{$memberpay}',freightFeePay=freightFee";
	$save_gd="goodsFeePay=goodsFee";
	
	//保存支付币/退费币金额:按所支付/退费的金额来算
	if($kf['toMoney']>0)
	{
		//检查刚才是支付哪种类型
		$toExchange=daigou_exchangeBase(exchange($rs['priceCurrency'],$kf['toCurrency']),$rs['userid']);
		
		$save.=",toCurrency='{$kf['toCurrency']}'";
		$save.=",freightFeePayTo=freightFeePayTo+((freightFee-freightFeePay)*{$toExchange})";//如果是负数也自动减掉
		$save_gd.=",goodsFeePayTo=goodsFeePayTo+{$kf['toMoney']}";//如果是负数也自动减掉
	}
	
	if(!$rs['PayFirstTime']){$save.=",PayFirstTime='".time()."'";}//初次扣费:添加初次时间
	$xingao->query("update daigou_goods set {$save_gd} where dgid='{$rs['dgid']}'");

	return $save;
}






//添加/修改代购单时更新商品列表-----------------------------------
/*
	$dgid 不支持多ID
*/
function daigou_goods_save($dgid='',$tmp='',$dgdh='',$Mmy='')
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	if(!$dgid){return;}
	
	//更新商品列表
	if($dgid){
		$godh=FeData('daigou_goods','godh',"dgid='{$dgid}' order by goid desc");
		$i=substr($godh,-2);//截获最后一个单号的后面序号
	}
	
	$query="select * from daigou_goods where tmp<>'' and (tmp='{$tmp}' or tmpStaging='{$tmp}' or dgid='{$dgid}') {$Mmy} order by goid asc";
	$sql=$xingao->query($query);
	while($gd=$sql->fetch_array())
	{
		$i++;
		$godh=$dgdh.Digit($i,2);
		$xingao->query("update daigou_goods set dgid='{$dgid}',godh='{$godh}',tmp='',tmpStaging='' where goid='{$gd['goid']}' {$Mmy}");
		SQLError('更新商品');
	}
	
	//计算更新代购单商品总价
	daigou_goodsFee($dgid);
}




//相关更新:商品goodsFee总价,支付情况-------------------------------------------------------------------------
/*
	$dgid 	更新ID,支持多个
	$typ=0  更新全部
	$typ=1  更新goodsFee和代购单支付情况
	
*/
function daigou_Update($dgid,$typ=0)
{
	if(!$dgid){return;}
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	
	if(!$typ||$typ==1)
	{
		//计算更新代购单商品goodsFee总价
		daigou_goodsFee($dgid);

		//更新支付情况
		$query="select dgid,freightFeePay,freightFee from daigou where dgid in ({$dgid})";
		$sql=$xingao->query($query);
		while($rs=$sql->fetch_array())
		{
			$totalFee=daigou_totalFee($rs);//此单全部费用:价格币
			$totalPay=daigou_totalPay($rs);//已支付:价格币
			$pay_money=spr($totalFee-$totalPay);//要付的费用(总费用－已付费用)
			
			//$pay 0=未支付;1=已付清;2=未付清;3=已多付
			if($totalPay>0){
				if($pay_money==0){$pay=1;}
				elseif($pay_money>0){$pay=2;}
				elseif($pay_money<0){$pay=3;}
			}else{
				$pay=0;
			}

			$xingao->query("update daigou set pay='{$pay}',payDiffer='{$pay_money}' where dgid='{$rs['dgid']}'");
		}
	}
}


//计算更新代购单商品总价,代购单pay状态-----------------------------------
/*
	$dgid 支持多ID
	可用上面的:daigou_Update()
*/
function daigou_goodsFee($dgid)
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	if(!$dgid){return;}
	$query="select brandDiscount,serviceRate,dgid from daigou where dgid in ({$dgid})";
	$sql=$xingao->query($query);
	while($rs=$sql->fetch_array())
	{
		//更新商品总价----------------------------
		$query_go="select number,price,goid from daigou_goods where dgid='{$rs['dgid']}'";
		$sql_go=$xingao->query($query_go);
		while($gd=$sql_go->fetch_array())
		{
			$goodsFee=daigou_goodsFee_calc('',$rs['brandDiscount'],$rs['serviceRate'],$gd);
			$xingao->query("update daigou_goods set goodsFee='{$goodsFee}' where goid='{$gd['goid']}'");
			SQLError('更新商品总价');
		}
	}
}


//获取商品相关费用:单价币种-----------------------------------
function daigou_GetTotal_goodsFee($dgid)
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	if(!$dgid){return;}
	
	$gd=FeData('daigou_goods',"count(*) as total,sum(`goodsFee`) as goodsFee,sum(`goodsFeePay`) as goodsFeePay,sum(`goodsFeePayTo`) as goodsFeePayTo","dgid='{$dgid}'");
	
	return $gd;
}




//计算商品总费(商品单价*商品数*品牌折扣)+手续费 (不含运费):单价币种-----------------------------------
/*
	$brandDiscount='',$serviceRate=''非真空时优先用此值,否则用$dgid
	$number='',$price=''非真空时优先用此值,否则用$gd
*/
function daigou_goodsFee_calc($dgid='',$brandDiscount='',$serviceRate='',$gd='',$number='',$price='')
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	
	if(!CheckEmpty($brandDiscount)||!CheckEmpty($serviceRate))
	{
		if($dgid){$dg=FeData('daigou','brandDiscount,serviceRate',"dgid='{$dgid}'");}
		if(!CheckEmpty($brandDiscount)){$brandDiscount=$dg['brandDiscount'];}
		if(!CheckEmpty($serviceRate)){$serviceRate=$dg['serviceRate'];}
	}

	if(!CheckEmpty($number)){$number=$gd['number'];}
	if(!CheckEmpty($price)){$price=$gd['price'];}
	
	$goodsFee=$price*$number;
	if(!$goodsFee){return 0;}
	if($brandDiscount>0&&$brandDiscount<10){$goodsFee*=$brandDiscount/10;}//算折扣:0.X折扣
	if($serviceRate>0&&$serviceRate<100){$goodsFee+=$goodsFee*($serviceRate/100);}//算手续费:X%百分比
	return spr($goodsFee);
}



//此单全部费用：返回价格币--------------------------------------------------------------------------------
/*
	按当天汇率单价币种兑换网站主币:因此会经常有未付清或多付显示,因此要用pay判断,如果已经支付,则不再显示是否少付多付
	
	$freight=1 是否加运费
*/
function daigou_totalFee($rs,$freight=1)
{
	$gd=daigou_GetTotal_goodsFee($rs['dgid']);
	$total=$gd['goodsFee'];//总共商品费和服务费:单价币种
	if($freight){$total+=$rs['freightFee'];}//主单时算运费
	return spr($total);
}




//已支付费用-------------------------------------------------------------------------
/*
	$toCurrency='1' 返回支付币
	$toCurrency='0' 返回价格币
	
	$totalFee=daigou_totalFee($rs);//此单全部费用:价格币
	$totalPay=daigou_totalPay($rs);//已支付:价格币
	$totalPayTo=daigou_totalPay($rs,1);//已支付:支付币
	$toCurrency=cadd($rs['toCurrency']);//支付币
	$pay_money=spr($totalFee-$totalPay);//要付的费用(总费用－已付费用)
*/
function daigou_totalPay($rs,$toCurrency='0')
{
	//总费=商品费+服务费+运费
	$gd=daigou_GetTotal_goodsFee($rs['dgid']);
	if(!$toCurrency){$totalPay=$gd['goodsFeePay']+$rs['freightFeePay'];}
	else{$totalPay=$gd['goodsFeePayTo']+$rs['freightFeePayTo'];}
	return spr($totalPay);
}

























//---------------------------------------------------------------------------------------------
//------------------------------------------下单发货相关-------------------------------------------
//---------------------------------------------------------------------------------------------
//更新代购商品数量
/*
	$yd 数组或ydid
	
	$typ=1 减掉库存
	$typ=0 退回库存
*/
function daigou_updateNumber($yd='',$typ=1)
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	
	if(!$yd){exit("<script>alert('开发错误:daigou_updateNumber $yd 空 (DG1443)');goBack('c');</script>");}

	if(!$yd['ydid']){$yd=FeData('yundan','*',"ydid='{$yd}'");}
	
	$arr=ToArr($yd['goid']);
	if($arr)
	{
		foreach($arr as $key=>$value)
		{
			$sum=FeData('wupin',"count(*) as total,sum(`wupin_number`) as wupin_number","fromtable='yundan' and fromid='{$yd['ydid']}' and goid='{$value}'");
			
			$number=spr($sum['wupin_number']);
			if(!$number){continue;}

			if($typ==1)
			{
				//减掉库存
				$save="number=number-{$number},inventoryNumber=inventoryNumber-{$number},deliveryNumber=deliveryNumber+{$number}";
			}elseif(!$typ){
				//退回库存
				$save="number=number+{$number},inventoryNumber=inventoryNumber+{$number},deliveryNumber=deliveryNumber-{$number}";
			}
			
			$xingao->query("update daigou_goods set {$save} where goid='{$value}' ");
		}
	}
}



//可以发货的SQL
/*
	$typ=1 返回SQL ($dg,$gd可空)
	$typ=0 返回IF条件 ($dg可空,空时自动查询,$gd要有数组)
*/
function daigou_deliveryAsk($dg='',$gd='',$typ=0)
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	global $Mmy;
	
	if($typ==1){
	   return "
		   number>0 and
		   inventoryNumber>0 and
		   memberStatus=0 and
		   dgid in (select dgid from daigou where status in (8,9) {$Mmy})
		";
		
	}elseif($typ==0){
		global $rs;
		if(!$dg){$status=FeData('daigou','status',"dgid='{$gd['dgid']}' {$Mmy}"); }else{$status=spr($dg['status']);}
		if(
			have($status,'8,9') &&
			$gd['number']>0 &&
			$gd['inventoryNumber']>0 && 
			$gd['memberStatus']==0
		)
		{return 1;}else{return 0;}
		
	}
}





//单个商品ID时:自动获取同收件人所有商品================
function daigou_getGdid($goid)
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	global $Mmy;
	
	if(!$goid){exit("<script>alert('goid空,getGdid开发错误 FD1488');goBack('c');</script>");}

	if(!arrcount($goid)==1){return $goid;}
	
	//获取主商品相关资料
	$delivery_where=whereCHK(daigou_deliveryAsk('','',1),'and');
	$gd=FeData('daigou_goods','dgid,goid,addid',"goid='{$goid}' {$delivery_where} {$Mmy}");
	if(!$gd['goid']){exit("<script>alert('{$LG['daigou.180']}');goBack('c');</script>");}
	
	//获取代购单相关资料
	$rs=FeData('daigou','dgid,warehouse',"dgid='{$gd['dgid']}'");
	
	$query="select * from daigou_goods where  addid='{$gd['addid']}' and number>0 {$Mmy}
	and dgid in (select dgid from daigou where warehouse='{$rs['warehouse']}' )
	";
	$sql=$xingao->query($query);
	while($gd=$sql->fetch_array())
	{
		$err=0;
		if($gd['inventoryNumber']<$gd['number']){$delivery_no.=cadd($gd['godh']).',';$inventoryNumber++;$err=1;}
		if($gd['memberStatus']!=0){$delivery_no.=cadd($gd['godh']).',';$memberStatus++;$err=1;}
		if(!$err){$goid.=','.$gd['goid'];}
	}
	
	if(!$goid){exit("<script>alert('{$LG['daigou.180']}');goBack('c');</script>");}
	
	//同收件人还有不可发货商品时提示
	if($delivery_no){$delivery_no=ToStr(array_unique(ToArr(DelStr($delivery_no))));}
	if($delivery_no)
	{
		$ppt=$delivery_no.'\\n';
		if($inventoryNumber){$ppt.=LGtag($LG['daigou.181'],'<tag1>=='.$inventoryNumber).'\\n';}//该收货人还有<tag1>个商品,数量未全入库!
		if($memberStatus){$ppt.=LGtag($LG['daigou.182'],'<tag1>=='.$memberStatus).'\\n';}//该收货人还有<tag1>个商品,在申请操作中!
		exit ("<script>if(confirm('{$ppt}{$LG['daigou.183']}')){location='/xamember/yundan/form.php?addSource=7&goid={$goid}';}else{goBack('c');}</script>");//确定还要下单吗?
	}
}








//验证可发货================
function daigou_deliveryCHK($goid)
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	global $Mmy;

	if(!$goid){exit("<script>alert('goid空,deliveryCHK开发错误 FD1530');goBack('c');</script>");}

	$query="select * from daigou_goods where goid in ({$goid}) {$Mmy} ";
	$sql=$xingao->query($query);
	while($gd=$sql->fetch_array())
	{
		$err=0;
		
		//获取代购单相关资料:检查是否是同一个仓库的商品
		$rs=FeData('daigou','status,warehouse',"dgid='{$gd['dgid']}'");
		if(!$warehouse){$warehouse=$rs['warehouse'];}
		elseif($warehouse!=$rs['warehouse']){exit("<script>alert('{$LG['daigou.191']}');goBack('c');</script>");}
		
		if(!daigou_deliveryAsk($rs,$gd)){$delivery_no.=cadd($gd['godh']).',';$i++;$err=1;}
		if(!$err){$goid_now.=$gd['goid'].',';}
	}
	
	//提示
	$goid=DelStr($goid_now);
	if(!$goid){exit("<script>alert('{$LG['daigou.180']}');goBack('c');</script>");}

	$delivery_no=ToArr(DelStr($delivery_no)); if($delivery_no){$delivery_no=ToStr(array_unique($delivery_no));}
	if($delivery_no)
	{
		$ppt=$delivery_no.'\\n';
		$ppt.=LGtag($LG['daigou.184'],'<tag1>=='.$i).'\\n';//有x个商品暂时不能发货(可能在申请操作或未入库或其他原因)
		
		exit ("<script>if(confirm('{$ppt}{$LG['daigou.185']}')){location='/xamember/yundan/form.php?addSource=7&goid={$goid}';}else{goBack('c');}</script>");//还要对可发货的商品进行下单吗?
	}
}
	







//验证数量================
/*
	$goid,$number 元素位置要对应
*/
function daigou_numberCHK($goid,$number)
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	global $Mmy;

	if(!$goid){exit("<script>alert('goid空,numberCHK开发错误 FD1573');goBack();</script>");}
	if(!$number){exit("<script>alert('{$LG['yundan.30']}');goBack();</script>");}
	
	$arr=ToArr($goid);
	if($arr)
	{
		foreach($arr as $key=>$value)
		{
			if(!$value){continue;}
			$gd=FeData('daigou_goods',"godh,inventoryNumber","goid='{$value}' {$Mmy}");
			if($number[$key]>$gd['inventoryNumber']){exit("<script>alert('{$gd['godh']}{$LG['daigou.186']}');goBack();</script>");}
		}
	}
}


//验证是否可发货:验证各类限制
/*
	返回:不可发货时,直接提示并停止关闭
	
	$typ=0 全部验证 (需要有$weight)
	$typ=1 只限制种数
	$typ=2 只限制重量 (需要有$weight)
*/
function daigou_deliveryLimit($goid,$typ=0,$weight=0)
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	global $Mmy,$Mgroupid,$member_per;
 	$go_number=arrcount($goid);
	
	
	//发货限制:种数============
	if(!$typ||$typ==1)
	{
		if($go_number>1&&$go_number>$member_per[$Mgroupid]['dg_DeliveryLimitNumber']&&$member_per[$Mgroupid]['dg_DeliveryLimitNumber']>0)
		{
			exit ("<script>alert('".LGtag($LG['yundan.form_7_1'],'<tag1>=='.$member_per[$Mgroupid]['dg_DeliveryLimitNumber'])."');goBack('uc');</script>");
		}
	}
	
	//发货限制:重量============
	if(!$typ||$typ==2)
	{
		if($go_number>1&&$weight>$member_per[$Mgroupid]['dg_DeliveryLimitWeight']&&$member_per[$Mgroupid]['dg_DeliveryLimitWeight']>0)
		{
			exit ("<script>alert('".LGtag($LG['yundan.form_8_1'],'<tag1>=='.($member_per[$Mgroupid]['dg_DeliveryLimitWeight']).$XAwt)."');goBack('uc');</script>");
		}
	}
}



//计算汇率基数:返回实际要使用的汇率-------------------------------------------------------------------------
function daigou_exchangeBase($exchange,$userid)
{
 	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');	
	global $member_per;
	if(!$userid){return $exchange;}
	$groupid=FeData('member','groupid',"userid='{$userid}'");
	$exchangeBase=$member_per[$groupid]['dg_exchangeBase'];
	
	if($exchangeBase!=0){$exchange+=($exchange*($exchangeBase/100));}
	return $exchange;
}
?>