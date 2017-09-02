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
$pervar='qujian';require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');
$headtitle="上门送件";
$alonepage=1;//单页形式
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/head.php');

//获取,处理
$lx=par($_GET['lx']);
$qjid=par($_GET['qjid']);
if(!$lx){$lx='add';}

$token=new Form_token_Core();
$tokenkey= $token->grante_token('qujian_add'.$qjid); //生成令牌密钥

if($lx=='edit')
{
	if(!$qjid){exit ("<script>alert('qjid{$LG['pptError']}');goBack();</script>");}
	
	$rs=FeData('qujian','*',"qjid='{$qjid}'");
}
?>

<div class="page_ny"> 
  <!-- BEGIN PAGE HEADER-->
	<div class=""><!--单页时去掉 class="row",不然会有横滚动条,并且form 加 style="margin:20px;"-->
		<div class="col-md-12"> 
<h3 class="page-title">
        <a href="javascript:void(0)" onClick="javascript:window.location.href=window.location.href;" title="<?=$LG['refresh']?>" class="gray"><?=$headtitle?></a>
        </h3>
    </div>
  </div>
  <!-- END PAGE HEADER--> 
  
  <!-- BEGIN PAGE CONTENT-->
  
  <form action="save.php" method="post" class="form-horizontal form-bordered" name="xingao"><!--删除 style="margin:20px;"-->
  <input name="lx" type="hidden" value="<?=add($lx)?>">
  <input name="qjid" type="hidden" value="<?=$rs['qjid']?>">
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
                  <?=qujian_Status(spr($rs['status']),1)?>
                </select>
                 
                  </div>
                </div>
                 
                 
                 <div class="form-group">
                  <label class="control-label col-md-2">回复</label>
                  <div class="col-md-10">
                  <textarea name="old_reply" style="display:none;"><?=cadd($rs['reply'])?></textarea>
                    <textarea  class="form-control" rows="3" name="reply"><?=cadd($rs['reply'])?></textarea>
                  </div>
                </div>
                
              </div>
            </div>
            
            
            <div class="portlet">
              <div class="portlet-title">
                  <div class="caption"><i class="icon-reorder"></i><strong>会员要求</strong></div>
                  <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
                </div>
              <div class="portlet-body form"> 
                <!--表单内容-->
              
                

            <div class="form-group">
                  <label class="control-label col-md-2">送件日期</label>
         <div class="col-md-10 has-error">
        <input class="form-control form-control-inline  input-small date-picker"  data-date-format="yyyy-mm-dd" size="16" type="text" name="qjdate" required value="<?=DateYmd($rs['qjdate'],'Y/m/d')?>">
       
        
                  </div>
                </div>
                
                <div class="form-group">
                  <label class="control-label col-md-2">送件时间</label>
                  <div class="col-md-10">
                    <input type="text" class="form-control input-medium"  name="qjtime"  value="<?=cadd($rs['qjtime'])?>">
                     <span class="help-block">如:8点左右 (留空则表示当天任何时间均可取)</span>
                  </div>
                </div>
                
                     
                    
                <div class="form-group">
                  <label class="control-label col-md-2">联系人</label>
                  <div class="col-md-10 has-error">
                
                    <input type="text" class="form-control input-medium" name="truename" required value="<?=cadd($rs['truename'])?>">
                 
                  </div>
                </div>
                             
                    <div class="form-group">
                  <label class="control-label col-md-2">联系电话</label>
                  <div class="col-md-10 has-error">
                
                    <input type="text" class="form-control input-medium" name="mobile" required value="<?=cadd($rs['mobile'])?>">
                 
                  </div>
                </div>
                        
                
                <div class="form-group">
                  <label class="control-label col-md-2">大约重量</label>
                  <div class="col-md-10 has-error">
                
                    <input type="text" class="form-control input-small" name="weight" required value="<?=cadd($rs['weight'])?>">
                 <?=$XAwt?>
                  </div>
                </div>
                             
   

                
                <div class="form-group">
                  <label class="control-label col-md-2">送件地址</label>
                  <div class="col-md-10 has-error">
                
                    <input type="text" class="form-control" name="address" required value="<?=cadd($rs['address'])?>">
                 
                  </div>
                </div>
                 
                 
                 <div class="form-group">
                  <label class="control-label col-md-2">说明备注</label>
                  <div class="col-md-10">
                    <textarea  class="form-control" rows="3" name="content"><?=cadd($rs['content'])?></textarea>
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
       <button type="button" class="btn btn-danger" onClick="goBack('c');"  style="margin-left:30px;"><i class="icon-remove"></i> <?=$LG['close']?> </button>
    </div>
      </div>
      
      
    
    </div>
    
  </form>
</div>

<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/foot.php');
?>
