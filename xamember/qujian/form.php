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
$pervar='off_qujian';//权限验证
require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/session.php');
$headtitle=$LG['name.nav_27'];//上门取件
require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/head.php');


//获取,处理
$lx=par($_GET['lx']);
$qjid=par($_GET['qjid']);
if(!$lx){$lx='add';}

if($lx=='edit')
{
	if(!$qjid){exit ("<script>alert('qjid{$LG['pptError']}');goBack();</script>");}
	
	$rs=FeData('qujian','*',"qjid='{$qjid}' {$Mmy}");
}

if(!$rs['truename']){$rs['truename']=$Mtruename;}

//生成令牌密钥(为安全要放在所有验证的最后面)
$token=new Form_token_Core();
$tokenkey= $token->grante_token("qujian_add");
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
  <input name="qjid" type="hidden" value="<?=$rs['qjid']?>">
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
				<label class="control-label col-md-2"><?=$LG['qujian.qjdate'];//取件日期?></label>
				<div class="col-md-10 has-error">
					<input class="form-control form-control-inline  input-small date-picker"  data-date-format="yyyy-mm-dd" size="16" type="text" name="qjdate" required value="<?=DateYmd($rs['qjdate'],'Y/m/d')?>">
					<span class="help-block"><?=$LG['qujian.form_1'];//最快要选择明天?></span>
				</div>
				</div>
                
                <div class="form-group">
                  <label class="control-label col-md-2"><?=$LG['qujian.qjtime'];//取件时间?></label>
                  <div class="col-md-10">
                    <input type="text" class="form-control input-medium"  name="qjtime"  value="<?=cadd($rs['qjtime'])?>">
                     <span class="help-block"><?=$LG['qujian.form_2'];//如:8点左右 (留空则表示当天任何时间均可取)?></span>
                  </div>
                </div>
                
                     
                    
                <div class="form-group">
                  <label class="control-label col-md-2"><?=$LG['qujian.truename'];//联系人?></label>
                  <div class="col-md-10 has-error">
                
                    <input type="text" class="form-control input-medium" name="truename" required value="<?=cadd($rs['truename'])?>">
                 
                  </div>
                </div>
                             
                    <div class="form-group">
                  <label class="control-label col-md-2"><?=$LG['qujian.mobile'];//联系电话?></label>
                  <div class="col-md-10 has-error">
                
                    <input type="text" class="form-control input-medium" name="mobile" required value="<?=cadd($rs['mobile'])?>">
                 
                  </div>
                </div>
                        
                
                <div class="form-group">
                  <label class="control-label col-md-2"><?=$LG['qujian.weight'];//大约重量?></label>
                  <div class="col-md-10 has-error">
                
                    <input type="text" class="form-control input-small" name="weight" required value="<?=cadd($rs['weight'])?>">
                 <?=$XAwt?>
                  </div>
                </div>
                             
   

                
                <div class="form-group">
                  <label class="control-label col-md-2"><?=$LG['qujian.address'];//取件地址?></label>
                  <div class="col-md-10 has-error">
                
                    <input type="text" class="form-control" name="address" required value="<?=cadd($rs['address'])?>">
                 
                  </div>
                </div>
                 
                 
                 <div class="form-group">
                  <label class="control-label col-md-2"><?=$LG['qujian.content'];//说明备注?></label>
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
