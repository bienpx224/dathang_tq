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
$headtitle=$LG['coupons.headtitle'];//优惠券/折扣券
require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/head.php');

//过期更新
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/coupons/call/update.php');

//搜索
$where="1=1";
$so=(int)$_GET['so'];

$status=par($_GET['status']);
if(CheckEmpty($status)){$where.=" and status='{$status}'";}
$search.="&status={$status}";

if($so==1)
{
	$key=par($_GET['key']);
	$types=par($_GET['types']);
	$usetypes=par($_GET['usetypes']);
	$allot=par($_GET['allot']);
	$getSource=par($_GET['getSource']);
	$fromtable=par($_GET['fromtable']);
	
	if($key){$where.=" and (use_title like '%{$key}%' or use_content like '%{$key}%' or fromid='".CheckNumber($key,-0.1)."' or codes like '%{$key}%' or content like '%{$key}%' or value='{$key}' or userid='".CheckNumber($key,-0.1)."' or username like '%{$key}%')";}
	if(CheckEmpty($types)){$where.=" and types='{$types}'";}
	if(CheckEmpty($usetypes)){$where.=" and usetypes='{$usetypes}'";}
	if(CheckEmpty($getSource)){$where.=" and getSource='{$getSource}'";}
	if(CheckEmpty($allot)){
		if($allot){$where.=" and userid>0";}else{$where.=" and userid=0";}
	}
	if(CheckEmpty($fromtable)){$where.=" and fromtable='{$fromtable}'";}
	
	$search.="&so={$so}&key={$key}&types={$types}&usetypes={$usetypes}&allot={$allot}&getSource={$getSource}&fromtable={$fromtable}";
}

$order=' order by addtime desc,status asc';//默认排序
include($_SERVER['DOCUMENT_ROOT'].'/public/orderby.php');//排序处理页
$query="select * from coupons where {$where} {$Mmy} {$order}";

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
        <button type="button" class="btn btn-info" onClick="location.href='form.php';"><i class="icon-plus"></i> <?=$LG['coupons.list_5'];//兑 换?> </button>
      </ul>
      <!-- END PAGE TITLE & BREADCRUMB--> 
    </div>
  </div>
  <!-- END PAGE HEADER--> 
  
  <!-- BEGIN PAGE CONTENT-->
  <div class="tabbable tabbable-custom boxless">
    
    <ul class="nav nav-tabs">
			
			<li class="<?=!CheckEmpty($status)?'active':''?>" style="margin-right:30px;"><a href="?status="><?=$LG['all'];//全部?><span class="badge badge-default"></span></a></li>
			
			<li class="<?=CheckEmpty($status)&&$status==0?'active':''?>"><a href="?status=0"><?=Coupons_Status(0)?></a></li>
			<li class="<?=$status==1?'active':''?>"><a href="?status=1"><?=Coupons_Status(1)?></a></li>
			<li class="<?=$status==2?'active':''?>"><a href="?status=2"><?=Coupons_Status(2)?></a></li>
			<li class="<?=$status==10?'active':''?>"><a href="?status=10"><?=Coupons_Status(10)?></a></li>
			
		</ul>
        
    <div class="tab-content" style="padding:10px;"> 
      <!--搜索-->
      <div class="navbar navbar-default" role="navigation">
        
        <div class="collapse navbar-collapse navbar-ex1-collapse">
          <form class="navbar-form navbar-left"  method="get" action="?">
            <input name="so" type="hidden" value="1">
            <div class="form-group">
              <input type="text" name="key" class="form-control input-msmall popovers" data-trigger="hover" data-placement="right"  data-content="<?=$LG['coupons.list_1'];//兑换码/价值/使用说明 (可留空)?>" value="<?=$key?>">
            </div>
                
			<div class="form-group">
              <div class="col-md-0">
                     <select  class="form-control input-small select2me" name="types" data-placeholder="<?=$LG['coupons.list_11'];//类型?>">
                     <option></option>
                	 <?=Coupons_Types($types,1)?>
                  	 </select>
              </div>
            </div>
                 
			<div class="form-group">
                <div class="col-md-0">
                     <select  class="form-control input-small select2me" name="usetypes" data-placeholder="<?=$LG['coupons.list_16'];//可使用类型?>" >
                     <option></option>
                	 <?=Coupons_usetypes($usetypes,1)?>
                  	 </select>
               </div>
              </div>
          
          	 <div class="form-group">
                <div class="col-md-0">
                  <select  class="form-control input-small select2me" name="allot" data-placeholder="<?=$LG['coupons.list_7'];//获取?>">
                    <option></option>
                    <option value="0"  <?=$allot=='0'?' selected':''?>><?=$LG['coupons.list_2'];//未获取?></option>
                    <option value="1"  <?=$allot=='1'?' selected':''?>><?=$LG['coupons.list_3'];//已获取?></option>
                  </select>
               </div>
              </div>
                
             <div class="form-group">
                <div class="col-md-0">
                     <select  class="form-control input-small select2me" name="getSource" data-placeholder="<?=$LG['coupons.list_8'];//获取途径?>" >
                     <option></option>
                	 <?=Coupons_getSource($getSource,1)?>
                  	 </select>
               </div>
              </div>
                
             <div class="form-group">
                <div class="col-md-0">
                     <select  class="form-control input-small select2me" name="fromtable" data-placeholder="<?=$LG['coupons.list_19'];//用途?>" >
                     <option></option>
                	 <?=fromtableName($fromtable,1)?>
                  	 </select>
               </div>
              </div>
         	<button type="submit"  class="btn btn-info"><i class="icon-search"></i> <?=$LG['search'];//搜索?></button>
          
          </form>
        </div>
      </div>
      <form action="save.php" method="post" name="XingAoForm">
        <table class="table table-striped table-bordered table-hover" >
          <thead>
            <tr>
              <th align="center" class="table-checkbox"> <input type="checkbox"  id="aAll" onClick="chkAll(this)"  title="<?=$LG['checkAll'];//全选/取消?>"/>
              </th>
              
              <th align="center"><a href="?<?=$search?>&orderby=types&orderlx=" class="<?=orac('types')?>"><?=$LG['coupons.list_11'];//类型?></a></th>
              <th align="center"><a href="?<?=$search?>&orderby=codes&orderlx=" class="<?=orac('codes')?>"><?=$LG['coupons.codes'];//兑换码?></a></th>
              <th align="center"><a href="?<?=$search?>&orderby=value&orderlx=" class="<?=orac('value')?>"><?=$LG['coupons.list_13'];//价值?></a></th>
              
