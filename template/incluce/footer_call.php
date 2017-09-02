<!--底部-->

<footer>
  <div class="xa_container">
    <div class="xa_foot_left">
    
<ul>
	<?php 
    $classid=27;
    $crs=ClassData($classid);
    ?>		
    <b><a href="<?=pathLT($crs['path'])?>" target="_blank"><?=cadd($crs['name'])?></a></b>
    <?=nav_small_foot($classid)?>
</ul>


<ul>
	<?php 
    $classid=14;
    $crs=ClassData($classid);
    ?>		
    <b><a href="<?=pathLT($crs['path'])?>" target="_blank"><?=cadd($crs['name'])?></a></b>
    <?=nav_small_foot($classid)?>
</ul>

      
      
      
<?php 
//友情链接
if($index)
{
	$i=0;
	$class='1';
	$limit=6;
	$order=' order by myorder desc,id desc';//默认排序
	$query="select * from link where checked=1 and class='{$class}' {$order} limit {$limit}";
	$sql=$xingao->query($query);
	while($rs=$sql->fetch_array())
	{
		$i+=1;if($i==1){echo '<ul><b>'.$LG['front.49'].'</b>';}
	?> <li style="width:110px;"><a href="<?=cadd($rs['url'])?>" target="_blank" title="<?=cadd($rs['name'])?>">- <?=cadd($rs['name'])?></a></li> <?php 
	}
	if($i){echo '</ul>';$link=1;}
}
?>



<?php if(!$link){ //没有输出友情链接时,再多输出一个分类?>
<ul>
	<?php 
    $classid=1;
    $crs=ClassData($classid);
    ?>		
    <b><a href="<?=pathLT($crs['path'])?>" target="_blank"><?=cadd($crs['name'])?></a></b>
    <?=nav_small_foot($classid)?>
</ul>
<?php }?>




<?php 
//子栏目
function nav_small_foot($classid)
{	
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	$small='';
	$querysmall="select classid,bclassid,name{$LT},path,url{$LT},classtype from class where bclassid='{$classid}' and  checked=1 order by myorder desc,classid desc limit 8";
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
		$small.='<li><a href="'.$url.'" target="'.$target.'">- '.cadd($rssmall['name'.$LT]).'</a></li>';
		//二级菜单样式设置-结束------------------------------------------------------------------------
		
		
		
	}
	return $small;
}
?> 

     
    </div>
    
    <div class="xa_foot_right">
        
        <li>
            <img src="/images/wx.jpg"/>
            <font><?=$LG['front.50']?></font>
        </li>
        <li>
            <img src="/images/wx_mp.jpg"/>
            <font><?=$LG['front.51']?></font>
        </li>
    </div>
    
    
    <div class="xa_foot_copy">
      <p>
      Copyright ©  <?=date('Y',time())?> <?=str_ireplace('/','',str_ireplace('https://','',str_ireplace('http://','',cadd($siteurl))))?> All Right Reserved. <!--<a href="http://www.miibeian.gov.cn/" target="_blank">桂ICP备13006844号</a>-->
       <?php if($off_jishu){?><a href="http://www.xingaowl.com" target="_blank" style="margin-left:20px;"><?=$LG['front.52']?>:<?php if($LT=='CN'){echo '兴奥网络';}else{echo 'XingAo';}?></a><?php }?>

      </p>
    
    <!--[if lt IE 9]>
    <font style="color:#F00"><br /> <?=$LG['pptBrowserHTML']?><br>
    </font>
    <![endif]--> 
    </div>
    
  </div>
</footer>
<?php require_once($_SERVER['DOCUMENT_ROOT'].'/template/incluce/service.php');?> 

<?php if(!$member){?> 
<!--流量统计代码:推广账号的代码--> 
<script>
var _hmt = _hmt || [];
(function() {
  var hm = document.createElement("script");
  hm.src = "https://hm.baidu.com/hm.js?ba8959dd1f9d63fd28746140657c1b79";
  var s = document.getElementsByTagName("script")[0]; 
  s.parentNode.insertBefore(hm, s);
})();
</script>


<!--分享代码：会员中心不能分享--> 
<script>window._bd_share_config={"common":{"bdSnsKey":{},"bdText":"","bdMini":"1","bdMiniList":false,"bdPic":"","bdStyle":"0","bdSize":"16"},"slide":{"type":"slide","bdImg":"3","bdPos":"left","bdTop":"100"}};with(document)0[(getElementsByTagName('head')[0]||body).appendChild(createElement('script')).src='http://bdimg.share.baidu.com/static/api/js/share.js?v=89860593.js?cdnversion='+~(-new Date()/36e5)];
</script> 
<?php }?> 



<!--汇率更新--> 
<iframe src="/public/exchangeJS.php?up=1" width="0" height="0" style="display:none;"></iframe>