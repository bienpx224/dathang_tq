<form action="<?=$m?>/yundan/status.php" method="get">
<?php if($index){?>
	<?php if($m){?>
        <!--首页-手机版-->
        <input name="ydh" type="text" class="form-control"  placeholder="<?=$LG['front.19'];//请输入您的运单号?>">
        <button type="submit" class="btn btn-warning" style="width:100%; margin-top:5px;" > 
        <i class="icon-search"></i> <?=$LG['front.21'];//运单查询?>
        </button>
    <?php }else{?>
   		<!--首页-电脑版-->
        <input name="ydh" type="text" class="xa_text" placeholder="<?=$LG['front.19'];//请输入您的运单号?>">
        <input type="submit" class="xa_btn" value="<?=$LG['front.1'];//运单查询?>">
	<?php }?>
<?php }else{?>
    <!--电脑和手机内页-->
    <textarea name="ydh" rows="<?=$rows?>" class="form-control"  placeholder="<?=$LG['front.20'];//一行一个单号?>"><?=cadd($_GET['ydh'])?></textarea>
    
    <button type="submit" class="btn btn-primary" style="width:100%; margin-top:10px;"> 
    <i class="icon-search"></i> <?=$LG['front.21'];//运单查询?>
    </button>
<?php }?>
</form>
