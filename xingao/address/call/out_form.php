<?php 
$path=getPath($callFrom);
?>

<!--收件人信息版块-->
<?php if($call_table=='yundan'){?>
<div class="portlet">
  <div class="portlet-title">
    <div class="caption"><i class="icon-reorder"></i><?=$LG['yundan.form_38'];//收件人信息?></div>
    <div class="tools"> <a href="javascript:;" class="collapse"></a></div>
  </div>
  <div class="portlet-body form" style="display:block;"> 
<?php }?>    

    <!--表单内容-开始-->
    <div class="form-group">
      <label class="control-label col-md-2"><?=$LG['yundan.s_name'];//收件人姓名?></label>
      <div class="col-md-10 <?=$call_table=='yundan'?'has-error':''?>">
        <input type="text" class="form-control input-small" name="s_name" value="<?=cadd($rs['s_name'])?><?=cadd($mrs['truename'])?>" <?=$call_table=='yundan'?'required':''?>>
        
        <button type="button" class="btn btn-default" onClick="window.open('<?=$path?>address/address_copy.php?lx=s<?php if($callFrom=='manage'){?>&userid='+document.xingao.userid.value+'&username='+document.xingao.username.value+'<?php }?>');"><i class="icon-plus"></i> <?=$LG['yundan.form_39'];//从地址薄导入?> </button>
         
        <button type="button" class="btn btn-default" onClick="del_add('s');"><i class="icon-remove"></i> <?=$LG['yundan.form_39_1'];//清空地址?> </button>
       
        &nbsp;&nbsp;
        <input type="checkbox" name="address_save_s" value="1" checked>
        <font title="<?=$LG['yundan.form_40'];//如果地址簿已有该姓名和r手机号则更新最新一个地址，否则添加一个地址?>" class="gray2"><?=$LG['yundan.form_41'];//保存到地址簿?></font> </div>
    </div>

    <div class="form-group">
      <label class="control-label col-md-2"><?=$LG['yundan.s_mobile_code'];//手机地区/号码?></label>
      <div class="col-md-10 <?=$call_table=='yundan'?'has-error':''?>">
        <select  class="form-control input-small select2me" data-placeholder="<?=$LG['yundan.form_18'];//请选择?>" name="s_mobile_code" <?=$call_table=='yundan'?'required':''?>>
          <?php mobileCountry(cadd($rs['s_mobile_code']).cadd($mrs['mobile_code']),1)?>
        </select>
        <input type="text" class="form-control input-msmall" name="s_mobile" value="<?=cadd($rs['s_mobile'])?><?=cadd($mrs['mobile'])?>" <?=$call_table=='yundan'?'required':''?> placeholder="<?=$LG['yundan.form_42'];//手机号码?>">
      </div>
    </div>

    <div class="form-group">
      <label class="control-label col-md-2"><?=$LG['yundan.s_tel'];//收件人固话?></label>
      <div class="col-md-2">
        <input type="text" class="form-control" name="s_tel" value="<?=cadd($rs['s_tel'])?><?=cadd($mrs['tel'])?>">
      </div>

      <label class="control-label col-md-2"><?=$LG['yundan.s_zip'];//收件人邮编?></label>
      <div class="col-md-2">
        <input type="text" class="form-control" name="s_zip" value="<?=cadd($rs['s_zip'])?><?=cadd($mrs['zip'])?>">
      </div>
    </div>

    <div class="form-group">
      <label class="control-label col-md-2"><?=$LG['yundan.s_add_shengfen'];//收件人省份?></label>
      <div class="col-md-2 <?=$call_table=='yundan'?'has-error':''?>">
        <input type="text" class="form-control" name="s_add_shengfen" value="<?=cadd($rs['s_add_shengfen'])?><?=cadd($mrs['add_shengfen'])?>" <?=$call_table=='yundan'?'required':''?>>
      </div>

      <label class="control-label col-md-2"><?=$LG['yundan.s_add_chengshi'];//收件人城市?></label>
      <div class="col-md-2 <?=$call_table=='yundan'?'has-error':''?>">
        <input type="text" class="form-control" name="s_add_chengshi" value="<?=cadd($rs['s_add_chengshi'])?><?=cadd($mrs['add_chengshi'])?>" <?=$call_table=='yundan'?'required':''?>>
      </div>

      <label class="control-label col-md-2"><?=$LG['yundan.s_add_quzhen'];//收件人区镇?></label>
      <div class="col-md-2 <?=$call_table=='yundan'?'has-error':''?>">
        <input type="text" class="form-control" name="s_add_quzhen" value="<?=cadd($rs['s_add_quzhen'])?><?=cadd($mrs['add_quzhen'])?>" <?=$call_table=='yundan'?'required':''?>>
      </div>
    </div>

    <div class="form-group">
      <label class="control-label col-md-2"><?=$LG['yundan.s_add_dizhi'];//具体地址?></label>
      <div class="col-md-10 <?=$call_table=='yundan'?'has-error':''?>" >
        <input type="text" class="form-control" name="s_add_dizhi" value="<?=cadd($rs['s_add_dizhi'])?><?=cadd($mrs['add_dizhi'])?>" <?=$call_table=='yundan'?'required':''?>>
      </div>
    </div>
    
    <?php if($off_shenfenzheng){?>
    <div id="sfz_hide" style="display:<?=channelPar($warehouse,$channel,'shenfenzheng')||$call_table=='daigou'?'block':'none'?>">
      <div class="form-group"> <br>
      </div>
      
      <!--获取证件部分--> 
      <?php if($ON_cardInstead&&$callFrom=='manage'&&$call_table=='yundan'){?>
      <div class="form-group">
        <label class="control-label col-md-2"><?=$LG['address.Xcall_out_form_1']?></label>
        <div class="col-md-10 <?=$call_table=='yundan'?'has-error':''?>">
          <button type="button" class="btn btn-default" onClick="cardInstead('get');"><?=$LG['address.Xcall_out_form_2']?> </button>
          <button type="button" class="btn btn-default" onClick="cardInstead('failure');"><?=$LG['address.Xcall_out_form_3']?> </button>
          <br>
          <br>
          <span id="cardInstead_msg"></span>
          <span class="help-block"><?=$LG['address.Xcall_out_form_4']?></span>
        </div>
      </div>
      <?php }?>
      
      
      <div class="form-group">
        <label class="control-label col-md-2"><?=$LG['yundan.s_shenfenhaoma'];//身份证号码?></label>
        <div class="col-md-10 <?=$call_table=='yundan'?'has-error':''?>">
          <input type="text" class="form-control input-medium" name="s_shenfenhaoma" value="<?=cadd($rs['s_shenfenhaoma'])?><?=cadd($mrs['shenfenhaoma'])?>" title="<?=$LG['yundan.form_43'];//地址薄导入的身份证?>">
          
          <span class="help-block">
          
          	  <?php 
			  if($call_table=='yundan')
			  {
				  echo $LG['yundan.form_44'];
				  if($off_upload_cert){echo '<font class="red2">'.$LG['yundan.form_45'].'</font>';}
			  }
			  ?>
              
          	  <?php if($call_table=='daigou'){echo $LG['address.Xcall_out_form_5'];}?>
          </span>
        </div>
      </div>
      
      
      
      
      
      
      
    
      
      
      <div class="form-group">
        <label class="control-label col-md-2"><?=$LG['yundan.s_shenfenimg_z_add'];//身份证正面?></label>
        <div class="col-md-4"> 
<?php 
//文件上传配置
$uplx='img';//img,file
$uploadLimit='10';//允许上传文件个数(如果是单个，此设置无法，默认1)
$inputname='s_shenfenimg_z';//保存字段名，多个时加[]
$Pathname='card';//指定存放目录分类

$off_water=0;//水印(不手工设置则按后台设置)
$off_narrow=1;//是否裁剪
$img_w=$certi_w;$img_h=$certi_h;//裁剪尺寸：证件
//$img_w=$other_w;$img_h=$other_h;//裁剪尺寸：通用
//$img_w=500;$img_h=500;//裁剪尺寸：指定
include($_SERVER['DOCUMENT_ROOT'].'/public/uploadify/call.php');
?>
          <span class="help-block">
          <input type="hidden" name="s_shenfenimg_z_add" value="<?=cadd($mrs['shenfenimg_z'])?>">
          <span id="s_shenfenimg_z_msg"> <?php if($mrs['shenfenimg_z']){?> <a href="<?=cadd($mrs['shenfenimg_z'])?>" target="_blank"><img src="<?=cadd($mrs['shenfenimg_z'])?>" width="200" height="150"/></a> <?php }?> </span>
           </span>
        </div>

        <label class="control-label col-md-2"><?=$LG['yundan.s_shenfenimg_b_add'];//身份证背面?></label>
        <div class="col-md-4"> 
<?php 
//文件上传配置
$uplx='img';//img,file
$uploadLimit='10';//允许上传文件个数(如果是单个，此设置无法，默认1)
$inputname='s_shenfenimg_b';//保存字段名，多个时加[]
$Pathname='card';//存放目录分类

$off_water=0;//水印(不手工设置则按后台设置)
$off_narrow=1;//是否裁剪
$img_w=$certi_w;$img_h=$certi_h;//裁剪尺寸：证件
//$img_w=$other_w;$img_h=$other_h;//裁剪尺寸：通用
//$img_w=500;$img_h=500;//裁剪尺寸：指定
include($_SERVER['DOCUMENT_ROOT'].'/public/uploadify/call.php');
?>
          <span class="help-block">
          <input type="hidden" name="s_shenfenimg_b_add" value="<?=cadd($mrs['shenfenimg_b'])?>">
          <span id="s_shenfenimg_b_msg"> <?php if($mrs['shenfenimg_b']){?> <a href="<?=cadd($mrs['shenfenimg_b'])?>" target="_blank"><img src="<?=cadd($mrs['shenfenimg_b'])?>" width="200" height="150"/></a> <?php }?> </span>
          </span>
        </div>
      </div>
    </div>
    <?php }?> 
    <!--表单内容-结束-->
    
    
    
    
