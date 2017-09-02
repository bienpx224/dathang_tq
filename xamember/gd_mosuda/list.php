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
$headtitle=$LG['gd.12'];
require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/head.php');
if(!$ON_gd_mosuda||!$ON_gd_mosuda_apply){exit ("<script>alert('{$LG['pptCloseGD']}');goBack();</script>");}


//搜索
$where="1=1";
$so=(int)$_GET['so'];

//if(!$so){$so=1;$_GET['record']=2;}//默认显示已备案
if($so==1)
{
	$key=par($_GET['key']);
	$stime_add=par($_GET['stime_add']);
	$etime_add=par($_GET['etime_add']);
	
	
	if($key){$where.=" and (name like '%{$key}%' or brand like '%{$key}%' or url like '%{$key}%' or gdid='".CheckNumber($key,-0.1)."' )";}
	
	if($stime_add){$where.=" and addtime>='".strtotime($stime_add." 00:00:00")."'";}
	if($etime_add){$where.=" and addtime<='".strtotime($etime_add." 23:59:59")."'";}

	$search.="&so={$so}&key={$key}&checked={$checked}&record={$record}&stime_add={$stime_add}&etime_add={$etime_add}";
}

$order=' order by record asc,myorder desc, onclick desc';//默认排序
include($_SERVER['DOCUMENT_ROOT'].'/public/orderby.php');//排序处理页


$query="select * from gd_mosuda where {$where} {$Mmy} {$order}";

$line=10;$page_line=15;//分页处理，不设置则默认
include($_SERVER['DOCUMENT_ROOT'].'/public/page.php');
?>

<div class="page_ny"> 
  <!-- BEGIN PAGE HEADER-->
	<div class="row"><!--单页时去掉 class="row",不然会有横滚动条,并且form 加 style="margin:20px;"-->
		<div class="col-md-12"> 
<!-- BEGIN PAGE TITLE & BREADCRUMB-->
      <h3 class="page-title"><a href="javascript:void(0)" onClick="javascript:window.location.href=window.location.href;" title="<?=$LG['refresh']?>" class="gray">
        <?=$headtitle?>
        </a> </h3>
        
      <ul class="page-breadcrumb breadcrumb">
      <a href="/xamember/gd_mosuda/form.php" class="btn btn-info showdiv" target="XingAobox"><?=$LG['gd.8'];//申请备案?></a>
	  </ul>
      

      <!-- END PAGE TITLE & BREADCRUMB--> 
    </div>
  </div>
  <!-- END PAGE HEADER--> 
  
  <!-- BEGIN PAGE CONTENT-->
  <div class="tabbable tabbable-custom boxless">
    <div class="tab-content" style="padding:10px;"> 
      <!--搜索-->
      <div class="navbar navbar-default" role="navigation">
        
        <div class="collapse navbar-collapse navbar-ex1-collapse">
          <form class="navbar-form navbar-left"  method="get" action="?">
            <input name="so" type="hidden" value="1">
            <div class="form-group">
              <input type="text" name="key" class="form-control input-msmall popovers" data-trigger="hover" data-placement="right"  data-content="<?=$LG['gd.10']//关键词 (可留空)?>" value="<?=$key?>">
            </div>
            
            
  
        <div class="form-group">
          <div class="col-md-0">
            <div class="input-group input-xmedium date-picker input-daterange" data-date-format="yyyy-mm-dd">
              <input type="text" class="form-control input-small" name="stime_add" value="<?=$stime_add?>" placeholder="<?=$LG['tixian.list_3'];//申请时间?>">
              <span class="input-group-addon">-</span>
              <input type="text" class="form-control input-small" name="etime_add" value="<?=$etime_add?>"  placeholder="<?=$LG['tixian.list_3'];//申请时间?>">
            </div>

          </div>

        </div>

       <button type="submit"  class="btn btn-info"><i class="icon-search"></i> <?=$LG['search'];//搜索?></button>
            
          </form>
        </div>
      </div>
      <form action="save.php" method="post" name="XingAoForm">
        <input name="lx" type="hidden">
        <table class="table table-striped table-bordered table-hover" style="border:0px solid #ddd;">
          <thead>
            <tr>
              <th align="center" class="table-checkbox"> <input type="checkbox"  id="aAll" onClick="chkAll(this)"  title="<?=$LG['checkAll'];//全选/取消?>"/>
              </th>
              <th align="center"><a href="?<?=$search?>&orderby=name&orderlx=" class="<?=orac('name')?>"><?=$LG['mall_order.form_23'];//品名?></a></th>
              <th align="center"><a href="?<?=$search?>&orderby=brand&orderlx=" class="<?=orac('brand')?>"><?=$LG['brand'];//品牌?></a></th>
              <th align="center"><a href="?<?=$search?>&orderby=price&orderlx=" class="<?=orac('price')?>"><?=$LG['mall_order.Xcall_money_payment_9'];//单价?></a></th>
               <th align="center"><a href="?<?=$search?>&orderby=url&orderlx=" class="<?=orac('url')?>"><?=$LG['gd_mosuda.url'];//购买链接?></a></th>
               <th align="center"><a href="?<?=$search?>&orderby=addtime&orderlx=" class="<?=orac('addtime')?>"><?=$LG['tixian.list_3'];//申请时间?></a></th>
             <th align="center"><?=$LG['op'];//操作?></th>
            </tr>
          </thead>
          <tbody>
