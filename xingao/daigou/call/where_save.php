<?php 
//获取修改条件---------------------------------------
//文本域处理不能加par
$wh_dgdh=$_POST['wh_dgdh'];
$wh_s_dgdh=par($_POST['wh_s_dgdh']);
$wh_b_dgdh=par($_POST['wh_b_dgdh']);
$wh_s_dgid=par($_POST['wh_s_dgid']);
$wh_b_dgid=par($_POST['wh_b_dgid']);
$wh_dgid=par($_POST['wh_dgid']);
$wh_username=$_POST['wh_username'];

$wh_procurementTime_s=par($_POST['wh_procurementTime_s']);
$wh_procurementTime_b=par($_POST['wh_procurementTime_b']);
$wh_inStorageTime_s=par($_POST['wh_inStorageTime_s']);
$wh_inStorageTime_b=par($_POST['wh_inStorageTime_b']);
$wh_addtime_s=par($_POST['wh_addtime_s']);
$wh_addtime_b=par($_POST['wh_addtime_b']);

$wh_addSource=par($_POST['wh_addSource']);
$wh_status=par($_POST['wh_status']);
$wh_memberStatus=par($_POST['wh_memberStatus']);
$wh_warehouse=par($_POST['wh_warehouse']);
$wh_pay=par($_POST['wh_pay']);

$wh_source=par($_POST['wh_source']);
$wh_types=par(ToStr($_POST['wh_types']));
$wh_brand=par(ToStr($_POST['wh_brand']));

//验证修改条件---------------------------------------
$where='';
if(CheckEmpty($wh_s_dgdh)||CheckEmpty($wh_b_dgdh))
{
	if($wh_s_dgdh>$wh_b_dgdh)
	{
		exit ("<script>alert('单号范围要从小到大填写!');goBack('uc');</script>");
	}
	$where.=" and dgdh>='".$wh_s_dgdh."' and dgdh<='".$wh_b_dgdh."' ";//如果单号不是纯数字必须加''
}	

if(CheckEmpty($wh_s_dgid)||CheckEmpty($wh_b_dgid))
{
	if($wh_s_dgid>$wh_b_dgid)
	{
		exit ("<script>alert('ID范围要从小到大填写！');goBack('uc');</script>");
	}
	$where.=" and dgid>='".$wh_s_dgid."' and dgid<='".$wh_b_dgid."' ";
}

if(CheckEmpty($wh_dgid)){$where.=" and dgid in ({$wh_dgid})";}
if(CheckEmpty($wh_dgdh)){$where.=" and dgdh in ('".ToArr($wh_dgdh,1,1,"','")."')";}
if($wh_procurementTime_s){$where.=" and procurementTime>='".strtotime($wh_procurementTime_s." 00:00:00")."'";}
if($wh_procurementTime_b){$where.=" and procurementTime<='".strtotime($wh_procurementTime_b." 23:59:59")."'";}
if($wh_inStorageTime_s){$where.=" and inStorageTime>='".strtotime($wh_inStorageTime_s." 00:00:00")."'";}
if($wh_inStorageTime_b){$where.=" and inStorageTime<='".strtotime($wh_inStorageTime_b." 23:59:59")."'";}
if($wh_addtime_s){$where.=" and addtime>='".strtotime($wh_addtime_s." 00:00:00")."'";}
if($wh_addtime_b){$where.=" and addtime<='".strtotime($wh_addtime_b." 23:59:59")."'";}

if(CheckEmpty($wh_addSource)){$where.=" and addSource='{$wh_addSource}'";}
if(CheckEmpty($wh_status)){$where.=" and status='{$wh_status}'";}
if(CheckEmpty($wh_memberStatus)){$where.=" and dgid in (select dgid from daigou_goods where memberStatus='{$wh_memberStatus}' )";}
if(CheckEmpty($wh_warehouse)){$where.=" and warehouse='{$wh_warehouse}'";}
if($wh_brand){$where.=" and brand in ({$wh_brand})";}
if($wh_types){$where.=" and types in ({$wh_types})";}
if(CheckEmpty($wh_pay)){$where.=" and pay='{$wh_pay}'";}
if(CheckEmpty($wh_source)){$where.=" and source='{$wh_source}'";}
if(CheckEmpty($wh_username)){$where.=" and (username in ('".ToArr($wh_username,1,1,"','")."') or userid in (".ToArr($wh_username,1,1,",").")) ";}

if(!$where)
{
	exit ("<script>alert('请填写/选择操作条件 (至少要有一项)！');goBack('uc');</script>");
}
?>