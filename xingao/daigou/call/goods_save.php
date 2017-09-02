<?php 
if(!$ON_daigou){exit ("<script>alert('{$LG['daigou.45']}');goBack('uc');</script>");}
$tokenkey=par($_POST['tokenkey']);

//会员时重新指定:防止注入
if($Muserid){
	$_POST['userid']=$Muserid;
	$_POST['username']=$Musername;
}

//添加,修改=====================================================
if($smt&&($typ=='add'||$typ=='edit'))
{
	$tmp=par($_POST['tmp']);
	//通用验证------------------------------------
	$token=new Form_token_Core();
	$token->is_token("goods{$goid}",$tokenkey); //验证令牌密钥

	if(!$_POST['tmp'])	{exit ("<script>alert('{$LG['daigou.84']}');goBack();</script>");}
	if($_POST['number']<=0||$_POST['price']<=0)	{exit ("<script>alert('{$LG['lipei.save_1']}');goBack();</script>");}
	if(!$_POST['userid']||!$_POST['username'])	{exit ("<script>alert('userid/username{$LG['pptEmpty']}');goBack();</script>");}
	
	
	//地址簿
	if($callFrom='manage'){$Mmy=" and userid='{$_POST['userid']}'";$Muserid=spr($_POST['userid']);$Musername=add($_POST['username']);}
	$address_save_s=par($_POST['address_save_s']);
	if($address_save_s)	{ $sf='s';require($_SERVER['DOCUMENT_ROOT'].'/xingao/address/call/out_save.php'); }//返回$retAaddid
	$_POST['addid']=$retAaddid;
	
	
	//添加------------------------------------
	if($typ=='add')
	{
		$_POST['addtime']=time();
		$savelx='add';//调用类型(add,edit,cache)
		$getlx='POST';//获取类型(POST,GET,REQUEST,SQL)
		$alone=daigou_with_field('alone');//不处理的字段
		$digital=daigou_with_field('digital');//数字字段
		$radio='';//单选、复选、空文本、数组字段
		$textarea='';//过滤不安全的HTML代码
		$date='';//日期格式转数字
		$save=XingAoSave($_POST,$getlx,$savelx,$alone,$digital,$radio,$textarea,$html,$date);
		
		$xingao->query("insert into daigou_goods  (".$save['field'].") values(".$save['value'].")");
		SQLError('商品添加');
		
		if(mysqli_affected_rows($xingao)>0)
		{
			$token->drop_token("goods{$goid}"); //处理完后删除密钥
			$err=0;	$ppt=$LG['pptAddSucceed'];
		}else{
			exit ("<script>alert('{$LG['pptAddFailure']}');goBack();</script>");
		}
	}
	
	//修改------------------------------------
	if($typ=='edit')
	{
		if(!$goid){exit ("<script>alert('goid{$LG['pptError']}');goBack();</script>");}
		
		//更新
		$_POST['edittime']=time();
		$savelx='edit';//调用类型(add,edit,cache)
		$getlx='POST';//获取类型(POST,GET,REQUEST,SQL)
		$alone=daigou_with_field('alone');//不处理的字段
		$digital=daigou_with_field('digital');//数字字段
		$radio='';//单选、复选、空文本、数组字段
		$textarea='';//过滤不安全的HTML代码
		$date='';//日期格式转数字
		$save=XingAoSave($_POST,$getlx,$savelx,$alone,$digital,$radio,$textarea,$html,$date);
		$xingao->query("update daigou_goods  set ".$save." where goid='{$goid}' {$Mmy}");
		SQLError('商品更新');		
		
		if(mysqli_affected_rows($xingao)>0)
		{
			$token->drop_token("goods{$goid}"); //处理完后删除密钥
			$err=0;	$ppt=$LG['pptEditSucceed']; $typ='add';
		}else{
			exit ("<script>alert('{$LG['pptEditNo']}');goBack();</script>");
		}
		
	}
	
	
	
//删除=====================================================
}elseif($typ=='del'){
	if(!$goid){exit ("<script>alert('{$LG['daigou.164']}');goBack('goods.php?tmp={$_GET['tmp']}');</script>");}
	
	$xingao->query("delete from daigou_goods  where goid in ({$goid}) and (memberStatus=0 or manageStatus<>'1' or memberStatus_pay=0) {$Mmy}");
	$rc=mysqli_affected_rows($xingao);
	
	if($rc>0){
		$err=0;	$ppt=$LG['pptDelSucceed'].$rc;
	}else{
		if($callFrom=='manage'){$ppt='\\n如有待处理的服务时,不可删除,请先处理申请';}
		exit("<script>alert('{$LG['pptDelEmpty']}{$ppt}');goBack('goods.php?tmp={$_GET['tmp']}');</script>");
	}
	
}
?>