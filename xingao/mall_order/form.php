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
$pervar='mall_order';require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');
$headtitle="查看/编辑订单";
$alonepage=1;//单页形式
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/head.php');
if(!$off_mall)
{
	exit ("<script>alert('商城系统未开启,无法使用！');goBack('uc');</script>");
}

//获取,处理
$lx=par($_GET['lx']);
$odid=par($_GET['odid']);
if(!$lx){$lx='add';}

if($lx=='edit')
{
	if(!$odid){exit ("<script>alert('odid{$LG['pptError']}');goBack();</script>");}
	
	$rs=FeData('mall_order','*',"odid='{$odid}'");
	warehouse_per('ts',$zhi=$rs['warehouse']);//验证可管理的仓库
	$rs['unit']=classify($rs['unit'],2);
}

//生成令牌密钥(为安全要放在所有验证的最后面)
$token=new Form_token_Core();
$tokenkey= $token->grante_token("mall_order".$odid);

?>

<div class="page_ny"> 
  <!-- BEGIN PAGE HEADER-->
	<div class=""><!--单页时去掉 class="row",不然会有横滚动条,并且form 加 style="margin:20px;"-->
		<div class="col-md-12"> 
<h3 class="page-title"> <a href="javascript:void(0)" onClick="javascript:window.location.href=window.location.href;" title="<?=$LG['refresh']?>" class="gray">
        <?=$headtitle?>
        </a></h3>
    </div>
  </div>
  <!-- END PAGE HEADER--> 
  
  <!-- BEGIN PAGE CONTENT-->
  
  <form action="save.php" method="post" class="form-horizontal form-bordered" name="xingao"><!--删除 style="margin:20px;"-->
    <input name="lx" type="hidden" value="<?=add($lx)?>">
    <input name="odid" type="hidden" value="<?=$rs['odid']?>">
    <input name="tokenkey" type="hidden" value="<?=$tokenkey?>">
    <div class="tabbable tabbable-custom boxless">
      <div class="tab-content">
        <div class="tab-pane active" id="tab_1">
          <div class="form">
            <div class="form-body">
              <div class="portlet">
                <div class="portlet-title">
                  <div class="caption"><i class="icon-reorder"></i><strong>管理操作</strong></div>
                  <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
                </div>
                <div class="portlet-body form" style="display: block;"> 
                  <!--表单内容-->
                
                  
                  <div class="form-group">
                    <label class="control-label col-md-2"><?=$LG['status']//状态?></label>
                    <div class="col-md-10 has-error">
                      <?php if(spr($rs['status'])=='3'){?>失效订单不能再修改<?php }else{?>
                      <select  class="form-control input-medium select2me" name="status" data-placeholder="状态">
                        <?=mall_order_Status(spr($rs['status']),1)?>
                      </select>
                      <?php }?>
                      <span class="help-block">   
					  <?php if(!$rs['pay']){?><font class="red">该订单还处在购物车中,如非特殊情况请勿乱修改状态!</font><br><?php }?>
                      
                      生成包裹：需要手工添加包裹并填写包裹ID，此处只是修改订单状态！<br>
                      失效订单：会还原商品数量(不退回已付金额)，并且不能再修改状态！<br>
                      </span> </div>
                  </div>
                
                  <div class="form-group">
                    <label class="control-label col-md-2">包裹ID</label>
                    <div class="col-md-10">
                      <input type="text" class="form-control input-small"  name="bgid" value="<?=$rs['bgid']?$rs['bgid']:''?>" >
                      <span class="help-block">如果是手工修改状态，请填写包裹ID (是ID不是运单号)</span> </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2">已付金额</label>
                    <div class="col-md-10">
                      <input type="text" class="form-control input-small"  name="payment" value="<?=spr($rs['payment'])?>" >
                      <?=$XAmc?>
                      <span class="help-block"> 注意:此处只是保存付款金额,不会对会员账号扣款! (用于特殊情况修改) </span> </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2">回复</label>
                    <div class="col-md-10">
                      <textarea name="old_reply" style="display:none;"><?=cadd($rs['reply'])?>
</textarea>
                      <textarea  class="form-control" rows="3" name="reply"><?=cadd($rs['reply'])?>
