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
$headtitle="后台用户权限管理";
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/head.php');

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
  <form action="save.php" method="post" name="XingAoForm">
    <input name="lx" type="hidden">
        <table class="table table-striped table-bordered table-hover" >
          <thead>
            <tr>
              <th align="center" class="table-checkbox"> <input type="checkbox"  id="aAll" onClick="chkAll(this)"  title="全选/取消"/>
              </th>
              <th align="center">ID</th>
              <th align="center">名称</th>
              <th align="center">超级权限</th>
              <th align="center">用户数</th>
              <th align="center">操作</th>
            </tr>
          </thead>
          <tbody>
            <?php
$query="select groupid,groupname,admin,myorder from manage_group order by myorder desc, groupname desc,groupid desc";
$sql=$xingao->query($query);
while($rs=$sql->fetch_array())
{
?>
            <tr class="odd gradeX">
              <td align="center"><input type="checkbox" id="a" onClick="chkColor(this);" name="groupid[]" value="<?=$rs['groupid']?>" /></td>
              <td align="center"><?=$rs['groupid']?></td>
              <td align="center"><?=$rs['groupname']?></td>
              <td align="center"><?=$rs['admin']?'超级权限':'';?></td>
              <td align="center"><a href="../manage/list.php?so=1&groupid=<?=$rs['groupid']?>" target="_blank" title="查看所有用户"><?=mysqli_num_rows($xingao->query("select groupid from manage where groupid='{$rs[groupid]}'"));?></a></td>
             
              <td align="center">
              <a href="form.php?lx=add&groupid=<?=$rs['groupid']?>" class="btn btn-xs btn-default"><i class="icon-copy"></i> 复制</a> 

			  <a href="form.php?lx=edit&groupid=<?=$rs['groupid']?>" class="btn btn-xs btn-info"><i class="icon-edit"></i> 编辑</a> 
              <?php if($rs['groupid']!=1){?>
              <a href="save.php?lx=del&groupid=<?=$rs['groupid']?>" class="btn btn-xs btn-danger"  onClick="return confirm('分类里的所有用户也将被删除,确认要删除吗?此操作不可恢复! ');"><i class="icon-remove"></i> <?=$LG['del']?></a>
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


<div class="col-md-0">
          <select  class="form-control input-medium select2me" data-placeholder="Select..." name="groupid_new">
            <option></option>
<?php
$query="select groupid,groupname,admin from manage_group order by  myorder desc,groupname desc,groupid desc";
$sql=$xingao->query($query);
while($rs=$sql->fetch_array())
{
?>
            <option value="<?=$rs['groupid']?>"><?=$rs['groupname']?></option>
<?php
}
?>
          </select>
          <span class="help-block"> </span> </div>
        <!--btn-primary--><button type="submit" class="btn btn-grey" title="把以上所选分类里的所有用户转到新分类中"  
         onClick="
  document.XingAoForm.lx.value='mobile';
  return confirm('确认要把所选分类里的所有用户转到新分类中吗?此操作不可恢复! ');
  "><i class="icon-signin"></i> 转移用户</button>
  
  <!--btn-danger--><button type="submit" class="btn btn-grey" onClick="
  document.XingAoForm.lx.value='del';
  return confirm('分类里的所有用户也将被删除,确认要删除所选分类吗?此操作不可恢复! ');
  "><i class="icon-signin"></i> <?=$LG['delSelect']//删除所选?></button>
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
