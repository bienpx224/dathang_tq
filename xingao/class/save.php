<?php
/*
软件著作权：=====================================================
软件名称：兴奥全球代购网站管理系统(简称：兴奥代购系统)
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
$classid=$_REQUEST['classid'];
$bclassid=par($_POST['bclassid']);
$myorderid=$_POST['myorderid'];
$myorder=$_POST['myorder'];
$tokenkey=par($_POST['tokenkey']);
$path=letter(par($_POST['path']));
$old_path=par($_POST['old_path']);
$classtype=par($_POST['classtype']);
$listt=par($_POST['listt']);
$contentt=par($_POST['contentt']);


if (is_array($classid)){$classid=implode(',',$classid);}
$classid=par($classid);


//添加,修改=====================================================
if($lx=='add'||$lx=='edit')
{
	//通用验证------------------------------------
	$token=new Form_token_Core();
	$token->is_token("class",$tokenkey); //验证令牌密钥
	
	if(!par($_POST['path'])||!par($_POST['classtype'])){exit ("<script>alert('请填写或选择完整红框内容！');goBack();</script>");}
	
	if(strtolower(par($_POST['path']))=='m'){exit ("<script>alert('存放目录不能用“m” (m是系统目录)');goBack();</script>");}
	
	//获取全部上级目录
	if($bclassid)
	{
		$bfr=mysqli_fetch_array($xingao->query("select path from class where classid='{$bclassid}'"));
		$bpath=$bfr['path'].'/';
	}
	if(!have($bpath,'/html/',0)){$bpath='/html/'.$bpath;}
	$bpath=str_ireplace('//','/',$bpath);
	

	//语言字段处理--
	if(!$LGList){$LGList=languageType('',3);}
	if($LGList)
	{
		foreach($LGList as $arrkey=>$language)
		{
			if(!$_POST['img'.$language]){$_POST['img'.$language]=$_POST['imgadd'.$language];}		
			
			//留空时自动获取内容
			if(!trim($_POST['intro'.$language])){
				$_POST['intro'.$language]=leng($_POST['content'.$language],1000);
			}
			
			//保存远程文件
			if(spr($_POST['resave'.$language])){
				$_POST['content'.$language]=RemoteSave($_POST['content'.$language],spr($_POST['rewater'.$language]));
			}
			
			//处理
			$save_alone.="resave{$language},rewater{$language},imgadd{$language},old_img{$language},";
			$save_textarea.="intro{$language},";
			
		}
	}

	//添加------------------------------------
	if($lx=='add')
	{
		//检测存放目录是否重复
		if (file_exists($_SERVER['DOCUMENT_ROOT'].$bpath.$path)) 
		{
			exit ("<script>alert('该".$path."存放目录已被使用，请修改！');goBack();</script>");
		}
		
		$_POST['addtime']=time();
		$_POST['userid']=$Xuserid;
		$_POST['username']=$Xusername;
		$_POST['edittime']=0;
		$_POST['path']=$bpath.$path;
		
		$savelx='add';//调用类型(add,edit,cache)
		$getlx='POST';//获取类型(POST,GET,REQUEST,SQL)
		$alone=DelStr($save_alone).',classid,old_path';//不处理的字段
		$digital='myorder,classtype,onclick,onclick_ip,checked';//数字字段
		$radio='';//单选、复选、空文本、数组字段
		$textarea=DelStr($save_textarea);//过滤不安全的HTML代码
		$date='';//日期格式转数字
		$save=XingAoSave($_POST,$getlx,$savelx,$alone,$digital,$radio,$textarea,$html,$date);
		
		$xingao->query("insert into class (".$save['field'].") values(".$save['value'].")");
		SQLError();
		$rc=mysqli_affected_rows($xingao);
		
		if($rc>0)
		{
			//建立目录(商城时也建立,可能会有其他类型栏目)
			DoMkdir($bpath.$path);
			
			if($classtype!=3)
			{
				//生成静态页(商城不用静态页)
				$classid=mysqli_insert_id($xingao);
				$rootdir=$bpath.$path;
				CallREhtml($classtype,$listt,$contentt,$classid,0,$time,$rootdir,'list');
			}
			//处理完后删除密钥
			$token->drop_token("class"); 

			exit("<script>alert('{$LG['pptAddSucceed']}');location='form.php?bclassid=".$bclassid."&myorder=".$myorder."';</script>");
		}else{
			exit ("<script>alert('{$LG['pptAddFailure']}');goBack();</script>");
		}
	}
	
	
	
	
	
	
	
	
	
	//修改------------------------------------
	if($lx=='edit')
	{
		if(!$classid){exit ("<script>alert('classid{$LG['pptError']}');goBack();</script>");}
		if($bclassid==$classid){exit ("<script>alert('所属分类选择错误！');goBack();</script>");}
		$rs=FeData('class','bclassid,classid',"classid='{$classid}'");
		
		
		
		//修改存放目录||修改所属父级
		if($old_path!=$path||$rs['bclassid']!=$bclassid)
		{
			$old_bpath=FeData('class','path',"classid='{$rs['bclassid']}'");
			$old_allpath=$old_bpath.'/'.$old_path.'/';
			$new_allpath=$bpath.$path.'/';
			
			if(!have($old_allpath,'/html/',0)){$old_allpath='/html/'.$old_allpath;}$old_allpath=str_ireplace('//','/',$old_allpath);
			if(!have($new_allpath,'/html/',0)){$new_allpath='/html/'.$new_allpath;}$new_allpath=str_ireplace('//','/',$new_allpath);
			
				
			//检测存放目录是否重复========
			if(file_exists($_SERVER['DOCUMENT_ROOT'].$new_allpath)){exit ("<script>alert('该".$new_allpath."存放目录存在，请修改！');goBack();</script>");}

			if (file_exists($_SERVER['DOCUMENT_ROOT'].$old_allpath))
			{
				//旧目录存在:转移/修改到新目录
				renamePath($old_allpath,$new_allpath);
			}else{ 
				//不存在:建立目录
				DoMkdir($new_allpath);
			}
			
			
			//修改本栏目目录========
			$set=",path='".add(DelStr($new_allpath,'/'))."'";
			

			//修改子栏目目录和调用网址========
			//语言字段处理--
			if(!$LGList){$LGList=languageType('',3);}
			if($LGList)
			{
				foreach($LGList as $arrkey=>$language)
				{
					$where_url.=" or url{$language} like '%{$old_allpath}%'";
				}
			}

			$query="select * from class  where path like '%{$old_allpath}%' {$where_url}";
			$sql=$xingao->query($query);
			while($small=$sql->fetch_array())
			{
				$save_small="path='".add(str_replace($old_allpath,$new_allpath,$small['path']))."'";
				//语言字段处理--
				if($LGList)
				{
					foreach($LGList as $arrkey=>$language)
					{
						$save_small.=",url{$language}='".add(str_replace($old_allpath,$new_allpath,$small["url{$language}"]))."'";
					}
				}
				$xingao->query("update class set {$save_small} where classid='{$small[classid]}'");
				SQLError();
			}
			
		}
		
		
		
		
		
		


		//有单个文件字段时需要处理(要放在XingAoSave前面)
		//语言字段处理--
		if(!$LGList){$LGList=languageType('',3);}
		if($LGList)
		{
			foreach($LGList as $arrkey=>$language)
			{
				DelFile("img{$language}",'edit');
			}
		}
		
		$_POST['edittime']=time();
		
		//更新
		$savelx='edit';//调用类型(add,edit,cache)
		$getlx='POST';//获取类型(POST,GET,REQUEST,SQL)
		$alone=DelStr($save_alone).',classid,path,old_path';//不处理的字段
		$digital='myorder,classtype,checked';//数字字段
		$radio='';//单选、复选、空文本、数组字段
		$textarea=DelStr($save_textarea);//过滤不安全的HTML代码
		$date='';//日期格式转数字
		$save=XingAoSave($_POST,$getlx,$savelx,$alone,$digital,$radio,$textarea,$html,$date);
		
		$save.=$set;
		$xingao->query("update class set {$save} where classid='{$classid}'");
		SQLError('修改');
		$rc=mysqli_affected_rows($xingao);
	
		//生成静态页
		if($classtype==4)
		{
			$rootdir=$bpath.$path;
			CallREhtml($classtype,$listt,$contentt,$classid,0,$time,$rootdir,'list');
		}
	
		//处理完后删除密钥
		$token->drop_token("class"); 
		
		if($rc>0)
		{
			$ts=$LG['pptEditSucceed'];
		}else{
			$ts=$LG['pptEditNo'];
		}
		exit("<script>location='list.php';</script>");//alert('".$ts."');
	}
	
	
	
//删除=====================================================
}elseif($lx=='del'){
	
	if(!$classid){exit ("<script>alert('classid{$LG['pptError']}');goBack();</script>");}
	
	//验证是否有禁删栏目
	$aid=explode(",",$classid);//转为数组
	foreach($aid as $arrkey=>$value)//全输出
	{
		if (delclass_yz($value))
		{
			exit ("<script>alert('所选栏目中有禁册的栏目，不可删除！');goBack();</script>");
		}
	}

	//验证是否有子栏目
	$num=mysqli_num_rows($xingao->query("select bclassid from class where  bclassid in ({$classid})"));
	if($num)
	{
		exit ("<script>alert('所选栏目有子栏目，为防止误删，请先删除子栏目！');goBack();</script>");
	}

	
	$query="select * from class where classid in ({$classid}) ";
	$sql=$xingao->query($query);
	while($rs=$sql->fetch_array())
	{
		//删除栏目文件及目录
		if(!$LGList){$LGList=languageType('',3);}
		if($LGList)//语言字段处理
		{
			foreach($LGList as $arrkey=>$language)
			{
				DelFile($rs['img'.$language]);
				DelDirAndFile($rs['path']);
				DelEditorFile($rs['content'.$language]);
			}
		}
		
		
		
		//删除栏目里的信息
		if($rs['classtype']==1||$rs['classtype']==2)
		{
			$forid=0;
			$query2="select * from article where classid='{$rs[classid]}' ";
			$sql2=$xingao->query($query2);
			while($rs2=$sql2->fetch_array())
			{
				//删除文件(HTML目录上面已经删除)
				if(!$LGList){$LGList=languageType('',3);}
				if($LGList)//语言字段处理
				{
					foreach($LGList as $arrkey=>$language)
					{
						DelFile($rs2['img'.$language]);
						DelFile($rs2['hdimg'.$language]);
						DelFile($rs2['dow'.$language]);
						DelEditorFile($rs2['content'.$language]);
					}
				}

				if($forid){$forid.=','.$rs2['id'];}else{$forid=$rs2['id'];}
			}
			if($forid){$xingao->query("delete from article where id in ({$forid})");}
			
		}
		//删除栏目里的晒单
		elseif($rs['classtype']==2&&$off_shaidan)
		{
			$forid=0;
			$query2="select img,path from shaidan where classid='{$rs[classid]}' ";
			$sql2=$xingao->query($query2);
			while($rs2=$sql2->fetch_array())
			{
				//删除文件(HTML目录上面已经删除)
				DelFile($rs2['img']);

				if($forid){$forid.=','.$rs2['sdid'];}else{$forid=$rs2['sdid'];}
			}
			if($forid){$xingao->query("delete from shaidan where sdid in ({$forid})");}
		}
		//删除栏目里的商城
		elseif($rs['classtype']==3&&$off_mall)
		{
			$forid=0;
			$query2="select * from mall where classid='{$rs[classid]}' ";
			$sql2=$xingao->query($query2);
			while($rs2=$sql2->fetch_array())
			{
				//删除文件(HTML目录上面已经删除)
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

				if($forid){$forid.=','.$rs2['mlid'];}else{$forid=$rs2['mlid'];}
			}
			if($forid){$xingao->query("delete from mall where mlid in ({$forid})");}
		}
		
		
	}
	
	//删除栏目数据
	$xingao->query("delete from class where classid in ({$classid})");
	$rc=mysqli_affected_rows($xingao);
	
	if($rc>0){
		exit("<script>location='list.php';</script>");//alert('{$LG['pptDelSucceed']}{$rc}');
	}else{
		exit("<script>alert('{$LG['pptDelEmpty']}');location='list.php';</script>");
	}
	
}


//修改排序=====================================================
elseif($lx=='editorder')
{
	for($mfi=0;$mfi<count($myorderid);$mfi++)
	{
		$xingao->query("update class set myorder='{$myorder[$mfi]}' where classid='{$myorderid[$mfi]}'");
		SQLError();
	}
	exit("<script>location='list.php';</script>");
}

?>