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

$headtitle="日本清关资料";
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/head.php');
if(!$ON_gd_japan){exit ("<script>alert('日本清关资料系统已关闭！');goBack();</script>");}

$classtag='so';//标记:同个页面,同个$classtype时,要有不同标记
$classtype=1;//分类类型

//搜索
$where="1=1";
$so=(int)$_GET['so'];
if($so==1)
{
	$key=par($_GET['key']);
	$checked=par($_GET['checked']);
	$time=par($_GET['time']);
	
	$classid=GetEndArr($_GET['classid'.$classtag.$classtype]);
	if(!CheckEmpty($classid)){$classid=par($_GET['classid']);}
	
	
	if($key){$where.=" and (nameCN like '%{$key}%' or nameEN like '%{$key}%' or nameJP like '%{$key}%' or composition like '%{$key}%' or barcode like '%{$key}%' or content like '%{$key}%' or username like '%{$key}%' or userid='".CheckNumber($key,-0.1)."' or gdid='".CheckNumber($key,-0.1)."' )";}
	
	if(CheckEmpty($checked)){$where.=" and checked='{$checked}'";}
	if(CheckEmpty($classid))
	{
		$classid_all=$classid.SmallClassID($classid,'classify');
		$where.=" and classid in ({$classid_all})";
	}
	
	if($time)
	{
		$nowtime=time()-$time;
		$where.=" and addtime>='$nowtime'";
	}
	$search.="&so={$so}&key={$key}&checked={$checked}&time={$time}&classid={$classid}";
}

$order=' order by myorder desc, gdid desc';//默认排序
include($_SERVER['DOCUMENT_ROOT'].'/public/orderby.php');//排序处理页


$query="select * from gd_japan where {$where} {$order}";

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
     
     <a class="btn btn-info" href="/xingao/iframe.php?typ=gd_japan_excel_import" ><i class="icon-plus"></i> 批量导入</a>
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
              <input type="text" name="key" class="form-control input-msmall popovers" data-trigger="hover" data-placement="right"  data-content="品名/成分/条码/备注/编辑员/商品ID (可留空)" value="<?=$key?>">
            </div>
            <div class="form-group">
              <div class="col-md-0">
                <select  class="form-control input-small select2me" name="time" data-placeholder="添加时间">
                  <option></option>
                  <option value="86400" <?=$time=='86400'?' selected':''?>>1天内</option>
                  <option value="172800" <?=$time=='172800'?' selected':''?>>2天内</option>
                  <option value="604800" <?=$time=='604800'?' selected':''?>>1周内</option>
                  <option value="2592000" <?=$time=='2592000'?' selected':''?>>1个月内</option>
                  <option value="7948800" <?=$time=='7948800'?' selected':''?>>3个月内</option>
                  <option value="15897600" <?=$time=='15897600'?' selected':''?>>6个月内</option>
                  <option value="31536000" <?=$time=='31536000'?' selected':''?>>1年内</option>
                </select>
              </div>
            </div>
            
            <div class="form-group">
              <div class="col-md-0">
                <select  class="form-control select2me" name="checked" data-placeholder="可用" style="width:100px;">
                  <option></option>
                    <option value="0"  <?=$checked=='0'?' selected':''?>>不可用</option>
                    <option value="1"  <?=$checked=='1'?' selected':''?>>可用</option>
                </select>
              </div>
            </div>
            <button type="submit" class="btn btn-info"><i class="icon-search"></i> <?=$LG['search']//搜索?></button>
<br>
            
            <div class="form-group" style="margin-top:10px;">
              <div class="col-md-0">
<?php 
    //$classtag='so';//标记:同个页面,同个$classtype时,要有不同标记
    //$classtype=1;//分类类型
    //$classid=8;//已保存的ID
    require($_SERVER['DOCUMENT_ROOT'].'/public/classify.php');
