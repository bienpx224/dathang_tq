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
$classtype=3;//指定类型
$headtitle=ClassifyType($classtype).'分类';
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
                <div class="portlet-body form" style="display: block;"> 
                  <!--表单内容-->
                  
                   
  
				 <input type="hidden" name="classtype" value="<?=$classtype?>">
                 
                 <div class="form-group">
                    <label class="control-label col-md-2">所属分类</label>
                    <div class="col-md-10 has-error">
                        <select  class="form-control input-medium select2me" data-placeholder="Select..." name="bclassid" >
                        <option value="0" >根目录</option>
                         <?=LevelClassify(0,0,$rs['bclassid'],$classtype,0)?>
                        </select>
                      
<span class="help-block">
&raquo; 一级为【航班号/船运号】，二级为【航次号/船次号/提单号】，三级为【分单号】(这级可没有)，最小级为【托盘号】<br>
&raquo; <font class="red">一级和二级的级别必须对应 ，否则后期各类操作都可能有错误,比如导出报表。</font>在未有【航班号/船运号】或【航次号/船次号/提单号】时，可以先随便添加临时号码，待有号码后再修改为正式号码
</span>
                    </div>
                  </div>
 
                 
                  <div class="form-group">
                    <label class="control-label col-md-2">分类名称</label>
                    <div class="col-md-10 has-error">
                    
                      <?php if($lx=='add'){?>
                      <textarea  class="form-control input-medium" rows="10" name="name"  style="float:left;" placeholder="一行一个"></textarea>
                      
                      <button type="button" class="btn btn-default" onClick="AutoInput(1);"  style="margin-bottom:10px;display:block;">自动生成临时【航班号/船运号】 </button>
                      
                      <button type="button" class="btn btn-default" onClick="AutoInput(2);"  style="margin-bottom:10px;display:block;">自动生成临时【航次号/船次号/提单号】 </button>
                     
                      <button type="button" class="btn btn-default" onClick="AutoInput(4);"  style="margin-bottom:10px;display:block;">自动生成【托盘号】 </button>
                      
<script>
function AutoInput(typ) 
{
	var Context=document.getElementsByName("name")[0].value;
	Context=Context.replace(/\n|\r|(\r\n)|(\u0085)|(\u2028)|(\u2029)/g, ",");
	window.open('AutoInput.php?typ='+typ+'&content='+Context+'&returnform=opener.document.xingao.name.value','','width=100,height=100');
}
</script>                  
                      
                      <?php }else{?>
                      <input type="text" class="form-control input-medium" name="name"  value="<?=cadd($rs['name'])?>" required>
                      <?php }?>
                      
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
        <?php if($lx=='edit'){?><font class="red2"> &raquo; 此修改将会影响所有使用到该分类的运单</font><?php }?>
        </div>
</div>


<?php require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/foot.php');?>