</textarea>
                    </div>
                  </div>
                </div>
              </div>
              <!---->
              
              <div class="portlet">
                <div class="portlet-title">
                  <div class="caption"><i class="icon-reorder"></i>订单资料</div>
                  <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
                </div>
                <div class="portlet-body form" style="display: block;">
                  <div class="form-group">
                    <div class="control-label col-md-2 right">订单ID</div>
                    <div class="col-md-10"> 
                      <?=cadd($rs['odid'])?>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="control-label col-md-2 right">商品链接</div>
                    <div class="col-md-10"> <a href="<?=$rs['url']?cadd($rs['url']):'/mall/show.php?mlid='.$rs['mlid'];?>" target="_blank">
                      <?=cadd($rs['title'])?>
                      </a> </div>
                  </div>
                  <div class="form-group">
                    <div class="control-label col-md-2 right">订购数量</div>
                    <div class="col-md-10">
                      <?=cadd($rs['number'])?>
                      <?=$rs['unit']?>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="control-label col-md-2 right">金额</div>
                    <div class="col-md-10">
                      <?php require($_SERVER['DOCUMENT_ROOT'].'/xingao/mall_order/call/money_payment.php');?>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="control-label col-md-2 right">付款状态</div>
                    <div class="col-md-10">
                      <?=$pay_status?>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="control-label col-md-2 right">重量</div>
                    <div class="col-md-10">
                      <?php if($rs['weight']){ echo spr($rs['weight']).$XAwt.'*'.$rs['number'].$rs['unit'].'='.(spr($rs['weight'])*$rs['number']).$XAwt.' &nbsp; ';}?>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="control-label col-md-2 right">存入仓库</div>
                    <div class="col-md-10">
                      <?=warehouse($rs['warehouse'])?>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="control-label col-md-2 right">订购套餐</div>
                    <div class="col-md-10">
                      <?=cadd($rs['package'])?>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="control-label col-md-2 right">订购尺寸</div>
                    <div class="col-md-10">
                      <?=cadd($rs['size'])?>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="control-label col-md-2 right">订购颜色</div>
                    <div class="col-md-10">
                      <?=cadd($rs['color'])?>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="control-label col-md-2 right">会员</div>
                    <div class="col-md-10">
                      <?=cadd($rs['username'])?>
                      <font class="gray">
                      <?=$rs['userid']?>
                      </font> </div>
                  </div>
                  <div class="form-group">
                    <div class="control-label col-md-2 right">会员留言</div>
                    <div class="col-md-10">
                      <?=TextareaToBr($rs['content'])?>
                     </div>
                  </div>
                </div>
              </div>
              <!---->
              <div class="portlet">
                <div class="portlet-title">
                  <div class="caption"><i class="icon-reorder"></i>商品基本资料</div>
                  <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
                </div>
                <div class="portlet-body form" style="display: block;">
                  <div class="form-group">
                    <div class="control-label col-md-2 right">编号</div>
                    <div class="col-md-10">
                      <?=cadd($rs['coding'])?>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="control-label col-md-2 right">品牌</div>
                    <div class="col-md-10">
                      <?=classify($rs['brand'],2)?>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="control-label col-md-2 right">类别</div>
                    <div class="col-md-10">
                      <?=classify($rs['category'],2)?>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="control-label col-md-2 right">品名</div>
                    <div class="col-md-10">
                      <?=cadd($rs['goods'])?>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="control-label col-md-2 right">规格</div>
                    <div class="col-md-10">
                      <?=cadd($rs['spec'])?>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="control-label col-md-2 right">单位</div>
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
<style>body{margin-bottom:50px !important;}</style><!--后台不用隐藏,增高底部高度-->
<div align="center" class="fixed_btn" id="Autohidden">





		<?php if(spr($rs['status'])=='3'){?>失效订单不能再修改<?php }else{?>
        <button type="submit" class="btn btn-primary input-small" id="openSmt1" disabled > <i class="icon-ok"></i> <?=$LG['submit']?> </button>
        <button type="reset" class="btn btn-default" style="margin-left:30px;"> <?=$LG['reset']?> </button>
        <?php }?>
          <button type="button" class="btn btn-danger" onClick="goBack('c');"  style="margin-left:30px;"><i class="icon-remove"></i> <?=$LG['close']?> </button>
        </div>
      </div>
    </div>
  </form>
</div>
<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/foot.php');
?>
