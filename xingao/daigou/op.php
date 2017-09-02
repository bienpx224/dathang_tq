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
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');
$headtitle='操作';
$alonepage=2;require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/head.php');
if(!$ON_daigou){ exit("<script>alert('{$LG['daigou.45']}');goBack('uc');</script>"); }


//获取,处理-----------------------------------------------------------------------------------------------
$typ=par($_REQUEST['typ']); 
$field=par($_REQUEST['field']);
$value=par($_REQUEST['value']);
$dgid=par(ToStr($_REQUEST['dgid']));
$goid=spr($_REQUEST['goid']);
$callFrom='manage';


if(!CheckEmpty($value))
{
	if(!is_array($field)&&$field){$field_now=explode(",",$field);}
	$field=par($field_now[0]);
	$value=par($field_now[1]);
}
if(!$dgid){$dgid=$_SESSION["Xdgid"];}

//生成令牌密钥(为安全要放在所有验证的最后面)
$token=new Form_token_Core();
$tokenkey=$token->grante_token("daigouOP{$dgid}");
?>

<style>
html{overflow-x:hidden;}
body{margin:0px; background-color:#fff!important;  }/*有错误时,红色提示文字*/
.alert{margin:0px;}
.help-block{ padding:0px;}
</style>

<!----------------------------------------显示表单-开始------------------------------------------------>
<form action="op_save.php" method="post" class="form-horizontal form-bordered" name="xingao" >
<input name="typ" type="hidden" value="<?=$typ?>">
<input name="dgid" type="hidden" value="<?=$dgid?>">
<input name="goid" type="hidden" value="<?=$goid?>">
<input name="field" type="hidden" value="<?=$field?>">
<input name="value" type="hidden" value="<?=$value?>">
<input name="tokenkey" type="hidden" value="<?=$tokenkey?>">

<?php
ob_start();//开始缓冲-------------------------

	//验证提示:在form里面显示才美观
	if($typ&&!$dgid){echo('开发错误OP002');goto a;}
	
	if($dgid){$rs=FeData('daigou','*',"dgid='{$dgid}' {$Xwh}");}
	if($goid){$gd=FeData('daigou_goods','*',"dgid='{$rs['dgid']}' and goid='{$goid}'");}
	

	//处理会员申请============================================================================================
	if($typ=='op')
	{
		if(!$goid){echo('goid空');goto a;}
		if(!$gd['memberStatus']){echo('无需处理！ (可能已处理过或会员取消了申请)');goto a;}
		if(!daigou_per_op($gd['memberStatus'])){echo('无操作权限！');goto a;}//验证权限
	
	
		//申请查货-----------------------------------------
		if($gd['memberStatus']==1)
		{
			generalOPForm();//通用表单
		}
			
			
			
		//申请换货---------------------------------------------
		elseif($gd['memberStatus']==2)
		{
			generalOPForm();//通用表单
			?>
			 <div id="hide" style="display:none;">   
				<div class="form-group">
				  <label class="control-label col-md-2">颜色</label>
				  <div class="col-md-4">
					<select name="color" class="form-control select2me input-small"  data-placeholder="请选择" onChange="inputOther('color');" style="float:left;">
					  <?php  ClassifyAll(8,$gd['color'])?>
					  <option value="Other" <?=$gd['color']==0&&$gd['colorOther']?'selected':''?>>自填</option>
					</select>
					<input type="text" class="form-control input-small" name="colorOther" value="<?=cadd($gd['colorOther'])?>" style="float:left;<?=$gd['color']==0&&$gd['colorOther']?'':'display:none;'?>">
				  </div>
				  
				  <label class="control-label col-md-2">尺寸</label>
				  <div class="col-md-4">
					<select name="size" class="form-control select2me input-small"  data-placeholder="请选择" onChange="inputOther('size');" style="float:left;">
					  <?php  ClassifyAll(7,$gd['size'])?>
					  <option value="Other" <?=$gd['size']==0&&$gd['sizeOther']?'selected':''?>>自填</option>
					</select>
					<input type="text" class="form-control input-small" name="sizeOther" value="<?=cadd($gd['sizeOther'])?>" style="float:left; <?=$gd['size']==0&&$gd['sizeOther']?'':'display:none;'?>">
				  </div>
				</div>
                
               <?php if($gd['inventoryNumber']>0){?> 
           	   <div class="form-group">
				  <label class="control-label col-md-2">出库数量</label>
				  <div class="col-md-4">
					<input type="text" class="form-control input-small" name="inventoryNumber" onBlur="limitNumber(this,'0,<?=spr($gd['inventoryNumber'])?>','0');">
                    <span class="help-block">如果商品已入库，需要退回换货，请填写退回数量</span>
				  </div>
				  
				  <label class="control-label col-md-2"></label>
				  <div class="col-md-4">
				  </div>
				</div>
               <?php }?> 
                                
			</div>    
			<?php
		}
		
		
		
		
	
		//申请增购数量-----------------------------------------
		elseif($gd['memberStatus']==3)
		{
			generalOPForm();//通用表单
			?>
			<div id="hide" style="display:none;">   
			   <div class="form-group">
				  <label class="control-label col-md-2">新增数量</label>
				  <div class="col-md-4 has-error">
					<input type="text" class="form-control input-small" name="addNumber" value="" onBlur="limitNumber(this,'1,1000000','0');" required> 现存总数:<?=spr($gd['number'])?>
				  </div>
				  
				  <label class="control-label col-md-2">立即扣费</label>
				  <div class="col-md-4 has-error">
					<div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
					<input type="checkbox" class="toggle" name="pay" value="1"/>
					</div>
					<a href="javascript:void(0)" class=" tooltips" data-container="body" data-placement="top" data-original-title="不选择时，如果总费用未超过会员所设置的自动扣额度则自动扣，否则由会员自行支付"> <i class="icon-info-sign"></i></a>
				  </div>
				</div>
				
				<?=generalCostForm();//成本字段?>
				
			</div>
			<?php
		}
	
	
	
		//申请退货退款-----------------------------------------
		elseif($gd['memberStatus']==4)
		{
			generalOPForm();//通用表单
			?>
			<div id="hide" style="display:none;">   
			   <div class="form-group">
				  <label class="control-label col-md-2">退货数量</label>
				  <div class="col-md-10 has-error">
					<input type="text" class="form-control input-small" name="numberRet" value="" onBlur="limitNumber(this,'1,<?=spr($gd['number'])?>','0');" required>
					现存总数:<?=spr($gd['number'])?>
					<span class="help-block">
					 已退总计<?=spr($gd['numberRet'])?>
					 <span class="xa_sep"> | </span>
					 全退后,此单为【<?=daigou_Status(10)?>】
					</span>
					
				  </div>
				</div>
				
				<?=generalCostForm();//成本字段?>
				
			</div>
			<?php
		}
		
		?>
		
		<div align="center"><button type="submit" class="btn btn-primary input-xmedium" style=" margin-top:10px; margin-bottom:10px;"> <i class="icon-ok"></i> <?=$LG['submit']?>  (<?=daigou_memberStatus($gd['memberStatus'])?> 处理)</button></div>
		
		<!--预留高度-->
		<br>
		<br>
		<br>
		<br>
		
		<!----------------------------------------显示表单-结束------------------------------------------------>
	
	<?php }?>
	
	
	<?php 
	//处理会员申请 通用表单:函数不能放在IF条件里------------------------------------------
	function generalOPForm()
	{
		global $rs,$gd;
	?>
		<div style="height:20px;"><!--为了显示提示则增高--></div>
		<div class="form-group">
			<div class="control-label col-md-2 right"><strong><?=daigou_memberStatus($gd['memberStatus'])?></strong></div>
			<div class="col-md-10">
			  <?=cadd($gd['memberStatusRequ'])?>
			  <span class="gray_prompt2"> (申请时间:<?=DateYmd($gd['memberStatusTime'],1)?>)</span>
			</div>
		</div>
		
		<div class="form-group">
			<label class="control-label col-md-2">处理结果</label>
			<div class="col-md-10">
			  <div class="radio-list">
			  <?php daigou_manageStatus('',3)//不默认选择,必须手工选择.$gd['manageStatus']?>
			  </div>
			</div>
		</div>
		
		<div class="form-group">
			<label class="control-label col-md-2">回复会员</label>
			<div class="col-md-10">
			  <textarea name="memberContentReply" rows="2" class="form-control"><?=cadd($rs['memberContentReply'])?></textarea>
			</div>
		</div>
	<?php }?>
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	<?php
	//采购处理============================================================================================
	if($typ=='procurement')
	{
		permissions('daigou_cg',1,'manage','');//验证权限
		
		$rs=FeData('daigou','*',"dgid='{$dgid}' {$Xwh}");
		if(spr($rs['status'])==10){echo('此单为【'.daigou_Status(10).'】,不能再操作,如要操作请先修改为其他状态');goto a;}
		
		if(!$rs['pay']){echo('此单未支付,不能操作');goto a;}
	
		//采购-----------------------------------------
		if($value==1)
		{
			//空时表示不能再修改提交
			if(have('3,5,6',spr($rs['status']),1)){$name='采购';}
			elseif(spr($rs['status'])<6){echo('此单未支付,不能操作');goto a;}
			?>
			<div style="height:25px;"><?=$ppt?><!--为了显示提示则增高--></div>
			<div class="form-group">
			  <label class="control-label col-md-2">采购成本</label>
			  <div class="col-md-10">
				<input type="text" class="form-control input-medium tooltips" data-container="body" data-placement="top" data-original-title="包含采购运费和各种优惠折扣的总采购费用" name="procurementCost" value="<?=spr($rs['procurementCost'])?>" onBlur="limitNumber(this,'0,1000000','2');" onKeyUp="profits();" >
				<?=$rs['priceCurrency']?>
				<a href="javascript:void(0)" class=" tooltips" data-container="body" data-placement="top" data-original-title="如币种不对,请在【补款/退款】中操作或修改代购单"> <i class="icon-info-sign"></i> </a>
				
				<font id="msg" style="margin-left:20px; font-size:20px;"></font>
	
			   
			   <span class="help-block">
				  <?php if(!$rs['procurementTime']){$rs['procurementTime']=time();}?>
				  <input type="hidden" name="procurementTime" value="<?=$rs['procurementTime']?>">
				  代购付款时间:<font class="red2"><?=DateYmd($rs['procurementTime'],1)?> </font>
				  
				  <span class="xa_sep"> | </span> 会员首付时间:<?=DateYmd($rs['PayFirstTime'],1)?>
			   </span>
				<script>
				//计算利润
				function profits()
				{
					var procurementCost=Number(document.getElementsByName('procurementCost')[0].value);
					if(!procurementCost){return;}
					
					var differ=<?=daigou_totalPay($rs)?>-procurementCost;
					differ=decimalNumber(differ,2);
					if(differ>=0)
					{
						document.getElementById("msg").innerHTML='利润<font class="">'+differ+'</font><?=$rs['priceCurrency']?>';
					}else{
						document.getElementById("msg").innerHTML='亏本<font class="red">'+differ+'</font><?=$rs['priceCurrency']?>';
					}
				}
				
				$(function(){  
					profits();
				});
				</script>
				
			  </div>
			</div>
			
			
			
	
			 <div class="form-group">       
			  <label class="control-label col-md-2">采购地址</label>
			  <div class="col-md-10">
				<input type="text" class="form-control" name="procurementAddress" value="<?=cadd($rs['procurementAddress'])?>" >
			  </div>
			</div>
			<?php
			generalProForm();//通用表单
			
		}
			
			
			
		//补款/退款---------------------------------------------
		elseif($value==2)
		{
			if(!$goid){echo('goid空');goto a;}
			if(have('0,1,2,3,4,5',spr($rs['status']),1)){$name='补款/退款';}//空时表示不能再修改提交
			
			if(arrcount($dg_openCurrency)>1&&$rs['pay']){$dg_openCurrency=$rs['priceCurrency'];$ppt_pc='已支付过无法再变更币种';}//已支付时,无法再变更币种
			?>
			<div style="height:25px;"><!--为了显示提示则增高--></div>
	
			<div class="form-group">
				<label class="control-label col-md-2">新单价</label>
				<div class="col-md-10 has-error">
				  <input type="hidden" name="number" value="<?=spr($gd['number'])?>">
				  <input type="text" class="form-control input-small tooltips" data-container="body" data-placement="top" data-original-title="新单价比现单价高则补款，反之则退款" name="price" style="float:left;" value="" onKeyUp="differ();" required>
	
	
				 <?php if(arrcount($dg_openCurrency)>1){?>
					 <select name="priceCurrency" class="form-control input-small select2me" required data-placeholder="币种" style="float:left;" onChange="differ();" >
						<?=openCurrency(cadd($_POST['priceCurrency'].$rs['priceCurrency']),3)?>
					  </select>
					  <a href="javascript:void(0)" class=" tooltips" data-container="body" data-placement="top" data-original-title="必须选择该国家所用币种,否则计费错误,无法代购"> <i class="icon-info-sign"></i></a>
				 <?php }else{?>
					 <input type="hidden"  name="priceCurrency" value="<?=$dg_openCurrency?>">
					 <a href="javascript:void(0)" class=" tooltips" data-container="body" data-placement="top" data-original-title="<?=$ppt_pc?>"> <i class="icon-info-sign"></i></a>
				 <?php }?>
				  
				  <font id="msg" style="margin-left:20px;font-size:20px;">	</font> 
				  
				  <span class="clear help-block">
					  现单价:<?=spr($gd['price']).$rs['priceCurrency']?>
					  <span class="xa_sep"> | </span>
					  数量:<?=spr($gd['number'])?>
				  </span>
			  
				
				 </div>
			  </div>
			  
			  <script>
			  function differ()
			  {
				  var price=Number(document.getElementsByName('price')[0].value);
				  var priceCurrency=document.getElementsByName('priceCurrency')[0].value;
				  if(!price){return;}
				  var msg=(price-<?=spr($gd['price'])?>)*<?=$gd['number']?>;
				  var msg=decimalNumber(msg,2);
				  
				  if(priceCurrency!='<?=$rs['priceCurrency']?>')
				  {
					  document.getElementById("msg").innerHTML='币种已变更,提交后计算汇率';
				  }else if(msg>=0){
					  document.getElementById("msg").innerHTML='补款<font> '+msg+' </font>'+priceCurrency;
				  }else if(msg<0){
					  document.getElementById("msg").innerHTML='退款<font class="red"> '+msg+' </font>'+priceCurrency;
				  }
			  }
			  </script>
	
	
			 
			   <div class="form-group">
				  <label class="control-label col-md-2">立即扣费</label>
				  <div class="col-md-10">
				  
					<div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
					<input type="checkbox" class="toggle" name="pay" value="1"/>
					</div>
					<a href="javascript:void(0)" class=" tooltips" data-container="body" data-placement="top" data-original-title="不选择时，如果新价格未超过会员所设置的自动扣额度则自动扣，否则由会员自行支付<br>如果是退款此设置无作用"> <i class="icon-info-sign"></i></a>
					 
				  </div>
				</div>
				 
				
				<?=generalCostForm();//成本字段?>
				
			  
			<?php
			generalProForm();//通用表单
		}
		
		
		
	
	
		//断货退款-----------------------------------------
		elseif($value==3)
		{
			if(!$goid){echo('goid空');goto a;}
			if(have('0,1,2,3,4,5,6',spr($rs['status']),1)){$name='断货退款';}//空时表示不能再修改提交
			?>
			   <div class="form-group">
				  <label class="control-label col-md-2">退货数量</label>
				  <div class="col-md-10 has-error">
					<input type="text" class="form-control input-small" name="numberRet" value="" onBlur="limitNumber(this,'1,<?=spr($gd['number'])?>','0');" required>
					现存总数:<?=spr($gd['number'])?>
					<span class="help-block">
					 已退总数<?=spr($gd['numberRet'])?>
					 <span class="xa_sep"> | </span>
					 全退后,此商品状态自动变为【<?=daigou_Status(10)?>】
					</span>
					
				  </div>
				</div>
				
				<?=generalCostForm();//成本字段?>
				
		<?php
			generalProForm();//通用表单
		}
		
		?>
		
		<div align="center">
		<?php if($name){?>
			<button type="submit" class="btn btn-primary input-xmedium" style=" margin-top:10px; margin-bottom:10px;"> <i class="icon-ok"></i> <?=$LG['submit']?>  (<?=$name?>)</button>
		<?php }else{?>
			已处理过:只可查看,不可再修改
		<?php }?>
		</div>
		
		<!----------------------------------------显示表单-结束------------------------------------------------>
	
	<?php }?>
	
	
	
	
	
	
	
	
	<?php 
	//采购处理 通用表单:函数不能放在IF条件里------------------------------------------
	function generalProForm()
	{
		global $rs;
	?>
		<?php if($rs['memberContent']){?>
		<div class="form-group">
			<div class="control-label col-md-2 right">会员留言</div>
			<div class="col-md-9">
			  <?=cadd($rs['memberContent'])?>
			  <span class="help-block"><?=DateYmd($rs['memberContentTime'],1)?></span>
			  
			  <?php if($rs['memberContentNew']){?>
				  <input type="checkbox" class="toggle" name="read" value="1"/>已读留言
			  <?php }?>
			
			</div>
		</div>
		<?php }?>
		
		<div class="form-group">
			<label class="control-label col-md-2">回复会员</label>
			<div class="col-md-10">
			  <textarea name="memberContentReply" rows="2" class="form-control"><?=cadd($rs['memberContentReply'])?></textarea>
			</div>
		</div>
	<?php }?>
	
	
	
	
	<?php 
	//处理会员申请和采购处理 通用表单:函数不能放在IF条件里------------------------------------------
	function generalCostForm()
	{
		global $rs;
		if(have('6,7,8',spr($rs['status']),1)){
	?>
	<div class="form-group">
	  <label class="control-label col-md-2">采购成本</label>
	  <div class="col-md-10">
		<input type="text" class="form-control input-medium tooltips" data-container="body" data-placement="top" data-original-title="包含采购运费和各种优惠折扣的总采购费用" name="procurementCost" value="" onBlur="limitNumber(this,'0,1000000','2');"><?=$rs['priceCurrency']?>
	
	   <span class="help-block">
		  现成本:<font class="red2"><?=spr($rs['procurementCost']).$rs['priceCurrency']?></font>
		 (需要重填成本，以便统计利润)
	   </span>
		
	  </div>
	</div>
	<?php 
		}
	}?>
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	<?php
	//修改各种备注============================================================================================
	if($typ=='content'&&($field=='memberContentReply'||$field=='sellerContentReply'))
	{
		//验证权限
		permissions('daigou_ed,daigou_cg,daigou_hh,daigou_ch,daigou_th,daigou_zg',1,'manage','');
		$content=FeData('daigou',$field,"dgid='{$dgid}' {$Xwh}");
		
		if($field=='memberContentReply'){$name='回复会员';}
		elseif($field=='sellerContentReply'){$name='回复供应商';}
		?>
		<textarea name="value" rows="2" class="form-control"><?=cadd($content)?></textarea>
	
	   <div align="center"><button type="submit" class="btn btn-primary input-xmedium" style=" margin-top:10px; margin-bottom:10px;"> <i class="icon-ok"></i> <?=$name?></button></div>
	<?php }?>




<?php
a://跳到这个位置

$DataCache=ob_get_contents();//得到缓冲区的数据
ob_end_clean();//结束缓存：清除并关闭缓冲区


//商品列表-开始
$call_basic=1;//基本资料
$call_content=0;//会员备注
$call_memberContent=1;//会员留言
$call_memberContentReply=1;//回复会员留言
$call_sellerContent=1;//供应商留言
$call_sellerContentReply=1;//回复供应商留言
$callFrom_show=0;//显示全部留言文字内容
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/daigou/call/list_goods.php');
//商品列表-结束


if($typ&&!$gd_i){
	XAalert($LG['daigou.166'],'warning');//没有操作的商品
	
	echo "<script>setTimeout(\"goBack('op.php?dgid={$dgid}')\",'1000');</script>";
}else{
	echo $DataCache;//输出缓存
}
?>



</form>









<script>
function hide(obj)
{
	if(!document.getElementById("hide")){return;}//判断元素是否存在
	
	if(obj){document.getElementById("hide").style.display="block";	}
	else{document.getElementById("hide").style.display="none";	}
}
</script>
<?php 
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/foot.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/js/daigouJS.php');
?>
