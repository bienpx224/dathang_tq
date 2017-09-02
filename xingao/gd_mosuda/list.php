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
$pervar='goodsdata';require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');

$headtitle="跨境翼清关资料";
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/head.php');
if(!$ON_gd_mosuda){exit ("<script>alert('跨境翼清关资料系统已关闭！');goBack();</script>");}


//搜索
$where="1=1";
$so=(int)$_GET['so'];

//if(!$so){$so=1;$_GET['record']=2;}//默认显示已备案
if($so==1)
{
	$key=par($_GET['key']);
	$checked=par($_GET['checked']);
	$record=par($_GET['record']);
	$stime_add=par($_GET['stime_add']);
	$etime_add=par($_GET['etime_add']);
	
	
	if($key){$where.=" and (itemNo like '%{$key}%' or HYG like '%{$key}%' or taxCode like '%{$key}%' or recordCode like '%{$key}%' or HSCode like '%{$key}%' or itemsTaxCode like '%{$key}%' or name like '%{$key}%' or spec like '%{$key}%' or unit like '%{$key}%' or barcode like '%{$key}%' or types like '%{$key}%' or content like '%{$key}%' or merchants like '%{$key}%' or brand like '%{$key}%' or producer like '%{$key}%' or composition like '%{$key}%' or foodAdditives like '%{$key}%' or harmful like '%{$key}%'  or username like '%{$key}%' or userid='".CheckNumber($key,-0.1)."' or gdid='".CheckNumber($key,-0.1)."' )";}
	
	if(CheckEmpty($checked)){$where.=" and checked='{$checked}'";}
	if(CheckEmpty($record)){$where.=" and record='{$record}'";}
	if($stime_add){$where.=" and addtime>='".strtotime($stime_add." 00:00:00")."'";}
	if($etime_add){$where.=" and addtime<='".strtotime($etime_add." 23:59:59")."'";}

	$search.="&so={$so}&key={$key}&checked={$checked}&record={$record}&stime_add={$stime_add}&etime_add={$etime_add}";
}

$_SESSION['Xexport_gd_mosuda']=$where;
$order=' order by myorder desc, onclick desc';//默认排序
include($_SERVER['DOCUMENT_ROOT'].'/public/orderby.php');//排序处理页


$query="select * from gd_mosuda where {$where} {$order}";

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
	 <button type="button" class="btn btn-info" onClick="window.open('form.php');"><i class="icon-plus"></i> 添加商品 </button>	
     
     <a class="btn btn-info" href="/xingao/iframe.php?typ=gd_mosuda_excel_import" ><i class="icon-plus"></i> 批量导入</a>
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
              <input type="text" name="key" class="form-control input-msmall popovers" data-trigger="hover" data-placement="right"  data-content="关键词 (可留空)" value="<?=$key?>">
            </div>
            
            
            
            <div class="form-group">
              <div class="col-md-0">
                <select  class="form-control select2me" name="checked" data-placeholder="可用" style="width:120px;">
                  <option></option>
                    <option value="0"  <?=$checked=='0'?' selected':''?>>不可用</option>
                    <option value="1"  <?=$checked=='1'?' selected':''?>>可用</option>
                </select>
              </div>
            </div>
            
  <div class="form-group">
  <div class="col-md-0">
      <select  class="form-control select2me" name="record" data-placeholder="备案情况"  style="width:120px;">
		   <option></option>
		   <?=Record($record,1)?>
      </select>
  </div>
