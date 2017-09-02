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
$pervar='notice';//权限验证
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');
$headtitle="内部公告";
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/head.php');

//过期更新
if (update_time('notice','-30 minutes'))//多久更新一次:1 week 3 days 7 hours 30 minutes 5 seconds
{
	$xingao->query("update notice set checked='0' where checked=1 and duetime>0 and duetime<".time()."");
}


//搜索
$where="1=1";
$so=(int)$_GET['so'];
if($so==1)
{
	$key=par($_GET['key']);
	$level=par($_GET['level']);
	$checked=par($_GET['checked']);
	$status=par($_GET['status']);
	$to_groupid=par($_GET['to_groupid']);
	
	if($key){$where.=" and (title like '%{$key}%' or content like '%{$key}%' or userid='".CheckNumber($key,-0.1)."' or username like '%{$key}%')";}
	if(CheckEmpty($checked)){$where.=" and checked='{$checked}'";}
	if(CheckEmpty($level)){$where.=" and level='{$level}'";}
	if(CheckEmpty($status)){$where.=" and status='{$status}'";}
	if(CheckEmpty($to_groupid)){$where.=" and to_groupid='{$to_groupid}'";}

	$search.="&so={$so}&key={$key}&status={$status}&checked={$checked}&level={$level}&to_groupid={$to_groupid}";
}

$order=' order by level desc,edittime desc,status asc,checked desc';//默认排序
include($_SERVER['DOCUMENT_ROOT'].'/public/orderby.php');//排序处理页
$query="select * from notice where {$where} {$order}";

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
    <div class="tab-content" style="padding:10px;"> 
      <!--搜索-->
      <div class="navbar navbar-default" role="navigation">
        
        <div class="collapse navbar-collapse navbar-ex1-collapse">
          <form class="navbar-form navbar-left"  method="get" action="?">
            <input name="so" type="hidden" value="1">
            <div class="form-group">
              <input type="text" name="key" class="form-control input-msmall popovers" data-trigger="hover" data-placement="right"  data-content="标题/内容/会员ID/会员名 (可留空)" value="<?=$key?>">
            </div>
                
			<div class="form-group">
              <div class="col-md-0">
                <select  class="form-control input-small select2me" name="to_groupid" data-placeholder="所属">
                  <option></option>
<?php
$query_g="select groupid,groupname from manage_group order by  myorder desc,groupname desc,groupid desc";
$sql_g=$xingao->query($query_g);
while($rs_g=$sql_g->fetch_array())
{
?>
            <option value="<?=$rs_g['groupid']?>" <?=$to_groupid==$rs_g['groupid']?'selected':''?>><?=cadd($rs_g['groupname'])?></option>
<?php
}
?>
                </select>
              </div>
            </div>
            
			<div class="form-group">
                <div class="col-md-0">
                  <select  class="form-control input-small select2me" name="level" data-placeholder="等级">
                    <option></option>
                     <?=Notice_Level($level,1)?>
                  </select>
               </div>
              </div>
              
         <div class="form-group">
                <div class="col-md-0">
                  <select  class="form-control input-small select2me" name="status" data-placeholder="处理">
                    <option></option>
                    <option value="2"  <?=$status=='2'?' selected':''?>>已处理</option>
                    <option value="1"  <?=$status=='1'?' selected':''?>>待处理</option>
                    <option value="0"  <?=$status=='0'?' selected':''?>>无需处理</option>
                  </select>
               </div>
              </div>
          
          	 <div class="form-group">
                <div class="col-md-0">
                  <select  class="form-control input-small select2me" name="checked" data-placeholder="过期">
                    <option></option>
                    <option value="1"  <?=$checked=='1'?' selected':''?>>未过期</option>
                    <option value="0"  <?=$checked=='0'?' selected':''?>>已过期</option>
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
              
              <th align="center"><a href="?<?=$search?>&orderby=title&orderlx=" class="<?=orac('title')?>">标题</a></th>
               
              <th align="center"><a href="?<?=$search?>&orderby=to_groupid&orderlx=" class="<?=orac('to_groupid')?>">所属</a></th>
             
              <th align="center"><a href="?<?=$search?>&orderby=status&orderlx=" class="<?=orac('status')?>">处理</a></th>
              <th align="center"><a href="?<?=$search?>&orderby=checked&orderlx=" class="<?=orac('checked')?>">过期</a> / <a href="?<?=$search?>&orderby=duetime&orderlx=" class="<?=orac('duetime')?>" title="过期时间">时间</a></th>
               <th align="center"><a href="?<?=$search?>&orderby=username&orderlx=" class="<?=orac('username')?>">发布者</a></th>
               <th align="center"><a href="?<?=$search?>&orderby=edittime&orderlx=" class="<?=orac('edittime')?>" title="修改时间">修改</a> / <a href="?<?=$search?>&orderby=addtime&orderlx=" class="<?=orac('addtime')?>" title="添加时间">添加</a></th>
              <th align="center">操作</th>
            </tr>
          </thead>
          <tbody>
