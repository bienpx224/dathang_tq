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
$pervar='classify';require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');

//获取,处理=====================================================
$my=par($_REQUEST['my']);
$lx=par($_REQUEST['lx']);
$classid=$_REQUEST['classid'];
$bclassid=par($_POST['bclassid']);
$myorderid=$_POST['myorderid'];
$myorder=$_POST['myorder'];
$tokenkey=par($_POST['tokenkey']);
$classtype=par($_POST['classtype']);

if (is_array($classid)){$classid=implode(',',$classid);}
$classid=par($classid);



//添加,修改=====================================================
if($lx=='add'||$lx=='edit')
{
	//通用验证------------------------------------
	$token=new Form_token_Core();
	$token->is_token("classify",$tokenkey); //验证令牌密钥
	
	if(!par($_POST['classtype'])){exit ("<script>alert('请填写或选择完整红框内容！');goBack();</script>");}
	
	

	
	//添加------------------------------------
	if($lx=='add')
	{
		
		$codes=ToArr($_POST['codes'],1);
		
		//验证---------------------------
		//语言字段处理--
		if(!$LGList){$LGList=languageType('',3);}
		if($LGList)
		{
			foreach($LGList as $arrkey=>$language)
			{
				
				if(!$main['name'])
				{
					//第一次
					$main['name']=ToArr($_POST['name'.$language],1);
					$main['LT']=$language;
					if(!$main['name']){exit ("<script>alert('请填写完整名称！');goBack();</script>");}
					if($codes&&arrcount($codes)!=arrcount($main['name'])) {exit ("<script>alert('代码行数和{$main['LT']}名称行数不相同，请检查！');goBack();</script>");}
				}else{
					//非第一次后验证数量
					if(arrcount(ToArr($_POST['name'.$language],1))!=arrcount($main['name'])) {exit ("<script>alert('{$language}名称行数 和 {$main['LT']}名称行数不相同，请检查！');goBack();</script>");}
				}
				
				//生成保存字段
				$saveFieldName.="name{$language},";
				
				//处理
				$save_alone.="name{$language},";
				
			}//foreach($LGList as $arrkey=>$language)
		}//if($LGList)
		
		$saveFieldName=DelStr($saveFieldName);
		$save_alone=DelStr($save_alone);
		
		
		
		
		
		
		$_POST['addtime']=time();
		$savelx='add';//调用类型(add,edit,cache)
		$getlx='POST';//获取类型(POST,GET,REQUEST,SQL)
		$alone=$save_alone.',classid,codes';//不处理的字段
		$digital='myorder,classtype,checked';//数字字段
		$radio='';//单选、复选、空文本、数组字段
		$textarea='';//过滤不安全的HTML代码
		$date='';//日期格式转数字
		$save=XingAoSave($_POST,$getlx,$savelx,$alone,$digital,$radio,$textarea,$html,$date);
		
		
		
		
		
		
		
		
		
		//批量添加---------------------------
		$arr=$main['name'];
		if($arr)
		{
			if(!is_array($arr)){$arr=explode(',',$arr);}//转数组
			foreach($arr as $arrkey=>$value)
			{
				
				
				$saveFieldValue='';
				$numWhere='';
				
				//语言字段处理--
				if(!$LGList){$LGList=languageType('',3);}
				if($LGList)
				{
					foreach($LGList as $arrkey=>$language)
					{
						$name=ToArr($_POST['name'.$language],1);
						
						//生成查询重名条件
						if($name[$arrkey]){$numWhere.=" name{$language}='{$name[$arrkey]}' or";}
						
						//生成保存名称
						$saveFieldValue.="'{$name[$arrkey]}',";
						
					}
				}
				$numWhere=DelStr($numWhere,'or');
				$saveFieldValue=DelStr($saveFieldValue);
				
				
				
				
				//验证同分类里是否有重名
				$chk=1;
				$num=mysqli_num_rows($xingao->query("select classid from classify where classtype='{$classtype}' and bclassid='{$bclassid}' and ({$numWhere})"));
				
				if($num){
					$err++;$chk=0;
				}else{
					//批量添加：代码
					$codes_value='';
					if($codes[$arrkey])
					{
						//验证同分类里是否有重复
						$num=mysqli_num_rows($xingao->query("select classid from classify where classtype='{$classtype}' and bclassid='{$bclassid}' and codes='".add($codes[$arrkey])."'"));
						if($num){$err++;$chk=0;}
						else{$codes_value=add($codes[$arrkey]);}
					}
				}
				
				if($chk){$save_value.="(".$save['value'].",'{$codes_value}',{$saveFieldValue}),";}
			}
		}
		$save_value=DelStr($save_value);
		
		

		
		
		$xingao->query("insert into classify (".$save['field'].",codes,{$saveFieldName}) values {$save_value}");
		SQLError('添加分类');
		$rc=mysqli_affected_rows($xingao);
		
		
		if($rc>0)
		{
			$token->drop_token("classify"); 
			if($err){$ppt='\\n该分类里已有同名'.$err.'个 (未添加)';}
			if($classtype==3){$form='_3';}
			exit("<script>alert('{$LG['pptAddSucceed']}\\n共添加{$rc}{$ppt}');location='form{$form}.php?bclassid=".$bclassid."&myorder=".$myorder."';</script>");
		}else{
			exit ("<script>alert('{$LG['pptAddFailure']}');goBack();</script>");
		}
	}
	
	
	
	
	
	//修改------------------------------------
	elseif($lx=='edit')
	{
		if(!$classid){exit ("<script>alert('classid{$LG['pptError']}');goBack();</script>");}
		if($bclassid==$classid){exit ("<script>alert('所属分类选择错误！');goBack();</script>");}
		
		
		//语言字段处理--
		if(!$LGList){$LGList=languageType('',3);}
		if($LGList)
		{
			foreach($LGList as $arrkey=>$language)
			{
				//生成查询重名条件
				if($_POST['name'.$language]){$numWhere.=" name{$language}='".add($_POST['name'.$language])."' or";}
			}
		}
		$numWhere=DelStr($numWhere,'or');

	
		//验证同分类里是否有重复名称
		$num=mysqli_num_rows($xingao->query("select classtype from classify where classtype='{$classtype}' and bclassid='{$bclassid}' and classid<>'{$classid}' and ({$numWhere})"));
		if($num)
		{
			exit ("<script>alert('该分类里已有重复名称！');goBack();</script>");
		}
		
		//验证同大分类里是否有重复代码
		if(par($_POST['codes']))
		{
			$num=mysqli_num_rows($xingao->query("select classtype from classify where classtype='{$classtype}' and bclassid='{$bclassid}' and codes='".par($_POST['codes'])."' "));
			if($num>1)
			{
				exit ("<script>alert('该分类里已有重复代码！');goBack();</script>");
			}
		}
		
		
		//更新
		$savelx='edit';//调用类型(add,edit,cache)
		$getlx='POST';//获取类型(POST,GET,REQUEST,SQL)
		$alone='classid';//不处理的字段
		$digital='myorder,classtype,checked';//数字字段
		$radio='';//单选、复选、空文本、数组字段
		$textarea='';//过滤不安全的HTML代码
		$date='';//日期格式转数字
		$save=XingAoSave($_POST,$getlx,$savelx,$alone,$digital,$radio,$textarea,$html,$date);
		$xingao->query("update classify set ".$save." where classid='{$classid}'");
		SQLError('修改');
		$rc=mysqli_affected_rows($xingao);
	
		
		if($rc>0)
		{
			//处理完后删除密钥
			$token->drop_token("classify"); 
			exit("<script>location='list.php';</script>");
		}else{
			exit ("<script>alert('{$LG['pptEditNo']}');goBack();</script>");
		}
		
		
	}
}	
	
	
//删除=====================================================
elseif($lx=='del'){
	
	if(!$classid){exit ("<script>alert('classid{$LG['pptError']}');goBack();</script>");}
	
	//验证是否有子分类
	$num=mysqli_num_rows($xingao->query("select bclassid from classify where  bclassid in ({$classid})"));
	if($num)
	{
		exit ("<script>alert('所选分类有子分类，为防止误删，请先删除子分类！');goBack();</script>");
	}

	
	$query="select * from classify where classid in ({$classid}) ";
	$sql=$xingao->query($query);
	while($rs=$sql->fetch_array())
	{
		//删除分类里的清关资料
		if($rs['classtype']==1)
		{
			$query2="select img,content from gd_japan where classid='{$rs[classid]}' ";
			$sql2=$xingao->query($query2);
			while($rs2=$sql2->fetch_array())
			{
				//删除文件
				DelFile($rs2['img']);
				DelEditorFile($rs2['content']);
			}
			$xingao->query("delete from gd_japan where classid='{$rs[classid]}' ");
			
		}
		//删除分类里的地址(未开发)
		elseif($rs['classtype']==2)
		{
			
		}
		
	}
	
	//删除分类数据
	$xingao->query("delete from classify where classid in ({$classid})");
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
		$xingao->query("update classify set myorder='{$myorder[$mfi]}' where classid='{$myorderid[$mfi]}'");
		SQLError();
	}
	exit("<script>location='list.php';</script>");
}
?>