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
$headtitle=$LG['address.headtitleList'];
require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/head.php');


//搜索
$where="1=1";
$so=(int)$_GET['so'];
if($so==1)
{
	$key=par($_GET['key']);
	$addclass=par($_GET['addclass']);
	$checked=par($_GET['checked']);
	if($key){$where.=" and (truename like '%{$key}%' or mobile like '%{$key}%' or add_dizhi like '%{$key}%')";}
	if(CheckEmpty($addclass)){$where.=" and addclass='{$addclass}'";}
	if(CheckEmpty($checked)){$where.=" and checked='{$checked}'";}

	$search.="&so={$so}&key={$key}&addclass={$addclass}&checked={$checked}";
}

$order=' order by addid desc';//默认排序
include($_SERVER['DOCUMENT_ROOT'].'/public/orderby.php');//排序处理页


//echo $search;exit();
$query="select * from member_address where {$where}  {$Mmy} {$order}";

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
        <button type="button" class="btn btn-info" onClick="location.href='form.php';"><i class="icon-plus"></i> <?=$LG['add']?> </button>
        <button type="button" class="btn btn-info" onClick="location.href='excel_import.php';"><i class="icon-plus"></i> <?=$LG['import']?> </button>
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
              <input type="text" name="key" class="form-control input-msmall popovers" data-trigger="hover" data-placement="right"  data-content="<?=$LG['address.pptList1']//姓名/电话/详细地址 (可留空)?>" value="<?=$key?>">
            </div>
            <div class="form-group">
              <div class="col-md-0">
                <select  class="form-control input-small select2me" name="addclass" data-placeholder="<?=$LG['address.addclass']//分类?>">
                  <option></option>
                  <?=AddClass($addclass,1)?>
                </select>
              </div>
            </div>
            <div class="form-group">
              <div class="col-md-0">
                <select  class="form-control input-small select2me" name="checked" data-placeholder="<?=$LG['status']//状态?>">
                  <option></option>
                  <option value="1"  <?=$checked=='1'?' selected':''?>><?=$LG['checkedOn']//开通?></option>
                  <option value="0"  <?=$checked=='0'?' selected':''?>><?=$LG['checkedOff']//关闭?></option>
                </select>
              </div>
            </div>
            <button type="submit" class="btn btn-info"><i class="icon-search"></i> <?=$LG['search']?></button>
          </form>
        </div>
      </div>
      <form action="save.php" method="post" name="XingAoForm">
        <input name="lx" type="hidden">
        <input name="addclass" type="hidden">
        <table class="table table-striped table-bordered table-hover" >
          <thead>
            <tr>
              <th align="center" class="table-checkbox"> <input type="checkbox"  id="aAll" onClick="chkAll(this)"  title="全选/取消"/>
              </th>
              <th align="left"><a href="?<?=$search?>&orderby=truename&orderlx=" class="<?=orac('truename')?>"><?=$LG['address.truename']//姓名?></a>/<a href="?<?=$search?>&orderby=addclass&orderlx=" class="<?=orac('addclass')?>"><?=$LG['address.addclass']//分类?></a></th>
              <th align="center"><a href="?<?=$search?>&orderby=mobile&orderlx=" class="<?=orac('mobile')?>"><?=$LG['address.mobile']//手机?></a>/<a href="?<?=$search?>&orderby=tel&orderlx=" class="<?=orac('tel')?>"><?=$LG['address.tel']//固话?></a></th>
              <th align="center"><a href="?<?=$search?>&orderby=zip&orderlx=" class="<?=orac('zip')?>"><?=$LG['address.tel']//邮编?></a>/<a href="?<?=$search?>&orderby=add_dizhi&orderlx=" class="<?=orac('add_dizhi')?>"><?=$LG['address.add_dizhi']//地址?></a></th>
              <th align="center"><a href="?<?=$search?>&orderby=shenfenhaoma&orderlx=" class="<?=orac('shenfenhaoma')?>"><?=$LG['address.shenfenhaoma']//证件?></a></th>
              <th align="center"><?=$LG['op']//操作?></th>
            </tr>
          </thead>
          <tbody>
