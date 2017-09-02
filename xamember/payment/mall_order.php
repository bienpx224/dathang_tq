<?php 
//立即更新coupons和查询数量
$update_userid=$Muserid;$usetypes='0,2';require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/coupons/call/update.php');//返回$cp
$coupons_number=$cp['number'];

$pack=par($_POST['pack']);//打包方式

//获取订单信息
$query="select * from mall_order where odid in ({$payid})  and status<>'3' and pay='0' {$Mmy}";
$sql=$xingao->query($query);
while($rs=$sql->fetch_array())
{
	//通用获取资料---------------------------------------------------------------------------
	$integral_to=$rs['integral_to'];
	$integral_use=$rs['integral_use'];//是否用积分消费 
	$payment=$rs['payment'];//已付费用
	$title=$rs['title'];//商品名
	$userid= $rs['userid'];
	$username= $rs['username'];
	$number= $rs['number'];
	$mlid= $rs['mlid'];
	$odid=$rs['odid'];
	$pay_money=spr( spr($rs['price'])*$rs['number'] + spr($rs['price_other'])-$payment);//要付的费用(总费用－已付费用)
	if($pay_money<=0){exit("ID{$odid}:".$LG['payment.mall_order_1']);}
	$pay_total=$pay_money;

	if(!$nosame_warehouse)
	{
		if(!$warehouse_old){$warehouse_old=$rs['warehouse'];}
		elseif($warehouse_old!=$rs['warehouse']){$nosame_warehouse=1;}
	}
	
	$pay_money_total1=$pay_money_total1+$pay_money;//未算优惠券/折扣券和积分前要付的费用
	$integral_lx='mall';$coupons_calllx='query';
	require($_SERVER['DOCUMENT_ROOT'].'/xamember/payment/call/calc_coupons.php');//必须排前,优先用优惠券/折扣券
	require($_SERVER['DOCUMENT_ROOT'].'/xamember/payment/call/calc_integral.php');//积分运算,计算真正要付的费用

	//总计并用于显示
	$pay_money_total2+=$pay_money;//总共要付的费用
	$coupons_value_all+=$coupons_value;//总共可用优惠券/折扣券的元
	$coupons_use_number_all+=$coupons_use_number;//总共可用优惠券/折扣券的数量
	if($coupons_value>0)
	{
		if($cp_dixiao_yes){$cp_dixiao_yes.='、'.$odid;}else{$cp_dixiao_yes=$odid;}//可以使用的优惠券/折扣券
	}else{
		if($cp_dixiao_no){$cp_dixiao_no.='、'.$odid;}else{$cp_dixiao_no=$odid;}//不可以使用的优惠券/折扣券
	}
	
	
	$integral_jian_hb_all+=$integral_jian_hb;//总共可用的积分元
	$integral_user_all=$integral_user_all+$integral_user;//总共可用的积分
	if($integral_jian_hb>0)
	{
		if($dixiao_yes){$dixiao_yes.='、'.$odid;}else{$dixiao_yes=$odid;}//可以使用的积分信息ID
	}else{
		if($dixiao_no){$dixiao_no.='、'.$odid;}else{$dixiao_no=$odid;}//不可以使用的积分信息ID
	}
	
	//提交保存-开始-------------------------------------------------------------------------------------------------
	if($lx=='pay')
	{
		if(!CheckEmpty($pack)&&arrcount($payid)>1){exit ("<script>alert('{$LG['payment.mall_order_3']}');goBack();</script>");}
		
		if($pay_money>0)
		{
			//扣费
			MoneyKF($userid,'mall_order',$odid,$fromMoney=$pay_money,$fromCurrency='',
			$title,'',1);
			
			if ($integral_jian_hb>0)
			{
				//扣分
				integralKF($userid,'mall_order',$odid,$integral_user,$integral_jian_hb,$title,'',3);
			}
			
			//优惠券/折扣券 使用
			if($cpc['cpid'])
			{
				couponsKF($cpc,'mall_order',$odid,$coupons_value,$title,'');
			}
			
			//开始送积分
			if ($integral_song_hb>0)
			{
				 //会员加分
				 integralCZ($userid,'mall_order',$odid,$integral_song_hb,$title,'',5);
				 
				 //推广员加分
				 tuiguang_hqsf($userid,$integral_song_hb,'mall',$odid);
			}
		}
		
		//保存成功支付的ID,用于生成包裹
		if($ok_payid){$ok_payid.=",".$odid;}else{$ok_payid=$odid;}
		if($pack){if($ok_paywarehouse){$ok_paywarehouse.=",".$rs["warehouse"];}else{$ok_paywarehouse=$rs["warehouse"];}}
		

		//更新订单
		$xingao->query("update mall_order set pay=1,payment=payment+$pay_total,payment_time='".time()."' where odid='{$odid}' {$Mmy}");
		SQLError('更新订单');
		
		//更新商品出售数量
		$xingao->query("update mall set number_sold=number_sold+{$number} where mlid='{$mlid}'");
	
		$ts_pay=$title.'(ID'.$odid.'):'.$LG['havePay'].$pay_money.$XAmc;
		
		if ($coupons_value>0){$ts_pay.=LGtag($LG['payment.mall_order_6'],'<tag1>=='.$coupons_use_number ).$coupons_value.$XAmc;}
		if ($integral_jian_hb>0){$ts_pay.=LGtag($LG['payment.mall_order_7'],'<tag1>=='.$integral_user ).$integral_jian_hb.$XAmc;}
		if ($coupons_value||$integral_jian_hb){$ts_pay.=$LG['payment.mall_order_8'].$pay_total.$XAmc;}
		if ($integral_song_hb>0){$ts_pay.=LGtag($LG['payment.mall_order_9'],'<tag1>=='.$integral_song_hb );}

		
		//发通知－开始－－－－－－－－－
		$send_msg=$mall_order_pay_msg;
		$send_mail=$mall_order_pay_mail;
		$send_sms=$mall_order_pay_sms;
		$send_wx=$mall_order_pay_wx;
		
		if($send_msg||$send_mail||$send_sms||$send_wx)
		{
			$send_userid=$userid;
			$send_username=$username;
	
			//获取发送通知内容
			$NoticeTemplate='member_payment_mall_order';	
			require($_SERVER['DOCUMENT_ROOT'].'/public/NoticeTemplate.php');
	
			$send_file='';
			
			//发站内信息
			if($send_msg){SendMsg($send_userid,$send_username,$send_title,$send_content_msg,$send_file,$from_userid='',$from_username='',$new=1,$status=0,$issys=1,$xs=0);}
			//发邮件
			if($send_mail){SendMail($rsemail='',$send_title,$send_content_mail,$send_file,$issys=1,$xs=0,$send_userid);}
			//发短信
			if($send_sms){SendSMS($rsmobile='',$send_content_sms,$xs=0,$send_userid);}
			//发微信
			if($send_wx){SendWX($send_WxTemId,$send_WxTemName,$send_content_wx,$send_userid);}
		}
		//通知结束－－－－－－－－－
		
		echo '<font class="gray_prompt">&raquo;  '.$ts_pay."<br></font>";
	}
	//提交保存-结束-------------------------------------------------------------------------------------------------

}//while($rs=$sql->fetch_array())
if(!$odid)
{
	exit($LG['payment.mall_order_24']);
}

