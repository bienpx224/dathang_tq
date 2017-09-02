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
$pervar='manage_ex';require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/head.php');
$headtitle='汇率设置';

if(!$openCurrency)
{
	exit ("<script>alert('请先在系统设置里选择支持的币种！');goBack('uc');</script>");
}

exchangeUpdate();//更新币种


//输出
$where='1=1';
$order=' order by fromCurrency asc,toCurrency asc';//默认排序
include($_SERVER['DOCUMENT_ROOT'].'/public/orderby.php');//排序处理页

//echo $search;exit();
$query="select * from exchange where {$where} {$order}";

//分页处理
$line=-1;//$line=20;$page_line=15;//$line=-1则不分页(不设置则默认)
include($_SERVER['DOCUMENT_ROOT'].'/public/page.php');

?>

<div class="page_ny"> 
  <!-- BEGIN PAGE HEADER-->
	<div class="row"><!--单页时去掉 class="row",不然会有横滚动条,并且form 加 style="margin:20px;"-->
		<div class="col-md-12"> 
<!-- BEGIN PAGE TITLE & BREADCRUMB-->
      <h3 class="page-title">
        <a href="javascript:void(0)" onClick="javascript:window.location.href=window.location.href;" title="<?=$LG['refresh']?>" class="gray"><?=$headtitle?></a>
      </h3>
      
      <!-- END PAGE TITLE & BREADCRUMB--> 
    </div>
  </div>
  <!-- END PAGE HEADER--> 
  
  <!-- BEGIN PAGE CONTENT-->
    <div class="portlet tabbable">
      <div class="portlet-body" style="padding:10px;">
      <!--搜索-->
      
  <form action="save.php" method="post" class="form-horizontal form-bordered" name="xingao">
  <input name="lx" type="hidden" value="edit">
        <table class="table table-striped table-bordered table-hover" >
          <thead>
            <tr>
              <th align="center"><a href="?<?=$search?>&orderby=exid&orderlx=" class="<?=orac('exid')?>">ID</a></th>
              <th align="center"><a href="?<?=$search?>&orderby=exchange&orderlx=" class="<?=orac('exchange')?>">汇率</a></th>
              
              <?php if($ON_exchange){?> 
              <th align="center"><a href="?<?=$search?>&orderby=autoGet&orderlx=" class="<?=orac('autoGet')?>">自动获取</a></th>
              <th align="center"><a href="?<?=$search?>&orderby=autoScopeStart&orderlx=" class="<?=orac('autoScopeStart')?>">自动获取范围</a></th>
              <th align="center"><a href="?<?=$search?>&orderby=autoInRe&orderlx=" class="<?=orac('autoInRe')?>">自动获取增减</a></th>
              <?php }?>
              
               <th align="center"><a href="?<?=$search?>&orderby=edittime&orderlx=" class="<?=orac('edittime')?>">修改时间</a></th>
            </tr>
          </thead>
          <tbody>

<?php
while($rs=$sql->fetch_array())
{
?>
            
            
            <tr class="odd gradeX gray2">
               <td align="center" class="gray_prompt2">
              <input type="hidden" name="exid[]" value="<?=$rs['exid']?>">
              <?=$rs['exid']?>
              
              </td>

             <td align="center">
              1 <?=cadd($rs['fromCurrency'])?>=
              <input class="form-control input-small tooltips" data-container="body" data-placement="top" data-original-title="<?php if($ON_exchange){?>开启自动获取时可留空<?php }else{?>必填<?php }?>" type="text" name="exchange[]" value="<?=spr($rs['exchange'],5)?>" <?=$ON_exchange?'':'required'?>>
			  <?=cadd($rs['toCurrency'])?>
              
              </td>
              
<?php if($ON_exchange){?> 

    <td align="center">
        <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
        <input type="checkbox" class="toggle" name="autoGet<?=$rs['exid']?>" value="1" <?=$rs['autoGet']?'checked':''?> />
        </div>
        
        
    </td>
    
    <td align="center">
        <input class="form-control input-small tooltips" data-container="body" data-placement="top" data-original-title="最小值 (0则不限)" type="text" name="autoScopeStart[]" value="<?=spr($rs['autoScopeStart'],5)?>" >
        -
        <input class="form-control input-small tooltips" data-container="body" data-placement="top" data-original-title="最大值 (0则不限)" type="text" name="autoScopeEed[]" value="<?=spr($rs['autoScopeEed'],5)?>">
        
        
    </td>
    
    <td align="center">
        <input class="form-control input-small tooltips" data-container="body" data-placement="top" data-original-title="在已获取的汇率基础上再多加或减少 (正数为增加，负数为减少)" type="text" name="autoInRe[]" value="<?=spr($rs['autoInRe'],5)?>" >
    </td>

<?php }?>             
              
              <td align="center" class="gray2">
			 <span class="help-block tooltips" data-container="body" data-placement="top" data-original-title="修改时间"> <?=DateYmd($rs['edittime'])?></span>
                
               <?php if($ON_exchange){?>
               <span class="help-block tooltips" data-container="body" data-placement="top" data-original-title="获取时间"><?=DateYmd($rs['autotime'])?></span>
			   <?php }?>
              </td>
              
            </tr>
            <?php
}
?>
          </tbody>
        </table>
        
        <br>        
        
                
<!--提交按钮固定--> 
<style>body{margin-bottom:50px !important;}</style><!--后台不用隐藏,增高底部高度-->
<div align="center" class="fixed_btn" id="Autohidden">





      <button type="submit" class="btn btn-primary input-small" id="openSmt1" disabled > <i class="icon-ok"></i> <?=$LG['submit']?> </button>
          <button type="reset" class="btn btn-default" style="margin-left:30px;"> <?=$LG['reset']?> </button>
        </div>

</form>
      </div>
      <!--表格内容结束--> 

    <div class="xats">
        <br>
        <strong> 提示：</strong><br />
        
		<?php if($ON_exchange){?>
        &raquo; 当前系统已开启自动获取汇率功能，可以对各币种单独设置是否使用自动获取；<br>
        
        <font class="red2">&raquo; 自动获取是从<strong>兴奥转运接口平台</strong>获取，汇率数据来源网络，兴奥公司不承担因汇率错误而对您公司造成的损失。<br></font>
        &raquo; 建议您填写获取范围值，以防止获取错误时减少损失，并且建议定期检查汇率。您也可以关闭自动获取功能，用手工填写。
        <?php }else{?>
        &raquo; 当前系统已关闭自动获取汇率功能，以上需要手工填写汇率并且不可设置自动获取；
        <?php }?>	 
         
	</div>
     
    
        </div>
</div>
<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/foot.php');
?>
