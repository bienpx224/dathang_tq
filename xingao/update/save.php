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
$pervar='qita';require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');
//获取,处理=====================================================
set_time_limit(0);//批量处理时,速度慢,设为永不超时
//ob_end_clean();ob_implicit_flush(1);//实时输出 //有内容输出时,无法自动关闭

$lx=par($_GET['lx']);
$headtitle=par($_GET['headtitle']);$alonepage=1;//单页形式
$classid=par($_GET['classid']);
$id=par($_GET['id']);
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/head.php');
?>
<link href="/css/xingao.css" rel="stylesheet" type="text/css" />
<div align="center">
<p><img src="/images/loading_b.gif" /></p>
<p><strong>正在操作中，请稍等……</strong></p>
<p class="red2">不要刷新或反复点击，更新完成会自动关闭</p>
<?php
//基本更新---------------------------------------------------------------------------------------------------------
if($lx=='jiben'){
	//删除上传目录里空目录
	DelDirEmpty('/upxingao/');
	
	//生成缓存文件
	cache_manage_group();
	cache_member_group();
	cache_warehouse();
	
	//左边菜单缓存
	$_SESSION['cache_manage']='';
	$_SESSION['cache_member']='';
	
	//生成配置文件
	$cf=FeData('config','id,value3',"name='config'");//注意：不能只查询一个字段，因为FeData输出会是return cadd($cf[0]);
	file_put_contents($_SERVER['DOCUMENT_ROOT'].'/cache/config.php',$cf['value3']);//不能加caddonly($cf['value2'])
	
	//删除15天外的临时支付记录
	$start =strtotime('-10 days');
	$xingao->query("delete from paytemp where addtime<".$start."");
	
}
//更新信息列表-----------------------------------------------------------------------------------------------------
elseif($lx=='list'){

	$i=0;
	$upsl=30;//每次更新数量
	echo '<p>更新到 <strong class="show_price">'.spr($classid).'</strong> 以上ClassID,每次更新'.$upsl.'个栏目</p>';
	
	$query="select classtype,listt,contentt,classid,path from class where classid>'{$classid}' order by classid asc limit {$upsl}";
	$sql=$xingao->query($query);
	while($rs=$sql->fetch_array())
	{
		//生成静态页
		CallREhtml($rs['classtype'],$rs['listt'],$rs['contentt'],$rs['classid'],0,$time,$rs['path'],'list');
		
		$i+=1;
		if($i>=$upsl)
		{
			echo '<script language=javascript>setTimeout(function(){';
			echo 'location.href="?lx='.$lx.'&headtitle='.$headtitle.'&classid='.$rs['classid'].'";';
			echo '},3000)</script>';
			exit ();
		}
	}
	
}
//更新信息内容页-------------------------------------------------------------------------------------------
elseif($lx=='content'){

	$i=0;
	$upsl=10;//每次更新数量
	echo '<p>更新到 <strong class="show_price">'.spr($id).'</strong> 以上ID,每次更新'.$upsl.'条信息</p>';
	
	$query="select id,classid,addtime,contentt from article where id>'{$id}' order by id asc limit {$upsl}";
	$sql=$xingao->query($query);
	while($rs=$sql->fetch_array())
	{
		//生成静态页
		$rid=$rs['id'];
		$classid=$rs['classid'];
		$time=$rs['addtime'];
		$contentt=$rs['contentt'];
		
		if(!$fr_classtype||$old_classid!=$classid||!$contentt)
		{
			$fr=mysqli_fetch_array($xingao->query("select path,classtype,contentt from class where classid='{$classid}'"));
			$fr_classtype=$fr['classtype'];
			if(!$contentt){$contentt=$fr['contentt'];}
			$fr_path=$fr['path'];
			$old_classid=$classid;
		}
		
		$path=add(CallREhtml($fr_classtype,$listt='',$contentt,$classid,$rid,$time,$fr_path));
		
		//保存生成路径
		$xingao->query("update article set path='{$path}' where id='{$rid}'"); 
		SQLError();

		$i+=1;
		if($i>=$upsl)
		{
			echo '<script language=javascript>setTimeout(function(){';
			echo 'location.href="?lx='.$lx.'&headtitle='.$headtitle.'&id='.$rid.'";';
			echo '},2000)</script>';
			exit ();
		}
	}

}

//更新晒单内容页--------------------------------------------------------------------------------------------------
elseif($lx=='content_shaidan'&&$off_shaidan){
	$i=0;
	$upsl=10;//每次更新数量
	echo '<p>更新到 <strong class="show_price">'.spr($id).'</strong> 以上ID,每次更新'.$upsl.'条信息</p>';
	
	$query="select sdid,addtime,classid from shaidan where sdid>'{$id}' order by sdid asc limit {$upsl}";
	$sql=$xingao->query($query);
	while($rs=$sql->fetch_array())
	{
		//生成静态页
		$rid=$rs['sdid'];
		$classid=$rs['classid'];
		$time=$rs['addtime'];
		
		
		if(!$fr_classtype||$old_classid!=$classid)
		{
			$fr=mysqli_fetch_array($xingao->query("select path,classtype,contentt from class where classid='{$classid}'"));
			$fr_classtype=$fr['classtype'];
			$fr_path=$fr['path'];
			$fr_contentt=$fr['contentt'];
			$old_classid=$classid;
		}
		
		$path=add(CallREhtml($fr_classtype,$listt='',$fr_contentt,$classid,$rid,$time,$fr_path));
		
		//保存生成路径
		$xingao->query("update shaidan set path='{$path}' where sdid='{$rid}'"); 
		SQLError();

		$i+=1;
		if($i>=$upsl)
		{
			echo '<script language=javascript>setTimeout(function(){';
			echo 'location.href="?lx='.$lx.'&headtitle='.$headtitle.'&id='.$rid.'";';
			echo '},2000)</script>';
			exit ();
		}
	}

}

//更新首页及网站地图
CallREhtml('0',$listt,$contentt,$classid,0,$time,'/','list');

//更新完成
exit ("<script language=JavaScript>setTimeout(function(){goBack('c');},2000)</script>");//alert('".$headtitle." 完成！');
?>

</div>