//生成包裹-开始-------------------------------------------------------------------------------------------------
if($ok_payid)
{
	$status=0;
	if($baoguo_ruku_od){$status=2;$field=",rukutime";$zhi=",'".time()."'";}
	if($baoguo_qr&&$status==2){$status=3;}

	//一个订单一个包裹
	if(!$pack)
	{
		$query="select * from mall_order where odid in ({$ok_payid}) and pay='1' {$Mmy}";
		$sql=$xingao->query($query);
		while($rs=$sql->fetch_array())
		{
			$bgid=NextId('baoguo');
			$save_bgydh=DateYmd(time(),'YmdHis').$bgid;echo $save_bgydh;
			$xingao->query("insert into baoguo (`bgydh`, `warehouse`, `wangzhan`, `wangzhan_other`,`weight`, `odid`,`fahuotime`,`addSource`, `status`,`addtime`, `userid`, `username` {$field}) values('".$save_bgydh."','".$rs['warehouse']."','other','{$LG['payment.mall_order_25']}','".($rs['number']*$rs['weight'])."','".$rs['odid']."','".$rs['payment_time']."','3','".$status."','".time()."','{$Muserid}','{$Musername}' {$zhi})");
			SQLError('单个生成包裹');
			
			//保存物品
			$fromtable='baoguo';
			$fromid=mysqli_insert_id($xingao);
			if($fromid>0)
			{
				$xingao->query("insert into wupin (fromtable,fromid,wupin_type,wupin_name,wupin_brand,wupin_spec,wupin_price,wupin_unit,wupin_weight,wupin_number,wupin_total) values ('".add($fromtable)."','".spr($fromid)."','".add($rs['category'])."','".add($rs['goods'])."','".add(classify($rs['brand'],2))."','".add($rs['spec'])."','".spr($rs['price'])."','".add($rs['unit'])."','0','".spr($rs['number'])."','".spr($rs['number']*$rs['price'])."')");
				SQLError('保存物品');
			}
			
			//更新订单
			$xingao->query("update mall_order set status=1,bgid='{$fromid}' where odid='$rs[odid]' ");

		}
	}
	//对于相同的存放仓库就打成一个包裹
	else
	{
		$arr=$ok_paywarehouse;
		if($arr)
		{
			if(!is_array($arr)){$arr=explode(",",$arr);}//转数组
			$arr=array_unique($arr);//删除重复数组
			foreach($arr as $arrkey=>$value)
			{

				//开始-----------------------------------
				$fromtable=make_NoAndPa(10);
				$fromid=0;
				
				$query="select * from mall_order where odid in ({$ok_payid}) and pay='1' and warehouse='{$value}' {$Mmy}";
				$sql=$xingao->query($query);
				while($rs=$sql->fetch_array())
				{
					//保存临时物品
					$xingao->query("insert into wupin (fromtable,fromid,wupin_type,wupin_name,wupin_brand,wupin_spec,wupin_price,wupin_unit,wupin_weight,wupin_number,wupin_total) values ('".add($fromtable)."','".spr($fromid)."','".add($rs['category'])."','".add($rs['goods'])."','".add(classify($rs['brand'],2))."','".add($rs['spec'])."','".spr($rs['price'])."','".add($rs['unit'])."','0','".spr($rs['number'])."','".spr($rs['number']*$rs['price'])."')");
					SQLError('保存临时物品');
					
					if($save_odid){$save_odid.=",".$rs["odid"];}else{$save_odid=$rs["odid"];}
					$save_weight=$save_weight+($rs['number']*$rs['weight']);
				}
				
				$bgid=NextId('baoguo');
				$save_bgydh=DateYmd(time(),'YmdHis').$bgid;
				
				$xingao->query("insert into baoguo (`bgydh`, `warehouse`, `wangzhan`, `wangzhan_other`,`weight`,  `odid`,`fahuotime`,`addSource`, `status`,`addtime`, `userid`, `username` {$field}) values('".$save_bgydh."','".$value."','other','{$LG['payment.mall_order_25']}','".$save_weight."','".$save_odid."','".time()."','3','".$status."','".time()."','{$Muserid}','{$Musername}'  {$zhi})");
				SQLError('多个商品生成一个包裹');
				
				//临时物品转正式物品
				$fromid=mysqli_insert_id($xingao);
				if($fromid>0)
				{
					$xingao->query("update wupin set fromtable='baoguo',fromid='".$fromid."' where fromtable='".$fromtable."'");SQLError('临时物品转正式物品');
				}else{
					$xingao->query("delete from wupin where fromtable='".$fromtable."' ");SQLError('添加包裹失败时删除已保存的物品');
				}
				
				//更新订单
				$xingao->query("update mall_order set status=1,bgid='{$fromid}' where odid in ({$save_odid}) ");
				
				$save_odid='';
				$save_weight=0;
				
				//结束-----------------------------------
				
				
			}//foreach($arr as $arrkey=>$value)
		}
	}
}
//生成包裹-结束-------------------------------------------------------------------------------------------------



