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
$pervar='member_le';require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');


//获取,处理=====================================================
$my=par($_REQUEST['my']);
$lx=par($_REQUEST['lx']);
$id=par(ToStr($_REQUEST['id']));
$tokenkey=par($_POST['tokenkey']);
$content=html($_POST['content']);


//回复=====================================================
if($lx=='reply')
{
	//通用验证------------------------------------
	$token=new Form_token_Core();
	$token->is_token('msg'.$id,$tokenkey); //验证令牌密钥
	
	if(!$content){exit ("<script>alert('请填写内容！');goBack();</script>");}

	//回复------------------------------------
	if($lx=='reply')
	{
		$addtime=time();

		$savelx='add';//调用类型(add,edit,cache)
		$getlx='POST';//获取类型(POST,GET,REQUEST,SQL)
		$alone='old_img,msgid,content';//不处理的字段
		$digital='';//数字字段
		$radio='';//单选、复选、空文本、数组字段
		$textarea='';//过滤不安全的HTML代码
		$date='';//日期格式转数字
		$save=XingAoSave($_POST,$getlx,$savelx,$alone,$digital,$radio,$textarea,$html,$date);

		$xingao->query("insert into msg_reply (".$save['field'].",msgid,content,addtime) values(".$save['value'].",'{$id}','{$content}','{$addtime}')");
		SQLError();
		$rc=mysqli_affected_rows($xingao);
		if($rc>0)
		{
			//更新主信息状态
			$xingao->query("update msg set status='2',new='1',edittime='{$addtime}' where id='{$id}'");

			$token->drop_token('msg'.$id); //处理完后删除密钥
			exit("<script>alert('回复成功，刷新管理列表才会看到最新状态！');goBack('c');</script>");
		}else{
			exit ("<script>alert('{$LG['pptAddFailure']}');goBack();</script>");
		}
	}

	
//删除=====================================================
}elseif($lx=='del'){
	
	if(!$id){exit ("<script>alert('ID{$LG['pptError']}');goBack();</script>");}
	
	//删除站内信息
	$query="select id,file from msg where id in ({$id})";
	$sql=$xingao->query($query);
	while($rs=$sql->fetch_array())
	{
		//删除站内信息文件
		DelFile($rs['file']);
		
		//删除站内信息回复
		$query2="select file from msg_reply where msgid in ({$rs[id]}) ";
		$sql2=$xingao->query($query2);
		while($rs2=$sql2->fetch_array())
		{
			//删除站内信息回复文件
			DelFile($rs2['file']);
		}
		$xingao->query("delete from msg_reply where msgid in ({$rs[id]}) ");
		
	}
	$xingao->query("delete from msg where id in ({$id}) ");

	$rc=mysqli_affected_rows($xingao);
	
	if($rc>0){
		exit("<script>alert('{$LG['pptDelSucceed']}{$rc}');location='list.php';</script>");
	}else{
		exit("<script>alert('{$LG['pptDelEmpty']}');location='list.php';</script>");
	}
	
}
?>