</div>
                  
        <div class="form-group">
          <div class="col-md-0">
            <div class="input-group input-xmedium date-picker input-daterange" data-date-format="yyyy-mm-dd">
              <input type="text" class="form-control input-small" name="stime_add" value="<?=$stime_add?>" placeholder="添加时间">
              <span class="input-group-addon">-</span>
              <input type="text" class="form-control input-small" name="etime_add" value="<?=$etime_add?>"  placeholder="添加时间">
            </div>

          </div>

        </div>

          <button type="submit" class="btn btn-info"><i class="icon-search"></i> <?=$LG['search']//搜索?></button>
            
            
            
            
          </form>
        </div>
      </div>
      <form action="save.php" method="post" name="XingAoForm">
        <input name="lx" type="hidden">
        <table class="table table-striped table-bordered table-hover" style="border:0px solid #ddd;">
          <thead>
            <tr>
              <th align="center" class="table-checkbox"> <input type="checkbox"  id="aAll" onClick="chkAll(this)"  title="全选/取消"/>
              </th>
              <th align="center"><a href="?<?=$search?>&orderby=name&orderlx=" class="<?=orac('name')?>">品名</a></th>
              <th align="center"><a href="?<?=$search?>&orderby=types&orderlx=" class="<?=orac('types')?>">分类</a></th>
              <th align="center"><a href="?<?=$search?>&orderby=brand&orderlx=" class="<?=orac('brand')?>">品牌</a></th>
              <th align="center"><a href="?<?=$search?>&orderby=spec&orderlx=" class="<?=orac('spec')?>">规格型号</a></th>
              <th align="center"><a href="?<?=$search?>&orderby=unit&orderlx=" class="<?=orac('unit')?>">单位</a></th>
              <th align="center"><a href="?<?=$search?>&orderby=merchants&orderlx=" class="<?=orac('merchants')?>">生产企业</a></th>
              <th align="center"><a href="?<?=$search?>&orderby=weightGross&orderlx=" class="<?=orac('weightGross')?>">毛重</a>/<a href="?<?=$search?>&orderby=weightSuttle&orderlx=" class="<?=orac('weightSuttle')?>">净重</a></th>
              
              <th align="center"><a href="?<?=$search?>&orderby=recordPrice&orderlx=" class="<?=orac('recordPrice')?>">备案价格</a></th>
              <th align="center"><a href="?<?=$search?>&orderby=username&orderlx=" class="<?=orac('username')?>">编辑员</a></th>
              <th align="center">操作</th>
            </tr>
          </thead>
          <tbody>
<?php
while($rs=$sql->fetch_array())
{
?>
<tr class="odd gradeX  <?=$rs['checked']?'':'gray2';?>">
              <td rowspan="2" align="center" valign="middle">
               
                <?php EnlargeImg(ImgAdd($rs['img']),$rs['gdid'],2)?>
                <font class="gray tooltips" data-container="body" data-placement="top" data-original-title="商品条码"><?=cadd($rs['barcode'])?></font><span class="xa_sep"> | </span><font class="gray tooltips" data-container="body" data-placement="top" data-original-title="资料ID"><?=cadd($rs['gdid'])?></font>
                <br>
                <input type="checkbox" id="a" onClick="chkColor(this);" name="gdid[]" value="<?=$rs['gdid']?>" class="gray tooltips" data-container="body" data-placement="top" data-original-title="选中该商品" />
                <input type="hidden" name="myorderid[]" value="<?=$rs['gdid']?>">
                <input type="text"  name="myorder[]" value="<?=$rs['myorder']?>" size="3" class="gray tooltips" data-container="body" data-placement="top" data-original-title="排序 (越小排越前)" style="height:20px;">
                
                </td>
              <td align="center" valign="middle">
              <font href="javascript:void(0)" class="<?=$rs['record']==1?'red2':'';?> tooltips" data-container="body" data-placement="top" data-original-title="<?=Record($rs['record'])?>"> <?=cadd($rs['name'])?> </font>
              </td>
              <td align="center" valign="middle"><?=cadd($rs['types'])?></td>
              <td align="center" valign="middle"><?=cadd($rs['brand'])?></td>
              <td align="center" valign="middle"><?=cadd($rs['spec'])?></td>
              <td align="center" valign="middle"><?=cadd($rs['unit'])?></td>
              <td align="center" valign="middle"><?=cadd($rs['merchants'])?></td>
              <td align="center" valign="middle">
			  <?=$rs['weightGross']>0?spr($rs['weightGross']).'KG':''?>
			  <?=$rs['weightSuttle']>0?'/'.spr($rs['weightSuttle']).'KG':''?>
              </td>
             <td align="center" valign="middle"><?=$rs['recordPrice']>0?spr($rs['recordPrice']).'CNY':''?></td>
             
              <td align="center" valign="middle"><?=cadd($rs['username'])?></td>
              
              <td align="center" valign="middle">
              
              <a href="form.php?lx=edit&gdid=<?=$rs['gdid']?>" class="btn btn-xs btn-info" target="_blank"><i class="icon-edit"></i> 查看/编辑
              </a>
                
                <a href="save.php?lx=del&gdid=<?=$rs['gdid']?>" class="btn btn-xs btn-danger"  onClick="return confirm('<?=$LG['pptDelConfirm']//确认要删除所选吗?此操作不可恢复!?>');"><i class="icon-remove"></i> <?=$LG['del']?></a>
                </td>
            </tr>
            
            
                        <!---->
            <tr>
              <td colspan="15" align="left">
         <!----------------------------------------------------------------->
          <div class="gray modal_border">
      	  <?=$rs['itemNo']?'商品货号:'.cadd($rs['itemNo']).'<span class="xa_sep"> | </span>':''?>
      	  <?=$rs['recordCode']?'商品备案号:'.cadd($rs['recordCode']).'<span class="xa_sep"> | </span>':''?>
      	  <?=$rs['HYG']?'HYG备案号:'.cadd($rs['HYG']).'<span class="xa_sep"> | </span>':''?>
       	  <?=$rs['itemsTaxCode']?'海关物品税号:'.cadd($rs['itemsTaxCode']).'<span class="xa_sep"> | </span>':''?>
     	  <?=$rs['taxCode']?'行邮税号:'.cadd($rs['taxCode']).'<span class="xa_sep"> | </span>':''?>
      	  <?=$rs['taxes']?'行邮税率:'.cadd($rs['taxes']).'%<span class="xa_sep"> | </span>':''?>
      	  <?=$rs['producer']?'原产国:'.cadd($rs['producer']).'<span class="xa_sep"> | </span>':''?>
        
         </div>
       <!----------------------------------------------------------------->
         <?php 
         $zhi=cadd($rs['composition']);
         if($zhi){
		 ?>
          <div class="gray modal_border"> <strong>成份：</strong>
         <?php 
			echo leng($zhi,200,"...");
			Modals($zhi,$title='成份',$time=$rs['addtime'],$nameid='composition'.$rs['gdid'],$count=200);
		 ?>
         </div>
        <?php }?>
        <!----------------------------------------------------------------->
         <?php
         $zhi=cadd($rs['content']);
         if($zhi){
		 ?>
          <div class="gray modal_border"> <strong>备注：</strong>
         <?php 
			echo leng($zhi,200,"...");
			Modals($zhi,$title='备注',$time=$rs['addtime'],$nameid='content'.$rs['gdid'],$count=200);
		 ?>
         </div>
        <?php }?>
                
                
        </td>
            </tr>
		<!--分隔-开始-->
		<tr>
			<td colspan="11" style="border-left: 0px none #ffffff;	border-right: 0px none #ffffff; background-color:#ffffff; height:30px;"></td>
		</tr>
		<tr></tr>
		<!--分隔-结束-->

<?php
}
?>
          </tbody>
        </table>				
			
            
