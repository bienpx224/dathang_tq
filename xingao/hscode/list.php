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
$pervar='yundan_ot';require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');
$headtitle="HS/HG/批次号/快递单号管理";
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/head.php');

//搜索
$where="1=1";
$so=(int)$_GET['so'];
if($so==1)
{
	$key=par($_GET['key']);
	$types=par($_GET['types']);
	$checked=par($_GET['checked']);
	
	if($key){$where.=" and number like '%{$key}%' ";}
	if(CheckEmpty($types)){$where.=" and types='{$types}'";}
	if(CheckEmpty($checked)){$where.=" and checked='{$checked}'";}
	$search.="&so={$so}&key={$key}&checked={$checked}&types={$types}";
}

$order=' order by hsid desc';//默认排序
include($_SERVER['DOCUMENT_ROOT'].'/public/orderby.php');//排序处理页


//echo $search;exit();
$query="select * from hscode where {$where} {$order}";

//分页处理
//$line=20;$page_line=15;//$line=-1则不分页(不设置则默认)
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
      <ul class="page-breadcrumb breadcrumb">
        <button type="button" class="btn btn-info" onClick="location.href='excel_import.php';"><i class="icon-plus"></i>  <?=$LG['import']?> </button>
        <button type="button" class="btn btn-info" onClick="location.href='form.php';"><i class="icon-plus"></i> <?=$LG['add']?> </button>
      </ul>
      <!-- END PAGE TITLE & BREADCRUMB--> 
    </div>
  </div>
  <!-- END PAGE HEADER--> 
  
  <!-- BEGIN PAGE CONTENT-->
    <div class="portlet tabbable">
      <div class="portlet-body" style="padding:10px;">
      <!--搜索-->
      <div class="navbar navbar-default" role="navigation"> 
        
        <div class="collapse navbar-collapse navbar-ex1-collapse">
          <form class="navbar-form navbar-left"  method="get" action="?">
          <input name="so" type="hidden" value="1">
            <div class="form-group">
              <input type="text" name="key" class="form-control input-msmall popovers" data-trigger="hover" data-placement="right"  data-content="号码 (可留空)" value="<?=$key?>">
            </div>
            
           <div class="form-group">
                <div class="col-md-0">
                  <select  class="form-control input-xmedium select2me" name="types" data-placeholder="类型">
                    <option></option>
                    <?php hscode_Types(cadd($types),1)?>
                  </select>
               </div>
              </div>
                 
           <div class="form-group">
                <div class="col-md-0">
                  <select  class="form-control input-small select2me" name="checked" data-placeholder="状态">
                    <option></option>
                    <option value="0"  <?=$checked=='0'?' selected':''?>>已使用</option>
                    <option value="1"  <?=$checked=='1'?' selected':''?>>未使用</option>
                  </select>
               </div>
              </div>
              
            <button type="submit" class="btn btn-info"><i class="icon-search"></i> <?=$LG['search']//搜索?></button>
          </form>
        </div>
      </div>
      
<form action="save.php" method="post" name="XingAoForm">
    <input name="lx" type="hidden">

        <table class="table table-striped table-bordered table-hover" >
          <thead>
            <tr>
              <th align="center" class="table-checkbox"> <input type="checkbox"  id="aAll" onClick="chkAll(this)"  title="全选/取消"/>
              </th>
              <th align="center"><a href="?<?=$search?>&orderby=hscode&orderlx=" class="<?=orac('hscode')?>">号码</a></th>
              <th align="center"><a href="?<?=$search?>&orderby=types&orderlx=" class="<?=orac('types')?>">类型</a></th>              
              <th align="center"><a href="?<?=$search?>&orderby=checked&orderlx=" class="<?=orac('checked')?>"><?=$LG['status']//状态?></a></th>              
              <th align="center"><a href="?<?=$search?>&orderby=addtime&orderlx=" class="<?=orac('addtime')?>">添加时间</a></th>
              <th align="center">操作</th>
            </tr>
          </thead>
          <tbody>
