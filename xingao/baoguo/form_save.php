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
$pervar='baoguo_ed';require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');


//获取,处理------------------------------------
$lx=par($_POST['lx']);
$bgid=par($_POST['bgid']);
//<!--2017.06.17:删除dgid-->
$tokenkey=par($_POST['tokenkey']);

$status=par($_POST['status']);
$bgydh=par($_POST['bgydh']);
$warehouse=par($_POST['warehouse']);
$weight=spr($_POST['weight']);
$ware=par($_POST['ware']);
$content=$_POST['content'];

//通用验证------------------------------------
$token=new Form_token_Core();
$token->is_token("baoguo".$bgid,$tokenkey); //验证令牌密钥
if(!CheckEmpty($status)){exit ("<script>alert('请选择状态！');goBack();</script>");}
if(!$bgydh){exit ("<script>alert('请填写单号！');goBack();</script>");}
if(!$warehouse){exit ("<script>alert('请选择仓库！');goBack();</script>");}



//================================================================================================
//===========================================添加=================================================
//================================================================================================
if($lx=='add')
{
	//验证------------------------------------
	
	//获取会员账号资料并赋值到POST,验证账号是否正确
	getMemberUser(1,1);

	//验证运单号
	$num=mysqli_num_rows($xingao->query("select bgydh from baoguo where bgydh='{$bgydh}' "));
	if($num){exit ("<script>alert('单号已存在,请修改！');goBack();</script>");}
	

	$addtime=time();

	$savelx='add';//调用类型(add,edit,cache)
	$getlx='POST';//获取类型(POST,GET,REQUEST,SQL)
	$alone='bgid,rukutime_date,rukutime_time,tjtype,hx_fj,zb_zl,ware_time,ware_out_time,
	mvf_record_j,mvf_record_bgydh,mvf_record_weizhi,mvf_record_weight,mvf_record_fx_suo,waretime_edit
	';//不处理的字段
	$digital='status,addSource,warehouse,weight,
	op_02,op_02_pay,op_04,op_04_pay,op_06,op_06_pay,op_07,op_07_pay,op_09,op_09_pay,op_10,op_10_pay,op_11,op_11_pay,
	th,th_pay,fx,fx_pay,fx_suo,hx,hx_suo,hx_pay,ware,ware_pay';//数字字段
	$radio='';//单选、复选、空文本、数组字段
	$textarea='reply,content,unclaimedContent';//过滤不安全的HTML代码
	$date='fahuotime';//日期格式转数字
	$save=XingAoSave($_POST,$getlx,$savelx,$alone,$digital,$radio,$textarea,$html,$date);
	
	//未入库改为其他状态时，自动以当前时间为入库时间
	if($_POST['status']>1&&!$_POST['rukutime_date'])
	{
		$save['field'].=",rukutime";
		$save['value'].=",'".time()."'";
	}else{
		$save['field'].=",rukutime";
		$save['value'].=",'".ToStrtotime($_POST['rukutime_date'],$_POST['rukutime_time'])."'";
	}
	//<!--2017.06.17:删除dgid-->

	//仓储
	if($_POST['waretime_edit'])
	{
		$save['field'].=",ware_time,ware_out_time";
		$save['value'].=",'".ToStrtotime($_POST['ware_time'])."','".ToStrtotime($_POST['ware_out_time'])."'";
	}
	else
	{
		if($ware)
		{
			$save['field'].=",ware_time";
			$save['value'].=",'".time()."'";
		}
	}
	
	//回复时间
	if($_POST['reply'])
	{
		$save['field'].=",replytime";
		$save['value'].=",'".time()."'";
	}
	
	$xingao->query("insert into baoguo (".$save['field'].",addtime) values(".$save['value'].",'{$addtime}')");
	SQLError('添加');
	$rc=mysqli_affected_rows($xingao);
	$bgid=mysqli_insert_id($xingao);
	wupin_save('baoguo',$bgid,0);
	
	if($rc>0)
	{
		$token->drop_token("baoguo".$bgid); //处理完后删除密钥
		exit("<script>alert('添加成功,可继续添加！ \\n(需要刷新列表才看到最新添加包裹)');location='list.php';</script>");
	}else{
		exit ("<script>alert('{$LG['pptAddFailure']}');goBack();</script>");
	}
}







