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
$pervar='goodsdata';require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');
$headtitle="添加/编辑 商品资料";$alonepage=1;//单页形式
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/head.php');
if(!$ON_gd_mosuda){exit ("<script>alert('跨境翼清关资料系统已关闭！');goBack();</script>");}

//获取,处理
$lx=par($_GET['lx']);
$gdid=par($_GET['gdid']);
if(!$lx){$lx='add';}


if($lx=='edit'&&!$gdid){exit ("<script>alert('gdid{$LG['pptError']}');goBack();</script>");}

if($gdid){$rs=FeData('gd_mosuda','*',"gdid='{$gdid}'");}else{$_GET['copy']=1;}

//生成令牌密钥(为安全要放在所有验证的最后面)
$token=new Form_token_Core();
$tokenkey= $token->grante_token("goodsdata".$gdid);
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
  
 <?php 	
 if($_GET['ret']==1){echo '<div class="alert alert-success">添加成功</div>';} 
 elseif($_GET['ret']==2){echo '<div class="alert alert-success">修改成功</div>';} 
 ?> 
  
  <form action="save.php" method="post" class="form-horizontal form-bordered" name="xingao">
    <input name="lx" type="hidden" value="<?=add($lx)?>">
    <input name="gdid" type="hidden" value="<?=$rs['gdid']?>">
    <input name="tokenkey" type="hidden" value="<?=$tokenkey?>">
    <div class="tab-content">
        <div class="tab-pane active" id="tab_1">
          <div class="form">
            <div class="form-body">
              <div class="portlet">
                <div class="portlet-body form" style="display: block;"> 
                  <!--表单内容-->

                  
                  <div class="form-group">
                    <label class="control-label col-md-2">可用</label>
                    <div class="col-md-10">
                      <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                        <input type="checkbox" class="toggle" name="checked" value="1"  <?php if($rs['checked']||$lx=='add'){echo 'checked';}?> />
                      </div>
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="control-label col-md-2">排序</label>
                    <div class="col-md-10">
                      <input type="text" class="form-control input-xsmall"  name="myorder" value="<?=spr($rs['myorder'])?>">
                      <span class="help-block">越大越排前 (可不设置，默认按会员使用次数自动排序)</span>
                    </div>
                  </div>
                 
                 <div class="form-group">&nbsp;</div>
                
                  
<div class="form-group">
  <label class="control-label col-md-2">备案情况</label>
  <div class="col-md-10  has-error">
      <select  class="form-control select2me" name="record" data-placeholder="备案情况" required style="width:120px;">
           <option></option>
		   <?php if($lx=='add'){$rs['record']=2;}?>
		  <?=Record($rs['record'],1)?>
      </select>
  </div>
</div>
                  
<div class="form-group">
  <label class="control-label col-md-2">商品货号</label>
  <div class="col-md-10 has-error">
    <input type="text" class="form-control input-medium" name="itemNo"  value="<?=cadd($rs['itemNo'])?>" required>
  </div>
</div>
<div class="form-group">
  <label class="control-label col-md-2">HYG备案号</label>
  <div class="col-md-10 has-error">
    <input type="text" class="form-control input-medium" name="HYG"  value="<?=cadd($rs['HYG'])?>" required>
  </div>
</div>
<div class="form-group">
  <label class="control-label col-md-2">行邮税号</label>
  <div class="col-md-10 has-error">
    <input type="text" class="form-control input-medium" name="taxCode"  value="<?=cadd($rs['taxCode'])?>" required>
  </div>
</div>
<div class="form-group">
  <label class="control-label col-md-2">行邮税率</label>
  <div class="col-md-10 has-error">
    <input type="text" class="form-control input-small" name="taxes"  value="<?=spr($rs['taxes'])?>" required>%
  </div>
</div>
<div class="form-group">
  <label class="control-label col-md-2">消费税</label>
  <div class="col-md-10">
    <input type="text" class="form-control input-small" name="consumptionTax"  value="<?=spr($rs['consumptionTax'])?>">%
  </div>
</div>
<div class="form-group">
  <label class="control-label col-md-2">增值税</label>
  <div class="col-md-10">
    <input type="text" class="form-control input-small" name="valueTax"  value="<?=spr($rs['valueTax'])?>">%
  </div>
</div>
<div class="form-group">
  <label class="control-label col-md-2">综合税率</label>
  <div class="col-md-10">
    <input type="text" class="form-control input-small" name="comprehensiveTax"  value="<?=spr($rs['comprehensiveTax'])?>">%
  </div>
</div>
<div class="form-group">
  <label class="control-label col-md-2">商品备案号</label>
  <div class="col-md-10 has-error">
    <input type="text" class="form-control input-medium" name="recordCode"  value="<?=cadd($rs['recordCode'])?>"required>
  </div>
</div>
<div class="form-group">
  <label class="control-label col-md-2">商检HS CODE</label>
  <div class="col-md-10">
    <input type="text" class="form-control input-medium" name="HSCode"  value="<?=cadd($rs['HSCode'])?>">
  </div>
</div>
<div class="form-group">
  <label class="control-label col-md-2">海关物品税号</label>
  <div class="col-md-10 has-error">
    <input type="text" class="form-control input-medium" name="itemsTaxCode"  value="<?=cadd($rs['itemsTaxCode'])?>"  required>
  </div>
