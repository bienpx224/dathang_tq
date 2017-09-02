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
$headtitle=$LG['tixian_zh.form_1'];//提现账号
require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/head.php');

//获取,处理
$lx=par($_GET['lx']);
$txzhid=par($_GET['txzhid']);
if(!$lx){$lx='add';}

if($lx=='edit')
{
	if(!$txzhid){exit ("<script>alert('txzhid{$LG['pptError']}');goBack();</script>");}
	$rs=FeData('tixian_zh','*',"txzhid='{$txzhid}'  {$Mmy}");
}

//生成令牌密钥(为安全要放在所有验证的最后面)
$token=new Form_token_Core();
$tokenkey= $token->grante_token("tixian_zh");
?>

<div class="page_ny"> 
  <!-- BEGIN PAGE HEADER-->
	<div class="row"><!--单页时去掉 class="row",不然会有横滚动条,并且form 加 style="margin:20px;"-->
		<div class="col-md-12"> 
<h3 class="page-title"> <a href="javascript:history.go(-1)" title="<?=$LG['back']?>"><i class="icon-reply"></i></a> <a href="list.php" class="gray" target="_parent"><?=$LG['backList']?></a> > 
        <a href="javascript:void(0)" onClick="javascript:window.location.href=window.location.href;" title="<?=$LG['refresh']?>" class="gray"><?=$headtitle?></a>
       </h3>
    </div>
  </div>
  <!-- END PAGE HEADER--> 
  
  <!-- BEGIN PAGE CONTENT-->
  
  <form action="save.php" method="post" class="form-horizontal form-bordered" name="xingao">
  <input name="lx" type="hidden" value="<?=add($lx)?>">
  <input name="txzhid" type="hidden" value="<?=$rs['txzhid']?>">
  <input name="tokenkey" type="hidden" value="<?=$tokenkey?>">
    <div><!-- class="tabbable tabbable-custom boxless"-->
      <div class="tab-content">
        <div class="tab-pane active" id="tab_1">
          <div class="form">
            <div class="form-body">
            <div class="portlet">
              
              <div class="portlet-body form" style="display: block;"> 
                <!--表单内容-->
              
                
                
                  <div class="form-group">
                  <label class="control-label col-md-2"><?=$LG['UseOn'];//可使用?></label>
                  <div class="col-md-10">
                    <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                      <input type="checkbox" class="toggle" name="checked" value="1"  <?php if($rs['checked']||$lx=='add'){echo 'checked';}?> />
                    </div>
                  </div>
                </div>


                
              <div class="form-group">
                  <label class="control-label col-md-2"><?=$LG['tixian_zh.bank'];//开户银行?></label>
                  <div class="col-md-10 has-error">
                  <select name="bank" required class="form-control input-medium select2me" data-placeholder="Select...">
                   <?=tixian_Bank($rs['bank'],1)?>
                  </select>
                 
                  </div>
                </div>
                
                <div class="form-group">
                  <label class="control-label col-md-2"><?=$LG['tixian_zh.name'];//开户姓名?></label>
                  <div class="col-md-10 has-error">
                
                    <input type="text" class="form-control input-medium" name="name" required value="<?=cadd($rs['name'])?>">
                 
                  </div>
                </div>
                             
    
                <div class="form-group">
                  <label class="control-label col-md-2"><?=$LG['tixian_zh.account'];//开户账号?></label>
                  <div class="col-md-10 has-error">
                
                    <input type="text" class="form-control input-large" name="account" required value="<?=cadd($rs['account'])?>">
                 
                  </div>
                </div>                  
                
                
                
                     
                <div class="form-group">
                  <label class="control-label col-md-2"><?=$LG['tixian_zh.address'];//开户地址?></label>
                  <div class="col-md-10">
                
                    <input type="text" class="form-control input-large" name="address"  value="<?=cadd($rs['address'])?>">
                 
                  </div>
                </div>
                             
            
                             
                 
                 <div class="form-group">
                  <label class="control-label col-md-2"><?=$LG['note']?></label>
                  <div class="col-md-10">
                    <textarea  class="form-control" rows="3" name="content"><?=cadd($rs['content'])?></textarea>
                  </div>
                </div>
                
                <div class="form-group">
                  <label class="control-label col-md-2"><?=$LG['name.nav_52'];//提现密码?></label>
                  <div class="col-md-10 has-error">
                
                  <input type="password" class="form-control input-medium" name="tixianpassword" required value="" autocomplete="off"><input type="password" style="display:none" /> <!--这个只是为了防止某些浏览器自动填写表单-->
                  <span class="help-block"><?=$LG['tixian_zh.form_2'];//为了安全,需要输入正确的提现密码才能操作?></span>
                 
                  </div>
                </div>
           
                
              </div>
            </div>
          </div>
          
          </div>
        </div>        
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
