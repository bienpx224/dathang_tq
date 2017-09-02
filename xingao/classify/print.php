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
$pervar='classify';require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');
?>

<script language="javascript" src="/js/jquery.jqprint.js"></script>
<script type="text/javascript"> 
$(function(){ 
$("#print").click(function(){ 
$(".my_show").jqprint(); 
}) 
}); 
</script> 

<script language="JavaScript">   
var hkey_root,hkey_path,hkey_key
hkey_root="HKEY_CURRENT_USER"
hkey_path=" Explorer\\PageSetup\\"
//设置网页打印的页眉页脚为空 
try{
var RegWsh = new ActiveXObject("WScript.Shell")
hkey_key="header" 
RegWsh.RegWrite(hkey_root+hkey_path+hkey_key,"")
hkey_key="footer"
RegWsh.RegWrite(hkey_root+hkey_path+hkey_key,"")
}catch(e){}
</script>
   
<object id="WebBrowser" classid=CLSID:8856F961-340A-11D0-A96B-00C04FD705A2 height="0" width="0"></object> 
<style media="print" type="text/css"> 
.Noprint{display:none;} 
.PageNext{page-break-after: always;} 
</style>

<div align="center"  class="my_show"> 
<?php 
//获取,处理=====================================================
$classid=$_REQUEST['classid'];
$classid=ToStr($classid);
$classid=par($classid);
if(!$classid){exit ("<script>alert('请选择分类！');goBack('c');</script>");}

//输出-------------------------------------------------------------------------------------
$query="select name{$LT} from classify where classid in ({$classid}) order by name asc";
$sql=$xingao->query($query);
$rc_print=mysqli_affected_rows($xingao);
while($rs=$sql->fetch_array())
{
	?>
    <img src="/public/barcode/?number=<?=cadd($rs['name'.$LT])?>" />
	<?php
	$rc_print--;
	if($rc_print>0){echo '<div  class="PageNext"></div>';}//分页
}//while($rs=$sql->fetch_array())
?>


</div>
</body>
</html>
