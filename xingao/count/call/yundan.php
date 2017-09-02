		<tr class="odd gradeX">
			<td align="center"><?=$month?'今年'.$month.'月':'指定的范围'?></td>
			<td align="center">
				<a href="../yundan/list.php?status=0" target="_blank">
					<?php 
					$fe=FeData('yundan',"count(*) as total,sum(`weight`) as weight"," {$where} and status='0'");
					$num_0=spr($fe['total']);$weight_0=spr($fe['weight']);
					if($num_0){
						echo $num_0.'单';
						echo '<span class="xa_sep"> / </span>';
						echo $weight_0.$XAwt;
					}
				?>
					</a>			  
			</td>

			<td align="center">
				<?php 
					$fe=FeData('yundan',"count(*) as total,sum(`weight`) as weight"," {$where} and status in (2,3,4)");
					$num_ruku=spr($fe['total']);$weight_ruku=spr($fe['weight']);
					if($num_ruku){
						echo $num_ruku.'单';
						echo '<span class="xa_sep"> / </span>';
						echo $weight_ruku.$XAwt;
					}
				?>
			</td>
			
			<td align="center">
				<?php 
					$fe=FeData('yundan',"count(*) as total,sum(`weight`) as weight"," {$where} and status>'4' and status<='13'");
					$num_4=spr($fe['total']);$weight_4=spr($fe['weight']);
					if($num_4){
						echo $num_4.'单';
						echo '<span class="xa_sep"> / </span>';
						echo $weight_4.$XAwt;
					}
				?>
			</td>
			<?php if($status_on_14){?>
			<td align="center">
				<?php 
					$fe=FeData('yundan',"count(*) as total,sum(`weight`) as weight"," {$where} and status>='14' and status<='19'");
					$num_14=spr($fe['total']);$weight_14=spr($fe['weight']);
					if($num_14){
						echo $num_14.'单';
						echo '<span class="xa_sep"> / </span>';
						echo $weight_14.$XAwt;
					}
				?>
			</td>
			<?php }?>
			
			<td align="center">
				<?php 
					$fe=FeData('yundan',"count(*) as total,sum(`weight`) as weight"," {$where} and status>='20' and status<='29'");
					$num_20=spr($fe['total']);$weight_20=spr($fe['weight']);
					if($num_20){
						echo $num_20.'单';
						echo '<span class="xa_sep"> / </span>';
						echo $weight_20.$XAwt;
					}
				?>
			</td>
			<td align="center">
				<?php 
					$fe=FeData('yundan',"count(*) as total,sum(`weight`) as weight"," {$where} and status='30'");
					$num_30=spr($fe['total']);$weight_30=spr($fe['weight']);
					if($num_30){
						echo $num_30.'单';
						echo '<span class="xa_sep"> / </span>';
						echo $weight_30.$XAwt;
					}
				?>
			</td>

			<td align="center">
				<?php 
				$total_num=$num_ruku+$num_4+$num_14+$num_20+$num_30;
				$total_weight=$weight_ruku+$weight_4+$weight_14+$weight_20+$weight_30;
				if($total_num){
					echo $total_num.'单';
					echo '<span class="xa_sep"> / </span>';
					echo $total_weight.$XAwt;
				}
				?>				
			</td>
		</tr>
