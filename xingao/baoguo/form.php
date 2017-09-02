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
$pervar='baoguo_ed';require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');
$headtitle="包裹";$alonepage=1;//单页形式
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/head.php');

if(!$off_baoguo){exit ("<script>alert('包裹系统已关闭！');goBack();</script>");}


//获取,处理
$lx=par($_GET['lx']);
if(!$lx){$lx='add';}
$bgid=par($_GET['bgid']);

if($lx=='edit')
{
	if(!$bgid){exit ("<script>alert('bgid{$LG['pptError']}');goBack();</script>");}
	$rs=FeData('baoguo','*',"bgid='{$bgid}'");
	warehouse_per('ts',$rs['warehouse']);//验证可管理的仓库
}
$pay_ts='如果要真实扣费/退费,请点击右边按钮&#13;最后一次费用操作记录：负数表示扣费；正数表示退费；0表示未扣费或免费；';

//生成令牌密钥(为安全要放在所有验证的最后面)
$token=new Form_token_Core();
$tokenkey= $token->grante_token("baoguo".$bgid);
?>

<div class="page_ny"> 
  <!-- BEGIN PAGE HEADER-->
	<div><!--单页时去掉 class="row",不然会有横滚动条,并且form 加 style="margin:20px;"-->
		<div class="col-md-12"> 
<h3 class="page-title"> <!--<a href="javascript:history.go(-1)" title="<?=$LG['back']?>"><i class="icon-reply"></i></a> <a href="list.php" class="gray" target="_parent"><?=$LG['backList']?></a> >--> <a href="javascript:void(0)" onClick="javascript:window.location.href=window.location.href;" title="<?=$LG['refresh']?>" class="gray">
        <?=cadd($rs['bgydh']).par($_GET['bgydh'])?> <?=$headtitle?>
        </a> </h3>
    </div>
  </div>
  <!-- END PAGE HEADER--> 
  
  <!-- BEGIN PAGE CONTENT-->
    <form action="form_save.php" method="post" class="form-horizontal form-bordered" name="xingao"><!--删除style="margin:20px;"-->
    <input name="lx" type="hidden" value="<?=add($lx)?>">
    <input name="bgid" type="hidden" value="<?=$rs['bgid']?>">
    <input name="tokenkey" type="hidden" value="<?=$tokenkey?>">
	
    <!--扣费/退费用到-->
    <input name="tjtype" type="hidden" >
   <div><!-- class="tabbable tabbable-custom boxless"-->
      <div class="tab-content">
        <div class="tab-pane active" id="tab_1">
          <div class="form">
            <div class="form-body">
