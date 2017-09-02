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
$pervar='lipei';//权限验证
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');


//获取,处理=====================================================
$lx=par($_REQUEST['lx']);
$lpid=$_REQUEST['lpid'];
$tokenkey=par($_POST['tokenkey']);
$status=par($_POST['status']);
$money=spr($_POST['money']);
$payment=spr($_POST['payment']);

if (is_array($lpid)){$lpid=implode(',',$lpid);}
$lpid=par($lpid);

//添加,修改=====================================================
if($lx=='add'||$lx=='edit')
{
	//通用验证------------------------------------
	$token=new Form_token_Core();
	$token->is_token("lipei_add",$tokenkey); //验证令牌密钥
	
	//修改------------------------------------
	if($lx=='edit')
	{
		
		if(!$lpid){exit ("<script>alert('lpid{$LG['pptError']}');goBack();</script>");}

		//赔付金-充值******************
		if($payment>0)
		{
			//查询主信息
			$rs=FeData('lipei','*',"lpid='{$lpid}'");
			$userid=$rs['userid'];
			$username=$rs['username'];
			
			 MoneyCZ($userid,$fromtable='lipei',$fromid=$lpid,$fromMoney=$payment,$fromCurrency='',
			 $title=$_POST['ydh'],$content='',$type=54);
			 $ts.=',已充值'.$payment.$XAmc;

		}

		
		//更新
		if($_POST['old_reply']!=$_POST['reply']){$save.=",replytime='".time()."'";}
		$xingao->query("update lipei set status='{$status}',money='{$money}',reply='".html($_POST['reply'])."',edittime='".time()."' {$save} where lpid='{$lpid}' ");
		
		SQLError();		
		$rc=mysqli_affected_rows($xingao);
		if($rc>0)
		{
			$ts=$LG['pptEditSucceed'].$ts;
		}else{
			$ts=$LG['pptEditNo'].$ts;
		}
		
		$token->drop_token("lipei_add"); //处理完后删除密钥
		exit("<script>alert('".$ts."');location='list.php';</script>");
	}
	
	
	
//删除=====================================================
}elseif($lx=='del'){
	
	if(!$lpid){exit ("<script>alert('lpid{$LG['pptError']}');goBack();</script>");}

	//开启“长期保存记录”时禁止删除的状态
	if($off_delbak)
	{
		$delbak_status="and status<>'2'";
		$delbak_ts='\\n已开启“长期保存记录”功能，如果是正常记录则不能删除';
	}
	
	$where="lpid in ({$lpid}) and status in (0,3) {$delbak_status}";
	//查询文件
	$query="select img from lipei where {$where} and img<>'' ";
	$sql=$xingao->query($query);
	while($rs=$sql->fetch_array())
	{
		//删除文件
		DelFile($rs['img']);
	}
	$xingao->query("delete from lipei where {$where} ");
	$rc=mysqli_affected_rows($xingao);
	
	if($rc>0){
		exit("<script>alert('{$LG['pptDelSucceed']}{$rc}');location='list.php';</script>");
	}else{
		exit("<script>alert('{$LG['pptDelEmpty']}{$delbak_ts}');location='list.php';</script>");
	}
	
}
?>