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
$pervar='classify';require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');
$headtitle="分类";
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/head.php');


//获取,处理
$lx=par($_GET['lx']);
$classid=par($_GET['classid']);
if(!$lx){$lx='add';}

if($lx=='edit')
{
	if(!$classid){exit ("<script>alert('classid{$LG['pptError']}');goBack();</script>");}
	$rs=FeData('classify','*',"classid='{$classid}'");
}

//生成令牌密钥(为安全要放在所有验证的最后面)
$token=new Form_token_Core();
$tokenkey= $token->grante_token("classify");

?>
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
    <div class="tab-content">
        <div class="tab-pane active" id="tab_1">
          <div class="form">
            <div class="form-body">
              <div class="portlet">
                <div class="portlet-title">
                  <div class="caption"><i class="icon-reorder"></i>分类属性</div>
                  <div class="tools"> <a href="javascript:;" class="collapse"></a> </div><!--缩小expand 展开collapse-->
                </div>
                <div class="portlet-body form" style="display: block;"> 
                  <!--表单内容-->
                  
                   
  

                  <div class="form-group">
                    <label class="control-label col-md-2">类型</label>
                    <div class="col-md-10  has-error">
                      <select  class="form-control select2me input-small" data-placeholder="类型" name="classtype" required onChange="bclass_show();">
                      	<option></option>
                        <?php ClassifyType(cadd($rs['classtype']),2)?>
                     </select>
  					  <span class="help-block">
                          <font id="classtype_ppt"></font>
                      </span>

                   </div>
                  </div>

                 <div class="form-group">
                    <label class="control-label col-md-2">所属分类</label>
                    <div class="col-md-10 has-error">
                      <span id="bclass_show">请先选择类型</span>
                    </div>
                  </div>
 
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
                      <input type="text" class="form-control input-xsmall"  name="myorder" value="<?=cadd($rs['myorder'])?><?=cadd($_REQUEST['myorder'])?>"><span class="help-block">越大越排前</span>
                    </div>
                  </div>
                  
                   
                  <div class="form-group">
                    <label class="control-label col-md-2">代码</label>
                    <div class="col-md-10">
                    
                      <?php if($lx=='add'){?>
                      <textarea  class="form-control input-medium" rows="10" name="codes"></textarea>
                      <span class="help-block">
                      &raquo; 一行一个<br>
                      &raquo; 如要添加，行数则要下面的名称行数对应
                      </span>
                      
                      <?php }else{?>
                      <input type="text" class="form-control input-medium"  name="codes" value="<?=cadd($rs['codes'])?>">
                      <?php }?>
                      
                      <span class="help-block">
                      &raquo; 例如对应的行邮税号、计量单位代码等号码 (不能有重复号码)
                      </span>
                    </div>
                  </div>
                
                 
                  
                </div>
              </div>
              
              
              <!--版块-->
              <div class="portlet">
                <div class="portlet-title">
                  <div class="caption"><i class="icon-reorder"></i>名称</div>
                  <div class="tools"> <a href="javascript:;" class="collapse"></a> </div><!--缩小expand 展开collapse-->
                </div>
                <div class="portlet-body form" style="display: block;"> <!--缩小none 展开block-->
                  <!--表单内容-开始-->
                  
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
                  <div class="form-group">
                    <label class="control-label col-md-2"><?=languageType($language)?> 名称</label>
                    <div class="col-md-10 <?=$LG_i==1?'has-error':''?>">
                    
                      <?php if($lx=='add'){?>
                      <textarea class="form-control input-medium" rows="10" name="name<?=$language?>"  <?=$LG_i==1?'required':''?>></textarea>
                      <span class="help-block">一行一个</span>
                      
                      <?php }else{?>
                      <input type="text" class="form-control input-medium" name="name<?=$language?>"  value="<?=cadd($rs['name'.$language])?>"  <?=$LG_i==1?'required':''?>>
                      <?php }?>
                      
                    </div>
                  </div>
		<?php 
	}
}
?>
                  
                  
				  
                  <!--表单内容-结束-->
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
      
        </form>
        
        <div class="xats">
        <?php if($lx=='edit'){?><font class="red2"> &raquo; 此修改将会影响所有使用到该分类的信息</font><?php }?>
        </div>
</div>


<script type="text/javascript">
//显示所属分类
function bclass_show()
{
	var classtype=document.getElementsByName('classtype')[0].value;
	
	if(classtype==2)
	{
		document.getElementById('classtype_ppt').innerHTML='';//提示内容
	}else{
		document.getElementById('classtype_ppt').innerHTML='';
	}
	
	var xmlhttp=createAjax(); 
	if (xmlhttp) 
	{ 
		xmlhttp.open('POST','/public/ajax.php?n='+Math.random(),true); 
		xmlhttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		xmlhttp.send('lx=bclass_show&classtype='+classtype+'&bclassid=<?=$rs['bclassid']?>');

		xmlhttp.onreadystatechange=function() 
		{  
			if (xmlhttp.readyState==4 && xmlhttp.status==200) 
			{ 
				//输出:innerHTML输出到页面；value输出到文本框；
				document.getElementById('bclass_show').innerHTML=unescape(xmlhttp.responseText); 
			}
		}
	}
}
</script>

<?php require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/foot.php');?>

<script language="javascript">
$(function(){  
	bclass_show();
});
</script>
