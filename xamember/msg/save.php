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
$my=par($_REQUEST['my']);
$lx=par($_REQUEST['lx']);
$id=par(ToStr($_REQUEST['id']));
$tokenkey=par($_POST['tokenkey']);
$file=par($_POST['file'],'',1);
$status=par($_POST['status']);
$title=par($_POST['title']);
$content=html($_POST['content']);
$code=strtolower(par($_POST['code']));


//添加、回复=====================================================
if($lx=='add'||$lx=='reply')
{
	//通用验证------------------------------------
	if(!CheckEmpty($status)){exit ("<script>alert('{$LG['msg.save_1']}');goBack();</script>");}
	if(!$content&&$status>10){exit ("<script>alert('{$LG['msg.save_2']}');goBack();</script>");}
	
	if($off_code_liuyan)
	{
		if(!$code){exit ( "<script>alert('{$LG['codeEmpty']}');goBack();</script>");}
		
		$vname=xaReturnKeyVarname('gbook');
		if($code!=$_SESSION[$vname]){unset($_SESSION[$vname]);exit ( "<script>alert('{$LG['codeOverdue']}');goBack();</script>");}
		unset($_SESSION[$vname]);
	}

	//添加------------------------------------
	if($lx=='add')
	{
		if(!$title){exit ("<script>alert('{$LG['msg.save_3']}');goBack();</script>");}

		$token=new Form_token_Core();
		$token->is_token("msg_add",$tokenkey); //验证令牌密钥

		//发站内信息
		$rc=SendMsg($Muserid,$Musername,add($title),html($content),$file,$from_userid='0',$from_username='',$new=0,$status,$issys=0,$xs=0);

		if($rc>0)
		{
			$token->drop_token("msg_add"); //处理完后删除密钥
			exit("<script>alert('{$LG['msg.save_4']}');location='list.php';</script>");
		}else{
			exit ("<script>alert('{$LG['msg.save_5']}');goBack();</script>");
		}
	}


	//回复------------------------------------
	if($lx=='reply')
	{
		$token=new Form_token_Core();
		$token->is_token('msg'.$id,$tokenkey); //验证令牌密钥

		$addtime=time();
		$xingao->query("insert into msg_reply (file,msgid,content,addtime) values('{$file}','{$id}','{$content}','{$addtime}')");
		SQLError();
		$rc=mysqli_affected_rows($xingao);
		if($rc>0)
		{
			//更新主信息状态
			$xingao->query("update msg set status='{$status}',edittime='{$addtime}' where id='{$id}' {$Mmy}");

			$token->drop_token('msg'.$id); //处理完后删除密钥
			exit("<script>alert('{$LG['msg.save_6']}');goBack('c');</script>");
		}else{
			exit ("<script>alert('{$LG['msg.save_7']}');goBack();</script>");
		}
	}

	
}
//删除=====================================================
elseif($lx=='del'){
	
	if(!$id){exit ("<script>alert('ID{$LG['pptError']}');goBack();</script>");}
	
	//删除站内信息
	$query="select id,file from msg where id in ({$id}) {$Mmy}";
	$sql=$xingao->query($query);
	while($rs=$sql->fetch_array())
	{
		//删除站内信息文件
		DelFile($rs['file']);
		
		//删除站内信息回复
		$query2="select id,file from msg_reply where msgid in ({$rs[id]}) ";
		$sql2=$xingao->query($query2);
		while($rs2=$sql2->fetch_array())
		{
			//删除站内信息回复文件
			DelFile($rs2['file']);
		}
		$xingao->query("delete from msg_reply where msgid in ({$rs[id]}) ");
		
	}
	$xingao->query("delete from msg where id in ({$id}) {$Mmy}");

	$rc=mysqli_affected_rows($xingao);
	
	if($rc>0){
		exit("<script>alert('{$LG['pptDelSucceed']}{$rc}');location='list.php';</script>");
	}else{
		exit("<script>alert('{$LG['pptDelEmpty']}');location='list.php';</script>");
	}
	
}
//修改属性=====================================================
elseif($lx=='new'){
	$new=par($_POST['new']);
	if(!$id){exit ("<script>alert('ID{$LG['pptError']}');goBack();</script>");}
	if(!CheckEmpty($new)){exit ("<script>alert('{$LG['msg.save_1']}');goBack();</script>");}
	//更新主信息状态
	$xingao->query("update msg set new='{$new}' where id in ({$id}) {$Mmy}");
	SQLError();
	$rc=mysqli_affected_rows($xingao);
	if($rc>0){
		exit("<script>location='list.php';</script>");
	}else{
		exit("<script>alert('{$LG['pptEditEmpty']}');goBack();</script>");
	}
}
?>