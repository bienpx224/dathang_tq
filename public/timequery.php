<?php
//按时间快速查询,字段为数字类型
/*
调用：
$timequerycall=1;$timequeryshow=0;
$field="cztime";
include($_SERVER['DOCUMENT_ROOT'].'/public/timequery.php');//按时间快速查询
*/
if($timequerycall==1)
{
	$solx=(int)$_GET['solx'];
	if($solx)
	{
		$time=par($_GET['time']);
		if($time==1){//今天
			$start =strtotime(date('Y-m-d')." 00:00:00");
			$where.=" and ".$field.">=".$start;
		}
		elseif($time==2){//本周
			$date = date("Y-m-d")." 00:00:00";
			$first=1; // 1 表示每周星期一为开始时间，0表示每周日为开始时间
			$w = date("w", strtotime($date));  //获取当前是本周的第几天，周日是 0，周一 到周六是 1 -6 
			$d = $w ? $w - $first : 6;  //如果是周日 -6天 
			$now_start = date("Y-m-d", strtotime("$date -".$d." days"));
			$now =  strtotime($now_start);
			$where.=" and ".$field.">=".$now;
		}
		elseif($time==3){//本月
			$start =strtotime(date('Y-m'.'-1'));
			$where.=" and ".$field.">=".$start;
		}
		elseif($time==12){//今年
			$start =strtotime(date('Y').'-1-1');
			$where.=" and ".$field.">=".$start;
		}
		elseif($time==4){//3个月内
			$start =strtotime('-3 Month');
			$where.=" and ".$field.">=".$start;
		}
		elseif($time==5){//6个月内
			$start =strtotime('-6 Month');
			$where.=" and ".$field.">=".$start;
		}
	
		$search.="&solx={$solx}&time={$time}";
	}
}

?>
<?php
/*
调用：
$timequerycall=0;$timequeryshow=1;
$searchtime="&so={$so}&key={$key}";//可空
include($_SERVER['DOCUMENT_ROOT'].'/public/timequery.php');//按时间快速查询
*/
 if($timequeryshow==1){?>
<div class="navbar-header navbar-brand"><?=$timequeryName?$timequeryName:$LG['timequery.2']//快速查询:?>
        <a href="?<?=$searchtime?>"> <?=$LG['unlimited']//不限?> </a> | 
        <a href="?solx=1&time=1&my=<?=$my?><?=$searchtime?>"  class="<?php if ($solx==1&&$time==1){echo "label label-default";}?>"> <?=$LG['timequery.3']//今天?> </a> | 
        <a href="?solx=1&time=2&my=<?=$my?><?=$searchtime?>"  class="<?php if ($solx==1&&$time==2){echo "label label-default";}?>"> <?=$LG['timequery.4']//本周?> </a> | 
        <a href="?solx=1&time=3&my=<?=$my?><?=$searchtime?>"  class="<?php if ($solx==1&&$time==3){echo "label label-default";}?>"> <?=$LG['timequery.5']//本月?> </a> | 
        <a href="?solx=1&time=12&my=<?=$my?><?=$searchtime?>"  class="<?php if ($solx==1&&$time==12){echo "label label-default";}?>"> <?=$LG['timequery.6']//今年?> </a> | 
		
        <a href="?solx=1&time=4&my=<?=$my?><?=$searchtime?>"  class="<?php if ($solx==1&&$time==4){echo "label label-default";}?>"> <?=$LG['timequery.7']//3个月内?> </a> | 
        <a href="?solx=1&time=5&my=<?=$my?><?=$searchtime?>"  class="<?php if ($solx==1&&$time==5){echo "label label-default";}?>"> <?=$LG['timequery.8']//6个月内?> </a> 
		
		&nbsp;
        <!--最后要加 &nbsp; 不然选中时会换行-->
     </div>
<?php }?>
