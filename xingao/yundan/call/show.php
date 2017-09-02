<?php 
$bgid=$rs['bgid'];
$bg_number=arrcount($bgid);

//图片扩大插件
require_once($_SERVER['DOCUMENT_ROOT'].'/public/enlarge/call.html');
$mr=FeData('member','groupid,useric',"userid='{$rs['userid']}'");
?>

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
								<div class="caption"><i class="icon-reorder"></i><?=$LG['yundan.Xcall_show_1'];//运单信息?></div>
								<div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
							</div>
							<div class="portlet-body form" style="display: block;"> 
								<!--表单内容-->
								<div class="form-group">
									<div class="control-label col-md-1 right"><?=$LG['yundan.list_6'];//运单号?></div>
									<div class="col-md-2"><?=cadd($rs['ydh'])?></div>

									<div class="control-label col-md-1 right"><?=$LG['yundan.Xcall_show_2'];//批次号?></div>
									<div class="col-md-2"><?=cadd($rs['lotno'])?></div>

									<div class="control-label col-md-1 right"><?=$LG['yundan.Xcall_show_3'];//托盘号?></div>
									<div class="col-md-5"><?=classify($rs['classid'])?></div>
								</div>
                                
                                
								<div class="form-group">


									<div class="control-label col-md-1 right"><?=$LG['status'];//状态?></div>
									<div class="col-md-2">
									<a href="/yundan/status.php?ydh=<?=$rs['ydh']?>" target="_blank">
									<?=status_name(spr($rs['status']),$rs['statustime'],$rs['statusauto'])?>	
									</a>
									</div>


									<div class="control-label col-md-1 right"><?=$LG['yundan.country'];//寄往国家?></div>
									<div class="col-md-2">
										<?=yundan_Country(spr($rs['country']))?>
									</div>

									<div class="control-label col-md-1 right"><?=$LG['yundan.form_19'];//运输渠道?></div>
									<div class="col-md-5">
										<?=channel_name($mr['groupid'],$rs['warehouse'],$rs['country'],$rs['channel'])?>
									</div>
								</div>
  
								<div class="form-group">
									<div class="control-label col-md-1 right"><?=$LG['yundan.list_34'];//第三方单号?></div>
									<div class="col-md-2"><?=cadd($rs['dsfydh'])?></div>

									<div class="control-label col-md-1 right"><?=$LG['yundan.gwkdydh'];//寄库单号?></div>
									<div class="col-md-2"><?=cadd($rs['gwkdydh'])?></div>

									<div class="control-label col-md-1 right"><?=$LG['yundan.Xcall_show_4'];//派送快递?></div>
									<div class="col-md-5"><?=$expresses[$rs['gnkd']]?> <?=cadd($rs['gnkdydh'])?></div>
								</div>
                                
								<div class="form-group">
									<div class="control-label col-md-1 right">HS/HG</div>
									<div class="col-md-2"><?=cadd($rs['hscode'])?></div>
                                    
									<div class="control-label col-md-1 right"></div>
									<div class="col-md-2"></div>
                                    

									<div class="control-label col-md-1 right"><?=$LG['yundan.Xcall_show_5'];//会员?></div>
									<div class="col-md-5">
										<?=cadd($rs['username'])?> 
                                        <font class="gray2">(<?=$LG['Muserid']?>:<?=cadd($rs['userid'])?> <?=$LG['Museric']?>:<?=$mr['useric']?>)</font>
									</div>
                              
								</div>
                                
                                <?php if($callFrom=='manage'){?>
                                <!--只有后台可见,不用译语种-->
                                <div class="form-group">
									<div class="control-label col-md-1 right">最后打印拣货单</div>
									<div class="col-md-2"><?=DateYmd($rs['printPickTime'])?></div>
                                    
									<div class="control-label col-md-1 right">最后打印打包单</div>
									<div class="col-md-2"><?=DateYmd($rs['printPackTime'])?></div>
                                    

									<div class="control-label col-md-1 right">最后打印面单</div>
									<div class="col-md-5"><?=DateYmd($rs['printExpTime'])?></div>
								</div>
                                <?php }?>
                                
								
							</div>
						</div>
						
						<div class="portlet">
							<div class="portlet-title">
								<div class="caption"><i class="icon-reorder"></i><?=$LG['yundan.form_9'];//基本信息?></div>
								<div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
							</div>
							<div class="portlet-body form" style="display: block;">
								<div class="form-group">
								<?php  
								//基本资料
								//$callFrom='member';//member 会员中心
								$call_payment=1;//费用及付款情况
								$call_basic=1;//基本资料
								$call_op=1;//操作要求
								$call_baoguo=0;//包裹
								$call_goodsdescribe=0;//货物
								$call_content=1;//备注
								$call_reply=1;//回复
								$callFrom_show=1;//显示全部文字内容
								require($_SERVER['DOCUMENT_ROOT'].'/xingao/yundan/call/basic_show.php');
								?>	
								</div>
								
							</div>
						</div>
						

