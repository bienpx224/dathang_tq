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
if(!defined('InXingAo'))
{
	exit('No InXingAo');
}


//统计等待操作的包裹数量
function baoguo_num_op()
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	global $Mmy;
	return NumData('baoguo','bgid','1=1 '.baoguo_fahuo(3).$Mmy);
}

//显示操作菜单
//----------------------------------------------------------------------------------------
function baoguo_op($field,$rs)
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	global $member_per,$Mgroupid;
	global $ON_ware,$fahuo,$off_tra_user,$off_hx,$off_fx,$off_baoguo_op_02,$off_baoguo_op_04,$off_baoguo_op_06,$off_baoguo_op_07,$off_baoguo_th,$off_baoguo_op_09,$off_baoguo_op_10,$off_baoguo_op_11,$off_edit_wp;


	
	//下单发货-----------------
	if($field=='fahuo')
	{
		if($fahuo)
		{ 
			echo '<li><a href="/xamember/baoguo/delivery.php?typ=0&bgid='.$rs['bgid'].'" target="_blank"><font class="red2"><i class="icon-plane"></i> '.$LG['function.26'].' </font></a></li>';
		}
	}
	//编辑物品-----------------
	elseif($field=='edit_wupin')
	{
		if($off_edit_wp)
		{ 
			if (spr($rs['status'])<4&&$rs['addSource']!=3)
			{
				echo '<li><a href="edit_wupin.php?bgid='.$rs['bgid'].'" target="_blank" ><i class="icon-edit"></i> '.$LG['function.27'].'</a></li>';
			}
		}
	}
	//合箱-----------------
	elseif($field=='hx')
	{
		if($off_hx)
		{ 
			if ($rs['hx']!=1&&$rs['hx_suo']!=1&&$rs['hx']!=1)
			{
				echo '<li><a href="hx.php?bgid='.$rs['bgid'].'" class="showdiv" target="XingAobox" title="'.$LG['function.28'].'"><i class="icon-dropbox"></i> '.baoguo_hx(1).'</a></li>';
			}
		}
	}
	//分箱-----------------
	elseif($field=='fx')
	{
		if($off_fx)
		{ 
			if ($rs['fx']!=1 &&$rs['hx']!=1&&$rs['fx_suo']!=1)
			{
				echo '<li><a href="fx.php?bgid='.$rs['bgid'].'&lx=start" class="showdiv" target="XingAobox"  title="'.$LG['function.29'].'"><i class="icon-sitemap"></i> '.baoguo_fx(1).'</a></li>';
			}
		}
	}
	
	//转移会员-----------------
	elseif($field=='tra_user')
	{
		if($off_tra_user)
		{ 
			echo '<li><a href="op.php?bgid='.$rs['bgid'].'&field='.$field.'" class="showdiv" target="XingAobox" ><i class="icon-user"></i> '.$LG['function.30'].'</a></li>';
		}
	}
	
	//仓储-----------------
	elseif($field=='ware')
	{
		if($ON_ware)
		{ 
			$value=1;
			echo '<li><a href="op.php?bgid='.$rs['bgid'].'&field='.$field.'&value='.$value.'" class="showdiv" target="XingAobox" ><i class="icon-inbox"></i> '.$LG['function.31'].'</a></li>';
		}
	}
	

	//退货-----------------
	elseif($field=='th')
	{
		$op_name='baoguo_'.$field;
		$rs_op=$rs[$field];
		if($off_baoguo_th)
		{ 
			if($rs_op!=1&&$rs_op!=2) 
			{
				$value=1;
				echo '<li><a href="op.php?bgid='.$rs['bgid'].'&field='.$field.'&value='.$value.'" class="showdiv" target="XingAobox"><i class="icon-share"></i> '.$op_name($value).'</a></li>';
			}
			
			elseif($rs_op==1)
			{
				$value=10;
				echo '<li><a href="op.php?bgid='.$rs['bgid'].'&field='.$field.'&value='.$value.'" style="color:#FF6600" class="showdiv" target="XingAobox" ><i class="icon-share"></i> '.$op_name($value).'</a></li>';
			}

		}
	}

	//其他通用-----------------
	else
	{
		$op_name='baoguo_op_'.$field;
		$off_baoguo='off_baoguo_op_'.$field;
		$rs_op=$rs['op_'.$field];
		
		if($$off_baoguo)//要用$$
		{ 
			if($rs_op!=1&&$rs_op!=2) 
			{
				$value=1;
				echo '<li><a href="op.php?bgid='.$rs['bgid'].'&field=op_'.$field.'&value='.$value.'" class="showdiv" target="XingAobox"><i class="icon-share"></i> '.$op_name($value).'</a></li>';
			}
			
			elseif($rs_op==1)
			{
				$value=10;
				echo '<li><a href="op.php?bgid='.$rs['bgid'].'&field=op_'.$field.'&value='.$value.'"  style="color:#FF6600" class="showdiv" target="XingAobox" ><i class="icon-share"></i> '.$op_name($value).'</a></li>';
			}
		
		}
	}
}

