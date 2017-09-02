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

if(!$off_shaidan)
{
	exit ("<script>alert('{$LG['shaidan.form_1']}');goBack('uc');</script>");
}

//获取,处理=====================================================
$lx=par($_REQUEST['lx']);
$sdid=$_REQUEST['sdid'];
$tokenkey=par($_POST['tokenkey']);
$ydh=par($_POST['ydh']);
$old_ydh=par($_POST['old_ydh']);
$types=par($_POST['types']);
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
	    
	if(!$_POST['classid']){exit ("<script>alert('{$LG['shaidan.save_8']}');goBack();</script>");}

	if(!$ydh||!$_POST['content']){exit ("<script>alert('{$LG['shaidan.save_2']}');goBack();</script>");}
	
	if(!$types&&!$img){exit ("<script>alert('{$LG['shaidan.save_7']}');goBack();</script>");}

	//站外晒单时处理
	if($types==1)
	{
		if(!$_POST['content']){exit ("<script>alert('{$LG['shaidan.save_9']}');goBack();</script>");}
		
		$arr=ToArr($_POST['content'],1);
		if($arr)
		{
			$_POST['content']='';
			foreach($arr as $arrkey=>$value)
			{
				 $_POST['content'].=addhttp($value).PHP_EOL;
			}
		}
	}
	
	
	
	//验证运单号
	if(!$_SESSION['manage']['userid'])
	{
		$num=mysqli_num_rows($xingao->query("select ydh from yundan where ydh='{$ydh}' {$Mmy}"));
		if(!$num)
		{
			exit ("<script>alert('{$LG['shaidan.save_3']}');goBack();</script>");
		}
	}
	
	//验证重复晒单
	if($ydh!=$old_ydh)
	{
		$num=mysqli_num_rows($xingao->query("select ydh from shaidan where ydh='{$ydh}' {$Mmy}"));
		if($num)
		{
			exit ("<script>alert('".LGtag($LG['shaidan.save_4'],'<tag1>=='.$ydh)."');goBack();</script>");
		}
	}
		
		


	//添加------------------------------------
	if($lx=='add')
	{
		$_POST['checked']=0;
		if(!$shaidan_checked){$_POST['checked']=1;$_POST['songfen']=1;}
		
		$_POST['addtime']=time();
		$_POST['language']=$LT;
		
		$savelx='add';//调用类型(add,edit,cache)
		$getlx='POST';//获取类型(POST,GET,REQUEST,SQL)
		$alone='sdid,old_ydh';//不处理的字段
		$digital='types';//数字字段
		$radio='';//单选、复选、空文本、数组字段
		$textarea='content';//过滤不安全的HTML代码
		$date='';//日期格式转数字
		$save=XingAoSave($_POST,$getlx,$savelx,$alone,$digital,$radio,$textarea,$html,$date);
		
		$xingao->query("insert into shaidan (".$save['field'].",userid,username) values(".$save['value'].",'{$Muserid}','{$Musername}')");
		SQLError('添加');
		$rc=mysqli_affected_rows($xingao);
		$sdid=mysqli_insert_id($xingao);//返回上一次 添加 产生 AUTO_INCREMENT 的 ID
		
		if($rc>0)
		{
			//送分
			if(!$shaidan_checked&&$off_integral&&$integral_shaidan>0)
			{
				$content=LGtag($LG['shaidan.save_5'],
						'<tag1>=='.$sdid.'||'.
						'<tag2>=='.$ydh
					 );
				integralCZ($Muserid,'shaidan',$sdid,$integral_shaidan,$ydh,'',2);
			}
			
			//生成静态列表页,内容页,要有sdid
			require($_SERVER['DOCUMENT_ROOT'].'/xingao/shaidan/rehtml_call.php');

			$token->drop_token("shaidan"); //处理完后删除密钥
			exit("<script>alert('{$LG['pptAddSucceed']}');location='list.php';</script>");
		}else{
			exit ("<script>alert('{$LG['pptAddFailure']}');goBack();</script>");
		}
	}
	
	//修改------------------------------------
	if($lx=='edit')
	{
		if(!$sdid){exit ("<script>alert('sdid{$LG['pptError']}');goBack();</script>");}
		
		$_POST['language']=$LT;
		$_POST['edittime']=time();

		//更新
		$savelx='edit';//调用类型(add,edit,cache)
		$getlx='POST';//获取类型(POST,GET,REQUEST,SQL)
		$alone='sdid,old_ydh';//不处理的字段
		$digital='types';//数字字段
		$radio='img';//单选、复选、空文本、数组字段
		$textarea='content';//过滤不安全的HTML代码
		$date='';//日期格式转数字
		$save=XingAoSave($_POST,$getlx,$savelx,$alone,$digital,$radio,$textarea,$html,$date);
		$xingao->query("update shaidan set ".$save." where sdid='{$sdid}' {$Mmy}");
		SQLError();		
		$rc=mysqli_affected_rows($xingao);

		$token->drop_token("shaidan"); //处理完后删除密钥
		if($rc>0)
		{
			//生成静态内容页,要有sdid
			require($_SERVER['DOCUMENT_ROOT'].'/xingao/shaidan/rehtml_call.php');
			$ts=$LG['pptEditSucceed'];
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
	$query="select img,path from shaidan where  {$where} and (img<>'' or path<>'') {$Mmy}";
	$sql=$xingao->query($query);
	while($rs=$sql->fetch_array())
	{
		//删除文件
		DelFile($rs['img']);
		DelFile($rs['path']);
	}
	$xingao->query("delete from shaidan where {$where} {$Mmy}");
	$rc=mysqli_affected_rows($xingao);
	
	if($rc>0){
		exit("<script>alert('{$LG['pptDelSucceed']}{$rc}');location='list.php';</script>");
	}else{
		exit("<script>alert('{$LG['pptDelEmpty']}\\n{$LG['shaidan.save_6']}');location='list.php';</script>");
	}
	
}
?>