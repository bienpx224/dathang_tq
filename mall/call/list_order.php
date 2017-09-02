<div id="goods_list_cat">
<div class="tt">
  <div id="categorylistnav"> 
    <!-- <span class="px"><?=$LG['front.77'];//库存情况：?></span> <a HREF="?orderby="><b class="qb"></b><?=$LG['all'];//全部?></a> <a HREF="category.php?category=2&display=grid&brand=0&kuc=1&zys=0&price_min=0&price_max=0&filter_attr=0&page=1&sort=click_count&order=DESC#goods_list"><b class="yh"></b>仅显示有货</a> <span class="fg">&nbsp;</span> --> 
    
    <span class="px"><?=$LG['front.78'];//排列：?></span> 
    <a href="?<?=$search?>&orderby=mlid&orderlx="> <img src="/images/goods_id_<?=$orderby=='mlid'||!$orderby?'DESC':'default'?>.gif" title="<?=$LG['front.80'];//按上架时间排序?>"></a>
     <a href="?<?=$search?>&orderby=onclick&orderlx="> <img src="/images/click_count_<?=$orderby=='onclick'?'DESC':'default'?>.gif" title="<?=$LG['front.81'];//按人气排序?>"></a>
      <a href="?<?=$search?>&orderby=number_sold&orderlx="> <img src="/images/salesnum_<?=$orderby=='number_sold'?'DESC':'default'?>.gif" title="<?=$LG['front.82'];//按销量排序?>"></a> 
      <a href="?<?=$search?>&orderby=price&orderlx="> <img src="/images/shop_price_<?=$orderby=='price'?'DESC':'default'?>.gif" title="<?=$LG['front.83'];//按价格排序?>"></a>
       </div>
  <div class="clear"></div>
  </div>
  
<div class="zyzxss">
  <div class="zyzxs"><?=$LG['front.79'];//提示：我们所有商品均为海外本土商品，非国内进口版，需按规定经海关通关，若有疑问可阅读相关文章或咨询客服。?></div>
</div> </div>