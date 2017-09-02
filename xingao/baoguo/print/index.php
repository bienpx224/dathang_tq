<title><?=baoguo_print(par($_REQUEST['print_tem']))?>-打印</title>
<!--分页打印后，最后总是打印一张空白页，可能是浏览器问题未找到原因-->
<link rel="stylesheet" type="text/css" href="/css/xingao.css">
<link rel="stylesheet" type="text/css" href="/xingao/baoguo/print/style/print.css">
<script src="/bootstrap/plugins/jquery-1.10.2.min.js" type="text/javascript"></script> 
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
</head>
<body>


<div class="my_show" align="center"> 
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
//获取,处理=====================================================
$print_tem=par($_REQUEST['print_tem']);
$warehouse=par($_POST['warehouse']);

if (!$print_tem){exit ("<script>alert('请选择打印模板！');goBack('c');</script>");}

if($callFrom=='member')
{
	$id_name='bgid';
}else{
	$id_name='Xbgid';
}

$bgid=par(ToStr($_REQUEST['bgid']));
if(!$bgid||is_array($_REQUEST['bgid'])){$bgid=$_SESSION[$id_name];}//如果是数组,说明是从底部点击的按钮,要用_SESSION才能获取分页里的勾选





//获取及验证条件---------------------------------
$where='';
if($lx=='tj')
{
	//输出:$where
	if($warehouse){	$where.=" and warehouse='{$warehouse}' ";}
}
else
{
	if (!$bgid){exit ("<script>alert('请勾选要打印的包裹！');goBack('c');</script>");}
	$where.=" and bgid in ({$bgid})";
}

//打印操作清单
if($print_tem=='tem_listing'){$where.=baoguo_fahuo(3);}

if (!$where){exit ("<script>alert('请选择条件！');goBack('c');</script>");}


$query="select * from baoguo where 1=1 {$where} {$Xwh} order by bgydh asc";
$sql=$xingao->query($query);$rc_baoguo=mysqli_affected_rows($xingao);
$rc_print=mysqli_affected_rows($xingao);
while($rs=$sql->fetch_array())
{
	//打印模板-开始--------------------------------------------------------------------------------
	$number=cadd($rs['bgydh']);
	include($_SERVER['DOCUMENT_ROOT'].'/xingao/baoguo/print/tem/'.$print_tem.'.php'); 
	//打印模板-结束--------------------------------------------------------------------------------
	
	$rc_print--;
	if($print_tem!='tem_listing'&&$rc_print>0){echo '<div  class="PageNext"></div>';}//分页
}

if (!$rc_baoguo){exit("<script>alert('没有找到符合的包裹！');goBack('c');</script>");}
?>




<!--//插件打印输出--------------------------------------------------------------------------------------->
<?php if($print_i){?>	
   <strong><font class="show_price"><?=baoguo_print($print_tem)?></font><br> 共需打印<?=$print_i?>页</strong><br><br>
    <?php 
		//需要放在模板的后面,模板中需要有$print_i(没有表示模板不支持插件打印)
		$button_print=1;//显示按钮信息
		$auto_print_jq=0;//JQ自动打印
		$auto_print_js=0;//JS自动打印
		require_once($_SERVER['DOCUMENT_ROOT'].'/public/lodop/call.php');
}?>


</div>
</body>
</html>
