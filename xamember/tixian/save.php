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
$pervar='off_tixian';//权限验证
require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/session.php');


//获取,处理=====================================================
$lx=par($_REQUEST['lx']);
$txid=par(ToStr($_REQUEST['txid']));
$tokenkey=par($_POST['tokenkey']);
$tixianpassword=par($_POST['tixianpassword']);
$tixian_zhid=par($_POST['tixian_zhid']);
$money=spr($_POST['money']);
$tixian_xiao=$member_per[$Mgroupid]['tixian_xiao'];

//添加,修改=====================================================
if($lx=='add'||$lx=='edit')
{
	//通用验证------------------------------------
	$token=new Form_token_Core();
	$token->is_token("tixian",$tokenkey); //验证令牌密钥
	
	if(!$_POST['tixian_zhid']||!$_POST['tixianpassword']||!$money){exit ("<script>alert('{$LG['tixian.save_1']}');goBack();</script>");}

	if($money<$tixian_xiao){exit ("<script>alert('".$LG['tixian.save_2'].$tixian_xiao.$Mcurrency."');goBack();</script>");}
	
	//查询验证
	$fr=mysqli_fetch_array($xingao->query("select money,txrnd,tixianpassword from member where 1=1 {$Mmy}"));
	$tixianpassword=md5($fr['txrnd'].md5($tixianpassword));
	if($fr['tixianpassword']!=$tixianpassword){exit( "<script>alert('{$LG['tixian.save_3']}');goBack();</script>");}
	if($fr['money']<$money){exit( "<script>alert('".$LG['tixian.save_4'].$fr['money'].$Mcurrency."');goBack();</script>");}
	
	//添加------------------------------------
	if($lx=='add')
	{
		//读取提现账号
		$zhr=mysqli_fetch_array($xingao->query("select * from tixian_zh where checked='1' and txzhid='{$tixian_zhid}' {$Mmy}"));
		$_POST['bank']=$zhr['bank'];
		$_POST['name']=$zhr['name'];
		$_POST['account']=$zhr['account'];
		$_POST['address']=$zhr['address'];
		$_POST['currency']=$Mcurrency;		
		
		//指定状态
		$_POST['status']=1;		
		$addtime=time();
		
		$savelx='add';//调用类型(add,edit,cache)
		$getlx='POST';//获取类型(POST,GET,REQUEST,SQL)
		$alone='txid,tixian_zhid,tixianpassword';//不处理的字段
		$digital='';//数字字段
		$radio='';//单选、复选、空文本、数组字段
		$textarea='content';//过滤不安全的HTML代码
		$date='';//日期格式转数字
		$save=XingAoSave($_POST,$getlx,$savelx,$alone,$digital,$radio,$textarea,$html,$date);
		
		$xingao->query("insert into tixian (".$save['field'].",addtime,userid,username) values(".$save['value'].",'{$addtime}','{$Muserid}','{$Musername}')");
		SQLError('添加');
		$rc=mysqli_affected_rows($xingao);
		if($rc>0)
		{
			//冻结会员金额
			$xingao->query("update member set money=money-$money,money_lock=money_lock+$money where 1=1 {$Mmy}");

			$token->drop_token("tixian"); //处理完后删除密钥
			exit("<script>alert('{$LG['pptAddSucceed']}');location='list.php';</script>");
		}else{
			exit ("<script>alert('{$LG['pptAddFailure']}');goBack();</script>");
		}
		
	}
	
//删除=====================================================
}elseif($lx=='del'){
	
	if(!$txid){exit ("<script>alert('txid{$LG['pptError']}');goBack();</script>");}
	
	//开启“长期保存记录”时禁止删除的状态
	if($off_delbak)
	{
		$delbak_status="and status<>'2'";
	}

	$xingao->query("delete from tixian where txid in ({$txid}) and status='3' {$delbak_status} {$Mmy}");
	$rc=mysqli_affected_rows($xingao);
	
	if($rc>0){
		exit("<script>alert('{$LG['pptDelSucceed']}{$rc}');location='list.php';</script>");
	}else{
		exit("<script>alert('{$LG['pptDelEmpty']}');location='list.php';</script>");
	}
	
}
?>