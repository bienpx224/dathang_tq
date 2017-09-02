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
$pervar='off_settlement';require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/session.php');
$headtitle=$LG['settlement.list_other_1'];
require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/head.php');


if(!CheckEmpty($_GET['so'])){$_GET['so']=1;$_GET['tally']=1;}
$op2=par($_GET['op2']);

require($_SERVER['DOCUMENT_ROOT'].'/xingao/settlement/call/list_other_where.php');//条件处理,返回{$select} {$where} {$group} {$order}
include($_SERVER['DOCUMENT_ROOT'].'/public/orderby.php');//排序处理页

$query="select {$select} from money_kfbak where {$where} {$Mmy} {$group} {$order}";

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
        </a> 
		</h3>
        
   <ul class="page-breadcrumb breadcrumb">
  <?php require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/settlement/call/menu.php');?>
  </ul>
     
      <!-- END PAGE TITLE & BREADCRUMB--> 
    </div>
  </div>
  <!-- END PAGE HEADER--> 
  
  <!-- BEGIN PAGE CONTENT-->
  	<div class="tabbable tabbable-custom boxless">
		<ul class="nav nav-tabs">
			
			<li class="" style="margin-right:30px;"><a href="list_yundan.php?<?=$search?>"><?=$LG['settlement.list_other_3'];//运单账单?></a></li>
			<li class="active"><a href="list_other.php?<?=$search?>"><?=$LG['settlement.list_other_4'];//其他账单?></a></li>
			
		</ul>
		<div class="tab-content" style="padding:10px;"> 
       <!--搜索-->
      <div class="navbar navbar-default" role="navigation">
        
        <div class="collapse navbar-collapse navbar-ex1-collapse">
          <form class="navbar-form navbar-left"  method="get" action="?">
            <input name="so" type="hidden" value="1">

             <div class="form-group">
                <div class="col-md-0">
                     <select  class="form-control input-small select2me" name="tally" data-placeholder="<?=$LG['settlement.list_other_5'];//月结销账?>" >
                     <option></option>
                      <?=Tally($tally,1)?>
                  	 </select>
               </div>
              </div>
             
         
        
                
             <div class="form-group">
                <div class="col-md-0">
                  <div class="input-group input-xmedium date-picker input-daterange" data-date-format="yyyy-mm-dd">
                    <input type="text" class="form-control input-small" name="stime" value="<?=$stime?>" placeholder="<?=$LG['settlement.list_other_6'];//记账时间-起?>">
                    <span class="input-group-addon">-</span>
                    <input type="text" class="form-control input-small" name="etime" value="<?=$etime?>" placeholder="<?=$LG['settlement.list_other_8'];//记账时间-止?>">
                  </div>
                 </div>
              </div>
            
               <div class="form-group">
                   <div class="col-md-0">
                  <!--<input type="checkbox" name="op1" value="1" <?=$op1?'checked':''?>>无运单账单的
                  &nbsp;&nbsp;-->
                  <input type="checkbox" name="op2" value="1" <?=$op2?'checked':''?>><?=$LG['total']?>
                  </div>
              </div>
              
              <button type="submit"  class="btn btn-info"><i class="icon-search"></i> <?=$LG['settlement.list_other_12'];//搜索更新?></button>    
                  
                       
          </form>
        </div>
      </div>
      <form action="?<?=$search?>" method="post" name="XingAoForm">
        <table class="table table-striped table-bordered table-hover" >
          <thead>
            <tr>
              
              <th align="center"><a href="?<?=$search?>&orderby=type&orderlx=" class="<?=orac('type')?>"><?=$LG['settlement.list_other_13'];//扣费类型?></a></th>
               <th align="center"><a href="?<?=$search?>&orderby=fromtable&orderlx=" class="<?=orac('fromtable')?>"><?=$LG['settlement.list_other_14'];//用途?></a> </th>
               <th align="center"><a href="?<?=$search?>&orderby=title&orderlx=" class="<?=orac('title')?>"><?=$LG['settlement.list_other_15'];//使用说明?></a> </th>
              
              <th align="center"><a href="?<?=$search?>&orderby=money&orderlx=" class="<?=orac('money')?>"><?=$LG['settlement.list_other_16'];//扣费?></a></th>
              <th align="center"><a href="?<?=$search?>&orderby=addtime&orderlx=" class="<?=orac('addtime')?>"><?=$LG['settlement.list_other_17'];//扣费时间?></a></th>
            
            </tr>
          </thead>
          <tbody>
<?php
while($rs=$sql->fetch_array())
{
?>
            <tr class="odd gradeX">
              
              <td align="center">
              <?=money_kf($rs['type'])?>
              </td>
              
              <td align="center">
			  <?=$rs['fromtable']?fromtableName($rs['fromtable']).'ID:'.$rs['fromid'].'':''?> </td>
              
              <td align="center">
              <?php if(!$op2){?>
                  <a <?=fromtableUrl($rs['fromtable'],$rs['fromid'])?>  class="popovers" data-trigger="hover" data-placement="top"  data-content="<?=cadd($rs['title'])?><?=$rs['content']?'：'.cadd($rs['content']):''?>">
                  <?=$rs['title']?leng($rs['title'],20,'...'):$LG['explain']?>
                  </a>
              <?php }?>
              </td>
              <td align="center">
              <?php 
			  if($op2)
			  {
				  $sr=FeData('money_kfbak',"count(*) as total,sum(`fromMoney`) as fromMoney","{$where} and userid='{$rs[userid]}'");
				  echo spr($sr['fromMoney']).$XAmc;
			  }
			  ?>
             
              <?php if(!$op2){?>
				  <?=spr($rs['money']).$XAmc?>
                  <?=Tally($rs['tally'])?>
              <?php }?>
              
              </td>
              <td align="center"><?=DateYmd($rs['addtime'],1)?></td>
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



<a class="btn btn btn-info" href="excel_export.php?ex_tem=tem_other_all&<?=$search?>" target="_blank"><i class="icon-share"></i> <?=$LG['settlement.list_other_18'];//按搜索导出?></a>
</div>       

        <div class="row">
          <?=$listpage?>
        </div>
      </form>
      
    <div class="xats"> 
        <br>
        <strong> <?=$LG['pptInfo']?></strong><br />
        
        &raquo; <?=$LG['settlement.list_other_20'];//此账单的退费不显示，按扣费金额计算 (退费:由于某些原因已把费用充到账户,相当给您多充值了款)?><br>
        <font class=" popovers" data-trigger="hover" data-placement="top"  data-content="<?=$LG['settlement.list_other_21']?>">
        &nbsp;&nbsp; <?=$LG['settlement.list_other_22']?>
        </font>
        <br>
    </div>

    </div>
    <!--表格内容结束--> 
    
  </div>
</div>
<?php
$showbox=1;//是否用到 操作弹窗 (/public/showbox.php)
require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/foot.php');
?>
