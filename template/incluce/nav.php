<?php if(!$member){?>
<div class="xa_top_fixed">
<!--顶部-开始-->
<div class="xa_tit">
	<div class="xa_container">
    
    <?php if($JSCurrency){?>
	<div class="xia_tit_left fl_l">
    	<div class="txtScroll-top">
			<div class="bd" title="<?=LGtag($LG['name.nav_2'],'<tag1>==3')//每3小时更新一次?>">
            	<font class="title"><?=$LG['name.nav_3'];//当前汇率：?></font>
				<script>document.write('<script src="/public/exchangeJS.php?up=0&t='+Math.random()+'"><'+'/script>');</script>
			</div>
		</div>
        
		<script type="text/javascript">
          jQuery(".txtScroll-top").slide({mainCell:".bd ul",autoPage:true,effect:"topLoop",autoPlay:true,vis:1,interTime:2500});
		</script>
    </div>
    <?php }?>
    
    <div class="xia_tit_right fl_r">
    <script>document.write('<script src="/template/incluce/navTop.php?t='+Math.random()+'"><'+'/script>');</script> <!--顶部导航JS-->
<script type="text/javascript">
$(document).ready(function(e)
{
	$(".yuyan").hover(
		function(){
			$(this).find("span").show();
			},
		function(){
			$(this).find("span").hide();
			}
	);
});
</script>

    </div>
    </div>
</div>
<!--顶部-结束-->
<?php }else{?> 
<div>
<?php }?>





<!--导航-开始-->
<div class="xa_header_bg" id="xa_nav_scroll">
<div class="xa_container">
    	<div class="xa_header">
            <a href="/" class="xa_logo"><img src="/images/logo.png"/></a>
            <nav class="xa_navbar">
              <div class="xa_collapse">
                <ul class="xa_nav">
                  <?php nav($classid);?>
                </ul>
              </div>
            </nav>
        </div>
    </div>
</div>
<!--导航-结束-->


</div><!--<div class="xa_top_fixed">-->

<script type="text/javascript">
$(document).ready(function(e) {
	$('.xa_dropdown').hover(function(){
		$(this).find('.xa_dropdown-menu').show();
		$(this).addClass("xa_open");
	},
	function(){
		$(this).find('.xa_dropdown-menu').hide();
		$(this).removeClass("xa_open");
	});
});
</script>








<?php 
//前台主导航
function nav($classid=0)
{   
	
	
	//高亮样式设置-开始------------------------------------------------------------------------
	$acno='xa_active';
	$acoff='';
	//高亮样式设置-结束------------------------------------------------------------------------
	
	
	
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	global $index,$member,$off_member_nav,$off_mall;//此页尽量少用global，很多冲突失效
	$rootclassid=RootClassID($classid);
	$ac=$acoff;	if ($index){$ac=$acno;}//首页链接
	
	
	
	
	//一级菜单(首页)样式设置-开始------------------------------------------------------------------------
	echo '<li class="'.$ac.'"><a href="'.pathLT('/html/').'">'.$LG['name.nav_0'].'</a></li>';
	//一级菜单(首页)样式设置-结束------------------------------------------------------------------------




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
			
			
			
			
			//一级菜单样式设置-开始------------------------------------------------------------------------
			echo '<li class="xa_dropdown '.$ac.'"><a href="'.$url.'" target="'.$target.'">'.cadd($rs['name'.$LT]).'</a>';
			
				if(!$member||($member&&$off_member_nav))
				{
					$small=nav_small($rs['classid']);
					if($small)
					{
						
						//二级菜单外框-开始------------------------------------------------------------------
						echo '<ul class="xa_dropdown-menu">'.$small.'</ul>';
						//二级菜单外框-结束------------------------------------------------------------------
						
					}
				}
				
			echo '</li>';
			//一级菜单样式设置-结束------------------------------------------------------------------------
		
		
			
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
				$url='/mall/list.php?classid='.$rssmall['classid'];$target='';
			}else{
				$url=pathLT($rssmall['path']);$target='';
			}
		}
		
		
		
		//二级菜单样式设置-开始------------------------------------------------------------------------
		$small.='<li><a href="'.$url.'" target="'.$target.'">'.cadd($rssmall['name'.$LT]).'</a></li>';
		//二级菜单样式设置-结束------------------------------------------------------------------------
		
		
		
	}
	return $small;
}
?>