<!--表单内容-开始------------------------------------------------------------------------------------------------------>
			<div class="portlet">
                <div class="portlet-title">
                  <div class="caption"><i class="icon-reorder"></i>基本资料</div>
                  <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
                </div>
                <div class="portlet-body form" style="display: block;"> 
                  <!--表单内容-->
				<div class="form-group">
                    <label class="control-label col-md-2">会员</label>
                    <div class="col-md-10 has-error">
 					 <input type="text" class="form-control input-small" name="useric"  value="<?=cadd($rs['useric'])?>" title="会员入库码"  placeholder="会员入库码" onBlur="getUsernameId('useric');">  
					 <input type="text" class="form-control input-small" name="userid" autocomplete="off"   value="<?=cadd($rs['userid'])?>" title="会员ID"  placeholder="会员ID" onBlur="getUsernameId('userid');">
                    <input type="text" class="form-control input-medium" name="username" autocomplete="off"  value="<?=cadd($rs['username'])?>" title="会员名"  placeholder="会员名" onBlur="getUsernameId('username');">
					<!-- <input type="text" class="form-control input-medium" name="nickname" autocomplete="off"  value="<?=cadd($rs['nickname'])?>" title="昵称"  placeholder="昵称" onBlur="getUsernameId('nickname');"> -->
					 	<span class="help-block">待领包裹时,请全部留空 (填写其中一项，自动获取其他)</span>	
                    </div>
                  </div>

      
  <div class="form-group">
	<label class="control-label col-md-2">认领资料</label>
	<div class="col-md-10">
	  <textarea name="unclaimedContent" class="form-control" placeholder="对于待领包裹,可填写认领时需验证的资料,如包裹姓名"><?=cadd($rs['unclaimedContent'])?></textarea>
	</div>
  </div>

				 <div class="form-group">
                    <label class="control-label col-md-2"><?=$LG['status']//状态?></label>
                    <div class="col-md-10 has-error">
                     <select name="status" class="form-control input-medium select2me"  data-placeholder="请选择" required>
					 <?php baoguo_Status(spr($rs['status']),1);?>
					 </select>
                    </div>
                  </div>
                 
				  
                  <div class="form-group" <?=$warehouse_more?'':'style="display:none"'?>>
                    <label class="control-label col-md-2">仓库</label>
                    <div class="col-md-10 has-error">
                     <select name="warehouse" class="form-control input-medium select2me" required  data-placeholder="请选择">
					 <?php warehouse(cadd($rs['warehouse']),1,1);?>
					 </select>
                    </div>
                  </div>
				  
				  <div class="form-group">
                    <label class="control-label col-md-2">仓位</label>
                    <div class="col-md-10">
                      <input type="text" class="form-control input-small" name="whPlace" value="<?=cadd($rs['whPlace'])?>">
                    </div>
                  </div>

				
				<div class="form-group">
					<div class="control-label col-md-2 right">入库时间</div>
					<div class="col-md-10">
						<input class="form-control form-control-inline  input-small date-picker"  data-date-format="yyyy-mm-dd" size="16" type="text" name="rukutime_date" value="<?=DateYmd($rs['rukutime'],2)?>">
					
						<input type="text" id="clockface_2"  name="rukutime_time" value="<?=DateYmd($rs['rukutime'],'H:i');?>" class="form-control input-xsmall" readonly style="margin-right:0px;">
						<button class="btn btn-default" type="button" id="clockface_2_toggle"><i class="icon-time"></i></button>
					
					
					<span class="help-block">添加/预报时间:<?=DateYmd($rs['addtime']);?></span>					
					</div>
				</div>

				<div class="form-group">
					<div class="control-label col-md-2 right">存放时间</div>
					<div class="col-md-10"><?php bg_ware_days();?></div>
				</div>
				<div class="form-group">
                    <label class="control-label col-md-2">重量</label>
                    <div class="col-md-10">
                      <input type="text" class="form-control input-small"  name="weight" value="<?=spr($rs['weight'])?>" >
                      <?=$XAwt?>
                    </div>
                  </div>

 				   <div class="form-group">
                    <label class="control-label col-md-2">来源</label>
                    <div class="col-md-10">
                     <select name="addSource" class="form-control input-small select2me"  data-placeholder="请选择">
					 <?php baoguo_addSource($rs['addSource'],1);?>
					 </select>
					 <span class="help-block">必须正确选择</span>
                    </div>
                  </div>
				  <?php if($off_mall){?>
				  <div class="form-group">
                    <label class="control-label col-md-2">来自商城订单ID</label>
                    <div class="col-md-10">
                      <input type="text" class="form-control" name="odid" value="<?=cadd($rs['odid'])?>"><span class="help-block">如果是从商城打包请填写订单ID（多个时用英文逗号“,”分开如:10,11,12），来源选择“<?=baoguo_addSource(3)?>”</span>
                    </div>
                  </div>
				  <?php }?>
				  
				<!--2017.06.17:删除dgid-->
				 
				  
                  <div class="form-group">
                    <label class="control-label col-md-2">会员备注/系统备注</label>
                    <div class="col-md-10">
                     <?=cadd($rs['content'])?>
                    </div>
                  </div>
				  

				
				 
				  
                </div>
              </div>

			<a name="bgydh"></a>
            <div class="portlet">
                <div class="portlet-title">
                  <div class="caption"><i class="icon-reorder"></i>寄库信息</div>
                  <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
                </div>
                <div class="portlet-body form" style="display: block;"> 
                  <!--表单内容-->
				 
				  
				   <div class="form-group">
                    <label class="control-label col-md-2">快递单号</label>
                    <div class="col-md-10 has-error">
                      <input type="text" class="form-control input-medium" name="bgydh" required value="<?=cadd($rs['bgydh']).par($_GET['bgydh'])?>" title="如果没有运单号可填写购物的订单号"><!--扫描入库时传值-->
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2">快递公司</label>
                    <div class="col-md-10">
                     <select name="kuaidi" class="form-control input-medium select2me"  data-placeholder="请选择">
					 <?php baoguo_kuaidi(cadd($rs['kuaidi']));?>
					 </select>
                    </div>
                  </div>
				  
                  <div class="form-group">
                    <label class="control-label col-md-2">发货点</label>
                    <div class="col-md-10">
                      <input type="text" class="form-control input-medium" name="fahuodiqu" value="<?=cadd($rs['fahuodiqu'])?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2">购物网站</label>
                    <div class="col-md-10">
                     <select name="wangzhan" id="wangzhan" class="form-control input-medium select2me" data-placeholder="请选择" onChange="wangzhan_other();">
					 <?php wangzhan(cadd($rs['wangzhan']),1);?>
					 </select>

	<div id="msg_wangzhan_other"></div>
	<script language="javascript">
	function wangzhan_other()
	{
		var wangzhan=document.getElementById("wangzhan").value;
		if(wangzhan=="other")
		{
			document.getElementById("msg_wangzhan_other").innerHTML = '<br><input name="wangzhan_other" class="form-control input-medium" type="text" value="<?=cadd($rs['wangzhan_other'])?>"/>';
		}else
		{
			document.getElementById("msg_wangzhan_other").innerHTML = '';
		}
	}
	</script> 
					                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2">发货/购物日期</label>
                    <div class="col-md-10">
                      <input name="fahuotime" type="text" class=" form-control input-small form-control-inline date-picker"  data-date-format="yyyy-mm-dd" value="<?=DateYmd($rs['fahuotime'],2)?>">
                    </div>
                  </div>

				  
                </div>
              </div>
			  		
			
              <div class="portlet">
			  <div class="portlet-title">
                  <div class="caption"><i class="icon-reorder"></i>物品信息</div>
                  <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
                </div>
                <div class="portlet-body form" style="display: block;"> 

				  
				<?php 
				$wupin_req_type='0';
				$wupin_req_name='0';
				$wupin_req_brand='0';
				$wupin_req_spec='0';
				$wupin_req_price='0';
				$wupin_req_unit='0';
				$wupin_req_number='0';
				$wupin_req_total='0';
				$wupin_req_weight='0';
				wupin_from_general('baoguo',$rs['bgid']);//通用物品表单
				?>


        
                </div>
              </div>
			  
			  
			  
			  <div class="portlet">
                <div class="portlet-title">
                  <div class="caption"><i class="icon-reorder"></i>服务操作<a name="op"></a></div>
                  <div class="tools"> <a href="javascript:;" class="<?=spr($rs['status'])>1?'collapse':'expand'?>"></a> </div>
                </div>
                <div class="portlet-body form" style="display:<?=spr($rs['status'])>1?'block':'none'?>;"> 
                  <!--表单内容-->
