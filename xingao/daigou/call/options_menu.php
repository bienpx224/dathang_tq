<!--筛选菜单-->
	<li class="dropdown" id="header_task_bar"> 
		<button type="button" class="btn btn-info dropdown-toggle"  data-hover="dropdown"  data-close-others="true"><i class="icon-tasks"></i> <?=$LG['daigou.138'];//代购状态?> </button>
		
		<ul class="dropdown-menu extended inbox" style="width:700px;">
			<li>
				<p></p>
			</li>
            
            
        <li style="padding:5px;"> 
        <span class="btn"><?=$LG['daigou.139'];//留言：?></span>
        <?php 
		echo daigou_Options('memberContentNew',1,daigou_ContentNew(1));
		echo daigou_Options('memberContentReplyNew',1,daigou_ContentNew(2));
		echo daigou_Options('sellerContentNew',1,daigou_ContentNew(3));
		echo daigou_Options('sellerContentReplyNew',1,daigou_ContentNew(4));
		?>
        </li>

        
		<?php if($callFrom=='member'||$callFrom=='manage'){?>
			<?php if($callFrom=='member'||($callFrom=='manage'&&permissions('daigou_ed,daigou_cg,daigou_th,daigou_zg,daigou_hh,daigou_ch','','manage',1)) ){?>       
                <li style="padding:5px;"> 
                <span class="btn"><?=$LG['daigou.140'];//申请：?></span>
                <?php for ($i=1; $i<=10; $i++){ echo daigou_Options('memberStatus',$i,daigou_memberStatus($i)); }?>
                </li>
    
                <li style="padding:5px;"> 
                <span class="btn"><?=$LG['daigou.141'];//处理：?></span>
                <?php for ($i=0; $i<=10; $i++){ echo daigou_Options('manageStatus',$i,daigou_manageStatus($i)); }?>
                </li>
            <?php }?>
            
            <li style="padding:5px;"> 
            <span class="btn"><?=$LG['daigou.142'];//缺货：?></span>
            <?php for ($i=0; $i<=10; $i++){ echo daigou_Options('lackStatus',$i,daigou_lackStatus($i)); }?>
            </li>
        <?php }?>




		<?php if($callFrom=='seller'||$callFrom=='manage'){?>
            <li style="padding:5px;"> 
			<span class="btn"><?=$LG['daigou.143'];//供应商：?></span>
			<?php for ($i=0; $i<=10; $i++){ echo daigou_Options('sellerStatus',$i,daigou_sellerStatus($i)); }?>
			</li>
        <?php }?>




		<?php if($callFrom=='member'||$callFrom=='manage'){?>
			<li style="padding:5px;"> 
			<span class="btn"><?=$LG['yundan.Xcall_options_menu_2'];//状态：?></span>
			<?php for ($i=0; $i<=10; $i+=0.1){ echo daigou_Options('status',$i,daigou_Status($i));}?>
			</li>
        <?php }?>
           

		</ul>
	</li>