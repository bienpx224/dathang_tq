<?php
//排序处理页
/*
include($_SERVER['DOCUMENT_ROOT'].'/public/orderby.php');//排序处理页

重复点击会自动换排序方式:<a href="?<?=$search?>&orderby=username&orderlx=" class="<?=orac('username')?>">用户</a>
固定排序:<a href="?<?=$search?>&orderby=username&orderlx=desc">用户</a>
*/

$orderby=par($_GET['orderby']);
$orderby_old=par($_GET['orderby_old']);
$orderlx=par($_GET['orderlx']);
$orderlx_old=par($_GET['orderlx_old']);
$page=par($_GET['page']);
$page_old=par($_GET['page_old']);
if($orderby)
{
	if(!$orderlx && $page_old==$page)
	{
		$desc='desc';//默认
		if($orderby==$orderby_old)
		{
			if($orderlx_old=='desc')
			{
				$desc='asc';
			}
		}
	}else{
		$desc=$orderlx;
	}
	
	$order_field=$orderby;
	
	//特殊排序
	if(stristr($orderby,'special_'))
	{
		$order_field=str_ireplace('special_','',$orderby);
		$order_field="right({$order_field},1)";
	}

	if($fixed_order){$fixed_order.=',';}
	$order=' order by '.$fixed_order.$order_field.' '.$desc;
	$search.="&page_old={$page}&orderby_old={$orderby}&orderlx_old={$desc}&orderby={$orderby}&orderlx={$orderlx}";
}
?>