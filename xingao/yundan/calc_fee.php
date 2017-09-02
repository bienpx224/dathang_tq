<?php
/*
软件著作权：=====================================================
软件名称：兴奥国际物流转运网站管理系统(简称：兴奥转运系统)V7.0
著作权人：广西兴奥网络科技有限责任公司
软件登记号：2016SR041223
网址：www.xingaowl.com
本系统已在中华人民共和国国家版权局注册，著作权受法律及国际公约保护！
版权所有，未购买严禁使用，未经书面许可严禁开发衍生品，违反将追究法律责任！
*/
require_once($_SERVER['DOCUMENT_ROOT'].'/public/config_top.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/public/html.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/public/function.php');


$pervar='yundan_fe';require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');
$headtitle="计算总费用";$alonepage=1;//单页形式
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/head.php');


//获取,处理
$ydid=par($_REQUEST['ydid']);
if(!$ydid){exit("<script>alert('ydid{$LG['pptError']}');goBack('c');</script>");}

//查询显示和处理==================================================================================================
$rs=FeData('yundan','*',"ydid=$ydid");//查询
if($rs['tally']==2){exit("<script>alert('该运单已销账,不能再变更费用！\\n如果要扣费请在会员账户中扣费并且“扣费原因”要留空否则不能销账');goBack('c');</script>");}
warehouse_per('ts',$rs['warehouse']);//验证可管理的仓库

$bgid=cadd($rs['bgid']);
$bg_number=arrcount($bgid);
$addSource=cadd($rs['addSource']);
$weight=spr(($_REQUEST['weight']?$_REQUEST['weight']:$rs['weight']),2,0);
$weightEstimate=spr($rs['weightEstimate']);


//会员资料
$m=FeData('member','groupid,money,currency',"userid='{$rs['userid']}'");
$member_money=$m['money'];
$groupid=$m['groupid'];
$warehouse=$rs['warehouse'];
$channel=$rs['channel'];
$country=$rs['country'];
$area=GetArea($groupid,$warehouse,$country);
require($_SERVER['DOCUMENT_ROOT'].'/xingao/yundan/call/get_price.php');//获取价格

$channel_name=channel_name($groupid,$rs['warehouse'],$rs['country'],$rs['channel']);
?>

<div class="page_ny"> 
  <!-- BEGIN PAGE HEADER-->
	<div><!--单页时去掉 class="row",不然会有横滚动条,并且form 加 style="margin:20px;"-->
		<div class="col-md-12"> 
<h3 class="page-title"> <!--<a href="javascript:history.go(-1)" title="<?=$LG['back']?>"><i class="icon-reply"></i></a> <a href="list.php" class="gray" target="_parent"><?=$LG['backList']?></a> > -->
        <a href="javascript:void(0)" onClick="javascript:window.location.href=window.location.href;" title="<?=$LG['refresh']?>" class="gray"><?=$headtitle?></a>
<small>
<?=cadd($rs['ydh'])?>
<span class="xa_sep"> | </span>
<?=yundan_Country($rs['country'])?>
<span class="xa_sep"> | </span>
<?=$channel_name?>
</small>
        </h3>
    </div>
  </div>
  <!-- END PAGE HEADER--> 
  
