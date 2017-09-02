<!--导航开始-->
<div class="index_nav">
	<ul>
		<?php nav($classid);?>
	</ul>
</div>
<!--导航结束-->

<?php 
//前台主导航
function nav($classid=0)
{   
	//此页尽量少用global，很多冲突失效
	$acno='hover';
	$acoff='';
	
	global $xingao,$LG,$index,$member,$off_member_nav,$off_mall;
	$rootclassid=RootClassID($classid);
	
	//首页链接
	$ac=$acoff;	if ($index){$ac=$acno;}
	echo '  
        <li><a href="'.pathLT('/m/html/').'" class="'.$ac.'">'.$LG['name.nav_0'].'</a></li>
	';
	
	//栏目链接
	$query="select classid,bclassid,name{$LT},path,url{$LT},classtype from class where bclassid='0' and  checked=1 order by myorder desc,classid desc";
	$sql=$xingao->query($query);
	while($rs=$sql->fetch_array())
	{
		$url='';
		if($rs['url'.$LT])
		{
			$url=cadd($rs['url'.$LT]);
			if(stristr($url,'http://')||stristr($url,'https://')){$target='_blank';}else{$target='';}
		}else{
			if($rs['classtype']==3)
			{
				if($off_mall){$url='/mall/list.php?classid='.$rs['classid'];$target='';}
			}else{
				$url=pathLT($rs['path']);$target='';
			}
		}
		
		if($url)
		{
			$ac=$acoff;	if ($rootclassid==$rs['classid']){$ac=$acno;}
			echo '       
			 <li><a href="'.$url.'" target="'.$target.'"  class="'.$ac.'">'.cadd($rs['name'.$LT]).'</a></li>
			';
		}
	}
}
?>