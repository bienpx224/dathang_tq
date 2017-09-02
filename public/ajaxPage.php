<?php
//分页设置部分
if(!$line){$line=20;}//查询条数
if(!$page_line){$page_line=10;}//显示按钮数量
$start=0;

//分页处理部分
$page=RepPIntvar($_POST['page']);
$offset=$page*$line;//总偏移量

$num=mysqli_num_rows($xingao->query($query));//要放$query的后面

if($line!=-1)//-1则不分页
{
	$listpage=ajaxPage($num,$line,$page_line,$start,$page,$search,$pagelx,$ajaxName);
	$query.=" limit $offset,$line";
}


//输出
$sql=$xingao->query($query);
?>