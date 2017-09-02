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
$headtitle=$LG['name.nav_9'];//我的推广
require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/head.php');

if(!$off_tuiguang){exit ("<script>alert('{$LG['tuiguang.tuiguang_bak_1']}');goBack();</script>");}
	 
//搜索
$where="1=1";
$so=(int)$_GET['so'];
if($so)
{
	$key=par($_GET['key']);
	$stime=par($_GET['stime']);
	$etime=par($_GET['etime']);
	$status=par($_GET['status']);
	if($key){$where.=" and (tg_userid='".CheckNumber($key,-0.1)."' or tg_username='{$key}')";}
	if($stime){$where.=" and addtime>='".strtotime($stime.' 00:00:00')."'";}
	if($etime){$where.=" and addtime<='".strtotime($etime.' 23:59:59')."'";}

	$search.="&so={$so}&key={$key}&stime={$stime}&etime={$etime}";
}

$timequerycall=1;
$field="addtime";
include($_SERVER['DOCUMENT_ROOT'].'/public/timequery.php');//按时间快速查询


$order=' order by id desc';//默认排序
include($_SERVER['DOCUMENT_ROOT'].'/public/orderby.php');//排序处理页


//echo $search;exit();
$query="select * from tuiguang_bak where {$where}  {$Mmy} {$order}";

//分页处理
//$line=20;$page_line=15;//$line=-1则不分页(不设置则默认)
include($_SERVER['DOCUMENT_ROOT'].'/public/page.php');
?>

<div class="page_ny"> 
  <!-- BEGIN PAGE HEADER-->
	<div class="row"><!--单页时去掉 class="row",不然会有横滚动条,并且form 加 style="margin:20px;"-->
		<div class="col-md-12"> 
<!-- BEGIN PAGE TITLE & BREADCRUMB-->
      <h3 class="page-title"> <a href="javascript:void(0)" onClick="javascript:window.location.href=window.location.href;" title="<?=$LG['refresh']?>" class="gray">
        <?=$headtitle?>
        </a> </h3>
      
      <!-- END PAGE TITLE & BREADCRUMB--> 
    </div>
  </div>
  <!-- END PAGE HEADER--> 
  
  <!-- BEGIN PAGE CONTENT-->
  <div class="portlet tabbable">
    <div class="portlet-body" style="padding:10px;">
    
    
    
      <?php if($off_tuiguang){?>
      <div class="navbar navbar-default" role="navigation">
        <div class="collapse navbar-collapse navbar-ex1-collapse" style="padding:10px;">
          
		<?php require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/tuiguang/call/ppt.php');?>

        </div>
      </div>
      <?php }?>
      
      
      
      
      
      <!--搜索-->
      <div class="navbar navbar-default" role="navigation" style="z-index:0"><!--列表搜索版块档住弹窗或下拉框时,加这个style="z-index:0" -->
        
        <div class="collapse navbar-collapse navbar-ex1-collapse">
          <form class="navbar-form navbar-left"  method="get" action="?">
            <input name="so" type="hidden" value="1">
            <div class="form-group">
              <input type="text" name="key" class="form-control input-msmall popovers" data-trigger="hover" data-placement="right"  data-content="<?=$LG['tuiguang.tuiguang_bak_6'];//注册会员ID/注册会员名?>" value="<?=$key?>">
            </div>
            <div class="form-group">
              <div class="col-md-0">
                <div class="input-group input-xmedium date-picker input-daterange" data-date-format="yyyy-mm-dd">
                  <input type="text" class="form-control input-small" name="stime" value="<?=$stime?>">
                  <span class="input-group-addon">-</span>
                  <input type="text" class="form-control input-small" name="etime" value="<?=$etime?>">
                </div>
              </div>
            </div>
            <button type="submit"  class="btn btn-info"><i class="icon-search"></i> <?=$LG['search'];//搜索?></button>
          </form>
        </div>
        <?php
		$timequeryshow=1;
		$searchtime="&so={$so}&key={$key}";
		include($_SERVER['DOCUMENT_ROOT'].'/public/timequery.php');//按时间快速查询
		?>
      </div>
      <form action="save.php" method="post" name="XingAoForm">
        <input name="lx" type="hidden">
        <table class="table table-striped table-bordered table-hover" >
          <thead>
            <tr>
              <th align="center"><a href="?<?=$search?>&orderby=tg_username&orderlx=" class="<?=orac('tg_username')?>"><?=$LG['Musername'];//会员名?></a></th>
              <th align="center"><a href="?<?=$search?>&orderby=tg_userid&orderlx=" class="<?=orac('tg_userid')?>"><?=$LG['Muserid'];//会员ID?></a></th>
              <th align="center"><a href="?<?=$search?>&orderby=status&orderlx=" class="<?=orac('status')?>"><?=$LG['status'];//状态?></a></th>
              <th align="center"><a href="?<?=$search?>&orderby=integral&orderlx=" class="<?=orac('integral')?>"><?=$LG['tuiguang.tuiguang_bak_7'];//送积分?></a></th>
              <th align="center"><a href="?<?=$search?>&orderby=coupons&orderlx=" class="<?=orac('coupons')?>"><?=$LG['coupons.headtitle'];//优惠券/折扣券?></a></th>
              <th align="center"><a href="?<?=$search?>&orderby=addtime&orderlx=" class="<?=orac('addtime')?>"><?=$LG['tuiguang.tuiguang_bak_8'];//注册时间?></a></th>
            </tr>
          </thead>
          <tbody>
<?php
while($rs=$sql->fetch_array())
{
?>
            <tr class="odd gradeX  <?=!spr($rs['status'])?'gray2':''?>">
              <td align="center"><?=substr_cut($rs['tg_username'],2)?></td>
              <td align="center"><?=cadd($rs['tg_userid'])?></td>
              <td align="center"><?=spr($rs['status'])?$LG['tuiguang.tuiguang_bak_9']:$LG['tuiguang.tuiguang_bak_10']?></td>
			   <td align="center"><?=spr($rs['integral'])?></td>
			   <td align="center"><?=spr($rs['coupons'])?></td>
              <td align="center"><?=DateYmd($rs['addtime'],1)?></td>
            </tr>
<?php
}
?>
          </tbody>
        </table>
        <div class="row">
          <?=$listpage?>
        </div>
      </form>
    </div>
    <!--表格内容结束--> 
    
  </div>
</div>
<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/foot.php');
?>
