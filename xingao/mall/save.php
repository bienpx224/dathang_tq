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
$pervar='mall';require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');
if(!$off_mall)
{
	exit ("<script>alert('商城系统未开启,无法使用！');goBack('uc');</script>");
}

//获取,处理=====================================================
$my=par($_REQUEST['my']);
$lx=par($_REQUEST['lx']);
$mlid=$_REQUEST['mlid'];
$classid=par($_REQUEST['classid']);
$tokenkey=par($_POST['tokenkey']);

if (is_array($mlid)){$mlid=implode(',',$mlid);}
$mlid=par($mlid);



//添加,修改=====================================================
if($lx=='add'||$lx=='edit')
{
	//通用验证------------------------------------
	$token=new Form_token_Core();
	$token->is_token("mall",$tokenkey); //验证令牌密钥
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
			$save_alone.="resave{$language},rewater{$language},old_img{$language},old_titleimg{$language},";
			$save_textarea.="intro{$language},";
			$save_radio.="img{$language},";//数组字段
			
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
		$alone=DelStr($save_alone).',mlid';//不处理的字段
		$digital='classid,isgood,ishead,istop,addtime,price,weight,price_other,number,number_limit,checked,integral_use,integral_to,ensure';//数字字段
		$radio=DelStr($save_radio).',warehouse';//单选、复选、空文本、数组字段
		$textarea=DelStr($save_textarea);//过滤不安全的HTML代码
		$date='';//日期格式转数字
		$save=XingAoSave($_POST,$getlx,$savelx,$alone,$digital,$radio,$textarea,$html,$date);
		
		$xingao->query("insert into mall (".$save['field'].") values(".$save['value'].")");
		SQLError();
		$rc=mysqli_affected_rows($xingao);
		
		if($rc>0)
		{
			//处理完后删除密钥
			$token->drop_token("mall"); 

			exit("<script>alert('{$LG['pptAddSucceed']}');location='form.php?classid=".$classid."';</script>");
		}else{
			exit ("<script>alert('{$LG['pptAddFailure']}');goBack();</script>");
		}
	}
	
	//修改------------------------------------
	if($lx=='edit')
	{
		if(!$mlid){exit ("<script>alert('mlid{$LG['pptError']}');goBack();</script>");}
		
		//有单个文件字段时需要处理(要放在XingAoSave前面)
		//语言字段处理--
		if(!$LGList){$LGList=languageType('',3);}
		if($LGList)
		{
			foreach($LGList as $arrkey=>$language)
			{
				DelFile("titleimg{$language}",'edit');
			}
		}
		$_POST['edittime']=time();
		
		//更新
		$savelx='edit';//调用类型(add,edit,cache)
		$getlx='POST';//获取类型(POST,GET,REQUEST,SQL)
		$alone=DelStr($save_alone).',mlid,addtime';//不处理的字段
		$digital='classid,isgood,ishead,istop,price,weight,price_other,number,number_limit,checked,integral_use,integral_to,ensure';//数字字段
		$radio=DelStr($save_radio).',warehouse';//单选、复选、空文本、数组字段
		$textarea=DelStr($save_textarea);//过滤不安全的HTML代码
		$date='';//日期格式转数字

		$save=XingAoSave($_POST,$getlx,$savelx,$alone,$digital,$radio,$textarea,$html,$date);
		$xingao->query("update mall set {$save} where mlid='{$mlid}' {$Xwh}");
		SQLError();
		$rc=mysqli_affected_rows($xingao);

		//处理完后删除密钥
		$token->drop_token("mall"); 
		
		if($rc>0)
		{
			$ts=$LG['pptEditSucceed'];
		}else{
			$ts='未修改商品';
		}

		exit("<script>alert('".$ts."');location='list.php?so=1&classid=".$classid."';</script>");
	}
	
	
	
}
//删除=====================================================
elseif($lx=='del'){
	
	if(!$mlid){exit ("<script>alert('mlid{$LG['pptError']}');goBack();</script>");}
	
	$query="select * from mall where mlid in ({$mlid})  {$Xwh}";
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
				DelFile($rs['titleimg'.$language]);
				DelEditorFile($rs['content'.$language]);
			}
		}
	}
	
	//删除数据
	$xingao->query("delete from mall where mlid in ({$mlid}) {$Xwh}");
	$rc=mysqli_affected_rows($xingao);
	
	if($rc>0)
	{
		exit("<script>alert('{$LG['pptDelSucceed']}{$rc}');location='list.php?so=1&classid=".$classid."';</script>");
	}else{
		exit("<script>alert('{$LG['pptDelEmpty']}');location='list.php?so=1&classid=".$classid."';</script>");
	}
	
	
}
//修改属性=====================================================
elseif($lx=='attr'){
	if(!$mlid){exit ("<script>alert('请勾选商品！');goBack();</script>");}

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

	$xingao->query("update mall set {$set} where mlid in ({$mlid}) {$Xwh}");
	SQLError();
	$rc=mysqli_affected_rows($xingao);
	
	$prevurl = isset($_SERVER['HTTP_REFERER']) ? trim($_SERVER['HTTP_REFERER']) : '';
	exit("<script>alert('共修改{$rc}个商品！');location='".$prevurl."';</script>");
}
?>