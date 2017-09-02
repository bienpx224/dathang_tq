<!--底部栏目导航 (指定栏目ID)-->

<li>
  <h3><a href="<?php $xacd=ClassData($cid=1);echo pathLT($xacd['path']);?>"><?=cadd($xacd['name'])?></a></h3>
  <span>
  <?=nav_small_foot($cid);?>
  </span>
</li>

<li>
  <h3><a href="<?php $xacd=ClassData($cid=22);echo pathLT($xacd['path']);?>"><?=cadd($xacd['name'])?></a></h3>
  <span>
  <?=nav_small_foot($cid);?>
  </span>
</li>

<?php 
//子栏目
function nav_small_foot($classid)
{	
	$limit=3;//显示条数
	
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	$small='';
	$querysmall="select classid,bclassid,name{$LT},path,url{$LT},classtype from class where bclassid='{$classid}' and  checked=1 order by myorder desc,classid desc limit {$limit}";
	$sqlsmall=$xingao->query($querysmall);
	while($rssmall=$sqlsmall->fetch_array())
	{
		if($rssmall['url'.$LT])
		{
			$url=cadd($rssmall['url'.$LT]);
			if(stristr($url,'http://')||stristr($url,'https://')){$target='_blank';}else{$target='';}
		}else{
			if($rssmall['classtype']==3)
			{
				$url='/mall/list.php?classid='.$rssmall['classid'];$target='';
			}else{
				$url=pathLT($rssmall['path']);$target='';
			}
		}
		
		
		
		//二级菜单样式设置-开始------------------------------------------------------------------------
		$small.='<a href="'.$url.'" target="'.$target.'">'.cadd($rssmall['name'.$LT]).'</a>';
		//二级菜单样式设置-结束------------------------------------------------------------------------
		
		
		
	}
	return $small;
}
?>
