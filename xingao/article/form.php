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
$headtitle="信息";
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/head.php');


//获取,处理
$lx=par($_GET['lx']);
$id=par($_GET['id']);
$classid=par($_GET['classid']);
if(!$lx){$lx='add';}

$cr=ClassData($classid);

if($lx=='edit')
{
	if(!$id){exit ("<script>alert('id{$LG['pptError']}');goBack();</script>");}
}
if($id){$rs=FeData('article','*',"id='{$id}'");}

//生成令牌密钥(为安全要放在所有验证的最后面)
$token=new Form_token_Core();
$tokenkey= $token->grante_token('article'.$id);
?>
<link rel="stylesheet" href="/public/kindeditor/themes/default/default.css" />
<script charset="utf-8" src="/public/kindeditor/kindeditor-min.js"></script>
<script charset="utf-8" src="/public/kindeditor/lang/zh_CN.js"></script>

<div class="page_ny"> 
  <!-- BEGIN PAGE HEADER-->
	<div class="row"><!--单页时去掉 class="row",不然会有横滚动条,并且form 加 style="margin:20px;"-->
		<div class="col-md-12"> 
<h3 class="page-title">
      <a href="../class/list.php" class="gray">栏目列表</a> >
      
       <a href="javascript:history.go(-1)" title="<?=$LG['back']?>"><i class="icon-reply"></i></a> <a href="list.php?so=1&classid=<?=$classid?>" class="gray"><?=$cr['name'.$LT];?></a> >
       <a href="javascript:void(0)" onClick="javascript:window.location.href=window.location.href;" title="<?=$LG['refresh']?>" class="gray">
        <?=$headtitle?>
        </a>  </h3>
    </div>
  </div>
  <!-- END PAGE HEADER--> 
  
  <!-- BEGIN PAGE CONTENT-->
  
  <form action="save.php" method="post" class="form-horizontal form-bordered" name="xingao">
    <input name="lx" type="hidden" value="<?=add($lx)?>">
    <input name="id" type="hidden" value="<?=$rs['id']?>">
    <input name="tokenkey" type="hidden" value="<?=$tokenkey?>">
    <input name="addtime" type="hidden" value="<?=$rs['addtime']?$rs['addtime']:time()?>"><!--修改时获取来生成HTML-->
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
            
              <div>
               <div class="portlet-body form" style="display: block;"> 
                  <!--表单内容-->
                  
                  <div class="form-group">
                    <label class="control-label col-md-2">所属栏目</label>
                    <div class="col-md-10 has-error">
                      <select  class="form-control input-medium select2me" data-placeholder="Select..." name="classid" >
                      <option value="0" ></option>
					  <?php
					  if($rs['classid']){$classid=$rs['classid'];}
                      LevelClass(0,0,$classid,'1');
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
  <div class="help-block" style="clear:both;">
  <br>
  新闻公告栏目推荐显示：
  1级显示首页；
  2级显示首页、会员中心；
  3级显示首页、会员中心、会员登录弹出；
  </div>
                      
                      
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
                    <label class="control-label col-md-2">前台显示</label>
                    <div class="col-md-10">
                      <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                        <input type="checkbox" class="toggle" name="checked" value="1"  <?php if($rs['checked']||$lx=='add'){echo 'checked';}?> />
                      </div>
                    </div>
                  </div>
                   
                 </div>
              </div>
                 
                  



			   <!---->              
                  
               
            </div>
          </div>
        </div>
 
 
 
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
                    <div class="col-md-4">
<?php 
//文件上传配置
$uplx='img';//img,file
$uploadLimit='10';//允许上传文件个数(如果是单个，此设置无法，默认1)
$inputname='img'.$language;//保存字段名，多个时加[]

$off_water=0;//水印(不手工设置则按后台设置)
$off_narrow=1;//是否裁剪
//$img_w=$certi_w;$img_h=$certi_h;//裁剪尺寸：证件
//$img_w=$other_w;$img_h=$other_h;//裁剪尺寸：通用
$img_w=500;$img_h=500;//裁剪尺寸：指定
include($_SERVER['DOCUMENT_ROOT'].'/public/uploadify/call.php');
?>
                    </div>
                    
                    <label class="control-label col-md-2">幻灯图</label>
                    <div class="col-md-4">
<?php 
//文件上传配置
$uplx='img';//img,file
$uploadLimit='10';//允许上传文件个数(如果是单个，此设置无法，默认1)
$inputname='hdimg'.$language;//保存字段名，多个时加[]

$off_water=0;//水印(不手工设置则按后台设置)
$off_narrow=1;//是否裁剪
//$img_w=$certi_w;$img_h=$certi_h;//裁剪尺寸：证件
//$img_w=$other_w;$img_h=$other_h;//裁剪尺寸：通用
$img_w=2500;$img_h=1000;//裁剪尺寸：指定
include($_SERVER['DOCUMENT_ROOT'].'/public/uploadify/call.php');
?>
                    </div>
                  </div>
                  
                  <div class="form-group">
                    <label class="control-label col-md-2">文件下载</label>
                    <div class="col-md-10">
<?php 
//文件上传配置
$uplx='file';//img,file
$uploadLimit='10';//允许上传文件个数(如果是单个，此设置无法，默认1)
$inputname='dow'.$language;//保存字段名，多个时加[]
include($_SERVER['DOCUMENT_ROOT'].'/public/uploadify/call.php');
?>
                    </div>
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
 
<?php if($LG_i>1&&1==2){?><!--未做：原因不方便，比如某个信息不希望有图片或下载文件时，就无法实现-->
<div class="xats"><font class="red2">&raquo; 以上资料留空时，将默认用第一种语言的资料</font></div>
<?php }?>


             
        </div>
      </div>
      </div>
		<?php 
	}
}
?>        
        
                
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
