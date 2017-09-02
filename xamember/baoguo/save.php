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

if(!$off_baoguo||!$member_per[$Mgroupid]['ON_Mbaoguo']){exit ("<script>alert('{$LG['baoguo.add_form_2']}');goBack();</script>");}

//获取,处理=====================================================
$lx=par($_REQUEST['lx']);
$bgid=par(ToStr($_REQUEST['bgid']));
$tokenkey=par($_POST['tokenkey']);


if(!$bgid){exit ("<script>alert('bgid{$LG['pptError']}');goBack();</script>");}

//设为“已全部下运单”=====================================================
if($lx=='allxd')
{
	$rc=0;
	$query="select bgid from baoguo where bgid in ({$bgid})  and status in (2,3)  and ware=0 {$Mmy}";
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
		exit("<script>alert('".LGtag($LG['baoguo.save_1'],'<tag1>=='.$rc)."');location='list.php?status=ruku';</script>");
	}else{
		exit("<script>alert('{$LG['baoguo.save_2']}');location='list.php?status=ruku';</script>");
	}
	
}
//设为“已全部直下运单”=====================================================
elseif($lx=='zxyd')
{
	$rc=0;
	$query="select bgid from baoguo where bgid in ({$bgid})  and status in (0)  and ware=0 {$Mmy}";
	$sql=$xingao->query($query);
	while($rs=$sql->fetch_array())
	{
		$num=mysqli_num_rows($xingao->query("select * from yundan where find_in_set('{$rs[bgid]}',bgid) "));
		if($num)
		{
			$xingao->query("update baoguo set status='1' where bgid='{$rs[bgid]}'");
			SQLError('已全部直下运单');
			$rc+=1;
		}
	}
	
	if($rc>0){
		exit("<script>alert('".LGtag($LG['baoguo.save_1'],'<tag1>=='.$rc)."');location='list.php?status=kuwai';</script>");
	}else{
		exit("<script>alert('{$LG['baoguo.save_2']}');location='list.php?status=kuwai';</script>");
	}
	
}

//删除=====================================================
elseif($lx=='del')
{
	//查询文件和验证是否可删除(注:运单删除时也要改)
	
	//开启"长期保存记录"时禁止删除的状态
	if(!$off_delbak)
	{
		$delbak_status="and status<>'9'";
	}
	
	$query="select op_06_img,addSource,status,bgid from baoguo where bgid in ({$bgid})  and status in (0,1,9,10) {$delbak_status} {$Mmy}";
	$sql=$xingao->query($query);
	while($rs=$sql->fetch_array())
	{
		if(spr($rs['status'])==9||spr($rs['status'])==10||(spr($rs['status'])<=1&&$rs['addSource']!=3&&$rs['addSource']!=4))
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
		exit("<script>alert('{$LG['pptDelEmpty']}\\n{$LG['baoguo.save_3']}');location='list.php';</script>");
	}
	
}

?>