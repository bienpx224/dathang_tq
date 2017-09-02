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
$pervar='goodsdata';require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');
if(!$ON_gd_mosuda){exit ("<script>alert('跨境翼清关资料系统已关闭！');goBack();</script>");}
//获取,处理=====================================================
$lx=par($_REQUEST['lx']);
$gdid=$_REQUEST['gdid'];
$myorderid=$_POST['myorderid'];
$myorder=$_POST['myorder'];
$copy=par($_POST['copy']);
$img=$_POST['img'];$img=ToStr($img);
$tokenkey=par($_POST['tokenkey']);

if (is_array($gdid)){$gdid=implode(',',$gdid);} $gdid=par($gdid);



//添加,修改=====================================================
if($lx=='add'||$lx=='edit')
{
	//通用验证------------------------------------
	$token=new Form_token_Core();
	$token->is_token('goodsdata'.$gdid,$tokenkey); //验证令牌密钥
	
	//验证同分类里是否有重名
	if($lx=='edit'){$where_repeat=" and gdid<>'{$gdid}'";}
	$num=mysqli_num_rows($xingao->query("select gdid from gd_mosuda where  itemNo='".par($_POST['itemNo'])."' {$where_repeat}"));//只验证 商品货号 ,同一个商品有不同的规格,因此其他属性都有可能重复
	if($num){exit ("<script>alert('商品货号重复！');goBack();</script>");}
	
	$_POST['types']=str_ireplace(',','，',$_POST['types']);/*types用于限制渠道分类,不能用[,]逗号*/

	//添加------------------------------------
	if($lx=='add')
	{
		$_POST['userid']=$Xuserid;
		$_POST['username']=$Xusername;
		$_POST['addtime']=time();

		$savelx='add';//调用类型(add,edit,cache)
		$getlx='POST';//获取类型(POST,GET,REQUEST,SQL)
		$alone='gdid,old_img,copy';//不处理的字段
		$digital='taxes,consumptionTax,valueTax,comprehensiveTax,recordPrice,weightGross,weightSuttle,record,myorder,checked';//数字字段
		$radio='';//单选、复选、空文本、数组字段
		$textarea='composition,content';//过滤不安全的HTML代码
		$date='';//日期格式转数字
		$save=XingAoSave($_POST,$getlx,$savelx,$alone,$digital,$radio,$textarea,$html,$date);
		
		$xingao->query("insert into gd_mosuda (".$save['field'].") values(".$save['value'].")");SQLError('添加商品');
		$rc=mysqli_affected_rows($xingao);
		$gdid=mysqli_insert_id($xingao);
		
		if($rc>0)
		{
			//处理完后删除密钥
			$token->drop_token('goodsdata'.$gdid);
			if($copy)
			{
				exit("<script>location='form.php?ret=1&copy=1&gdid=".$gdid."';</script>");
			}else{
				exit("<script>location='form.php?ret=1';</script>");
			}
			
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
		$alone='gdid,old_img,copy';//不处理的字段
		$digital='taxes,consumptionTax,valueTax,comprehensiveTax,recordPrice,weightGross,weightSuttle,record,myorder,checked';//数字字段
		$radio='';//单选、复选、空文本、数组字段
		$textarea='composition,content';//过滤不安全的HTML代码
		$date='';//日期格式转数字

		$save=XingAoSave($_POST,$getlx,$savelx,$alone,$digital,$radio,$textarea,$html,$date);
		$xingao->query("update gd_mosuda set ".$save." where gdid='{$gdid}'");SQLError('修改商品');
		$rc=mysqli_affected_rows($xingao);

		
		if($rc>0)
		{
			//处理完后删除密钥
			$token->drop_token("goodsdata".$gdid); 
			exit("<script>goBack('c');</script>");
		}else{
			exit("<script>alert('未有修改');goBack();</script>");
		}

		
	}
	
}


//删除=====================================================
elseif($lx=='del'){
	
	if(!$gdid){exit ("<script>alert('gdid{$LG['pptError']}');goBack();</script>");}
	
	$query="select img,gdid from gd_mosuda where gdid in ({$gdid}) and gdid not in (select gdid from wupin)";
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
		exit("<script>alert('{$LG['pptDelEmpty']}\\n只能删除未有运单使用过的资料');location='list.php';</script>");
	}
	
	
}
//修改属性=====================================================
elseif($lx=='attr'){
	if(!$gdid){exit ("<script>alert('请勾选订单！');goBack();</script>");}

	$checked=par($_POST['checked']);

	$set='';
	if(CheckEmpty($checked)){$set.="checked='{$checked}'";}
	if(!$set){exit ("<script>alert('请选择要修改的属性！');goBack();</script>");}
	if($set){$set.=",edittime=".time();}

	$xingao->query("update gd_mosuda set {$set} where gdid in ({$gdid})");
	SQLError('修改属性');
	$rc=mysqli_affected_rows($xingao);
	
	$prevurl = isset($_SERVER['HTTP_REFERER']) ? trim($_SERVER['HTTP_REFERER']) : '';
	if($rc)
	{
		exit("<script>alert('共修改{$rc}个商品资料！');location='".$prevurl."';</script>");
	}else{
		exit("<script>alert('未有修改！');location='".$prevurl."';</script>");
	}
}


//修改排序=====================================================
elseif($lx=='editorder')
{
	for($mfi=0;$mfi<count($myorderid);$mfi++)
	{
		$xingao->query("update gd_mosuda set myorder='{$myorder[$mfi]}' where gdid='{$myorderid[$mfi]}'");
		SQLError();
	}
	exit("<script>location='list.php';</script>");
}
?>