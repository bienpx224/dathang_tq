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
$pervar='qujian';require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');

//获取,处理=====================================================
$lx=par($_REQUEST['lx']);
$qjid=par(ToStr($_REQUEST['qjid']));
$tokenkey=par($_POST['tokenkey']);

//添加,修改=====================================================
if($lx=='add'||$lx=='edit')
{
	//通用验证------------------------------------
	$token=new Form_token_Core();
	$token->is_token('qujian_add'.$qjid,$tokenkey); //验证令牌密钥
	
	    
	if(!$_POST['qjdate']||!$_POST['truename']||!$_POST['mobile']||!$_POST['weight']||!$_POST['address']){exit ("<script>alert('请填写完整红框内容！');goBack();</script>");}
	

	
	//修改------------------------------------
	if($lx=='edit')
	{
		if(!$qjid){exit ("<script>alert('qjid{$LG['pptError']}');goBack();</script>");}
		//更新
		$savelx='edit';//调用类型(add,edit,cache)
		$getlx='POST';//获取类型(POST,GET,REQUEST,SQL)
		$alone='qjid,old_reply';//不处理的字段
		$digital='status';//数字字段
		$radio='';//单选、复选、空文本、数组字段
		$textarea='content,reply';//过滤不安全的HTML代码
		$date='qjdate';//日期格式转数字
		$save=XingAoSave($_POST,$getlx,$savelx,$alone,$digital,$radio,$textarea,$html,$date);
		
		if($_POST['old_reply']!=$_POST['reply']){$save.=",replytime='".time()."'";}
		
		$xingao->query("update qujian set ".$save.",edittime='".time()."' where qjid='{$qjid}' ");
		SQLError();		
		$rc=mysqli_affected_rows($xingao);

		$token->drop_token('qujian_add'.$qjid); //处理完后删除密钥
		if($rc>0)
		{
			$ts=$LG['pptEditSucceed'].'\\n如果想看到最新修改,请刷新列表';
		}else{
			$ts=$LG['pptEditNo'];
		}
		exit("<script>alert('".$ts."');goBack('c');</script>");
	}
	
	
	
//删除=====================================================
}elseif($lx=='del'){
	
	if(!$qjid){exit ("<script>alert('qjid{$LG['pptError']}');goBack();</script>");}
	
	$xingao->query("delete from qujian where qjid in ({$qjid}) ");
	$rc=mysqli_affected_rows($xingao);
	
	if($rc>0){
		exit("<script>alert('{$LG['pptDelSucceed']}{$rc}');location='list.php';</script>");
	}else{
		exit("<script>alert('{$LG['pptDelEmpty']}');location='list.php';</script>");
	}
	
}


//修改属性=====================================================
elseif($lx=='attr'){
	if(!$qjid){exit ("<script>alert('请勾选信息！');goBack();</script>");}

	$status=(int)$_POST['status'];
	$xingao->query("update qujian set status='{$status}',edittime='".time()."' where qjid in ({$qjid})");
	SQLError();
	$rc=mysqli_affected_rows($xingao);
	
	if($rc>0)
	{
		$ts=$LG['pptEditSucceed'].'\\n如果想看到最新修改,请刷新列表';
	}else{
		$ts=$LG['pptEditNo'];
	}
	exit("<script>alert('".$ts."');goBack('c');</script>");
}
?>