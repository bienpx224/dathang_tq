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
$pervar='daigou';require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/session.php');
$headtitle=$LG['name.nav_70'];//代购
require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/head.php');

if(!$ON_daigou){ exit("<script>alert('{$LG['daigou.45']}');goBack('uc');</script>"); }

//获取,处理
$typ=par($_GET['typ']);
$dgid=spr($_GET['dgid'],0,0);//禁止为0,否则token错误
$callFrom='member';//member 会员中心

if(!$typ)
{
	//添加-----------------------------------------------------------------------------
	$typ='add';
}elseif($typ=='edit'){
	
	//修改-----------------------------------------------------------------------------
	if(!$dgid){exit ("<script>alert('dgid{$LG['pptError']}');goBack();</script>");}
	$rs=FeData('daigou','*',"dgid='{$dgid}' {$Mmy}");
	if($dg_checked&&spr($rs['status'])>=2||!$dg_checked&&spr($rs['status'])>=3){exit ("<script>alert('{$LG['lipei.form_1']}');goBack('uc');</script>");}
	
}
$tmp=make_NoAndPa(32);

//生成令牌密钥(为安全要放在所有验证的最后面)
$token=new Form_token_Core();
$tokenkey= $token->grante_token("daigou{$dgid}");
?>

<div class="page_ny"> 
  <!-- BEGIN PAGE HEADER-->
	<div class="row"><!--单页时去掉 class="row",不然会有横滚动条,并且form 加 style="margin:20px;"-->
		<div class="col-md-12"> 
<h3 class="page-title"> <a href="javascript:history.go(-1)" title="<?=$LG['back']?>"><i class="icon-reply"></i></a> <a href="list.php" class="gray" target="_parent"><?=$LG['backList']?></a> > <a href="javascript:void(0)" onClick="javascript:window.location.href=window.location.href;" title="<?=$LG['refresh']?>" class="gray">
        <?=$headtitle?>
        </a> </h3>
    </div>
  </div>
  <!-- END PAGE HEADER--> 
  
  <!-- BEGIN PAGE CONTENT-->
  
  <form action="save.php" method="post" class="form-horizontal form-bordered" name="xingao">
    <input name="typ" type="hidden" value="<?=add($typ)?>">
    <input name="dgid" type="hidden" value="<?=$dgid?>">
    <input name="tokenkey" type="hidden" value="<?=$tokenkey?>">
    <input name="groupid"  type="hidden" value="<?=$Mgroupid?>">
    <input name="userid" autocomplete="off"  type="hidden" value="<?=$Muserid?>">
    <input name="username" autocomplete="off" type="hidden" value="<?=$Musername?>">
 	<input name="tmp" autocomplete="off" type="hidden" value="<?=$tmp?>">
  
    
    <div><!-- class="tabbable tabbable-custom boxless"-->
      <div class="tab-content">
        <div class="tab-pane active" id="tab_1">
          <div class="form">
            <div class="form-body">
            
            <!----------------------------------------基本资料---------------------------------------->
              <div class="portlet">
                 <div class="portlet-title">
                  <div class="caption"><i class="icon-reorder"></i><?=$LG['daigou.47'];//基本要求?></div>
                  <div class="tools"> <a href="javascript:;" class="collapse"></a> </div><!--缩小expand 展开collapse-->
                </div>
               <div class="portlet-body form"> 
                  <!--表单内容-->
                  
<?php if(spr($rs['status'])==1){?>
<div class="form-group">
  <label class="control-label col-md-2"><?=$LG['lipei.status'];//重新申请?></label>
  <div class="col-md-10">
	<div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
	  <input type="checkbox" class="toggle" name="status" value="0"  <?php if(spr($rs['status'])==0){echo 'checked';}?> />
	</div>
  </div>
</div>
<?php }?>

<div class="form-group">

  <label class="control-label col-md-2" <?=$warehouse_more?'':'style="display:none"'?>><?=$LG['daigou.48'];//寄存仓库?></label>
  <div class="col-md-4 has-error" <?=$warehouse_more?'':'style="display:none"'?>>
    <select name="warehouse" class="form-control input-medium select2me" required>
      <?=warehouse($rs['warehouse'],1)?>
    </select>
    <a href="javascript:void(0)" class="tooltips" data-container="body" data-placement="top" data-original-title="<?=$LG['daigou.49'];//购买的商品会存放到该仓库，以便统一发货?>"><i class="icon-info-sign"></i></a>
  </div>
  
  <label class="control-label col-md-2"><?=$LG['daigou.50'];//寄库运费?></label>
  <div class="col-md-4">
      <input type="text" class="form-control input-small tooltips" data-container="body" data-placement="top" data-original-title="<?=$LG['daigou.161']//请填写正确,乱填时会耽误代购时间?>" name="freightFee" value="<?=spr($rs['freightFee'],2,0)?>"  onBlur="daigouPar();"><font id="priceCurrency_msg"><?=$rs['priceCurrency']?></font>
  </div>
</div>



<div class="form-group" style="display:<?=arrcount($dg_openCurrency)<=1?'none':''?>">
  <label class="control-label col-md-2"><?=$LG['daigou.160'];//商品币种?></label>
  <div class="col-md-4 has-error">

