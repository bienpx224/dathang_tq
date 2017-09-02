<?php 
switch($temlx)
{
	case 'news'://------------------------------------------------------------------------------------------------
		?>
		<li><a href="<?=$rs['url'.$LT]?cadd($rs['url'.$LT]):pathLT($rs['path'])?>"   target="_blank"  style="color:<?=cadd($rs['titlecolor'])?>" title="<?=cadd($rs['title'.$LT])?>">&raquo; <?=leng($rs['title'.$LT],42,"...");?><span><?=DateYmd($rs['edittime'],2,$rs['addtime'])?></span></a></li>
		<?php
	break;
	
	case 'help'://------------------------------------------------------------------------------------------------
		$i+=1;
		?>
		<!--第2个加<li style="float:right;">-->
		<li <?php if($i==2){echo 'style="float:right;"';$i=0;}?>> <a class="img-cont" href="<?=$rs['url'.$LT]?cadd($rs['url'.$LT]):pathLT($rs['path'])?>" target="_blank"><img src="<?=ImgAdd($rs['img'.$LT])?>"></a>
		
			<div class="text-cont"> <strong><a href="<?=$rs['url'.$LT]?cadd($rs['url'.$LT]):pathLT($rs['path'])?>" target="_blank"  style="color:<?=cadd($rs['titlecolor'])?>" title="<?=cadd($rs['title'.$LT])?>"><?=leng($rs['title'.$LT],25,"...");?></a></strong>
			
				<p class="remark"><?=leng($rs['intro'.$LT],130,"...");?></p>
			</div>
		</li>
		<?php
	break;
	
}
?>

