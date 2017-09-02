<?php 
//获取修改条件---------------------------------------
//文本域处理不能加par
$wh_lotno=$_POST['wh_lotno'];
$wh_ydh=$_POST['wh_ydh'];
$wh_username=$_POST['wh_username'];

$wh_s_ydh=par($_POST['wh_s_ydh']);
$wh_b_ydh=par($_POST['wh_b_ydh']);
$wh_s_ydid=par($_POST['wh_s_ydid']);
$wh_b_ydid=par($_POST['wh_b_ydid']);
$wh_ydid=par($_POST['wh_ydid']);

$wh_chukutime_s=par($_POST['wh_chukutime_s']);
$wh_chukutime_b=par($_POST['wh_chukutime_b']);
$wh_addtime_s=par($_POST['wh_addtime_s']);
$wh_addtime_b=par($_POST['wh_addtime_b']);
$wh_addSource=par($_POST['wh_addSource']);
$wh_status=par($_POST['wh_status']);
$wh_warehouse=par($_POST['wh_warehouse']);
$wh_channel=par(ToStr($_POST['wh_channel']));
$wh_country=par(ToStr($_POST['wh_country']));
$wh_pay=par($_POST['wh_pay']);
$wh_gnkdydh=par($_POST['wh_gnkdydh']);

$classtag='wh_';//标记:同个页面,同个$classtype时,要有不同标记
$classtype=3;//分类类型
$wh_classid=GetEndArr($_POST['classid'.$classtag.$classtype]);
//验证修改条件---------------------------------------
$where='';
if(CheckEmpty($wh_s_ydh)||CheckEmpty($wh_b_ydh))
{
	if($wh_s_ydh>$wh_b_ydh)
	{
		exit ("<script>alert('单号范围要从小到大填写!');goBack('uc');</script>");
	}
	$where.=" and ydh>='".$wh_s_ydh."' and ydh<='".$wh_b_ydh."' ";//如果单号不是纯数字必须加''
}	

if(CheckEmpty($wh_s_ydid)||CheckEmpty($wh_b_ydid))
{
	if($wh_s_ydid>$wh_b_ydid)
	{
		exit ("<script>alert('ID范围要从小到大填写！');goBack('uc');</script>");
	}
	$where.=" and ydid>='".$wh_s_ydid."' and ydid<='".$wh_b_ydid."' ";
}

if(CheckEmpty($wh_lotno)){
	$wh_lotno=ToArr($wh_lotno,1,1,"','");
	$wh_lotno=str_replace('empty','',$wh_lotno);//替换empty
	$where.=" and lotno in ('".$wh_lotno."')";
}
if(CheckEmpty($wh_ydid)){$where.=" and ydid in ({$wh_ydid})";}
if(CheckEmpty($wh_ydh)){$where.=" and ydh in ('".ToArr($wh_ydh,1,1,"','")."')";}
if($wh_chukutime_s){$wh_ydh.=" and chukutime>='".strtotime($wh_chukutime_s." 00:00:00")."'";}
if($wh_chukutime_b){$wh_ydh.=" and chukutime<='".strtotime($wh_chukutime_b." 23:59:59")."'";}
if($wh_addtime_s){$wh_ydh.=" and addtime>='".strtotime($wh_addtime_s." 00:00:00")."'";}
if($wh_addtime_b){$wh_ydh.=" and addtime<='".strtotime($wh_addtime_b." 23:59:59")."'";}

if(CheckEmpty($wh_addSource)){$where.=" and addSource='{$wh_addSource}'";}
if(CheckEmpty($wh_status)){$where.=" and status='{$wh_status}'";}
if(CheckEmpty($wh_warehouse)){$where.=" and warehouse='{$wh_warehouse}'";}
if($wh_country){$where.=" and country in ({$wh_country})";}
if($wh_channel){$where.=" and channel in ({$wh_channel})";}
if(CheckEmpty($wh_pay)){$where.=" and pay='{$wh_pay}'";}
if(CheckEmpty($wh_gnkdydh)){if($wh_gnkdydh){$where.=" and gnkdydh<>''";}else{$where.=" and gnkdydh=''";}}
if(CheckEmpty($wh_username)){$where.=" and (username in ('".ToArr($wh_username,1,1,"','")."') or userid in (".ToArr($wh_username,1,1,",").")) ";}

if(CheckEmpty($wh_classid))
{
	$wh_classid_all=$wh_classid.SmallClassID($wh_classid,'classify');
	$where.=" and classid in ({$wh_classid_all})";
}

if(!$where)
{
	exit ("<script>alert('请填写/选择操作条件 (至少要有一项)！');goBack('uc');</script>");
}
?>