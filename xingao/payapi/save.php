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
$lx=par($_POST['lx']);
$payid=par($_POST['payid']);
$tokenkey=par($_POST['tokenkey']);
$payname=par($_POST['payname']);
$currency=par($_POST['currency']);


//添加,修改=====================================================
if($lx=='edit')
{
	//通用验证------------------------------------
	$token=new Form_token_Core();
	$token->is_token("payapi",$tokenkey); //验证令牌密钥

	if(!$payname){exit ("<script>alert('请填写名称！');goBack();</script>");}
	if(!$currency){exit ("<script>alert('请选择币种！');goBack();</script>");}
	if(!$payid){exit ("<script>alert('payid{$LG['pptError']}');goBack();</script>");}
	
	//币种验证
	if($payid!=3&&$payid!=9&&$payid!=8&&$payid!=6&&$currency!='CNY'&&$_POST['checked']==1){exit ("<script>alert('该接口只支持人民币！');goBack();</script>");}
	
	if($payid==3&&$currency!='CNY'&&$_POST['paymethod']!=3&&$_POST['checked']==1){exit ("<script>alert('国内支付宝只支持人民币！');goBack();</script>");}
	
	if($payid==3&&$currency=='CNY'&&$_POST['paymethod']==3&&$_POST['checked']==1){exit ("<script>alert('境外支付宝不支持人民币！');goBack();</script>");}
	
	if($payid==6&&$currency=='CNY'&&$_POST['checked']==1){exit ("<script>alert('不支持人民币！');goBack();</script>");}
	
	//保存
	$savelx='edit';//调用类型(add,edit,cache)
	$getlx='POST';//获取类型(POST,GET,REQUEST,SQL)
	$alone='';//不处理的字段
	$digital='checked,payIncMoney,payMinMoney';//数字字段
	$radio='openApi';//单选、复选、空文本、数组字段
	$textarea='paysay';//过滤不安全的HTML代码
	$date='';//日期格式转数字
	$save=XingAoSave($_POST,$getlx,$savelx,$alone,$digital,$radio,$textarea,$html,$date);

	$xingao->query("update payapi set ".$save." where payid='{$payid}'");
	SQLError();
	$rc=mysqli_affected_rows($xingao);
	
	$token->drop_token("payapi"); //处理完后删除密钥
	$ts=$rc>0?$LG['pptEditSucceed']:$LG['pptEditNo'];
	exit("<script>alert('".$ts."');location='list.php';</script>");
}
?>