//完成提示-------------------------------------------------------------------------------------------------
if($lx=="pay")
{
	$_SESSION["odid"]='';//支付成功,清空所保存的勾选
}



//输出表单-开始-------------------------------------------------------------------------------------------------
if($lx!="pay")
{
	if(arrcount($payid)>1)
	{
?>
<h4 class="modal-title"><strong><?=$LG['payment.mall_order_18'];//打包方式：?></strong></h4>
	<div class="form-group">
	<div class="radio-list">
	<label>
	<input type="radio"  value="1" name="pack"> <?=$LG['payment.mall_order_26'];//对于相同的存放仓库就打成一个包裹?> <?=$nosame_warehouse?'(<font class="red">'.$LG['payment.mall_order_27'].'</font>)':'('.$LG['payment.mall_order_28'].')' ?>
	</label>
	<label>
	<input type="radio" value="0"  name="pack"> <?=$LG['payment.mall_order_19'];//一个订单一个包裹?>
	</label>
	</div>
	<span class="help-block"><?=$LG['payment.mall_order_20'];//打包后如果要分箱或合箱可能需要另收费,请注意选择?></span>
	</div>
<?php 		
	}
	$ts='&raquo; <font class="red">'.$LG['payment.mall_order_21'].'</font><br>';
}
//输出表单-结束
?>