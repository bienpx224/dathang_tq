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
$headtitle="栏目";
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/head.php');


//获取,处理
$lx=par($_GET['lx']);
$classid=par($_GET['classid']);
if(!$lx){$lx='add';}

if($lx=='edit')
{
	if(!$classid){exit ("<script>alert('classid{$LG['pptError']}');goBack();</script>");}
	$rs=FeData('class','*',"classid='{$classid}'");
}

//生成令牌密钥(为安全要放在所有验证的最后面)
$token=new Form_token_Core();
$tokenkey= $token->grante_token("class");

?>
<link rel="stylesheet" href="/public/kindeditor/themes/default/default.css" />
<script charset="utf-8" src="/public/kindeditor/kindeditor-min.js"></script>
<script charset="utf-8" src="/public/kindeditor/lang/zh_CN.js"></script>

<div class="page_ny"> 
  <!-- BEGIN PAGE HEADER-->
	<div class="row"><!--单页时去掉 class="row",不然会有横滚动条,并且form 加 style="margin:20px;"-->
		<div class="col-md-12"> 
<h3 class="page-title"> <a href="javascript:history.go(-1)" title="<?=$LG['back']?>"><i class="icon-reply"></i></a> <a href="list.php" class="gray" target="_parent"><?=$LG['backList']?></a> > <a href="javascript:void(0)" onClick="javascript:window.location.href=window.location.href;" title="<?=$LG['refresh']?>" class="gray">
        <?=$headtitle?>
        </a> <small>
        <?=cadd($rs['name'])?>
        </small> </h3>
    </div>
  </div>
  <!-- END PAGE HEADER--> 
  
  <!-- BEGIN PAGE CONTENT-->
  
  <form action="save.php" method="post" class="form-horizontal form-bordered" name="xingao">
    <input name="lx" type="hidden" value="<?=add($lx)?>">
    <input name="classid" type="hidden" value="<?=$rs['classid']?>">
    <input name="tokenkey" type="hidden" value="<?=$tokenkey?>">
    <div class="tabbable tabbable-custom boxless">
      <ul class="nav nav-tabs">
        <li class="active"><a href="#tab_1" data-toggle="tab">栏目属性</a></li>
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
                    <label class="control-label col-md-2">所属分类</label>
                    <div class="col-md-10 has-error">
                      <select  class="form-control input-medium select2me" data-placeholder="Select..." name="bclassid" >
                      <option value="0" >根目录</option>
					  <?php
                      LevelClass(0,0,par($_REQUEST['bclassid']).$rs['bclassid'],'1,2,3,4');
                      ?>
                      </select>
                    </div>
                  </div>
                  
                  <div class="form-group">
                    <label class="control-label col-md-2">存放目录</label>
                    <div class="col-md-10 has-error">
                    <?php $path=EndArray(cadd($rs['path']),'/');?>
                    <input type="hidden" name="old_path"  value="<?=$path?>">
                      <input type="text" class="form-control input-medium" name="path"  value="<?=$path?>" style="float:left; margin-right:10px;" required>
           				 <button type="button" class="btn btn-default popovers" data-trigger="hover" data-placement="top"  data-content="填写【<?=languageType($LT)?>】的栏目名称,可自动按名称生成拼音" onClick="getpath();"  style="float:left;">自动生成 </button>
<script>
function getpath()
{
	var name=document.xingao.name<?=$LT?>.value;
	if(!name){
		document.getElementsByName("path")[0].value='<?=$rs['classid']?$rs['classid']:NextId('class')?>';
	}else{
		window.open('/public/AutoInput.php?typ=py&space=0&case=1&content='+name+'&returnform=opener.document.xingao.path.value','','width=100,height=100');
	}
}
</script>                         
      
                    </div>
                  </div>
                  
                  <div class="form-group">
                    <label class="control-label col-md-2">类型/模板</label>
                    <div class="col-md-10  has-error">
                      <select  class="form-control select2me input-small" data-placeholder="类型" name="classtype" style="float:left; margin-right:10px;" required>
                        <?php ClassType(cadd($rs['classtype']),1)?>
                      </select>
                      <select  class="form-control select2me" data-placeholder="列表模板" name="listt" style="float:left; margin-right:10px;width:150px;">
                       
                         <?php LTemplate(cadd($rs['listt']))?>
                      </select>
                      <select  class="form-control select2me" data-placeholder="内容模板" name="contentt" style="float:left; margin-right:10px;width:150px;">
                        <?php CTemplate(cadd($rs['contentt']))?>
                      </select>
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
                  
                  
                  
                  
                  <div class="form-group">
                    <label class="control-label col-md-2">排序</label>
                    <div class="col-md-10">
                      <input type="text" class="form-control input-xsmall"  name="myorder" value="<?=cadd($rs['myorder'])?><?=cadd($_REQUEST['myorder'])?>"><span class="help-block">越大越排前</span>
                    </div>
                  </div>
                  
                </div>
              </div>
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
                    <label class="control-label col-md-2">栏目名称</label>
                    <div class="col-md-10 <?=$LG_i==1?'has-error':''?>">
                      <input type="text" class="form-control" name="name<?=$language?>"  value="<?=cadd($rs['name'.$language])?>" <?=$LG_i==1?'required':''?>>
                    </div>
                  </div>
                  
                  <div class="form-group">
                    <label class="control-label col-md-2">栏目图片(填写)</label>
                    <div class="col-md-10">
                    <input type="text" class="form-control" name="imgadd<?=$language?>"  value="<?=cadd($rs['img'.$language])?>" >
                    </div>
                  </div>
				  				  


                  <div class="form-group">
                    <label class="control-label col-md-2">栏目图片(上传)</label>
                    <div class="col-md-10">
<?php 
//文件上传配置
$uplx='img';//img,file
$uploadLimit='10';//允许上传文件个数(如果是单个，此设置无法，默认1)
$inputname='img'.$language;//保存字段名，多个时加[]

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
