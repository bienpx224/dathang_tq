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

require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/session.php');


//获取,处理=====================================================
$lx=par($_REQUEST['lx']);
$ydid=par(ToStr($_REQUEST['ydid']));
$bgid=par($_POST['bgid']);
$fx=par($_POST['fx']);
$bg_zxyd=par($_POST['bg_zxyd']);
$tokenkey=par($_POST['tokenkey']);
$goid=par(ToStr($_POST['goid']));
$wupin_number=par(ToStr($_POST['wupin_number']));
$addSource=spr($_POST['addSource']);


//添加,修改=====================================================
if($lx=='add'||$lx=='edit')
{
	//通用验证------------------------------------
	$token=new Form_token_Core();
	$token->is_token("yundan",$tokenkey); //验证令牌密钥
	
	if(!$_POST['channel']){exit ("<script>alert('{$LG['yundan.save_1']}');goBack();</script>");}
	if(!$_POST['warehouse']){exit ("<script>alert('{$LG['yundan.save_2']}');goBack();</script>");}
	if(($addSource==2||$ON_yundan_prepay)&&!spr($_POST['weightEstimate'])){exit ("<script>alert('{$LG['yundan.save_21']}');goBack();</script>");}
	if(!$_POST['s_name']){exit ("<script>alert('{$LG['yundan.save_3']}');goBack();</script>");}
	if(!$_POST['s_mobile_code']){exit ("<script>alert('{$LG['yundan.save_4']}');goBack();</script>");}
	if(!$_POST['s_mobile']){exit ("<script>alert('{$LG['yundan.save_5']}');goBack();</script>");}
	if(!($_POST['s_add_shengfen'].$_POST['s_add_chengshi'].$_POST['s_add_quzhen'].$_POST['s_add_dizhi'])){exit ("<script>alert('{$LG['yundan.save_6']}');goBack();</script>");}
	
	
	
	//验证地址资料
	$Receive=CheckReceive('yundan');
	if($Receive){exit ("<script>alert('{$Receive}');goBack();</script>");}

	
	if($off_shenfenzheng)
	{
		if(!$_POST['s_shenfenimg_z']&&$_POST['s_shenfenimg_z_add'])
		{
			$shenfenimg_z='/upxingao/card/'.DateYmd(time(),2).'/'.newFilename($_POST['s_shenfenimg_z_add']);
			$_POST['s_shenfenimg_z']=$shenfenimg_z;
		}elseif(!$_POST['s_shenfenimg_z']&&$_POST['old_s_shenfenimg_z']){
			$_POST['s_shenfenimg_z']=$_POST['old_s_shenfenimg_z'];
		}
		
		
		if(!$_POST['s_shenfenimg_b']&&$_POST['s_shenfenimg_b_add'])
		{
			$shenfenimg_b='/upxingao/card/'.DateYmd(time(),2).'/'.newFilename($_POST['s_shenfenimg_b_add']);
			$_POST['s_shenfenimg_b']=$shenfenimg_b;
		}elseif(!$_POST['s_shenfenimg_b']&&$_POST['old_s_shenfenimg_b']){
			$_POST['s_shenfenimg_b']=$_POST['old_s_shenfenimg_b'];
		}
	
		if(($off_upload_cert&&strtoupper($_POST['s_shenfenhaoma'])!='LATE')&&channelPar($_POST['warehouse'],$_POST['channel'],'shenfenzheng'))
		{
			if(!$_POST['s_shenfenhaoma']){exit ("<script>alert('{$LG['yundan.save_8']}');goBack();</script>");}
			if(!$_POST['s_shenfenimg_z']||!$_POST['s_shenfenimg_b']){exit ("<script>alert('{$LG['yundan.save_9']}');goBack();</script>");}
		}
	}

	//在IF里面赋值并判断
	if($ppt=wupin_yz()){exit ("<script>alert('{$ppt}');goBack();</script>");}
	
	//为了安全,再次处理(重要)
	$wupin=wupin_morefield();
	$_POST['goodsdescribe']=goodsdescribe($wupin);//物品描述
	$declarevalue=declarevalue($_POST['declarevalue'],$wupin);//物品价值
	$insureamount=insureamount($_POST['warehouse'],$_POST['channel'],$_POST['insureamount'],$declarevalue,$_POST['declarevalue']);//保价
	$insurevalue=insurance($_POST['warehouse'],$_POST['channel'],$insureamount,0);//保价费
	$_POST['declarevalue']=$declarevalue;
	$_POST['insureamount']=$insureamount;
	$_POST['insurevalue']=$insurevalue;
	
	
	

	//添加------------------------------------
	if($lx=='add')
	{
		
		//包裹:验证限制
		if($addSource==1)
		{
			$r=baoguo_deliveryCHK($bgid);//验证可发货
			baoguo_deliveryLimit($bgid);//验证限制
			//if($ON_yundan_prepay){$_POST['weightEstimate']=$r['weightEstimate'];}//为安全:再次获取重量 ,包裹没有重量时,就会错误
		
		//代购:验证限制
		}elseif($addSource==7){
			daigou_deliveryCHK($goid);//验证可发货
			daigou_numberCHK($goid,$wupin_number);//验证数量
			daigou_deliveryLimit($goid,0,$_POST['weightEstimate']);//验证限制
		}

		$fx_same=0;
		if($_POST['fx']&&$_POST['fx_same'])
		{
			$fx_same=spr($_POST['fx_same']);
			$copy_number=spr($_POST['fx_number'])-1;
		}
		
		$newydh=OrderNo('yundan',$_POST['warehouse']);//生成运单号
		
		$status=0;
		if($bg_zxyd||$_POST['addSource']==2){$status=-1;$_POST['notStorage']=1;}
		if($ON_yundan_prepay){$status=-2;}
		
		$statustime=time();
		$statusauto=0;
		if($off_statusauto&&$yd_statusauto){$statusauto=1;}
		$_POST['addtime']=time();
		$_POST['userid']=$Muserid;
		$_POST['username']=$Musername;
		
		$savelx='add';//调用类型(add,edit,cache)
		$getlx='POST';//获取类型(POST,GET,REQUEST,SQL)
		$alone='old_shenfenimg_z,old_shenfenimg_b,s_shenfenimg_z_add,s_shenfenimg_b_add,old_s_shenfenimg_z,old_s_shenfenimg_b,bg_zxyd,
		ydid,bg_number,go_number,baoguo_hx_fee,address_save_s,address_save_f,fx_number,fx_total,fx_same,
		menu_producer,menu_brand,menu_unit,menu_types,menu_key,menu_name,gdid,record,tag';//不处理的字段
		$digital='addSource,weightEstimate,prefer,kffs,op_bgfee1,op_bgfee2,op_wpfee1,op_wpfee2,op_ydfee1,op_ydfee2,op_free,op_overweight,fx';//数字字段
		$radio='op_freearr';//单选、复选、空文本、数组字段
		$textarea='content,goodsdescribe,fx_content';//过滤不安全的HTML代码
		$date='';//日期格式转数字
		$save=XingAoSave($_POST,$getlx,$savelx,$alone,$digital,$radio,$textarea,$html,$date);
		
		$save['field'].=',ydh,status,statustime,statusauto';
		$save['value'].=",'{$newydh}','{$status}','{$statustime}','{$statusauto}'";
		
		$xingao->query("insert into yundan (".$save['field'].") values(".$save['value'].")");
		SQLError('添加');
		$rc=mysqli_affected_rows($xingao);
		$ydid=mysqli_insert_id($xingao);
		wupin_save('yundan',$ydid,0);//要放到$rc=mysqli_affected_rows($xingao)的后面

		if($rc>0)
		{
			if($shenfenimg_z){CopyFile($_POST['s_shenfenimg_z_add'],$shenfenimg_z);}//复制图片
			if($shenfenimg_b){CopyFile($_POST['s_shenfenimg_b_add'],$shenfenimg_b);}//复制图片
			
			//更新包裹-开始
			if($bgid)
			{
				if($bg_zxyd){$status=1;}else{$status=4;}
				if($fx)
				{
					$fx_number=spr($_POST['fx_number'])-1;
					
					$arr=$bgid;
					if($arr&&$member_per[$Mgroupid]['baoguo_fx']>0)
					{
						if(!is_array($arr)){$arr=explode(",",$arr);}//转数组
						foreach($arr as $arrkey=>$value)
						{
							$num=mysqli_num_rows($xingao->query("select * from yundan where find_in_set('{$value}',bgid) "));
							if($num>=$member_per[$Mgroupid]['baoguo_fx'])
							{
								$xingao->query("update baoguo set status='{$status}' where bgid in ($value)");
								SQLError('更新单个包裹');
							}
						}
					}
					
				}
			
				
				if(!$fx||$fx_number==0||$fx_same)
				{
					$xingao->query("update baoguo set status='{$status}' where bgid in ($bgid)");
					SQLError('更新全部包裹');
					$_SESSION['bgid']='';
				}
			}
			//更新包裹-结束
			
			
			
			
			
			//更新代购商品-开始
			if($addSource==7)
			{
				//代购下单不支持分箱:因此未做分箱处理功能
				
				//减掉代购商品库存数量
				daigou_updateNumber($ydid,1);
			}
			//更新代购商品-结束
			
			
			

			
			//更新或添加地址簿(添加成功才操作否则会重复复制上传文件)
			$address_save_s=par($_POST['address_save_s']);
			$address_save_f=par($_POST['address_save_f']);
			if($address_save_s)	{$sf='s';require($_SERVER['DOCUMENT_ROOT'].'/xingao/address/call/out_save.php');	}
			if($address_save_f)	{$sf='f';require($_SERVER['DOCUMENT_ROOT'].'/xingao/address/call/out_save.php');	}

			//直接下单时,包裹下单,按预估费用预先计费
			if($ydid&&($_POST['addSource']==2||$ON_yundan_prepay))
			{
				$ppt.='\\n'.$LG['yundan.save_19']; 
				$calc=0;if($member_per[$Mgroupid]['off_zjxd_calc']||$ON_yundan_prepay){$calc=2;}//会员
				yundan_calc_save($ydid,$calc,1);
			}


			//地址相同时复制运单:分包运单
			if($fx_same&&$copy_number>0)
			{
				$where="ydid='{$ydid}'";//主运单
				//$copy_number=1;//复制数量
				$callFrom=='member';//member:会员下单时; manage:后台复制
				require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/yundan/call/copyYundan.php');//复制处理
			}
			


			$token->drop_token("yundan"); //处理完后删除密钥
			if($_POST['tag']){$_SESSION[$_POST['tag']]='';}
			
			if($fx&&$fx_number>0&&!$fx_same)
			{
				$fx_total=par($_POST['fx_total']);
				if(!$fx_total){$fx_total=par($_POST['fx_number']);}
				exit("<script>alert('".(LGtag($LG['yundan.save_11'],'<tag1>=='.($fx_total-$fx_number))).'\\n'.$LG['awb'].$newydh."');location='form.php?addSource=1&bgid={$bgid}&bg_zxyd={$bg_zxyd}&fx_total={$fx_total}&fx_number={$fx_number}';</script>");
				

			}else{
				if($ON_yundan_prepay){$status=-2;}elseif($_POST['addSource']==2){$status=-1;}else{$status=0;}			
				
				$ppt='\\n'.$LG['awb'].':'.$newydh; 
				if($copy_number){$ppt='\\n'.$LG['yundan.save_13'].($copy_number+1); }
				exit("<script>alert('{$LG['yundan.save_14']}\\n{$ppt}');location='list.php?status={$status}';</script>");
			}
		}else{
			exit ("<script>alert('{$LG['yundan.save_15']}');goBack();</script>");
		}
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	//修改------------------------------------
	if($lx=='edit')
	{
		if(!$ydid){exit ("<script>alert('ydid{$LG['pptError']}');goBack();</script>");}
		
		//有单个文件字段时需要处理(要放在XingAoSave前面)
		DelFile($onefilefield='s_shenfenimg_z','edit');
		DelFile($onefilefield='s_shenfenimg_b','edit');

		//更新
		$savelx='edit';//调用类型(add,edit,cache)
		$getlx='POST';//获取类型(POST,GET,REQUEST,SQL)
		$alone='old_shenfenimg_z,old_shenfenimg_b,s_shenfenimg_z_add,s_shenfenimg_b_add,old_s_shenfenimg_z,old_s_shenfenimg_b,bg_zxyd,
		ydid,bg_number,go_number,baoguo_hx_fee,address_save_s,address_save_f,fx,fx_number,fx_total,fx_same,
		menu_producer,menu_brand,menu_unit,menu_key,menu_types,menu_name,gdid,record,tag';//不处理的字段
		$digital='addSource,weightEstimate,prefer,kffs';//数字字段
		$radio='op_freearr';//单选、复选、空文本、数组字段
		$textarea='content,goodsdescribe,fx_content';//过滤不安全的HTML代码
		$date='';//日期格式转数字
		$save=XingAoSave($_POST,$getlx,$savelx,$alone,$digital,$radio,$textarea,$html,$date);
		$save.=",edittime=".time();
		$xingao->query("update yundan set ".$save." where ydid='{$ydid}' {$Mmy}");
		SQLError();		
		$rc=mysqli_affected_rows($xingao);
		
		wupin_save('yundan',$ydid,0);//要放到$rc=mysqli_affected_rows($xingao)的后面
		$token->drop_token("yundan"); //处理完后删除密钥
		if($_POST['tag']){$_SESSION[$_POST['tag']]='';}
		
		if($rc>0)
		{
			if($shenfenimg_z){CopyFile($_POST['s_shenfenimg_z_add'],$shenfenimg_z);}//复制图片
			if($shenfenimg_b){CopyFile($_POST['s_shenfenimg_b_add'],$shenfenimg_b);}//复制图片

			$ts=$LG['pptEditSucceed'];
			
			//更新或添加地址簿(添加成功才操作否则会重复复制上传文件)
			$address_save_s=par($_POST['address_save_s']);
			$address_save_f=par($_POST['address_save_f']);
			if($address_save_s)	{$sf='s';require($_SERVER['DOCUMENT_ROOT'].'/xingao/address/call/out_save.php');	}
			if($address_save_f)	{$sf='f';require($_SERVER['DOCUMENT_ROOT'].'/xingao/address/call/out_save.php');	}


		}else{
			$ts=$LG['pptEditNo'];
		}
		
		
		if($ON_yundan_prepay){$status=-2;}elseif($_POST['addSource']==2){$status=-1;}else{$status=0;}			
		
		exit("<script>alert('".$ts."');location='list.php?status={$status}';</script>");
	}
	
//修改属性=====================================================
}elseif($lx=='attr'){
	if(!$ydid){exit ("<script>alert('ydid{$LG['pptError']}');goBack();</script>");}

	$status=spr($_REQUEST['status']);

	if(!CheckEmpty($status)){exit ("<script>alert('status{$LG['pptError']}');goBack();</script>");}

	$query="select * from yundan where ydid in ({$ydid}) and status>=20 and status<>30 {$Mmy}";
	$sql=$xingao->query($query);
	while($rs=$sql->fetch_array())
	{
		yundan_updateStatus($rs,$status,0,0);
	}
	$prevurl = isset($_SERVER['HTTP_REFERER']) ? trim($_SERVER['HTTP_REFERER']) : '';
	exit("<script>location='".$prevurl."';</script>");
	
	
//签收=====================================================
}elseif($lx=='sign'){
		$rs=FeData('yundan','*',"ydid='{$ydid}' {$Mmy}");
		
		$signday=channelPar($rs['warehouse'],$rs['channel'],'signday');
		if($signday>0&&$rs['chukutime']&&$rs['chukutime']<=strtotime('-'.$signday.' days')&&spr($rs['status'])>4&&spr($rs['status'])<30)
		{
			yundan_updateStatus($rs,$update_status=30,0,0);
			
			$prevurl = isset($_SERVER['HTTP_REFERER']) ? trim($_SERVER['HTTP_REFERER']) : '';
			exit("<script>alert('{$LG['yundan.save_16']}');location='{$prevurl}';</script>");
		}else{
			exit("<script>alert('{$LG['yundan.save_17']}');goBack();</script>");
		}

//删除=====================================================
}elseif($lx=='del'){
	
	if(!$ydid){exit ("<script>alert('ydid{$LG['pptError']}');goBack();</script>");}
	
	//开启“长期保存记录”时禁止删除的状态
	if($off_delbak)
	{
		$delbak_status="and status<>'30'";
	}
	
	$where="ydid in ({$ydid}) and status in (-1,-2,0,1,30) {$delbak_status}";
	$rc=0;
	//删除文件,查询包裹ID
	$query="select * from yundan where {$where} {$Mmy}";
	$sql=$xingao->query($query);
	while($rs=$sql->fetch_array())
	{
		DelFile($rs['tax_img']);
		DelFile($rs['s_shenfenimg_z']);
		DelFile($rs['s_shenfenimg_b']);
		
		//未审核运单时更新关联表
		if(spr($rs['status'])!=30)
		{
			//退款
			if($rs['payment']>0){yundan_refund($rs);}
		
			//恢复包裹状态
			if($rs['bgid']){
				$num=NumData('yundan',"bgid='{$rs['bgid']}' and ydid<>'{$rs['ydid']}'");
				if(!$num){
					$xingao->query("update baoguo set status='3' where bgid in ($rs[bgid])");
					SQLError('更新包裹');
				}
			}
			
			//退回代购商品
			daigou_updateNumber($rs,0);
			
		}else{//已完成运单时删除包裹
			
			if($rs['bgid'])
			{
				$query_bg="select op_06_img,bgid from baoguo where bgid in ({$rs[bgid]}) and status in (4,9,10) {$Mmy}";
				$sql_bg=$xingao->query($query_bg);
				while($bg=$sql_bg->fetch_array())
				{
					DelFile($bg['op_06_img']);//删除文件
					$xingao->query("delete from baoguo where bgid='{$bg[bgid]}'");SQLError('删除包裹');
					wupin_del('baoguo',$bg['bgid']);
				}
			}
		}
		//删除状态记录
		$xingao->query("delete from yundan_bak where ydid='{$rs[ydid]}'");SQLError('删除状态记录表');
		//删除主信息
		$xingao->query("delete from yundan where ydid='{$rs[ydid]}'");SQLError('删除主信息');
		wupin_del('yundan',$rs['ydid']);
		$rc+=1;
	}
	$prevurl = isset($_SERVER['HTTP_REFERER']) ? trim($_SERVER['HTTP_REFERER']) : '';
	if($rc>0){
		exit("<script>alert('{$LG['pptDelSucceed']}{$rc}');location='{$prevurl}';</script>");
	}else{
		exit("<script>alert('{$LG['yundan.save_18']}');location='{$prevurl}';</script>");
	}
	
}
?>