<?php
while($rs=$sql->fetch_array())
{
?>
            <tr class="odd gradeX <?=!$rs['checked']?'gray2':''?>">
              <td align="center" valign="middle">
               <input type="checkbox" id="a" onClick="chkColor(this);" name="noid[]" value="<?=$rs['noid']?>" />
               </td>
              
              <td align="left" valign="middle">
              
               <?=Notice_Level($rs['level'])?>
			   <?php if($rs['popup']){?><span class="label label-sm label-default tooltips" data-container="body" data-placement="top" data-original-title="每<?=$rs['popuptime']?>分钟弹出一次">弹出</span><?php }?>
                
			   <?php if(fnCharCount($rs['title'])>50){?>
              	 <font class="popovers" data-trigger="hover" data-placement="top"  data-content="<?=cadd($rs['title'])?>">
			   <?php }else{?>
              	 <font>
			   <?php }?>
               
				 <?php
                 Modals(cadd($rs['content']),cadd($rs['title']),$rs['edittime'],'content'.$rs['noid'],$count=0,$html=0,$link_name=leng($rs['title'],50))
                 ?>
                 
               </font>
         

              </td>
                
              <td align="center" valign="middle">
               <?=$rs['to_groupid']?cadd($manage_per[$rs['to_groupid']]['groupname']):'全部'?>
                </td>
                
              <td align="center" valign="middle">
              
			  <?php if(!spr($rs['status'])){?>
              	<span class="label label-sm label-default">无需处理</span>
			  <?php }elseif(spr($rs['status'])==1){?>
              	<span class="label label-sm label-warning">待处理</span>
			  <?php }elseif(spr($rs['status'])==2){?>
             	<span class="label label-sm label-success">已处理</span>
              <?php }?>
              </td>
              <td align="center" valign="middle">
                  <font title="过期时间"><?=DateYmd($rs['duetime'])?></font>
                
                <font class="gray2">
                 <?=!$rs['checked']?'<br>已过期':''?>   
                </font>
              </td>
              
              <td align="center" valign="middle">
			  <?=cadd($rs['username'])?><br>
                <font class="gray2">
                <?=cadd($rs['userid'])?>
                </font>
              
              </td>
              <td align="center" valign="middle">
			  <font title="修改时间"> <?=DateYmd($rs['edittime'])?> </font><br>
                <font class="gray2" title="添加时间">
                <?=DateYmd($rs['addtime'])?>
                </font>
              </td>
               
              <td align="center" valign="middle">
            <?php if($rs['groupid']==$Xgroupid){?>
              <a href="form.php?lx=edit&noid=<?=$rs['noid']?>" class="btn btn-xs btn-info"><i class="icon-edit"></i> <?=$LG['edit']?></a>
              <a href="save.php?lx=del&noid=<?=$rs['noid']?>" class="btn btn-xs btn-danger"  onClick="return confirm('<?=$LG['pptDelConfirm']//确认要删除所选吗?此操作不可恢复!?>');"><i class="icon-remove"></i> <?=$LG['del']?></a> 
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
        <input name="checked" type="hidden">
        
   <select  class="form-control input-small select2me" data-placeholder="所属" name="groupid_new">
        <option></option>
        <?php
        $query_g="select groupid,groupname from manage_group order by  myorder desc,groupname desc,groupid desc";
        $sql_g=$xingao->query($query_g);
        while($rs_g=$sql_g->fetch_array())
        {
        ?>
        <option value="<?=$rs_g['groupid']?>"><?=$rs_g['groupname']?></option>
        <?php
        }
        ?>
    </select>
    <!--btn-primary--><button type="submit" class="btn btn-grey"  style="margin-right:20px;"
    onClick="
    document.XingAoForm.lx.value='mobile';
    return confirm('确认要把所选转移到新所属吗?');
    "><i class="icon-signin"></i> 转移所属</button>

    <select  class="form-control input-small select2me" data-placeholder="处理" name="status_new">
        <option></option>
        <option value="2" >已处理</option>
        <option value="1" >待处理</option>
        <option value="0" >无需处理</option>
    </select>
  <!--btn-primary--><button type="submit" class="btn btn-grey"   style="margin-right:20px;"
  onClick="
  document.XingAoForm.lx.value='status';
  return confirm('确认要修改所选的状态吗?');
  "><i class="icon-signin"></i> 修改状态</button>


        
           <button type="submit" class="btn btn-grey" 
         onClick="
  document.XingAoForm.lx.value='checked';
  document.XingAoForm.checked.value='1';
  return confirm('确认要把所选设置为未过期吗?');
  "><i class="icon-signin"></i> 未过期</button>

         <button type="submit" class="btn btn-grey"   style="margin-right:20px;"
         onClick="
  document.XingAoForm.lx.value='checked';
  document.XingAoForm.checked.value='0';
  return confirm('确认要把所选设置为已过期吗?');
  "><i class="icon-signin"></i> 已过期</button>
      
          <!--btn-danger--><button type="submit" class="btn btn-grey   tooltips" data-container="body" data-placement="top" data-original-title="不勾选则删除所有过期信息"
         onClick="
  document.XingAoForm.lx.value='del';
  return confirm('确认要删除吗?此操作不可恢复! ');
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
