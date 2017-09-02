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

$typ=par($_GET['typ']);
$content=striptags($_GET['content']);
$returnform=par($_GET['returnform']);
if(empty($returnform))
{
	exit("<script>alert('没指定返回输入框!');window.close();</script>");
}




if($typ=='1')
{
	//1级
	$letter=Cumulative('auto_classify3_1','d');//获取当前累积数字
	$letter=strtoupper(chr($letter+96));//按数字转成字母并转大写
	$name='TempFlight-'.substr(date('Y'),-2).date('m').date('d').'-'.$letter;//生成完成的批次号码
	if(!have($content,'TempFlight',0)){$content='';}//清空之前非此类内容
}elseif($typ=='2'){
	//2级
	$letter=Cumulative('auto_classify3_2','d');//获取当前累积数字
	$letter=strtoupper(chr($letter+96));//按数字转成字母并转大写
	$name='TempVoyage-'.substr(date('Y'),-2).date('m').date('d').'-'.$letter;//生成完成的批次号码
	if(!have($content,'TempVoyage',0)){$content='';}//清空之前非此类内容
}elseif($typ=='3'){
	//3级
	$letter=Cumulative('auto_classify3_3','d');//获取当前累积数字
	$letter=strtoupper(chr($letter+96));//按数字转成字母并转大写
	$name='TempSmallVoyage-'.substr(date('Y'),-2).date('m').date('d').'-'.$letter;//生成完成的批次号码
	if(!have($content,'TempSmallVoyage',0)){$content='';}//清空之前非此类内容
}elseif($typ=='4'){
	//4级
	$letter=Cumulative('auto_classify3_4','d');//获取当前累积数字
	$letter=strtoupper(chr($letter+96));//按数字转成字母并转大写
	$name='Pallet-'.substr(date('Y'),-2).date('m').date('d').'-'.$letter;//生成完成的批次号码
	if(!have($content,'Pallet',0)){$content='';}//清空之前非此类内容
}

$content.=','.$name;
$content=DelStr($content,',',1);
$content=str_replace(',', '\\n',$content);

?>
<script>
<?=$returnform?>="<?=$content?>";
window.close();
</script>
