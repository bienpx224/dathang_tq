<!--筛选菜单-->
	<li class="dropdown" id="header_task_bar">
		<button type="button" class="btn btn-info dropdown-toggle"  data-hover="dropdown"  data-close-others="true"><i class="icon-tasks"></i> <?=$LG['yundan.Xcall_options_menu_1'];//物流状态?> </button>
		<ul class="dropdown-menu extended inbox" style="width:550px;">
			<li>
				<p></p>
			</li>
			<li style="padding:5px;"> 
			<span class="btn"><?=$LG['yundan.Xcall_options_menu_2'];//状态：?></span>
				<?php 
				for ($i=0; $i<=30; $i++) 
				{
					$status_name=status_name($i);
					$on='status_on_'.$i;
					if($$on&&$status_name){yundan_Screening('status',$i,$status_name);}
				}
				?>
			</li>

		</ul>
	</li>