?>

              </div>
            </div>
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
              <th align="center"><a href="?<?=$search?>&orderby=nameJP&orderlx=" class="<?=orac('nameJP')?>">日文名称</a></th>
              <th align="center"><a href="?<?=$search?>&orderby=nameCN&orderlx=" class="<?=orac('nameCN')?>">中文名称</a></th>
              <th align="center"><a href="?<?=$search?>&orderby=nameEN&orderlx=" class="<?=orac('nameEN')?>">英文名称</a></th>
              <th align="center"><a href="?<?=$search?>&orderby=brand&orderlx=" class="<?=orac('brand')?>">品牌</a></th>
              <th align="center"><a href="?<?=$search?>&orderby=price&orderlx=" class="<?=orac('price')?>">单价</a></th>
              <th align="center"><a href="?<?=$search?>&orderby=spec&orderlx=" class="<?=orac('spec')?>">规格型号</a></th>
              <th align="center"><a href="?<?=$search?>&orderby=weight&orderlx=" class="<?=orac('weight')?>">净重</a>/<a href="?<?=$search?>&orderby=capacity&orderlx=" class="<?=orac('capacity')?>">容量</a></th>
              <th align="center"><a href="?<?=$search?>&orderby=taxCode&orderlx=" class="<?=orac('taxCode')?>">行邮税号</a></th>
              <th align="center"><a href="?<?=$search?>&orderby=imgurl&orderlx=&typ=so" class="<?=orac('imgurl')?>">外图</a></th>
              <th align="center"><a href="?<?=$search?>&orderby=username&orderlx=" class="<?=orac('username')?>">编辑员</a></th>
              <th align="center">操作</th>
            </tr>
          </thead>
          <tbody>
<?php
while($rs=$sql->fetch_array())
{
?>
            <tr class="odd gradeX <?=$rs['checked']?'':'gray2';?>">
              <td rowspan="2" align="center" valign="middle">
               
                <?php EnlargeImg(ImgAdd($rs['img']),$rs['gdid'],2)?>
                <font class="gray tooltips" data-container="body" data-placement="top" data-original-title="条码号码"><?=cadd($rs['barcode'])?></font>
                <br>
                <input type="checkbox" id="a" onClick="chkColor(this);" name="gdid[]" value="<?=$rs['gdid']?>" class="gray tooltips" data-container="body" data-placement="top" data-original-title="选中该商品" />
                <input type="hidden" name="myorderid[]" value="<?=$rs['gdid']?>">
                <input type="text"  name="myorder[]" value="<?=$rs['myorder']?>" size="3" class="gray tooltips" data-container="body" data-placement="top" data-original-title="排序 (越小排越前)" style="height:20px;">
                
                </td>
              <td align="center" valign="middle"><?=cadd($rs['nameJP'])?></td>
              <td align="center" valign="middle"><?=cadd($rs['nameCN'])?></td>
              <td align="center" valign="middle"><?=cadd($rs['nameEN'])?></td>
              <td align="center" valign="middle"><?=cadd($rs['brand'])?></td>
              <td align="center" valign="middle"><?=spr($rs['price']).$XAsc?></td>
              <td align="center" valign="middle"><?=cadd($rs['spec'])?></td>
              <td align="center" valign="middle">
			  <?=$rs['weight']>0?spr($rs['weight']).$XAwt:''?>
			  <?=$rs['capacity']>0?'/'.spr($rs['capacity']).'ML':''?>
              </td>
              <td align="center" valign="middle"><?=cadd($rs['taxCode'])?></td>
             <td align="center" valign="middle"><?php if($rs['imgurl']){?><a href="<?=cadd($rs['imgurl'])?>" target="_blank" class=" tooltips" data-container="body" data-placement="top" data-original-title="查看图片"><i class="icon-picture"></i></a><?php }?></td>
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
              
         <div class="gray modal_border"> <strong>分类：</strong>
         <?=classify($rs['classid'])?>
         </div>
                
                <!---->
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
                
                <!---->
                
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
                
                
                <!----></td>
            </tr>
		<!--分隔-开始-->
		<tr>
			<td colspan="11" style="border-left: 0px none #ffffff;	border-right: 0px none #ffffff; background-color:#ffffff; height:30px;"><!--放在CSS文件无效--></td>
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


<select  class="form-control input-medium select2me" data-placeholder="转移" name="classid" >
    <option value=""></option>
    <?php LevelClassify(0,0,0,1,0);?>
    </select>

   <select  class="form-control input-small select2me" data-placeholder="可用" name="checked">
    <option value=""></option>
    <option value="0" >不可用</option>                    
    <option value="1" >可用</option>                    
    </select>
          
    <button type="submit" class="btn btn-info" 
    onClick="
    document.XingAoForm.lx.value='attr';
    return confirm('确认修改所选信息的属性吗? ');
    "><i class="icon-signin"></i> 修改</button>
    
    <button type="submit" class="btn btn-info"  style="margin-left:10px;"
    onClick="
    document.XingAoForm.lx.value='editorder'; 
    "><i class="icon-signin"></i> 修改排序</button>
 
    
    <!--btn-danger--><button type="submit" class="btn btn-grey" style="margin-left:10px;"
    onClick="
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
$enlarge=1;//是否用到 图片扩大插件 (/public/enlarge/call.html)
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/foot.php');
?>