<!--------------------------------------------------------------->
				  <?php if ($off_hx){?>
                  <div class="form-group">
                    <label class="control-label col-md-2">合箱</label>
                    <div class="col-md-10">

          <?php $field='hx';?>
		  <select name="<?=$field?>"  class="form-control input-small select2me" data-placeholder="空">
            <?php baoguo_hx($rs[$field],1);?>
          </select>
		  <input name="<?=$field?>_pay"  class="form-control input-xsmall" type="text" value="<?=spr($rs[$field.'_pay'])?>" title="<?=$pay_ts?>" /><?=$XAmc?>
		
		<input type="button" value="扣费/退费"
		class="btn btn-xs btn-warning"
		onClick="
		document.xingao.tjtype.value='<?=$field?>';
		document.xingao.target='_blank';
		document.xingao.action='money_czkf.php';
		czkf_submit();
		">

      
		<br><br>
         <input name="hx_suo" type="checkbox" value="1" <?php if($rs['hx_suo']){echo ' checked="checked"';}?>/>
          不可再申请合箱
		  
		
    <?php if($rs['hx']==1) {?>
		 <br>
          <input name="hx_fj" type="checkbox" value="1" <?php if($rs[$field]==1){echo ' checked="checked"';}?> />
		   状态改为“已合箱”时，自动把其他包裹的 重量、总价、物品 合并起来
   <?php  }?>
   
        
    <br>
    <br>
	<?php require($_SERVER['DOCUMENT_ROOT'].'/xingao/baoguo/call/hx_show.php'); ?>
    <?php if(cadd($rs['hx_requ'])){echo '<span class="help-block"><br><strong>合箱要求：</strong>'.cadd($rs['hx_requ']).'</span>';} ?><br>  
				  
                    </div>
                  </div>
				  <?php }?>