//显示操作申请状态
//----------------------------------------------------------------------------------------
function baoguo_op_show($field,$rs)
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');	
	$op_pay_ts='&#13;'.$LG['function.32'];
	
	//合箱-----------------
	if($field=='hx')
	{
		if ($rs['hx']){echo '<font title="'.spr($rs['hx_pay']).$XAmc.$op_pay_ts.'">'.baoguo_hx($rs['hx'],2).'</font>';}
	}
	
	//退货-----------------
	elseif($field=='th')
	{
		if ($rs['th']){echo '<font title="'.spr($rs['th_pay']).$XAmc.$op_pay_ts.'">'.baoguo_th($rs['th'],2).'</font>';}
	}
	
	//仓储-----------------
	elseif($field=='ware')
	{
		if ($rs['ware']){echo '<font title="'.spr($rs['ware_pay']).$XAmc.$op_pay_ts.'">'.$LG['function.40'].DateYmd($rs['ware_time']).'</font>';}
	}
	
	//分箱-----------------
	elseif($field=='fx')
	{
		if ($rs['fx']){echo '<font title="'.spr($rs['fx_pay']).$XAmc.$op_pay_ts.'">'.baoguo_fx($rs['fx'],2).'</font>';}
	}
	
	//其他通用-----------------
	else
	{
		$op_name='baoguo_op_'.$field;
		if ($rs['op_'.$field])
		{
			echo '<font title="'.spr($rs['op_'.$field.'_pay']).$XAmc.$op_pay_ts.'">'.$op_name($rs['op_'.$field],2).'</font>';
			if($field=='06'&&$rs['op_06_img'])//拍照
			{
				ShowImg($rs['op_06_img']);
			}
		}
	}
}

//op开头的通用操作保存
//----------------------------------------------------------------------------------------
function baoguo_op_save($money,$type)   
{  
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	global $field,$value,$bgid,$Mmy,$XAmc; 
	
	if($value==1||$value==10)//安全验证
	{
		//查询
		$query="select userid,username,bgydh,bgid,weight,warehouse,{$field} from baoguo where bgid in ({$bgid}) and status=3 and th<>2 {$Mmy}";
		$sql=$xingao->query($query);
		while($rs=$sql->fetch_array())
		{
			$update=1;
			if($field==$rs[$field]){$update=0;}//要改的值与目前值相同就不更新
			$set="{$field}='{$value}'";
			
			//特殊操作-开始------
			if($field=='op_04'&&$update)//转移仓库
			{
				global $warehouse;
				if(!$price){$price=$money;}
				
				$weight=$rs['weight'];
				if(!$weight){$weight=1;}
				
				$money=$price*$weight;//转移仓库时按重量计费(默认全部是按包裹数量计费)
				
				$set.=",op_04_warehouse='{$warehouse}'";
				if($warehouse==$rs['warehouse']){$update=0;}//仓库相同就不更新
			}
			
			if($field=='th'&&$update)//退货
			{
				global $th_requ,$_POST; 
				
				if(!$_POST['th_img']&&$_POST['old_th_img']){$_POST['th_img']=$_POST['old_th_img'];}
				if($th_requ){$set.=",th_requ='{$th_requ}'";}
				$set.=",th_img='".add($_POST['th_img'])."'";
			}
			//特殊操作-结束------
			
			//验证
			if($update)
			{
				//申请服务时扣费
				$money=spr($money);
				if($money>0&&$value==1&&$rs[$field]!=1)
				{
					$content=$LG['function.33'].$rs['bgydh'].' (ID:'.$rs['bgid'].')';//发信息可能用到
					
					MoneyKF($rs['userid'],$fromtable='baoguo',$fromid=$rs['bgid'],$fromMoney=$money,$fromCurrency='',
					$title=$rs['bgydh'],'',$type);
					
					$ts= $LG['function.41'].'<strong>'.$money.$XAmc.'</strong>';
					$set.=",{$field}_pay='-{$money}'";
				}
				
				echo '&raquo; '.$rs['bgydh'].$LG['function.42'].$ts.'<br>';
				
				//更新主表
				$xingao->query("update baoguo set {$set} where bgid='{$rs[bgid]}' and th<>2 {$Mmy}");
				SQLError('更新主表');
				$rc+=1;
			}else{
				echo '&raquo; '.$rs['bgydh'].$LG['function.43'].'<br>';
			}
			
		}
		SQLError('查询');
	}
	return $rc;
	
}