</div>

<div class="form-group">&nbsp;</div>
<div class="form-group">
  <label class="control-label col-md-2">描述/分类</label>
  <div class="col-md-10">
    <input type="text" class="form-control input-medium" name="types"  value="<?=cadd($rs['types'])?>">
  </div>
</div>

<div class="form-group">
  <label class="control-label col-md-2">商品名称</label>
  <div class="col-md-10 has-error">
    <input type="text" class="form-control input-xlarge" name="name"  value="<?=cadd($rs['name'])?>" required>
  </div>
</div>
<div class="form-group">
  <label class="control-label col-md-2">规格型号</label>
  <div class="col-md-10 has-error">
    <input type="text" class="form-control input-medium" name="spec"  value="<?=cadd($rs['spec'])?>" required>
  </div>
</div>
<div class="form-group">
  <label class="control-label col-md-2">单位</label>
  <div class="col-md-10 has-error">
    <input type="text" class="form-control input-small" name="unit"  value="<?=cadd($rs['unit'])?>" required>
  </div>
</div>
<div class="form-group">
  <label class="control-label col-md-2">商品条码</label>
  <div class="col-md-10 has-error">
    <input type="text" class="form-control input-medium" name="barcode"  value="<?=cadd($rs['barcode'])?>" required>
  </div>
</div>

<div class="form-group">
  <label class="control-label col-md-2">备注</label>
  <div class="col-md-10">
    <textarea class="form-control" name="content" rows="2"><?=cadd($rs['content'])?></textarea>
  </div>
</div>

<div class="form-group">&nbsp;</div>

<div class="form-group">
  <label class="control-label col-md-2">生产企业</label>
  <div class="col-md-10 has-error">
    <input type="text" class="form-control input-medium" name="merchants"  value="<?=cadd($rs['merchants'])?>" required>
  </div>
</div>
<div class="form-group">
  <label class="control-label col-md-2">品牌</label>
  <div class="col-md-10 has-error">
    <input type="text" class="form-control input-medium" name="brand"  value="<?=cadd($rs['brand'])?>" required>
  </div>
</div>
<div class="form-group">
  <label class="control-label col-md-2">产地</label>
  <div class="col-md-10 has-error">
    <input type="text" class="form-control input-medium" name="producer" value="<?=cadd($rs['producer'])?>" required>
  </div>
</div>

<div class="form-group">
  <label class="control-label col-md-2">毛重/净重</label>
  <div class="col-md-10">
    <input type="text" class="input_txt_red form-control input-small tooltips" data-container="body" data-placement="top" data-original-title="毛重" placeholder="毛重" name="weightGross" value="<?=spr($rs['weightGross'])?>"  required>KG
    
    &nbsp;&nbsp;&nbsp;
    <input type="text" class="input_txt_red form-control input-small tooltips" data-container="body" data-placement="top" data-original-title="净重" placeholder="净重"  name="weightSuttle" value="<?=spr($rs['weightSuttle'])?>" required>KG
   
  </div>
</div>


<div class="form-group">
  <label class="control-label col-md-2">所含成分</label>
  <div class="col-md-10">
    <textarea class="form-control" name="composition" rows="2"><?=cadd($rs['composition'])?></textarea>
  </div>
</div>
<div class="form-group">
  <label class="control-label col-md-2">超范围使用食品添加剂</label>
  <div class="col-md-10">
    <textarea class="form-control" name="foodAdditives" rows="2"><?=cadd($rs['foodAdditives'])?></textarea>
  </div>
</div>
<div class="form-group">
  <label class="control-label col-md-2">含有毒害物质</label>
  <div class="col-md-10">
    <textarea class="form-control" name="harmful" rows="2"><?=cadd($rs['harmful'])?></textarea>
  </div>
</div>
<div class="form-group">&nbsp;</div>
<div class="form-group">
  <label class="control-label col-md-2">备案价格</label>
  <div class="col-md-10 has-error">
    <input type="text" class="form-control input-small" name="recordPrice"  value="<?=spr($rs['recordPrice'])?>" required>CNY
  </div>
</div>

                  
                 
<div class="form-group">
  <label class="control-label col-md-2">商品图片</label>
  <div class="col-md-10">
<?php 
//文件上传配置
$uplx='img';//img,file
$uploadLimit='10';//允许上传文件个数(如果是单个，此设置无法，默认1)
$inputname='img[]';//保存字段名，多个时加[]

$off_water=0;//水印(不手工设置则按后台设置)
$off_narrow=1;//裁剪，缩小(不手工设置则按后台设置)
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





           <input name="copy" type="checkbox" value="1" checked="<?php if(par($_GET['copy'])){echo 'checked';}?>">提交后不清空内容(可修改后继续添加) 
        
          <button type="submit" class="btn btn-primary input-small" id="openSmt1" disabled > <i class="icon-ok"></i> <?=$LG['submit']?> </button>
          <button type="reset" class="btn btn-default" style="margin-left:30px;"> <?=$LG['reset']?> </button>
          
          <button type="button" class="btn btn-default" onClick="goBack('c');"  style="margin-left:30px;"><i class="icon-remove"></i> <?=$LG['close']?> </button>
          
        </div>
      </div>
      
        </form>
        
</div>
<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/foot.php');
?>
