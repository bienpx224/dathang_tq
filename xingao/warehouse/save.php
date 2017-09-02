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
$pervar='manage_sy';require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');

//获取,处理=====================================================
$my=par($_REQUEST['my']);
$lx=par($_REQUEST['lx']);
$whid=$_REQUEST['whid'];
$tokenkey=par($_POST['tokenkey']);

if (is_array($whid)){$whid=ToStr($whid,',');}
$whid=par($whid);


//添加,修改=====================================================
if($lx=='add'||$lx=='edit')
{
	//通用验证------------------------------------
	$token=new Form_token_Core();
	$token->is_token('warehouse',$tokenkey); //验证令牌密钥
	$_POST['area']=spr($_POST['area']);if(!$_POST['area']){$_POST['area']=1;}
	
	
	//语言字段处理--
	if(!$LGList){$LGList=languageType('',3);}
	if($LGList)
	{
		foreach($LGList as $arrkey=>$language)
		{
			//转字符串
			if (is_array($_POST['channel'.$language])){$_POST['channel'.$language]=ToStr($_POST['channel'.$language],':::');}
			if (is_array($_POST['weight_limit_ppt'.$language])){$_POST['weight_limit_ppt'.$language]=ToStr($_POST['weight_limit_ppt'.$language],':::');}
			if (is_array($_POST['content'.$language])){$_POST['content'.$language]=ToStr($_POST['content'.$language],':::');}
			
			//处理
			$save_textarea.="address{$language},weight_limit_ppt{$language},content{$language},";
			
		}
	}
	$save_textarea=DelStr($save_textarea);
	
	
	
	
	
	//多维数组:数组字段里有数组 (需要放在[单个数组]的上面)
	//渠道数量
	for ($i=0; $i<=20; $i++)
	{
		//通用清关公司
		if($_POST['customs'][$i])
		{
			$_POST['customs_DutyFree'].=spr($_POST['customs_DutyFree'.$i][0]).':::';
		}else{
			$_POST['customs_DutyFree'].=':::';
		}

		//如果还有其他清关公司,则加到此处
		if($_POST['customs'][$i]=='gd_mosuda')
		{
			$_POST['customs_types_limit'].=ToStr($_POST['gd_mosuda_types_limit'.$i],',').':::';
			$_POST['customs_weight_limit'].=spr($_POST['gd_mosuda_weight_limit'.$i][0]).':::';
		}
/*		elseif($_POST['customs'][$i]=='XXXXXXX')
		{
			//如果还有其他清关公司,则加到此处
		}
*/
		else{
			$_POST['customs_types_limit'].=':::';
			$_POST['customs_weight_limit'].=':::';
		}
		
		
		/*不保存小数组*/
		$alone.='gd_mosuda_types_limit'.$i.',';
		$alone.='gd_mosuda_weight_limit'.$i.',';
		$alone.='customs_DutyFree'.$i.',';
	}
	

	//渠道多值字段处理:转字符串
	$arr=ToArr('weight_limit,customs_types_limit,customs_weight_limit,customs_DutyFree,signday,shenfenzheng,JPChannel,customs,baoxian_1,baoxian_2,baoxian_3,baoxian_4,baoxian_5,insuranceFormula,insuranceFormulaType,ON_op_bgfee1,ON_op_bgfee2,ON_op_wpfee1,ON_op_wpfee2,ON_op_ydfee1,ON_op_ydfee2,ON_op_free,ON_op_freearr');
	if($arr)
	{
		foreach($arr as $arrkey=>$value)
		{
			if(substr($joint,-2)==$LT){$joint=substr($joint,0,-2);}//变量名不能带有LT标识
			if(!$_POST[$value]){continue;}
			$_POST[$value]=ToStr($_POST[$value],':::');
		}
	}



	//添加------------------------------------
	if($lx=='add')
	{
		$savelx='add';//调用类型(add,edit,cache)
		$getlx='POST';//获取类型(POST,GET,REQUEST,SQL)
		$alone=$alone.'whid';//不处理的字段
		$digital='country,myorder,checked,area';//数字字段
		$radio='';//单选、复选、空文本、数组字段
		$textarea=$save_textarea;//过滤不安全的HTML代码
		$date='';//日期格式转数字
		$save=XingAoSave($_POST,$getlx,$savelx,$alone,$digital,$radio,$textarea,$html,$date);
		
		$xingao->query("insert into warehouse (".$save['field'].") values(".$save['value'].")");
		SQLError('添加');
		$rc=mysqli_affected_rows($xingao);
		if($rc>0)
		{
			cache_warehouse();//生成缓存
			$token->drop_token('warehouse'); //处理完后删除密钥
			exit("<script>alert('添加成功，需要在会员组开启及配置价格才能正式使用！');location='list.php';</script>");
		}else{
			exit ("<script>alert('{$LG['pptAddFailure']}');goBack();</script>");
		}
	}
	
	//修改------------------------------------
	if($lx=='edit')
	{
		if(!$whid){exit ("<script>alert('whid{$LG['pptError']}');goBack();</script>");}

		//更新
		$savelx='edit';//调用类型(add,edit,cache)
		$getlx='POST';//获取类型(POST,GET,REQUEST,SQL)
		$alone=$alone.'whid';//不处理的字段
		$digital='country,myorder,checked,area';//数字字段
		$radio='';//单选、复选、空文本、数组字段
		$textarea=$save_textarea;//过滤不安全的HTML代码
		$date='';//日期格式转数字
		$save=XingAoSave($_POST,$getlx,$savelx,$alone,$digital,$radio,$textarea,$html,$date);
		$xingao->query("update warehouse set ".$save." where whid='{$whid}'");
		SQLError('修改');		
		$rc=mysqli_affected_rows($xingao);

		$token->drop_token('warehouse'); //处理完后删除密钥
		if($rc>0)
		{
			cache_warehouse();//生成缓存
			//$ts=$LG['pptEditSucceed'];
			exit("<script>location='list.php';</script>");
		}else{
			$ts=$LG['pptEditNo'];
			exit("<script>alert('".$ts."');location='list.php';</script>");
		}
	}
	
	
	
//删除=====================================================
}elseif($lx=='del'){
	
	if(!$whid){exit ("<script>alert('whid{$LG['pptError']}');goBack();</script>");}
	
	$xingao->query("delete from warehouse where whid in ({$whid})");
	$rc=mysqli_affected_rows($xingao);
	
	if($rc>0){
		cache_warehouse();//生成缓存
		exit("<script>alert('共删除{$rc}个仓库！');location='list.php';</script>");
	}else{
		exit("<script>alert('{$LG['pptDelEmpty']}');location='list.php';</script>");
	}
	
}
?>