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
ob_end_clean();ob_implicit_flush(1);//实时输出:要放最前面,因为内容从此输出(之前的内容不输出)
require_once($_SERVER['DOCUMENT_ROOT'].'/public/config_top.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/public/html.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/public/function.php');
?>

<title>Excel打印</title>
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

<div class="my_show"> 
<?php

$lx=par($_POST['lx']);
$print_tem=par($_POST['print_tem']);
$file=par($_POST['file'],'',1);
$tokenkey=par($_POST['tokenkey']);
$number=0;

if ($lx=="tj")
{ 
	//基本验证
	if (!$print_tem){exit("<script>alert('请选择 Excel类型和打印模板！');goBack('c');</script>");}

	$token=new Form_token_Core();
	$token->is_token('printExcel',$tokenkey); //验证令牌密钥

	require_once($_SERVER['DOCUMENT_ROOT'].'/public/PHPExcel/Print_call.php');
	
	//读取部分-开始-------------------------------------------------------------------------------------
	for ($row=2;$row<=$highestRow;$row++) //$row =2; 从第2行读取(第一行是标题)
	{
		$strs=array();
		for ($col = 0;$col < $highestColumnIndex;$col++)
		{
			$strs[$col] =$objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
		}    

		$ok=1;

		//通用验证
		//if($ok && (!par($strs[9])&&!par($strs[11])&&!par($strs[18])) ){$ok=0;}
		
		include($_SERVER['DOCUMENT_ROOT'].'/xingao/yundan/print/tem/'.$print_tem.'.php'); 
		
	}//for ($row=2;$row<=$highestRow;$row++)
	

	//读取部分-结束-------------------------------------------------------------------------------------
}

if(!$number)
{
	exit("<script>alert('没有找到可打印的运单,可能类型选择错误,请修改类型试下！');goBack('c');</script>");
}else{
	DelFile($file);//删除文件
}
?>





</div>
</body>
</html>
