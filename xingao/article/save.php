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
$pervar='qita';require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');

//获取,处理=====================================================
$my=par($_REQUEST['my']);
$lx=par($_REQUEST['lx']);
$id=par(ToStr($_REQUEST['id']));
$classid=par($_REQUEST['classid']);
$tokenkey=par($_POST['tokenkey']);



//添加,修改=====================================================
if($lx=='add'||$lx=='edit')
{
	//通用验证------------------------------------
	$token=new Form_token_Core();
	$token->is_token('article'.$id,$tokenkey); //验证令牌密钥
	if(!par($_POST['classid'])){exit ("<script>alert('请填写或选择完整红框内容！');goBack();</script>");}
	
	//语言字段处理--
	if(!$LGList){$LGList=languageType('',3);}
	if($LGList)
	{
		foreach($LGList as $arrkey=>$language)
		{
			
			//留空时自动获取内容
			if(!trim($_POST['intro'.$language])){
				$_POST['intro'.$language]=leng($_POST['content'.$language],1000);
			}
			
			//保存远程文件
			if(spr($_POST['resave'.$language])){
				$_POST['content'.$language]=RemoteSave($_POST['content'.$language],spr($_POST['rewater'.$language]));
			}
			
			//处理
			$save_alone.="resave{$language},rewater{$language},old_img{$language},old_hdimg{$language},old_dow{$language},";
			$save_textarea.="intro{$language},";
			
		}
	}
	
	
	
	
	
	
	
	
	//添加------------------------------------
	if($lx=='add')
	{
		$_POST['addtime']=time();
		$_POST['userid']=$Xuserid;
		$_POST['username']=$Xusername;
		$_POST['ismember']=0;
		
		$savelx='add';//调用类型(add,edit,cache)
		$getlx='POST';//获取类型(POST,GET,REQUEST,SQL)
		$alone=DelStr($save_alone).',id';//不处理的字段
		$digital='classid,isgood,ishead,istop,checked';//数字字段
		$radio='';//单选、复选、空文本、数组字段
		$textarea=DelStr($save_textarea);//过滤不安全的HTML代码
		$date='';//日期格式转数字
		$save=XingAoSave($_POST,$getlx,$savelx,$alone,$digital,$radio,$textarea,$html,$date);
		
		$xingao->query("insert into article (".$save['field'].") values(".$save['value'].")");SQLError('添加');
		$rc=mysqli_affected_rows($xingao);
		$id=mysqli_insert_id($xingao);
		
		if($rc>0)
		{
			//生成列表静态页
			$fr=FeData('class','classtype,listt,contentt,classid,path',"classid='{$classid}'");
			CallREhtml($fr['classtype'],$fr['listt'],$fr['contentt'],$fr['classid'],0,$time,$fr['path'],'list',1);

			//生成内容静态页
			
			$contentt=par($_POST['contentt']);if(!$contentt){$contentt=par($fr['contentt']);}
			$time=par($_POST['addtime']);
			$path=add(CallREhtml($fr['classtype'],$listt='',$contentt,$classid,$id,$time,$fr['path']));

			//保存生成路径
			$xingao->query("update article set path='{$path}' where id='{$id}'"); 
			SQLError();
			
			//处理完后删除密钥
			$token->drop_token('article'.$id); 

			exit("<script>alert('{$LG['pptAddSucceed']}');location='form.php?classid=".$classid."';</script>");
		}else{
			exit ("<script>alert('{$LG['pptAddFailure']}');goBack();</script>");
		}
	}
	
	//修改------------------------------------
	if($lx=='edit')
	{
		if(!$id){exit ("<script>alert('ID{$LG['pptError']}');goBack();</script>");}

		//有单个文件字段时需要处理(要放在XingAoSave前面)
		//语言字段处理--
		if(!$LGList){$LGList=languageType('',3);}
		if($LGList)
		{
			foreach($LGList as $arrkey=>$language)
			{
				DelFile("img{$language}",'edit');
				DelFile("hdimg{$language}",'edit');
				DelFile("dow{$language}",'edit');
			}
		}

		$_POST['edittime']=time();

		//更新
		$savelx='edit';//调用类型(add,edit,cache)
		$getlx='POST';//获取类型(POST,GET,REQUEST,SQL)
		$alone=DelStr($save_alone).',id,addtime';//不处理的字段
		$digital='classid,isgood,ishead,istop,checked';//数字字段
		$radio='';//单选、复选、空文本、数组字段
		$textarea=DelStr($save_textarea);//过滤不安全的HTML代码
		$date='';//日期格式转数字

		$save=XingAoSave($_POST,$getlx,$savelx,$alone,$digital,$radio,$textarea,$html,$date);
		$xingao->query("update article set {$save} where id='{$id}'");
		SQLError('修改');
		$rc=mysqli_affected_rows($xingao);
		
		//生成列表静态页
		$fr=FeData('class','classtype,listt,contentt,classid,path',"classid='{$classid}'");
		CallREhtml($fr['classtype'],$fr['listt'],$fr['contentt'],$fr['classid'],0,$time,$fr['path'],'list',1);

		//生成内容静态页
		$contentt=par($_POST['contentt']);if(!$contentt){$contentt=par($fr['contentt']);}
		$time=par($_POST['addtime']);
		$path=add(CallREhtml($fr['classtype'],$listt='',$contentt,$classid,$id,$time,$fr['path']));
		
		//保存生成路径
		$xingao->query("update article set path='{$path}' where id='{$id}'"); 
		SQLError('保存生成路径');
		
		//处理完后删除密钥
		$token->drop_token('article'.$id); 
		
		if($rc>0)
		{
			$ts=$LG['pptEditSucceed'];
		}else{
			$ts=$LG['pptEditNo'];
		}

		exit("<script>location='list.php?so=1&classid=".$classid."';</script>");//alert('".$ts."');
	}
	
	
	
}
//删除=====================================================
elseif($lx=='del'){
	
	if(!$id){exit ("<script>alert('id{$LG['pptError']}');goBack();</script>");}
	
	$query="select * from article where id in ({$id})";
	$sql=$xingao->query($query);
	while($rs=$sql->fetch_array())
	{
		//删除文件
		if(!$LGList){$LGList=languageType('',3);}
		if($LGList)//语言字段处理
		{
			foreach($LGList as $arrkey=>$language)
			{
				DelFile($rs['img'.$language]);
				DelFile($rs['hdimg'.$language]);
				DelFile($rs['dow'.$language]);
				DelFile(pathLT($rs['path'],$language));
				DelEditorFile($rs['content'.$language]);
			}
		}
		
		//生成列表静态页
		$fr=FeData('class','classtype,listt,contentt,classid,path',"classid='{$rs['classid']}'");
		CallREhtml($fr['classtype'],$fr['listt'],$fr['contentt'],$fr['classid'],0,$time,$fr['path'],'list',1);
	}
	
	//删除数据
	$xingao->query("delete from article where id in ({$id})");
	$rc=mysqli_affected_rows($xingao);
	
	if($rc>0)
	{
		exit("<script>location='list.php?so=1&classid=".$classid."';</script>");//alert('{$LG['pptDelSucceed']}{$rc}');
	}else{
		exit("<script>alert('{$LG['pptDelEmpty']}');location='list.php?so=1&classid=".$classid."';</script>");
	}
	
	
}
//修改属性=====================================================
elseif($lx=='attr'){
	if(!$id){exit ("<script>alert('请勾选信息！');goBack();</script>");}

	$isgood=par($_POST['isgood']);
	$ishead=par($_POST['ishead']);
	$istop=par($_POST['istop']);
	$checked=par($_REQUEST['checked']);//这个要用_REQUEST

	if( !CheckEmpty($isgood)&&!CheckEmpty($ishead)&&!CheckEmpty($istop)&&!CheckEmpty($checked) ){exit ("<script>alert('请选择要修改的属性！');goBack();</script>");}
	

	$set="edittime=".time();
	if(CheckEmpty($isgood)){$set.=",isgood='{$isgood}'";}
	if(CheckEmpty($ishead)){$set.=",ishead='{$ishead}'";}
	if(CheckEmpty($istop)){$set.=",istop='{$istop}'";}
	if(CheckEmpty($checked)){$set.=",checked='{$checked}'";}

	$xingao->query("update article set {$set} where id in ({$id})");
	SQLError('更新');
	$rc=mysqli_affected_rows($xingao);
	
	//生成静态页
	if(CheckEmpty($checked))
	{
		$query="select id,contentt,addtime,classid from article where id in ({$id})";
		$sql=$xingao->query($query);
		while($rs=$sql->fetch_array())
		{
			//生成列表静态页
			$fr=FeData('class','classtype,listt,contentt,classid,path',"classid='{$rs['classid']}'");
			CallREhtml($fr['classtype'],$fr['listt'],$fr['contentt'],$fr['classid'],0,$time,$fr['path'],'list',1);

			$rid=$rs['id'];
			$contentt=par($rs['contentt']);if(!$contentt){$contentt=par($fr['contentt']);}
			$time=par($rs['addtime']);
			$path=add(CallREhtml($fr['classtype'],$listt='',$contentt,$classid,$rid,$time,$fr['path']));
			
			//保存生成路径
			$xingao->query("update article set path='{$path}' where id='{$rid}'"); 
			SQLError();
		}
		
	}
	$prevurl = isset($_SERVER['HTTP_REFERER']) ? trim($_SERVER['HTTP_REFERER']) : '';
	exit("<script>alert('共修改{$rc}个信息！');location='".$prevurl."';</script>");
}
?>