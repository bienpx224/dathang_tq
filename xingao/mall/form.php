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
$pervar='mall';require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');
$headtitle="商品";
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/head.php');
if(!$off_mall)
{
	exit ("<script>alert('商城系统未开启,无法使用！');goBack('uc');</script>");
}

//获取,处理
$lx=par($_GET['lx']);
$mlid=par($_GET['mlid']);
$classid=par($_GET['classid']);
if(!$lx){$lx='add';}

$cr=ClassData($classid);

if($lx=='edit')
{
	if(!$mlid){exit ("<script>alert('mlid{$LG['pptError']}');goBack();</script>");}
	
	$rs=FeData('mall','*',"mlid='{$mlid}'");
	warehouse_per('ts',$zhi=$rs['warehouse']);//验证可管理的仓库
}

//生成令牌密钥(为安全要放在所有验证的最后面)
$token=new Form_token_Core();
$tokenkey= $token->grante_token("mall");

?>
<link rel="stylesheet" href="/public/kindeditor/themes/default/default.css" />
<script charset="utf-8" src="/public/kindeditor/kindeditor-min.js"></script>
<script charset="utf-8" src="/public/kindeditor/lang/zh_CN.js"></script>

<div class="page_ny"> 
  <!-- BEGIN PAGE HEADER-->
	<div class="row"><!--单页时去掉 class="row",不然会有横滚动条,并且form 加 style="margin:20px;"-->
		<div class="col-md-12"> 
<h3 class="page-title"><a href="javascript:history.go(-1)" title="<?=$LG['back']?>"><i class="icon-reply"></i></a> <a href="../class/list.php" class="gray">栏目列表</a> >  <a href="list.php?so=1&classid=<?=$classid?>" class="gray">
        <?=$cr['name'.$LT]?$cr['name'.$LT]:'商品列表'?></a> > <a href="javascript:void(0)" onClick="javascript:window.location.href=window.location.href;" title="<?=$LG['refresh']?>" class="gray">
        <?=$headtitle?>
        </a>  </h3>
    </div>
  </div>
  <!-- END PAGE HEADER--> 
  
  <!-- BEGIN PAGE CONTENT-->
  
  <form action="save.php" method="post" class="form-horizontal form-bordered" name="xingao">
    <input name="lx" type="hidden" value="<?=add($lx)?>">
    <input name="mlid" type="hidden" value="<?=$rs['mlid']?>">
    <input name="tokenkey" type="hidden" value="<?=$tokenkey?>">
    <input name="addtime" type="hidden" value="<?=$rs['addtime']?$rs['addtime']:time()?>">
    <!--修改时获取来生成HTML-->
    <div class="tabbable tabbable-custom boxless">
   
      <ul class="nav nav-tabs">
        <li class="active"><a href="#tab_1" data-toggle="tab">信息属性</a></li>