<!-- BEGIN PAGE CONTENT-->
 
	<form action="calc_fee_save.php" method="post" class="form-horizontal form-bordered" name="xingao"><!--删除 style="margin:20px;"-->
	<input name="ydid" type="hidden" value="<?=$rs['ydid']?>">
  <div><!-- class="tabbable tabbable-custom boxless"-->
      <div class="tab-content">
        <div class="tab-pane active" id="tab_1">
          <div class="form">
            <div class="form-body">
			
			  <!--基本信息版块-->
			  <div class="portlet">
                <div class="portlet-title">
                  <div class="caption"><i class="icon-reorder"></i>基本信息</div>
                  <div class="tools"> <a href="javascript:;" class="expand"></a></div><!--默认关闭:class="expand"-->
                </div>
                <div class="portlet-body form" style="display: none;"> <!--默认关闭:display: none;-->
                  <!--表单内容-->

					<div class="form-group">
						<div class="control-label col-md-2 right">会员</div>
						<div class="col-md-10">
							<?=cadd($rs['username'])?> (<?=cadd($rs['userid'])?>) 
						</div>
					</div>
					
					<div class="form-group">
						<div class="control-label col-md-2 right">来源</div>
						<div class="col-md-10">
						<?=yundan_addSource($rs['addSource'])?>
						
						</div>
					</div>
					<div class="form-group">
						<div class="control-label col-md-2 right">下单</div>
						<div class="col-md-10">
						<?=DateYmd($rs['addtime'],1)?>
						</div>
					</div>
	
			  
			
					<?php if($ON_country){?>
					 <div class="form-group">
					 <div class="control-label col-md-2 right">寄往国家</div>
						<div class="col-md-10">
						<?php yundan_Country(spr($rs['country']))?>
						 </div>
					  </div>
				   <?php }?>

				 
				
              </div>
			  </div>

            <!--物品信息版块-->
			 <div class="portlet">
                <div class="portlet-title">
                  <div class="caption"><i class="icon-reorder"></i>物品信息 <?=$bg_number?'共'.$bg_number.'个包裹':''?> </div>
                  <div class="tools"> <a href="javascript:;" class="expand"></a></div>
                </div>
                <div class="portlet-body form" style="display: none;">
                  <!--表单内容-->
                  <?php wupin_show('yundan',$rs['ydid']);//物品显示调用 ?>
				  
                </div>	
					<span class="help-block" style="padding:10px; ">
					 <?php 
					 //$bgid=$rs['bgid'];
					 //$groupid=FeData('member','groupid',"userid='{$rs['userid']}'");
					 $callFrom='manage';//manage member
					 require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/yundan/call/bg_hx_fh.php');//直接输出和$baoguo_hx_fee
					 ?>
					 </span>				  
              </div>

			
			  			
            <!--其他要求版块-->
			 <div class="portlet">
                <div class="portlet-title">
                  <div class="caption"><i class="icon-reorder"></i>服务要求</div>
                  <div class="tools"> <a href="javascript:;" class="collapse"></a></div>
                </div>
                <div class="portlet-body form" style="display: block;">
                  <!--表单内容-->
					
					 				  
				  <?php if($rs['insurevalue']>0){?>
				 <div class="form-group">
					<div class="control-label col-md-2 right">保价费</div>
					<div class="col-md-10">
					<?=spr($rs['insurevalue']).$XAmc?>
					(
					<?php 
					if($rs['declarevalue']>0){echo '物品价值';echo spr($rs['declarevalue']).$XAsc;}
					if($rs['insureamount']>0){echo '，物品保价';echo spr($rs['insureamount']).$XAsc;}
					?>
					)
				  </div>
				 </div>
				 <?php }?>
				  
				 <div class="form-group">
				 <div class="control-label col-md-2 right">增值服务</div>
					<div class="col-md-10">
					<?php require($_SERVER['DOCUMENT_ROOT'].'/xingao/yundan/call/value_show.php');?>
					 </div>
				  </div>
				  
				 <div class="form-group">
				 <div class="control-label col-md-2 right">说明</div>
					<div class="col-md-10">
                    <?php if($rs['op_freearr']){?>
						<?=op_freearr('name')?>:<?=op_freearr($rs['op_freearr'])?>
                        <span class="xa_sep"> | </span>
					<?php }?>
                    
                    <?php if($rs['op_free']){?>
						<?=op_free('name')?>:<?=op_free($rs['op_free'])?>
                        <span class="xa_sep"> | </span>
					<?php }?>
                    
					<?=op_overweight('name')?>:<?=op_overweight($rs['op_overweight'])?><br>
					
					<?php  
					//基本资料
					$callFrom='manage';//member 会员中心
					$call_payment=0;//费用及付款情况
					$call_basic=0;//基本资料
					$call_op=0;//操作要求
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
			</div>
			
			<div class="portlet">
                <div class="portlet-title">
                  <div class="caption"><i class="icon-reorder"></i>操作</div>
                  <div class="tools"> <a href="javascript:;" class="collapse"></a></div><!--默认关闭:class="expand"-->
                </div>
                <div class="portlet-body form" style="display: block;"> <!--默认关闭:display: none;-->
                  <!--表单内容-->
					<table class="table table-striped table-bordered table-hover">
						<thead>
							<tr>
								<th align="center">重量</th>
								<th align="center">运费</th>
								<th align="center">运费折扣</th>
								<?php if($status_on_14){?>
								<th align="center">税次</th>
								<th align="center">税费</th>
								<?php }?>
								<th align="center">体积费</th>
								<th align="center">服务费</th>
								<th align="center">仓储费</th>
								<th align="center">其他费</th>
							
								<th align="center">总费用 <?php if(spr($rs['money'])>0){echo '(之前'.spr($rs['money']).$XAmc.')';}?> </th>
							</tr>
						</thead>
						<tbody>
							<tr class="odd gradeX">
								<td align="center" valign="middle" class="has-error">
								<input type="text" class="form-control tooltips" data-container="body" data-placement="top" data-original-title="自动计算:运费、体积费、服务费、总费用 (如税费框为空则计算否则不计算税)"  name="weight" onclick="select();" onKeyUp="calc_weight();" value="<?=$weight?>" required style="width:100px;"/><?=$XAwt?> 
                                <!-- onKeyUp="calc_weight();" 如果不稳定，价格经常变动，用下面-->
                                <!--<br>
                                <button type="button" class="btn btn-xs btn-success" onClick="calc_weight();"> <i class="icon-list-alt"></i> 计费 </button>-->
                                <!--不能使用 onBlur 失去焦点时再计算,因为当之前手工改运费过了,再打开该页面默认是在重量框,点其他地方时将会自动计算,之前的运费会还原-->
								
								<span class="help-block"> 预估<?=$weightEstimate?><?=$XAwt?></span>
								</td>

								<td align="center" valign="middle">
								<input type="text" class="form-control tooltips" data-container="body" data-placement="top" data-original-title="自动计算:总费用" name="fee_transport"  onKeyUp="calc_transport();" style="width:80px;" value="<?=spr($rs['fee_transport'])?>"><?=$XAmc?>
								</td>
								
								<td align="center" valign="middle">
								<input type="text" class="form-control input-xsmall tooltips" data-container="body" data-placement="top" data-original-title="空时则用默认,填0则不打折" name="discount"  onKeyUp="calc_transport();" value="<?=spr($rs['money'])?spr($rs['discount']):spr($channel_discount)?>">
								折
								<span class="help-block" id="msg_discount"></span>
								</td>
							
								<?php if($status_on_14){?>
								<td align="center" valign="middle">
								扣<input name="tax_number" class="form-control input-xsmall tooltips" data-container="body" data-placement="top" data-original-title="填写时按此为准<br>留空时按物品税率自动计算<br> （在右侧税费框中显示结果）" type="text"  onKeyUp="calc_transport();" value="<?=spr($rs['tax_number'],2,0)?>"/>次<br>
								<span class="help-block">乘以税额<?=$channel_tax?><?=$XAmc?></span>
								</td>
                                
								<td align="center" valign="middle">
								<input name="fee_tax" class="form-control input-xsmall tooltips" data-container="body" data-placement="top" data-original-title="左边填写时则按左边为准<br>留空时按物品税率自动计算"  type="text" value="<?=spr($rs['fee_tax'],2,0)?>" onKeyUp="calc_transport();"/><?=$XAmc?><br>
								
								</td>
								<?php }?>
								
								<td align="center" valign="middle">
								<input type="text" class="form-control tooltips" data-container="body" data-placement="top" data-original-title="自动计算:总费用" name="fee_cc"  onKeyUp="calc_transport(1);" style="width:80px;" value="<?=spr($rs['fee_cc'])?>"><?=$XAmc?>
								</td>
                                
								<td align="center" valign="middle">
								<input type="text" class="form-control tooltips" data-container="body" data-placement="top" data-original-title="自动计算:总费用"  name="fee_service"  onKeyUp="calc_transport();" style="width:80px;" value="<?=spr($rs['fee_service'])?>"><?=$XAmc?>
								</td>
                                
								<td align="center" valign="middle">
								<input type="text" class="form-control tooltips" data-container="body" data-placement="top" data-original-title="自动计算:总费用 (代购下单才计算,包裹下单时已在取出仓储时收费)<br>留空时按今天日期自动计算"  name="fee_ware"  onKeyUp="calc_transport();" style="width:80px;" value="<?=spr($rs['fee_ware'],2,0)?>"><?=$XAmc?>
								</td>
							
								<td align="center" valign="middle">
								<input type="text" class="form-control tooltips" data-container="body" data-placement="top" data-original-title="自动计算:总费用" name="fee_other"  onKeyUp="calc_transport();" style="width:80px;" value="<?=spr($rs['fee_other'])?>"><?=$XAmc?>
								</td>
								
								<td align="center" valign="middle" class="has-error">
								<input type="text" class="form-control" name="money" required  style="width:100px;" value="<?=spr($rs['money'])?>"><?=$XAmc?>
								
