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


//获取,处理=====================================================
$lx=par($_REQUEST['lx']);
$ydid=par(ToStr($_REQUEST['ydid']));
$bgid=par($_POST['bgid']);
$calc=par($_POST['calc']);
$tokenkey=par($_POST['tokenkey']);
$goid=par(ToStr($_POST['goid']));
$wupin_number=par(ToStr($_POST['wupin_number']));
$addSource=spr($_POST['addSource']);
$copy=par($_POST['copy']);

//添加,修改=====================================================
if($lx=='add'||$lx=='edit')
{
	//通用验证------------------------------------
	if(!$copy){//复制新加时,可能会复制多个表单最后才一起提交,因此无法验证多个表单
	$token=new Form_token_Core();
	$token->is_token("yundan".$ydid,$tokenkey); //验证令牌密钥
	}
	
	//$classtag='1';//标记:同个页面,同个$classtype时,要有不同标记
	$classtype=3;//分类类型
	$classid=GetEndArr($_POST['classid'.$classtag.$classtype]);
	$_POST['classid']=$classid;

	if(!$_POST['warehouse']){exit ("<script>alert('请选择仓库！');goBack();</script>");}
	if(!$_POST['country']){exit ("<script>alert('请选择寄往国家！');goBack();</script>");}
	if(!$_POST['channel']){exit ("<script>alert('请选择渠道！');goBack();</script>");}
	
	if(!$_POST['s_name']){exit ("<script>alert('请填写收件人姓名！');goBack();</script>");}
	if(!$_POST['s_mobile_code']){exit ("<script>alert('请填写手机所属地区！');goBack();</script>");}
	if(!$_POST['s_mobile']){exit ("<script>alert('请填写手机号码！');goBack();</script>");}
	
	if(!($_POST['s_add_shengfen'].$_POST['s_add_chengshi'].$_POST['s_add_quzhen'].$_POST['s_add_dizhi'])){exit ("<script>alert('请填写地址资料！');goBack();</script>");}
	
	//验证地址资料
	$Receive=CheckReceive('yundan');
	if($Receive){exit ("<script>alert('{$Receive} (再次验证提示)');goBack();</script>");}

	//证件处理
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
			if(!$_POST['s_shenfenhaoma']){exit ("<script>alert('请填写身份证号码！');goBack();</script>");}
			if(!$_POST['s_shenfenimg_z']||!$_POST['s_shenfenimg_b']){exit ("<script>alert('请上传身份证！(需要两面)');goBack();</script>");}
		}
	}

	//生成单号
	if(!$_POST['ydh'])
	{
		$_POST['ydh']=OrderNo('yundan',$_POST['warehouse']);
	}else{
		$num=mysqli_num_rows($xingao->query("select ydid from yundan where ydh='".add($_POST['ydh'])."' and ydid<>'".spr($_POST['ydid'])."'"));
		if($num>0){exit ("<script>alert('运单号重复，请修改!');goBack();</script>");}
	}
		
	//客户要求:去掉一定要添加物品和地址的限制

	//在IF里面赋值并判断
	/*
	if($ppt=wupin_yz()){exit ("<script>alert('{$ppt}');goBack();</script>");}
	*/
	
	//为了安全,再次处理(重要)
	$wupin=wupin_morefield();
	if($wupin){
		$_POST['goodsdescribe']=goodsdescribe($wupin);//物品描述
		$declarevalue=declarevalue($_POST['declarevalue'],$wupin);//物品价值
		$insureamount=insureamount($_POST['warehouse'],$_POST['channel'],$_POST['insureamount'],$declarevalue,$_POST['declarevalue']);//保价
		$insurevalue=insurance($_POST['warehouse'],$_POST['channel'],$insureamount,0);//保价费
		$_POST['declarevalue']=$declarevalue;
		$_POST['insureamount']=$insureamount;
		$_POST['insurevalue']=$insurevalue;
	}
	
