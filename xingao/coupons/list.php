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
$pervar='coupons';//权限验证
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');
$headtitle="优惠券/折扣券";
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/head.php');

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
$query="select * from coupons where {$where} {$order}";

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
      </ul>
      <!-- END PAGE TITLE & BREADCRUMB--> 
    </div>
  </div>
  <!-- END PAGE HEADER--> 
  
  <!-- BEGIN PAGE CONTENT-->
  <div class="tabbable tabbable-custom boxless">
    
    <ul class="nav nav-tabs">
			
			<li class="<?=!CheckEmpty($status)?'active':''?>" style="margin-right:30px;"><a href="?status=">全部<span class="badge badge-default"></span></a></li>
			
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
            <input name="status" type="hidden" value="<?=$status?>">
            <div class="form-group">
              <input type="text" name="key" class="form-control input-msmall popovers" data-trigger="hover" data-placement="right"  data-content="兑换码/价值/会员ID/会员名/备注内容/使用说明 (可留空)" value="<?=$key?>">
            </div>
                
			<div class="form-group">
              <div class="col-md-0">
                     <select  class="form-control input-small select2me" name="types" data-placeholder="类型">
                     <option></option>
                	 <?=Coupons_Types($types,1)?>
                  	 </select>
              </div>
            </div>
                 
			<div class="form-group">
                <div class="col-md-0">
                     <select  class="form-control input-small select2me" name="usetypes" data-placeholder="可使用类型" >
                     <option></option>
                	 <?=Coupons_usetypes($usetypes,1)?>
                  	 </select>
               </div>
              </div>
          
          	 <div class="form-group">
                <div class="col-md-0">
                  <select  class="form-control input-small select2me" name="allot" data-placeholder="获取">
                    <option></option>
                    <option value="0"  <?=$allot=='0'?' selected':''?>>未获取</option>
                    <option value="1"  <?=$allot=='1'?' selected':''?>>已获取</option>
                  </select>
               </div>
              </div>
                
             <div class="form-group">
                <div class="col-md-0">
                     <select  class="form-control input-small select2me" name="getSource" data-placeholder="获取途径" >
                     <option></option>
                	 <?=Coupons_getSource($getSource,1)?>
                  	 </select>
               </div>
              </div>
                
             <div class="form-group">
                <div class="col-md-0">
                     <select  class="form-control input-small select2me" name="fromtable" data-placeholder="用途" >
                     <option></option>
                	 <?=fromtableName($fromtable,1)?>
                  	 </select>
               </div>
              </div>
            
         	<button type="submit" class="btn btn-info"><i class="icon-search"></i> <?=$LG['search']//搜索?></button>
          
          </form>
        </div>
      </div>
      <form action="save.php" method="post" name="XingAoForm">
        <table class="table table-striped table-bordered table-hover" >
          <thead>
            <tr>
              <th align="center" class="table-checkbox"> <input type="checkbox"  id="aAll" onClick="chkAll(this)"  title="全选/取消"/>
              </th>
              
              <th align="center"><a href="?<?=$search?>&orderby=types&orderlx=" class="<?=orac('types')?>">类型</a></th>
              <th align="center"><a href="?<?=$search?>&orderby=codes&orderlx=" class="<?=orac('codes')?>">兑换码</a></th>
              <th align="center"><a href="?<?=$search?>&orderby=value&orderlx=" class="<?=orac('value')?>">价值</a></th>
<?php if($status!=1){?>             
              <th align="center"><a href="?<?=$search?>&orderby=number&orderlx=" class="<?=orac('number')?>">数量</a></th>
              <th align="center"><a href="?<?=$search?>&orderby=limitmoney&orderlx=" class="<?=orac('limitmoney')?>">最低消费</a></th>
               <th align="center"><a href="?<?=$search?>&orderby=usetypes&orderlx=" class="<?=orac('usetypes')?>">可使用类型</a></th>
               <th align="center"><a href="?<?=$search?>&orderby=duetime&orderlx=" class="<?=orac('duetime')?>">到期时间</a></th>
 <?php }?>              
               <th align="center"><a href="?<?=$search?>&orderby=username&orderlx=" class="<?=orac('username')?>">所属</a></th>
               <th align="center"><a href="?<?=$search?>&orderby=status&orderlx=" class="<?=orac('status')?>"><?=$LG['status']//状态?></a> / <a href="?<?=$search?>&orderby=addtime&orderlx=" class="<?=orac('addtime')?>">添加时间</a></th>
               
               
               
<?php if($status==1){?>                
               <th align="center"><a href="?<?=$search?>&orderby=fromtable&orderlx=" class="<?=orac('fromtable')?>">用途</a> </th>
               <th align="center"><a href="?<?=$search?>&orderby=use_title&orderlx=" class="<?=orac('use_title')?>">使用说明</a> </th>
               <th align="center"><a href="?<?=$search?>&orderby=money&orderlx=" class="<?=orac('money')?>">使用优惠</a> </th>
               <th align="center"><a href="?<?=$search?>&orderby=use_time&orderlx=" class="<?=orac('use_time')?>">使用时间</a> </th>
<?php }?>

              <th align="center">操作</th>
            </tr>
          </thead>
          <tbody>