<?php 
//语言字段处理--
if(!$LGList){$LGList=languageType('',3);}
if($LGList)
{
	foreach($LGList as $arrkey=>$language)
	{
		?>
        <li><a href="#tab_<?=$language?>" data-toggle="tab"><?=languageType($language)?></a></li>
		<?php 
	}
}
?>
      </ul>
      
      
      <div class="tab-content">
        <div class="tab-pane active" id="tab_1">
          <div class="form">
            <div class="form-body">
              <div class="portlet">
                <div class="portlet-title">
                  <div class="caption"><i class="icon-reorder"></i>基本资料</div>
                  <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
                </div>
                <div class="portlet-body form" style="display: block;"> 
                  <!--表单内容-->
                  
                  <div class="form-group">
                    <label class="control-label col-md-2">所属栏目</label>
                    <div class="col-md-10 has-error">
                      <select  class="form-control input-medium select2me" data-placeholder="Select..." name="classid" >
                        <option value="0" ></option>
                      <?php 
					  if($rs['classid']){$classid=$rs['classid'];}
                      LevelClass(0,0,$classid,'3',0);
                      ?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2">信息属性</label>
                    <div class="col-md-10">
                      <select  class="form-control input-small select2me" data-placeholder="Select..." name="isgood" style="float:left; margin-right:10px;" title="级别越大越排前">
                        <?php isgood((int)$rs['isgood'],1)?>
                      </select>
                      <select  class="form-control input-small select2me" data-placeholder="Select..." name="ishead" style="float:left; margin-right:10px;" title="级别越大越排前">
                        <?php ishead((int)$rs['ishead'],1)?>
                      </select>
                      <select  class="form-control input-small select2me" data-placeholder="Select..." name="istop" style="float:left; margin-right:10px;" title="级别越大越排前">
                        <?php istop((int)$rs['istop'],1)?>
                      </select>
                      <select  class="form-control select2me" data-placeholder="Select..." name="contentt" style="float:left; margin-right:10px; width:150px;">
                        <?php CTemplate(cadd($rs['contentt']))?>
                      </select>
                    </div>
                  </div>
                  
                   <div class="form-group">
                    <label class="control-label col-md-2">标题颜色</label>

                    <div class="input-group color colorpicker-default input-small" data-color="<?=cadd($rs['titlecolor'])?>" data-color-format="rgba">
                    <input type="text" class="form-control" value="<?=cadd($rs['titlecolor'])?>" name="titlecolor" title="留空则默认" style="width:90px;" onClick="select();">
                    <span class="input-group-btn">
                    <button class="btn btn-default" type="button" title="列表标题颜色"><i style="background-color: <?=cadd($rs['titlecolor'])?>;"></i>&nbsp;</button>
                    </span>
                   
                     </div> 
                  </div> 
                                  

                  <div class="form-group">
                    <label class="control-label col-md-2">上架</label>
                    <div class="col-md-10">
                      <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                        <input type="checkbox" class="toggle" name="checked" value="1"  <?php if($rs['checked']||$lx=='add'){echo 'checked';}?> />
                      </div>
                    </div>
                  </div>

                </div>
              </div>
              <!---->
              
              <div class="portlet">
                <div class="portlet-title">
                  <div class="caption"><i class="icon-reorder"></i>商品资料</div>
                  <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
                </div>
                <div class="portlet-body form" style="display: block;"> 
                  <!--表单内容-->
                <div class="form-group">
                    <label class="control-label col-md-2">正品保证</label>
                    <div class="col-md-10">
                      <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                        <input type="checkbox" class="toggle" name="ensure" value="1"  <?php if($rs['ensure']||$lx=='add'){echo 'checked';}?> />
                      </div>
                    </div>
                  </div>
                <div class="form-group">
                    <label class="control-label col-md-2">编号</label>
                    <div class="col-md-10">
                      <input type="text" class="form-control input-medium"  name="coding" value="<?=cadd($rs['coding'])?>" >
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2">品牌</label>
                    <div class="col-md-10">
                      <select  class="form-control input-medium select2me" data-placeholder="Select..." name="brand">
                        <?=ClassifyAll(6,$rs['brand'])?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2">单价</label>
                    <div class="col-md-10">
                      <input type="text" class="form-control input-small"  name="price" value="<?=spr($rs['price'])?>" >
                      <?=$XAmc?>
                      <span class="help-block">空或0为免费</span> </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2">其他收费</label>
                    <div class="col-md-10">
                      <input type="text" class="form-control input-small"  name="price_other" value="<?=spr($rs['price_other'])?>" >
                      <?=$XAmc?>
                      <span class="help-block">不限数量和重量只收费一次</span> </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2">其他收费原因</label>
                    <div class="col-md-10">
                      <input type="text" class="form-control"  name="price_otherwhy" value="<?=cadd($rs['price_otherwhy'])?>" >
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2">市场价格</label>
                    <div class="col-md-10">
                      <input type="text" class="form-control input-small"  name="price_market" value="<?=cadd($rs['price_market'])?>" >
                      <span class="help-block">无实际用处，只是展示 (同时填写币种,格式:19.8美元)</span> </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2">数量</label>
                    <div class="col-md-10">
                      <input type="text" class="form-control input-small"  name="number" value="<?=cadd($rs['number'])?>" >
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2">限量出售</label>
                    <div class="col-md-10">
                      <input type="text" class="form-control input-small"  name="number_limit" value="<?=cadd($rs['number_limit'])?>" >
                      <span class="help-block">一次最多可购买多少件(限量时每个会员只能购买一次),空或0为不限</span> </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2">重量</label>
                    <div class="col-md-10">
                      <input type="text" class="form-control input-small"  name="weight" value="<?=spr($rs['weight'])?>" >
                      <?=$XAwt?>
                    </div>
                  </div>
                  <?php if($off_integral){?>
                  <div class="form-group">
                    <label class="control-label col-md-2">可使用积分</label>
                    <div class="col-md-10">
                      <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                        <input type="checkbox" class="toggle" name="integral_use" value="1"  <?php if($rs['integral_use']||$lx=='add'){echo 'checked';}?> />
                      </div>
                    </div>
                  </div>
                  <?php }?>
                  <?php if($off_integral&&$integral_mall>0){?>
                  <div class="form-group">
                    <label class="control-label col-md-2">赠送积分</label>
                    <div class="col-md-10">
                      <div class="radio-list">
                        <label>
                          <input type="radio" name="integral_to"  value="1" <?php if($rs['integral_to']==1||$lx=='add'){echo 'checked';}?>>
                          无消费积分时才送 (用积分抵消的部分不算) </label>
                        <label>
                          <input type="radio" name="integral_to" value="2"  <?php if($rs['integral_to']==2){echo 'checked';}?>>
                          都送(用积分抵消的部分不算) </label>
                        <label>
                          <input type="radio" name="integral_to" value="0"  <?php if(!$rs['integral_to']&&$lx!='add'){echo 'checked';}?>>
                          不送 </label>
                      </div>
                    </div>
                  </div>
                  <?php }?>
                <div class="form-group">
                    <label class="control-label col-md-2">可存仓库</label>
                    <div class="col-md-10">
                    <select multiple="multiple" class="multi-select" id="my_multi_select2" name="warehouse[]"><!--id="my_multi_select2" 不能改-->
                    <?=warehouse($rs['warehouse'],1,1,1)?>
                    </select>
                    <span class="help-block">会员购买时可存入的仓库,留空表示支持全部并且以后增加的仓库也会支持</span> 
                    <span class="help-block">
					  <?php 
					  if($WHPerShow){echo '可管理的仓库：'.$WHPerShow.' (不显示无权管理的仓库)';}
					  ?>
					</span>
                               
                    </div>
                  </div>
 <div class="form-group">
                    <label class="control-label col-md-2">可购套餐</label>
                    <div class="col-md-10">
                    <textarea class="form-control input-medium" name="package" rows="5" ><?=cadd($rs['package'])?>
