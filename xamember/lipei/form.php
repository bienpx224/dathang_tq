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
$pervar='off_lipei';//权限验证
require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/session.php');
$headtitle=$LG['name.nav_31'];//理赔
require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/head.php');


//获取,处理
$lx=par($_GET['lx']);
$lpid=par($_GET['lpid']);
if(!$lx){$lx='add';}

if($lx=='edit')
{
	if(!$lpid){exit ("<script>alert('lpid{$LG['pptError']}');goBack();</script>");}
	
	$rs=FeData('lipei','*',"lpid='{$lpid}' {$Mmy}");
	if(spr($rs['status'])==1||spr($rs['status'])==2){exit ("<script>alert('{$LG['lipei.form_1']}');goBack('uc');</script>");}
}

//生成令牌密钥(为安全要放在所有验证的最后面)
$token=new Form_token_Core();
$tokenkey= $token->grante_token("lipei_add");
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
    <input name="lx" type="hidden" value="<?=add($lx)?>">
    <input name="lpid" type="hidden" value="<?=$rs['lpid']?>">
    <input name="tokenkey" type="hidden" value="<?=$tokenkey?>">
    <div><!-- class="tabbable tabbable-custom boxless"-->
      <div class="tab-content">
        <div class="tab-pane active" id="tab_1">
          <div class="form">
            <div class="form-body">
              <div class="portlet">
                <div class="portlet-body form" style="display: block;"> 
                  <!--表单内容-->
                  <?php if(spr($rs['status'])==3){?>
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
                    <label class="control-label col-md-2"><?=$LG['lipei.types'];//类型?></label>
                    <div class="col-md-10 has-error">
                      <select name="types" class="form-control input-small select2me" data-placeholder="Select..." required>
                        <?=lipei_Types($rs['types'],1)?>
                      </select>
                    </div>
                  </div>
                  
                  <div class="form-group">
                    <label class="control-label col-md-2"><?=$LG['lipei.ydh'];//运单号?></label>
                    <div class="col-md-10 has-error">
                    
						  <select name="ydh" class="form-control input-medium select2me" data-placeholder="Select..." required>
						   <?php 
						    if($lx=='add'){$status='0,1';}elseif($lx=='edit'){$status='1';}
							$query_yd="select ydh from yundan where status>='20' {$Mmy} order by ydh desc";
							$sql_yd=$xingao->query($query_yd);
							while($yd=$sql_yd->fetch_array())
							{
								$num=mysqli_num_rows($xingao->query("select ydh from lipei where ydh='{$yd[ydh]}' and  status in ({$status}) {$Mmy}"));
								if(!$num)
								{
									echo '<option value="'.$yd['ydh'].'"  '.($rs['ydh']==$yd['ydh']?'selected':'').'>'.$yd['ydh'].'</option>';
								}
							}
						   ?>
						  </select>
                          <span class="help-block"><?=$LG['lipei.form_3'];//每个运单号只能申请一次,请检查清楚是否还有其他问题一同申请?></span>

                    </div>
                  </div>
                  
                  <div class="form-group">
                    <label class="control-label col-md-2"><?=$LG['lipei.mobile'];//联系电话?></label>
                    <div class="col-md-10 has-error">
                      <input type="text" class="form-control input-medium" name="mobile" required value="<?=cadd($rs['mobile'])?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2"><?=$LG['lipei.email'];//联系邮箱?></label>
                    <div class="col-md-10 has-error">
                      <input type="text" class="form-control input-medium" name="email" required value="<?=cadd($rs['email'])?>">
                    
                    </div>
                  </div>
                  
                  
                  <div class="form-group">
                    <label class="control-label col-md-2"><?=$LG['explain']?></label>
                    <div class="col-md-10 has-error">
                      <textarea  class="form-control" rows="3" name="content" required><?=cadd($rs['content'])?>
</textarea>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2"><?=$LG['lipei.form_2'];//凭证(必须上传)?></label>
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
                    <label class="control-label col-md-2"><?=$LG['lipei.requ'];//赔付要求?></label>
                    <div class="col-md-10">
                      <textarea  class="form-control" rows="3" name="requ"><?=cadd($rs['requ'])?>
</textarea>
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
        
        
<button type="submit" class="btn btn-primary input-small" id="openSmt1" disabled > <i class="icon-ok"></i> <?=$LG['submit']?> </button>
          <button type="reset" class="btn btn-default" style="margin-left:30px;"> <?=$LG['reset']?> </button>
        </div>
      </div>
    </div>
  </form>
</div>
<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/foot.php');
?>