//添加=====================================================
	if($lx=='add')
	{
		permissions('yundan_ad',0,'manage','');
		
		//获取会员账号资料并赋值到POST,验证账号是否正确
		getMemberUser(1,0);

		//出库时间
		$_POST['chukutime']=ToStrtotime($_POST['chukutime_date'],$_POST['chukutime_time']);
		if($_POST['status']>4&&!$_POST['chukutime'])
		{
			$_POST['chukutime']=time();
		}

		//回复时间
		if($_POST['reply']){$_POST['replytime']=time();}
		
		
		$addtime=time();
		
		$_POST['adminId']=$Xuserid;
		$_POST['adminName']=$Xusername;

		$savelx='add';//调用类型(add,edit,cache)
		$getlx='POST';//获取类型(POST,GET,REQUEST,SQL)
		$alone='address_save_s,address_save_f,old_s_shenfenimg_z,old_s_shenfenimg_b,s_shenfenimg_z_add,s_shenfenimg_b_add,old_s_shenfenimg_z,old_s_shenfenimg_b,
		ydid,bg_number,go_number,baoguo_hx_fee,chukutime_date,chukutime_time,calc,copy,classid'.$classtag.$classtype.',
		menu_producer,menu_brand,menu_unit,menu_types,menu_key,menu_name,gdid,record,tag';//不处理的字段

		$digital='addSource,weightEstimate,prefer,kffs,op_bgfee1,op_bgfee2,op_wpfee1,op_wpfee2,op_ydfee1,op_ydfee2,op_free,op_overweight,insureamount,insurevalue,status,statusauto,pay,fx,country,classid,cardYdid';//数字字段
		$radio='op_freearr';//单选、复选、空文本、数组字段
		$textarea='content,goodsdescribe,reply,fx_content,manage_content';//过滤不安全的HTML代码
		$date='';//日期格式转数字
		$save=XingAoSave($_POST,$getlx,$savelx,$alone,$digital,$radio,$textarea,$html,$date);

		$xingao->query("insert into yundan (".$save['field'].",addtime) values(".$save['value'].",'{$addtime}')");
		
		SQLError('添加');
		$rc=mysqli_affected_rows($xingao);
		$ydid=mysqli_insert_id($xingao);
		wupin_save('yundan',$ydid,0);//要放到$rc=mysqli_affected_rows($xingao)的后面
		
		if($rc>0)
		{
			cardUseTime($_POST['cardYdid']);//代替证件-更新使用时间
			
			if($shenfenimg_z){CopyFile($_POST['s_shenfenimg_z_add'],$shenfenimg_z);}//复制图片
			if($shenfenimg_b){CopyFile($_POST['s_shenfenimg_b_add'],$shenfenimg_b);}//复制图片
			
			//添加状态
			$rs=FeData('yundan','*',"ydid=$ydid");//查询
			$update_status=par($_POST['status']);
			yundan_updateStatus($rs,$update_status,0,0);
			
			if(!$copy){//复制新加时,可能会复制多个表单最后才一起提交,因此无法验证多个表单
			$token->drop_token("yundan".$ydid); //处理完后删除密钥
			if($_POST['tag']){$_SESSION[$_POST['tag']]='';}
			}
			
			//更新或添加地址簿(添加成功才操作否则会重复复制上传文件)
			$address_save_s=par($_POST['address_save_s']);
			$address_save_f=par($_POST['address_save_f']);
			$Mmy=" and userid='{$_POST['userid']}'";$Muserid=spr($_POST['userid']);$Musername=add($_POST['username']);
			if($address_save_s)	{$sf='s';require($_SERVER['DOCUMENT_ROOT'].'/xingao/address/call/out_save.php');	}
			if($address_save_f)	{$sf='f';require($_SERVER['DOCUMENT_ROOT'].'/xingao/address/call/out_save.php');	}
			
			if(!$calc){exit("<script>alert('{$LG['yundan.save_14']}');goBack('c');</script>");}
			else{exit("<script>location='calc_fee.php?ydid={$ydid}';</script>");}
		}else{
			exit ("<script>alert('{$LG['pptAddFailure']}');goBack();</script>");
		}
	}
	
