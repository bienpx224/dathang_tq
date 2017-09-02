<?php


//搜索
$where="1=1";
$where.=" and fromtable<>'yundan'";
$so=(int)$_GET['so'];
$select=" * ";

if($so)
{
	//$op1=par($_GET['op1']);
	$username=par($_GET['username']);
	$tally=par($_GET['tally']);
	$stime=par($_GET['stime']);
	$etime=par($_GET['etime']);

	
	if($op2){$select=" userid,username ";$group=" group by userid";}
	
	if(CheckEmpty($tally)){if($tally==-1){$where.=" and tally>'0'";}else{$where.=" and tally='{$tally}'";}}
	if($username){$where.=" and (userid='".CheckNumber($username)."' or username='{$username}')";}
	if($stime){$where.=" and addtime>='".strtotime($stime.' 00:00:00')."'";}
	if($etime){$where.=" and addtime<='".strtotime($etime.' 23:59:59')."'";}
	//无运单账单
/*	if($op1)
	{
		$where_yundan="1=1";
		if($stime){$where_yundan.=" and chukutime>='".strtotime($stime.' 00:00:00')."'";}
		if($etime){$where_yundan.=" and chukutime<='".strtotime($etime.' 23:59:59')."'";}
		$where.=" and userid not in (select distinct userid from yundan where {$where}) ";
	}
*/	
	
	$search.="&so={$so}&key={$key}&classid={$classid}&lotno={$lotno}&username={$username}&member_type={$member_type}&tally={$tally}&op1={$op1}&op2={$op2}&stime={$stime}&etime={$etime}";
}

$fixed_order=' username asc';//固定优先排序,必须username或userid
$order=' order by '.$fixed_order.',id asc';//默认排序
?>