<a href="javascript:void(0)" class=" popovers" data-trigger="hover" data-placement="top"  data-content="计费公式：<?=fee_gongshi($val)?><br>
<?php 
//显示所用价格表
$val=$member_warehouse[$groupid][$warehouse][$area]['channel_'.$channel.'_formula'];
echo '重量取整： ';
if(!$val){echo weight_int(1,$member_warehouse[$groupid][$warehouse][$area]['channel_'.$channel.'_weight_int']);}//重量向上取整说明
?>
"> <i class="icon-info-sign"></i> </a>
															
								</td>
							</tr>
							<tr class="odd gradeX">
							  <td align="center" valign="middle">
                              <strong>尺寸</strong>
                              </td>
							  <td colspan="10" valign="middle"> 
长<input name="cc_chang" type="text"  value="<?=spr($rs['cc_chang'],1)?>" class="form-control input-xsmall tooltips" data-container="body" data-placement="top" data-original-title="自动计算:运费、体积费、服务费、总费用 (如税费框为空则计算否则不计算税)"  onKeyUp="calc_weight();"  /><?=$XAsz?>
*
宽<input name="cc_kuan" type="text" value="<?=spr($rs['cc_kuan'],1)?>" class="form-control input-xsmall tooltips" data-container="body" data-placement="top" data-original-title="自动计算:运费、体积费、服务费、总费用 (如税费框为空则计算否则不计算税)"  onKeyUp="calc_weight();"  /><?=$XAsz?>
*
高 <input name="cc_gao" type="text" value="<?=spr($rs['cc_gao'],1)?>" class="form-control input-xsmall tooltips" data-container="body" data-placement="top" data-original-title="自动计算:运费、体积费、服务费、总费用 (如税费框为空则计算否则不计算税)"  onKeyUp="calc_weight();"  /><?=$XAsz?>


