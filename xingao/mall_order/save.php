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
$pervar='mall_order';require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');
if(!$off_mall)
{
	exit ("<script>alert('商城系统未开启,无法使用！');goBack('uc');</script>");
}

//获取,处理=====================================================
$my=par($_REQUEST['my']);
$lx=par($_REQUEST['lx']);
$pay=par($_REQUEST['pay']);
$odid=$_REQUEST['odid'];
$tokenkey=par($_POST['tokenkey']);

if (is_array($odid)){$odid=implode(',',$odid);}
$odid=par($odid);



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
		
		//查询主信息
		$rs=FeData('mall_order','*',"odid='{$odid}'");
		warehouse_per('ts',$zhi=$rs['warehouse']);//验证可管理的仓库
		if(spr($rs['status'])=='3'){exit ("<script>alert('失效订单不能再修改！');goBack('c');</script>");}

		//失效订单
		if($_POST['status']=='3')
		{
			//更新库存量:还原数量
			$xingao->query("update mall set number=number+{$rs[number]} where mlid='{$rs[mlid]}'");
		}

		//更新
		$savelx='edit';//调用类型(add,edit,cache)
		$getlx='POST';//获取类型(POST,GET,REQUEST,SQL)
		$alone='odid,old_reply';//不处理的字段
		$digital='status,bgid,payment';//数字字段
		$radio='';//单选、复选、空文本、数组字段
		$textarea='reply';//过滤不安全的HTML代码
		$date='';//日期格式转数字

		$save=XingAoSave($_POST,$getlx,$savelx,$alone,$digital,$radio,$textarea,$html,$date);
		if($_POST['old_reply']!=$_POST['reply']){$save.=",replytime='".time()."'";}
		$xingao->query("update mall_order set ".$save.",edittime='".time()."' where odid='{$odid}' {$Xwh}");
		SQLError();
		$rc=mysqli_affected_rows($xingao);

		//处理完后删除密钥
		$token->drop_token("mall_order".$odid); 
		
		if($rc>0)
		{
			$ts=$LG['pptEditSucceed'].'\\n如果想看到最新修改,请刷新列表';
		}else{
			$ts='未修改订单';
		}

		exit("<script>alert('".$ts."');goBack('c');</script>");
	}
	
}
//删除=====================================================
elseif($lx=='del'){
	
	if(!$odid){exit ("<script>alert('odid{$LG['pptError']}');goBack();</script>");}
	
	//开启“长期保存记录”时禁止删除的状态
	if($off_delbak)
	{
		$delbak_status="and pay<>'1'";
		$delbak_ts='\\n已开启“长期保存记录”功能，如果是正常记录则不能删除';
	}

	$where="odid in ({$odid}) and (status='3' or pay='0') {$delbak_status}";
	$query="select titleimg,number,status,mlid from mall_order where {$where} {$Xwh}";
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
	$xingao->query("delete from mall_order where {$where} {$Xwh}");
	$rc=mysqli_affected_rows($xingao);
	
	if($rc>0)
	{
		exit("<script>alert('{$LG['pptDelSucceed']}{$rc}');location='list.php?pay=$pay';</script>");
	}else{
		exit("<script>alert('{$LG['pptDelEmpty']}\\n只能删除购物车中或失效订单{$delbak_ts}');location='list.php?pay=$pay';</script>");
	}
	
	
}
//修改属性=====================================================
elseif($lx=='attr'){
	if(!$odid){exit ("<script>alert('请勾选订单！');goBack();</script>");}

	$status=par($_REQUEST['status']);//这个要用_REQUEST

	if( !CheckEmpty($status)){exit ("<script>alert('请选择要修改的属性！');goBack();</script>");}

	if(CheckEmpty($status)){$set="status='{$status}',edittime=".time();}

	//设置失效订单
	if($status=='3')
	{
		$query="select number,status,mlid from mall_order where odid in ({$odid}) and status<>'3' {$Xwh}";
		$sql=$xingao->query($query);
		while($rs=$sql->fetch_array())
		{
			//更新库存量:还原数量
			$xingao->query("update mall set number=number+{$rs[number]} where mlid='{$rs[mlid]}'");
		}
	}

	$xingao->query("update mall_order set {$set} where odid in ({$odid}) and status<>'3' {$Xwh}");
	SQLError('更新属性');
	
	$rc=mysqli_affected_rows($xingao);
	
	$prevurl = isset($_SERVER['HTTP_REFERER']) ? trim($_SERVER['HTTP_REFERER']) : '';
	if($rc)
	{
		exit("<script>alert('共修改{$rc}个订单！');location='".$prevurl."';</script>");
	}else{
		exit("<script>alert('未修改订单！\\n(失效订单不能再修改)');location='".$prevurl."';</script>");
	}
}
?>