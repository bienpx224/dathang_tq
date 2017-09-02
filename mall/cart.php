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

//获取上一页以便登录后自动返回商品内容页
if(!$_SESSION['member']['userid'])
{
	$prevurl = isset($_SERVER['HTTP_REFERER']) ? trim($_SERVER['HTTP_REFERER']) : '';
	$_SESSION['member']['prevurl']=$prevurl;
}

require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/session.php');

$headtitle=$LG['front.134'];
require_once($_SERVER['DOCUMENT_ROOT'].'/template/incluce/header.php');//放查询的后面

if(!$off_mall)
{
	exit ("<script>alert('{$LG['front.136']}');goBack('uc');</script>");
}

//获取,初步验证---------------------------------------------------------------------------------------------------------
$lx=par($_POST['lx']);//1立即购买;0放购物车
$mlid=(int)$_POST['mlid'];
$warehouse=par($_POST['warehouse']);
$package=par($_POST['package']);
$size=par($_POST['size']);
$color=par($_POST['color']);
$number=spr($_POST['number']);//数字字段只能0不能空,所以用spr
$integral_use=spr($_POST['integral_use']);//数字字段只能0不能空,所以用spr
$content=html($_POST['content']);

if($lx){$tslx="goBack('c');";}else{$tslx="goBack('c');";}//全改成一样,已用不到

//验证,查询
if(!$mlid){exit ("<script>alert('mlid{$LG['pptError']}');{$tslx}</script>");}
if(!$warehouse){exit ("<script>alert('{$LG['yundan.save_2']}');{$tslx}</script>");}
if(!$number){exit ("<script>alert('{$LG['front.138']}');{$tslx}</script>");}


//读取验证---------------------------------------------------------------------------------------------------------
$rs=FeData('mall','*',"mlid='{$mlid}'");

if(!$rs['checked']){exit(XAts('mall_checked'));}

if(!$rs['number']){exit ("<script>alert('{$LG['front.139']}');{$tslx}</script>");}
if($number>$rs['number']){exit ("<script>alert('{$LG['front.140']}');location='".$_SERVER['HTTP_REFERER']."';</script>");}


//限量出售
if($rs['number_limit']>0)
{
	$num=mysqli_num_rows($xingao->query("select mlid from mall_order where mlid='{$rs[mlid]}' and status<>'3' {$Mmy}"));
	if($num)
	{
		exit ("<script>alert('{$LG['front.141']}');{$tslx}</script>");
	}
	
	if($number>$rs['number_limit']){exit ("<script>alert('{$LG['front.142']}');{$tslx}</script>");}
}

//保存:读取商品表以复制到订单表---------------------------------------------------------------------------------------
$query_mall="select mlid,brand,weight,coding,price,price_market,price_other,price_otherwhy,category,goods,spec,unit,integral_to from mall where mlid='{$mlid}'";
$sql_mall=$xingao->query($query_mall);
$ml=mysqli_fetch_array($sql_mall);

$ml['title']=$rs['title'.$LT];
$ml['url']=$rs['url'.$LT];

$savelx='add';//调用类型(add,edit,cache)
$getlx='SQL';//获取类型(POST,GET,REQUEST,SQL)
$alone="";//不处理的字段
$digital='';//数字字段
$radio='';//单选、复选、空文本、数组字段
$textarea='';//过滤不安全的HTML代码
$date='';//日期格式转数字
$save=XingAoSave($ml,$getlx,$savelx,$alone,$digital,$radio,$textarea,$html,$date);

//if(!$rs['price']&&!$rs['price_other']){$pay=1;}else{$pay=0;}//是否已付款(免费则为已付款)

if($rs['titleimg'.$LT])//复制商品标题图片
{
	$titleimg='/upxingao/'.DateYmd(time(),2).'/'.newFilename($rs['titleimg'.$LT]);
	CopyFile($rs['titleimg'.$LT],$titleimg);
}

$save['field'].=",warehouse,package,size,color,number,integral_use,content,titleimg,addtime,userid,username";
$save['value'].=",'{$warehouse}','{$package}','{$size}','{$color}','{$number}','{$integral_use}','{$content}','{$titleimg}','".time()."','{$Muserid}','{$Musername}'";

$xingao->query("insert into mall_order (".$save['field'].") values(".$save['value'].")");
SQLError('添加购物车');
$rc=mysqli_affected_rows($xingao);
$odid=mysqli_insert_id($xingao);

if($rc>0)
{
	$rs['unit']=classify($rs['unit'],2);
	//更新库存量
	$xingao->query("update mall set number=number-{$number} where mlid='{$mlid}'");
	SQLError('更新库存量');
	if($mall_order_time>0){	$time_ts=LGtag($LG['front.145'],'<tag1>=='.$mall_order_time);}
	if($lx)
	{	
		//立即购买-转向到支付页
		$_SESSION['autoClick']=1;
		$url='/xamember/mall_order/list.php?pay=0&autoClick='.$odid;
		exit("<script>location.href='$url';</script>");
	}else{
		//放购物车
		exit("<script>alert('{$LG['front.134']}{$number}{$rs[unit]}\\n{$time_ts}');{$tslx}</script>");
	}

	
}else{
	exit ("<script>alert('{$LG['front.144']}');{$tslx}</script>");
}
?>