<?php if($call_table=='yundan'){?>
    </div>
</div>
<?php }?>

<script>
function del_add(typ)
{
	if(typ=='s')
	{
		$('[name="s_name"]')[0].value='';
		$('[name="s_mobile_code"]')[0].value='';
		$('[name="s_mobile"]')[0].value='';
		$('[name="s_tel"]')[0].value='';
		$('[name="s_zip"]')[0].value='';
		$('[name="s_add_shengfen"]')[0].value='';
		$('[name="s_add_chengshi"]')[0].value='';
		$('[name="s_add_quzhen"]')[0].value='';
		$('[name="s_add_dizhi"]')[0].value='';
		$('[name="s_shenfenhaoma"]')[0].value='';
		document.getElementById("s_shenfenimg_z_msg").innerHTML='';
		document.getElementById("s_shenfenimg_b_msg").innerHTML='';
	}

	else if(typ=='f')
	{
		$('[name="f_name"]')[0].value='';
		$('[name="f_mobile_code"]')[0].value='';
		$('[name="f_mobile"]')[0].value='';
		$('[name="f_tel"]')[0].value='';
		$('[name="f_zip"]')[0].value='';
		$('[name="f_add_shengfen"]')[0].value='';
		$('[name="f_add_chengshi"]')[0].value='';
		$('[name="f_add_quzhen"]')[0].value='';
		$('[name="f_add_dizhi"]')[0].value='';
	}
	
	if($('[name="addid"]').length>0)
	{
		$('[name="addid"]')[0].value='';
	}
}
</script>










