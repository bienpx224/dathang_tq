<!--筛选菜单-->
	<li class="dropdown" id="header_task_bar">
		<button type="button" class="btn btn-info dropdown-toggle"  data-hover="dropdown"  data-close-others="true">
        <i class="icon-tasks"></i> 
		<?=$LG['baoguo.Xcall_options_menu_1']?> 
        <?php 
		$bgnum_op=baoguo_num_op();
		if($bgnum_op){echo '<span class="badge badge-warning">'.$bgnum_op.'</span>';}
		?>
        </button>
		<ul class="dropdown-menu extended inbox" style="width:550px;">
			<li>
				<p></p>
			</li>
			
			<?php if($off_hx||$off_fx||$off_tra_user){?>
			<li style="padding:5px;"> 
			<span class="btn"><?=$LG['baoguo.Xcall_options_menu_2']?></span>
				<?php 
				if($off_hx){baoguo_Screening('hx',1);}
				if($off_fx){baoguo_Screening('fx',1);}
				if($off_tra_user){baoguo_Screening('tra_user',1);}
				?>
			</li>
			<?php }?>

			<?php if($off_baoguo_op_02){?>
			<li style="padding:5px;"> 
			<span class="btn"><?=$LG['baoguo.Xcall_options_menu_3']?></span>
				<?php 
				baoguo_Screening($scr_field='op_02',1);
				baoguo_Screening($scr_field,2);
				baoguo_Screening($scr_field,3);
				baoguo_Screening($scr_field,10);
				?>
			</li>
			<?php }?>

			<?php if($off_baoguo_op_06){?>
			<li style="padding:5px;"> 
			<span class="btn"><?=$LG['baoguo.Xcall_options_menu_4']?></span>
				<?php 
				baoguo_Screening($scr_field='op_06',1);
				baoguo_Screening($scr_field,2);
				baoguo_Screening($scr_field,3);
				baoguo_Screening($scr_field,10);
				?>
			</li>
			<?php }?>

			<?php if($off_baoguo_op_07){?>
			<li style="padding:5px;"> 
			<span class="btn"><?=$LG['baoguo.Xcall_options_menu_5']?></span>
				<?php 
				baoguo_Screening($scr_field='op_07',1);
				baoguo_Screening($scr_field,2);
				baoguo_Screening($scr_field,3);
				baoguo_Screening($scr_field,10);
				?>
			</li>
			<?php }?>

			<?php if($off_baoguo_th){?>
			<li style="padding:5px;"> 
			<span class="btn"><?=$LG['baoguo.Xcall_options_menu_6']?></span>
				<?php 
				baoguo_Screening($scr_field='th',1);
				baoguo_Screening($scr_field,2);
				baoguo_Screening($scr_field,3);
				baoguo_Screening($scr_field,10);
				?>
			</li>
			<?php }?>

			<?php if($off_baoguo_op_09){?>
			<li style="padding:5px;"> 
			<span class="btn"><?=$LG['baoguo.Xcall_options_menu_7']?></span>
				<?php 
				baoguo_Screening($scr_field='op_09',1);
				baoguo_Screening($scr_field,2);
				baoguo_Screening($scr_field,3);
				baoguo_Screening($scr_field,10);
				?>
			</li>
			<?php }?>

			<?php if($off_baoguo_op_10){?>
			<li style="padding:5px;"> 
			<span class="btn"><?=$LG['baoguo.Xcall_options_menu_8']?></span>
				<?php 
				baoguo_Screening($scr_field='op_10',1);
				baoguo_Screening($scr_field,2);
				baoguo_Screening($scr_field,3);
				baoguo_Screening($scr_field,10);
				?>
			</li>
			<?php }?>

			<?php if($off_baoguo_op_11){?>
			<li style="padding:5px;"> 
			<span class="btn"><?=$LG['baoguo.Xcall_options_menu_9']?></span>
				<?php 
				baoguo_Screening($scr_field='op_11',1);
				baoguo_Screening($scr_field,2);
				baoguo_Screening($scr_field,3);
				baoguo_Screening($scr_field,10);
				?>
			</li>
			<?php }?>

			
		</ul>
	</li>