<!--底部操作按钮固定--> 

<style>body{margin-bottom:50px !important;}</style><!--后台不用隐藏,增高底部高度-->
<div align="right" class="fixed_btn" id="Autohidden">


<select  class="form-control input-small select2me" data-placeholder="可用" name="checked">
    <option value=""></option>
    <option value="0" >不可用</option>                    
    <option value="1" >可用</option>                    
    </select>
          
    <button type="submit" class="btn btn-grey" 
    onClick="
    document.XingAoForm.lx.value='attr';
	document.XingAoForm.action='save.php';
    return confirm('确认修改所选信息的属性吗? ');
    "><i class="icon-signin"></i> 修改</button>
    
    <button type="submit" class="btn btn-grey"  style="margin-left:10px;"
    onClick="
    document.XingAoForm.lx.value='editorder'; 
	document.XingAoForm.action='save.php';
    "><i class="icon-signin"></i> 修改排序</button>
 
 
 
    <!--************导出************-->
    <font  class="tooltips" data-container="body" data-placement="top" data-original-title="<?=$LG['yundan.2'];//不勾选时则导出所选?>"  style="margin-left:20px;">
	<input name="use_where" type="checkbox" value="1"><?=$LG['yundan.1'];//导出搜索结果?>
    </font>
	 
	<button type="submit" class="btn btn-grey" style="margin-right:20px;"
	onClick="
	document.XingAoForm.target='_blank';
	document.XingAoForm.action='excelExport/';
	"><i class="icon-signin"></i> <?=$LG['excelExport'];//导出?></button>


   
    <!--************删除************-->
    <!--btn-danger--><button type="submit" class="btn btn-grey" style="margin-left:10px;"
    onClick="
    document.XingAoForm.lx.value='del';
	document.XingAoForm.action='save.php';
    return confirm('<?=$LG['pptDelConfirm']//确认要删除所选吗?此操作不可恢复!?>');
    "><i class="icon-signin"></i> <?=$LG['delSelect']//删除所选?></button>

</div>

        <div class="row"><?=$listpage?></div>
      </form>
    </div>
    <!--表格内容结束-->

	<div class="xats">
        <br>
        <strong> 提示：</strong><br />
		 &raquo; 只能删除未有运单使用过的资料<br>		  
	</div>


 
  </div>
</div>
<?php
$sql->free(); //释放资源
$enlarge=1;//是否用到 图片扩大插件 (/public/enlarge/call.html)
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/foot.php');
?>
