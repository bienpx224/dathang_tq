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

if(!$off_baoguo){exit ("<script>alert('包裹系统已关闭！');goBack();</script>");}


//----------------------------其他修改----------------------------

//获取,处理=====================================================
$lx=par($_REQUEST['lx']);
$bgid=par(ToStr($_REQUEST['bgid']));
$tokenkey=par($_POST['tokenkey']);

$id_name='Xbgid';if(!$bgid){$bgid=$_SESSION[$id_name];}

if(!$bgid){exit ("<script>alert('bgid{$LG['pptError']}');goBack();</script>");}
$prevurl = isset($_SERVER['HTTP_REFERER']) ? trim($_SERVER['HTTP_REFERER']) : '';



//修改操作服务=====================================================
$field=par($_REQUEST['field']);
if($field)
{
	$value=par($_REQUEST['value']);
	if(!CheckEmpty($value))
	{
		if(!is_array($field)&&$field){$field_now=explode(",",$field);}
		$field=par($field_now[0]);
		$value=par($field_now[1]);
	}

	if(!CheckEmpty($value)){exit ("<script>alert('value错误！');goBack();</script>");}
	
	$where="bgid in ({$bgid}) and $field<>$value {$Xwh}";
	$save="{$field}='{$value}'";
	
	//退货处理
	if($field=='th'&&$value==2)
	{
		$save.=",status='10'";
	}
	
	//仓储处理
	if($field=='ware')
	{
		if($value)
		{
			$save.=",ware_time=".time();
		}else{
			$save.=",ware_out_time=".time();
		}
	}
	
	//通用退费处理
	if($value==3)
	{
		$query="select $field,{$field}_pay,bgydh,bgid,userid,username from baoguo where {$field}='1' and {$field}_pay<0 and bgid in ({$bgid}) {$Xwh}";
		$sql=$xingao->query($query);
		while($rs=$sql->fetch_array())
		{
			 //充值
			 $op_name='baoguo_'.$field;
			 $money=abs($rs[$field.'_pay']);
			 $type=op_money_type($field,1);
			 
			 MoneyCZ($rs['userid'],$fromtable='baoguo',$fromid=$rs['bgid'],$fromMoney=$money,$fromCurrency='',
			 $title=$rs['bgydh'],$content='',$type);
			 
			 $xingao->query("update baoguo set {$field}_pay='{$money}' where bgid='{$rs[bgid]}'");
			 $rc=mysqli_affected_rows($xingao);
		}
		
	}
	
	//更新主信息 
	$xingao->query("update baoguo set ".$save." where {$where}  ");
	SQLError('属性修改-更新主信息');
	$rc=mysqli_affected_rows($xingao);
   
	if($rc>0)
	{
		$_SESSION[$id_name]='';
		exit("<script>alert('{$LG['pptEditSucceed']}共修改{$rc}个包裹');location='".$prevurl."';</script>");
	}else{
		exit("<script>alert('{$LG['pptEditEmpty']}');goBack();</script>");
	}
}



//入库=====================================================
if($lx=='ruku')
{
	if($baoguo_qr){$status=3;}else{$status=2;}
	$where="bgid in ({$bgid}) and status in (0,1) {$Xwh}";
	$save="status='{$status}',rukutime='".time()."'";
	
	//查询发通知
	$query="select bgid from baoguo where {$where} ";
	$sql=$xingao->query($query);
	while($rs=$sql->fetch_array())
	{
		//更新主信息 
		$xingao->query("update baoguo set ".$save." where  bgid='{$rs[bgid]}'");
		SQLError('入库-更新主信息');
		$rc+=1;
		
		baoguoInStorage($rs['bgid']);//通用更新
					
		//发通知
		//获取发送通知内容
		$bgid=$rs['bgid'];
		$NoticeTemplate='baoguo_notice_storage';
		require($_SERVER['DOCUMENT_ROOT'].'/public/NoticeTemplate.php');
	}
   
	if($rc>0)
	{
		$_SESSION[$id_name]='';
		exit("<script>alert('入库成功！共入库{$rc}个包裹');location='".$prevurl."';</script>");
	}else{
		exit("<script>alert('没有可入库的包裹！');goBack();</script>");
	}
}

//设为“已全部下运单”=====================================================
elseif($lx=='allxd')
{
	$rc=0;
	$query="select bgid from baoguo where bgid in ({$bgid})  and status in (2,3)  and ware=0 {$Xwh}";
	$sql=$xingao->query($query);
	while($rs=$sql->fetch_array())
	{
		$num=mysqli_num_rows($xingao->query("select * from yundan where find_in_set('{$rs[bgid]}',bgid) "));
		if($num)
		{
			$xingao->query("update baoguo set status='4' where bgid='{$rs[bgid]}'");
			SQLError('更新已全部下运单');
			$rc+=1;
		}
	}
	
	if($rc>0){
		exit("<script>alert('共成功设置{$rc}个包裹！');location='list.php?status=ruku';</script>");
	}else{
		exit("<script>alert('没有找到可设置的包裹！\\n只能设置已下运单的包裹');location='list.php?status=ruku';</script>");
	}
	
}

//设为“待下运单”=====================================================
elseif($lx=='noxd')
{
	$xingao->query("update baoguo set status='3' where  bgid in ({$bgid})  and status in (4)  and ware=0 {$Xwh}");
	SQLError('更新待下运单');
	$rc=mysqli_affected_rows($xingao);
	
	if($rc>0){
		exit("<script>location='list.php?status=ruku';</script>");
	}else{
		exit("<script>alert('没有找到可设置的包裹！');location='list.php?status=ruku';</script>");
	}
	
}

//删除=====================================================
elseif($lx=='del')
{
	//开启“长期保存记录”时禁止删除的状态
	if($off_delbak)
	{
		$delbak_status="and status<>'9'";
		$delbak_ts='\\n已开启“长期保存记录”功能，如果是正常记录则不能删除';
	}
	
	$where="bgid in ({$bgid}) and status in (0,1,9,10) {$delbak_status} {$Xwh}";
	
	//查询文件和验证是否可删除
	$query="select op_06_img,addSource,status,bgid from baoguo where {$where} ";
	$sql=$xingao->query($query);
	while($rs=$sql->fetch_array())
	{
		if(spr($rs['status'])==9||spr($rs['status'])==10||spr($rs['status'])<=1)
		{
			DelFile($rs['op_06_img']);//删除文件
			$xingao->query("delete from baoguo where bgid='{$rs[bgid]}'");
			wupin_del('baoguo',$rs['bgid']);
			$rc+=1;
		}
	}
	
	if($rc>0){
		exit("<script>alert('{$LG['pptDelSucceed']}{$rc}');location='list.php';</script>");
	}else{
		exit("<script>alert('{$LG['pptDelEmpty']}\\n只能删除未入库或是记录的包裹{$delbak_ts}');location='list.php';</script>");
	}
	
}

?>