<?php 
$save='fx='.(int)$_POST['fx'].',edittime=edittime';//清空之前的$save

//合箱状态：已合箱================================
if((int)$_POST['hx']==2&&$rs['hx']==1&&$rs['hx_id'])
{
	if($_POST['hx_fj'])//附加数据
	{
		$content_bgydh='';

		//获取次包裹资料
		$query_son="select * from baoguo where bgid in ({$rs[hx_id]})";
		$sql_son=$xingao->query($query_son);
		while($son=$sql_son->fetch_array())
		{
			$weight+=$son['weight'];
			if($content_bgydh){$content_bgydh.=";".$son['bgydh']." (id:".$son['bgid'].")";}else{$content_bgydh=$son['bgydh']." (id:".$son['bgid'].")";}//备注
			if($content){$content.="
			".$son['content'];}else{$content=$son['content'];}//备注
		}
		
		wupin_run($zheng=1,$fromtable='baoguo',$fromid=$bgid,$fromtable_new='baoguo',$fromid_new=$rs['hx_id']);
		
		//主包裹-要更新的内容
		if($content_bgydh)
		{
			$content='由'.$content_bgydh.'包裹合箱一起
			'.$content;
		}
		$save.=",addSource='5',weight='".spr($weight,3)."',content='".html($content)."'";
	}//if($_POST['hx_fj'])//附加数据
	
	//发通知设置
	$send_msg=$baoguo_hx_msg;
	$send_mail=$baoguo_hx_mail;
	$send_sms=$baoguo_hx_sms;
	$send_wx=$baoguo_hx_wx;
	$MLG=memberLT($se['userid']);
	
	$send_title=LGtag($MLG['baoguo.send_9'],'<tag1>=='.cadd($rs['bgydh']));//发站内信和邮箱必须有
}//if((int)$_POST['hx']==2&&$rs['hx']==1&&$rs['hx_id'])
//合箱状态：已合箱－结束================================


//合箱状态：拒绝合箱================================
if((int)$_POST['hx']==3&&$rs['hx']==1&&$rs['hx_id'])
{
	//更新次包裹，从记录包裹取出
	$xingao->query("update baoguo set status='3' where bgid in ({$rs[hx_id]})");
	SQLError('更新次包裹');
	
	//主包裹-要更新的内容
	$save.=",hx_id=''";
	
	//发通知设置
	$send_msg=$baoguo_hx_msg;
	$send_mail=$baoguo_hx_mail;
	$send_sms=$baoguo_hx_sms;
	$send_wx=$baoguo_hx_wx;
	$MLG=memberLT($se['userid']);
	
	$send_title=LGtag($MLG['baoguo.send_10'],'<tag1>=='.cadd($rs['bgydh']));//发站内信和邮箱必须有
	
}//if((int)$_POST['hx']==3&&$rs['hx']==1&&$rs['hx_id'])
//合箱状态：拒绝合箱－结束================================















//分箱状态：已分箱================================
if($_POST['fx']==2&&$rs['fx']==1)
{
	//验证分箱资料
	$mvf_record_j=$_POST['mvf_record_j'];//分箱id
	$mvf_record_bgydh=$_POST['mvf_record_bgydh'];//分箱单号
	$mvf_record_weight=$_POST['mvf_record_weight'];//分箱重量
	$mvf_record_weizhi=$_POST['mvf_record_weizhi'];//仓位
	$mvf_record_fx_suo=$_POST['mvf_record_fx_suo'];//锁定
	
	$keyshow=0;
	foreach($mvf_record_j as $key=>$value)//输出
	{
		$keyshow=$key+1;
		if(!$mvf_record_bgydh[$key])
		{ 
			exit ("<script>alert('第 ".$keyshow." 个分箱包裹 没填写运单号，每个分箱要填写运单号！');goBack();</script>");
		}
		
	}
	
	foreach($mvf_record_bgydh as $key=>$value)//输出
	{
		$num=mysqli_num_rows($xingao->query("select bgydh from baoguo where bgydh='{$value}' "));
		if($num){exit ("<script>alert('".$value."分箱单号已存在,请修改！');goBack();</script>");}
	}

	//添加分箱包裹：处理分箱物品、单号、重量
	$arr=cadd($rs['fx_wupin']);
	if($arr)
	{
		$i=0;
		$zb_zl=$weight;
		$fromid_fx='';
		
		if(!is_array($arr)){$arr=explode(",",$arr);}//转数组
		foreach($arr as $arrkey=>$value)
		{
			$i+=1;
			
			//获取分箱基本资料
			$fx_bgydh=$mvf_record_bgydh[$arrkey];
			$fx_weight=spr($mvf_record_weight[$arrkey],3);
			$fx_weizhi=$mvf_record_weizhi[$arrkey];
			$fx_fx_suo=(int)$mvf_record_fx_suo[$arrkey];
			$zb_zl-=$fx_weight;
			
			if($baoguo_qr){$status=3;}else{$status=2;}
			$fx_content='由'.cadd($rs['bgydh']).'(ID:'.$rs['bgid'].')的包裹分箱出来';

			//添加保存
			$xingao->query("insert into baoguo 
			(`bgydh`, `userid`, `username`, `status`, `addSource`, `warehouse`, `whPlace`, `weight`,  `wangzhan`, `wangzhan_other`,  `content`,  `fx_suo`, `rukutime`,  `addtime`) 
			
			values('".add($fx_bgydh)."','".add($_POST['userid'])."','".add($_POST['username'])."','".$status."','6','".$warehouse."','".add($fx_weizhi)."','".$fx_weight."','".add($_POST['wangzhan'])."','".add($_POST['wangzhan_other'])."','".html($fx_content)."','".$fx_fx_suo."','".$rs['rukutime']."','".time()."')");
			SQLError('分箱-添加包裹');
			
			//临时物品转正式物品
			$rid=mysqli_insert_id($xingao);
			$xingao->query("update wupin set fromtable='baoguo',fromid='{$rid}' where fromtable='{$value}'");
			SQLError('分箱-临时物品转正式物品');
			
			if($fromid_fx){$fromid_fx.=','.$rid;}else{$fromid_fx=$rid;}

	   }//foreach($arr as $arrkey=>$value)
	}//if($arr)

	
	if($fromid_fx)
	{	
		//更新主箱物品
		wupin_run($zheng=3,$fromtable='baoguo',$fromid=$bgid,$fromtable_fx='baoguo',$fromid_fx);
		
		//删除主箱物品:删除0数量物品
		wupin_run($zheng=5,$fromtable='baoguo',$fromid=$bgid);
	}

	
	//主包裹-要更新的内容
	$content.='
	该包裹已分箱过';
	$save.=",addSource='6',fx_wupin='',content='".html($content)."'";
	if($_POST['zb_zl']){$save.=",weight='".RepPIntvar($zb_zl)."'";}
	$save.=",bgydh='".$bgydh."A'";//在单号后面加A
	
	//发通知设置
	$send_msg=$baoguo_fx_msg;
	$send_mail=$baoguo_fx_mail;
	$send_sms=$baoguo_fx_sms;
	$send_wx=$baoguo_fx_wx;
	$MLG=memberLT($se['userid']);
	
	$send_title=LGtag($MLG['baoguo.send_11'],'<tag1>=='.cadd($rs['bgydh']));//发站内信和邮箱必须有
}//if($_POST['fx']==2&&$rs['fx']==1)




//拒绝分箱-开始================================
if($_POST['fx']==3&&$rs['fx']==1)
{
	//主包裹-要更新的内容
	$save.=",fx_wupin=''";
	
	//发通知设置
	$send_msg=$baoguo_fx_msg;
	$send_mail=$baoguo_fx_mail;
	$send_sms=$baoguo_fx_sms;
	$send_wx=$baoguo_fx_wx;
	$MLG=memberLT($se['userid']);
	
	$send_title=LGtag($MLG['baoguo.send_12'],'<tag1>=='.cadd($rs['bgydh']));//发站内信和邮箱必须有
}//if($_POST['fx']==3&&$rs['fx']==1)
//拒绝分箱－结束================================









//发通知－开始------------------------------------------------------------------------------------------------
if($send_msg||$send_mail||$send_sms||$send_wx)
{
	//获取发送通知内容
	$NoticeTemplate='xingao_hxfx';	
	require($_SERVER['DOCUMENT_ROOT'].'/public/NoticeTemplate.php');
	
	$send_file='';

	$send_userid=add($_POST['userid']);
	$send_username=add($_POST['username']);

	//发站内信息
	if($send_msg){SendMsg($send_userid,$send_username,$send_title,$send_content_msg,$send_file,$from_userid='',$from_username='',$new=1,$status=0,$issys=1,$xs=0);}
	//发邮件
	if($send_mail){SendMail($rsemail='',$send_title,$send_content_mail,$send_file,$issys=1,$xs=0,$send_userid);}
	//发短信
	if($send_sms){SendSMS($rsmobile='',$send_content_sms,$xs=0,$send_userid);}
	//发微信
	if($send_wx){SendWX($send_WxTemId,$send_WxTemName,$send_content_wx,$send_userid);}
}
//发通知结束------------------------------------------------------------------------------------------------









//更新主包裹--------------------------------------------------------------------------------------------------
$xingao->query("update baoguo set ".$save." where bgid='{$bgid}' ");
SQLError('合箱,分箱修改主包裹');	

?>