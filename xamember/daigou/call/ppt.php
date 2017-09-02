<div class="xats"> <strong><?=$LG['pptInfo']?></strong><br />

    &raquo; <?=$LG['daigou.40']//不支持积分抵消费用；不支持使用优惠券/折扣券；?>
    
    <?php if ($off_integral&&$integral_daigou>0){ ?>
   		 <?=LGtag($LG['daigou.42'],'<tag1>==<font class="red"> '.$integral_daigou.'  </font>')//每个代购单支付后可以获得?><br> 
    <?php }?>
    
    <?php if ($status==9.5){ ?>
    	&raquo; <?=$LG['daigou.43']//已经全部发货并签收的才会在此分类?><br> 
    <?php }?>
    
    <?php if(!$cr_daigou){$cr_daigou=ClassData($daigou_classid);}?>
    &raquo; <?=LGtag($LG['daigou.44'],'<tag1>=='.cadd($cr_daigou['path']))//如有疑问请看?>
    
    <!--************************************通用-提示**********************************-->
    <?php if($ON_ware&&$status=='inStorage'){?>
    &raquo; <?=LGtag($LG['daigou.176'],'<tag1>=='.$member_per[$Mgroupid]['dg_ware_freeDays']);?><br />	
    <?php }?>
    
</div>