<!--------------------------------------------------------------->


		 <?php if ($off_fx){?>
		  <div class="form-group">
                    <label class="control-label col-md-2">分箱</label>
                    <div class="col-md-10">
		 
          <?php $field='fx';?>
		  <select name="<?=$field?>"  class="form-control input-small select2me" data-placeholder="空">
            <?php baoguo_fx($rs[$field],1);?>
          </select>
		  <input name="<?=$field?>_pay"  class="form-control input-xsmall" type="text" value="<?=spr($rs[$field.'_pay'])?>" title="<?=$pay_ts?>" /><?=$XAmc?>
		
		<input type="button" value="扣费/退费"
		class="btn btn-xs btn-warning"
		onClick="
		document.xingao.tjtype.value='<?=$field?>';
		document.xingao.target='_blank';
		document.xingao.action='money_czkf.php';
		czkf_submit();
		">

		 
    
      <br><br>
	  <input name="fx_suo" type="checkbox" value="1" <?php if($rs['fx_suo']){echo ' checked';}?>/>
      不可再申请分箱
	  <br>
      <input name="zb_zl" type="checkbox" value="1" checked/>
      主箱重量自动减出<br>
	  
      <?php if($rs['fx']==1){?>
		  <span class="help-block">
		  提示：<br />
		  <font class="red2">
		  &raquo; 状态改为“已分箱”时：主箱单号后面自动加“A”；主箱物品自动更新；主箱物品数量为0时自动删除该物品；<br />
		  </font>
		  &raquo; 如有填写重量请按重量分,否则按数量分<br />
		  &raquo; 支持扫描枪、电子称(鼠标点文本框再扫描/称重可自动获取条码/重量)<br />
		  </span>
      <?php  }?>
    <?php $form=1;require($_SERVER['DOCUMENT_ROOT'].'/xingao/baoguo/call/fx_show.php'); ?>
    <?php if(cadd($rs['fx_requ'])){echo '<span class="help-block"><br><strong>分箱要求：</strong>'.cadd($rs['fx_requ']).'</span>';} ?><br>
      
	
                    </div>
                  </div>
<?php }//if ($off_fx){?>

<!--------------------------------------------------------------->

				<?php 
				$field='ware';
				$off_baoguo='off_'.$field;
				if ($$off_baoguo){ ?>
					<div class="form-group">
					<label class="control-label col-md-2">仓储</label>
					<div class="col-md-10">
					<div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                    <input type="checkbox" class="toggle" name="ware" value="1"  <?php if($rs['ware']){echo ' checked="checked"';}?>/>
                  </div>
					
					<input name="<?=$field?>_pay"  class="form-control input-xsmall" type="text" value="<?=spr($rs[$field.'_pay'])?>" title="<?=$pay_ts?>" /><?=$XAmc?>
					
					<input type="button" value="扣费/退费"
					class="btn btn-xs btn-warning"
					onClick="
					document.xingao.tjtype.value='<?=$field?>';
					document.xingao.target='_blank';
					document.xingao.action='money_czkf.php';
					czkf_submit();
					">
					<br><br>
					<input name="waretime_edit" type="checkbox" value="1"/><font title="如果不打勾，右边设置则不保存">修改日期</font>
					→
					仓储<input class="form-control form-control-inline  input-small date-picker"  data-date-format="yyyy-mm-dd" size="16" type="text" name="ware_time" value="<?=DateYmd($rs['ware_time'],2)?>">
					
					取出<input class="form-control form-control-inline  input-small date-picker"  data-date-format="yyyy-mm-dd" size="16" type="text" name="ware_out_time" value="<?=DateYmd($rs['ware_out_time'],2)?>">
					
					</div>
					</div>
				<?php }?>
				
