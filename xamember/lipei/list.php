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
$headtitle=$LG['lipei.list_1'];
require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/head.php');


//搜索
$where="1=1";
$so=(int)$_GET['so'];
if($so==1)
{
	$key=par($_GET['key']);
	$status=par($_GET['status']);
	$types=par($_GET['types']);
	
	if($key){$where.=" and (ydh like '%{$key}%' or mobile like '%{$key}%' or email like '%{$key}%')";}
	if(CheckEmpty($status)){$where.=" and status='{$status}'";}
	if(CheckEmpty($types)){$where.=" and types='{$types}'";}

	$search.="&so={$so}&key={$key}&status={$status}&types={$types}";
}

$order=' order by status asc,lpid desc';//默认排序
include($_SERVER['DOCUMENT_ROOT'].'/public/orderby.php');//排序处理页


//echo $search;exit();
$query="select * from lipei where {$where}  {$Mmy} {$order}";

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
      <ul class="page-breadcrumb breadcrumb">
        <button type="button" class="btn btn-info" onClick="location.href='form.php';"><i class="icon-plus"></i> <?=$LG['name.nav_32'];//申请理赔?> </button>
        
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
            <input name="my" type="hidden" value="<?=$my?>">
            <div class="form-group">
              <input type="text" name="key" class="form-control input-msmall popovers" data-trigger="hover" data-placement="right"  data-content="<?=$LG['lipei.list_12']?>" value="<?=$key?>">
            </div>
            <div class="form-group">
              <div class="col-md-0">
                <select  class="form-control input-small select2me" name="status" data-placeholder="<?=$LG['status'];//状态?>">
                  <option></option>
                  <?=lipei_Status($status,1)?>
                </select>
              </div>
            </div>
<div class="form-group">
              <div class="col-md-0">
                <select  class="form-control input-small select2me" name="types" data-placeholder="<?=$LG['lipei.types'];//类型?>">
                  <option></option>
                  <?=lipei_Types($types,1)?>
                </select>
              </div>
            </div>
            
                        <button type="submit"  class="btn btn-info"><i class="icon-search"></i> <?=$LG['search'];//搜索?></button>
          </form>
        </div>
      </div>
      <form action="save.php" method="post" name="XingAoForm">
        <input name="lx" type="hidden">
        <input name="addclass" type="hidden">
        <table class="table table-striped table-bordered table-hover" >
          <thead>
            <tr>
              <th align="center" class="table-checkbox"> <input type="checkbox"  id="aAll" onClick="chkAll(this)"  title="<?=$LG['checkAll'];//全选/取消?>"/>
              </th>
              
              <th align="center"><a href="?<?=$search?>&orderby=lpid&orderlx=" class="<?=orac('lpid')?>">ID</a></th>
              <th align="center"><a href="?<?=$search?>&orderby=ydh&orderlx=" class="<?=orac('ydh')?>"><?=$LG['lipei.ydh'];//运单号?></a>/<a href="?<?=$search?>&orderby=types&orderlx=" class="<?=orac('types')?>"><?=$LG['lipei.types'];//类型?></a></th>
             
              <th align="center"><a href="?<?=$search?>&orderby=money&orderlx=" class="<?=orac('money')?>"><?=$LG['lipei.list_5'];//赔付金?></a></th>
               <th align="center"><a href="?<?=$search?>&orderby=mobile&orderlx=" class="<?=orac('mobile')?>"><?=$LG['lipei.list_6'];//联系方式?></a></th>
              <th align="center"><a href="?<?=$search?>&orderby=status&orderlx=" class="<?=orac('status')?>"><?=$LG['status'];//状态?></a></th>
              <th align="center"><?=$LG['op'];//操作?></th>
            </tr>
          </thead>
          <tbody>
