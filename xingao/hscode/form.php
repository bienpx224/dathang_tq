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
$pervar='yundan_ot';require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');
$headtitle=" HS/HG/批次号/快递单号";
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/head.php');

//获取,处理
$lx=par($_GET['lx']);
$hsid=par($_GET['hsid']);
if(!$lx){$lx='add';}

if($lx=='edit')
{
	if(!$hsid){exit ("<script>alert('hsid{$LG['pptError']}');goBack();</script>");}
	
	$rs=FeData('hscode','*',"hsid='{$hsid}'");
}

//生成令牌密钥(为安全要放在所有验证的最后面)
$token=new Form_token_Core();
$tokenkey= $token->grante_token("hscode");


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
  <input name="hsid" type="hidden" value="<?=$rs['hsid']?>">
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
                  <label class="control-label col-md-2">号码类型</label>
                  <div class="col-md-10 has-error">
                    <select  class="form-control input-medium select2me" data-placeholder="请选择" name="types" required  onChange="types_show()">
                    <option></option>
                    <?php hscode_Types(cadd($rs['types']),1)?>
                    </select>
                </div>
                </div>
                
<script language="javascript">
function types_show()
{
	var types=document.getElementsByName("types")[0].value;
	if(types=="2")
	{
	   document.getElementById("show_general").style.display ="none";
	   document.getElementById("number_str_name").innerHTML ="批次号码<?php if($lx=='add'){?>/生成数量<?php }?>";
	   document.getElementById("number_str_help").innerHTML ="<?php if($lx=='add'){?>使用自动生成数量时，请填写生成数量<?php }?>";
	   
	   document.getElementById("show_2").style.display ="block";
	}else{
	   document.getElementById("show_general").style.display ="block"; 
	   document.getElementById("number_str_name").innerHTML ="号码/号段开头";
	   document.getElementById("number_str_help").innerHTML ="号段支持开头或结束为非数字字符，如A123、123B、A123B，但不支持中间有非数字字符，如A123B456、A123-456";
	   
	   document.getElementById("show_2").style.display ="none"; 
	}
}
</script>







                 <div class="form-group">
                  <label class="control-label col-md-2"><span id='number_str_name'></span></label>
                  <div class="col-md-10 has-error">
                
                    <input type="text" class="form-control input-medium" name="number_str" required value="<?=cadd($rs['number_str'])?>">
                    <span class="help-block">
                    <span id='number_str_help'></span>
                    </span>
                  </div>
                </div>



         <div id='show_general'>
               <div class="form-group">
                  <label class="control-label col-md-2">号段结尾</label>
                  <div class="col-md-10">
                
                    <input type="text" class="form-control input-medium" name="number_end" value="<?=cadd($rs['number_end'])?>">
                    <span class="help-block">要求同上</span>
                  </div>
                </div>
         </div> 
              
         <div id='show_2'>
         <?php if($lx=='add'){?>
               <div class="form-group">
                  <label class="control-label col-md-2">自动生成</label>
                  <div class="col-md-10">
                  
                    <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                      <input type="checkbox" class="toggle" name="auto" value="1" checked />
                    </div>
                    
                      <span class="help-block">
                      按日期自动生成，格式：“A日期”，“B日期”。 比如:今天是2016年11月5号，第一个批次号则是 “A161105” 
                      </span>
                      
                  </div>
                </div>
         <?php }?>     
         </div>      
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                               
                <div class="form-group">
                  <label class="control-label col-md-2">可使用</label>
                  <div class="col-md-10">
                    <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                      <input type="checkbox" class="toggle" name="checked" value="1"  <?php if($rs['checked']||$lx=='add'){echo 'checked';}?> />
                    </div>
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
<script language="javascript">
$(function(){       
	 types_show();
});
</script>
