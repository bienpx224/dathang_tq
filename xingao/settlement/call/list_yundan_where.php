<?php
$classtag='so';//标记:同个页面,同个$classtype时,要有不同标记
$classtype=3;//分类类型

//搜索
$where="1=1";
$so=(int)$_GET['so'];
$select=" * ";

if($so)
{
	$lotno=add($_GET['lotno']);
	$username=par($_GET['username']);
	$sf_name=par($_GET['sf_name']);
	$tally=par($_GET['tally']);
	$stime=par($_GET['stime']);
	$etime=par($_GET['etime']);
	
	$classid=GetEndArr($_GET['classid'.$classtag.$classtype]);
	if(!CheckEmpty($classid)){$classid=par($_GET['classid']);}
	
	
	if($op2){$select=" userid,username,ydid ";$group=" group by userid";}
	
	if(CheckEmpty($classid)){$where.=" and classid='{$classid}'";}
	if(CheckEmpty($lotno)){$where.=" and lotno in ('".str_replace(",","','",str_replace("，",",",$lotno))."')";}
	if(CheckEmpty($tally)){if($tally==-1){$where.=" and tally>'0'";}else{$where.=" and tally='{$tally}'";}}
	if($username){$where.=" and (userid='".CheckNumber($username)."' or username='{$username}')";}
	if($sf_name){$where.=" and (f_name='{$sf_name}' or s_name='{$sf_name}')";}
	if($stime){$where.=" and chukutime>='".strtotime($stime.' 00:00:00')."'";}
	if($etime){$where.=" and chukutime<='".strtotime($etime.' 23:59:59')."'";}
	if(CheckEmpty($classid))
	{
		$classid_all=$classid.SmallClassID($classid,'classify');
		$where.=" and classid in ({$classid_all})";
	}

	$search.="&so={$so}&key={$key}&lotno={$lotno}&username={$username}&member_type={$member_type}&tally={$tally}&op2={$op2}&stime={$stime}&etime={$etime}&classid={$classid}";
}

$fixed_order=' username asc';//固定优先排序,必须username或userid
$order=' order by '.$fixed_order.',chukutime asc,ydh asc';//默认排序
?>