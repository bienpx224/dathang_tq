<div class="goodss_left"> 
<script language="javascript">
function changeTabs(src)
{
	for(var i=1;i<=6;i++)
	{
		if(i==src)
		{
			document.getElementById('tabs-menu'+src).className   = "current";
			document.getElementById('nav-tabs-content'+src).style.display    = "block";
		}
		else
		{
			document.getElementById('tabs-menu'+i).className   = "";
			document.getElementById('nav-tabs-content'+i).style.display    = "none";
		}
	}
}
</script>

<div class="reviewsAndConsult" id="nav_left_layout">
  <div class="nav-tabs">
    <ul>
      <li id="tabs-menu1" class="current" onClick="changeTabs(1)"><a href="javascript:void(0)"><?=$LG['front.126'];//产品信息?></a></li>
      <li id="tabs-menu2" onClick="changeTabs(2)"><a href="javascript:void(0)"><?=$LG['front.127'];//常见问题?></a></li>
      <li id="tabs-menu3" onClick="changeTabs(3)"><a href="/comments/show.php?fromtable=mall&fromid=<?=$rs['mlid']?>" name="pl" target="XingAobox"><?=$rs['plclick']?><?=$LG['comments'];//评论?></a></li>
    </ul>
  </div>
</div>
<div id="nav-tabs-content1" style="margin-bottom:30px;"> <br>
  <div class="suxing">
    <div> <span><?=$LG['front.128'];//类别：?></span> <span><?=classify($rs['category'],2)?></span> </div>
    <div> <span><?=$LG['front.129'];//品名：?></span> <span><?=cadd($rs['goods'])?></span> </div>
    <div> <span><?=$LG['front.130'];//规格：?></span> <span><?=cadd($rs['spec'])?></span> </div>
    <div> <span><?=$LG['front.131'];//品牌：?></span> <span><?=classify($rs['brand'],2)?></span> </div>
    <div> <span><?=$LG['front.132'];//单位：?></span> <span><?=$rs['unit']?></span> </div>
    <div> <span><?=$LG['front.133'];//编号：?></span> <span><?=cadd($rs['coding'])?></span> </div>
  </div>
  <br>
  <div style="margin-top:20px;">
  
  <?php 
  if($rs['content'.$LT])
  {
      //主内容
      echo caddhtml($rs['content'.$LT]);
  }else{
      $arr=$rs['img'.$LT]?$rs['titleimg'.$LT].','.$rs['img'.$LT]:$rs['titleimg'.$LT];
      if($arr)
      {
          if(!is_array($arr)){$arr=explode(",",$arr);}//转数组
          foreach($arr as $arrkey=>$value)
          {
          ?>
            <div align="center"><img src="<?=$value?>"/></div>
          <?php
          }
      }
  }
  ?>

  </div>
</div>
<div id="nav-tabs-content2" style="display:none; margin-bottom:30px;">
<img src="/images/ashangcheng_lc.jpg"/>
  <div class="faq">
   <?=caddhtml($mallFAQ)?>
  </div>
</div>
<div id="nav-tabs-content3" style="display:none; margin-bottom:30px;">
 <div class="row">
<!--评论开始-->
<iframe id="XingAobox" name="XingAobox"  width="100%" height="0" frameborder="0" scrolling="auto"></iframe>
<script> $(function(){ iframeHeight('XingAobox',30); });</script>
<!--评论结束-->

</div>
  
  
</div>
</div>