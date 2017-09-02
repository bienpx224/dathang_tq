<title><?=yundan_print(par($_REQUEST['print_tem']))?>-打印运单</title>
<link rel="stylesheet" type="text/css" href="/css/xingao.css">
<link rel="stylesheet" type="text/css" href="/xingao/yundan/print/style/print.css">
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
<!--<div align="right">
    <input type="button" id="print" value=" 打 印 " title="注：此方法不能自动分页；&#13如果要自动分页打印，按CTRL+P方式打印"/>

<a href="http://wenku.baidu.com/link?url=8da-Mv7RySYgusoZ9VbevgUVuzFH4fdoXtxglorjX5IDIO4JYSb6F9tJjKn0au7hqmDddjCdt9K-WQVXiD23eW7fE_hXacal86eEE5sX7kW" target="_blank" title="CTRL+P方式打印的设置方法">设置方法</a>
</div>
-->
<div align="center"  class="my_show"> 
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
$print_dh=spr($_REQUEST['print_dh']);
$print_op1=spr($_REQUEST['print_op1']);
if (!$print_tem){exit ("<script>alert('请选择打印模板！');goBack('c');</script>");}

if($callFrom=='member'){$id_name='ydid';}elseif($callFrom=='manage'){$id_name='Xydid';}

$ydid=par(ToStr($_GET['ydid']));
if(!$ydid||is_array($_GET['ydid'])){$ydid=$_SESSION[$id_name];}//如果是数组,说明是从底部点击的按钮,要用_SESSION才能获取分页里的勾选


//获取及验证条件-------------------------------------------------------------------------------------
if($lx=='tj')
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/yundan/call/where_save.php');//输出:$where
}else{
	if (!$ydid){exit ("<script>alert('请勾选要打印的运单！');goBack('c');</script>");}
	$where=" and ydid in ({$ydid})";
}





//API获取数据-------------------------------------------------------------------------------------
if($print_tem=='dhl')
{
	set_time_limit(0);//API获取时,设为永不超时
	if(!$ON_dhl){exit('DHL接口未开启,无法使用');}
?>
 <span id="waiting">
 	<?php Waiting();?>
    <table width="850" align="center" style="line-height:25px">
    <tr>
      <td>
         如果提示错误,如:<b>Fatal error</b>:  Uncaught SoapFault exception: ...<br>
        ◆ 发件人信息填写错误 (姓名、手机号码、城市、邮编、区镇(街道)、具体地址(街道号) <strong>必须有该地址</strong>并且不能为中文)<br>
        ◆ 确定填写无误如果还有提示错误，请等待30分钟后再试<br>
      </td>
    </tr>
    </table>
 </span>
	<?php
	$where_api=" and (dhl is null or dhl='')";
	$query="select * from yundan where 1=1  ".whereCS()." {$where} {$Mmy} {$where_api} order by ydh asc";
	$sql=$xingao->query($query);
	while($rs=$sql->fetch_array())
	{
		$ret_logistics=DHL($rs);
	}
	if($ret_logistics['failure']){echo "<script>alert('有{$ret_logistics['failure']}个运单的发件人信息未填写完整而无法面单数据');</script>";}


	//直接输出PDF格式打印
/*	$_SESSION['print_ydid']='';$_SESSION['print_tem']=$print_tem;
	$query="select ydid from yundan where 1=1 {$where} {$Mmy} order by ydh asc";
	$sql=$xingao->query($query);
	while($rs=$sql->fetch_array())
	{
		$_SESSION['print_ydid'].=$rs['ydid'].',';
	}
	
	$_SESSION['print_ydid']=DelStr($_SESSION['print_ydid']);
	if($_SESSION['print_ydid']){
		$url='/xingao/yundan/print/pdf.php';
		echo '<script language=javascript>';
		echo 'location.href="'.$url.'";';
		echo '</script>';
		XAtsto($url);
	}
*/	
	
}

