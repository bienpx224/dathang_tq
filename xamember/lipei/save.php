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
$pervar='off_lipei';//权限验证
require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/session.php');


//获取,处理=====================================================
$lx=par($_REQUEST['lx']);
$lpid=par(ToStr($_REQUEST['lpid']));
$tokenkey=par($_POST['tokenkey']);
$ydh=par($_POST['ydh']);
$img=$_POST['img'];

if (is_array($img)){$img=implode(',',$img);}
$img=par($img,'',1);


//添加,修改=====================================================
if($lx=='add'||$lx=='edit')
{
	//通用验证------------------------------------
	$token=new Form_token_Core();
	$token->is_token("lipei_add",$tokenkey); //验证令牌密钥
	
	    
	if(!$_POST['types']||!$ydh||!$_POST['content']||!$_POST['mobile']||!$_POST['email']){exit ("<script>alert('{$LG['lipei.save_1']}');goBack();</script>");}
	
	if(!$img){exit ("<script>alert('{$LG['lipei.save_4']}');goBack();</script>");}
	
	//验证运单号
	$num=mysqli_num_rows($xingao->query("select ydh from yundan where ydh='{$ydh}' {$Mmy}"));
	if(!$num)
	{
		exit ("<script>alert('{$LG['lipei.save_2']}');goBack();</script>");
	}
	
	//验证重复提交运单号
	if($lx=='add'){$status='0,1';}elseif($lx=='edit'){$status='1';}
	$num=mysqli_num_rows($xingao->query("select ydh from lipei where ydh='{$ydh}' and  status in ({$status}) {$Mmy}"));
	if($num)
	{
		exit ("<script>alert('{$LG['lipei.save_5']}');goBack();</script>");
	}
	
	//添加------------------------------------
	if($lx=='add')
	{
		$_POST['status']=0;
		
		$addtime=time();
		$savelx='add';//调用类型(add,edit,cache)
		$getlx='POST';//获取类型(POST,GET,REQUEST,SQL)
		$alone='lpid';//不处理的字段
		$digital='';//数字字段
		$radio='';//单选、复选、空文本、数组字段
		$textarea='content,requ';//过滤不安全的HTML代码
		$date='';//日期格式转数字
		$save=XingAoSave($_POST,$getlx,$savelx,$alone,$digital,$radio,$textarea,$html,$date);
		
		$xingao->query("insert into lipei (".$save['field'].",addtime,userid,username) values(".$save['value'].",'{$addtime}','{$Muserid}','{$Musername}')");
		SQLError('添加');
		$rc=mysqli_affected_rows($xingao);
		if($rc>0)
		{
			$token->drop_token("lipei_add"); //处理完后删除密钥
			exit("<script>alert('{$LG['pptAddSucceed']}');location='list.php';</script>");
		}else{
			exit ("<script>alert('{$LG['pptAddFailure']}');goBack();</script>");
		}
	}
	
	//修改------------------------------------
	if($lx=='edit')
	{
		if(!$lpid){exit ("<script>alert('lpid{$LG['pptError']}');goBack();</script>");}
		
		//更新
		$savelx='edit';//调用类型(add,edit,cache)
		$getlx='POST';//获取类型(POST,GET,REQUEST,SQL)
		$alone='lpid';//不处理的字段
		$digital='';//数字字段
		$radio='img';//单选、复选、空文本、数组字段
		$textarea='content,requ';//过滤不安全的HTML代码
		$date='';//日期格式转数字
		$save=XingAoSave($_POST,$getlx,$savelx,$alone,$digital,$radio,$textarea,$html,$date);
		$xingao->query("update lipei set ".$save." where lpid='{$lpid}' {$Mmy}");
		SQLError();		
		$rc=mysqli_affected_rows($xingao);

		$token->drop_token("lipei_add"); //处理完后删除密钥
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
	
	if(!$lpid){exit ("<script>alert('lpid{$LG['pptError']}');goBack();</script>");}

	//开启“长期保存记录”时禁止删除的状态
	if($off_delbak)
	{
		$delbak_status="and status<>'2'";
		$delbak_ts='\\n'.$LG['lipei.save_3'];
	}
	
	$where="lpid in ({$lpid})  and status in (0,3) {$delbak_status}";
	//查询文件
	$query="select img from lipei where {$where} and img<>'' {$Mmy}";
	$sql=$xingao->query($query);
	while($rs=$sql->fetch_array())
	{
		//删除文件
		DelFile($rs['img']);
	}
	$xingao->query("delete from lipei where {$where} {$Mmy}");
	$rc=mysqli_affected_rows($xingao);
	
	if($rc>0){
		exit("<script>alert('{$LG['pptDelSucceed']}{$rc}');location='list.php';</script>");
	}else{
		exit("<script>alert('{$LG['pptDelEmpty']}{$delbak_ts}');location='list.php';</script>");
	}
	
}
?>