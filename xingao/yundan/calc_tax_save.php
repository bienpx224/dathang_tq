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


$pervar='yundan_ta';require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');

//获取,处理=====================================================
$ydid=$_POST['ydid'];
$status_up=par($_POST['status_up']);
$kffs=par($_POST['kffs']);
$tax_money=spr($_POST['tax_money'],3);

if(!$ydid){exit ("<script>alert('ydid{$LG['pptError']}');goBack('c');</script>");}
if($tax_money<=0){exit ("<script>alert('总税费错误！');goBack();</script>");}

//查询,验证=====================================================
$rs=FeData('yundan','*',"ydid=$ydid");//查询旧数据
warehouse_per('ts',$rs['warehouse']);//验证可管理的仓库

//保存主表=====================================================
$savelx='edit';//调用类型(add,edit,cache)
$getlx='POST';//获取类型(POST,GET,REQUEST,SQL)
$alone='ydid,prefer,status_up,kffs,gdid';//不处理的字段
$digital='tax_money';//数字字段
$radio='';//单选、复选、空文本、数组字段
$textarea='manage_content';//过滤不安全的HTML代码
$date='';//日期格式转数字
$save=XingAoSave($_POST,$getlx,$savelx,$alone,$digital,$radio,$textarea,$html,$date);
$save.=",memberpay=0";
$xingao->query("update yundan set ".$save." where ydid='{$ydid}'");
SQLError();		
//$rc=mysqli_affected_rows($xingao);//不用
$ts_pay='保存成功';

//费用处理=====================================================
$rs=FeData('yundan','*',"ydid='{$ydid}'");//保存后重新查询,查询新数据
$sending=0;
$field='tax_money';//税费
require($_SERVER['DOCUMENT_ROOT'].'/xingao/yundan/call/calc_payment.php');//输出:$kf
require($_SERVER['DOCUMENT_ROOT'].'/xingao/yundan/call/calc_refund.php');//退费

//添加状态
if($status_up&&spr($rs['status'])!=1&&spr($rs['status'])<14)
{
	if(!$kf){$update_status=14;}else{$update_status=15;}
	yundan_updateStatus($rs,$update_status,0,0);
}

exit("<script>alert('{$ts_pay}');goBack('c');</script>");
?>