<?php
while($rs=$sql->fetch_array())
{
?>
            <tr class="odd gradeX <?=spr($rs['status'])?'gray2':''?>">
              <td align="center" valign="middle">
               <input type="checkbox" id="a" onClick="chkColor(this);" name="cpid[]" value="<?=$rs['cpid']?>" />
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
			   <?=spr($rs['number'])?>张
              </td>
           
              <td align="center" valign="middle">
              <a class="tooltips" data-container="body" data-placement="top" data-original-title="达到这个消费金额才能使用"><?=spr($rs['limitmoney']).$XAmc?></a>
              </td>
              
              <td align="center" valign="middle">
			  <?=Coupons_usetypes($rs['usetypes'])?>
              </td>
              
              <td align="center" valign="middle">
			  <?=$rs['duetime']?DateYmd($rs['duetime']):'不限'?>
              </td>
<?php }?>              
              <td align="center" valign="middle">
              <a class="popovers" data-trigger="hover" data-placement="top"  data-content="<?=DateYmd($rs['getTime'])?> <?=Coupons_getSource($rs['getSource'])?> 取得">
			  <?=$rs['username']?cadd($rs['username']).' ('.$rs['userid'].')':''?>
              </a>
              </td>
              
              <td align="center" valign="middle">
              <?php if($rs['use_time']){?>
              	<a class="popovers" data-trigger="hover" data-placement="top"  data-content="<?=DateYmd($rs['use_time'])?>：<?=cadd($rs['use_content'])?>"><?=Coupons_Status(spr($rs['status']))?></a>
              <?php }else{echo Coupons_Status(spr($rs['status']));}?>
              
               <br>
               <font class="gray2" title="添加时间">
                <?=DateYmd($rs['addtime'])?>
               </font>
              </td>
               
      <?php if($status==1){?> 
              <td align="center" valign="middle">
              <?=$rs['fromtable']?fromtableName($rs['fromtable']).'ID:'.$rs['fromid'].'':''?> 
              </td>
               
              <td align="center" valign="middle">
              	<a <?=fromtableUrl($rs['fromtable'],$rs['fromid'])?>  class="popovers" data-trigger="hover" data-placement="top"  data-content="<?=cadd($rs['use_title'])?><?=$rs['use_content']?'：'.cadd($rs['use_content']):''?>">
				<?=$rs['use_title']?leng($rs['use_title'],20,'...'):'说明'?>
                </a>
              </td>
               
              <td align="center" valign="middle">
              <?=spr($rs['money']).$XAmc?>
              </td>
               
              <td align="center" valign="middle">
              <?=DateYmd($rs['use_time'])?>
              </td>
      <?php }?>         
               
              <td align="center" valign="middle">
              <?php if((!$rs['userid']||spr($rs['status']))&&$status!=1){?>

              <a href="save.php?lx=del&cpid=<?=$rs['cpid']?>" class="btn btn-xs btn-danger"  onClick="return confirm('确认要删除<?=cadd($rs['codes'])?>吗?此操作不可恢复! ');"><i class="icon-remove"></i> <?=$LG['del']?></a> 
              
              <?php }elseif(!spr($rs['status'])){?>
              <a href="save.php?lx=status&status=10&cpid=<?=$rs['cpid']?>" class="btn btn-xs btn-warning"  onClick="return confirm('确认要设为失效<?=cadd($rs['codes'])?>吗?此操作不可恢复! ');"><i class="icon-off"></i> 设为失效</a> 
              <?php }?>
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


<input name="lx" type="hidden">
        <input name="status" type="hidden">

    <select  class="form-control input-small select2me" data-placeholder="可使用类型" name="usetypes">
        <option></option>
        <?=Coupons_usetypes('',1)?>
    </select>
  <!--btn-primary--><button type="submit" class="btn btn-grey"   style="margin-right:20px;"
  onClick="
  document.XingAoForm.lx.value='usetypes';
  return confirm('确认要修改所选的可使用类型吗?');
  "><i class="icon-signin"></i> 修改可使用类型</button>

        
         <button type="submit" class="btn btn-grey"   style="margin-right:20px;"
         onClick="
  document.XingAoForm.lx.value='status';
  document.XingAoForm.status.value='10';
  return confirm('确认要把所选设置为失效吗?此操作不可恢复!');
  "><i class="icon-signin"></i> 设为失效</button>

<?php if(!$off_delbak||$off_delbak&&$status==0){?>
          <input type="text" class="form-control input-xsmall tooltips" data-container="body" data-placement="top" data-original-title="删除X月之前所添加的 (填0则删除全部)" name="date" value="">月
          <!--btn-danger--><button type="submit" class="btn btn-grey tooltips" data-container="body" data-placement="top" data-original-title="如有勾选则删除所选,否则按左边所填删除 (不能删除已分配且未使用信息,如要删除请先设为失效)"
         onClick="
  document.XingAoForm.lx.value='del';
  document.XingAoForm.status.value='<?=$status?>';
  return confirm('<?=!$off_delbak?'注意：对于已使用的记录会用于数据统计，删除后将影响统计结果！\r':''?>确认要删除吗?此操作不可恢复! ');
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
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/foot.php');
?>