<?php if($channel_cc_exceed>0&&$channel_cc_formula){?>
<a href="javascript:void(0)" class=" popovers" data-trigger="hover" data-placement="top"  data-content="体积重大于重量时并且超过<?=$channel_cc_exceed.'立方'.$XAsz?>时,按体积重作为重量计费,体积重公式: <strong><?=cc_formula($channel_cc_formula)?></strong>"> 体积重 </a>
<?php }?>
                 
	 </td>
						  </tr>
						</tbody>
					</table>
				<div class="help-block xats"> 
					
					&raquo; 
					<?php if (spr($rs['payment'])>0){?>
					之前总扣：
					<font class="red"><?=spr($rs['payment'])?></font><?=$XAmc?>
					(最后扣费:<?=DateYmd($rs['payment_time'],1)?>)
					<?php }?>
					会员账户<strong><?=$member_money?></strong><?=$m['currency']?><br>
                    
                     &raquo; 如果无法用电子秤自动提交，请把电子称自动提交功能延时1-5秒(秒数视您网速而定)；电子称需要支持即插即用<br>					
				</div>				 

				  <div class="form-group"><br></div>
                 <div class="form-group">
					<label class="control-label col-md-2">仓位</label>
					<div class="col-md-10">
					<?php 
					$whPlace=cadd($rs['whPlace']);
					if(!$whPlace)
					{
						$whPlace=FeData('yundan','whPlace',"userid='$rs[userid]' order by moneytime desc,ydid desc");//查询
					}
					?>
					  <input type="text" class="form-control input-small" name="whPlace" value="<?=$whPlace?>">
					<span class="help-block">如果未填写过,则自动获取该会员上一次的存放位置</span>
				  </div>
				 </div>
				 
				 
				  <div class="form-group"><br></div>


				 

				 <?php if ($off_integral&&$integral_yundan>0){ ?>
				 <div class="form-group">
					<label class="control-label col-md-2">是否送分</label>
					<div class="col-md-10">
					 <div class="radio-list">
					  <label class="radio-inline">
					  <input name="integral_to" type="radio" value="1"  <?=$rs['integral_to']||!spr($rs['money'])?'checked':''?>>
					  送
					  </label>
					  
					  <label class="radio-inline">
					  <input name="integral_to" type="radio" value="0"  <?=!$rs['integral_to']&&spr($rs['money'])?'checked':''?>>
					  不送 
					  </label>
					  </div>
					  <span class="help-block">当前系统设置:<?=$integral_4?'用积分消费时还送积分':'用积分消费时不送积分'?></span>
				  </div>
				 </div>
				 <?php }?>
				 
				<div class="form-group">
					<label class="control-label col-md-2">计费说明</label>
					<div class="col-md-10">
						<textarea  class="form-control" rows="3" name="money_content"><?=cadd($rs['money_content'])?>
