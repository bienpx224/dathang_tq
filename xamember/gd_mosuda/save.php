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
if(!$ON_gd_mosuda_apply){exit ("<script>alert('{$LG['pptClose']}');goBack();</script>");}
?>
<style>
html{overflow-x:hidden;}
body{width:620px; margin:0px;min-height:50px;}
</style>
<?php 

//获取,处理-----------------------------------------------------------------------------------------------
$lx=par($_REQUEST['lx']);
$gdid=par($_POST['gdid']);
$tokenkey=par($_POST['tokenkey']);
//添加,修改=====================================================
if($lx=='add'||$lx=='edit')
{
	//通用验证------------------------------------
	$token=new Form_token_Core();
	$token->is_token('goodsdata'.$gdid,$tokenkey); //验证令牌密钥
	
	//验证同分类里是否有重名
	if($lx=='edit'){$where_repeat=" and gdid<>'{$gdid}'";}
	$num=mysqli_num_rows($xingao->query("select gdid from gd_mosuda where  name='".par($_POST['name'])."' and (record in (0,2) or (record='1' and userid='{$Muserid}')) {$where_repeat}"));
	if($num){exit ("<script>alert('{$LG['gd_mosuda.4']}');goBack();</script>");}

	if(!$_POST['name']||!$_POST['brand']||!$_POST['price']||!$_POST['url']){exit ("<script>alert('{$LG['gd_mosuda.5']}');goBack();</script>");}

	
	//添加------------------------------------
	if($lx=='add')
	{
		$_POST['checked']=1;
		$_POST['record']=1;
		$_POST['member']=1;
		$_POST['userid']=$Muserid;
		$_POST['username']=$Musername;
		$_POST['addtime']=time();

		$savelx='add';//调用类型(add,edit,cache)
		$getlx='POST';//获取类型(POST,GET,REQUEST,SQL)
		$alone='gdid';//不处理的字段
		$digital='price';//数字字段
		$radio='';//单选、复选、空文本、数组字段
		$textarea='';//过滤不安全的HTML代码
		$date='';//日期格式转数字
		$save=XingAoSave($_POST,$getlx,$savelx,$alone,$digital,$radio,$textarea,$html,$date);
		
		$xingao->query("insert into gd_mosuda (".$save['field'].") values(".$save['value'].")");SQLError('添加商品备案');
		//$gdid=mysqli_insert_id($xingao);
		
		if(mysqli_affected_rows($xingao)>0)
		{
			//处理完后删除密钥
			$token->drop_token('goodsdata'.$gdid);
			exit($LG['gd_mosuda.6']);
		}else{
			exit ("<script>alert('{$LG['pptAddFailure']}');goBack();</script>");
		}
	}
	
	//修改------------------------------------
	elseif($lx=='edit')
	{
		if(!$gdid){exit ("<script>alert('gdid{$LG['pptError']}');goBack('c');</script>");}
		$_POST['edittime']=time();
		
		//更新
		$savelx='edit';//调用类型(add,edit,cache)
		$getlx='POST';//获取类型(POST,GET,REQUEST,SQL)
		$alone='gdid';//不处理的字段
		$digital='price';//数字字段
		$radio='';//单选、复选、空文本、数组字段
		$textarea='';//过滤不安全的HTML代码
		$date='';//日期格式转数字
		$save=XingAoSave($_POST,$getlx,$savelx,$alone,$digital,$radio,$textarea,$html,$date);

		$xingao->query("update gd_mosuda set ".$save." where gdid='{$gdid}' {$Mmy}");SQLError('修改商品备案');
		
		if(mysqli_affected_rows($xingao)>0)
		{
			//处理完后删除密钥
			$token->drop_token("goodsdata".$gdid); 
			exit($LG['pptEditSucceed']);
		}else{
			exit("<script>alert('{$LG['pptEditFailure']}');goBack();</script>");
		}
	}
	
}



//删除------------------------------------
elseif($lx=='del')
{
	$gdid=par($_REQUEST['gdid']);
	if(!$gdid){exit ("<script>alert('gdid{$LG['pptError']}');goBack();</script>");}
	
	$query="select img,gdid from gd_mosuda where gdid in ({$gdid}) and gdid not in (select gdid from wupin) {$Mmy}";
	$sql=$xingao->query($query);
	while($rs=$sql->fetch_array())
	{
		//删除文件
		DelFile($rs['img']);
		
		//删除数据
		$xingao->query("delete from gd_mosuda where gdid='{$rs['gdid']}'");
		
		$rc++;
	}
	
	
	if($rc>0)
	{
		exit("<script>alert('{$LG['pptDelSucceed']}{$rc}');location='list.php';</script>");
	}else{
		exit("<script>alert('{$LG['pptDelEmpty']}\\n{$LG['gd.9']}');location='list.php';</script>");
	}
}

?>