<?php
while($rs=$sql->fetch_array())
{
?>
            <tr class="odd gradeX <?=!$rs['checked']?'gray2':''?>">
              <td align="center" valign="middle"><input type="checkbox" id="a" onClick="chkColor(this);" name="hsid[]" value="<?=$rs['hsid']?>" /></td>
              <td align="center" valign="middle">
			  <?=cadd($rs['number_str'])?>
			  <?=$rs['number_end']?'至 '.cadd($rs['number_end']):''?>
          	  <br>
			  <font class="gray2">
			  <?php 
			  if($rs['number_use'])
			  {
			  	echo '已用到 '.cadd($rs['number_use']);
				echo ' (剩余'.(findNum($rs['number_end'])-findNum($rs['number_use'])).'个)';
             }elseif($rs['number_end']){
				echo ' (剩余'.(findNum($rs['number_end'])-findNum($rs['number_str'])).'个)';
			 }
			?>
              </font>             
              </td>
              <td align="center" valign="middle"><?=hscode_Types(cadd($rs['types']))?></td>
              <td align="center" valign="middle"><?=$rs['checked']?'未使用':'已使用';?></td>
            
              <td align="center" valign="middle">
                <?=DateYmd($rs['addtime'],1);?>
              </td>
              <td align="right" valign="middle">
<?php if($rs['types']==2){?>
    <a href="/xingao/yundan/batch.php?hsid=<?=$rs['hsid']?>" class="btn btn-xs btn-default" target="_blank"><i class="icon-share"></i> 修改运单</a>
    
    <a href="/xingao/yundan/excel_export.php?hsid=<?=$rs['hsid']?>" class="btn btn-xs btn-default" target="_blank"><i class="icon-share"></i> 导出运单</a>
<?php }?>
              
			<?php if($rs['checked']){?>
                <a href="save.php?lx=attr&checked=0&hsid=<?=$rs['hsid']?>" class="btn btn-xs btn-success" onClick="return confirm('确定要修改状态吗?');"><i class="icon-check-empty"></i> 设已用</a>
            <?php }else{ ?>
                <a href="save.php?lx=attr&checked=1&hsid=<?=$rs['hsid']?>" class="btn btn-xs btn-default" onClick="return confirm('确定要修改状态吗?');"><i class="icon-check"></i> 设未用</a>
            <?php }?>
              
                <a href="form.php?lx=edit&hsid=<?=$rs['hsid']?>" class="btn btn-xs btn-info"><i class="icon-edit"></i> <?=$LG['edit']?></a> 
                <a href="save.php?lx=del&hsid=<?=$rs['hsid']?>" class="btn btn-xs btn-danger"  onClick="return confirm('<?=$LG['pptDelConfirm']//确认要删除所选吗?此操作不可恢复!?>');"><i class="icon-remove"></i> <?=$LG['del']?></a>
                
                
                
              </td>
            </tr>
<?php
}
?>
          </tbody>
        </table>				
			
            
<!--底部操作按钮固定--> 

<style>body{margin-bottom:50px !important;}</style><!--后台不用隐藏,增高底部高度-->
<div align="right" class="fixed_btn" id="Autohidden">


<select  class="form-control input-small select2me" data-placeholder="状态" name="checked" style=" margin-right:10px;">
      <option></option>
      <option value="0"  <?=$checked=='0'?' selected':''?>>已使用</option>
      <option value="1"  <?=$checked=='1'?' selected':''?>>未使用</option>
      </select>
                
       <!--btn-primary--><button type="submit" class="btn btn-grey"  style=" margin-right:10px;"
  onClick="
  document.XingAoForm.lx.value='attr';
  document.XingAoForm.target='';
  return confirm('确认修改所选信息的状态吗? ');
  "><i class="icon-signin"></i> 修改状态</button>
        
         
  <!--btn-danger--><button type="submit" class="btn btn-grey" onClick="
  document.XingAoForm.lx.value='del';
  document.XingAoForm.target='';
  return confirm('<?=$LG['pptDelConfirm']//确认要删除所选吗?此操作不可恢复!?>');
  " style="margin-right:30px;"><i class="icon-signin"></i> <?=$LG['delSelect']//删除所选?></button>
  
  
  <button type="submit" class="btn btn-grey" onClick="
  document.XingAoForm.lx.value='';
  document.XingAoForm.target='_blank';
  document.XingAoForm.action='/xingao/yundan/batch.php';
  "><i class="icon-share"></i> 修改运单</button>
  
  <button type="submit" class="btn btn-grey" onClick="
  document.XingAoForm.lx.value='';
  document.XingAoForm.target='_blank';
  document.XingAoForm.action='/xingao/yundan/excel_export.php';
  "><i class="icon-share"></i> 导出运单</button>
 
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
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/foot.php');
?>