</textarea>
					</div>
				</div>
                
                <div class="form-group">
					<label class="control-label col-md-2">自动扣费</label>
					<div class="col-md-10">
                       <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                        <input type="checkbox" class="toggle" name="kffs" value="1"  <?php if($rs['kffs']){ echo 'checked';}?> />
                       </div>
					  <span class="help-block"><?php if(!$rs['kffs']){echo '会员不同意自动扣费';}?></span>
					</div>
				</div>
                
                
                
                
				<div class="form-group">
					<label class="control-label col-md-2">变更状态</label>
					<div class="col-md-10">
                    
                      <select  class="form-control input-medium select2me" data-placeholder="请选择" name="status_up">
                        <option></option>
                       <?php 
					   $status_up='';if(spr($rs['status'])!=1&&spr($rs['status'])<4){$status_up=4;}
					   yundan_Status($status_up);
					   ?>
                      </select>

					  <span class="help-block">
                    	 &raquo;  当前状态：<?=status_name(spr($rs['status']))?><br>
                    	 &raquo;  扣费成功才变更到所选状态，否则变更到“<?=status_name(3)?>”，不选则保留原状态<br>
                      </span>
					</div>
				</div>
				

                <div class="form-group">
                  <label class="control-label col-md-2">添加派送单号</label>
                  <div class="col-md-10">
                    <select  class="form-control input-medium select2me" data-placeholder="请选择" name="gnkd">
                    <option></option>
                    <?php yundan_gnkd(cadd($rs['gnkd']) )?>
                    </select>
                    <input type="text" class="form-control input-medium" name="gnkdydh" value="<?=cadd($rs['gnkdydh']) ?>" placeholder="快递单号">
                    <span class="help-block">
                     <input name="gnkd_op2" type="checkbox" value="1"  <?php if(!CheckEmpty($_SESSION['gnkd_op2'])||$_SESSION['gnkd_op2']){echo "checked";}?>/>
                    扣费成功才添加
                  <br>
                   <input name="gnkd_op1" type="checkbox" value="1"  <?php if($_SESSION['gnkd_op1']){echo "checked";}?>/>
                    已填过单号的也强行修改为此单号
                    </span>
                  </div>
                </div>
                
                <div class="form-group">
					<label class="control-label col-md-2">打印</label>
					<div class="col-md-10">
					 <div class="radio-list">
					<?php if($rs['addSource']==2){$print_tem='tem_listing';}else{$print_tem=$_SESSION['print_tem'];}?>
					<select class="form-control select2me input-medium" data-placeholder="打印模板" name="print_tem">
					<option></option>
					<?php yundan_print($print_tem,1)?>
					</select>
                    <span class="help-block">
                    <input type="checkbox" name="print_op1" value="1"  <?php if(!CheckEmpty($_SESSION['print_op1'])||$_SESSION['print_op1']){ echo 'checked';}?>>扣费成功才打印面单，否则打印清单
                    </span>
					  </div>
					</div>
				</div>

                
				<div class="form-group">
					<label class="control-label col-md-2">管理备注</label>
					<div class="col-md-10">
						<textarea  class="form-control tooltips" data-container="body" data-placement="top" data-original-title="此备注只有后台可见" rows="3" name="manage_content"><?=$rs['manage_content']?cadd($rs['manage_content']):'审核员'.$Xuserid?></textarea>
					
					</div>
				</div>



              </div>
			  </div>			 
			  
          </div>
          </div>
        </div>
		
        
        
                
        
        
                