//筛选菜单
//----------------------------------------------------------------------------------------
function baoguo_Screening($field,$zhi='')
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	global $_GET,$callFrom,$Muserid,$search;
	
	$CN_where="and status in (2,3) and ware=0";
	if($callFrom=='member'){$CN_userid=$Muserid;}else{$CN_userid='';}

	$scr_act='default';
	if($field==par($_GET['field'])&&$zhi==par($_GET['zhi'])){$scr_act='info';}
	
	$baoguo_='baoguo_'.$field;
	
	echo  ' <button type="button" class="btn btn-'.$scr_act.'" onClick="location.href=\'?'.$search.'&so=1&status=ruku&field='.$field.'&zhi='.$zhi.'\';">';//按钮
	if($field=='tra_user'){echo $LG['function.44'];}else{echo $baoguo_($zhi);}//显示名称
	echo CountNum('baoguo',$field,$zhi,$CN_where,$CN_userid,'default');//统计数量
	echo '</button> ';//结束按钮
}




//是否可发货
//----------------------------------------------------------------------------------------
function baoguo_fahuo($lx)
{  
	global $off_baoguo_zxyd; 
	if($lx==1){
		global $rs; 
		//查询之后再判断
		$fahuo=1;
		if($off_baoguo_zxyd&&spr($rs['status'])!=1&&spr($rs['status'])!=3){$fahuo=0;}
		elseif(!$off_baoguo_zxyd&&spr($rs['status'])!=3){$fahuo=0;}
		elseif($rs['hx']==1||$rs['hx']==10){$fahuo=0;}
		elseif($rs['fx']==1||$rs['fx']==10){$fahuo=0;}
		elseif($rs['th']==1||$rs['th']==10||$rs['th']==2){$fahuo=0;}
		elseif($rs['op_02']==1||$rs['op_02']==10){$fahuo=0;}
		elseif($rs['op_04']==1||$rs['op_04']==10){$fahuo=0;}
		elseif($rs['op_06']==1||$rs['op_06']==10){$fahuo=0;}
		elseif($rs['op_07']==1||$rs['op_07']==10){$fahuo=0;}
		elseif($rs['op_09']==1||$rs['op_09']==10){$fahuo=0;}
		elseif($rs['op_10']==1||$rs['op_10']==10){$fahuo=0;}
		elseif($rs['op_11']==1||$rs['op_11']==10){$fahuo=0;}
		elseif($rs['ware']==1){$fahuo=0;}//是在仓储中
		return $fahuo; 
	}elseif($lx==2){
		//SQL查询时判断-返回可以发货的包裹
		if($off_baoguo_zxyd){$where_fahuo="and status in (0,1,3)";}else{$where_fahuo="and status in (3)";}
		$where_fahuo.="
		and hx<>1 and hx<>10
		and fx<>1 and fx<>10
		and th<>1 and th<>10 and th<>2
		and op_02<>1 and op_02<>10
		and op_04<>1 and op_04<>10
		and op_06<>1 and op_06<>10
		and op_07<>1 and op_07<>10
		and op_09<>1 and op_09<>10
		and op_10<>1 and op_10<>10
		and op_11<>1 and op_11<>10
		and ware=0
		";
		return $where_fahuo; 
	}elseif($lx==3){
		//SQL查询时判断-返回要操作的包裹
		$where_fahuo="
		and status in (2,3) and ware=0
		and (
		hx=1 
		or fx=1
		or tra_user=1
		or th in (1,10)
		or op_02 in (1,10)
		or op_04 in (1,10)
		or op_06 in (1,10)
		or op_07 in (1,10)
		or op_09 in (1,10)
		or op_10 in (1,10)
		or op_11 in (1,10)
		)
		";
		return $where_fahuo; 
	}
}