<?php
while($rs=$sql->fetch_array())
{
?>
        <tr class="odd gradeX  <?=$rs['checked']?'':'gray2';?>">
              <td rowspan="2" align="center" valign="middle">
               
                <input type="checkbox" id="a" onClick="chkColor(this);"  name="gdid[]" value="<?=$rs['gdid']?>"/><br>
<font class="gray2 tooltips" data-container="body" data-placement="top" data-original-title="<?=$LG['gd.11'];//商品资料ID?>"><?=$rs['gdid']?></font>
                
                </td>
              <td align="center" valign="middle">
                <font class="<?=$rs['record']==1?'red2':'';?> tooltips" data-container="body" data-placement="top" data-original-title="<?=Record($rs['record'])?>"> <?=cadd($rs['name'])?> </font>
              </td>
              <td align="center" valign="middle"><?=cadd($rs['brand'])?></td>
              <td align="center" valign="middle"><?=$rs['price']>0?spr($rs['price']).'CNY':''?></td>
              <td align="center" valign="middle"><a href="<?=cadd($rs['url'])?>" target="_blank"><?=leng($rs['url'],30)?></a></td>
              <td align="center" valign="middle"><?=DateYmd($rs['addtime'])?></td>
             
              <td align="center" valign="middle">
                
                <?php if($rs['userid']==$Muserid){?>
                <a href="form.php?lx=edit&gdid=<?=$rs['gdid']?>" class="btn btn-xs btn-info showdiv" target="XingAobox"><i class="icon-edit"></i> <?=$LG['edit']//修改?></a>
                
                <a href="save.php?lx=del&gdid=<?=$rs['gdid']?>" class="btn btn-xs btn-danger"  onClick="return confirm('<?=$LG['pptDelConfirm'];//确认要删除所选吗?此操作不可恢复!?>');"><i class="icon-remove"></i> <?=$LG['del']?></a>
                <?php }?>
                
              </td>
            </tr>
<?php
}
?>
          </tbody>
        </table>			
            
            
<!--底部操作按钮固定--> 
<script>/*$(function(){ Autohidden(); });*/</script> <!--拉到底时自动隐藏-->
<style>body{margin-bottom:40px !important;}</style><!--不用隐藏,增高底部高度-->
 
<div align="right" class="fixed_btn" id="Autohidden">



<!--************删除************-->
    <!--btn-danger--><button type="submit"  class="btn btn-grey"  style="margin-left:10px;"
    onClick="
    document.XingAoForm.lx.value='del';
	document.XingAoForm.action='save.php';
    return confirm('<?=$LG['pptDelConfirm'];//确认要删除所选吗?此操作不可恢复!?>');
    "><i class="icon-signin"></i> <?=$LG['delSelect'];//删除所选?></button>
</div>

        <div class="row"><?=$listpage?></div>
      </form>
    </div>
    <!--表格内容结束-->
    
	<div class="xats">
        <br>
        <strong> <?=$LG['pptInfo']?></strong><br />
		 &raquo; <?=$LG['gd.9']?><br>		  
	</div>

  </div>
</div>
<?php
$sql->free(); //释放资源
$showbox=1;//是否用到 操作弹窗 (/public/showbox.php)
$enlarge=1;//是否用到 图片扩大插件 (/public/enlarge/call.html)
require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/foot.php');
?>