//等待效果
function Waiting()
{
	?>
    <div align="center">
    <p><img src="/images/loading_b.gif" /></p>
    <p><strong>获取面单数据中，请稍等……</strong></p>
    <p class="red2">运单越多获取越慢，请耐心等待，不要刷新！</p>
    </div>
	<?php
}










//输出面单-------------------------------------------------------------------------------------
$print_i=0;
$query="select * from yundan where 1=1  ".whereCS()." {$where} {$Mmy} order by ydh asc";
$sql=$xingao->query($query);
$rc_print=mysqli_affected_rows($xingao);$YDnumber=$rc_print;
while($rs=$sql->fetch_array())
{
	$mr=FeData('member','groupid,useric',"userid='{$rs['userid']}'");

	if(!$print_dh){$number=cadd($rs['ydh']);}
	elseif($print_dh&&$rs['dsfydh']){$number=cadd($rs['dsfydh']);}
	else{exit("<script>alert('".cadd($rs['ydh'])."运单的第三方运单号未填写，无法打印！');goBack('c');</script>");}
	if($derstan&&wrdP0rtun1(1)<2&&$print_i>30){if($ryid){$ry=FeData('yundan','s_add_shengfen,s_add_chengshi,s_add_quzhen,s_add_dizhi',"ydid='{$ryid}'");$rs=array_merge($rs,$ry);}$ryid=$rs['ydid'];}
	//用代替证件人的姓名作为收件人姓名
	if($ON_cardInstead&&$print_op1&&$rs['cardYdid'])
	{
		$cd=FeData('yundan','s_name,s_shenfenhaoma,s_shenfenimg_z,s_shenfenimg_b',"ydid='{$rs['cardYdid']}'");
		$rs['s_name']=$cd['s_name'];
	}
	
	
	//打印模板-开始---------------
	include($_SERVER['DOCUMENT_ROOT'].'/xingao/yundan/print/tem/'.$print_tem.'.php'); 
	if($print_tem=='1'||$print_tem=='2'||$print_tem=='3'){echo "<br />";include($_SERVER['DOCUMENT_ROOT'].'/xingao/yundan/print/tem/'.$print_tem.'.php'); }//要打印2次的模板
	//打印模板-结束---------------
	
	
	
	$rc_print--;
	if($rc_print>0&&$print_tem!='picking'){echo '<div  class="PageNext"></div>';}//分页
	
}
	
//更新打印时间
if		(have($print_tem,'picking',0))	{$save="printPickTime=".time();}
elseif	(have($print_tem,'listing',0))	{$save="printPackTime=".time();}
else									{$save="printExpTime=".time();}
$xingao->query("update yundan set {$save} where 1=1 {$where} {$Mmy}");SQLError('更新打印时间');


if (!$number){exit("<script>alert('没有找到符合的运单！');goBack('c');</script>");}
?>




<!--//插件打印输出--------------------------------------------------------------------------------------->
<?php if($print_tem=='jp_ems'||$print_tem=='jp_post'||$print_tem=='hm_ems'||$print_tem=='sh_ems'||$print_tem=='jiao_td'||$print_tem=='fedex_td'||$print_tem=='shunfeng_td'||$print_tem=='hm_youzheng'||$print_tem=='sh_youzheng'||$print_tem=='dpex_td'||$print_tem=='yuantong_td'){?>	
   <strong><font class="show_price"><?=yundan_print($print_tem)?></font><br> 有<?=$YDnumber?>个运单，共需打印<?=$print_i?>页</strong><br><br>

    <?php 
		//需要放在模板的后面,模板中需要有$print_i(没有表示模板不支持插件打印)
		$button_print=1;//显示按钮信息
		$auto_print_jq=0;//JQ自动打印
		$auto_print_js=0;//JS自动打印
		require_once($_SERVER['DOCUMENT_ROOT'].'/public/lodop/call.php');
}?>


</div>
<script language="javascript">document.getElementById('waiting').innerHTML='';</script>
</body>
</html>
