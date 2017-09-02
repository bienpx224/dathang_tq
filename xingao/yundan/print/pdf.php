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
require_once($_SERVER['DOCUMENT_ROOT'].'/public/function.php');

if(!$_SESSION['print_ydid']){exit("<script>alert('运单ID错误！');goBack('c');</script>");}

//等待解决问题:只输出一个

header('Content-Type: application/pdf');

//输出面单---------------------------------
$query="select dhl from yundan where ydid in ({$_SESSION['print_ydid']}) order by ydh asc";
$sql=$xingao->query($query);
$rc_print=mysqli_affected_rows($xingao);
while($rs=$sql->fetch_array())
{
	//直接输出显示
	$dhl=GetJson($rs['dhl']);
	echo base64_decode($dhl['label']);//解编码
}
?>
