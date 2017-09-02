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
header("Content-Type: text/html; charset=utf-8");
require_once($_SERVER['DOCUMENT_ROOT'].'/public/config_top.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/public/function.php');

if(!$off_api){exit('100');}//网站接口已暂停

//获取
$userid=spr($_POST['userid']);
$key=par($_POST['key']);
$wupin=$_POST['wupin'];
$return='|||';

//验证
if(!$userid||!$key){exit('200'.$return);}
$user=FeData('member','userid,username,api,api_key,api_yd_query,api_yd_add',"userid='{$userid}'");
if(!$user['api']){exit('201'.$return);}
if(md5(md5($user['api_key']).$_POST['s_name'].$_POST['s_mobile'])!=$key){exit('200'.$return);}
//if(!$user['api_yd_query']){exit('202'.$return);}
if(!$user['api_yd_add']){exit('202'.$return);}

//必填验证
if(!$_POST['warehouse']||!$_POST['country']||!$_POST['channel']||!$wupin){exit('300'.$return);}
if(!$_POST['s_name']||!$_POST['s_mobile_code']||!$_POST['s_mobile']||!$_POST['s_add_shengfen']||!$_POST['s_add_chengshi']||!$_POST['s_add_quzhen']||!$_POST['s_add_dizhi']){exit('300'.$return);}

if($off_shenfenzheng&&channelPar($_POST['warehouse'],$_POST['channel'],'shenfenzheng'))
{
	if(!$_POST['s_shenfenhaoma']||!$_POST['s_shenfenimg_z']||!$_POST['s_shenfenimg_b']){exit('300'.$return);}
}

if(!channel_name(FeData('member','groupid',"userid='{$userid}'"),$_POST['warehouse'],$_POST['country'],$_POST['channel'])){exit('302'.$return);}

//处理
$_POST['goodsdescribe']=goodsdescribe($wupin);//物品描述
$declarevalue=declarevalue($_POST['declarevalue'],$wupin);//物品价值
$insureamount=insureamount($_POST['warehouse'],$_POST['channel'],$_POST['insureamount'],$declarevalue,$_POST['declarevalue']);//保价
$insurevalue=insurance($_POST['warehouse'],$_POST['channel'],$insureamount,0);//保价费
$_POST['declarevalue']=$declarevalue;
$_POST['insureamount']=$insureamount;
$_POST['insurevalue']=$insurevalue;

//固定保存
$_POST['addSource']=6;
$status=0;
$statustime=time();
$statusauto=0;
if($off_statusauto&&$yd_statusauto){$statusauto=1;}
$addtime=time();

//保存
$savelx='add';//调用类型(add,edit,cache)
$getlx='POST';//获取类型(POST,GET,REQUEST,SQL)
$alone='old_shenfenimg_z,old_shenfenimg_b,s_shenfenimg_z_add,s_shenfenimg_b_add,old_s_shenfenimg_z,old_s_shenfenimg_b,bg_zxyd,
ydid,bg_number,baoguo_hx_fee,address_save_s,address_save_f,fx,fx_number,fx_total,userid,key';//不处理的字段
$digital='addSource,weightEstimate,integral_use,kffs,op_bgfee1,op_bgfee2,op_wpfee1,op_wpfee2,op_ydfee1,op_ydfee2,op_free,op_overweight,country';//数字字段
$radio='';//单选、复选、空文本、数组字段
$textarea='content,goodsdescribe,fx_content';//过滤不安全的HTML代码
$date='';//日期格式转数字
$save=XingAoSave($_POST,$getlx,$savelx,$alone,$digital,$radio,$textarea,$html,$date);

$save['field'].=',ydh,status,statustime,statusauto';
$save['value'].=",'{$newydh}','{$status}','{$statustime}','{$statusauto}'";

$xingao->query("insert into yundan (".$save['field'].",addtime,userid,username) values(".$save['value'].",'{$addtime}','{$userid}','{$user[username]}')");

//SQLError('添加');
$error=mysqli_error($xingao);if($error){exit('301'.$return);}
$rc=mysqli_affected_rows($xingao);
$ydid=mysqli_insert_id($xingao);

if($rc>0)
{
	wupin_save_import('yundan',$ydid,$wupin);
	if($off_shenfenzheng&&channelPar($_POST['warehouse'],$_POST['channel'],'shenfenzheng'))
	{
		$Muserid=$userid;
		if($_POST['s_shenfenimg_z']){$img_z=DoTranUrl($_POST['s_shenfenimg_z']);}//保存图片
		if($_POST['s_shenfenimg_b']){$img_b=DoTranUrl($_POST['s_shenfenimg_b']);}//保存图片
	}

	$newydh=OrderNo('yundan',$_POST['warehouse']);//生成运单号

	$xingao->query("update yundan set s_shenfenimg_z='{$img_z[savefile]}',s_shenfenimg_b='{$img_b[savefile]}',ydh='{$newydh}' where ydid='{$ydid}'");//保存成功后再生成单号以免浪费
	
	exit('1'.'|||'.$newydh);//成功返回结果
}


exit('500'.$return);//无成功返回结果