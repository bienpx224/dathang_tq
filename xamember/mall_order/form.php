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
require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/session.php');
$headtitle=$LG['mall_order.form_1'];//查看/备注订单
require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/head.php');
if(!$off_mall)
{
	exit ("<script>alert('{$LG['mall_order.form_2']}');goBack('uc');</script>");
}

//获取,处理
$lx=par($_GET['lx']);
$odid=par($_GET['odid']);
if(!$lx){$lx='add';}

if($lx=='edit')
{
	if(!$odid){exit ("<script>alert('odid{$LG['pptError']}');goBack();</script>");}
	
	$rs=FeData('mall_order','*',"odid='{$odid}' {$Mmy} ");
	
	$rs['unit']=classify($rs['unit'],2);
}

//生成令牌密钥(为安全要放在所有验证的最后面)
$token=new Form_token_Core();
$tokenkey= $token->grante_token("mall_order".$odid);

?>

<div class="page_ny"> 
  <!-- BEGIN PAGE HEADER-->
	<div class="row"><!--单页时去掉 class="row",不然会有横滚动条,并且form 加 style="margin:20px;"-->
		<div class="col-md-12"> 
<h3 class="page-title"> <a href="javascript:history.go(-1)" title="<?=$LG['back']?>"><i class="icon-reply"></i></a> <a href="list.php?pay=<?=$rs['pay']?>" class="gray" target="_parent"><?=$LG['backList']?></a> > <a href="javascript:void(0)" onClick="javascript:window.location.href=window.location.href;" title="<?=$LG['refresh']?>" class="gray">
        <?=$headtitle?>
        </a> </h3>
    </div>
  </div>
  <!-- END PAGE HEADER--> 
  
  <!-- BEGIN PAGE CONTENT-->
  
  <form action="save.php" method="post" class="form-horizontal form-bordered" name="xingao">
    <input name="lx" type="hidden" value="<?=add($lx)?>">
    <input name="odid" type="hidden" value="<?=$rs['odid']?>">
    <input name="tokenkey" type="hidden" value="<?=$tokenkey?>">
    <input name="pay" type="hidden" value="<?=$rs['pay']?>">
    <div class="tabbable tabbable-custom boxless">
      <div class="tab-content">
        <div class="tab-pane active" id="tab_1">
          <div class="form">
            <div class="form-body">
              <div class="portlet">
                <div class="portlet-title">
                  <div class="caption"><i class="icon-reorder"></i><strong><?=$LG['op'];//操作?></strong></div>
                  <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
                </div>
                <div class="portlet-body form" style="display: block;"> 
                  <!--表单内容-->
                  <div class="form-group">
                    <label class="control-label col-md-2"><?=$LG['note']?></label>
                    <div class="col-md-10">
                      <textarea name="old_content" style="display:none;"><?=cadd($rs['content'])?>
</textarea>
                      <textarea  class="form-control" rows="3" name="content"><?=cadd($rs['content'])?>
