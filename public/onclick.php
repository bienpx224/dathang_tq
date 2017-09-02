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
//require_once($_SERVER['DOCUMENT_ROOT'].'/public/html.php');//JS页不能有这个
require_once($_SERVER['DOCUMENT_ROOT'].'/public/function.php');
$ip=GetIP();
$classid=(int)$_GET['classid'];
$id=(int)$_GET['id'];
$table=par($_GET['table']);
$field=par($_GET['field']);
$lx=par($_GET['lx']);
$num='';

/*
调用:
lx:空时更新并显示;up只更新;show只显示
<script src="/public/onclick.php?table=mall&field=plclick&lx=show&id=<?=$rs['mlid']?>"></script>
*/
if($table)
{
	switch($table)
	{
		case "class":
			$where=" classid='{$classid}' ";
			break;
			
		case "article":
			$where=" id='{$id}' ";
			break;
			
		case "shaidan":
			$where=" sdid='{$id}' ";
			break;
			
		case "mall":
			$where=" mlid='{$id}' ";
			break;
	}
	
	if(!$field){$field='onclick';}
	
	if(!$lx||$lx=='up')
	{
		//更新浏览次数
		if($field=='onclick')
		{
			$xingao->query("update {$table} set {$field}={$field}+1,onclick_ip='{$ip}' where {$where} and  onclick_ip<>'{$ip}'");
		}
		//统计更新评论次数
		elseif($field=='plclick')
		{
			$num=mysqli_num_rows($xingao->query("select * from comments where fromtable='{$table}' and fromid='{$id}' and repcmid='0' and checked='1' "));
			$xingao->query("update {$table} set {$field}={$num} where {$where}");
		}
		
	}
	
	if(!$lx||$lx=='show')
	{
		if(!CheckEmpty($num))
		{
			$num=FeData($table,$field,$where);
		}
		echo 'document.writeln(" '.$num.' ");';
	}
}
?>