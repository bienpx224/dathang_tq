<?php 
/*
	调用
	$bgid=$bgid;
	$print_tem=par($_POST['print_tem']);
	require($_SERVER["DOCUMENT_ROOT"]."/xingao/baoguo/call/inStorage_print.php");//自动返回JS变量printOK
*/

//打印-开始
if($print_tem)
{
	//直接打印(插件)--------------------------------------------------
	$query="select * from baoguo where bgid='{$bgid}' order by bgydh asc";
	$sql=$xingao->query($query);
	while($rs=$sql->fetch_array())
	{
		$number=cadd($rs['bgydh']);
		include($_SERVER['DOCUMENT_ROOT'].'/xingao/baoguo/print/tem/'.$print_tem.'.php'); 
	}

	if($print_i)
	{
		//需要放在模板的后面,模板中需要有$print_i(没有表示模板不支持插件打印)
		$button_print=0;//显示按钮信息
		$auto_print=1;//自动打印
		require_once($_SERVER['DOCUMENT_ROOT'].'/public/lodop/call.php');//自动返回JS变量printOK
	}
	
	
	//用浏览器打印--------------------------------------------------
	if(!$print_i)
	{
		$url="/xingao/baoguo/print.php?print_tem={$print_tem}&lx=pr&bgid={$bgid}";
		echo '<script language=javascript>';
		echo 'printOK=1;window.open("'.$url.'");';//返回JS变量printOK
		echo '</script>';
	}
}else{
	echo '<script>printOK=1;</script>';//不用打印时,返回JS变量printOK,以便可关闭页面
}
//打印-结束
?>