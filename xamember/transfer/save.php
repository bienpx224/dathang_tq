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
if(!$ON_bankAccount){exit ("<script>alert('{$LG['transfer.form_2']}');goBack();</script>");}


//获取,处理=====================================================
$lx=par($_REQUEST['lx']);
$tfid=$_REQUEST['tfid'];
$tokenkey=par($_POST['tokenkey']);
if (is_array($tfid)){$tfid=implode(',',$tfid);} $tfid=par($tfid);

//添加,修改=====================================================
if($lx=='add'||$lx=='edit')
{
	//通用验证------------------------------------
	$token=new Form_token_Core();
	$token->is_token("transfer_add",$tokenkey); //验证令牌密钥

	if(!$_POST['img']){exit ("<script>alert('{$LG['transfer.save_1']}');goBack();</script>");}
	
	//添加------------------------------------
	if($lx=='add')
	{
		$_POST['popup']=1;
		$_POST['status']=0;
		$_POST['addtime']=time();
		$_POST['userid']=$Muserid;
		$_POST['username']=$Musername;

		$savelx='add';//调用类型(add,edit,cache)
		$getlx='POST';//获取类型(POST,GET,REQUEST,SQL)
		$alone='tfid';//不处理的字段
		$digital='autoPay';//数字字段
		$radio='';//单选、复选、空文本、数组字段
		$textarea='content';//过滤不安全的HTML代码
		$date='';//日期格式转数字
		$save=XingAoSave($_POST,$getlx,$savelx,$alone,$digital,$radio,$textarea,$html,$date);
		
		$xingao->query("insert into transfer (".$save['field'].") values(".$save['value'].")");
		SQLError('添加');
		$rc=mysqli_affected_rows($xingao);
		if($rc>0)
		{
			$token->drop_token("transfer_add"); //处理完后删除密钥
			exit("<script>alert('{$LG['pptAddSucceed']}');location='list.php';</script>");
		}else{
			exit ("<script>alert('{$LG['pptAddFailure']}');goBack();</script>");
		}
	}
	
	//修改------------------------------------
	if($lx=='edit')
	{
		if(!$tfid){exit ("<script>alert('tfid{$LG['pptError']}');goBack();</script>");}
		$rs=FeData('transfer','*'," tfid='{$tfid}' {$Mmy} ");
		if(spr($rs['status'])>0){exit ("<script>alert('{$LG['transfer.form_3']}');goBack('uc');</script>");}

		//更新
		$savelx='edit';//调用类型(add,edit,cache)
		$getlx='POST';//获取类型(POST,GET,REQUEST,SQL)
		$alone='tfid';//不处理的字段
		$digital='autoPay';//数字字段
		$radio='';//单选、复选、空文本、数组字段
		$textarea='content';//过滤不安全的HTML代码
		$date='';//日期格式转数字
		$save=XingAoSave($_POST,$getlx,$savelx,$alone,$digital,$radio,$textarea,$html,$date);
		$xingao->query("update transfer set ".$save." where tfid='{$tfid}' and status='0' {$Mmy}");
		SQLError();		
		$rc=mysqli_affected_rows($xingao);

		$token->drop_token("transfer_add"); //处理完后删除密钥
		if($rc>0)
		{
			$ts=$LG['pptEditSucceed'];
		}else{
			$ts=$LG['pptEditNo'];
		}
		exit("<script>alert('".$ts."');location='list.php';</script>");
	}
	
	
	
//删除=====================================================
}elseif($lx=='del'){
	
	if(!$tfid){exit ("<script>alert('tfid{$LG['pptError']}');goBack();</script>");}

	//开启“长期保存记录”时禁止删除的状态
	if($off_delbak)
	{
		$delbak_status="and status='0'";
		$delbak_ts='\\n'.$LG['transfer.save_2'];
	}
	
	$where="tfid in ({$tfid})  and status in (0,1,5) {$delbak_status}";
	//查询文件
	$query="select img from transfer where {$where} and img<>'' {$Mmy}";
	$sql=$xingao->query($query);
	while($rs=$sql->fetch_array())
	{
		//删除文件
		DelFile($rs['img']);
	}
	$xingao->query("delete from transfer where {$where} {$Mmy}");
	$rc=mysqli_affected_rows($xingao);
	
	if($rc>0){
		exit("<script>alert('{$LG['pptDelSucceed']}{$rc}');location='list.php';</script>");
	}else{
		exit("<script>alert('{$LG['pptDelEmpty']}{$delbak_ts}');location='list.php';</script>");
	}
	
}
?>