</textarea>
                    <span class="help-block">每行一个</span>          
                    </div>
                  </div>
                  
 <div class="form-group">
                    <label class="control-label col-md-2">可购尺寸</label>
                    <div class="col-md-10">
                    <textarea class="form-control input-medium" name="size" rows="5" ><?=cadd($rs['size'])?>
</textarea>
                    <span class="help-block">每行一个</span>          
                    </div>
                  </div>
                  
  <div class="form-group">
                    <label class="control-label col-md-2">可购颜色</label>
                    <div class="col-md-10">
                    <textarea class="form-control input-medium" name="color" rows="5" ><?=cadd($rs['color'])?>
</textarea>
                    <span class="help-block">每行一个</span>          
                    </div>
                  </div>
                  
                  
                  
                                                                     
                </div>
              </div>
              <!---->
              
              
             <div class="portlet">
                <div class="portlet-title">
                  <div class="caption"><i class="icon-reorder"></i>包裹资料 (包裹入库时自动复制的物品信息)</div>
                  <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
                </div>
                <div class="portlet-body form" style="display: block;"> 
                  <!--表单内容-->
                  <div class="form-group">
                    <label class="control-label col-md-2">类别</label>
                    <div class="col-md-10">
                      <select  class="form-control input-medium select2me" data-placeholder="Select..." name="category">
                        <?php ClassifyAll(4,$rs['category'])?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2">品名</label>
                    <div class="col-md-10">
                      <input type="text" class="form-control input-medium"  name="goods" value="<?=cadd($rs['goods'])?>" >
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2">规格</label>
                    <div class="col-md-10">
                      <input type="text" class="form-control input-small"  name="spec" value="<?=cadd($rs['spec'])?>" >
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2">单位</label>
                    <div class="col-md-10">
                      <select  class="form-control input-small select2me" data-placeholder="Select..." name="unit">
                        <?=ClassifyAll(5,$rs['unit'])?>
                      </select>
                    </div>
                  </div>
                </div>
              </div>
             <!---->              
              
              
              
              
              
              
              
              
              
            </div>
          </div>
        </div>
        <!---->
        
        