<?php
while($rs=$sql->fetch_array())
{
?>
            <tr class="odd gradeX <?=spr($rs['status'])==2?'gray2':''?>">
              <td align="center" valign="middle">
               <?php if(spr($rs['status'])==0||spr($rs['status'])==3){?>
               <input type="checkbox" id="a" onClick="chkColor(this);"  name="lpid[]" value="<?=$rs['lpid']?>" />
               <?php }?>
               </td>
              
              <td align="center" valign="middle">
              
			 <?=$rs['lpid']?><br>
             <font class="gray2">
              <?=DateYmd($rs['addtime']);?>
              </font>
                </td>
                
              <td align="center" valign="middle">
              
			   <?=cadd($rs['ydh'])?><br>
                <font class="gray2">
               <?=lipei_Types($rs['types'])?>
                </font>
                
                </td>
              <td align="center" valign="middle"><?php if(spr($rs['status'])==2){?><?=spr($rs['money'])?> <?=$XAmc?><?php }else{echo $LG['lipei.list_7'];}?></td>
              
              <td align="center" valign="middle">  
			  
			    <?=cadd($rs['mobile'])?><br>
                <font class="gray2">
               <?=cadd($rs['email'])?>
                </font>
                </td>
              
              
                <td align="center" valign="middle">
			   <?=lipei_Status(spr($rs['status']))?>
               </td>
               
              <td align="center" valign="middle">
              <?php if(spr($rs['status'])==0||spr($rs['status'])==3){?>
              <a href="form.php?lx=edit&lpid=<?=$rs['lpid']?>" class="btn btn-xs btn-info"><i class="icon-edit"></i> <?=$LG['showedit']?></a> 
               
              <a href="save.php?lx=del&lpid=<?=$rs['lpid']?>" class="btn btn-xs btn-danger"  onClick="return confirm('<?=$LG['pptDelConfirm'];//确认要删除所选吗?此操作不可恢复!?>');"><i class="icon-remove"></i> <?=$LG['del']?></a> 
              <?php }?>

              </td>
            </tr>
            
            <tr>
              <td colspan="7" align="left">
              
		 <?php
         $zhi=cadd($rs['content']);
         if($zhi){
		 ?>
          <div class="gray modal_border"> <strong><?=$LG['lipei.list_8'];//说明?>：</strong>
            <?php 
			echo leng($zhi,100,"...");
			Modals($zhi,$title=$LG['lipei.list_8'],$time=$rs['addtime'],$nameid='content'.$rs['lpid'],$count=100);
			?>
           </div>
        <?php }?>
        <!---->
		
		 <?php
         $zhi=cadd($rs['requ']);
         if($zhi){
		 ?>
          <div class="gray modal_border"> <strong><?=$LG['lipei.list_9'];//要求：?>：</strong>
            <?php 
			echo leng($zhi,100,"...");
			Modals($zhi,$title=$LG['lipei.list_9'],$time=$rs['addtime'],$nameid='requ'.$rs['lpid'],$count=100);
			?>
           </div>
        <?php }?>
        <!---->
              
        <!--凭证-->
        <?php if($rs['img']){?>
        <div class="gray modal_border"><!-- <strong><?=$LG['lipei.list_10'];//凭证：?></strong>-->
      	  <?php EnlargeImg($rs['img'],$rs['lpid'])?>
        </div>
        <?php }?>
                  
           <!---->                      
		 <?php
         $zhi=cadd($rs['reply']);
         if($zhi){
		 ?>
          <div class="gray modal_border"> <strong><?=$LG['lipei.list_11'];//回复：?>：</strong>
            <?php 
			echo leng($zhi,100,"...");
			Modals($zhi,$title=$LG['lipei.list_11'],$time=$rs['replytime'],$nameid='reply'.$rs['lpid'],$count=100);
			?>
           </div>
        <?php }?>
        <!---->

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



<?php if(!$off_delbak||$off_delbak&&$status==0){?>
          <!--btn-danger--><button type="submit"  class="btn btn-grey" onClick="
  document.XingAoForm.lx.value='del';
  return confirm('<?=$LG['pptDelConfirm'];//确认要删除所选吗?此操作不可恢复!?>');
  "><i class="icon-signin"></i> <?=$LG['delSelect'];//删除所选?></button>
 <?php }?> 
       </div>
        <div class="row">
          <?=$listpage?>
        </div>
      </form>
    </div>
    <!--表格内容结束--> 
    
  </div>
</div>

  
                     
     
<?php
$sql->free(); //释放资源
$enlarge=1;//是否用到 图片扩大插件 (/public/enlarge/call.html)
require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/foot.php');
?>
