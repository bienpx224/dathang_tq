<?php 
switch($temlx)
{
	case 'news'://------------------------------------------------------------------------------------------------
		?>
		<li><a href="<?=$rs['url'.$LT]?cadd($rs['url'.$LT]):'/m'.pathLT($rs['path'])?>" style="color:<?=cadd($rs['titlecolor'])?>" target="_blank">&raquo; <?=cadd($rs['title'.$LT])?></a></li>
		<?php
	break;
	
	case 'help'://------------------------------------------------------------------------------------------------
		?>
	  <div class="liebiao"> <a href="<?=$rs['url'.$LT]?cadd($rs['url'.$LT]):'/m'.pathLT($rs['path'])?>" target="_blank"><img class="img" src="<?=ImgAdd($rs['img'.$LT])?>"></a>
		<div class="article">
		  
		 	 <a href="<?=$rs['url'.$LT]?cadd($rs['url'.$LT]):'/m'.pathLT($rs['path'])?>" target="_blank" style="color:<?=cadd($rs['titlecolor'])?>">
			 <?=cadd($rs['title'.$LT])?>
			 </a>
		   <p><?=leng($rs['intro'.$LT],130,"...");?></p>
		</div>
	  </div>
		<?php
	break;
}
?>