<?php if(arrcount($dg_openCurrency)>1){?>
   <!--有多代购币种时显示下拉-->
   <select name="priceCurrency" class="form-control input-small select2me" required data-placeholder="<?=$LG['currency'];//币种?>" onChange="daigouPar();">
	  <?=openCurrency(cadd($_POST['priceCurrency'].$rs['priceCurrency']),3)?>
	</select>
	<a href="javascript:void(0)" class="tooltips" data-container="body" data-placement="top" data-original-title="<?=$LG['daigou.108'];//必须选择该国家所用币种,否则计费错误,无法代购?>"> <i class="icon-info-sign"></i> </a>
    
<?php }elseif($typ=='add'){?>
   <input type="hidden"  name="priceCurrency" value="<?=$dg_openCurrency?>">
<?php }?>
<!--修改时:商品表单已在本页,已经有name="priceCurrency"-->

  </div>
</div>


<div class="form-group">
  <label class="control-label col-md-2"><?=$LG['daigou.source'];//货源?></label>
  <div class="col-md-4 has-error">
	  <select name="source" class="form-control select2me input-medium"  data-placeholder="<?=$LG['pleaseSelect'];//请选择?>" required onChange="get_source();daigouPar();">
	  <?php daigou_source(spr($rs['source']),1)?>
	  </select>
  </div>
  
<span class="form-group" id="brandShow"> 
  <label class="control-label col-md-2"><?=$LG['brand'];//品牌?></label>
  <div class="col-md-4 has-error">
	  <select name="brand" class="form-control select2me input-msmall"  data-placeholder="<?=$LG['pleaseSelect'];//请选择?>" onChange="daigouPar();">
	  <?php daigou_brand($rs['brand'],$Mgroupid,1)?>
	  </select>
      
	  <span>
      <?=$LG['discount'];//折扣?><font id="brandDiscount_msg" class="red"><?=spr($rs['brandDiscount'])?></font><?=$LG['fold'];//折?>
	  <input type="hidden" name="brandDiscount" value="<?=spr($rs['brandDiscount'])?>">
      </span>
  </div>
</span>
  

</div>
    

    
    
    
    
    


<div class="form-group">
  
  <label class="control-label col-md-2"><?=$LG['daigou.types'];//品类?></label>
  <div class="col-md-4 has-error">
  	  <select name="types" class="form-control select2me input-medium"  data-placeholder="<?=$LG['pleaseSelect'];//请选择?>" required>
      <?php ClassifyAll(4,$rs['types'])?>
      </select>
  </div>

  <label class="control-label col-md-2"><?=$LG['daigou.name'];//品名/货号?></label>
  <div class="col-md-4 has-error">
    <input type="text" class="form-control" name="name" required value="<?=cadd($rs['name'])?>">
  </div>

</div>


<div class="form-group">
  <label class="control-label col-md-2" id="address_name"></label>
  <div class="col-md-10">
    <input type="text" class="form-control" name="address" value="<?=cadd($rs['address'])?>">
  </div>
</div>

            
  <div class="form-group">
  <label class="control-label col-md-2"><?=$LG['daigou.autoAddPay'];//自动补款限额?></label>
  <div class="col-md-10">
    <input type="text" class="form-control input-small popovers" data-trigger="hover" data-placement="top"  data-content="<?=$LG['daigou.101']?>" name="autoAddPay" value="<?=spr($_POST['autoAddPay'].$rs['autoAddPay'])?>" onKeyUp="totalPrice();">
    <font id="priceCurrency_msg3"><?=$rs['priceCurrency']?></font> 
    <a href="javascript:void(0)" class=" popovers" data-trigger="hover" data-placement="top"  data-content="<?=$LG['daigou.116']?>"><i class="icon-info-sign"></i></a>
  </div>
</div>


<div class="form-group">
  <label class="control-label col-md-2"><?=$LG['daigou.51']//商品图?></label>
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
//$rsfile_my='no';//指定文件，no则空
include($_SERVER['DOCUMENT_ROOT'].'/public/uploadify/call.php');
?>
  </div>
</div>



            
            


        <div class="form-group">
          <label class="control-label col-md-2"><?=$LG['transfer.list_14'];//留言?></label>
          <div class="col-md-10">
            <textarea  class="form-control" rows="1" name="memberContent"><?=cadd($rs['memberContent'])?></textarea>
          </div>
        </div>

      <div class="form-group">
          <label class="control-label col-md-2"><?=$LG['daigou.94'];//我的备注?></label>
          <div class="col-md-10">
            <textarea  class="form-control" rows="1" name="content"><?=cadd($rs['content'])?></textarea><span class="help-block"><?=$LG['daigou.94_1']?></span>
          </div>
        </div>
            




                </div>
              </div>
              
<!----------------------------------------商品---------------------------------------->
<iframe src="goods.php?tmp=<?=$tmp?>&dgid=<?=$dgid?>" id="iframe" width="100%" frameborder="0" scrolling="auto"></iframe>
<script>$(function(){ iframeHeight('iframe',0);	});</script>
              
              
            </div>
          </div>
        </div>        
        <!--提交按钮固定--> 
		<script>/*$(function(){ Autohidden(); });*/</script> <!--拉到底时自动隐藏-->
<style>body{margin-bottom:40px !important;}</style><!--不用隐藏,增高底部高度-->
 
        <div align="center" class="fixed_btn" id="Autohidden">

<button type="button" class="btn btn-info input-small" onClick='$("#iframe").contents().find("#goods_smt").click();' style="margin-right:50px;"> <i class="icon-check"></i> <?=$LG['daigou.106']?> </button>



        
<button type="submit"  class="btn btn-primary input-large tooltips" data-container="body" data-placement="top" data-original-title="<?=$LG['daigou.52'];//请确保已经保存所填商品,再提交此处?>" > <i class="icon-ok"></i> <?=$LG['daigou.53'];//提交代购单?></button>

		</div>
      </div>
    </div>
  </form>
  <?php require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/daigou/call/ppt.php');?>
</div>

<?php 
require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/foot.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/js/daigouJS.php');
?>


