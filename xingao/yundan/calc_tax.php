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


$pervar='yundan_ta';require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');
$headtitle="计算税费";$alonepage=1;//单页形式
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/head.php');

//获取,处理
$ydid=par($_REQUEST['ydid']);
if(!$ydid){exit("<script>alert('ydid{$LG['pptError']}');goBack('c');</script>");}

//查询显示和处理==================================================================================================
$rs=FeData('yundan','*',"ydid=$ydid");//查询
if($rs['tally']==2){exit("<script>alert('该运单已销账,不能再变更费用！\\n如果要扣费请在会员账户中扣费并且“扣费原因”要留空否则不能销账');goBack('c');</script>");}
warehouse_per('ts',$rs['warehouse']);//验证可管理的仓库

//会员资料
$m=FeData('member','groupid,money,currency',"userid='{$rs['userid']}'");
$member_money=$m['money'];
$groupid=$m['groupid'];
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
<?=channel_name($groupid,$rs['warehouse'],$rs['country'],$rs['channel'])?>
</small>
        </h3>
    </div>
  </div>
  <!-- END PAGE HEADER--> 
  
<!-- BEGIN PAGE CONTENT-->
 
	<form action="calc_tax_save.php" method="post" class="form-horizontal form-bordered" name="xingao"><!--删除 style="margin:20px;"-->
	<input name="lx" type="hidden" value="fee">
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

			<div class="portlet">
                <div class="portlet-title">
                  <div class="caption"><i class="icon-reorder"></i>操作</div>
                  <div class="tools"> <a href="javascript:;" class="collapse"></a></div><!--默认关闭:class="expand"-->
                </div>
                <div class="portlet-body form" style="display: block;"> <!--默认关闭:display: none;-->
                  <!--表单内容-->
				 <div class="form-group">
					<label class="control-label col-md-2">总税费</label>
					<div class="col-md-10 has-error">
					  <input type="text" class="form-control input-small" name="tax_money" required value="<?=spr($rs['tax_money'])?>"><?=$XAmc?>
				
					<div class="help-block"> 
						
						<?php if (spr($rs['tax_payment'])>0){?>
						&raquo; 
						之前总扣:
						<font class="red"><?=spr($rs['tax_payment'])?></font><?=$XAmc?>
						(最后扣费:<?=DateYmd($rs['tax_payment_time'],1)?>)
						<br>
						<?php }?>
	
						&raquo; 这里税费与会员组渠道的税额无关<br>
						&raquo; 税费不能用积分抵消，并且不送积分<br>	
						
						&raquo; 会员账户<?=$member_money?><?=$m['currency']?><br>
					</div>
				  </div>

				 </div>
				 
				 <div class="form-group">
					<label class="control-label col-md-2">税费扫描件</label>
					<div class="col-md-10">
<?php 
//文件上传配置
$uplx='img';//img,file
$uploadLimit='10';//允许上传文件个数(如果是单个，此设置无法，默认1)
$inputname='tax_img[]';//保存字段名，多个时加[]

$off_water=0;//水印(不手工设置则按后台设置)
$off_narrow=1;//是否裁剪
//$img_w=$certi_w;$img_h=$certi_h;//裁剪尺寸：证件
$img_w=$other_w;$img_h=$other_h;//裁剪尺寸：通用
//$img_w=2500;$img_h=1000;//裁剪尺寸：指定
include($_SERVER['DOCUMENT_ROOT'].'/public/uploadify/call.php');
?>
				  </div>
				 </div>
				  <div class="form-group"><br></div>
				 
				
				<div class="form-group">
					<label class="control-label col-md-2">变更状态</label>
					<div class="col-md-10">
						<div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                        <input type="checkbox" class="toggle" name="status_up" value="1"  checked />
                      </div>
					  <span class="help-block">当前状态在“<?=status_name(14)?>”之前时才变更并且不是“<?=status_name(1)?>”</span>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-md-2">操作类型</label>
					<div class="col-md-10">
					 <div class="radio-list">
					  <label class="radio-inline">
					  <input name="kffs" type="radio" value="0" <?php if(!$rs['kffs']){ echo 'checked';}?>>
					  只保存 
					  </label>
					  
					  <label class="radio-inline">
					  <input name="kffs" type="radio" value="1" <?php if($rs['kffs']){ echo 'checked';}?>>
					  保存并扣费
					  </label>
					  </div>
					  <span class="help-block"><?php if($rs['kffs']){ echo '会员同意自动扣费';}else{echo '会员要求手动支付';}?></span>
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





		  <button type="submit" class="btn btn-primary input-small" id="openSmt1" disabled  onClick="return ConfirmFee();"><i class="icon-ok"></i> <?=$LG['submit']?> </button>
		  <button type="reset" class="btn btn-default" style="margin-left:30px;"> <?=$LG['reset']?> </button>
   		 </div>
      </div>
      
      
    
    </div>
    
  </form>
    
</div>

<script language="javascript">
//默认光标在某个INPUT停留,可不用放在foot.php后面,要确保有那个ID,否则会停止执行后面的其他JS
$(function(){  
	document.getElementsByName("tax_money")[0].focus(); //停留
	document.getElementsByName("tax_money")[0].select(); //全选
});

//验证费用是否是退费
function ConfirmFee()
{
   if(parseFloat(document.getElementsByName("tax_money")[0].value) < <?=spr($rs['tax_payment'])?>)
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

<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/foot.php');
?>
