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
$pervar='daigou';require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/session.php');
$headtitle=$LG['daigou.73'];
$alonepage=2;require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/head.php');
if(!$ON_daigou){ exit("<script>alert('{$LG['daigou.45']}');goBack('uc');</script>");}


//获取,处理-----------------------------------------------------------------------------------------------
$typ=par($_REQUEST['typ']); 
$field=par($_REQUEST['field']);
$value=par($_REQUEST['value']);
$dgid=par(ToStr($_REQUEST['dgid']));
$callFrom='member';//member 会员中心

if(!CheckEmpty($value))
{
	if(!is_array($field)&&$field){$field_now=explode(",",$field);}
	$field=par($field_now[0]);
	$value=par($field_now[1]);
}
?>


<style>
html {overflow-x:hidden;}
body{min-width:0px; background-color:#fff!important; }/*覆盖template.css*/
.help-block{ padding:0px;}
</style>
<!----------------------------------------显示表单-开始------------------------------------------------>
<form action="op_save.php" method="post" class="form-horizontal form-bordered" name="xingao">
<input name="typ" type="hidden" value="<?=$typ?>">
<input name="dgid" type="hidden" value="<?=$dgid?>">
<input name="field" type="hidden" value="<?=$field?>">
<input name="value" type="hidden" value="<?=$value?>">





<?php
ob_start();//开始缓冲-------------------------

	//验证提示:在form里面显示才美观
	if($typ&&(!$field||!$dgid)){$err=1;echo('开发错误OP001');}
	
	
	//操作处理============================================================================================
	if(!$err&&$typ=='op')
	{
		if($field=='memberStatus'&&is_numeric($value))
		{
			$checkbox=1;//显示勾选
			$checkbox_where="and manageStatus<>'1' and  number>0 ";//可勾选操作条件
			$checkbox_where.="and (deliveryNumber=0 or goid<>(select goid from yundan where goid<>'' and status<=4 limit 1 ) )";//如已下单发货并且未出库，则需要等待仓库处理出库完后才能申请操作
			
			$memberStatusRequ=FeData('daigou_goods','memberStatusRequ',"dgid='{$dgid}' and (memberStatus='{$value}' and manageStatus<>'1') and memberStatusRequ<>'' {$Mmy}");
			
			$pptRequ=$LG['daigou.74'];
			if(have('2,3,4',$value,1)){$important=1;}
			
			if($value){
				//申请
				$LG['daigou.72_1']='<br>'.$LG['daigou.72_1'].'<br>';
			}else{
				//取消申请
				$LG['daigou.72_2']='';
			}
			
			?>
			<div class="form-group">
			<label class="control-label col-md-2 red2">
                <!--显示标题名称-->
                <strong><?=daigou_memberStatus($value)?></strong><?=$LG['daigou.72_1']?><?=$LG['daigou.72_2']?>
            </label>
            
			<?php if($value){?>
            <div class="col-md-10 <?=$important?'has-error':''?>">
              <textarea name="memberStatusRequ" class="form-control" rows="4" placeholder="<?=$pptRequ?>"  <?=$important?'required':''?>><?=cadd($memberStatusRequ)?></textarea>
            </div>
            <?php }?>
            
			</div>
			
			<div align="center" style="margin-top:10px;"><button type="submit"  class="btn btn-primary input-xmedium"> <i class="icon-ok"></i> <?=$LG['submit']?></button></div>
			
			<div class="xats">
			  <?php 
			  //输出收费说明
			  $serviceFee=$member_per[$Mgroupid]['dg_serviceFee_'.$value];
			  if($serviceFee)
			  {
				  $mr=FeData('member','money,currency',"userid='{$Muserid}'");
				  $ppt_momey=LGtag($LG['daigou.79_2'],'<tag1>=='.$mr['money'].$mr['currency']);
				  if($XAMcurrency!=$mr['currency']){$ppt_momey.='('.$mr['money']*daigou_exchangeBase(exchange($mr['currency'],$XAMcurrency),$Muserid).$XAmc.')';}

				  echo  '&raquo; '.LGtag($LG['daigou.79_1'],'<tag1>==<font class="red">'.$serviceFee.$XAmc.'</font>');
				  echo  '&nbsp;&nbsp;<font class="red2">'.$ppt_momey.'</font><br>';
				  
			 }
			  
			  if($value==0){
				 echo '&raquo; '.$LG['daigou.79_3'].'<br>';//取消成功会自动退回已扣费用
			  }elseif($value==2){
				 echo '&raquo; '.$LG['daigou.76'].'<br>';//必须写明要更换的颜色和尺寸等商品属性资料，否则无法给予处理！
			  }elseif($value==3){
				 echo '&raquo; '.$LG['daigou.77'].'<br>';//必须写明数量及相关要求，否则无法给予处理！(只订购本单相同属性的商品，否则请重新下单)
				 echo '&raquo; '.$LG['daigou.78'].'<br>';//如要减少数量，请申请【退货退款】
			  }elseif($value==4){
				 echo '&raquo; '.$LG['daigou.79'].'<br>';//必须写明数量及相关要求，否则无法给予处理！(全部退货时才退寄库运费)
			  }
			  echo '&raquo; '.$LG['daigou.178'].'<br>';//如已下单发货并且未出库，则需要等待仓库处理出库完后才能申请操作
			  ?>
				
			</div>
		<?php
		}else{
			echo $LG['pptError'];
		}
		?>
		<!----------------------------------------显示表单-结束------------------------------------------------>
	<?php }?>
	
	
	
	
	
	<?php
	//修改各种备注============================================================================================
	if(!$err&&$typ=='content'&&($field=='content'||$field=='memberContent'))
	{
		$ppt=$LG['Tool.ppt_2']; if($field=='content'){$ppt=$LG['Tool.ppt_1'];}
		$content=FeData('daigou',$field,"dgid='{$dgid}' {$Mmy}");
		?>
		<textarea name="value" rows="2" class="form-control"><?=cadd($content)?></textarea>
		<span class="help-block"><?=$ppt?></span>
	
	   <div align="center" style="margin-top:10px;"><button type="submit"  class="btn btn-primary input-xmedium" > <i class="icon-ok"></i> <?=$LG['save']?></button></div>
	<?php }?>


<?php
$DataCache=ob_get_contents();//得到缓冲区的数据
ob_end_clean();//结束缓存：清除并关闭缓冲区


//没有操作时,默认显示可发货的按钮
if(!$typ)
{
	$checkbox=1;//显示勾选
}




//商品列表-开始
$call_basic=1;//基本资料
$call_content=1;//会员备注
$call_memberContent=1;//会员留言
$call_memberContentReply=1;//回复会员留言
$call_sellerContent=0;//供应商留言
$call_sellerContentReply=0;//回复供应商留言
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

<?php require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/foot.php');?>
