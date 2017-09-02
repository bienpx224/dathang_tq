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
$pervar='pinglun';require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');
$headtitle="修改评论";
$alonepage=1;//单页形式
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/head.php');

//获取,处理
$lx=par($_GET['lx']);
$cmid=par($_GET['cmid']);
if(!$lx)
{
	exit ("<script>alert('不能在此添加！');goBack('c');</script>");
}


$token=new Form_token_Core();
$tokenkey= $token->grante_token('comments'.$cmid); //生成令牌密钥

if($lx=='edit')
{
	if(!$cmid){exit ("<script>alert('cmid{$LG['pptError']}');goBack();</script>");}
	$rs=FeData('comments','*',"cmid='{$cmid}'");
}
?>

<div class="page_ny"> 
  <!-- BEGIN PAGE HEADER-->
	<div class=""><!--单页时去掉 class="row",不然会有横滚动条,并且form 加 style="margin:20px;"-->
		<div class="col-md-12"> 
<h3 class="page-title"> <a href="javascript:void(0)" onClick="javascript:window.location.href=window.location.href;" title="<?=$LG['refresh']?>" class="gray">
        <?=$headtitle?>
        </a> <small>
        <?=cadd($rs['username'])?>
        (
        <?=cadd($rs['userid'])?>
        )
		<?=DateYmd($rs['addtime'],1) ?> </small> </h3>
    </div>
  </div>
  <!-- END PAGE HEADER--> 
  
  <!-- BEGIN PAGE CONTENT-->
  
  <form action="save.php" method="post" class="form-horizontal form-bordered" name="xingao"><!--删除 style="margin:20px;"-->
    <input name="lx" type="hidden" value="<?=add($lx)?>">
    <input name="cmid" type="hidden" value="<?=$rs['cmid']?>">
    <input name="tokenkey" type="hidden" value="<?=$tokenkey?>">
    <div><!-- class="tabbable tabbable-custom boxless"-->
      <div class="tab-content">
        <div class="tab-pane active" id="tab_1">
          <div class="form">
            <div class="form-body">
              <div class="portlet">
                <!--<div class="portlet-title">
                  <div class="caption"><i class="icon-reorder"></i><strong>评论内容</strong></div>
                  <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
                </div>-->
                <div class="portlet-body form"> 
                  <!--表单内容-->
                  
                  <div class="form-group">
                    <label class="control-label col-md-2"><?=$LG['checked']//审核?></label>
                    <div class="col-md-10">
                      <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                        <input type="checkbox" class="toggle" name="checked" value="1"  <?php if($rs['checked']){echo 'checked';}?> />
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2">内容</label>
                    <div class="col-md-10">
                      <textarea  class="form-control" rows="3" name="content"><?=cadd($rs['content'])?>
</textarea>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2"><?=$LG['img']//图片?></label>
                    <div class="col-md-10">
                       <?php 
//文件上传配置
$uplx='img';//img,file
$uploadLimit='10';//允许上传文件个数(如果是单个，此设置无法，默认1)
$inputname='img[]';//保存字段名，多个时加[]

//$off_water=0;//水印(不手工设置则按后台设置)
//$off_narrow=1;//是否裁剪
//$img_w=$certi_w;$img_h=$certi_h;//裁剪尺寸：证件
$img_w=$other_w;$img_h=$other_h;//裁剪尺寸：通用
//$img_w=500;$img_h=500;//裁剪尺寸：指定
include($_SERVER['DOCUMENT_ROOT'].'/public/uploadify/call.php');
?>
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
