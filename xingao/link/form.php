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
$pervar='qita';require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');
$headtitle="友情链接";
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/head.php');



//获取,处理
$lx=par($_GET['lx']);
$id=par($_GET['id']);
if(!$lx){$lx='add';}

if($lx=='edit')
{
	if(!$id){exit ("<script>alert('id{$LG['pptError']}');goBack();</script>");}
	$rs=FeData('link','*',"id='{$id}'");
}

//生成令牌密钥(为安全要放在所有验证的最后面)
$token=new Form_token_Core();
$tokenkey= $token->grante_token("link");

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
  <input name="id" type="hidden" value="<?=$rs['id']?>">
  <input name="tokenkey" type="hidden" value="<?=$tokenkey?>">
    <div class="tabbable tabbable-custom boxless">
      <div class="tab-content">
        <div class="tab-pane active" id="tab_1">
          <div class="form">
            <div class="form-body">
            <div class="portlet">
              
              <div class="portlet-body form" style="display: block;"> 
                <!--表单内容-->
              
                <div class="form-group">
                  <label class="control-label col-md-2">所属分类</label>
                  <div class="col-md-0 has-error">
                  <select  class="form-control input-medium select2me" data-placeholder="Select..." name="class">
<?php
LinkClass($rs['class'],1);
?>
                  </select>
              </div>
                </div>
                
                <div class="form-group">
                  <label class="control-label col-md-2">名称</label>
                  <div class="col-md-10 has-error">
                
                    <input type="text" class="form-control input-medium" name="name" required value="<?=cadd($rs['name'])?>">
                 
                  </div>
                </div>
                <div class="form-group">
                  <label class="control-label col-md-2">网址</label>
                  <div class="col-md-10 has-error">
                    <input type="text" class="form-control input-medium"  name="url" value="<?=cadd($rs['url'])?>" required><span class="help-block">以http://开头</span>
                  </div>
                  
                </div>
                
             <div class="form-group">
                  <label class="control-label col-md-2"><?=$LG['img']//图片?></label>
                  <div class="col-md-10">

<?php 
//文件上传配置
$uplx='img';//img,file
$uploadLimit='10';//允许上传文件个数(如果是单个，此设置无法，默认1)
$inputname='img';//保存字段名，多个时加[]

$off_water=0;//水印(不手工设置则按后台设置)
$off_narrow=1;//是否裁剪
//$img_w=$certi_w;$img_h=$certi_h;//裁剪尺寸：证件
//$img_w=$other_w;$img_h=$other_h;//裁剪尺寸：通用
$img_w=500;$img_h=500;//裁剪尺寸：指定
include($_SERVER['DOCUMENT_ROOT'].'/public/uploadify/call.php');
?>


                   
                  </div>
                </div>
                               
                <div class="form-group">
                  <label class="control-label col-md-2">显示</label>
                  <div class="col-md-10">
                    <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                      <input type="checkbox" class="toggle" name="checked" value="1"  <?php if($rs['checked']||$lx=='add'){echo 'checked';}?> />
                    </div>
                  </div>
                </div>
             
                
             <div class="form-group">
                  <label class="control-label col-md-2">排序</label>
                  <div class="col-md-10">
                    <input type="text" class="form-control input-xsmall"  name="myorder" value="<?=cadd($rs['myorder'])?>"><span class="help-block">越大越排前</span>
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
