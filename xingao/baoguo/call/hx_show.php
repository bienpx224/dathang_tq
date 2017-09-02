 		<?php if($rs['hx']==1&&$rs['hx_id']){ ?>
		 要合入的包裹：
			<?php
			$query_hx="select bgydh,bgid,warehouse,weight,whPlace from baoguo where bgid in ($rs[hx_id])";
			$sql_hx=$xingao->query($query_hx);
			while($hx=$sql_hx->fetch_array())
			{
			?>
				<a href="show.php?bgid=<?=$hx['bgid']?>" target="_blank"><?=cadd($hx['bgydh'])?></a> <font class="gray2">(仓位:<?=cadd($rs['whPlace'])?>  重量:<?=spr($hx['weight']).$XAwt?>)</font><span class="xa_sep"> | </span>
			<?php
			}
			$rc=mysqli_affected_rows($xingao);
			if (!$rc){echo '没有能合箱的包裹，可能要合入的包裹已被删除！';} 
		}?>