//修改=====================================================
	if($lx=='edit')
	{
		permissions('yundan_ed',0,'manage','');
		if(!$ydid){exit ("<script>alert('ydid{$LG['pptError']}');goBack();</script>");}
		
		$rs=FeData('yundan','*',"ydid=$ydid");//查询
		warehouse_per('ts',$rs['warehouse']);//验证可管理的仓库
		
		//会员
		if($rs['username']!=add($_POST['username'])||$rs['userid']!=add($_POST['userid']))
		{
			//获取会员账号资料并赋值到POST,验证账号是否正确
			getMemberUser(1,0);
		}
		
		//出库时间
		$_POST['chukutime']=ToStrtotime($_POST['chukutime_date'],$_POST['chukutime_time']);
		if(spr($rs['status'])<=4&&$_POST['status']>4&&!$_POST['chukutime'])
		{
			$_POST['chukutime']=time();
		}
		
		//变更仓库或渠道时
		if($rs['warehouse']!=$_POST['warehouse']||$rs['channel']!=$_POST['channel'])
		{
			$_POST['sendApi']='0';//设为未发送过清关资料
		}
		
		//有单个文件字段时需要处理(要放在XingAoSave前面)
		DelFile($onefilefield='s_shenfenimg_z','edit');
		DelFile($onefilefield='s_shenfenimg_b','edit');

		//更新
		$savelx='edit';//调用类型(add,edit,cache)
		$getlx='POST';//获取类型(POST,GET,REQUEST,SQL)
		$alone='address_save_s,address_save_f,old_s_shenfenimg_z,old_s_shenfenimg_b,s_shenfenimg_z_add,s_shenfenimg_b_add,old_s_shenfenimg_z,old_s_shenfenimg_b,
		ydid,bg_number,go_number,baoguo_hx_fee,chukutime_date,chukutime_time,calc,copy,classid'.$classtag.$classtype.',
		menu_producer,menu_brand,menu_unit,menu_types,menu_key,menu_name,gdid,record,tag';//不处理的字段
		
		$digital='addSource,weightEstimate,prefer,kffs,insureamount,insurevalue,chukutime,status,statusauto,pay,fx,country,classid,cardYdid';//数字字段
		$radio='op_freearr';//单选、复选、空文本、数组字段
		$textarea='content,goodsdescribe,reply,fx_content,manage_content';//过滤不安全的HTML代码
		$date='';//日期格式转数字
		$save=XingAoSave($_POST,$getlx,$savelx,$alone,$digital,$radio,$textarea,$html,$date);
		
		//回复时间
		if(html($_POST['reply'])!=$rs['reply']){$save.=",replytime='".time()."'";}
		
		$xingao->query("update yundan set ".$save." where ydid='{$ydid}'  {$Xwh}");
		SQLError('修改');		
		$rc=mysqli_affected_rows($xingao);
		wupin_save('yundan',$ydid,0);//要放到$rc=mysqli_affected_rows($xingao)的后面
		
		$token->drop_token("yundan".$ydid); //处理完后删除密钥
		if($_POST['tag']){$_SESSION[$_POST['tag']]='';}
		if($rc>0)
		{
			cardUseTime($_POST['cardYdid']);//代替证件-更新使用时间
			
			if($shenfenimg_z){CopyFile($_POST['s_shenfenimg_z_add'],$shenfenimg_z);}//复制图片
			if($shenfenimg_b){CopyFile($_POST['s_shenfenimg_b_add'],$shenfenimg_b);}//复制图片

			//添加状态
			$rs=FeData('yundan','*',"ydid=$ydid");//重新查询
			$update_status=spr($_POST['status']);
			yundan_updateStatus($rs,$update_status,0,0);
			
			//更新或添加地址簿(添加成功才操作否则会重复复制上传文件)
			$address_save_s=par($_POST['address_save_s']);
			$address_save_f=par($_POST['address_save_f']);
			$Mmy=" and userid='{$_POST['userid']}'";$Muserid=spr($_POST['userid']);$Musername=add($_POST['username']);
			if($address_save_s)	{$sf='s';require($_SERVER['DOCUMENT_ROOT'].'/xingao/address/call/out_save.php');	}
			if($address_save_f)	{$sf='f';require($_SERVER['DOCUMENT_ROOT'].'/xingao/address/call/out_save.php');	}
			
			$ts=$LG['pptEditSucceed'];
		}else{
			$ts=$LG['pptEditNo'];
		}
		if(!$calc){exit("<script>alert('{$ts}');goBack('c');</script>");}
		else{exit("<script>location='calc_fee.php?ydid={$ydid}';</script>");}
	}
	
	
//复制=====================================================
}elseif($lx=='copy'){
	permissions('yundan_ad',0,'manage','');
	$copy_number=spr($_POST['copy_number']);//分包数量
	if(!$ydid){exit ("<script>alert('ydid{$LG['pptError']}');goBack();</script>");}
	if(!$copy_number){exit ("<script>alert('请填写分包数量!');goBack();</script>");}
	
	
	$where="ydid in ({$ydid})  {$Xwh}";//主运单
	//$copy_number=1;//复制数量
	$callFrom='manage';//member:会员下单时; manage:后台复制
	require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/yundan/call/copyYundan.php');//复制处理
	
	
	$prevurl = isset($_SERVER['HTTP_REFERER']) ? trim($_SERVER['HTTP_REFERER']) : '';
	if($rc>0){
		exit("<script>alert('共复制{$rc}个运单！');location='{$prevurl}';</script>");
	}else{
		exit("<script>alert('复制失败！');location='{$prevurl}';</script>");
	}
	
//删除=====================================================
}elseif($lx=='del'){
	permissions('yundan_ed',0,'manage','');
	if(!$ydid){exit ("<script>alert('ydid{$LG['pptError']}');goBack();</script>");}
	
	//开启“长期保存记录”时禁止删除的状态
	if($off_delbak)
	{
		$delbak_status="and status<>'30'";
		$delbak_ts='\\n已开启“长期保存记录”功能，如果是正常记录则不能删除';
	}
	
	$where="ydid in ({$ydid}) and status in (-1,-2,0,1,30) {$delbak_status} {$Xwh}";
	$rc=0;
	//删除文件,查询包裹ID
	$query="select * from yundan where {$where} ".whereCS()."  ";
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
				$query_bg="select op_06_img,bgid from baoguo where bgid in ({$rs[bgid]}) and status in (4,9,10) ";
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
		$xingao->query("delete from yundan where ydid='{$rs[ydid]}' ".whereCS()." ");SQLError('删除主信息');
		wupin_del('yundan',$rs['ydid']);
		$rc+=1;
	}
	$prevurl = isset($_SERVER['HTTP_REFERER']) ? trim($_SERVER['HTTP_REFERER']) : 'list.php';
	if($rc>0){
		exit("<script>alert('{$LG['pptDelSucceed']}{$rc}');location='{$prevurl}';</script>");
	}else{
		exit("<script>alert('只能删除待审、无效(未通过审核)、完成的运单！{$delbak_ts}');location='{$prevurl}';</script>");
	}
	
}
?>