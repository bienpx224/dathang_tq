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
require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/session.php');
$headtitle=$LG['address.headtitleForm'];
require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/head.php');

//获取,处理
$lx=par($_GET['lx']);
$addid=par($_GET['addid']);
if(!$lx){$lx='add';}

if($lx=='edit')
{
	if(!$addid){exit ("<script>alert('addid{$LG['pptError']}');goBack();</script>");}
	
	$rs=FeData('member_address','*',"addid='{$addid}' {$Mmy}");
}

//生成令牌密钥(为安全要放在所有验证的最后面)
$token=new Form_token_Core();
$tokenkey= $token->grante_token("address_add");

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
  <input name="addid" type="hidden" value="<?=$rs['addid']?>">
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
                  <label class="control-label col-md-2"><?=$LG['address.checked']?><!--可使用--></label>
                  <div class="col-md-10">
                    <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                      <input type="checkbox" class="toggle" name="checked" value="1"  <?php if($rs['checked']||$lx=='add'){echo 'checked';}?> />
                    </div>
                  </div>
                </div>

              <div class="form-group">
                  <label class="control-label col-md-2"><?=$LG['address.addclass']?><!--分类--></label>
                  <div class="col-md-10 has-error">
                
                  <select name="addclass">
                   <?=AddClass($rs['addclass'],1)?>
                  </select>
                 
                  </div>
                </div>
                
                <div class="form-group">
                  <label class="control-label col-md-2"><?=$LG['address.truename']?><!--姓名--></label>
                  <div class="col-md-10 has-error">
                
                    <input type="text" class="form-control input-medium" name="truename" required value="<?=cadd($rs['truename'])?>" >
                 
                  </div>
                </div>
                             
                  <div class="form-group">
                  <label class="control-label col-md-2"><?=$LG['address.mobile_code']?><!--手机地区/号码--></label>
                  <div class="col-md-10 has-error">
                 
        <select  class="form-control input-small select2me" data-placeholder="Select..." required name="mobile_code">
        <?php mobileCountry($rs['mobile_code'],1)?>
        </select>
       <input type="text" class="form-control input-medium"  name="mobile" required value="<?=cadd($rs['mobile'])?>" placeholder="<?=$LG['address.pptForm1']?>" ><!--手机号码-->
         <span class="help-block"> <?=$LG['address.pptForm2']?><!--请选择正确，否则可能无法发送短信!--></span>
         
                  </div>
                </div>
                
                
                
                     
                <div class="form-group">
                  <label class="control-label col-md-2"><?=$LG['address.tel']?><!--固话--></label>
                  <div class="col-md-10">
                
                    <input type="text" class="form-control input-medium" name="tel"  value="<?=cadd($rs['tel'])?>">
                 
                  </div>
                </div>
                             
                
                <div class="form-group">
                  <label class="control-label col-md-2"><?=$LG['address.zip']?><!--邮编--></label>
                  <div class="col-md-10">
                
                    <input type="text" class="form-control input-medium" name="zip" value="<?=cadd($rs['zip'])?>" >
                 
                  </div>
                </div>
                             
   
                
                <div class="form-group">
                  <label class="control-label col-md-2"><?=$LG['address.add_shengfen']?><!--省份--></label>
                  <div class="col-md-10 has-error">
                
                    <input type="text" class="form-control input-medium" name="add_shengfen" required value="<?=cadd($rs['add_shengfen'])?>">
                 
                  </div>
                </div>
                             
                
                <div class="form-group">
                  <label class="control-label col-md-2"><?=$LG['address.add_chengshi']?><!--城市--></label>
                  <div class="col-md-10 has-error">
                
                    <input type="text" class="form-control input-medium" name="add_chengshi" required value="<?=cadd($rs['add_chengshi'])?>">
                 
                  </div>
                </div>
                             
                
                <div class="form-group">
                  <label class="control-label col-md-2"><?=$LG['address.add_quzhen']?><!--区镇--></label>
                  <div class="col-md-10 has-error">
                
                    <input type="text" class="form-control input-medium" name="add_quzhen" required value="<?=cadd($rs['add_quzhen'])?>">
                 
                  </div>
                </div>
                             
                
                <div class="form-group">
                  <label class="control-label col-md-2"><?=$LG['address.add_dizhi']?><!--详细地址--></label>
                  <div class="col-md-10 has-error">
                
                    <input type="text" class="form-control input-xlarge" name="add_dizhi" required value="<?=cadd($rs['add_dizhi'])?>" >
                 
                  </div>
                </div>
                 
                 <?php if($off_shenfenzheng){?>     
                 <div class="form-group">
                  <label class="control-label col-md-2"><?=$LG['address.shenfenhaoma']?><!--身份证号码--></label>
                  <div class="col-md-10">
                
                    <input type="text" class="form-control input-medium" name="shenfenhaoma" value="<?=cadd($rs['shenfenhaoma'])?>" >
                 
                  </div>
                </div>   
                    
                 <div class="form-group">
                  <label class="control-label col-md-2"><?=$LG['address.shenfenimg_z']?><!--身份证正面--></label>
                  <div class="col-md-10">
                
                  <?php 
//文件上传配置
$uplx='img';//img,file
$uploadLimit='10';//允许上传文件个数(如果是单个，此设置无法，默认1)
$inputname='shenfenimg_z';//保存字段名，多个时加[]
$Pathname='card';//存放目录分类

$off_water=0;//水印(不手工设置则按后台设置)
//$off_narrow=1;//是否裁剪
$img_w=$certi_w;$img_h=$certi_h;//裁剪尺寸：证件
//$img_w=$other_w;$img_h=$other_h;//裁剪尺寸：通用
//$img_w=500;$img_h=500;//裁剪尺寸：指定
include($_SERVER['DOCUMENT_ROOT'].'/public/uploadify/call.php');
?>

                 
                  </div>
                </div>   
  
  
  <div class="form-group">
                  <label class="control-label col-md-2"><?=$LG['address.shenfenimg_b']?><!--身份证背面--></label>
                  <div class="col-md-10">
                
<?php 
//文件上传配置
$uplx='img';//img,file
$uploadLimit='10';//允许上传文件个数(如果是单个，此设置无法，默认1)
$inputname='shenfenimg_b';//保存字段名，多个时加[]
$Pathname='card';//存放目录分类

$off_water=0;//水印(不手工设置则按后台设置)
//$off_narrow=1;//是否裁剪
$img_w=$certi_w;$img_h=$certi_h;//裁剪尺寸：证件
//$img_w=$other_w;$img_h=$other_h;//裁剪尺寸：通用
//$img_w=500;$img_h=500;//裁剪尺寸：指定
include($_SERVER['DOCUMENT_ROOT'].'/public/uploadify/call.php');
?>

                 
                  </div>
                </div>  
                 <div class="form-group">
                  <label class="control-label col-md-2"><?=$LG['explain']?><!--说明--></label>
                  <div class="col-md-10">
                   <?=$LG['address.explain_form']?>
                  </div>
                </div>

                <?php }?>  
                 
                 
                 <div class="form-group">
                  <label class="control-label col-md-2"><?=$LG['address.content']?><!--备注--></label>
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
        
        
      <button type="button" class="btn btn-primary input-small" onClick="receive_check();"> 
      <i class="icon-ok"></i> <?=$LG['submit']?> 
      </button>
  	  <input  type="submit" id="submit_none" disabled style="display: none;"/>
      
      <input type="hidden" id="receive_check_ppt" value="">
      <button type="reset" class="btn btn-default" style="margin-left:30px;"> <?=$LG['reset']?> </button>
    </div>
      </div>
      
      
    
    </div>
    
  </form>
