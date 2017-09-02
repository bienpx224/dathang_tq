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
$pervar='notice';//权限验证
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');
$headtitle="内部公告";
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/head.php');

$Xgr=" and groupid='{$Xgroupid}' ";

//获取,处理
$lx=par($_GET['lx']);
$noid=par($_GET['noid']);
if(!$lx){$lx='add';}

if($lx=='edit')
{
	if(!$noid){exit ("<script>alert('noid{$LG['pptError']}');goBack();</script>");}
	$rs=FeData('notice','*'," noid='{$noid}' {$Xgr}");
	if(!$rs['noid']){exit ("<script>alert('无权修改:可能您与发布者不是同一个分组里！');goBack();</script>");}
}


//生成令牌密钥(为安全要放在所有验证的最后面)
$token=new Form_token_Core();
$tokenkey= $token->grante_token("notice");

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
    <input name="noid" type="hidden" value="<?=$rs['noid']?>">
    <input name="tokenkey" type="hidden" value="<?=$tokenkey?>">
    <div><!-- class="tabbable tabbable-custom boxless"-->
      <div class="tab-content">
        <div class="tab-pane active" id="tab_1">
          <div class="form">
            <div class="form-body">

              <div class="portlet">
                <div class="portlet-title">
                  <div class="caption"><i class="icon-reorder"></i><strong>发布/修改公告</strong></div>
                  <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
                </div>
                <div class="portlet-body form" style="display: block;"> 
                  <!--表单内容-->
                  
                  <div class="form-group">
                    <label class="control-label col-md-2">标题</label>
                    <div class="col-md-10 has-error">
                      <input type="text" class="form-control" name="title" required value="<?=cadd($rs['title'])?>" >
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2">发给</label>
                    <div class="col-md-10">
                  <select  class="form-control input-small select2me" name="to_groupid" data-placeholder="所属">
                  <option></option>
<?php
$query_g="select groupid,groupname from manage_group order by  myorder desc,groupname desc,groupid desc";
$sql_g=$xingao->query($query_g);
while($rs_g=$sql_g->fetch_array())
{
?>
            <option value="<?=$rs_g['groupid']?>" <?=$rs_g['groupid']==$rs['to_groupid']?'selected':''?>><?=cadd($rs_g['groupname'])?></option>
<?php
}
?>
                </select>
					<span class="help-block">不限则发给全部分组</span>
                    </div>
                  </div>
                  
 				 <div class="form-group">
                    <label class="control-label col-md-2">等级</label>
                    <div class="col-md-10">
                  <select  class="form-control input-small select2me" name="level" data-placeholder="等级">
                    <option></option>
                    <?=Notice_Level($rs['level'],1)?>
                  </select>
                    </div>
                  </div>
                                  
         
                   <div class="form-group">
                      <label class="control-label col-md-2">自动弹出</label>
                      <div class="col-md-10"> 
                      
                        <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
                          <input type="checkbox" class="toggle" name="popup" value="1"  <?=$rs['popup']?' checked':''?>/>
                        </div>
                        
                        <input class="form-control input-xsmall tooltips" data-container="body" data-placement="top" data-original-title="每X分钟弹出一次 (不能小于30分)" name="popuptime" value="<?=$rs['popuptime']?$rs['popuptime']:30?>"/>
                        
                        <span class="help-block">只能弹出一条公告 (设置多条时，则用最新时间的那条)</span>
                        
                      </div>
                    </div>
                    
                    
                    
                  <div class="form-group">
                    <label class="control-label col-md-2">处理</label>
                    <div class="col-md-10">
                    
                    <div class="radio-list">
                       <label class="radio-inline">
                       <input type="radio" name="status" value="0" <?=spr($rs['status'])=='0'||$lx=='add'?' checked':''?>>无需处理
                       </label>
                       
                       <label class="radio-inline">
                       <input type="radio" name="status" value="1" <?=spr($rs['status'])=='1'?' checked':''?>>待处理
                       </label>
                       
                       <label class="radio-inline">
                       <input type="radio" name="status" value="2" <?=spr($rs['status'])=='2'?' checked':''?>> 已处理
                       </label>
                    </div>
                      
                    </div>
                  </div>
                  
                  <div class="form-group">
                    <label class="control-label col-md-2">过期</label>
                    <div class="col-md-10">
                    
                    <div class="radio-list">
                       <label class="radio-inline">
                       <input type="radio" name="checked" value="1" <?=$rs['checked']=='1'||$lx=='add'?' checked':''?>>未过期
                       </label>
                       
                       <label class="radio-inline">
                       <input type="radio" name="checked" value="0" <?=$rs['checked']=='0'?' checked':''?>> 已过期
                       </label>
                    </div>
                      
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2">过期时间</label>
                    <div class="col-md-10">
                      	<input class="form-control form-control-inline  input-small date-picker"  data-date-format="yyyy-mm-dd" size="16" type="text" name="duetime1" value="<?=DateYmd($rs['duetime'],2)?>">
					
						<input type="text" id="clockface_2"  name="duetime2" value="<?=DateYmd($rs['duetime'],'H:i')?>" class="form-control input-xsmall" readonly style="margin-right:0px;">
						<button class="btn btn-default" type="button" id="clockface_2_toggle"><i class="icon-time"></i></button> <span class="help-block">留空则不限时</span>
                    </div>
                  </div>
                  
                
                  <div class="form-group">
                    <label class="control-label col-md-2">内容</label>
                    <div class="col-md-10">
                      <textarea  class="form-control" rows="10" name="content"><?=cadd($rs['content'])?></textarea>
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