<?php 
//语言字段处理--
if(!$LGList){$LGList=languageType('',3);}
if($LGList)
{
	$LG_i=0;
	foreach($LGList as $arrkey=>$language)
	{
		$LG_i+=1;
		?>
        
 		<!---------------------------------<?=$language?>--------------------------------->
         <div class="tab-pane" id="tab_<?=$language?>">
          <div class="form">
            <div class="form-body"> 
            
            <div>
               <div class="portlet-body form" style="display: block;"> 
                  <!--表单内容-->

                 <div class="form-group">
                    <label class="control-label col-md-2">标题</label>
                    <div class="col-md-10 <?=$LG_i==1?'has-error':''?>">
                      <input type="text" class="form-control" name="title<?=$language?>"  value="<?=cadd($rs['title'.$language])?>" <?=$LG_i==1?'required':''?>>
                    </div>
                  </div>
                  


                  <div class="form-group">
                    <label class="control-label col-md-2">标题图片</label>
                    <div class="col-md-10">
<?php 
//文件上传配置
$uplx='img';//img,file
$uploadLimit='10';//允许上传文件个数(如果是单个，此设置无法，默认1)
$inputname='titleimg'.$language;//保存字段名，多个时加[]

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
                    <label class="control-label col-md-2">商品图片</label>
                    <div class="col-md-10">
<?php 
//文件上传配置
$uplx='img';//img,file
$uploadLimit='20';//允许上传文件个数(如果是单个，此设置无法，默认1)
$inputname='img'.$language.'[]';//保存字段名，多个时加[]

//$off_water=0;//水印(不手工设置则按后台设置)
//$off_narrow=1;//是否裁剪
//$img_w=$certi_w;$img_h=$certi_h;//裁剪尺寸：证件
$img_w=$other_w;$img_h=$other_h;//裁剪尺寸：通用
//$img_w=500;$img_h=500;//裁剪尺寸：指定
include($_SERVER['DOCUMENT_ROOT'].'/public/uploadify/call.php');
?>
                    </div>
                  </div>
                  
                  
                  
                  <div class="form-group">
                    <label class="control-label col-md-2">卖点</label>
                    <div class="col-md-10">
                    <input type="text" class="form-control"  name="selling<?=$language?>" value="<?=cadd($rs['selling'.$language])?>" >
                      <span class="help-block">卖点或重要说明</span> </div>
                  </div>
                  
                  
                  
                  
                  <div class="form-group">
                    <label class="control-label col-md-2">绑定链接</label>
                    <div class="col-md-10">
                      <input type="text" class="form-control"  name="url<?=$language?>" value="<?=cadd($rs['url'.$language])?>" >
                      <span class="help-block">绑定后将使用此链接。以http://开头则用新窗口打开反之则本窗口打开（外站必须以http://开头）</span> </div>
                  </div>

                  <div class="form-group">
                    <label class="control-label col-md-2">SEO标题</label>
                    <div class="col-md-10">
                      <input type="text" class="form-control" name="seotitle<?=$language?>"  value="<?=cadd($rs['seotitle'.$language])?>" maxlength="40" id="maxlength_defaultconfig">
                      <span class="help-block">用英文(-)符号分开</span> </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2">SEO关键字</label>
                    <div class="col-md-10">
                      <input type="text" class="form-control" name="seokey<?=$language?>"  value="<?=cadd($rs['seokey'.$language])?>" maxlength="50" id="maxlength_defaultconfig">
                      <span class="help-block"> 填写信息关键词,用英文“,”符号分开</span> </div>
                  </div>
                  <!--内容-->
                   <div class="form-group">
                    <label class="control-label col-md-2">简介</label>
                    <div class="col-md-10">
                      <textarea class="form-control" name="intro<?=$language?>" rows="2" ><?=cadd($rs['intro'.$language])?>
</textarea>
                      <span class="help-block">留空时自动获取内容</span> </div>
                  </div>
                  <div class="form-group">
                    <div class="col-md-12">
<!--调用编辑器（完整型）--------------------------------------------------------------------------------> 
<script>
  var editor;
  KindEditor.ready(function(K) {
	  editor = K.create('textarea[id="editor<?=$language?>"]', {
		  allowFileManager : true
	  });
	  K('input[name=getHtml]').click(function(e) {
		  alert(editor.html());
	  });
	  K('input[name=isEmpty]').click(function(e) {
		  alert(editor.isEmpty());
	  });
	  K('input[name=getText]').click(function(e) {
		  alert(editor.text());
	  });
	  K('input[name=selectedHtml]').click(function(e) {
		  alert(editor.selectedHtml());
	  });
	  K('input[name=setHtml]').click(function(e) {
		  editor.html('<h3>Hello KindEditor</h3>');
	  });
	  K('input[name=setText]').click(function(e) {
		  editor.text('<h3>Hello KindEditor</h3>');
	  });
	  K('input[name=insertHtml]').click(function(e) {
		  editor.insertHtml('<strong>插入HTML</strong>');
	  });
	  K('input[name=appendHtml]').click(function(e) {
		  editor.appendHtml('<strong>添加HTML</strong>');
	  });
	  K('input[name=clear]').click(function(e) {
		  editor.html('');
	  });
  });
</script>
<script src="/public/kindeditor/php/JS.php" type="text/javascript"></script>
<textarea id="editor<?=$language?>" style="width:100%;height:500px;visibility:hidden;" name="content<?=$language?>"><?=caddhtml($rs['content'.$language])?></textarea>
                      <p>
                        <input name="resave<?=$language?>" type="checkbox" value="1" checked="checked">
                        保存远程文件
                        <input name="rewater<?=$language?>" type="checkbox" value="1" checked="checked">
                        保存远程文件时加水印
                        <input type="button" name="clear" value="清空内容" />
                        <input type="reset" name="reset" value="重写" />
                      </p>
                    </div>
                  </div>
                  <!--内容结束-->
                  
                </div>
              </div>
 
        </div>
      </div>
      </div>
<?php 
	}
}
?>

        
        
        
        
        
        <!---->        
        
                
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
