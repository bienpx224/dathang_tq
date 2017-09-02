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
$pervar='lipei';//权限验证
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');
$headtitle="理赔";
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/head.php');


//获取,处理
$lx=par($_GET['lx']);
$lpid=par($_GET['lpid']);
if(!$lx){$lx='add';}

if($lx=='edit')
{
	if(!$lpid){exit ("<script>alert('lpid{$LG['pptError']}');goBack();</script>");}
	
	$rs=FeData('lipei','*',"lpid='{$lpid}'");
}

//生成令牌密钥(为安全要放在所有验证的最后面)
$token=new Form_token_Core();
$tokenkey= $token->grante_token("lipei_add");

?>

<div class="page_ny"> 
  <!-- BEGIN PAGE HEADER-->
	<div class="row"><!--单页时去掉 class="row",不然会有横滚动条,并且form 加 style="margin:20px;"-->
		<div class="col-md-12"> 
<h3 class="page-title"> <a href="javascript:history.go(-1)" title="<?=$LG['back']?>"><i class="icon-reply"></i></a> <a href="list.php" class="gray" target="_parent"><?=$LG['backList']?></a> > <a href="javascript:void(0)" onClick="javascript:window.location.href=window.location.href;" title="<?=$LG['refresh']?>" class="gray">
        <?=$headtitle?>
        </a> </h3>
    </div>
  </div>
  <!-- END PAGE HEADER--> 
  
  <!-- BEGIN PAGE CONTENT-->
  
  <form action="save.php" method="post" class="form-horizontal form-bordered" name="xingao">
    <input name="lx" type="hidden" value="<?=add($lx)?>">
    <input name="lpid" type="hidden" value="<?=$rs['lpid']?>">
    <input name="tokenkey" type="hidden" value="<?=$tokenkey?>">
    <div><!-- class="tabbable tabbable-custom boxless"-->
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
                      <select  class="form-control input-small select2me" name="status" data-placeholder="状态">
                        <?=lipei_Status(spr($rs['status']),1)?>
                      </select>
                      <span class="help-block"> 
                      处理中,处理完毕：会员不能再修改;<br>
                      待处理,拒绝处理：会员可以修改信息; <br>
                      </span> </div>
                  </div>
                  
                  
                  <div class="form-group">
                    <label class="control-label col-md-2">保存赔付金</label>
                    <div class="col-md-10">
                      <input type="text" class="form-control input-small" name="money" value="<?=cadd($rs['money'])?>"><?=$XAmc?>
                      <span class="help-block"> 此栏只用于保存所给的赔付金</span> </div>
                  </div>
                  
                  <div class="form-group">
                    <label class="control-label col-md-2">给会员充值</label>
                    <div class="col-md-10">
                      <input type="text" class="form-control input-small" name="payment"  onKeyUp="value=value.replace(/[^\d\.]/g,'');" onafterpaste="value=value.replace(/[^\d\.]/g,'');"><?=$XAmc?>
                      <span class="help-block"> 只要填写正数并提交都将会给会员账户充值</span> </div>
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
                  <div class="caption"><i class="icon-reorder"></i><strong>理赔资料</strong></div>
                  <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
                </div>
                <div class="portlet-body form" style="display: block;"> 
                  <!--表单内容-->
                  
                  <div class="form-group">
                    <div class="control-label col-md-2 right">类型</div>
                    <div class="col-md-10">
                      <?=lipei_Types($rs['types'])?>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="control-label col-md-2 right">运单号</div>
                    <div class="col-md-10">
                    <input name="ydh" type="hidden" value=" <?=cadd($rs['ydh'])?>">
                      <?=cadd($rs['ydh'])?>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="control-label col-md-2 right">联系电话</div>
                    <div class="col-md-10">
                      <?=cadd($rs['mobile'])?>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="control-label col-md-2 right">联系邮箱</div>
                    <div class="col-md-10">
                      <?=cadd($rs['email'])?>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="control-label col-md-2 right">说明</div>
                    <div class="col-md-10">
                      <?=cadd($rs['content'])?>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="control-label col-md-2 right">凭证</div>
                    <div class="col-md-10"> 
                      <!--凭证-->
					  <?php if($rs['img']){EnlargeImg($rs['img'],$rs['lpid']); }?>
                     
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="control-label col-md-2 right">赔付要求</div>
                    <div class="col-md-10">
                      <?=cadd($rs['requ'])?>
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
    </div>
  </form>
</div>
<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/foot.php');
?>
