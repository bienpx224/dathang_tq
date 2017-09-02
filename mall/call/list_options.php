
          <div class="choosetow"><span class="choose_l"><?=$LG['brand'];//品牌?>：</span>
           <span class="choose_r">
          <a href="?<?=$search?>&so=1&brand=&orderby=" class="<?=!$brand?'hover':''?>"><?=$LG['unlimited'];//不限?></a>
          </span>
    <?php 
	$query_cs="select * from classify where classtype='6' and checked=1 and bclassid<>0";
	$sql_cs=$xingao->query($query_cs);
	while($cs=$sql_cs->fetch_array())
	{
		$num=mysqli_num_rows($xingao->query("select classid from mall where checked=1 and classid in ({$allclassid}) and brand='{$cs['classid']}'"));
		if($num)
		{
			?> 			
			<span class="choose_r">
			<a href="?<?=$search?>&so=1&brand=<?=$cs['classid']?>&orderby=" class="<?=$brand==$cs['classid']?'hover':''?>"><?=$cs['name'.$LT]?></a>
			</span>
			<?php
		}		
	}
?>     
          </div>
          <div class="clear"></div>
          <div class="choosetow"><span class="choose_l"><?=$LG['warehouse'];//仓库?>：</span>
          
          <span class="choose_r">
          <a href="?<?=$search?>&so=1&warehouse=&orderby=" class="<?=!$warehouse?'hover':''?>"><?=$LG['unlimited'];//不限?></a>
          </span>
          
     <?php 
		$zhi=ToArr($warehouse_all,1);
		foreach($zhi as $a=>$b)
		{
			$zhi2=str_replace(':','：',$b);//把:替换为：
			$zhi2=ToArr($zhi2,'：');
			$b=html($b);
			if($b)
			{
	?> 			
          <span class="choose_r">
          <a href="?<?=$search?>&so=1&warehouse=<?=$zhi2[1]?>&orderby=" class="<?=$warehouse==$zhi2[1]?'hover':''?>"><?=$zhi2[0]?></a>
          </span>
	
     <?php			
			}
		}
	 ?>     
</div>
          <div class="clear"></div>
          <!--结合项--> 
<script type="text/javascript" language="javascript"> 
function $D1(objID){
  return document.getElementById(objID);
}
function zhankai(id,obj){
  if($D1(id).style.height=="auto"){
    $D1(id).style.height=75+"px";
    obj.innerHTML="<?=$LG['front.75'];//更多品牌?>";
		obj.parentNode.className = "filter_opera";
  }
  else{
    $D1(id).style.height="auto";
		obj.parentNode.className += " zhankai";
    obj.innerHTML="<?=$LG['front.76'];//收起品牌?>";
		$D1(id).getElementsByTagName("dt")[0].style.height = $D1(id).offsetHeight+"px";
  }
}
</script>
        