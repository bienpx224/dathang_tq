<div class="search-container">
  <form action="list.php" method="get">
    <input name="so" type="hidden" value="1">
    <input name="key" value="<?=$key?>" type="text" size="32" class="inputkey"  placeholder="<?=$LG['front.68'];//输入关键词搜索商品?>" required/>
    <input type="submit" class="inputSub btn btn-info"  value="<?=$LG['search']?>" />
  </form>
</div>
<div class="clear"></div>
<div class="inbox">
  <ul class="inbox-nav">
    <li class="<?=$attr=='mlid'?'active':''?>"><a href="?so=1&attr=mlid" class="btn"><strong><?=$LG['front.69'];//最新商品?></strong></a></li>
    <li class="<?=$attr=='isgood'?'active':''?>"><a href="?so=1&attr=isgood" class="btn"><strong><?=$LG['front.70'];//推荐商品?></strong></a></li>
    <li class="<?=$attr=='onclick'?'active':''?>"><a href="?so=1&attr=onclick" class="btn"><strong><?=$LG['front.71'];//人气商品?></strong></a></li>
    <li class="<?=$attr=='number_sold'?'active':''?>"><a href="?so=1&attr=number_sold" class="btn"><strong><?=$LG['front.72'];//热销商品?></strong></a></li>
  </ul>
</div>
<div class="clear"></div>
<div class="inbox">
  <ul class="inbox-nav">
    <?php SmallNav($classid,0,1);?>
  </ul>
</div>

 <br>
     <div class="nmbbhis">
        <div class="tt"> <span><?=$LG['front.73'];//本栏热门推荐?></span> </div>
        <div class="ct" id='history_list'>
          <div class="bgw p1-0">
            <ul class="clearfix">
<?php 
$allclassid_lt=$classid.SmallClassID($classid);
$query_lt="select mlid,url{$LT},title{$LT},edittime,addtime,titlecolor,titleimg{$LT},plclick,price,selling{$LT} from mall where checked=1 and classid in ({$allclassid_lt}) order by istop desc,isgood desc,ishead desc,edittime desc,addtime desc limit 6";
$sql_lt=$xingao->query($query_lt);
while($lt=$sql_lt->fetch_array())
{
?>
             <!--＝＝＝＝＝＝＝＝-->
              <li class="goodsimg"><a href="<?=$lt['url'.$LT]?cadd($lt['url'.$LT]):'/mall/show.php?mlid='.$lt['mlid'];?>"  target="_blank" title="<?=cadd($lt['selling'.$LT])?>"><img src="<?=ImgAdd($lt['titleimg'.$LT])?>" alt="<?=cadd($lt['title'.$LT])?>" class="B_blue" onload="AutoResizeImage(170,170,this)" /></a></li>
              
              <li><a href="<?=$lt['url'.$LT]?cadd($lt['url'.$LT]):'/mall/show.php?mlid='.$lt['mlid'];?>" target="_blank" style="color:<?=cadd($lt['titlecolor'])?>" title="<?=fnCharCount(cadd($lt['title'.$LT]))>50?cadd($lt['title'.$LT]):''?>"><?=leng($lt['title'.$LT],40,"...");?></a></li>
              <li class="jiage">
              <font><?=spr($lt['price'])?></font> <?=$XAmc?>
              
              <span> <?=$lt['plclick']?'('.$lt['plclick'].$LG['comments'].')':'';?></span>
              <br /></li>
<?php 
}
?>

            </ul>
          </div>
        </div>
        <div class="bt"><span></span></div>
      </div>
