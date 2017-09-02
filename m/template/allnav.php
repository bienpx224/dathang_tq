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
//http://zy/m/template/allnav.php

//获取,处理

$lx=par($_GET['lx']);

$headtitle='网站导航';
require_once($_SERVER['DOCUMENT_ROOT'].'/m/template/incluce/header.php');//放查询的后面

//验证,查询
if(!$_SESSION['manage']['userid']&&$lx!='html'){XAts('temp');}
$lanmu=1;require_once($_SERVER['DOCUMENT_ROOT'].'/m/template/incluce/top.php');//放查询的后面
?>
<!--内容开始-->
<div class="navi_main">
<table width="100%">
	<tbody>
	<?php allnav($classid);?>
	</tbody>
</table>
</div>

<?php 
//前台主导航
function allnav($classid=0)
{   
	//此页尽量少用global，很多冲突失效
	$acno='hover';
	$acoff='';
	
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	global $index,$member,$off_member_nav,$off_mall;
	$rootclassid=RootClassID($classid);
	
	//首页链接
	$ac=$acoff;	if ($index){$ac=$acno;}
	echo '  
        <tr class="navi_border">
			<td align="center" class="first">
				<a href="/m/">首页</a>
			</td> 
			<td>
			 <a href="/m/yundan/status.php">运单跟踪</a>
			 <a href="/m/yundan/price.php">在线报价</a>
			</td>
		</tr>
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
				if($off_mall){$url='/m/mall/list.php?classid='.$rs['classid'];$target='';}
			}else{
				$url='/m'.pathLT($rs['path']);$target='';
			}
		}
		if($url)
		{
			$ac=$acoff;	if ($rootclassid==$rs['classid']){$ac=$acno;}
			echo '       
			 <tr class="navi_border">
			 	<td align="center" class="first">
			   	 <a href="'.$url.'" target="'.$target.'"  class="'.$ac.'">'.cadd($rs['name'.$LT]).'</a>
				</td> 
				<td align="left">
				';
					//二级菜单栏目-开始
					if(!$member||($member&&$off_member_nav))
					{
						$Small=nav_small($rs['classid']);
						if($Small){echo '<div class="navi_small">'.$Small.'</div>';}
					}
					//二级菜单栏目-结束
			echo ' 
				</td>
			</tr>
			';
		}
	}
}

//前台主导航-子栏目
function nav_small($classid)
{	
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	$small='';
	$querysmall="select classid,bclassid,name{$LT},path,url{$LT},classtype from class where bclassid='{$classid}' and  checked=1 order by myorder desc,classid desc";
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
				$url='/m/mall/list.php?classid='.$rssmall['classid'];$target='';
			}else{
				$url='/m'.pathLT($rssmall['path']);$target='';
			}
		}
		
		
		//下级栏目
		$small3=nav_small($rssmall['classid']);
		if($small3){
			$small.= '<span><a href="'.$url.'" target="'.$target.'" class="bigclass">'.cadd($rssmall['name'.$LT]).'：</a>'.$small3.'</span>';
		}else{
			$small.= '<a href="'.$url.'" target="'.$target.'">'.cadd($rssmall['name'.$LT]).'</a>';
		}
		
	}
	return $small;
}
?>
<!--内容结束-->
<?php require_once($_SERVER['DOCUMENT_ROOT'].'/m/template/incluce/footer.php');  checked: ?>