//显示存放时间
//$lx=0;显示 $lx=1 计算还有免费仓储天数
//----------------------------------------------------------------------------------------
function bg_ware_days($lx='',$rs='')
{
	global $ON_ware,$member_per;
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	if(!$rs){global $rs;}

	$ts= LGtag($LG['function.45'],'<tag1>=='.DateDiff(time(),$rs['rukutime'],'d') ).'<br>';
	
	if($rs['addSource']!="3"&&$rs['rukutime']>0)
	{
		if ($ON_ware)
		{
			
			$groupid=FeData('member','groupid',"userid='{$rs['userid']}'");
			$free_days=$member_per[$groupid]['bg_ware_freeDays'];//会员组免费存放天数
			$free_days_have=$free_days-DateDiff(time(),$rs['rukutime'],'d');//会员还有免费存放天数
			$free_days_have=(int)$free_days_have;
			
			if($free_days_have>=0)
			{
				$ts.= '<font class="gray2">'.LGtag($LG['function.46'],'<tag1>=='.$free_days_have).'</font>';
			}
		} 
		
		if(!$lx){echo $ts;}elseif($lx==1){return $free_days_have;}
	}
	elseif($rs['addSource']=="3"&&$rs['rukutime']>0)
	{
		$ts.= '<font class="gray2">'.$LG['function.47'].'</font>';
		if(!$lx){echo $ts;}
	}
}

//仓储计算费用
//$lx=0;显示 $lx=1 计算费用
//----------------------------------------------------------------------------------------
function bg_ware_fee($lx='',$rs='')
{ 
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	global $ON_ware,$member_per;
	if(!$rs){global $rs;}
	
	if($rs['addSource']!="3"&&$rs['rukutime']>0)
	{
		//获取计算时间---------------------------
		$free_days_have=bg_ware_days(1);//还有免费仓储天数
		$free_ware_time=strtotime('+'.$free_days_have.' days');//最后免费仓储时间
		$rs_ware_time=$rs['ware_time'];//存入仓储的时间
		
		$time=$rs_ware_time;//用来计算的时间
		if($free_ware_time>$rs_ware_time){$time=$free_ware_time;}
	
		//计算仓储天数---------------------------
		$date=DateDiff(time(),$time,'d');
		
		//计算费用---------------------------
		if($date>0)
		{
			$groupid=FeData('member','groupid',"userid='{$rs['userid']}'");
			$price=$member_per[$groupid]['bg_ware_price'];//会员组仓储费用
			$bg_ware_weight=$member_per[$groupid]['bg_ware_weight'];
			if($bg_ware_weight>0&&$rs['weight']>0)
			{
				$weight_price=$rs['weight']/$bg_ware_weight;
				if($weight_price>0){$money=$weight_price*$price*$date;}
				else{$money=$price*$date;}
			}else{
				$money=$date*$price;
			}
			
			$money=abs(spr($money));
			$ts=LGtag($LG['function.48'],'<tag1>=='.$date);
		}else{
			$money=0;
		}
		
		if(!$lx){echo $money.$XAmc.$ts;}elseif($lx==1){return $money;}
	}
	
} 


