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
$lx=par($_POST['lx']);
$fromtable=par($_POST['fromtable']);
$fromid=par($_POST['fromid']);
$repcmid=par($_POST['repcmid']);
$code=strtolower(par($_POST['code']));
$tokenkey=par($_POST['tokenkey']);

if(!$fromtable||!$fromid)
{
	exit($LG['comments.1']);
}

if($lx=='add')
{
	//通用验证------------------------------------
	
	//防提交太快
	if($_SESSION['comments_time']>=strtotime('-15 seconds'))
	{
		exit( "<script>alert('{$LG['comments.2']}');goBack();</script>");
	}
	
	
	if($fromtable=='mall'){$off_code_sp=$off_code_shangpin_sp;}
	elseif($fromtable=='shaidan'){$off_code_sp=$off_code_shaidan_sp;}
	else{exit( "<script>alert('fromtable{$LG['pptError']}');goBack();</script>");}//防垃圾广告提交

	$token=new Form_token_Core();
	$token->is_token('comments'.$fromid,$tokenkey); //验证令牌密钥

	if($off_code_sp)
	{
		if(!$code){exit ( "<script>alert('{$LG['codeEmpty']}');goBack();</script>");}
		
		$vname=xaReturnKeyVarname('pl');
		if($code!=$_SESSION[$vname]){unset($_SESSION[$vname]);exit ( "<script>alert('{$LG['codeOverdue']}');goBack();</script>");}
		unset($_SESSION[$vname]);
	}

	if(!$_POST['content']){exit ("<script>alert('{$LG['comments.3']}');goBack();</script>");}

	//保存------------------------------------
		$addtime=time();
		$savelx='add';//调用类型(add,edit,cache)
		$getlx='POST';//获取类型(POST,GET,REQUEST,SQL)
		$alone='old_img,content';//不处理的字段
		$digital='repcmid';//数字字段
		$radio='img';//单选、复选、空文本、数组字段
		$textarea='';//过滤不安全的HTML代码
		$date='';//日期格式转数字
		$save=XingAoSave($_POST,$getlx,$savelx,$alone,$digital,$radio,$textarea,$html,$date);
		$checked=0;
		$ts=$LG['comments.4'];
		if(!$comments_checked){$checked=1;$ts='';}
		
		//content因为引用时有DIV代码,所以要特别处理
		$save['field'].=",content";
		$save['value'].=",'".add(rephtml($_POST['content']))."'";

		$xingao->query("insert into comments (".$save['field'].",addtime,userid,username,checked) values(".$save['value'].",'{$addtime}','{$Muserid}','{$Musername}','{$checked}')");
		SQLError();
		$rc=mysqli_affected_rows($xingao);
		if($rc>0)
		{
			//更新主评论回复状态
			if($repcmid)
			{
				$xingao->query("update comments set rep='1' where cmid='{$repcmid}'");
				SQLError('更新');
			}
			$_SESSION['comments_time']=time();//防提交太快
			$token->drop_token('comments'.$fromid); //处理完后删除密钥
			$prevurl = isset($_SERVER['HTTP_REFERER']) ? trim($_SERVER['HTTP_REFERER']) : '';
			exit("<script>alert('{$LG['pptSMTSucceed']}{$ts}！');location='".$prevurl."';</script>");
		}else{
			exit ("<script>alert('{$LG['pptAddFailure']}');goBack();</script>");
		}

}
?>