</textarea>
                      <span class="help-block">
                      <?php if(spr($rs['status'])=='3'){?>
                     	 <?=$LG['mall_order.form_26'];//失效订单我们无法再处理，如果要购买请重新订购!?>
                      <?php }else{?> 
                       	<?=$LG['mall_order.form_27'];//后台也可以看到备注 (可以给管理员说明要求) ?>
                      <?php }?>
                      </span> </div>
                  </div>
                </div>
              </div>
              <!---->
              
              <div class="portlet">
                <div class="portlet-title">
                  <div class="caption"><i class="icon-reorder"></i><?=$LG['mall_order.form_4'];//订单资料?></div>
                  <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
                </div>
                <div class="portlet-body form" style="display: block;">
                <div class="form-group">
                    <div class="control-label col-md-2 right"><?=$LG['mall_order.form_5'];//订单ID?></div>
                    <div class="col-md-10"> 
                      <?=cadd($rs['odid'])?>
                    </div>
                  </div>
                  
                  <div class="form-group">
                    <div class="control-label col-md-2 right"><?=$LG['mall_order.form_6'];//商品链接?></div>
                    <div class="col-md-10"> <a href="<?=$rs['url']?cadd($rs['url']):'/mall/show.php?mlid='.$rs['mlid'];?>" target="_blank">
                      <?=cadd($rs['title'])?>
                      </a> </div>
                  </div>
                  <div class="form-group">
                    <div class="control-label col-md-2 right"><?=$LG['mall_order.form_7'];//订购数量?></div>
                    <div class="col-md-10">
                      <?=cadd($rs['number'])?>
                      <?=$rs['unit']?>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="control-label col-md-2 right"><?=$LG['mall_order.form_8'];//金额?></div>
                    <div class="col-md-10">
                      <?php require($_SERVER['DOCUMENT_ROOT'].'/xingao/mall_order/call/money_payment.php');?>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="control-label col-md-2 right"><?=$LG['mall_order.form_9'];//付款状态?></div>
                    <div class="col-md-10">
                      <?=$pay_status?>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="control-label col-md-2 right"><?=$LG['status'];//状态?></div>
                    <div class="col-md-10 has-error">
					 <?php if($rs['bgid']){?>
					  <a href="/xamember/baoguo/show.php?bgid=<?=$rs['bgid']?>" target="_blank" title="包裹ID: <?=$rs['bgid']?>"> 
					  <?=mall_order_Status(spr($rs['status']));?>                     
					  </a>
					 <?php 
					 }else{ 
						 echo mall_order_Status(spr($rs['status']));
					 }
					 ?>
                    </div>
                  </div>
                  
				  
                  <div class="form-group">
                    <div class="control-label col-md-2 right"><?=$LG['mall_order.form_10'];//已付金额?></div>
                    <div class="col-md-10">
                      <?=spr($rs['payment'])?>
                      <?=$XAmc?>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="control-label col-md-2 right"><?=$LG['mall_order.form_11'];//回复?></div>
                    <div class="col-md-10">
                      <?=cadd($rs['reply'])?>
                      <?=DateYmd($rs['replytime'])?>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="control-label col-md-2 right"><?=$LG['mall_order.form_12'];//重量?></div>
                    <div class="col-md-10">
                      <?php if($rs['weight']){ echo spr($rs['weight']).$XAwt.'*'.$rs['number'].$rs['unit'].'='.(spr($rs['weight'])*$rs['number']).$XAwt.' &nbsp; ';}?>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="control-label col-md-2 right"><?=$LG['mall_order.form_13'];//存入仓库?></div>
                    <div class="col-md-10">
                      <?=warehouse($rs['warehouse'])?>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="control-label col-md-2 right"><?=$LG['mall_order.form_14'];//订购套餐?></div>
                    <div class="col-md-10">
                      <?=cadd($rs['package'])?>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="control-label col-md-2 right"><?=$LG['mall_order.form_15'];//订购尺寸?></div>
                    <div class="col-md-10">
                      <?=cadd($rs['size'])?>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="control-label col-md-2 right"><?=$LG['mall_order.form_16'];//订购颜色?></div>
                    <div class="col-md-10">
                      <?=cadd($rs['color'])?>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="control-label col-md-2 right"><?=$LG['member'];//会员?></div>
                    <div class="col-md-10">
                      <?=cadd($rs['username'])?>
                      <font class="gray">
                      <?=$rs['userid']?>
                      </font> </div>
                  </div>
                  <div class="form-group">
                    <div class="control-label col-md-2 right"><?=$LG['mall_order.form_18'];//会员留言?></div>
                    <div class="col-md-10">
                      <?=TextareaToBr($rs['content'])?>
                    </div>
                  </div>
                </div>
              </div>
              <!---->
              <div class="portlet">
                <div class="portlet-title">
                  <div class="caption"><i class="icon-reorder"></i><?=$LG['mall_order.form_19'];//商品基本资料?></div>
                  <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
                </div>
                <div class="portlet-body form" style="display: block;">
                  <div class="form-group">
                    <div class="control-label col-md-2 right"><?=$LG['mall_order.form_20'];//编号?></div>
                    <div class="col-md-10">
                      <?=cadd($rs['coding'])?>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="control-label col-md-2 right"><?=$LG['brand'];//品牌?></div>
                    <div class="col-md-10">
                      <?=classify($rs['brand'],2)?>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="control-label col-md-2 right"><?=$LG['mall_order.form_22'];//类别?></div>
                    <div class="col-md-10">
                      <?=classify($rs['category'],2)?>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="control-label col-md-2 right"><?=$LG['mall_order.form_23'];//品名?></div>
                    <div class="col-md-10">
                      <?=cadd($rs['goods'])?>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="control-label col-md-2 right"><?=$LG['mall_order.form_24'];//规格?></div>
                    <div class="col-md-10">
                      <?=cadd($rs['spec'])?>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="control-label col-md-2 right"><?=$LG['mall_order.form_25'];//单位?></div>
                    <div class="col-md-10">
                      <?=$rs['unit']?>
                    </div>
                  </div>
                </div>
              </div>
              <!----> 
              
            </div>
          </div>
        </div>
        <!---->        
        <!--提交按钮固定--> 
		<script>/*$(function(){ Autohidden(); });*/</script> <!--拉到底时自动隐藏-->
<style>body{margin-bottom:40px !important;}</style><!--不用隐藏,增高底部高度-->
 
        <div align="center" class="fixed_btn" id="Autohidden">
        
        
<button type="submit" class="btn btn-primary input-small" id="openSmt1" disabled > <i class="icon-ok"></i> <?=$LG['submit']?> </button>
          <button type="reset" class="btn btn-default" style="margin-left:30px;"> <?=$LG['reset']?> </button>
          
        </div>
      </div>
    </div>
  </form>
</div>
<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/foot.php');
?>