//计算包裹合箱费用和合箱发货费用
//----------------------------------------------------------------------------------------
function baoguo_hx_fee($num,$userid,$groupid='',$fh=0)
{
	global $member_per;
	$money=0;
	if($num>0)
	{
		if(!$groupid){$groupid=FeData('member','groupid',"userid='{$userid}'");}//为提高下面效率,先查询组ID
		
		if(!$fh)//包裹合箱费用
		{
			$Price_hxsl=$member_per[$groupid]['Price_hxsl'];
			$Price_hx1=$member_per[$groupid]['Price_hx1'];
			$Price_hx2=$member_per[$groupid]['Price_hx2'];
		}elseif($fh){//合箱发货费用
			$Price_hxsl=$member_per[$groupid]['Price_fh_hxsl'];
			$Price_hx1=$member_per[$groupid]['Price_fh_hx_fee1'];
			$Price_hx2=$member_per[$groupid]['Price_fh_hx_fee2'];
			$Price_way1=$member_per[$groupid]['Price_fh_hx_fee1_way'];
			$Price_way2=$member_per[$groupid]['Price_fh_hx_fee2_way'];
			$feesl=$member_per[$groupid]['Price_fh_feesl'];
			if($num<=$feesl){return 0;}//X个以上才收费
		}
		
		//没超过优惠限制
		if($num<=$Price_hxsl){
			if($fh&&$Price_way1){
				//合箱发货时,并且只收一次时
				$money=$Price_hx1;
			}else{
				//非合箱发货时,或按包裹收费时
				$money=$Price_hx1*$num;//优惠价格＝优惠费用*总数量
			}
		}
		
		//超过优惠限制（超过部分用另一个价格算）
		else{
			//第一个费用
			if($fh&&$Price_way1){
				//合箱发货时,并且只收一次时
				$money1=$Price_hx1;
			}else{
				//非合箱发货时,或按包裹收费时
				$money1=$Price_hx1*$Price_hxsl;//优惠价格＝优惠费用*优惠数量
			}

			//第二个费用
			if($fh&&$Price_way2){
				//合箱发货时,并且只收一次时
				$money2=$Price_hx2;
			}else{
				//非合箱发货时,或按包裹收费时
				$money2=($num-$Price_hxsl)*$Price_hx2;//超过价格＝超过费用*超过数量
				
			}
			
			//通用
			$money=$money1+$money2;
		}
	}
	return spr($money);
}


//计算包裹分箱费用
//----------------------------------------------------------------------------------------
function baoguo_fx_fee($num,$userid)
{
	global $member_per;
	$money=0;
	if($num>0)
	{
		$groupid=FeData('member','groupid',"userid='{$userid}'");//为提高下面效率,先查询组ID
		$Price_fxsl=$member_per[$groupid]['Price_fxsl'];
		$Price_fx1=$member_per[$groupid]['Price_fx1'];
		$Price_fx2=$member_per[$groupid]['Price_fx2'];
		
		if($num<=$Price_fxsl){//没超过优惠限制
			$money=$Price_fx1*$num;//优惠价格＝优惠费用*总数量
		}else{//超过优惠限制（超过部分用另一个价格算）
			$money1=$Price_fx1*$Price_fxsl;//优惠价格＝优惠费用*优惠数量
			$money2=($num-$Price_fxsl)*$Price_fx2;//超过价格＝超过费用*超过数量
			$money=$money1+$money2;
		}
	}
	return spr($money);
}


//包裹入库通用处理
function baoguoInStorage($bgid)
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	if(!$bgid){return;}

	//包裹入库后更新为运单状态【已入库】
	$query="select bgid,ydid,status from yundan where (status=-1 or notStorage=1) and find_in_set('{$bgid}',bgid) ";
	$sql=$xingao->query($query);
	while($rs=$sql->fetch_array())
	{
		$status=0;//已上架(入库)
		$num=NumData('baoguo',"status<2 and  bgid in ({$rs[bgid]})");
		if($num){$status=-1;}//有未上架(不入库)

		if($status==0)
		{
			if(spr($rs['status'])==-2){$status=-2;}//如果 待预付 则不更新状态,只更新 已入库
			$xingao->query("update yundan set notStorage=0,status='{$status}' where ydid='{$rs[ydid]}'");
			SQLError('待入库时检查并更新为已入库');
		}
	}
}











