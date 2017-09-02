<?php if($callFrom_op){//在列表中调用?>
	<?php if(have(spr($rs['status']),'2,4') && have($rs['pay'],'0,2')){?>
    <a href="../payment/?fromtable=daigou&payid=<?=$rs['dgid']?>" class="btn btn btn-warning showdiv"  target="XingAobox"><i class="icon-money"></i> <?=$LG['payment.index_1'];//支付运费?></a><!--showdiv只能放外部才有效-->
    <?php }?>


	<div class="btn-group"> <a class="btn  btn-info dropdown-toggle" href="#" data-toggle="dropdown" onClick="show_op_member('<?=$rs['dgid']?>');" > <?=$LG['op']?> <i class="icon-angle-down"></i> </a>
	<ul class="dropdown-menu dropdown-menu_baoguo" style="min-width:120px;">
    
		<?php if($wb){?>
        <li><a href="delivery.php?dgid=<?=$rs['dgid']?>" target="_blank"> <i class="icon-plane"></i> <?=$LG['baoguo.call_op_menu_1'];//下单发货?></a></li>
        <?php }?>
        <span id="show_menu<?=$rs['dgid']?>"></span>
	</ul>
	</div>
<?php }?>