<?php if($status!=1){?>             
              <th align="center"><a href="?<?=$search?>&orderby=number&orderlx=" class="<?=orac('number')?>"><?=$LG['coupons.list_14'];//数量?></a></th>
          
              <th align="center"><a href="?<?=$search?>&orderby=limitmoney&orderlx=" class="<?=orac('limitmoney')?>"><?=$LG['coupons.list_15'];//最低消费?></a></th>
               <th align="center"><a href="?<?=$search?>&orderby=usetypes&orderlx=" class="<?=orac('usetypes')?>"><?=$LG['coupons.list_16'];//可使用类型?></a></th>
               <th align="center"><a href="?<?=$search?>&orderby=duetime&orderlx=" class="<?=orac('duetime')?>"><?=$LG['coupons.list_17'];//到期时间?></a></th>
<?php }?> 
             
               <th align="center"><a href="?<?=$search?>&orderby=getSource&orderlx=" class="<?=orac('getSource')?>"><?=$LG['coupons.list_8'];//获取途径?></a></th>
               <th align="center"><a href="?<?=$search?>&orderby=status&orderlx=" class="<?=orac('status')?>"><?=$LG['status'];//状态?></a> </th>
               
<?php if($status==1){?>                
               <th align="center"><a href="?<?=$search?>&orderby=fromtable&orderlx=" class="<?=orac('fromtable')?>"><?=$LG['coupons.list_19'];//用途?></a> </th>
               <th align="center"><a href="?<?=$search?>&orderby=use_title&orderlx=" class="<?=orac('use_title')?>"><?=$LG['coupons.list_20'];//使用说明?></a> </th>
               <th align="center"><a href="?<?=$search?>&orderby=money&orderlx=" class="<?=orac('money')?>"><?=$LG['coupons.list_21'];//使用优惠?></a> </th>
               <th align="center"><a href="?<?=$search?>&orderby=use_time&orderlx=" class="<?=orac('use_time')?>"><?=$LG['coupons.list_22'];//使用时间?></a> </th>
<?php }?>

              <th align="center"><?=$LG['op'];//操作?></th>
            </tr>
          </thead>
          <tbody>