<!--------------------------------------------------------------->
				
				<?php 
				$field='th';
				$name='退货';
				$op_ts='选择“'.baoguo_th(2).'”时会自动转到“'.baoguo_Status(10).'”分类';
				require($_SERVER['DOCUMENT_ROOT'].'/xingao/baoguo/call/form_op.php');
				?>
				  
				<?php 
				$field='op_02';
				$name='验货';
				$op_ts='';
				require($_SERVER['DOCUMENT_ROOT'].'/xingao/baoguo/call/form_op.php');
				?>
				  
				<?php 
				$field='op_04';
				$name='转移仓库';
				$op_ts='';
				require($_SERVER['DOCUMENT_ROOT'].'/xingao/baoguo/call/form_op.php');
				?>
				  
				<?php 
				$field='op_06';
				$name='拍照';
				$op_ts='';
				require($_SERVER['DOCUMENT_ROOT'].'/xingao/baoguo/call/form_op.php');
				?>
				  
				<?php 
				$field='op_07';
				$name='减重';
				$op_ts='';
				require($_SERVER['DOCUMENT_ROOT'].'/xingao/baoguo/call/form_op.php');
				?>
				  
				<?php 
				$field='op_09';
				$name='清点';
				$op_ts='';
				require($_SERVER['DOCUMENT_ROOT'].'/xingao/baoguo/call/form_op.php');
				?>
				  
				<?php 
				$field='op_10';
				$name='复称';
				$op_ts='';
				require($_SERVER['DOCUMENT_ROOT'].'/xingao/baoguo/call/form_op.php');
				?>
				  
				<?php 
				$field='op_11';
				$name='填空隙';
				$op_ts='';
				require($_SERVER['DOCUMENT_ROOT'].'/xingao/baoguo/call/form_op.php');
				?>
                  
                  
                  <div class="form-group">
                    <label class="control-label col-md-2">回复</label>
                    <div class="col-md-10">
                      <textarea name="reply" class="form-control" placeholder="给会员回复"><?=cadd($rs['reply'])?></textarea>
                    </div>
                  </div>

				  
                </div>
              </div>
 <!--表单内容-结束------------------------------------------------------------------------------------------------------>

            </div>
          </div>
        </div>
        
        
                
        
        
                
<!--提交按钮固定--> 
<style>body{margin-bottom:50px !important;}</style><!--后台不用隐藏,增高底部高度-->
<div align="center" class="fixed_btn" id="Autohidden">





          <button type="submit" class="btn btn-primary input-small" id="openSmt1" disabled 
		onClick="
		document.xingao.target='';
		document.xingao.action='form_save.php';
		">
		 <i class="icon-ok"></i> <?=$LG['submit']?> </button>
          <button type="reset" class="btn btn-default" style="margin-left:30px;"> <?=$LG['reset']?> </button>
        </div>
      </div>
    </div>
  </form>
	<div class="xats">提示:<br />
		&raquo; 红框为必填,灰框选填 (如果无法提交，请检查红框是否填写完整)<br />
	</div>
</div>


<script src="/js/AntongJQ.js" type="text/javascript"></script>
<?php require_once($_SERVER['DOCUMENT_ROOT'].'/js/baoguoJS.php');?>

<script language="JavaScript" type="text/JavaScript">
function czkf_submit()
{
   if(confirm('确认要扣费/退费吗? (负数则扣费,正数则退费)'))
    document.xingao.submit();
   else
     return false;
}
</script>




<?php
$enlarge=1;//是否用到 图片扩大插件 (/public/enlarge/call.html)
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/foot.php');
?>
<script language="javascript">
//单独分开,要放在foot.php后面
$(function(){       
     wangzhan_other();//包裹表单,选择其他购物网站时
});
//单独分开,要放在foot.php后面
$(function(){       
   CalcDeclareValue();//包裹表单,修改时自动计算总物品价值
});
</script>