</div>


<script>
//验证收件人资料
function receive_check()
{
	var truename=document.getElementsByName("truename")[0].value ;
	var country=document.getElementsByName("mobile_code")[0].value ;
	
	var mobile='';		if($('[name="mobile"]').length>0)		{mobile=$('[name="mobile"]')[0].value;}
	var zip='';			if($('[name="zip"]').length>0)			{zip=$('[name="zip"]')[0].value;}
	var add_dizhi='';	if($('[name="add_dizhi"]').length>0)		{add_dizhi=$('[name="add_dizhi"]')[0].value;}
	var shenfenhaoma='';	if($('[name="shenfenhaoma"]').length>0)		{shenfenhaoma=$('[name="shenfenhaoma"]')[0].value;}
	
	var shenfenimg_z='';		if($('[name="shenfenimg_z"]').length>0)			{shenfenimg_z=$('[name="shenfenimg_z"]')[0].value;}
	var old_shenfenimg_z='';	if($('[name="old_shenfenimg_z"]').length>0)		{old_shenfenimg_z=$('[name="old_shenfenimg_z"]')[0].value;}
	var shenfenimg_z_add='';	if($('[name="shenfenimg_z_add"]').length>0)		{shenfenimg_z_add=$('[name="shenfenimg_z_add"]')[0].value;}

	var shenfenimg_b='';		if($('[name="shenfenimg_b"]').length>0)			{shenfenimg_b=$('[name="shenfenimg_b"]')[0].value;}
	var old_shenfenimg_b='';	if($('[name="old_shenfenimg_b"]').length>0)		{old_shenfenimg_b=$('[name="old_shenfenimg_b"]')[0].value;}
	var shenfenimg_b_add='';	if($('[name="shenfenimg_b_add"]').length>0)		{shenfenimg_b_add=$('[name="shenfenimg_b_add"]')[0].value;}

	
	var xmlhttp_receive=createAjax(); 
	if (xmlhttp_receive) 
	{  
		xmlhttp_receive.open('POST','/public/ajax.php?n='+Math.random(),true); 
		xmlhttp_receive.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		xmlhttp_receive.send('lx=receive_check&truename='+truename+'&country='+country+'&mobile='+mobile+'&zip='+zip+'&add_dizhi='+add_dizhi+'&shenfenhaoma='+shenfenhaoma+'&shenfenimg_z='+shenfenimg_z+'&shenfenimg_z_add='+shenfenimg_z_add+'&old_shenfenimg_z='+old_shenfenimg_z+'&shenfenimg_b='+shenfenimg_b+'&shenfenimg_b_add='+shenfenimg_b_add+'&old_shenfenimg_b='+old_shenfenimg_b+'');

		xmlhttp_receive.onreadystatechange=function() 
		{  
			if (xmlhttp_receive.readyState==4 && xmlhttp_receive.status==200) 
			{ 
				var ret=unescape(xmlhttp_receive.responseText); 
				if(ret!=0)
				{
					return alert($.trim(ret));
				}else{
					document.getElementById('submit_none').disabled=false;
					document.getElementById ('submit_none').click ();
					document.getElementById('submit_none').disabled=true;
				}
			}
		}
	}
}
</script>

<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/foot.php');
?>
