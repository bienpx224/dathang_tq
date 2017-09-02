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
$pervar='member_ma';require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');
$headtitle="会员权限管理";
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/head.php');

//生成缓存
if (strtotime($member_cache_time)<=strtotime('-1 hours')){cache_member_group();}//多久（时）可以更新一次
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
              <th align="center">开通注册</th>
              <th align="center">企业类型</th>
              <th align="center">会员数</th>
              <th align="center">可升级</th>
              <th align="center">操作</th>
            </tr>
          </thead>
          <tbody>
<?php
$query="select * from member_group order by myorder desc, groupname{$LT} desc,groupid desc";
$sql=$xingao->query($query);
while($rs=$sql->fetch_array())
{
?>
            <tr class="odd gradeX <?=!$rs['checked']?'gray2':''?>">
              <td align="center"><input type="checkbox" id="a" onClick="chkColor(this);" name="groupid[]" value="<?=$rs['groupid']?>" /></td>
              <td align="center"><?=$rs['groupid']?></td>
              <td align="center">
              <!--显示标题-开始-->
              <font class=" popovers" data-trigger="hover" data-placement="top"  data-content="
              <?php 
				//语言字段处理--
				if(!$LGList){$LGList=languageType('',3);}
				if($LGList)
				{
					foreach($LGList as $arrkey=>$language)
					{
						if($language!=$LT){echo '<br>'.cadd($rs['groupname'.$language]);}
					}
				}
			  ?>
              ">
              <?=cadd($rs['groupname'.$LT])?>
              </font>
              <!--显示标题-结束-->
              
              </td>
              <td align="center"><?=$rs['regchecked']?'可注册':'禁止注册';?></td>
              <td align="center"><?=$rs['off_company']?'企业类型':'个人类型';?></td>
              <td align="center"><a href="../member/list.php?so=1&groupid=<?=$rs['groupid']?>" target="_blank" title="查看所有用户"><?=mysqli_num_rows($xingao->query("select groupid from member where groupid='{$rs[groupid]}'"));?></a></td>
			  
              <td align="center">
			  <?php if($rs['up_groupid']){?>
			  <a class="popovers" data-trigger="hover" data-placement="top" data-content="
			  【<?php 
			    $show='';
				$arr=$rs['up_groupid'];
				if($arr)
				{
					if(!is_array($arr)){$arr=explode(",",$arr);}//转数组
					foreach($arr as $arrkey=>$value)
					{
						if($show){$show.='、'.$member_per[$value]['groupname'];}else{$show=$member_per[$value]['groupname'];}
					}
				}
				echo $show;
			  ?>
			  】
			 〖
			 <?php if($rs['up_groupid_integral']>=0){echo '用积分购买'.spr($rs['up_groupid_integral']).'分；';}?>
			 <?php if($rs['up_groupid_max_cz_once']>=0){echo '单次充值数额'.spr($rs['up_groupid_max_cz_once']).$XAmc;}?>
			 <?php if($rs['up_groupid_max_cz_more']>=0){echo '累计充值数额'.spr($rs['up_groupid_max_cz_more']).$XAmc;}?>
			 〗
			  " >
			  可升级
			  </a>
			  <?php }?>
			  </td>
			  
              <td align="center">
              <a href="form.php?lx=add&groupid=<?=$rs['groupid']?>" class="btn btn-xs btn-default"><i class="icon-copy"></i> 复制</a> 
               <a href="../member/send.php?groupid=<?=$rs['groupid']?>" class="btn btn-xs btn-success" target="_blank"><i class="icon-envelope-alt"></i> 发信息</a>
               
              <a href="form.php?lx=edit&groupid=<?=$rs['groupid']?>" class="btn btn-xs btn-info"><i class="icon-edit"></i> 编辑</a> 
              
              <a href="save.php?lx=del&groupid=<?=$rs['groupid']?>" class="btn btn-xs btn-danger"  onClick="return confirm('确认要删除吗?此操作不可恢复! ');"><i class="icon-remove"></i> <?=$LG['del']?></a>
              
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
$query="select groupid,groupname{$LT} from member_group where checked=1 order by  myorder desc,groupname{$LT} desc,groupid desc";
$sql=$xingao->query($query);
while($rs=$sql->fetch_array())
{
?>
            <option value="<?=$rs['groupid']?>"><?=cadd($rs['groupname'.$LT])?></option>
<?php
}
?>
          </select>
          <span class="help-block"> </span> </div>
         <!--btn-primary--><button type="submit" class="btn btn-grey"
         onClick="
  document.XingAoForm.lx.value='mobile';
  return confirm('确认要把所选分类里的所有会员转到新分类中吗?');
  "><i class="icon-signin"></i> 转移会员</button>

           <!--btn-success--><button type="submit" class="btn btn-grey" 
  onClick="
  document.XingAoForm.action='../member/send.php';
  document.XingAoForm.target='_blank';
  "><i class="icon-envelope-alt"></i> 发信息</button>


  
  <!--btn-danger--><button type="submit" class="btn btn-grey" onClick="
  document.XingAoForm.lx.value='del';
  return confirm('确认要删除所选分类吗?此操作不可恢复! ');
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
