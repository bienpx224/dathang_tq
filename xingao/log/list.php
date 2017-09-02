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
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');
$my=$_REQUEST['my'];
if($my){$headtitle="管理我的登录日志";}else{$headtitle=cadd($_GET['key'])." 管理后台登录日志";}
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/head.php');


$where="1=1";

if($my)
{
	$where.=" and userid='{$Xuserid}'";
}else{
	permissions('manage_ma','','manage','');//权限验证
}

//搜索
$so=(int)$_GET['so'];
if($so)
{
	$key=par($_GET['key']);
	$stime=par($_GET['stime']);
	$etime=par($_GET['etime']);
	$status=par($_GET['status']);
	if($key){$where.=" and (userid='".CheckNumber($key,-0.1)."' or username='{$key}' or loginip='{$key}')";}
	if($stime){$where.=" and logintime>='".strtotime($stime.' 00:00:00')."'";}
	if($etime){$where.=" and logintime<='".strtotime($etime.' 23:59:59')."'";}
	if(CheckEmpty($status)&&$status==0){$where.=" and status=0";}elseif($status==1){$where.=" and status>0";}

	$search.="&so={$so}&key={$key}&stime={$stime}&etime={$etime}&status={$status}";
}

$timequerycall=1;
$field="logintime";
include($_SERVER['DOCUMENT_ROOT'].'/public/timequery.php');//按时间快速查询

$order=' order by logintime desc';//默认排序
include($_SERVER['DOCUMENT_ROOT'].'/public/orderby.php');//排序处理页


//echo $search;exit();
$query="select * from manage_log where {$where} {$order}";

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
      <button type="button" class="btn btn-danger" onClick="javascript:if(confirm('确认要删除吗?此操作不可恢复!'))location.href='save.php?lx=del&my=<?=$my?>&date=12';" ><i class="icon-remove"></i> <?=$LG['del']?>12个月前日志 </button>
      <button type="button" class="btn btn-danger" onClick="javascript:if(confirm('确认要删除吗?此操作不可恢复!'))location.href='save.php?lx=del&my=<?=$my?>&date=6';"><i class="icon-remove"></i> <?=$LG['del']?>6个月前日志 </button>
      <button type="button" class="btn btn-danger" onClick="javascript:if(confirm('确认要删除吗?此操作不可恢复!'))location.href='save.php?lx=del&my=<?=$my?>&date=3';"><i class="icon-remove"></i> <?=$LG['del']?>3个月前日志 </button>
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
          <input name="my" type="hidden" value="<?=$my?>">
            <div class="form-group">
              <input type="text" name="key" class="form-control input-msmall popovers" data-trigger="hover" data-placement="right"  data-content="用户ID/用户名/登录IP (可留空)" value="<?=$key?>">
            </div>
            <div class="form-group">
                <div class="col-md-0">
                  <div class="input-group input-xmedium date-picker input-daterange" data-date-format="yyyy-mm-dd">
                    <input type="text" class="form-control input-small" name="stime" value="<?=$stime?>">
                    <span class="input-group-addon">-</span>
                    <input type="text" class="form-control input-small" name="etime" value="<?=$etime?>">
                  </div>
                 </div>
              </div>
              
           <div class="form-group">
                <div class="col-md-0">
                  <select  class="form-control input-small select2me" name="status" data-placeholder="状态">
                    <option></option>
                    <option value="0"  <?=$status=='0'?' selected':''?>>登录成功</option>
                    <option value="1"  <?=$status=='1'?' selected':''?>>登录失败</option>
                  </select>
               </div>
              </div>
              
            <button type="submit" class="btn btn-info"><i class="icon-search"></i> <?=$LG['search']//搜索?></button>
          </form>
          
        </div>
        <?php
		$timequeryshow=1;
		include($_SERVER['DOCUMENT_ROOT'].'/public/timequery.php');//按时间快速查询
		?>
      </div>
      
        <table class="table table-striped table-bordered table-hover" >
          <thead>
            <tr>
              <th align="center"><a href="?<?=$search?>&orderby=logintime&orderlx=" class="<?=orac('logintime')?>">登录时间</a></th>
              <th align="center"><a href="?<?=$search?>&orderby=userid&orderlx=" class="<?=orac('userid')?>">用户ID</a></th>
              <th align="center"><a href="?<?=$search?>&orderby=username&orderlx=" class="<?=orac('username')?>">用户名</a></th>
              <th align="center"><a href="?<?=$search?>&orderby=password&orderlx=" class="<?=orac('password')?>">登录密码</a></th>
              <th align="center"><a href="?<?=$search?>&orderby=loginip&orderlx=" class="<?=orac('loginip')?>">登录IP</a></th>
               <th align="center"><a href="?<?=$search?>&orderby=loginadd&orderlx=" class="<?=orac('loginadd')?>">登录地址</a></th>
              <th align="center"><a href="?<?=$search?>&orderby=status&orderlx=" class="<?=orac('status')?>"><?=$LG['status']//状态?></a></th>           
            </tr>
          </thead>
          <tbody>
<?php
while($rs=$sql->fetch_array())
{
?>
            <tr class="odd gradeX">
              <td align="center"><?=DateYmd($rs['logintime'],1)?></td>
              <td align="center"><?=$rs['userid']?></td>
              <td align="center"><?=cadd($rs['username'])?></td>
              <td align="center"><?=cadd($rs['password'])?></td>
              <td align="center"><?=cadd($rs['loginip'])?></td>
              <td align="center"><?=cadd($rs['loginadd'])?></td>
              <td align="center"><?=$rs['loginauth']?'<font color="#FF0000">'.LoginStatus(spr($rs['status'])).'</font>':LoginStatus(spr($rs['status']))?></td>
            </tr>
            <?php
}
?>
          </tbody>
        </table>
      <div class="row">
      <?=$listpage?>
      </div>
     
      </div>
      <!--表格内容结束--> 
      
    </div>
  
</div>
<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/foot.php');
?>