//---------------------------------------------------------------------------------------------
//------------------------------------------下单发货相关-------------------------------------------
//---------------------------------------------------------------------------------------------
//验证可发货和基本处理
/*
	返回:$r['bgid']=可发货的包裹ID;		$r['warehouse']=仓库		$r['weightEstimate']=预估重量;		$r['addid']=地址ID
*/
function baoguo_deliveryCHK($bgid,$bg_zxyd=0)
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	global $Mmy;
	
	$where=baoguo_fahuo(2);
	if($bg_zxyd){$where.=" and status=0";}
	$query="select bgid,weight,warehouse,addid from baoguo where bgid in ({$bgid}) {$where} {$Mmy}";
	$sql=$xingao->query($query);
	while($rs=$sql->fetch_array())
	{
		if(!$r['warehouse']){$r['warehouse']=$rs['warehouse'];}
		elseif($r['warehouse']!=$rs['warehouse']){exit ("<script>alert('{$LG['yundan.form_4']}');goBack('c');</script>");}
		
		$r['bgid'].=$rs['bgid'].',';
		$r['weightEstimate']+=$rs['weight'];
		
		//地址ID相同时,自动读取地址
		if(!$r['addid']){$r['addid']=$rs['addid'];}elseif($r['addid']!=$rs['addid']){$r['addid']='err';}
	}
	$r['weightEstimate']=spr($r['weightEstimate']);
	$r['bgid']=DelStr($r['bgid']);
 	if(!$r['bgid']){exit ("<script>alert('{$LG['yundan.form_5']}');goBack('c');</script>");}
	return $r;
}			





//验证是否可发货:验证各类限制
/*
	返回:不可发货时,直接提示并停止关闭
*/
function baoguo_deliveryLimit($bgid)
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	global $Mmy,$Mgroupid,$member_per;
	
 	$bg_number=arrcount($bgid);
  
	//发货限制:分箱次数============
	$arr=ToArr($bgid);
	if($arr&&$member_per[$Mgroupid]['baoguo_fx']>0)
	{
		foreach($arr as $arrkey=>$value)
		{
			$num=NumData('yundan',"find_in_set('{$value}',bgid)");
			if($num>$member_per[$Mgroupid]['baoguo_fx'])
			{
				$_SESSION['bgid']='';
				exit ("<script>alert('".LGtag($LG['yundan.form_6'],
						'<tag1>=='.$member_per[$Mgroupid]['baoguo_fx'].'||'.
						'<tag2>=='.$value.'||'.
						'<tag3>=='.$member_per[$Mgroupid]['baoguo_fx']
						)."');goBack('c');</script>");
			}
		}
	}
	
	
	
	//发货限制:数量============
	if($bg_number>1&&$bg_number>$member_per[$Mgroupid]['baoguo_fh']&&$member_per[$Mgroupid]['baoguo_fh']>0)
	{
		exit ("<script>alert('".LGtag($LG['yundan.form_7'],'<tag1>=='.$member_per[$Mgroupid]['baoguo_fh'])."');goBack('c');</script>");
	}
	
	//发货限制:重量============
	if($bg_number>1&&$weightEstimate>$member_per[$Mgroupid]['baoguo_fh2']&&$member_per[$Mgroupid]['baoguo_fh2']>0)
	{
		exit ("<script>alert('".LGtag($LG['yundan.form_8'],'<tag1>=='.($member_per[$Mgroupid]['baoguo_fh2']).$XAwt)."');goBack('c');</script>");
	}
}



//物品现存数量:减掉运单发货中的数量
/*
	是从包裹下单并且当前是在下运单操作:自动减少分包中物品的数量
*/
function baoguo_wupin_number($wp)
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	
	
	if($wp['wupin_number']>0)
	{
		//查询分包运单ID
		$query_yd="select ydid from yundan where find_in_set('{$wp[fromid]}',bgid)";
		$sql_yd=$xingao->query($query_yd);
		while($yd=$sql_yd->fetch_array())
		{
			$ydid.=$yd['ydid'].',';
		}

		//查询该运单的物品ID:查询该物品数量
		$ydid=DelStr($ydid);
		if($ydid)
		{
			$query_wp2="select wupin_number from wupin where fromtable='yundan' and fromid in ({$ydid}) and wupin_type='{$wp['wupin_type']}' and wupin_name='{$wp['wupin_name']}' and wupin_brand='{$wp['wupin_brand']}' and wupin_spec='{$wp['wupin_spec']}'";
			$sql_wp2=$xingao->query($query_wp2);
			while($wp2=$sql_wp2->fetch_array())
			{
				//减掉数量
				$wp['wupin_number']=RepPIntvar($wp['wupin_number']-$wp2['wupin_number']);
				if($wp['wupin_number']<=0){break;}
			}
		}
		$wp['wupin_total']=spr($wp['wupin_price']*$wp['wupin_number']);
	}
	
	return $wp;
}

?>