//================================================================================================
//===========================================修改=================================================
//================================================================================================
if($lx=='edit')
{
	if(!$bgid){exit ("<script>alert('bgid{$LG['pptError']}');goBack();</script>");}
	
	$rs=FeData('baoguo','*',"bgid=$bgid");//查询
	warehouse_per('ts',$rs['warehouse']);//验证可管理的仓库
	
	//验证------------------------------------
	//修改会员
	if($rs['username']!=add($_POST['username'])||$rs['userid']!=add($_POST['userid'])||$rs['useric']!=add($_POST['useric']))
	{
		//获取会员账号资料并赋值到POST,验证账号是否正确
		getMemberUser(1,1);
	}
	
	//验证运单号
	if($rs['bgydh']!=$bgydh)
	{
		$num=mysqli_num_rows($xingao->query("select bgydh from baoguo where bgydh='{$bgydh}' "));
		if($num){exit ("<script>alert('单号已存在,请修改！');goBack();</script>");}
	}
	

	//正常修改------------------------------------------------------------------------------------	
	$savelx='edit';//调用类型(add,edit,cache)
	$getlx='POST';//获取类型(POST,GET,REQUEST,SQL)
	$alone='bgid,rukutime_date,rukutime_time,tjtype,ware_time,ware_out_time,hx_fj,zb_zl,
	mvf_record_j,mvf_record_bgydh,mvf_record_weizhi,mvf_record_weight,mvf_record_fx_suo,waretime_edit,fx
	';//不处理的字段
	$digital='status,addSource,warehouse,weight,
	op_02,op_02_pay,op_04,op_04_pay,op_06,op_06_pay,op_07,op_07_pay,op_09,op_09_pay,op_10,op_10_pay,op_11,op_11_pay,
	th,th_pay,fx_pay,fx_suo,hx,hx_suo,hx_pay,ware,ware_pay';//数字字段
	$radio='';//单选、复选、空文本、数组字段
	$textarea='reply,content,unclaimedContent';//过滤不安全的HTML代码
	$date='fahuotime';//日期格式转数字
	$save=XingAoSave($_POST,$getlx,$savelx,$alone,$digital,$radio,$textarea,$html,$date);
	
	
	//未入库改为其他状态时，自动以当前时间为入库时间
	if($_POST['status']>1&&!$_POST['rukutime_date']&&spr($rs['status'])<=1)
	{
		$save.=",rukutime='".time()."'";
	}else{
		$save.=",rukutime='".ToStrtotime($_POST['rukutime_date'],$_POST['rukutime_time'])."'";
	}
	//<!--2017.06.17:删除dgid-->
	
	//仓储
	if($_POST['waretime_edit'])
	{
		$save.=",ware_time='".ToStrtotime($_POST['ware_time'])."',ware_out_time='".ToStrtotime($_POST['ware_out_time'])."'";
	}
	else
	{
		if($rs['ware']!=$ware&&$ware)
		{
			$save.=",ware_time='".time()."'";
		}
		elseif($rs['ware']!=$ware&&!$ware)
		{
			$save.=",ware_out_time='".time()."'";
		}
	}

	//退货
	if($_POST['th']==2)
	{
		$save.=",status='10'";
	}
	
	//回复时间
	if(html($_POST['reply'])!=$rs['reply'])
	{
		$save.=",replytime='".time()."'";
	}
	
	//转移会员且修改过仓位
	if($rs['old_userid']&&$_POST['whPlace']!=cadd($rs['whPlace']))
	{
		$save.=",tra_user='0'";
	}
	
	//物品
	$save.=",edittime=".time();
	
	$xingao->query("update baoguo set ".$save." where bgid='{$bgid}'  {$Xwh}");
	SQLError('正常修改');		
	$rc=mysqli_affected_rows($xingao);
	wupin_save('baoguo',$bgid,0);

	if($rc>0)
	{
		//未入库改为其他状态时,发通知
		if($_POST['status']>1&&spr($rs['status'])<=1)
		{
			//发通知
			//获取发送通知内容
			$NoticeTemplate='baoguo_notice_storage';
			require($_SERVER['DOCUMENT_ROOT'].'/public/NoticeTemplate.php');
		}
	}


	//合箱,分箱修改------------------------------------------------------------------------------------	
	require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/baoguo/form_save_hxfx.php');

	$token->drop_token("baoguo".$bgid); //处理完后删除密钥
	exit("<script>alert('{$LG['pptEditSucceed']}\\n需要刷新列表才看到最新修改');goBack('c');</script>");
}
?>