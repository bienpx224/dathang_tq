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
$pervar='manage_sy';require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');
$headtitle="修改充值支付接口";
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/head.php');

//获取,处理
$lx=par($_GET['lx']);
$payid=par($_GET['payid']);

if($lx=='edit')
{
	if(!$payid){exit ("<script>alert('payid{$LG['pptError']}');goBack();</script>");}
	
	$rs=FeData('payapi','*',"payid='{$payid}'");
}

//生成令牌密钥(为安全要放在所有验证的最后面)
$token=new Form_token_Core();
$tokenkey= $token->grante_token("payapi");

?>

<div class="page_ny"> 
  <!-- BEGIN PAGE HEADER-->
	<div class="row"><!--单页时去掉 class="row",不然会有横滚动条,并且form 加 style="margin:20px;"-->
		<div class="col-md-12"> 
<h3 class="page-title"> <a href="javascript:history.go(-1)" title="<?=$LG['back']?>"><i class="icon-reply"></i></a> <a href="list.php" class="gray" target="_parent"><?=$LG['backList']?></a> > <a href="javascript:void(0)" onClick="javascript:window.location.href=window.location.href;" title="<?=$LG['refresh']?>" class="gray">
        <?=$headtitle?>
        </a> <small>
        <?=cadd($rs['payname'])?>
        </small> </h3>
    </div>
  </div>
  <!-- END PAGE HEADER--> 
  
  <!-- BEGIN PAGE CONTENT-->
  
  <form action="save.php" method="post" class="form-horizontal form-bordered" name="xingao">
    <input name="lx" type="hidden" value="<?=add($lx)?>">
    <input name="payid" type="hidden" value="<?=$rs['payid']?>">
    <input name="tokenkey" type="hidden" value="<?=$tokenkey?>">
    <div class="tabbable tabbable-custom boxless">
    <div class="tab-content">
      <div class="tab-pane active" payid="tab_1">
        <div class="form">
          <div class="form-body">
            <div class="portlet">
              <div class="portlet-title">
                <div class="caption"><i class="icon-reorder"></i><strong>
                  <?=$rs['paytype']?>
                  </strong></div>
                <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
              </div>
              <div class="portlet-body form" style="display: block;"> 
                <!--表单内容-->
                <div class="form-group">
                  <label class="control-label col-md-2">名称</label>
                  <div class="col-md-10 has-error">
                    <input type="text" class="form-control input-large" name="payname" required value="<?=cadd($rs['payname'])?>">
                  </div>
                </div>
                <div class="form-group">
                  <label class="control-label col-md-2">排序</label>
                  <div class="col-md-10">
                    <input type="text" class="form-control input-xsmall"  name="myorder" value="<?=cadd($rs['myorder'])?>"><span class="help-block">越大越排前</span>
                  </div>
                </div>
                <div class="form-group">
                  <label class="control-label col-md-2">开通</label>
                  <div class="col-md-10">
                    <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                      <input type="checkbox" class="toggle" name="checked" value="1"  <?=$rs['checked']==1?'checked':''?> />
                    </div>
                  </div>
                </div>
                
                

<?php 
if($rs['payid']==8){require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/payapi/call/form_SoftBank.php');}
elseif($rs['payid']==9){require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/payapi/call/form_NihaoPay.php');}
else{require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/payapi/call/form_normal.php');}
?>





                <div class="form-group">
                  <label class="control-label col-md-2">充值时多加/减</label>
                  <div class="col-md-10"> 
                    <input type="text" class="form-control input-large"  name="payIncMoney" value="<?=spr($rs['payIncMoney'])?>">
                     <span class="help-block"> 如果汇率有多位小数点，在兑换时会四舍五入，可能导致充值不够或过多，请设置多加/减几分钱</span>
                  </div>
                </div>
                
                <div class="form-group">
                  <label class="control-label col-md-2">每次最低充值</label>
                  <div class="col-md-10"> 
                    <input type="text" class="form-control input-large"  name="payMinMoney" value="<?=spr($rs['payMinMoney'])?>">
                  </div>
                </div>

                <div class="form-group">
                  <label class="control-label col-md-2"><?=$LG['explain']//说明?></label>
                  <div class="col-md-10">
                    <textarea  class="form-control" rows="5" name="paysay"><?=cadd($rs['paysay'])?>
</textarea>
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





      <button type="submit" class="btn btn-primary input-small" id="openSmt1" disabled > <i class="icon-ok"></i> <?=$LG['submit']?> </button>
        <button type="reset" class="btn btn-default" style="margin-left:30px;"> <?=$LG['reset']?> </button>
      </div>
    </div>
  </form>
</div>
<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/foot.php');
?>