<?php
while($rs=$sql->fetch_array())
{
	
		$shenfenimg_z="";
		if($rs['shenfenimg_z'])
		{
			$shenfenimg_z='<a href="'.$rs['shenfenimg_z'].'" class=" tooltips" data-container="body" data-placement="top" data-original-title="'.$LG['address.pptList2'].'" target="_blank"><i class="icon-picture"></i></a>';//预览证件-正面
		}
		$shenfenimg_b="";
		if($rs['shenfenimg_b'])
		{
			$shenfenimg_b='<a href="'.$rs['shenfenimg_b'].'" class=" tooltips" data-container="body" data-placement="top" data-original-title="'.$LG['address.pptList3'].'" target="_blank"><i class="icon-picture"></i></a>';//预览证件-背面
		}
?>
            <tr class="odd gradeX <?=$rs['checked']?'gray2':''?>">
              <td align="center" valign="middle"><input type="checkbox" id="a" onClick="chkColor(this);"  name="addid[]" value="<?=$rs['addid']?>" /></td>
              <td align="center" valign="middle"><?=cadd($rs['truename'])?><br>
                <font class="gray2">
                <?=AddClass($rs['addclass'])?>
                </font></td>
              <td align="center" valign="middle"><?=cadd($rs['mobile'])?><br>
                <font class="gray2">
                <?=$rs['tel']?>
                </font></td>
              <td align="center" valign="middle"><?=cadd($rs['zip'])?><br>
                <font class="gray2">
                <?=$rs['add_shengfen'].' '.$rs['add_chengshi'].' '.$rs['add_quzhen'].' '.$rs['add_dizhi']?>
                </font></td>
              <td align="center" valign="middle"><?=cadd($rs['shenfenhaoma'])?>
                <br>
                <?=$shenfenimg_z?>
                <?=$shenfenimg_b?></td>
              <td align="center" valign="middle"><a href="form.php?lx=edit&addid=<?=$rs['addid']?>" class="btn btn-xs btn-info"><i class="icon-edit"></i> <?=$LG['showedit']?></a> <a href="save.php?lx=del&addid=<?=$rs['addid']?>" class="btn btn-xs btn-danger"  onClick="return confirm('<?=$LG['pptDelConfirm']?>');"><i class="icon-remove"></i> <?=$LG['del']?></a> <br>
                
                <?php if($rs['mrs']!=1){?>
                <a href="save.php?lx=mr&mrs=1&addid=<?=$rs['addid']?>" class="btn btn-xs btn-default"><?=$LG['address.btn_mrs_1']//设为默认收货?></a>
                <?php }else{ ?>
                <a href="save.php?lx=mr&mrs=2&addid=<?=$rs['addid']?>"  class="btn btn-xs btn-success"><?=$LG['address.btn_mrs_2']//取消默认收货?></a>
                <?php }?>

                
				<?php if($rs['mrf']!=1){?>
                <a href="save.php?lx=mr&mrf=1&addid=<?=$rs['addid']?>" class="btn btn-xs btn-default"><?=$LG['address.btn_mrf_1']//设为默认发货?></a>
                <?php }else{ ?>
                <a href="save.php?lx=mr&mrf=2&addid=<?=$rs['addid']?>"  class="btn btn-xs btn-success"><?=$LG['address.btn_mrf_2']//取消默认发货?></a>
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



<button type="submit"  class="btn btn-grey"
    onClick="
  document.XingAoForm.lx.value='addclass';
  document.XingAoForm.addclass.value='0'; 
  "><i class="icon-signin"></i> <?=$LG['address.btnAddclass0']//设为通用?></button>
  
          <button type="submit" id="openSmt2" disabled class="btn btn-grey"
    onClick="
  document.XingAoForm.lx.value='addclass';
  document.XingAoForm.addclass.value='1'; 
     return confirm('<?=$LG['address.btnAddclassPpt1']//确定要设为设为发货人??> ');

  "><i class="icon-signin"></i> <?=$LG['address.btnAddclass1']//设为发货人?></button>
  
          <button type="submit" id="openSmt3" disabled class="btn btn-grey"
    onClick="
  document.XingAoForm.lx.value='addclass';
  document.XingAoForm.addclass.value='2'; 
   return confirm('<?=$LG['address.btnAddclassPpt2']//确定要设为设为收货人? ?>');
  "><i class="icon-signin"></i> <?=$LG['address.btnAddclass2']//设为收货人?></button>
  
          <!--btn-danger--><button type="submit" id="openSmt4" disabled class="btn btn-grey" onClick="
  document.XingAoForm.lx.value='del';
  return confirm('<?=$LG['pptDelConfirm']//确认要删除所选吗?此操作不可恢复!?>');
  "><i class="icon-signin"></i> <?=$LG['delSelect']//删除所选?></button>
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
require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/foot.php');
?>
