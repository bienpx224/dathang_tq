<!--在线客服--> 
<div class="global-toolbar">
    <div class="toolbar-bottom">
    
    
		<script>document.write('<script src="/template/incluce/linkCS.php?CS=floatingCS&t='+Math.random()+'"><'+'/script>');</script>


   
    	<?php if($m){echo caddhtml($cs_m);}else{echo caddhtml($cs);}?>
        <br>
        <a href="<?=$m?>/yundan/price.php" target="_blank">
            <div id="my-history" class="toolbar-ico my-history">
                <i></i>
                <em><?=$LG['front.3'];//在线报价?></em>
            </div>
        </a>
       <a href="<?=$m?>/yundan/status.php" target="_blank">
            <div class="toolbar-ico my-message">
                <i></i>
                <em><?=$LG['front.65'];//运单跟踪?></em>
            </div>
        </a>
        
        
        
        <a href="/xamember/mall_order/list.php?pay=0" target="_blank">
            <div id="my-history" class="toolbar-ico my-shop">
                <i></i>
                <em><?=$LG['my_shop']//购物车?></em>
            </div>
        </a>
        <a href="/xamember/other/warehouse.php" target="_blank">
            <div id="my-history" class="toolbar-ico my-warehouse">
                <i></i>
                <em><?=$LG['my_warehouse']//仓库地址?></em>
            </div>
        </a>
        <a href="/xamember/msg/form.php?status=11&title=我要留言" target="_blank">
            <div id="my-history" class="toolbar-ico my-warehouse2">
                <i></i>
                <em><?=$LG['front.04']//我要留言?></em>
            </div>
        </a>

        <div id="go_top" class="toolbar-ico go-top">
            <i></i>
            <em><?=$LG['front.66'];//返回顶部?></em>
        </div>
    </div>

<link href="/css/temp2_service.css" rel="stylesheet" type="text/css">
<script type="text/javascript">
$(document).ready(function(e)
{
	$("#go_top").click(function(){
		$("html,body").animate({scrollTop: 0}, 500);	
	});
});
</script>
</div>
<!--在线客服结束-->