<!--提交按钮固定--> 
<style>body{margin-bottom:50px !important;}</style><!--后台不用隐藏,增高底部高度-->
<div align="center" class="fixed_btn" id="Autohidden">





		  <button type="submit" class="btn btn-primary input-small" id="openSmt1" disabled onClick="return ConfirmFee();"><i class="icon-ok"></i> <?=$LG['submit']?> </button>
		  <button type="reset" class="btn btn-default" style="margin-left:30px;"> <?=$LG['reset']?> </button>
   		 </div>
      </div>
      
      
    
    </div>
    
  </form>

</div>

<script language="javascript">
//默认光标在某个INPUT停留,可不用放在foot.php后面,要确保有那个ID,否则会停止执行后面的其他JS
$(function(){  
	document.getElementsByName("weight")[0].focus(); //停留
	document.getElementsByName("weight")[0].select(); //全选
});


//验证费用是否是退费
function ConfirmFee()
{
   if(parseFloat(document.getElementsByName("money")[0].value) < <?=spr($rs['payment'])?>)
   {
	   if(confirm("该费用低于已扣费用，将退给会员已多扣的费用\r确定要提交吗？"))
	   {
		 return true;
	   }else{
		 return false;
	   }
   }
   return true;
}

</script>



<script>
<?php if($_REQUEST['weight']>0){?>
	$(function(){  calc_weight();  });
<?php }?>

//按重量框计算
function calc_weight()
{
	<?php require($_SERVER['DOCUMENT_ROOT'].'/xingao/yundan/call/calc_varJS.php');?>
	fee_service='';
	$.ajax({
        type: "POST",
        cache: false,
        data: 'yf=1&warehouse='+warehouse+'&country='+country+'&weight='+weight+'&op_bgfee1='+op_bgfee1+'&op_bgfee2='+op_bgfee2+'&op_wpfee1='+op_wpfee1+'&op_wpfee2='+op_wpfee2+'&op_ydfee1='+op_ydfee1+'&op_ydfee2='+op_ydfee2+'&channel='+channel+'&userid='+userid+'&insurevalue='+insurevalue+'&baoguo_hx_fee='+baoguo_hx_fee+'&bg_number='+bg_number+'&tax_number='+tax_number+'&fee_tax='+fee_tax+'&fee_service='+fee_service+'&fee_other='+fee_other+'&discount='+discount+'&fee_cc='+fee_cc+'&fee_ware='+fee_ware+'&cc_chang='+cc_chang+'&cc_kuan='+cc_kuan+'&cc_gao='+cc_gao+'&goid='+goid+'&go_number='+go_number+'&gdid='+gdid+'&go_number='+go_number+'&wp_number='+wp_number+'',
        async: false,//true导步处理;false为同步处理
        url: "/xingao/yundan/call/calc.php",
        success: function (data) 
		{
			arr = data;
		}
    });

				
	arr =arr.split(",");//字符串转数组
	document.getElementsByName('money')[0].value=arr[0];//总费用
	
	var zhi=arr[1];if (typeof(zhi) == "undefined"){zhi=arr[0];}
	document.getElementsByName('fee_transport')[0].value=zhi;//单运费
	
	var zhi=arr[2];if (typeof(zhi) == "undefined"){zhi='';}//必须zhi='';
	document.getElementsByName('discount')[0].value=zhi;//打多少折扣

	var zhi=arr[3];if (typeof(zhi) == "undefined"){zhi=arr[0];}
	document.getElementById('msg_discount').innerHTML=zhi;//单运费折扣
	
	var zhi=arr[4];if (typeof(zhi) == "undefined"){zhi=arr[0];}
	document.getElementsByName('fee_service')[0].value=zhi;//单服务费
	
	var zhi=arr[5];if (typeof(zhi)!= "undefined"){
		document.getElementsByName('fee_cc')[0].value=zhi;//单体积费
	}
	var zhi=arr[7];if (typeof(zhi)!= "undefined"){
		document.getElementsByName('fee_ware')[0].value=zhi;//单仓储费
	}
	
	if($('[name="fee_tax"]').length>0){
		var zhi=arr[6];if (typeof(zhi) == "undefined"){zhi=arr[0];}
		document.getElementsByName('fee_tax')[0].value=zhi;//税费
	}
				
				
}



