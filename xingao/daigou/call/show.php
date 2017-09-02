<?php require_once($_SERVER['DOCUMENT_ROOT'].'/public/enlarge/call.html');?>

<div class="page_ny"> 
<div class=""><!--单页时去掉 class="row",不然会有横滚动条,并且form 加 style="margin:20px;"-->
    <div class="col-md-12">
      <h3 class="page-title"><?=$headtitle?></h3>
    </div>
  </div>
  
	<!-- BEGIN PAGE CONTENT-->
	<div class="form-horizontal form-bordered">
	<div class="tabbable tabbable-custom boxless">

      <div class="tab-content">
        <div class="tab-pane active" id="tab_1">
          <div class="form">
            <div class="form-body">
            
            <!----------------------------------------基本资料---------------------------------------->
              <div class="portlet">
                 <div class="portlet-title">
                  <div class="caption"><i class="icon-reorder"></i><?=$LG['data.form_2'];//基本资料?></div>
                  <div class="tools"> <a href="javascript:;" class="collapse"></a> </div><!--缩小expand 展开collapse-->
                </div>
               <div class="portlet-body form"> 
                  <!--表单内容-->
 
                  <div class="form-group">
                    <div class="control-label col-md-2 right"><?=$LG['daigou.60'];//代购单号?></div>
                    <div class="col-md-2 has-error">
					<?=cadd($rs['dgdh'])?>
                    </div>

                      <div class="control-label col-md-2 right"><?=$LG['status'];//状态?></div>
                      <div class="col-md-2 has-error">
                          <span class="gray2 tooltips" data-container="body" data-placement="top" data-original-title="<?=$LG['function.148']?><?=DateYmd($rs['statusTime'])?>">
                          <?=daigou_Status(spr($rs['status']))?>
                          </span>
                      </div>

                    <div class="control-label col-md-2 right"><?=$LG['source'];//来源?></div>
                    <div class="col-md-2 has-error">
                    <?=daigou_addSource($rs['addSource'])?>
                    </div>

                  </div>
                  
                  
 				<div class="form-group">
                     <div class="control-label col-md-2 right"><?=$LG['member'];//会员?></div>
                    <div class="col-md-2 has-error">
                    <?=cadd($rs['username'])?> (<?=$rs['userid']?>)
                    </div>

                  <div class="control-label col-md-2 right"><?=$LG['daigou.146'];//会员申请?></div>
                    <div class="col-md-2 has-error">
    					<?=daigou_memberStatus('',($callFrom=='member'?4:3),$rs)?>
                    </div>

                   <div class="control-label col-md-2 right"><?=$LG['daigou.147'];//支付情况?></div>
                    <div class="col-md-2 has-error">
					<?=daigou_payStatus($rs['pay'])?>
                    </div>
                    

                  </div>  
                                 
                  

             	 <div class="form-group">
                    
                    <div class="control-label col-md-2 right"><?=$LG['Museric'];//入库码?></div>
                    <div class="col-md-2 has-error">
                    <?=cadd($rs['whcod'])?>
                    </div>
     
                    <div class="control-label col-md-2 right"><?=$LG['baoguo.send_4'];//入库时间?></div>
                    <div class="col-md-2 has-error">
                    <?=DateYmd($rs['inStorageTime'])?>
                    </div>
                 
                    <div class="control-label col-md-2 right"><?=$LG['addtime'];//下单时间?></div>
                    <div class="col-md-2 has-error">
                    <?=DateYmd($rs['addtime'])?>
                    </div>
                    
                  </div>
                  
                  
  <div class="form-group">

  <div class="control-label col-md-2 right" <?=$warehouse_more?'':'style="display:none"'?> > <?=$LG['daigou.150'];//寄存仓库?></div>
  <div class="col-md-2 has-error" <?=$warehouse_more?'':'style="display:none"'?>>
      <?=warehouse($rs['warehouse'])?>
  </div>
  
  <div class="control-label col-md-2 right"><?=$LG['positions'];//仓位?></div>
  <div class="col-md-2 has-error">
  <?=cadd($rs['whPlace'])?>
  </div>
  
  <div class="control-label col-md-2 right"></div>
  <div class="col-md-2">
  </div>
 
</div>

                  
                 
             </div> 
          </div>  
          
 
             


             
            <!-------------------------------------------------------------------------------->
              <div class="portlet">
                 <div class="portlet-title">
                  <div class="caption"><i class="icon-reorder"></i><?=$LG['daigou.149'];//代购资料?></div>
                  <div class="tools"> <a href="javascript:;" class="collapse"></a> </div><!--缩小expand 展开collapse-->
                </div>
               <div class="portlet-body form"> 
                  <!--表单内容-->




<div class="form-group">
  <div class="control-label col-md-2 right"><?=$LG['daigou.source'];//货源?></div>
  <div class="col-md-4 has-error">
	  <?=daigou_source(spr($rs['source']))?>
  </div>
  
<span class="form-group" id="brandShow"> 
  <div class="control-label col-md-2 right"><?=$LG['brand'];//品牌?></div>
  <div class="col-md-4 has-error">
	  <?=daigou_brand($rs['brand'])?>
      
	  <span class="gray2" >
      <?=$LG['discount']//折扣?><?=spr($rs['brandDiscount'])?><?=$LG['fold']//折?>
      </span>
  </div>
</span>


</div>
    

    
    
    
    


<div class="form-group">
  
  <div class="control-label col-md-2 right"><?=$LG['daigou.types'];//品类?></div>
  <div class="col-md-4 has-error">
      <?=classify($rs['types'],2)?>
  </div>

  <div class="control-label col-md-2 right"><?=$LG['daigou.name'];//品名/货号?></div>
  <div class="col-md-4 has-error">
    <?=cadd($rs['name'])?>
  </div>

