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

/*

	$typ='py' 拼音
	$typ='en' 英文
	$typ='pw32' 随机密码，后面数字是要生成的位数
	
	$space=0 无空格
	$space=1 有空格
	
	$case=0 默认
	$case=1 全部小写
	$case=2 全部大写
	$case=3 每个单词首字母大写
	$case=4 第一个单词首字母大写
	
	调用：
	<input type="text" class="form-control input-medium"  name="enname" value="<?=cadd($rs['enname'])?>" style="float:left; margin-right:10px;">
	
	<button type="button" class="btn btn-default" onClick="window.open('/public/AutoInput.php?typ=py&space=1&case=1&content='+document.xingao.truename.value+'&returnform=opener.document.xingao.enname.value','','width=100,height=100');"  style="float:left;">生成拼音 </button>
*/

$typ=par($_GET['typ']);
$space=spr($_GET['space']);
$case=spr($_GET['case']);
$content=striptags($_GET['content']);
$returnform=par($_GET['returnform']);

if(empty($returnform)){exit("<script>alert('{$LG['pptError']}');window.close();</script>");}


if($typ=='py'){
	$content=ReturnPinyinFun($content);//默认有空格
}elseif($typ=='en'){
	$content=FanYi($content,'auto','en');
}elseif(stristr($typ,'pw')){
	$content=make_password(findNum($typ));
}


if(!$space){$content=letter($content);}

if($case==1){$content=strtolower($content);}
elseif($case==2){$content=strtoupper($content);}
elseif($case==3){$content=ucwords($content);}
elseif($case==4){$content=ucfirst($content);}
?>
<script>
<?=$returnform?>="<?=$content?>";
window.close();
</script>
