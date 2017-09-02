		<tr class="odd gradeX">
			<td align="center"><?=$month?'今年'.$month.'月':'指定的范围'?></td>
			<td align="center">
				<a href="../mall_order/list.php?so=1&status=0" target="_blank">
					<?php 
					$fe=FeData('mall_order',"count(*) as total,sum(`payment`) as payment"," {$where} and status='0'");
					$num_0=spr($fe['total']);$payment_1=spr($fe['payment']);
					if($num_0){
						echo $num_0.'单';
						echo '<span class="xa_sep"> / </span>';
						echo $payment_0.$XAmc;
					}
				?>
					</a>			  
			</td>

			<td align="center">
				<a href="../mall_order/list.php?so=1&status=1" target="_blank">
				<?php 
					$fe=FeData('mall_order',"count(*) as total,sum(`payment`) as payment"," {$where} and status='1'");
					$num_1=spr($fe['total']);$payment_1=spr($fe['payment']);
					if($num_1){
						echo $num_1.'单';
						echo '<span class="xa_sep"> / </span>';
						echo $payment_1.$XAmc;
					}
				?>
				</a>			  
			</td>
			
			
			
			<td align="center">
				<?php 
				$total_num=$num_0+$num_1;
				$total_payment=$payment_0+$payment_1;
				if($total_num){
					echo $total_num.'单';
					echo '<span class="xa_sep"> / </span>';
					echo $total_payment.$XAmc;
				}
				?>				
			</td>
		</tr>