<?php
while($rs=$sql->fetch_array())
{
?>
            <tr class="odd gradeX <?=spr($rs['status'])?'gray2':''?>">
              <td align="center" valign="middle">
               <input type="checkbox" id="a" onClick="chkColor(this);"  name="cpid[]" value="<?=$rs['cpid']?>" />
               </td>
              
              <td align="center" valign="middle">
               <?=Coupons_Types($rs['types'])?>
              </td>
                
              <td align="center" valign="middle">
              <?php if($rs['content']){?>
              <a class="popovers" data-trigger="hover" data-placement="top"  data-content="<?=cadd($rs['content'])?>"><?=cadd($rs['codes'])?></a>
              <?php }else{echo cadd($rs['codes']);}?>
              </td>
                
              <td align="center" valign="middle">
			  <?=spr($rs['value'])?><?=$rs['types']==1?$XAmc:'折'?>
              </td>
              
<?php if($status!=1){?>             
              <td align="center" valign="middle">
			   <?=spr($rs['number'])?><?=$LG['coupons.zhang'];//张?>
              </td>
           
              <td align="center" valign="middle">
              <a class="tooltips" data-container="body" data-placement="top" data-original-title="<?=$LG['coupons.list_23'];//达到这个消费金额才能使用?>"><?=spr($rs['limitmoney']).$XAmc?></a>
              </td>
              
              <td align="center" valign="middle">
			  <?=Coupons_usetypes($rs['usetypes'])?>
              </td>
              
              <td align="center" valign="middle">
			   <?=$rs['duetime']?DateYmd($rs['duetime']):'不限'?>
              </td>
<?php }?>              
              <td align="center" valign="middle">
              <a class="tooltips" data-container="body" data-placement="top" data-original-title="<?=DateYmd($rs['getTime'])?>">
			  <?=Coupons_getSource($rs['getSource'])?>
              </a>
              </td>
              
              <td align="center" valign="middle">
              <?=Coupons_Status(spr($rs['status']))?>
              </td>
               
      <?php if($status==1){?> 
              <td align="center" valign="middle">
              <?=fromtableName($rs['fromtable'])?> (ID:<?=$rs['fromid']?>)
              </td>
               
              <td align="center" valign="middle">
              	<a <?=fromtableUrl($rs['fromtable'],$rs['fromid'])?>  class="popovers" data-trigger="hover" data-placement="top"  data-content="<?=cadd($rs['use_title'])?>：<?=cadd($rs['use_content'])?>"><?=$rs['use_title']?leng($rs['use_title'],20,'...'):$LG['explain']?></a>
              </td>
               
              <td align="center" valign="middle">
              <?=spr($rs['money']).$XAmc?>
              </td>
               
              <td align="center" valign="middle">
              <?=DateYmd($rs['use_time'])?>
              </td>
      <?php }?>         
              <td align="center" valign="middle">
              <?php if($status!=1){?>
              <a href="save.php?lx=del&cpid=<?=$rs['cpid']?>" class="btn btn-xs btn-danger"  onClick="return confirm('<?=LGtag($LG['coupons.list_9'],'<tag1>=='.cadd($rs['codes']))?>');"><i class="icon-remove"></i> <?=$LG['del']?></a> 
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



<input name="lx" type="hidden">
        <input name="status" type="hidden">
<?php if(!CheckEmpty($status)||($status&&$status!=1)){?>
          <input type="text" class="form-control input-xsmall tooltips" data-container="body" data-placement="top" data-original-title="<?=$LG['coupons.list_24'];//删除X月之前所取得的 (填0则删除全部)?>" name="date" value=""><?=$LG['coupons.list_25'];//月?>
<?php }?> 
 
<?php if($status!=1){?>
<!--不能删除已使用记录-->
          <!--btn-danger--><button type="submit"  class="btn btn-grey tooltips" data-container="body" data-placement="top" data-original-title="<?=$LG['coupons.list_26'];//如有勾选则删除所选,否则按左边所填删除?>"
         onClick="
  document.XingAoForm.lx.value='del';
  document.XingAoForm.status.value='<?=$status?>';
  return confirm('<?=$LG['coupons.list_27']?>');
  "><i class="icon-remove"></i> <?=$LG['del']?></button>
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
