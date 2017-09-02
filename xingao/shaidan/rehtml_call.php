<?php
//生成静态页
$query="select * from shaidan where sdid in ({$sdid})";
$sql=$xingao->query($query);
while($rs=$sql->fetch_array())
{
	$fr=mysqli_fetch_array($xingao->query("select classid,path,classtype,listt,contentt from class where classid='{$rs[classid]}' "));
	
	//生成静态列表页
	CallREhtml($fr['classtype'],$fr['listt'],$fr['contentt'],$fr['classid'],0,$time='',$fr['path'],'list',$listnum='3');//$listnum只生成前面多少页
	
	//生成静态内容页
	$path=add(CallREhtml($fr['classtype'],$listt='',$fr['contentt'],$fr['classid'],$rs['sdid'],$rs['addtime'],$fr['path']));
	
  //保存生成路径
  $xingao->query("update shaidan set path='{$path}' where sdid='{$rs[sdid]}'"); 
}
?>