<?php if($call_table=='yundan'){?>

<!--发件人信息版块-->
<div class="portlet">
  <div class="portlet-title">
    <div class="caption"><i class="icon-reorder"></i> <?=$LG['yundan.form_46'];//发件人信息?> 
	<?php if(!$off_fajian){echo $LG['yundan.form_47'];}?> </div>
    <div class="tools"> <a href="javascript:;" class="<?=$off_fajian?'collapse':'expand'?>"></a></div>
    <!--默认关闭:class="expand"--> 
  </div>
  <div class="portlet-body form" style="display:<?=$off_fajian?'block':'none'?>;"> <!--默认关闭:display: none;--> 
    <!--表单内容--> 
    <div class="form-group">
      <label class="control-label col-md-2"><?=$LG['yundan.f_name'];//发件人姓名?></label>
      <div class="col-md-10 <?=$f_name_req?'has-error':''?>">
        <input type="text" class="form-control input-small" name="f_name" value="<?=cadd($rs['f_name'])?><?=cadd($mrf['truename'])?>" <?=$f_name_req?'required':''?>>
        <button type="button" class="btn btn-default" onClick="window.open('<?=$path?>address/address_copy.php?lx=f<?php if($callFrom=='manage'){?>&userid='+document.xingao.userid.value+'&username='+document.xingao.username.value+'<?php }?>');"><i class="icon-plus"></i> <?=$LG['yundan.form_39'];//从地址薄导入?> </button>
         
        <button type="button" class="btn btn-default" onClick="del_add('f');"><i class="icon-remove"></i> <?=$LG['yundan.form_39_1'];//清空地址?> </button>
       
        &nbsp;&nbsp;
        <input type="checkbox" name="address_save_f" value="1" checked>
        <font title="<?=$LG['yundan.form_40'];//如果地址簿已有该姓名和r手机号则更新最新一个地址，否则添加一个地址?>" class="gray2"><?=$LG['yundan.form_41'];//保存到地址簿?></font> </div>
    </div>

    <div class="form-group">
      <label class="control-label col-md-2"><?=$LG['yundan.s_mobile_code'];//手机地区/号码?></label>
      <div class="col-md-10"> <font class="<?=$f_mobile_code_req?'has-error':''?>">
        <select  class="form-control input-small select2me" data-placeholder="<?=$LG['yundan.form_18'];//请选择?>" name="f_mobile_code" <?=$f_mobile_code_req?'required':''?>>
          <?php mobileCountry(cadd($rs['f_mobile_code']).cadd($mrf['mobile_code']),1)?>
        </select>
        </font>
        
        <font class="<?=$f_mobile_req?'has-error':''?>">
        <input type="text" class="form-control input-msmall" name="f_mobile" value="<?=cadd($rs['f_mobile'])?><?=cadd($mrf['mobile'])?>" placeholder="<?=$LG['yundan.form_42'];//手机号码?>" <?=$f_mobile_req?'required':''?>>
        </font> 
       </div>
    </div>

    <div class="form-group">
      <label class="control-label col-md-2"><?=$LG['yundan.f_tel'];//发件人固话?></label>
      <div class="col-md-2 <?=$f_tel_req?'has-error':''?>">
        <input type="text" class="form-control input-small" name="f_tel" value="<?=cadd($rs['f_tel'])?><?=cadd($mrf['tel'])?>" <?=$f_tel_req?'required':''?>>
      </div>
      
      <label class="control-label col-md-2"><?=$LG['yundan.f_zip'];//发件人邮编?></label>
      <div class="col-md-2 <?=$f_zip_req?'has-error':''?>">
        <input type="text" class="form-control input-small" name="f_zip" value="<?=cadd($rs['f_zip'])?><?=cadd($mrf['zip'])?>" <?=$f_zip_req?'required':''?>>
      </div>
    </div>

    <div class="form-group">
      <label class="control-label col-md-2"><?=$LG['yundan.f_add_shengfen'];//发件人省份?></label>
      <div class="col-md-2 <?=$f_add_shengfen_req?'has-error':''?>">
        <input type="text" class="form-control" name="f_add_shengfen" value="<?=cadd($rs['f_add_shengfen'])?><?=cadd($mrf['add_shengfen'])?>" <?=$f_add_shengfen_req?'required':''?>>
      </div>

      <label class="control-label col-md-2"><?=$LG['yundan.f_add_chengshi'];//发件人城市?></label>
      <div class="col-md-2 <?=$f_add_chengshi_req?'has-error':''?>">
        <input type="text" class="form-control" name="f_add_chengshi" value="<?=cadd($rs['f_add_chengshi'])?><?=cadd($mrf['add_chengshi'])?>" <?=$f_add_chengshi_req?'required':''?>>
      </div>

      <label class="control-label col-md-2"><?=$LG['yundan.f_add_quzhen'];//发件人区镇?></label>
      <div class="col-md-2 <?=$f_add_quzhen_req?'has-error':''?>">
        <input type="text" class="form-control" name="f_add_quzhen" value="<?=cadd($rs['f_add_quzhen'])?><?=cadd($mrf['add_quzhen'])?>" <?=$f_add_quzhen_req?'required':''?>>
      </div>
    </div>

    <div class="form-group">
      <label class="control-label col-md-2"><?=$LG['yundan.s_add_dizhi'];//具体地址?></label>
      <div class="col-md-10 <?=$f_add_dizhi_req?'has-error':''?>">
        <input type="text" class="form-control" name="f_add_dizhi" value="<?=cadd($rs['f_add_dizhi'])?><?=cadd($mrf['add_dizhi'])?>" <?=$f_add_dizhi_req?'required':''?>>
      </div>
    </div>
    <div class="xats"> <?php if($ON_dhl){?> <font class="red2"> &raquo; <?=$LG['yundan.form_48'];//用DHL寄至仓库，在打印DHL面单时,需要填写(姓名、电话、城市、邮编、区镇(街道)、具体地址(街道号) 且不能为中文)?> </font> <?php }?> </div>
  </div>
</div>

<?php }?>