<?php 
if ($weight>0)
{
?>
<table class="table table-striped table-bordered table-hover">
	<thead>
		<tr>
			<th align="center"><?=$LG['channel']?> <br>
            <font style="font-weight:normal"><?=warehouse($warehouse)?> <?=$weight.$XAwt?></font>
            </th>
            <th align="center"><?=$LG['fee']?>
			<br><font style="font-weight:normal">
			<?php
			 if(!$Mgroupid)
			 {
				 echo LGtag($LG['front.11'],'<tag1>=='.cadd($member_per[$groupid]['groupname']));
			 }else{
				echo cadd($member_per[$groupid]['groupname']);
			 }
			 ?>
             
             </font>
            </th>
		</tr>
	</thead>
	<tbody>
	<?php 
	for($i=1; $i<=20; $i++)
	{ 
		$channel=$i;
		require($_SERVER['DOCUMENT_ROOT'].'/xingao/yundan/call/get_price.php');//获取价格
		if($channel_checked)
		{
	?>
		<tr>
			<td align="center" style="font-size:16px;">
            <?=cadd($channel_name)?>
			</td>
             <td align="center">
				<?php 
					$weight=spr($_GET['weight']);
					$channel=$i;
					$warehouse=$warehouse;
					
					echo '<font class="show_price">';
					$phpcall=1;require($_SERVER['DOCUMENT_ROOT'].'/xingao/yundan/call/calc.php');
					echo '</font>'.$XAmc;
					
					echo '<br><font class="gray2">';
					$content='warehouse_'.$warehouse.'_content_'.$i;
					echo $$content;
					echo '</font>';
                ?>
             </td>
			
		</tr>
	<?php
		}
	}
	?>
	</tbody>
</table>
<?php 
}else{echo '<font class="red">'.$LG['front.15'].'</font>';}
?>
<div class="gray2">
    <strong><?=$LG['pptInfo'];//提示：?></strong> <br>
    
    &raquo; <?=$LG['front.17']?><a href="/xamember/other/tab.php?classid=30" target="_blank"><?=$LG['front.16'];//会员中心查看?></a> ，<?=$LG['front.18']?><a href="<?php $xacd=ClassData($price_classid);echo $m.pathLT($xacd['path']);?>" target="_blank"><?=cadd($xacd['name'])?></a> 
</div>