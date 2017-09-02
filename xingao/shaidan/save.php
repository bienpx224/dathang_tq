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
$pervar='shaidan';//权限验证
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');

if(!$off_shaidan)
{
	exit ("<script>alert('晒单功能未开启,无法使用！');goBack('uc');</script>");
}

//获取,处理=====================================================
$lx=par($_REQUEST['lx']);
$sdid=$_REQUEST['sdid'];
$checked=par($_REQUEST['checked']);

$tokenkey=par($_POST['tokenkey']);
$ydh=par($_POST['ydh']);
$img=$_POST['img'];

if (is_array($img)){$img=implode(',',$img);}
$img=par($img,'',1);

if (is_array($sdid)){$sdid=implode(',',$sdid);}
$sdid=par($sdid);

//添加,修改=====================================================
if($lx=='add'||$lx=='edit')
{
	//通用验证------------------------------------
	$token=new Form_token_Core();
	$token->is_token("shaidan",$tokenkey); //验证令牌密钥
	
	    
	if(!$_POST['classid']||!$ydh||!$_POST['content']||!$img){exit ("<script>alert('请完善内容 (栏目、运单号、内容、图片)!');goBack();</script>");}
	

	//修改------------------------------------
	if($lx=='edit')
	{
		if(!$sdid){exit ("<script>alert('sdid{$LG['pptError']}');goBack();</script>");}

		//查询原信息
		$rs=FeData('shaidan','*',"sdid='{$sdid}'");
		$userid=$rs['userid'];
		$username=$rs['username'];
		

		//验证运单号
		$num=mysqli_num_rows($xingao->query("select ydh from yundan where ydh='{$ydh}' and userid='{$userid}' "));
		if(!$num)
		{
			exit ("<script>alert('运单号错误或已被删除！');goBack();</script>");
		}

		//更新
		if($checked){$_POST['songfen']=1;}
		$savelx='edit';//调用类型(add,edit,cache)
		$getlx='POST';//获取类型(POST,GET,REQUEST,SQL)
		$alone='sdid';//不处理的字段
		$digital='';//数字字段
		$radio='img';//单选、复选、空文本、数组字段
		$textarea='content';//过滤不安全的HTML代码
		$date='';//日期格式转数字
		$save=XingAoSave($_POST,$getlx,$savelx,$alone,$digital,$radio,$textarea,$html,$date);
		$xingao->query("update shaidan set ".$save.",edittime='".time()."' where sdid='{$sdid}' ");
		SQLError();		
		$rc=mysqli_affected_rows($xingao);

		if($rc>0)
		{
			//送分
			if($checked&&$off_integral&&$integral_shaidan>0&&!$rs['songfen'])
			{
				$content='晒单ID:'.$sdid.'，运单号:'.$ydh;
				integralCZ($userid,'shaidan',$sdid,$integral_shaidan,$ydh,'',2);
			}
			
			//生成静态内容页,要有sdid
			require($_SERVER['DOCUMENT_ROOT'].'/xingao/shaidan/rehtml_call.php');

			$ts=$LG['pptEditSucceed'];
			$token->drop_token("shaidan"); //处理完后删除密钥
		}else{
			$ts=$LG['pptEditNo'];
		}
		exit("<script>alert('".$ts."');location='list.php';</script>");
	}
	
	
	
//删除=====================================================
}elseif($lx=='del'){
	
	if(!$sdid){exit ("<script>alert('sdid{$LG['pptError']}');goBack();</script>");}
	
	$where="sdid in ({$sdid}) and checked='0'";
	//查询文件
	$query="select img,path from shaidan where {$where}  and (img<>'' or path<>'')";
	$sql=$xingao->query($query);
	while($rs=$sql->fetch_array())
	{
		//删除文件
		DelFile($rs['img']);
		DelFile($rs['path']);
	}
	$xingao->query("delete from shaidan where {$where} ");
	$rc=mysqli_affected_rows($xingao);
	
	if($rc>0){
		exit("<script>alert('{$LG['pptDelSucceed']}{$rc}');location='list.php';</script>");
	}else{
		exit("<script>alert('{$LG['pptDelEmpty']}\\n已审核信息不能删除,如果要删除请先设为未审');location='list.php';</script>");
	}
	
}

//修改属性=====================================================
elseif($lx=='attr'){
	if(!$sdid){exit ("<script>alert('请勾选信息！');goBack();</script>");}
	if(!CheckEmpty($checked)){exit ("<script>alert('请选择操作类型！');goBack();</script>");}
	
	if($checked)
	{
		//设为已审时:查询主信息
		$query="select * from shaidan where sdid in ({$sdid})";
		$sql=$xingao->query($query);
		while($rs=$sql->fetch_array())
		{
			  //送分
			  if($checked&&$off_integral&&$integral_shaidan>0&&!$rs['songfen'])
			  {
				 $userid=$rs['userid'];
				 $username=$rs['username'];
				 $content='晒单ID:'.$rs['sdid'].'，运单号:'.$rs['ydh'];
				 integralCZ($userid,'shaidan',$rs['sdid'],$integral_shaidan,$rs['ydh'],'',2);
			  }
		}
	   $save=",songfen='1'";
	}
			   
   //更新主信息 
   $xingao->query("update shaidan set checked='{$checked}' ".$save." where sdid in ({$sdid})");
   SQLError('属性修改-更新主信息');
   $rc=mysqli_affected_rows($xingao);
   $prevurl = isset($_SERVER['HTTP_REFERER']) ? trim($_SERVER['HTTP_REFERER']) : '';
	if($rc>0)
	{
		//生成静态内容页,要有sdid
		require($_SERVER['DOCUMENT_ROOT'].'/xingao/shaidan/rehtml_call.php');
		exit("<script>alert('{$LG['pptEditSucceed']}');location='".$prevurl."';</script>");
	}else{
		exit("<script>alert('{$LG['pptEditEmpty']}');goBack();</script>");
	}
}

?>