<?php if($rs['addSource']==1){?>
<!--版块-->
<div class="portlet">
  <div class="portlet-title">
	<div class="caption"><i class="icon-reorder"></i>
	<?=$LG['baoguo.show_2']//包裹信息?>
    <?=LGtag($LG['yundan.form_58'],'<tag1>=='.arrcount($rs['bgid']))?> 
    </div>
	<div class="tools"> <a href="javascript:;" class="collapse"></a> </div><!--缩小expand 展开collapse-->
  </div>
  <div class="portlet-body form" style="display: block;"> <!--缩小none 展开block-->
	<!--表单内容-开始-->
	<?php yundan_bg_list($rs['bgid'],$callFrom='manage');?>
	<!--表单内容-结束-->
  </div>
</div>
<?php }?>
						
                        
                        <div class="portlet">
							<div class="portlet-title">
								<div class="caption"><i class="icon-reorder"></i><?=$LG['yundan.Xcall_show_8'];//物品信息?></div>
								<div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
							</div>
							<div class="portlet-body form" style="display: block;">
                            
                            <?php wupin_show('yundan',$rs['ydid']);//物品显示调用 ?>
                            
							<div class="form-group">
								<div class="control-label col-md-0 right"><?=$LG['yundan.Xcall_show_6'];//货物描述:?></div>
								<div>
								<?=cadd($rs['goodsdescribe'])?>
								</div>
							</div>

							</div>
						</div>
						

						 <!--收件人信息版块-->	
                        <a name="s_name"></a>
						<div class="portlet">
							<div class="portlet-title">
							  <div class="caption"><i class="icon-reorder"></i><?=$LG['yundan.form_38'];//收件人信息?></div>
							  <div class="tools"> <a href="javascript:;" class="collapse"></a></div>
							</div>
							<div class="portlet-body form" style="display:block;">
							  <!--表单内容-->
						
						 <div class="form-group">
							<div class="control-label col-md-2 right"><?=$LG['yundan.s_name'];//收件人姓名?></div>
							<div class="col-md-2">
							<?=cadd($rs['s_name'])?>
							 </div>
 
 							<div class="control-label col-md-2 right"><?=$LG['yundan.Xcall_show_7'];//手机所属地区?></div>
							<div class="col-md-2">
							   <?=mobileCountry(cadd($rs['s_mobile_code']))?>
							 </div>
						 
							<div class="control-label col-md-2 right"><?=$LG['yundan.form_42'];//手机号码?></div>
							<div class="col-md-2" required>
								<?=cadd($rs['s_mobile'])?>
							 </div>
						  </div>
                          
                          
						 <div class="form-group">
							<div class="control-label col-md-2 right"><?=$LG['yundan.s_tel'];//收件人固话?></div>
							<div class="col-md-2">
							  <?=cadd($rs['s_tel'])?>
							 </div>
						 
							<div class="control-label col-md-2 right"><?=$LG['yundan.s_zip'];//收件人邮编?></div>
							<div class="col-md-2">
							  <?=cadd($rs['s_zip'])?>
							 </div>
						  </div>
						 
						 <div class="form-group">
							<div class="control-label col-md-2 right"><?=$LG['yundan.s_add_shengfen'];//收件人省份?></div>
							<div class="col-md-2">
							  <?=cadd($rs['s_add_shengfen'])?>
							 </div>
						 
							<div class="control-label col-md-2 right"><?=$LG['yundan.s_add_chengshi'];//收件人城市?></div>
							<div class="col-md-2">
							  <?=cadd($rs['s_add_chengshi'])?>
							 </div>
						 
							<div class="control-label col-md-2 right"><?=$LG['yundan.s_add_quzhen'];//收件人区镇?></div>
							<div class="col-md-2">
							 <?=cadd($rs['s_add_quzhen'])?>
							 </div>
						  </div>
                          
                          
						 <div class="form-group">
							<div class="control-label col-md-2 right"><?=$LG['yundan.s_add_dizhi'];//具体地址?></div>
							<div class="col-md-10" required>
							 <?=cadd($rs['s_add_dizhi'])?>
							 </div>
						  </div>
						  
						<?php if($off_shenfenzheng&&channelPar($rs['warehouse'],$rs['channel'],'shenfenzheng')){?>			  
						 <div class="form-group">
							<br>
						  </div>
						 <div class="form-group">
							<div class="control-label col-md-2 right"><?=$LG['yundan.s_shenfenhaoma'];//身份证号码?></div>
							<div class="col-md-2">
							  <?=cadd($rs['s_shenfenhaoma'])?>
							 </div>
						  
							<div class="control-label col-md-2 right"><?=$LG['yundan.s_shenfenimg_z_add'];//身份证正面?></div>
							<div class="col-md-2">
							<?php if($rs['s_shenfenimg_z']){?>
							<a href="<?=cadd($rs['s_shenfenimg_z'])?>" target="_blank"><img src="<?=cadd($rs['s_shenfenimg_z'])?>" width="200" height="150"/></a>
							<?php }?>
							 </div>
						 
							<div class="control-label col-md-2 right"><?=$LG['yundan.s_shenfenimg_b_add'];//身份证背面?></div>
							<div class="col-md-2">
							<?php if($rs['s_shenfenimg_b']){?>
							<a href="<?=cadd($rs['s_shenfenimg_b'])?>" target="_blank"><img src="<?=cadd($rs['s_shenfenimg_b'])?>" width="200" height="150"/></a>
							<?php }?>
							 </div>
						  </div>
						<?php }?>	
						
							</div>
						  </div>			  
						
						<!--发件人信息版块-->
						 <div class="portlet">
							<div class="portlet-title">
							  <div class="caption"><i class="icon-reorder"></i><?=$LG['yundan.form_46'];//发件人信息?></div>
							  <div class="tools"> <a href="javascript:;" class="<?=$off_fajian?'collapse':'expand'?>"></a></div><!--默认关闭:class="expand"-->
							</div>
							<div class="portlet-body form" style="display:<?=$off_fajian?'block':'none'?>;"> <!--默认关闭:display: none;-->
							  <!--表单内容-->
						 <div class="form-group">
							<div class="control-label col-md-2 right"><?=$LG['yundan.f_name'];//发件人姓名?></div>
							<div class="col-md-2">
							  <?=cadd($rs['f_name'])?>
							 </div>
						  
							<div class="control-label col-md-2 right"><?=$LG['yundan.Xcall_show_7'];//手机所属地区?></div>
							<div class="col-md-2">
							   <?=mobileCountry(cadd($rs['f_mobile_code']))?>
							 </div>
						  
							<div class="control-label col-md-2 right"><?=$LG['yundan.form_42'];//手机号码?></div>
							<div class="col-md-2">
							 <?=cadd($rs['f_mobile'])?>
							 </div>
						  </div>
                          
                          
						 <div class="form-group">
							<div class="control-label col-md-2 right"><?=$LG['yundan.f_tel'];//发件人固话?></div>
							<div class="col-md-2">
							 <?=cadd($rs['f_tel'])?>
							 </div>
						 
                         
							<div class="control-label col-md-2 right"><?=$LG['yundan.f_zip'];//发件人邮编?></div>
							<div class="col-md-2">
							 <?=cadd($rs['f_zip'])?>
							 </div>
						  </div>
						 
						 <div class="form-group">
							<div class="control-label col-md-2 right"><?=$LG['yundan.f_add_shengfen'];//发件人省份?></div>
							<div class="col-md-2">
							 <?=cadd($rs['f_add_shengfen'])?>
							 </div>
						 
                         
							<div class="control-label col-md-2 right"><?=$LG['yundan.f_add_chengshi'];//发件人城市?></div>
							<div class="col-md-2">
							  <?=cadd($rs['f_add_chengshi'])?>
							 </div>
						
                        
							<div class="control-label col-md-2 right"><?=$LG['yundan.f_add_quzhen'];//发件人区镇?></div>
							<div class="col-md-2">
							  <?=cadd($rs['f_add_quzhen'])?>
							 </div>
						  </div>
                          
                          
						 <div class="form-group">
							<div class="control-label col-md-2 right"><?=$LG['yundan.s_add_dizhi'];//具体地址?></div>
							<div class="col-md-10">
							  <?=cadd($rs['f_add_dizhi'])?>
							 </div>
						  </div>
										  
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