//按运费框计算
function calc_transport(calc_typ) 
{
	<?php require($_SERVER['DOCUMENT_ROOT'].'/xingao/yundan/call/calc_varJS.php');?>
	
	$.ajax({
        type: "POST",
        cache: false,
        data: 'yf=1&calc_typ='+calc_typ+'&warehouse='+warehouse+'&country='+country+'&weight='+weight+'&fee_transport='+fee_transport+'&op_bgfee1='+op_bgfee1+'&op_bgfee2='+op_bgfee2+'&op_wpfee1='+op_wpfee1+'&op_wpfee2='+op_wpfee2+'&op_ydfee1='+op_ydfee1+'&op_ydfee2='+op_ydfee2+'&channel='+channel+'&userid='+userid+'&insurevalue='+insurevalue+'&baoguo_hx_fee='+baoguo_hx_fee+'&bg_number='+bg_number+'&tax_number='+tax_number+'&fee_tax='+fee_tax+'&fee_service='+fee_service+'&fee_other='+fee_other+'&discount='+discount+'&fee_cc='+fee_cc+'&fee_ware='+fee_ware+'&cc_chang='+cc_chang+'&cc_kuan='+cc_kuan+'&cc_gao='+cc_gao+'&goid='+goid+'&go_number='+go_number+'&gdid='+gdid+'&go_number='+go_number+'&wp_number='+wp_number+'',
        async: false,//true导步处理;false为同步处理
        url: "/xingao/yundan/call/calc.php",
        success: function (data) 
		{
			arr = data;
		}
    });

	arr =arr.split(",");//字符串转数组
	document.getElementsByName('money')[0].value=arr[0];//总费用
	
	var zhi=arr[2];if (typeof(zhi) == "undefined"){zhi='';}//必须zhi='';
	document.getElementsByName('discount')[0].value=zhi;//打多少折扣
	
	var zhi=arr[3];if (typeof(zhi) == "undefined"){zhi=arr[0];}
	document.getElementById('msg_discount').innerHTML=zhi;//单运费折扣
	
	var zhi=arr[5];if (typeof(zhi)!= "undefined"){
		document.getElementsByName('fee_cc')[0].value=zhi;//单体积费
	}
	
	var zhi=arr[7];if (typeof(zhi)!= "undefined"){
		document.getElementsByName('fee_ware')[0].value=zhi;//单仓储费
	}
	
	if($('[name="fee_tax"]').length>0){
		var zhi=arr[6];if (typeof(zhi) == "undefined"){zhi=arr[0];}
		document.getElementsByName('fee_tax')[0].value=zhi;//税费
	}
				
}
</SCRIPT>

<?php
$CountryRequired=1;//yundanJS.php 参数:国家是否必选
require_once($_SERVER['DOCUMENT_ROOT'].'/js/yundanJS.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/foot.php');
?>
