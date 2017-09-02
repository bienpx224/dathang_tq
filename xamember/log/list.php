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
$headtitle=$LG['name.nav_64'];//登录日志
require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/head.php');
//搜索
$where="1=1";
$so=(int)$_GET['so'];
if($so)
{
	$key=par($_GET['key']);
	$stime=par($_GET['stime']);
	$etime=par($_GET['etime']);
	$status=par($_GET['status']);
	if($key){$where.=" and loginip='{$key}'";}
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
$query="select * from member_log where {$where}  {$Mmy} {$order}";

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
      <button type="button" class="btn btn-danger" onClick="javascript:if(confirm('<?=$LG['log.list_2']?>'))location.href='save.php?lx=del&date=12';" ><i class="icon-remove"></i> <?=$LG['log.list_1'];//删除12个月前日志?></button>
      <button type="button" class="btn btn-danger" onClick="javascript:if(confirm('<?=$LG['log.list_2']?>'))location.href='save.php?lx=del&date=6';"><i class="icon-remove"></i> <?=$LG['log.list_3'];//删除6个月前日志?></button>
      <button type="button" class="btn btn-danger" onClick="javascript:if(confirm('<?=$LG['log.list_2']?>'))location.href='save.php?lx=del&date=3';"><i class="icon-remove"></i> <?=$LG['log.list_4'];//删除3个月前日志?></button>
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
                  <select  class="form-control input-small select2me" name="status" data-placeholder="<?=$LG['status'];//状态?>">
                    <option></option>
                    <option value="0"  <?=$status=='0'?' selected':''?>><?=$LG['log.list_6'];//登录成功?></option>
                    <option value="1"  <?=$status=='1'?' selected':''?>><?=$LG['log.list_8'];//登录失败?></option>
                  </select>
               </div>
              </div>
              
            <button type="submit"  class="btn btn-info"><i class="icon-search"></i> <?=$LG['search'];//搜索?></button>
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
              <th align="center"><a href="?<?=$search?>&orderby=logintime&orderlx=" class="<?=orac('logintime')?>"><?=$LG['log.list_10'];//登录时间?></a></th>
              <th align="center"><a href="?<?=$search?>&orderby=userid&orderlx=" class="<?=orac('userid')?>"><?=$LG['Muserid'];//会员ID?></a></th>
              <th align="center"><a href="?<?=$search?>&orderby=username&orderlx=" class="<?=orac('username')?>"><?=$LG['Musername'];//会员名?></a></th>
              <th align="center"><a href="?<?=$search?>&orderby=password&orderlx=" class="<?=orac('password')?>"><?=$LG['log.list_11'];//登录密码?></a></th>
              <th align="center"><a href="?<?=$search?>&orderby=loginip&orderlx=" class="<?=orac('loginip')?>"><?=$LG['log.list_12'];//登录IP?></a></th>
			  <th align="center"><a href="?<?=$search?>&orderby=loginadd&orderlx=" class="<?=orac('loginadd')?>"><?=$LG['log.list_13'];//登录地址?></a></th>
              <th align="center"><a href="?<?=$search?>&orderby=status&orderlx=" class="<?=orac('status')?>"><?=$LG['status'];//状态?></a></th>           
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
require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/foot.php');
?>
