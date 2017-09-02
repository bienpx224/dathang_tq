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
//require_once($_SERVER['DOCUMENT_ROOT'].'/public/html.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/public/function.php');

$lx=par($_GET['lx']);
$field=par($_GET['field']);
$length=par($_GET['length']);
if(!$length){$length=6;}

//生成随机数
if($lx==1){ $random=  make_letter(4,'b').make_NoAndPa($length-4);}//字母+数字
elseif($lx==2){ $random= no_make_password($length);}//数字
elseif($lx==3){ $random= make_password($length);}//大小写字母
elseif($lx==4){ $random= make_letter($length,'b');}//大写字母
elseif($lx==5){ $random= make_letter($length,'s');}//小写字母
?>
<script>
//每个输入框要对应,否则后面不会执行
opener.document.xingao.<?=$field?>.value="<?=$random?>";
window.close();
</script>
