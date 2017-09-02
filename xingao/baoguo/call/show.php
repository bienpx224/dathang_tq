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
						<!--表单内容-开始------------------------------------------------------------------------------------------------------>
						<div class="portlet">
							<div class="portlet-title">
								<div class="caption"><i class="icon-reorder"></i><?=$LG['baoguo.edit_form_4'];//基本信息?></div>
								<div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
							</div>
							<div class="portlet-body form" style="display: block;"> 
								<!--表单内容-->
								<div class="form-group">
									<div class="control-label col-md-1 right"><?=$LG['status'];//状态?></div>
									<div class="col-md-2">
									<?php 
									//状态显示
									$fahuo=baoguo_fahuo(1);
									require($_SERVER['DOCUMENT_ROOT'].'/xingao/baoguo/call/status_show.php');
									?>	
									</div>

									<div class="control-label col-md-1 right"><?=$LG['baoguo.op_9'];//单号?></div>
									<div class="col-md-2">
										<?=cadd($rs['bgydh'])?>
									</div>

									<div class="control-label col-md-1 right"><?=$LG['baoguo.Xcall_show_1'];//会员?></div>
									<div class="col-md-2">
										<?=cadd($rs['username'])?> <font class="gray2">(<?=cadd($rs['userid'])?>)</font>
									</div>

									<div class="control-label col-md-1 right"><?=$LG['baoguo.add_form_6'];//快递公司?></div>
									<div class="col-md-2">
										<?=cadd($rs['kuaidi'])?>
									</div>
								</div>
                                
                                
								<div class="form-group">
									<div class="control-label col-md-1 right"><?=$LG['baoguo.add_form_7'];//寄至仓库?></div>
									<div class="col-md-2">
										<?=warehouse(cadd($rs['warehouse']))?>
									</div>

									<div class="control-label col-md-1 right"><?=$LG['positions'];//仓位?></div>
									<div class="col-md-2">
										<?=cadd($rs['whPlace'])?>
									</div>

									<div class="control-label col-md-1 right"><?=$LG['baoguo.list_4'];//存放时间?></div>
									<div class="col-md-2"><?php bg_ware_days();?></div>

									<div class="control-label col-md-1 right"><?=$LG['baoguo.fx_7'];//重量?></div>
									<div class="col-md-2">
										<?=$rs['weight']>0?spr($rs['weight']).$XAwt:''?>
									</div>
								</div>
                                
                                
                                
								<div class="form-group">
									<div class="control-label col-md-1 right"><?=$LG['source'];//来源?></div>
									<div class="col-md-2">
										<?=baoguo_addSource($rs['addSource'])?>
									</div>

									<div class="control-label col-md-1 right"><?=$LG['baoguo.add_form_8'];//发货点?></div>
									<div class="col-md-2">
										<?=cadd($rs['fahuodiqu'])?>
									</div>

									<div class="control-label col-md-1 right"><?=$LG['baoguo.add_form_9'];//购物网站?></div>
									<div class="col-md-2">
										<?=wangzhan(cadd($rs['wangzhan']));?>
										<?=cadd($rs['wangzhan_other'])?>
									</div>

									<div class="control-label col-md-1 right"><?=$LG['baoguo.fahuotime'];//发货/购物日期?></div>
									<div class="col-md-2">
										<?=DateYmd($rs['fahuotime'],2)?>
									</div>
								</div>
								
								
								<div class="form-group">
									<div class="control-label col-md-1 right"><?=$LG['baoguo.Xcall_show_2'];//预报时间?></div>
									<div class="col-md-2">
									<?=DateYmd($rs['addtime']);?>
									</div>

									<div class="control-label col-md-1 right"><?=$LG['baoguo.list_9'];//入库时间?></div>
									<div class="col-md-2">
									<?=DateYmd($rs['rukutime']);?>
									</div>
								</div>
								
								
								
								
							</div>
						</div>
						
						<div class="portlet">
							<div class="portlet-title">
								<div class="caption"><i class="icon-reorder"></i><?=$LG['baoguo.op_1'];//包裹操作?></div>
								<div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
							</div>
							<div class="portlet-body form" style="display: block;">
								<div class="form-group">
		<?php  
		//底部通用内容调用
		$callFrom_show=1;
		require($_SERVER['DOCUMENT_ROOT'].'/xingao/baoguo/call/basic_show.php');
		?>	
								</div>
								
							</div>
						</div>
						
						<div class="portlet">
							<div class="portlet-title">
								<div class="caption"><i class="icon-reorder"></i><?=$LG['baoguo.edit_form_6'];//物品信息?></div>
								<div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
							</div>
							<div class="portlet-body form" style="display: block;">
							<?php wupin_show('baoguo',$rs['bgid']);//物品显示调用 ?>
							</div>
						</div>
						<!--表单内容-结束------------------------------------------------------------------------------------------------------> 
						
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