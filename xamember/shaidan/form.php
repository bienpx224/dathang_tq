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
$headtitle=$LG['name.nav_53'];//晒单
require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/head.php');

if(!$off_shaidan)
{
	exit ("<script>alert('{$LG['shaidan.form_1']}');goBack('uc');</script>");
}

//获取,处理
$lx=par($_GET['lx']);
$sdid=par($_GET['sdid']);
if(!$lx){$lx='add';}
$types=par($_GET['types']);

if($lx=='edit')
{
	if(!$sdid){exit ("<script>alert('sdid{$LG['pptError']}');goBack();</script>");}
	
	$rs=FeData('shaidan','*',"sdid='{$sdid}' {$Mmy}");
	if($rs['checked']){exit ("<script>alert('{$LG['shaidan.form_2']}');goBack('uc');</script>");}
	if(!CheckEmpty($types)){$types=par($rs['types']);}
}

//生成令牌密钥(为安全要放在所有验证的最后面)
$token=new Form_token_Core();
$tokenkey= $token->grante_token("shaidan");
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
    <input name="sdid" type="hidden" value="<?=$rs['sdid']?>">
    <input name="tokenkey" type="hidden" value="<?=$tokenkey?>">
    <div><!-- class="tabbable tabbable-custom boxless"-->
      <div class="tab-content">
        <div class="tab-pane active" id="tab_1">
          <div class="form">
            <div class="form-body">
              <div class="portlet">
                <div class="portlet-body form" style="display: block;"> 
                  <!--表单内容-->
                  
                  
                <div class="form-group" style="display:none">
                    <label class="control-label col-md-2"><?=$LG['shaidan.classid'];//所属栏目?></label>
                    <div class="col-md-10 has-error">
                      <select  class="form-control input-medium select2me" data-placeholder="Select..." name="classid">
					  <?php
					  if($rs['classid']){$classid=$rs['classid'];}
                      LevelClass(0,0,$classid,'2',0);
                      ?>
                      </select>
                       
                    </div>
                  </div>
                                        
                <div class="form-group">
                    <label class="control-label col-md-2"><?=$LG['shaidan.types'];//类型?></label>
                    <div class="col-md-10 has-error">
                      <select  class="form-control input-medium select2me" data-placeholder="Select..." name="types" required onChange="shaidan_Types()";>
                      <option></option>
					  <?=shaidan_Types($types,1)?>
                      </select>
                       
                    </div>
                  </div>

<SCRIPT LANGUAGE="JavaScript">  
function shaidan_Types()  
{  
    location.href="?lx=<?=$lx?>&sdid=<?=$sdid?>&types="+$('[name="types"]')[0].value;  
}  
</SCRIPT>


<?php if(CheckEmpty($types)){?>                   
                  <div class="form-group">
                    <label class="control-label col-md-2"><?=$LG['shaidan.old_ydh'];//运单号?></label>
                    <div class="col-md-10 has-error">
					   <!--后台添加时手工填写-->
					   <input type="hidden" name="old_ydh" value="<?=cadd($rs['ydh'])?>">
					  <?php if($_SESSION['manage']['userid']){?>
						  <input type="text" class="form-control input-medium" name="ydh" required value="<?=cadd($rs['ydh'])?>">
						   <span class="help-block"><?=$LG['shaidan.form_3'];//您已登录后台,有特殊权限,可以手工填写单号并且可以不是已有单号?></span>
					  <?php }else{?>
						  <select name="ydh" class="form-control input-medium select2me" data-placeholder="Select..." required>
						   <?php 
							$query_yd="select ydh from yundan where status>='20' {$Mmy} order by ydh desc";
							$sql_yd=$xingao->query($query_yd);
							while($yd=$sql_yd->fetch_array())
							{
								$num=mysqli_num_rows($xingao->query("select ydh from shaidan where ydh='{$yd[ydh]}' and  types='{$types}'"));
								if(!$num)
								{
									echo '<option value="'.$yd['ydh'].'">'.$yd['ydh'].'</option>';
								}
							}
							
							if($rs['ydh']){echo '<option value="'.cadd($rs['ydh']).'">'.cadd($rs['ydh']).'</option>';}
						   ?>
						  </select>
						   <span class="help-block"><?=$LG['shaidan.form_4'];//每个运单号只能晒单一次,如果没有显示某运单号,说明已经晒单过?></span>
					  <?php }?>
                     
                    </div>
                  </div>
                  
                  
                  
                  
                  
                  <div class="form-group">
                    <label class="control-label col-md-2"><?=!$types?$LG['shaidan.content']:$LG['shaidan.form_6']?></label>
                    <div class="col-md-10 has-error">
                      <textarea  class="form-control" rows="3" name="content" required><?=cadd($rs['content'])?>
</textarea>
                     <span class="help-block"><?=$types?$LG['shaidan.form_7']:''?></span>
                     
                     <!--站外晒单说明-->
					 <?php if($types){?>
                     <span class="help-block"><?=caddhtml($shaidan_explain);?></span>
                     <?php }?>

                    </div>
                  </div>
                  
                  
                  
                  <?php if(!$types){?>
                  <div class="form-group">
                    <label class="control-label col-md-2"><?=$LG['img'];//图片?>
                    (<font class="red2"><?=$LG['MustUploaded']//必须上传?></font>)
                    </label>
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
				<?php }?>                
<?php }//if(CheckEmpty($types)){?>                  
                  
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
require_once('ts_call.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/foot.php');
?>
