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
$pervar='manage_ma';require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');
$headtitle='用户管理';
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/head.php');

//搜索
$where="1=1";
$so=(int)$_GET['so'];
if($so==1)
{
	$key=par($_GET['key']);
	$groupid=par($_GET['groupid']);
	$checked=par($_GET['checked']);
	if($key){$where.=" and (userid='".CheckNumber($key,-0.1)."' or username='{$key}' or truename like '%{$key}%' )";}
	if(CheckEmpty($groupid)){$where.=" and groupid='{$groupid}'";}
	if(CheckEmpty($checked)){$where.=" and checked='{$checked}'";}
	
	$search.="&so={$so}&key={$key}&groupid={$groupid}&checked={$checked}";
}

$order=' order by userid desc';//默认排序
include($_SERVER['DOCUMENT_ROOT'].'/public/orderby.php');//排序处理页


//echo $search;exit();
$query="select * from manage where {$where} {$order}";

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
              <input type="text" name="key" class="form-control input-msmall popovers" data-trigger="hover" data-placement="right"  data-content="用户ID/用户名/姓名 (可留空)" value="<?=$key?>">
            </div>
            <div class="form-group">
               
                <div class="col-md-0">
                  <select  class="form-control input-medium select2me" name="groupid" data-placeholder="所属分类">
                    <option></option>
<?php
$query2="select groupid,groupname,admin from manage_group order by myorder desc,groupname desc,groupid desc";
$sql2=$xingao->query($query2);
while($rs2=$sql2->fetch_array())
{
?>
            <option value="<?=$rs2['groupid']?>"  <?=$rs2['groupid']==$groupid?' selected':''?>>
            <?=$rs2['groupname']?>
            </option>
            <?php
}
?>
                  </select>
                  </div>
              </div>
              
           <div class="form-group">
                <div class="col-md-0">
                  <select  class="form-control input-small select2me" name="checked" data-placeholder="状态">
                    <option></option>
                    <option value="0"  <?=$checked=='0'?' selected':''?>><?=$LG['close']?> </option>
                    <option value="1"  <?=$checked=='1'?' selected':''?>><?=$LG['checkedOn']//开通?></option>
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
              <th align="center"><a href="?<?=$search?>&orderby=username&orderlx=" class="<?=orac('username')?>">用户</a>/<a href="?<?=$search?>&orderby=userid&orderlx=" class="<?=orac('userid')?>">ID</a></th>
              <th align="center"><a href="?<?=$search?>&orderby=truename&orderlx=" class="<?=orac('truename')?>">姓名</a></th>
              <th align="center"><a href="?<?=$search?>&orderby=groupid&orderlx=" class="<?=orac('groupid')?>">所属分类</a></th>
              <th align="center"><a href="?<?=$search?>&orderby=checked&orderlx=" class="<?=orac('checked')?>"><?=$LG['status']//状态?></a></th>              
              <th align="center"><a href="?<?=$search?>&orderby=loginnum&orderlx=" class="<?=orac('loginnum')?>">登录次数</a></th>
              
              <th align="center"><a href="?<?=$search?>&orderby=addtime&orderlx=" class="<?=orac('addtime')?>">添加时间</a></th>
              <th align="center"><a href="?<?=$search?>&orderby=lastip&orderlx=" class="<?=orac('lastip')?>">上次登录IP</a></th>
              <th align="center"><a href="?<?=$search?>&orderby=lasttime&orderlx=" class="<?=orac('lasttime')?>">上次登录时间</a></th>
              <th align="center">操作</th>
            </tr>
          </thead>
          <tbody>
<?php
while($rs=$sql->fetch_array())
{
?>
            <tr class="odd gradeX <?=!$rs['checked']?'gray2':''?>">
              <td align="center">
            
              <input name="userid[]" type="checkbox"  id="a" onClick="chkColor(this);" value="<?=$rs['userid']?>" />
             
              </td>
              <td align="center"><?=cadd($rs['username'])?> (<?=$rs['userid']?>)
</td>
              <td align="center"><?=cadd($rs['truename'])?></td>
              <td align="center">
			  <?php 
			  $fr=mysqli_fetch_array($xingao->query("select groupname from manage_group where groupid='{$rs[groupid]}'"));
			  echo $fr['groupname'];
			  ?>
              </td>
              <td align="center"><?=$rs['checked']?'开通':'关闭';?></td>
              <td align="center"><?=cadd($rs['loginnum'])?></td>
              
              <td align="center"><?=DateYmd($rs['addtime'],1)?></td>
              <td align="center"><?=cadd($rs['lastip'])?></td>
              <td align="center"><?=DateYmd($rs['lasttime'],1)?><br>

              <a href="../log/list.php?so=1&key=<?=$rs['userid']?>" target="_blank">登录日志</a>
              </td>
              <td align="center">
              <a href="form.php?lx=edit&userid=<?=$rs['userid']?>" class="btn btn-xs btn-info"><i class="icon-edit"></i> 查看/编辑</a> 
              <br>
              <a href="save.php?lx=del&userid=<?=$rs['userid']?>" class="btn btn-xs btn-danger"  onClick="return confirm('<?=$LG['pptDelConfirm']//确认要删除所选吗?此操作不可恢复!?>');"><i class="icon-remove"></i> <?=$LG['del']?></a>
               
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


<!--btn-danger--><button type="submit" class="btn btn-grey" onClick="
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
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/foot.php');
?>
