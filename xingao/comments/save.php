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
$pervar='pinglun';require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');


//获取,处理=====================================================
$lx=par($_REQUEST['lx']);
$cmid=$_REQUEST['cmid'];
$tokenkey=par($_POST['tokenkey']);
$content=html($_POST['content']);
$checked=par($_POST['checked']);

if (is_array($cmid)){$cmid=implode(',',$cmid);}
$cmid=par($cmid);

//修改,回复=====================================================
if($lx=='reply'||$lx=='edit')
{
	//通用验证------------------------------------
	$token=new Form_token_Core();
	$token->is_token('comments'.$cmid,$tokenkey); //验证令牌密钥
	
	if(!$content){exit ("<script>alert('请填写内容！');goBack();</script>");}
	
	//修改------------------------------------
	if($lx=='edit')
	{
		if(!$cmid){exit ("<script>alert('cmid{$LG['pptError']}');goBack();</script>");}
		//更新
		$savelx='edit';//调用类型(add,edit,cache)
		$getlx='POST';//获取类型(POST,GET,REQUEST,SQL)
		$alone='cmid,old_img';//不处理的字段
		$digital='checked';//数字字段
		$radio='img';//单选、复选、空文本、数组字段
		$textarea='';//过滤不安全的HTML代码
		$date='';//日期格式转数字
		$save=XingAoSave($_POST,$getlx,$savelx,$alone,$digital,$radio,$textarea,$html,$date);
		
		$xingao->query("update comments set ".$save." where cmid='{$cmid}' ");
		SQLError();		
		$rc=mysqli_affected_rows($xingao);

		$token->drop_token('comments'.$cmid); //处理完后删除密钥
		if($rc>0)
		{
			$ts=$LG['pptEditSucceed'].'\\n如果想看到最新修改,请刷新列表';
		}else{
			$ts=$LG['pptEditNo'];
		}
		exit("<script>alert('".$ts."');goBack('c');</script>");
	}


	//回复------------------------------------
	if($lx=='reply')
	{
		$addtime=time();

		$savelx='add';//调用类型(add,edit,cache)
		$getlx='POST';//获取类型(POST,GET,REQUEST,SQL)
		$alone='old_img,cmid';//不处理的字段
		$digital='checked';//数字字段
		$radio='img';//单选、复选、空文本、数组字段
		$textarea='content';//过滤不安全的HTML代码
		$date='';//日期格式转数字
		$save=XingAoSave($_POST,$getlx,$savelx,$alone,$digital,$radio,$textarea,$html,$date);

		$xingao->query("insert into comments (".$save['field'].",reply_userid,reply_username,addtime) values(".$save['value'].",'{$Xuserid}','{$Xusername}','{$addtime}')");
		SQLError();
		$rc=mysqli_affected_rows($xingao);
		if($rc>0)
		{
			//更新主信息状态
			$xingao->query("update comments set rep='1' where cmid='{$cmid}'");

			$token->drop_token('comments'.$cmid); //处理完后删除密钥
			exit("<script>alert('回复成功!\\n(刷新管理列表才会看到最新状态)');goBack('c');</script>");
		}else{
			exit ("<script>alert('{$LG['pptAddFailure']}');goBack();</script>");
		}
	}

	
//删除=====================================================
}elseif($lx=='del'){
	
	if(!$cmid){exit ("<script>alert('cmid{$LG['pptError']}');goBack();</script>");}
	
	//删除评论
	$query="select cmid,img from comments where cmid in ({$cmid})";
	$sql=$xingao->query($query);
	while($rs=$sql->fetch_array())
	{
		//删除评论文件
		DelFile($rs['img']);
		
		//删除评论回复
		$query2="select img from comments where img<>'' and repcmid in ({$rs[cmid]}) ";
		$sql2=$xingao->query($query2);
		while($rs2=$sql2->fetch_array())
		{
			//删除评论回复文件
			DelFile($rs2['img']);
		}
		$xingao->query("delete from comments where repcmid in ({$rs[cmid]}) ");
	}
	$xingao->query("delete from comments where cmid in ({$cmid}) ");
	$rc=mysqli_affected_rows($xingao);
	
	if($rc>0){
		exit("<script>alert('{$LG['pptDelSucceed']}{$rc}');location='list.php';</script>");
	}else{
		exit("<script>alert('{$LG['pptDelEmpty']}');location='list.php';</script>");
	}
	
}
//修改属性=====================================================
elseif($lx=='attr'){
	if(!$cmid){exit ("<script>alert('请勾选信息！');goBack();</script>");}
	if(!CheckEmpty($checked)){exit ("<script>alert('请选择操作类型！');goBack();</script>");}

	//更新主信息状态
	$xingao->query("update comments set checked='{$checked}' where cmid in ({$cmid})");
	SQLError();
	$rc=mysqli_affected_rows($xingao);
	
	$prevurl = isset($_SERVER['HTTP_REFERER']) ? trim($_SERVER['HTTP_REFERER']) : '';
	if($rc>0){
		exit("<script>alert('{$LG['pptEditSucceed']}');location='".$prevurl."';</script>");
	}else{
		exit("<script>alert('{$LG['pptEditEmpty']}');goBack();</script>");
	}
}
?>