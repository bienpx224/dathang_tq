<?php
$Xgroupid=$_SESSION['manage']['groupid'];
$Xuserid=$_SESSION['manage']['userid'];
$Xusername=$_SESSION['manage']['username'];
$Xtruename=$_SESSION['manage']['truename'];
$Xrnd=$_SESSION['manage']['rnd'];
/*
	获取组资料
	$manage_per[$Xgroupid]['groupname'];
*/

//$noper不做任何验证; $pervar验证指定权限
if(!$noper)
{
	if(permissions('admin',0,'manage',1)){$Xadmin=1;}//是否超级管理员
	if(!$Xadmin&&permissions('member_my',0,'manage',1)){$myMember=" and CustomerService='{$Xuserid}'";}
	
	//安全入口验证
	if($_SESSION['entrance']!='xazy')
	{
		echo '<script language=javascript>';
		echo 'alert("来自SE页提示:请从安全入口进入!\\n如果不知道入口,请联系兴奥转运系统项目负责人!");location.href="/xingao/login_save.php?lx=logout";';
		echo '</script>';
		exit;
	}
	
	//后台关闭
	if($off_site_manage&&$off_site_manage!=$Xusername)
	{
		echo '<script language=javascript>';
		echo 'location.href="/xingao/login_save.php?lx=logout";';
		echo '</script>';
		exit();
	}
	
	//权限验证
	if($pervar)
	{
		permissions($pervar,1,'manage','');//验证权限和是否登录(有提示)
		//if(permissions('baoguo_ad','','manage',1)){}//用于导航(无提示)
	}else{
		permissions(0,1,'manage','2');//默认只验证是否登录(有提示)
	}
	
	
	//防止以上函数执行错误而无提示，最后做验证
	if(!$Xuserid){exit('登录超时！');}

}


//固定查询
$Xmy=" and userid='{$Xuserid}' ";

//验证可管理的仓库
if(!$Xwh){$Xwh=warehouse_per('sql');}
if(!$WHPerShow){$WHPerShow=warehouse_per('show');}
?>