		<tr class="odd gradeX">
			<td align="center"><?=$month?'今年'.$month.'月':'指定的范围'?></td>
			<td align="center">
				<a href="../baoguo/list.php?status=1" target="_blank">
					<?php 
					$fe=FeData('baoguo',"count(*) as total,sum(`weight`) as weight"," {$where} and status='1'");
					$num_1=spr($fe['total']);$weight_1=spr($fe['weight']);
					if($num_1){
						echo $num_1.'个';
						echo '<span class="xa_sep"> / </span>';
						echo $weight_1.$XAwt;
					}
				?>
			  </a>			  
			</td>

			<td align="center">
				<a href="../baoguo/list.php?status=ruku" target="_blank">
				<?php 
					$fe=FeData('baoguo',"count(*) as total,sum(`weight`) as weight"," {$where} and status in (2,3)");
					$num_ruku=spr($fe['total']);$weight_ruku=spr($fe['weight']);
					if($num_ruku){
						echo $num_ruku.'个';
						echo '<span class="xa_sep"> / </span>';
						echo $weight_ruku.$XAwt;
					}
				?>
				</a>			  
			</td>
			
			<td align="center">
				<a href="../baoguo/list.php?status=4" target="_blank">
				<?php 
					$fe=FeData('baoguo',"count(*) as total,sum(`weight`) as weight"," {$where} and status='4'");
					$num_4=spr($fe['total']);$weight_4=spr($fe['weight']);
					if($num_4){
						echo $num_4.'个';
						echo '<span class="xa_sep"> / </span>';
						echo $weight_4.$XAwt;
					}
				?>
				</a>			  
			</td>
			<td align="center">
				<a href="../baoguo/list.php?status=9" target="_blank">
				<?php 
					$fe=FeData('baoguo',"count(*) as total,sum(`weight`) as weight"," {$where} and status='9'");
					$num_9=spr($fe['total']);$weight_9=spr($fe['weight']);
					if($num_9){
						echo $num_9.'个';
						echo '<span class="xa_sep"> / </span>';
						echo $weight_9.$XAwt;
					}
				?>
				</a>			  
			</td>
			
			<?php if($ON_ware){?>
			<td align="center">
				<a href="../baoguo/list.php?status=ware&orderby=ware_time&orderlx=desc" target="_blank">
				<?php 
					$fe=FeData('baoguo',"count(*) as total,sum(`weight`) as weight"," {$where} and ware='1'");
					$num_ware=spr($fe['total']);$bg_ware_weight=spr($fe['weight']);
					if($num_ware){
						echo $num_ware.'个';
						echo '<span class="xa_sep"> / </span>';
						echo $bg_ware_weight.$XAwt;
					}
				?>
				</a>			  
			</td>
			<?php }?>
			
			<td align="center">
				<?php 
				$total_num=$num_ruku+$num_4+$num_9+$num_ware;
				$total_weight=$weight_ruku+$weight_4+$weight_9+$bg_ware_weight;
				if($total_num){
					echo $total_num.'个';
					echo '<span class="xa_sep"> / </span>';
					echo $total_weight.$XAwt;
				}
				?>				
			</td>
		</tr>
