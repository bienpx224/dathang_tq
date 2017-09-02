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
$pervar='yundan_ot';require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');

//获取,处理=====================================================
$lx=par($_REQUEST['lx']);
$hsid=$_REQUEST['hsid'];
$tokenkey=par($_POST['tokenkey']);

if($_POST['types']==2){$auto=spr($_POST['auto']);}

if (is_array($hsid)){$hsid=implode(',',$hsid);}
$hsid=par($hsid);


//添加,修改=====================================================
if($lx=='add'||$lx=='edit')
{
	//通用验证------------------------------------
	$token=new Form_token_Core();
	$token->is_token("hscode",$tokenkey); //验证令牌密钥
	
	if($auto&&spr($_POST['number_str'])<=0){exit ("<script>alert('自动生成数量填写错误！');goBack();</script>");}
	if(!$_POST['number_str']){exit ("<script>alert('请填写号码！');goBack();</script>");}
	if(!$_POST['types']){exit ("<script>alert('请选择类型！');goBack();</script>");}
	
	if($_POST['number_end'])
	{
		$number=findNum($_POST['number_str']);//提取数字部分
		$number=str_replace($number,$number+1,$_POST['number_str']);
		if($number==$_POST['number_str']){exit ("<script>alert('号段开头格式错误！');goBack();</script>");}
		
		$number=findNum($_POST['number_end']);//提取数字部分
		$number=str_replace($number,$number+1,$_POST['number_end']);
		if($number==$_POST['number_end']){exit ("<script>alert('号段结尾格式错误！');goBack();</script>");}
	}


	//添加------------------------------------
	if($lx=='add')
	{
		$number=1;if($auto){$number=spr($_POST['number_str']);$alone=',number_str';}
		
		$_POST['addtime']=time();
		$savelx='add';//调用类型(add,edit,cache)
		$getlx='POST';//获取类型(POST,GET,REQUEST,SQL)
		$alone='hsid,auto'.$alone;//不处理的字段
		$digital='checked';//数字字段
		$radio='';//单选、复选、空文本、数组字段
		$textarea='';//过滤不安全的HTML代码
		$date='';//日期格式转数字
		$save=XingAoSave($_POST,$getlx,$savelx,$alone,$digital,$radio,$textarea,$html,$date);

		//自动生成批次号
		for ($ci=1; $ci<=$number; $ci++) 
		{
			
			if($auto)
			{
				$letter=Cumulative('auto_lotno','d');//获取当前累积数字
				$letter=strtoupper(chr($letter+96));//按数字转成字母并转大写
				$number_str=$letter.substr(date('Y'),-2).date('m').date('d');//生成完成的批次号码
				$save['field_auto']=",number_str";
				$save['value_auto']=",'{$number_str}'";
			}
			
			$xingao->query("insert into hscode (".$save['field'].$save['field_auto'].") values(".$save['value'].$save['value_auto'].")");
			SQLError('添加号码');
			$rc+=mysqli_affected_rows($xingao);
		}
		
		
		if($rc>0)
		{
			$token->drop_token("hscode"); //处理完后删除密钥
			$ppt="添加成功!";if($auto){$ppt="共添加{$rc}个!";}
			exit("<script>alert('{$ppt}可继续添加！');location='form.php';</script>");
		}else{
			exit ("<script>alert('{$LG['pptAddFailure']}');goBack();</script>");
		}
	}
	
	//修改------------------------------------
	if($lx=='edit')
	{
		if(!$hsid){exit ("<script>alert('hsid{$LG['pptError']}');goBack();</script>");}
		
		//更新
		$savelx='edit';//调用类型(add,edit,cache)
		$getlx='POST';//获取类型(POST,GET,REQUEST,SQL)
		$alone='hsid';//不处理的字段
		$digital='checked';//数字字段
		$radio='';//单选、复选、空文本、数组字段
		$textarea='';//过滤不安全的HTML代码
		$date='';//日期格式转数字
		$save=XingAoSave($_POST,$getlx,$savelx,$alone,$digital,$radio,$textarea,$html,$date);
		$xingao->query("update hscode set ".$save." where hsid='{$hsid}'");
		SQLError();		
		$rc=mysqli_affected_rows($xingao);

		$token->drop_token("hscode"); //处理完后删除密钥
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
	
	if(!$hsid){exit ("<script>alert('hsid{$LG['pptError']}');goBack();</script>");}
	
	$xingao->query("delete from hscode where hsid in ({$hsid})");
	$rc=mysqli_affected_rows($xingao);
	
	if($rc>0){
		exit("<script>alert('{$LG['pptDelSucceed']}{$rc}');location='list.php';</script>");
	}else{
		exit("<script>alert('{$LG['pptDelEmpty']}');location='list.php';</script>");
	}
	
}

//修改属性=====================================================
elseif($lx=='attr'){
	$checked=$_REQUEST['checked'];
	
	if(!$hsid){exit ("<script>alert('请勾选要修改的信息!');goBack();</script>");}
	if( !CheckEmpty($checked)){exit ("<script>alert('请选择要修改的状态！');goBack();</script>");}
	
	$xingao->query("update hscode set checked='{$checked}' where hsid in ({$hsid})");
	SQLError('修改状态');
	$rc=mysqli_affected_rows($xingao);
	$prevurl = isset($_SERVER['HTTP_REFERER']) ? trim($_SERVER['HTTP_REFERER']) : '';
	exit("<script>location='".$prevurl."';</script>");//alert('共修改{$rc}个信息！');
}
?>