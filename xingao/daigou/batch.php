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
$pervar='daigou_ed';require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');
$headtitle="批量修改";
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/head.php');
if(!$ON_daigou){ exit("<script>alert('{$LG['daigou.45']}');goBack('uc');</script>"); }

//显示表单获取,处理
$lx=par($_POST['lx']);
$forList=par($_POST['forList']);
$id_name='Xdgid';

$dgid=par(ToStr($_GET['dgid']));
if($forList&&(!$dgid||is_array($_GET['dgid']))){$dgid=$_SESSION[$id_name];}//如果是数组,说明是从底部点击的按钮,要用_SESSION才能获取分页里的勾选
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
  
  <form action="batch_save.php" method="post" class="form-horizontal form-bordered" name="xingao" target="_blank">
  <input type="hidden" name="lx" value="tj">
    <div><!-- class="tabbable tabbable-custom boxless"-->
      <div class="tab-content">
        <div class="tab-pane active" id="tab_1">
          <div class="form">
            <div class="form-body">
			<div class="portlet">
                <div class="portlet-title">
                  <div class="caption"><font class="red2"><i class="icon-reorder"></i> 修改内容</font></div>
                  <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
                </div>
                <div class="portlet-body form" style="display: block;">
                  <!--表单内容-->
                  
                  
               <div class="form-group">
                  <label class="control-label col-md-2"><?=$LG['status']//状态?></label>
                  <div class="col-md-10">
					<select  class="form-control input-medium select2me" data-placeholder="留空则不修改" name="status">
					<option></option>
                    <?php daigou_Status('',1)?>
                   </select>
                   
					<span class="help-block">
					<input type="checkbox" name="options1" value="1" <?=$options1||!$lx?'checked':''?>>未超过该状态时才修改<br>
					<input type="checkbox" name="options2" value="1" <?=$options2||!$lx?'checked':''?>>通过审核时才修改<br>
					<input type="checkbox" name="options3" value="1" <?=$options3||!$lx?'checked':''?>>未有申请操作时才修改<br>
					</span>
                    
                     <span class="help-block">&raquo; 已支付的代购单不可变更为【<?=daigou_Status(10)?>】,请处理为【<?=daigou_lackStatus(2)?>】会自动变更</span>
                     
                    
                  </div>
                </div>   
                
				<div class="form-group">
                    <label class="control-label col-md-2">处理会员申请</label>
                    <div class="col-md-10">
                     <select name="manageStatus" class="form-control input-medium select2me" data-placeholder="留空则不修改">
						 <option></option>
						 <?php daigou_manageStatus('',1);?>
					 </select>
                     <span class="help-block">如未选择【商品申请状态】,将对该单的所有商品进行更新</span>
                    </div>
                  </div>

               
				<div class="form-group">
                    <label class="control-label col-md-2">所在仓库</label>
                    <div class="col-md-10">
                     <select name="warehouse" class="form-control input-medium select2me" data-placeholder="留空则不修改">
						 <option></option>
						 <?php warehouse('',1);?>
					 </select>
                    </div>
                  </div>

                  
				<div class="form-group">
					<label class="control-label col-md-2">回复会员</label>
					<div class="col-md-10">
						<textarea  class="form-control" rows="3" name="memberContentReply"  placeholder="留空则不修改"></textarea>
						<span class="help-block">
						 <div class="radio-list">
							<label class="radio-inline"><input type="radio" name="memberContentReply_lx" value="1" checked>增加方式 </label>
							<label class="radio-inline"><input type="radio" name="memberContentReply_lx" value="0">修改方式 </label>
						</div>
						</span>
					</div>
				</div>
                  
				<div class="form-group">
					<label class="control-label col-md-2">回复供应商</label>
					<div class="col-md-10">
						<textarea  class="form-control" rows="3" name="sellerContentReply"  placeholder="留空则不修改"></textarea>
						<span class="help-block">
						 <div class="radio-list">
							<label class="radio-inline"><input type="radio" name="sellerContentReply_lx" value="1" checked>增加方式 </label>
							<label class="radio-inline"><input type="radio" name="sellerContentReply_lx" value="0">修改方式 </label>
						</div>
						</span>
					</div>
				</div>



                </div>
              </div>
			  <?php require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/daigou/call/where_form.php');?>
			  			
          </div>
          
          </div>
        </div>
        
    <div align="center">
      <button type="submit" class="btn btn-primary input-small" id="openSmt1" disabled 
      onClick="
      document.xingao.lx.value='tj';
      document.xingao.target='_blank';
      "
      > <i class="icon-ok"></i> <?=$LG['submit']?> </button>
      
      <button type="submit" class="btn btn-grey input-small"
      onClick="
      document.xingao.lx.value='num';
      document.xingao.target='iframe';
      ">显示代购单数 </button>
      
      <button type="reset" class="btn btn-default" style="margin-left:30px;"> <?=$LG['reset']?> </button>
      
      <iframe src="" id="iframe" name="iframe" width="100%" height="40" frameborder="0" scrolling="auto"></iframe>
         
    </div>
    
      </div>
    </div>
  </form>
 <div class="xats"><br>
	<strong>提示:<br></strong>
	&raquo; 修改状态时发邮件通知速度较慢，操作完后请不要立即关闭本页面! (建议等待10秒后再关闭)<br />
</div>
 
</div>
<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/foot.php');
?>

