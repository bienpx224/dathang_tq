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
if(!$off_mall)
{
	exit ("<script>alert('{$LG['mall_order.form_2']}');goBack('uc');</script>");
}

//获取,处理=====================================================
$my=par($_REQUEST['my']);
$lx=par($_REQUEST['lx']);
$pay=par($_REQUEST['pay']);
$odid=par(ToStr($_REQUEST['odid']));
$tokenkey=par($_POST['tokenkey']);
$content=html($_POST['content']);

//添加,修改=====================================================
if($lx=='add'||$lx=='edit')
{
	//通用验证------------------------------------
	$token=new Form_token_Core();
	$token->is_token("mall_order".$odid,$tokenkey); //验证令牌密钥
	

	//修改------------------------------------
	if($lx=='edit')
	{
		if(!$odid){exit ("<script>alert('odid{$LG['pptError']}');goBack('c');</script>");}
		
		//更新
		if($_POST['old_content']!=$content)
		{
			$xingao->query("update mall_order set content='{$content}',edittime='".time()."' where odid='{$odid}' {$Mmy}");
			SQLError();
			$rc=mysqli_affected_rows($xingao);
		}
		
		//处理完后删除密钥
		$token->drop_token("mall_order".$odid); 
		
		if($rc>0)
		{
			$ts=$LG['mall_order.save_1'];
		}else{
			$ts=$LG['mall_order.save_2'];
		}
		//$prevurl = isset($_SERVER['HTTP_REFERER']) ? trim($_SERVER['HTTP_REFERER']) : '';
		exit("<script>alert('".$ts."');location='list.php?pay=$pay';</script>");
	}
	
}
//删除=====================================================
elseif($lx=='del'){
	
	if(!$odid){exit ("<script>alert('odid{$LG['pptError']}');goBack();</script>");}
	
	//开启“长期保存记录”时禁止删除的状态
	if($off_delbak)
	{
		$delbak_status="and pay<>'1'";
	}
	
	$where="odid in ({$odid}) and (status='3' or pay='0') {$delbak_status}";
	$query="select titleimg,number,status,mlid from mall_order where  {$where} {$Mmy}";
	$sql=$xingao->query($query);
	while($rs=$sql->fetch_array())
	{
		//删除文件
		DelFile($rs['titleimg']);
		//不是失效订单
		if(spr($rs['status'])!='3')
		{
			//更新库存量:还原数量
			$xingao->query("update mall set number=number+{$rs[number]} where mlid='{$rs[mlid]}'");
		}
	}
	
	//删除数据
	$xingao->query("delete from mall_order where {$where} {$Mmy}");
	$rc=mysqli_affected_rows($xingao);
	
	if($rc>0)
	{
		exit("<script>alert('{$LG['pptDelSucceed']}{$rc}');location='list.php?pay=$pay';</script>");
	}else{
		exit("<script>alert('{$LG['pptDelEmpty']}\\n({$LG['mall_order.save_3']})');location='list.php?pay=$pay';</script>");
	}
	
	
}
?>