</div>

<?php if($callFrom=='manage'){?>
<!--保密资料-->

    <div class="form-group">
        <?php if(permissions('daigou_cg','','manage',1)){?>
          <div class="control-label col-md-2 right"><?=$LG['daigou.151'];//采购成本?></div>
          <div class="col-md-2">
            <?=spr($rs['procurementCost']).$rs['priceCurrency']?>
          </div>
        
          <div class="control-label col-md-2 right"><?=$LG['daigou.152'];//采购时间?></div>
          <div class="col-md-2">
            <?=DateYmd($rs['procurementTime'],1)?>
          </div>
        
          <div class="control-label col-md-2 right"><?=$LG['daigou.163'];//差额?></div>
          <div class="col-md-2">
            <?=spr(daigou_totalPay($rs)-$rs['procurementCost']).$rs['priceCurrency']?>
          </div>
        <?php }?>
    </div>
    
	<?php if(permissions('daigou_cg,daigou_ck','','manage',1)){?>
    <div class="form-group">
      <div class="control-label col-md-2 right"><?=$LG['daigou.103']//采购地址?></div>
      <div class="col-md-10">
        <?=cadd($rs['procurementAddress'])?>
      </div>
    </div>
    <?php }?>
<?php }?>
                 
    






                 
<div class="form-group">
  <div class="control-label col-md-2 right"><?=$rs['source']==1?$LG['js.4']:$LG['js.5']?></div>
  <div class="col-md-10">
<?php 
	if( $rs['source']==1 && $rs['address']){
		echo '<a href="'.cadd($rs['address']).'" target="_blank">'.cadd($rs['address']).'</a>';
	}else{
		echo cadd($rs['address']);
	}
?>    
  </div>
</div>






  <div class="form-group">
  <div class="control-label col-md-2 right"><?=$LG['daigou.autoAddPay'];//自动补款限额?></div>
  <div class="col-md-10">
    <?=$rs['autoAddPay']>0?spr($rs['autoAddPay']).$rs['priceCurrency']:$LG['daigou.17']?> 
  </div>
  </div>  



<div class="form-group">
  <div class="control-label col-md-2 right"><?=$LG['daigou.51'];//商品图?></div>
  <div class="col-md-10">
	<?php EnlargeImg(ImgAdd($rs['img']),$rs['dgid'],1)?>  </div>
</div>





<div class="form-group">
  <div class="control-label col-md-2 right"><?=$LG['mall_order.form_18'];//会员留言?></div>
  <div class="col-md-10">
   <?=cadd($rs['memberContent'])?><br>
   <span class="gray2 tooltips" data-container="body" data-placement="top" data-original-title="<?=$LG['daigou.153'];//留言时间?>"><?=DateYmd($rs['memberContentTime'])?></span>
  </div>
</div>

<div class="form-group">
  <div class="control-label col-md-2 right"><?=$LG['daigou.98'];//回复会员?></div>
  <div class="col-md-10">
    <?=cadd($rs['memberContentReply'])?><br>

    <span class="gray2 tooltips" data-container="body" data-placement="top" data-original-title="<?=$LG['daigou.154'];//上次回复?>"><?=DateYmd($rs['memberContentReplyTime'])?></span>
  </div>
</div>

                </div>
              </div>
 
 
 
 
 
 
 
 <!-----------------------------商品资料--------------------------------------------------->
<?php 
	require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/daigou/call/show_goods.php');
?>
      
      
      
      
      
                
<!-------------------------------------------------------------------------------->
              
              
  <?php if($callFrom=='manage'){?>
              <div class="portlet">
                 <div class="portlet-title">
                  <div class="caption"><i class="icon-reorder"></i><?=$LG['daigou.155'];//供应商?></div>
                  <div class="tools"> <a href="javascript:;" class="collapse"></a> </div><!--缩小expand 展开collapse-->
                </div>
               <div class="portlet-body form"> 
                  <!--表单内容-->
                  
 <div class="form-group">
  <div class="control-label col-md-2 right"><?=$LG['daigou.156'];//供应商状态?></div>
  <div class="col-md-10 has-error">
     <?=daigou_sellerStatus($rs['sellerStatus'])?><br>

     <span class="gray2 tooltips" data-container="body" data-placement="top" data-original-title="<?=$LG['function.148']?>"><?=DateYmd($rs['sellerStatusTime'])?></span>
  </div>

</div>


<div class="form-group">
  <div class="control-label col-md-2 right"><?=$LG['daigou.157'];//供应商留言?></div>
  <div class="col-md-10">
   <?=cadd($rs['sellerContent'])?><br>

   <span class="gray2 tooltips" data-container="body" data-placement="top" data-original-title="<?=$LG['daigou.153'];//留言时间?>"><?=DateYmd($rs['sellerContentTime'])?></span>
  </div>
</div>

<div class="form-group">
  <div class="control-label col-md-2 right"><?=$LG['daigou.158'];//回复供应商?></div>
  <div class="col-md-10">
    <?=cadd($rs['sellerContentReply'])?><br>
    <span class="gray2 tooltips" data-container="body" data-placement="top" data-original-title="<?=$LG['daigou.154'];//上次回复?>"><?=DateYmd($rs['sellerContentReplyTime'])?></span>
  </div>
</div>
                    
                    
                </div>
              </div>
 <?php }?>
 
 
 
             
              
              
            </div>
          </div>
        </div>
        <div align="center">

          <button type="button" class="btn btn-danger" onClick="goBack('c');" ><i class="icon-remove"></i> <?=$LG['close']?> </button>

		</div>
      </div>
      
      
      
    </div>
	</div>
</div>
</div>